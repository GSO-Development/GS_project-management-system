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

    // View Mode: 'all' (All Tasks) | 'stuck' (Stuck & Blocked Radar)
    public string $viewMode       = 'all';

    // All Tasks Filters
    public string $search         = '';
    public string $statusFilter   = 'all';
    public string $projectFilter  = 'all';
    public string $priorityFilter = 'all';
    public string $sortBy         = 'end_date'; // end_date | priority | project | title
    public int    $perPage        = 25;

    // Stuck Tasks Filters
    public string $stuckTypeFilter     = 'all'; // 'all', 'blocked', 'on_hold', 'overdue', 'delay_reported'
    public string $stuckProjectFilter  = 'all';
    public string $stuckAssigneeFilter = 'all';
    public string $stuckPriorityFilter = 'all';

    protected $queryString = [
        'viewMode' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        $v = request()->query('view') ?? request()->query('tab') ?? request()->query('viewMode');
        if ($v === 'stuck') {
            $this->viewMode = 'stuck';
        }

        // Pre-apply status from URL query param (from dashboard links)
        $s = request()->query('status');
        if ($s === 'stuck') {
            $this->viewMode = 'stuck';
        } elseif ($s && in_array($s, ['completed', 'in_progress', 'on_hold', 'not_started', 'blocked', 'all'])) {
            $this->statusFilter = $s;
        }
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['all', 'stuck']) ? $mode : 'all';
        $this->resetPage();
    }

    public function setStuckTypeFilter(string $type): void
    {
        $this->stuckTypeFilter = $type;
        $this->resetPage();
    }

    public function resetStuckFilters(): void
    {
        $this->reset(['search', 'stuckTypeFilter', 'stuckProjectFilter', 'stuckAssigneeFilter', 'stuckPriorityFilter']);
        $this->resetPage();
    }

    public function updatedSearch()        { $this->resetPage(); }
    public function updatedStatusFilter()  { $this->resetPage(); }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedPriorityFilter(){ $this->resetPage(); }
    public function updatedStuckTypeFilter(){ $this->resetPage(); }
    public function updatedStuckProjectFilter(){ $this->resetPage(); }
    public function updatedStuckAssigneeFilter(){ $this->resetPage(); }
    public function updatedStuckPriorityFilter(){ $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'projectFilter', 'priorityFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $today = now()->startOfDay();

        // ═══════════════════════════════════════════════════════════════
        // 1. ALL TASKS KPI COUNTS
        // ═══════════════════════════════════════════════════════════════
        $allTasks = WbsItem::whereHas('project', fn($q) => $q->whereNull('deleted_at'))->get();
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

        // ═══════════════════════════════════════════════════════════════
        // 2. STUCK & BLOCKED TASKS BASE QUERY & METRICS
        // ═══════════════════════════════════════════════════════════════
        $stuckBaseQuery = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers.reporter'])
            ->whereHas('project', fn($q) => $q->whereNull('deleted_at'))
            ->where(function($q) use ($today) {
                $q->where('status', WbsStatus::BLOCKED)
                  ->orWhereNotNull('delay_reason')
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('end_date', '<', $today)
                           ->whereNotIn('status', ['completed', 'cancelled']);
                  })
                  ->orWhereHas('blockers', fn($bq) => $bq->where('status', 'open'));
            });

        $totalStuckTasksCount  = (clone $stuckBaseQuery)->count();
        $blockedTasksOnlyCount = (clone $stuckBaseQuery)->where('status', WbsStatus::BLOCKED)->count();
        $overdueTasksOnlyCount = (clone $stuckBaseQuery)->where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count();
        $onHoldTasksCount      = (clone $stuckBaseQuery)->where('status', WbsStatus::ON_HOLD)->count();
        if ($onHoldTasksCount === 0) {
            $onHoldTasksCount = (clone $stuckBaseQuery)->whereHas('project', fn($pq) => $pq->where('status', 'on_hold'))->count();
        }

        $allRawStuck = $stuckBaseQuery->get();
        $stuckProjectsList  = $allRawStuck->pluck('project')->filter()->unique('id')->values();
        $stuckAssigneesList = $allRawStuck->pluck('assignedUser')->filter()->unique('id')->values();

        // Filter Stuck Tasks collection
        $stuckTasks = $allRawStuck->filter(function($task) use ($today) {
            if ($this->stuckTypeFilter === 'blocked' && $task->status?->value !== 'blocked') return false;
            if ($this->stuckTypeFilter === 'overdue' && !($task->end_date && $task->end_date->lt($today) && $task->status?->value !== 'completed')) return false;
            if ($this->stuckTypeFilter === 'delay_reported' && empty($task->delay_reason)) return false;
            if ($this->stuckTypeFilter === 'on_hold' && $task->status?->value !== 'on_hold' && ($task->project?->status?->value ?? '') !== 'on_hold') return false;

            if ($this->stuckProjectFilter !== 'all' && (string)$task->project_id !== $this->stuckProjectFilter) return false;
            if ($this->stuckAssigneeFilter !== 'all' && (string)$task->assigned_user_id !== $this->stuckAssigneeFilter) return false;
            if ($this->stuckPriorityFilter !== 'all' && strtolower($task->priority?->value ?? '') !== strtolower($this->stuckPriorityFilter)) return false;

            if ($this->search) {
                $term = strtolower($this->search);
                $matchTitle = str_contains(strtolower($task->title ?? ''), $term);
                $matchCode = str_contains(strtolower($task->wbs_code ?? ''), $term);
                $matchProject = str_contains(strtolower($task->project?->name ?? ''), $term) || str_contains(strtolower($task->project?->code ?? ''), $term);
                $matchUser = str_contains(strtolower($task->assignedUser?->name ?? ''), $term);
                $matchReason = str_contains(strtolower($task->delay_reason ?? ''), $term);
                if (!$matchTitle && !$matchCode && !$matchProject && !$matchUser && !$matchReason) return false;
            }

            return true;
        });

        // ═══════════════════════════════════════════════════════════════
        // 3. ALL TASKS QUERY
        // ═══════════════════════════════════════════════════════════════
        $query = WbsItem::with(['project.subsidiary', 'assignedUser'])
            ->whereHas('project', fn($q) => $q->whereNull('deleted_at'));

        if ($this->search && $this->viewMode === 'all') {
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

        return view('livewire.all-tasks-viewer', compact(
            'tasks',
            'projects',
            'kpi',
            'totalStuckTasksCount',
            'blockedTasksOnlyCount',
            'overdueTasksOnlyCount',
            'onHoldTasksCount',
            'stuckProjectsList',
            'stuckAssigneesList',
            'stuckTasks'
        ));
    }
}
