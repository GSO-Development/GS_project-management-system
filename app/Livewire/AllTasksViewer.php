<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\WbsItem;
use Livewire\Component;
use Livewire\WithPagination;

class AllTasksViewer extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $statusFilter  = 'all';
    public string $projectFilter = 'all';
    public string $priorityFilter = 'all';
    public string $sortBy        = 'end_date'; // end_date | priority | project | title
    public int    $perPage       = 25;

    public function mount(): void
    {
        // Pre-apply status from URL query param (from dashboard links)
        $s = request()->query('status');
        if ($s && in_array($s, ['completed', 'in_progress', 'on_hold', 'not_started', 'blocked', 'all'])) {
            $this->statusFilter = $s;
        }
    }

    public function updatedSearch()        { $this->resetPage(); }
    public function updatedStatusFilter()  { $this->resetPage(); }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedPriorityFilter(){ $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'projectFilter', 'priorityFilter']);
        $this->resetPage();
    }

    public function render()
    {
        // KPI Counts (across ALL tasks)
        $allTasks = WbsItem::whereHas('project')->get();
        $kpi = [
            'total'       => $allTasks->count(),
            'in_progress' => $allTasks->where('status', WbsStatus::IN_PROGRESS)->count(),
            'completed'   => $allTasks->where('status', WbsStatus::COMPLETED)->count(),
            'on_hold'     => $allTasks->where('status', WbsStatus::ON_HOLD)->count(),
            'not_started' => $allTasks->whereIn('status', [WbsStatus::NOT_STARTED, WbsStatus::BACKLOG])->count(),
            'blocked'     => $allTasks->where('status', WbsStatus::BLOCKED)->count(),
            'overdue'     => $allTasks->filter(fn($t) =>
                                $t->end_date &&
                                $t->end_date->lt(now()->today()) &&
                                !in_array($t->status->value, ['completed', 'cancelled'])
                            )->count(),
        ];

        // Main filtered query
        $query = WbsItem::with(['project.subsidiary', 'assignedUser'])
            ->whereHas('project');

        if ($this->search) {
            $s = "%{$this->search}%";
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', $s)
                  ->orWhere('wbs_code', 'like', $s)
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', $s))
                  ->orWhereHas('assignedUser', fn($uq) => $uq->where('name', 'like', $s));
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->projectFilter !== 'all') {
            $query->where('project_id', $this->projectFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        $query->orderBy(match($this->sortBy) {
            'priority' => 'priority',
            'title'    => 'title',
            'project'  => 'project_id',
            default    => 'end_date',
        }, 'asc')
        ->orderBy('id', 'asc');

        $tasks    = $query->paginate($this->perPage);
        $projects = Project::orderBy('name')->get(['id', 'name']);

        return view('livewire.all-tasks-viewer', compact('tasks', 'projects', 'kpi'));
    }
}
