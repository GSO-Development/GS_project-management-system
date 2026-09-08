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
                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200/70 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $updatedTodayCount }} Logged Today</span>
                </span>
            </div>
        </div>

        <!-- Right Side: Publish Button -->
        <div class="flex items-center gap-3 flex-shrink-0">
            @if(!$isSuperAdmin)
                <button
                    wire:click="openStatusUpdateModal()"
                    type="button"
                    class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 cursor-pointer"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Publish Daily Log</span>
                </button>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI METRICS SUMMARY (CLEAN MODERN CARDS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- 1. Logged Today -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-center justify-between gap-3">
            <div class="space-y-1 min-w-0">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider truncate">Logged Today</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $updatedTodayCount }}</span>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/70 px-2 py-0.5 rounded-full inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-50 text-emerald-600 border border-emerald-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- 2. Task Logs -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-center justify-between gap-3">
            <div class="space-y-1 min-w-0">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider truncate">Task Logs</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $taskUpdatesCount }}</span>
                    <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200/70 px-2 py-0.5 rounded-full">
                        Tasks
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-rose-50 text-[#c3122e] border border-rose-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>

        <!-- 3. Overall Reports -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-center justify-between gap-3">
            <div class="space-y-1 min-w-0">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider truncate">Project Summaries</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $projectUpdatesCount }}</span>
                    <span class="text-[10px] font-bold text-amber-800 bg-amber-50 border border-amber-200/70 px-2 py-0.5 rounded-full">
                        Overall
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50 text-amber-600 border border-amber-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>

        <!-- 4. Pending Feedback -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 flex items-center justify-between gap-3">
            <div class="space-y-1 min-w-0">
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider truncate">Pending Review</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $uncommentedUpdatesCount }}</span>
                    <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200/70 px-2 py-0.5 rounded-full">
                        Unanswered
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-indigo-50 text-indigo-600 border border-indigo-100">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
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
    <div class="w-full rounded-2xl bg-white shadow-xs border border-slate-200/90 overflow-hidden">
        <!-- Table Title Header Bar -->
        <div class="px-6 py-4 border-b border-slate-200/80 bg-white flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Project Status Updates Matrix
                    </h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                        Latest execution reports & progress logs across active projects
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
                                    @if(!$isSuperAdmin)
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
                                                            <strong class="text-rose-950 font-black">Reported Blockers:</strong> 
                                                            <span>{{ $historyLatestUpdate->current_blockers }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right side: Feedback & Comments Loop (42%) -->
                                <div class="w-full md:w-[42%] space-y-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider text-slate-800 bg-white border border-slate-200">
                                        💬 Feedback Discussion ({{ $historyLatestUpdate->comments->count() }})
                                    </div>

                                    <!-- Comments Thread Box -->
                                    <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 daily-update-scroll">
                                        @forelse($historyLatestUpdate->comments as $comment)
                                            <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
                                                <div class="w-7 h-7 rounded-xl bg-slate-900 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0 space-y-1">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-extrabold text-xs text-slate-900">{{ $comment->user->name ?? 'User' }}</span>
                                                        <span class="text-[9px] font-mono font-bold text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
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
                                    <div class="relative group cursor-pointer" wire:click="selectHistoryUpdate({{ $pUpdate->id }})" title="Click to view details and feedback comments for this log">
                                        <!-- Timeline Dot Node -->
                                        <span class="absolute -left-[24px] top-2 w-3.5 h-3.5 rounded-full border-2 border-white shadow-2xs flex-shrink-0 transition-transform group-hover:scale-125" style="background: {{ ($historyLatestUpdate && $historyLatestUpdate->id === $pUpdate->id) ? '#c3122e' : ($pIndex === 0 ? '#c3122e' : '#cbd5e1') }};"></span>

                                        <!-- Log Card Content -->
                                        <div class="p-5 rounded-2xl bg-white border {{ ($historyLatestUpdate && $historyLatestUpdate->id === $pUpdate->id) ? 'border-[#c3122e] ring-2 ring-[#c3122e]/10 shadow-md' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }} transition-all duration-200 space-y-3">
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
