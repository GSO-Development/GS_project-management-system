<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\TaskBlocker;
use App\Models\WbsItem;
use App\Notifications\TaskCompletedNotification;
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
    public string $viewMode = 'kanban'; // 'kanban' or 'table'
    public string $kanbanMode = 'time'; // 'time' (Due, Today, Tomorrow, Next Week, Later, Completed) or 'status' (To Do, In Progress, In Review, Completed, Blocked)

    // Blocker Modal
    public bool $showBlockerModal = false;
    public ?int $selectedTaskId = null;
    public string $blockerDescription = '';
    public string $blockerSeverity = 'medium';

    // Delay / Issue Reason Modal
    public bool $showDelayReasonModal = false;
    public ?int $delayTaskId = null;
    public string $delayReasonText = '';

    // Create Task Modal (from Kanban + Add Task)
    public bool $showCreateTaskModal = false;
    public ?int $createTaskProjectId = null;
    public string $createTaskTitle = '';
    public string $createTaskStatus = 'not_started';
    public string $createTaskPriority = 'medium';
    public ?string $createTaskStartDate = null;
    public ?string $createTaskEndDate = null;
    public ?float $createTaskEstimatedHours = null;

    // Checkbox selections
    public array $selectedTaskIds = [];
    public bool $selectAll = false;

    // Create Sub-Task Modal (Team Member / Participant Sub-Task creation)
    public bool $showSubTaskModal = false;
    public ?int $parentTaskId = null;
    public string $subTaskTitle = '';
    public ?string $subTaskDescription = null;
    public ?string $subTaskStartDate = null;
    public ?string $subTaskEndDate = null;
    public string $subTaskPriority = 'medium';
    public ?float $subTaskEstimatedHours = null;

    // Task Detail Drawer / Quick Modal
    public bool $showTaskDetailModal = false;
    public ?int $detailTaskId = null;
    public string $newSubtaskTitle = '';

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
        if (!$user->hasRole('super_admin') && $parentTask->project->project_manager_id !== $user->id && $parentTask->assigned_user_id !== $user->id) {
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

        if (!$user->hasRole('super_admin') && $task->project->project_manager_id !== $user->id && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only update status for tasks assigned to you.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) return;

        $task->status = $statusEnum;

        if ($status === 'completed') {
            $task->progress = 100;
            $this->notifyTaskCompletion($task);
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
            $this->notifyTaskCompletion($task);
        } elseif ($progress > 0 && $task->status->value === 'not_started') {
            $task->status = WbsStatus::IN_PROGRESS;
        }

        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: 'Task progress saved.', type: 'success');
    }

    public function toggleTaskComplete(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        if ($task->status === WbsStatus::COMPLETED) {
            $task->status = WbsStatus::IN_PROGRESS;
            $task->progress = 50;
            $this->dispatch('toast', message: 'Task marked as In Progress.', type: 'info');
        } else {
            $task->status = WbsStatus::COMPLETED;
            $task->progress = 100;
            $this->notifyTaskCompletion($task);
            $this->dispatch('toast', message: 'Task completed! 🎉', type: 'success');
        }
        $task->save();
        (new ProgressCalculationService())->updateItemProgress($task);
    }

    protected function notifyTaskCompletion(WbsItem $task): void
    {
        try {
            $user = auth()->user();
            $pm = $task->project->projectManager ?? null;
            if ($pm && $pm->id !== $user->id) {
                $pm->notify(new TaskCompletedNotification(
                    taskTitle: $task->title,
                    projectName: $task->project->name ?? 'Project',
                    completedByName: $user->name,
                    url: route('projects.show', $task->project_id)
                ));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('TaskCompletedNotification error: ' . $e->getMessage());
        }
    }

    public function quickMoveTask(int $taskId, string $destination): void
    {
        $task = WbsItem::findOrFail($taskId);
        switch ($destination) {
            case 'today':
                $task->end_date = now()->toDateString();
                if ($task->status === WbsStatus::NOT_STARTED || $task->status === WbsStatus::BLOCKED) {
                    $task->status = WbsStatus::IN_PROGRESS;
                }
                $this->dispatch('toast', message: 'Moved to Today’s focus', type: 'success');
                break;
            case 'tomorrow':
                $task->end_date = now()->addDay()->toDateString();
                $this->dispatch('toast', message: 'Rescheduled for Tomorrow', type: 'success');
                break;
            case 'next_week':
                $task->end_date = now()->addDays(7)->toDateString();
                $this->dispatch('toast', message: 'Moved to Next Week', type: 'success');
                break;
            case 'in_progress':
                $task->status = WbsStatus::IN_PROGRESS;
                if ($task->progress == 0) $task->progress = 10;
                $this->dispatch('toast', message: 'Status updated to In Progress', type: 'success');
                break;
            case 'under_review':
                $task->status = WbsStatus::UNDER_REVIEW;
                $this->dispatch('toast', message: 'Submitted for Review', type: 'info');
                break;
            case 'completed':
                $this->toggleTaskComplete($taskId);
                return;
        }
        $task->save();
        (new ProgressCalculationService())->updateItemProgress($task);
    }

    public function openTaskDetail(int $taskId): void
    {
        $this->detailTaskId = $taskId;
        $this->showTaskDetailModal = true;
    }

    public function addQuickSubtask(): void
    {
        if (!$this->detailTaskId || empty(trim($this->newSubtaskTitle))) return;
        $parent = WbsItem::findOrFail($this->detailTaskId);
        $subCount = WbsItem::where('parent_id', $parent->id)->count();

        WbsItem::create([
            'project_id' => $parent->project_id,
            'parent_id' => $parent->id,
            'wbs_code' => $parent->wbs_code . '.' . ($subCount + 1),
            'item_type' => \App\Enums\ItemType::SUBTASK,
            'title' => trim($this->newSubtaskTitle),
            'assigned_user_id' => auth()->id(),
            'start_date' => now()->toDateString(),
            'end_date' => $parent->end_date ?? now()->addDays(3)->toDateString(),
            'status' => WbsStatus::NOT_STARTED,
            'priority' => $parent->priority,
            'progress' => 0,
            'weight' => 1.0,
        ]);

        $this->newSubtaskTitle = '';
        (new ProgressCalculationService())->updateItemProgress($parent);
        $this->dispatch('toast', message: 'Sub-task added successfully', type: 'success');
    }

    public function toggleSubtask(int $subtaskId): void
    {
        $sub = WbsItem::findOrFail($subtaskId);
        if ($sub->status === WbsStatus::COMPLETED) {
            $sub->status = WbsStatus::IN_PROGRESS;
            $sub->progress = 0;
        } else {
            $sub->status = WbsStatus::COMPLETED;
            $sub->progress = 100;
        }
        $sub->save();

        if ($sub->parent_id) {
            $parent = WbsItem::find($sub->parent_id);
            if ($parent) {
                (new ProgressCalculationService())->updateItemProgress($parent);
            }
        }
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

    public function setKanbanMode(string $mode): void
    {
        $this->kanbanMode = $mode;
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $user = auth()->user();
            $this->selectedTaskIds = WbsItem::whereHas('project')
                ->where('assigned_user_id', $user->id)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedTaskIds = [];
        }
    }

    public function openAddTaskModal(string $target = 'not_started'): void
    {
        $this->createTaskTitle = '';
        $this->createTaskStartDate = now()->toDateString();
        $this->createTaskPriority = 'medium';
        $this->createTaskEstimatedHours = null;

        switch ($target) {
            case 'today':
                $this->createTaskStatus = 'in_progress';
                $this->createTaskStartDate = now()->toDateString();
                $this->createTaskEndDate = now()->toDateString();
                break;
            case 'tomorrow':
                $this->createTaskStatus = 'in_progress';
                $this->createTaskStartDate = now()->toDateString();
                $this->createTaskEndDate = now()->addDay()->toDateString();
                break;
            case 'next_week':
                $this->createTaskStatus = 'in_progress';
                $this->createTaskStartDate = now()->addDays(2)->toDateString();
                $this->createTaskEndDate = now()->addDays(7)->toDateString();
                break;
            case 'later':
                $this->createTaskStatus = 'not_started';
                $this->createTaskStartDate = now()->addDays(8)->toDateString();
                $this->createTaskEndDate = now()->addDays(14)->toDateString();
                break;
            case 'completed':
                $this->createTaskStatus = 'completed';
                $this->createTaskStartDate = now()->subDays(2)->toDateString();
                $this->createTaskEndDate = now()->toDateString();
                break;
            case 'blocked':
            case 'overdue':
                $this->createTaskStatus = 'blocked';
                $this->createTaskStartDate = now()->toDateString();
                $this->createTaskEndDate = now()->addDays(2)->toDateString();
                break;
            default:
                $this->createTaskStatus = $target;
                $this->createTaskEndDate = now()->addDays(3)->toDateString();
                break;
        }
        
        $firstProject = Project::where(function($q) {
                $q->where('project_manager_id', auth()->id())
                  ->orWhere(function($sub) {
                      $sub->where('pm_accepted', true)
                          ->whereHas('members', fn($m) => $m->where('users.id', auth()->id()));
                  });
            })
            ->first();
        $this->createTaskProjectId = $firstProject?->id;
        $this->showCreateTaskModal = true;
    }

    public function saveNewTask(): void
    {
        $this->validate([
            'createTaskProjectId' => 'required|exists:projects,id',
            'createTaskTitle' => 'required|string|max:255',
            'createTaskStartDate' => 'nullable|date',
            'createTaskEndDate' => 'nullable|date|after_or_equal:createTaskStartDate',
            'createTaskPriority' => 'required|string',
            'createTaskStatus' => 'required|string',
        ]);

        $user = auth()->user();
        $project = Project::findOrFail($this->createTaskProjectId);
        $childCount = WbsItem::where('project_id', $project->id)->whereNull('parent_id')->count();

        $statusEnum = WbsStatus::tryFrom($this->createTaskStatus) ?? WbsStatus::NOT_STARTED;
        if ($this->createTaskStartDate && $this->createTaskStartDate <= now()->toDateString() && $statusEnum === WbsStatus::NOT_STARTED) {
            $statusEnum = WbsStatus::IN_PROGRESS;
        }

        $task = WbsItem::create([
            'project_id' => $project->id,
            'parent_id' => null,
            'wbs_code' => (string) ($childCount + 1),
            'item_type' => \App\Enums\ItemType::TASK,
            'title' => trim($this->createTaskTitle),
            'assigned_user_id' => $user->id,
            'start_date' => $this->createTaskStartDate,
            'end_date' => $this->createTaskEndDate,
            'status' => $statusEnum,
            'priority' => Priority::tryFrom($this->createTaskPriority) ?? Priority::MEDIUM,
            'progress' => $statusEnum === WbsStatus::COMPLETED ? 100 : ($statusEnum === WbsStatus::IN_PROGRESS ? 10 : 0),
            'estimated_hours' => $this->createTaskEstimatedHours,
            'weight' => 1.0,
            'created_by' => $user->id,
        ]);

        $this->showCreateTaskModal = false;
        $this->dispatch('toast', message: "Task '{$task->title}' created successfully!", type: 'success');
    }

    public function deleteTask(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$user->hasRole('super_admin') && $task->project->project_manager_id !== $user->id && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'Unauthorized to delete this task.', type: 'error');
            return;
        }

        $task->delete();
        $this->dispatch('toast', message: "Task '{$task->title}' deleted successfully!", type: 'success');
    }

    public function markTaskCompleted(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $task->status = WbsStatus::COMPLETED;
        $task->progress = 100;
        $task->save();

        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: "🎉 Task '{$task->title}' marked 100% Completed!", type: 'success');
    }

    public function rescheduleTask(int $taskId, string $target): void
    {
        $task = WbsItem::findOrFail($taskId);
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $nextWeek = now()->addDays(7)->toDateString();

        switch ($target) {
            case 'today':
                $task->end_date = $today;
                break;
            case 'tomorrow':
                $task->end_date = $tomorrow;
                break;
            case 'next_week':
                $task->end_date = $nextWeek;
                break;
        }

        if ($task->status->value === 'blocked') {
            $task->status = WbsStatus::IN_PROGRESS;
        }

        $task->save();
        $this->dispatch('toast', message: "📅 Task '{$task->title}' rescheduled to " . ucfirst(str_replace('_', ' ', $target)), type: 'info');
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
        if (!$user->hasRole('super_admin') && $task->project->project_manager_id !== $user->id && $task->assigned_user_id !== $user->id) {
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
        $whoSees = ($task->project->project_manager_id === $user->id)
            ? 'PMO Admin'
            : 'Project Manager & PMO Admin';

        $this->dispatch('toast',
            message: "Status set to {$statusEnum->label()}. Reason escalated to {$whoSees}.",
            type: 'warning'
        );
    }

    public function render()
    {
        $user = auth()->user();
        $today = now()->today()->toDateString();

        // Automatically transition tasks whose start_date has arrived to in_progress
        WbsItem::autoStartDueTasks();

        if ($user->hasRole('super_admin') && $this->saView === 'team') {
            $allUserTasks = WbsItem::whereHas('project');
        } else {
            $allUserTasks = WbsItem::whereHas('project', function($pq) use ($user) {
                $pq->where('pm_accepted', true)
                   ->orWhere('project_manager_id', $user->id);
            })->where('assigned_user_id', $user->id);
        }

        $totalCount = (clone $allUserTasks)->count();
        $leadTasksCount = (clone $allUserTasks)->whereHas('project', fn($p) => $p->where('project_manager_id', $user->id))->count();
        $collabTasksCount = (clone $allUserTasks)->whereHas('project', fn($p) => $p->where('project_manager_id', '!=', $user->id))->count();

        $inProgressCount = (clone $allUserTasks)->where('status', 'in_progress')->count();
        $dueTodayCount = (clone $allUserTasks)->where(function($q) use ($today) {
            $q->whereDate('end_date', $today)
              ->orWhere(function($sq) use ($today) {
                  $sq->whereNull('end_date')->whereDate('start_date', $today);
              });
        })->count();
        $overdueCount = (clone $allUserTasks)->where('end_date', '<', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $completedCount = (clone $allUserTasks)->where('status', 'completed')->count();
        $notStartedCount = (clone $allUserTasks)->where('status', 'not_started')->count();
        $blockedCount = (clone $allUserTasks)->where('status', 'blocked')->count();

        // Assigned Projects for clean dropdown selector
        if ($user->hasRole('super_admin')) {
            $myProjects = Project::with('subsidiary')->orderBy('name')->get();
        } else {
            $myProjects = Project::where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhere(function($sub) use ($user) {
                          $sub->where('pm_accepted', true)
                              ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                      });
                })
                ->with('subsidiary')
                ->orderBy('name')
                ->get();
        }

        // Query for task list table (only include tasks from active accepted projects)
        $query = WbsItem::whereHas('project', function($pq) use ($user) {
                if (!$user->hasRole('super_admin')) {
                    $pq->where('pm_accepted', true)
                       ->orWhere('project_manager_id', $user->id);
                }
            })
            ->with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'delayReporter', 'blockers', 'children.assignedUser']);

        if ($user->hasRole('super_admin') && $this->saView === 'team') {
            // Super Admin Team view sees all active tasks
        } else {
            // Only tasks assigned directly to the current logged in user (PM or Team Member)
            $query->where('assigned_user_id', $user->id);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('wbs_code', 'like', "%{$this->search}%")
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"));
            });
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
                  ->orWhere(function($sq) use ($today) {
                      $sq->whereNull('end_date')->whereDate('start_date', $today);
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
                  ->orWhere(function($sq) use ($today) {
                      $sq->whereNull('end_date')->whereDate('start_date', $today);
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
        $loggedIssues = WbsItem::whereHas('project', function($pq) use ($user) {
                if (!$user->hasRole('super_admin')) {
                    $pq->where('pm_accepted', true)
                       ->orWhere('project_manager_id', $user->id);
                }
            })
            ->whereNotNull('delay_reason')
            ->with(['assignedUser', 'project', 'delayReporter'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // ══════════════════════════════════════════════════════════
        // 📊 TIME-HORIZON KANBAN BOARD DATA
        // ══════════════════════════════════════════════════════════
        $todayDate = now()->startOfDay();
        $tomorrowDate = now()->addDay()->startOfDay();
        $nextWeekEndDate = now()->addDays(7)->endOfDay();

        $kanbanBase = WbsItem::whereHas('project', function($pq) use ($user) {
                if (!$user->hasRole('super_admin')) {
                    $pq->where('pm_accepted', true)
                       ->orWhere('project_manager_id', $user->id);
                }
            })
            ->with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'delayReporter', 'blockers', 'children.assignedUser']);

        if ($user->hasRole('super_admin') && $this->saView === 'team') {
            // Team mode
        } else {
            $kanbanBase->where('assigned_user_id', $user->id);
        }

        if ($this->search) {
            $kanbanBase->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('wbs_code', 'like', "%{$this->search}%")
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"));
            });
        }
        if ($this->projectFilter !== 'all') {
            $kanbanBase->where('project_id', $this->projectFilter);
        }
        if ($this->priorityFilter !== 'all') {
            $kanbanBase->where('priority', $this->priorityFilter);
        }

        $allKanbanTasks = $kanbanBase->orderBy('priority', 'desc')->orderBy('end_date', 'asc')->get();

        // ══════════════════════════════════════════════════════════
        // 📊 5-STATUS KANBAN BOARD DATA (Matching Design)
        // ══════════════════════════════════════════════════════════
        // 1. To Do (Not Started, Backlog)
        $kanbanToDo = $allKanbanTasks->filter(function($t) {
            return in_array($t->status->value, ['not_started', 'backlog']) && $t->status->value !== 'blocked';
        });

        // 2. In Progress
        $kanbanInProgress = $allKanbanTasks->filter(function($t) {
            return in_array($t->status->value, ['in_progress', 'on_hold']) && $t->status->value !== 'blocked';
        });

        // 3. In Review (Under Review)
        $kanbanInReview = $allKanbanTasks->filter(function($t) {
            return $t->status->value === 'under_review' && $t->status->value !== 'blocked';
        });

        // 4. Completed
        $kanbanCompleted = $allKanbanTasks->filter(function($t) {
            return $t->status->value === 'completed';
        });

        // 5. Blocked
        $kanbanBlocked = $allKanbanTasks->filter(function($t) {
            return $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0);
        });

        // ══════════════════════════════════════════════════════════
        // 📊 TIME-HORIZON KANBAN COLLECTIONS (Schedule-based)
        // ══════════════════════════════════════════════════════════
        $todayDate = now()->startOfDay();
        $tomorrowDate = now()->addDay()->startOfDay();
        $nextWeekEndDate = now()->addDays(7)->endOfDay();

        // 1. Overdue & Blocked (Due Tasks needing immediate attention)
        $kanbanOverdue = $allKanbanTasks->filter(function($t) use ($todayDate) {
            if ($t->status->value === 'completed' || $t->status->value === 'cancelled') return false;
            $isOverdue = $t->end_date && $t->end_date->lt($todayDate);
            $isBlocked = $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0);
            return $isOverdue || $isBlocked;
        });

        // 2. Today's Tasks (Due Today)
        $kanbanToday = $allKanbanTasks->filter(function($t) use ($todayDate) {
            if ($t->status->value === 'completed' || $t->status->value === 'cancelled' || $t->status->value === 'blocked') return false;
            if ($t->end_date && $t->end_date->lt($todayDate)) return false;
            if ($t->end_date && $t->end_date->isSameDay($todayDate)) return true;
            if (!$t->end_date && $t->start_date && $t->start_date->isSameDay($todayDate)) return true;
            return false;
        });

        // 3. Tomorrow's Tasks (Due Tomorrow)
        $kanbanTomorrow = $allKanbanTasks->filter(function($t) use ($tomorrowDate) {
            if ($t->status->value === 'completed' || $t->status->value === 'cancelled' || $t->status->value === 'blocked') return false;
            return $t->end_date && $t->end_date->isSameDay($tomorrowDate);
        });

        // 4. Next Week's Tasks (Next 7 Days)
        $kanbanNextWeek = $allKanbanTasks->filter(function($t) use ($tomorrowDate, $nextWeekEndDate) {
            if ($t->status->value === 'completed' || $t->status->value === 'cancelled' || $t->status->value === 'blocked') return false;
            return $t->end_date && $t->end_date->gt($tomorrowDate) && $t->end_date->lte($nextWeekEndDate);
        });

        // 5. Later & Backlog (Future / >7 days or unscheduled)
        $kanbanLater = $allKanbanTasks->filter(function($t) use ($todayDate, $nextWeekEndDate) {
            if ($t->status->value === 'completed' || $t->status->value === 'cancelled' || $t->status->value === 'blocked') return false;
            if ($t->end_date && $t->end_date->lt($todayDate)) return false;
            if ($t->end_date && $t->end_date->isSameDay($todayDate)) return false;
            if ($t->end_date && $t->end_date->lte($nextWeekEndDate)) return false;
            return true;
        });

        $detailTask = null;
        if ($this->showTaskDetailModal && $this->detailTaskId) {
            $detailTask = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers', 'children.assignedUser'])->find($this->detailTaskId);
        }

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
            'loggedIssues',
            'kanbanToDo',
            'kanbanInProgress',
            'kanbanInReview',
            'kanbanCompleted',
            'kanbanBlocked',
            'kanbanOverdue',
            'kanbanToday',
            'kanbanTomorrow',
            'kanbanNextWeek',
            'kanbanLater',
            'detailTask'
        ));
    }
}

