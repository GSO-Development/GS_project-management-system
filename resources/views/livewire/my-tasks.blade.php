<div class="space-y-5 pt-1">
    <!-- Style Override for Custom Select Arrow & Modern Micro-Interactions -->
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 11px;
            padding-right: 34px !important;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER & CONTROLS HUB
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-800 shadow-xl p-4 sm:p-6 lg:p-7 text-white mb-5" style="background: linear-gradient(135deg, #18060c 0%, #2e0915 50%, #18060c 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-full sm:w-2/3 lg:w-1/2 pointer-events-none opacity-20 overflow-hidden flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 60%); mask-image: linear-gradient(to right, transparent 0%, black 60%);">
        </div>

        <!-- Gold Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-25 hidden md:block">
            <svg viewBox="0 0 1200 300" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 400 0 C 520 120 580 200 750 300" stroke="#f59e0b" stroke-width="2" opacity="0.7" />
                <path d="M 420 0 C 540 120 600 200 770 300" stroke="#c3122e" stroke-width="1.5" opacity="0.4" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            <!-- Left Side: 3D Crimson App Icon + Title + Actionable Pill + Meta -->
            <div class="flex items-center gap-3.5 sm:gap-4 min-w-0">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-xl flex-shrink-0 border border-white/25 ring-4 ring-rose-500/20 flex items-center justify-center p-2 sm:p-2.5" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 sm:gap-2.5 flex-wrap">
                        <h1 class="text-lg sm:text-2xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            My Tasks Workspace
                        </h1>
                        @if(($inProgressCount + $dueTodayCount + $overdueCount) > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.4);">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                                <span>{{ $inProgressCount + $dueTodayCount + $overdueCount }} Actionable</span>
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-semibold text-slate-300 mt-1 flex-wrap">
                        <span class="text-rose-200 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('D, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal hidden sm:inline">|</span>
                        <span class="text-slate-300 font-medium hidden sm:inline">Personal Deliverables &amp; Execution Horizon</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Luxury Controls & Floating Progress Ring Card -->
            <div class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0 self-stretch sm:self-auto justify-between sm:justify-end flex-wrap">
                <!-- ⚡ View Switcher: Kanban vs Table -->
                <div class="inline-flex p-1 rounded-xl border border-white/20 shadow-xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button 
                        wire:click="setViewMode('kanban')" 
                        type="button" 
                        class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white text-[#c3122e] shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        <span>Kanban</span>
                    </button>
                    <button 
                        wire:click="setViewMode('table')" 
                        type="button" 
                        class="px-3 sm:px-3.5 py-1.5 rounded-lg text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-slate-900 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Table</span>
                    </button>
                </div>

                <!-- Floating Tasks Progress Ring Card -->
                @php
                    $completionPct = $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0;
                @endphp
                <div class="hidden md:flex items-center gap-3 bg-white rounded-xl px-3.5 py-1.5 shadow-xl text-slate-900 border border-white/60 flex-shrink-0">
                    <div class="relative w-9 h-9 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-9 h-9 -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-[#c3122e] transition-all duration-700" stroke-width="3.5" stroke-dasharray="{{ $completionPct }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-[10px] font-black text-slate-900 font-mono">{{ $completionPct }}%</span>
                    </div>
                    <div class="text-left">
                        <div class="text-[9px] font-black uppercase tracking-wider text-slate-400">Done</div>
                        <div class="text-xs font-black text-slate-900 font-mono">{{ $completedCount }}/{{ $totalCount }}</div>
                    </div>
                </div>


                @if(auth()->user()->hasRole('super_admin'))
                    <button wire:click="$set('saView', '{{ $saView === 'team' ? 'mine' : 'team' }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-black border transition-all duration-200 cursor-pointer shadow-lg active:scale-95 {{ $saView === 'team' ? 'border-[#c3122e] bg-[#c3122e] text-white' : 'border-white/20 text-white hover:bg-white/20' }}"
                            style="background: {{ $saView === 'team' ? '#c3122e' : 'rgba(0,0,0,0.45)' }};">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ $saView === 'team' ? 'My View' : 'Team Monitor' }}</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. SLEEK EXECUTIVE KPI COCKPIT (6 INTERACTIVE METRIC TILES)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $metricData = [
                [
                    'label' => 'Total Tasks',
                    'val' => $totalCount,
                    'icon' => '<svg class="w-4 h-4 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                    'iconBg' => 'bg-slate-100 text-slate-700',
                    'activeRing' => 'ring-2 ring-slate-800 border-slate-800 bg-slate-50/50',
                    'action' => '$set("statusFilter", "all"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'all' && $dueDateFilter === 'all'
                ],
                [
                    'label' => 'In Progress',
                    'val' => $inProgressCount,
                    'icon' => '<svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z"/></svg>',
                    'iconBg' => 'bg-blue-50 text-blue-600',
                    'activeRing' => 'ring-2 ring-blue-500 border-blue-500 bg-blue-50/20',
                    'action' => '$set("statusFilter", "in_progress"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'in_progress'
                ],
                [
                    'label' => 'Due Today',
                    'val' => $dueTodayCount,
                    'icon' => '<svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'iconBg' => 'bg-amber-50 text-amber-600',
                    'activeRing' => 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/20',
                    'action' => '$set("dueDateFilter", "today"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'today'
                ],
                [
                    'label' => 'Overdue',
                    'val' => $overdueCount,
                    'icon' => '<svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    'iconBg' => 'bg-rose-50 text-rose-600',
                    'activeRing' => 'ring-2 ring-rose-500 border-rose-500 bg-rose-50/20',
                    'action' => '$set("dueDateFilter", "overdue"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'overdue'
                ],
                [
                    'label' => 'Completed',
                    'val' => $completedCount,
                    'icon' => '<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'iconBg' => 'bg-emerald-50 text-emerald-600',
                    'activeRing' => 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20',
                    'action' => '$set("statusFilter", "completed"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'completed'
                ],
                [
                    'label' => 'Blocked',
                    'val' => $blockedCount,
                    'icon' => '<svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                    'iconBg' => 'bg-red-50 text-red-600',
                    'activeRing' => 'ring-2 ring-red-600 border-red-600 bg-red-50/20',
                    'action' => '$set("statusFilter", "blocked"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'blocked'
                ],
            ];
        @endphp

        @foreach($metricData as $md)
            <button type="button"
                    wire:click="{!! $md['action'] !!}"
                    class="group relative w-full text-left bg-white border border-slate-200/90 rounded-2xl p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $md['active'] ? $md['activeRing'] . ' shadow-xs' : 'shadow-2xs hover:border-slate-300' }}">
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-600 transition-colors">{{ $md['label'] }}</span>
                    <div class="w-7 h-7 rounded-xl {{ $md['iconBg'] }} flex items-center justify-center flex-shrink-0 shadow-2xs">
                        {!! $md['icon'] !!}
                    </div>
                </div>
                <div class="flex items-baseline justify-between gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight leading-none block font-mono">{{ $md['val'] }}</span>
                    @if($md['val'] > 0 && $md['label'] === 'Overdue')
                        <span class="text-[9px] font-extrabold text-rose-600 bg-rose-50 px-1.5 py-0.2 rounded-md">Action Req</span>
                    @elseif($md['val'] > 0 && $md['label'] === 'Due Today')
                        <span class="text-[9px] font-extrabold text-amber-700 bg-amber-50 px-1.5 py-0.2 rounded-md">Today</span>
                    @endif
                </div>
            </button>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CONSOLIDATED SMART SEARCH & FILTER CONTROLS BAR
         ═══════════════════════════════════════════════════════════════ -->
    @if($saView === 'mine' || !auth()->user()->hasRole('super_admin'))
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Search Input -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks, WBS, projects..."
                           class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all outline-none placeholder:text-slate-400">
                    @if($search)
                        <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            ✕
                        </button>
                    @endif
                </div>

                <!-- Project Selector (Unified Single List of Assigned Projects) -->
                <div>
                    <select wire:model.live="projectFilter" class="w-full px-3 py-2 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all outline-none cursor-pointer custom-select">
                        <option value="all">📁 All Assigned Projects</option>
                        @foreach($myProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->code }} — {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Selector -->
                <div>
                    <select wire:model.live="statusFilter" class="w-full px-3 py-2 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all outline-none cursor-pointer custom-select">
                        <option value="incomplete">⏳ Incomplete Tasks (Default)</option>
                        <option value="all">📋 All Statuses</option>
                        @foreach(\App\Enums\WbsStatus::cases() as $st)<option value="{{ $st->value }}">{{ $st->label() }}</option>@endforeach
                    </select>
                </div>

                <!-- Priority Selector -->
                <div>
                    <select wire:model.live="priorityFilter" class="w-full px-3 py-2 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all outline-none cursor-pointer custom-select">
                        <option value="all">🚩 All Priorities</option>
                        @foreach(\App\Enums\Priority::cases() as $pr)<option value="{{ $pr->value }}">{{ $pr->label() }} Priority</option>@endforeach
                    </select>
                </div>
            </div>

            <!-- Filter Status Strip & Quick Clear Action -->
            @if($search || $projectFilter !== 'all' || $priorityFilter !== 'all' || $statusFilter !== 'incomplete' || $dueDateFilter !== 'all')
                <div class="flex items-center justify-between gap-3 pt-2.5 border-t border-slate-100 text-xs flex-wrap">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Active Filters:</span>
                        @if($search)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold">
                                Search: "{{ $search }}"
                            </span>
                        @endif
                        @if($projectFilter !== 'all')
                            @php
                                $selProj = $myProjects->firstWhere('id', $projectFilter);
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-rose-50 text-[#c3122e] border border-rose-200 text-[10px] font-bold">
                                Project: {{ $selProj ? $selProj->code : $projectFilter }}
                            </span>
                        @endif
                        @if($dueDateFilter !== 'all')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold">
                                Due: {{ ucfirst(str_replace('_', ' ', $dueDateFilter)) }}
                            </span>
                        @endif
                        @if($statusFilter !== 'incomplete')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-bold">
                                Status: {{ ucfirst(str_replace('_', ' ', $statusFilter)) }}
                            </span>
                        @endif
                        @if($priorityFilter !== 'all')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-orange-50 text-orange-700 border border-orange-200 text-[10px] font-bold">
                                Priority: {{ ucfirst($priorityFilter) }}
                            </span>
                        @endif
                    </div>

                    <button type="button" wire:click="clearFilters" class="px-2.5 py-1 text-[11px] font-black bg-rose-50 text-[#c3122e] hover:bg-rose-100 rounded-lg border border-rose-200 transition-colors flex items-center gap-1 cursor-pointer">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Clear All Filters</span>
                    </button>
                </div>
            @endif
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         4. TEAM MONITOR CONSOLE (SUPER ADMIN ONLY)
         ═══════════════════════════════════════════════════════════════ -->
    @if(auth()->user()->hasRole('super_admin') && $saView === 'team')
        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5 flex-wrap gap-2">
                <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    <span>Enterprise Blocked &amp; Delayed Tasks Watchlist</span>
                </h3>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    {{ $loggedIssues->count() }} Issues Requiring Action
                </span>
            </div>

            <div class="space-y-3">
                @forelse($loggedIssues as $t)
                    <div class="p-4 rounded-xl border border-rose-100 bg-rose-50/20 hover:bg-rose-50/40 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-50 text-[#c3122e] border border-rose-200">{{ $t->wbs_code }}</span>
                                <span class="font-bold text-slate-900 text-xs">{{ $t->title }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-600">{{ $t->project->name ?? 'Project' }}</span>
                            </div>
                            <p class="text-xs text-rose-700 font-medium italic">"{{ $t->delay_reason }}"</p>
                        </div>
                        <div class="text-right flex-shrink-0 text-xs font-bold text-slate-500">
                            <div>Assigned: <strong class="text-slate-800">{{ $t->assignedUser->name ?? 'Unassigned' }}</strong></div>
                            <div class="text-[10px] text-slate-400 font-normal mt-0.5">Reported {{ $t->delay_reason_at?->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-4 text-center font-medium">No delayed tasks or blockers reported across the enterprise.</p>
                @endforelse
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. KANBAN BOARD & TABLE VIEW SWITCHER CONTENT
         ═══════════════════════════════════════════════════════════════ -->
    <div class="space-y-8 animate-in fade-in duration-200">
        
        <!-- ═══════════════════════════════════════════════════════════════
             5A. KANBAN BOARD VIEW
             ═══════════════════════════════════════════════════════════════ -->
        @if($viewMode === 'all' || $viewMode === 'kanban')
            <div class="space-y-4">
                <!-- Kanban Perspective Mode Switcher -->
                <div class="flex items-center justify-between gap-3 flex-wrap">
                    <div class="inline-flex p-1 rounded-2xl bg-slate-100/90 border border-slate-200/70 shadow-inner">
                        <button 
                            wire:click="setKanbanMode('time')" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $kanbanMode === 'time' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80 scale-[1.02]' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <span>📅 Schedule Horizon</span>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-md {{ $kanbanMode === 'time' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-600' }} font-mono">6 Stages</span>
                        </button>
                        <button 
                            wire:click="setKanbanMode('status')" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $kanbanMode === 'status' ? 'bg-white text-[#c3122e] shadow-xs ring-1 ring-rose-200/80 scale-[1.02]' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <span>⚡ Workflow Status</span>
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-md {{ $kanbanMode === 'status' ? 'bg-[#c3122e] text-white' : 'bg-slate-200 text-slate-600' }} font-mono">5 Columns</span>
                        </button>
                    </div>

                    <div class="text-xs font-bold text-slate-400">
                        {{ $kanbanMode === 'time' ? 'Sorted by chronological urgency' : 'Sorted by delivery workflow status' }}
                    </div>
                </div>

                <!-- 1. TIME-HORIZON KANBAN BOARD (Due, Today, Tomorrow, Next Week, Later, Completed) -->
                @if($kanbanMode === 'time')
                    <div class="flex gap-4 items-start overflow-x-auto pb-4 pt-1 scrollbar-thin">
                        <!-- 1. Overdue & Due Tasks -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Due / Overdue',
                            'icon' => '🚨',
                            'statusKey' => 'overdue',
                            'badge' => 'bg-rose-100 text-rose-800',
                            'accentColor' => '#e11d48',
                            'tasks' => $kanbanOverdue,
                            'emptyText' => 'No overdue or blocked tasks! Clean delivery.',
                            'colType' => 'overdue'
                        ])

                        <!-- 2. Today's Tasks -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Today',
                            'icon' => '⚡',
                            'statusKey' => 'today',
                            'badge' => 'bg-amber-100 text-amber-900',
                            'accentColor' => '#d97706',
                            'tasks' => $kanbanToday,
                            'emptyText' => 'No deliverables scheduled for today.',
                            'colType' => 'today'
                        ])

                        <!-- 3. Tomorrow's Tasks -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Tomorrow',
                            'icon' => '🌅',
                            'statusKey' => 'tomorrow',
                            'badge' => 'bg-indigo-100 text-indigo-800',
                            'accentColor' => '#6366f1',
                            'tasks' => $kanbanTomorrow,
                            'emptyText' => 'No deliverables due tomorrow.',
                            'colType' => 'tomorrow'
                        ])

                        <!-- 4. Next Week's Tasks -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Next 7 Days',
                            'icon' => '📅',
                            'statusKey' => 'next_week',
                            'badge' => 'bg-blue-100 text-blue-800',
                            'accentColor' => '#2563eb',
                            'tasks' => $kanbanNextWeek,
                            'emptyText' => 'No tasks in next 7 days.',
                            'colType' => 'next_week'
                        ])

                        <!-- 5. Later & Backlog -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Later Horizon',
                            'icon' => '🗓️',
                            'statusKey' => 'later',
                            'badge' => 'bg-slate-200 text-slate-700',
                            'accentColor' => '#64748b',
                            'tasks' => $kanbanLater,
                            'emptyText' => 'No future backlog items.',
                            'colType' => 'later'
                        ])

                        <!-- 6. Completed Tasks -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Completed',
                            'icon' => '✅',
                            'statusKey' => 'completed',
                            'badge' => 'bg-emerald-100 text-emerald-800',
                            'accentColor' => '#10b981',
                            'tasks' => $kanbanCompleted,
                            'emptyText' => 'Completed tasks appear here.',
                            'colType' => 'completed'
                        ])
                    </div>
                @else
                    <!-- 2. WORKFLOW STATUS KANBAN BOARD (To Do, In Progress, In Review, Completed, Blocked) -->
                    <div class="flex gap-4 items-start overflow-x-auto pb-4 pt-1 scrollbar-thin">
                        <!-- 1. To Do -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'To Do',
                            'icon' => '📝',
                            'statusKey' => 'not_started',
                            'badge' => 'bg-slate-100 text-slate-700',
                            'accentColor' => '#64748b',
                            'tasks' => $kanbanToDo,
                            'emptyText' => 'No tasks to do.',
                            'colType' => 'todo'
                        ])

                        <!-- 2. In Progress -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'In Progress',
                            'icon' => '🚀',
                            'statusKey' => 'in_progress',
                            'badge' => 'bg-blue-100 text-blue-700',
                            'accentColor' => '#3b82f6',
                            'tasks' => $kanbanInProgress,
                            'emptyText' => 'No active tasks in progress.',
                            'colType' => 'in_progress'
                        ])

                        <!-- 3. In Review -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'In Review',
                            'icon' => '🔍',
                            'statusKey' => 'under_review',
                            'badge' => 'bg-purple-100 text-purple-700',
                            'accentColor' => '#a855f7',
                            'tasks' => $kanbanInReview,
                            'emptyText' => 'No tasks under review.',
                            'colType' => 'in_review'
                        ])

                        <!-- 4. Completed -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Completed',
                            'icon' => '✅',
                            'statusKey' => 'completed',
                            'badge' => 'bg-emerald-100 text-emerald-700',
                            'accentColor' => '#10b981',
                            'tasks' => $kanbanCompleted,
                            'emptyText' => 'No completed tasks yet.',
                            'colType' => 'completed'
                        ])

                        <!-- 5. Blocked -->
                        @include('livewire.partials.my-tasks-kanban-col', [
                            'title' => 'Blocked',
                            'icon' => '🚫',
                            'statusKey' => 'blocked',
                            'badge' => 'bg-rose-100 text-rose-700',
                            'accentColor' => '#f43f5e',
                            'tasks' => $kanbanBlocked,
                            'emptyText' => 'No blocked tasks! Clear path.',
                            'colType' => 'blocked'
                        ])
                    </div>
                @endif
            </div>
        @endif

        <!-- ═══════════════════════════════════════════════════════════════
             5B. ALL TASKS TABLE VIEW (Executive Data Table)
             ═══════════════════════════════════════════════════════════════ -->
        @if($viewMode === 'all' || $viewMode === 'table')
            <div class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        All Assigned Tasks
                    </h3>
                    <span class="text-xs font-bold text-slate-500 font-mono">
                        Showing {{ $tasks->total() }} Total Tasks
                    </span>
                </div>

                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead>
                                <tr class="border-b border-slate-200/80 bg-slate-50/70 text-xs font-bold text-slate-600">
                                    <th class="py-3.5 pl-5 pr-2 w-10">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectAll" 
                                            class="rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e] cursor-pointer"
                                        >
                                    </th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">WBS &amp; Task Name</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Project</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Status</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Priority</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Due Date</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Assignee</th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Progress</th>
                                    <th class="py-3.5 pl-4 pr-5 text-xs font-bold text-slate-700 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs bg-white">
                                @forelse($tasks as $t)
                                    @php
                                        $user = auth()->user();
                                        $assignee = $t->assignedUser ?? $user;
                                        $assigneeName = $assignee->name ?? 'User';
                                        $nameParts = explode(' ', trim($assigneeName));
                                        $shortName = count($nameParts) >= 2 
                                            ? $nameParts[0] . ' ' . substr($nameParts[count($nameParts) - 1], 0, 1) . '.'
                                            : $assigneeName;
                                        $initials = count($nameParts) >= 2 
                                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1))
                                            : strtoupper(substr($assigneeName, 0, 2));

                                        // Status Badge styling
                                        $statusLabel = match($t->status->value) {
                                            'not_started', 'backlog' => 'To Do',
                                            'in_progress', 'on_hold' => 'In Progress',
                                            'under_review' => 'In Review',
                                            'completed' => 'Completed',
                                            'blocked' => 'Blocked',
                                            default => 'To Do',
                                        };
                                        $statusBadge = match($t->status->value) {
                                            'not_started', 'backlog' => 'background-color: #f1f5f9; color: #475569; border-color: #e2e8f0;',
                                            'in_progress', 'on_hold' => 'background-color: #eff6ff; color: #2563eb; border-color: #bfdbfe;',
                                            'under_review' => 'background-color: #faf5ff; color: #9333ea; border-color: #e9d5ff;',
                                            'completed' => 'background-color: #ecfdf5; color: #059669; border-color: #a7f3d0;',
                                            'blocked' => 'background-color: #fff1f2; color: #e11d48; border-color: #fecdd3;',
                                            default => 'background-color: #f1f5f9; color: #475569; border-color: #e2e8f0;',
                                        };

                                        // Priority Badge styling
                                        $priStr = ucfirst($t->priority->value ?? 'medium');
                                        $priBadge = match(strtolower($priStr)) {
                                            'critical', 'high' => 'background-color: #fff1f2; color: #e11d48; border-color: #fecdd3;',
                                            'low' => 'background-color: #ecfdf5; color: #059669; border-color: #a7f3d0;',
                                            default => 'background-color: #fffbeb; color: #d97706; border-color: #fde68a;',
                                        };

                                        // Progress bar color
                                        $barColor = match($t->status->value) {
                                            'completed' => 'bg-emerald-500',
                                            'in_progress' => 'bg-blue-600',
                                            'under_review' => 'bg-purple-600',
                                            'blocked' => 'bg-rose-500',
                                            default => 'bg-slate-300',
                                        };

                                        $isOverdue = $t->end_date && $t->end_date->isPast() && $t->status->value !== 'completed';
                                    @endphp

                                    <tr class="hover:bg-slate-50/70 transition-colors group">
                                        <!-- Checkbox -->
                                        <td class="py-3.5 pl-5 pr-2">
                                            <input 
                                                type="checkbox" 
                                                wire:model="selectedTaskIds" 
                                                value="{{ $t->id }}" 
                                                class="rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e] cursor-pointer"
                                            >
                                        </td>

                                        <!-- WBS & Task Name -->
                                        <td class="py-3.5 px-4 font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-mono text-[10px] font-bold px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $t->wbs_code ?? 'TASK' }}
                                                </span>
                                                @if($t->is_milestone)
                                                    <span style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                                        🏁 Milestone
                                                    </span>
                                                @endif
                                                <span class="text-xs font-bold text-slate-800">{{ $t->title }}</span>
                                            </div>
                                        </td>

                                        <!-- Project -->
                                        <td class="py-3.5 px-4 text-slate-500 font-medium truncate max-w-[200px]">
                                            <div class="flex items-center gap-1.5">
                                                <span class="px-1.5 py-0.2 rounded font-mono text-[9px] font-bold bg-slate-100 text-slate-700">{{ $t->project->code ?? 'PRJ' }}</span>
                                                <span class="truncate">{{ $t->project->name ?? 'General Delivery' }}</span>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span style="{{ $statusBadge }}; font-size: 9.5px; font-weight: 700; padding: 1.5px 6px; border-radius: 4px; border-width: 1px; display: inline-block;">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <!-- Priority -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span style="{{ $priBadge }}; font-size: 9.5px; font-weight: 700; padding: 1.5px 6px; border-radius: 4px; border-width: 1px; display: inline-block;">
                                                {{ $priStr }}
                                            </span>
                                        </td>

                                        <!-- Due Date -->
                                        <td class="py-3.5 px-4 text-slate-600 font-semibold whitespace-nowrap" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                            <div class="flex items-center gap-1.5 text-xs {{ $isOverdue ? 'text-rose-600 font-bold' : '' }}">
                                                <svg class="w-3.5 h-3.5 {{ $isOverdue ? 'text-rose-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $t->end_date ? $t->end_date->format('M d, Y') : ($t->start_date ? $t->start_date->format('M d, Y') : 'No Date') }}</span>
                                            </div>
                                        </td>

                                        <!-- Assignee -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full bg-slate-900 text-white text-[8px] font-black flex items-center justify-center shadow-2xs flex-shrink-0">
                                                    {{ $initials }}
                                                </div>
                                                <span class="text-xs font-semibold text-slate-700">{{ $shortName }}</span>
                                            </div>
                                        </td>

                                        <!-- Progress -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2.5">
                                                <span class="text-[11px] font-bold text-slate-700 font-mono w-8">{{ $t->progress }}%</span>
                                                <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden flex-shrink-0">
                                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ max(4, $t->progress) }}%;"></div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Actions Dropdown -->
                                        <td class="py-3.5 pl-4 pr-5 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <a href="{{ route('daily-updates.index', ['project' => $t->project_id, 'task' => $t->id, 'create' => 1]) }}" class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-all flex items-center gap-1 no-underline" title="Log Daily Update">
                                                    <span>📝</span>
                                                    <span>Log</span>
                                                </a>

                                                <div x-data="{ openMenu: false }" class="relative inline-block text-left">
                                                    <button 
                                                        @click="openMenu = !openMenu" 
                                                        type="button" 
                                                        class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 cursor-pointer"
                                                    >
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                        </svg>
                                                    </button>
                                                    <div 
                                                        x-show="openMenu" 
                                                        @click.away="openMenu = false" 
                                                        class="absolute right-0 top-full mt-1 w-44 bg-white rounded-2xl shadow-xl border border-slate-200 p-1.5 z-40 space-y-1 animate-in fade-in duration-150"
                                                        style="display:none"
                                                    >
                                                        @if($t->status->value !== 'completed')
                                                            <button wire:click="markTaskCompleted({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50 rounded-xl flex items-center gap-2 cursor-pointer">
                                                                <span>✓</span> Mark Completed
                                                            </button>
                                                        @endif
                                                        <button wire:click="openSubTaskModal({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-50 rounded-xl flex items-center gap-2 cursor-pointer">
                                                            <span>➕</span> Add Sub-task
                                                        </button>
                                                        <button wire:click="openDelayReasonModal({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-50 rounded-xl flex items-center gap-2 cursor-pointer">
                                                            <span>⚠️</span> Report Blocker
                                                        </button>
                                                        <button wire:click="deleteTask({{ $t->id }}); openMenu = false" wire:confirm="Are you sure you want to delete this task?" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl flex items-center gap-2 cursor-pointer">
                                                            <span>🗑️</span> Delete Task
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-12 text-slate-400 text-xs font-medium">
                                            No tasks found matching your filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($tasks->hasPages())
                        <div class="p-4 border-t border-slate-200/80 bg-slate-50/50">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         6. CREATE TASK MODAL (100% Centered Executive Overlay)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); margin: 0; box-sizing: border-box;">
            <div style="position: relative; width: 100%; max-width: 520px; max-height: calc(100vh - 32px); margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4); border: 1px solid #cbd5e1; overflow: hidden;">
                
                <!-- Modal Header -->
                <div style="padding: 18px 24px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-shrink: 0; border-bottom: 3px solid #c3122e;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(195,18,46,0.3);">
                            ✨
                        </div>
                        <div>
                            <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #ffffff;">Add New Task</h3>
                            <p style="margin: 2px 0 0 0; font-size: 11px; color: #94a3b8;">Create deliverable in your assigned project</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showCreateTaskModal', false)" 
                        type="button" 
                        style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.08); color: #cbd5e1; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;"
                    >
                        ✕
                    </button>
                </div>

                <!-- Form Content -->
                <form wire:submit="saveNewTask" style="padding: 22px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; background: #ffffff;">
                    <!-- Project Selector -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Project *</label>
                        <select wire:model="createTaskProjectId" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1; background: #f8fafc; outline: none;" required>
                            <option value="">-- Select Project --</option>
                            @foreach($myProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} — {{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('createTaskProjectId') <span style="font-size: 10px; color: #e11d48; font-weight: bold; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Task Title -->
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Task Name *</label>
                        <input type="text" wire:model="createTaskTitle" placeholder="e.g. Design Architecture Wireframes" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;" required>
                        @error('createTaskTitle') <span style="font-size: 10px; color: #e11d48; font-weight: bold; display: block; margin-top: 3px;">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Start Date</label>
                            <input type="date" wire:model="createTaskStartDate" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Due Date</label>
                            <input type="date" wire:model="createTaskEndDate" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <!-- Priority & Status Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Priority</label>
                            <select wire:model="createTaskPriority" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1; outline: none; cursor: pointer;">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Status</label>
                            <select wire:model="createTaskStatus" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1; outline: none; cursor: pointer;">
                                <option value="not_started">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="under_review">In Review</option>
                                <option value="completed">Completed</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid #f1f5f9; margin-top: 6px;">
                        <button wire:click="$set('showCreateTaskModal', false)" type="button" style="padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 8px 22px; border-radius: 10px; font-size: 12px; font-weight: 800; background: #c3122e; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(195,18,46,0.25);">
                            + Save Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         7. DELAY / ISSUE REASON MODAL (100% Centered)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showDelayReasonModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); margin: 0; box-sizing: border-box;">
            <div style="position: relative; width: 100%; max-width: 480px; margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4); border: 1px solid #cbd5e1; overflow: hidden;">
                
                <div style="padding: 18px 22px; background: #fff1f2; border-bottom: 1px solid #fecdd3; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #be123c; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        ⚠️
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 15px; font-weight: 800; color: #9f1239;">Report Task Blocker / Issue</h3>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #be123c;">Explain why this deliverable is delayed or blocked</p>
                    </div>
                </div>

                <div style="padding: 20px 22px; display: flex; flex-direction: column; gap: 14px;">
                    <textarea wire:model="delayReasonText" rows="4" placeholder="Describe the blocker (e.g. Waiting on client credentials, vendor delayed delivery)..." style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #cbd5e1; font-size: 12px; font-family: inherit; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
                    @error('delayReasonText') <span style="font-size: 10.5px; color: #e11d48; font-weight: bold;">{{ $message }}</span> @enderror

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <button wire:click="$set('showDelayReasonModal', false)" type="button" style="padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer;">
                            Cancel
                        </button>
                        <button wire:click="submitDelayReason" type="button" style="padding: 8px 20px; border-radius: 10px; font-size: 12px; font-weight: 800; background: #be123c; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(190,18,60,0.25);">
                            Submit Issue Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8. SUB-TASK CREATION MODAL (100% Centered)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showSubTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); margin: 0; box-sizing: border-box;">
            <div style="position: relative; width: 100%; max-width: 500px; margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4); border: 1px solid #cbd5e1; overflow: hidden;">
                
                <div style="padding: 18px 22px; background: #eff6ff; border-bottom: 1px solid #bfdbfe; display: flex; align-items: center; gap: 12px;">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: #2563eb; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        📋
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 15px; font-weight: 800; color: #1e40af;">Add Sub-Task Deliverable</h3>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #3b82f6;">Break down your task into smaller deliverables</p>
                    </div>
                </div>

                <form wire:submit="createSubTask" style="padding: 20px 22px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Sub-Task Title *</label>
                        <input type="text" wire:model="subTaskTitle" placeholder="e.g. Conduct user feedback session" style="width: 100%; height: 38px; padding: 0 12px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;" required>
                        @error('subTaskTitle') <span style="font-size: 10.5px; color: #e11d48; font-weight: bold;">{{ $message }}</span> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Target End Date</label>
                            <input type="date" wire:model="subTaskEndDate" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Priority</label>
                            <select wire:model="subTaskPriority" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 700; border: 1px solid #cbd5e1; outline: none; cursor: pointer;">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 5px;">Est. Hours</label>
                            <input type="number" step="0.5" wire:model="subTaskEstimatedHours" placeholder="e.g. 4.0" style="width: 100%; height: 38px; padding: 0 10px; border-radius: 10px; font-size: 12px; font-weight: 600; border: 1px solid #cbd5e1; outline: none; box-sizing: border-box;">
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        <button wire:click="$set('showSubTaskModal', false)" type="button" style="padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 8px 20px; border-radius: 10px; font-size: 12px; font-weight: 800; background: #2563eb; color: #ffffff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                            + Create Sub-Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         9. INTERACTIVE TASK DETAIL MODAL (100% Centered & Responsive)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showTaskDetailModal && $detailTask)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); margin: 0; box-sizing: border-box;">
            <div style="position: relative; width: 100%; max-width: 580px; max-height: calc(100vh - 32px); margin: auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4); border: 1px solid #cbd5e1; overflow: hidden;">
                
                <!-- Modal Header -->
                <div style="padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e2e8f0; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-shrink: 0;">
                    <div style="min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="padding: 2px 7px; border-radius: 6px; font-family: monospace; font-size: 10.5px; font-weight: 800; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                {{ $detailTask->wbs_code ?? 'TASK' }}
                            </span>
                            <span style="font-size: 11.5px; font-weight: 700; color: #64748b;">
                                {{ $detailTask->project->name ?? 'General Delivery' }}
                            </span>
                        </div>
                        <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                            {{ $detailTask->title }}
                        </h3>
                    </div>
                    <button wire:click="$set('showTaskDetailModal', false)" type="button" style="width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold;">
                        ✕
                    </button>
                </div>

                <!-- Scrollable Body -->
                <div style="padding: 20px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: #f8fafc;">
                    <!-- Key Badges Strip -->
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                        <div style="padding: 10px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                            <span style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; display: block;">Status</span>
                            <span style="font-size: 11.5px; font-weight: 800; color: #0f172a; display: block; margin-top: 2px; text-transform: capitalize;">{{ str_replace('_', ' ', $detailTask->status->value) }}</span>
                        </div>
                        <div style="padding: 10px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                            <span style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; display: block;">Priority</span>
                            <span style="font-size: 11.5px; font-weight: 800; color: #0f172a; display: block; margin-top: 2px; text-transform: capitalize;">{{ $detailTask->priority->value ?? 'Medium' }}</span>
                        </div>
                        <div style="padding: 10px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                            <span style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; display: block;">Due Date</span>
                            <span style="font-size: 11px; font-weight: 800; color: #0f172a; font-family: monospace; display: block; margin-top: 2px;">{{ $detailTask->end_date ? $detailTask->end_date->format('M d, Y') : '—' }}</span>
                        </div>
                        <div style="padding: 10px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                            <span style="font-size: 9.5px; font-weight: 700; text-transform: uppercase; color: #64748b; display: block;">Assignee</span>
                            <span style="font-size: 11.5px; font-weight: 800; color: #0f172a; display: block; margin-top: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $detailTask->assignedUser->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <!-- Live Progress Slider -->
                    <div style="background: #ffffff; padding: 14px 16px; border-radius: 14px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                            <span>Completion Progress</span>
                            <span style="font-size: 13px; color: #c3122e; font-family: monospace;">{{ $detailTask->progress }}%</span>
                        </div>
                        <input 
                            type="range" 
                            min="0" 
                            max="100" 
                            step="5" 
                            value="{{ $detailTask->progress }}" 
                            wire:change="updateProgress({{ $detailTask->id }}, $event.target.value)" 
                            style="width: 100%; cursor: pointer; accent-color: #c3122e;"
                        >
                    </div>

                    <!-- Sub-tasks Checklist -->
                    <div style="background: #ffffff; padding: 16px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 10px;">
                        <h4 style="margin: 0; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; letter-spacing: 0.05em;">
                            Sub-tasks &amp; Checklist ({{ $detailTask->children->where('status.value', 'completed')->count() }}/{{ $detailTask->children->count() }})
                        </h4>

                        <!-- Quick Add Subtask Form -->
                        <form wire:submit.prevent="addQuickSubtask" style="display: flex; gap: 8px;">
                            <input 
                                type="text" 
                                wire:model="newSubtaskTitle" 
                                placeholder="+ Add subtask or checklist item..." 
                                style="flex: 1; height: 34px; padding: 0 10px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 11.5px; outline: none;"
                            >
                            <button type="submit" style="padding: 0 14px; border-radius: 8px; font-size: 11.5px; font-weight: 800; background: #0f172a; color: #ffffff; border: none; cursor: pointer;">
                                Add
                            </button>
                        </form>

                        <!-- Subtask list -->
                        <div style="display: flex; flex-direction: column; gap: 6px; max-height: 180px; overflow-y: auto;">
                            @forelse($detailTask->children as $sub)
                                <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; min-width: 0; flex: 1;">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleSubtask({{ $sub->id }})" 
                                            {{ $sub->status->value === 'completed' ? 'checked' : '' }} 
                                            style="cursor: pointer;"
                                        >
                                        <span style="font-size: 11.5px; font-weight: 600; color: #334155; {{ $sub->status->value === 'completed' ? 'text-decoration: line-through; color: #94a3b8;' : '' }}">
                                            {{ $sub->title }}
                                        </span>
                                    </label>
                                    @if($sub->end_date)
                                        <span style="font-size: 10px; font-family: monospace; color: #94a3b8;">
                                            {{ $sub->end_date->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p style="margin: 0; font-size: 11px; color: #94a3b8; font-style: italic; text-align: center; padding: 8px 0;">No sub-tasks added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div style="padding: 14px 24px; background: #ffffff; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-shrink: 0;">
                    <button 
                        wire:click="toggleTaskComplete({{ $detailTask->id }})" 
                        type="button" 
                        style="padding: 8px 16px; border-radius: 10px; font-size: 11.5px; font-weight: 800; cursor: pointer; {{ $detailTask->status->value === 'completed' ? 'background: #fffbeb; color: #b45309; border: 1px solid #fde68a;' : 'background: #059669; color: #ffffff; border: none; box-shadow: 0 4px 10px rgba(5,150,105,0.25);' }}"
                    >
                        {{ $detailTask->status->value === 'completed' ? '↩ Re-open Task' : '✓ Mark Completed' }}
                    </button>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a href="{{ route('daily-updates.index', ['project' => $detailTask->project_id, 'task' => $detailTask->id, 'create' => 1]) }}" style="padding: 8px 14px; border-radius: 10px; font-size: 11.5px; font-weight: 800; background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>📝</span>
                            <span>Daily Update</span>
                        </a>
                        <button wire:click="$set('showTaskDetailModal', false)" type="button" style="padding: 8px 16px; border-radius: 10px; font-size: 11.5px; font-weight: 700; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; cursor: pointer;">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
