<div class="space-y-4 sm:space-y-5 pb-16">
    <style>
        .cal-glass-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 1px 3px rgba(15,23,42,.03); }
        .cal-btn-outline { background: #ffffff; border: 1px solid #e2e8f0; color: #475569; font-weight: 700; transition: all .15s; }
        .cal-btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
        .cal-event-chip { transition: transform .1s ease, box-shadow .1s ease; }
        .cal-event-chip:hover { transform: translateY(-1px); box-shadow: 0 2px 5px rgba(0,0,0,.06); }
        .scrollbar-slim { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        .scrollbar-slim::-webkit-scrollbar { width: 4px; height: 4px; }
        .scrollbar-slim::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & CORPORATE ACTION BAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 sm:gap-4">
        <!-- Left: Clean Title & Month Indicator -->
        <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Corporate Calendar
            </h1>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ $currentDate->format('F Y') }}</span>
            </span>
        </div>

        <!-- Right: Navigator, View Switcher & Actions -->
        <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap xl:flex-nowrap">
            <!-- Period Navigator -->
            <div class="inline-flex items-center p-0.5 rounded-xl border border-slate-200 bg-white shadow-2xs">
                <button wire:click="prevPeriod" type="button" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all cursor-pointer" title="Previous Period">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button wire:click="today" type="button" class="px-3 py-1 text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all cursor-pointer">
                    Today
                </button>
                <button wire:click="nextPeriod" type="button" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all cursor-pointer" title="Next Period">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <!-- View Switcher -->
            <div class="inline-flex p-0.5 rounded-xl border border-slate-200 bg-slate-100/90 shadow-2xs text-xs font-bold">
                <button wire:click="setViewMode('calendar')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    Month
                </button>
                <button wire:click="setViewMode('week')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'week' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    Week
                </button>
                <button wire:click="setViewMode('day')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'day' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                    Day
                </button>
                <button wire:click="setViewMode('list')" type="button" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer {{ $viewMode === 'list' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
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
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Schedule Event</span>
                </button>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. UNIFIED CLEAN FILTER & SEARCH TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="cal-glass-card p-2.5 sm:p-3 space-y-2.5">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
            <!-- Left: Scope Switcher & Category Filter Pills -->
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Scope Switcher -->
                <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 text-xs font-bold shrink-0">
                    <button wire:click="setScope('all')" type="button" class="px-3 py-1 rounded-lg transition-all cursor-pointer {{ $scopeFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                        All Events
                    </button>
                    <button wire:click="setScope('my_events')" type="button" class="px-3 py-1 rounded-lg transition-all cursor-pointer {{ $scopeFilter === 'my_events' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                        My Assigned
                    </button>
                </div>

                <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

                <!-- Category Filter Pills with Live Counters -->
                <div class="inline-flex items-center gap-1.5 flex-wrap">
                    <!-- Meetings -->
                    <button wire:click="toggleCategory('meeting')" type="button" class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border {{ $categoryFilter === 'meeting' ? 'bg-[#0078d4] text-white border-[#0078d4] shadow-2xs' : 'bg-slate-50/90 text-slate-700 hover:bg-sky-50/80 hover:text-sky-900 border-slate-200/80 hover:border-sky-200' }}">
                        <span class="w-2 h-2 rounded-full {{ $categoryFilter === 'meeting' ? 'bg-white' : 'bg-[#0078d4]' }}"></span>
                        <span>Meetings</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $categoryFilter === 'meeting' ? 'bg-sky-700 text-white' : 'bg-slate-200/80 text-slate-600' }}">{{ $meetingsCount }}</span>
                    </button>

                    <!-- Milestones -->
                    <button wire:click="toggleCategory('milestone')" type="button" class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border {{ $categoryFilter === 'milestone' ? 'bg-purple-600 text-white border-purple-600 shadow-2xs' : 'bg-slate-50/90 text-slate-700 hover:bg-purple-50/80 hover:text-purple-900 border-slate-200/80 hover:border-purple-200' }}">
                        <span class="w-2 h-2 rounded-full {{ $categoryFilter === 'milestone' ? 'bg-white' : 'bg-purple-500' }}"></span>
                        <span>Milestones</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $categoryFilter === 'milestone' ? 'bg-purple-700 text-white' : 'bg-slate-200/80 text-slate-600' }}">{{ $milestonesCount }}</span>
                    </button>

                    <!-- Tasks -->
                    <button wire:click="toggleCategory('task')" type="button" class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border {{ $categoryFilter === 'task' ? 'bg-emerald-600 text-white border-emerald-600 shadow-2xs' : 'bg-slate-50/90 text-slate-700 hover:bg-emerald-50/80 hover:text-emerald-900 border-slate-200/80 hover:border-emerald-200' }}">
                        <span class="w-2 h-2 rounded-full {{ $categoryFilter === 'task' ? 'bg-white' : 'bg-emerald-500' }}"></span>
                        <span>Tasks</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $categoryFilter === 'task' ? 'bg-emerald-700 text-white' : 'bg-slate-200/80 text-slate-600' }}">{{ $tasksCount }}</span>
                    </button>

                    @if($categoryFilter !== 'all')
                        <button wire:click="$set('categoryFilter', 'all')" type="button" class="px-2 py-1 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 cursor-pointer flex items-center gap-1 transition-colors" title="Clear category filter">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Clear</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Right: Subsidiary, Project & Search -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Subsidiary Filter Dropdown -->
                <div class="relative min-w-[135px]">
                    <select wire:model.live="subsidiaryFilter" class="w-full text-xs font-semibold rounded-xl pl-3 pr-7 py-1.5 border border-slate-200 bg-white hover:border-slate-300 focus:bg-white text-slate-700 focus:outline-none focus:border-slate-800 cursor-pointer appearance-none transition-colors shadow-2xs">
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
                <div class="relative min-w-[155px] max-w-[220px]">
                    <select wire:model.live="projectFilter" class="w-full text-xs font-semibold rounded-xl pl-3 pr-7 py-1.5 border border-slate-200 bg-white hover:border-slate-300 focus:bg-white text-slate-700 focus:outline-none focus:border-slate-800 cursor-pointer appearance-none transition-colors truncate shadow-2xs">
                        <option value="all">All Projects</option>
                        @foreach($projectsList as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="w-full sm:w-52 relative shrink-0">
                    <div class="absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input
                        type="text"
                        wire:model.live.debounce.250ms="search"
                        placeholder="Search events..."
                        class="w-full text-xs font-medium pl-8 pr-7 py-1.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-slate-800 transition-all shadow-2xs"
                    >
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. VIEW 1: MONTH CALENDAR GRID (CLEAN EXECUTIVE MATRIX)
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'calendar')
        <div class="cal-glass-card overflow-hidden border border-slate-200/90 rounded-2xl shadow-2xs bg-white">
            <!-- Day of Week Header -->
            <div class="grid grid-cols-7 border-b border-slate-200/90 bg-slate-50/90 text-center text-xs font-bold text-slate-500 divide-x divide-slate-100 select-none">
                <div class="py-2.5 text-rose-500/80">Sun</div>
                <div class="py-2.5">Mon</div>
                <div class="py-2.5">Tue</div>
                <div class="py-2.5">Wed</div>
                <div class="py-2.5">Thu</div>
                <div class="py-2.5">Fri</div>
                <div class="py-2.5 text-slate-400">Sat</div>
            </div>

            <!-- Calendar Days Grid -->
            <div class="grid grid-cols-7 divide-x divide-y divide-slate-100 bg-slate-100/30">
                @foreach($weeks as $week)
                    @foreach($week as $day)
                        @php
                            $evts = $day['events'];
                            $evCount = count($evts);
                            $isToday = $day['isToday'];
                            $isSelected = $day['isSelected'];
                            $isCurrentMonth = $day['isCurrentMonth'];
                        @endphp
                        <div
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="group relative min-h-[125px] sm:min-h-[135px] p-1.5 sm:p-2 flex flex-col transition-all cursor-pointer select-none
                                   {{ $isCurrentMonth ? 'bg-white hover:bg-slate-50/80' : 'bg-slate-50/60 hover:bg-slate-100/50 opacity-65' }}
                                   {{ $isToday ? 'bg-rose-50/20' : '' }}
                                   {{ $isSelected && !$isToday ? 'ring-2 ring-slate-800/15 ring-inset bg-slate-50/50' : '' }}"
                        >
                            <!-- Day Number & Header Action -->
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs transition-transform
                                            {{ $isToday
                                                ? 'bg-[#c3122e] text-white font-black shadow-xs ring-2 ring-rose-200'
                                                : ($isSelected
                                                    ? 'bg-slate-900 text-white font-bold'
                                                    : ($isCurrentMonth ? 'font-bold text-slate-700 group-hover:text-slate-900' : 'font-medium text-slate-400')) }}">
                                    {{ $day['dayNumber'] }}
                                </span>

                                <!-- Quick Schedule Event Shortcut on Hover (for PMO Admin / PM) -->
                                @if($canCreate && $isCurrentMonth)
                                    <button
                                        type="button"
                                        wire:click.stop="openCreateEventModal('{{ $day['date'] }}')"
                                        class="opacity-0 group-hover:opacity-100 w-5 h-5 rounded-md text-slate-400 hover:text-slate-800 hover:bg-slate-200/70 flex items-center justify-center transition-all cursor-pointer"
                                        title="Schedule event on {{ $day['date'] }}"
                                    >
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Events List (Max 3 visible per cell) -->
                            <div class="space-y-1 flex-1 overflow-hidden">
                                @foreach(array_slice($evts, 0, 3) as $e)
                                    @php
                                        $evType = $e['event_type'] ?? 'task';
                                        $isMeeting = in_array($evType, ['meeting', 'review']) || ($e['source_type'] ?? '') === 'calendar_event';
                                        $isMilestone = in_array($evType, ['milestone']) || ($e['theme_color'] ?? '') === 'purple';
                                        $isDeadline = in_array($evType, ['deadline']) || ($e['theme_color'] ?? '') === 'rose';
                                        $isCompleted = ($e['status'] ?? '') === 'completed';

                                        if ($isMeeting) {
                                            $chipStyle = 'bg-sky-50/90 text-sky-950 border-sky-200/70 border-l-[3px] border-l-[#0078d4] hover:bg-sky-100 hover:border-sky-300';
                                            $iconColor = 'text-[#0078d4]';
                                        } elseif ($isMilestone) {
                                            $chipStyle = 'bg-purple-50/90 text-purple-950 border-purple-200/70 border-l-[3px] border-l-purple-600 hover:bg-purple-100 hover:border-purple-300';
                                            $iconColor = 'text-purple-600';
                                        } elseif ($isDeadline) {
                                            $chipStyle = 'bg-rose-50/90 text-rose-950 border-rose-200/70 border-l-[3px] border-l-[#c3122e] hover:bg-rose-100 hover:border-rose-300';
                                            $iconColor = 'text-[#c3122e]';
                                        } elseif ($isCompleted) {
                                            $chipStyle = 'bg-emerald-50/80 text-emerald-950 border-emerald-200/70 border-l-[3px] border-l-emerald-500 hover:bg-emerald-100 line-through opacity-85';
                                            $iconColor = 'text-emerald-600';
                                        } else {
                                            $chipStyle = 'bg-slate-50/90 text-slate-800 border-slate-200/80 border-l-[3px] border-l-slate-400 hover:bg-slate-100/90 hover:border-slate-300';
                                            $iconColor = 'text-slate-400';
                                        }
                                    @endphp
                                    <div
                                        wire:click.stop="showEventDetails({{ json_encode($e) }})"
                                        class="cal-event-chip px-1.5 py-1 rounded-md text-[11px] font-semibold border {{ $chipStyle }} truncate flex items-center gap-1.5 cursor-pointer shadow-2xs transition-all"
                                        title="{{ $e['title'] }} ({{ $e['project'] }}) &bull; {{ $e['time_range'] }}"
                                    >
                                        <!-- Semantic Icon -->
                                        @if($isMeeting)
                                            <svg class="w-3 h-3 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        @elseif($isMilestone)
                                            <svg class="w-3 h-3 {{ $iconColor }} shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/></svg>
                                        @elseif($isDeadline)
                                            <svg class="w-3 h-3 {{ $iconColor }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/></svg>
                                        @elseif($isCompleted)
                                            <svg class="w-3 h-3 {{ $iconColor }} shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                                        @endif

                                        <!-- Time Badge if timed meeting or task -->
                                        @if(!$e['is_all_day'] && !empty($e['start_time']) && $e['start_time'] !== '??:??:??')
                                            <span class="font-bold text-[9.5px] opacity-75 shrink-0 font-mono">
                                                {{ substr($e['time_range'], 0, 5) }}
                                            </span>
                                        @endif

                                        <span class="truncate leading-none">{{ $e['title'] }}</span>

                                        @if(!empty($e['is_synced_to_ms']))
                                            <svg class="w-2.5 h-2.5 text-[#0078d4] shrink-0 ml-auto" fill="currentColor" viewBox="0 0 24 24" title="Synced to Outlook"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/></svg>
                                        @endif
                                    </div>
                                @endforeach

                                @if($evCount > 3)
                                    <button
                                        type="button"
                                        wire:click.stop="selectDate('{{ $day['date'] }}'); $wire.setViewMode('day')"
                                        class="w-full text-left text-[10px] font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-100/90 px-1.5 py-0.5 rounded transition-colors flex items-center justify-between cursor-pointer group/more"
                                    >
                                        <span>+{{ $evCount - 3 }} more</span>
                                        <span class="opacity-0 group-hover/more:opacity-100 text-[9px] text-slate-400 font-semibold">View Day &rarr;</span>
                                    </button>
                                @endif
                            </div>
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
        <div class="cal-glass-card overflow-hidden border border-slate-200/90 rounded-2xl shadow-2xs bg-white">
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center divide-x divide-slate-100 select-none">
                @foreach($weekDays as $wDay)
                    <div
                        wire:click="selectDate('{{ $wDay['date'] }}')"
                        class="py-3 px-2 cursor-pointer transition-colors {{ $wDay['isSelected'] ? 'bg-rose-50/30' : 'hover:bg-slate-100/60' }}"
                    >
                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $wDay['dayName'] }}</div>
                        <div class="text-sm font-black mt-1 inline-flex items-center justify-center w-7 h-7 rounded-full {{ $wDay['isToday'] ? 'bg-[#c3122e] text-white shadow-xs' : ($wDay['isSelected'] ? 'bg-slate-900 text-white' : 'text-slate-800') }}">
                            {{ $wDay['dayNumber'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 7 Columns Week Grid Content -->
            <div class="grid grid-cols-7 divide-x divide-slate-100 min-h-[420px] p-2 bg-slate-50/30">
                @foreach($weekDays as $wDay)
                    <div class="p-1.5 space-y-2">
                        @forelse($wDay['events'] as $e)
                            @php
                                $evType = $e['event_type'] ?? 'task';
                                $isMeeting = in_array($evType, ['meeting', 'review']) || ($e['source_type'] ?? '') === 'calendar_event';
                                $isMilestone = in_array($evType, ['milestone']) || ($e['theme_color'] ?? '') === 'purple';
                                $isDeadline = in_array($evType, ['deadline']) || ($e['theme_color'] ?? '') === 'rose';

                                if ($isMeeting) {
                                    $wChipStyle = 'bg-sky-50/90 text-sky-950 border-sky-200/70 border-l-[3px] border-l-[#0078d4]';
                                } elseif ($isMilestone) {
                                    $wChipStyle = 'bg-purple-50/90 text-purple-950 border-purple-200/70 border-l-[3px] border-l-purple-600';
                                } elseif ($isDeadline) {
                                    $wChipStyle = 'bg-rose-50/90 text-rose-950 border-rose-200/70 border-l-[3px] border-l-[#c3122e]';
                                } else {
                                    $wChipStyle = 'bg-white text-slate-800 border-slate-200/80 border-l-[3px] border-l-slate-400';
                                }
                            @endphp
                            <div
                                wire:click="showEventDetails({{ json_encode($e) }})"
                                class="p-2.5 rounded-xl border {{ $wChipStyle }} shadow-2xs hover:shadow-xs transition-all cursor-pointer space-y-1"
                            >
                                <div class="text-[10px] font-bold opacity-75 uppercase tracking-wider font-mono">{{ $e['time_range'] }}</div>
                                <div class="text-xs font-bold leading-tight line-clamp-2">{{ $e['title'] }}</div>
                                <div class="text-[10px] opacity-75 truncate pt-0.5 flex items-center gap-1">
                                    <span>📁</span>
                                    <span>{{ $e['project_code'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-[11px] text-slate-400 font-medium">
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
                            class="px-3 py-1.5 rounded-xl text-xs font-bold text-[#c3122e] bg-rose-50 hover:bg-rose-100 border border-rose-200 cursor-pointer transition-colors"
                        >
                            + Schedule on this Day
                        </button>
                    @endif
                </div>

                <div class="space-y-2.5 pt-2">
                    @forelse($selectedDayEvents as $e)
                        @php
                            $evType = $e['event_type'] ?? 'task';
                            $borderCol = match($e['theme_color']) {
                                'purple' => 'border-l-4 border-l-purple-500',
                                'blue', 'sky' => 'border-l-4 border-l-[#0078d4]',
                                'indigo' => 'border-l-4 border-l-indigo-500',
                                'rose'   => 'border-l-4 border-l-[#c3122e]',
                                'emerald'=> 'border-l-4 border-l-emerald-500',
                                default  => 'border-l-4 border-l-slate-400',
                            };
                        @endphp
                        <div
                            wire:click="showEventDetails({{ json_encode($e) }})"
                            class="p-3.5 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50/80 shadow-2xs transition-all cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $borderCol }}"
                        >
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $e['event_type'] }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-500 font-mono">{{ $e['time_range'] }}</span>
                                    @if($e['is_synced_to_ms'])
                                        <span class="text-[10px] font-bold text-[#0078d4] bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200/60">Synced to Outlook</span>
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
                                    class="p-2 rounded-lg text-slate-500 hover:text-[#0078d4] hover:bg-sky-50 transition-colors"
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
                            <div class="w-7 h-7 rounded-lg bg-sky-50 text-[#0078d4] flex items-center justify-center">
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
                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
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
                <div class="cal-glass-card overflow-hidden border border-slate-200/90 rounded-2xl shadow-2xs bg-white">
                    <div class="px-4 py-2.5 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between">
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
                            @php
                                $evType = $e['event_type'] ?? 'task';
                                $borderCol = match($e['theme_color']) {
                                    'purple' => 'border-l-purple-500',
                                    'blue', 'sky' => 'border-l-[#0078d4]',
                                    'indigo' => 'border-l-indigo-500',
                                    'rose'   => 'border-l-[#c3122e]',
                                    'emerald'=> 'border-l-emerald-500',
                                    default  => 'border-l-slate-400',
                                };
                            @endphp
                            <div
                                wire:click="showEventDetails({{ json_encode($e) }})"
                                class="p-3 rounded-xl hover:bg-slate-50/80 transition-all cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-l-[3px] {{ $borderCol }}"
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
                                        class="px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-sky-50 hover:text-[#0078d4] hover:border-sky-200 transition-all flex items-center gap-1.5"
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
                            @if($selectedEvent['source_type'] === 'calendar_event')
                                <button
                                    wire:click="openEditEventModal({{ $selectedEvent['raw_id'] }})"
                                    type="button"
                                    class="px-3 py-1.5 rounded-lg border border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 text-xs font-bold cursor-pointer flex items-center gap-1.5 transition-colors"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit Meeting</span>
                                </button>
                            @endif

                            <button
                                wire:click="deleteEvent('{{ $selectedEvent['source_type'] }}', {{ $selectedEvent['raw_id'] }})"
                                wire:confirm="Are you sure you want to remove this event from the calendar?"
                                type="button"
                                class="px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 text-xs font-bold cursor-pointer flex items-center gap-1.5 transition-colors"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Delete</span>
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
         8. MODAL: 2-STEP INTERACTIVE PROJECT SCHEDULER & AVAILABILITY WIZARD
         ═══════════════════════════════════════════════════════════════ -->
    @if($canCreate)
        <div x-data="{ open: @entangle('showCreateEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 overflow-y-auto" style="display:none" x-cloak>
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showCreateEventModal = false"></div>

            <!-- Modal Card -->
            <div class="relative bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-100 w-full max-w-5xl z-10 my-auto flex flex-col max-h-[92vh] overflow-hidden transition-all duration-200">

                <!-- Clean Modal Header with Integrated Minimal Stepper -->
                <div class="px-6 py-3.5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Schedule Project Event</h2>
                            <p class="text-[11px] text-slate-400 font-medium">
                                {{ $createEventStep === 1 ? 'Configure event details and required attendees' : 'Select conflict-free meeting time' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Exchange Connected indicator -->
                        <div class="hidden md:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200/60 text-[11px] font-medium text-slate-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Exchange Connected</span>
                        </div>

                        <!-- Segmented Pill Stepper -->
                        <div class="hidden sm:inline-flex items-center p-1 rounded-xl bg-slate-100/90 border border-slate-200/60 text-xs font-semibold">
                            <button type="button" wire:click="goToStep1" class="px-3 py-1 rounded-lg transition-all cursor-pointer {{ $createEventStep === 1 ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                                1. Details
                            </button>
                            <button type="button" @if($createEventStep === 2) wire:click="goToStep2" @endif class="px-3 py-1 rounded-lg transition-all {{ $createEventStep === 2 ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-400 cursor-default' }}">
                                2. Availability
                            </button>
                        </div>

                        <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Body Content -->
                <div class="flex-1 overflow-y-auto px-6 py-4 scrollbar-slim">

                    <!-- ── STEP 1: CLEAN 2-COLUMN LAYOUT ──────────────────── -->
                    @if($createEventStep === 1)
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                        <!-- Left Column: Event Fields (7 cols) -->
                        <div class="lg:col-span-7 space-y-3.5">
                            <!-- Associated Project -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Associated Project <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select wire:model.live="newEventProject" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                        @if($isPmoAdmin)
                                            <option value="">— Global Corporate Event —</option>
                                        @endif
                                        @foreach($creatableProjects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                @error('newEventProject') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Event Title -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Event Title <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" wire:model="newEventTitle"
                                    placeholder="e.g. Steering Committee Review or Sprint Planning"
                                    class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all">
                                @error('newEventTitle') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- 2-Column: Date & Location -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                                        Date <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" wire:model.live="newEventDate"
                                        class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 text-slate-800 cursor-pointer transition-all" required>
                                    @error('newEventDate') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                                        Location / Room
                                    </label>
                                    <input type="text" wire:model="newEventLocation"
                                        placeholder="e.g. Boardroom A or Teams Link"
                                        class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all">
                                </div>
                            </div>

                            <!-- 2-Column: Category & Priority -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                                    <div class="relative">
                                        <select wire:model="newEventType" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                            <option value="meeting">👥 Meeting / Sync</option>
                                            <option value="review">📊 Governance Review</option>
                                            <option value="milestone">🎯 Key Milestone</option>
                                            <option value="task">📋 Deliverable Task</option>
                                            <option value="deadline">⏰ Deadline</option>
                                        </select>
                                        <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Priority</label>
                                    <div class="relative">
                                        <select wire:model="newEventPriority" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                            <option value="low">🟢 Low</option>
                                            <option value="medium">🟡 Medium</option>
                                            <option value="high">🔴 High</option>
                                            <option value="critical">🚨 Critical</option>
                                        </select>
                                        <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Optional Agenda & Objectives -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Agenda &amp; Notes <span class="text-[11px] font-normal text-slate-400">(Optional)</span>
                                </label>
                                <textarea wire:model="newEventDescription" rows="2"
                                    placeholder="Add brief agenda points or discussion topics..."
                                    class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Right Column: Project Team & Attendees (5 cols) -->
                        <div class="lg:col-span-5 flex flex-col space-y-2.5" x-data="{ attendeeSearch: '' }">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-800">Team Attendees</span>
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        {{ count($newEventAttendees) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <button type="button" wire:click="selectAllAttendees" class="text-xs font-medium text-slate-600 hover:text-slate-900 cursor-pointer transition-colors">Select all</button>
                                    <span class="text-slate-300">·</span>
                                    <button type="button" wire:click="deselectAllAttendees" class="text-xs font-medium text-slate-400 hover:text-slate-600 cursor-pointer transition-colors">Clear</button>
                                </div>
                            </div>

                            <!-- Search Input -->
                            <div class="relative">
                                <input type="text" x-model="attendeeSearch" placeholder="Search members by name or role..."
                                    class="w-full text-xs font-medium rounded-xl pl-8 pr-7 py-2 border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all">
                                <div class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <button x-show="attendeeSearch" @click="attendeeSearch = ''" type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <!-- Attendee List Container -->
                            <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                                <div class="h-[320px] overflow-y-auto divide-y divide-slate-100 scrollbar-slim">
                                    @forelse($this->projectAttendees as $attendee)
                                        @php
                                            $isSelected = in_array((int)$attendee->id, $newEventAttendees);
                                            $initials = $this->getInitials($attendee->name);
                                            $avatarColors = [
                                                '#0284c7', '#059669', '#7c3aed', '#d97706', '#db2777', '#0d9488', '#4f46e5', '#ea580c'
                                            ];
                                            $aColor = $avatarColors[$attendee->id % count($avatarColors)];
                                            $filterText = strtolower($attendee->name . ' ' . $attendee->email . ' ' . ($attendee->project_role_label ?? ''));
                                        @endphp
                                        <div x-show="!attendeeSearch || {{ json_encode($filterText) }}.includes(attendeeSearch.toLowerCase())"
                                             wire:click="toggleAttendee({{ $attendee->id }})"
                                             class="px-3.5 py-2 transition-colors cursor-pointer select-none flex items-center justify-between gap-3 {{ $isSelected ? 'bg-slate-50/70 hover:bg-slate-100/60' : 'hover:bg-slate-50 bg-white' }}">
                                            <!-- Avatar & Info -->
                                            <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                                <div class="w-7 h-7 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0 shadow-2xs" style="background:{{ $aColor }};">
                                                    {{ $initials }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <span class="font-semibold text-slate-900 text-xs truncate">{{ $attendee->name }}</span>
                                                        @if(!empty($attendee->project_role_label))
                                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-medium bg-slate-100 text-slate-500">{{ $attendee->project_role_label }}</span>
                                                        @endif
                                                        @if($this->userHasAzureAccount($attendee))
                                                            <svg class="w-2.5 h-2.5 shrink-0" viewBox="0 0 23 23" fill="none" title="Microsoft 365 Connected"><rect x="1" y="1" width="10" height="10" fill="#f25022"/><rect x="12" y="1" width="10" height="10" fill="#7fba00"/><rect x="1" y="12" width="10" height="10" fill="#00a4ef"/><rect x="12" y="12" width="10" height="10" fill="#ffb900"/></svg>
                                                        @endif
                                                    </div>
                                                    <span class="text-[11px] text-slate-400 truncate block">{{ $attendee->email }}</span>
                                                </div>
                                            </div>
                                            <!-- Checkbox -->
                                            <div class="shrink-0">
                                                @if($isSelected)
                                                    <div class="w-4.5 h-4.5 rounded-full bg-[#c3122e] text-white flex items-center justify-center shadow-xs">
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                @else
                                                    <div class="w-4.5 h-4.5 rounded-full border border-slate-300 bg-white hover:border-slate-400 transition-colors"></div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-6 text-center text-slate-400 text-xs font-medium">
                                            No team members found for this project.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Microsoft 365 Clean Helper Footnote -->
                            <div class="flex items-center gap-1.5 pt-1 text-[11px] text-slate-400">
                                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 23 23" fill="none"><rect x="1" y="1" width="10" height="10" fill="#f25022"/><rect x="12" y="1" width="10" height="10" fill="#7fba00"/><rect x="1" y="12" width="10" height="10" fill="#00a4ef"/><rect x="12" y="12" width="10" height="10" fill="#ffb900"/></svg>
                                <span>Outlook availability will be evaluated in the next step.</span>
                            </div>
                        </div>

                    </div>
                    @endif

                    <!-- ── STEP 2: OUTLOOK AVAILABILITY & TIME PICKER ──────────── -->
                    @if($createEventStep === 2)
                    <div class="space-y-3">

                        <!-- Top Executive Toolbar: Date Navigator + Quick Availability Pill -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-2 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <!-- Date switcher -->
                            <div class="flex items-center gap-2">
                                <div class="inline-flex items-center bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                                    <button wire:click="changeScheduleDate('prev')" type="button" class="p-1.5 px-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 cursor-pointer transition-colors" title="Previous Day">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <label class="relative cursor-pointer flex items-center group">
                                        <span class="px-2.5 py-1 font-bold text-xs text-slate-800 group-hover:text-[#c3122e] transition-colors flex items-center gap-1.5" title="Click to pick a specific date">
                                            {{ \Carbon\Carbon::parse($newEventDate)->format('D, M d, Y') }}
                                            <svg class="w-3 h-3 text-slate-400 group-hover:text-[#c3122e] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </span>
                                        <input type="date" wire:model.live="newEventDate" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                                    </label>
                                    <button wire:click="changeScheduleDate('next')" type="button" class="p-1.5 px-2 text-slate-500 hover:text-slate-900 hover:bg-slate-50 cursor-pointer transition-colors" title="Next Day">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                <button wire:click="computeAttendeeAvailability" type="button" class="p-1.5 px-2 text-slate-600 hover:text-slate-900 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 shadow-2xs cursor-pointer transition-colors" title="Refresh Live Outlook Availability">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </button>
                            </div>

                            <!-- Availability status & Microsoft 365 indicator -->
                            @php
                                $busyCount = collect($attendeeSchedules)->filter(fn($s) => count($s['busy_slots']) > 0)->count();
                                $totalAtt = count($attendeeSchedules);
                            @endphp
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($busyCount > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-medium bg-white border border-slate-200 text-slate-700 shadow-2xs">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        {{ $totalAtt - $busyCount }} of {{ $totalAtt }} Free Today
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        All {{ $totalAtt }} Attendees Free Today
                                    </span>
                                @endif

                                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-medium shadow-2xs">
                                    <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 23 23" fill="none"><rect x="1" y="1" width="10" height="10" fill="#f25022"/><rect x="12" y="1" width="10" height="10" fill="#7fba00"/><rect x="1" y="12" width="10" height="10" fill="#00a4ef"/><rect x="12" y="12" width="10" height="10" fill="#ffb900"/></svg>
                                    <span>Outlook 365 Connected</span>
                                </span>
                            </div>
                        </div>

                        <!-- Availability Timeline Card (Unified Precision Grid) -->
                        <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs bg-white">
                            <!-- Card Header -->
                            <div class="px-4 py-2 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#0078d4]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                                    <span class="font-bold text-slate-800 text-xs">Microsoft 365 Availability Timeline</span>
                                    <span class="text-slate-400 font-normal text-[11px]">(08:00 AM – 06:00 PM)</span>
                                </div>
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="flex items-center gap-1 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Free</span>
                                    <span class="flex items-center gap-1 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 rounded-full bg-[#0078d4]"></span> Busy</span>
                                    <span class="flex items-center gap-1 text-[11px] text-slate-500 font-medium"><span class="w-2 h-2 rounded-full bg-sky-400"></span> Tentative</span>
                                </div>
                            </div>

                            <!-- Unified Scheduling Matrix -->
                            <div class="overflow-x-auto scrollbar-slim">
                                <div class="min-w-[760px]">

                                    <!-- Table Header (Hours) -->
                                    <div class="flex items-center border-b border-slate-200 bg-slate-50/60">
                                        <div style="width:190px;" class="shrink-0 px-3.5 py-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-r border-slate-200">
                                            Team Member
                                        </div>
                                        <div class="flex-1 grid grid-cols-10 text-center">
                                            @foreach(['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00'] as $h)
                                                @php
                                                    $nextH = \Carbon\Carbon::createFromFormat('H:i', $h)->addHour()->format('H:i');
                                                    $hHasConflict = $this->hasSchedulingConflict($h, $nextH);
                                                    $isHourSelected = (!$newEventIsAllDay && substr($newEventStartTime, 0, 2) === substr($h, 0, 2));
                                                @endphp
                                                <div wire:click="selectTimeSlot('{{ $h }}')"
                                                     class="py-1.5 border-r border-slate-100 last:border-r-0 cursor-pointer transition-colors {{ $isHourSelected ? 'bg-rose-50 text-[#c3122e] font-black' : 'text-slate-600 font-bold hover:bg-slate-100/80 hover:text-slate-900' }}"
                                                     title="{{ $hHasConflict ? 'Busy: Conflict exists at ' . \Carbon\Carbon::createFromFormat('H:i', $h)->format('g A') : 'Free: All attendees available at ' . \Carbon\Carbon::createFromFormat('H:i', $h)->format('g A') }}">
                                                    <span class="text-[10px] block">
                                                        {{ \Carbon\Carbon::createFromFormat('H:i', $h)->format('g A') }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Row 1: All Attendees Summary Lane -->
                                    @php
                                        try {
                                            $sT2=\Carbon\Carbon::createFromFormat('H:i',substr($newEventStartTime,0,5));
                                            $eT2=\Carbon\Carbon::createFromFormat('H:i',substr($newEventEndTime,0,5));
                                            $sL2=max(0,min(600,($sT2->hour-8)*60+$sT2->minute));
                                            $sW2=max(20,min(600-$sL2,($eT2->hour-8)*60+$eT2->minute-$sL2));
                                            $sLP2=round(($sL2/600)*100,1);
                                            $sWP2=round(($sW2/600)*100,1);
                                            $shortTimeLabel = $sT2->format('g:i') . '–' . $eT2->format('g:i');
                                        } catch(\Exception $ex2){$sLP2=0;$sWP2=0;$shortTimeLabel='';}
                                    @endphp
                                    <div class="flex items-center border-b border-slate-200 bg-slate-50/40">
                                        <div style="width:190px;" class="shrink-0 px-3.5 py-1.5 flex items-center gap-2.5 border-r border-slate-200">
                                            <div class="w-6.5 h-6.5 rounded-md bg-slate-800 text-white flex items-center justify-center shrink-0 shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <span class="font-bold text-slate-900 text-xs block truncate">All Attendees</span>
                                                <span class="text-[10px] text-slate-400 block font-medium leading-none">Team Summary</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 h-8 relative">
                                            <!-- Hairline Column Guidelines -->
                                            <div class="absolute inset-0 grid grid-cols-10 pointer-events-none">
                                                @for($i=0; $i<10; $i++)
                                                    <div class="border-r border-slate-100/90 last:border-r-0 h-full"></div>
                                                @endfor
                                            </div>

                                            <!-- Continuous Vertical Highlight Beam for Selected Meeting -->
                                            @if(!$newEventIsAllDay && $newEventStartTime && $newEventEndTime)
                                                <div class="absolute inset-y-0 pointer-events-none z-10 transition-all"
                                                     style="left:{{ $sLP2 }}%;width:{{ $sWP2 }}%;background-color:rgba(195,18,46,0.06);border-left:1.5px solid #c3122e;border-right:1.5px solid #c3122e;">
                                                </div>
                                                <!-- Selected Badge on Top Lane -->
                                                <div class="absolute inset-y-1 rounded-md bg-[#c3122e] text-white flex items-center justify-center font-bold text-[8.5px] shadow-xs z-20 transition-all pointer-events-none px-1"
                                                     style="left:{{ $sLP2 }}%;width:{{ max($sWP2, 7) }}%;"
                                                     title="Selected Meeting: {{ $newEventStartTime }} – {{ $newEventEndTime }}">
                                                    <span class="truncate font-bold">{{ $shortTimeLabel }}</span>
                                                </div>
                                            @endif

                                            <!-- Busy Conflict Blocks in Summary Lane -->
                                            @foreach($combinedBusySlots as $cSlot)
                                                <div class="absolute top-1 bottom-1 rounded-md bg-slate-700 text-white flex items-center justify-center overflow-hidden z-15 shadow-2xs"
                                                     style="left:{{ $cSlot['left_pct'] }}%;width:{{ max($cSlot['width_pct'], 4.5) }}%;min-width:18px;"
                                                     title="Busy Conflict: {{ $cSlot['start'] }}–{{ $cSlot['end'] }}">
                                                    <span class="truncate px-0.5 text-[8px] font-bold">Busy</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Member Rows -->
                                    <div class="divide-y divide-slate-100">
                                        @foreach($attendeeSchedules as $sched)
                                            <div class="flex items-center bg-white hover:bg-slate-50/40 transition-colors">
                                                <!-- Member Info -->
                                                <div style="width:190px;" class="shrink-0 px-3.5 py-1.5 flex items-center gap-2.5 border-r border-slate-200">
                                                    <div class="w-6.5 h-6.5 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0 shadow-2xs"
                                                         style="background-color: {{ $sched['color']['hex'] ?? '#c3122e' }};">
                                                        {{ $sched['initials'] }}
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <span class="font-semibold text-slate-800 text-xs block truncate" title="{{ $sched['name'] }}">{{ $sched['name'] }}</span>
                                                        @php
                                                            $hasStrictBusy = collect($sched['busy_slots'])->contains(fn($s) => ($s['status'] ?? 'busy') !== 'tentative');
                                                            $hasTentativeOnly = !$hasStrictBusy && count($sched['busy_slots']) > 0;
                                                        @endphp
                                                        @if($hasStrictBusy)
                                                            <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1 leading-none">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unavailable
                                                            </span>
                                                        @elseif($hasTentativeOnly)
                                                            <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1 leading-none">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Tentative
                                                            </span>
                                                        @else
                                                            <span class="text-[10px] font-medium text-slate-400 flex items-center gap-1 leading-none">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Available
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Member Grid Track -->
                                                <div class="flex-1 h-8 relative">
                                                    <!-- Hairline Column Guidelines -->
                                                    <div class="absolute inset-0 grid grid-cols-10 pointer-events-none">
                                                        @for($i=0; $i<10; $i++)
                                                            <div class="border-r border-slate-100 last:border-r-0 h-full"></div>
                                                        @endfor
                                                    </div>

                                                    <!-- Continuous Vertical Highlight Beam for Selected Meeting -->
                                                    @if(!$newEventIsAllDay && $newEventStartTime && $newEventEndTime)
                                                        <div class="absolute inset-y-0 pointer-events-none z-10 transition-all"
                                                             style="left:{{ $sLP2 }}%;width:{{ $sWP2 }}%;background-color:rgba(195,18,46,0.06);border-left:1.5px solid #c3122e;border-right:1.5px solid #c3122e;">
                                                        </div>
                                                    @endif

                                                    <!-- Busy and Tentative Slots -->
                                                    @forelse($sched['busy_slots'] as $slot)
                                                        @php
                                                            $isTentative = (($slot['status'] ?? '') === 'tentative' || ($slot['is_tentative'] ?? false));
                                                        @endphp
                                                        @if($isTentative)
                                                            <div class="absolute top-1 bottom-1 rounded-md bg-sky-50 border border-sky-300 text-sky-700 flex items-center justify-center overflow-hidden z-15 shadow-2xs"
                                                                 style="left:{{ $slot['left_pct'] }}%;width:{{ max($slot['width_pct'], 4.5) }}%;min-width:18px;background: repeating-linear-gradient(45deg, rgba(0, 120, 212, 0.15), rgba(0, 120, 212, 0.15) 3px, #ffffff 3px, #ffffff 6px);"
                                                                 title="{{ $sched['name'] }}: Tentative ({{ $slot['start'] }}–{{ $slot['end'] }})">
                                                                <span class="truncate px-0.5 text-[7.5px] font-bold text-sky-800">Tentative</span>
                                                            </div>
                                                        @else
                                                            <div class="absolute top-1 bottom-1 rounded-md bg-[#0078d4] text-white flex items-center justify-center overflow-hidden z-15 shadow-2xs hover:brightness-110 transition-all"
                                                                 style="left:{{ $slot['left_pct'] }}%;width:{{ max($slot['width_pct'], 4.5) }}%;min-width:18px;"
                                                                 title="{{ $sched['name'] }}: Busy ({{ $slot['start'] }}–{{ $slot['end'] }})">
                                                                <span class="truncate px-0.5 text-[8px] font-bold">Busy</span>
                                                            </div>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 2-Column Responsive Control Deck: Time Settings & Recommendations -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-start">
                            <!-- Left: Time Selector & Quick Durations (7 cols) -->
                            <div class="lg:col-span-7 rounded-2xl border border-slate-200/80 bg-white p-3 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                                        <span>Selected Meeting Time</span>
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <!-- Duration Chips Inline -->
                                        @if(!$newEventIsAllDay)
                                            <div class="inline-flex items-center gap-1">
                                                @foreach([30=>'30m', 45=>'45m', 60=>'1h', 90=>'1.5h', 120=>'2h'] as $mins=>$lbl)
                                                    @php
                                                        $currDuration = 0;
                                                        try {
                                                            $sC = \Carbon\Carbon::createFromFormat('H:i', substr($newEventStartTime,0,5));
                                                            $eC = \Carbon\Carbon::createFromFormat('H:i', substr($newEventEndTime,0,5));
                                                            $currDuration = abs($eC->diffInMinutes($sC));
                                                        } catch(\Exception $e) {}
                                                        $isActive = ($currDuration === $mins);
                                                    @endphp
                                                    <button type="button" wire:click="setDuration({{ $mins }})" class="px-2 py-0.5 rounded-md text-[11px] font-bold transition-all cursor-pointer {{ $isActive ? 'bg-[#c3122e] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                                        {{ $lbl }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                        <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold text-slate-600">
                                            <input type="checkbox" wire:model.live="newEventIsAllDay" class="rounded text-[#c3122e] focus:ring-0 w-3.5 h-3.5 cursor-pointer">
                                            <span>All-Day</span>
                                        </label>
                                    </div>
                                </div>

                                @if(!$newEventIsAllDay)
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Start Time</label>
                                            <input type="time" wire:model.live="newEventStartTime" class="w-full text-xs font-bold rounded-xl px-3 py-1.5 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 transition-all cursor-pointer" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">End Time</label>
                                            <input type="time" wire:model.live="newEventEndTime" class="w-full text-xs font-bold rounded-xl px-3 py-1.5 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 transition-all cursor-pointer" required>
                                        </div>
                                    </div>
                                @endif

                                <!-- Inline Availability / Conflict Status Banner -->
                                @if(!empty($this->schedulingConflicts))
                                    <div class="p-2 rounded-xl bg-rose-50 border border-rose-200 flex items-center gap-2 text-rose-800 text-xs font-medium">
                                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <div class="truncate">
                                            <strong>Conflict:</strong> {{ $this->schedulingConflicts[0]['user_name'] ?? 'Attendee' }} is busy at {{ $this->schedulingConflicts[0]['conflict_time'] ?? '' }}
                                        </div>
                                    </div>
                                @else
                                    <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-200/70 flex items-center gap-2 text-emerald-800 text-xs font-semibold">
                                        <span class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] shrink-0 font-bold">✓</span>
                                        <span>All selected attendees are available for this meeting time!</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Right: Recommended Free Slots & Quick Notes (5 cols) -->
                            <div class="lg:col-span-5 space-y-2.5">
                                <!-- Recommended Slots Card -->
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 shadow-2xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[11px] font-bold text-slate-700 flex items-center gap-1.5">
                                            <span>✨ Recommended Free Slots</span>
                                        </span>
                                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100/70 px-1.5 py-0.2 rounded">100% Free</span>
                                    </div>
                                    @if(!empty($suggestedSlots))
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @foreach($suggestedSlots as $sSlot)
                                                @php
                                                    $isSlotActive = (substr($newEventStartTime, 0, 5) === $sSlot['start']);
                                                @endphp
                                                <button type="button" wire:click="selectTimeSlot('{{ $sSlot['start'] }}', '{{ $sSlot['end'] }}')"
                                                    class="px-2.5 py-1 rounded-xl text-xs font-bold transition-all cursor-pointer border {{ $isSlotActive ? 'bg-[#c3122e] text-white border-[#c3122e] shadow-2xs ring-2 ring-[#c3122e]/20' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                                                    {{ $sSlot['label'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-[11px] text-amber-700 bg-amber-50/80 p-2 rounded-lg border border-amber-200/60">
                                            No fully free slots found for this duration today.
                                        </div>
                                    @endif
                                </div>

                                <!-- Compact Notes -->
                                <div>
                                    <input type="text" wire:model="newEventDescription" placeholder="Meeting agenda / quick note (optional)..."
                                        class="w-full text-xs font-medium rounded-xl px-3.5 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 placeholder:text-slate-400 text-slate-800 transition-colors shadow-2xs">
                                </div>
                            </div>
                        </div>

                    </div>
                    @endif

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between bg-white shrink-0">
                    @if($createEventStep === 2)
                        <button type="button" wire:click="goToStep1" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            Back to Details
                        </button>
                    @else
                        <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancel
                        </button>
                    @endif

                    @if($createEventStep === 1)
                        <button type="button" wire:click="goToStep2" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#c3122e] hover:bg-[#a50f26] shadow-sm shadow-[#c3122e]/20 flex items-center gap-2 cursor-pointer transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="goToStep2" class="flex items-center gap-1.5">
                                Check Availability
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <span wire:loading wire:target="goToStep2" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Checking Availability...
                            </span>
                        </button>
                    @elseif($createEventStep === 2)
                        <button type="button" wire:click="createEvent" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#c3122e] hover:bg-[#a50f26] shadow-sm shadow-[#c3122e]/20 flex items-center gap-2 cursor-pointer transition-all disabled:opacity-60">
                            <span wire:loading.remove wire:target="createEvent" class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Confirm &amp; Schedule
                            </span>
                            <span wire:loading wire:target="createEvent" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Scheduling...
                            </span>
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8.5. MODAL: EDIT MEETING & RESCHEDULE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    <div x-data="{ open: @entangle('showEditEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 overflow-y-auto" style="display:none" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" @click="open = false; $wire.closeEditEventModal()"></div>

        <!-- Modal Card -->
        <div class="relative bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-100 w-full max-w-5xl z-10 my-auto flex flex-col max-h-[92vh] overflow-hidden transition-all duration-200">

            <!-- Clean Modal Header -->
            <div class="px-6 py-3.5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Edit Meeting &amp; Schedule</h2>
                        <p class="text-[11px] text-slate-400 font-medium">
                            Update meeting specifications, schedule dates, location, and attendee list
                        </p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.closeEditEventModal()" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Scrollable Body Content (Executive 2-Column Corporate Grid) -->
            <div class="flex-1 overflow-y-auto px-6 py-5 scrollbar-slim">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                    <!-- Left Column: Meeting Specifications & Schedule (7 cols) -->
                    <div class="lg:col-span-7 space-y-3.5">
                        <!-- Associated Project -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Associated Project <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model.live="editEventProject" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                    @if($isPmoAdmin)
                                        <option value="">— Organization Wide / Global Corporate Event —</option>
                                    @endif
                                    @foreach($creatableProjects as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            @error('editEventProject') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Event Title -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Event Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" wire:model="editEventTitle"
                                placeholder="e.g. Steering Committee Review or Sprint Planning"
                                class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all">
                            @error('editEventTitle') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category & Priority (2 columns) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Category</label>
                                <div class="relative">
                                    <select wire:model="editEventType" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                        <option value="meeting">👥 Meeting / Sync</option>
                                        <option value="review">📊 Governance Review</option>
                                        <option value="milestone">🎯 Key Milestone</option>
                                        <option value="task">📋 Deliverable Task</option>
                                        <option value="deadline">⏰ Deadline</option>
                                    </select>
                                    <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Priority</label>
                                <div class="relative">
                                    <select wire:model="editEventPriority" class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 pr-9 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:border-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-800/10 appearance-none cursor-pointer text-slate-800 transition-all">
                                        <option value="low">🟢 Low</option>
                                        <option value="medium">🟡 Medium</option>
                                        <option value="high">🔴 High</option>
                                        <option value="critical">🚨 Critical</option>
                                    </select>
                                    <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date and All-Day Switch -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5 shadow-2xs">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Date &amp; Time Schedule
                                </span>
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" wire:model.live="editEventIsAllDay" class="rounded border-slate-300 text-[#c3122e] focus:ring-0 h-3.5 w-3.5 cursor-pointer">
                                    <span class="text-xs font-semibold text-slate-600">All-day event</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Start Date <span class="text-rose-500">*</span></label>
                                    <input type="date" wire:model="editEventDate"
                                        class="w-full text-xs font-medium rounded-xl px-3 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 text-slate-800 cursor-pointer shadow-2xs transition-all">
                                    @error('editEventDate') <span class="text-rose-600 text-[11px] font-semibold mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">End Date</label>
                                    <input type="date" wire:model="editEventEndDate"
                                        class="w-full text-xs font-medium rounded-xl px-3 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 text-slate-800 cursor-pointer shadow-2xs transition-all">
                                    @error('editEventEndDate') <span class="text-rose-600 text-[11px] font-semibold mt-0.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            @if(!$editEventIsAllDay)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1 border-t border-slate-200/60">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Start Time</label>
                                        <input type="time" wire:model="editEventStartTime"
                                            class="w-full text-xs font-medium rounded-xl px-3 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 text-slate-800 cursor-pointer shadow-2xs transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">End Time</label>
                                        <input type="time" wire:model="editEventEndTime"
                                            class="w-full text-xs font-medium rounded-xl px-3 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 text-slate-800 cursor-pointer shadow-2xs transition-all">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Location & Meeting Link -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Location / Room
                                </label>
                                <input type="text" wire:model="editEventLocation"
                                    placeholder="e.g. Boardroom A, Level 5"
                                    class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 placeholder:text-slate-400 text-slate-800 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">
                                    Teams / Video Link
                                </label>
                                <input type="text" wire:model="editEventMeetingLink"
                                    placeholder="https://teams.microsoft.com/..."
                                    class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2.5 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 placeholder:text-slate-400 text-slate-800 transition-all">
                                @error('editEventMeetingLink') <span class="text-rose-600 text-[11px] font-semibold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Description / Agenda -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Description / Agenda <span class="text-[11px] font-normal text-slate-400">(Optional)</span>
                            </label>
                            <textarea wire:model="editEventDescription" rows="2"
                                placeholder="Add meeting agenda or notes..."
                                class="w-full text-xs sm:text-sm font-medium rounded-xl px-3.5 py-2 border border-slate-200 bg-white hover:border-slate-300 focus:outline-none focus:ring-1 focus:ring-slate-800/10 focus:border-slate-800 placeholder:text-slate-400 text-slate-800 transition-all resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Right Column: Project Team & Attendees (5 cols) -->
                    <div class="lg:col-span-5 flex flex-col space-y-2.5" x-data="{ editAttendeeSearch: '' }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">Team Attendees</span>
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                    {{ count($editEventAttendees) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <button type="button" wire:click="selectAllEditAttendees" class="text-xs font-medium text-slate-600 hover:text-slate-900 cursor-pointer transition-colors">Select all</button>
                                <span class="text-slate-300">·</span>
                                <button type="button" wire:click="deselectAllEditAttendees" class="text-xs font-medium text-slate-400 hover:text-slate-600 cursor-pointer transition-colors">Clear</button>
                            </div>
                        </div>

                        <!-- Live Filter Input -->
                        <div class="relative">
                            <input type="text" x-model="editAttendeeSearch" placeholder="Search members by name or role..."
                                class="w-full text-xs font-medium rounded-xl pl-8 pr-7 py-2 border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800/10 placeholder:text-slate-400 text-slate-800 transition-all">
                            <div class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <button x-show="editAttendeeSearch" @click="editAttendeeSearch = ''" type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Attendee List Container -->
                        <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-2xs">
                            <div class="h-[320px] overflow-y-auto divide-y divide-slate-100 scrollbar-slim">
                                @forelse($this->editProjectAttendees as $attendee)
                                    @php
                                        $isSelected = in_array((int)$attendee->id, $editEventAttendees);
                                        $initials = $this->getInitials($attendee->name);
                                        $avatarColors = [
                                            '#0284c7', '#059669', '#7c3aed', '#d97706', '#db2777', '#0d9488', '#4f46e5', '#ea580c'
                                        ];
                                        $aColor = $avatarColors[$attendee->id % count($avatarColors)];
                                        $filterText = strtolower($attendee->name . ' ' . $attendee->email . ' ' . ($attendee->project_role_label ?? ''));
                                    @endphp
                                    <div x-show="!editAttendeeSearch || {{ json_encode($filterText) }}.includes(editAttendeeSearch.toLowerCase())"
                                         wire:click="toggleEditAttendee({{ $attendee->id }})"
                                         class="px-3.5 py-2 transition-colors cursor-pointer select-none flex items-center justify-between gap-3 {{ $isSelected ? 'bg-slate-50/70 hover:bg-slate-100/60' : 'hover:bg-slate-50 bg-white' }}">
                                        <!-- Avatar & Info -->
                                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                            <div class="w-7 h-7 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0 shadow-2xs" style="background:{{ $aColor }};">
                                                {{ $initials }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-semibold text-slate-900 text-xs truncate">{{ $attendee->name }}</span>
                                                    @if(!empty($attendee->project_role_label))
                                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-medium bg-slate-100 text-slate-500">{{ $attendee->project_role_label }}</span>
                                                    @endif
                                                    @if($this->userHasAzureAccount($attendee))
                                                        <svg class="w-2.5 h-2.5 shrink-0" viewBox="0 0 23 23" fill="none" title="Microsoft 365 Connected"><rect x="1" y="1" width="10" height="10" fill="#f25022"/><rect x="12" y="1" width="10" height="10" fill="#7fba00"/><rect x="1" y="12" width="10" height="10" fill="#00a4ef"/><rect x="12" y="12" width="10" height="10" fill="#ffb900"/></svg>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] text-slate-400 truncate block">{{ $attendee->email }}</span>
                                            </div>
                                        </div>
                                        <!-- Checkbox -->
                                        <div class="shrink-0">
                                            @if($isSelected)
                                                <div class="w-4.5 h-4.5 rounded-full bg-[#c3122e] text-white flex items-center justify-center shadow-xs">
                                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            @else
                                                <div class="w-4.5 h-4.5 rounded-full border border-slate-300 bg-white hover:border-slate-400 transition-colors"></div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-center text-slate-400 text-xs font-medium">
                                        No team members found for this project.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Microsoft 365 Integration Note -->
                        <div class="flex items-center gap-1.5 pt-1 text-[11px] text-slate-400">
                            <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 23 23" fill="none"><rect x="1" y="1" width="10" height="10" fill="#f25022"/><rect x="12" y="1" width="10" height="10" fill="#7fba00"/><rect x="1" y="12" width="10" height="10" fill="#00a4ef"/><rect x="12" y="12" width="10" height="10" fill="#ffb900"/></svg>
                            <span>Updates will automatically synchronize across all member Outlook calendars.</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3.5 bg-white border-t border-slate-100 flex items-center justify-between shrink-0">
                <button type="button" @click="open = false; $wire.closeEditEventModal()" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors cursor-pointer">
                    Cancel
                </button>

                <button type="button" wire:click="updateEvent" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#c3122e] hover:bg-[#a50f26] shadow-sm shadow-[#c3122e]/20 flex items-center gap-2 cursor-pointer transition-all disabled:opacity-60">
                    <span wire:loading.remove wire:target="updateEvent" class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </span>
                    <span wire:loading wire:target="updateEvent" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Saving Changes...
                    </span>
                </button>
            </div>

        </div>
    </div>

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
