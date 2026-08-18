<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class MyLeadProjects extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public int $perPage = 12;

    public bool $showReviewModal = false;
    public ?int $reviewProjectId = null;
    public bool $showRejectModal = false;
    public string $rejectionReasonInput = '';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function openReviewModal(int $projectId): void
    {
        $this->reviewProjectId = $projectId;
        $this->rejectionReasonInput = '';
        $this->showRejectModal = false;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->showRejectModal = false;
        $this->reviewProjectId = null;
        $this->rejectionReasonInput = '';
    }

    public function acceptProject(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $user = auth()->user();

        if ($project->project_manager_id !== $user->id && !$user->isSuperAdmin()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $project->acceptByPm($user);
        $this->closeReviewModal();
        $this->dispatch('toast', message: '🎉 Project leadership accepted! You can now access and manage this project workspace.', type: 'success');
    }

    public function openRejectForm(int $projectId): void
    {
        $this->reviewProjectId = $projectId;
        $this->rejectionReasonInput = '';
        $this->showRejectModal = true;
    }

    public function submitRejection(): void
    {
        $this->validate([
            'rejectionReasonInput' => 'required|string|min:5|max:1000',
        ], [
            'rejectionReasonInput.required' => 'Please explain the issue or reason for declining this assignment.',
            'rejectionReasonInput.min' => 'The reason must be at least 5 characters.',
        ]);

        $project = Project::findOrFail($this->reviewProjectId);
        $user = auth()->user();

        if ($project->project_manager_id !== $user->id && !$user->isSuperAdmin()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $project->rejectByPm($user, $this->rejectionReasonInput);
        $this->closeReviewModal();
        $this->dispatch('toast', message: 'Project assignment declined. PMO Admin has been notified of your issue report.', type: 'warning');
    }

    public function render()
    {
        $user = auth()->user();

        // PMO Admin sees all projects they created / all projects in the system
        // Regular users see only projects where they are the designated leader
        if ($user->isSuperAdmin()) {
            $query = Project::query()->with(['subsidiary', 'projectManager', 'members']);
        } else {
            $query = Project::query()
                ->where('project_manager_id', $user->id)
                ->with(['subsidiary', 'projectManager', 'members']);
        }

        // Summary counts before filters
        $totalLeadCount = (clone $query)->count();
        $activeLeadCount = (clone $query)->where('status', 'in_progress')->count();
        $overdueLeadCount = (clone $query)
            ->where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $completedLeadCount = (clone $query)->where('status', 'completed')->count();

        // Apply search
        if ($this->search) {
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
            );
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->paginate($this->perPage);
        $reviewProject = $this->reviewProjectId ? Project::with(['subsidiary', 'projectManager', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId) : null;

        return view('livewire.my-lead-projects', compact(
            'projects',
            'reviewProject',
            'totalLeadCount',
            'activeLeadCount',
            'overdueLeadCount',
            'completedLeadCount'
        ))->title('Lead Projects — GS NexusPM');
    }
}
