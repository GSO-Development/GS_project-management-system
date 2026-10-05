<div class="space-y-5 pb-12">
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
        @keyframes slideDownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .filter-toolbar-enter {
            animation: slideDownFade 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & ACTION CONTROLS
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-1 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#c3122e]/10 text-[#c3122e] tracking-wide uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                    Progress Tracking
                </span>
            </div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Daily Status Updates
                </h1>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold text-[#c3122e] bg-rose-50 border border-rose-200/60">
                    {{ $totalUpdatesCount }} Total Logs
                </span>
                @if($updatedTodayCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/60 inline-flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ $updatedTodayCount }} today</span>
                    </span>
                @endif
            </div>
        </div>

        <!-- Top Right Action Controls (Filter toggle button only, post update button removed per user request) -->
        <div class="flex items-center gap-2 self-start sm:self-center">
            <!-- Filter Toggle Button -->
            <button wire:click="toggleFilters" type="button"
                    class="px-3.5 py-2 border rounded-xl text-xs font-bold flex items-center gap-2 transition-all cursor-pointer active:scale-95 {{ $showFilters ? 'bg-slate-100 border-slate-300 text-slate-900 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700 hover:text-slate-900 shadow-2xs' }}">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>Filter</span>
                @if($hasActiveFilters)
                    <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                @endif
            </button>
        </div>
    </div>    <!-- ═══════════════════════════════════════════════════════════════
         2. COLLAPSIBLE FILTER TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    @if($showFilters)
        <div wire:key="daily-updates-filter-toolbar" class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-2xs filter-toolbar-enter">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                {{-- Search Box --}}
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="searchQuery" type="text"
                           placeholder="Search project, task, reporter..."
                           class="w-full pl-9 pr-8 py-2.5 bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all">
                    @if($searchQuery)
                        <button type="button" wire:click="$set('searchQuery', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer p-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                {{-- Subsidiary Selector Dropdown --}}
                <div>
                    <select wire:model.live="selectedSubsidiaryId"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="">All Subsidiaries</option>
                        @foreach($subsidiaries as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Project Selector --}}
                <div>
                    <select wire:model.live="selectedProjectId"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="">All Accessible Projects</option>
                        @foreach($accessibleProjects as $p)
                            @if(!$selectedSubsidiaryId || $p->subsidiary_id == $selectedSubsidiaryId)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Scope Filter Dropdown --}}
                <div>
                    <select wire:model.live="selectedScope"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="all">All Scopes</option>
                        <option value="task">Task Logs Only</option>
                        <option value="project">Project Summaries Only</option>
                    </select>
                </div>

                {{-- Date Filter Dropdown --}}
                <div>
                    <select wire:model.live="dateFilter"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                    </select>
                </div>
            </div>

            {{-- Filter Footer --}}
            @if($hasActiveFilters)
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-medium">Filtered results applied</span>
                    <button wire:click="clearFilters" type="button" class="text-[#c3122e] font-bold hover:underline cursor-pointer flex items-center gap-1 active:scale-95 transition-transform">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Clear All Filters</span>
                    </button>
                </div>
            @endif
        </div>
    @endif    <!-- ═══════════════════════════════════════════════════════════════
         3. PROJECTS DAILY UPDATES MATRIX TABLE
         ═══════════════════════════════════════════════════════════════ -->
    <div class="w-full rounded-2xl bg-white shadow-xs border border-slate-200/90 overflow-hidden transition-all">
        <!-- Section Bar / Table Header Toolbar -->
        <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between gap-4 flex-wrap bg-white">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-50 via-white to-slate-50 border border-rose-200/60 flex items-center justify-center text-[#c3122e] shadow-2xs">
                    <svg class="w-5 h-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Active Projects Status
                    </h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 text-xs">
                @if($hasActiveFilters)
                    <div class="flex items-center gap-2 bg-rose-50/80 border border-rose-200/70 px-3 py-1.5 rounded-xl shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                        <span class="text-xs font-bold text-rose-900">Filtered view</span>
                        <button wire:click="clearFilters" type="button" class="ml-1.5 text-xs text-[#c3122e] font-bold hover:underline cursor-pointer flex items-center gap-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Reset</span>
                        </button>
                    </div>
                @else
                    <span class="text-xs text-slate-500 font-medium hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50/80 border border-slate-200/80 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Click <strong class="text-slate-800 font-bold">"Update"</strong> to log progress or <strong class="text-slate-800 font-bold">"Logs"</strong> to view history</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/90 text-[10.5px] font-bold uppercase tracking-wider text-slate-500" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <th scope="col" class="py-3.5 pl-6 pr-4 w-[28%]">Project Details</th>
                        <th scope="col" class="py-3.5 px-4 w-[18%]">Project Manager</th>
                        <th scope="col" class="py-3.5 px-4 w-[34%]">Latest Progress Log</th>
                        <th scope="col" class="py-3.5 px-4 w-[10%] hidden lg:table-cell">Last Activity</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right w-[10%] whitespace-nowrap">Actions</th>
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

                            $pmName = $pm->name ?? 'Unassigned';
                            $pmParts = explode(' ', trim($pmName));
                            $pmInitials = count($pmParts) >= 2 
                                ? strtoupper(substr($pmParts[0], 0, 1) . substr($pmParts[count($pmParts) - 1], 0, 1))
                                : strtoupper(substr($pmName, 0, 2));

                            $projInitial = strtoupper(substr($project->name, 0, 1));
                        @endphp

                        <tr class="hover:bg-slate-50/80 transition-all duration-150 group">
                            <!-- Project & Code -->
                            <td class="py-4 pl-6 pr-4 align-middle">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-10 h-10 rounded-full bg-rose-50 text-[#c3122e] border border-rose-200/80 flex items-center justify-center font-extrabold text-sm shrink-0 transition-all duration-200 shadow-2xs group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e]">
                                        {{ $projInitial }}
                                    </div>
                                    <div class="min-w-0 flex-1 space-y-1">
                                        <a href="{{ route('projects.show', $project) }}" class="font-bold text-xs sm:text-[13.5px] text-slate-900 hover:text-[#c3122e] transition-colors truncate block max-w-[260px]" title="{{ $project->name }}">
                                            {{ $project->name }}
                                        </a>
                                        <div class="flex items-center">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200/80 shrink-0 shadow-2xs">
                                                {{ $project->code ?? 'PRJ-' . $project->id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Project Manager -->
                            <td class="py-4 px-4 align-middle">
                                <span class="font-bold text-slate-800 text-xs sm:text-[13px] truncate block group-hover:text-slate-950 transition-colors" title="{{ $pmName }}">{{ $pmName }}</span>
                            </td>

                            <!-- Latest Update Summary -->
                            <td class="py-4 px-4 align-middle">
                                @if($update)
                                    @php
                                        $statusKey = $update->updated_status?->value ?? (string)$update->updated_status;
                                        $statusConfig = match($statusKey) {
                                            'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200/80', 'dot' => 'bg-emerald-500', 'label' => 'Completed'],
                                            'on_hold' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200/80', 'dot' => 'bg-amber-500', 'label' => 'On Hold'],
                                            default => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200/80', 'dot' => 'bg-blue-500', 'label' => 'In Progress'],
                                        };
                                    @endphp
                                    <div class="p-3 rounded-xl bg-slate-50/70 group-hover:bg-white border border-slate-200/70 group-hover:border-slate-300/80 transition-all duration-150 space-y-1.5 min-w-0 max-w-lg shadow-2xs">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} shadow-2xs">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                                {{ $statusConfig['label'] }}
                                            </span>
                                            @if($update->wbsItem)
                                                <span class="inline-flex items-center gap-1 text-[10.5px] text-slate-600 font-medium bg-white px-2 py-0.5 rounded-md border border-slate-200/70 truncate max-w-[210px] shadow-2xs" title="{{ $update->wbsItem->title }}">
                                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    <span class="truncate">{{ $update->wbsItem->title }}</span>
                                                </span>
                                            @endif
                                            @if($update->current_blockers)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200/70 shadow-2xs">
                                                    <svg class="w-3 h-3 text-rose-500 shrink-0 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    Blocker
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-700 font-normal line-clamp-2 leading-relaxed" title="{{ $update->summary }}">
                                            {{ $update->summary }}
                                        </p>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 py-1.5 px-3 rounded-xl bg-slate-50/80 border border-dashed border-slate-200/90 text-slate-400 text-[11px] font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                        <span>No activity logs recorded yet</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Last Activity -->
                            <td class="py-4 px-4 align-middle text-slate-500 whitespace-nowrap hidden lg:table-cell">
                                @if($update)
                                    <div class="space-y-0.5">
                                        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $update->created_at->diffForHumans() }}
                                        </span>
                                        <span class="text-[10.5px] text-slate-400 block font-mono pl-5">{{ $update->created_at->format('M d, Y') }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-mono text-xs pl-2">—</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 pl-3 pr-6 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button
                                        wire:click="openStatusUpdateModal({{ $project->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold text-white shadow-xs hover:shadow active:scale-95 transition-all cursor-pointer"
                                        style="background: linear-gradient(135deg, #c3122e 0%, #9e0e25 100%);"
                                        title="Log new daily progress update"
                                    >
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        <span>Update</span>
                                    </button>

                                    <button
                                        wire:click="openHistoryModal({{ $project->id }})"
                                        type="button"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 border border-slate-200/90 shadow-2xs hover:shadow-xs active:scale-95 transition-all cursor-pointer"
                                        title="View project logs history"
                                    >
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Logs</span>
                                        <span class="px-1.5 py-0.2 rounded-md {{ $allUpdatesCount > 0 ? 'bg-rose-50 text-[#c3122e] border border-rose-200/70' : 'bg-slate-100 text-slate-500 border border-slate-200' }} text-[10px] font-black">
                                            {{ $allUpdatesCount }}
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-xs text-slate-400">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No Projects Found</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">No accessible projects match your current search and filter criteria.</p>
                                @if($hasActiveFilters)
                                    <div class="pt-3">
                                        <button wire:click="clearFilters" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#c3122e] bg-rose-50 border border-rose-100 hover:bg-rose-100 active:scale-95 transition-all cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>Clear All Filters</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        @if($groupedProjectUpdates->hasPages())
            <div class="px-6 py-4 bg-white border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
                <span class="text-xs text-slate-500 font-medium">
                    Showing <strong class="text-slate-800">{{ $groupedProjectUpdates->firstItem() }}</strong> to <strong class="text-slate-800">{{ $groupedProjectUpdates->lastItem() }}</strong> of <strong class="text-slate-800">{{ $groupedProjectUpdates->total() }}</strong> projects
                </span>
                <div>
                    {{ $groupedProjectUpdates->links() }}
                </div>
            </div>
        @endif
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
                <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="closeHistoryModal"></div>

                <!-- Popup Content Box -->
                <div class="relative bg-white rounded-2xl max-w-4xl w-full shadow-2xl z-10 flex flex-col max-h-[90vh] md:max-h-[85vh] overflow-hidden border border-slate-200 animate-in fade-in zoom-in-95 duration-150 mx-auto">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 bg-white flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                                <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">
                                        Project Activity &amp; Progress Logs
                                    </h3>
                                    @if($historyProject->code)
                                        <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $historyProject->code }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 font-normal mt-0.5 truncate">
                                    {{ $historyProject->name }} &bull; <span class="text-slate-700 font-semibold">{{ $historyUpdatesCount }} progress log(s)</span>
                                </p>
                            </div>
                        </div>
                        <button 
                            wire:click="closeHistoryModal" 
                            type="button" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                            title="Close Window"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showUpdateModal', false)"></div>

            <div class="relative bg-white rounded-2xl max-w-xl w-full p-0 overflow-hidden shadow-2xl z-10 border border-slate-200 animate-in fade-in zoom-in-95 duration-200 mx-3 sm:mx-auto max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Publish Daily Progress Log</h3>
                            <p class="text-xs text-slate-500 font-normal mt-0.5">Log task execution progress or overall project status update</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showUpdateModal', false)" 
                        type="button" 
                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                        title="Close Window"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
