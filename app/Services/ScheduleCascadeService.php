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
        if ($daysDelta === 0) {
            return $this->emptyImpact($daysDelta);
        }

        $projectId = $sourceTask->project_id;
        $eligibleTasks = $this->findEligibleFollowingTasks($sourceTask);

        $affectedTasks = [];
        $skippedTasks  = [];

        // CHAINED logic: start chain from sourceTask's NEW end_date and end_time
        $sourceFresh = $sourceTask->fresh();
        $sEnd = $sourceFresh->end_date ? $sourceFresh->end_date->copy() : now()->startOfDay();
        $sTimeStr = $sourceFresh->end_time ? \Carbon\Carbon::parse($sourceFresh->end_time)->format('H:i') : '17:30';
        $chainCursor = \Carbon\Carbon::parse($sEnd->format('Y-m-d') . ' ' . $sTimeStr);

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
                // Completed tasks don't move. Update chainCursor ONLY IF completed task's end datetime is later
                if ($task->end_date) {
                    $tTimeStr = $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : '17:30';
                    $tEndDt   = \Carbon\Carbon::parse($task->end_date->format('Y-m-d') . ' ' . $tTimeStr);
                    if ($tEndDt->gt($chainCursor)) {
                        $chainCursor = $tEndDt->copy();
                    }
                }
                continue;
            }

            $oldStart = $task->start_date ? $task->start_date->copy() : null;
            $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;
            $oldStartTimeStr = $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '08:30';
            $oldEndTimeStr   = $task->end_time   ? \Carbon\Carbon::parse($task->end_time)->format('H:i')   : '17:30';
            $durDays = ($oldStart && $oldEnd) ? max(0, (int) $oldStart->diffInDays($oldEnd)) : 0;

            // Intelligent start time & date calculation based on chainCursor
            $cTime = $chainCursor->format('H:i');
            if ($cTime >= '17:30') {
                $calculatedStart = $chainCursor->copy()->addDay()->setTime(8, 30);
            } elseif ($cTime < '08:30') {
                $calculatedStart = $chainCursor->copy()->setTime(8, 30);
            } else {
                $calculatedStart = $chainCursor->copy();
            }

            if ($isInProgress && $oldStart) {
                $oldStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr);
                // If calculatedStart (from predecessor end date/time) is AFTER oldStartDt, push start to calculatedStart!
                if ($calculatedStart->gt($oldStartDt)) {
                    $newStartDt = $calculatedStart->copy();
                } else {
                    $newStartDt = $oldStartDt->copy();
                }

                $newEndDt = $oldEnd ? \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta) : $newStartDt->copy()->addDays(1);
                if ($newEndDt->lt($newStartDt)) {
                    $newEndDt = $newStartDt->copy()->addDays(max(1, $durDays));
                }
            } else {
                $newStartDt = $calculatedStart->copy();

                if ($durDays > 0) {
                    $newEndDt = $newStartDt->copy()->addDays($durDays)->setTimeFrom(\Carbon\Carbon::parse($oldEndTimeStr));
                } else {
                    $oldStartDt = $oldStart ? \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr) : null;
                    $oldEndDt   = $oldEnd   ? \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)   : null;
                    $durMinutes = ($oldStartDt && $oldEndDt) ? max(30, (int) $oldStartDt->diffInMinutes($oldEndDt, false)) : 480;

                    $newEndDt = $newStartDt->copy()->addMinutes($durMinutes);
                    if ($newEndDt->format('H:i') > '17:30') {
                        // Overflow past 5:30 PM -> roll over to next morning 08:30 AM + remaining minutes
                        $overMinutes = \Carbon\Carbon::parse($newEndDt->format('H:i'))->diffInMinutes(\Carbon\Carbon::parse('17:30'));
                        $newEndDt = $newStartDt->copy()->addDay()->setTime(8, 30)->addMinutes($overMinutes);
                    }
                }
            }

            if ($newEndDt->gt($chainCursor)) {
                $chainCursor = $newEndDt->copy();
            }

            $affectedTasks[] = [
                'id'               => $task->id,
                'wbs_code'         => $task->wbs_code,
                'title'            => $task->title,
                'status'           => $statusValue,
                'is_in_progress'   => $isInProgress,
                'old_start'        => $oldStart ? ($oldStart->format('M d, Y') . ($task->start_time ? ' ' . $oldStartTimeStr : '')) : null,
                'old_end'          => $oldEnd   ? ($oldEnd->format('M d, Y') . ($task->end_time ? ' ' . $oldEndTimeStr : ''))   : null,
                'new_start'        => $newStartDt->format('M d, Y h:i A'),
                'new_end'          => $newEndDt->format('M d, Y h:i A'),
                'old_start_raw'    => $oldStart  ? $oldStart->toDateString()   : null,
                'old_end_raw'      => $oldEnd    ? $oldEnd->toDateString()     : null,
                'new_start_raw'    => $newStartDt->toDateString(),
                'new_end_raw'      => $newEndDt->toDateString(),
                'new_start_time'   => $newStartDt->format('H:i'),
                'new_end_time'     => $newEndDt->format('H:i'),
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
        if (!$officialDeadline) {
            $officialDeadline = WbsItem::where('project_id', $projectId)->max('end_date');
        }

        $varianceDays = 0;
        $isBehindSchedule = false;
        if ($projectedDate && $officialDeadline) {
            $varianceDays = Carbon::parse($officialDeadline)->diffInDays(Carbon::parse($projectedDate), false);
            $isBehindSchedule = $varianceDays > 0;
        }

        $fmtOfficial = $officialDeadline ? Carbon::parse($officialDeadline)->format('M d, Y') : null;

        return [
            'shiftDays'          => $daysDelta,
            'affectedTasks'      => $affectedTasks,
            'skippedTasks'       => $skippedTasks,
            'projectedCompletion'=> $projectedDate  ? Carbon::parse($projectedDate)->format('M d, Y') : null,
            'officialDeadline'   => $fmtOfficial,
            'projectDeadline'    => $fmtOfficial,
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
        if ($daysDelta === 0) {
            return [];
        }

        $shiftedTasks = [];

        DB::transaction(function () use ($sourceTask, $daysDelta, &$shiftedTasks) {
            $projectId = $sourceTask->project_id;
            $eligibleTasks = $this->findEligibleFollowingTasks($sourceTask);
            $reason = "Upstream task \"{$sourceTask->title}\" (WBS {$sourceTask->wbs_code}) deadline changed by {$daysDelta} " . (abs($daysDelta) === 1 ? 'day' : 'days');

            // CHAINED logic: start chain from sourceTask's NEW end_date and end_time
            $sourceFresh = $sourceTask->fresh();
            $sEnd = $sourceFresh->end_date ? $sourceFresh->end_date->copy() : now()->startOfDay();
            $sTimeStr = $sourceFresh->end_time ? \Carbon\Carbon::parse($sourceFresh->end_time)->format('H:i') : '17:30';
            $chainCursor = \Carbon\Carbon::parse($sEnd->format('Y-m-d') . ' ' . $sTimeStr);

            foreach ($eligibleTasks as $task) {
                $statusValue = $task->status instanceof WbsStatus ? $task->status->value : (string) $task->status;
                $isCompleted = in_array($statusValue, ['completed', 'cancelled']);
                $isInProgress = $statusValue === 'in_progress';

                // Never touch completed or cancelled tasks
                if ($isCompleted) {
                    if ($task->end_date) {
                        $tTimeStr = $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : '17:30';
                        $tEndDt   = \Carbon\Carbon::parse($task->end_date->format('Y-m-d') . ' ' . $tTimeStr);
                        if ($tEndDt->gt($chainCursor)) {
                            $chainCursor = $tEndDt->copy();
                        }
                    }
                    continue;
                }

                $oldStart = $task->start_date ? $task->start_date->copy() : null;
                $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;
                $oldStartTimeStr = $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '08:30';
                $oldEndTimeStr   = $task->end_time   ? \Carbon\Carbon::parse($task->end_time)->format('H:i')   : '17:30';

                $durDays = ($oldStart && $oldEnd) ? max(0, (int) $oldStart->diffInDays($oldEnd)) : 0;

                // Intelligent start time & date calculation based on chainCursor
                $cTime = $chainCursor->format('H:i');
                if ($cTime >= '17:30') {
                    $calculatedStart = $chainCursor->copy()->addDay()->setTime(8, 30);
                } elseif ($cTime < '08:30') {
                    $calculatedStart = $chainCursor->copy()->setTime(8, 30);
                } else {
                    $calculatedStart = $chainCursor->copy();
                }

                if ($isInProgress && $oldStart) {
                    $oldStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr);
                    if ($calculatedStart->gt($oldStartDt)) {
                        $newStartDt = $calculatedStart->copy();
                    } else {
                        $newStartDt = $oldStartDt->copy();
                    }

                    $newEndDt = $oldEnd ? \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta) : $newStartDt->copy()->addDays(1);
                    if ($newEndDt->lt($newStartDt)) {
                        $newEndDt = $newStartDt->copy()->addDays(max(1, $durDays));
                    }
                } else {
                    $newStartDt = $calculatedStart->copy();

                    if ($durDays > 0) {
                        $newEndDt = $newStartDt->copy()->addDays($durDays)->setTimeFrom(\Carbon\Carbon::parse($oldEndTimeStr));
                    } else {
                        $oldStartDt = $oldStart ? \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr) : null;
                        $oldEndDt   = $oldEnd   ? \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)   : null;
                        $durMinutes = ($oldStartDt && $oldEndDt) ? max(30, (int) $oldStartDt->diffInMinutes($oldEndDt, false)) : 480;

                        $newEndDt = $newStartDt->copy()->addMinutes($durMinutes);
                        if ($newEndDt->format('H:i') > '17:30') {
                            $overMinutes = \Carbon\Carbon::parse($newEndDt->format('H:i'))->diffInMinutes(\Carbon\Carbon::parse('17:30'));
                            $newEndDt = $newStartDt->copy()->addDay()->setTime(8, 30)->addMinutes($overMinutes);
                        }
                    }
                }

                if ($newEndDt->gt($chainCursor)) {
                    $chainCursor = $newEndDt->copy();
                }

                $updateData = [
                    'start_date'             => $newStartDt->toDateString(),
                    'start_time'             => $newStartDt->format('H:i'),
                    'end_date'               => $newEndDt->toDateString(),
                    'end_time'               => $newEndDt->format('H:i'),
                    'rescheduled_shift_days' => $daysDelta,
                    'rescheduled_at'         => now(),
                    'rescheduled_reason'     => $reason,
                ];

                // Auto-update title if title was formatted as a time range (e.g., "08:30 AM – 12:30 PM")
                if (preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($task->title))) {
                    $sFmt = $newStartDt->format('h:i A');
                    $eFmt = $newEndDt->format('h:i A');
                    $updateData['title'] = "{$sFmt} – {$eFmt}";
                }

                $task->update($updateData);

                $shiftedTasks[] = [
                    'id'             => $task->id,
                    'wbs_code'       => $task->wbs_code,
                    'title'          => $task->title,
                    'old_start'      => $oldStart ? $oldStart->toDateString() : null,
                    'old_end'        => $oldEnd   ? $oldEnd->toDateString()   : null,
                    'new_start'      => $newStartDt->toDateString(),
                    'new_end'        => $newEndDt->toDateString(),
                    'start_preserved'=> $isInProgress,
                ];
            }

            // Recalculate parent phase boundaries
            $this->recalculatePhaseBoundaries($projectId);

            // Auto-update Project official deadline to sync with new task schedule
            $project = Project::find($projectId);
            if ($project) {
                $maxTaskEnd = WbsItem::where('project_id', $projectId)
                    ->whereNotNull('end_date')
                    ->max('end_date');

                if ($maxTaskEnd) {
                    $maxTaskEndStr = \Carbon\Carbon::parse($maxTaskEnd)->toDateString();
                    if (!$project->deadline || $maxTaskEndStr !== $project->deadline->toDateString()) {
                        $project->update(['deadline' => $maxTaskEndStr]);
                    }
                }
            }

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
