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
        $myProject = Project::whereHas('members', fn($q) => $q->where('user_id', $user->id))
            ->orWhere('project_manager_id', $user->id)
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

        ApprovalRequest::create([
            'project_id' => $this->projectId,
            'request_type' => $this->requestType,
            'requested_by' => auth()->id(),
            'current_value' => null,
            'requested_value' => $requestedValue,
            'reason' => $this->reason,
            'status' => ApprovalStatus::PENDING,
            'submitted_at' => now(),
        ]);

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
        if (!$user->hasAnyRole(['super_admin', 'project_manager'])) {
            $this->dispatch('toast', message: 'Only Super Admins and Project Managers can approve formal requests.', type: 'error');
            return;
        }

        $request = ApprovalRequest::with(['project', 'requester'])->findOrFail($this->selectedRequestId);

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
        if (!$user->hasAnyRole(['super_admin', 'project_manager'])) {
            $this->dispatch('toast', message: 'Only Super Admins and Project Managers can reject requests.', type: 'error');
            return;
        }

        $this->validate(['reviewComment' => 'required|string|min:5'],
            ['reviewComment.required' => 'A rejection reason is required.']);

        $request = ApprovalRequest::with(['project', 'requester'])->findOrFail($this->selectedRequestId);

        if ($request->status->value !== 'pending') {
            $this->dispatch('toast', message: 'This request has already been processed.', type: 'error');
            return;
        }

        try {
            (new ApprovalService())->reject($request, $user, $this->reviewComment);
            $this->showReviewModal   = false;
            $this->selectedRequestId = null;
            $this->reviewComment     = '';
            $this->dispatch('toast', message: '❌ Request has been REJECTED.', type: 'warning');
        } catch (InvalidArgumentException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'An error occurred: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $user = auth()->user();

        $query = ApprovalRequest::with(['project', 'requester', 'reviewer'])
            ->latest();

        // Scope filter for Team Members vs Managers vs Admins
        if ($this->scopeFilter === 'my_requests') {
            $query->where('requested_by', $user->id);
        } elseif (!$user->hasRole('super_admin')) {
            if ($user->hasRole('project_manager')) {
                $query->where(function($q) use ($user) {
                    $q->where('requested_by', $user->id)
                      ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id));
                });
            } else {
                // Team members see requests submitted by them or for projects they belong to
                $query->where(function($q) use ($user) {
                    $q->where('requested_by', $user->id)
                      ->orWhereHas('project', fn($pq) => $pq->whereHas('members', fn($mq) => $mq->where('user_id', $user->id)));
                });
            }
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter !== 'all') {
            $query->where('request_type', $this->typeFilter);
        }

        $requests = $query->paginate(10);

        $selectedRequest = $this->selectedRequestId
            ? ApprovalRequest::with(['project', 'requester', 'reviewer'])->find($this->selectedRequestId)
            : null;

        // Data for Create Modal
        $userProjects = $user->hasRole('super_admin')
            ? Project::all()
            : Project::whereHas('members', fn($q) => $q->where('user_id', $user->id))
                ->orWhere('project_manager_id', $user->id)
                ->get();

        $userWbsTasks = $this->projectId
            ? WbsItem::where('project_id', $this->projectId)
                ->when(!$user->hasAnyRole(['super_admin', 'project_manager']), fn($q) => $q->where('assigned_user_id', $user->id))
                ->get()
            : collect();

        return view('livewire.approval-manager', compact('requests', 'selectedRequest', 'userProjects', 'userWbsTasks'));
    }
}
