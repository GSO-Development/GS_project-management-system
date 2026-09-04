<div x-data="{
    tooltip: { visible: false, x: 0, y: 0, data: null },
    showTooltip(el, data) {
        this.tooltip.data = data;
        const rect = el.getBoundingClientRect();
        const tw = 310, th = 220;
        const gap = 10;
        let x = rect.left + 290;
        let y = rect.top + (rect.height / 2) - (th / 2);
        if (x + tw > window.innerWidth - 8) x = rect.left - tw - gap;
        if (y < 8) y = 8;
        if (y + th > window.innerHeight - 8) y = window.innerHeight - th - 8;
        this.tooltip.x = x;
        this.tooltip.y = y;
        this.tooltip.visible = true;
    },
    hideTooltip() { this.tooltip.visible = false; },
    scrollToMarker(type) {
        const container = this.$refs.timelineScrollContainer;
        if (!container) return;
        if (type === 'today' && {{ !is_null($todayPx) ? 'true' : 'false' }}) {
            container.scrollTo({ left: Math.max(0, {{ 280 + ($todayPx ?? 0) }} - 400), behavior: 'smooth' });
        } else if (type === 'deadline' && {{ !is_null($deadlinePx) ? 'true' : 'false' }}) {
            container.scrollTo({ left: Math.max(0, {{ 280 + ($deadlinePx ?? 0) }} - 400), behavior: 'smooth' });
        }
    },
    scrollTimeline(dir) {
        const container = this.$refs.timelineScrollContainer;
        if (!container) return;
        const offset = dir === 'left' ? -350 : 350;
        container.scrollBy({ left: offset, behavior: 'smooth' });
    }
}"
x-on:scroll-to-marker.window="scrollToMarker($event.detail.marker)"
x-on:scroll-timeline.window="scrollTimeline($event.detail.direction)">

    <style>
        .gantt-scroll::-webkit-scrollbar {
            height: 7px;
        }
        .gantt-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 9999px;
        }
        .gantt-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .gantt-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- ===== GANTT HERO CARD CONTAINER ===== -->
    <div class="card p-0 overflow-hidden shadow-2xs border border-slate-200/90 bg-white rounded-2xl mb-4">
        
        <!-- ── Modern Executive Toolbar (2 Clean Rows) ── -->
        <!-- Row 1: Primary Controls & Navigation -->
        <div class="px-4 py-2.5 sm:px-5 bg-white border-b border-slate-100 flex items-center justify-between gap-3 flex-wrap sm:flex-nowrap">
            
            <!-- Left: Icon + Title + Code + Date Navigator -->
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0 flex-wrap">
                <!-- Title & Code -->
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl flex items-center justify-center text-white shadow-2xs" style="background: #c3122e;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-extrabold text-slate-900 tracking-tight whitespace-nowrap">WBS Gantt Schedule</span>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                            {{ $project->code }}
                        </span>
                    </div>
                </div>

                <!-- Date Navigator (< Today / Deadline >) -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/90 bg-slate-50/80 shadow-2xs p-0.5 gap-0.5">
                    <button wire:click="goToPrevious" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer" title="Scroll Left">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    @if(!is_null($todayPx))
                        <button wire:click="goToToday" class="px-2.5 py-1 text-slate-700 hover:text-[#c3122e] hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5" title="Jump to Today">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                            <span>Today</span>
                        </button>
                    @endif
                    @if(!is_null($deadlinePx))
                        <button wire:click="goToDeadline" class="px-2.5 py-1 text-slate-700 hover:text-amber-800 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border-l border-slate-200/60" title="Jump to Project Deadline">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Deadline</span>
                        </button>
                    @endif
                    <button wire:click="goToNext" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer" title="Scroll Right">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Right: Timeframe Switcher (Day / Week / Month) + Search Input -->
            <div class="flex items-center gap-2 shrink-0">
                <!-- Timeframe Tabs: Day vs Week vs Month -->
                <div class="inline-flex p-0.5 rounded-xl bg-slate-100/90 border border-slate-200/80 gap-0.5 text-xs">
                    <button wire:click="setTimeframe('day')" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $timeframe === 'day' ? 'bg-gradient-to-r from-[#c3122e] to-[#9e0f26] text-white shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}"
                            title="Daily Schedule">
                        Day
                    </button>
                    <button wire:click="setTimeframe('week')" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $timeframe === 'week' ? 'bg-gradient-to-r from-[#c3122e] to-[#9e0f26] text-white shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}"
                            title="Weekly Schedule">
                        Week
                    </button>
                    <button wire:click="setTimeframe('month')" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $timeframe === 'month' ? 'bg-gradient-to-r from-[#c3122e] to-[#9e0f26] text-white shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}"
                            title="Monthly Overview">
                        Month
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative w-36 sm:w-44">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search WBS..."
                           class="w-full text-xs font-medium rounded-xl border border-slate-200/90 pl-7.5 pr-2.5 py-1 bg-white focus:border-slate-400 focus:ring-1 focus:ring-slate-200 outline-none placeholder:text-slate-400 shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- Row 2: Secondary Bar (Tree Controls + Roadmap Dates + Clean Legend + Deliverables Count) -->
        <div class="px-4 sm:px-5 py-2 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between gap-4 text-xs font-medium text-slate-600 overflow-x-auto scrollbar-none">
            <!-- Left: Tree Expand/Collapse + Divider + Project Dates Info -->
            <div class="flex items-center gap-3 shrink-0">
                <div class="inline-flex items-center gap-1.5 text-xs">
                    <button wire:click="expandAll" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors cursor-pointer">
                        Expand All
                    </button>
                    <span class="text-slate-300">•</span>
                    <button wire:click="collapseAll" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors cursor-pointer">
                        Collapse All
                    </button>
                </div>

                <div class="h-3 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Project Roadmap Bounds -->
                <div class="hidden sm:inline-flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                    <span>Start: <strong class="text-slate-700 font-semibold">{{ $project->start_date ? $project->start_date->format('M d, Y') : '—' }}</strong></span>
                    <span class="text-slate-300">➔</span>
                    <span>Deadline: <strong class="text-slate-800 font-semibold">{{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}</strong></span>
                    @if(!is_null($daysToDeadline))
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold font-mono {{ $daysToDeadline >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-rose-50 text-[#c3122e] border border-rose-200/60' }}">
                            {{ $daysToDeadline >= 0 ? $daysToDeadline . 'd left' : abs($daysToDeadline) . 'd overdue' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Right: Clean Legend Dots + Deliverables Count -->
            <div class="flex items-center gap-3.5 shrink-0">
                <div class="flex items-center gap-3 text-[11px] font-semibold text-slate-600">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#c3122e]"></span> In Progress</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Planned</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-500 rotate-45"></span> Milestone</span>
                    <span class="flex items-center gap-1 text-amber-700 font-bold"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Has Risk</span>
                </div>

                <div class="h-3 w-px bg-slate-200 hidden md:block"></div>

                <div class="hidden md:flex items-center gap-1 text-slate-400 font-medium text-[11px] whitespace-nowrap">
                    <span>Showing <strong class="text-slate-700 font-bold">{{ $wbsItems->count() }}</strong> of <strong class="text-slate-700 font-bold">{{ $allRawItems->count() }}</strong> deliverables</span>
                </div>
            </div>
        </div>

        <!-- ── Scrollable Gantt Canvas Area ── -->
        <div class="overflow-x-auto gantt-scroll pt-5 pb-2" x-ref="timelineScrollContainer">
            {{-- Outer wrapper is exactly: 280px title col + totalCanvasPx --}}
            <div style="min-width: {{ 280 + $totalCanvasPx }}px;" class="flex flex-col relative">

                <!-- 1. MULTI-TIER HEADER -->
                <div class="bg-slate-50/90 border-b border-slate-200 flex flex-col flex-shrink-0 sticky top-0 z-20 shadow-2xs">

                    <!-- Top: Month / Year headers -->
                    <div class="flex items-stretch border-b border-slate-200/80 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <div class="flex-shrink-0 border-r border-slate-200 bg-slate-100/70 text-slate-700 flex items-center justify-between px-4 py-2" style="width:280px;">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Work Breakdown</span>
                            <span class="text-[9.5px] font-mono font-bold text-slate-400">HIERARCHY</span>
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($monthHeaders as $mHead)
                                <div class="text-center border-r border-slate-200 font-bold text-slate-800 bg-slate-50/80 py-2 px-1 truncate tracking-wide text-xs"
                                     style="width:{{ $mHead['pxWidth'] }}px; flex-shrink:0;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom: Sub-column (Day / Week / Month) headers -->
                    <div class="flex items-stretch text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <div class="border-r border-slate-200 flex items-center px-4 py-2 bg-slate-50/50 text-[11px] font-bold text-slate-600" style="width:280px; flex-shrink:0;">
                            Phase &amp; Deliverable Title
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($columns as $col)
                                <div class="text-center border-r border-slate-200 py-1.5 px-1 flex-shrink-0 flex flex-col items-center justify-center
                                        {{ ($col['isToday'] ?? false) ? 'bg-rose-50 text-[#c3122e] font-bold border-rose-200' : (($col['isWeekend'] ?? false) ? 'bg-slate-100/40' : '') }}"
                                     style="width:{{ $col['px'] }}px;">
                                    <div class="text-xs leading-tight font-black {{ ($col['isToday'] ?? false) ? 'text-[#c3122e]' : 'text-slate-800' }}">{{ $col['label'] }}</div>
                                    <div class="text-[10px] font-mono font-semibold {{ ($col['isToday'] ?? false) ? 'text-rose-600' : 'text-slate-500' }} mt-0.5">{{ $col['sublabel'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. GANTT ROWS & TIMELINE CANVAS -->
                <div class="relative divide-y divide-slate-100/80 bg-white">

                    <!-- Today Line -->
                    @if(!is_null($todayPx))
                        <div class="absolute top-0 bottom-0 z-30 pointer-events-none" style="left:{{ 280 + $todayPx }}px; width:1.5px; background:#c3122e;">
                            <!-- Top Capsule Flag -->
                            <div class="absolute top-0 -translate-x-1/2 -mt-5 z-40 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#c3122e] text-white shadow-xs flex items-center gap-1 pointer-events-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span>Today • {{ now()->format('M d') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Target Project Deadline Marker Line -->
                    @if(!is_null($deadlinePx))
                        <div class="absolute top-0 bottom-0 z-30 pointer-events-none" style="left:{{ 280 + $deadlinePx }}px; width:1.5px; border-left:1.5px dashed #f59e0b;">
                            <!-- Top Banner Tag -->
                            <div class="absolute top-0 -translate-x-1/2 -mt-5 z-40 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white shadow-xs flex items-center gap-1 pointer-events-auto">
                                <span>🎯</span>
                                <span>Deadline • {{ $project->deadline ? $project->deadline->format('M d') : '' }}</span>
                            </div>
                        </div>
                    @endif

                    @forelse($wbsItems as $index => $item)
                        @php
                            /* ── resolve dates ── */
                            $itemStart = $item->start_date ? $item->start_date->copy()->startOfDay() : $timelineStart->copy();
                            $itemEnd   = $item->end_date   ? $item->end_date->copy()->endOfDay()     : $itemStart->copy()->addDays(4);

                            /* ── pixel positions using shared dayToPx map ── */
                            $startDayOff = max(0, (int) $timelineStart->diffInDays($itemStart, false));
                            $endDayOff   = max($startDayOff + 1, (int) $timelineStart->diffInDays($itemEnd, false) + 1);

                            $startDayOff = min($startDayOff, count($dayToPx) - 1);
                            $endDayOff   = min($endDayOff,   count($dayToPx) - 1);

                            $barLeft  = round($dayToPx[$startDayOff], 2);
                            $barRight = round($dayToPx[$endDayOff],   2);
                            $barWidth = max(8, $barRight - $barLeft);

                            /* ── indent level ── */
                            $levelIndent = match($item->item_type->value) {
                                'work_package' => 1,
                                'task'         => 2,
                                'subtask'      => 3,
                                default        => 0,
                            };

                            /* ── Check if item exceeds project deadline ── */
                            $exceedsDeadline = $project->deadline && $item->end_date && $item->end_date->gt($project->deadline);

                            /* ── Modern Sleek Phase & Task Styling ── */
                            $phaseStyles = [
                                ['bg' => 'linear-gradient(135deg, #2563eb 0%, #3b82f6 100%)', 'border' => '#1d4ed8'],
                                ['bg' => 'linear-gradient(135deg, #c3122e 0%, #e11d48 100%)', 'border' => '#9f1239'],
                                ['bg' => 'linear-gradient(135deg, #0d9488 0%, #14b8a6 100%)', 'border' => '#0f766e'],
                                ['bg' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)', 'border' => '#b45309'],
                                ['bg' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%)', 'border' => '#6d28d9'],
                            ];
                            $pStyle = $phaseStyles[$index % count($phaseStyles)];

                            $taskStyle = match($item->status->value) {
                                'completed' => ['bg' => '#059669', 'border' => '#047857'],
                                'in_progress' => ['bg' => '#c3122e', 'border' => '#9f1239'],
                                default => ['bg' => '#4f46e5', 'border' => '#4338ca'],
                            };

                            /* ── Detect hour/time-slot items ── */
                            $isHourSlot = ($item->parent_id !== null)
                                       && (!empty($item->start_time) || (bool) preg_match('/\d{1,2}:\d{2}\s*(?:AM|PM)/i', $item->title))
                                       && $item->item_type->value !== 'phase'
                                       && $item->item_type->value !== 'work_package';

                            /* ── Risks count and highest severity score ── */
                            $risksCount = $item->risks ? $item->risks->count() : 0;
                            $highestRiskScore = $risksCount > 0 ? (int) $item->risks->max('risk_score') : 0;

                            /* ── Tooltip Data ── */
                            $typeLabel    = $item->is_milestone ? 'Milestone' : ucfirst($item->item_type->value);
                            $assigneeName = $item->assignedUser ? $item->assignedUser->name : 'Unassigned';
                            $assigneeInit = $item->assignedUser ? strtoupper(substr($item->assignedUser->name, 0, 1)) : '?';
                            $tooltipData  = json_encode([
                                'wbs'               => $item->wbs_code,
                                'title'             => $item->title,
                                'type'              => $typeLabel,
                                'assignee'          => $assigneeName,
                                'initial'           => $assigneeInit,
                                'start'             => $item->start_date ? $item->start_date->format('M d, Y') : '—',
                                'end'               => $item->end_date   ? $item->end_date->format('M d, Y')   : '—',
                                'progress'          => $item->progress,
                                'status'            => $item->status->label(),
                                'exceeds_deadline'  => $exceedsDeadline,
                                'has_risk'          => $risksCount > 0,
                                'risks_count'       => $risksCount,
                                'highest_score'     => $highestRiskScore,
                                'risks_list'        => $risksCount > 0 ? $item->risks->map(fn($r) => [
                                    'title' => $r->title,
                                    'score' => $r->risk_score,
                                    'severity' => $r->risk_score >= 6 ? 'High' : ($r->risk_score >= 3 ? 'Medium' : 'Low')
                                ])->values()->toArray() : [],
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                        @endphp

                        <div class="flex items-center transition-colors py-2 text-xs cursor-default hover:bg-slate-50/80">

                            <!-- Left: WBS Title Column (fixed 280px) -->
                            <div class="border-r border-slate-100 flex-shrink-0 flex items-center overflow-hidden px-3.5 hover:bg-slate-50 transition-colors cursor-pointer"
                                 style="width:280px;"
                                 @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                 @mouseleave="hideTooltip()">
                                <div style="padding-left: {{ $levelIndent * 0.75 }}rem;" class="flex items-center gap-2 min-w-0 w-full">
                                    @if($item->children->isNotEmpty())
                                        @php $isCollapsed = in_array($item->id, $collapsedIds); @endphp
                                        <button type="button"
                                                wire:click="toggleCollapse({{ $item->id }})"
                                                class="p-0.5 rounded hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors flex items-center justify-center cursor-pointer flex-shrink-0 focus:outline-none"
                                                @mouseenter.stop @mouseleave.stop>
                                            <svg class="w-3.5 h-3.5 transform transition-transform duration-150 {{ $isCollapsed ? '-rotate-90' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="w-3.5 flex-shrink-0 inline-block text-slate-300 text-center font-bold">›</span>
                                    @endif

                                    @if($isHourSlot)
                                        <span class="flex-shrink-0 opacity-80">
                                            <svg class="w-3.5 h-3.5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </span>
                                    @elseif($item->item_type->value === 'phase')
                                        <span class="text-amber-500 text-sm flex-shrink-0">📁</span>
                                    @endif

                                    <span class="font-mono text-[11px] font-black flex-shrink-0 {{ $isHourSlot ? 'text-violet-600' : 'text-[#c3122e]' }}">{{ $item->wbs_code }}</span>

                                    @if($isHourSlot)
                                        <span class="truncate text-slate-900 font-bold text-xs" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </span>
                                    @else
                                        <span class="truncate text-slate-800 {{ $item->item_type->value === 'phase' ? 'font-black text-slate-900 text-xs' : 'font-semibold' }}">
                                            {{ $item->title }}
                                        </span>
                                    @endif

                                    {{-- ⚠️ RISK INDICATOR ICON ON TASK ROW --}}
                                    @if($risksCount > 0)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9.5px] font-black {{ $highestRiskScore >= 6 ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }} shadow-2xs flex-shrink-0"
                                              title="{{ $risksCount }} Active Risk(s) Associated with this Task">
                                            <span>⚠️</span>
                                            <span class="font-mono">{{ $risksCount }}</span>
                                        </span>
                                    @endif

                                    {{-- Exceeds Deadline Badge --}}
                                    @if($exceedsDeadline)
                                        <span class="px-1 py-0.2 rounded text-[9px] font-black bg-rose-100 text-[#c3122e] border border-rose-300 flex-shrink-0" title="This deliverable exceeds the project deadline">
                                            Late
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Timeline canvas (exact totalCanvasPx wide) -->
                            <div class="relative flex-shrink-0 h-9 flex items-center" style="width:{{ $totalCanvasPx }}px;">

                                <!-- Grid column lines (subtle background) -->
                                @php $lineLeft = 0; @endphp
                                @foreach($columns as $col)
                                    <div class="absolute top-0 bottom-0 border-r border-slate-100/70 {{ ($col['isWeekend'] ?? false) ? 'bg-slate-50/60' : '' }}"
                                         style="left:{{ $lineLeft }}px; width:{{ $col['px'] }}px;"></div>
                                    @php $lineLeft += $col['px']; @endphp
                                @endforeach

                                @if($item->is_milestone)
                                    <!-- Milestone 3D Diamond -->
                                    <div class="absolute top-1/2 -translate-y-1/2 flex items-center gap-2 z-20"
                                         style="left:{{ $barLeft }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                         <div class="relative">
                                            <div class="w-5 h-5 bg-gradient-to-br from-[#c3122e] via-rose-600 to-amber-500 rotate-45 border-2 border-white shadow-md flex-shrink-0 ring-2 ring-rose-200"></div>
                                            @if($risksCount > 0)
                                                <span class="absolute -top-2 -right-2 text-[10px] bg-amber-400 text-slate-950 rounded-full w-4 h-4 flex items-center justify-center font-black ring-1 ring-white shadow-xs">⚠️</span>
                                            @endif
                                         </div>
                                        <span class="text-[11px] font-black text-[#c3122e] whitespace-nowrap bg-white/95 px-2.5 py-0.5 rounded-lg shadow-sm border border-rose-200 flex items-center gap-1.5">
                                            <span>◆ {{ $item->title }}</span>
                                            @if($risksCount > 0)
                                                <span class="text-amber-700 font-extrabold text-[10px] bg-amber-100 px-1 py-0.2 rounded border border-amber-200">⚠️ {{ $risksCount }} Risk</span>
                                            @endif
                                        </span>
                                    </div>

                                @elseif($isHourSlot)
                                    <!-- ── HOUR TIME-SLOT BAR ───────────────────────────────── -->
                                    @php
                                        $hasPattern = preg_match('/(\d{1,2}:\d{2}\s*(?:AM|PM))\s*[-–—\s]+\s*(\d{1,2}:\d{2}\s*(?:AM|PM))/iu', $item->title, $tParts);
                                        $tStart = $hasPattern ? $tParts[1] : ($item->start_time_formatted ?? $item->start_time);
                                        $tEnd   = $hasPattern ? $tParts[2] : ($item->end_time_formatted ?? $item->end_time);

                                        $hourBg = match($item->status->value) {
                                            'completed'   => 'linear-gradient(135deg, #047857 0%, #10b981 60%, #34d399 100%)',
                                            'in_progress' => 'linear-gradient(135deg, #b45309 0%, #d97706 40%, #f59e0b 70%, #fbbf24 100%)',
                                            default       => 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 40%, #a78bfa 100%)',
                                        };
                                        $slotWidth = max(24, $barWidth - 2);
                                    @endphp

                                    <div class="absolute top-1/2 -translate-y-1/2 z-20"
                                         style="left:{{ $barLeft + 1 }}px; width:{{ $slotWidth }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        <div class="relative h-6 rounded-md overflow-hidden flex items-center cursor-pointer hover:scale-[1.02] hover:brightness-110 transition-all shadow-xs border border-white/40"
                                             style="background:{{ $hourBg }};">
                                            @if($item->progress > 0)
                                                <div class="absolute inset-y-0 left-0 rounded-l-md bg-black/20"
                                                     style="width:{{ $item->progress }}%;"></div>
                                            @endif
                                            <span class="absolute inset-0 flex items-center px-1.5 gap-1 overflow-hidden justify-center sm:justify-start">
                                                <svg class="w-2.5 h-2.5 text-white flex-shrink-0 opacity-90 hidden sm:inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                @php
                                                    $compactStart = $tStart ? trim(preg_replace('/\s*(AM|PM)/i', '', $tStart)) : null;
                                                    $compactEnd   = $tEnd   ? trim(preg_replace('/\s*(AM|PM)/i', '', $tEnd))   : null;
                                                @endphp
                                                @if($slotWidth >= 110 && $compactStart && $compactEnd)
                                                    <span class="text-[9px] font-black text-white truncate drop-shadow-sm font-mono">{{ $compactStart }}–{{ $compactEnd }}</span>
                                                @elseif($slotWidth >= 32 && $compactStart)
                                                    <span class="text-[9px] font-black text-white truncate drop-shadow-sm font-mono tracking-tight">{{ $compactStart }}</span>
                                                @else
                                                    <span class="text-[8px] font-bold text-white/90">1h</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                @elseif($item->item_type->value === 'phase')
                                    <!-- ── PHASE BAR ── -->
                                    <div class="absolute h-7 rounded-lg flex items-center overflow-hidden z-20 hover:brightness-105 transition-all cursor-pointer shadow-xs {{ $exceedsDeadline ? 'ring-2 ring-rose-500' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px; background:{{ $pStyle['bg'] }}; border:1px solid {{ $pStyle['border'] }};"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        @if($item->progress > 0)
                                            <div class="h-full bg-black/15 transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-3 text-xs font-bold text-white truncate tracking-wide">
                                            <span class="truncate">📁 {{ $item->title }} ({{ $item->progress }}%)</span>
                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                @if($exceedsDeadline)
                                                    <span class="px-1.5 py-0.5 rounded bg-rose-700 text-white text-[9px] font-bold shadow-xs">Late</span>
                                                @endif
                                                @if($risksCount > 0)
                                                    <span class="px-1.5 py-0.5 rounded bg-amber-400 text-slate-900 text-[9px] font-bold shadow-xs flex items-center gap-0.5">
                                                        <span>⚠️</span>
                                                        <span>{{ $risksCount }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </span>
                                    </div>

                                @else
                                    <!-- ── TASK / SUBTASK BAR ── -->
                                    <div class="absolute h-6 rounded-md flex items-center overflow-hidden z-20 hover:brightness-105 transition-all cursor-pointer shadow-2xs {{ $exceedsDeadline ? 'ring-2 ring-rose-500' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px; background:{{ $taskStyle['bg'] }}; border:1px solid {{ $taskStyle['border'] }};"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        @if($item->progress > 0)
                                            <div class="h-full bg-black/15 transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-2.5 text-[11px] font-semibold truncate text-white">
                                            <span class="truncate">
                                                @if($item->status->value === 'completed') ✓ {{ $item->title }} (100%)
                                                @else {{ $item->title }} ({{ $item->progress }}%)
                                                @endif
                                            </span>
                                            <div class="flex items-center gap-1 flex-shrink-0">
                                                @if($exceedsDeadline)
                                                    <span class="px-1 py-0.5 rounded bg-rose-700 text-white text-[8px] font-bold">Late</span>
                                                @endif
                                                @if($risksCount > 0)
                                                    <span class="px-1 py-0.5 rounded bg-amber-400 text-slate-900 text-[9px] font-bold shadow-xs flex items-center gap-0.5" title="{{ $risksCount }} Associated Risk(s)">
                                                        <span>⚠️</span>
                                                        <span class="font-mono">{{ $risksCount }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs font-semibold">No WBS items matching your filter.</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING TOOLTIP (Brand-Coloured with Deadline Warning & Risk Section) ===== -->
    <div
        x-show="tooltip.visible && tooltip.data"
        x-cloak
        :style="`position:fixed; left:${tooltip.x}px; top:${tooltip.y}px; z-index:9999; pointer-events:none; width:310px;`"
        x-transition:enter="transition ease-out duration-120"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-80"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <template x-if="tooltip.data">
            <div class="bg-white rounded-r-2xl rounded-l-md shadow-2xl border-y border-r border-slate-200 border-l-4 border-l-[#c3122e] overflow-hidden ring-1 ring-black/5">

                <!-- Header -->
                <div class="px-4 pt-3.5 pb-2.5 bg-gradient-to-r from-[#fdf4f4]/60 to-white border-b border-[#faeaea]">
                    <div class="flex items-center justify-between gap-1.5 mb-1">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] font-black uppercase tracking-widest text-[#c3122e]" x-text="'WBS ' + tooltip.data.wbs"></span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 inline-block"></span>
                            <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border bg-[#fdf4f4] text-[#c3122e] border-[#f5c2c9]"
                                  x-text="tooltip.data.type"></span>
                        </div>
                        <template x-if="tooltip.data.exceeds_deadline">
                            <span class="px-1.5 py-0.5 rounded text-[8.5px] font-black bg-rose-100 text-[#c3122e] border border-rose-300">
                                ⚠️ Exceeds Deadline
                            </span>
                        </template>
                    </div>
                    <div class="font-extrabold text-slate-800 text-[13px] leading-snug" x-text="tooltip.data.title"></div>
                </div>

                <!-- Body -->
                <div class="px-4 py-3 space-y-3 bg-white">
                    <!-- Assignee & Progress Row -->
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-full bg-[#c3122e] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-sm"
                                 x-text="tooltip.data.initial"></div>
                            <div class="min-w-0">
                                <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Assignee</div>
                                <div class="text-[11px] font-semibold text-slate-700 truncate" x-text="tooltip.data.assignee"></div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-0.5">Progress</div>
                            <div class="flex items-center gap-1.5 justify-end">
                                <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-full border leading-none"
                                      :class="{
                                          'bg-emerald-50 text-emerald-700 border-emerald-200': tooltip.data.progress == 100,
                                          'bg-sky-50 text-sky-700 border-sky-200': tooltip.data.progress > 0 && tooltip.data.progress < 100,
                                          'bg-slate-50 text-slate-650 border-slate-200': tooltip.data.progress == 0
                                      }"
                                      x-text="tooltip.data.status"></span>
                                <span class="text-xs font-black text-slate-800" x-text="tooltip.data.progress + '%'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress bar -->
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all"
                             :class="tooltip.data.progress == 100 ? 'bg-emerald-500' : 'bg-[#c3122e]'"
                             :style="`width: ${Math.max(tooltip.data.progress, 2)}%`"></div>
                    </div>

                    <!-- Dates capsule -->
                    <div class="bg-slate-50 rounded-lg px-2.5 py-1.5 border border-slate-100 flex items-center justify-between text-slate-600">
                        <div class="flex flex-col">
                            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Start</span>
                            <span class="text-[10px] font-mono font-bold text-slate-700 leading-none" x-text="tooltip.data.start"></span>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <div class="flex flex-col text-right">
                            <span class="text-[7px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">End</span>
                            <span class="text-[10px] font-mono font-bold text-slate-700 leading-none" x-text="tooltip.data.end"></span>
                        </div>
                    </div>

                    <!-- Associated Risks Box in Tooltip -->
                    <template x-if="tooltip.data.has_risk">
                        <div class="p-2.5 rounded-xl bg-amber-50/90 border border-amber-200 text-amber-950">
                            <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-amber-800 mb-1.5">
                                <span class="flex items-center gap-1">
                                    <span>⚠️</span>
                                    <span>Associated Risks</span>
                                </span>
                                <span class="px-1.5 py-0.5 rounded-full bg-amber-200 text-amber-900 font-mono text-[9px] font-black" x-text="tooltip.data.risks_count + ' ' + (tooltip.data.risks_count > 1 ? 'Risks' : 'Risk')"></span>
                            </div>
                            <div class="space-y-1 max-h-24 overflow-y-auto">
                                <template x-for="r in tooltip.data.risks_list" :key="r.title">
                                    <div class="flex items-center justify-between text-[10px] font-semibold bg-white/90 p-1.5 rounded-lg border border-amber-100">
                                        <span class="truncate max-w-[170px] text-slate-800" x-text="r.title"></span>
                                        <span class="text-[8px] font-black px-1.5 py-0.2 rounded" :class="r.score >= 6 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200'" x-text="r.severity"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
        </template>
    </div>

</div>
