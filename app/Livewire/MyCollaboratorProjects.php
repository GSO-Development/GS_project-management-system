<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class MyCollaboratorProjects extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public int $perPage = 12;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if (!$user) {
            return view('livewire.my-collaborator-projects', [
                'projects' => collect(),
                'totalCollabCount' => 0,
                'activeCollabCount' => 0,
                'overdueCollabCount' => 0,
                'completedCollabCount' => 0,
            ]);
        }

        // Projects where user is a collaborator (member) but NOT the project leader AND PM has accepted
        $query = Project::query()
            ->where('pm_accepted', true)
            ->whereHas('members', fn($q) => $q
                ->where('users.id', $user->id)
                ->where('role', 'member')
            )
            ->where('project_manager_id', '!=', $user->id)
            ->with(['subsidiary', 'projectManager', 'members']);

        // Summary counts before filters
        $totalCollabCount = (clone $query)->count();
        $activeCollabCount = (clone $query)->where('status', 'in_progress')->count();
        $overdueCollabCount = (clone $query)
            ->where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $completedCollabCount = (clone $query)->where('status', 'completed')->count();

        // Apply search
        if ($this->search) {
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
                ->orWhereHas('projectManager', fn($pq) => $pq->where('name', 'like', "%{$this->search}%"))
            );
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $projects = $query->latest()->paginate($this->perPage);

        return view('livewire.my-collaborator-projects', compact(
            'projects',
            'totalCollabCount',
            'activeCollabCount',
            'overdueCollabCount',
            'completedCollabCount'
        ))->title('Collaborator Projects — GS NexusPM');
    }
}
