<?php

namespace App\Services;

use App\Models\Project;
use App\Models\WbsItem;
use Illuminate\Support\Carbon;

class WbsScheduleCascadeService
{
    /**
     * Cascade and persist schedule dates for all WBS items in a project
     * based on hierarchical nesting (Months -> Weeks -> Days -> Hours).
     */
    public static function cascadeProjectSchedule(int $projectId, bool $force = false): int
    {
        $project = Project::find($projectId);
        if (!$project) return 0;

        $wbsItems = WbsItem::where('project_id', $projectId)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($wbsItems->isEmpty()) return 0;

        if (!$force) {
            // Check if items already have distinct manual dates set
            $datedItems = $wbsItems->filter(fn($i) => $i->start_date && $i->end_date);
            $uniqueStarts = $datedItems->map(fn($i) => $i->start_date->toDateString())->unique()->count();
            $uniqueEnds   = $datedItems->map(fn($i) => $i->end_date->toDateString())->unique()->count();
            $allSameDate  = $datedItems->count() > 1 && $uniqueStarts === 1 && $uniqueEnds === 1;

            // Only run automatic cascade if items have no dates or all share the template default single date
            if (!$allSameDate && $datedItems->isNotEmpty()) {
                return 0;
            }
        }

        $projectStart = $project->start_date 
            ? $project->start_date->copy()->startOfDay() 
            : now()->startOfDay();

        $childrenMap = [];
        foreach ($wbsItems as $item) {
            $pId = $item->parent_id ?? 0;
            $childrenMap[$pId][] = $item;
        }

        $updatedCount = 0;

        $assignHierarchyDates = function ($parentId, $startCursor) use (&$assignHierarchyDates, &$childrenMap, &$updatedCount) {
            $siblings = $childrenMap[$parentId] ?? [];
            $cursor = $startCursor->copy();
            $branchMin = null;
            $branchMax = null;

            foreach ($siblings as $item) {
                $hasChildren = !empty($childrenMap[$item->id]);
                $hasTimePattern = (bool) preg_match('/(\d{1,2}:\d{2}\s*(?:AM|PM))\s*[-–—\s]+\s*(\d{1,2}:\d{2}\s*(?:AM|PM))/iu', $item->title, $mTimes);
                $isHourSlot = ($hasTimePattern || !empty($item->start_time)) && !$hasChildren && !empty($parentId);

                if ($isHourSlot) {
                    // Hour slots stay on their parent's specific day
                    $item->start_date = $startCursor->copy();
                    $item->end_date   = $startCursor->copy();
                    if ($hasTimePattern) {
                        try {
                            $item->start_time = \Carbon\Carbon::parse($mTimes[1])->format('H:i');
                            $item->end_time   = \Carbon\Carbon::parse($mTimes[2])->format('H:i');
                        } catch (\Throwable $e) {}
                    }
                } elseif ($hasChildren) {
                    // Parent item (Month, Week, Day with hours)
                    $childStart = $cursor->copy();
                    [$cMin, $cMax] = $assignHierarchyDates($item->id, $childStart);

                    $item->start_date = $cMin ? $cMin->copy() : $childStart->copy();
                    $item->end_date   = $cMax ? $cMax->copy() : $childStart->copy();

                    // Cursor advances to next day after this container ends
                    $cursor = $item->end_date->copy()->addDay();
                } else {
                    // Leaf task (standard task or multi-day deliverable)
                    $dur = max(1, (int) ($item->duration ?? 1));
                    $item->start_date = $cursor->copy();
                    $item->end_date   = $cursor->copy()->addDays($dur - 1);

                    $cursor = $item->end_date->copy()->addDay();
                }

                $item->saveQuietly();
                $updatedCount++;

                if (!$branchMin || $item->start_date->lt($branchMin)) {
                    $branchMin = $item->start_date->copy();
                }
                if (!$branchMax || $item->end_date->gt($branchMax)) {
                    $branchMax = $item->end_date->copy();
                }
            }

            return [$branchMin, $branchMax];
        };

        $assignHierarchyDates(0, $projectStart);

        return $updatedCount;
    }

