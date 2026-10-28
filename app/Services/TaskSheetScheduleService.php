<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class TaskSheetScheduleService
{
    /** Plan once from the original schedule, in the same depth-first order as the sheet. */
    public function plan(Collection $items, array $drafts): array
    {
        $groups = $items->sortBy([['sort_order', 'asc'], ['id', 'asc']])->groupBy(fn ($item) => $item->parent_id ?? 0);
        $ordered = [];
        $walk = function ($parent) use (&$walk, &$ordered, $groups) {
            foreach ($groups->get($parent, collect()) as $item) {
                $ordered[] = $item;
                $walk($item->id);
            }
        };
        $walk(0);
        $dates = [];
        $explicit = [];
        $affected = [];
        $offset = 0;
        foreach ($ordered as $item) {
            $oldStart = $item->start_date?->toDateString();
            $oldEnd = $item->end_date?->toDateString();
            $draft = $drafts[$item->id] ?? [];
            $start = ($draft['start_date'] ?? null) ?: null;
            $end = ($draft['end_date'] ?? null) ?: null;
            $startChanged = array_key_exists('start_date', $draft) && $start !== $oldStart;
            $endChanged = array_key_exists('end_date', $draft) && $end !== $oldEnd;
            if ($startChanged || $endChanged) {
                // Explicit edits are absolute anchors; do not add earlier shifts twice.
                if ($startChanged && !$endChanged && $oldStart && $start) {
                    $offset = (int) Carbon::parse($oldStart)->diffInDays($start, false);
                    $end = $oldEnd ? Carbon::parse($oldEnd)->addDays($offset)->toDateString() : $end;
                } elseif ($oldEnd && $end) {
                    $offset = (int) Carbon::parse($oldEnd)->diffInDays($end, false);
                } elseif ($oldStart && $start) {
                    $offset = (int) Carbon::parse($oldStart)->diffInDays($start, false);
                }
                $dates[$item->id] = ['start_date' => $start, 'end_date' => $end];
                $explicit[$item->id] = true;
            } else {
                // Shift every following row, including nested tasks, without clamping
                // children to their summary deadline or losing their durations/gaps.
                $dates[$item->id] = [
                    'start_date' => $oldStart ? Carbon::parse($oldStart)->addDays($offset)->toDateString() : null,
                    'end_date' => $oldEnd ? Carbon::parse($oldEnd)->addDays($offset)->toDateString() : null,
                ];
            }
        }
        // Summaries enclose their children; retain dates explicitly entered by the user.
        foreach (array_reverse($ordered) as $item) {
            if (isset($explicit[$item->id])) continue;
            $children = $groups->get($item->id, collect());
            if ($children->isEmpty()) continue;
            $starts = $children->map(fn ($child) => $dates[$child->id]['start_date'])->filter();
            $ends = $children->map(fn ($child) => $dates[$child->id]['end_date'])->filter();
            if ($starts->isNotEmpty()) $dates[$item->id]['start_date'] = $starts->min();
            if ($ends->isNotEmpty()) $dates[$item->id]['end_date'] = $ends->max();
        }
        $updates = [];
        foreach ($ordered as $item) {
            $oldStart = $item->start_date?->toDateString();
            $oldEnd = $item->end_date?->toDateString();
            $new = $dates[$item->id];
            if ($new['start_date'] === $oldStart && $new['end_date'] === $oldEnd) continue;
            $updates[$item->id] = $new;
            $affected[] = ['id' => $item->id, 'wbs_code' => $item->wbs_code, 'title' => $drafts[$item->id]['title'] ?? $item->title,
                'old_start' => $oldStart, 'old_end' => $oldEnd, 'new_start' => $new['start_date'], 'new_end' => $new['end_date']];
        }
        return ['updates' => $updates, 'tasks' => $affected, 'deadline' => collect($dates)->pluck('end_date')->filter()->max()];
    }
}
