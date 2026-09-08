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
    public string $roleFilter = 'all';
    public int $perPage = 12;

    public bool $showReviewModal = false;
    public ?int $reviewProjectId = null;
    public bool $showRejectModal = false;
    public string $rejectionReasonInput = '';

    protected $queryString = [
        'roleFilter'   => ['except' => 'all', 'as' => 'role'],
        'statusFilter' => ['except' => 'all', 'as' => 'status'],
        'search'       => ['except' => ''],
    ];

    public function mount()
    {
        if (request()->has('role')) {
            $this->roleFilter = request('role');
        }
        if (request()->has('status')) {
            $this->statusFilter = request('status');
        }
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedRoleFilter() { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->roleFilter = 'all';
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

        // Base query: all projects this user is involved in (any role)
        if ($user->isPmoAdmin()) {
            // PMO Admin sees all projects
            $baseQuery = Project::query()->with(['subsidiary', 'projectManager', 'members']);
        } else {
            // Regular users:
            // - If user is the designated PM: sees their assigned projects (both accepted and pending acceptance)
            // - If user is any other member (sponsor, owner, committee, team member): ONLY sees projects where pm_accepted is true
            $baseQuery = Project::query()
                ->where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('pm_accepted', true)
                               ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                      });
                })
                ->with(['subsidiary', 'projectManager', 'members']);
        }

        // Summary counts (before filters)
        $totalCount      = (clone $baseQuery)->count();
        $activeCount     = (clone $baseQuery)->where('status', 'in_progress')->count();
        $overdueCount    = (clone $baseQuery)->where('deadline', '<', now())->whereNotIn('status', ['completed', 'cancelled'])->count();
        $completedCount  = (clone $baseQuery)->where('status', 'completed')->count();

        // Role breakdown counts (before filters)
        $roleCounts = [
            'lead' => (clone $baseQuery)->where('project_manager_id', $user->id)->count(),
            'sponsor' => (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'sponsor'))->count(),
            'owner' => (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'owner'))->count(),
            'steering_committee' => (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'steering_committee'))->count(),
            'member' => (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'member'))->count(),
        ];

        // Role filter
        if ($this->roleFilter !== 'all') {
            if ($this->roleFilter === 'lead') {
                $baseQuery->where('project_manager_id', $user->id);
            } else {
                $baseQuery->whereHas('members', fn($mq) =>
                    $mq->where('users.id', $user->id)->where('role', $this->roleFilter)
                );
            }
        }

        // Search
        if ($this->search) {
            $baseQuery->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
            );
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $baseQuery->where('status', $this->statusFilter);
        }

        $projects = $baseQuery->latest()->paginate($this->perPage);

        // Compute user role per project for display
        $userRoles = [];
        foreach ($projects as $p) {
            if ($user->isPmoAdmin()) {
                $userRoles[$p->id] = 'pmo_admin';
            } elseif ($p->project_manager_id === $user->id) {
                $userRoles[$p->id] = 'lead';
            } else {
                $member = $p->members->firstWhere('id', $user->id);
                $userRoles[$p->id] = $member?->pivot?->role ?? 'member';
            }
        }

        $reviewProject = $this->reviewProjectId ? Project::with(['subsidiary', 'projectManager', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId) : null;

        return view('livewire.my-lead-projects', compact(
            'projects',
            'reviewProject',
            'totalCount',
            'activeCount',
            'overdueCount',
            'completedCount',
            'roleCounts',
            'userRoles'
        ))->title('My Projects — GS NexusPM');
    }
}
