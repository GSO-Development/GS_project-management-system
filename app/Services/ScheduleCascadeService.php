<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use Carbon\Carbon;

class ScheduleCascadeService
{
    /**
     * Cascades schedule adjustments from a modified task to all downstream / subsequent tasks.
     *
     * @param WbsItem $sourceTask The task whose deadline was extended
     * @param int $daysDelta Positive number of days shifted forward
     * @return array List of shifted tasks with metadata
     */
    public function cascadeFromTask(WbsItem $sourceTask, int $daysDelta): array
    {
        if ($daysDelta <= 0) {
            return [];
        }

        $projectId = $sourceTask->project_id;
        $shiftedTasks = [];
        $visited = [$sourceTask->id => true];

        // 1. Fetch all tasks in the project sorted naturally by wbs_code
        $allTasks = WbsItem::where('project_id', $projectId)
            ->where('item_type', '!=', 'phase')
            ->get()
            ->sort(function ($a, $b) {
                return strnatcmp($a->wbs_code, $b->wbs_code);
            })
            ->values();

        // Find the index of the source task in the sequential project flow
        $sourceIndex = $allTasks->search(fn($t) => $t->id === $sourceTask->id);

        // 2. Identify all subsequent tasks that need shifting
        // (both explicit successors in WbsDependency AND all sequential subsequent tasks in the project)
        $tasksToShift = [];

        // Direct & indirect dependency graph successors
        $queue = new \SplQueue();
        $directDeps = WbsDependency::where('predecessor_id', $sourceTask->id)->get();
        foreach ($directDeps as $dep) {
            $successor = WbsItem::find($dep->successor_id);
            if ($successor && !isset($visited[$successor->id])) {
                $queue->enqueue($successor);
                $visited[$successor->id] = true;
            }
        }
        while (!$queue->isEmpty()) {
            $t = $queue->dequeue();
            $tasksToShift[$t->id] = $t;
            $nextDeps = WbsDependency::where('predecessor_id', $t->id)->get();
            foreach ($nextDeps as $nd) {
                $ns = WbsItem::find($nd->successor_id);
                if ($ns && !isset($visited[$ns->id])) {
                    $queue->enqueue($ns);
                    $visited[$ns->id] = true;
                }
            }
        }

        // Sequential subsequent tasks in the project flow (after sourceIndex)
        if ($sourceIndex !== false) {
            for ($i = $sourceIndex + 1; $i < $allTasks->count(); $i++) {
                $subsequent = $allTasks[$i];
                if (!isset($visited[$subsequent->id])) {
                    $tasksToShift[$subsequent->id] = $subsequent;
                    $visited[$subsequent->id] = true;
                }
            }
        }

        // 3. Shift each identified downstream task forward
        foreach ($tasksToShift as $task) {
            $oldStart = $task->start_date ? $task->start_date->copy() : null;
            $oldEnd   = $task->end_date ? $task->end_date->copy() : null;

            $newStart = $oldStart ? $oldStart->copy()->addDays($daysDelta) : null;
            $newEnd   = $oldEnd ? $oldEnd->copy()->addDays($daysDelta) : ($newStart ? $newStart->copy()->addDays(max(1, $task->duration ?: 1)) : null);

            $task->update([
                'start_date' => $newStart ? $newStart->toDateString() : null,
                'end_date'   => $newEnd ? $newEnd->toDateString() : null,
            ]);

            $shiftedTasks[] = [
                'id'        => $task->id,
                'wbs_code'  => $task->wbs_code,
                'title'     => $task->title,
                'old_start' => $oldStart ? $oldStart->format('Y-m-d') : null,
                'old_end'   => $oldEnd ? $oldEnd->format('Y-m-d') : null,
                'new_start' => $newStart ? $newStart->format('Y-m-d') : null,
                'new_end'   => $newEnd ? $newEnd->format('Y-m-d') : null,
            ];
        }

        // 4. Recalculate Parent Phase Date Boundaries (start_date = min child, end_date = max child)
        $this->recalculatePhaseBoundaries($projectId);

        // 5. Expand overall Project Deadline if last task pushes past current deadline
        $this->syncProjectDeadline($projectId);

        // 6. Record Audit Log for Governance
        if (!empty($shiftedTasks)) {
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'auto_cascaded_schedule',
                'module'      => 'wbs',
                'record_type' => WbsItem::class,
                'record_id'   => $sourceTask->id,
                'new_values'  => [
                    'source_task'   => $sourceTask->title,
                    'wbs_code'      => $sourceTask->wbs_code,
                    'days_shifted'  => $daysDelta,
                    'tasks_shifted' => count($shiftedTasks),
                    'affected_tasks'=> array_map(fn($t) => "WBS {$t['wbs_code']} ({$t['title']}): {$t['new_start']} to {$t['new_end']}", $shiftedTasks),
                ],
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        }

        return $shiftedTasks;
    }

    /**
     * Recalculates all parent phase start/end dates from their constituent child tasks.
     */
    public function recalculatePhaseBoundaries(int $projectId): void
    {
        $phases = WbsItem::where('project_id', $projectId)
            ->where('item_type', 'phase')
            ->get();

        foreach ($phases as $phase) {
            $childMinStart = WbsItem::where('project_id', $projectId)
                ->where('wbs_code', 'like', $phase->wbs_code . '.%')
                ->where('item_type', '!=', 'phase')
                ->whereNotNull('start_date')
                ->min('start_date');

            $childMaxEnd = WbsItem::where('project_id', $projectId)
                ->where('wbs_code', 'like', $phase->wbs_code . '.%')
                ->where('item_type', '!=', 'phase')
                ->whereNotNull('end_date')
                ->max('end_date');

            if ($childMinStart || $childMaxEnd) {
                $phase->update([
                    'start_date' => $childMinStart ?: $phase->start_date,
                    'end_date'   => $childMaxEnd ?: $phase->end_date,
                ]);
            }
        }
    }

    /**
     * Synchronizes Project deadline if WBS items extend past it.
     */
    public function syncProjectDeadline(int $projectId): void
    {
        $project = Project::find($projectId);
        if (!$project) return;

        $maxTaskEnd = WbsItem::where('project_id', $projectId)->max('end_date');
        if ($maxTaskEnd && (!$project->deadline || Carbon::parse($maxTaskEnd)->gt($project->deadline))) {
            $project->update(['deadline' => $maxTaskEnd]);
        }
    }
}
