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
    hideTooltip() { this.tooltip.visible = false; }
}">

    <!-- ===== GANTT HERO CARD CONTAINER ===== -->
    <div class="card p-0 overflow-hidden shadow-xs border border-slate-200/90 bg-white rounded-2xl mb-6">
        <!-- Top Toolbar & Legend -->
        <div class="p-3 sm:px-5 bg-gradient-to-r from-slate-50/80 via-white to-slate-50/80 border-b border-slate-200/90 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Left: Timeline Title + Date Navigator (< Today >) + Legend -->
            <div class="flex items-center gap-3 sm:gap-3.5 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black text-slate-900 tracking-tight flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span>Project Timeline</span>
                    </span>
                </div>

                <!-- Sleek Date Navigation Pill Cluster (< Today >) -->
                <div class="inline-flex items-center rounded-xl border border-slate-200/90 bg-white shadow-2xs overflow-hidden p-0.5">
                    <button wire:click="goToPrevious" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-bold transition-all cursor-pointer" title="Previous Range">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button wire:click="goToToday" class="px-3 py-1 text-slate-800 hover:text-[#c3122e] hover:bg-rose-50/80 rounded-lg text-xs font-black transition-all cursor-pointer" title="Go to Today">
                        Today
                    </button>
                    <button wire:click="goToNext" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-bold transition-all cursor-pointer" title="Next Range">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- Legend Pills (Including Risk Indicator) -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-rose-50/80 text-[#c3122e] border border-rose-200/80 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span> In Progress
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-emerald-50/80 text-emerald-700 border border-emerald-200/80 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Completed
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-amber-50/80 text-amber-700 border border-amber-200/80 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Planned
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-rose-50/80 text-[#c3122e] border border-rose-200/80 flex items-center gap-1.5 shadow-2xs">
                        <span class="w-1.5 h-1.5 bg-[#c3122e] rotate-45"></span> Milestone
                    </span>
                    <span class="px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-amber-50 text-amber-800 border border-amber-300 flex items-center gap-1 shadow-2xs">
                        <span>⚠️</span> Has Risk
                    </span>
                </div>
            </div>

            <!-- Right: Search Input + Timeframe Switcher (Day/Week/Month) -->
            <div class="flex items-center gap-2.5 flex-wrap justify-between sm:justify-end">
                <div class="relative w-40 sm:w-48">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks..."
                           class="w-full text-xs font-medium rounded-xl border border-slate-200 pl-8 pr-3 py-1.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 outline-none bg-white shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <div class="inline-flex p-1 rounded-xl bg-slate-100/90 border border-slate-200/90 gap-0.5 shadow-2xs">
                    <button wire:click="setTimeframe('day')"   class="px-2.5 py-1 rounded-lg text-xs font-extrabold transition-all cursor-pointer {{ $timeframe === 'day'   ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Day</button>
                    <button wire:click="setTimeframe('week')"  class="px-2.5 py-1 rounded-lg text-xs font-extrabold transition-all cursor-pointer {{ $timeframe === 'week'  ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Week</button>
                    <button wire:click="setTimeframe('month')" class="px-2.5 py-1 rounded-lg text-xs font-extrabold transition-all cursor-pointer {{ $timeframe === 'month' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Month</button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto scrollbar-thin">
            {{-- Outer wrapper is exactly: 280px title col + totalCanvasPx --}}
            <div style="min-width: {{ 280 + $totalCanvasPx }}px;" class="flex flex-col">

                <!-- 1. MULTI-TIER HEADER -->
                <div class="bg-slate-50 border-b border-slate-200 flex flex-col flex-shrink-0">

                    <!-- Top: Month headers -->
                    <div class="flex items-stretch border-b border-slate-200/80 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <div class="flex-shrink-0 border-r border-slate-200 bg-slate-100/90 text-slate-700 flex items-center px-3.5 py-2.5" style="width:280px;">
                            WORK BREAKDOWN
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($monthHeaders as $mHead)
                                <div class="text-center border-r border-slate-200 font-black text-slate-700 bg-slate-100/60 py-2.5 px-1 truncate"
                                     style="width:{{ $mHead['pxWidth'] }}px; flex-shrink:0;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom: Sub-column (day/week/month) headers -->
                    <div class="flex items-stretch text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <div class="border-r border-slate-200 flex items-center px-3.5 py-2 bg-slate-50" style="width:280px; flex-shrink:0;">
                            Phase &amp; Task Name
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($columns as $col)
                                <div class="text-center border-r border-slate-200 py-1.5 px-1 flex-shrink-0
                                        {{ ($col['isToday'] ?? false) || ($col['isCurrent'] ?? false) ? 'bg-[#fdf4f4] text-[#c3122e] font-black' : (($col['isWeekend'] ?? false) ? 'bg-slate-100/60' : '') }}"
                                     style="width:{{ $col['px'] }}px;">
                                    <div class="text-[11px] leading-none font-bold">{{ $col['label'] }}</div>
                                    <div class="text-[9px] font-mono text-slate-400 mt-0.5">{{ $col['sublabel'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. GANTT ROWS & TIMELINE CANVAS -->
                <div class="relative divide-y divide-slate-100/80">

                    <!-- Continuous Single Full-Height Today Laser Line across all rows -->
                    @if(!is_null($todayPx))
                        <div class="absolute top-0 bottom-0 z-30 pointer-events-none" style="left:{{ 280 + $todayPx }}px; width:2px; background:linear-gradient(to bottom, #c3122e, #f43f5e);">
                            <div class="w-3 h-3 rounded-full -translate-x-[5px] -mt-1 shadow-md border-2 border-white" style="background:#c3122e;"></div>
                        </div>
                    @endif

                    @forelse($wbsItems as $index => $item)
                        @php
                            /* ── resolve dates ── */
                            $itemStart = $item->start_date
                                ? $item->start_date->copy()
                                : ($project->start_date ? $project->start_date->copy() : $timelineStart->copy());
                            $itemEnd = $item->end_date
                                ? $item->end_date->copy()
                                : $itemStart->copy()->addDays(5);

                            /* ── pixel positions using shared dayToPx map ── */
                            $startDayOff = max(0, (int) $timelineStart->diffInDays($itemStart, false));
                            $endDayOff   = max($startDayOff + 1, (int) $timelineStart->diffInDays($itemEnd, false) + 1);

                            $startDayOff = min($startDayOff, count($dayToPx) - 1);
                            $endDayOff   = min($endDayOff,   count($dayToPx) - 1);

                            $barLeft  = round($dayToPx[$startDayOff], 2);
                            $barRight = round($dayToPx[$endDayOff],   2);
                            $barWidth = max(24, $barRight - $barLeft);   // min 24px

                            /* ── indent level ── */
                            $levelIndent = match($item->item_type->value) {
                                'work_package' => 1,
                                'task'         => 2,
                                'subtask'      => 3,
                                default        => 0,
                            };

                            /* ── 100% Reliable Inline CSS Gradient Themes for Phases & Items ── */
                            $phaseStyles = [
                                ['bg' => 'linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #6366f1 100%)', 'border' => '#60a5fa', 'shadow' => 'rgba(59, 130, 246, 0.4)'],
                                ['bg' => 'linear-gradient(135deg, #9f1239 0%, #c3122e 50%, #f43f5e 100%)', 'border' => '#fb7185', 'shadow' => 'rgba(195, 18, 46, 0.4)'],
                                ['bg' => 'linear-gradient(135deg, #065f46 0%, #0d9488 50%, #06b6d4 100%)', 'border' => '#2dd4bf', 'shadow' => 'rgba(13, 148, 136, 0.4)'],
                                ['bg' => 'linear-gradient(135deg, #b45309 0%, #ea580c 50%, #f97316 100%)', 'border' => '#fb923c', 'shadow' => 'rgba(234, 88, 12, 0.4)'],
                                ['bg' => 'linear-gradient(135deg, #581c87 0%, #7c3aed 50%, #d946ef 100%)', 'border' => '#c084fc', 'shadow' => 'rgba(124, 58, 237, 0.4)'],
                            ];
                            $pStyle = $phaseStyles[$index % count($phaseStyles)];

                            $taskStyle = match($item->status->value) {
                                'completed' => ['bg' => 'linear-gradient(135deg, #047857 0%, #10b981 100%)', 'border' => '#34d399', 'shadow' => 'rgba(16, 185, 129, 0.35)'],
                                'in_progress' => ['bg' => 'linear-gradient(135deg, #c3122e 0%, #fb7185 100%)', 'border' => '#fda4af', 'shadow' => 'rgba(195, 18, 46, 0.35)'],
                                default => ['bg' => 'linear-gradient(135deg, #3730a3 0%, #4f46e5 50%, #38bdf8 100%)', 'border' => '#818cf8', 'shadow' => 'rgba(79, 70, 229, 0.35)'],
                            };

                            /* ── Risks count and highest severity score ── */
                            $risksCount = $item->risks ? $item->risks->count() : 0;
                            $highestRiskScore = $risksCount > 0 ? (int) $item->risks->max('risk_score') : 0;

                            /* ── tooltip data ── */
                            $typeLabel    = $item->is_milestone ? 'Milestone' : ucfirst($item->item_type->value);
                            $assigneeName = $item->assignedUser ? $item->assignedUser->name : 'Unassigned';
                            $assigneeInit = $item->assignedUser ? strtoupper(substr($item->assignedUser->name, 0, 1)) : '?';
                            $tooltipData  = json_encode([
                                'wbs'           => $item->wbs_code,
                                'title'         => $item->title,
                                'type'          => $typeLabel,
                                'assignee'      => $assigneeName,
                                'initial'       => $assigneeInit,
                                'start'         => $item->start_date ? $item->start_date->format('M d, Y') : '—',
                                'end'           => $item->end_date   ? $item->end_date->format('M d, Y')   : '—',
                                'progress'      => $item->progress,
                                'status'        => $item->status->label(),
                                'has_risk'      => $risksCount > 0,
                                'risks_count'   => $risksCount,
                                'highest_score' => $highestRiskScore,
                                'risks_list'    => $risksCount > 0 ? $item->risks->map(fn($r) => [
                                    'title' => $r->title,
                                    'score' => $r->risk_score,
                                    'severity' => $r->risk_score >= 6 ? 'High' : ($r->risk_score >= 3 ? 'Medium' : 'Low')
                                ])->values()->toArray() : [],
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                        @endphp

                        <div class="flex items-center hover:bg-slate-50/90 transition-colors py-2.5 text-xs cursor-default">

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

                                    @if($item->item_type->value === 'phase')
                                        <span class="text-amber-500 text-sm flex-shrink-0">📁</span>
                                    @endif

                                    <span class="font-mono text-[11px] font-black text-[#c3122e] flex-shrink-0">{{ $item->wbs_code }}</span>

                                    <span class="truncate text-slate-800 {{ $item->item_type->value === 'phase' ? 'font-black text-slate-900 text-xs' : 'font-semibold' }}">
                                        {{ $item->title }}
                                    </span>

                                    {{-- ⚠️ RISK INDICATOR ICON ON TASK ROW --}}
                                    @if($risksCount > 0)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9.5px] font-black {{ $highestRiskScore >= 6 ? 'bg-rose-100 text-rose-700 border border-rose-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }} shadow-2xs flex-shrink-0"
                                              title="{{ $risksCount }} Active Risk(s) Associated with this Task">
                                            <span>⚠️</span>
                                            <span class="font-mono">{{ $risksCount }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Timeline canvas (exact totalCanvasPx wide) -->
                            <div class="relative flex-shrink-0 h-9 flex items-center" style="width:{{ $totalCanvasPx }}px;">

                                <!-- Grid column lines (subtle background) -->
                                @php $lineLeft = 0; @endphp
                                @foreach($columns as $col)
                                    <div class="absolute top-0 bottom-0 border-r border-slate-100/60 {{ ($col['isWeekend'] ?? false) ? 'bg-slate-50/50' : '' }}"
                                         style="left:{{ $lineLeft }}px; width:{{ $col['px'] }}px;"></div>
                                    @php $lineLeft += $col['px']; @endphp
                                @endforeach

                                @if($item->is_milestone)
                                    <!-- Milestone 3D Diamond -->
                                    <div class="absolute top-1/2 -translate-y-1/2 flex items-center gap-2 z-20"
                                         style="left:{{ $barLeft }}px;">
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

                                @elseif($item->item_type->value === 'phase')
                                    <!-- Phase bar: 100% Vibrant Solid Gradient Pill with Glossy Highlight -->
                                    <div class="absolute h-7 rounded-full flex items-center overflow-hidden z-20 hover:scale-[1.01] hover:brightness-110 transition-all cursor-pointer shadow-md {{ $risksCount > 0 ? 'ring-2 ring-amber-400' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px; background:{{ $pStyle['bg'] }}; border:1px solid {{ $pStyle['border'] }}; box-shadow: 0 4px 14px {{ $pStyle['shadow'] }};">
                                        <!-- Top gloss highlight -->
                                        <div class="absolute inset-x-0 top-0 h-1/2 bg-white/25 rounded-t-full pointer-events-none"></div>
                                        @if($item->progress > 0)
                                            <div class="h-full bg-black/20 transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-3.5 text-[11px] font-black text-white truncate drop-shadow-sm tracking-wide">
                                            <span class="truncate">📁 {{ $item->title }} ({{ $item->progress }}%)</span>
                                            @if($risksCount > 0)
                                                <span class="flex-shrink-0 ml-1.5 px-1.5 py-0.2 rounded bg-amber-400/90 text-slate-900 text-[9px] font-black shadow-xs flex items-center gap-0.5">
                                                    <span>⚠️</span>
                                                    <span>{{ $risksCount }}</span>
                                                </span>
                                            @endif
                                        </span>
                                    </div>

                                @else
                                    <!-- Task / Subtask bar: 100% Vibrant Solid Gradient Pill -->
                                    <div class="absolute h-6 rounded-full flex items-center overflow-hidden z-20 hover:scale-[1.01] hover:brightness-105 transition-all cursor-pointer shadow-sm {{ $risksCount > 0 ? 'ring-2 ring-amber-400 ring-offset-1' : '' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px; background:{{ $taskStyle['bg'] }}; border:1px solid {{ $taskStyle['border'] }}; box-shadow: 0 2px 10px {{ $taskStyle['shadow'] }};">
                                        <!-- Top gloss -->
                                        <div class="absolute inset-x-0 top-0 h-1/2 bg-white/25 rounded-t-full pointer-events-none"></div>
                                        @if($item->progress > 0)
                                            <div class="h-full bg-black/20 transition-all duration-300" style="width:{{ $item->progress }}%"></div>
                                        @endif
                                        <span class="absolute inset-0 flex items-center justify-between px-3 text-[10px] font-black truncate text-white drop-shadow-xs">
                                            <span class="truncate">
                                                @if($item->status->value === 'completed') ✓ {{ $item->title }} (100%)
                                                @else {{ $item->title }} ({{ $item->progress }}%)
                                                @endif
                                            </span>
                                            {{-- ⚠️ Risk Badge on Bar --}}
                                            @if($risksCount > 0)
                                                <span class="flex-shrink-0 ml-1.5 px-1 py-0.2 rounded bg-amber-400 text-slate-950 text-[9px] font-black shadow-xs flex items-center gap-0.5" title="{{ $risksCount }} Associated Risk(s)">
                                                    <span>⚠️</span>
                                                    <span class="font-mono">{{ $risksCount }}</span>
                                                </span>
                                            @endif
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

    <!-- ===== FLOATING TOOLTIP (white, brand-coloured with Risk Section) ===== -->
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
            <div class="bg-white rounded-r-2xl rounded-l-md shadow-2xl border-y border-r border-slate-200/90 border-l-4 border-l-[#c3122e] overflow-hidden ring-1 ring-black/5">

                <!-- Header -->
                <div class="px-4 pt-3.5 pb-2.5 bg-gradient-to-r from-[#fdf4f4]/50 to-white border-b border-[#faeaea]">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#c3122e]" x-text="'WBS ' + tooltip.data.wbs"></span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 inline-block"></span>
                        <span class="px-1.5 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider border"
                              :class="{
                                  'bg-slate-50 text-slate-650 border-slate-200': tooltip.data.type === 'Phase',
                                  'bg-[#fdf4f4] text-[#c3122e] border-[#f5c2c9]': tooltip.data.type === 'Task' || tooltip.data.type === 'Subtask',
                                  'bg-amber-50 text-amber-800 border-amber-200': tooltip.data.type === 'Milestone',
                                  'bg-slate-50 text-slate-650 border-slate-200': !['Phase','Task','Subtask','Milestone'].includes(tooltip.data.type)
                              }"
                              x-text="tooltip.data.type"></span>
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
