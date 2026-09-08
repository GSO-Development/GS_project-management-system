<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use App\Notifications\TaskCompletedNotification;
use App\Services\ProgressCalculationService;
use App\Services\WbsScheduleCascadeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class MyTasks extends Component
{
    use WithPagination;

    // Filter and display state
    public string $taskScope = 'assigned';      // 'assigned' (Assigned to Me) or 'pm_projects' (All Tasks in My Managed Projects)
    public string $assigneeFilter = 'all';     // 'all', 'unassigned', or specific user ID when in pm_projects
    public string $search = '';
    public string $projectFilter = 'all';
    public string $priorityFilter = 'all';
    public string $statusFilter = 'all'; // 'all', 'incomplete', or specific status
    public string $dueDateFilter = 'all';       // 'all', 'today', 'overdue', 'this_week'
    public string $groupBy = 'flat';         // 'flat', 'project', 'date'
    public string $viewMode = 'table';          // 'table', 'kanban', 'timeline'
    public string $kanbanMode = 'time';         // 'time' (Overdue, Today, Tomorrow, Next Week, Later, Completed) or 'status'
    public int $perPage = 10;
    public string $saView = 'mine';             // 'mine' or 'team' (super admin only)
    public bool $showAllTasks = false;          // true = show ALL tasks across ALL projects (admin only)
    public array $collapsedProjectIds = [];
    public array $selectedTaskIds = [];
    public bool $selectAll = false;

    // Timeline state
    public string $timelineTimeframe = 'day';   // 'day', 'week', 'month'
    public string $timelineDate = '';           // 'YYYY-MM-DD'

    // Blocker / Issue Modal
    public bool $showBlockerModal = false;
    public ?int $selectedTaskId = null;
    public string $blockerDescription = '';
    public string $blockerSeverity = 'medium';

    // Delay Reason Modal
    public bool $showDelayReasonModal = false;
    public ?int $delayTaskId = null;
    public string $delayReasonText = '';

    // Create Sub-Task Modal
    public bool $showSubTaskModal = false;
    public ?int $parentTaskId = null;
    public string $subTaskTitle = '';
    public ?string $subTaskDescription = null;
    public ?string $subTaskStartDate = null;
    public ?string $subTaskEndDate = null;
    public ?string $subTaskStartTime = null;
    public ?string $subTaskEndTime = null;
    public string $subTaskPriority = 'medium';
    public ?float $subTaskEstimatedHours = null;

    // Quick Task Detail & Edit Drawer / Modal
    public bool $showTaskDetailModal = false;
    public ?int $detailTaskId = null;
    public string $detailTitle = '';
    public ?string $detailDescription = null;
    public string $detailStatus = 'not_started';
    public string $detailPriority = 'medium';
    public int $detailProgress = 0;
    public ?string $detailStartDate = null;
    public ?string $detailEndDate = null;
    public ?string $detailStartTime = null;
    public ?string $detailEndTime = null;
    public string $newSubtaskTitle = '';

    // Quick Create Task Modal (for adding tasks to any assigned project)
    public bool $showCreateTaskModal = false;
    public ?int $createTaskProjectId = null;
    public string $createTaskTitle = '';
    public string $createTaskStatus = 'not_started';
    public string $createTaskPriority = 'medium';
    public ?string $createTaskStartDate = null;
    public ?string $createTaskEndDate = null;
    public ?string $createTaskStartTime = null;
    public ?string $createTaskEndTime = null;

    protected $listeners = [
        'wbsUpdated' => '$refresh',
        'taskUpdated' => '$refresh',
    ];

    public function mount(): void
    {
        if (auth()->check() && auth()->user()->isPmoAdmin()) {
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }

        // Pre-filter by scope (e.g. from dashboard PM tasks shortcut)
        $scopeParam = request()->query('scope');
        if ($scopeParam && in_array($scopeParam, ['assigned', 'pm_projects'])) {
            $this->taskScope = $scopeParam;
        }

        // Pre-filter by status when redirected from dashboard
        $statusParam = request()->query('status');
        if ($statusParam && in_array($statusParam, ['completed', 'in_progress', 'on_hold', 'not_started', 'all'])) {
            $this->statusFilter = $statusParam;
        }

        // Show ALL tasks across all projects (admin only)
        if (request()->query('all') === 'true' && auth()->check() && auth()->user()->isSuperAdmin()) {
            $this->showAllTasks = true;
            $this->groupBy = 'project'; // default to project grouping for clarity
        }
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedProjectFilter() { $this->resetPage(); }
    public function updatedPriorityFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedDueDateFilter() { $this->resetPage(); }
    public function updatedAssigneeFilter() { $this->resetPage(); }
    public function updatedTaskScope() { $this->resetPage(); }
    public function updatedGroupBy() { $this->resetPage(); }
    public function updatedPerPage() { $this->resetPage(); }

    public function setTaskScope(string $scope): void
    {
        $this->taskScope = in_array($scope, ['assigned', 'pm_projects']) ? $scope : 'assigned';
        $this->projectFilter = 'all';
        $this->assigneeFilter = 'all';
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'projectFilter', 'priorityFilter', 'statusFilter', 'dueDateFilter', 'assigneeFilter']);
        $this->resetPage();
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'kanban', 'timeline']) ? $mode : 'table';
        if ($this->viewMode === 'timeline') {
            $this->statusFilter = 'all';
            $this->dueDateFilter = 'all';
        }
    }

    public function setKanbanMode(string $mode): void
    {
        $this->kanbanMode = in_array($mode, ['time', 'status']) ? $mode : 'time';
    }

    public function setGroupBy(string $group): void
    {
        $this->groupBy = in_array($group, ['project', 'date', 'flat']) ? $group : 'project';
    }

    public function setStatusFilter(string $value): void
    {
        $this->statusFilter = $this->statusFilter === $value ? 'all' : $value;
        $this->resetPage();
    }

    public function setDueDateFilter(string $value): void
    {
        $this->dueDateFilter = $this->dueDateFilter === $value ? 'all' : $value;
        $this->resetPage();
    }

    public function toggleProjectCollapse(int $projectId): void
    {
        if (in_array($projectId, $this->collapsedProjectIds)) {
            $this->collapsedProjectIds = array_values(array_diff($this->collapsedProjectIds, [$projectId]));
        } else {
            $this->collapsedProjectIds[] = $projectId;
        }
    }

    public function expandAllProjects(): void
    {
        $this->collapsedProjectIds = [];
    }

    public function collapseAllProjects(array $allProjectIds): void
    {
        $this->collapsedProjectIds = $allProjectIds;
    }

    // ── TIMELINE CONTROLS ───────────────────────────────────────────
    public function setTimelineTimeframe(string $tf): void
    {
        $this->timelineTimeframe = in_array($tf, ['day', 'week', 'month']) ? $tf : 'day';
    }

    public function selectTimelineDay(string $dateStr): void
    {
        $this->timelineDate = $dateStr;
    }

    public function goToTimelineToday(): void
    {
        $this->timelineDate = now()->toDateString();
    }

    public function goToTimelinePrev(): void
    {
        $current = $this->timelineDate ? Carbon::parse($this->timelineDate) : now();
        if ($this->timelineTimeframe === 'day') {
            $this->timelineDate = $current->subDay()->toDateString();
        } elseif ($this->timelineTimeframe === 'week') {
            $this->timelineDate = $current->subWeek()->toDateString();
        } else {
            $this->timelineDate = $current->subMonth()->toDateString();
        }
    }

    public function goToTimelineNext(): void
    {
        $current = $this->timelineDate ? Carbon::parse($this->timelineDate) : now();
        if ($this->timelineTimeframe === 'day') {
            $this->timelineDate = $current->addDay()->toDateString();
        } elseif ($this->timelineTimeframe === 'week') {
            $this->timelineDate = $current->addWeek()->toDateString();
        } else {
            $this->timelineDate = $current->addMonth()->toDateString();
        }
    }

    // ── TASK STATUS & PROGRESS ACTIONS ──────────────────────────────
    public function toggleTaskComplete(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$this->canModifyTask($task, $user)) {
            $this->dispatch('toast', message: 'You can only update tasks assigned to you.', type: 'error');
            return;
        }

        if ($task->status === WbsStatus::COMPLETED) {
            $task->status = WbsStatus::IN_PROGRESS;
            $task->progress = 50;
            $this->dispatch('toast', message: "Task '{$task->title}' marked In Progress.", type: 'info');
        } else {
            $task->status = WbsStatus::COMPLETED;
            $task->progress = 100;
            $this->notifyTaskCompletion($task);
            $this->dispatch('toast', message: "Task '{$task->title}' completed! 🎉", type: 'success');
        }

        $task->saveQuietly();
        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('wbsUpdated');
    }

    public function updateStatus(int $taskId, string $status): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$this->canModifyTask($task, $user)) {
            $this->dispatch('toast', message: 'You can only update tasks assigned to you.', type: 'error');
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

        $task->saveQuietly();
        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: "Status updated to " . $statusEnum->label(), type: 'success');
        $this->dispatch('wbsUpdated');
    }

    public function updateProgress(int $taskId, int $progress): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$this->canModifyTask($task, $user)) {
            $this->dispatch('toast', message: 'Access denied.', type: 'error');
            return;
        }

        $progress = max(0, min(100, $progress));
        $task->progress = $progress;

        if ($progress === 100) {
            $task->status = WbsStatus::COMPLETED;
            $this->notifyTaskCompletion($task);
        } elseif ($progress > 0 && $task->status === WbsStatus::NOT_STARTED) {
            $task->status = WbsStatus::IN_PROGRESS;
        }

        $task->saveQuietly();
        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: "Progress updated to {$progress}%", type: 'success');
        $this->dispatch('wbsUpdated');
    }

    private function canModifyTask(WbsItem $task, User $user): bool
    {
        return $user->hasRole('super_admin')
            || $task->assigned_user_id === $user->id
            || ($task->project && $task->project->project_manager_id === $user->id);
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
            Log::warning('TaskCompletedNotification error: ' . $e->getMessage());
        }
    }

    // ── TASK DETAIL DRAWER & EDIT ───────────────────────────────────
    public function openTaskDetail(int $taskId): void
    {
        $task = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers', 'children'])->findOrFail($taskId);
        $this->detailTaskId      = $task->id;
        $this->detailTitle       = $task->title;
        $this->detailDescription = $task->description;
        $this->detailStatus      = $task->status->value;
        $this->detailPriority    = $task->priority->value;
        $this->detailProgress    = $task->progress;
        $this->detailStartDate   = $task->start_date?->toDateString();
        $this->detailEndDate     = $task->end_date?->toDateString();
        $this->detailStartTime   = $task->start_time ? Carbon::parse($task->start_time)->format('H:i') : null;
        $this->detailEndTime     = $task->end_time ? Carbon::parse($task->end_time)->format('H:i') : null;
        $this->newSubtaskTitle   = '';
        $this->showTaskDetailModal = true;
    }

    public function saveTaskDetail(): void
    {
        if (!$this->detailTaskId) return;

        $task = WbsItem::findOrFail($this->detailTaskId);
        $user = auth()->user();

        if (!$this->canModifyTask($task, $user)) {
            $this->dispatch('toast', message: 'Access denied.', type: 'error');
            return;
        }

        $this->validate([
            'detailTitle'     => 'required|string|max:255',
            'detailStartDate' => 'nullable|date',
            'detailEndDate'   => 'nullable|date|after_or_equal:detailStartDate',
        ]);

        $statusEnum = WbsStatus::tryFrom($this->detailStatus) ?? WbsStatus::NOT_STARTED;
        $progress = max(0, min(100, (int) $this->detailProgress));

        if ($progress >= 100) {
            $statusEnum = WbsStatus::COMPLETED;
            $this->notifyTaskCompletion($task);
        } elseif ($statusEnum === WbsStatus::COMPLETED && $progress < 100) {
            $progress = 100;
        }

        $task->title       = trim($this->detailTitle);
        $task->description = $this->detailDescription;
        $task->status      = $statusEnum;
        $task->priority    = Priority::tryFrom($this->detailPriority) ?? Priority::MEDIUM;
        $task->progress    = $progress;
        $task->start_date  = $this->detailStartDate ?: null;
        $task->end_date    = $this->detailEndDate ?: null;
        $task->start_time  = $this->detailStartTime ?: null;
        $task->end_time    = $this->detailEndTime ?: null;

        $task->saveQuietly();

        // Cascade time slots to subsequent siblings if intraday times were changed
        WbsScheduleCascadeService::cascadeTimeSlotsForSiblings($task);
        (new ProgressCalculationService())->updateItemProgress($task);

        $this->showTaskDetailModal = false;
        $this->dispatch('toast', message: 'Task updated successfully!', type: 'success');
        $this->dispatch('wbsUpdated');
    }

    public function addQuickSubtask(): void
    {
        if (!$this->detailTaskId || empty(trim($this->newSubtaskTitle))) return;
        $parent = WbsItem::findOrFail($this->detailTaskId);
        $subCount = WbsItem::where('parent_id', $parent->id)->count();

        WbsItem::create([
            'project_id'       => $parent->project_id,
            'parent_id'        => $parent->id,
            'wbs_code'         => $parent->wbs_code . '.' . ($subCount + 1),
            'item_type'        => \App\Enums\ItemType::SUBTASK,
            'title'            => trim($this->newSubtaskTitle),
            'assigned_user_id' => auth()->id(),
            'start_date'       => $parent->start_date ?? now()->toDateString(),
            'end_date'         => $parent->end_date ?? now()->toDateString(),
            'status'           => WbsStatus::NOT_STARTED,
            'priority'         => $parent->priority,
            'progress'         => 0,
            'weight'           => 0.5,
            'created_by'       => auth()->id(),
        ]);

        $this->newSubtaskTitle = '';
        (new ProgressCalculationService())->updateItemProgress($parent);
        $this->dispatch('toast', message: 'Sub-task added successfully', type: 'success');
        $this->dispatch('wbsUpdated');
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
        $sub->saveQuietly();

        if ($sub->parent_id) {
            $parent = WbsItem::find($sub->parent_id);
            if ($parent) {
                (new ProgressCalculationService())->updateItemProgress($parent);
            }
        }
        $this->dispatch('wbsUpdated');
    }

    // ── BLOCKER & DELAY REASON ACTIONS ──────────────────────────────
    public function openBlockerModal(int $taskId): void
    {
        $this->selectedTaskId = $taskId;
        $this->blockerDescription = '';
        $this->blockerSeverity = 'medium';
        $this->showBlockerModal = true;
    }

    public function reportBlocker(): void
    {
        $this->validate([
            'blockerDescription' => 'required|string|min:5',
        ]);

        $task = WbsItem::findOrFail($this->selectedTaskId);

        TaskBlocker::create([
            'wbs_item_id' => $task->id,
            'reported_by' => auth()->id(),
            'description' => $this->blockerDescription,
            'severity'    => $this->blockerSeverity,
            'status'      => 'open',
        ]);

        $task->status = WbsStatus::BLOCKED;
        $task->saveQuietly();

        $this->showBlockerModal = false;
        $this->dispatch('toast', message: 'Task blocker reported to Project Manager!', type: 'warning');
        $this->dispatch('wbsUpdated');
    }

    public function openDelayReasonModal(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $this->delayTaskId = $task->id;
        $this->delayReasonText = $task->delay_reason ?? '';
        $this->showDelayReasonModal = true;
    }

    public function submitDelayReason(): void
    {
        $this->validate([
            'delayReasonText' => 'required|string|min:5|max:1000',
        ]);

        $task = WbsItem::findOrFail($this->delayTaskId);
        $user = auth()->user();

        $task->delay_reason    = $this->delayReasonText;
        $task->delay_reason_by = $user->id;
        $task->delay_reason_at = now();

        if (!in_array($task->status->value, ['completed', 'cancelled'])) {
            $task->status = WbsStatus::BLOCKED;
        }

        $task->saveQuietly();

        TaskBlocker::create([
            'wbs_item_id' => $task->id,
            'reported_by' => $user->id,
            'description' => 'Uncompleted Task Reason: ' . $this->delayReasonText,
            'severity'    => 'high',
            'status'      => 'open',
        ]);

        $this->showDelayReasonModal = false;
        $this->dispatch('toast', message: 'Task issue/delay reason logged successfully!', type: 'warning');
        $this->dispatch('wbsUpdated');
    }

    // ── SUBTASK CREATION MODAL ───────────────────────────────────────
    public function openSubTaskModal(int $parentTaskId): void
    {
        $parentTask = WbsItem::findOrFail($parentTaskId);
        $this->parentTaskId          = $parentTask->id;
        $this->subTaskTitle           = '';
        $this->subTaskDescription     = null;
        $this->subTaskStartDate       = $parentTask->start_date ? $parentTask->start_date->format('Y-m-d') : now()->toDateString();
        $this->subTaskEndDate         = $parentTask->end_date ? $parentTask->end_date->format('Y-m-d') : now()->toDateString();
        $this->subTaskStartTime       = null;
        $this->subTaskEndTime         = null;
        $this->subTaskPriority        = 'medium';
        $this->subTaskEstimatedHours  = null;
        $this->showSubTaskModal       = true;
    }

    public function createSubTask(): void
    {
        $this->validate([
            'subTaskTitle'     => 'required|string|max:255',
            'subTaskStartDate' => 'nullable|date',
            'subTaskEndDate'   => 'nullable|date|after_or_equal:subTaskStartDate',
        ]);

        $parentTask = WbsItem::findOrFail($this->parentTaskId);
        $user       = auth()->user();

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
            'start_time'       => $this->subTaskStartTime ?: null,
            'end_time'         => $this->subTaskEndTime ?: null,
            'status'           => WbsStatus::NOT_STARTED,
            'priority'         => Priority::tryFrom($this->subTaskPriority) ?? Priority::MEDIUM,
            'progress'         => 0,
            'estimated_hours'  => $this->subTaskEstimatedHours,
            'weight'           => 0.5,
            'created_by'       => $user->id,
        ]);

        (new ProgressCalculationService())->updateItemProgress($parentTask);

        $this->showSubTaskModal = false;
        $this->dispatch('toast', message: "Sub-task '{$subTask->title}' created successfully!", type: 'success');
        $this->dispatch('wbsUpdated');
    }

    // ── CREATE NEW TASK MODAL ───────────────────────────────────────
    public function openAddTaskModal(string $target = 'not_started'): void
    {
        $this->createTaskTitle = '';
        $this->createTaskStartDate = now()->toDateString();
        $this->createTaskEndDate = now()->toDateString();
        $this->createTaskStartTime = null;
        $this->createTaskEndTime = null;
        $this->createTaskPriority = 'medium';
        $this->createTaskStatus = $target === 'today' ? 'in_progress' : ($target === 'completed' ? 'completed' : 'not_started');

        $user = auth()->user();
        $firstProject = Project::where(function($q) use ($user) {
            $q->where('project_manager_id', $user->id)
              ->orWhere(function($sub) use ($user) {
                  $sub->where('pm_accepted', true)
                      ->whereHas('members', fn($m) => $m->where('users.id', $user->id));
              });
        })->first();

        $this->createTaskProjectId = $firstProject?->id;
        $this->showCreateTaskModal = true;
    }

    public function saveNewTask(): void
    {
        $this->validate([
            'createTaskProjectId' => 'required|exists:projects,id',
            'createTaskTitle'     => 'required|string|max:255',
            'createTaskStartDate' => 'nullable|date',
            'createTaskEndDate'   => 'nullable|date|after_or_equal:createTaskStartDate',
        ]);

        $user = auth()->user();
        $project = Project::findOrFail($this->createTaskProjectId);
        $childCount = WbsItem::where('project_id', $project->id)->whereNull('parent_id')->count();

        $statusEnum = WbsStatus::tryFrom($this->createTaskStatus) ?? WbsStatus::NOT_STARTED;

        $task = WbsItem::create([
            'project_id'       => $project->id,
            'parent_id'        => null,
            'wbs_code'         => (string) ($childCount + 1),
            'item_type'        => \App\Enums\ItemType::TASK,
            'title'            => trim($this->createTaskTitle),
            'assigned_user_id' => $user->id,
            'start_date'       => $this->createTaskStartDate,
            'end_date'         => $this->createTaskEndDate,
            'start_time'       => $this->createTaskStartTime ?: null,
            'end_time'         => $this->createTaskEndTime ?: null,
            'status'           => $statusEnum,
            'priority'         => Priority::tryFrom($this->createTaskPriority) ?? Priority::MEDIUM,
            'progress'         => $statusEnum === WbsStatus::COMPLETED ? 100 : 0,
            'weight'           => 1.0,
            'created_by'       => $user->id,
        ]);

        $this->showCreateTaskModal = false;
        $this->dispatch('toast', message: "Task '{$task->title}' created successfully!", type: 'success');
        $this->dispatch('wbsUpdated');
    }

    public function deleteTask(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        if (!$this->canModifyTask($task, $user)) {
            $this->dispatch('toast', message: 'Unauthorized to delete this task.', type: 'error');
            return;
        }

        $task->delete();
        $this->dispatch('toast', message: "Task deleted successfully.", type: 'success');
        $this->dispatch('wbsUpdated');
    }

    // ── MAIN RENDER ─────────────────────────────────────────────────
    public function render()
    {
        $user = auth()->user();
        $today = now()->today()->toDateString();

        // Transition tasks whose start_date has arrived
        WbsItem::autoStartDueTasks();

        // 1. Project Manager Status & Scope Counts
        $managedProjectsCount = Project::where('project_manager_id', $user->id)->count();
        $isProjectManager = $managedProjectsCount > 0;

        $myAssignedCount = WbsItem::where('assigned_user_id', $user->id)->whereHas('project')->count();
        $pmProjectsTasksCount = $isProjectManager 
            ? WbsItem::whereHas('project', fn($q) => $q->where('project_manager_id', $user->id))->count() 
            : 0;

        // If user is not PM on any project, enforce 'assigned'
        if (!$isProjectManager && $this->taskScope === 'pm_projects') {
            $this->taskScope = 'assigned';
        }

        // 2. Base Query & Available Filter Projects & Assignees
        if ($this->taskScope === 'pm_projects' && $isProjectManager) {
            $baseQuery = WbsItem::whereHas('project', fn($q) => $q->where('project_manager_id', $user->id));
            $myProjects = Project::where('project_manager_id', $user->id)
                ->with('subsidiary')
                ->orderBy('name')
                ->get();

            $assigneeIds = (clone $baseQuery)->whereNotNull('assigned_user_id')->pluck('assigned_user_id')->unique();
            $availableAssignees = User::whereIn('id', $assigneeIds)->orderBy('name')->get();
        } elseif ($this->showAllTasks && $user->isSuperAdmin()) {
            $baseQuery = WbsItem::whereHas('project');
            $myProjects = Project::with('subsidiary')->orderBy('name')->get();
            $assigneeIds = (clone $baseQuery)->whereNotNull('assigned_user_id')->pluck('assigned_user_id')->unique();
            $availableAssignees = User::whereIn('id', $assigneeIds)->orderBy('name')->get();
        } else {
            $baseQuery = WbsItem::where('assigned_user_id', $user->id)
                ->whereHas('project');
            $myProjects = Project::whereHas('wbsItems', fn($q) => $q->where('assigned_user_id', $user->id))
                ->with('subsidiary')
                ->orderBy('name')
                ->get();
            if ($myProjects->isEmpty()) {
                $myProjects = Project::where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                })->with('subsidiary')->orderBy('name')->get();
            }
            if ($myProjects->isEmpty()) {
                $myProjects = Project::with('subsidiary')->orderBy('name')->get();
            }
            $availableAssignees = collect();
        }

        // 3. KPI Cockpit Counts across active scope
        $allAssigned = (clone $baseQuery)->get();
        $totalCount       = $allAssigned->count();
        $inProgressCount  = $allAssigned->where('status', WbsStatus::IN_PROGRESS)->count();
        $completedCount   = $allAssigned->where('status', WbsStatus::COMPLETED)->count();
        $blockedCount     = $allAssigned->where('status', WbsStatus::BLOCKED)->count();
        $notStartedCount  = $allAssigned->whereIn('status', [WbsStatus::NOT_STARTED, WbsStatus::BACKLOG])->count();
        
        $dueTodayCount = $allAssigned->filter(function($t) use ($today) {
            if (in_array($t->status->value, ['completed', 'cancelled'])) return false;
            if ($t->end_date && $t->end_date->toDateString() === $today) return true;
            if ($t->start_date && $t->start_date->toDateString() === $today) return true;
            if ($t->start_date && $t->end_date && $t->start_date->toDateString() <= $today && $t->end_date->toDateString() >= $today) return true;
            return false;
        })->count();

        $overdueCount = $allAssigned->filter(function($t) use ($today) {
            if (in_array($t->status->value, ['completed', 'cancelled'])) return false;
            return $t->end_date && $t->end_date->toDateString() < $today;
        })->count();

        $activeProjectsCount = $allAssigned->pluck('project_id')->unique()->count();

        // 4. Filtered Query for Current View
        $filteredQuery = (clone $baseQuery)->with([
            'project.subsidiary',
            'project.projectManager',
            'assignedUser',
            'blockers',
            'children.assignedUser',
            'parent',
            'risks'
        ]);

        if ($this->taskScope === 'pm_projects' && $this->assigneeFilter !== 'all') {
            if ($this->assigneeFilter === 'unassigned') {
                $filteredQuery->whereNull('assigned_user_id');
            } else {
                $filteredQuery->where('assigned_user_id', $this->assigneeFilter);
            }
        }

        if ($this->search) {
            $filteredQuery->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('wbs_code', 'like', "%{$this->search}%")
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"));
            });
        }

        if ($this->projectFilter !== 'all') {
            $filteredQuery->where('project_id', $this->projectFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $filteredQuery->where('priority', $this->priorityFilter);
        }

        if ($this->statusFilter === 'incomplete') {
            $filteredQuery->whereNotIn('status', ['completed', 'cancelled']);
        } elseif ($this->statusFilter !== 'all') {
            $filteredQuery->where('status', $this->statusFilter);
        }

        if ($this->dueDateFilter === 'today') {
            $filteredQuery->where(function($q) use ($today) {
                $q->whereDate('end_date', $today)
                  ->orWhereDate('start_date', $today)
                  ->orWhere(function($sq) use ($today) {
                      $sq->whereDate('start_date', '<=', $today)
                         ->whereDate('end_date', '>=', $today);
                  });
            });
        } elseif ($this->dueDateFilter === 'overdue') {
            $filteredQuery->where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled']);
        } elseif ($this->dueDateFilter === 'this_week') {
            $filteredQuery->whereBetween('end_date', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        $paginatedTasks = (clone $filteredQuery)
            ->orderBy('sort_order', 'asc')
            ->orderBy('end_date', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        $allFilteredTasks = $filteredQuery
            ->orderBy('project_id', 'asc')
            ->orderBy('sort_order', 'asc')
            ->orderBy('start_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // 5. Grouped datasets for Table View
        $tasksByProject = $allFilteredTasks->groupBy('project_id');

        // Group by Date Horizon
        $tasksByDate = [
            'overdue'   => $allFilteredTasks->filter(fn($t) => $t->end_date && $t->end_date->toDateString() < $today && !in_array($t->status->value, ['completed','cancelled'])),
            'today'     => $allFilteredTasks->filter(fn($t) => ($t->end_date && $t->end_date->toDateString() === $today) || (!$t->end_date && $t->start_date && $t->start_date->toDateString() === $today)),
            'tomorrow'  => $allFilteredTasks->filter(fn($t) => $t->end_date && $t->end_date->toDateString() === now()->addDay()->toDateString()),
            'this_week' => $allFilteredTasks->filter(fn($t) => $t->end_date && $t->end_date->toDateString() > now()->addDay()->toDateString() && $t->end_date->lte(now()->endOfWeek())),
            'later'     => $allFilteredTasks->filter(fn($t) => (!$t->end_date && !$t->start_date) || ($t->end_date && $t->end_date->gt(now()->endOfWeek()))),
            'completed' => $allFilteredTasks->filter(fn($t) => $t->status === WbsStatus::COMPLETED),
        ];

        // 6. Datasets for Kanban View (Time-Horizon & Status)
        $todayDate    = now()->toDateString();
        $tomorrowDate = now()->addDay()->toDateString();
        $nextWeekEnd  = now()->addDays(7)->toDateString();

        $timeKanbanColumns = [
            'overdue' => [
                'title'  => 'Overdue',
                'badge'  => 'bg-rose-100 text-rose-800 border-rose-300',
                'accent' => 'border-t-rose-500',
                'icon'   => '🚨',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->toDateString() < $todayDate),
                'status' => 'not_started',
            ],
            'today' => [
                'title'  => 'Today',
                'badge'  => 'bg-amber-100 text-amber-800 border-amber-300',
                'accent' => 'border-t-amber-500',
                'icon'   => '⭐',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value !== 'completed' && (($t->end_date && $t->end_date->toDateString() === $todayDate) || (!$t->end_date && $t->start_date && $t->start_date->toDateString() === $todayDate))),
                'status' => 'in_progress',
            ],
            'tomorrow' => [
                'title'  => 'Tomorrow',
                'badge'  => 'bg-blue-100 text-blue-800 border-blue-300',
                'accent' => 'border-t-blue-500',
                'icon'   => '📅',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value !== 'completed' && (($t->end_date && $t->end_date->toDateString() === $tomorrowDate) || (!$t->end_date && $t->start_date && $t->start_date->toDateString() === $tomorrowDate))),
                'status' => 'not_started',
            ],
            'next_week' => [
                'title'  => 'Next Week',
                'badge'  => 'bg-purple-100 text-purple-800 border-purple-300',
                'accent' => 'border-t-purple-500',
                'icon'   => '📆',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->toDateString() > $tomorrowDate && $t->end_date->toDateString() <= $nextWeekEnd),
                'status' => 'not_started',
            ],
            'later' => [
                'title'  => 'Later & Future',
                'badge'  => 'bg-slate-100 text-slate-700 border-slate-300',
                'accent' => 'border-t-slate-400',
                'icon'   => '⏳',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value !== 'completed' && ((!$t->end_date && !$t->start_date) || ($t->end_date && $t->end_date->toDateString() > $nextWeekEnd))),
                'status' => 'not_started',
            ],
            'completed' => [
                'title'  => 'Completed',
                'badge'  => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'accent' => 'border-t-emerald-500',
                'icon'   => '🎉',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status === WbsStatus::COMPLETED),
                'status' => 'completed',
            ],
        ];

        $statusKanbanColumns = [
            'to_do' => [
                'title'  => 'To Do',
                'badge'  => 'bg-slate-100 text-slate-700 border-slate-200',
                'accent' => 'border-t-slate-400',
                'icon'   => '⚪',
                'tasks'  => $allFilteredTasks->filter(fn($t) => in_array($t->status->value, ['not_started', 'backlog']) && $t->status->value !== 'blocked'),
                'status' => 'not_started'
            ],
            'in_progress' => [
                'title'  => 'In Progress',
                'badge'  => 'bg-blue-100 text-blue-800 border-blue-200',
                'accent' => 'border-t-blue-500',
                'icon'   => '🟡',
                'tasks'  => $allFilteredTasks->filter(fn($t) => in_array($t->status->value, ['in_progress', 'on_hold']) && $t->status->value !== 'blocked'),
                'status' => 'in_progress'
            ],
            'under_review' => [
                'title'  => 'In Review',
                'badge'  => 'bg-purple-100 text-purple-800 border-purple-200',
                'accent' => 'border-t-purple-500',
                'icon'   => '🔵',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value === 'under_review' && $t->status->value !== 'blocked'),
                'status' => 'under_review'
            ],
            'blocked' => [
                'title'  => 'Blocked',
                'badge'  => 'bg-red-100 text-red-800 border-red-200',
                'accent' => 'border-t-red-500',
                'icon'   => '🔴',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0)),
                'status' => 'blocked'
            ],
            'completed' => [
                'title'  => 'Completed',
                'badge'  => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'accent' => 'border-t-emerald-500',
                'icon'   => '🟢',
                'tasks'  => $allFilteredTasks->filter(fn($t) => $t->status->value === 'completed'),
                'status' => 'completed'
            ],
        ];

        $kanbanColumns = $this->kanbanMode === 'time' ? $timeKanbanColumns : $statusKanbanColumns;

        // 7. Datasets for Timeline View
        $timelineDateObj = $this->timelineDate ? Carbon::parse($this->timelineDate) : now();
        $currentDay = $timelineDateObj->copy()->startOfDay();

        $dayTasks = $allFilteredTasks->filter(function($t) use ($currentDay) {
            $start = $t->start_date ? $t->start_date->copy()->startOfDay() : null;
            $end = $t->end_date ? $t->end_date->copy()->endOfDay() : null;
            if ($start && $end) {
                return $currentDay->between($start, $end);
            } elseif ($start) {
                return $start->isSameDay($currentDay);
            } elseif ($end) {
                return $end->isSameDay($currentDay);
            }
            return false;
        });

        $morningTasks   = $dayTasks->filter(fn($t) => $t->start_time && $t->start_time < '12:00:00');
        $afternoonTasks = $dayTasks->filter(fn($t) => $t->start_time && $t->start_time >= '12:00:00' && $t->start_time < '17:00:00');
        $eveningTasks   = $dayTasks->filter(fn($t) => $t->start_time && $t->start_time >= '17:00:00');
        $allDayTasks    = $dayTasks->filter(fn($t) => empty($t->start_time));

        $weekStart = $timelineDateObj->copy()->startOfWeek();
        $weekDays = [];
        $weekSchedule = [];
        for ($i = 0; $i < 7; $i++) {
            $wDay = $weekStart->copy()->addDays($i);
            $weekDays[] = $wDay;
            $wDateStr = $wDay->toDateString();
            $wTasks = $allFilteredTasks->filter(function($t) use ($wDay) {
                $start = $t->start_date ? $t->start_date->copy()->startOfDay() : null;
                $end = $t->end_date ? $t->end_date->copy()->endOfDay() : null;
                if ($start && $end) {
                    return $wDay->between($start, $end);
                } elseif ($start) {
                    return $start->isSameDay($wDay);
                } elseif ($end) {
                    return $end->isSameDay($wDay);
                }
                return false;
            });
            $weekSchedule[$wDateStr] = [
                'date'       => $wDay,
                'isToday'    => $wDay->isToday(),
                'isSelected' => $wDateStr === $timelineDateObj->toDateString(),
                'tasks'      => $wTasks,
            ];
        }

        // Active task for Drawer
        $detailTask = null;
        if ($this->showTaskDetailModal && $this->detailTaskId) {
            $detailTask = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers', 'children.assignedUser'])->find($this->detailTaskId);
        }

        return view('livewire.my-tasks', compact(
            'myProjects',
            'isProjectManager',
            'managedProjectsCount',
            'myAssignedCount',
            'pmProjectsTasksCount',
            'availableAssignees',
            'totalCount',
            'inProgressCount',
            'dueTodayCount',
            'overdueCount',
            'completedCount',
            'notStartedCount',
            'blockedCount',
            'activeProjectsCount',
            'allFilteredTasks',
            'paginatedTasks',
            'tasksByProject',
            'tasksByDate',
            'kanbanColumns',
            'timelineDateObj',
            'dayTasks',
            'morningTasks',
            'afternoonTasks',
            'eveningTasks',
            'allDayTasks',
            'weekDays',
            'weekSchedule',
            'detailTask'
        ))->with([
            'taskScope'           => $this->taskScope,
            'assigneeFilter'      => $this->assigneeFilter,
            'viewMode'            => $this->viewMode,
            'kanbanMode'          => $this->kanbanMode,
            'groupBy'             => $this->groupBy,
            'statusFilter'        => $this->statusFilter,
            'dueDateFilter'       => $this->dueDateFilter,
            'search'              => $this->search,
            'projectFilter'       => $this->projectFilter,
            'priorityFilter'      => $this->priorityFilter,
            'timelineTimeframe'   => $this->timelineTimeframe,
            'timelineDate'        => $this->timelineDate,
            'collapsedProjectIds' => $this->collapsedProjectIds,
            'showTaskDetailModal' => $this->showTaskDetailModal,
            'showCreateTaskModal' => $this->showCreateTaskModal,
            'showBlockerModal'    => $this->showBlockerModal,
            'showDelayReasonModal'=> $this->showDelayReasonModal,
            'showSubTaskModal'    => $this->showSubTaskModal,
        ]);
    }
}
