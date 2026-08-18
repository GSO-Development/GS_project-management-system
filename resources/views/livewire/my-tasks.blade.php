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
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold Elegant Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: 3D App Icon + Title + Actionable Pill + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            My Tasks Workspace
                        </h1>
                        @if(($inProgressCount + $dueTodayCount + $overdueCount) > 0)
                            <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                                <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                                <span>{{ $inProgressCount + $dueTodayCount + $overdueCount }} Actionable</span>
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Personal Deliverables &amp; Execution Hub</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Luxury Controls & Floating Progress Ring Card -->
            <div class="flex items-center gap-3.5 flex-shrink-0 self-start lg:self-center flex-wrap">
                <!-- 👑 Lead Projects vs 👥 Collaborator Filter Tabs -->
                <div class="inline-flex p-1.5 rounded-2xl border border-white/20 shadow-2xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button 
                        wire:click="setProjectRoleFilter('all')" 
                        type="button" 
                        class="px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-2 cursor-pointer {{ $projectRoleFilter === 'all' ? 'bg-white text-slate-900 shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <span>⚡ All Tasks</span>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $projectRoleFilter === 'all' ? 'bg-slate-900 text-white' : 'bg-white/20 text-white' }} font-mono">{{ $totalCount }}</span>
                    </button>

                    <button 
                        wire:click="setProjectRoleFilter('lead')" 
                        type="button" 
                        class="px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-2 cursor-pointer {{ $projectRoleFilter === 'lead' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                        title="Tasks in projects where you are the designated Project Leader"
                    >
                        <span>👑 Lead</span>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $projectRoleFilter === 'lead' ? 'bg-[#c3122e] text-white' : 'bg-white/20 text-white' }} font-mono">{{ $leadTasksCount }}</span>
                    </button>

                    <button 
                        wire:click="setProjectRoleFilter('collaborator')" 
                        type="button" 
                        class="px-4 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-2 cursor-pointer {{ $projectRoleFilter === 'collaborator' ? 'bg-white text-indigo-700 shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                        title="Tasks assigned to you in collaborating projects"
                    >
                        <span>👥 Collaborator</span>
                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-black {{ $projectRoleFilter === 'collaborator' ? 'bg-indigo-700 text-white' : 'bg-white/20 text-white' }} font-mono">{{ $collabTasksCount }}</span>
                    </button>
                </div>

                <!-- ⚡ View Switcher: Unified vs Kanban vs Table -->
                <div class="inline-flex p-1.5 rounded-2xl border border-white/20 shadow-2xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button 
                        wire:click="setViewMode('all')" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'all' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <span>⚡ Unified</span>
                    </button>
                    <button 
                        wire:click="setViewMode('kanban')" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        <span>Kanban</span>
                    </button>
                    <button 
                        wire:click="setViewMode('table')" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Table</span>
                    </button>
                </div>

                <!-- Floating Tasks Progress Ring Card -->
                @php
                    $completionPct = $totalCount > 0 ? (int) round(($completedCount / $totalCount) * 100) : 0;
                @endphp
                <div class="hidden xl:flex items-center gap-3.5 bg-white rounded-2xl px-4 py-2.5 shadow-2xl text-slate-900 border border-white/60 flex-shrink-0">
                    <div class="relative w-11 h-11 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-11 h-11 -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-[#c3122e] transition-all duration-700" stroke-width="3.5" stroke-dasharray="{{ $completionPct }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-[11px] font-black text-slate-900 font-mono">{{ $completionPct }}%</span>
                    </div>
                    <div class="text-left">
                        <div class="text-[9px] font-black uppercase tracking-wider text-slate-400">Tasks Progress</div>
                        <div class="text-xs font-black text-slate-900 font-mono">{{ $completedCount }}/{{ $totalCount }} Done</div>
                    </div>
                </div>

                @if(auth()->user()->hasRole('super_admin'))
                    <button wire:click="$set('saView', '{{ $saView === 'team' ? 'mine' : 'team' }}')"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl text-xs font-black border transition-all duration-200 cursor-pointer shadow-lg active:scale-95 {{ $saView === 'team' ? 'border-[#c3122e] bg-[#c3122e] text-white shadow-rose-950/40' : 'border-white/20 text-white hover:bg-white/20' }}"
                            style="background: {{ $saView === 'team' ? '#c3122e' : 'rgba(0,0,0,0.45)' }};">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $saView === 'team' ? 'My View' : 'Team Monitor' }}</span>
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
                    'accent' => 'hover:border-slate-400 group-hover:text-slate-900',
                    'activeRing' => 'ring-2 ring-slate-800 border-slate-800 bg-slate-50/50',
                    'action' => '$set("statusFilter", "all"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'all' && $dueDateFilter === 'all'
                ],
                [
                    'label' => 'In Progress',
                    'val' => $inProgressCount,
                    'icon' => '<svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z"/></svg>',
                    'iconBg' => 'bg-blue-50 text-blue-600',
                    'accent' => 'hover:border-blue-300 group-hover:text-blue-600',
                    'activeRing' => 'ring-2 ring-blue-500 border-blue-500 bg-blue-50/20',
                    'action' => '$set("statusFilter", "in_progress"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'in_progress'
                ],
                [
                    'label' => 'Due Today',
                    'val' => $dueTodayCount,
                    'icon' => '<svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'iconBg' => 'bg-amber-50 text-amber-600',
                    'accent' => 'hover:border-amber-300 group-hover:text-amber-600',
                    'activeRing' => 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/20',
                    'action' => '$set("dueDateFilter", "today"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'today'
                ],
                [
                    'label' => 'Overdue',
                    'val' => $overdueCount,
                    'icon' => '<svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    'iconBg' => 'bg-rose-50 text-rose-600',
                    'accent' => 'hover:border-rose-300 group-hover:text-rose-600',
                    'activeRing' => 'ring-2 ring-rose-500 border-rose-500 bg-rose-50/20',
                    'action' => '$set("dueDateFilter", "overdue"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'overdue'
                ],
                [
                    'label' => 'Completed',
                    'val' => $completedCount,
                    'icon' => '<svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'iconBg' => 'bg-emerald-50 text-emerald-600',
                    'accent' => 'hover:border-emerald-300 group-hover:text-emerald-600',
                    'activeRing' => 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20',
                    'action' => '$set("statusFilter", "completed"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'completed'
                ],
                [
                    'label' => 'Blocked',
                    'val' => $blockedCount,
                    'icon' => '<svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                    'iconBg' => 'bg-red-50 text-red-600',
                    'accent' => 'hover:border-red-300 group-hover:text-red-600',
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
                        <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            ✕
                        </button>
                    @endif
                </div>

                <!-- Project Selector (Organized by Lead vs Collaborator) -->
                <div>
                    <select wire:model.live="projectFilter" class="w-full px-3 py-2 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all outline-none cursor-pointer custom-select">
                        <option value="all">📁 All Assigned Projects</option>
                        @if(isset($leadProjects) && $leadProjects->count() > 0)
                            <optgroup label="👑 Projects I Lead (PM)">
                                @foreach($leadProjects as $lp)
                                    <option value="{{ $lp->id }}">{{ $lp->code }} — {{ $lp->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if(isset($collabProjects) && $collabProjects->count() > 0)
                            <optgroup label="👥 Collaborating Projects (Member)">
                                @foreach($collabProjects as $cp)
                                    <option value="{{ $cp->id }}">{{ $cp->code }} — {{ $cp->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
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
            @if($search || $projectFilter !== 'all' || $projectRoleFilter !== 'all' || $priorityFilter !== 'all' || $statusFilter !== 'incomplete' || $dueDateFilter !== 'all')
                <div class="flex items-center justify-between gap-3 pt-2.5 border-t border-slate-100 text-xs flex-wrap">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Active Filters:</span>
                        @if($search)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-slate-100 text-slate-700 text-[10px] font-bold">
                                Search: "{{ $search }}"
                            </span>
                        @endif
                        @if($projectRoleFilter !== 'all')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-rose-50 text-[#c3122e] border border-rose-200 text-[10px] font-black">
                                Role: {{ ucfirst($projectRoleFilter) }}
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
         5. 5-STATUS KANBAN BOARD (To Do, In Progress, In Review, Completed, Blocked)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="space-y-8 animate-in fade-in duration-200">
        <!-- ═══════════════════════════════════════════════════════════════
             5. 5-STATUS KANBAN BOARD (To Do, In Progress, In Review, Completed, Blocked)
             ═══════════════════════════════════════════════════════════════ -->
        @if($viewMode === 'all' || $viewMode === 'kanban')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-start overflow-x-auto pb-2 scrollbar-thin">
                <!-- 1. To Do -->
                @include('livewire.partials.my-tasks-kanban-col', [
                    'title' => 'To Do',
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
                    'statusKey' => 'blocked',
                    'badge' => 'bg-rose-100 text-rose-700',
                    'accentColor' => '#f43f5e',
                    'tasks' => $kanbanBlocked,
                    'emptyText' => 'No blocked tasks! Clear path.',
                    'colType' => 'blocked'
                ])
            </div>
        @endif

        <!-- ═══════════════════════════════════════════════════════════════
             6. ALL TASKS TABLE VIEW (Matching Design)
             ═══════════════════════════════════════════════════════════════ -->
        @if($viewMode === 'all' || $viewMode === 'table')
            <div class="space-y-3.5">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        All Tasks
                    </h3>
                    <span class="text-xs font-bold text-slate-500 font-mono">
                        Showing {{ $tasks->total() }} Total Tasks
                    </span>
                </div>

                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
                    <div class="overflow-x-auto scrollbar-thin">
                        <table class="w-full text-left border-collapse min-w-[850px]">
                            <thead>
                                <tr class="border-b border-slate-200/80 bg-slate-50/70 text-xs font-bold text-slate-600">
                                    <th class="py-3.5 pl-5 pr-2 w-10">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="selectAll" 
                                            class="rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e] cursor-pointer"
                                        >
                                    </th>
                                    <th class="py-3.5 px-4 text-xs font-bold text-slate-700">Task Name</th>
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

                                        <!-- Task Name -->
                                        <td class="py-3.5 px-4 font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors">
                                            {{ $t->title }}
                                        </td>

                                        <!-- Project -->
                                        <td class="py-3.5 px-4 text-slate-500 font-medium truncate max-w-[200px]">
                                            {{ $t->project->name ?? 'General Delivery' }}
                                        </td>

                                        <!-- Status -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span style="{{ $statusBadge }}; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; border-width: 1px; display: inline-block;">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <!-- Priority -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span style="{{ $priBadge }}; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 6px; border-width: 1px; display: inline-block;">
                                                {{ $priStr }}
                                            </span>
                                        </td>

                                        <!-- Due Date -->
                                        <td class="py-3.5 px-4 text-slate-600 font-medium font-mono whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                                        <button wire:click="markTaskCompleted({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50 rounded-xl flex items-center gap-2">
                                                            <span>✓</span> Mark Completed
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('daily-updates.index', ['project' => $t->project_id, 'task' => $t->id, 'create' => 1]) }}" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 rounded-xl flex items-center gap-2 no-underline">
                                                        <span>📝</span> Log Daily Update
                                                    </a>
                                                    <button wire:click="openSubTaskModal({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-50 rounded-xl flex items-center gap-2">
                                                        <span>➕</span> Add Sub-task
                                                    </button>
                                                    <button wire:click="openDelayReasonModal({{ $t->id }}); openMenu = false" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-50 rounded-xl flex items-center gap-2">
                                                        <span>⚠️</span> Report Blocker
                                                    </button>
                                                    <button wire:click="deleteTask({{ $t->id }}); openMenu = false" wire:confirm="Are you sure you want to delete this task?" class="w-full text-left px-2.5 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl flex items-center gap-2">
                                                        <span>🗑️</span> Delete Task
                                                    </button>
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
         7. CREATE TASK MODAL (from Kanban + Add Task)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateTaskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-lg p-6 sm:p-7 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-black text-lg">
                        ✨
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Add New Task</h3>
                        <p class="text-xs text-slate-500 font-medium">Create a new task and assign timeline deliverables</p>
                    </div>
                </div>

                <form wire:submit="saveNewTask" class="space-y-4">
                    <!-- Project Selector -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Project <span class="text-rose-500">*</span></label>
                        <select wire:model="createTaskProjectId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold outline-none focus:border-[#c3122e] bg-slate-50/50 focus:bg-white" required>
                            <option value="">-- Select Project --</option>
                            @foreach($myProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('createTaskProjectId') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Task Title -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Task Name <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="createTaskTitle" placeholder="e.g. UI/UX Wireframes" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]" required>
                        @error('createTaskTitle') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Start Date</label>
                            <input type="date" wire:model="createTaskStartDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Due Date</label>
                            <input type="date" wire:model="createTaskEndDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        </div>
                    </div>

                    <!-- Priority & Status Row -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Priority</label>
                            <select wire:model="createTaskPriority" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none cursor-pointer custom-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Status</label>
                            <select wire:model="createTaskStatus" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none cursor-pointer custom-select">
                                <option value="not_started">To Do</option>
                                <option value="in_progress">In Progress</option>
                                <option value="under_review">In Review</option>
                                <option value="completed">Completed</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showCreateTaskModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button type="submit" class="btn-primary text-xs">+ Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8. MODALS FOR DELAY/BLOCKERS & SUB-TASKS
         ═══════════════════════════════════════════════════════════════ -->
    
    <!-- 1. Delay / Blocker Reason Modal -->
    @if($showDelayReasonModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-md p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-black">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Report Task Blocker / Issue</h3>
                        <p class="text-xs text-slate-500 font-medium">Explain why this deliverable is delayed or blocked.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <textarea wire:model="delayReasonText" rows="4" placeholder="Describe the blocker (e.g. Waiting on client credentials, vendor delayed delivery)..." class="w-full p-3.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e] leading-relaxed"></textarea>
                    @error('delayReasonText') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showDelayReasonModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button wire:click="submitDelayReason" type="button" class="btn-primary text-xs">Submit Issue Report</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Sub-Task Creation Modal -->
    @if($showSubTaskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg p-6 space-y-4">
                <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center font-black text-lg">
                        📋
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Add Sub-Task Deliverable</h3>
                        <p class="text-xs text-slate-500 font-medium">Break down your task into smaller manageable deliverables.</p>
                    </div>
                </div>

                <form wire:submit="createSubTask" class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Sub-Task Title *</label>
                        <input type="text" wire:model="subTaskTitle" placeholder="e.g. Conduct requirement analysis..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        @error('subTaskTitle') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Description (Optional)</label>
                        <textarea wire:model="subTaskDescription" rows="2" placeholder="Provide extra delivery details..." class="w-full p-3 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Target End Date</label>
                            <input type="date" wire:model="subTaskEndDate" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Priority</label>
                            <select wire:model="subTaskPriority" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none cursor-pointer custom-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Est. Hours (Optional)</label>
                            <input type="number" step="0.5" wire:model="subTaskEstimatedHours" placeholder="e.g. 4.0" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showSubTaskModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button type="submit" class="btn-primary text-xs">+ Create Sub-Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
