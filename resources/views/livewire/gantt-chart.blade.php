<div x-data="{
    tooltip: { visible: false, x: 0, y: 0, data: null },
    showTooltip(el, data) {
        this.tooltip.data = data;
        const rect = el.getBoundingClientRect();
        const tw = 300, th = 210;
        const gap = 10;
        let x = rect.left + 270;
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
            container.scrollTo({ left: Math.max(0, {{ 280 + ($todayPx ?? 0) }} - 350), behavior: 'smooth' });
        } else if (type === 'deadline' && {{ !is_null($deadlinePx) ? 'true' : 'false' }}) {
            container.scrollTo({ left: Math.max(0, {{ 280 + ($deadlinePx ?? 0) }} - 350), behavior: 'smooth' });
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
            height: 6px;
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
    <div class="bg-white border border-slate-200/80 shadow-2xs rounded-2xl overflow-hidden">
        
        <!-- ── Clean Minimalist Toolbar ── -->
        <div class="px-4 py-3 sm:px-5 sm:py-3.5 bg-white border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-3">
            
            <!-- Left: Title, Code, Period Navigator & Zoom Scale -->
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                <!-- Title & Code -->
                <div class="flex items-center gap-2 shrink-0">
                    <span class="w-7 h-7 rounded-xl flex items-center justify-center text-white shadow-2xs bg-[#c3122e]">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    <span class="text-sm font-extrabold text-slate-900 tracking-tight whitespace-nowrap">WBS Gantt Schedule</span>
                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-200/70 shadow-2xs">
                        {{ $project->code }}
                    </span>
                </div>

                <!-- Date Navigator (< Today / Deadline >) -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/90 bg-slate-50/80 shadow-2xs p-0.5 shrink-0">
                    <button wire:click="goToPrevious" class="px-2 py-1 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Scroll Left">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        <span class="hidden sm:inline">Prev</span>
                    </button>
                    @if(!is_null($todayPx))
                        <button wire:click="goToToday" class="px-2.5 py-1 text-slate-700 hover:text-[#c3122e] hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border-x border-slate-200/60" title="Center Timeline on Today">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] animate-pulse"></span>
                            <span>Today</span>
                        </button>
                    @endif
                    @if(!is_null($deadlinePx))
                        <button wire:click="goToDeadline" class="px-2.5 py-1 text-slate-700 hover:text-amber-800 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border-r border-slate-200/60" title="Jump to Project Deadline">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Deadline</span>
                        </button>
                    @endif
                    <button wire:click="goToNext" class="px-2 py-1 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Scroll Right">
                        <span class="hidden sm:inline">Next</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Timeframe Scale Switcher (Day / Week / Month) -->
                <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 text-xs font-bold shrink-0">
                    <button wire:click="setTimeframe('day')" 
                            class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $timeframe === 'day' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                            title="Daily Resolution">
                        Day
                    </button>
                    <button wire:click="setTimeframe('week')" 
                            class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $timeframe === 'week' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                            title="Weekly Resolution">
                        Week
                    </button>
                    <button wire:click="setTimeframe('month')" 
                            class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $timeframe === 'month' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                            title="Monthly Overview">
                        Month
                    </button>
                </div>
            </div>

            <!-- Right: Search, Status Legend & Tree Controls -->
            <div class="flex items-center gap-3 flex-wrap justify-between xl:justify-end">
                
                <!-- Status Legend Dots -->
                <div class="flex items-center gap-3 text-xs font-semibold text-slate-600 overflow-x-auto scrollbar-none py-0.5 shrink-0">
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-xs"></span> Done</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-xs"></span> In Progress</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-xs"></span> Delayed</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-400 shadow-xs"></span> Planned</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-purple-600 rotate-45 rounded-xs shadow-xs"></span> Milestone</span>
                </div>

                <div class="h-3.5 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Search Input -->
                <div class="relative w-36 sm:w-44 shrink-0">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search WBS…"
                           class="w-full text-xs font-medium rounded-xl border border-slate-200/90 pl-7.5 pr-2.5 py-1 bg-slate-50/90 focus:bg-white focus:outline-none focus:ring-1.5 focus:ring-slate-400 placeholder:text-slate-400 shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

            </div>
        </div>

        <!-- ── Scrollable Gantt Canvas Area ── -->
        <div class="overflow-x-auto gantt-scroll" x-ref="timelineScrollContainer">
            {{-- Outer wrapper is exactly: 280px title col + totalCanvasPx --}}
            <div style="min-width: {{ 280 + $totalCanvasPx }}px;" class="flex flex-col relative">

                <!-- 1. MULTI-TIER HEADER (STICKY TOP) -->
                <div class="bg-slate-50/95 border-b border-slate-200/90 flex flex-col flex-shrink-0 sticky top-0 z-30 shadow-2xs backdrop-blur-xs">

                    <!-- Top: Month / Year headers -->
                    <div class="flex items-stretch border-b border-slate-200/80 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <div class="flex-shrink-0 border-r border-slate-200/90 bg-slate-50 text-slate-700 flex items-center justify-between px-4 py-2 sticky left-0 z-40" style="width:280px;">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                <span>WBS / TASK</span>
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[9.5px] font-mono font-bold bg-white text-slate-600 border border-slate-200 shadow-2xs">
                                {{ $wbsItems->count() }} {{ $wbsItems->count() === 1 ? 'Item' : 'Items' }}
                            </span>
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($monthHeaders as $mHead)
                                <div class="text-center border-r border-slate-200/80 font-bold text-slate-800 bg-slate-50/80 py-2 px-1 truncate tracking-wide text-xs"
                                     style="width:{{ $mHead['pxWidth'] }}px; flex-shrink:0;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom: Sub-column (Day / Week / Month) headers -->
                    <div class="flex items-stretch text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <div class="border-r border-slate-200/90 flex items-center px-4 py-1.5 bg-slate-50 text-[11px] font-bold text-slate-600 sticky left-0 z-40" style="width:280px; flex-shrink:0;">
                            Deliverable Hierarchy
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($columns as $col)
                                <div class="text-center border-r border-slate-200/80 py-1.5 px-1 flex-shrink-0 flex flex-col items-center justify-center relative
                                        {{ ($col['isToday'] ?? false) ? 'bg-rose-50/80 text-[#c3122e] font-black border-b-2 border-b-[#c3122e]' : (($col['isWeekend'] ?? false) ? 'bg-slate-100/30 text-slate-400' : 'text-slate-700') }}"
                                     style="width:{{ $col['px'] }}px;">
                                    <div class="text-xs leading-tight font-extrabold {{ ($col['isToday'] ?? false) ? 'text-[#c3122e]' : 'text-slate-800' }}">{{ $col['label'] }}</div>
                                    <div class="text-[9.5px] font-mono font-bold {{ ($col['isToday'] ?? false) ? 'text-rose-600' : 'text-slate-400' }} mt-0.5">{{ $col['sublabel'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. GANTT ROWS & TIMELINE CANVAS -->
                <div class="relative divide-y divide-slate-100 bg-white">

                    <!-- Today Laser Line (Background Layer, z-10) -->
                    @if(!is_null($todayPx))
                        <div class="absolute top-0 bottom-0 z-10 pointer-events-none" style="left:{{ 280 + $todayPx }}px; width:1.5px; border-left:2px dashed #c3122e; opacity:0.85;">
                            <!-- Top Capsule Flag -->
                            <div class="absolute top-1 -translate-x-1/2 z-20 whitespace-nowrap px-2 py-0.5 rounded-full text-[9.5px] font-bold bg-[#c3122e] text-white shadow-xs flex items-center gap-1 pointer-events-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span>Today</span>
                            </div>
                        </div>
                    @endif

                    <!-- Target Project Deadline Marker Line (Background Layer, z-10) -->
                    @if(!is_null($deadlinePx))
                        <div class="absolute top-0 bottom-0 z-10 pointer-events-none" style="left:{{ 280 + $deadlinePx }}px; width:1.5px; border-left:2px dashed #f59e0b; opacity:0.85;">
                            <!-- Top Banner Tag -->
                            <div class="absolute top-1 -translate-x-1/2 z-20 whitespace-nowrap px-2 py-0.5 rounded-full text-[9.5px] font-bold bg-amber-500 text-white shadow-xs flex items-center gap-1 pointer-events-auto">
                                <span>🎯</span>
                                <span>Deadline</span>
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
                                ['bg' => 'bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 border-blue-700 text-white shadow-xs shadow-blue-950/20'],
                                ['bg' => 'bg-gradient-to-r from-[#c3122e] via-rose-600 to-[#9e0f26] border-rose-800 text-white shadow-xs shadow-rose-950/20'],
                                ['bg' => 'bg-gradient-to-r from-teal-600 via-emerald-600 to-teal-700 border-teal-800 text-white shadow-xs shadow-teal-950/20'],
                                ['bg' => 'bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 border-amber-700 text-white shadow-xs shadow-amber-950/20'],
                                ['bg' => 'bg-gradient-to-r from-purple-600 via-violet-600 to-purple-700 border-purple-800 text-white shadow-xs shadow-purple-950/20'],
                            ];
                            $pStyle = $phaseStyles[$index % count($phaseStyles)];

                            $taskStyle = match($item->status->value) {
                                'completed'   => 'bg-gradient-to-r from-emerald-500 to-teal-600 border-emerald-700 text-white shadow-xs shadow-emerald-950/20',
                                'in_progress' => 'bg-gradient-to-r from-blue-500 to-blue-600 border-blue-700 text-white shadow-xs shadow-blue-950/20',
                                'blocked'     => 'bg-gradient-to-r from-rose-500 to-rose-600 border-rose-700 text-white shadow-xs shadow-rose-950/20',
                                default       => 'bg-gradient-to-r from-slate-400 to-slate-500 border-slate-600 text-white shadow-xs shadow-slate-950/20',
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

                        <div class="flex items-center transition-colors min-h-[44px] h-[44px] text-xs cursor-default hover:bg-slate-50/80 group">

                            <!-- Left: WBS Title Column (fixed 280px, sticky left) -->
                            <div class="border-r border-slate-200/90 flex-shrink-0 flex items-center justify-between overflow-hidden px-3.5 h-[44px] bg-white group-hover:bg-slate-50 sticky left-0 z-20 transition-colors cursor-pointer shadow-2xs"
                                 style="width:280px;"
                                 @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                 @mouseleave="hideTooltip()">
                                <div style="padding-left: {{ $levelIndent * 0.75 }}rem;" class="flex items-center gap-2 min-w-0 flex-1">
                                    @if($item->children->isNotEmpty())
                                        @php $isCollapsed = in_array($item->id, $collapsedIds); @endphp
                                        <button type="button"
                                                wire:click="toggleCollapse({{ $item->id }})"
                                                class="w-5 h-5 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-500 transition-all flex items-center justify-center cursor-pointer flex-shrink-0 focus:outline-none shadow-2xs"
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
                                    @elseif($item->is_milestone)
                                        <span class="text-amber-500 text-sm flex-shrink-0">💎</span>
                                    @else
                                        <span class="text-slate-400 text-xs flex-shrink-0">📄</span>
                                    @endif

                                    <span class="font-mono text-[10px] font-bold px-1.5 py-0.2 rounded-md bg-slate-100 text-slate-600 border border-slate-200/80 flex-shrink-0">{{ $item->wbs_code }}</span>

                                    <span class="truncate text-slate-800 {{ $item->item_type->value === 'phase' ? 'font-extrabold text-slate-900 text-xs' : 'font-medium' }}" title="{{ $item->title }}">
                                        {{ $item->title }}
                                    </span>
                                </div>

                                <!-- Right: Badges inside left column -->
                                <div class="flex items-center gap-1.5 flex-shrink-0 pl-1">
                                    @if($risksCount > 0)
                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[9px] font-black {{ $highestRiskScore >= 6 ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }} shadow-2xs flex-shrink-0"
                                              title="{{ $risksCount }} Active Risk(s)">
                                            <span>⚠️</span>
                                            <span class="font-mono">{{ $risksCount }}</span>
                                        </span>
                                    @endif

                                    @if($exceedsDeadline)
                                        <span class="px-1.5 py-0.2 rounded text-[8.5px] font-black uppercase bg-rose-100 text-[#c3122e] border border-rose-300 flex-shrink-0" title="Exceeds deadline">
                                            Late
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Timeline canvas (exact totalCanvasPx wide) -->
                            <div class="relative flex-shrink-0 h-[44px] flex items-center" style="width:{{ $totalCanvasPx }}px;">

                                <!-- Grid column lines (subtle background) -->
                                @php $lineLeft = 0; @endphp
                                @foreach($columns as $col)
                                    <div class="absolute top-0 bottom-0 border-r border-slate-100 {{ ($col['isToday'] ?? false) ? 'bg-rose-50/15' : (($col['isWeekend'] ?? false) ? 'bg-slate-50/40' : '') }}"
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
                                            <div class="w-5 h-5 bg-gradient-to-br from-purple-500 via-violet-600 to-indigo-700 rotate-45 border-2 border-white shadow-md flex-shrink-0 ring-2 ring-purple-300"></div>
                                            @if($risksCount > 0)
                                                <span class="absolute -top-2 -right-2 text-[10px] bg-rose-500 text-white rounded-full w-4 h-4 flex items-center justify-center font-black ring-1 ring-white shadow-xs">⚠️</span>
                                            @endif
                                         </div>
                                        <span class="text-[10.5px] font-bold text-purple-900 whitespace-nowrap bg-purple-50/95 px-2.5 py-0.5 rounded-lg shadow-2xs border border-purple-200 flex items-center gap-1.5">
                                            <span>◆ {{ $item->title }}</span>
                                            @if($risksCount > 0)
                                                <span class="text-rose-700 font-black text-[9px] bg-rose-100 px-1 py-0.2 rounded border border-rose-200">⚠️ {{ $risksCount }}</span>
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
                                            'completed'   => 'bg-gradient-to-r from-emerald-500 to-teal-600 border-emerald-700 shadow-xs shadow-emerald-950/20',
                                            'in_progress' => 'bg-gradient-to-r from-blue-500 to-blue-600 border-blue-700 shadow-xs shadow-blue-950/20',
                                            'blocked'     => 'bg-gradient-to-r from-rose-500 to-rose-600 border-rose-700 shadow-xs shadow-rose-950/20',
                                            default       => 'bg-gradient-to-r from-slate-400 to-slate-500 border-slate-600 shadow-xs shadow-slate-950/20',
                                        };
                                        $slotWidth = max(24, $barWidth - 2);
                                    @endphp

                                    <div class="absolute top-1/2 -translate-y-1/2 z-20"
                                         style="left:{{ $barLeft + 1 }}px; width:{{ $slotWidth }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        <div class="relative h-6 rounded-lg overflow-hidden flex items-center cursor-pointer hover:scale-[1.01] hover:brightness-105 transition-all shadow-xs border border-white/20 {{ $hourBg }}">
                                            @if($item->progress > 0)
                                                <div class="absolute inset-y-0 left-0 rounded-l-lg bg-white/20 backdrop-blur-[0.5px]"
                                                     style="width:{{ $item->progress }}%;"></div>
                                            @endif
                                            <span class="absolute inset-0 flex items-center px-2 gap-1 overflow-hidden justify-center sm:justify-start">
                                                <svg class="w-2.5 h-2.5 text-white flex-shrink-0 opacity-90 hidden sm:inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                @php
                                                    $compactStart = $tStart ? trim(preg_replace('/\s*(AM|PM)/i', '', $tStart)) : null;
                                                    $compactEnd   = $tEnd   ? trim(preg_replace('/\s*(AM|PM)/i', '', $tEnd))   : null;
                                                @endphp
                                                @if($slotWidth >= 110 && $compactStart && $compactEnd)
                                                    <span class="text-[9.5px] font-bold text-white truncate drop-shadow-sm font-mono">{{ $compactStart }}–{{ $compactEnd }}</span>
                                                @elseif($slotWidth >= 32 && $compactStart)
                                                    <span class="text-[9.5px] font-bold text-white truncate drop-shadow-sm font-mono tracking-tight">{{ $compactStart }}</span>
                                                @else
                                                    <span class="text-[8.5px] font-bold text-white/90">1h</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                @elseif($item->item_type->value === 'phase')
                                    <!-- ── PHASE CAPSULE BAR ── -->
                                    <div class="absolute h-7.5 rounded-xl flex items-center overflow-hidden z-20 hover:brightness-105 transition-all cursor-pointer shadow-xs border {{ $pStyle['bg'] }} {{ $exceedsDeadline ? 'ring-2 ring-rose-500' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        @if($item->progress > 0)
                                            <div class="h-full bg-white/20 backdrop-blur-[0.5px] rounded-l-xl transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-3 text-xs font-bold text-white truncate tracking-tight drop-shadow-xs">
                                            <span class="truncate">📁 {{ $item->title }} ({{ $item->progress }}%)</span>
                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                @if($exceedsDeadline)
                                                    <span class="px-1.5 py-0.2 rounded bg-rose-700 text-white text-[8.5px] font-bold shadow-xs">Late</span>
                                                @endif
                                                @if($risksCount > 0)
                                                    <span class="px-1.5 py-0.2 rounded bg-amber-400 text-slate-900 text-[8.5px] font-black shadow-xs flex items-center gap-0.5">
                                                        <span>⚠️</span>
                                                        <span>{{ $risksCount }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </span>
                                    </div>

                                @else
                                    <!-- ── TASK / SUBTASK CAPSULE BAR ── -->
                                    <div class="absolute h-6 rounded-lg flex items-center overflow-hidden z-20 hover:brightness-105 transition-all cursor-pointer shadow-xs border {{ $taskStyle }} {{ $exceedsDeadline ? 'ring-2 ring-rose-500' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        @if($item->progress > 0)
                                            <div class="h-full bg-white/20 backdrop-blur-[0.5px] rounded-l-lg transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-2.5 text-[10.5px] font-bold truncate text-white drop-shadow-xs">
                                            <span class="truncate">
                                                @if($item->status->value === 'completed') ✓ {{ $item->title }} (100%)
                                                @else {{ $item->title }} ({{ $item->progress }}%)
                                                @endif
                                            </span>
                                            <div class="flex items-center gap-1 flex-shrink-0">
                                                @if($exceedsDeadline)
                                                    <span class="px-1 py-0.2 rounded bg-rose-700 text-white text-[8px] font-bold">Late</span>
                                                @endif
                                                @if($risksCount > 0)
                                                    <span class="px-1 py-0.2 rounded bg-amber-400 text-slate-900 text-[8.5px] font-black shadow-xs flex items-center gap-0.5" title="{{ $risksCount }} Associated Risk(s)">
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
        :style="`position:fixed; left:${tooltip.x}px; top:${tooltip.y}px; z-index:9999; pointer-events:none; width:300px;`"
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
                <div class="px-4 pt-3 pb-2 bg-gradient-to-r from-[#fdf4f4]/60 to-white border-b border-[#faeaea]">
                    <div class="flex items-center justify-between gap-1.5 mb-1">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] font-black uppercase tracking-widest text-[#c3122e]" x-text="'WBS ' + tooltip.data.wbs"></span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 inline-block"></span>
                            <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border bg-[#fdf4f4] text-[#c3122e] border-[#f5c2c9]"
                                  x-text="tooltip.data.type"></span>
                        </div>
                        <template x-if="tooltip.data.exceeds_deadline">
                            <span class="px-1.5 py-0.5 rounded text-[8.5px] font-black bg-rose-100 text-[#c3122e] border border-rose-300">
                                ⚠️ Late
                            </span>
                        </template>
                    </div>
                    <div class="font-extrabold text-slate-800 text-xs leading-snug" x-text="tooltip.data.title"></div>
                </div>

                <!-- Body -->
                <div class="px-4 py-3 space-y-2.5 bg-white">
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
                        <div class="p-2 rounded-xl bg-amber-50/90 border border-amber-200 text-amber-950">
                            <div class="flex items-center justify-between text-[9.5px] font-black uppercase tracking-wider text-amber-800 mb-1">
                                <span class="flex items-center gap-1">
                                    <span>⚠️</span>
                                    <span>Associated Risks</span>
                                </span>
                                <span class="px-1.5 py-0.2 rounded-full bg-amber-200 text-amber-900 font-mono text-[8.5px] font-black" x-text="tooltip.data.risks_count + ' ' + (tooltip.data.risks_count > 1 ? 'Risks' : 'Risk')"></span>
                            </div>
                            <div class="space-y-1 max-h-20 overflow-y-auto">
                                <template x-for="r in tooltip.data.risks_list" :key="r.title">
                                    <div class="flex items-center justify-between text-[9.5px] font-semibold bg-white/90 p-1 rounded border border-amber-100">
                                        <span class="truncate max-w-[170px] text-slate-800" x-text="r.title"></span>
                                        <span class="text-[8px] font-black px-1 py-0.2 rounded" :class="r.score >= 6 ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-amber-100 text-amber-800 border border-amber-200'" x-text="r.severity"></span>
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
