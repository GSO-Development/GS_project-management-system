<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\WbsItem;
use App\Services\ProgressCalculationService;
use Livewire\Component;

class TeamMemberDashboard extends Component
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
        $this->dispatch('toast', message: 'Progress updated!', type: 'success');
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
        $this->dispatch('toast', message: 'Status updated to ' . $statusEnum->label(), type: 'success');
    }

    public function render()
    {
        $userId = auth()->id();
        $today  = now()->today();

        $myTasks = WbsItem::whereHas('project')
            ->with('project')
            ->where('assigned_user_id', $userId)
            ->orderBy('end_date', 'asc')
            ->get();


        $dueToday   = $myTasks->filter(fn($t) => $t->end_date && $t->end_date->isToday());
        $inProgress = $myTasks->filter(fn($t) => $t->status->value === 'in_progress');
        $completed  = $myTasks->filter(fn($t) => $t->status->value === 'completed');
        $overdue    = $myTasks->filter(fn($t) => $t->end_date && $t->end_date->isPast() && !in_array($t->status->value, ['completed','cancelled']));
        $blocked    = $myTasks->filter(fn($t) => $t->status->value === 'blocked');
        $upcoming   = $myTasks->filter(fn($t) => $t->end_date && $t->end_date->gte($today) && !in_array($t->status->value, ['completed','cancelled']))->take(3);

        // Completion percentage
        $totalCount    = $myTasks->count();
        $completionPct = $totalCount > 0 ? round(($completed->count() / $totalCount) * 100) : 0;

        // Today's Focus: overdue + due today + in progress (active tasks needing attention)
        $todayFocus = $myTasks->filter(function ($t) use ($today) {
            if (in_array($t->status->value, ['completed', 'cancelled'])) return false;
            $isOverdue  = $t->end_date && $t->end_date->lt($today);
            $isDueToday = $t->end_date && $t->end_date->isToday();
            $isActive   = $t->status->value === 'in_progress';
            return $isOverdue || $isDueToday || $isActive;
        });

        return view('livewire.team-member-dashboard', compact(
            'myTasks', 'dueToday', 'inProgress', 'completed',
            'overdue', 'blocked', 'upcoming', 'totalCount', 'completionPct',
            'todayFocus'
        ));
    }
}
