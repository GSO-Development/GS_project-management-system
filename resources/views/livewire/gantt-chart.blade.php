<div x-data="{
    tooltip: { visible: false, x: 0, y: 0, data: null },
    showTooltip(el, data) {
        this.tooltip.data = data;
        const rect = el.getBoundingClientRect();
        const tw = 296, th = 175;
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

    <!-- ===== GANTT TOOLBAR & CONTROLS ===== -->
    <div class="card mb-6 p-4 bg-white border border-slate-200 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#f0dada] flex items-center justify-center text-[#c3122e] flex-shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Interactive Gantt Schedule</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#f0dada]">
                            {{ $timelineStart->format('M d') }} &rarr; {{ $timelineEnd->format('M d, Y') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Hover any row to see full task details</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="relative w-44">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks..."
                           class="w-full text-xs rounded-xl border border-slate-200 pl-8 pr-3 py-1.5 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e] outline-none">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <select wire:model.live="statusFilter" class="text-xs rounded-xl border border-slate-200 px-3 py-1.5 bg-white text-slate-700 font-medium focus:border-[#c3122e] outline-none">
                    <option value="all">All Statuses</option>
                    <option value="not_started">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="under_review">Under Review</option>
                    <option value="completed">Completed</option>
                    <option value="blocked">Blocked</option>
                </select>
                <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 gap-1">
                    <button wire:click="setTimeframe('day')"   class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'day'   ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Day</button>
                    <button wire:click="setTimeframe('week')"  class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'week'  ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Week</button>
                    <button wire:click="setTimeframe('month')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'month' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Month</button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-slate-100 text-[11px] font-medium text-slate-600">
            <span class="font-bold text-slate-700">Legend:</span>
            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-[#1a0a0d] inline-block border border-slate-700"></span> Phase</div>
            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-[#c3122e] inline-block"></span> In Progress</div>
            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-600 inline-block"></span> Completed</div>
            <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-[#c3122e] rotate-45 inline-block border border-white"></span> Milestone</div>
            <div class="flex items-center gap-1.5 ml-auto"><span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> Today Line</div>
        </div>
    </div>

    <!-- ===== GANTT CHART CONTAINER ===== -->
    <div class="card p-0 overflow-hidden shadow-xs border border-slate-200/80 bg-white">
        <div class="overflow-x-auto scrollbar-thin">
            {{-- Outer wrapper is exactly: 280px title col + totalCanvasPx --}}
            <div style="min-width: {{ 280 + $totalCanvasPx }}px;" class="flex flex-col">

                <!-- 1. MULTI-TIER HEADER -->
                <div class="bg-slate-50 border-b border-slate-200 flex flex-col flex-shrink-0">

                    <!-- Top: Month headers -->
                    <div class="flex items-stretch border-b border-slate-200/80 text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                        <div class="flex-shrink-0 border-r border-slate-200 bg-slate-100/80 text-slate-600 flex items-center px-2.5 py-2" style="width:280px;">
                            WBS Task Breakdown
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($monthHeaders as $mHead)
                                <div class="text-center border-r border-slate-200 font-bold text-slate-700 bg-slate-100/50 py-2 px-1 truncate"
                                     style="width:{{ $mHead['pxWidth'] }}px; flex-shrink:0;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom: Sub-column (day/week/month) headers -->
                    <div class="flex items-stretch text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <div class="border-r border-slate-200 flex items-center px-2.5 py-2" style="width:280px; flex-shrink:0;">
                            Title &amp; Code
                        </div>
                        <div class="flex" style="width:{{ $totalCanvasPx }}px; flex-shrink:0;">
                            @foreach($columns as $col)
                                <div class="text-center border-r border-slate-200 py-1.5 px-1 flex-shrink-0
                                        {{ ($col['isToday'] ?? false) || ($col['isCurrent'] ?? false) ? 'bg-[#fdf4f4] text-[#c3122e] font-extrabold' : (($col['isWeekend'] ?? false) ? 'bg-slate-100/60' : '') }}"
                                     style="width:{{ $col['px'] }}px;">
                                    <div class="text-[11px] leading-none">{{ $col['label'] }}</div>
                                    <div class="text-[9px] font-mono text-slate-400 mt-0.5">{{ $col['sublabel'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. GANTT ROWS -->
                <div class="relative divide-y divide-slate-100">

                    @forelse($wbsItems as $item)
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
                            $barWidth = max(6, $barRight - $barLeft);   // min 6px so bar is always visible

                            /* ── indent level ── */
                            $levelIndent = match($item->item_type->value) {
                                'work_package' => 1,
                                'task'         => 2,
                                'subtask'      => 3,
                                default        => 0,
                            };

                            /* ── tooltip data ── */
                            $typeLabel    = $item->is_milestone ? 'Milestone' : ucfirst($item->item_type->value);
                            $assigneeName = $item->assignedUser ? $item->assignedUser->name : 'Unassigned';
                            $assigneeInit = $item->assignedUser ? strtoupper(substr($item->assignedUser->name, 0, 1)) : '?';
                            $tooltipData  = json_encode([
                                'wbs'      => $item->wbs_code,
                                'title'    => $item->title,
                                'type'     => $typeLabel,
                                'assignee' => $assigneeName,
                                'initial'  => $assigneeInit,
                                'start'    => $item->start_date ? $item->start_date->format('M d, Y') : '—',
                                'end'      => $item->end_date   ? $item->end_date->format('M d, Y')   : '—',
                                'progress' => $item->progress,
                                'status'   => $item->status->label(),
                            ], JSON_HEX_APOS | JSON_HEX_QUOT);
                        @endphp

                        <div class="flex items-center hover:bg-[#fdf4f4]/30 transition-colors py-1.5 text-xs cursor-default">

                            <!-- Left: WBS Title Column (fixed 280px) -->
                            <div class="border-r border-slate-100 flex-shrink-0 flex items-center overflow-hidden px-3 hover:bg-slate-50 transition-colors cursor-pointer"
                                 style="width:280px;"
                                 @mouseenter="showTooltip($el, {{ $tooltipData }})"
                                 @mouseleave="hideTooltip()">
                                <div style="padding-left: {{ $levelIndent * 0.75 }}rem;" class="flex items-center gap-1.5 min-w-0 w-full">
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
                                        <span class="w-4 flex-shrink-0 inline-block"></span>
                                    @endif
                                    <span class="font-mono text-[11px] font-bold text-[#c3122e] flex-shrink-0">{{ $item->wbs_code }}</span>
                                    <span class="truncate text-slate-800 {{ $item->item_type->value === 'phase' ? 'font-extrabold text-slate-900 text-xs uppercase tracking-tight' : 'font-semibold' }}">
                                        {{ $item->title }}
                                    </span>
                                </div>
                            </div>

                            <!-- Right: Timeline canvas (exact totalCanvasPx wide) -->
                            <div class="relative flex-shrink-0" style="width:{{ $totalCanvasPx }}px; height:28px;">

                                <!-- Grid column lines (background) -->
                                @php $lineLeft = 0; @endphp
                                @foreach($columns as $col)
                                    <div class="absolute top-0 bottom-0 border-r border-slate-100 {{ ($col['isWeekend'] ?? false) ? 'bg-slate-50/50' : '' }}"
                                         style="left:{{ $lineLeft }}px; width:{{ $col['px'] }}px;"></div>
                                    @php $lineLeft += $col['px']; @endphp
                                @endforeach

                                <!-- Today line -->
                                @if(!is_null($todayPx))
                                    <div class="absolute top-0 bottom-0 z-20 pointer-events-none" style="left:{{ $todayPx }}px; width:2px; background:#f43f5e;">
                                        <div class="w-2.5 h-2.5 rounded-full -translate-x-[4px] -mt-0.5 shadow-xs border border-white" style="background:#e11d48;"></div>
                                    </div>
                                @endif

                                @if($item->is_milestone)
                                    <!-- Milestone diamond -->
                                    <div class="absolute top-1/2 -translate-y-1/2 flex items-center gap-1.5 z-10"
                                         style="left:{{ $barLeft }}px;">
                                        <div class="w-4 h-4 bg-[#c3122e] rotate-45 border-2 border-white shadow-md flex-shrink-0"></div>
                                        <span class="text-[10px] font-bold text-[#a00e24] whitespace-nowrap bg-white/95 px-2 py-0.5 rounded-md shadow-xs border border-[#f0dada]">{{ $item->title }}</span>
                                    </div>

                                @elseif($item->item_type->value === 'phase')
                                    <!-- Phase bar -->
                                    <div class="absolute top-1 bottom-1 rounded-lg bg-[#1a0a0d] border border-slate-700 shadow-xs flex items-center overflow-hidden z-10"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px;">
                                        <div class="h-full bg-gradient-to-r from-[#c3122e] to-[#b8860b] rounded-lg" style="width:{{ $item->progress }}%"></div>
                                        <span class="absolute inset-0 flex items-center px-2.5 text-[10px] font-extrabold text-white truncate">
                                            {{ $item->wbs_code }} {{ $item->title }} ({{ $item->progress }}%)
                                        </span>
                                    </div>

                                @else
                                    <!-- Task / Subtask bar -->
                                    <div class="absolute top-1 bottom-1 rounded-lg shadow-xs flex items-center overflow-hidden border z-10
                                            {{ $item->status->value === 'completed' ? 'bg-emerald-50 border-emerald-300' : 'bg-[#fdf4f4] border-[#f0dada]' }}"
                                         style="left:{{ $barLeft }}px; width:{{ $barWidth }}px;">
                                        <div class="h-full rounded-md {{ $item->status->value === 'completed' ? 'bg-emerald-600' : 'bg-[#c3122e]' }}" style="width:{{ $item->progress }}%"></div>
                                        <span class="absolute inset-0 flex items-center px-2 text-[10px] font-bold truncate {{ $item->progress > 35 ? 'text-white' : 'text-slate-800' }}">
                                            @if($item->status->value === 'completed') ✓ {{ $item->title }} (100%)
                                            @else {{ $item->progress }}%
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs">No WBS items matching your filter.</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>

    <!-- ===== FLOATING TOOLTIP (white, brand-coloured) ===== -->
    <div
        x-show="tooltip.visible && tooltip.data"
        x-cloak
        :style="`position:fixed; left:${tooltip.x}px; top:${tooltip.y}px; z-index:9999; pointer-events:none; width:288px;`"
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
                </div>
            </div>
        </template>
    </div>

</div>
