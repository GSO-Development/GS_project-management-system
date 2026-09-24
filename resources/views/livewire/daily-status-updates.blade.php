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
         1. TOP HEADER (DAILY STATUS UPDATES)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Daily Status Updates
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-black text-white bg-[#c3122e] shadow-2xs flex items-center justify-center shrink-0" title="Total Status Updates">
                    {{ $totalUpdatesCount }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200/70 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $updatedTodayCount }} Logged Today</span>
                </span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI METRICS SUMMARY (CLEAN MODERN CARDS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-3.5">
        <!-- 1. Logged Today -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-150 overflow-hidden flex flex-col justify-between h-full">
            <div>
                <div class="h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 w-full"></div>
                <div class="p-3.5 sm:p-4 pb-2">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block truncate">Logged Today</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 tracking-tight leading-none mb-1">{{ $updatedTodayCount }}</div>
                </div>
            </div>
            <div class="px-3.5 pb-3.5 sm:px-4 sm:pb-4 pt-0">
                <div class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Active updates</span>
                </div>
            </div>
        </div>

        <!-- 2. Task Logs -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-150 overflow-hidden flex flex-col justify-between h-full">
            <div>
                <div class="h-1 bg-gradient-to-r from-rose-500 to-rose-400 w-full"></div>
                <div class="p-3.5 sm:p-4 pb-2">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block truncate">Task Logs</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-[#c3122e] shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 022 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 tracking-tight leading-none mb-1">{{ $taskUpdatesCount }}</div>
                </div>
            </div>
            <div class="px-3.5 pb-3.5 sm:px-4 sm:pb-4 pt-0">
                <div class="text-[11px] font-semibold text-slate-400 truncate">
                    WBS item updates
                </div>
            </div>
        </div>

        <!-- 3. Project Summaries -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-150 overflow-hidden flex flex-col justify-between h-full">
            <div>
                <div class="h-1 bg-gradient-to-r from-amber-500 to-amber-400 w-full"></div>
                <div class="p-3.5 sm:p-4 pb-2">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block truncate">Project Summaries</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 tracking-tight leading-none mb-1">{{ $projectUpdatesCount }}</div>
                </div>
            </div>
            <div class="px-3.5 pb-3.5 sm:px-4 sm:pb-4 pt-0">
                <div class="text-[11px] font-semibold text-slate-400 truncate">
                    Overall status logs
                </div>
            </div>
        </div>

        <!-- 4. Pending Review -->
        <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-150 overflow-hidden flex flex-col justify-between h-full">
            <div>
                <div class="h-1 {{ $uncommentedUpdatesCount > 0 ? 'bg-gradient-to-r from-indigo-500 to-indigo-400' : 'bg-gradient-to-r from-emerald-500 to-emerald-400' }} w-full"></div>
                <div class="p-3.5 sm:p-4 pb-2">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-wider block truncate">Pending Review</span>
                        <div class="w-8 h-8 rounded-lg {{ $uncommentedUpdatesCount > 0 ? 'bg-indigo-50 text-indigo-500' : 'bg-emerald-50 text-emerald-500' }} flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black tracking-tight leading-none mb-1 {{ $uncommentedUpdatesCount > 0 ? 'text-indigo-600' : 'text-slate-900' }}">{{ $uncommentedUpdatesCount }}</div>
                </div>
            </div>
            <div class="px-3.5 pb-3.5 sm:px-4 sm:pb-4 pt-0">
                @if($uncommentedUpdatesCount > 0)
                    <div class="text-[11px] font-semibold text-indigo-600 truncate">Awaiting feedback</div>
                @else
                    <div class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>All reviewed</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CONTROLS, SEARCH & FILTER TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-slate-200/90 shadow-2xs">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <!-- Search Box -->
            <div class="relative flex-1 min-w-[220px]">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search project, task, reporter, or keywords..."
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
         4. PROJECTS DAILY UPDATES MATRIX TABLE
         ═══════════════════════════════════════════════════════════════ -->
    <!-- ═══════════════════════════════════════════════════════════════
         4. PROJECTS DAILY UPDATES MATRIX TABLE (PMO & TEAM MATRIX)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="w-full rounded-2xl bg-white shadow-2xs border border-slate-200/80 overflow-hidden">
        <!-- Table Title Header Bar -->
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200/80 bg-white flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center shadow-2xs shrink-0">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Projects Daily Status Matrix
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">
                        Latest execution reports &amp; progress logs across active projects
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-slate-700 bg-slate-100/80 border border-slate-200/80">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ count($groupedProjectUpdates) }} Active Projects</span>
                </span>
            </div>
        </div>

        <div class="w-full overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-[720px] lg:min-w-full">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[10.5px] font-extrabold uppercase tracking-wider text-slate-400" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <th class="py-3.5 pl-5 sm:pl-6 pr-3 w-[26%] sm:w-[20%] lg:w-[18%]">Project &amp; Code</th>
                        <th class="py-3.5 px-3 w-[16%] lg:w-[14%] hidden md:table-cell">Subsidiary</th>
                        <th class="py-3.5 px-3 w-[18%] sm:w-[16%] lg:w-[14%]">Project Manager</th>
                        <th class="py-3.5 px-3 w-[38%] sm:w-[34%] lg:w-[34%]">Latest Status Log</th>
                        <th class="py-3.5 px-3 w-[10%] hidden lg:table-cell">Last Logged</th>
                        <th class="py-3.5 pl-2 pr-5 sm:pr-6 text-right whitespace-nowrap w-[18%] sm:w-[14%] lg:w-[10%]">Actions</th>
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
                            $subsidiary = $group['project']->subsidiary ?? null;

                            $pmName = $pm->name ?? 'Unassigned';
                            $pmParts = explode(' ', trim($pmName));
                            $pmInitials = count($pmParts) >= 2 
                                ? strtoupper(substr($pmParts[0], 0, 1) . substr($pmParts[count($pmParts) - 1], 0, 1))
                                : strtoupper(substr($pmName, 0, 2));
                        @endphp

                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Project Code & Name -->
                            <td class="py-3.5 pl-5 sm:pl-6 pr-3 align-middle">
                                <div class="space-y-1 min-w-0">
                                    <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-xs sm:text-[13px] text-slate-900 hover:text-[#c3122e] transition-colors block leading-snug truncate group-hover:text-[#c3122e]" title="{{ $project->name }}">
                                        {{ $project->name }}
                                    </a>
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <span class="px-1.5 py-0.5 rounded-md text-[9.5px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-200/80 shrink-0 leading-none shadow-2xs">
                                            {{ $project->code ?? 'PRJ-' . $project->id }}
                                        </span>
                                        <span class="text-[10.5px] text-slate-400 font-medium whitespace-nowrap">
                                            • {{ $allUpdatesCount }} {{ Str::plural('update', $allUpdatesCount) }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Subsidiary Name -->
                            <td class="py-3.5 px-3 hidden md:table-cell align-middle whitespace-nowrap">
                                @if($subsidiary)
                                    <div class="flex items-center gap-2 text-slate-700 min-w-0">
                                        <div class="w-6 h-6 rounded-md bg-slate-100 flex items-center justify-center shrink-0 text-slate-400 border border-slate-200/60">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-xs text-slate-800 truncate" title="{{ $subsidiary->name }}">{{ $subsidiary->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium italic text-xs">—</span>
                                @endif
                            </td>

                            <!-- Project Manager -->
                            <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-6.5 h-6.5 rounded-full bg-slate-900 text-white flex items-center justify-center font-black text-[9.5px] shrink-0 border border-slate-700 shadow-2xs">
                                        {{ $pmInitials }}
                                    </div>
                                    <div class="min-w-0 flex items-center gap-1.5">
                                        <span class="font-bold text-slate-900 truncate text-xs" title="{{ $pmName }}">{{ $pmName }}</span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80">PM</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Latest Update Summary -->
                            <td class="py-3.5 px-3 align-middle">
                                @if($update)
                                    <div class="min-w-0">
                                        <p class="text-xs text-slate-900 font-bold leading-snug truncate bg-slate-50/90 px-3 py-1.5 rounded-lg border border-slate-200/80 shadow-2xs" title="{{ $update->summary }}">
                                            "{{ $update->summary }}"
                                        </p>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-slate-400 italic text-xs font-medium">
                                        <span>No status updates logged yet</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Last Log Date -->
                            <td class="py-3.5 px-3 hidden lg:table-cell align-middle text-slate-600 font-semibold whitespace-nowrap" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                @if($update)
                                    <div class="flex items-center gap-1.5 text-xs text-slate-700 font-bold">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $update->created_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-light">—</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 pl-2 pr-5 sm:pr-6 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    @if(!$isSuperAdmin)
                                        <button
                                            wire:click="openStatusUpdateModal({{ $project->id }})"
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00f26] shadow-2xs hover:shadow-xs transition-all cursor-pointer"
                                        >
                                            + Log Update
                                        </button>
                                    @endif

                                    <!-- View History Modal Trigger -->
                                    <button
                                        wire:click="openHistoryModal({{ $project->id }})"
                                        type="button"
                                        class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-[#c3122e] hover:text-white border border-slate-200/80 transition-all cursor-pointer flex items-center gap-1.5 shadow-2xs group-hover:shadow-xs"
                                        title="View status logs history for {{ $project->name }}"
                                    >
                                        <span>Updates ({{ $allUpdatesCount }})</span>
                                        <svg class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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

        <!-- Table Pagination Footer -->
        <div class="px-6 py-4 bg-white border-t border-slate-100">
            {{ $groupedProjectUpdates->links() }}
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODAL: PROJECT STATUS UPDATE HISTORY & FEEDBACK LOOP POPUP
         ═══════════════════════════════════════════════════════════════ -->
    @if($showHistoryModal && $historyProjectId)
        @php
            $historyProject = \App\Models\Project::with(['projectManager', 'subsidiary'])->find($historyProjectId);
            $historyAllUpdates = $historyProject 
                ? \App\Models\ProjectStatusUpdate::with(['creator', 'comments.user', 'wbsItem'])
                    ->where('project_id', $historyProjectId)
                    ->latest()
                    ->get()
                : collect();
            $historyLatestUpdate = $selectedHistoryUpdateId 
                ? ($historyAllUpdates->firstWhere('id', $selectedHistoryUpdateId) ?? $historyAllUpdates->first())
                : $historyAllUpdates->first();
            $historyUpdatesCount = $historyAllUpdates->count();
        @endphp

        @if($historyProject)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 animate-fade-in" x-data="{ modalTab: 'report' }">
                <!-- Overlay Backdrop with soft blur -->
                <div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-slate-900/40" wire:click="closeHistoryModal"></div>

                <!-- Popup Content Box -->
                <div class="relative bg-white rounded-2xl max-w-4xl w-full shadow-xl z-10 flex flex-col max-h-[90vh] md:max-h-[85vh] overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150 mx-auto">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 bg-white flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center text-base font-bold flex-shrink-0">
                                📊
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold text-slate-900 leading-tight">
                                        Project Activity &amp; Progress Logs
                                    </h3>
                                    @if($historyProject->code)
                                        <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $historyProject->code }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">
                                    {{ $historyProject->name }} · <span class="text-slate-700 font-semibold">{{ $historyUpdatesCount }} progress log(s)</span>
                                </p>
                            </div>
                        </div>
                        <button 
                            wire:click="closeHistoryModal" 
                            type="button" 
                            class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex items-center justify-center text-lg font-bold cursor-pointer"
                            title="Close Window"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Modal Navigation Tabs Bar -->
                    <div class="px-6 pt-3 pb-0 bg-slate-50/70 border-b border-slate-200/80 flex items-center gap-2 overflow-x-auto scrollbar-none flex-shrink-0">
                        <button 
                            @click="modalTab = 'report'" 
                            :class="modalTab === 'report' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>📌 Progress Report</span>
                        </button>

                        <button 
                            @click="modalTab = 'discussion'" 
                            :class="modalTab === 'discussion' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>💬 Team Discussion</span>
                            @php $commentsCnt = $historyLatestUpdate ? $historyLatestUpdate->comments->count() : 0; @endphp
                            <span :class="modalTab === 'discussion' ? 'bg-rose-50 text-[#c3122e] border-rose-200' : 'bg-slate-200/70 text-slate-600 border-slate-300'" class="px-2 py-0.2 rounded-full text-[10px] font-bold border">
                                {{ $commentsCnt }}
                            </span>
                        </button>

                        <button 
                            @click="modalTab = 'history'" 
                            :class="modalTab === 'history' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>📜 Log History</span>
                            <span :class="modalTab === 'history' ? 'bg-rose-50 text-[#c3122e] border-rose-200' : 'bg-slate-200/70 text-slate-600 border-slate-300'" class="px-2 py-0.2 rounded-full text-[10px] font-bold border">
                                {{ $historyUpdatesCount }}
                            </span>
                        </button>
                    </div>

                    <!-- Modal Body Container -->
                    <div class="flex-1 overflow-y-auto p-6 bg-slate-50/50">
                        @if($historyLatestUpdate)
                            <!-- TAB 1: PROGRESS REPORT -->
                            <div x-show="modalTab === 'report'" class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Active Progress Report Details</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                                        Log #{{ $historyUpdatesCount }}
                                    </span>
                                </div>

                                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                                    <!-- Author Row -->
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-9 h-9 rounded-xl font-bold flex items-center justify-center text-xs text-white shadow-2xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                {{ strtoupper(substr($historyLatestUpdate->creator->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-sm text-slate-900 block leading-tight">{{ $historyLatestUpdate->creator->name ?? 'User' }}</span>
                                                <span class="text-xs text-slate-500 font-medium block mt-0.5">{{ $historyLatestUpdate->created_at->format('M d, Y · h:i A') }} ({{ $historyLatestUpdate->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                        <button @click="modalTab = 'discussion'" class="text-xs font-bold text-[#c3122e] hover:underline flex items-center gap-1 cursor-pointer">
                                            <span>💬 {{ $historyLatestUpdate->comments->count() }} comment(s)</span>
                                            <span>→</span>
                                        </button>
                                    </div>

                                    <!-- Content -->
                                    <div class="space-y-2">
                                        <h4 class="font-bold text-base text-slate-900 leading-snug">{{ $historyLatestUpdate->title }}</h4>
                                        
                                        <!-- Summary Box -->
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                            {{ $historyLatestUpdate->summary }}
                                        </div>
                                    </div>

                                    <!-- Work Completed & Blockers -->
                                    @if($historyLatestUpdate->work_completed || $historyLatestUpdate->current_blockers)
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                                            @if($historyLatestUpdate->work_completed)
                                                <div class="p-4 rounded-xl bg-emerald-50/80 text-emerald-900 border border-emerald-200/70 font-medium flex items-start gap-2.5">
                                                    <span class="text-emerald-600 font-bold text-sm">✓</span>
                                                    <div>
                                                        <strong class="font-bold text-emerald-950 block mb-0.5">Work Completed</strong> 
                                                        <span class="leading-relaxed">{{ $historyLatestUpdate->work_completed }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($historyLatestUpdate->current_blockers)
                                                <div class="p-4 rounded-xl bg-rose-50/80 text-rose-900 border border-rose-200/70 font-medium flex items-start gap-2.5">
                                                    <span class="text-rose-600 font-bold text-sm">⚠️</span>
                                                    <div>
                                                        <strong class="font-bold text-rose-950 block mb-0.5">Reported Blockers</strong> 
                                                        <span class="leading-relaxed">{{ $historyLatestUpdate->current_blockers }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- TAB 2: TEAM DISCUSSION -->
                            <div x-show="modalTab === 'discussion'" class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span>💬 Team Discussion &amp; Feedback Thread</span>
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium">{{ $historyLatestUpdate->comments->count() }} comment(s)</span>
                                </div>

                                <!-- Add Comment Form -->
                                <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                                    <label class="text-xs font-bold text-slate-800 block">Add Feedback or Comment</label>
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            wire:model="newCommentContent.{{ $historyLatestUpdate->id }}"
                                            wire:keydown.enter="addComment({{ $historyLatestUpdate->id }})"
                                            placeholder="Type a feedback comment or question for the team..."
                                            class="w-full text-xs font-medium py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all flex-1"
                                        >
                                        <button
                                            wire:click="addComment({{ $historyLatestUpdate->id }})"
                                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-[#c3122e] hover:bg-[#8b0d1f] shadow-xs active:scale-95 transition-all cursor-pointer flex-shrink-0"
                                        >
                                            Post Comment
                                        </button>
                                    </div>
                                </div>

                                <!-- Comments Thread List -->
                                <div class="space-y-3 max-h-[360px] overflow-y-auto pr-1">
                                    @forelse($historyLatestUpdate->comments as $comment)
                                        <div class="p-4 rounded-xl bg-white border border-slate-200 text-xs space-y-1.5 shadow-2xs">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-lg bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center">
                                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span class="font-bold text-slate-900">{{ $comment->user->name ?? 'User' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] text-slate-500 font-semibold whitespace-nowrap">
                                                        {{ $comment->created_at->format('M d, Y · h:i A') }} ({{ $comment->created_at->diffForHumans() }})
                                                    </span>
                                                    @if($isSuperAdmin || $comment->user_id === auth()->id() || ($historyProject && $historyProject->project_manager_id === auth()->id()))
                                                        <button
                                                            wire:click="deleteComment({{ $comment->id }})"
                                                            wire:confirm="Are you sure you want to delete this comment?"
                                                            type="button"
                                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                                            title="Delete Comment"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-slate-700 leading-relaxed pt-1 font-medium">{{ $comment->content }}</p>
                                        </div>
                                    @empty
                                        <div class="py-12 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200 p-6 space-y-1">
                                            <div class="text-2xl">💬</div>
                                            <p class="font-bold text-slate-700 text-sm">No feedback comments yet</p>
                                            <p class="text-slate-400 text-xs">Be the first to share feedback or ask a question for this project update!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @else
                            <div class="py-10 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200">
                                No status update logs posted yet for this project.
                            </div>
                        @endif

                        <!-- TAB 3: LOG HISTORY -->
                        <div x-show="modalTab === 'history'" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span>📜 Execution History &amp; Past Log Updates</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium italic">Click any report below to load its full details</span>
                            </div>

                            <div class="space-y-3">
                                @forelse($historyAllUpdates as $pIndex => $pUpdate)
                                    @php
                                        $pCreator = $pUpdate->creator;
                                        $pCreatorRole = 'Collaborator';
                                        $pBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';

                                        if ($pCreator) {
                                            if ($pCreator->hasRole('super_admin')) {
                                                $pCreatorRole = 'PMO Admin';
                                                $pBadgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                            } elseif ($pCreator->hasRole('project_manager') || $historyProject->project_manager_id === $pCreator->id) {
                                                $pCreatorRole = 'Project Manager';
                                                $pBadgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                                            }
                                        }
                                        $isSelectedLog = ($historyLatestUpdate && $historyLatestUpdate->id === $pUpdate->id);
                                    @endphp
                                    <div @click="modalTab = 'report'"
                                         wire:click="selectHistoryUpdate({{ $pUpdate->id }})" 
                                         class="p-4 rounded-xl bg-white border {{ $isSelectedLog ? 'border-[#c3122e] bg-rose-50/20 shadow-xs' : 'border-slate-200 hover:border-slate-300' }} transition-all cursor-pointer space-y-2"
                                         title="Click to view details for this log">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                    #{{ $historyUpdatesCount - $pIndex }}
                                                </span>
                                                <h6 class="font-bold text-xs text-slate-900">{{ $pUpdate->title }}</h6>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="font-bold text-slate-800">{{ $pCreator->name ?? 'User' }}</span>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $pBadgeClass }}">{{ $pCreatorRole }}</span>
                                                <span class="text-slate-500 text-[10px] font-medium">{{ $pUpdate->created_at->format('M d, Y · h:i A') }} ({{ $pUpdate->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                        
                                        <div class="p-2.5 rounded-lg text-xs bg-slate-50 text-slate-700 leading-relaxed font-medium">
                                            {{ $pUpdate->summary }}
                                        </div>
                                        
                                        @if($pUpdate->work_completed || $pUpdate->current_blockers)
                                            <div class="flex flex-wrap gap-2 text-[10px] pt-0.5">
                                                @if($pUpdate->work_completed)
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">
                                                        ✓ {{ $pUpdate->work_completed }}
                                                    </span>
                                                @endif
                                                @if($pUpdate->current_blockers)
                                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200 font-medium">
                                                        ⚠️ {{ $pUpdate->current_blockers }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200">
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

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-0 overflow-hidden shadow-2xl z-10 border border-slate-200/90 animate-in fade-in zoom-in-95 duration-200 mx-3 sm:mx-auto max-h-[90vh] overflow-y-auto">
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
