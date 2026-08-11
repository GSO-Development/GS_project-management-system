<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\TaskBlocker;
use App\Models\WbsItem;
use App\Services\ProgressCalculationService;
use Livewire\Component;
use Livewire\WithPagination;

class MyTasks extends Component
{
    use WithPagination;

    public string $search = '';
    public string $projectFilter = 'all';
    public string $priorityFilter = 'all';
    public string $statusFilter = 'incomplete'; // default: show incomplete only
    public string $dueDateFilter = 'all';
    public int $perPage = 10;
    public string $saView = 'mine'; // 'mine' or 'team'

    // Blocker Modal
    public bool $showBlockerModal = false;
    public ?int $selectedTaskId = null;
    public string $blockerDescription = '';
    public string $blockerSeverity = 'medium';

    // Delay / Issue Reason Modal
    public bool $showDelayReasonModal = false;
    public ?int $delayTaskId = null;
    public string $delayReasonText = '';

    // Create Sub-Task Modal (Team Member / Participant Sub-Task creation)
    public bool $showSubTaskModal = false;
    public ?int $parentTaskId = null;
    public string $subTaskTitle = '';
    public ?string $subTaskDescription = null;
    public ?string $subTaskStartDate = null;
    public ?string $subTaskEndDate = null;
    public string $subTaskPriority = 'medium';
    public ?float $subTaskEstimatedHours = null;

