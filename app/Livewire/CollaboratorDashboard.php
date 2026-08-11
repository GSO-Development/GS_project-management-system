<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\WbsItem;
use App\Services\ProgressCalculationService;
use Livewire\Component;

class CollaboratorDashboard extends Component
{
    public function updateTaskProgress(int $taskId, int $progress): void
    {
        $task = WbsItem::where('assigned_user_id', auth()->id())->findOrFail($taskId);
        $task->progress = max(0, min(100, $progress));

        if ($progress >= 100) {
            $task->status = WbsStatus::COMPLETED;
        } elseif ($progress > 0 && $task->status->value === 'not_started') {
            $task->status = WbsStatus::IN_PROGRESS;
        }

        $task->save();
        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: 'Task progress updated successfully!', type: 'success');
    }

    public function updateTaskStatus(int $taskId, string $status): void
    {
        $task = WbsItem::where('assigned_user_id', auth()->id())->findOrFail($taskId);
        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) return;

        $task->status = $statusEnum;
        if ($status === 'completed') $task->progress = 100;
        elseif ($status === 'in_progress' && $task->progress == 0) $task->progress = 10;
        elseif ($status === 'not_started') $task->progress = 0;

        $task->save();
        (new ProgressCalculationService())->updateItemProgress($task);
        $this->dispatch('toast', message: 'Task status updated to ' . $statusEnum->label(), type: 'success');
    }

    // Sub-task creation state
    public bool $showSubTaskModal = false;
    public ?int $parentTaskId = null;
    public string $subTaskTitle = '';
    public ?string $subTaskDescription = null;
    public ?string $subTaskStartDate = null;
    public ?string $subTaskEndDate = null;
    public string $subTaskPriority = 'medium';
    public ?float $subTaskEstimatedHours = null;

    public function openSubTaskModal(int $parentTaskId): void
    {
        $parentTask = WbsItem::where('assigned_user_id', auth()->id())->findOrFail($parentTaskId);
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

        $parentTask = WbsItem::where('assigned_user_id', auth()->id())->findOrFail($this->parentTaskId);
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
            'status'           => WbsStatus::NOT_STARTED,
            'priority'         => \App\Enums\Priority::tryFrom($this->subTaskPriority) ?? \App\Enums\Priority::MEDIUM,
            'progress'         => 0,
            'estimated_hours'  => $this->subTaskEstimatedHours,
            'weight'           => 0.5,
            'created_by'       => $user->id,
        ]);

        (new ProgressCalculationService())->updateItemProgress($parentTask);

        $this->showSubTaskModal = false;
        $this->dispatch('toast', message: "Sub-task '{$subTask->title}' created successfully!", type: 'success');
    }

    public function render()
    {
        $userId = auth()->id();
        $today = now()->today();

        // Projects where this user is attached as a Collaborator
        $attachedProjects = Project::whereHas('members', fn($q) => $q->where('users.id', $userId))
            ->orWhere('project_manager_id', $userId)
            ->with(['subsidiary', 'projectManager', 'wbsItems'])
            ->latest()
            ->get();

        // Tasks assigned to this Collaborator
        $myTasks = WbsItem::whereHas('project')
            ->with(['project.projectManager', 'children.assignedUser'])
            ->where('assigned_user_id', $userId)
            ->orderBy('end_date', 'asc')
            ->get();

        $dueToday = $myTasks->filter(fn($t) => $t->end_date && $t->end_date->isToday());
        $inProgress = $myTasks->filter(fn($t) => $t->status->value === 'in_progress');
        $completed = $myTasks->filter(fn($t) => $t->status->value === 'completed');
        $overdue = $myTasks->filter(fn($t) => $t->end_date && $t->end_date->isPast() && !in_array($t->status->value, ['completed', 'cancelled']));
        $blocked = $myTasks->filter(fn($t) => $t->status->value === 'blocked');

        $totalCount = $myTasks->count();
        $completionPct = $totalCount > 0 ? round(($completed->count() / $totalCount) * 100) : 0;

        return view('livewire.collaborator-dashboard', compact(
            'attachedProjects',
            'myTasks',
            'dueToday',
            'inProgress',
            'completed',
            'overdue',
            'blocked',
            'totalCount',
            'completionPct'
        ));
    }
}
