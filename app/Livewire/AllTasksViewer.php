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

    private const WORKFLOW_STATUS_GROUPS = [
        'not_started' => ['backlog', 'not_started'],
        'in_progress' => ['in_progress', 'under_review', 'at_risk'],
        'completed' => ['completed'],
        'on_hold' => ['on_hold', 'blocked'],
    ];

    // View Mode: 'all' (All Tasks) | 'stuck' (Stuck & Blocked Radar)
    public string $viewMode       = 'all';

    // Collapsible Filters State
    public bool   $showFilters    = false;

    // All Tasks Filters
    public string $search         = '';
    public string $statusFilter   = 'all';
    public string $dueFilter      = 'all';
    public string $riskFilter     = 'all';
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
        } elseif ($s === 'blocked') {
            $this->statusFilter = 'on_hold';
        } elseif ($s && in_array($s, ['completed', 'in_progress', 'on_hold', 'not_started', 'all'], true)) {
            $this->statusFilter = $s;
        }

        if ($this->hasActiveFilters()) {
            $this->showFilters = true;
        }
    }

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function hasActiveFilters(): bool
    {
        if ($this->viewMode === 'all') {
            return ($this->search !== '' || $this->statusFilter !== 'all' || $this->dueFilter !== 'all' || $this->riskFilter !== 'all' || $this->projectFilter !== 'all' || $this->priorityFilter !== 'all' || $this->sortBy !== 'end_date');
        } else {
            return ($this->search !== '' || $this->stuckProjectFilter !== 'all' || $this->projectFilter !== 'all' || $this->stuckAssigneeFilter !== 'all' || $this->stuckPriorityFilter !== 'all' || $this->stuckTypeFilter !== 'all');
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
        $this->reset(['search', 'stuckTypeFilter', 'stuckProjectFilter', 'stuckAssigneeFilter', 'stuckPriorityFilter', 'projectFilter']);
        $this->resetPage();
    }

    public function updatedSearch()        { $this->resetPage(); }
    public function updatedStatusFilter(string $status): void
    {
        if (!in_array($status, ['all', ...array_keys(self::WORKFLOW_STATUS_GROUPS)], true)) {
            $this->statusFilter = 'all';
        }

        $this->dueFilter = 'all';
        $this->riskFilter = 'all';
        $this->resetPage();
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = in_array($status, array_keys(self::WORKFLOW_STATUS_GROUPS), true) ? $status : 'all';
        $this->dueFilter = 'all';
        $this->riskFilter = 'all';
        $this->resetPage();
    }

    public function setOverdueFilter(): void
    {
        $this->statusFilter = 'all';
        $this->dueFilter = 'overdue';
        $this->riskFilter = 'all';
        $this->resetPage();
    }

    public function setAtRiskFilter(): void
    {
        $this->statusFilter = 'all';
        $this->dueFilter = 'all';
        $this->riskFilter = 'at_risk';
        $this->resetPage();
    }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedPriorityFilter(){ $this->resetPage(); }
    public function updatedStuckTypeFilter(){ $this->resetPage(); }
    public function updatedStuckProjectFilter(){ $this->resetPage(); }
    public function updatedStuckAssigneeFilter(){ $this->resetPage(); }
    public function updatedStuckPriorityFilter(){ $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'dueFilter', 'riskFilter', 'projectFilter', 'priorityFilter', 'sortBy']);
        $this->resetPage();
    }

    public function render()
    {
        $today = now()->startOfDay();

        // ═══════════════════════════════════════════════════════════════
        // 1. ALL TASKS KPI COUNTS
        // ═══════════════════════════════════════════════════════════════
        // KPI cards and table rows share the selected project scope.
        $taskBaseQuery = WbsItem::whereHas('project', fn ($q) => $q->whereNull('deleted_at'))
            ->whereNotIn('status', ['cancelled'])
            ->when($this->projectFilter !== 'all', fn ($q) => $q->where('project_id', $this->projectFilter));
        $allTasks = (clone $taskBaseQuery)->get();
        $statusValue = fn (WbsItem $task): string => $task->status instanceof WbsStatus
            ? $task->status->value
            : (string) $task->status;
        $kpi = [
            'total'       => $allTasks->count(),
            'not_started' => $allTasks->filter(fn (WbsItem $task) => in_array($statusValue($task), self::WORKFLOW_STATUS_GROUPS['not_started'], true))->count(),
            'in_progress' => $allTasks->filter(fn (WbsItem $task) => in_array($statusValue($task), self::WORKFLOW_STATUS_GROUPS['in_progress'], true))->count(),
            'completed'   => $allTasks->filter(fn (WbsItem $task) => in_array($statusValue($task), self::WORKFLOW_STATUS_GROUPS['completed'], true))->count(),
            'on_hold'     => $allTasks->filter(fn (WbsItem $task) => in_array($statusValue($task), self::WORKFLOW_STATUS_GROUPS['on_hold'], true))->count(),
            'at_risk'     => (clone $taskBaseQuery)
                                ->where(function ($q) {
                                    $q->where('status', 'at_risk')
                                        ->orWhereHas('risks', fn ($riskQuery) => $riskQuery->whereIn('status', ['open', 'monitoring']));
                                })
                                ->count(),
            'overdue'     => $allTasks->filter(fn($t) =>
                                $t->end_date &&
                                $t->end_date->lt(now()->today()) &&
                                !in_array($t->status->value, ['completed', 'cancelled'])
                            )->count(),
        ];

        // ═══════════════════════════════════════════════════════════════
        // 2. STUCK & BLOCKED TASKS BASE QUERY & METRICS
        // ═══════════════════════════════════════════════════════════════
        $stuckProjectId = $this->stuckProjectFilter !== 'all' ? $this->stuckProjectFilter : $this->projectFilter;
        $stuckBaseQuery = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers.reporter'])
            ->whereHas('project', fn($q) => $q->whereNull('deleted_at'))
            ->when($stuckProjectId !== 'all', fn ($q) => $q->where('project_id', $stuckProjectId))
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
        $query = (clone $taskBaseQuery)->with(['project.subsidiary', 'assignedUser']);

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
            $statuses = self::WORKFLOW_STATUS_GROUPS[$this->statusFilter] ?? [];
            $query->whereIn('status', $statuses);
        }

        if ($this->dueFilter === 'overdue') {
            $query->where('end_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled']);
        }

        if ($this->riskFilter === 'at_risk') {
            $query->where(function ($q) {
                $q->where('status', 'at_risk')
                    ->orWhereHas('risks', fn ($riskQuery) => $riskQuery->whereIn('status', ['open', 'monitoring']));
            });
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
        ))->with([
            'showFilters' => $this->showFilters,
            'hasActiveFilters' => $this->hasActiveFilters(),
        ]);
    }
}
