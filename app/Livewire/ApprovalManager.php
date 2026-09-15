<?php

namespace App\Livewire;

use App\Enums\ApprovalStatus;
use App\Enums\ApprovalType;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\WbsItem;
use App\Services\ApprovalService;
use InvalidArgumentException;
use Livewire\Component;
use Livewire\WithPagination;

class ApprovalManager extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public string $typeFilter   = 'all';
    public string $scopeFilter  = 'all'; // 'all' or 'my_requests'

    // Review Modal (Admin / PM)
    public bool   $showReviewModal     = false;
    public ?int   $selectedRequestId   = null;
    public string $reviewComment       = '';

    // Create Request Modal (Team Members & Managers)
    public bool   $showCreateModal     = false;
    public ?int   $projectId           = null;
    public string $requestType         = 'deadline_extension';
    public ?int   $wbsItemId           = null;
    public string $reason              = '';
    public string $newDeadline         = '';
    public string $newBudget           = '';
    public string $scopeDescription    = '';

    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedTypeFilter()   { $this->resetPage(); }
    public function updatedScopeFilter()  { $this->resetPage(); }

    public function openCreateModal(): void
    {
        $user = auth()->user();
        $myProject = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->whereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                  });
            })
            ->first();

        $this->projectId = $myProject?->id ?? Project::first()?->id;
        $this->requestType = 'deadline_extension';
        $this->wbsItemId = null;
        $this->reason = '';
        $this->newDeadline = now()->addDays(7)->toDateString();
        $this->newBudget = '';
        $this->scopeDescription = '';
        $this->showCreateModal = true;
    }

    public function createRequest(): void
    {
        $this->validate([
            'projectId' => 'required|exists:projects,id',
            'requestType' => 'required|string',
            'reason' => 'required|string|min:5|max:1000',
        ]);

        $requestedValue = [];
        if ($this->requestType === 'deadline_extension' && $this->newDeadline) {
            $requestedValue['new_deadline'] = $this->newDeadline;
            if ($this->wbsItemId) {
                $requestedValue['wbs_item_id'] = $this->wbsItemId;
            }
        } elseif ($this->requestType === 'budget_change' && $this->newBudget) {
            $requestedValue['new_budget'] = (float) $this->newBudget;
        } elseif ($this->requestType === 'scope_change' && $this->scopeDescription) {
            $requestedValue['scope_description'] = $this->scopeDescription;
        } elseif ($this->wbsItemId) {
            $requestedValue['wbs_item_id'] = $this->wbsItemId;
        }

        $approval = ApprovalRequest::create([
            'project_id' => $this->projectId,
            'request_type' => $this->requestType,
            'requested_by' => auth()->id(),
            'current_value' => null,
            'requested_value' => $requestedValue,
            'reason' => $this->reason,
            'status' => ApprovalStatus::PENDING,
            'submitted_at' => now(),
        ]);

        try {
            $recipients = \App\Models\User::role('super_admin')->where('id', '!=', auth()->id())->get();
            if ($approval->project && $approval->project->projectManager && $approval->project->projectManager->id !== auth()->id()) {
                $recipients->push($approval->project->projectManager);
            }
            $recipients = $recipients->unique('id');
            foreach ($recipients as $recipient) {
                $recipient->notify(new \App\Notifications\ApprovalStatusNotification($approval, 'submitted'));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Approval notification error: ' . $e->getMessage());
        }

        $this->showCreateModal = false;
        $this->reset(['reason', 'newDeadline', 'newBudget', 'scopeDescription', 'wbsItemId']);
        $this->dispatch('toast', message: '✅ Approval request submitted successfully for review!', type: 'success');
    }

    public function cancelRequest(int $id): void
    {
        $request = ApprovalRequest::findOrFail($id);
        $user = auth()->user();

        if ($request->requested_by !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'You can only cancel requests submitted by you.', type: 'error');
            return;
        }

        if ($request->status->value !== 'pending') {
            $this->dispatch('toast', message: 'Only pending requests can be cancelled.', type: 'error');
            return;
        }

        $request->status = ApprovalStatus::CANCELLED;
        $request->save();

        $this->dispatch('toast', message: 'Approval request cancelled.', type: 'info');
    }

    public function review(int $id): void
    {
        $this->selectedRequestId = $id;
        $this->reviewComment     = '';
        $this->showReviewModal   = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal   = false;
        $this->selectedRequestId = null;
        $this->reviewComment     = '';
    }

    public function approveRequest(): void
    {
        $user = auth()->user();
        $request = ApprovalRequest::with(['project', 'requester'])->findOrFail($this->selectedRequestId);
        abort_if(!$request->project?->userCan($user, 'approval.approve') && !$request->project?->userCan($user, 'approval.final_approve'), 403, 'You do not have permission to approve formal requests.');

        if ($request->status->value !== 'pending') {
            $this->dispatch('toast', message: 'This request has already been processed.', type: 'error');
            return;
        }

        try {
            (new ApprovalService())->approve($request, $user, $this->reviewComment ?: null);
            $this->showReviewModal   = false;
            $this->selectedRequestId = null;
            $this->reviewComment     = '';
            $this->dispatch('toast', message: '✅ Request APPROVED! Project has been updated automatically.', type: 'success');
        } catch (InvalidArgumentException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function rejectRequest(): void
    {
        $user = auth()->user();
        $request = ApprovalRequest::with(['project', 'requester'])->findOrFail($this->selectedRequestId);
        abort_if(!$request->project?->userCan($user, 'approval.reject'), 403, 'You do not have permission to reject formal requests.');

        if ($request->status->value !== 'pending') {
            $this->dispatch('toast', message: 'This request has already been processed.', type: 'error');
            return;
        }

        try {
            (new ApprovalService())->reject($request, $user, $this->reviewComment ?: null);
            $this->showReviewModal   = false;
            $this->selectedRequestId = null;
            $this->reviewComment     = '';
            $this->dispatch('toast', message: '❌ Request REJECTED.', type: 'warning');
        } catch (InvalidArgumentException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    // Project Leadership Acceptance State
    public bool   $showDeclineModal       = false;
    public ?int   $decliningProjectId     = null;
    public string $declineReason          = '';

    // Delete Approval Request State
    public bool   $showDeleteConfirmModal = false;
    public ?int   $deletingRequestId      = null;

    public function openDeleteModal(int $id): void
    {
        $this->deletingRequestId = $id;
        $this->showDeleteConfirmModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteConfirmModal = false;
        $this->deletingRequestId = null;
    }

    public function confirmDelete(): void
    {
        $user = auth()->user();
        $request = ApprovalRequest::findOrFail($this->deletingRequestId);

        if (!$user->hasRole('super_admin') && $request->requested_by !== $user->id) {
            $this->dispatch('toast', message: 'You are not authorized to delete this approval record.', type: 'error');
            return;
        }

        $request->delete();
        $this->closeDeleteModal();
        $this->dispatch('toast', message: '🗑️ Approval request deleted successfully.', type: 'success');
    }

    public function acceptProject(int $projectId)
    {
        $user = auth()->user();
        $project = Project::findOrFail($projectId);

        if ($project->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Only the assigned Project Manager or PMO Admin can accept project leadership.', type: 'error');
            return;
        }

        $project->acceptByPm($user);
        $this->dispatch('toast', message: "🎉 Project '{$project->name}' leadership accepted! You can now access the full workspace.", type: 'success');
    }

    public function openDeclineProjectModal(int $projectId): void
    {
        $this->decliningProjectId = $projectId;
        $this->declineReason = '';
        $this->showDeclineModal = true;
    }

    public function closeDeclineProjectModal(): void
    {
        $this->showDeclineModal = false;
        $this->decliningProjectId = null;
        $this->declineReason = '';
    }

    public function submitDeclineProject(): void
    {
        $this->validate([
            'declineReason' => 'required|string|min:5|max:1000',
        ], [
            'declineReason.required' => 'Please state the reason or issue for declining this leadership assignment.',
            'declineReason.min' => 'The reason must be at least 5 characters.',
        ]);

        $project = Project::findOrFail($this->decliningProjectId);
        $user = auth()->user();

        if ($project->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $project->rejectByPm($user, $this->declineReason);
        $this->closeDeclineProjectModal();
        $this->dispatch('toast', message: 'Project leadership assignment declined. PMO Admin has been notified.', type: 'warning');
    }

    // Reassign PM Leadership & Reconfigure Project Modal State
    public bool   $showReassignModal       = false;
    public ?int   $reassignProjectId       = null;
    public ?int   $reassignRequestId       = null;
    public ?string $reassignDeclineReason  = null;

    // Editable Project Creation Fields for Reassign Modal
    public string  $reassignName           = '';
    public string  $reassignCode           = '';
    public ?int    $reassignSubsidiaryId   = null;
    public string  $reassignCategory       = 'General';
    public string  $reassignPriority       = 'medium';
    public ?string $reassignStartDate      = null;
    public ?string $reassignDeadline       = null;
    public ?float  $reassignEstimatedBudget= null;
    public ?string $reassignDescription    = null;
    public ?int    $reassignPmId           = null;

    public array   $reassignSponsorIds     = [];
    public array   $reassignOwnerIds       = [];
    public array   $reassignSteeringIds    = [];
    public array   $reassignMemberIds      = [];

    public function addReassignSponsor(string $userId): void
    {
        if ($userId !== '' && !in_array($userId, $this->reassignSponsorIds)) {
            $this->reassignSponsorIds[] = $userId;
        }
    }

    public function removeReassignSponsor(string $userId): void
    {
        $this->reassignSponsorIds = array_values(array_diff($this->reassignSponsorIds, [$userId]));
    }

    public function addReassignOwner(string $userId): void
    {
        if ($userId !== '' && !in_array($userId, $this->reassignOwnerIds)) {
            $this->reassignOwnerIds[] = $userId;
        }
    }

    public function removeReassignOwner(string $userId): void
    {
        $this->reassignOwnerIds = array_values(array_diff($this->reassignOwnerIds, [$userId]));
    }

    public function addReassignSteering(string $userId): void
    {
        if ($userId !== '' && !in_array($userId, $this->reassignSteeringIds)) {
            $this->reassignSteeringIds[] = $userId;
        }
    }

    public function removeReassignSteering(string $userId): void
    {
        $this->reassignSteeringIds = array_values(array_diff($this->reassignSteeringIds, [$userId]));
    }

    public function addReassignMember(string $userId): void
    {
        if ($userId !== '' && !in_array($userId, $this->reassignMemberIds)) {
            $this->reassignMemberIds[] = $userId;
        }
    }

    public function removeReassignMember(string $userId): void
    {
        $this->reassignMemberIds = array_values(array_diff($this->reassignMemberIds, [$userId]));
    }

    public function openReassignModal(int $projectId, ?int $requestId = null): void
    {
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'Only PMO Admins can reassign project leadership.', type: 'error');
            return;
        }

        $project = Project::with(['subsidiary', 'projectManager', 'members'])->find($projectId);
        if (!$project) {
            $this->dispatch('toast', message: 'Project not found.', type: 'error');
            return;
        }

        $this->reassignProjectId = $projectId;
        $this->reassignRequestId = $requestId;

        // Fetch decline/rejection reason if available
        $req = $requestId ? ApprovalRequest::find($requestId) : ApprovalRequest::where('project_id', $projectId)->where('request_type', ApprovalType::NEW_PROJECT_PLAN)->latest()->first();
        $this->reassignDeclineReason = $project->pm_rejection_reason ?: ($req?->review_comment ?: $req?->reason);

        // Pre-fill all original project creation fields
        $this->reassignName            = $project->name ?? '';
        $this->reassignCode            = $project->code ?? '';
        $this->reassignSubsidiaryId    = $project->subsidiary_id;
        $this->reassignCategory        = $project->category ?? 'General';
        $this->reassignPriority        = $project->priority?->value ?? 'medium';
        $this->reassignStartDate       = $project->start_date ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d');
        $this->reassignDeadline        = $project->deadline ? $project->deadline->format('Y-m-d') : now()->addMonths(3)->format('Y-m-d');
        $this->reassignEstimatedBudget = $project->estimated_budget ? (float) $project->estimated_budget : 0;
        $this->reassignDescription     = $project->description ?? '';
        $this->reassignPmId            = $project->project_manager_id;

        // Sync governance and member arrays
        $this->reassignSponsorIds  = $project->members->where('pivot.role', 'sponsor')->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->reassignOwnerIds    = $project->members->where('pivot.role', 'owner')->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->reassignSteeringIds = $project->members->where('pivot.role', 'steering_committee')->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->reassignMemberIds   = $project->members->where('pivot.role', 'member')->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $this->showReassignModal = true;
    }

    public function closeReassignModal(): void
    {
        $this->showReassignModal       = false;
        $this->reassignProjectId       = null;
        $this->reassignRequestId       = null;
        $this->reassignDeclineReason   = null;
        $this->reassignName           = '';
        $this->reassignCode           = '';
        $this->reassignSubsidiaryId   = null;
        $this->reassignCategory       = 'General';
        $this->reassignPriority       = 'medium';
        $this->reassignStartDate      = null;
        $this->reassignDeadline       = null;
        $this->reassignEstimatedBudget= null;
        $this->reassignDescription    = null;
        $this->reassignPmId           = null;
        $this->reassignSponsorIds     = [];
        $this->reassignOwnerIds       = [];
        $this->reassignSteeringIds    = [];
        $this->reassignMemberIds      = [];
    }

    public function submitReassignPm(): void
    {
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $this->validate([
            'reassignName'            => 'required|string|max:255',
            'reassignCode'            => 'required|string|max:100',
            'reassignSubsidiaryId'    => 'required|exists:subsidiaries,id',
            'reassignPmId'            => 'required|exists:users,id',
            'reassignStartDate'       => 'required|date',
            'reassignDeadline'        => 'required|date|after_or_equal:reassignStartDate',
            'reassignEstimatedBudget' => 'nullable|numeric|min:0',
            'reassignPriority'        => 'required|string',
        ], [
            'reassignName.required'            => 'Please enter the project name.',
            'reassignCode.required'            => 'Please enter the project code.',
            'reassignSubsidiaryId.required'    => 'Please select a subsidiary.',
            'reassignPmId.required'            => 'Please select a new Project Manager.',
            'reassignStartDate.required'       => 'Please select a start date.',
            'reassignDeadline.required'        => 'Please select a target deadline.',
            'reassignDeadline.after_or_equal' => 'Deadline must be on or after the start date.',
        ]);

        $project   = Project::findOrFail($this->reassignProjectId);
        $newLeader = \App\Models\User::findOrFail($this->reassignPmId);
        $isSelfAssigned = ($user->id === $newLeader->id);

        // 1. Update project details & reset PM acceptance status
        $project->update([
            'code'               => strtoupper($this->reassignCode),
            'name'               => $this->reassignName,
            'description'        => $this->reassignDescription,
            'subsidiary_id'      => $this->reassignSubsidiaryId,
            'category'           => $this->reassignCategory,
            'priority'           => $this->reassignPriority,
            'start_date'         => $this->reassignStartDate,
            'deadline'           => $this->reassignDeadline,
            'estimated_budget'   => $this->reassignEstimatedBudget ?: 0,
            'project_manager_id' => $newLeader->id,
            'pm_accepted'        => $isSelfAssigned,
            'pm_accepted_at'     => $isSelfAssigned ? now() : null,
            'pm_rejection_reason'=> null,
            'pm_rejected_at'     => null,
        ]);

        // 2. Sync all governance roles & team members in project_members
        $syncData = [];
        foreach (array_map('intval', $this->reassignSponsorIds) as $sId) {
            if ($sId > 0) $syncData[$sId] = ['role' => 'sponsor'];
        }
        foreach (array_map('intval', $this->reassignOwnerIds) as $oId) {
            if ($oId > 0) $syncData[$oId] = ['role' => 'owner'];
        }
        foreach (array_map('intval', $this->reassignSteeringIds) as $scId) {
            if ($scId > 0) $syncData[$scId] = ['role' => 'steering_committee'];
        }
        $syncData[$newLeader->id] = ['role' => 'lead'];

        foreach (array_map('intval', $this->reassignMemberIds) as $mId) {
            if ($mId > 0 && !isset($syncData[$mId])) {
                $syncData[$mId] = ['role' => 'member'];
            }
        }
        $project->members()->sync($syncData);

        // 3. Update or recreate the approval request
        $req = null;
        if ($this->reassignRequestId) {
            $req = ApprovalRequest::find($this->reassignRequestId);
        }
        if (!$req) {
            $req = ApprovalRequest::where('project_id', $project->id)
                ->where('request_type', ApprovalType::NEW_PROJECT_PLAN)
                ->latest()
                ->first();
        }

        $requestedValue = [
            'project_name'         => $project->name,
            'project_code'         => $project->code,
            'assigned_pm_id'       => $newLeader->id,
            'project_manager_id'   => $newLeader->id,
            'project_manager_name' => $newLeader->name,
            'start_date'           => $project->start_date?->toDateString(),
            'deadline'             => $project->deadline?->toDateString(),
            'estimated_budget'     => $project->estimated_budget,
        ];

        if ($req) {
            $req->update([
                'status'          => $isSelfAssigned ? ApprovalStatus::APPROVED : ApprovalStatus::PENDING,
                'reason'          => "PMO Administration reassigned & reconfigured project details for '{$project->name}'.",
                'reviewed_by'     => $isSelfAssigned ? $user->id : null,
                'reviewed_at'     => $isSelfAssigned ? now() : null,
                'review_comment'  => $isSelfAssigned ? 'Self-assigned by PMO Admin' : null,
                'requested_value' => $requestedValue,
            ]);
        } else {
            ApprovalRequest::create([
                'project_id'      => $project->id,
                'request_type'    => ApprovalType::NEW_PROJECT_PLAN,
                'requested_by'    => $user->id,
                'reason'          => "PMO Administration reassigned & reconfigured project details for '{$project->name}'.",
                'status'          => $isSelfAssigned ? ApprovalStatus::APPROVED : ApprovalStatus::PENDING,
                'submitted_at'    => now(),
                'requested_value' => $requestedValue,
            ]);
        }

        // 4. Log activity
        \App\Models\ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'reassigned_project_and_reconfigured',
            'module'      => 'projects',
            'record_type' => Project::class,
            'record_id'   => $project->id,
            'new_values'  => [
                'name'                 => $project->name,
                'code'                 => $project->code,
                'project_manager_id'  => $newLeader->id,
                'project_manager_name' => $newLeader->name,
                'start_date'           => $project->start_date?->toDateString(),
                'deadline'             => $project->deadline?->toDateString(),
                'estimated_budget'     => $project->estimated_budget,
            ],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        // 5. Send notification to the new leader
        try {
            $newLeader->notify(new \App\Notifications\ProjectAssignmentNotification($project, 'lead'));
            \Illuminate\Support\Facades\Mail::to($newLeader->email)->send(new \App\Mail\ProjectAssignedMail($project, $newLeader, 'lead'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Reassign notification error: ' . $e->getMessage());
        }

        $this->closeReassignModal();
        $this->dispatch('toast', message: "🎉 Project '{$project->name}' reassigned to {$newLeader->name} and reconfigured successfully!", type: 'success');
    }

    public function render()
    {
        $user = auth()->user();

        $query = ApprovalRequest::with(['project.subsidiary', 'project.projectManager', 'requester', 'reviewer'])
            ->latest('submitted_at');

        // Scope filter for Team Members vs Managers vs Admins
        if ($this->scopeFilter === 'my_requests') {
            $query->where('requested_by', $user->id);
        } elseif (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $query->where(function ($q) use ($user) {
                $q->where('requested_by', $user->id)
                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                  ->orWhere(function ($memberQuery) use ($user) {
                      $memberQuery->whereHas('project.members', fn($mq) => $mq->where('users.id', $user->id))
                                  ->where('request_type', '!=', ApprovalType::NEW_PROJECT_PLAN);
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter !== 'all') {
            $query->where('request_type', $this->typeFilter);
        }

        $requests = $query->paginate(10);

        $selectedRequest = $this->selectedRequestId
            ? ApprovalRequest::with(['project.subsidiary', 'project.projectManager', 'project.members', 'project.wbsItems.assignedUser', 'requester', 'reviewer'])->find($this->selectedRequestId)
            : null;

        $deletingRequest = $this->deletingRequestId
            ? ApprovalRequest::with(['project.subsidiary', 'requester'])->find($this->deletingRequestId)
            : null;

        // Pending Leadership Projects
        $pendingLeadershipProjects = collect();
        if ($user->hasRole('super_admin')) {
            $pendingLeadershipProjects = Project::where('pm_accepted', false)
                ->with(['subsidiary', 'template', 'projectManager', 'wbsItems'])
                ->latest()
                ->get();
        } else {
            $pendingLeadershipProjects = Project::where('project_manager_id', $user->id)
                ->where('pm_accepted', false)
                ->with(['subsidiary', 'template', 'projectManager', 'wbsItems'])
                ->latest()
                ->get();
        }

        // Data for Create Modal
        $userProjects = $user->hasRole('super_admin')
            ? Project::all()
            : Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->whereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                  });
            })->get();

        $isPmOrAdmin = $user->hasRole('super_admin') || ($this->projectId && Project::where('id', $this->projectId)->where('project_manager_id', $user->id)->exists());
        $userWbsTasks = $this->projectId
            ? WbsItem::where('project_id', $this->projectId)
                ->when(!$isPmOrAdmin, fn($q) => $q->where('assigned_user_id', $user->id))
                ->get()
            : collect();

        $subUsers = $this->reassignSubsidiaryId
            ? \App\Models\User::getUsersForSubsidiary($this->reassignSubsidiaryId)
            : \App\Models\User::where('is_active', true)->orderBy('name')->get();

        $assignedIds = array_filter(array_map('intval', array_merge(
            [$this->reassignPmId],
            $this->reassignSponsorIds,
            $this->reassignOwnerIds,
            $this->reassignSteeringIds,
            $this->reassignMemberIds
        )));

        if (!empty($assignedIds)) {
            $assignedUsers = \App\Models\User::with(['subsidiary', 'roles'])->whereIn('id', $assignedIds)->get();
            $availablePms = $subUsers->concat($assignedUsers)->unique('id')->sortBy('name')->values();
        } else {
            $availablePms = $subUsers;
        }

        $subsidiaries = \App\Models\Subsidiary::orderBy('name')->get();
        $reassignProject = $this->reassignProjectId ? Project::with(['projectManager', 'subsidiary', 'members'])->find($this->reassignProjectId) : null;

        return view('livewire.approval-manager', compact(
            'requests',
            'selectedRequest',
            'deletingRequest',
            'userProjects',
            'userWbsTasks',
            'pendingLeadershipProjects',
            'availablePms',
            'subsidiaries',
            'reassignProject'
        ))->layout('layouts.app', ['title' => 'Approval Workflows — GS NexusPM']);
    }
}
