<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\WbsItem;
use Carbon\Carbon;
use Livewire\Component;

class GanttChart extends Component
{
    public Project $project;
    public string $timeframe = 'week'; // 'day', 'week', 'month'
    public string $search = '';
    public string $statusFilter = 'all';

    public function setTimeframe(string $mode)
    {
        if (in_array($mode, ['day', 'week', 'month'])) {
            $this->timeframe = $mode;
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter']);
    }

    public function render()
    {
        $query = WbsItem::with(['assignedUser', 'children'])
            ->where('project_id', $this->project->id);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $wbsItems = $query->orderBy('wbs_code')->get();

        // Calculate project bounds
        $minDate = $this->project->start_date ? $this->project->start_date->copy() : now()->startOfMonth();
        $maxDate = $this->project->deadline ? $this->project->deadline->copy() : now()->addMonths(2);

        foreach ($wbsItems as $item) {
            if ($item->start_date && $item->start_date->lt($minDate)) {
                $minDate = $item->start_date->copy();
            }
            if ($item->end_date && $item->end_date->gt($maxDate)) {
                $maxDate = $item->end_date->copy();
            }
        }

        $timelineStart = $minDate->copy()->startOfWeek();
        $timelineEnd = $maxDate->copy()->addWeek()->endOfWeek();

        if ($timelineStart->diffInDays($timelineEnd) < 14) {
            $timelineEnd = $timelineStart->copy()->addDays(35);
        }

        $totalTimelineDays = max(1, $timelineStart->diffInDays($timelineEnd));

        // Group months for top tier header
        $monthHeaders = [];
        $currMonth = $timelineStart->copy()->startOfMonth();
        $endMonth = $timelineEnd->copy()->endOfMonth();

        while ($currMonth->lte($endMonth)) {
            $mStart = $currMonth->copy()->startOfMonth();
            $mEnd = $currMonth->copy()->endOfMonth();

            $effectiveStart = $mStart->gt($timelineStart) ? $mStart : $timelineStart;
            $effectiveEnd = $mEnd->lt($timelineEnd) ? $mEnd : $timelineEnd;

            $days = max(1, $effectiveStart->diffInDays($effectiveEnd) + 1);
            $widthPct = ($days / $totalTimelineDays) * 100;

            $monthHeaders[] = [
                'label' => $currMonth->format('F Y'),
                'widthPct' => $widthPct,
            ];

            $currMonth->addMonth();
        }

        // Sub-columns header
        $columns = [];
        $curr = $timelineStart->copy();

        if ($this->timeframe === 'day') {
            while ($curr->lte($timelineEnd)) {
                $columns[] = [
                    'label' => $curr->format('d'),
                    'sublabel' => $curr->format('D'),
                    'isToday' => $curr->isToday(),
                    'isWeekend' => $curr->isWeekend(),
                    'days' => 1,
                ];
                $curr->addDay();
            }
        } elseif ($this->timeframe === 'month') {
            $currM = $timelineStart->copy()->startOfMonth();
            while ($currM->lte($endMonth)) {
                $columns[] = [
                    'label' => $currM->format('M'),
                    'sublabel' => $currM->format('Y'),
                    'isCurrent' => $currM->isCurrentMonth(),
                    'days' => $currM->daysInMonth,
                ];
                $currM->addMonth();
            }
        } else {
            // week (default)
            while ($curr->lte($timelineEnd)) {
                $columns[] = [
                    'label' => 'W' . $curr->weekOfYear,
                    'sublabel' => $curr->format('M d'),
                    'isCurrent' => $curr->isCurrentWeek(),
                    'days' => 7,
                ];
                $curr->addWeek();
            }
        }

        $todayOffset = $timelineStart->diffInDays(now(), false);
        $todayPct = ($todayOffset >= 0 && $todayOffset <= $totalTimelineDays)
            ? ($todayOffset / $totalTimelineDays) * 100
            : null;

        return view('livewire.gantt-chart', compact(
            'wbsItems',
            'timelineStart',
            'timelineEnd',
            'totalTimelineDays',
            'monthHeaders',
            'columns',
            'todayPct'
        ));
    }
}
