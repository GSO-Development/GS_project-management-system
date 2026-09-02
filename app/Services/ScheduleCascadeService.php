<?php

namespace App\Services;

use App\Enums\WbsStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleCascadeService
{
    /**
     * Non-destructive: Calculate cascade impact without writing to DB.
     * Returns full preview data for the Impact Modal.
     *
     * @param WbsItem $sourceTask  The task whose deadline was extended
     * @param int     $daysDelta   Positive number of days extended
     * @return array{
     *     shiftDays: int,
     *     affectedTasks: array,
     *     skippedTasks: array,
     *     projectedCompletion: string|null,
     *     projectDeadline: string|null,
     *     varianceDays: int,
     *     isBehindSchedule: bool
     * }
     */
    public function calculateImpact(WbsItem $sourceTask, int $daysDelta): array
    {
        if ($daysDelta <= 0) {
            return $this->emptyImpact($daysDelta);
        }

        $projectId = $sourceTask->project_id;
        $eligibleTasks = $this->findEligibleFollowingTasks($sourceTask);

        $affectedTasks = [];
        $skippedTasks  = [];

        // CHAINED logic: each task's start = previous task's end + 1 day, duration preserved
        // Start chain from sourceTask's NEW end_date (already saved to DB)
        $chainPrevEnd = $sourceTask->fresh()->end_date; // Carbon instance of new deadline

        foreach ($eligibleTasks as $task) {
            $statusValue = $task->status instanceof WbsStatus ? $task->status->value : (string) $task->status;
            $isCompleted = in_array($statusValue, ['completed', 'cancelled']);
            $isInProgress = $statusValue === 'in_progress';

            if ($isCompleted) {
                $skippedTasks[] = [
                    'id'       => $task->id,
                    'wbs_code' => $task->wbs_code,
                    'title'    => $task->title,
                    'status'   => $statusValue,
                    'reason'   => 'Completed — historical dates preserved',
                ];
                // Completed tasks don't move, so chain continues from their original end
                if ($task->end_date) {
                    $chainPrevEnd = $task->end_date->copy();
                }
                continue;
            }

            $oldStart = $task->start_date ? $task->start_date->copy() : null;
            $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;

            // Original duration in days (e.g. 9 days = Sep 03 to Sep 12)
            $duration = ($oldStart && $oldEnd) ? (int) $oldStart->diffInDays($oldEnd) : 0;
            if ($duration < 0) $duration = 0;

            if ($isInProgress) {
                // In-progress: preserve actual start date, shift end by original delta
                $newStart = $oldStart;
                $newEnd   = $oldEnd ? $oldEnd->copy()->addDays($daysDelta) : null;
                // Chain continues from in-progress task's new end
                $chainPrevEnd = $newEnd ?? $chainPrevEnd;
            } else {
                // CHAINED: start = previous task end + 1 day, preserve duration
                $newStart = $chainPrevEnd ? $chainPrevEnd->copy()->addDay() : $oldStart;
                $newEnd   = $newStart ? $newStart->copy()->addDays($duration) : null;
                // Update chain for next task
                $chainPrevEnd = $newEnd ?? $chainPrevEnd;
            }

            $affectedTasks[] = [
                'id'               => $task->id,
                'wbs_code'         => $task->wbs_code,
                'title'            => $task->title,
                'status'           => $statusValue,
                'is_in_progress'   => $isInProgress,
                'old_start'        => $oldStart ? $oldStart->format('M d, Y') : null,
                'old_end'          => $oldEnd   ? $oldEnd->format('M d, Y')   : null,
                'new_start'        => $newStart  ? $newStart->format('M d, Y') : null,
                'new_end'          => $newEnd    ? $newEnd->format('M d, Y')   : null,
                'old_start_raw'    => $oldStart  ? $oldStart->toDateString()   : null,
                'old_end_raw'      => $oldEnd    ? $oldEnd->toDateString()     : null,
                'new_start_raw'    => $newStart  ? $newStart->toDateString()   : null,
                'new_end_raw'      => $newEnd    ? $newEnd->toDateString()     : null,
                'start_preserved'  => $isInProgress,
            ];
        }

        // Projected completion = max end_date across all tasks after cascade
        $projectMaxEnd = WbsItem::where('project_id', $projectId)
            ->where('id', '!=', $sourceTask->id)
            ->where('item_type', '!=', 'phase')
            ->whereNotNull('end_date')
            ->max('end_date');

        // Factor in the source task's new end_date (already saved before this call)
        $sourceNewEnd = $sourceTask->fresh()->end_date;

        // Also factor in the projected new end dates from affected tasks
        $maxAffectedEnd = null;
        foreach ($affectedTasks as $t) {
            if ($t['new_end_raw']) {
                if (!$maxAffectedEnd || $t['new_end_raw'] > $maxAffectedEnd) {
                    $maxAffectedEnd = $t['new_end_raw'];
                }
            }
        }

        $projectedDate = $maxAffectedEnd ?? ($sourceNewEnd ? $sourceNewEnd->toDateString() : $projectMaxEnd);

        // Project official deadline
        $project = Project::find($projectId);
        $officialDeadline = $project?->deadline ? $project->deadline->toDateString() : null;

        $varianceDays = 0;
        $isBehindSchedule = false;
        if ($projectedDate && $officialDeadline) {
            $varianceDays = Carbon::parse($officialDeadline)->diffInDays(Carbon::parse($projectedDate), false);
            $isBehindSchedule = $varianceDays > 0;
        }

        return [
            'shiftDays'          => $daysDelta,
            'affectedTasks'      => $affectedTasks,
            'skippedTasks'       => $skippedTasks,
            'projectedCompletion'=> $projectedDate  ? Carbon::parse($projectedDate)->format('M d, Y') : null,
            'projectDeadline'    => $officialDeadline ? Carbon::parse($officialDeadline)->format('M d, Y') : null,
            'varianceDays'       => abs($varianceDays),
            'isBehindSchedule'   => $isBehindSchedule,
        ];
    }

    /**
     * Destructive: Apply cascade reschedule within a DB transaction.
     * Skips completed/cancelled tasks.
     * Preserves in-progress start dates.
     * Does NOT change project deadline.
     * Records full audit log.
     *
     * @return array List of shifted tasks with metadata
     */
    public function applyReschedule(WbsItem $sourceTask, int $daysDelta): array
    {
        if ($daysDelta <= 0) {
            return [];
        }

        $shiftedTasks = [];

        DB::transaction(function () use ($sourceTask, $daysDelta, &$shiftedTasks) {
            $projectId = $sourceTask->project_id;
            $eligibleTasks = $this->findEligibleFollowingTasks($sourceTask);
            $reason = "Upstream task \"{$sourceTask->title}\" (WBS {$sourceTask->wbs_code}) was extended by {$daysDelta} " . ($daysDelta === 1 ? 'day' : 'days');

            // CHAINED logic: start from sourceTask's current (new) end_date
            $chainPrevEnd = $sourceTask->fresh()->end_date; // Carbon instance

            foreach ($eligibleTasks as $task) {
                $statusValue = $task->status instanceof WbsStatus ? $task->status->value : (string) $task->status;
                $isCompleted = in_array($statusValue, ['completed', 'cancelled']);
                $isInProgress = $statusValue === 'in_progress';

                // Never touch completed or cancelled tasks
                if ($isCompleted) {
                    // Chain continues from their original end date
                    if ($task->end_date) {
                        $chainPrevEnd = $task->end_date->copy();
                    }
                    continue;
                }

                $oldStart = $task->start_date ? $task->start_date->copy() : null;
                $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;

                // Preserve original duration in days
                $duration = ($oldStart && $oldEnd) ? (int) $oldStart->diffInDays($oldEnd) : 0;
                if ($duration < 0) $duration = 0;

                if ($isInProgress) {
                    // In-progress: preserve start, shift end by original delta
                    $newStart = $oldStart;
                    $newEnd   = $oldEnd ? $oldEnd->copy()->addDays($daysDelta) : null;
                    $chainPrevEnd = $newEnd ?? $chainPrevEnd;
                } else {
                    // CHAINED: start = previous task end + 1 day, preserve duration
                    $newStart = $chainPrevEnd ? $chainPrevEnd->copy()->addDay() : $oldStart;
                    $newEnd   = $newStart ? $newStart->copy()->addDays($duration) : null;
                    $chainPrevEnd = $newEnd ?? $chainPrevEnd;
                }

                $task->update([
                    'start_date'             => $newStart ? $newStart->toDateString() : null,
                    'end_date'               => $newEnd   ? $newEnd->toDateString()   : null,
                    'rescheduled_shift_days' => $daysDelta,
                    'rescheduled_at'         => now(),
                    'rescheduled_reason'     => $reason,
                ]);

                $shiftedTasks[] = [
                    'id'             => $task->id,
                    'wbs_code'       => $task->wbs_code,
                    'title'          => $task->title,
                    'old_start'      => $oldStart ? $oldStart->toDateString() : null,
                    'old_end'        => $oldEnd   ? $oldEnd->toDateString()   : null,
                    'new_start'      => $newStart ? $newStart->toDateString() : null,
                    'new_end'        => $newEnd   ? $newEnd->toDateString()   : null,
                    'start_preserved'=> $isInProgress,
                ];
            }

            // Recalculate parent phase boundaries
            $this->recalculatePhaseBoundaries($projectId);

            // Audit log
            if (!empty($shiftedTasks)) {
                ActivityLog::create([
                    'user_id'         => auth()->id(),
                    'action'          => 'cascade_rescheduled',
                    'module'          => 'wbs',
                    'record_type'     => WbsItem::class,
                    'record_id'       => $sourceTask->id,
                    'previous_values' => [
                        'source_task'  => $sourceTask->title,
                        'wbs_code'     => $sourceTask->wbs_code,
                        'days_shifted' => $daysDelta,
                        'affected'     => array_map(fn($t) => "WBS {$t['wbs_code']} ({$t['title']}): {$t['old_start']} to {$t['old_end']}", $shiftedTasks),
                    ],
                    'new_values' => [
                        'source_task'   => $sourceTask->title,
                        'wbs_code'      => $sourceTask->wbs_code,
                        'days_shifted'  => $daysDelta,
                        'tasks_shifted' => count($shiftedTasks),
                        'affected'      => array_map(fn($t) => "WBS {$t['wbs_code']} ({$t['title']}): {$t['new_start']} to {$t['new_end']}", $shiftedTasks),
                        'reason'        => $reason,
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });

        return $shiftedTasks;
    }

    /**
     * Recalculates all parent phase start/end dates from their child tasks.
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
                    'end_date'   => $childMaxEnd   ?: $phase->end_date,
                ]);
            }
        }
    }

    /**
     * Find all eligible following tasks in sequential project order after sourceTask.
     * Includes both explicit WbsDependency successors AND all sequential tasks that come
     * after the source task in the natural project WBS order.
     */
    private function findEligibleFollowingTasks(WbsItem $sourceTask): \Illuminate\Support\Collection
    {
        $projectId = $sourceTask->project_id;
        $visited   = [$sourceTask->id => true];
        $tasksToShift = collect();

        // 1. All non-phase tasks in natural WBS order
        $allTasks = WbsItem::where('project_id', $projectId)
            ->where('item_type', '!=', 'phase')
            ->get()
            ->sort(fn($a, $b) => strnatcmp($a->wbs_code, $b->wbs_code))
            ->values();

        $sourceIndex = $allTasks->search(fn($t) => $t->id === $sourceTask->id);

        // 2. Direct dependency-graph successors (BFS)
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

        // 3. All sequential tasks after source in project order
        if ($sourceIndex !== false) {
            for ($i = $sourceIndex + 1; $i < $allTasks->count(); $i++) {
                $subsequent = $allTasks[$i];
                if (!isset($visited[$subsequent->id])) {
                    $tasksToShift[$subsequent->id] = $subsequent;
                    $visited[$subsequent->id] = true;
                }
            }
        }

        return $tasksToShift->values();
    }

    /**
     * Helper: Return an empty impact result.
     */
    private function emptyImpact(int $daysDelta = 0): array
    {
        return [
            'shiftDays'          => $daysDelta,
            'affectedTasks'      => [],
            'skippedTasks'       => [],
            'projectedCompletion'=> null,
            'projectDeadline'    => null,
            'varianceDays'       => 0,
            'isBehindSchedule'   => false,
        ];
    }
}
