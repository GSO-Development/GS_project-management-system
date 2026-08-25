<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\WbsItem;
use Carbon\Carbon;
use Livewire\Component;

class GanttChart extends Component
{
    public Project $project;
    public string $timeframe = 'month'; // Default to month view
    public string $search = '';
    public string $statusFilter = 'all';

    public array $collapsedIds = [];
    public bool $initialised = false;

    protected $listeners = [
        'wbsUpdated' => '$refresh',
    ];

    /**
     * Pre-collapse all phase-level items on first load so only
     * the main phases are visible in the Gantt.
     */
    public function mount(Project $project)
    {
        $this->project = $project;
        $user = auth()->user();

        // If project is not yet accepted by the Project Manager, only PMO Admin and the designated Project Manager can access it
        if (!$project->isPmAccepted() && !$user->isPmoAdmin() && $project->project_manager_id !== $user->id) {
            abort(403, 'This project is pending Project Manager acceptance.');
        }

        // Collapse every phase row by default
        $this->collapsedIds = WbsItem::where('project_id', $project->id)
            ->where('item_type', 'phase')
            ->pluck('id')
            ->toArray();

        $this->initialised = true;
    }

    /** Collapse all phases with one click */
    public function collapseAll()
    {
        $this->collapsedIds = WbsItem::where('project_id', $this->project->id)
            ->where('item_type', 'phase')
            ->pluck('id')
            ->toArray();
    }

    /** Expand everything with one click */
    public function expandAll()
    {
        $this->collapsedIds = [];
    }

    public function toggleCollapse(int $itemId)
    {
        if (in_array($itemId, $this->collapsedIds)) {
            $this->collapsedIds = array_diff($this->collapsedIds, [$itemId]);
        } else {
            $this->collapsedIds[] = $itemId;
        }
    }

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
        // ── Query WBS items ─────────────────────────────────────────────────
        $query = WbsItem::with(['assignedUser', 'children', 'risks'])
            ->where('project_id', $this->project->id);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $wbsItems = $query->get()->sort(function ($a, $b) {
            return strnatcmp($a->wbs_code, $b->wbs_code);
        })->values();

        // ── Expand / Collapse filter ─────────────────────────────────────────
        $collapsedCodes = [];
        foreach ($wbsItems as $item) {
            if (in_array($item->id, $this->collapsedIds)) {
                $collapsedCodes[] = $item->wbs_code . '.';
            }
        }
        if (!empty($collapsedCodes)) {
            $wbsItems = $wbsItems->filter(function ($item) use ($collapsedCodes) {
                foreach ($collapsedCodes as $prefix) {
                    if (str_starts_with($item->wbs_code, $prefix)) return false;
                }
                return true;
            });
        }

        // ── Project bounds ───────────────────────────────────────────────────
        $minDate = $this->project->start_date
            ? $this->project->start_date->copy()
            : now()->startOfMonth();
        $maxDate = $this->project->deadline
            ? $this->project->deadline->copy()
            : now()->addMonths(2);

        foreach ($wbsItems as $item) {
            if ($item->start_date && $item->start_date->lt($minDate)) $minDate = $item->start_date->copy();
            if ($item->end_date   && $item->end_date->gt($maxDate))   $maxDate = $item->end_date->copy();
        }

        $timelineStart     = $minDate->copy()->startOfWeek();
        $timelineEnd       = $maxDate->copy()->addWeek()->endOfWeek();
        if ($timelineStart->diffInDays($timelineEnd) < 14) {
            $timelineEnd = $timelineStart->copy()->addDays(35);
        }
        $totalTimelineDays = max(1, $timelineStart->diffInDays($timelineEnd));

        // ── Pixel-based column system ────────────────────────────────────────
        // Each column has a fixed pixel width so headers and bars share the
        // exact same coordinate space — no CSS min-width drift.
        $colPx = match ($this->timeframe) {
            'day'   => 44,
            'month' => 120,
            default => 90,    // week
        };