    /**
     * Automatically cascade and shift subsequent sibling task times
     * within the same day/parent when an item's start/end time is changed.
     */
    public static function cascadeTimeSlotsForSiblings(WbsItem $sourceItem): void
    {
        if (!$sourceItem->parent_id || empty($sourceItem->end_time)) {
            return;
        }

        // Get all siblings under the same parent ordered by sort_order, then id
        $siblings = WbsItem::where('parent_id', $sourceItem->parent_id)
            ->where('project_id', $sourceItem->project_id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($siblings->count() <= 1) {
            return;
        }

        $foundSource = false;
        $currentEndCursor = null;

        try {
            $sourceStart = \Carbon\Carbon::parse($sourceItem->start_time);
            $sourceEnd = \Carbon\Carbon::parse($sourceItem->end_time);
            if ($sourceEnd->lt($sourceStart)) {
                // If end time is before start time, default to start + 1 hour
                $sourceEnd = $sourceStart->copy()->addHour();
                $sourceItem->end_time = $sourceEnd->format('H:i');
                $sourceItem->saveQuietly();
            }
            $currentEndCursor = $sourceEnd->copy();
        } catch (\Throwable $e) {
            return;
        }

        foreach ($siblings as $sibling) {
            if ($sibling->id === $sourceItem->id) {
                $foundSource = true;
                continue;
            }

            // Only cascade subsequent siblings that come after the edited item
            if (!$foundSource) {
                continue;
            }

            // Determine sibling's duration
            $durationMinutes = 60;
            if (!empty($sibling->start_time) && !empty($sibling->end_time)) {
                try {
                    $sT = \Carbon\Carbon::parse($sibling->start_time);
                    $eT = \Carbon\Carbon::parse($sibling->end_time);
                    $diff = $sT->diffInMinutes($eT, false);
                    if ($diff > 0) {
                        $durationMinutes = $diff;
                    }
                } catch (\Throwable $e) {}
            }

            $newStartTime = $currentEndCursor->copy();
            $newEndTime   = $newStartTime->copy()->addMinutes($durationMinutes);

            $formattedStart = $newStartTime->format('h:i A');
            $formattedEnd   = $newEndTime->format('h:i A');

            $sibling->start_time = $newStartTime->format('H:i');
            $sibling->end_time   = $newEndTime->format('H:i');

            // If title matches a time range pattern (e.g. "09:30 AM – 10:30 AM"), update the title
            if (preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($sibling->title))) {
                $sibling->title = "{$formattedStart} – {$formattedEnd}";
            }

            $sibling->saveQuietly();

            // Advance cursor for next sibling
            $currentEndCursor = $newEndTime->copy();
        }

        // Update parent's start_time and end_time to envelope all children
        $parent = WbsItem::find($sourceItem->parent_id);
        if ($parent) {
            $allChildren = WbsItem::where('parent_id', $parent->id)->orderBy('sort_order')->orderBy('id')->get();
            $minStart = null;
            $maxEnd = null;
            foreach ($allChildren as $ch) {
                if ($ch->start_time) {
                    $cStart = \Carbon\Carbon::parse($ch->start_time);
                    if (!$minStart || $cStart->lt($minStart)) {
                        $minStart = $cStart;
                    }
                }
                if ($ch->end_time) {
                    $cEnd = \Carbon\Carbon::parse($ch->end_time);
                    if (!$maxEnd || $cEnd->gt($maxEnd)) {
                        $maxEnd = $cEnd;
                    }
                }
            }
            if ($minStart) $parent->start_time = $minStart->format('H:i');
            if ($maxEnd) $parent->end_time = $maxEnd->format('H:i');
            $parent->saveQuietly();
        }
    }
}
