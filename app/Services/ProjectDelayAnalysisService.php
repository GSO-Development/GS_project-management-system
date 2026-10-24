<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;

class ProjectDelayAnalysisService
{
    public function analyze(Project $project): array
    {
        $project->loadMissing([
            'projectManager',
            'wbsItems.assignedUser',
            'wbsItems.blockers',
            'wbsItems.successors.successor',
        ]);

        $today = now()->startOfDay();
        $status = is_object($project->status) ? $project->status->value : (string) $project->status;
        $projectOverdue = $project->deadline
            && Carbon::parse($project->deadline)->startOfDay()->lt($today)
            && ($project->overall_progress ?? 0) < 100
            && !in_array($status, ['completed', 'cancelled'], true);

        $issues = $project->wbsItems
            ->filter(function ($task) use ($today) {
                $taskStatus = is_object($task->status) ? $task->status->value : (string) $task->status;
                $overdue = $task->end_date && $task->end_date->startOfDay()->lt($today)
                    && !in_array($taskStatus, ['completed', 'cancelled'], true);
                $openBlocker = $task->blockers->contains(fn ($blocker) => $blocker->status === 'open');

                return $overdue || $taskStatus === 'blocked' || $openBlocker || filled($task->delay_reason);
            })
            ->map(function ($task) use ($today, $project) {
                $taskStatus = is_object($task->status) ? $task->status->value : (string) $task->status;
                $openBlocker = $task->blockers->first(fn ($blocker) => $blocker->status === 'open');
                $overdueDays = $task->end_date && $task->end_date->startOfDay()->lt($today)
                    ? (int) $task->end_date->startOfDay()->diffInDays($today)
                    : 0;
                $downstreamCount = $task->successors
                    ->filter(fn ($dependency) => ($dependency->successor?->progress ?? 0) < 100)
                    ->count();

                $reason = $task->delay_reason
                    ?: $openBlocker?->description
                    ?: ($taskStatus === 'blocked'
                        ? 'Task is marked as blocked; a detailed blocker reason has not been recorded.'
                        : "Task missed its planned end date by {$overdueDays} day(s).");

                return [
                    'task_id' => $task->id,
                    'wbs_code' => $task->wbs_code,
                    'task' => $task->title,
                    'reason' => $reason,
                    'responsible' => $task->assignedUser?->name ?? $project->projectManager?->name ?? 'Unassigned',
                    'responsible_email' => $task->assignedUser?->email ?? $project->projectManager?->email,
                    'overdue_days' => $overdueDays,
                    'downstream_count' => $downstreamCount,
                    'is_critical' => $taskStatus === 'blocked' || $downstreamCount > 0 || $overdueDays > 0,
                    'status' => $taskStatus,
                ];
            })
            ->sortByDesc(fn ($issue) => ($issue['is_critical'] ? 100000 : 0) + ($issue['overdue_days'] * 100) + $issue['downstream_count'])
            ->values();

        if ($projectOverdue && $issues->isEmpty()) {
            $issues->push([
                'task_id' => null,
                'wbs_code' => null,
                'task' => 'Project deadline',
                'reason' => 'The project deadline has passed, but no delayed WBS task or blocker reason has been recorded.',
                'responsible' => $project->projectManager?->name ?? 'Unassigned',
                'responsible_email' => $project->projectManager?->email,
                'overdue_days' => (int) Carbon::parse($project->deadline)->startOfDay()->diffInDays($today),
                'downstream_count' => 0,
                'is_critical' => true,
                'status' => 'delayed',
            ]);
        }

        return [
            // A project is delayed only when its own deadline has passed while
            // incomplete, or when it has explicitly been marked as delayed.
            // Internal overdue/blocked tasks are reported separately and must
            // not turn a project with a future deadline into a delayed project.
            'is_delayed' => $projectOverdue || $status === 'delayed',
            'has_task_delays' => $issues->isNotEmpty(),
            'project_overdue_days' => $projectOverdue
                ? (int) Carbon::parse($project->deadline)->startOfDay()->diffInDays($today)
                : 0,
            'critical_issue_count' => $issues->where('is_critical', true)->count(),
            'issues' => $issues->all(),
        ];
    }
}
