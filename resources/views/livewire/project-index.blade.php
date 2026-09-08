<div>
    <style>
        .no-native-arrow {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 11px;
            padding-right: 30px !important;
        }
    </style>

    <!-- Flash Success / Error Toast Banners -->
    @if(session()->has('success_message'))
        <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif
    @if(session()->has('error_message'))
        <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">❌</span>
                <span>{{ session('error_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER (PROJECTS DIRECTORY + VIEW SWITCHER)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                @if($viewMode === 'gantt')
                    Portfolio Gantt Radar
                @elseif($viewMode === 'stuck')
                    Stuck &amp; Blocked Tasks Radar
                @else
                    Projects Directory
                @endif
            </h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">
                @if($viewMode === 'gantt')
                    Multi-project timeline, horizons, milestones, and deliverable schedule tracker
                @elseif($viewMode === 'stuck')
                    Live governance radar for blocked deliverables, overdue items, and escalations
                @else
                    Manage all organization projects, timelines, governance, and teams
                @endif
            </p>
        </div>

        <!-- Right Side: View Mode Switcher + Primary New Project CTA -->
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            <!-- View Mode Switcher: Table vs Portfolio Gantt -->
            <div class="inline-flex p-1 rounded-xl border border-slate-200/80 bg-slate-100 shadow-2xs flex-nowrap min-w-max">
                <button 
                    wire:click="setViewMode('table')" 
                    type="button" 
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'table' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-white/60' }}"
                    title="Projects Table View"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Table</span>
                </button>

                <button 
                    wire:click="setViewMode('gantt')" 
                    type="button" 
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'gantt' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900 hover:bg-white/60' }}"
                    title="Master Portfolio Gantt Radar"
                >
                    <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Portfolio Gantt</span>
                </button>
            </div>

            <!-- All Tasks Button (Super Admin / PMO Admin) -->
            @if(auth()->user()?->isSuperAdmin() || auth()->user()?->hasRole('pmo_admin'))
                <a href="{{ route('all-tasks.index') }}" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 hover:text-slate-900 transition-all duration-150 cursor-pointer no-underline active:scale-98 flex-shrink-0" title="Organisation-wide Tasks &amp; Stuck Deliverables">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>All Tasks</span>
                    @if($totalStuckTasksCount > 0)
                        <span class="px-1.5 py-0.5 rounded-md text-[10.5px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-200/70" title="{{ $totalStuckTasksCount }} Stuck/Blocked Tasks">{{ $totalStuckTasksCount }}</span>
                    @endif
                </a>
            @endif

            <!-- Primary New Project CTA -->
            @if(auth()->user()?->canCreateProject())
                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-sm hover:shadow transition-all duration-150 cursor-pointer no-underline active:scale-98 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         VIEW 1: PROJECTS DIRECTORY TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'table')
        <!-- Interactive 4-Metric Portfolio Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <!-- Metric 1: Total Projects -->
            <button 
                type="button" 
                wire:click="$set('statusFilter', 'all'); $set('healthFilter', 'all')"
                class="text-left bg-white border rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ ($statusFilter === 'all' && $healthFilter === 'all') ? 'border-slate-300 ring-2 ring-slate-900/5 bg-slate-50/20 shadow-xs' : 'border-slate-200/80 shadow-2xs hover:border-slate-300' }}"
            >
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-100/90 text-slate-700 border border-slate-200/80 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $totalCount }}</span>
                        <span class="text-xs font-bold text-slate-700 block mt-1.5 truncate">Total Projects</span>
                        <span class="text-[10.5px] text-slate-400 font-medium hidden sm:block mt-0.5">All initiatives</span>
                    </div>
                </div>
                @if($statusFilter === 'all' && $healthFilter === 'all')
                    <span class="w-2 h-2 rounded-full bg-slate-400 flex-shrink-0 shadow-xs" title="All Projects Active"></span>
                @endif
            </button>

            <!-- Metric 2: Active in Progress -->
            <button 
                type="button" 
                wire:click="$set('statusFilter', 'in_progress'); $set('healthFilter', 'all')"
                class="text-left bg-white border rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $statusFilter === 'in_progress' ? 'border-blue-300 ring-2 ring-blue-500/10 bg-blue-50/20 shadow-xs' : 'border-slate-200/80 shadow-2xs hover:border-slate-300' }}"
            >
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $activeCount }}</span>
                        <span class="text-xs font-bold text-slate-700 block mt-1.5 truncate">Active in Progress</span>
                        <span class="text-[10.5px] text-slate-400 font-medium hidden sm:block mt-0.5">Currently executing</span>
                    </div>
                </div>
                @if($statusFilter === 'in_progress')
                    <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 shadow-xs" title="Filtered by In Progress"></span>
                @endif
            </button>

            <!-- Metric 3: Past Deadline -->
            <button 
                type="button" 
                wire:click="$set('healthFilter', 'delayed'); $set('statusFilter', 'all')"
                class="text-left bg-white border rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $healthFilter === 'delayed' ? 'border-rose-300 ring-2 ring-rose-500/10 bg-rose-50/20 shadow-xs' : 'border-slate-200/80 shadow-2xs hover:border-slate-300' }}"
            >
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $overdueCount }}</span>
                        <span class="text-xs font-bold text-slate-700 block mt-1.5 truncate">Past Deadline</span>
                        <span class="text-[10.5px] text-slate-400 font-medium hidden sm:block mt-0.5">Needs PMO focus</span>
                    </div>
                </div>
                @if($healthFilter === 'delayed')
                    <span class="w-2 h-2 rounded-full bg-rose-500 flex-shrink-0 shadow-xs" title="Filtered by Delayed"></span>
                @endif
            </button>

            <!-- Metric 4: On Hold / Standby -->
            <button 
                type="button" 
                wire:click="$set('statusFilter', 'on_hold'); $set('healthFilter', 'all')"
                class="text-left bg-white border rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $statusFilter === 'on_hold' ? 'border-amber-300 ring-2 ring-amber-500/10 bg-amber-50/20 shadow-xs' : 'border-slate-200/80 shadow-2xs hover:border-slate-300' }}"
            >
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $onHoldCount }}</span>
                        <span class="text-xs font-bold text-slate-700 block mt-1.5 truncate">On Hold / Paused</span>
                        <span class="text-[10.5px] text-slate-400 font-medium hidden sm:block mt-0.5">Standby projects</span>
                    </div>
                </div>
                @if($statusFilter === 'on_hold')
                    <span class="w-2 h-2 rounded-full bg-amber-500 flex-shrink-0 shadow-xs" title="Filtered by On Hold"></span>
                @endif
            </button>
        </div>

        <!-- Filter and Search Bar -->
        <div class="bg-white p-3 sm:p-4 rounded-2xl border border-slate-200/80 shadow-2xs mb-4 sm:mb-6">
            <div class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search"
                        type="text" 
                        placeholder="Search by project name, code, subsidiary, or manager..."
                        class="w-full pl-9 pr-4 py-2 text-xs font-medium bg-slate-50/80 border border-slate-200/90 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e]/80 transition-all text-slate-800 placeholder-slate-400"
                    >
                </div>

                <!-- Dropdown Filters -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Subsidiary Filter -->
                    <div class="relative">
                        <select wire:model.live="subsidiaryFilter" class="appearance-none bg-slate-50/80 border border-slate-200/90 text-slate-700 text-xs font-semibold py-2 pl-3 pr-8 rounded-xl hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e]/80 cursor-pointer transition-all">
                            <option value="all">All Subsidiaries</option>
                            @foreach($subsidiaries as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative">
                        <select wire:model.live="statusFilter" class="appearance-none bg-slate-50/80 border border-slate-200/90 text-slate-700 text-xs font-semibold py-2 pl-3 pr-8 rounded-xl hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e]/80 cursor-pointer transition-all">
                            <option value="all">All Statuses</option>
                            @foreach(App\Enums\ProjectStatus::cases() as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- Health Filter -->
                    <div class="relative">
                        <select wire:model.live="healthFilter" class="appearance-none bg-slate-50/80 border border-slate-200/90 text-slate-700 text-xs font-semibold py-2 pl-3 pr-8 rounded-xl hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e]/80 cursor-pointer transition-all">
                            <option value="all">All Health</option>
                            @foreach(App\Enums\ProjectHealth::cases() as $hlth)
                                <option value="{{ $hlth->value }}">{{ $hlth->label() }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- Reset Filters Button -->
                    @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all' || $healthFilter !== 'all' || $managerFilter !== 'all' || $priorityFilter !== 'all')
                        <button 
                            type="button" 
                            wire:click="resetFilters" 
                            class="px-3 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Clear</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <!-- Table Header Title Strip -->
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-700 flex-shrink-0 shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Organization Projects
                        </h2>
                        <p class="text-[11px] text-slate-400 font-medium">
                            Real-time status milestones, health governance, and deliverable progress
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80 font-mono shadow-2xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ $projects->total() }} {{ Str::plural('Project', $projects->total()) }}</span>
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider select-none">
                            <th class="py-3.5 pl-6 pr-4 min-w-[240px]">Code / Project</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Subsidiary</th>
                            <th class="py-3.5 px-4 min-w-[170px]">Project Manager</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Status</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Health</th>
                            <th class="py-3.5 px-4 min-w-[130px]">Progress</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Deadline</th>
                            <th class="py-3.5 pl-4 pr-6 text-right min-w-[140px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($projects as $project)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Code & Name -->
                                <td class="py-3.5 pl-6 pr-4 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="min-w-0">
                                            <a href="{{ route('projects.show', $project->id) }}" class="font-bold text-sm text-slate-900 hover:text-[#c3122e] transition-colors block truncate no-underline">
                                                {{ $project->name }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="font-mono font-bold text-[11px] text-[#c3122e] bg-rose-50/90 px-2 py-0.5 rounded-md border border-rose-200/70 whitespace-nowrap shrink-0 inline-flex items-center leading-none">
                                                    {{ $project->code }}
                                                </span>
                                                <span class="text-[10px] text-slate-300">•</span>
                                                @php
                                                    $priorityDot = match($project->priority->value ?? 'medium') {
                                                        'urgent', 'critical', 'high' => 'bg-rose-500',
                                                        'medium' => 'bg-amber-500',
                                                        default => 'bg-slate-400',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-slate-500 capitalize whitespace-nowrap shrink-0">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $priorityDot }}"></span>
                                                    <span>{{ $project->priority->label() }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subsidiary -->
                                <td class="py-3.5 px-4 align-middle font-medium text-slate-600">
                                    <div class="flex items-center gap-2 max-w-[220px]" title="{{ $project->subsidiary->name ?? '—' }}">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="truncate font-semibold text-slate-700 text-xs">{{ $project->subsidiary->name ?? '—' }}</span>
                                    </div>
                                </td>

                                <!-- Project Manager -->
                                <td class="py-3.5 px-4 align-middle">
                                    @if($project->projectManager)
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-6.5 h-6.5 rounded-full bg-gradient-to-tr from-[#c3122e] to-rose-700 text-white font-extrabold text-[10px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($project->projectManager->name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-bold text-slate-800 block truncate text-xs">{{ $project->projectManager->name }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @php
                                        $stConfig = match($project->status->value) {
                                            'completed' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80', 'dot' => 'bg-emerald-500'],
                                            'in_progress' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200/80', 'dot' => 'bg-amber-500'],
                                            'planning' => ['bg' => 'bg-sky-50 text-sky-700 border-sky-200/80', 'dot' => 'bg-sky-500'],
                                            'on_hold' => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
                                            'under_review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200/80', 'dot' => 'bg-purple-500'],
                                            'delayed' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200/80', 'dot' => 'bg-rose-500'],
                                            default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $stConfig['bg'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $stConfig['dot'] }}"></span>
                                        <span>{{ $project->status->label() }}</span>
                                    </span>
                                </td>

                                <!-- Health -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @php
                                        $hlthConfig = match($project->health->value) {
                                            'on_track' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80', 'dot' => 'bg-emerald-500'],
                                            'at_risk' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200/80', 'dot' => 'bg-amber-500'],
                                            'delayed' => ['bg' => 'bg-orange-50 text-orange-700 border-orange-200/80', 'dot' => 'bg-orange-500'],
                                            'critical' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200/80', 'dot' => 'bg-rose-500 animate-pulse'],
                                            default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $hlthConfig['bg'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $hlthConfig['dot'] }}"></span>
                                        <span>{{ $project->health->label() }}</span>
                                    </span>
                                </td>

                                <!-- Progress -->
                                <td class="py-3.5 px-4 align-middle">
                                    <div class="w-24 sm:w-28">
                                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                            <span>{{ $project->overall_progress }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full {{ $project->overall_progress == 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-[#c3122e] to-rose-500' }} rounded-full transition-all duration-300" style="width: {{ $project->overall_progress }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Deadline -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap text-[11px] font-semibold text-slate-600">
                                    @if($project->deadline)
                                        <span class="{{ $project->deadline->isPast() && $project->overall_progress < 100 ? 'text-rose-600 font-bold inline-flex items-center gap-1' : 'text-slate-600' }}">
                                            @if($project->deadline->isPast() && $project->overall_progress < 100)
                                                <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            @endif
                                            {{ $project->deadline->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 font-light">—</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1 bg-slate-100/70 hover:bg-slate-100 p-1 rounded-xl border border-slate-200/70 shadow-2xs transition-colors">
                                        <!-- Quick WBS Inspector Drawer -->
                                        <button 
                                            wire:click="openQuickDrawer({{ $project->id }})"
                                            type="button" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-white hover:shadow-2xs transition-all cursor-pointer"
                                            title="Inspect WBS Tasks & Schedule"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </button>

                                        <!-- Open Project Workspace -->
                                        <a 
                                            href="{{ route('projects.show', $project->id) }}" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-[#c3122e] hover:bg-white hover:shadow-2xs transition-all cursor-pointer no-underline inline-block"
                                            title="Open Workspace"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>

                                        <!-- Edit Modal Button -->
                                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('pmo_admin') || auth()->id() === $project->project_manager_id)
                                            <button 
                                                wire:click="openEditModal({{ $project->id }})" 
                                                type="button" 
                                                class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-white hover:shadow-2xs transition-all cursor-pointer"
                                                title="Edit Project"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                        @endif

                                        <!-- Delete Project Button (Super Admin / PMO Admin) -->
                                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('pmo_admin'))
                                            <button 
                                                wire:click="confirmDelete({{ $project->id }})" 
                                                type="button" 
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-white hover:shadow-2xs transition-all cursor-pointer"
                                                title="Delete Project"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">No projects found</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Try refining your search or filter parameters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         VIEW 2: PORTFOLIO GANTT TIMELINE RADAR
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'gantt')
        <div class="space-y-4">
            <!-- ── 1. GANTT CONTROL & FILTER TOOLBAR ── -->
            <div class="bg-white border border-slate-200/80 shadow-2xs rounded-3xl p-4 sm:p-5 space-y-3.5">
                <!-- Row 1: Timeline Navigation, Scale Zoom, Date Span & Status Legend -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                    <!-- Left: Navigation + Zoom + Date Range Chip -->
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <!-- Date Navigation Buttons (< Prev, Today, Next >) -->
                        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white shadow-2xs p-0.5 shrink-0">
                            <button wire:click="ganttPrev" type="button" class="px-2.5 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Shift 1 Month Earlier">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                <span>Prev</span>
                            </button>
                            <button wire:click="ganttToday" type="button" class="px-2.5 py-1.5 text-slate-700 hover:text-[#c3122e] hover:bg-rose-50 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border-x border-slate-100" title="Center Timeline on Current Month">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                                <span>Today</span>
                            </button>
                            <button wire:click="ganttNext" type="button" class="px-2.5 py-1.5 text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Shift 1 Month Later">
                                <span>Next</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        <!-- Horizon switcher (Auto / 3M / 6M / 12M) -->
                        <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 text-xs font-bold shrink-0">
                            <button wire:click="setGanttTimeframe('auto')" type="button"
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === 'auto' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                                    title="Auto fit timeline to project start and deadline dates">
                                Auto
                            </button>
                            <button wire:click="setGanttTimeframe('3m')" type="button"
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '3m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                3M
                            </button>
                            <button wire:click="setGanttTimeframe('6m')" type="button"
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '6m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                6M
                            </button>
                            <button wire:click="setGanttTimeframe('12m')" type="button"
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '12m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                12M
                            </button>
                        </div>

                        <!-- Date Span Details -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 shrink-0">
                            <span class="text-slate-800 font-mono font-bold">{{ $ganttTimeline['start']->format('M d, Y') }}</span>
                            <span class="text-slate-400">→</span>
                            <span class="text-slate-800 font-mono font-bold">{{ $ganttTimeline['end']->format('M d, Y') }}</span>
                            <span class="text-slate-400 font-normal">({{ $ganttTimeline['total_days'] }}d)</span>
                        </div>
                    </div>

                    <!-- Right: Modern Status Legend Indicators -->
                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-600 overflow-x-auto scrollbar-none py-0.5 shrink-0">
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 shadow-xs"></span>
                            <span>On Track</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0 shadow-xs"></span>
                            <span>At Risk</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c3122e] shrink-0 shadow-xs"></span>
                            <span>Delayed</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span class="w-2.5 h-2.5 bg-amber-500 rotate-45 shrink-0 rounded-xs"></span>
                            <span>Milestone</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap">
                            <span class="w-3.5 h-0.5 bg-rose-500 shrink-0"></span>
                            <span class="text-rose-600 font-bold text-[11px]">Today</span>
                        </span>
                    </div>
                </div>

                <!-- Row 2: Search, Filters & Expand All Controls -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-2 flex-wrap flex-1 min-w-0">
                        <!-- Search Box -->
                        <div class="relative w-full sm:w-56 shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search projects…"
                                   class="w-full pl-8.5 pr-3 py-1.5 bg-slate-50/90 border border-slate-200/90 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-1.5 focus:ring-slate-400 transition-all">
                        </div>

                        <!-- Subsidiary Select -->
                        <select wire:model.live="subsidiaryFilter" 
                                class="custom-select px-2.5 py-1.5 bg-white border border-slate-200/90 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none focus:ring-1.5 focus:ring-slate-400 transition-all cursor-pointer max-w-[160px] truncate shrink-0">
                            <option value="all">🏢 All Subsidiaries</option>
                            @foreach($subsidiaries as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>

                        <!-- Health Select -->
                        <select wire:model.live="healthFilter" 
                                class="custom-select px-2.5 py-1.5 bg-white border border-slate-200/90 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none focus:ring-1.5 focus:ring-slate-400 transition-all cursor-pointer shrink-0">
                            <option value="all">🚦 Health: All</option>
                            <option value="good">🟢 On Track</option>
                            <option value="at_risk">🟡 At Risk</option>
                            <option value="critical">🔴 Delayed</option>
                        </select>
                    </div>

                    <!-- Right: Expand / Collapse Deliverables Toggle -->
                    <div class="inline-flex p-0.5 rounded-xl bg-slate-100 border border-slate-200/80 text-xs font-bold items-center shrink-0 self-start lg:self-auto">
                        <button wire:click="expandAllGanttProjects" type="button" class="px-3 py-1 rounded-lg text-slate-600 hover:text-[#c3122e] hover:bg-white transition-all cursor-pointer" title="Expand all project deliverables">
                            Expand All
                        </button>
                        <span class="text-slate-300 font-normal">|</span>
                        <button wire:click="collapseAllGanttProjects" type="button" class="px-3 py-1 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer" title="Collapse all deliverables">
                            Collapse All
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── 2. GANTT TIMELINE WORKSPACE ── -->
            <div class="bg-white border border-slate-200/80 shadow-2xs rounded-3xl overflow-hidden">
                <div class="overflow-x-auto overflow-y-auto max-h-[760px] scrollbar-thin">
                    <div class="min-w-[1550px]" style="min-width: 1550px;">
                        <!-- Timeline Header Row -->
                        <div class="flex border-b border-slate-200/90 bg-slate-50/95 text-slate-700 select-none sticky top-0 z-30 shadow-2xs backdrop-blur-xs">
                            <!-- Left Header -->
                            <div class="w-[400px] min-w-[400px] flex-shrink-0 flex items-center justify-between px-4 h-12 border-r border-slate-200/90 bg-slate-50 sticky left-0 z-40">
                                <div class="font-extrabold text-xs uppercase tracking-wider text-slate-800 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    <span>PROJECT &amp; GOVERNANCE</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-black bg-white text-slate-700 border border-slate-200 shadow-2xs">
                                    {{ count($ganttTimeline['projects']) }} {{ count($ganttTimeline['projects']) === 1 ? 'Project' : 'Projects' }}
                                </span>
                            </div>

                            <!-- Right Months Timeline Headers -->
                            <div class="flex-1 min-w-[1150px] flex relative bg-slate-50 h-12 px-0">
                                @foreach($ganttTimeline['months'] as $m)
                                    @php
                                        $mDisplayLabel = match($ganttTimeframe) {
                                             '3m'  => $m['label'],
                                             '12m' => $m['short'],
                                             default => $m['short_label'],
                                        };
                                        if ($ganttTimeframe === '12m' && $m['month_num'] === 1) {
                                            $mDisplayLabel = $m['short_label'];
                                        }
                                    @endphp
                                    <div class="border-r border-slate-200/80 flex items-center justify-center text-center h-full px-1 overflow-hidden relative {{ $m['is_current'] ? 'bg-rose-50/80 font-black text-[#c3122e] border-b-2 border-b-[#c3122e]' : 'text-slate-700 font-bold' }}" style="width: {{ $m['width_pct'] }}%;" title="{{ $m['label'] }}">
                                        <div class="flex items-center gap-1.5 text-xs tracking-tight whitespace-nowrap">
                                            <span>{{ $mDisplayLabel }}</span>
                                            @if($m['is_current'])
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] animate-pulse flex-shrink-0" title="Current Month"></span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Gantt Rows Container -->
                        <div class="relative divide-y divide-slate-100">
                            @forelse($ganttTimeline['projects'] as $gp)
                                @php
                                    $proj = $gp['project'];
                                    $rawHealth = $proj->health->value ?? 'good';
                                    
                                    $hBadge = match($rawHealth) {
                                        'critical'  => 'bg-rose-50 text-[#c3122e] border-rose-200/80',
                                        'at_risk'   => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                        default     => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                                    };
                                    
                                    $barBgColor = match($rawHealth) {
                                        'critical'  => 'bg-gradient-to-r from-[#c3122e] via-[#b01029] to-[#940c21] border-[#800a1c] shadow-xs shadow-rose-950/20',
                                        'at_risk'   => 'bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 border-amber-700 shadow-xs shadow-amber-950/20',
                                        default     => 'bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-700 border-emerald-700 shadow-xs shadow-emerald-950/20',
                                    };

                                    $barWidthPct = max(3.5, $gp['width_pct']);
                                    $isWide = $barWidthPct >= 16.0;
                                @endphp

                                <!-- Project Main Row -->
                                <div class="flex items-center min-h-[66px] h-[66px] hover:bg-slate-50/70 transition-all group relative z-0">
                                    <!-- Left Column: Project Info -->
                                    <div class="w-[400px] min-w-[400px] flex-shrink-0 px-4 py-2.5 h-[66px] border-r border-slate-200/90 bg-white group-hover:bg-slate-50 sticky left-0 z-20 transition-colors flex flex-col justify-center gap-1.5 shadow-2xs">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <button wire:click="toggleGanttExpand({{ $proj->id }})"
                                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-500 flex items-center justify-center transition-all flex-shrink-0 cursor-pointer shadow-2xs group/chevron"
                                                    title="{{ $gp['is_expanded'] ? 'Collapse Deliverables' : 'Expand Deliverables' }}">
                                                <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $gp['is_expanded'] ? 'rotate-90 text-[#c3122e] group-hover/chevron:text-white' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>

                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                               class="px-2 py-0.5 rounded-md font-mono font-bold text-[10px] bg-rose-50 text-[#c3122e] border border-rose-200/80 shrink-0 transition-colors no-underline">
                                                {{ $proj->code }}
                                            </a>

                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                               class="font-extrabold text-[13px] text-slate-900 hover:text-[#c3122e] truncate transition-colors no-underline flex-1 leading-tight">
                                                {{ $proj->name }}
                                            </a>
                                        </div>

                                        <div class="flex items-center gap-2 text-[11px] pl-8 text-slate-500 font-medium">
                                            <span class="truncate max-w-[130px] font-semibold text-slate-400">
                                                {{ $proj->subsidiary->name ?? '—' }}
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span class="inline-flex items-center gap-1.5 truncate max-w-[115px]">
                                                @if($proj->projectManager)
                                                    <span class="w-4.5 h-4.5 rounded-full bg-gradient-to-br from-[#c3122e] to-[#800a1c] text-white font-bold text-[8.5px] flex items-center justify-center shrink-0 shadow-2xs">
                                                        {{ strtoupper(substr($proj->projectManager->name, 0, 1)) }}
                                                    </span>
                                                    <span class="text-slate-700 font-semibold truncate">{{ Str::before($proj->projectManager->name, ' ') }}</span>
                                                @else
                                                    <span class="text-slate-400 italic">Unassigned</span>
                                                @endif
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span class="px-2 py-0.5 rounded-full font-black text-[9px] border uppercase {{ $hBadge }} shrink-0">
                                                {{ $proj->health->label() }} • {{ $gp['progress'] }}%
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Right Column: Timeline Bar Track -->
                                    <div class="flex-1 min-w-[1150px] relative h-[66px] flex items-center px-0">
                                        <div class="absolute inset-0 flex pointer-events-none">
                                            @foreach($ganttTimeline['months'] as $m)
                                                <div class="border-r border-slate-100 h-full {{ $m['is_current'] ? 'bg-rose-50/15' : ($loop->even ? 'bg-slate-50/30' : '') }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                            @endforeach
                                        </div>

                                        @if($ganttTimeline['today_visible'])
                                            <div class="absolute top-0 bottom-0 pointer-events-none z-5 flex flex-col items-center" 
                                                 style="left: {{ $ganttTimeline['today_pct'] }}%;">
                                                <div class="w-px h-full bg-rose-500 border-l border-dashed border-rose-500 opacity-60"></div>
                                            </div>
                                        @endif

                                        @if($gp['is_out_of_bounds'])
                                            @if($gp['left_pct'] <= 0)
                                                <div class="absolute left-3 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full shadow-2xs">
                                                    <span>◀</span>
                                                    <span>Ended {{ $gp['end_date']->format('M d, Y') }}</span>
                                                    <span class="text-slate-400">({{ $gp['progress'] }}%)</span>
                                                </div>
                                            @else
                                                <div class="absolute right-3 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full shadow-2xs">
                                                    <span>Starts {{ $gp['start_date']->format('M d, Y') }}</span>
                                                    <span>▶</span>
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-8 rounded-full bg-slate-50/80 border border-slate-100/90 flex items-center relative overflow-hidden px-0.5 mx-3">
                                                <a href="{{ route('projects.show', $proj->id) }}?tab=wbs"
                                                   class="absolute h-full rounded-full shadow-xs transition-all flex items-center overflow-hidden cursor-pointer group/bar z-10 border text-white no-underline {{ $barBgColor }} hover:brightness-105 hover:shadow-md"
                                                   style="left: {{ $gp['left_pct'] }}%; width: {{ $barWidthPct }}%;"
                                                   title="{{ $proj->name }} • {{ $gp['start_date']->format('M d, Y') }} – {{ $gp['end_date']->format('M d, Y') }} ({{ $gp['progress'] }}% Complete)">
                                                    
                                                    @if($gp['progress'] > 0)
                                                        <div class="h-full bg-white/20 transition-all duration-500 rounded-l-full backdrop-blur-[0.5px]"
                                                             style="width: {{ $gp['progress'] }}%;"></div>
                                                    @endif

                                                    @if($isWide)
                                                        <div class="absolute inset-0 flex items-center justify-between px-3 pointer-events-none text-[10.5px] font-bold font-mono text-white whitespace-nowrap overflow-hidden drop-shadow-sm">
                                                            <span class="flex items-center gap-1">
                                                                @if($gp['starts_before']) <span class="opacity-75 text-[9px]">◀</span> @endif
                                                                <span class="bg-black/20 px-1.5 py-0.5 rounded-full text-[10px]">{{ $gp['progress'] }}%</span>
                                                            </span>
                                                            <span class="opacity-95 text-[10px] font-semibold flex items-center gap-1">
                                                                <span>{{ $gp['start_date']->format('M d') }} – {{ $gp['end_date']->format('M d') }}</span>
                                                                @if($gp['ends_after']) <span class="opacity-75 text-[9px]">▶</span> @endif
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="absolute inset-0 flex items-center justify-center px-1 pointer-events-none text-[10px] font-bold font-mono text-white whitespace-nowrap overflow-hidden drop-shadow-sm">
                                                            {{ $gp['progress'] }}%
                                                        </div>
                                                    @endif
                                                </a>

                                                @if(!$isWide)
                                                    <div class="absolute flex items-center gap-2 pointer-events-none whitespace-nowrap z-10"
                                                         style="left: calc({{ $gp['left_pct'] + $barWidthPct }}% + 12px);">
                                                        <span class="text-[11px] font-mono font-bold text-slate-600 bg-white/95 px-2 py-0.5 rounded-md border border-slate-200/90 shadow-2xs">
                                                            {{ $gp['start_date']->format('M d') }} – {{ $gp['end_date']->format('M d') }}
                                                        </span>
                                                        @if($gp['is_overdue'])
                                                            <span class="px-2 py-0.5 rounded-full font-black text-[9px] bg-rose-600 text-white shadow-xs animate-pulse flex items-center gap-1">
                                                                <span>🚨</span>
                                                                <span>{{ abs($gp['days_remaining']) }}d Overdue</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Expanded WBS Tasks Loop -->
                                @if($gp['is_expanded'])
                                    @forelse($gp['wbs_tasks'] as $task)
                                        @php
                                            $tStatus = $task['status'];
                                            $tStatusBadge = match($tStatus) {
                                                'completed'   => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'in_progress' => 'bg-rose-50 text-[#c3122e] border-rose-200',
                                                'blocked'     => 'bg-rose-100 text-rose-900 border-rose-300 font-black',
                                                'delayed'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                                default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                            };
                                            $tBarBg = match($tStatus) {
                                                'completed'   => 'bg-gradient-to-r from-emerald-500 to-teal-600 border-emerald-600 shadow-xs shadow-emerald-950/20',
                                                'in_progress' => 'bg-gradient-to-r from-[#c3122e] to-[#990e24] border-[#800a1c] shadow-xs shadow-rose-950/20',
                                                'blocked'     => 'bg-gradient-to-r from-rose-600 to-rose-700 border-rose-800 shadow-xs shadow-rose-950/20',
                                                'delayed'     => 'bg-gradient-to-r from-amber-500 to-amber-600 border-amber-600 shadow-xs shadow-amber-950/20',
                                                default       => 'bg-gradient-to-r from-slate-400 to-slate-500 border-slate-500 shadow-xs shadow-slate-950/20',
                                            };
                                        @endphp
                                        <div class="flex items-center min-h-[46px] h-[46px] bg-slate-50/40 hover:bg-slate-100/60 transition-colors border-b border-slate-100/80 group/task relative z-0">
                                            <div class="w-[400px] min-w-[400px] flex-shrink-0 pl-9 pr-4 h-[46px] border-r border-slate-200/90 bg-slate-50/60 group-hover/task:bg-slate-100/80 sticky left-0 z-20 transition-colors flex items-center justify-between gap-2 shadow-2xs">
                                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                                    <span class="text-slate-300 font-mono text-xs select-none">└─</span>
                                                    @if($task['is_milestone'])
                                                        <span class="w-4.5 h-4.5 rounded bg-amber-100 border border-amber-300 text-amber-800 flex items-center justify-center text-[10px] shrink-0">💎</span>
                                                    @elseif($task['item_type'] === 'phase')
                                                        <span class="w-4.5 h-4.5 rounded bg-indigo-100 border border-indigo-300 text-indigo-800 flex items-center justify-center text-[10px] shrink-0">📦</span>
                                                    @else
                                                        <span class="w-4.5 h-4.5 rounded bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center text-[10px] shrink-0">📄</span>
                                                    @endif

                                                    <span class="font-mono font-bold text-[10px] text-slate-500 shrink-0">{{ $task['wbs_code'] }}</span>
                                                    <span class="text-xs font-bold text-slate-800 truncate" title="{{ $task['title'] }}">{{ $task['title'] }}</span>
                                                </div>

                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold border uppercase {{ $tStatusBadge }}">
                                                        {{ strtoupper(str_replace('_', ' ', $task['status'])) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex-1 min-w-[1150px] relative h-[46px] flex items-center px-0">
                                                <div class="absolute inset-0 flex pointer-events-none">
                                                    @foreach($ganttTimeline['months'] as $m)
                                                        <div class="border-r border-slate-100/60 h-full {{ $m['is_current'] ? 'bg-rose-50/10' : '' }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                                    @endforeach
                                                </div>

                                                @if(!$task['is_out_of_bounds'])
                                                    <div class="absolute h-5 rounded-md shadow-2xs border text-white flex items-center px-1.5 text-[9px] font-bold {{ $tBarBg }}"
                                                         style="left: {{ $task['left_pct'] }}%; width: {{ max(1.8, $task['width_pct']) }}%;"
                                                         title="{{ $task['title'] }} • {{ $task['start_date']->format('M d') }} – {{ $task['end_date']->format('M d') }}">
                                                        <span class="truncate">{{ $task['progress'] }}%</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-2.5 px-12 bg-slate-50/30 text-xs text-slate-400 italic">
                                            No WBS tasks or deliverables recorded yet.
                                        </div>
                                    @endforelse
                                @endif
                            @empty
                                <div class="py-16 text-center text-slate-400 font-medium">
                                    <span class="text-3xl block mb-1">📊</span>
                                    <span class="text-sm font-bold text-slate-700">No projects match the timeline filter</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         VIEW 3: STUCK & BLOCKED TASKS LIVE RADAR
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'stuck')
        <div class="space-y-4">
            <!-- 1. 4 KPI Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
                <button type="button" wire:click="setStuckTypeFilter('all')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'all' ? 'border-slate-800 ring-2 ring-slate-800/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $totalStuckTasksCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Total Stuck</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('blocked')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'blocked' ? 'border-rose-500 ring-2 ring-rose-500/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $blockedTasksOnlyCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Blocked Tasks</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('on_hold')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'on_hold' ? 'border-amber-500 ring-2 ring-amber-500/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-amber-200 bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $onHoldTasksCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">On Hold</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('overdue')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'overdue' ? 'border-rose-600 ring-2 ring-rose-600/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $overdueTasksOnlyCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Overdue Deliverables</div>
                    </div>
                </button>
            </div>

            <!-- 2. Search & Filter Bar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 flex-1 flex-wrap">
                        <div class="relative min-w-[240px] flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search task, project, reason, or assignee..."
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-1.5 focus:ring-slate-400 transition-all">
                        </div>

                        <select wire:model.live="stuckProjectFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer max-w-[170px] truncate">
                            <option value="all">📁 All Projects</option>
                            @foreach($stuckProjectsList as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->code }} - {{ $sp->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckAssigneeFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer max-w-[160px] truncate">
                            <option value="all">👤 All Assignees</option>
                            @foreach($stuckAssigneesList as $sa)
                                <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckPriorityFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer">
                            <option value="all">⚡ All Priority</option>
                            <option value="critical">Critical / Urgent</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    @if($search || $stuckTypeFilter !== 'all' || $stuckProjectFilter !== 'all' || $stuckAssigneeFilter !== 'all' || $stuckPriorityFilter !== 'all')
                        <button wire:click="resetStuckFilters" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all flex items-center justify-center gap-1 cursor-pointer shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Clear Filters</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- 3. Stuck Tasks Table -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider select-none">
                                <th class="py-3.5 px-4">Task / Deliverable</th>
                                <th class="py-3.5 px-4">Project</th>
                                <th class="py-3.5 px-4">Assignee</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Delay Days</th>
                                <th class="py-3.5 px-4">Reason / Blocker</th>
                                <th class="py-3.5 px-4">Priority</th>
                                <th class="py-3.5 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($stuckTasks as $task)
                                @php
                                    $pObj = $task->project;
                                    $assignee = $task->assignedUser;
                                    $isBlocked = $task->status->value === 'blocked' || ($task->blockers && $task->blockers->where('status', 'open')->count() > 0);
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- 1. Task Title & Code -->
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="flex items-center gap-2">
                                            @if($isBlocked)
                                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0"></span>
                                            @endif
                                            <div>
                                                <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="font-bold text-slate-900 hover:text-[#c3122e] no-underline block leading-tight">
                                                    {{ $task->title }}
                                                </a>
                                                <span class="text-[10px] font-mono text-slate-400">{{ $task->wbs_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Project -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-semibold text-slate-700 hover:text-[#c3122e] no-underline">
                                            {{ $pObj->code ?? 'PRJ' }}
                                        </a>
                                        <span class="text-[10.5px] text-slate-400 block truncate max-w-[120px]">{{ $pObj->name ?? '—' }}</span>
                                    </td>

                                    <!-- 3. Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($assignee)
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center">
                                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                </span>
                                                <span class="font-medium text-slate-800">{{ $assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                        @endif
                                    </td>

                                    <!-- 4. Status -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($isBlocked)
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-100 text-rose-800 border border-rose-200">
                                                🛑 Blocked
                                            </span>
                                        @elseif($task->status->value === 'on_hold')
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800 border border-amber-200">
                                                ⏸ On Hold
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">
                                                ⚠️ Overdue
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Delay Days -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap font-mono font-bold text-slate-700">
                                        @if($task->computed_overdue_days > 0)
                                            <span class="text-rose-600 font-extrabold">+{{ $task->computed_overdue_days }}d</span>
                                        @else
                                            <span class="text-slate-400 font-normal">—</span>
                                        @endif
                                    </td>

                                    <!-- 6. Blocker / Reason -->
                                    <td class="py-3.5 px-4 align-middle">
                                        @if(!empty($task->delay_reason))
                                            <span class="text-[11px] text-slate-600 italic block line-clamp-1" title="{{ $task->delay_reason }}">
                                                "{{ $task->delay_reason }}"
                                            </span>
                                        @elseif($task->blockers && $task->blockers->where('status', 'open')->count() > 0)
                                            <span class="text-[11px] text-rose-700 font-medium block line-clamp-1">
                                                {{ $task->blockers->where('status', 'open')->first()->description ?? 'Active blocker logged' }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-light text-[11px]">—</span>
                                        @endif
                                    </td>

                                    <!-- 7. Priority -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold capitalize
                                            {{ strtolower($task->priority->value ?? '') === 'critical' ? 'bg-rose-100 text-rose-800' : (strtolower($task->priority->value ?? '') === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $task->priority->label() }}
                                        </span>
                                    </td>

                                    <!-- 8. Action -->
                                    <td class="py-3.5 px-4 align-middle text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <button wire:click="nudgeStuckUser({{ $task->id }})" type="button" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all cursor-pointer flex items-center gap-1 shadow-2xs" title="Send PMO alert to assignee & PM">
                                                <span>🔔 Alert</span>
                                            </button>
                                            <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="p-1 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition-colors inline-block no-underline" title="Open Workspace">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-14 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-2xl">🎉</span>
                                            <span class="text-sm font-bold text-slate-700">No stuck tasks found</span>
                                            <span class="text-xs text-slate-400">All deliverables are progressing smoothly without delays.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         🔍 WBS DELIVERABLES & TASKS INSPECTOR SLIDE-OVER DRAWER
         ═══════════════════════════════════════════════════════════════ -->
    @if($showQuickDrawer && $selectedDrawerProject)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeQuickDrawer"></div>
            <div class="fixed inset-y-0 right-0 pl-6 sm:pl-10 max-w-full flex">
                <div class="w-screen max-w-3xl lg:max-w-4xl bg-white shadow-2xl border-l border-slate-200 flex flex-col justify-between animate-in slide-in-from-right duration-200">
                    
                    <!-- 1. Drawer Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/70 flex items-start justify-between gap-4 flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2 py-0.5 rounded-md font-mono font-bold text-[10px] text-[#c3122e] bg-rose-50 border border-rose-200/80">
                                    {{ $selectedDrawerProject->code }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border 
                                    {{ $selectedDrawerProject->status->value === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($selectedDrawerProject->status->value === 'in_progress' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                    {{ $selectedDrawerProject->status->label() }}
                                </span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs font-semibold text-slate-500">
                                    {{ $selectedDrawerProject->subsidiary->name ?? 'George Steuart Group' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 truncate" id="slide-over-title">
                                {{ $selectedDrawerProject->name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                <span>👤 PM: <strong class="text-slate-700">{{ $selectedDrawerProject->projectManager->name ?? 'Unassigned' }}</strong></span>
                                <span class="text-slate-300">•</span>
                                <span>👥 <strong class="text-slate-700">{{ $selectedDrawerProject->members->count() }}</strong> Team Members</span>
                            </p>
                        </div>

                        <!-- Right Header Actions & Close -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" 
                               class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 shadow-2xs transition-all no-underline">
                                <span>Full Workspace</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <button wire:click="closeQuickDrawer" type="button" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer" title="Close Drawer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Project Executive Health & KPI Summary Strip -->
                    <div class="px-4 sm:px-6 py-3 bg-white border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 flex-shrink-0">
                        <!-- Progress -->
                        <div class="flex items-center gap-3 min-w-[180px]">
                            <div class="w-28 sm:w-36">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                    <span>Progress</span>
                                    <span class="font-mono text-[#c3122e]">{{ $selectedDrawerProject->overall_progress }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#c3122e] to-rose-600 rounded-full transition-all duration-300" style="width: {{ $selectedDrawerProject->overall_progress }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Deadline -->
                        <div class="text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Deadline</span>
                            <span class="font-bold text-slate-800 {{ $selectedDrawerProject->deadline && $selectedDrawerProject->deadline->isPast() && $selectedDrawerProject->overall_progress < 100 ? 'text-rose-600 font-extrabold' : '' }}">
                                {{ $selectedDrawerProject->deadline ? $selectedDrawerProject->deadline->format('M d, Y') : 'Not Specified' }}
                            </span>
                        </div>

                        <!-- WBS KPI Pills -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <strong>{{ $drawerWbsStats['total'] }}</strong> Tasks
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <strong>{{ $drawerWbsStats['completed'] }}</strong> Done
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200">
                                <strong>{{ $drawerWbsStats['in_progress'] }}</strong> Active
                            </span>
                            @if($drawerWbsStats['blocked'] > 0)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-rose-100 text-rose-900 border border-rose-300 animate-pulse">
                                    🚨 <strong>{{ $drawerWbsStats['blocked'] }}</strong> Blocked
                                </span>
                            @endif
                            @if($drawerWbsStats['overdue'] > 0)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                    ⚠️ <strong>{{ $drawerWbsStats['overdue'] }}</strong> Overdue
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 3. WBS Search & Filter Toolbar -->
                    <div class="px-4 sm:px-6 py-2.5 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 flex-shrink-0">
                        <!-- Search Deliverables -->
                        <div class="relative flex-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input 
                                wire:model.live.debounce.250ms="drawerWbsSearch"
                                type="text" 
                                placeholder="Search deliverables, WBS code, assignee..."
                                class="w-full pl-8.5 pr-3 py-1.5 text-xs font-medium bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1.5 focus:ring-[#c3122e]/20 text-slate-800 placeholder-slate-400"
                            >
                        </div>

                        <!-- Status Filter Segment Buttons -->
                        <div class="inline-flex p-0.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold shrink-0 overflow-x-auto">
                            <button 
                                wire:click="setDrawerWbsStatusFilter('all')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'all' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                All ({{ $drawerWbsStats['total'] }})
                            </button>
                            <button 
                                wire:click="setDrawerWbsStatusFilter('in_progress')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'in_progress' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Active ({{ $drawerWbsStats['in_progress'] }})
                            </button>
                            @if($drawerWbsStats['blocked'] > 0 || $drawerWbsStats['overdue'] > 0)
                                <button 
                                    wire:click="setDrawerWbsStatusFilter('blocked')" 
                                    type="button" 
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'blocked' ? 'bg-rose-600 text-white font-bold shadow-2xs' : 'text-rose-700 hover:bg-rose-50' }}"
                                >
                                    Blocked ({{ $drawerWbsStats['blocked'] }})
                                </button>
                            @endif
                            <button 
                                wire:click="setDrawerWbsStatusFilter('completed')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'completed' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Done ({{ $drawerWbsStats['completed'] }})
                            </button>
                        </div>
                    </div>

                    <!-- 4. WBS Deliverables Inspection Table / List Body -->
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1 space-y-2.5 scrollbar-thin">
                        @forelse($drawerWbsItems as $item)
                            @php
                                $itemStatus = is_object($item->status) ? $item->status->value : (string)$item->status;
                                $itemType = is_object($item->item_type) ? $item->item_type->value : (string)$item->item_type;
                                $isExpanded = $selectedInspectorTaskId === $item->id;
                                
                                $statusBadge = match($itemStatus) {
                                    'completed'   => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'in_progress' => 'bg-rose-50 text-[#c3122e] border-rose-200',
                                    'blocked'     => 'bg-rose-100 text-rose-900 border-rose-300 font-bold',
                                    'on_hold'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                    default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                };
                            @endphp

                            <div class="rounded-2xl border transition-all duration-150 {{ $isExpanded ? 'border-[#c3122e] bg-rose-50/15 shadow-sm ring-1 ring-[#c3122e]/20' : 'border-slate-200/80 bg-white hover:border-slate-300 hover:bg-slate-50/40 shadow-2xs' }}">
                                <!-- Deliverable Row Main Strip -->
                                <div class="p-3.5 flex items-center justify-between gap-3 cursor-pointer select-none" wire:click="selectInspectorTask({{ $item->id }})">
                                    <!-- Left: Checkbox + WBS Code + Title + Assignee -->
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <!-- Checkbox toggle -->
                                        <input 
                                            type="checkbox" 
                                            wire:click.stop="toggleDrawerTask({{ $item->id }})" 
                                            @checked($itemStatus === 'completed')
                                            class="w-4.5 h-4.5 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300 cursor-pointer flex-shrink-0"
                                            title="Mark as Completed"
                                        >

                                        <!-- WBS Code & Type Icon -->
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            @if($item->is_milestone)
                                                <span class="w-5 h-5 rounded-md bg-amber-100 border border-amber-300 text-amber-800 flex items-center justify-center text-[10px]" title="Milestone">💎</span>
                                            @elseif($itemType === 'phase')
                                                <span class="w-5 h-5 rounded-md bg-indigo-100 border border-indigo-300 text-indigo-800 flex items-center justify-center text-[10px]" title="Phase">📦</span>
                                            @else
                                                <span class="w-5 h-5 rounded-md bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center text-[10px]" title="Deliverable">📄</span>
                                            @endif
                                            <span class="font-mono font-bold text-[10.5px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded border border-slate-200/70">
                                                {{ $item->wbs_code }}
                                            </span>
                                        </div>

                                        <!-- Title & Assignee -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-xs text-slate-900 truncate {{ $itemStatus === 'completed' ? 'line-through text-slate-400' : '' }}" title="{{ $item->title }}">
                                                    {{ $item->title }}
                                                </span>
                                                @if($item->has_active_blocker)
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0" title="Blocker active"></span>
                                                @endif
                                                @if($item->is_overdue)
                                                    <span class="px-1.5 py-0.2 rounded text-[9.5px] font-black bg-rose-600 text-white shrink-0">
                                                        +{{ $item->overdue_days }}d Overdue
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 text-[10.5px] text-slate-500 mt-0.5">
                                                <span>👤 {{ $item->assignedUser->name ?? 'Unassigned' }}</span>
                                                @if($item->end_date)
                                                    <span class="text-slate-300">•</span>
                                                    <span>📅 Due {{ $item->end_date->format('M d, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Progress + Status Badge + Chevron -->
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <!-- Mini Progress Bar -->
                                        <div class="hidden sm:flex items-center gap-2 w-24">
                                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-[#c3122e] to-rose-600 rounded-full" style="width: {{ $item->progress }}%"></div>
                                            </div>
                                            <span class="font-mono font-bold text-[10.5px] text-slate-700 w-7 text-right">{{ $item->progress }}%</span>
                                        </div>

                                        <!-- Status Badge -->
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] border uppercase {{ $statusBadge }} shrink-0">
                                            {{ strtoupper(str_replace('_', ' ', $itemStatus)) }}
                                        </span>

                                        <!-- Expand Chevron -->
                                        <button type="button" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $isExpanded ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Expanded WBS Task Detailed Inspection Card -->
                                @if($isExpanded)
                                    <div class="px-4 pb-4 pt-2 border-t border-slate-100/90 bg-slate-50/50 space-y-3 animate-in fade-in duration-150">
                                        <!-- Description -->
                                        @if($item->description)
                                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-700 leading-relaxed">
                                                <strong class="text-slate-900 block text-[10.5px] uppercase tracking-wider mb-1">Description / Deliverable Scope:</strong>
                                                {{ $item->description }}
                                            </div>
                                        @endif

                                        <!-- Details Grid -->
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Start Date</span>
                                                <span class="font-bold text-slate-800">{{ $item->start_date ? $item->start_date->format('M d, Y') : '—' }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">End Date / Deadline</span>
                                                <span class="font-bold {{ $item->is_overdue ? 'text-rose-600' : 'text-slate-800' }}">{{ $item->end_date ? $item->end_date->format('M d, Y') : '—' }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Priority</span>
                                                <span class="font-bold text-slate-800 capitalize">{{ $item->priority->label() }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Estimated Hours</span>
                                                <span class="font-bold text-slate-800">{{ $item->estimated_hours ? $item->estimated_hours . ' hrs' : '—' }}</span>
                                            </div>
                                        </div>

                                        <!-- Active Blocker / Delay Reason Alert if any -->
                                        @if(!empty($item->delay_reason) || ($item->blockers && $item->blockers->where('status', 'open')->count() > 0))
                                            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                                                <div class="flex items-center gap-1.5 font-bold text-[#c3122e]">
                                                    <span>🚨</span>
                                                    <span>Active Blocker / Delay Reported:</span>
                                                </div>
                                                <p class="text-[11.5px] italic pl-5">
                                                    "{{ $item->delay_reason ?: $item->blockers->where('status', 'open')->first()->description }}"
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Action Link to Workspace -->
                                        <div class="flex items-center justify-between pt-2">
                                            <span class="text-[11px] text-slate-500 font-medium">Deliverable ID: #{{ $item->id }}</span>
                                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" class="inline-flex items-center gap-1 text-xs font-bold text-[#c3122e] hover:underline no-underline">
                                                <span>Edit &amp; Manage in Project Workspace</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-16 text-center text-slate-400 font-medium bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                <span class="text-3xl block mb-2">📋</span>
                                <span class="text-sm font-bold text-slate-700">No deliverables found</span>
                                <p class="text-xs text-slate-400 mt-0.5">Try changing your search query or filter options.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- 5. Drawer Footer -->
                    <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-shrink-0">
                        <div class="text-xs text-slate-500 font-medium">
                            Showing <strong class="text-slate-800">{{ $drawerWbsItems->count() }}</strong> of <strong class="text-slate-800">{{ $drawerWbsStats['total'] }}</strong> deliverables
                        </div>

                        <div class="flex items-center gap-2.5">
                            <button wire:click="closeQuickDrawer" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer">
                                Close
                            </button>
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); color: #ffffff;" class="px-5 py-2 rounded-xl text-xs font-black shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 cursor-pointer no-underline">
                                <span>🚀 Open Project Workspace</span>
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Delete Project Confirmation Modal -->
    @if($showDeleteModal)
    <div style="position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); box-sizing: border-box;">
        <div style="position: relative; width: 100%; max-width: 480px; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4); border: 1px solid #e2e8f0; overflow: hidden; animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
            <div style="padding: 22px 24px 16px 24px; display: flex; align-items: flex-start; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.15);">
                    🗑️
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                        Delete Project?
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b; font-weight: 500; line-height: 1.4;">
                        Are you sure you want to delete <strong style="color: #0f172a;">{{ $projectToDeleteName }}</strong> (<code style="font-size: 11px; color: #c3122e; background: #fff1f2; padding: 1px 5px; border-radius: 4px; font-weight: 700;">{{ $projectToDeleteCode }}</code>)?
                    </p>
                </div>
                <button type="button" wire:click="cancelDelete" style="width: 28px; height: 28px; border-radius: 7px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    ✕
                </button>
            </div>

            <div style="margin: 0 24px 16px 24px; padding: 12px 14px; background: #fff1f2; border-radius: 12px; border: 1px solid #fecdd3; display: flex; align-items: flex-start; gap: 10px;">
                <span style="font-size: 15px; flex-shrink: 0;">🚨</span>
                <p style="margin: 0; font-size: 11.5px; color: #9f1239; font-weight: 600; line-height: 1.45;">
                    This will permanently delete this project and all its WBS items, deliverables, documents, risks, and records from the entire system. An audit log entry will be permanently recorded.
                </p>
            </div>

            <div style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button type="button" wire:click="cancelDelete" style="padding: 8px 16px; border-radius: 9px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    Cancel
                </button>
                <button type="button" wire:click="deleteProject" style="padding: 8px 20px; border-radius: 9px; border: none; background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35); transition: all 0.15s; display: flex; align-items: gap: 6px;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1.0'">
                    <span>Permanently Delete Project</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
