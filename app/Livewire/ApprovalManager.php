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

    public function render()
    {
        $user = auth()->user();

        $query = ApprovalRequest::with(['project', 'requester', 'reviewer'])
            ->latest();

        // Scope filter for Team Members vs Managers vs Admins
        if ($this->scopeFilter === 'my_requests') {
            $query->where('requested_by', $user->id);
        } elseif (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $query->where(function($q) use ($user) {
                $q->where('requested_by', $user->id)
                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                  ->orWhere(function($memberQuery) use ($user) {
                      $memberQuery->whereHas('project.members', fn($mq) => $mq->where('users.id', $user->id))
                                  ->where('request_type', '!=', \App\Enums\ApprovalType::NEW_PROJECT_PLAN);
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

        return view('livewire.approval-manager', compact('requests', 'selectedRequest', 'userProjects', 'userWbsTasks', 'pendingLeadershipProjects'));
    }
}
