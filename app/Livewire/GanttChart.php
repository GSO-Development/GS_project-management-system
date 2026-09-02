<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\WbsItem;
use Carbon\Carbon;
use Livewire\Component;

class GanttChart extends Component
{
    public Project $project;
    public string $timeframe = 'month'; // 'day', 'week', 'month'
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

    public function goToToday()
    {
        $this->dispatch('scroll-to-marker', ['marker' => 'today']);
    }

    public function goToDeadline()
    {
        $this->dispatch('scroll-to-marker', ['marker' => 'deadline']);
    }

    public function goToPrevious()
    {
        $this->dispatch('scroll-timeline', ['direction' => 'left']);
    }

    public function goToNext()
    {
        $this->dispatch('scroll-timeline', ['direction' => 'right']);
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter']);
    }

    public function render()
    {
        // ── 1. Query ALL WBS items for this project ────────────────────────
        $query = WbsItem::with(['assignedUser', 'children', 'risks'])
            ->where('project_id', $this->project->id);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $allRawItems = $query->get()->sort(function ($a, $b) {
            return strnatcmp($a->wbs_code ?? (string)$a->id, $b->wbs_code ?? (string)$b->id);
        })->values();

        // ── 2. Smart Hierarchical Date Cascade & Rollup ─────────────────────
        // Ensure every child has a valid start & end date based on project start and hierarchy
        $projectStart = $this->project->start_date
            ? $this->project->start_date->copy()->startOfDay()
            : now()->startOfDay();

        $projectDeadline = $this->project->deadline
            ? $this->project->deadline->copy()->endOfDay()
            : null;

        // Group items by parent_id
        $childrenMap = [];
        $itemMap = [];
        foreach ($allRawItems as $item) {
            $pId = $item->parent_id ?? 0;
            $childrenMap[$pId][] = $item;
            $itemMap[$item->id] = $item;
        }

        // Check if items have distinct dates or need cascade
        $datedCount = $allRawItems->filter(fn($i) => $i->start_date && $i->end_date)->count();
        $needsCascade = ($datedCount === 0);

        if ($needsCascade) {
            $assignHierarchyDates = function ($parentId, $startCursor) use (&$assignHierarchyDates, &$childrenMap) {
                $siblings = $childrenMap[$parentId] ?? [];
                $cursor = $startCursor->copy();

                foreach ($siblings as $item) {
                    $hasChildren = !empty($childrenMap[$item->id]);
                    $isTimeSlot = (bool) preg_match('/\d{1,2}:\d{2}\s*(?:AM|PM)/i', $item->title)
                        && $item->item_type->value !== 'phase'
                        && $item->item_type->value !== 'work_package';

                    if ($isTimeSlot) {
                        $item->start_date = $startCursor->copy();
                        $item->end_date   = $startCursor->copy();
                    } elseif ($hasChildren) {
                        $childStart = $cursor->copy();
                        $childMaxEnd = $assignHierarchyDates($item->id, $childStart);

                        $item->start_date = $childStart->copy();
                        $item->end_date   = $childMaxEnd ? $childMaxEnd->copy() : $childStart->copy();
                        $cursor = $item->end_date->copy()->addDay();
                    } else {
                        if (preg_match('/^Week\s*\d+/i', $item->title) && ($item->duration <= 1 || !$item->duration)) {
                            $dur = 7;
                        } elseif (preg_match('/^Month\s*\d+/i', $item->title) && ($item->duration <= 1 || !$item->duration)) {
                            $dur = 30;
                        } else {
                            $dur = max(1, (int) ($item->duration ?? 1));
                        }

                        $item->start_date = $cursor->copy();
                        $item->end_date   = $cursor->copy()->addDays($dur - 1);
                        $cursor = $item->end_date->copy()->addDay();
                    }
                }

                $maxEnd = null;
                foreach ($siblings as $item) {
                    if ($item->end_date && (!$maxEnd || $item->end_date->gt($maxEnd))) {
                        $maxEnd = $item->end_date->copy();
                    }
                }
                return $maxEnd;
            };

            $assignHierarchyDates(0, $projectStart);
        }

        // ── 3. Recursive Bottom-Up Date Rollup for ALL Parent Items ────────
        // This guarantees phases (Month 1, Month 2, etc.) reflect the true bounds of their children
        $rollupDates = function ($parentId) use (&$rollupDates, &$childrenMap) {
            $siblings = $childrenMap[$parentId] ?? [];
            $branchMinStart = null;
            $branchMaxEnd   = null;

            foreach ($siblings as $item) {
                if (!empty($childrenMap[$item->id])) {
                    [$cMin, $cMax] = $rollupDates($item->id);
                    if ($cMin) $item->start_date = $cMin->copy();
                    if ($cMax) $item->end_date   = $cMax->copy();
                }

                if ($item->start_date) {
                    if (!$branchMinStart || $item->start_date->lt($branchMinStart)) {
                        $branchMinStart = $item->start_date->copy();
                    }
                }
                if ($item->end_date) {
                    if (!$branchMaxEnd || $item->end_date->gt($branchMaxEnd)) {
                        $branchMaxEnd = $item->end_date->copy();
                    }
                }
            }

            return [$branchMinStart, $branchMaxEnd];
        };

        $rollupDates(0);

        // ── 4. Filter out items under collapsed parent nodes for display ────
        $collapsedCodes = [];
        foreach ($allRawItems as $item) {
            if (in_array($item->id, $this->collapsedIds)) {
                $collapsedCodes[] = $item->wbs_code . '.';
            }
        }

        $wbsItems = $allRawItems;
        if (!empty($collapsedCodes)) {
            $wbsItems = $wbsItems->filter(function ($item) use ($collapsedCodes) {
                foreach ($collapsedCodes as $prefix) {
                    if (str_starts_with($item->wbs_code, $prefix)) return false;
                }
                return true;
            })->values();
        }

        // ── 5. Project Timeline Bounds with Deadline Anchoring ──────────────
        $minDate = $projectStart->copy();
        $maxDate = $projectDeadline ? $projectDeadline->copy() : $projectStart->copy()->addMonths(2);

        foreach ($allRawItems as $item) {
            if ($item->start_date && $item->start_date->lt($minDate)) $minDate = $item->start_date->copy();
            if ($item->end_date   && $item->end_date->gt($maxDate))   $maxDate = $item->end_date->copy();
        }

        if ($this->timeframe === 'month') {
            $timelineStart = $minDate->copy()->startOfMonth();
            // Ensure at least 1 month buffer past max date / deadline
            $timelineEnd   = $maxDate->copy()->addMonth()->endOfMonth();
        } elseif ($this->timeframe === 'week') {
            $timelineStart = $minDate->copy()->startOfWeek();
            $timelineEnd   = $maxDate->copy()->addWeek()->endOfWeek();
            if ($timelineStart->diffInDays($timelineEnd) < 21) {
                $timelineEnd = $timelineStart->copy()->addDays(42);
            }
        } else {
            // Day view: add generous buffer around project start and deadline
            $timelineStart = $minDate->copy()->subDays(2);
            $timelineEnd   = $maxDate->copy()->addDays(3);
            if ($timelineStart->diffInDays($timelineEnd) < 14) {
                $timelineEnd = $timelineStart->copy()->addDays(21);
            }
        }

        $totalTimelineDays = max(1, (int) $timelineStart->diffInDays($timelineEnd) + 1);

        // ── 6. Pixel-based column system ────────────────────────────────────
        $columns = [];

        if ($this->timeframe === 'day') {
            $colPx = 56; // 56px per day for clear dates & clean task pills
            $curr = $timelineStart->copy()->startOfDay();
            while ($curr->lte($timelineEnd)) {
                $columns[] = [
                    'label'     => $curr->format('d'),
                    'sublabel'  => $curr->format('D'),
                    'monthKey'  => $curr->format('F Y'),
                    'isToday'   => $curr->isToday(),
                    'isWeekend' => $curr->isWeekend(),
                    'dateStr'   => $curr->format('M d, Y'),
                    'days'      => 1,
                    'px'        => $colPx,
                ];
                $curr->addDay();
            }
        } elseif ($this->timeframe === 'month') {
            $currM = $timelineStart->copy()->startOfMonth();
            $monthCount = 0;
            while ($currM->lte($timelineEnd)) {
                $monthCount++;
                $currM->addMonth();
            }
            // Proportional width for month columns
            $colPx = max(160, min(240, (int) round(900 / max(1, $monthCount))));

            $currM = $timelineStart->copy()->startOfMonth();
            while ($currM->lte($timelineEnd)) {
                $columns[] = [
                    'label'     => $currM->format('F'),
                    'sublabel'  => $currM->format('Y') . ' (' . $currM->daysInMonth . 'd)',
                    'yearKey'   => $currM->format('Y'),
                    'isCurrent' => $currM->isCurrentMonth(),
                    'dateStr'   => $currM->format('F Y'),
                    'days'      => $currM->daysInMonth,
                    'px'        => $colPx,
                ];
                $currM->addMonth();
            }
        } else {
            // Week (default)
            $colPx = 140; // 140px per 7 days = 20px/day
            $curr = $timelineStart->copy()->startOfWeek();
            while ($curr->lte($timelineEnd)) {
                $weekEnd = $curr->copy()->endOfWeek();
                $columns[] = [
                    'label'     => 'Week ' . $curr->weekOfYear,
                    'sublabel'  => $curr->format('M d') . ' – ' . $weekEnd->format('M d'),
                    'monthKey'  => $curr->format('F Y'),
                    'isCurrent' => $curr->isCurrentWeek(),
                    'dateStr'   => $curr->format('M d') . ' – ' . $weekEnd->format('M d, Y'),
                    'days'      => 7,
                    'px'        => $colPx,
                ];
                $curr->addWeek();
            }
        }

        // Total canvas pixel width
        $totalCanvasPx = (int) array_sum(array_column($columns, 'px'));

        // Build shared dayToPx coordinate mapping
        $dayToPx  = [];
        $pxCursor = 0.0;
        foreach ($columns as $col) {
            $pxPerDay = $col['px'] / $col['days'];
            for ($d = 0; $d < $col['days']; $d++) {
                $dayToPx[] = round($pxCursor + $d * $pxPerDay, 2);
            }
            $pxCursor += $col['px'];
        }
        $dayToPx[] = round($pxCursor, 2); // Sentinel: right edge of last column

        // ── 7. Multi-Tier Header Grouping ───────────────────────────────────
        $monthHeaders = [];
        if ($this->timeframe === 'month') {
            // Group months by Year
            $currentKey = null;
            $currentPx  = 0;
            foreach ($columns as $col) {
                $key = $col['yearKey'];
                if ($currentKey === null) {
                    $currentKey = $key;
                    $currentPx  = $col['px'];
                } elseif ($currentKey === $key) {
                    $currentPx += $col['px'];
                } else {
                    $monthHeaders[] = ['label' => 'Year ' . $currentKey, 'pxWidth' => $currentPx];
                    $currentKey = $key;
                    $currentPx  = $col['px'];
                }
            }
            if ($currentKey !== null) {
                $monthHeaders[] = ['label' => 'Year ' . $currentKey, 'pxWidth' => $currentPx];
            }
        } else {
            // Group days/weeks by Month & Year
            $currentKey = null;
            $currentPx  = 0;
            foreach ($columns as $col) {
                $key = $col['monthKey'];
                if ($currentKey === null) {
                    $currentKey = $key;
                    $currentPx  = $col['px'];
                } elseif ($currentKey === $key) {
                    $currentPx += $col['px'];
                } else {
                    $monthHeaders[] = ['label' => $currentKey, 'pxWidth' => $currentPx];
                    $currentKey = $key;
                    $currentPx  = $col['px'];
                }
            }
            if ($currentKey !== null) {
                $monthHeaders[] = ['label' => $currentKey, 'pxWidth' => $currentPx];
            }
        }

        // ── 8. Today & Project Deadline Laser Line Positions ────────────────
        $todayOffset = (int) $timelineStart->diffInDays(now()->startOfDay(), false);
        $todayPx     = null;
        if ($todayOffset >= 0 && $todayOffset < count($dayToPx)) {
            $todayPx = $dayToPx[$todayOffset];
        }

        $deadlinePx = null;
        $isPastDeadline = false;
        $daysToDeadline = null;
        if ($this->project->deadline) {
            $deadlineDate = $this->project->deadline->copy()->startOfDay();
            $deadOffset = (int) $timelineStart->diffInDays($deadlineDate, false);
            if ($deadOffset >= 0 && $deadOffset < count($dayToPx)) {
                $deadlinePx = $dayToPx[$deadOffset];
            } elseif ($deadOffset >= count($dayToPx)) {
                $deadlinePx = $dayToPx[count($dayToPx) - 1];
            }
            $isPastDeadline = now()->startOfDay()->gt($deadlineDate);
            $daysToDeadline = (int) now()->startOfDay()->diffInDays($deadlineDate, false);
        }

        $collapsedIds = $this->collapsedIds;
        $project      = $this->project;

        return view('livewire.gantt-chart', compact(
            'wbsItems',
            'allRawItems',
            'project',
            'timelineStart',
            'timelineEnd',
            'totalTimelineDays',
            'totalCanvasPx',
            'monthHeaders',
            'columns',
            'dayToPx',
            'todayPx',
            'deadlinePx',
            'isPastDeadline',
            'daysToDeadline',
            'collapsedIds'
        ));
    }
}
