<div class="space-y-6 pt-1">
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
        .task-checkbox:checked {
            background-color: #059669;
            border-color: #059669;
        }
        .kanban-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .kanban-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .kanban-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .kanban-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER & MULTI-PROJECT CONTROLS
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-7 lg:p-8 text-white mb-6" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="My Tasks Banner" 
                class="w-full h-full object-cover object-center"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#140205] via-[#24030a]/90 to-transparent lg:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/50 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5 sm:gap-6">
            <!-- Left Side: Icon + Title + Meta Hierarchy -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <!-- 3D Productivity App Icon Container -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-2 ring-black/60 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/tasks-banner.jpg') }}" alt="My Tasks" class="w-full h-full object-cover rounded-xl shadow-inner">
                </div>

                <div class="min-w-0 space-y-2 flex-1">
                    <!-- Suite Breadcrumb & Live Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-300 font-mono">
                            ⚡ PERSONAL PRODUCTIVITY &amp; WORKLOAD
                        </span>
                        <span class="text-white/30 text-xs">•</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-emerald-300 border border-emerald-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $activeProjectsCount }} {{ Str::plural('Project', $activeProjectsCount) }}</span>
                        </span>
                        @if(($dueTodayCount + $overdueCount) > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>{{ $dueTodayCount + $overdueCount }} Urgent / Due</span>
                            </span>
                        @endif
                    </div>

                    <!-- Main Title -->
                    <h1 class="text-xl sm:text-2xl lg:text-[27px] font-black text-white tracking-tight leading-tight drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        My Tasks
                    </h1>

                    <!-- Date & Subtitle Badge -->
                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <span class="text-rose-300 font-bold flex items-center gap-1">
                            <span>📅 {{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-white/30">•</span>
                        <span class="text-slate-300 text-xs">Multi-Project Deliverables &amp; Level 4 Hour Scheduling</span>
                    </div>
                </div>
            </div>

            <!-- Right: View Mode Switcher -->
            <div class="flex items-center gap-3 flex-shrink-0 self-stretch sm:self-auto justify-between sm:justify-end flex-wrap">
                <div class="inline-flex p-1 rounded-xl border border-white/20 shadow-xl backdrop-blur-xl" style="background: rgba(0, 0, 0, 0.55);">
                    <button 
                        wire:click="setViewMode('table')" 
                        type="button" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-slate-900 shadow-sm scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                        title="Grouped Table View"
                    >
                        <span>📑 Table</span>
                    </button>
                    <button 
                        wire:click="setViewMode('kanban')" 
                        type="button" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white text-[#c3122e] shadow-sm scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                        title="Kanban Board"
                    >
                        <span>📋 Kanban</span>
                    </button>
                    <button 
                        wire:click="setViewMode('timeline')" 
                        type="button" 
                        class="px-3.5 py-1.5 rounded-lg text-xs font-black transition-all duration-200 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'timeline' ? 'bg-white text-[#c3122e] shadow-sm scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                        title="Hourly Timeline"
                    >
                        <span>⏱️ Schedule</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. EXECUTIVE FILTER & NAVIGATION TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden mb-6">
        <!-- Row 1: Primary Status Segment Tabs (Top Level Hierarchy) -->
        <div class="px-5 pt-3.5 pb-0 border-b border-slate-100 flex items-center justify-between gap-4 overflow-x-auto scrollbar-none bg-white">
            <div class="flex items-center gap-6">
                <!-- All Tasks -->
                <button wire:click="clearFilters" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>All Tasks</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold font-mono inline-flex items-center justify-center leading-none {{ $totalCount > 0 ? 'bg-slate-100 text-slate-700' : 'bg-slate-50 text-slate-400' }}">
                        {{ $totalCount }}
                    </span>
                </button>

                <!-- Due Today -->
                <button wire:click="setDueDateFilter('today'); $set('statusFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $dueDateFilter === 'today' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Due Today</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold font-mono inline-flex items-center justify-center leading-none {{ $dueTodayCount > 0 ? 'bg-amber-100/90 text-amber-800 border border-amber-200/60' : 'bg-slate-100 text-slate-400' }}">
                        {{ $dueTodayCount }}
                    </span>
                </button>

                <!-- Overdue -->
                <button wire:click="setDueDateFilter('overdue'); $set('statusFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $dueDateFilter === 'overdue' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Overdue</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-black font-mono inline-flex items-center justify-center leading-none {{ $overdueCount > 0 ? 'bg-rose-50 text-[#c3122e] border border-rose-200/80 shadow-2xs' : 'bg-slate-100 text-slate-400' }}">
                        {{ $overdueCount }}
                    </span>
                </button>

                <!-- In Progress -->
                <button wire:click="$set('statusFilter', 'in_progress'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'in_progress' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>In Progress</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold font-mono inline-flex items-center justify-center leading-none {{ $inProgressCount > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-slate-100 text-slate-400' }}">
                        {{ $inProgressCount }}
                    </span>
                </button>

                <!-- Blocked -->
                <button wire:click="$set('statusFilter', 'blocked'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'blocked' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Blocked</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold font-mono inline-flex items-center justify-center leading-none {{ $blockedCount > 0 ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : 'bg-slate-100 text-slate-400' }}">
                        {{ $blockedCount }}
                    </span>
                </button>

                <!-- Completed -->
                <button wire:click="$set('statusFilter', 'completed'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'completed' ? 'border-[#c3122e] text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Completed</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold font-mono inline-flex items-center justify-center leading-none {{ $completedCount > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-slate-100 text-slate-400' }}">
                        {{ $completedCount }}
                    </span>
                </button>
            </div>

            <!-- Right Quick Counter / View Indicator -->
            <div class="hidden sm:flex items-center gap-2 pb-2.5 text-xs text-slate-500 font-medium">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100/80 text-slate-600 font-mono text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ $paginatedTasks->total() }} Assigned</span>
                </span>
            </div>
        </div>

        <!-- Row 2: Secondary Refinement Toolbar (Search + Filters + Reset) -->
        <div class="p-3.5 sm:p-4 bg-slate-50/40 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Left: Search Input with Modern Soft Fill -->
            <div class="relative flex-1 max-w-md">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search in tasks..."
                       class="w-full pl-10 pr-4 py-2 bg-white hover:bg-white/90 focus:bg-white border border-slate-200/90 hover:border-slate-300 focus:border-[#c3122e] rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 shadow-2xs transition-all">
            </div>

            <!-- Right: Filter Select Dropdowns + Reset -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <!-- Status ▾ -->
                <div class="relative min-w-[135px]">
                    <select wire:model.live="statusFilter"
                            class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                        <option value="all">Status: All Status</option>
                        <option value="not_started">Status: Not Started</option>
                        <option value="in_progress">Status: In Progress</option>
                        <option value="completed">Status: Completed</option>
                        <option value="blocked">Status: Blocked</option>
                    </select>
                    <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                </div>

                <!-- Project ▾ -->
                <div class="relative min-w-[155px] max-w-[220px]">
                    <select wire:model.live="projectFilter"
                            class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                        <option value="all">Project: All Projects</option>
                        @foreach($myProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                </div>

                <!-- Due ▾ -->
                <div class="relative min-w-[125px]">
                    <select wire:model.live="dueDateFilter"
                            class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                        <option value="all">Due: Any Time</option>
                        <option value="today">Due Today</option>
                        <option value="overdue">Overdue</option>
                        <option value="this_week">This Week</option>
                    </select>
                    <div class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                </div>

                <!-- Reset Button -->
                <button wire:click="clearFilters" type="button" 
                        class="bg-white border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl px-3.5 py-2 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs flex-shrink-0"
                        title="Reset All Filters">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Reset</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CLEAN EXECUTIVE TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'table')
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 pl-6 pr-4 min-w-[240px]">Task</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Project</th>
                            <th class="py-3.5 px-4 min-w-[110px]">Priority</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Start Date</th>
                            <th class="py-3.5 px-4 min-w-[140px]">Deadline</th>
                            <th class="py-3.5 px-4 min-w-[130px]">Progress</th>
                            <th class="py-3.5 px-4 min-w-[125px]">Status</th>
                            <th class="py-3.5 pr-6 pl-4 text-right w-16">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white font-medium text-slate-700">
                        @php
                            $todayStr = now()->toDateString();
                        @endphp
                        @forelse($paginatedTasks as $task)
                            @php
                                $isOverdue = $task->end_date && $task->end_date->toDateString() < $todayStr && !in_array($task->status->value, ['completed', 'cancelled']);
                                $isDueToday = ($task->end_date && $task->end_date->toDateString() === $todayStr) || ($task->start_date && $task->start_date->toDateString() === $todayStr);
                                $isBlocked = $task->status->value === 'blocked';
                                $isCompleted = $task->status->value === 'completed';
                                $isInProgress = $task->status->value === 'in_progress';
                                $pLabel = $task->priority?->label() ?? 'Medium';
                                
                                // Progress bar color
                                if ($isOverdue || $isBlocked) {
                                    $barColor = '#e11d48';
                                } elseif ($isCompleted) {
                                    $barColor = '#10b981';
                                } elseif ($task->progress > 50) {
                                    $barColor = '#2563eb';
                                } else {
                                    $barColor = '#f59e0b';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <!-- Task Column with Completion Checkbox -->
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex items-start gap-3">
                                        <!-- Quick Complete Toggle Circle -->
                                        <button wire:click="toggleTaskComplete({{ $task->id }})" type="button"
                                                class="w-4 h-4 mt-0.5 rounded-full border-2 flex items-center justify-center transition-all cursor-pointer flex-shrink-0 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-[#c3122e] bg-white' }}"
                                                title="{{ $isCompleted ? 'Mark incomplete' : 'Mark complete' }}">
                                            @if($isCompleted)
                                                <svg class="w-2.5 h-2.5 text-white stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @endif
                                        </button>

                                        <div class="min-w-0">
                                            <span wire:click="openTaskDetail({{ $task->id }})" 
                                                  class="font-bold text-slate-900 text-xs sm:text-[13px] block leading-snug cursor-pointer hover:text-[#c3122e] transition-colors {{ $isCompleted ? 'line-through text-slate-400 font-medium' : '' }}">
                                                {{ ucwords(strtolower($task->title)) }}
                                            </span>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200/60">
                                                    {{ $task->project->code ?? 'PRJ' }}
                                                </span>
                                                <span class="text-slate-400 text-[11px] font-mono">
                                                    WBS {{ $task->wbs_code ?? '1.0' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Project -->
                                <td class="py-4 px-4 align-middle">
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="font-bold text-slate-900 text-xs sm:text-[13px] hover:text-[#c3122e] block leading-snug transition-colors truncate max-w-[200px]" title="{{ $task->project->name ?? 'Project' }}">
                                        {{ $task->project->name ?? 'System Integration' }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-1 font-normal truncate max-w-[200px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="truncate">{{ $task->project->subsidiary->name ?? 'George Steuart Health' }}</span>
                                    </div>
                                </td>

                                <!-- Priority -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    @if(strtolower($pLabel) === 'high' || strtolower($pLabel) === 'critical')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>High</span>
                                        </span>
                                    @elseif(strtolower($pLabel) === 'medium')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Medium</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Low</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Start Date -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap text-xs text-slate-700 font-mono font-medium">
                                    {{ $task->start_date ? $task->start_date->format('M d, Y') : '—' }}
                                </td>

                                <!-- Deadline -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    <span class="text-xs text-slate-900 font-bold font-mono block leading-tight">
                                        {{ $task->end_date ? $task->end_date->format('M d, Y') : '—' }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200/80 mt-1">
                                            <span>⚠️</span> {{ max(1, (int)now()->diffInDays($task->end_date)) }}d overdue
                                        </span>
                                    @elseif($isDueToday)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200/80 mt-1">
                                            <span>⚡</span> Due today
                                        </span>
                                    @elseif($task->end_date)
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-50 text-slate-500 border border-slate-200/60 mt-1">
                                            {{ max(1, (int)now()->diffInDays($task->end_date)) }}d left
                                        </span>
                                    @endif
                                </td>

                                <!-- Progress -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    <span class="text-xs font-bold font-mono text-slate-800 block mb-1.5">
                                        {{ $task->progress }}%
                                    </span>
                                    <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200/60">
                                        <div class="h-full rounded-full transition-all duration-300" 
                                             style="width: {{ max(4, $task->progress) }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    @if($isBlocked)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <span>Blocked</span>
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Overdue</span>
                                        </span>
                                    @elseif($isDueToday)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Due Today</span>
                                        </span>
                                    @elseif($isInProgress)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>In Progress</span>
                                        </span>
                                    @elseif($isCompleted)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Completed</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-200/80 shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Not Started</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 pr-6 pl-4 align-middle text-right whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" type="button" 
                                                class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 inline-flex items-center justify-center transition-colors cursor-pointer" title="Options">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition
                                             class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-30 text-xs text-left">
                                            <button wire:click="openTaskDetail({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-semibold flex items-center gap-2.5">
                                                <span>📝</span> Task Details
                                            </button>
                                            <button wire:click="toggleTaskComplete({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-emerald-600 font-semibold flex items-center gap-2.5">
                                                <span>✔</span> {{ $isCompleted ? 'Mark Incomplete' : 'Mark Completed' }}
                                            </button>
                                            <button wire:click="openBlockerModal({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-rose-50 text-rose-600 font-semibold flex items-center gap-2.5">
                                                <span>⚠️</span> Report Blocker
                                            </button>
                                            <a href="{{ route('projects.show', $task->project_id) }}" class="w-full px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-semibold flex items-center gap-2.5">
                                                <span>📂</span> Open Project
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center gap-2.5">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                            📋
                                        </div>
                                        <span class="text-sm font-bold text-slate-800">No tasks found</span>
                                        <span class="text-xs text-slate-400 max-w-sm">There are currently no tasks matching your active filters. Try resetting the filters or switching views.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bottom Pagination -->
            <div class="px-6 py-3.5 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1">
                    {{ $paginatedTasks->links() }}
                </div>

                <!-- Per-page Selector -->
                <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium flex-shrink-0 self-end sm:self-center">
                    <span>Per page:</span>
                    <select wire:model.live="perPage" class="text-xs font-bold py-1 px-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-700">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         5. VIEW MODE 2: CROSS-PROJECT KANBAN BOARD (TIME HORIZON & STATUS)
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'kanban')
        <div class="space-y-4">
            <!-- Kanban Sub-Navigation Toolbar -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-3 sm:p-4 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider">Kanban Mode:</span>
                    <div class="inline-flex p-1 rounded-xl bg-slate-100/90 border border-slate-200 text-xs flex-wrap sm:flex-nowrap gap-1">
                        <button 
                            wire:click="setKanbanMode('time')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg font-black transition-all cursor-pointer {{ $kanbanMode === 'time' ? 'bg-white text-[#c3122e] shadow-2xs scale-[1.02]' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            📅 Time Horizon
                        </button>
                        <button 
                            wire:click="setKanbanMode('status')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg font-black transition-all cursor-pointer {{ $kanbanMode === 'status' ? 'bg-white text-[#c3122e] shadow-2xs scale-[1.02]' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            📋 Status Pipeline
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold self-end md:self-auto">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 font-bold text-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ $allFilteredTasks->count() }} Total Tasks</span>
                    </span>
                </div>
            </div>

            <!-- Kanban Columns Horizontal Scroll Canvas -->
            <div class="flex gap-4 overflow-x-auto pb-5 pt-1 items-start snap-x snap-mandatory kanban-scroll" style="scrollbar-width: thin;">
                @foreach($kanbanColumns as $colKey => $col)
                    <div class="w-[280px] sm:w-[300px] min-w-[280px] sm:min-w-[300px] flex-shrink-0 snap-start bg-slate-100/80 rounded-2xl p-3.5 border border-slate-200/90 border-t-4 {{ $col['accent'] }} flex flex-col gap-3 min-h-[540px] shadow-2xs">
                        <!-- Column Header -->
                        <div class="flex items-center justify-between px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-base flex-shrink-0">{{ $col['icon'] ?? '📋' }}</span>
                                <h3 class="font-black text-xs text-slate-800 uppercase tracking-wider truncate">{{ $col['title'] }}</h3>
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-full border {{ $col['badge'] }} flex-shrink-0 font-mono">
                                    {{ $col['tasks']->count() }}
                                </span>
                            </div>
                            <button wire:click="openAddTaskModal('{{ $col['status'] }}')" type="button" class="w-6 h-6 rounded-lg bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-black shadow-2xs cursor-pointer flex-shrink-0 border border-slate-200" title="Add task to {{ $col['title'] }}">+</button>
                        </div>

                        <!-- Column Cards List -->
                        <div class="flex-1 flex flex-col gap-3 overflow-y-auto max-h-[calc(100vh-290px)] pr-1">
                            @forelse($col['tasks'] as $kTask)
                                @php
                                    $isKDone = $kTask->status === \App\Enums\WbsStatus::COMPLETED;
                                    $isKOverdue = $kTask->isOverdue();
                                    $hasHourSlot = !empty($kTask->start_time_formatted) && !empty($kTask->end_time_formatted);
                                @endphp

                                <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-md transition-all duration-200 space-y-2.5 group relative {{ $isKDone ? 'bg-emerald-50/20' : ($isKOverdue ? 'bg-rose-50/20 border-rose-200' : '') }}">
                                    <!-- Top Row: Project Code Pill & Priority -->
                                    <div class="flex items-center justify-between gap-1.5">
                                        <span class="text-[10px] font-black font-mono px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[150px]" title="{{ $kTask->project->name ?? '' }}">
                                            {{ $kTask->project->code ?? 'PRJ' }}
                                        </span>
                                        <span class="text-[9.5px] font-extrabold uppercase px-2 py-0.5 rounded-md {{ $kTask->priority->badgeClass() }}">
                                            {{ $kTask->priority->label() }}
                                        </span>
                                    </div>

                                    <!-- Middle Row: Task Title & Hour Pill -->
                                    <div class="cursor-pointer" wire:click="openTaskDetail({{ $kTask->id }})">
                                        <div class="flex items-start gap-1.5 font-extrabold text-xs text-slate-900 group-hover:text-[#c3122e] transition-colors leading-relaxed">
                                            <span class="font-mono text-slate-400 flex-shrink-0">{{ $kTask->wbs_code }}</span>
                                            <span class="{{ $isKDone ? 'line-through text-slate-400' : '' }}">{{ $kTask->title }}</span>
                                        </div>

                                        @if($hasHourSlot)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold text-violet-700 bg-violet-50/90 border border-violet-200/80 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>{{ $kTask->start_time_formatted }} – {{ $kTask->end_time_formatted }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bottom Row: Due Date & Progress -->
                                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                                        <span class="flex items-center gap-1.5 font-bold {{ $isKOverdue ? 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md' : 'text-slate-600' }}">
                                            <span>📅</span>
                                            <span>{{ $kTask->end_date ? $kTask->end_date->format('M d') : 'No date' }}</span>
                                        </span>
                                        @if($kTask->progress > 0)
                                            <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">{{ $kTask->progress }}%</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                                    No tasks
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         6. VIEW MODE 3: INTRADAY HOURLY & SCHEDULE TIMELINE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'timeline')
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs p-4 sm:p-6 space-y-6">
            <!-- Top Controls Bar: Navigator + Timeframe Switcher -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <!-- Left: Navigation Arrows + Today Button + Date Title -->
                <div class="flex items-center gap-3 min-w-0 flex-wrap">
                    <div class="flex items-center gap-1.5 flex-shrink-0 bg-slate-100/90 p-1 rounded-2xl border border-slate-200/70">
                        <button wire:click="goToTimelinePrev" type="button" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black text-sm cursor-pointer shadow-2xs transition-all duration-200 active:scale-90" title="Previous Day">
                            ‹
                        </button>
                        <button wire:click="goToTimelineToday" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-black cursor-pointer shadow-2xs transition-all duration-200 active:scale-95 {{ $timelineDateObj->isToday() ? 'bg-[#c3122e] text-white' : 'bg-white text-slate-700 hover:bg-[#c3122e] hover:text-white' }}">
                            Today
                        </button>
                        <button wire:click="goToTimelineNext" type="button" class="w-8 h-8 rounded-xl bg-white hover:bg-slate-200 text-slate-700 flex items-center justify-center font-black text-sm cursor-pointer shadow-2xs transition-all duration-200 active:scale-90" title="Next Day">
                            ›
                        </button>
                    </div>

                    <div class="min-w-0 pl-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-black text-base sm:text-lg text-slate-900 tracking-tight">
                                {{ $timelineDateObj->format('l, F d, Y') }}
                            </h2>
                            @if($timelineDateObj->isToday())
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black text-amber-800 bg-amber-100 border border-amber-300 shadow-2xs">
                                    ⭐ Today
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 font-semibold mt-0.5 block flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                            <span>{{ $dayTasks->count() }} {{ Str::plural('deliverable', $dayTasks->count()) }} scheduled for this date</span>
                        </span>
                    </div>
                </div>

                <!-- Right: Timeframe Switcher + Native Date Input -->
                <div class="flex items-center gap-3 flex-shrink-0 self-end lg:self-auto flex-wrap">
                    <!-- Day Breakdown vs 7-Day Week Switcher -->
                    <div class="inline-flex p-1 rounded-2xl border border-slate-200/80 bg-slate-100/90 shadow-2xs">
                        <button 
                            wire:click="setTimelineTimeframe('day')" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'day' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            <span>🌅</span>
                            <span>Day Breakdown</span>
                        </button>
                        <button 
                            wire:click="setTimelineTimeframe('week')" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'week' ? 'bg-white text-[#c3122e] shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                        >
                            <span>📅</span>
                            <span>7-Day Horizon</span>
                        </button>
                    </div>

                    <!-- Native Date Picker Input -->
                    <div class="relative">
                        <input type="date" wire:model.live="timelineDate" class="px-3.5 py-2 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-white text-xs font-bold text-slate-800 outline-none focus:border-[#c3122e] focus:bg-white shadow-2xs cursor-pointer transition-all">
                    </div>
                </div>
            </div>

            <!-- 7-Day Interactive Mini Calendar Strip -->
            <div class="grid grid-cols-7 gap-2.5 overflow-x-auto pb-1" style="scrollbar-width: none;">
                @foreach($weekSchedule as $wDateKey => $wInfo)
                    @php
                        $wIsSelected = $wInfo['isSelected'];
                        $wIsToday = $wInfo['isToday'];
                        $wTaskCount = $wInfo['tasks']->count();
                    @endphp
                    <button 
                        wire:click="selectTimelineDay('{{ $wDateKey }}')" 
                        type="button" 
                        class="p-2.5 sm:p-3 rounded-2xl text-center transition-all duration-200 cursor-pointer border flex flex-col items-center justify-between min-w-[50px] relative group {{ $wIsSelected ? 'text-white border-transparent scale-[1.03]' : ($wIsToday ? 'bg-amber-50/80 border-amber-300 text-amber-950 hover:bg-amber-100 hover:scale-[1.02]' : 'bg-slate-50/70 border-slate-200/80 text-slate-700 hover:bg-white hover:border-slate-300 hover:shadow-xs hover:scale-[1.02]') }}"
                        @if($wIsSelected) style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); box-shadow: 0 4px 14px rgba(195,18,46,0.32);" @endif
                    >
                        <span class="text-[9.5px] font-black uppercase tracking-widest {{ $wIsSelected ? 'text-white/80' : ($wIsToday ? 'text-amber-700' : 'text-slate-400 group-hover:text-slate-600') }}">
                            {{ $wInfo['date']->format('D') }}
                        </span>
                        <span class="text-base sm:text-lg font-black font-mono my-0.5 {{ $wIsSelected ? 'text-white' : 'text-slate-900' }}">
                            {{ $wInfo['date']->format('d') }}
                        </span>
                        @if($wTaskCount > 0)
                            <span class="text-[9.5px] font-mono font-black px-2 py-0.2 rounded-full {{ $wIsSelected ? 'bg-white/25 text-white' : ($wIsToday ? 'bg-amber-200 text-amber-900' : 'bg-slate-200 text-slate-700') }}">
                                {{ $wTaskCount }}
                            </span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full {{ $wIsSelected ? 'bg-white/40' : 'bg-transparent' }}"></span>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- ── TIMEFRAME 1: DAY BREAKDOWN (Morning / Afternoon / Evening) ── -->
            @if($timelineTimeframe === 'day')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <!-- 1. Morning Block (08:30 AM – 12:30 PM) -->
                    <div class="bg-gradient-to-b from-amber-50/50 via-amber-50/20 to-white rounded-2xl p-4 border border-amber-200/80 space-y-3.5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between px-1 pb-2 border-b border-amber-200/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-sm shadow-2xs border border-amber-200">
                                    🌅
                                </div>
                                <div>
                                    <h3 class="font-black text-xs text-amber-950 uppercase tracking-wider">Morning Focus</h3>
                                    <span class="text-[10px] font-bold text-amber-800/80 bg-amber-100/80 px-1.5 py-0.2 rounded">08:30 AM – 12:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-amber-900 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full shadow-2xs font-mono">
                                {{ $morningTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-3 flex-1">
                            @forelse($morningTasks as $mTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $mTask])
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-amber-200/70 rounded-2xl bg-white/60 flex flex-col items-center justify-center gap-1.5">
                                    <span class="text-base opacity-60">☕</span>
                                    <span>No morning hour deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 2. Afternoon Block (12:30 PM – 05:30 PM) -->
                    <div class="bg-gradient-to-b from-sky-50/50 via-sky-50/20 to-white rounded-2xl p-4 border border-sky-200/80 space-y-3.5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between px-1 pb-2 border-b border-sky-200/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-sm shadow-2xs border border-sky-200">
                                    ☀️
                                </div>
                                <div>
                                    <h3 class="font-black text-xs text-sky-950 uppercase tracking-wider">Afternoon Execution</h3>
                                    <span class="text-[10px] font-bold text-sky-800/80 bg-sky-100/80 px-1.5 py-0.2 rounded">12:30 PM – 05:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-sky-900 bg-sky-100 border border-sky-300 px-2.5 py-0.5 rounded-full shadow-2xs font-mono">
                                {{ $afternoonTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-3 flex-1">
                            @forelse($afternoonTasks as $aTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $aTask])
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-sky-200/70 rounded-2xl bg-white/60 flex flex-col items-center justify-center gap-1.5">
                                    <span class="text-base opacity-60">🎯</span>
                                    <span>No afternoon hour deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Evening & All-Day (05:30 PM+ / Flexible) -->
                    <div class="bg-gradient-to-b from-purple-50/50 via-purple-50/20 to-white rounded-2xl p-4 border border-purple-200/80 space-y-3.5 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between px-1 pb-2 border-b border-purple-200/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm shadow-2xs border border-purple-200">
                                    🌙
                                </div>
                                <div>
                                    <h3 class="font-black text-xs text-purple-950 uppercase tracking-wider">Evening &amp; All-Day</h3>
                                    <span class="text-[10px] font-bold text-purple-800/80 bg-purple-100/80 px-1.5 py-0.2 rounded">After 05:30 PM / Flexible</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-purple-900 bg-purple-100 border border-purple-300 px-2.5 py-0.5 rounded-full shadow-2xs font-mono">
                                {{ $eveningTasks->count() + $allDayTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-3 flex-1">
                            @forelse($eveningTasks->concat($allDayTasks) as $eTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $eTask])
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-purple-200/70 rounded-2xl bg-white/60 flex flex-col items-center justify-center gap-1.5">
                                    <span class="text-base opacity-60">✨</span>
                                    <span>No evening or all-day deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            <!-- ── TIMEFRAME 2: 7-DAY WEEK HORIZON BOARD ── -->
            @else
                <div class="flex gap-4 overflow-x-auto pb-4 pt-1 items-start snap-x kanban-scroll" style="scrollbar-width: thin;">
                    @foreach($weekSchedule as $wDateKey => $wInfo)
                        @php
                            $wDayTasks = $wInfo['tasks'];
                            $wIsToday = $wInfo['isToday'];
                        @endphp
                        <div class="w-[280px] min-w-[280px] flex-shrink-0 snap-start rounded-2xl p-3.5 border flex flex-col gap-3 min-h-[480px] {{ $wIsToday ? 'bg-amber-50/40 border-amber-300 ring-2 ring-amber-400/30' : 'bg-slate-50/70 border-slate-200/90' }}">
                            <!-- Column Header -->
                            <div class="flex items-center justify-between pb-2 border-b {{ $wIsToday ? 'border-amber-200' : 'border-slate-200' }}">
                                <div>
                                    <h4 class="font-black text-xs text-slate-900 uppercase tracking-wider">
                                        {{ $wInfo['date']->format('l') }}
                                    </h4>
                                    <span class="text-[11px] font-bold text-slate-500 font-mono">
                                        {{ $wInfo['date']->format('M d') }}
                                    </span>
                                </div>
                                <span class="text-xs font-black font-mono px-2 py-0.5 rounded-full {{ $wIsToday ? 'bg-amber-200 text-amber-900' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $wDayTasks->count() }}
                                </span>
                            </div>

                            <!-- Cards -->
                            <div class="flex-1 flex flex-col gap-2.5 overflow-y-auto">
                                @forelse($wDayTasks as $wt)
                                    @include('livewire.my-tasks-timeline-card', ['t' => $wt])
                                @empty
                                    <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-slate-200 rounded-2xl bg-white/60">
                                        No tasks scheduled
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         7. QUICK TASK DETAIL DRAWER / MODAL (VIEW ONLY)
         ═══════════════════════════════════════════════════════════════ -->
    @if($showTaskDetailModal && $detailTask)
        @php
            $todayStr = now()->toDateString();
            $isOverdue = $detailTask->end_date && $detailTask->end_date->toDateString() < $todayStr && !in_array($detailTask->status->value, ['completed', 'cancelled']);
            $statusVal = $detailTask->status->value ?? 'not_started';
            $statusLabel = match($statusVal) {
                'completed' => 'Completed',
                'in_progress' => 'In Progress',
                'under_review' => 'Under Review',
                'blocked' => 'Blocked',
                default => 'Not Started',
            };
            $statusBadgeClass = match($statusVal) {
                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'in_progress' => 'bg-amber-50 text-amber-800 border-amber-200',
                'under_review' => 'bg-blue-50 text-blue-700 border-blue-200',
                'blocked' => 'bg-rose-50 text-rose-700 border-rose-200',
                default => 'bg-slate-100 text-slate-700 border-slate-200',
            };
            $priorityVal = $detailTask->priority->value ?? 'medium';
            $priorityBadgeClass = match($priorityVal) {
                'critical' => 'bg-rose-100 text-rose-800 border-rose-300',
                'high' => 'bg-orange-50 text-orange-700 border-orange-200',
                'medium' => 'bg-blue-50 text-blue-700 border-blue-200',
                default => 'bg-slate-100 text-slate-600 border-slate-200',
            };
        @endphp
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 620px; max-height: calc(100vh - 40px); background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden; display: flex; flex-direction: column;">
                <!-- Unified Brand Hero Header (Replaces redundant top bar & card) -->
                <div class="px-6 py-5 text-white relative overflow-hidden flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #9e0e24 50%, #750818 100%);">
                    <!-- Subtle Ambient Luxury Glow -->
                    <div class="absolute -right-8 -bottom-8 w-36 h-36 rounded-full bg-white/10 pointer-events-none blur-lg"></div>
                    <div class="absolute -left-8 -top-8 w-28 h-28 rounded-full bg-black/15 pointer-events-none"></div>

                    <!-- Top Row: Category Pill + Close Button -->
                    <div class="flex items-center justify-between gap-3 relative z-10 mb-2.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10.5px] font-black uppercase tracking-wider bg-white/15 text-rose-100 border border-white/20 shadow-2xs">
                                📁 Project Deliverable Overview
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-white/20 text-white border border-white/25">
                                {{ $detailTask->project->code ?? 'PRJ' }}
                            </span>
                        </div>

                        <button 
                            type="button" 
                            wire:click="$set('showTaskDetailModal', false)" 
                            class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center text-sm font-bold transition-all cursor-pointer border border-white/20 shadow-2xs"
                            title="Close"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- Project Identity Row -->
                    <div class="flex items-end justify-between gap-4 flex-wrap relative z-10">
                        <div class="min-w-0">
                            <span class="text-[10px] font-black uppercase tracking-wider text-rose-200/90 block mb-0.5">Project Name</span>
                            <h3 class="text-lg sm:text-xl font-black text-white tracking-tight leading-snug drop-shadow-xs truncate max-w-md" title="{{ $detailTask->project->name ?? 'Project' }}">
                                {{ $detailTask->project->name ?? 'Project' }}
                            </h3>
                        </div>

                        @if(isset($detailTask->project->subsidiary))
                            <span class="px-3 py-1 rounded-xl text-xs font-bold bg-white/15 text-white/95 border border-white/20 flex items-center gap-1.5 shadow-2xs">
                                <span>🏢</span>
                                <span>{{ $detailTask->project->subsidiary->name }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Body (Scrollable Read-Only View) -->
                <div style="padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 18px; flex: 1;">

                    <!-- Deliverable Task Title Section -->
                    <div class="space-y-1.5 pb-2 border-b border-slate-100">
                        <div class="flex items-center gap-2 text-[10.5px] font-black text-slate-400 uppercase tracking-wider">
                            <span class="text-[#c3122e]">Deliverable Task</span>
                            <span>&bull;</span>
                            <span class="font-mono text-slate-700 font-bold bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">WBS {{ $detailTask->wbs_code }}</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-snug">
                            {{ $detailTask->title }}
                        </h3>
                    </div>

                    <!-- Status, Priority & Progress Banner -->
                    <div class="p-4 rounded-2xl bg-rose-50/30 border border-rose-100/90 space-y-3">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-black border {{ $statusBadgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                                <span class="px-2 py-1 rounded-lg text-[10.5px] font-black uppercase tracking-wider border {{ $priorityBadgeClass }}">
                                    {{ strtoupper($priorityVal) }} Priority
                                </span>
                                @if($isOverdue)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-[#c3122e] text-white animate-pulse">
                                        Overdue
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-mono font-black text-[#c3122e]">
                                {{ $detailTask->progress ?? 0 }}% Progress
                            </span>
                        </div>

                        <!-- Progress Bar in Brand Crimson -->
                        <div class="w-full h-2.5 bg-slate-200/80 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300" style="background: linear-gradient(90deg, #c3122e 0%, #e11d48 100%); width: {{ max(0, min(100, $detailTask->progress ?? 0)) }}%;"></div>
                        </div>
                    </div>

                    <!-- Schedule & Assignment Meta Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Dates Card -->
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2">
                            <span class="text-[10.5px] font-black text-slate-400 uppercase tracking-wider block">📅 Timeline &amp; Schedule</span>
                            <div class="space-y-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Start Schedule:</span>
                                    <span class="font-bold text-slate-800 font-mono">
                                        {{ $detailTask->start_date ? $detailTask->start_date->format('M d, Y') : 'Not scheduled' }}
                                        @if($detailTask->start_time)
                                            <span class="text-slate-400 font-normal">({{ \Carbon\Carbon::parse($detailTask->start_time)->format('h:i A') }})</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Target Deadline:</span>
                                    <span class="font-bold {{ $isOverdue ? 'text-rose-600' : 'text-slate-800' }} font-mono">
                                        {{ $detailTask->end_date ? $detailTask->end_date->format('M d, Y') : 'No deadline' }}
                                        @if($detailTask->end_time)
                                            <span class="text-slate-400 font-normal">({{ \Carbon\Carbon::parse($detailTask->end_time)->format('h:i A') }})</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- People & Hours Card -->
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-2">
                            <span class="text-[10.5px] font-black text-slate-400 uppercase tracking-wider block">👥 Assignment &amp; Effort</span>
                            <div class="space-y-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Assigned To:</span>
                                    <span class="font-bold text-slate-800 truncate max-w-[140px]" title="{{ $detailTask->assignedUser->name ?? 'Unassigned' }}">
                                        {{ $detailTask->assignedUser->name ?? 'Unassigned' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Estimated Effort:</span>
                                    <span class="font-bold text-slate-800 font-mono">
                                        {{ $detailTask->estimated_hours ? $detailTask->estimated_hours . ' hrs' : 'Not estimated' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description / Deliverable Notes -->
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">Description / Scope Notes</label>
                        @if($detailTask->description)
                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-700 leading-relaxed font-medium whitespace-pre-line">
                                {{ $detailTask->description }}
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic py-1">No detailed scope notes provided for this task deliverable.</p>
                        @endif
                    </div>

                    <!-- Sub-tasks / Deliverable Breakdown (View Only) -->
                    @if($detailTask->children && $detailTask->children->count() > 0)
                        <div class="pt-2 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <label class="text-[11px] font-black text-slate-700 uppercase tracking-wider">Sub-tasks &amp; Deliverables Checklist</label>
                                <span class="text-[11px] font-bold text-slate-500 font-mono">
                                    {{ $detailTask->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() }} / {{ $detailTask->children->count() }} done
                                </span>
                            </div>

                            <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                                @foreach($detailTask->children as $ch)
                                    @php $chDone = $ch->status === \App\Enums\WbsStatus::COMPLETED; @endphp
                                    <div class="flex items-center gap-2.5 p-2.5 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                                        <div class="w-4 h-4 rounded flex items-center justify-center {{ $chDone ? 'bg-emerald-600 text-white' : 'border border-slate-300 bg-white' }}">
                                            @if($chDone)
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </div>
                                        <span class="flex-1 font-semibold {{ $chDone ? 'line-through text-slate-400' : 'text-slate-800' }}">
                                            {{ $ch->title }}
                                        </span>
                                        <span class="text-[10px] font-mono px-2 py-0.5 rounded {{ $chDone ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $chDone ? 'Done' : 'Pending' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Active Blockers (if any) -->
                    @if($detailTask->blockers && $detailTask->blockers->where('status', 'open')->count() > 0)
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-2.5 text-xs text-rose-900">
                            <span class="text-base">⚠️</span>
                            <div>
                                <strong class="font-black">Active Blockers Reported:</strong>
                                @foreach($detailTask->blockers->where('status', 'open') as $blk)
                                    <p class="mt-0.5 font-medium text-rose-800">&bull; {{ $blk->description }} ({{ ucfirst($blk->severity ?? 'medium') }} impact)</p>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer (View Only Controls) -->
                <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; background: #ffffff;">
                    <a 
                        href="{{ route('projects.show', $detailTask->project_id) }}" 
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 hover:text-[#c3122e] transition-colors no-underline group"
                    >
                        <span>📂 Open <span class="font-extrabold text-slate-900 group-hover:text-[#c3122e]">{{ $detailTask->project->name ?? 'Project' }}</span> Workspace</span>
                        <span>→</span>
                    </a>
                    <button 
                        type="button" 
                        wire:click="$set('showTaskDetailModal', false)" 
                        class="px-6 py-2.5 text-white text-xs font-black rounded-xl shadow-md shadow-[#c3122e]/20 hover:opacity-95 active:scale-95 transition-all cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8. CREATE TASK MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 520px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="font-extrabold text-sm text-slate-900">Add New Personal Deliverable</h3>
                    <button type="button" wire:click="$set('showCreateTaskModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="saveNewTask" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Target Project <span class="text-rose-500">*</span></label>
                        <select wire:model="createTaskProjectId" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50" required>
                            @foreach($myProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} — {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="createTaskTitle" placeholder="e.g. Design system integration" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Date</label>
                            <input type="date" wire:model="createTaskStartDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Due Deadline</label>
                            <input type="date" wire:model="createTaskEndDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Time (Optional)</label>
                            <input type="time" wire:model="createTaskStartTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">End Time (Optional)</label>
                            <input type="time" wire:model="createTaskEndTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateTaskModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-emerald-700 transition-colors">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         9. SUB-TASK CREATION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showSubTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 520px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="font-extrabold text-sm text-slate-900">Add Sub-Task</h3>
                    <button type="button" wire:click="$set('showSubTaskModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="createSubTask" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Sub-Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="subTaskTitle" placeholder="e.g. Prepare client review notes" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Due Deadline</label>
                            <input type="date" wire:model="subTaskEndDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Time (Optional)</label>
                            <input type="time" wire:model="subTaskStartTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">End Time (Optional)</label>
                            <input type="time" wire:model="subTaskEndTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSubTaskModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-slate-900 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-black transition-colors">Add Sub-Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         10. BLOCKER / ISSUE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showBlockerModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 480px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <div class="flex items-center gap-2 text-rose-600 font-extrabold text-sm">
                        <span>⚠️</span>
                        <span>Report Task Blocker / Issue</span>
                    </div>
                    <button type="button" wire:click="$set('showBlockerModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="reportBlocker" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Issue / Blocker Description <span class="text-rose-500">*</span></label>
                        <textarea wire:model="blockerDescription" rows="3" placeholder="Explain what is blocking this task..." class="w-full p-3 rounded-xl border border-slate-200 text-xs font-semibold outline-none" required></textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Severity</label>
                        <select wire:model="blockerSeverity" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                            <option value="low">🟢 Low (Minor delay)</option>
                            <option value="medium">🟡 Medium (Requires attention)</option>
                            <option value="high">🔴 High (Critical blocker)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showBlockerModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-rose-700 transition-colors">Submit to Project Manager</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
