<div class="space-y-6 pb-12">
    <style>
        .daily-update-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .daily-update-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .daily-update-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .daily-update-scroll::-webkit-scrollbar-thumb:hover {
            background: #c3122e;
        }
        .no-native-arrow {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-950/80 shadow-2xl p-6 sm:p-8 lg:p-9 text-white" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Glowing Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Background Cityscape Skyline Silhouette -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-20 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 40%); mask-image: linear-gradient(to right, transparent 0%, black 40%);">
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: Icon + Title + Meta -->
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/20 ring-4 ring-rose-500/20 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Daily Project Updates
                        </h1>
                        <span class="px-3 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/30 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $updatedTodayCount }} Logged Today</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-medium text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-bold flex items-center gap-1.5" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-600 hidden sm:inline">•</span>
                        <span class="text-slate-300">Track, review, and collaborate on project execution logs across your enterprise</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: View Switcher + Publish Button -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center flex-wrap">
                <!-- View Mode Toggle -->
                <div class="inline-flex p-1 rounded-2xl border border-white/20 shadow-2xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.45);">
                    <button 
                        wire:click="setViewMode('table')" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Project Matrix</span>
                    </button>
                    <button 
                        wire:click="setViewMode('feed')" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'feed' ? 'bg-white text-[#c3122e] shadow-lg shadow-black/40 scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Live Stream</span>
                    </button>
                </div>

                @if(!$this->isSuperAdminUser(auth()->user()))
                    <button
                        wire:click="openStatusUpdateModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-xs font-extrabold text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.25);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Publish Daily Log</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI METRICS SUMMARY (CLEAN EXECUTIVE CARDS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Logged Today -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-50 text-emerald-600 border border-emerald-100/80">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="space-y-1 flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-500">Logged Today</p>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $updatedTodayCount }}</span>
                    <span style="font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px; background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 4px;">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium truncate">Day-by-day progress stream</p>
            </div>
        </div>

        <!-- 2. Participant Task Logs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-rose-50 text-[#c3122e] border border-rose-100/80">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="space-y-1 flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-500">Task Logs</p>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $taskUpdatesCount }}</span>
                    <span style="font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px; background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; display: inline-block;">
                        Tasks
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium truncate">Participant task execution logs</p>
            </div>
        </div>

        <!-- 3. PM Overall Reports -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50 text-amber-600 border border-amber-100/80">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="space-y-1 flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-500">Overall Reports</p>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $projectUpdatesCount }}</span>
                    <span style="font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; display: inline-block;">
                        Overall
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium truncate">Project manager summaries</p>
            </div>
        </div>

        <!-- 4. Pending Feedback -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-indigo-50 text-indigo-600 border border-indigo-100/80">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div class="space-y-1 flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-500">Pending Feedback</p>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $uncommentedUpdatesCount }}</span>
                    <span style="font-size: 9.5px; font-weight: 700; padding: 2px 8px; border-radius: 9999px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; display: inline-block;">
                        Review
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium truncate">Unanswered status logs</p>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CONTROLS, SEARCH & FILTER TOOLBAR (CLEAN & SINGLE CHEVRONS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-slate-200/90 shadow-2xs">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Box -->
            <div class="relative flex-1 min-w-[220px]">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search logs, reporter, project, task..."
                    class="w-full h-10 text-xs font-semibold pl-9 pr-8 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all placeholder:text-slate-400"
                >
                <svg class="w-4 h-4 absolute left-3 top-3 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                @if($searchQuery)
                    <button type="button" wire:click="$set('searchQuery', '')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
                @endif
            </div>

            <!-- Project Selector Dropdown -->
            <div class="relative min-w-[220px] sm:max-w-xs">
                <select
                    wire:model.live="selectedProjectId"
                    class="no-native-arrow w-full h-10 text-xs font-bold pl-3 pr-8 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                >
                    <option value="">🌐 All Accessible Projects</option>
                    @foreach($accessibleProjects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <!-- Date Filter Dropdown -->
            <div class="relative min-w-[140px]">
                <select
                    wire:model.live="dateFilter"
                    class="no-native-arrow w-full h-10 text-xs font-bold pl-3 pr-8 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                >
                    <option value="all">📅 All Time</option>
                    <option value="today">Today</option>
                    <option value="this_week">This Week</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         4A. TABLE VIEW: PROJECTS DAILY UPDATES MATRIX
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'table')
        <div class="w-full rounded-2xl bg-white shadow-xs border border-slate-200/90 overflow-hidden">
            <!-- Executive Table Title Header Bar -->
            <div class="px-6 py-4 border-b border-slate-200/80 bg-white flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Enterprise Project Execution Matrix
                        </h2>
                        <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                            Live synchronization with assigned project updates
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200/80">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ count($groupedProjectUpdates) }} Projects</span>
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-xs font-bold text-slate-700" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            <th class="py-3.5 pl-6 pr-4">Project & Code</th>
                            <th class="py-3.5 px-4">Subsidiary</th>
                            <th class="py-3.5 px-4">Project Manager</th>
                            <th class="py-3.5 px-4">Latest Status Log</th>
                            <th class="py-3.5 px-4">Last Logged</th>
                            <th class="py-3.5 pl-4 pr-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs bg-white">
                        @forelse($groupedProjectUpdates as $group)
                            @php
                                $project = $group['project'];
                                $update = $group['latestUpdate'];
                                $pastUpdates = $group['pastUpdates'];
                                $allUpdatesCount = $group['allUpdates']->count();
                                $pm = $project->projectManager;
                                $subsidiary = $project->subsidiary;

                                $pmName = $pm->name ?? 'Unassigned';
                                $pmParts = explode(' ', trim($pmName));
                                $pmInitials = count($pmParts) >= 2 
                                    ? strtoupper(substr($pmParts[0], 0, 1) . substr($pmParts[count($pmParts) - 1], 0, 1))
                                    : strtoupper(substr($pmName, 0, 2));
                            @endphp

                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Project Code & Name -->
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex items-center gap-3">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-rose-50 text-[#c3122e] border border-rose-200/90 flex-shrink-0 shadow-2xs font-mono">
                                            {{ $project->code ?? 'PRJ-' . $project->id }}
                                        </span>
                                        <div class="min-w-0 space-y-0.5">
                                            <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-xs text-slate-900 hover:text-[#c3122e] transition-colors truncate block max-w-xs" title="{{ $project->name }}">
                                                {{ $project->name }}
                                            </a>
                                            <span class="text-[10.5px] text-slate-400 font-semibold block">{{ $allUpdatesCount }} update log(s)</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subsidiary Name -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    @if($subsidiary)
                                        <div class="flex items-center gap-2 text-slate-700">
                                            <div class="w-6 h-6 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0 text-slate-500">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <span class="font-bold text-xs text-slate-800 truncate max-w-[170px]" title="{{ $subsidiary->name }}">{{ $subsidiary->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 font-medium italic text-[11px]">—</span>
                                    @endif
                                </td>

                                <!-- Project Manager -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-slate-900 to-slate-700 text-white flex items-center justify-center font-black text-[9px] flex-shrink-0 shadow-2xs">
                                            {{ $pmInitials }}
                                        </div>
                                        <div class="min-w-0 space-y-0.5">
                                            <span class="font-bold text-slate-900 block truncate text-xs max-w-[160px]" title="{{ $pmName }}">{{ $pmName }}</span>
                                            <span style="font-size: 8.5px; font-weight: 700; padding: 1px 5px; border-radius: 4px; background: #fffbeb; color: #b45309; border: 1px solid #fde68a; display: inline-block;">PM</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Latest Update Summary -->
                                <td class="py-4 px-4 align-middle max-w-sm">
                                    @if($update)
                                        <div class="min-w-0 space-y-1">
                                            <span class="font-bold text-slate-800 block truncate text-xs" title="{{ $update->title }}">{{ $update->title }}</span>
                                            <p class="text-[11px] text-slate-500 italic font-medium truncate bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100">"{{ $update->summary }}"</p>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-slate-400 italic text-[11px] font-medium">
                                            <span>No status updates logged</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Last Log Date -->
                                <td class="py-4 px-4 align-middle text-slate-600 font-semibold whitespace-nowrap" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                    @if($update)
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $update->created_at->diffForHumans() }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 font-light">—</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        @if(!$this->isSuperAdminUser(auth()->user()))
                                            <button
                                                wire:click="openStatusUpdateModal({{ $project->id }})"
                                                type="button"
                                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00f26] shadow-2xs hover:shadow-xs transition-all cursor-pointer"
                                            >
                                                + Log Update
                                            </button>
                                        @endif

                                        <!-- View History Modal Trigger -->
                                        <button
                                            wire:click="openHistoryModal({{ $project->id }})"
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-[#c3122e] border border-slate-200/80 transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs"
                                            title="View status logs history for {{ $project->name }}"
                                        >
                                            <span>Updates ({{ $allUpdatesCount }})</span>
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-xs font-bold text-slate-400 space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400 shadow-inner mb-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="text-slate-600 font-black text-sm">No Projects Found</p>
                                    <p class="text-slate-400 font-medium text-xs">We couldn't find any projects matching your current query.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- ═══════════════════════════════════════════════════════════════
             4B. FEED VIEW: LIVE CHRONOLOGICAL ACTIVITY STREAM
             ═══════════════════════════════════════════════════════════════ -->
        <div class="space-y-4">
            @forelse($statusUpdates as $update)
                @php
                    $creator = $update->creator;
                    $creatorRole = 'Team Member';
                    $roleStyle = 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;';
                    if ($creator) {
                        if ($creator->hasRole('super_admin')) {
                            $creatorRole = 'PMO Admin';
                            $roleStyle = 'background:#ffe4e6; color:#9f1239; border:1px solid #fecdd3;';
                        } elseif ($creator->hasRole('project_manager') || ($update->project && $update->project->project_manager_id === $creator->id)) {
                            $creatorRole = 'Project Manager';
                            $roleStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;';
                        }
                    }

                    $creatorName = $creator->name ?? 'User';
                    $cParts = explode(' ', trim($creatorName));
                    $cInitials = count($cParts) >= 2 
                        ? strtoupper(substr($cParts[0], 0, 1) . substr($cParts[count($cParts) - 1], 0, 1))
                        : strtoupper(substr($creatorName, 0, 2));
                @endphp

                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-2xs hover:border-slate-300 transition-all duration-200 space-y-4">
                    <!-- Top Row: Creator + Tags + Timestamp -->
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black text-xs text-white shadow-2xs flex-shrink-0 bg-slate-900">
                                {{ $cInitials }}
                            </div>
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-extrabold text-sm text-slate-900">{{ $creatorName }}</span>
                                    <span style="{{ $roleStyle }}; font-size: 8.5px; font-weight: 700; padding: 1px 6px; border-radius: 4px; display: inline-block;">{{ $creatorRole }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium flex-wrap font-mono">
                                    <span class="text-slate-700 font-bold">{{ $update->project->name ?? 'General Project' }}</span>
                                    @if($update->wbsItem)
                                        <span>•</span>
                                        <span class="text-slate-500 font-bold">WBS {{ $update->wbsItem->wbs_code }} — {{ $update->wbsItem->title }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0 text-xs text-slate-400 font-mono">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $update->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Log Title & Summary Quote -->
                    <div class="space-y-2">
                        <h4 class="font-black text-sm text-slate-900 leading-snug">{{ $update->title }}</h4>
                        <div class="p-4 rounded-2xl bg-rose-50/50 border border-rose-100/80 border-l-4 border-l-[#c3122e] text-slate-800 text-xs font-semibold leading-relaxed">
                            "{{ $update->summary }}"
                        </div>
                    </div>

                    <!-- Work Completed & Blockers Pills -->
                    @if($update->work_completed || $update->current_blockers)
                        <div class="flex flex-wrap gap-2.5 text-xs pt-1">
                            @if($update->work_completed)
                                <div class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-900 border border-emerald-200/80 font-bold flex items-center gap-1.5 shadow-2xs">
                                    <span class="text-emerald-600">✓</span>
                                    <span><strong>Deliverables:</strong> {{ $update->work_completed }}</span>
                                </div>
                            @endif
                            @if($update->current_blockers)
                                <div class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-900 border border-rose-200/80 font-bold flex items-center gap-1.5 shadow-2xs">
                                    <span class="text-rose-600">⚠️</span>
                                    <span><strong>Blockers:</strong> {{ $update->current_blockers }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Feedback Comments Section -->
                    <div x-data="{ showComments: false }" class="pt-3 border-t border-slate-100 space-y-3">
                        <div class="flex items-center justify-between">
                            <button 
                                @click="showComments = !showComments" 
                                type="button" 
                                class="text-xs font-bold text-slate-500 hover:text-[#c3122e] flex items-center gap-1.5 transition-colors cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Feedback & Discussion ({{ $update->comments->count() }})</span>
                            </button>
                        </div>

                        <div x-show="showComments" class="space-y-3 pt-2" style="display: none;">
                            <!-- Comments List -->
                            <div class="space-y-2">
                                @forelse($update->comments as $comment)
                                    <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                                        <div class="w-7 h-7 rounded-xl bg-slate-900 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0 space-y-0.5">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-slate-900">{{ $comment->user->name ?? 'User' }}</span>
                                                <span class="text-[9px] font-mono text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-700 font-medium">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-1">No feedback posted yet.</p>
                                @endforelse
                            </div>

                            <!-- Comment Input -->
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    wire:model="newCommentContent.{{ $update->id }}"
                                    wire:keydown.enter="addComment({{ $update->id }})"
                                    placeholder="Write feedback comment..."
                                    class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all flex-1 shadow-2xs"
                                >
                                <button
                                    wire:click="addComment({{ $update->id }})"
                                    class="px-4 py-2 rounded-xl font-black text-xs text-white bg-[#c3122e] hover:bg-[#8b0d1f] shadow-2xs transition-all cursor-pointer flex-shrink-0"
                                >
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-xs font-bold text-slate-400 bg-white rounded-2xl border border-slate-200 space-y-2">
                    <p class="text-slate-600 font-black text-sm">No Daily Logs Found</p>
                    <p class="text-slate-400 font-medium text-xs">There are no updates matching your current filter.</p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODAL: PROJECT STATUS UPDATE HISTORY & FEEDBACK LOOP POPUP
         ═══════════════════════════════════════════════════════════════ -->
    @if($showHistoryModal && $historyProjectId)
        @php
            $historyGroup = collect($groupedProjectUpdates)->firstWhere(fn($g) => $g['project']->id === $historyProjectId);
        @endphp

        @if($historyGroup)
            @php
                $historyProject = $historyGroup['project'];
                $historyLatestUpdate = $historyGroup['latestUpdate'];
                $historyAllUpdates = $historyGroup['allUpdates'];
                $historyUpdatesCount = $historyAllUpdates->count();
            @endphp

            <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 md:p-10 animate-fade-in">
                <!-- Overlay Backdrop with soft blur -->
                <div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-slate-900/50" wire:click="closeHistoryModal"></div>

                <!-- Popup Content Box -->
                <div class="relative bg-white rounded-3xl max-w-4xl w-full shadow-2xl z-10 flex flex-col max-h-[90vh] md:max-h-[85vh] overflow-hidden border border-slate-200/90 animate-in fade-in zoom-in-95 duration-200">
                    <!-- Modal Header -->
                    <div class="px-6 py-4.5 flex items-center justify-between border-b border-rose-900/20 flex-shrink-0" style="background: linear-gradient(135deg, #18060c 0%, #300a16 50%, #1b0710 100%); color: #ffffff;">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg flex-shrink-0 shadow-inner bg-white/10 border border-white/20">
                                📊
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-black text-white leading-tight truncate">
                                    Project Execution Logs: {{ $historyProject->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="px-2 py-0.5 rounded-full font-mono text-[9px] font-black bg-rose-500/30 text-rose-200 border border-rose-400/40">
                                        {{ $historyProject->code ?? 'PRJ-' . $historyProject->id }}
                                    </span>
                                    <span class="text-rose-200/80 text-[11px] font-bold">
                                        • {{ $historyUpdatesCount }} Log(s) recorded
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button 
                            wire:click="closeHistoryModal" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-xl text-white font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 shadow-2xs"
                            title="Close Window"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Close</span>
                        </button>
                    </div>

                    <!-- Modal Body Grid -->
                    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6 daily-update-scroll bg-slate-50/50">
                        @if($historyLatestUpdate)
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <!-- Left side: Latest Update Details (58%) -->
                                <div class="w-full md:w-[58%] space-y-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Latest Status Update Log
                                    </div>

                                    <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-5">
                                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-xl font-black flex items-center justify-center text-xs text-white shadow-2xs" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                                                    {{ strtoupper(substr($historyLatestUpdate->creator->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-black text-xs text-slate-900 block leading-tight">{{ $historyLatestUpdate->creator->name ?? 'User' }}</span>
                                                    <span class="text-[10px] font-bold text-slate-400 font-mono tracking-wide block mt-0.5">{{ $historyLatestUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-black bg-[#c3122e] text-white shadow-2xs">
                                                Log #{{ $historyUpdatesCount }}
                                            </span>
                                        </div>

                                        <div class="space-y-3">
                                            <h4 class="font-black text-sm text-slate-900 leading-snug">{{ $historyLatestUpdate->title }}</h4>
                                            
                                            <!-- Quote Box with GS Crimson tint -->
                                            <div class="relative p-4 rounded-2xl text-slate-800 font-semibold text-xs leading-relaxed shadow-2xs bg-rose-50/60 border border-rose-100/80 border-l-4 border-l-[#c3122e]">
                                                <p class="pl-2 pr-1 italic">"{{ $historyLatestUpdate->summary }}"</p>
                                            </div>
                                        </div>

                                        @if($historyLatestUpdate->work_completed || $historyLatestUpdate->current_blockers)
                                            <div class="space-y-2 text-xs pt-1">
                                                @if($historyLatestUpdate->work_completed)
                                                    <div class="p-3.5 rounded-2xl bg-emerald-50 text-emerald-900 border border-emerald-200/80 font-bold flex items-start gap-2.5 shadow-2xs">
                                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <div>
                                                            <strong class="text-emerald-950 font-black">Work Completed:</strong> 
                                                            <span>{{ $historyLatestUpdate->work_completed }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($historyLatestUpdate->current_blockers)
                                                    <div class="p-3.5 rounded-2xl bg-rose-50 text-rose-900 border border-rose-200/80 font-bold flex items-start gap-2.5 shadow-2xs">
                                                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        <div>
                                                            <strong class="text-rose-950 font-black">Current Blockers:</strong> 
                                                            <span>{{ $historyLatestUpdate->current_blockers }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right side: Comments & Feedback Loop (42%) -->
                                <div class="w-full md:w-[42%] space-y-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider text-rose-800 bg-rose-50 border border-rose-200">
                                        💬 PM & PMO Admin Feedback Loop
                                    </div>

                                    @php
                                        $visibleComments = $historyLatestUpdate->comments;
                                    @endphp

                                    <div class="space-y-3 max-h-[320px] overflow-y-auto p-4 bg-white border border-slate-200/90 rounded-2xl shadow-2xs daily-update-scroll">
                                        @forelse($visibleComments as $comment)
                                            @php
                                                $commentUser = $comment->user;
                                                $commenterRole = 'Team Member';
                                                $commentBadgeStyle = 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;';

                                                if ($commentUser) {
                                                    if ($commentUser->hasRole('super_admin')) {
                                                        $commenterRole = 'PMO Admin';
                                                        $commentBadgeStyle = 'background:#ffe4e6; color:#9f1239; border:1px solid #fecdd3;';
                                                    } elseif ($commentUser->hasRole('project_manager') || $historyProject->project_manager_id === $commentUser->id) {
                                                        $commenterRole = 'Project Manager';
                                                        $commentBadgeStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;';
                                                    }
                                                }
                                            @endphp
                                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50/80 border border-slate-100 shadow-2xs">
                                                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-[10px] text-white shadow-2xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                                                    {{ strtoupper(substr($commentUser->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0 space-y-0.5">
                                                    <div class="flex items-center justify-between gap-1 flex-wrap">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <span class="text-xs font-black text-slate-900">{{ $commentUser->name ?? 'User' }}</span>
                                                            <span style="{{ $commentBadgeStyle }}; font-size: 8.5px; font-weight: 700; padding: 1px 5px; border-radius: 4px; display: inline-block;">{{ $commenterRole }}</span>
                                                        </div>
                                                        <span class="text-[9px] font-bold text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs font-medium text-slate-700 leading-relaxed">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-14 text-center text-xs font-semibold text-slate-400 italic space-y-2.5">
                                                <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400 shadow-inner">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                </div>
                                                <p>No feedback comments posted yet.</p>
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Add Comment Form -->
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            wire:model="newCommentContent.{{ $historyLatestUpdate->id }}"
                                            wire:keydown.enter="addComment({{ $historyLatestUpdate->id }})"
                                            placeholder="Write feedback comment..."
                                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all flex-1 shadow-2xs"
                                        >
                                        <button
                                            wire:click="addComment({{ $historyLatestUpdate->id }})"
                                            class="px-4 py-2.5 rounded-xl font-black text-xs text-white bg-[#c3122e] hover:bg-[#8b0d1f] shadow-2xs hover:shadow-xs transition-all cursor-pointer flex-shrink-0"
                                        >
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="py-12 text-center text-xs font-medium text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
                                No status update logs posted yet for this project.
                            </div>
                        @endif

                        <!-- ALL UPDATES LOG TIMELINE (PAST HISTORY) -->
                        <div class="space-y-4 pt-5 border-t border-slate-200">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider text-slate-600 bg-slate-100 border border-slate-200">
                                📜 All Execution Logs Timeline ({{ $historyUpdatesCount }})
                            </div>

                            <div class="relative pl-6 space-y-5 before:content-[''] before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-200">
                                @forelse($historyAllUpdates as $pIndex => $pUpdate)
                                    @php
                                        $pCreator = $pUpdate->creator;
                                        $pCreatorRole = 'Collaborator';
                                        $pRoleBadgeStyle = 'background:#f0fdf4; color:#166534; border:1px solid #bbf7d0;';

                                        if ($pCreator) {
                                            if ($pCreator->hasRole('super_admin')) {
                                                $pCreatorRole = 'PMO Admin';
                                                $pRoleBadgeStyle = 'background:#ffe4e6; color:#9f1239; border:1px solid #fecdd3;';
                                            } elseif ($pCreator->hasRole('project_manager') || $historyProject->project_manager_id === $pCreator->id) {
                                                $pCreatorRole = 'Project Manager';
                                                $pRoleBadgeStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;';
                                            }
                                        }
                                    @endphp
                                    <div class="relative group">
                                        <!-- Timeline Dot Node -->
                                        <span class="absolute -left-[24px] top-2 w-3.5 h-3.5 rounded-full border-2 border-white shadow-2xs flex-shrink-0 transition-transform group-hover:scale-125" style="background: {{ $pIndex === 0 ? '#c3122e' : '#cbd5e1' }};"></span>

                                        <!-- Log Card Content -->
                                        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:border-slate-300 transition-all duration-200 space-y-3">
                                            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-mono font-black bg-[#c3122e] text-white shadow-2xs">
                                                        #{{ $historyUpdatesCount - $pIndex }}
                                                    </span>
                                                    <h6 class="font-black text-xs text-slate-900 leading-snug">{{ $pUpdate->title }}</h6>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs flex-wrap">
                                                    <span class="font-bold text-slate-800">{{ $pCreator->name ?? 'User' }}</span>
                                                    <span style="{{ $pRoleBadgeStyle }}; font-size: 8.5px; font-weight: 700; padding: 1px 5px; border-radius: 4px; display: inline-block;">{{ $pCreatorRole }}</span>
                                                    <span class="text-slate-400 font-mono text-[9px] font-bold">{{ $pUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Quote Panel inside timeline card -->
                                            <div class="p-3.5 rounded-xl italic text-xs bg-slate-50 text-slate-800 leading-relaxed font-semibold shadow-2xs border border-slate-100 border-l-4 border-l-slate-300">
                                                "{{ $pUpdate->summary }}"
                                            </div>
                                            
                                            @if($pUpdate->work_completed || $pUpdate->current_blockers)
                                                <div class="flex flex-wrap gap-2.5 text-[10px] pt-1">
                                                    @if($pUpdate->work_completed)
                                                        <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold flex items-center gap-1 shadow-2xs">
                                                            ✓ {{ $pUpdate->work_completed }}
                                                        </span>
                                                    @endif
                                                    @if($pUpdate->current_blockers)
                                                        <span class="px-2.5 py-1 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 font-bold flex items-center gap-1 shadow-2xs">
                                                            ⚠ {{ $pUpdate->current_blockers }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center text-xs font-bold text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
                                        No past status update logs found.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         6. MODAL: POST DAILY STATUS UPDATE
         ═══════════════════════════════════════════════════════════════ -->
    @if($showUpdateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 md:p-10 animate-fade-in">
            <!-- Overlay Backdrop with soft blur -->
            <div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-slate-900/50" wire:click="$set('showUpdateModal', false)"></div>

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-0 overflow-hidden shadow-2xl z-10 border border-slate-200/90 animate-in fade-in zoom-in-95 duration-200">
                <!-- Modal Header -->
                <div class="px-6 py-4.5 flex items-center justify-between border-b border-rose-900/20 flex-shrink-0" style="background: linear-gradient(135deg, #18060c 0%, #300a16 50%, #1b0710 100%); color: #ffffff;">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg flex-shrink-0 shadow-inner bg-white/10 border border-white/20">
                            📝
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-white leading-tight">Publish Daily Progress Log</h3>
                            <p class="text-rose-200/80 text-[11px] font-bold mt-0.5">Log task execution progress or overall project status update</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showUpdateModal', false)" 
                        type="button" 
                        class="px-3.5 py-1.5 rounded-xl text-white font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 shadow-2xs"
                        title="Close Window"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Close</span>
                    </button>
                </div>

                <form wire:submit="saveStatusUpdate" class="p-6 space-y-4">
                    <!-- Target Project Select -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-700">Target Project <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select
                                wire:model.live="updateProjectId"
                                class="no-native-arrow w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                            >
                                @foreach($accessibleProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('updateProjectId') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Scope Selector (Task vs Project Overall) -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-2xl border border-slate-200/60 shadow-inner">
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'task')"
                                class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'task' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-900' }}"
                            >
                                📍 Task-Specific Log
                            </button>
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'project')"
                                class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'project' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-900' }}"
                            >
                                🌐 Full Project Report
                            </button>
                        </div>
                    @endif

                    <!-- Task Linker if Task Scope -->
                    @if($updateScopeType === 'task')
                        <div class="space-y-1.5">
                            <label class="block text-xs font-black text-slate-700">📍 WBS Scope Task</label>
                            <div class="relative">
                                <select
                                    wire:model="updateWbsItemId"
                                    class="no-native-arrow w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                                >
                                    <option value="">Select Task...</option>
                                    @foreach($modalWbsItems as $wbs)
                                        <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1">Select the specific task you worked on today.</p>
                        </div>
                    @endif

                    <!-- Log Title -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-700">Log Title <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            wire:model="updateTitle"
                            placeholder="e.g. Completed Payment Gateway API integration tests"
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                        >
                        @error('updateTitle') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Daily Executive Summary -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-black text-slate-700">Daily Executive Summary <span class="text-rose-500">*</span></label>
                        <textarea
                            wire:model="updateSummary"
                            rows="3"
                            placeholder="Detail main work completed, progress made, or key highlights..."
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                        ></textarea>
                        @error('updateSummary') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Deliverables and Blockers -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-black text-slate-700">Work Completed Today</label>
                            <input
                                type="text"
                                wire:model="updateWorkCompleted"
                                placeholder="Deliverables finished..."
                                class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                            >
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-black text-slate-700">Blockers / Issues (If Any)</label>
                            <input
                                type="text"
                                wire:model="updateBlockers"
                                placeholder="Execution blockers faced..."
                                class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                            >
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="$set('showUpdateModal', false)"
                            class="px-4 py-2.5 rounded-xl text-xs font-black text-slate-600 hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#8b0d1f] shadow-2xs hover:shadow-xs transition-all cursor-pointer hover:-translate-y-0.5"
                        >
                            Publish Daily Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