        // Build sub-columns with their pixel widths
        $columns   = [];
        $endMonth  = $timelineEnd->copy()->endOfMonth();

        if ($this->timeframe === 'day') {
            $curr = $timelineStart->copy();
            while ($curr->lte($timelineEnd)) {
                $columns[] = [
                    'label'     => $curr->format('d'),
                    'sublabel'  => $curr->format('D'),
                    'isToday'   => $curr->isToday(),
                    'isWeekend' => $curr->isWeekend(),
                    'days'      => 1,
                    'px'        => $colPx,
                ];
                $curr->addDay();
            }
        } elseif ($this->timeframe === 'month') {
            $currM = $timelineStart->copy()->startOfMonth();
            while ($currM->lte($endMonth)) {
                $px = max(100, $currM->daysInMonth * 4);
                $columns[] = [
                    'label'     => $currM->format('M'),
                    'sublabel'  => $currM->format('Y'),
                    'isCurrent' => $currM->isCurrentMonth(),
                    'days'      => $currM->daysInMonth,
                    'px'        => $px,
                ];
                $currM->addMonth();
            }
        } else {
            // week (default)
            $curr = $timelineStart->copy();
            while ($curr->lte($timelineEnd)) {
                $columns[] = [
                    'label'     => 'W' . $curr->weekOfYear,
                    'sublabel'  => $curr->format('M d'),
                    'isCurrent' => $curr->isCurrentWeek(),
                    'days'      => 7,
                    'px'        => $colPx,
                ];
                $curr->addWeek();
            }
        }

        // Total canvas pixel width
        $totalCanvasPx = (int) array_sum(array_column($columns, 'px'));

        // Build dayToPx lookup: dayToPx[dayOffset] = px from left canvas edge
        // where that day begins. This is the shared coordinate system for both
        // column headers and task bars.
        $dayToPx  = [];
        $pxCursor = 0.0;
        foreach ($columns as $col) {
            $pxPerDay = $col['px'] / $col['days'];
            for ($d = 0; $d < $col['days']; $d++) {
                $dayToPx[] = round($pxCursor + $d * $pxPerDay, 2);
            }
            $pxCursor += $col['px'];
        }
        $dayToPx[] = round($pxCursor, 2); // sentinel: right edge of last column

        // ── Month headers (pixel widths from same lookup) ────────────────────
        $monthHeaders = [];
        $currMonth    = $timelineStart->copy()->startOfMonth();
        while ($currMonth->lte($endMonth)) {
            $mStartOff = max(0, (int) $timelineStart->diffInDays($currMonth->copy()->startOfMonth(), false));
            $mEndOff   = max(0, (int) $timelineStart->diffInDays($currMonth->copy()->endOfMonth(),   false) + 1);

            $mStartOff = min($mStartOff, count($dayToPx) - 1);
            $mEndOff   = min($mEndOff,   count($dayToPx) - 1);

            $monthHeaders[] = [
                'label'   => $currMonth->format('F Y'),
                'pxWidth' => max(60, round($dayToPx[$mEndOff] - $dayToPx[$mStartOff], 2)),
            ];
            $currMonth->addMonth();
        }

        // ── Today line pixel position ────────────────────────────────────────
        $todayOffset = (int) $timelineStart->diffInDays(now(), false);
        $todayPx     = null;
        if ($todayOffset >= 0 && $todayOffset < count($dayToPx)) {
            $todayPx = $dayToPx[$todayOffset];
        }

        $collapsedIds = $this->collapsedIds;
        $project      = $this->project;

        return view('livewire.gantt-chart', compact(
            'wbsItems',
            'project',
            'timelineStart',
            'timelineEnd',
            'totalTimelineDays',
            'totalCanvasPx',
            'monthHeaders',
            'columns',
            'dayToPx',
            'todayPx',
            'collapsedIds'
        ));
    }
}
