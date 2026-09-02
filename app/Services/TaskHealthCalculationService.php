<?php

namespace App\Services;

use App\Models\WbsItem;
use Carbon\Carbon;

class TaskHealthCalculationService
{
    /**
     * Calculate RAG (Red / Amber / Green / Gray) traffic light status for a WbsItem.
     *
     * @param WbsItem $item
     * @return array{
     *   status: string,
     *   label: string,
     *   tag: string,
     *   time_diff_text: string,
     *   badge_class: string,
     *   dot_class: string,
     *   reason: string,
     *   active_lamp: string,
     *   is_overdue: bool
     * }
     */
    public static function calculate(WbsItem $item): array
    {
        $statusValue = $item->status ? $item->status->value : 'not_started';
        $progress = (int) ($item->progress ?? 0);
        $now = Carbon::now();

        // 1. Resolve exact Start and End DateTimes with granular times
        $startDateTime = null;
        if ($item->start_date) {
            $sDateStr = $item->start_date instanceof Carbon ? $item->start_date->format('Y-m-d') : substr((string) $item->start_date, 0, 10);
            $sTimeStr = $item->start_time ?: '00:00:00';
            try {
                $startDateTime = Carbon::parse("{$sDateStr} {$sTimeStr}");
            } catch (\Throwable $e) {
                $startDateTime = Carbon::parse($sDateStr)->startOfDay();
            }
        }

        $endDateTime = null;
        if ($item->end_date) {
            $eDateStr = $item->end_date instanceof Carbon ? $item->end_date->format('Y-m-d') : substr((string) $item->end_date, 0, 10);
            $eTimeStr = $item->end_time ?: '23:59:59';
            try {
                $endDateTime = Carbon::parse("{$eDateStr} {$eTimeStr}");
            } catch (\Throwable $e) {
                $endDateTime = Carbon::parse($eDateStr)->endOfDay();
            }
        }

        // ══════════════════════════════════════════════════════════
        // 1. COMPLETED TASK -> 🟢 GREEN
        // ══════════════════════════════════════════════════════════
        if ($statusValue === 'completed' || $progress >= 100) {
            return [
                'status'         => 'green',
                'active_lamp'    => 'green',
                'label'          => 'Completed',
                'tag'            => 'DONE',
                'time_diff_text' => '100% Done',
                'badge_class'    => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                'dot_class'      => 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.8)]',
                'reason'         => 'Task completed successfully',
                'is_overdue'     => false,
            ];
        }

        // ══════════════════════════════════════════════════════════
        // 2. BLOCKED TASK -> 🔴 RED
        // ══════════════════════════════════════════════════════════
        if ($statusValue === 'blocked') {
            return [
                'status'         => 'red',
                'active_lamp'    => 'red',
                'label'          => 'Blocked',
                'tag'            => 'BLOCKED',
                'time_diff_text' => 'Action Needed',
                'badge_class'    => 'bg-rose-50 text-rose-800 border-rose-300',
                'dot_class'      => 'bg-rose-600 animate-ping shadow-[0_0_12px_rgba(225,29,72,0.9)]',
                'reason'         => 'Work blocked by impediment / external dependency',
                'is_overdue'     => false,
            ];
        }

        // ══════════════════════════════════════════════════════════
        // 3. OVERDUE TASK (Exact Deadline Date & Time Passed) -> 🔴 RED
        // ══════════════════════════════════════════════════════════
        if ($endDateTime && $now->greaterThan($endDateTime)) {
            $diffHours = (int) $endDateTime->diffInHours($now);
            $diffDays = (int) $endDateTime->diffInDays($now);

            if ($diffDays > 0) {
                $timeText = "-{$diffDays}d Over";
                $reasonText = "Overdue by {$diffDays} " . ($diffDays === 1 ? 'day' : 'days') . " ({$progress}% completed)";
            } elseif ($diffHours > 0) {
                $timeText = "-{$diffHours}h Over";
                $reasonText = "Deadline passed {$diffHours} " . ($diffHours === 1 ? 'hour' : 'hours') . " ago ({$progress}% completed)";
            } else {
                $diffMins = max(1, (int) $endDateTime->diffInMinutes($now));
                $timeText = "-{$diffMins}m Over";
                $reasonText = "Deadline passed {$diffMins} minutes ago ({$progress}% completed)";
            }

            return [
                'status'         => 'red',
                'active_lamp'    => 'red',
                'label'          => 'Delayed (Overdue)',
                'tag'            => 'OVERDUE',
                'time_diff_text' => $timeText,
                'badge_class'    => 'bg-rose-50 text-rose-900 border-rose-300 ring-1 ring-rose-400/30',
                'dot_class'      => 'bg-rose-600 animate-pulse shadow-[0_0_12px_rgba(225,29,72,0.95)]',
                'reason'         => $reasonText,
                'is_overdue'     => true,
            ];
        }

        // ══════════════════════════════════════════════════════════
        // 4. CRITICAL ASSOCIATED RISKS -> 🔴 RED
        // ══════════════════════════════════════════════════════════
        if ($item->relationLoaded('risks') && $item->risks && $item->risks->where('status', 'open')->whereIn('impact', ['high', 'critical'])->count() > 0) {
            return [
                'status'         => 'red',
                'active_lamp'    => 'red',
                'label'          => 'Critical Risk',
                'tag'            => 'RISK ⚠️',
                'time_diff_text' => 'High Risk',
                'badge_class'    => 'bg-rose-50 text-rose-800 border-rose-300',
                'dot_class'      => 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.8)]',
                'reason'         => 'Open critical risk detected on deliverable',
                'is_overdue'     => false,
            ];
        }

        // ══════════════════════════════════════════════════════════
        // 5. IMMINENT DEADLINE (Due within 48 hours with low progress) -> 🟡 AMBER
        // ══════════════════════════════════════════════════════════
        if ($endDateTime && $now->lessThanOrEqualTo($endDateTime)) {
            $hoursLeft = (int) $now->diffInHours($endDateTime);
            $daysLeft = (int) $now->diffInDays($endDateTime);

            if ($hoursLeft <= 48 && $progress < 80) {
                if ($hoursLeft < 24) {
                    $timeText = $hoursLeft === 0 ? "Due now" : "Due in {$hoursLeft}h";
                } else {
                    $timeText = "Due in {$daysLeft}d";
                }

                return [
                    'status'         => 'amber',
                    'active_lamp'    => 'amber',
                    'label'          => 'At Risk (Due Soon)',
                    'tag'            => 'DUE SOON',
                    'time_diff_text' => $timeText,
                    'badge_class'    => 'bg-amber-50 text-amber-900 border-amber-300 ring-1 ring-amber-400/30',
                    'dot_class'      => 'bg-amber-500 animate-pulse shadow-[0_0_10px_rgba(245,158,11,0.9)]',
                    'reason'         => "Due in {$hoursLeft} hours with only {$progress}% completed",
                    'is_overdue'     => false,
                ];
            }
        }

        // ══════════════════════════════════════════════════════════
        // 6. SCHEDULE LAG CHECK (Time elapsed > progress)
        // ══════════════════════════════════════════════════════════
        if ($startDateTime && $endDateTime && $startDateTime->lessThanOrEqualTo($now) && $endDateTime->greaterThanOrEqualTo($now)) {
            $totalMins = max(1, $startDateTime->diffInMinutes($endDateTime));
            $passedMins = $startDateTime->diffInMinutes($now);
            $expectedProgress = min(100, (int) round(($passedMins / $totalMins) * 100));

            // Lagging behind schedule by more than 30%
            if (($expectedProgress - $progress) > 30) {
                return [
                    'status'         => 'amber',
                    'active_lamp'    => 'amber',
                    'label'          => 'Behind Schedule',
                    'tag'            => 'BEHIND',
                    'time_diff_text' => "Exp {$expectedProgress}%",
                    'badge_class'    => 'bg-amber-50 text-amber-800 border-amber-200',
                    'dot_class'      => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.7)]',
                    'reason'         => "Expected ~{$expectedProgress}% based on timeline, actual is {$progress}%",
                    'is_overdue'     => false,
                ];
            }

            return [
                'status'         => 'green',
                'active_lamp'    => 'green',
                'label'          => 'On Track',
                'tag'            => 'ON TRACK',
                'time_diff_text' => "{$progress}% Done",
                'badge_class'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'dot_class'      => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.7)]',
                'reason'         => "Executing on schedule ({$progress}% progress)",
                'is_overdue'     => false,
            ];
        }

        // ══════════════════════════════════════════════════════════
        // 7. UPCOMING TASK (Start Time in Future) -> ⚪ GRAY
        // ══════════════════════════════════════════════════════════
        if ($startDateTime && $startDateTime->greaterThan($now)) {
            $daysToStart = (int) $now->diffInDays($startDateTime);
            $hoursToStart = (int) $now->diffInHours($startDateTime);
            $timeText = ($daysToStart > 0) ? "In {$daysToStart}d" : "In {$hoursToStart}h";

            return [
                'status'         => 'gray',
                'active_lamp'    => 'none',
                'label'          => 'Upcoming (Scheduled)',
                'tag'            => 'PLAN',
                'time_diff_text' => $timeText,
                'badge_class'    => 'bg-slate-100 text-slate-600 border-slate-200',
                'dot_class'      => 'bg-slate-400',
                'reason'         => "Kick-off scheduled for " . $startDateTime->format('M d, Y h:i A'),
                'is_overdue'     => false,
            ];
        }

        // Default On Track
        return [
            'status'         => 'green',
            'active_lamp'    => 'green',
            'label'          => 'On Track',
            'tag'            => 'ON TRACK',
            'time_diff_text' => 'On Schedule',
            'badge_class'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot_class'      => 'bg-emerald-500',
            'reason'         => 'Deliverable on schedule',
            'is_overdue'     => false,
        ];
    }
}
