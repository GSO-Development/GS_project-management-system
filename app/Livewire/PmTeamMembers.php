<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;
use Livewire\WithPagination;

class PmTeamMembers extends Component
{
    use WithPagination;

    public string $search = '';
    public string $projectFilter = 'all';
    public string $statusFilter  = 'all';

    /* ─── modal ─── */
    public bool   $showDetailModal = false;
    public ?int   $detailUserId    = null;

    public function updatedSearch()        { $this->resetPage(); }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedStatusFilter()  { $this->resetPage(); }

    /** IDs of projects managed by the current PM */
    private function managedProjectIds(): array
    {
        return Project::where('project_manager_id', auth()->id())
            ->pluck('id')
            ->toArray();
    }

    public function openDetail(int $userId): void
    {
        $this->detailUserId    = $userId;
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->detailUserId    = null;
    }

    public function removeCollaboratorFromMyProjects(int $memberId): void
    {
        $managedProjectIds = $this->managedProjectIds();

        // Detach member from PM's projects
        \Illuminate\Support\Facades\DB::table('project_members')
            ->whereIn('project_id', $managedProjectIds)
            ->where('user_id', $memberId)
            ->delete();

        // Unassign from tasks in PM's projects
        WbsItem::whereIn('project_id', $managedProjectIds)
            ->where('assigned_user_id', $memberId)
            ->update(['assigned_user_id' => null]);

        $this->dispatch('toast', message: 'Collaborator removed from your projects successfully!', type: 'info');
    }

    public function render()
    {
        $user              = auth()->user();
        $managedProjectIds = $this->managedProjectIds();

        // All projects managed by this PM (for filter dropdown)
        $managedProjects = Project::whereIn('id', $managedProjectIds)->get();

        // Build members query – users who are members of this PM's projects
        $query = User::with(['subsidiary', 'roles', 'projects'])
            ->whereHas('projects', fn ($q) =>
                $q->whereIn('project_members.project_id', $managedProjectIds)
            );

        // Project-specific filter
        if ($this->projectFilter !== 'all') {
            $query->whereHas('projects', fn ($q) =>
                $q->where('project_members.project_id', $this->projectFilter)
            );
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        // Search
        if ($this->search) {
            $query->where(fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
            );
        }

        $members = $query->latest()->paginate(12);

        // Stats
        $totalMembers  = User::whereHas('projects', fn ($q) =>
            $q->whereIn('project_members.project_id', $managedProjectIds)
        )->count();

        $activeMembers = User::whereHas('projects', fn ($q) =>
            $q->whereIn('project_members.project_id', $managedProjectIds)
        )->where('is_active', true)->count();

        // Detail member data
        $detailMember      = null;
        $memberTasks       = collect();
        $memberProjects    = collect();
        if ($this->detailUserId) {
            $detailMember   = User::with(['subsidiary', 'roles'])->find($this->detailUserId);
            $memberTasks    = WbsItem::where('assigned_user_id', $this->detailUserId)
                ->whereIn('project_id', $managedProjectIds)
                ->with('project')
                ->latest()
                ->take(10)
                ->get();
            $memberProjects = Project::whereIn('id', $managedProjectIds)
                ->whereHas('members', fn ($q) => $q->where('users.id', $this->detailUserId))
                ->get();
        }

        return view('livewire.pm-team-members', compact(
            'members',
            'managedProjects',
            'totalMembers',
            'activeMembers',
            'detailMember',
            'memberTasks',
            'memberProjects'
        ));
    }
}
