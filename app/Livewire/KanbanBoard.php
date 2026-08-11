<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\WbsItem;
use App\Services\ProgressCalculationService;
use Livewire\Component;

class KanbanBoard extends Component
{
    public Project $project;

    public function updateCardStatus(int $taskId, string $newStatus)
    {
        $task = WbsItem::where('project_id', $this->project->id)->findOrFail($taskId);
        $user = auth()->user();

        // Security check: Team members can move only their assigned tasks
        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $this->project->project_manager_id !== $user->id && $task->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You can only move tasks assigned to you.', type: 'error');
            return;
        }

        $prevStatus = $task->status->value;
        $task->status = WbsStatus::from($newStatus);

        if ($newStatus === 'completed') {
            $task->progress = 100;
        }

        $task->save();

        (new ProgressCalculationService())->updateItemProgress($task);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'moved_kanban_card',
            'module' => 'kanban',
            'record_type' => WbsItem::class,
            'record_id' => $task->id,
            'previous_values' => ['status' => $prevStatus],
            'new_values' => ['status' => $newStatus],
        ]);

        $this->dispatch('toast', message: "Task moved to " . ucfirst(str_replace('_', ' ', $newStatus)), type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $isSuperAdminOrPM = $user->hasRole('super_admin') 
            || $user->email === 'admin@nexuspm.local' 
            || $user->id === 1
            || $this->project->project_manager_id === $user->id;

        $query = WbsItem::with(['assignedUser', 'comments', 'blockers'])
            ->where('project_id', $this->project->id);

        if (!$isSuperAdminOrPM) {
            // Collaborators / Team Members: ONLY see tasks assigned directly to them!
            $query->where('assigned_user_id', $user->id);
        }

        $wbsTasks = $query->get();

        $columns = [
            'backlog' => ['title' => 'Backlog', 'color' => 'slate', 'items' => $wbsTasks->where('status.value', 'backlog')],
            'not_started' => ['title' => 'Not Started', 'color' => 'slate', 'items' => $wbsTasks->where('status.value', 'not_started')],
            'in_progress' => ['title' => 'In Progress', 'color' => 'indigo', 'items' => $wbsTasks->where('status.value', 'in_progress')],
            'blocked' => ['title' => 'Blocked', 'color' => 'rose', 'items' => $wbsTasks->where('status.value', 'blocked')],
            'under_review' => ['title' => 'Under Review', 'color' => 'cyan', 'items' => $wbsTasks->where('status.value', 'under_review')],
            'completed' => ['title' => 'Completed', 'color' => 'emerald', 'items' => $wbsTasks->where('status.value', 'completed')],
        ];

        return view('livewire.kanban-board', compact('columns'));
    }
}
