<div x-data="{
    zoom: 100,
    tooltip: { visible: false, x: 0, y: 0, data: null },
    showTooltip(el, data) {
        this.tooltip.data = data;
        const rect = el.getBoundingClientRect();
        const tw = 275, th = 220;
        const gap = 10;
        let x = rect.left + 20;
        let y = rect.top - th - gap;
        if (x + tw > window.innerWidth - 8) x = window.innerWidth - tw - 12;
        if (x < 8) x = 8;
        if (y < 8) y = rect.bottom + gap;
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
        }
    },
    scrollTimeline(dir) {
        const container = this.$refs.timelineScrollContainer;
        if (!container) return;
        const offset = dir === 'left' ? -300 : 300;
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
    <div class="bg-white border border-slate-200/80 shadow-xs rounded-2xl p-6 overflow-hidden" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
        
        <!-- ── HEADER ROW: Title, Task Count & Status Legend ── -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <!-- Left: Title & Count -->
            <div class="flex items-baseline gap-2">
                <h2 class="text-lg font-black text-slate-900 tracking-tight">
                    WBS Gantt Schedule
                </h2>
                <span class="text-xs text-slate-400 font-normal">
                    {{ $wbsItems->count() }} {{ $wbsItems->count() === 1 ? 'task' : 'tasks' }}
                </span>
            </div>

            <!-- Right: Status Legends -->
            <div class="flex items-center gap-3 sm:gap-4 text-xs font-medium text-slate-600 flex-wrap">
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span>
                    <span>Task</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span>
                    <span>In Progress</span>
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#10b981]"></span>
                    <span>Completed</span>
                </span>
                <button wire:click="$set('statusFilter', '{{ $statusFilter === 'at_risk' ? 'all' : 'at_risk' }}')"
                        type="button" 
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg transition-all cursor-pointer {{ $statusFilter === 'at_risk' ? 'bg-rose-100 text-rose-800 ring-1 ring-rose-400 font-bold' : 'hover:bg-slate-100 text-slate-600' }}"
                        title="Click to toggle filter by At Risk tasks">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#e11d48] animate-pulse"></span>
                    <span>At Risk</span>
                    @if(isset($atRiskCount) && $atRiskCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[9.5px] font-black bg-[#e11d48] text-white">
                            {{ $atRiskCount }}
                        </span>
                    @endif
                </button>
                <span class="inline-flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 bg-[#8b5cf6] rotate-45 rounded-2xs"></span>
                    <span>Milestone</span>
                </span>
            </div>
        </div>

        <!-- ── CONTROLS ROW: View Mode, Date Navigation & Search ── -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <!-- Left Controls: Day / Week / Month Switcher & Prev / Today / Next -->
            <div class="flex items-center gap-3 flex-wrap">
                <!-- Timeframe Mode Switcher -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white shadow-2xs p-0.5">
                    <button wire:click="setTimeframe('day')" type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $timeframe === 'day' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Day
                    </button>
                    <button wire:click="setTimeframe('week')" type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $timeframe === 'week' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Week
                    </button>
                    <button wire:click="setTimeframe('month')" type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $timeframe === 'month' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Month
                    </button>
                </div>

                <!-- Navigation Controls (< Today >) -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white shadow-2xs p-0.5 divide-x divide-slate-100">
                    <button wire:click="goToPrevious" @click="scrollTimeline('left')" type="button" class="px-3 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-bold transition-colors cursor-pointer" title="Previous">
                        ‹
                    </button>
                    <button wire:click="goToToday" @click="scrollToMarker('today')" type="button" class="px-3.5 py-1.5 text-slate-700 hover:text-[#c3122e] hover:bg-slate-50 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                        Today
                    </button>
                    <button wire:click="goToNext" @click="scrollTimeline('right')" type="button" class="px-3 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-bold transition-colors cursor-pointer" title="Next">
                        ›
                    </button>
                </div>

                <!-- Collapse / Expand All Controls -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/80 bg-white shadow-2xs p-0.5 divide-x divide-slate-100">
                    <button wire:click="collapseAll" type="button" class="px-2.5 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1" title="Collapse All Tasks">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        <span>Collapse</span>
                    </button>
                    <button wire:click="expandAll" type="button" class="px-2.5 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1" title="Expand All Tasks">
                        <svg class="w-3.5 h-3.5 text-slate-400 -rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        <span>Expand</span>
                    </button>
                </div>
            </div>

            <!-- Right: Status Filter & Search Input -->
            <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                <div class="relative">
                    <select wire:model.live="statusFilter" class="text-xs font-semibold py-2 pl-3 pr-8 rounded-xl border border-slate-200/80 bg-white text-slate-700 focus:outline-none focus:border-[#c3122e] shadow-2xs cursor-pointer">
                        <option value="all">All Statuses</option>
                        <option value="at_risk">⚠️ At Risk {{ isset($atRiskCount) && $atRiskCount > 0 ? "($atRiskCount)" : "" }}</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="not_started">Not Started</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-56 shrink-0">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search tasks..."
                           class="w-full pl-9 pr-3.5 py-2 text-xs rounded-xl border border-slate-200/80 bg-white focus:outline-none focus:border-[#c3122e] text-slate-800 placeholder-slate-400 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- ── GANTT TABLE & TIMELINE CANVAS ── -->
        <div class="overflow-x-auto gantt-scroll border border-slate-200/80 rounded-xl" x-ref="timelineScrollContainer">
            {{-- Fixed width container: 280px left table + totalCanvasPx --}}
            <div style="min-width: {{ 280 + $totalCanvasPx }}px;" class="flex flex-col relative bg-white">

                <!-- 1. MULTI-TIER HEADER (STICKY TOP) -->
                <div class="flex items-stretch border-b border-slate-200/90 bg-white sticky top-0 z-30 select-none">
                    
                    <!-- Left Pinned Table Header (280px wide) -->
                    <div class="sticky left-0 z-40 flex bg-white border-r border-slate-200/90 shadow-xs" style="width: 280px; flex-shrink: 0;">
                        <div class="w-10 text-center flex items-center justify-center text-xs font-bold text-slate-700">#</div>
                        <div class="w-60 px-3.5 flex items-center text-xs font-bold text-slate-800">Task / WBS</div>
                    </div>

                    <!-- Right Timeline Header (Month top tier + Week/Day bottom tier) -->
                    <div class="flex flex-col relative" style="width: {{ $totalCanvasPx }}px; flex-shrink: 0;">
                        <!-- Tier 1: Months -->
                        <div class="flex h-8 border-b border-slate-100 bg-white">
                            @foreach($monthHeaders as $mHead)
                                <div class="text-center border-r border-slate-200/80 font-bold text-slate-800 text-xs flex items-center justify-center px-1 truncate"
                                     style="width: {{ $mHead['pxWidth'] }}px; flex-shrink: 0;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>

                        <!-- Tier 2: Sub-columns (Weeks / Days) -->
                        <div class="flex h-9 bg-white">
                            @foreach($columns as $col)
                                <div class="text-center border-r border-slate-200/80 flex flex-col items-center justify-center px-0.5 relative"
                                     style="width: {{ $col['px'] }}px; flex-shrink: 0;">
                                    <span class="text-[11px] font-bold text-slate-800 leading-none">{{ $col['label'] }}</span>
                                    <span class="text-[9.5px] font-normal text-slate-400 mt-0.5 leading-none">{{ $col['sublabel'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Today Pill Badge (Positioned directly in timeline header above vertical line) -->
                        @if(!is_null($todayPx))
                            <div class="absolute bottom-0 z-30 pointer-events-none" style="left: {{ $todayPx }}px; transform: translateX(-50%) translateY(50%);">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#c3122e] text-white shadow-xs">
                                    Today
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 2. GANTT ROWS AREA -->
                <div class="relative divide-y divide-slate-100 bg-white">

                    <!-- Today Vertical Red Dashed Line (Extends across all rows) -->
                    @if(!is_null($todayPx))
                        <div class="absolute top-0 bottom-0 pointer-events-none z-10"
                             style="left: {{ 280 + $todayPx }}px; width: 2px; border-left: 2px dashed #c3122e;">
                        </div>
                    @endif

                    @forelse($wbsItems as $index => $item)
                        @php
                            /* ── Resolve dates ── */
                            $itemStart = $item->start_date ? $item->start_date->copy()->startOfDay() : $timelineStart->copy();
                            $itemEnd   = $item->end_date   ? $item->end_date->copy()->endOfDay()     : $itemStart->copy()->addDays(4);

                            /* ── Pixel positions using shared dayToPx map ── */
                            $startDayOff = max(0, (int) $timelineStart->diffInDays($itemStart, false));
                            $endDayOff   = max($startDayOff + 1, (int) $timelineStart->diffInDays($itemEnd, false) + 1);

                            $startDayOff = min($startDayOff, count($dayToPx) - 1);
                            $endDayOff   = min($endDayOff,   count($dayToPx) - 1);

                            $barLeft  = round($dayToPx[$startDayOff], 2);
                            $barRight = round($dayToPx[$endDayOff],   2);
                            $barWidth = max(10, $barRight - $barLeft);

                            /* ── Indent level ── */
                            $levelIndent = match($item->item_type->value) {
                                'work_package' => 1,
                                'task'         => 2,
                                'subtask'      => 3,
                                default        => 0,
                            };

                            /* ── Risk & Status Detection ── */
                            $statusVal = is_object($item->status) ? $item->status->value : (string) $item->status;
                            $openRisks = $item->relationLoaded('risks') 
                                ? $item->risks->filter(fn($r) => in_array(strtolower($r->status), ['open', 'active', 'identified'])) 
                                : collect();
                            $isAtRisk = ($statusVal === 'at_risk' || $openRisks->isNotEmpty());
                            $isCompleted = ($statusVal === 'completed' || $item->progress == 100);
                            $isInProgress = ($statusVal === 'in_progress' || ($item->progress > 0 && $item->progress < 100));

                            /* ── Bar Color: Prioritize At Risk with Rose/Red styling ── */
                            $barBg = $isCompleted 
                                ? 'bg-[#10b981]' 
                                : ($isAtRisk 
                                    ? 'bg-[#e11d48] ring-2 ring-rose-400 ring-offset-1 shadow-md shadow-rose-200' 
                                    : ($isInProgress ? 'bg-[#3b82f6]' : 'bg-[#f59e0b]'));

                            /* ── Duration Days Calculation ── */
                            $durDays = ($item->start_date && $item->end_date)
                                ? max(1, (int) $item->start_date->diffInDays($item->end_date) + 1)
                                : max(1, (int) ($item->duration ?? 1));

                            /* ── Risk Details for Tooltip ── */
                            $firstRisk = $openRisks->first();
                            $riskAlert = null;
                            if ($isAtRisk) {
                                if ($firstRisk) {
                                    $riskAlert = $firstRisk->title . ($firstRisk->impact ? ' (Impact: ' . ucfirst($firstRisk->impact) . ')' : '');
                                } else {
                                    $riskAlert = 'Task flagged as At Risk';
                                }
                            }

                            /* ── Tooltip Data ── */
                            $typeLabel = $item->is_milestone ? 'Milestone' : ucfirst($item->item_type->value);
                            $assigneeName = $item->assignedUser ? $item->assignedUser->name : 'Unassigned';
                            $statusLabel = $isAtRisk ? 'At Risk' : (is_object($item->status) ? $item->status->label() : ucfirst(str_replace('_', ' ', $statusVal)));
                            $tooltipData = json_encode([
                                'wbs'        => $item->wbs_code,
                                'title'      => $item->title,
                                'type'       => $typeLabel,
                                'assignee'   => $assigneeName,
                                'start'      => $item->start_date ? $item->start_date->format('M d, Y') : '—',
                                'end'        => $item->end_date   ? $item->end_date->format('M d, Y')   : '—',
                                'duration'   => $durDays . ($durDays === 1 ? ' day' : ' days'),
                                'progress'   => $item->progress,
                                'status'     => $statusLabel,
                                'is_at_risk' => $isAtRisk,
                                'risk_alert' => $riskAlert,
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                            $hasChildren = in_array($item->id, $parentIds ?? []) || ($item->children && $item->children->count() > 0);
                            $isCollapsed = in_array($item->id, $collapsedIds ?? []);
                            $cleanTitle = str_replace(['???', '??'], '–', $item->title);
                        @endphp

                        <div class="flex items-center min-h-[48px] h-[48px] text-xs cursor-default hover:bg-slate-50/70 group transition-colors">

                            <!-- Left Pinned Table Cells (280px wide, sticky left) -->
                            <div class="sticky left-0 z-20 flex items-center h-[48px] bg-white group-hover:bg-slate-50 border-r border-slate-200/90 shadow-2xs transition-colors" style="width: 280px; flex-shrink: 0;">
                                <!-- # Column -->
                                <div class="w-10 text-center flex-shrink-0 text-slate-500 font-normal">
                                    {{ $loop->iteration }}
                                </div>

                                <!-- Task / WBS Column with Expand/Collapse Chevron -->
                                <div class="w-60 px-2 flex-shrink-0 flex items-center gap-1.5 min-w-0" style="padding-left: {{ max(8, 8 + ($levelIndent * 12)) }}px;">
                                    @if($hasChildren)
                                        <button 
                                            wire:click="toggleCollapse({{ $item->id }})" 
                                            type="button" 
                                            class="w-5 h-5 rounded hover:bg-slate-200/80 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-all cursor-pointer shrink-0"
                                            title="{{ $isCollapsed ? 'Expand subtasks' : 'Collapse subtasks' }}"
                                        >
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $isCollapsed ? '-rotate-90 text-slate-400' : 'rotate-0 text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    @else
                                        <div class="w-5 shrink-0"></div>
                                    @endif

                                    @if($item->is_milestone)
                                        <div class="w-3.5 h-3.5 rotate-45 border-2 {{ $isAtRisk ? 'border-[#e11d48] bg-rose-50 ring-1 ring-rose-400' : 'border-[#8b5cf6] bg-purple-50' }} shrink-0 flex items-center justify-center"></div>
                                    @elseif($hasChildren)
                                        <!-- Folder Icon -->
                                        <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    @else
                                        <!-- Document Icon -->
                                        <svg class="w-4 h-4 {{ $isAtRisk ? 'text-rose-500' : 'text-slate-400' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                                        <span class="truncate font-medium {{ $isAtRisk ? 'text-rose-950 font-semibold' : 'text-slate-800' }}" title="{{ $cleanTitle }}">
                                            {{ $cleanTitle }}
                                        </span>
                                        @if($isAtRisk)
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200 shrink-0 shadow-2xs"
                                                  title="{{ $riskAlert ?? 'Task is marked At Risk' }}">
                                                <svg class="w-2.5 h-2.5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>Risk</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right Timeline Canvas -->
                            <div class="relative flex-shrink-0 h-[48px] flex items-center" style="width: {{ $totalCanvasPx }}px;">
                                <!-- Column Grid Lines -->
                                @php $colLeft = 0; @endphp
                                @foreach($columns as $col)
                                    <div class="absolute top-0 bottom-0 border-r border-slate-100" style="left: {{ $colLeft }}px; width: {{ $col['px'] }}px;"></div>
                                    @php $colLeft += $col['px']; @endphp
                                @endforeach

                                <!-- Gantt Bar or Milestone Diamond -->
                                @if($item->is_milestone)
                                    <div class="absolute top-1/2 -translate-y-1/2 z-20 cursor-pointer"
                                         style="left: {{ $barLeft + 10 }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        <div class="w-4 h-4 {{ $isAtRisk ? 'bg-[#e11d48] ring-2 ring-rose-300' : 'bg-[#8b5cf6]' }} rotate-45 rounded-2xs shadow-xs hover:scale-125 transition-transform"></div>
                                    </div>
                                @else
                                    <div class="absolute top-1/2 -translate-y-1/2 h-6 rounded-md {{ $barBg }} z-20 cursor-pointer shadow-2xs hover:brightness-105 transition-all overflow-hidden flex items-center px-2 gap-1.5"
                                         style="left: {{ $barLeft }}px; width: {{ max(18, $barWidth) }}px;"
                                         @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                         @mouseleave="hideTooltip()">
                                        @if($item->progress > 0 && $item->progress < 100)
                                            <div class="absolute inset-y-0 left-0 bg-black/15 pointer-events-none" style="width: {{ $item->progress }}%;"></div>
                                        @endif
                                        @if($isAtRisk)
                                            <svg class="relative z-10 w-3.5 h-3.5 text-white shrink-0 drop-shadow-xs" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        @if($barWidth >= 70)
                                            <span class="relative z-10 text-[10.5px] font-semibold text-white truncate pointer-events-none drop-shadow-xs">
                                                {{ $item->title }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs font-semibold">No WBS tasks matching your query.</div>
                    @endforelse

                </div>
            </div>
        </div>

        <!-- ── FOOTER ROW: Showing count & Zoom Controls ── -->
        <div class="flex items-center justify-between pt-4 mt-2 text-xs">
            <!-- Left: Showing count -->
            <span class="text-slate-400 font-medium">
                Showing {{ $wbsItems->count() }} of {{ $allRawItems->count() }} tasks
            </span>

            <!-- Right: Zoom Controls -->
            <div class="flex items-center gap-1.5 text-slate-500 font-medium">
                <span class="text-slate-400 mr-1">Zoom:</span>
                <button @click="zoom = Math.max(50, zoom - 25)" type="button" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold flex items-center justify-center text-xs cursor-pointer transition-colors" title="Zoom Out">
                    -
                </button>
                <span x-text="zoom + '%'" class="text-slate-600 font-semibold px-1 text-xs">100%</span>
                <button @click="zoom = Math.min(200, zoom + 25)" type="button" class="w-6 h-6 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold flex items-center justify-center text-xs cursor-pointer transition-colors" title="Zoom In">
                    +
                </button>
                <button @click="zoom = 100" type="button" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer transition-colors ml-1">
                    Fit
                </button>
            </div>
        </div>

    </div>

    <!-- ===== FLOATING TOOLTIP ===== -->
    <div
        x-show="tooltip.visible && tooltip.data"
        x-cloak
        :style="`position:fixed; left:${tooltip.x}px; top:${tooltip.y}px; z-index:9999; pointer-events:none; width:275px;`"
        x-transition:enter="transition ease-out duration-120"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-80"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <template x-if="tooltip.data">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200/90 p-4 space-y-2.5">
                <!-- Header: WBS code & Status Badge -->
                <div class="flex items-center justify-between gap-1.5">
                    <span class="text-[11px] font-bold text-slate-400 font-mono tracking-wide" x-text="tooltip.data.wbs"></span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold shadow-2xs"
                          :class="{
                              'bg-emerald-50 text-emerald-700 border border-emerald-200': tooltip.data.status === 'Completed',
                              'bg-rose-50 text-rose-700 border border-rose-300 ring-1 ring-rose-400/20': tooltip.data.is_at_risk || tooltip.data.status === 'At Risk',
                              'bg-blue-50 text-blue-700 border border-blue-200': !tooltip.data.is_at_risk && tooltip.data.status === 'In Progress',
                              'bg-amber-50 text-amber-700 border border-amber-200': !tooltip.data.is_at_risk && tooltip.data.status !== 'Completed' && tooltip.data.status !== 'In Progress' && tooltip.data.status !== 'At Risk'
                          }"
                          x-text="tooltip.data.status"></span>
                </div>

                <!-- Title -->
                <div class="text-xs font-bold text-slate-900 leading-snug" x-text="tooltip.data.title"></div>

                <!-- Risk Alert Box (if at risk) -->
                <template x-if="tooltip.data.is_at_risk && tooltip.data.risk_alert">
                    <div class="flex items-start gap-2 p-2 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-[10.5px]">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <span class="font-extrabold block text-rose-900">Risk Identified:</span>
                            <span x-text="tooltip.data.risk_alert" class="leading-tight block"></span>
                        </div>
                    </div>
                </template>

                <!-- Assignee Pill / Box -->
                <div class="flex items-center gap-2 text-xs text-slate-700 bg-slate-50/80 px-2.5 py-1.5 rounded-xl border border-slate-100">
                    <div class="w-5 h-5 rounded-full bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-2xs text-slate-400">
                        <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1 flex items-center justify-between gap-1">
                        <span class="text-[10px] text-slate-400 font-medium">Assignee</span>
                        <span class="font-semibold text-slate-800 text-[11px] truncate" x-text="tooltip.data.assignee"></span>
                    </div>
                </div>

                <!-- Dates & Duration Grid -->
                <div class="grid grid-cols-2 gap-2 text-[10.5px] text-slate-500 pt-2 border-t border-slate-100">
                    <div>
                        <span class="block text-[10px] text-slate-400">Start</span>
                        <span class="font-semibold text-slate-700" x-text="tooltip.data.start"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400">End</span>
                        <span class="font-semibold text-slate-700" x-text="tooltip.data.end"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400">Duration</span>
                        <span class="font-semibold text-slate-700" x-text="tooltip.data.duration"></span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400">Progress</span>
                        <span class="font-semibold text-slate-700" x-text="tooltip.data.progress + '%'"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>
