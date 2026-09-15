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
     * @param int     $daysDelta   Positive or negative number of days extended
     * @return array
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
                continue;
            }

            $oldStart = $task->start_date ? $task->start_date->copy() : null;
            $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;
            $oldStartTimeStr = $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '08:30';
            $oldEndTimeStr   = $task->end_time   ? \Carbon\Carbon::parse($task->end_time)->format('H:i')   : '17:30';

            $sourceNewEnd = $sourceTask->fresh()?->end_date;
            $durDays = ($oldStart && $oldEnd) ? max(0, (int) $oldStart->diffInDays($oldEnd)) : 0;

            if ($oldStart && $oldEnd) {
                if ($isInProgress) {
                    // For tasks in progress, keep start date but extend end date by daysDelta
                    $newStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr);
                    $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                    if ($newEndDt->lt($newStartDt)) {
                        $newEndDt = $newStartDt->copy()->addDay();
                    }
                } else {
                    // Shift both start_date and end_date by daysDelta
                    $newStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr)->addDays($daysDelta);
                    $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                }
            } elseif ($oldEnd) {
                $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                $newStartDt = $oldStart ? \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr)->addDays($daysDelta) : $newEndDt->copy();
            } else {
                continue;
            }

            // Ensure start date of following task is not earlier than source task's new end date
            if ($sourceNewEnd && !$isInProgress && $newStartDt->lt($sourceNewEnd)) {
                $newStartDt = $sourceNewEnd->copy()->addDay()->setTimeFrom(\Carbon\Carbon::parse($oldStartTimeStr));
                $newEndDt   = $newStartDt->copy()->addDays($durDays)->setTimeFrom(\Carbon\Carbon::parse($oldEndTimeStr));
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
        $sourceNewEnd = $sourceTask->fresh()->end_date;
        $maxAffectedEnd = null;
        foreach ($affectedTasks as $t) {
            if ($t['new_end_raw']) {
                if (!$maxAffectedEnd || $t['new_end_raw'] > $maxAffectedEnd) {
                    $maxAffectedEnd = $t['new_end_raw'];
                }
            }
        }

        $projectMaxEnd = WbsItem::where('project_id', $projectId)
            ->whereNotNull('end_date')
            ->max('end_date');

        $projectedDate = $maxAffectedEnd ?? ($sourceNewEnd ? $sourceNewEnd->toDateString() : $projectMaxEnd);

        // Project official deadline
        $project = Project::find($projectId);
        $officialDeadline = $project?->deadline ? $project->deadline->toDateString() : null;
        if (!$officialDeadline) {
            $officialDeadline = $projectMaxEnd;
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
            'varianceDays'       => abs((int) $varianceDays),
            'isBehindSchedule'   => $isBehindSchedule,
        ];
    }

    /**
     * Destructive: Apply cascade reschedule within a DB transaction.
     * Skips completed/cancelled tasks.
     * Preserves in-progress start dates.
     * Automatically updates parent container dates and official project deadline.
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

            foreach ($eligibleTasks as $task) {
                $statusValue = $task->status instanceof WbsStatus ? $task->status->value : (string) $task->status;
                $isCompleted = in_array($statusValue, ['completed', 'cancelled']);
                $isInProgress = $statusValue === 'in_progress';

                if ($isCompleted) {
                    continue;
                }

                $oldStart = $task->start_date ? $task->start_date->copy() : null;
                $oldEnd   = $task->end_date   ? $task->end_date->copy()   : null;
                $oldStartTimeStr = $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '08:30';
                $oldEndTimeStr   = $task->end_time   ? \Carbon\Carbon::parse($task->end_time)->format('H:i')   : '17:30';

                $sourceNewEnd = $sourceTask->fresh()?->end_date;
                $durDays = ($oldStart && $oldEnd) ? max(0, (int) $oldStart->diffInDays($oldEnd)) : 0;

                if ($oldStart && $oldEnd) {
                    if ($isInProgress) {
                        $newStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr);
                        $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                        if ($newEndDt->lt($newStartDt)) {
                            $newEndDt = $newStartDt->copy()->addDay();
                        }
                    } else {
                        $newStartDt = \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr)->addDays($daysDelta);
                        $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                    }
                } elseif ($oldEnd) {
                    $newEndDt   = \Carbon\Carbon::parse($oldEnd->format('Y-m-d') . ' ' . $oldEndTimeStr)->addDays($daysDelta);
                    $newStartDt = $oldStart ? \Carbon\Carbon::parse($oldStart->format('Y-m-d') . ' ' . $oldStartTimeStr)->addDays($daysDelta) : $newEndDt->copy();
                } else {
                    continue;
                }

                // Ensure start date of following task is not earlier than source task's new end date
                if ($sourceNewEnd && !$isInProgress && $newStartDt->lt($sourceNewEnd)) {
                    $newStartDt = $sourceNewEnd->copy()->addDay()->setTimeFrom(\Carbon\Carbon::parse($oldStartTimeStr));
                    $newEndDt   = $newStartDt->copy()->addDays($durDays)->setTimeFrom(\Carbon\Carbon::parse($oldEndTimeStr));
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

            // Recalculate parent phase/container boundaries from child dates
            $this->recalculatePhaseBoundaries($projectId);

            // Auto-update Project official deadline to sync with new overall maximum task schedule
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
     * Recalculates all parent container (phase, work package, container task) start/end dates from child tasks.
     */
    public function recalculatePhaseBoundaries(int $projectId): void
    {
        $parentIds = WbsItem::where('project_id', $projectId)
            ->whereNotNull('parent_id')
            ->distinct()
            ->pluck('parent_id')
            ->toArray();

        // Perform multiple passes to cascade parent dates up through multi-level hierarchies
        for ($pass = 0; $pass < 4; $pass++) {
            $parents = WbsItem::where('project_id', $projectId)
                ->where(function ($q) use ($parentIds) {
                    $q->whereIn('id', $parentIds)
                      ->orWhere('item_type', 'phase');
                })
                ->get();

            foreach ($parents as $parent) {
                $childMinStart = WbsItem::where('project_id', $projectId)
                    ->where(function ($q) use ($parent) {
                        $q->where('parent_id', $parent->id)
                          ->orWhere('wbs_code', 'like', $parent->wbs_code . '.%');
                    })
                    ->where('id', '!=', $parent->id)
                    ->whereNotNull('start_date')
                    ->min('start_date');

                $childMaxEnd = WbsItem::where('project_id', $projectId)
                    ->where(function ($q) use ($parent) {
                        $q->where('parent_id', $parent->id)
                          ->orWhere('wbs_code', 'like', $parent->wbs_code . '.%');
                    })
                    ->where('id', '!=', $parent->id)
                    ->whereNotNull('end_date')
                    ->max('end_date');

                if ($childMinStart || $childMaxEnd) {
                    $parent->update([
                        'start_date' => $childMinStart ?: $parent->start_date,
                        'end_date'   => $childMaxEnd   ?: $parent->end_date,
                    ]);
                }
            }
        }
    }

    /**
     * Find all eligible following tasks in sequential project order after sourceTask.
     * Includes explicit WbsDependency successors AND sequential tasks that come
     * after the source task in WBS order.
     */
    private function findEligibleFollowingTasks(WbsItem $sourceTask): \Illuminate\Support\Collection
    {
        $projectId = $sourceTask->project_id;
        $visited   = [$sourceTask->id => true];
        $tasksToShift = collect();

        // 1. Direct and indirect dependency-graph successors (BFS)
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

        // 2. All tasks in natural WBS code sequence after sourceTask
        $allProjectItems = WbsItem::where('project_id', $projectId)
            ->get()
            ->sort(fn($a, $b) => strnatcmp($a->wbs_code, $b->wbs_code))
            ->values();

        $sourceCode = $sourceTask->wbs_code;

        foreach ($allProjectItems as $item) {
            if (isset($visited[$item->id])) {
                continue;
            }

            // Skip top-level phases (recalculated from children)
            if ($item->item_type?->value === 'phase' || $item->item_type === 'phase') {
                continue;
            }

            // If item has children, skip direct shifting (its children will be shifted)
            $hasChildren = WbsItem::where('parent_id', $item->id)->exists();
            if ($hasChildren) {
                continue;
            }

            // Check if item's WBS code is sequentially after sourceTask's WBS code
            if (!empty($sourceCode) && !empty($item->wbs_code) && strnatcmp($item->wbs_code, $sourceCode) > 0) {
                $tasksToShift[$item->id] = $item;
                $visited[$item->id] = true;
            }
        }

        // 3. If sourceTask itself has children, include its leaf subtasks
        if (!empty($sourceCode)) {
            $sourceChildren = WbsItem::where('project_id', $projectId)
                ->where('wbs_code', 'like', $sourceCode . '.%')
                ->get();

            foreach ($sourceChildren as $child) {
                $hasSubChildren = WbsItem::where('parent_id', $child->id)->exists();
                if (!$hasSubChildren && !isset($visited[$child->id])) {
                    $tasksToShift[$child->id] = $child;
                    $visited[$child->id] = true;
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