    public function updatedSearch() { $this->resetPage(); }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedPriorityFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedDueDateFilter() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->reset(['search', 'projectFilter', 'priorityFilter', 'statusFilter', 'dueDateFilter']);
        $this->resetPage();
    }

    public function setDueDateFilter(string $value)
    {
        $this->dueDateFilter = $this->dueDateFilter === $value ? 'all' : $value;
        $this->resetPage();
    }

    public function setStatusFilter(string $value)
    {
        $this->statusFilter = $this->statusFilter === $value ? 'all' : $value;
        $this->resetPage();
    }

    public function openDelayReasonModal(int $taskId)
    {
        $task = WbsItem::findOrFail($taskId);
        $this->delayTaskId = $task->id;
        $this->delayReasonText = $task->delay_reason ?? '';
        $this->showDelayReasonModal = true;
    }

    public function submitDelayReason()
    {
        $this->validate([
            'delayReasonText' => 'required|string|min:5|max:1000',
        ]);

        $task = WbsItem::findOrFail($this->delayTaskId);
        $user = auth()->user();

        $task->delay_reason = $this->delayReasonText;
        $task->delay_reason_by = $user->id;
        $task->delay_reason_at = now();

        if (!in_array($task->status->value, ['completed', 'cancelled'])) {
            $task->status = WbsStatus::BLOCKED;
        }

        $task->save();

        TaskBlocker::create([
            'wbs_item_id' => $task->id,
            'reported_by' => $user->id,
            'description' => 'Uncompleted Task Reason: ' . $this->delayReasonText,
            'severity'    => 'high',
            'status'      => 'open',
        ]);

        $this->showDelayReasonModal = false;
        $this->dispatch('toast', message: 'Task issue/delay reason logged successfully!', type: 'warning');
    }

    public function openBlockerModal(int $taskId)
    {
        $this->selectedTaskId = $taskId;
        $this->blockerDescription = '';
        $this->blockerSeverity = 'medium';
        $this->showBlockerModal = true;
    }

    public function reportBlocker()
    {
        $this->validate([
            'blockerDescription' => 'required|string|min:5',
        ]);

        $task = WbsItem::findOrFail($this->selectedTaskId);

        TaskBlocker::create([
            'wbs_item_id' => $task->id,
            'reported_by' => auth()->id(),
            'description' => $this->blockerDescription,
            'severity' => $this->blockerSeverity,
            'status' => 'open',
        ]);

        $task->status = WbsStatus::BLOCKED;
        $task->save();

        $this->showBlockerModal = false;
        $this->dispatch('toast', message: 'Task blocker reported to Project Manager!', type: 'warning');
    }

    public function openSubTaskModal(int $parentTaskId): void
    {
        $parentTask = WbsItem::findOrFail($parentTaskId);
        $this->parentTaskId           = $parentTask->id;
        $this->subTaskTitle            = '';
        $this->subTaskDescription      = null;
        $this->subTaskStartDate        = $parentTask->start_date ? $parentTask->start_date->format('Y-m-d') : now()->toDateString();
        $this->subTaskEndDate          = $parentTask->end_date ? $parentTask->end_date->format('Y-m-d') : now()->addDays(2)->toDateString();
        $this->subTaskPriority         = 'medium';
        $this->subTaskEstimatedHours   = null;
        $this->showSubTaskModal        = true;
    }

    public function createSubTask(): void
    {
        $this->validate([
            'subTaskTitle'          => 'required|string|max:255',
            'subTaskDescription'    => 'nullable|string',
            'subTaskStartDate'      => 'nullable|date',
            'subTaskEndDate'        => 'nullable|date|after_or_equal:subTaskStartDate',
            'subTaskPriority'       => 'required|string',
            'subTaskEstimatedHours' => 'nullable|numeric|min:0',
        ]);

        $parentTask = WbsItem::findOrFail($this->parentTaskId);
        $user       = auth()->user();

        // Security check: Only assigned user, PM, or Super Admin can create subtasks
        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $parentTask->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only add sub-tasks to tasks assigned to you.', type: 'error');
            return;
        }

        $childCount = WbsItem::where('parent_id', $parentTask->id)->count();
        $wbsCode    = ($parentTask->wbs_code ?? '1') . '.' . ($childCount + 1);

        $subTask = WbsItem::create([
            'project_id'       => $parentTask->project_id,
            'parent_id'        => $parentTask->id,
            'wbs_code'         => $wbsCode,
            'item_type'        => \App\Enums\ItemType::SUBTASK,
            'title'            => trim($this->subTaskTitle),
            'description'      => $this->subTaskDescription,
            'assigned_user_id' => $user->id,
            'start_date'       => $this->subTaskStartDate,
            'end_date'         => $this->subTaskEndDate,
            'status'           => WbsStatus::NOT_STARTED,
            'priority'         => Priority::tryFrom($this->subTaskPriority) ?? Priority::MEDIUM,
            'progress'         => 0,
            'estimated_hours'  => $this->subTaskEstimatedHours,
            'weight'           => 0.5,
            'created_by'       => $user->id,
        ]);

        // Recalculate parent progress
        (new ProgressCalculationService())->updateItemProgress($parentTask);

        $this->showSubTaskModal = false;
        $this->dispatch('toast', message: "Sub-task '{$subTask->title}' created successfully!", type: 'success');
    }

    public function updateStatus(int $taskId, string $status)
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only update status for tasks assigned to you.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) return;

        $task->status = $statusEnum;

        if ($status === 'completed') {
            $task->progress = 100;
        } elseif ($status === 'in_progress' && $task->progress == 0) {
            $task->progress = 10;
        } elseif ($status === 'not_started') {
            $task->progress = 0;
        }

        $task->save();

        (new ProgressCalculationService())->updateItemProgress($task);

        $this->dispatch('toast', message: 'Task status updated to ' . $statusEnum->label(), type: 'success');
    }

    public function updateProgress(int $taskId, int $progress)
    {
        $task = WbsItem::findOrFail($taskId);
        $task->progress = max(0, min(100, $progress));

        if ($progress === 100) {
            $task->status = WbsStatus::COMPLETED;
        } elseif ($progress > 0 && $task->status->value === 'not_started') {
            $task->status = WbsStatus::IN_PROGRESS;
        }

        $task->save();

        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: 'Task progress saved.', type: 'success');
    }

    /**
     * Inline reason submission from the Daily Quick Update panel.
     * Saves delay_reason, updates status, and notifies via toast.
     * Visibility rules:
     *   - PM logs reason  → Super Admin sees it
     *   - Team logs reason → PM + Super Admin see it
     */
    public function submitDelayReasonInline(int $taskId, string $reason, string $status = 'on_hold'): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        // Only assigned user, PM of the project, or SA can update
        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'Access denied.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) $statusEnum = WbsStatus::ON_HOLD;

        $task->delay_reason      = trim($reason) ?: null;
        $task->delay_reason_at   = $reason ? now() : null;
        $task->delay_reporter_id = $reason ? $user->id : null;
        $task->status            = $statusEnum;
        $task->save();

        (new ProgressCalculationService())->updateItemProgress($task);

        // Build visibility message
        $whoSees = $user->hasRole('project_manager')
            ? 'Super Admin'
            : 'Project Manager & Super Admin';

        $this->dispatch('toast',
            message: "Status set to {$statusEnum->label()}. Reason escalated to {$whoSees}.",
            type: 'warning'
        );
    }

    public function render()
    {
        $user = auth()->user();
        $today = now()->today()->toDateString();

        if ($user->hasRole('super_admin') && $this->saView === 'team') {
            $allUserTasks = WbsItem::whereHas('project');
        } else {
            $allUserTasks = WbsItem::whereHas('project')->where('assigned_user_id', $user->id);
        }

        $totalCount = (clone $allUserTasks)->count();
        $inProgressCount = (clone $allUserTasks)->where('status', 'in_progress')->count();
        $dueTodayCount = (clone $allUserTasks)->where(function($q) use ($today) {
            $q->whereDate('end_date', $today)
              ->orWhereDate('start_date', $today)
              ->orWhere(function($sq) use ($today) {
                  $sq->whereDate('start_date', '<=', $today)
                     ->whereDate('end_date', '>=', $today);
              });
        })->count();
        $overdueCount = (clone $allUserTasks)->where('end_date', '<', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $completedCount = (clone $allUserTasks)->where('status', 'completed')->count();
        $notStartedCount = (clone $allUserTasks)->where('status', 'not_started')->count();
        $blockedCount = (clone $allUserTasks)->where('status', 'blocked')->count();

        // Query for task list table (only include tasks from active projects)
        $query = WbsItem::whereHas('project')->with(['project', 'assignedUser', 'delayReporter', 'blockers', 'children.assignedUser']);

        if ($user->hasRole('super_admin') && $this->saView === 'team') {
            // Super Admin Team view sees all active tasks
        } else {
            // Only tasks assigned directly to the current logged in user (PM or Team Member)
            $query->where('assigned_user_id', $user->id);
        }



        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->projectFilter !== 'all') {
            $query->where('project_id', $this->projectFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->statusFilter === 'incomplete') {
            $query->whereNotIn('status', ['completed', 'cancelled']);
        } elseif ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dueDateFilter === 'today') {
            $query->where(function($q) use ($today) {
                $q->whereDate('end_date', $today)
                  ->orWhereDate('start_date', $today)
                  ->orWhere(function($sq) use ($today) {
                      $sq->whereDate('start_date', '<=', $today)
                         ->whereDate('end_date', '>=', $today);
                  });
            });
        } elseif ($this->dueDateFilter === 'overdue') {
            $query->where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled']);
        } elseif ($this->dueDateFilter === 'this_week') {
            $query->whereBetween('end_date', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $tasks = $query
            ->orderBy('project_id', 'asc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('wbs_code', 'asc')
            ->paginate($this->perPage);

        // Sidebar Widgets Data
        $todaySchedule = (clone $allUserTasks)
            ->where(function($q) use ($today) {
                $q->whereDate('end_date', $today)
                  ->orWhereDate('start_date', $today)
                  ->orWhere(function($sq) use ($today) {
                      $sq->whereDate('start_date', '<=', $today)
                         ->whereDate('end_date', '>=', $today);
                  });
            })
            ->whereNotIn('status', ['cancelled'])
            ->take(6)
            ->get();

        $upcomingDeadlines = (clone $allUserTasks)
            ->where('end_date', '>=', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date', 'asc')
            ->take(3)
            ->get();

        if ($user->hasRole('super_admin')) {
            $myProjects = Project::all();
        } else {
            $myProjects = Project::whereHas('members', fn($q) => $q->where('user_id', $user->id))
                ->orWhere('project_manager_id', $user->id)
                ->get();
        }

        // Team Daily Monitoring (Super Admin only)
        $teamDailyStats = collect();
        if ($user->hasRole('super_admin')) {
            $teamDailyStats = WbsItem::with(['assignedUser', 'project'])
                ->whereNotNull('assigned_user_id')
                ->whereNotIn('status', ['cancelled'])
                ->where(function($q) use ($today) {
                    $q->whereDate('end_date', '>=', $today)
                      ->orWhereDate('start_date', '<=', $today);
                })
                ->get()
                ->groupBy('assigned_user_id')
                ->map(function($items) use ($today) {
                    $user = $items->first()->assignedUser;
                    if (!$user) return null;
                    $total = $items->count();
                    $done  = $items->where('status', 'completed')->count();
                    $inProg = $items->where('status', 'in_progress')->count();
                    $blocked = $items->where('status', 'blocked')->count();
                    $notStarted = $items->whereIn('status', ['not_started', 'backlog'])->count();
                    $overdue = $items->filter(fn($t) => $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status->value, ['completed','cancelled']))->count();
                    return [
                        'user'       => $user,
                        'total'      => $total,
                        'done'       => $done,
                        'in_progress'=> $inProg,
                        'blocked'    => $blocked,
                        'not_started'=> $notStarted,
                        'overdue'    => $overdue,
                        'pct'        => $total > 0 ? round(($done / $total) * 100) : 0,
                        'tasks'      => $items->take(3),
                    ];
                })
                ->filter()
                ->sortByDesc('overdue');
        }

        // Logged Task Issues & Delays for Super Admin & PM monitoring
        $loggedIssues = WbsItem::whereHas('project')
            ->whereNotNull('delay_reason')
            ->with(['assignedUser', 'project', 'delayReporter'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('livewire.my-tasks', compact(
            'tasks',
            'myProjects',
            'totalCount',
            'inProgressCount',
            'dueTodayCount',
            'overdueCount',
            'completedCount',
            'notStartedCount',
            'blockedCount',
            'todaySchedule',
            'upcomingDeadlines',
            'teamDailyStats',
            'loggedIssues'
        ));
    }
}

