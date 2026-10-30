<?php

namespace App\Livewire;

use App\Enums\ApprovalStatus;
use App\Enums\ApprovalType;
use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectApprovalActions extends Component
{
    #[Locked]
    public ?int $approvalProjectId = null;
    public bool $showApprovalList = false;
    public bool $showReviewModal = false;
    public ?int $selectedRequestId = null;
    public string $reviewComment = '';
    public bool $showCreateModal = false;
    public ?int $projectId = null;
    public string $requestType = 'deadline_extension';
    public ?int $wbsItemId = null;
    public string $reason = '';
    public string $newDeadline = '';
    public string $newBudget = '';
    public string $scopeDescription = '';
    public bool $showDeclineModal = false;
    public ?int $decliningProjectId = null;
    public string $declineReason = '';

    public function mount(?int $projectId = null): void
    {
        if ($projectId) {
            $this->openProjectApprovals($projectId);
        }
    }

    private function authorizedProject(int $id): Project
    {
        $project = Project::findOrFail($id);
        $user = auth()->user();
        abort_unless($user && ($user->isPmoAdmin()
            || $project->project_manager_id === $user->id
            || ($project->pm_accepted && (
                $project->members()->where('users.id', $user->id)->exists()
                || $project->wbsItems()->where('assigned_user_id', $user->id)->exists()
            ))), 403);

        return $project;
    }

    private function contextualProject(int $id): Project
    {
        abort_unless($this->approvalProjectId === $id, 403);
        return $this->authorizedProject($id);
    }

    private function contextualRequest(int $id): ApprovalRequest
    {
        $this->contextualProject($this->approvalProjectId ?? 0);
        $request = ApprovalRequest::visibleTo(auth()->user())
            ->where('project_id', $this->approvalProjectId)
            ->with(['project.members', 'project.wbsItems.assignedUser', 'project.subsidiary', 'project.projectManager', 'requester', 'reviewer'])
            ->find($id);
        abort_unless($request, 404);
        return $request;
    }

    #[On('open-project-approvals')]
    public function openProjectApprovals(int $projectId, string $action = 'history'): void
    {
        $this->authorizedProject($projectId);
        $this->resetValidation();
        $this->reset(['showApprovalList', 'showReviewModal', 'showCreateModal', 'showDeclineModal', 'showReassignModal', 'selectedRequestId']);
        $this->approvalProjectId = $projectId;
        if ($action === 'reassign') {
            $this->openReassignModal($projectId);
        } elseif ($action === 'accept') {
            $this->acceptProject($projectId);
        } elseif ($action === 'reject') {
            $this->openDeclineProjectModal($projectId);
        } else {
            $this->showApprovalList = true;
        }
    }

    public function closeApprovals(): void
    {
        $this->reset(['showApprovalList', 'showReviewModal', 'showCreateModal', 'showDeclineModal', 'showReassignModal', 'selectedRequestId']);
        $this->resetValidation();
    }

    private function canDecide(ApprovalRequest $request, bool $approved): bool
    {
        $user = auth()->user();
        $project = $request->project;
        if ($request->status !== ApprovalStatus::PENDING || !$project) {
            return false;
        }
        if ($request->request_type === ApprovalType::NEW_PROJECT_PLAN) {
            return $project->isPendingPmAcceptance()
                && $project->canApproveAssignment($user);
        }
        if ($request->requested_by === $user->id && !$user->isSuperAdmin()) {
            return false;
        }
        if ($request->requested_by === $project->project_manager_id && !$user->isPmoAdmin()) {
            return false;
        }
        return $approved
            ? ($project->userCan($user, 'approval.approve') || $project->userCan($user, 'approval.final_approve'))
            : $project->userCan($user, 'approval.reject');
    }

    public function review(int $id): void
    {
        $this->contextualRequest($id);
        $this->resetValidation();
        $this->selectedRequestId = $id;
        $this->reviewComment = '';
        $this->showApprovalList = false;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->reset(['showReviewModal', 'selectedRequestId', 'reviewComment']);
        $this->resetValidation();
    }

    public function approveRequest(): void
    {
        $this->decideRequest(true);
    }

    public function rejectRequest(): void
    {
        $this->decideRequest(false);
    }

    private function decideRequest(bool $approved): void
    {
        $request = $this->contextualRequest($this->selectedRequestId ?? 0);
        abort_unless($request->status === ApprovalStatus::PENDING, 409, 'This request has already been reviewed.');
        abort_unless($this->canDecide($request, $approved), 403);
        $this->validate(['reviewComment' => $approved ? 'nullable|string|max:1000' : 'required|string|min:5|max:1000']);
        try {
            DB::transaction(function () use ($request, $approved) {
                $project = Project::lockForUpdate()->findOrFail($request->project_id);
                $locked = ApprovalRequest::lockForUpdate()->findOrFail($request->id);
                $locked->setRelation('project', $project);
                abort_unless($locked->status === ApprovalStatus::PENDING, 409);
                abort_unless($this->canDecide($locked, $approved), 403);
                if ($locked->request_type === ApprovalType::NEW_PROJECT_PLAN) {
                    $approved
                        ? $project->acceptByPm(auth()->user())
                        : $project->rejectByPm(auth()->user(), trim($this->reviewComment));
                } elseif ($approved) {
                    app(ApprovalService::class)->approve($locked, auth()->user(), $this->reviewComment ?: null);
                } else {
                    app(ApprovalService::class)->reject($locked, auth()->user(), trim($this->reviewComment));
                }
            });
        } catch (\InvalidArgumentException $e) {
            $this->addError('reviewComment', $e->getMessage());
            return;
        }
        $this->closeReviewModal();
        $this->dispatch('project-approval-updated');
        $this->dispatch('toast', message: $approved ? 'Request approved.' : 'Request rejected.', type: $approved ? 'success' : 'warning');
    }

    public function acceptProject(int $projectId): void
    {
        $this->decideLeadership($projectId, true);
    }

    public function openDeclineProjectModal(int $projectId): void
    {
        $project = $this->contextualProject($projectId);
        abort_unless($project->canApproveAssignment(auth()->user()), 403);
        abort_unless($project->isPendingPmAcceptance(), 409);
        $this->resetValidation();
        $this->decliningProjectId = $projectId;
        $this->declineReason = '';
        $this->showApprovalList = false;
        $this->showDeclineModal = true;
    }

    public function closeDeclineProjectModal(): void
    {
        $this->reset(['showDeclineModal', 'decliningProjectId', 'declineReason']);
        $this->resetValidation();
    }

    public function submitDeclineProject(): void
    {
        $this->validate(['declineReason' => 'required|string|min:5|max:1000']);
        $this->decideLeadership($this->decliningProjectId ?? 0, false);
        $this->closeDeclineProjectModal();
    }

    private function decideLeadership(int $id, bool $approved): void
    {
        $project = $this->contextualProject($id);
        abort_unless($project->canApproveAssignment(auth()->user()), 403);
        DB::transaction(function () use ($project, $approved) {
            $locked = Project::lockForUpdate()->findOrFail($project->id);
            abort_unless($locked->canApproveAssignment(auth()->user()), 403);
            abort_unless($locked->isPendingPmAcceptance(), 409, 'This assignment has already been reviewed.');
            $approved
                ? $locked->acceptByPm(auth()->user())
                : $locked->rejectByPm(auth()->user(), trim($this->declineReason));
        });
        $this->dispatch('project-approval-updated');
        $this->dispatch('toast', message: $approved ? 'Project assignment approved.' : 'Project assignment rejected. PMO has been notified.', type: $approved ? 'success' : 'warning');
    }

    public function updatedProjectId(): void
    {
        $this->wbsItemId = null;
    }

    public function openCreateModal(): void
    {
        $project = $this->contextualProject($this->approvalProjectId ?? 0);
        abort_unless($project->pm_accepted, 403);
        $this->resetValidation();
        $this->reset(['reason', 'newBudget', 'scopeDescription', 'wbsItemId']);
        $this->projectId = $project->id;
        $this->requestType = 'deadline_extension';
        $this->newDeadline = now()->addDays(7)->toDateString();
        $this->showApprovalList = false;
        $this->showCreateModal = true;
    }

    public function createRequest(): void
    {
        $project = $this->contextualProject($this->projectId ?? 0);
        abort_unless($project->pm_accepted, 403);
        $this->validate([
            'requestType' => ['required', Rule::enum(ApprovalType::class), Rule::notIn(['new_project_plan'])],
            'reason' => 'required|string|min:5|max:1000',
            'newDeadline' => 'required_if:requestType,deadline_extension|nullable|date',
            'newBudget' => 'required_if:requestType,budget_change|nullable|numeric|min:0',
            'scopeDescription' => 'required_if:requestType,scope_change|nullable|string|max:5000',
            'wbsItemId' => ['nullable', Rule::exists('wbs_items', 'id')->where('project_id', $project->id)],
        ]);
        $value = match ($this->requestType) {
            'deadline_extension' => ['new_deadline' => $this->newDeadline],
            'budget_change' => ['new_budget' => (float) $this->newBudget],
            'scope_change' => ['scope_description' => $this->scopeDescription],
            default => [],
        };
        if ($this->wbsItemId) {
            $value['wbs_item_id'] = $this->wbsItemId;
        }
        $approval = ApprovalRequest::create([
            'project_id' => $project->id, 'request_type' => $this->requestType,
            'requested_by' => auth()->id(), 'requested_value' => $value,
            'reason' => trim($this->reason), 'status' => ApprovalStatus::PENDING, 'submitted_at' => now(),
        ]);
        $recipients = User::role(['super_admin', 'pmo_admin'])->get();
        if ($project->projectManager) {
            $recipients->push($project->projectManager);
        }
        foreach ($recipients->unique('id')->where('id', '!=', auth()->id()) as $recipient) {
            $recipient->notify(new \App\Notifications\ApprovalStatusNotification($approval, 'submitted'));
        }
        $this->showCreateModal = false;
        $this->showApprovalList = true;
        $this->dispatch('project-approval-updated');
        $this->dispatch('toast', message: 'Approval request submitted.', type: 'success');
    }

    public function cancelRequest(int $id): void
    {
        $request = $this->contextualRequest($id);
        abort_unless($request->requested_by === auth()->id() || auth()->user()->isPmoAdmin(), 403);
        abort_if($request->request_type === ApprovalType::NEW_PROJECT_PLAN, 403);
        $updated = ApprovalRequest::whereKey($id)->where('status', ApprovalStatus::PENDING)->update([
            'status' => ApprovalStatus::CANCELLED, 'reviewed_by' => auth()->id(), 'reviewed_at' => now(),
        ]);
        abort_unless($updated, 409);
        $this->dispatch('project-approval-updated');
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

        $project = $this->contextualProject($projectId)->load(['subsidiary', 'projectManager', 'members']);
        abort_unless($project->isPmRejected(), 409, 'Only rejected assignments can be reassigned.');
        $this->showApprovalList = false;
        $this->resetValidation();
        if (!$project) {
            $this->dispatch('toast', message: 'Project not found.', type: 'error');
            return;
        }

        $this->reassignProjectId = $projectId;
        $this->reassignRequestId = $requestId;

        // Fetch decline/rejection reason if available
        $req = $requestId ? $this->contextualRequest($requestId) : ApprovalRequest::where('project_id', $projectId)->where('request_type', ApprovalType::NEW_PROJECT_PLAN)->latest()->first();
        $this->reassignDeclineReason = $project->pm_rejection_reason ?: ($req?->review_comment ?: $req?->reason);

        // Pre-fill all original project creation fields
        $this->reassignName            = $project->name ?? '';
        $this->reassignCode            = $project->code ?? '';
        $this->reassignSubsidiaryId    = $project->subsidiary_id;
        $this->reassignCategory        = $project->category ?? 'General';
        $this->reassignPriority        = $project->priority?->value ?? 'medium';
        $this->reassignStartDate       = $project->start_date ? $project->start_date->format('Y-m-d') : now()->format('Y-m-d');
        $this->reassignDeadline        = $project->deadline?->format('Y-m-d');
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
        abort_unless(auth()->user()->isPmoAdmin(), 403);
        $project = $this->contextualProject($this->reassignProjectId ?? 0);
        $this->reassignCode = strtoupper(trim($this->reassignCode));
        $eligibleIds = User::getPmsForSubsidiary($this->reassignSubsidiaryId)->pluck('id')->all();
        $this->validate([
            'reassignName' => 'required|string|max:255',
            'reassignCode' => ['required', 'string', 'max:30', Rule::unique('projects', 'code')->ignore($project->id)],
            'reassignSubsidiaryId' => 'required|exists:subsidiaries,id',
            'reassignPmId' => ['required', Rule::exists('users', 'id')->where('is_active', true), Rule::in($eligibleIds)],
            'reassignStartDate' => 'required|date',
            'reassignDeadline' => 'nullable|date|after_or_equal:reassignStartDate',
            'reassignEstimatedBudget' => 'nullable|numeric|min:0',
            'reassignPriority' => ['required', Rule::enum(\App\Enums\Priority::class)],
            'reassignDescription' => 'nullable|string',
            'reassignCategory' => 'nullable|string|max:100',
            'reassignSponsorIds.*' => 'integer|exists:users,id',
            'reassignOwnerIds.*' => 'integer|exists:users,id',
            'reassignSteeringIds.*' => 'integer|exists:users,id',
            'reassignMemberIds.*' => 'integer|exists:users,id',
        ]);
        $newLeader = User::findOrFail($this->reassignPmId);
        DB::transaction(function () use ($project, $newLeader) {
            $project = Project::lockForUpdate()->findOrFail($project->id);
            abort_unless($project->isPmRejected(), 409, 'Only rejected assignments can be reassigned.');
            $oldValues = $project->only(['project_manager_id', 'pm_rejection_reason', 'name', 'code', 'deadline']);
            $selfAssigned = auth()->id() === $newLeader->id;
            $project->update([
                'name' => $this->reassignName, 'code' => strtoupper($this->reassignCode),
                'description' => $this->reassignDescription, 'subsidiary_id' => $this->reassignSubsidiaryId,
                'category' => trim($this->reassignCategory) ?: null,
                'priority' => $this->reassignPriority, 'start_date' => $this->reassignStartDate,
                'deadline' => $this->reassignDeadline, 'estimated_budget' => $this->reassignEstimatedBudget ?: 0,
                'project_manager_id' => $newLeader->id, 'pm_accepted' => $selfAssigned,
                'pm_accepted_at' => $selfAssigned ? now() : null, 'pm_rejection_reason' => null, 'pm_rejected_at' => null,
            ]);
            // Keep custom roles when updating the standard governance assignments.
            $sync = $project->members()->get()->reject(fn ($member) => in_array($member->pivot->role, ['sponsor', 'owner', 'steering_committee', 'member', 'lead']))
                ->mapWithKeys(fn ($member) => [$member->id => ['role' => $member->pivot->role]])->all();
            foreach (['sponsor' => $this->reassignSponsorIds, 'owner' => $this->reassignOwnerIds, 'steering_committee' => $this->reassignSteeringIds, 'member' => $this->reassignMemberIds] as $role => $ids) {
                foreach ($ids as $id) {
                    $sync[(int) $id] ??= ['role' => $role];
                }
            }
            $sync[$newLeader->id] = ['role' => 'lead'];
            $project->members()->sync($sync);
            ApprovalRequest::where('project_id', $project->id)->where('request_type', ApprovalType::NEW_PROJECT_PLAN)
                ->where('status', ApprovalStatus::PENDING)->update([
                    'status' => ApprovalStatus::CANCELLED, 'reviewed_by' => auth()->id(), 'reviewed_at' => now(),
                    'review_comment' => 'Superseded by a new leadership assignment.',
                ]);
            // Create a new request so the rejected decision and its reason remain in history.
            ApprovalRequest::create([
                'project_id' => $project->id, 'request_type' => ApprovalType::NEW_PROJECT_PLAN,
                'requested_by' => auth()->id(), 'status' => $selfAssigned ? ApprovalStatus::APPROVED : ApprovalStatus::PENDING,
                'submitted_at' => now(), 'reviewed_by' => $selfAssigned ? auth()->id() : null,
                'reviewed_at' => $selfAssigned ? now() : null, 'review_comment' => $selfAssigned ? 'Self-assigned by PMO Admin.' : null,
                'reason' => "PMO Administration reassigned project leadership to {$newLeader->name}.",
                'requested_value' => [
                    'project_name' => $project->name, 'project_code' => $project->code,
                    'assigned_pm_id' => $newLeader->id, 'project_manager_name' => $newLeader->name,
                    'start_date' => $project->start_date?->toDateString(), 'deadline' => $project->deadline?->toDateString(),
                ],
            ]);
            ActivityLog::create([
                'user_id' => auth()->id(), 'action' => 'reassigned_project_and_reconfigured', 'module' => 'projects',
                'record_type' => Project::class, 'record_id' => $project->id, 'previous_values' => $oldValues,
                'new_values' => ['project_manager_id' => $newLeader->id, 'name' => $project->name, 'code' => $project->code],
                'ip_address' => request()->ip(), 'user_agent' => request()->userAgent(),
            ]);
        });
        $project->refresh();
        try {
            $newLeader->notify(new \App\Notifications\ProjectAssignmentNotification($project, 'lead'));
            \Illuminate\Support\Facades\Mail::to($newLeader->email)->send(new \App\Mail\ProjectAssignedMail($project, $newLeader, 'lead'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Reassign notification error: '.$e->getMessage());
        }
        $this->closeReassignModal();
        $this->dispatch('project-approval-updated');
        $this->dispatch('toast', message: "Project reassigned to {$newLeader->name}.", type: 'success');
    }

    public function render()
    {
        $approvalProject = $this->approvalProjectId ? $this->authorizedProject($this->approvalProjectId) : null;
        $requests = $approvalProject
            ? $approvalProject->approvalRequests()->visibleTo(auth()->user())->with(['requester', 'reviewer'])->orderByDesc('id')->get()
            : collect();
        $selectedRequest = $this->showReviewModal && $this->selectedRequestId ? $this->contextualRequest($this->selectedRequestId) : null;
        $canApprove = $selectedRequest && $this->canDecide($selectedRequest, true);
        $canReject = $selectedRequest && $this->canDecide($selectedRequest, false);
        $userProjects = collect($approvalProject ? [$approvalProject] : []);
        $userWbsTasks = $this->showCreateModal && $approvalProject
            ? $approvalProject->wbsItems()->when(!auth()->user()->isPmoAdmin() && $approvalProject->project_manager_id !== auth()->id(), fn ($q) => $q->where('assigned_user_id', auth()->id()))->orderBy('wbs_code')->get()
            : collect();
        $reassignProject = $this->showReassignModal && $this->reassignProjectId ? $this->contextualProject($this->reassignProjectId)->load(['projectManager', 'subsidiary', 'members']) : null;
        $availablePms = $reassignProject ? User::getUsersForSubsidiary($this->reassignSubsidiaryId)
            ->concat($reassignProject->members)->unique('id')->sortBy('name')->values() : collect();
        $subsidiaries = $reassignProject ? Subsidiary::orderBy('name')->get() : collect();

        return view('livewire.project-approval-actions', compact(
            'approvalProject', 'requests', 'selectedRequest', 'canApprove', 'canReject',
            'userProjects', 'userWbsTasks', 'reassignProject', 'availablePms', 'subsidiaries'
        ));
    }
}
