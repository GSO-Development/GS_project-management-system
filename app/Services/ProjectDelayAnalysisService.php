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
            'wbsItems.predecessors.predecessor',
            'wbsItems.successors.successor',
        ]);

        $today = now()->startOfDay();
        $status = is_object($project->status) ? $project->status->value : (string) $project->status;
        $projectOverdue = $project->deadline
            && Carbon::parse($project->deadline)->startOfDay()->lt($today)
            && ($project->overall_progress ?? 0) < 100
            && !in_array($status, ['completed', 'cancelled'], true);

        $allTasks = $project->wbsItems;
        $byId = $allTasks->keyBy('id');
        $childrenByParent = $allTasks->groupBy(fn ($t) => $t->parent_id ?? 0);

        // ── Helpers ─────────────────────────────────────────────────────────
        $statusOf = fn ($t) => is_object($t?->status) ? $t->status->value : (string) $t?->status;
        $isDone = fn ($t) => !$t
            || ($t->progress ?? 0) >= 100
            || in_array($statusOf($t), ['completed', 'cancelled'], true);
        $lateDays = function ($t) use ($today, $isDone) {
            if (!$t || !$t->end_date || $isDone($t)) return 0;
            $end = $t->end_date->copy()->startOfDay();
            return $end->lt($today) ? (int) $end->diffInDays($today) : 0;
        };
        $ownerOf = fn ($t) => [
            'name' => $t->assignedUser?->name ?? $project->projectManager?->name ?? 'Unassigned',
            'email' => $t->assignedUser?->email ?? $project->projectManager?->email,
            'assigned' => (bool) $t->assignedUser,
        ];

        // Walks upstream through unfinished predecessors and returns the
        // origin task(s) where the delay actually started.
        $findRoots = function ($task, array &$seen) use (&$findRoots, $byId, $isDone) {
            $roots = [];
            foreach ($task->predecessors as $dependency) {
                $predecessor = $byId->get($dependency->predecessor_id);
                if (!$predecessor || $isDone($predecessor) || isset($seen[$predecessor->id])) continue;
                $seen[$predecessor->id] = true;
                $upstream = $findRoots($predecessor, $seen);
                if ($upstream) {
                    $roots = array_merge($roots, $upstream);
                } else {
                    $roots[] = $predecessor;
                }
            }
            return $roots;
        };

        // ── Per-task issues ─────────────────────────────────────────────────
        $issues = $allTasks
            ->filter(function ($task) use ($today, $statusOf, $isDone, $lateDays) {
                $taskStatus = $statusOf($task);
                $overdue = $lateDays($task) > 0;
                $openBlocker = $task->blockers->contains(fn ($blocker) => $blocker->status === 'open');
                $incompletePredecessor = $task->predecessors->contains(fn ($dependency) => $dependency->predecessor && !$isDone($dependency->predecessor));
                $dependencyDelay = $incompletePredecessor
                    && $task->start_date
                    && $task->start_date->copy()->startOfDay()->lte($today)
                    && !$isDone($task);

                return $overdue || $taskStatus === 'blocked' || $taskStatus === 'on_hold' || $openBlocker || filled($task->delay_reason) || $dependencyDelay;
            })
            ->map(function ($task) use ($today, $project, $statusOf, $isDone, $lateDays, $ownerOf, $findRoots, $byId, $childrenByParent) {
                $taskStatus = $statusOf($task);
                $openBlocker = $task->blockers->first(fn ($blocker) => $blocker->status === 'open');
                $overdueDays = $lateDays($task);
                $progress = (int) ($task->progress ?? 0);

                $downstream = $task->successors
                    ->map(fn ($dependency) => $dependency->successor)
                    ->filter(fn ($successor) => $successor && ($successor->progress ?? 0) < 100)
                    ->values();
                $downstreamCount = $downstream->count();

                $incompleteDependencies = $task->predecessors
                    ->filter(fn ($dependency) => $dependency->predecessor && !$isDone($dependency->predecessor))
                    ->map(function ($dependency) use ($lateDays, $ownerOf, $statusOf) {
                        $predecessor = $dependency->predecessor;
                        $owner = $ownerOf($predecessor);

                        return [
                            'wbs_code' => $predecessor->wbs_code,
                            'task' => $predecessor->title,
                            'progress' => (int) ($predecessor->progress ?? 0),
                            'status' => $statusOf($predecessor),
                            'type' => $dependency->dependency_type,
                            'lag_days' => (int) ($dependency->lag_days ?? 0),
                            'overdue_days' => $lateDays($predecessor),
                            'responsible' => $owner['name'],
                            'responsible_email' => $owner['email'],
                        ];
                    })
                    ->values()
                    ->all();

                $seen = [$task->id => true];
                $rootCauses = collect($findRoots($task, $seen))
                    ->unique('id')
                    ->map(function ($root) use ($lateDays, $ownerOf) {
                        $owner = $ownerOf($root);

                        return [
                            'task_id' => $root->id,
                            'wbs_code' => $root->wbs_code,
                            'task' => $root->title,
                            'progress' => (int) ($root->progress ?? 0),
                            'overdue_days' => $lateDays($root),
                            'responsible' => $owner['name'],
                            'responsible_email' => $owner['email'],
                        ];
                    })
                    ->values()
                    ->all();

                $lateChildren = $childrenByParent->get($task->id, collect())
                    ->filter(fn ($child) => $lateDays($child) > 0)
                    ->sortByDesc(fn ($child) => $lateDays($child))
                    ->values();

                $openBlockers = $task->blockers
                    ->where('status', 'open')
                    ->map(fn ($blocker) => $blocker->description)
                    ->filter()
                    ->values()
                    ->all();

                // ── Causes, each tagged with the person who can resolve it ──
                $self = $ownerOf($task);
                $causes = [];
                if (filled($task->delay_reason)) {
                    $causes[] = ['type' => 'reported', 'label' => 'Reported issue', 'detail' => $task->delay_reason, 'responsible' => $self['name']];
                }
                foreach ($openBlockers as $blocker) {
                    $causes[] = ['type' => 'blocker', 'label' => 'Open blocker', 'detail' => $blocker, 'responsible' => $self['name']];
                }
                if ($taskStatus === 'blocked') {
                    $causes[] = ['type' => 'status', 'label' => 'Blocked status', 'detail' => 'This task is currently marked as blocked.', 'responsible' => $self['name']];
                }
                if ($taskStatus === 'on_hold') {
                    $causes[] = ['type' => 'status', 'label' => 'On hold', 'detail' => 'Work on this task is currently paused.', 'responsible' => $self['name']];
                }
                foreach ($incompleteDependencies as $dependency) {
                    $late = $dependency['overdue_days'] > 0;
                    $causes[] = [
                        'type' => 'dependency',
                        'label' => $late ? 'Delayed by dependency' : 'Waiting on dependency',
                        'detail' => '#'.$dependency['wbs_code'].' '.$dependency['task']
                            .($late
                                ? ' is '.$dependency['overdue_days'].' day(s) late ('.$dependency['progress'].'% complete), so this task cannot finish on time.'
                                : ' is only '.$dependency['progress'].'% complete.'),
                        'responsible' => $dependency['responsible'],
                    ];
                }
                foreach ($rootCauses as $root) {
                    $isDirect = collect($incompleteDependencies)->contains(fn ($d) => (string) $d['wbs_code'] === (string) $root['wbs_code']);
                    if ($isDirect) continue;
                    $causes[] = [
                        'type' => 'root',
                        'label' => 'Root cause upstream',
                        'detail' => 'The delay originates from #'.$root['wbs_code'].' '.$root['task']
                            .($root['overdue_days'] > 0 ? ' ('.$root['overdue_days'].' day(s) late)' : '').'.',
                        'responsible' => $root['responsible'],
                    ];
                }
                if ($lateChildren->isNotEmpty()) {
                    $worst = $lateChildren->first();
                    $worstOwner = $ownerOf($worst);
                    $causes[] = [
                        'type' => 'subtasks',
                        'label' => 'Late sub-tasks',
                        'detail' => $lateChildren->count().' sub-task(s) are late; worst is #'.$worst->wbs_code.' '.$worst->title.' ('.$lateDays($worst).' day(s) late).',
                        'responsible' => $worstOwner['name'],
                    ];
                }
                if ($overdueDays > 0) {
                    $causes[] = ['type' => 'schedule', 'label' => 'Missed deadline', 'detail' => "Planned end date passed {$overdueDays} day(s) ago with {$progress}% progress.", 'responsible' => $self['name']];
                } elseif ($task->start_date && $task->start_date->copy()->startOfDay()->lt($today) && $progress === 0 && !$isDone($task) && empty($incompleteDependencies)) {
                    $causes[] = ['type' => 'schedule', 'label' => 'Late start', 'detail' => 'Planned start date has passed but no progress has been recorded.', 'responsible' => $self['name']];
                }
                if (!$self['assigned']) {
                    $causes[] = ['type' => 'owner', 'label' => 'No owner assigned', 'detail' => 'No team member is assigned; the project manager is shown as responsible.', 'responsible' => $self['name']];
                }

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
                    'responsible' => $self['name'],
                    'responsible_email' => $self['email'],
                    'responsible_assigned' => $self['assigned'],
                    'overdue_days' => $overdueDays,
                    'downstream_count' => $downstreamCount,
                    'downstream_tasks' => $downstream->take(4)->map(fn ($s) => ['wbs_code' => $s->wbs_code, 'task' => $s->title])->all(),
                    'incomplete_dependencies' => $incompleteDependencies,
                    'root_causes' => $rootCauses,
                    'open_blockers' => $openBlockers,
                    'causes' => $causes,
                    'progress' => $progress,
                    'start_date' => $task->start_date?->format('M d, Y'),
                    'end_date' => $task->end_date?->format('M d, Y'),
                    'is_critical' => $taskStatus === 'blocked' || $downstreamCount > 0 || $overdueDays > 0 || count($incompleteDependencies) > 0,
                    'is_dependency_delay' => count($incompleteDependencies) > 0,
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
            'summary' => $this->buildSummary($project, $issues, $allTasks, $projectOverdue, $today, $lateDays),
            'issues' => $issues->all(),
        ];
    }

    /**
     * Project-level explanation: why the project is delayed, which root-cause
     * tasks are behind it, and who is responsible.
     */
    private function buildSummary(Project $project, $issues, $allTasks, bool $projectOverdue, Carbon $today, callable $lateDays): array
    {
        $reasons = [];
        $deadline = $project->deadline ? Carbon::parse($project->deadline)->startOfDay() : null;

        if ($projectOverdue) {
            $days = (int) $deadline->diffInDays($today);
            $reasons[] = [
                'type' => 'deadline',
                'label' => 'Deadline missed',
                'detail' => 'The project deadline ('.$deadline->format('M d, Y').") passed {$days} day(s) ago and overall progress is ".(int) ($project->overall_progress ?? 0).'%.',
            ];
        }

        $latestEnd = $allTasks->filter(fn ($t) => $t->end_date)->max(fn ($t) => $t->end_date->copy()->startOfDay()->timestamp);
        if ($deadline && $latestEnd && $latestEnd > $deadline->timestamp && !$projectOverdue) {
            $over = (int) $deadline->diffInDays(Carbon::createFromTimestamp($latestEnd)->startOfDay());
            $reasons[] = [
                'type' => 'deadline',
                'label' => 'Plan exceeds deadline',
                'detail' => 'The last planned task finishes '.$over.' day(s) after the project deadline.',
            ];
        }

        $lateTasks = $issues->filter(fn ($i) => ($i['overdue_days'] ?? 0) > 0 && !empty($i['task_id']));
        if ($lateTasks->isNotEmpty()) {
            $worst = $lateTasks->sortByDesc('overdue_days')->first();
            $reasons[] = [
                'type' => 'schedule',
                'label' => $lateTasks->count().' task(s) missed their end date',
                'detail' => 'Worst: #'.$worst['wbs_code'].' '.$worst['task'].' is '.$worst['overdue_days'].' day(s) late (owner: '.$worst['responsible'].').',
            ];
        }

        $dependencyIssues = $issues->filter(fn ($i) => !empty($i['is_dependency_delay']));
        if ($dependencyIssues->isNotEmpty()) {
            $reasons[] = [
                'type' => 'dependency',
                'label' => $dependencyIssues->count().' task(s) held up by dependencies',
                'detail' => 'These tasks depend on predecessors that are not finished, so they cannot complete on schedule.',
            ];
        }

        $blocked = $issues->filter(fn ($i) => in_array($i['status'] ?? '', ['blocked', 'on_hold'], true) || !empty($i['open_blockers']));
        if ($blocked->isNotEmpty()) {
            $reasons[] = [
                'type' => 'blocker',
                'label' => $blocked->count().' task(s) blocked or on hold',
                'detail' => 'Open blockers or a blocked/on-hold status are preventing progress.',
            ];
        }

        $unowned = $issues->filter(fn ($i) => array_key_exists('responsible_assigned', $i) && $i['responsible_assigned'] === false);
        if ($unowned->isNotEmpty()) {
            $reasons[] = [
                'type' => 'owner',
                'label' => $unowned->count().' delayed task(s) have no owner',
                'detail' => 'Assign a team member so someone is accountable for recovery.',
            ];
        }

        // Root-cause tasks, ranked by how many delayed tasks they cause.
        $roots = [];
        foreach ($issues as $issue) {
            foreach ($issue['root_causes'] ?? [] as $root) {
                $key = $root['task_id'];
                $roots[$key] ??= $root + ['affected' => 0];
                $roots[$key]['affected']++;
            }
        }
        $rootCauses = collect($roots)
            ->sortByDesc(fn ($r) => ($r['affected'] * 1000) + $r['overdue_days'])
            ->take(5)
            ->values()
            ->all();

        // Responsible people ranked by delayed workload.
        $people = [];
        foreach ($issues as $issue) {
            $name = $issue['responsible'] ?? 'Unassigned';
            $people[$name] ??= ['name' => $name, 'email' => $issue['responsible_email'] ?? null, 'task_count' => 0, 'max_days_late' => 0];
            $people[$name]['task_count']++;
            $people[$name]['max_days_late'] = max($people[$name]['max_days_late'], (int) ($issue['overdue_days'] ?? 0));
        }
        $responsible = collect($people)
            ->sortByDesc(fn ($p) => ($p['task_count'] * 1000) + $p['max_days_late'])
            ->take(5)
            ->values()
            ->all();

        return [
            'reasons' => $reasons,
            'root_causes' => $rootCauses,
            'responsible_people' => $responsible,
            'late_task_count' => $lateTasks->count(),
            'dependency_delay_count' => $dependencyIssues->count(),
            'blocked_count' => $blocked->count(),
        ];
    }
}
