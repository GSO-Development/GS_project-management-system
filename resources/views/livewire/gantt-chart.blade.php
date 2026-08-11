<div>
    <!-- ===== GANTT TOOLBAR & CONTROLS ===== -->
    <div class="card mb-6 p-4 bg-white border border-slate-200 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Title & Date Range Badge -->
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
                    <p class="text-xs text-slate-500 mt-0.5">Real-time schedule timeline & dependency tracking</p>
                </div>
            </div>

            <!-- Search, Status Filter & View Mode Controls -->
            <div class="flex flex-wrap items-center gap-3">
                <!-- Search Input -->
                <div class="relative w-44">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search tasks..."
                        class="w-full text-xs rounded-xl border border-slate-200 pl-8 pr-3 py-1.5 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e] outline-none"
                    >
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="text-xs rounded-xl border border-slate-200 px-3 py-1.5 bg-white text-slate-700 font-medium focus:border-[#c3122e] outline-none">
                    <option value="all">All Statuses</option>
                    <option value="not_started">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="under_review">Under Review</option>
                    <option value="completed">Completed</option>
                    <option value="blocked">Blocked</option>
                </select>

                <!-- Timeframe Mode Selector -->
                <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200 gap-1">
                    <button wire:click="setTimeframe('day')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'day' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Day</button>
                    <button wire:click="setTimeframe('week')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'week' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Week</button>
                    <button wire:click="setTimeframe('month')" class="px-3 py-1 rounded-lg text-xs font-bold transition-all {{ $timeframe === 'month' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Month</button>
                </div>
            </div>
        </div>

        <!-- Legend Bar -->
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
        <!-- Scrollable Outer Container -->
        <div class="overflow-x-auto scrollbar-thin">
            <div class="inline-flex min-w-full flex-col">

                <!-- 1. MULTI-TIER HEADER -->
                <div class="bg-slate-50 border-b border-slate-200 flex flex-col flex-shrink-0">
                    <!-- Top Tier: Months Header -->
                    <div class="flex items-center border-b border-slate-200/80 text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                        <!-- Left Table Header (540px) -->
                        <div class="w-[540px] p-2.5 border-r border-slate-200 bg-slate-100/80 text-slate-600 flex-shrink-0 whitespace-nowrap">
                            WBS Task Breakdown
                        </div>
                        <!-- Right Timeline Month Headers -->
                        <div class="flex-1 flex items-center min-w-max">
                            @foreach($monthHeaders as $mHead)
                                <div class="p-2 text-center border-r border-slate-200 font-bold text-slate-700 bg-slate-100/50" style="width: {{ $mHead['widthPct'] }}%; min-width: 100px;">
                                    {{ $mHead['label'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom Tier: Sub-columns Header -->
                    <div class="flex items-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <!-- Left Column Headers (540px total: 240 + 110 + 110 + 80) -->
                        <div class="w-[240px] p-2.5 border-r border-slate-200 flex-shrink-0 whitespace-nowrap">Title & Code</div>
                        <div class="w-[110px] p-2.5 border-r border-slate-200 flex-shrink-0 text-center whitespace-nowrap">Assignee</div>
                        <div class="w-[110px] p-2.5 border-r border-slate-200 flex-shrink-0 text-center whitespace-nowrap">Dates</div>
                        <div class="w-[80px] p-2.5 border-r border-slate-200 flex-shrink-0 text-center whitespace-nowrap">Progress</div>

                        <!-- Right Timeline Column Headers -->
                        <div class="flex-1 flex items-center min-w-max">
                            @foreach($columns as $col)
                                <div class="p-1.5 text-center border-r border-slate-200 flex-shrink-0 {{ ($col['isToday'] ?? false) || ($col['isCurrent'] ?? false) ? 'bg-[#fdf4f4] text-[#c3122e] font-extrabold' : (($col['isWeekend'] ?? false) ? 'bg-slate-100/60' : '') }}" style="width: {{ 100 / count($columns) }}%; min-width: {{ $timeframe === 'day' ? '45px' : ($timeframe === 'month' ? '110px' : '85px') }};">
                                    <div class="text-[11px] leading-none">{{ $col['label'] }}</div>
                                    <div class="text-[9px] font-mono text-slate-400 mt-0.5">{{ $col['sublabel'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. GANTT ROWS & GRID CANVAS -->
                <div class="relative divide-y divide-slate-100">

                    @forelse($wbsItems as $item)
                        @php
                            $itemStart = $item->start_date ? $item->start_date->copy() : ($project->start_date ? $project->start_date->copy() : $timelineStart->copy());
                            $itemEnd = $item->end_date ? $item->end_date->copy() : $itemStart->copy()->addDays(5);

                            $startOffset = max(0, $timelineStart->diffInDays($itemStart, false));
                            $durationDays = max(1, $itemStart->diffInDays($itemEnd, false));

                            $leftPct = max(0, min(95, ($startOffset / $totalTimelineDays) * 100));
                            $widthPct = max(3, min(100 - $leftPct, ($durationDays / $totalTimelineDays) * 100));

                            $levelIndent = 0;
                            if ($item->item_type->value === 'work_package') $levelIndent = 1;
                            elseif ($item->item_type->value === 'task') $levelIndent = 2;
                            elseif ($item->item_type->value === 'subtask') $levelIndent = 3;
                        @endphp

                        <div class="flex items-center hover:bg-[#fdf4f4]/30 transition-colors py-2 text-xs">
                            <!-- 1. Task Title & Code (240px) -->
                            <div class="w-[240px] px-3 border-r border-slate-100 flex-shrink-0 flex items-center gap-2 truncate">
                                <div style="padding-left: {{ $levelIndent * 0.75 }}rem;" class="flex items-center gap-1.5 truncate">
                                    <span class="font-mono text-[11px] font-bold text-[#c3122e] flex-shrink-0">{{ $item->wbs_code }}</span>
                                    <span class="truncate text-slate-800 {{ $item->item_type->value === 'phase' ? 'font-extrabold text-slate-900 text-xs uppercase tracking-tight' : 'font-semibold' }}" title="{{ $item->title }}">
                                        {{ $item->title }}
                                    </span>
                                </div>
                            </div>

                            <!-- 2. Assignee (110px) -->
                            <div class="w-[110px] px-2 border-r border-slate-100 flex-shrink-0 flex items-center justify-center gap-1.5">
                                @if($item->assignedUser)
                                    <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white text-[9px] font-bold flex items-center justify-center flex-shrink-0 shadow-xs" title="{{ $item->assignedUser->name }}">
                                        {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                                    </div>
                                    <span class="truncate text-[11px] text-slate-700 font-semibold max-w-[65px]">{{ $item->assignedUser->name }}</span>
                                @else
                                    <span class="text-slate-400 text-[10px]">—</span>
                                @endif
                            </div>

                            <!-- 3. Dates (110px) -->
                            <div class="w-[110px] px-1 text-center border-r border-slate-100 flex-shrink-0 font-mono text-[10px] text-slate-600 whitespace-nowrap">
                                @if($item->start_date || $item->end_date)
                                    <span>{{ $item->start_date ? $item->start_date->format('M d') : '-' }} &rarr; {{ $item->end_date ? $item->end_date->format('M d') : '-' }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </div>

                            <!-- 4. Progress % (80px) -->
                            <div class="w-[80px] px-1 text-center border-r border-slate-100 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold {{ $item->progress == 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $item->progress }}%
                                </span>
                            </div>

                            <!-- 5. Gantt Timeline Bar Area -->
                            <div class="flex-1 relative h-7 px-2 flex items-center min-w-max">
                                <!-- Background column vertical grid lines -->
                                <div class="absolute inset-0 flex pointer-events-none">
                                    @foreach($columns as $col)
                                        <div class="border-r border-slate-100 h-full flex-shrink-0 {{ ($col['isWeekend'] ?? false) ? 'bg-slate-50/50' : '' }}" style="width: {{ 100 / count($columns) }}%; min-width: {{ $timeframe === 'day' ? '45px' : ($timeframe === 'month' ? '110px' : '85px') }};"></div>
                                    @endforeach
                                </div>

                                <!-- Vertical Today Line Marker -->
                                @if(!is_null($todayPct))
                                    <div
                                        class="absolute top-0 bottom-0 z-20 w-0.5 bg-rose-500 pointer-events-none"
                                        style="left: {{ $todayPct }}%;"
                                        title="Today ({{ now()->format('M d, Y') }})"
                                    >
                                        <div class="w-2.5 h-2.5 rounded-full bg-rose-600 -translate-x-[4px] -mt-0.5 shadow-xs border border-white"></div>
                                    </div>
                                @endif

                                @if($item->is_milestone)
                                    <!-- Milestone Diamond Marker -->
                                    <div
                                        class="absolute top-1/2 -translate-y-1/2 flex items-center gap-1.5 z-10"
                                        style="left: {{ $leftPct }}%;"
                                        title="Milestone: {{ $item->title }}"
                                    >
                                        <div class="w-4 h-4 bg-[#c3122e] rotate-45 border-2 border-white shadow-md flex-shrink-0"></div>
                                        <span class="text-[10px] font-bold text-[#a00e24] whitespace-nowrap bg-white/95 px-2 py-0.5 rounded-md shadow-xs border border-[#f0dada]">{{ $item->title }}</span>
                                    </div>
                                @elseif($item->item_type->value === 'phase')
                                    <!-- Phase Summary Bar -->
                                    <div
                                        class="absolute top-1 bottom-1 rounded-lg bg-[#1a0a0d] border border-slate-700 shadow-xs flex items-center overflow-hidden z-10"
                                        style="left: {{ $leftPct }}%; width: {{ $widthPct }}%;"
                                        title="Phase: {{ $item->wbs_code }} {{ $item->title }} ({{ $item->progress }}%)"
                                    >
                                        <div class="h-full bg-gradient-to-r from-[#c3122e] to-[#b8860b] rounded-lg transition-all" style="width: {{ $item->progress }}%"></div>
                                        <span class="absolute inset-0 flex items-center px-2.5 text-[10px] font-extrabold text-white truncate shadow-xs">
                                            {{ $item->wbs_code }} {{ $item->title }} ({{ $item->progress }}%)
                                        </span>
                                    </div>
                                @else
                                    <!-- Standard Task Bar -->
                                    <div
                                        class="absolute top-1 bottom-1 rounded-lg shadow-xs flex items-center overflow-hidden border transition-all group hover:shadow-md z-10
                                            {{ $item->status->value === 'completed' ? 'bg-emerald-50 border-emerald-300' : 'bg-[#fdf4f4] border-[#f0dada]' }}"
                                        style="left: {{ $leftPct }}%; width: {{ $widthPct }}%;"
                                        title="{{ $item->wbs_code }} {{ $item->title }} • {{ $item->status->label() }} • {{ $item->progress }}%"
                                    >
                                        <!-- Fill -->
                                        <div
                                            class="h-full rounded-md transition-all {{ $item->status->value === 'completed' ? 'bg-emerald-600' : 'bg-[#c3122e]' }}"
                                            style="width: {{ $item->progress }}%"
                                        ></div>

                                        <span class="absolute inset-0 flex items-center px-2 text-[10px] font-bold truncate {{ $item->progress > 35 ? 'text-white' : 'text-slate-800' }}">
                                            @if($item->status->value === 'completed')
                                                ✓ {{ $item->title }} (100%)
                                            @else
                                                {{ $item->progress }}%
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-slate-400 text-xs">No WBS items matching your filter for Gantt visualization.</div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
