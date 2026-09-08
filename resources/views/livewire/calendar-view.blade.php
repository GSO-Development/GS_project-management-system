<div class="space-y-4 sm:space-y-5 pb-16">
    <style>
        .cal-glass-card { background: #ffffff; border: 1.5px solid #e8edf2; border-radius: 14px; box-shadow: 0 1px 3px rgba(15,23,42,.04); }
        .cal-btn-outline { background: #ffffff; border: 1.5px solid #e2e8f0; color: #475569; font-weight: 700; transition: all .15s; }
        .cal-btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
        .cal-event-chip { transition: transform .12s, box-shadow .12s; }
        .cal-event-chip:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(0,0,0,.08); }
        .scrollbar-slim { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        .scrollbar-slim::-webkit-scrollbar { width: 4px; height: 4px; }
        .scrollbar-slim::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & CORPORATE ACTION BAR (Clean, Single-Row Layout)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 sm:gap-4">
        <!-- Left: Title & Month Indicator -->
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-900 text-white flex items-center justify-center shadow-xs shrink-0">
                <svg class="w-4.5 h-4.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight" style="font-family: 'Inter', system-ui, sans-serif;">
                        Project Calendar
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ $currentDate->format('F Y') }}</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium">Milestones, governance reviews, and team deliverables</p>
            </div>
        </div>

        <!-- Right: Navigator, View Switcher & Actions in One Single Row -->
        <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap xl:flex-nowrap">
            <!-- Period Navigator -->
            <div class="inline-flex items-center p-0.5 rounded-xl border border-slate-200 bg-white shadow-2xs">
                <button wire:click="prevPeriod" type="button" class="p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all cursor-pointer" title="Previous Period">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button wire:click="today" type="button" class="px-2.5 py-1 text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all cursor-pointer">
                    Today
                </button>
                <button wire:click="nextPeriod" type="button" class="p-1.5 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all cursor-pointer" title="Next Period">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- View Switcher -->
            <div class="inline-flex p-0.5 rounded-xl border border-slate-200 bg-slate-100/80 shadow-2xs text-xs font-bold">
                <button wire:click="setViewMode('calendar')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Month
                </button>
                <button wire:click="setViewMode('week')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'week' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Week
                </button>
                <button wire:click="setViewMode('day')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'day' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Day
                </button>
                <button wire:click="setViewMode('list')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Schedule
                </button>
            </div>

            <!-- Outlook Sync Button -->
            <button
                wire:click="openSubscribeModal"
                type="button"
                class="cal-btn-outline px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 cursor-pointer shadow-2xs shrink-0"
                title="Sync calendar with Microsoft Outlook"
            >
                <svg class="w-3.5 h-3.5 text-[#0078d4]" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                <span>Outlook Sync</span>
            </button>

            <!-- Schedule Event Button (PMO Admin or PM only) -->
            @if($canCreate)
                <button
                    wire:click="openCreateEventModal"
                    type="button"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white flex items-center gap-1.5 cursor-pointer transition-all shadow-xs shrink-0 hover:brightness-105 active:scale-95"
                    style="background: linear-gradient(135deg, #c3122e 0%, #9e0e24 100%);"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Schedule Event</span>
                </button>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. CLEAN 2-TIER FILTER & SEARCH TOOLBAR (Zero Scrollbars)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="cal-glass-card p-3 sm:p-3.5 space-y-2.5">
        <!-- Tier 1: Scope Switcher, Subsidiaries, Projects & Search -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
            <!-- Left Controls -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Scope Switcher -->
                <div class="inline-flex p-0.5 rounded-lg bg-slate-100 border border-slate-200 text-xs font-bold shrink-0">
                    <button wire:click="setScope('all')" type="button" class="px-3 py-1 rounded-md transition-all cursor-pointer {{ $scopeFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                        All Events
                    </button>
                    <button wire:click="setScope('my_events')" type="button" class="px-3 py-1 rounded-md transition-all cursor-pointer {{ $scopeFilter === 'my_events' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                        My Assigned
                    </button>
                </div>

                <!-- Subsidiary Filter Dropdown -->
                <div class="relative min-w-[150px]">
                    <select wire:model.live="subsidiaryFilter" class="w-full text-xs font-semibold rounded-lg pl-3 pr-7 py-1.5 border border-slate-200 bg-slate-50 hover:bg-white focus:bg-white text-slate-700 focus:outline-none focus:border-[#c3122e] cursor-pointer appearance-none transition-colors">
                        <option value="all">All Subsidiaries</option>
                        @foreach($subsidiaries as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Project Filter Dropdown -->
                <div class="relative min-w-[170px] max-w-[240px]">
                    <select wire:model.live="projectFilter" class="w-full text-xs font-semibold rounded-lg pl-3 pr-7 py-1.5 border border-slate-200 bg-slate-50 hover:bg-white focus:bg-white text-slate-700 focus:outline-none focus:border-[#c3122e] cursor-pointer appearance-none transition-colors truncate">
                        <option value="all">All Projects</option>
                        @foreach($projectsList as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <!-- Right: Search Input -->
            <div class="w-full lg:w-64 relative shrink-0">
                <div class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search events, projects..."
                    class="w-full text-xs font-medium pl-8 pr-7 py-1.5 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:border-[#c3122e] transition-all"
                >
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Tier 2: Category Badges & Outlook Connection Status -->
        <div class="flex items-center justify-between border-t border-slate-100 pt-2.5 flex-wrap gap-2">
            <!-- Category Pills -->
            <div class="flex items-center gap-1.5 flex-wrap">
                <button wire:click="$set('categoryFilter', 'all')" type="button" class="px-2.5 py-1 rounded-lg text-[11.5px] font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $categoryFilter === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <span>All</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $categoryFilter === 'all' ? 'bg-slate-700 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $totalEventsCount }}</span>
                </button>

                <button wire:click="$set('categoryFilter', 'meeting')" type="button" class="px-2.5 py-1 rounded-lg text-[11.5px] font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $categoryFilter === 'meeting' ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $categoryFilter === 'meeting' ? 'bg-white' : 'bg-blue-500' }}"></span>
                    <span>Meetings</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $categoryFilter === 'meeting' ? 'bg-blue-700 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $meetingsCount }}</span>
                </button>

                <button wire:click="$set('categoryFilter', 'milestone')" type="button" class="px-2.5 py-1 rounded-lg text-[11.5px] font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $categoryFilter === 'milestone' ? 'bg-purple-600 text-white shadow-xs' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $categoryFilter === 'milestone' ? 'bg-white' : 'bg-purple-500' }}"></span>
                    <span>Milestones</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $categoryFilter === 'milestone' ? 'bg-purple-700 text-white' : 'bg-purple-100 text-purple-800' }}">{{ $milestonesCount }}</span>
                </button>

                <button wire:click="$set('categoryFilter', 'task')" type="button" class="px-2.5 py-1 rounded-lg text-[11.5px] font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $categoryFilter === 'task' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $categoryFilter === 'task' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                    <span>Tasks</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $categoryFilter === 'task' ? 'bg-emerald-700 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $tasksCount }}</span>
                </button>
            </div>

            <!-- Microsoft 365 Outlook Status -->
            <div class="hidden sm:flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 bg-slate-50 px-2.5 py-0.5 rounded-md border border-slate-200/60">
                <svg class="w-3.5 h-3.5 text-[#0078d4]" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.17 2.06a1.5 1.5 0 0 0-1.28.25l-9.4 6.84a1.5 1.5 0 0 0-.61 1.2v7.3a1.5 1.5 0 0 0 .61 1.2l9.4 6.84c.39.28.9.36 1.36.21.46-.15.82-.52.96-.98A1.5 1.5 0 0 0 22.5 24V3.5a1.5 1.5 0 0 0-1.33-1.44zM2 5a2 2 0 0 1 2-2h8v18H4a2 2 0 0 1-2-2V5z"/>
                </svg>
                <span>Microsoft 365 Connected</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. VIEW 1: MONTH CALENDAR GRID
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'calendar')
        <div class="cal-glass-card overflow-hidden">
            <!-- Day of Week Header -->
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50/80 text-center text-xs font-black text-slate-500 py-2.5 uppercase tracking-wider">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>

            <!-- Calendar Days Grid -->
            <div class="grid grid-cols-7 divide-x divide-y divide-slate-200/80 bg-slate-100/40">
                @foreach($weeks as $week)
                    @foreach($week as $day)
                        @php
                            $evts = $day['events'];
                            $evCount = count($evts);
                        @endphp
                        <div
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="min-h-[115px] p-2 flex flex-col justify-between transition-colors cursor-pointer {{ $day['isCurrentMonth'] ? 'bg-white' : 'bg-slate-50/60' }} {{ $day['isSelected'] ? 'ring-2 ring-[#c3122e] ring-inset' : '' }} hover:bg-slate-50"
                        >
                            <!-- Day Number & Indicators -->
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $day['isToday'] ? 'bg-[#c3122e] text-white' : ($day['isCurrentMonth'] ? 'text-slate-800' : 'text-slate-400') }}">
                                    {{ $day['dayNumber'] }}
                                </span>
                                @if($evCount > 0)
                                    <span class="text-[10px] font-bold text-slate-500 px-1.5 py-0.5 rounded-full bg-slate-100 font-mono">
                                        {{ $evCount }}
                                    </span>
                                @endif
                            </div>

                            <!-- Events List (Max 3 visible) -->
                            <div class="space-y-1 overflow-hidden">
                                @foreach(array_slice($evts, 0, 3) as $e)
                                    @php
                                        $chipStyle = match($e['theme_color']) {
                                            'purple' => 'bg-purple-50 text-purple-800 border-purple-200',
                                            'blue'   => 'bg-blue-50 text-blue-800 border-blue-200',
                                            'indigo' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                            'rose'   => 'bg-rose-50 text-rose-800 border-rose-200',
                                            'emerald'=> 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            default  => 'bg-slate-100 text-slate-800 border-slate-200',
                                        };
                                        $icon = match($e['event_type']) {
                                            'meeting'   => '👥',
                                            'review'    => '📊',
                                            'milestone' => '🎯',
                                            'deadline'  => '⏰',
                                            default     => '📋',
                                        };
                                    @endphp
                                    <div
                                        wire:click.stop="showEventDetails({{ json_encode($e) }})"
                                        class="cal-event-chip p-1 rounded-md text-[10.5px] font-semibold border {{ $chipStyle }} truncate flex items-center gap-1 cursor-pointer"
                                        title="{{ $e['title'] }} ({{ $e['project'] }})"
                                    >
                                        <span class="text-[9px]">{{ $icon }}</span>
                                        <span class="truncate">{{ $e['title'] }}</span>
                                    </div>
                                @endforeach

                                @if($evCount > 3)
                                    <button
                                        type="button"
                                        wire:click.stop="selectDate('{{ $day['date'] }}'); $wire.setViewMode('day')"
                                        class="text-[10px] font-bold text-[#c3122e] hover:underline block text-left pt-0.5"
                                    >
                                        +{{ $evCount - 3 }} more events
                                    </button>
                                @endif
                            </div>

                            <div class="h-1"></div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         4. VIEW 2: WEEK SCHEDULE GRID
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'week')
        <div class="cal-glass-card overflow-hidden">
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center divide-x divide-slate-200">
                @foreach($weekDays as $wDay)
                    <div
                        wire:click="selectDate('{{ $wDay['date'] }}')"
                        class="py-3 px-2 cursor-pointer transition-colors {{ $wDay['isSelected'] ? 'bg-[#fff8f8]' : 'hover:bg-slate-100/60' }}"
                    >
                        <div class="text-[11px] font-bold text-slate-400 uppercase">{{ $wDay['dayName'] }}</div>
                        <div class="text-sm font-black mt-0.5 inline-flex items-center justify-center w-7 h-7 rounded-full {{ $wDay['isToday'] ? 'bg-[#c3122e] text-white' : 'text-slate-800' }}">
                            {{ $wDay['dayNumber'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 7 Columns Week Grid Content -->
            <div class="grid grid-cols-7 divide-x divide-slate-200 min-h-[380px] p-2 bg-slate-50/40">
                @foreach($weekDays as $wDay)
                    <div class="p-1 space-y-2">
                        @forelse($wDay['events'] as $e)
                            @php
                                $cardBg = match($e['theme_color']) {
                                    'purple' => 'bg-purple-50 border-purple-200 text-purple-900',
                                    'blue'   => 'bg-blue-50 border-blue-200 text-blue-900',
                                    'indigo' => 'bg-indigo-50 border-indigo-200 text-indigo-900',
                                    'rose'   => 'bg-rose-50 border-rose-200 text-rose-900',
                                    'emerald'=> 'bg-emerald-50 border-emerald-200 text-emerald-900',
                                    default  => 'bg-white border-slate-200 text-slate-800',
                                };
                            @endphp
                            <div
                                wire:click="showEventDetails({{ json_encode($e) }})"
                                class="p-2 rounded-xl border {{ $cardBg }} shadow-2xs hover:shadow-xs transition-all cursor-pointer"
                            >
                                <div class="text-[10px] font-bold opacity-75 uppercase tracking-wider mb-0.5">{{ $e['time_range'] }}</div>
                                <div class="text-xs font-bold leading-tight line-clamp-2">{{ $e['title'] }}</div>
                                <div class="text-[10px] opacity-75 truncate mt-1">📁 {{ $e['project_code'] }}</div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-[11px] text-slate-400 font-medium">
                                No events
                            </div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. VIEW 3: DAY AGENDA VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'day')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Day Timeline (2 Cols) -->
            <div class="lg:col-span-2 cal-glass-card p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <div>
                        <h2 class="text-base font-black text-slate-900">
                            {{ $selectedDateObj->format('l, F j, Y') }}
                        </h2>
                        <p class="text-xs text-slate-500 font-medium">Daily agenda and scheduled deliverable checkpoints</p>
                    </div>
                    @if($canCreate)
                        <button
                            wire:click="openCreateEventModal('{{ $selectedDate }}')"
                            type="button"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-[#c3122e] bg-[#fef2f2] hover:bg-[#fee2e2] border border-[#fecaca] cursor-pointer"
                        >
                            + Schedule on this Day
                        </button>
                    @endif
                </div>

                <div class="space-y-2.5 pt-2">
                    @forelse($selectedDayEvents as $e)
                        @php
                            $borderCol = match($e['theme_color']) {
                                'purple' => 'border-l-4 border-l-purple-500',
                                'blue'   => 'border-l-4 border-l-blue-500',
                                'indigo' => 'border-l-4 border-l-indigo-500',
                                'rose'   => 'border-l-4 border-l-rose-500',
                                'emerald'=> 'border-l-4 border-l-emerald-500',
                                default  => 'border-l-4 border-l-slate-400',
                            };
                        @endphp
                        <div
                            wire:click="showEventDetails({{ json_encode($e) }})"
                            class="p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50/80 shadow-2xs transition-all cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $borderCol }}"
                        >
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $e['event_type'] }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500 font-mono">{{ $e['time_range'] }}</span>
                                    @if($e['is_synced_to_ms'])
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">Synced to Outlook</span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $e['title'] }}</h3>
                                <div class="text-xs text-slate-500 flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-slate-700">📁 {{ $e['project'] }}</span>
                                    @if($e['location'])
                                        <span>&middot; 📍 {{ $e['location'] }}</span>
                                    @endif
                                    @if($e['attendee_names'])
                                        <span>&middot; 👤 {{ $e['attendee_names'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                                <a
                                    href="{{ $e['outlook_web_url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    wire:click.stop
                                    class="p-2 rounded-lg text-slate-500 hover:text-[#0078d4] hover:bg-blue-50 transition-colors"
                                    title="Open in Microsoft Outlook"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                                </a>
                                <button type="button" class="text-xs font-bold text-[#c3122e] hover:underline">
                                    View Details &rarr;
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-xs font-medium text-slate-400">
                            No scheduled events or deadlines on {{ $selectedDateObj->format('M j, Y') }}.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Day Summary Sidebar (1 Col) -->
            <div class="cal-glass-card p-4 sm:p-5 space-y-4">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Quick Actions &amp; Sync</h3>
                <div class="space-y-2">
                    <button
                        wire:click="openSubscribeModal"
                        type="button"
                        class="w-full text-left p-3 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all text-xs font-bold text-slate-700 flex items-center justify-between"
                    >
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 text-[#0078d4] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                            </div>
                            <span>Subscribe in Outlook</span>
                        </div>
                        <span class="text-slate-400">&rarr;</span>
                    </button>

                    <a
                        href="{{ route('calendar.feed-ics') }}"
                        class="w-full text-left p-3 rounded-xl border border-slate-200 hover:border-slate-300 hover:bg-slate-50 transition-all text-xs font-bold text-slate-700 flex items-center justify-between no-underline"
                    >
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <span>Download Full .ICS</span>
                        </div>
                        <span class="text-slate-400">&darr;</span>
                    </a>
                </div>

                <div class="pt-3 border-t border-slate-200">
                    <span class="text-[11px] font-bold text-slate-400 block mb-1">Role Permissions Active:</span>
                    <p class="text-xs text-slate-600 font-medium">
                        @if($isPmoAdmin)
                            <strong class="text-slate-900">PMO Admin Mode:</strong> You have authorization to schedule and manage events across all subsidiary projects.
                        @elseif($isPm)
                            <strong class="text-slate-900">Project Manager Mode:</strong> You can create and manage events for projects you lead.
                        @else
                            <strong class="text-slate-900">Viewer Mode:</strong> You can view all assigned project events and sync them with your Outlook calendar.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         6. VIEW 4: SCHEDULE / LIST TIMELINE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'list')
        <div class="space-y-4">
            @forelse($groupedListEvents as $dateKey => $dayEvents)
                @php $dCarbon = \Carbon\Carbon::parse($dateKey); @endphp
                <div class="cal-glass-card overflow-hidden">
                    <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-slate-900">{{ $dCarbon->format('l, F j, Y') }}</span>
                            @if($dCarbon->isToday())
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#c3122e] text-white">TODAY</span>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-slate-400 font-mono">{{ count($dayEvents) }} {{ count($dayEvents) === 1 ? 'item' : 'items' }}</span>
                    </div>

                    <div class="divide-y divide-slate-100 p-2">
                        @foreach($dayEvents as $e)
                            <div
                                wire:click="showEventDetails({{ json_encode($e) }})"
                                class="p-3 rounded-xl hover:bg-slate-50/80 transition-all cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3"
                            >
                                <div class="space-y-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                            {{ $e['event_type'] }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-500 font-mono">{{ $e['time_range'] }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $e['title'] }}</h4>
                                    <div class="text-xs text-slate-500 flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold text-slate-700">📁 {{ $e['project'] }} ({{ $e['project_code'] }})</span>
                                        @if($e['location']) <span>&middot; 📍 {{ $e['location'] }}</span> @endif
                                        @if($e['attendee_names']) <span>&middot; 👥 {{ $e['attendee_names'] }}</span> @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a
                                        href="{{ $e['outlook_web_url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        wire:click.stop
                                        class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-[#0078d4] hover:border-blue-200 transition-all flex items-center gap-1.5"
                                    >
                                        <svg class="w-3.5 h-3.5 text-[#0078d4]" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                                        <span>Outlook</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="cal-glass-card p-12 text-center text-xs font-medium text-slate-400">
                    No scheduled events found matching your active filter criteria.
                </div>
            @endforelse
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         7. MODAL: EVENT DETAILS & MICROSOFT 365 ACTIONS
         ═══════════════════════════════════════════════════════════════ -->
    <div x-data="{ open: @entangle('showEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showEventModal = false"></div>
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg p-5 sm:p-6 z-10 space-y-4 max-h-[90vh] overflow-y-auto">
            @if($selectedEvent)
                <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="px-2 py-0.5 rounded text-[10.5px] font-black uppercase tracking-wider bg-slate-100 text-slate-800">
                                {{ $selectedEvent['event_type'] ?? 'EVENT' }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 font-mono">{{ $selectedEvent['project_code'] ?? 'GS' }}</span>
                            @if(!empty($selectedEvent['is_synced_to_ms']))
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200">Synced to Outlook</span>
                            @endif
                        </div>
                        <h3 class="text-base font-black text-slate-900 leading-snug">{{ $selectedEvent['title'] }}</h3>
                    </div>
                    <button type="button" @click="open = false; $wire.showEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <!-- Schedule Info -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-500">Date:</span>
                            <span class="font-bold text-slate-900">{{ $selectedEvent['start_date_formatted'] }} @if($selectedEvent['end_date_formatted'] !== $selectedEvent['start_date_formatted']) – {{ $selectedEvent['end_date_formatted'] }} @endif</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-500">Time Range:</span>
                            <span class="font-bold text-slate-900">{{ $selectedEvent['time_range'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-500">Project:</span>
                            <span class="font-bold text-slate-900">{{ $selectedEvent['project'] }}</span>
                        </div>
                        @if(!empty($selectedEvent['location']))
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-500">Location:</span>
                                <span class="font-bold text-slate-900">{{ $selectedEvent['location'] }}</span>
                            </div>
                        @endif
                    </div>

                    @if(!empty($selectedEvent['description']))
                        <div>
                            <span class="font-bold text-slate-700 block mb-1">Description / Scope:</span>
                            <p class="text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200/60 whitespace-pre-line leading-relaxed">
                                {{ $selectedEvent['description'] }}
                            </p>
                        </div>
                    @endif

                    @if(!empty($selectedEvent['meeting_link']))
                        <div class="p-3 rounded-xl bg-[#eff6ff] border border-[#bfdbfe] flex items-center justify-between">
                            <div>
                                <span class="font-bold text-[#1e40af] block">Microsoft Teams / Meeting Link</span>
                                <span class="text-[11px] text-[#3b82f6] truncate block max-w-xs">{{ $selectedEvent['meeting_link'] }}</span>
                            </div>
                            <a href="{{ $selectedEvent['meeting_link'] }}" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#2563eb] hover:bg-[#1d4ed8] no-underline flex-shrink-0">
                                Join Meeting
                            </a>
                        </div>
                    @endif

                    @if(!empty($selectedEvent['attendee_names']))
                        <div>
                            <span class="font-bold text-slate-700 block mb-1">Attendees / Assignees:</span>
                            <div class="text-slate-600 font-medium p-2.5 rounded-xl bg-slate-50 border border-slate-200/60">
                                {{ $selectedEvent['attendee_names'] }}
                            </div>
                        </div>
                    @endif

                    <!-- Microsoft 365 1-Click Sync Button -->
                    <div class="pt-2">
                        <a
                            href="{{ $selectedEvent['outlook_web_url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-full py-2.5 px-4 rounded-xl text-xs font-black text-white bg-[#0078d4] hover:bg-[#006ab8] transition-all flex items-center justify-center gap-2 no-underline shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                            <span>Open in Microsoft 365 Outlook</span>
                        </a>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2">
                        @if($selectedEvent['source_type'] === 'calendar_event')
                            <a
                                href="{{ route('calendar.export-ics', ['type' => 'event', 'id' => $selectedEvent['raw_id']]) }}"
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 no-underline"
                            >
                                Download .ICS
                            </a>
                        @elseif($selectedEvent['source_type'] === 'wbs_task')
                            <a
                                href="{{ route('calendar.export-ics', ['type' => 'task', 'id' => $selectedEvent['raw_id']]) }}"
                                class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 no-underline"
                            >
                                Download .ICS
                            </a>
                        @endif

                        @if(!empty($selectedEvent['can_manage']))
                            <button
                                wire:click="deleteEvent('{{ $selectedEvent['source_type'] }}', {{ $selectedEvent['raw_id'] }})"
                                wire:confirm="Are you sure you want to remove this event from the calendar?"
                                type="button"
                                class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 text-xs font-bold hover:bg-rose-50 cursor-pointer"
                            >
                                Delete
                            </button>
                        @endif
                    </div>

                    <button type="button" @click="open = false; $wire.showEventModal = false" class="px-4 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 cursor-pointer">
                        Close
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         8. MODAL: EVENT CREATION (PMO ADMIN & PM ONLY)
         ═══════════════════════════════════════════════════════════════ -->
    @if($canCreate)
        <div x-data="{ open: @entangle('showCreateEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showCreateEventModal = false"></div>
            <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg p-5 sm:p-6 z-10 space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-black text-slate-900">Schedule Project Calendar Event</h3>
                        <p class="text-xs text-slate-500 font-medium">Add a meeting, milestone, or deliverable deadline</p>
                    </div>
                    <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createEvent" class="space-y-3.5 text-xs">
                    <!-- Project Selector (Filtered by Permission) -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Associated Project <span class="text-rose-500">*</span>
                        </label>
                        <select wire:model="newEventProject" class="w-full text-xs font-semibold rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]" required>
                            @if($isPmoAdmin)
                                <option value="">-- Organization Wide / Global Event --</option>
                            @endif
                            @foreach($creatableProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                            @endforeach
                        </select>
                        @error('newEventProject') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Event Title -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">
                            Event Title <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" wire:model="newEventTitle" placeholder="e.g. Steering Committee Review or Sprint Planning" class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]" required>
                        @error('newEventTitle') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Event Type & Priority -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Event Category</label>
                            <select wire:model="newEventType" class="w-full text-xs font-semibold rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]">
                                <option value="meeting">👥 Project Meeting / Sync</option>
                                <option value="review">📊 Governance Review</option>
                                <option value="milestone">🎯 Key Milestone</option>
                                <option value="task">📋 Deliverable Task</option>
                                <option value="deadline">⏰ Target Deadline</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Priority</label>
                            <select wire:model="newEventPriority" class="w-full text-xs font-semibold rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Start Date <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="newEventDate" class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]" required>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">End Date (Optional)</label>
                            <input type="date" wire:model="newEventEndDate" class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-[#c3122e]">
                        </div>
                    </div>

                    <!-- All Day Toggle & Time Inputs -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="allDayCheck" wire:model.live="newEventIsAllDay" class="rounded text-[#c3122e] focus:ring-0 cursor-pointer">
                            <label for="allDayCheck" class="font-bold text-slate-700 cursor-pointer">All-day event / milestone</label>
                        </div>

                        @if(!$newEventIsAllDay)
                            <div class="grid grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="block font-semibold text-slate-600 mb-0.5">Start Time</label>
                                    <input type="time" wire:model="newEventStartTime" class="w-full text-xs rounded-lg px-2.5 py-1.5 border border-slate-200 bg-white text-slate-800">
                                </div>
                                <div>
                                    <label class="block font-semibold text-slate-600 mb-0.5">End Time</label>
                                    <input type="time" wire:model="newEventEndTime" class="w-full text-xs rounded-lg px-2.5 py-1.5 border border-slate-200 bg-white text-slate-800">
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Location & Teams Link -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Location / Room</label>
                            <input type="text" wire:model="newEventLocation" placeholder="e.g. Boardroom A / Microsoft Teams" class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Microsoft Teams Link</label>
                            <input type="url" wire:model="newEventMeetingLink" placeholder="https://teams.microsoft.com/..." class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800">
                        </div>
                    </div>

                    <!-- Attendees Multi-Select -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Assignees / Attendees</label>
                        <select wire:model="newEventAttendees" multiple class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 h-24">
                            @foreach($assignableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Hold Ctrl / Cmd to select multiple team members</span>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Agenda / Description</label>
                        <textarea wire:model="newEventDescription" rows="2" placeholder="Key meeting objectives, deliverables or review agenda..." class="w-full text-xs rounded-xl px-3 py-2 border border-slate-200 bg-white text-slate-800 leading-relaxed"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">
                            Cancel
                        </button>
                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-2 rounded-xl text-xs font-black text-white cursor-pointer shadow-sm flex items-center gap-1.5"
                            style="background: #c3122e;"
                        >
                            <span wire:loading.remove>Schedule Event</span>
                            <span wire:loading>Scheduling &amp; Syncing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         9. MODAL: OUTLOOK LIVE SUBSCRIPTION SETUP
         ═══════════════════════════════════════════════════════════════ -->
    <div x-data="{ open: @entangle('showSubscribeModal'), copied: false }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showSubscribeModal = false"></div>
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md p-5 sm:p-6 z-10 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#eff6ff] text-[#0078d4] flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Microsoft Outlook Live Sync</h3>
                        <p class="text-[11px] text-slate-500">Subscribe your project calendar directly to Outlook</p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.showSubscribeModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <p class="text-slate-600 font-medium">
                    You can add your personalized George Steuart calendar feed directly into Microsoft 365 Outlook (Desktop, Web, or Mobile). Any changes or new milestones will stay synchronized automatically.
                </p>

                <!-- Feed URL Copy Box -->
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                    <span class="text-[11px] font-bold text-slate-500 block">Your Secure iCalendar Feed URL:</span>
                    <div class="flex items-center gap-2">
                        <input type="text" readonly value="{{ $feedUrl }}" class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 text-slate-800 select-all">
                        <button
                            type="button"
                            @click="navigator.clipboard.writeText('{{ $feedUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#0078d4] hover:bg-[#006ab8] transition-all cursor-pointer flex-shrink-0"
                        >
                            <span x-show="!copied">Copy</span>
                            <span x-show="copied">Copied!</span>
                        </button>
                    </div>
                </div>

                <!-- 3-Step Guide -->
                <div class="space-y-1.5 text-[11.5px] text-slate-600">
                    <span class="font-bold text-slate-800 block">How to add in Outlook:</span>
                    <ol class="list-decimal list-inside space-y-1 pl-1">
                        <li>Open <a href="https://outlook.office.com/calendar" target="_blank" class="text-[#0078d4] font-bold hover:underline">Outlook Calendar</a>.</li>
                        <li>Click <strong>Add calendar</strong> in the sidebar &rarr; <strong>Subscribe from web</strong>.</li>
                        <li>Paste the copied URL and click <strong>Import</strong>.</li>
                    </ol>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('calendar.feed-ics') }}" class="text-xs font-bold text-[#c3122e] hover:underline no-underline">
                    Download .ICS file instead
                </a>
                <button type="button" @click="open = false; $wire.showSubscribeModal = false" class="px-4 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 cursor-pointer">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>
