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
         1. TOP HEADER & MULTI-PROJECT CONTROLS
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Tasks
                </h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold text-slate-600 bg-slate-100 border border-slate-200/80">
                    {{ $activeProjectsCount }} {{ Str::plural('Project', $activeProjectsCount) }}
                </span>
                @if($taskScope === 'pm_projects')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200/80 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        <span>Project Manager Scope</span>
                    </span>
                @endif
                @if(($dueTodayCount + $overdueCount) > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200/80 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>{{ $dueTodayCount + $overdueCount }} Urgent</span>
                    </span>
                @endif
            </div>
            @if($taskScope === 'pm_projects')
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Showing all {{ $totalCount }} tasks across the {{ $managedProjectsCount }} {{ Str::plural('project', $managedProjectsCount) }} where you are designated Project Manager.
                </p>
            @endif
        </div>

        <!-- Right: Scope Switcher + View Mode Switcher -->
        <div class="flex items-center gap-2.5 flex-shrink-0 self-stretch sm:self-auto justify-between sm:justify-end flex-wrap">
            @if($isProjectManager)
                <!-- Project Manager Scope Segmented Control Button -->
                <div class="inline-flex p-1 rounded-xl border border-slate-200/80 bg-slate-100/80 shadow-2xs">
                    <button 
                        wire:click="setTaskScope('assigned')" 
                        type="button" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 cursor-pointer {{ $taskScope === 'assigned' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}"
                        title="View tasks directly assigned to me"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Assigned to Me</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $taskScope === 'assigned' ? 'bg-slate-100 text-slate-700' : 'bg-slate-200/80 text-slate-600' }}">
                            {{ $myAssignedCount }}
                        </span>
                    </button>
                    <button 
                        wire:click="setTaskScope('pm_projects')" 
                        type="button" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 cursor-pointer {{ $taskScope === 'pm_projects' ? 'bg-[#c3122e] text-white shadow-2xs font-bold' : 'text-slate-600 hover:text-[#c3122e]' }}"
                        title="View all tasks across all projects where I am the Project Manager"
                    >
                        <svg class="w-3.5 h-3.5 {{ $taskScope === 'pm_projects' ? 'text-white' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Managed Projects</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $taskScope === 'pm_projects' ? 'bg-white/20 text-white' : 'bg-slate-200/80 text-slate-600' }}">
                            {{ $pmProjectsTasksCount }}
                        </span>
                    </button>
                </div>
            @endif

            <!-- View Mode Switcher -->
            <div class="inline-flex p-1 rounded-xl border border-slate-200/80 bg-slate-100/80 shadow-2xs">
                <button 
                    wire:click="setViewMode('table')" 
                    type="button" 
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}"
                    title="Table View"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Table</span>
                </button>
                <button 
                    wire:click="setViewMode('kanban')" 
                    type="button" 
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'kanban' ? 'bg-white text-[#c3122e] shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}"
                    title="Kanban Board"
                >
                    <svg class="w-3.5 h-3.5 {{ $viewMode === 'kanban' ? 'text-[#c3122e]' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <span>Kanban</span>
                </button>
                <button 
                    wire:click="setViewMode('timeline')" 
                    type="button" 
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 flex items-center gap-1.5 cursor-pointer {{ $viewMode === 'timeline' ? 'bg-white text-[#c3122e] shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}"
                    title="Schedule View"
                >
                    <svg class="w-3.5 h-3.5 {{ $viewMode === 'timeline' ? 'text-[#c3122e]' : 'text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Schedule</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. CLEAN FILTER & NAVIGATION TOOLBAR (Table & Kanban Views)
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode !== 'timeline')
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden mb-6">
        <!-- Row 1: Primary Status Segment Tabs (Top Level Hierarchy) -->
        <div class="px-5 pt-3 pb-0 border-b border-slate-100 flex items-center justify-between gap-4 overflow-x-auto scrollbar-none bg-white">
            <div class="flex items-center gap-5 sm:gap-6">
                <!-- All Tasks -->
                <button wire:click="clearFilters" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>All Tasks</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $totalCount > 0 ? 'bg-slate-100 text-slate-700' : 'bg-slate-50 text-slate-400' }}">
                        {{ $totalCount }}
                    </span>
                </button>

                <!-- Due Today -->
                <button wire:click="setDueDateFilter('today'); $set('statusFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $dueDateFilter === 'today' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Due Today</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $dueTodayCount > 0 ? 'bg-amber-100/80 text-amber-800' : 'bg-slate-100 text-slate-400' }}">
                        {{ $dueTodayCount }}
                    </span>
                </button>

                <!-- Overdue -->
                <button wire:click="setDueDateFilter('overdue'); $set('statusFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $dueDateFilter === 'overdue' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Overdue</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold inline-flex items-center justify-center leading-none {{ $overdueCount > 0 ? 'bg-rose-50 text-[#c3122e] border border-rose-200/70' : 'bg-slate-100 text-slate-400' }}">
                        {{ $overdueCount }}
                    </span>
                </button>

                <!-- Not Started -->
                <button wire:click="$set('statusFilter', 'not_started'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'not_started' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Not Started</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $notStartedCount > 0 ? 'bg-slate-100 text-slate-700' : 'bg-slate-50 text-slate-400' }}">
                        {{ $notStartedCount }}
                    </span>
                </button>

                <!-- In Progress -->
                <button wire:click="$set('statusFilter', 'in_progress'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'in_progress' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>In Progress</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $inProgressCount > 0 ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-400' }}">
                        {{ $inProgressCount }}
                    </span>
                </button>

                <!-- Blocked -->
                <button wire:click="$set('statusFilter', 'blocked'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'blocked' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Blocked</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $blockedCount > 0 ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-400' }}">
                        {{ $blockedCount }}
                    </span>
                </button>

                <!-- Completed -->
                <button wire:click="$set('statusFilter', 'completed'); $set('dueDateFilter', 'all')" type="button"
                        class="pb-3 pt-1 text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer border-b-2 whitespace-nowrap -mb-[1px] {{ $statusFilter === 'completed' ? 'border-[#c3122e] text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    <span>Completed</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold inline-flex items-center justify-center leading-none {{ $completedCount > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">
                        {{ $completedCount }}
                    </span>
                </button>
            </div>

            <!-- Right Quick Counter / View Indicator -->
            <div class="hidden sm:flex items-center gap-2 pb-2.5 text-xs text-slate-500 font-medium">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-100/90 text-slate-600 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ $paginatedTasks->total() }} Assigned</span>
                </span>
            </div>
        </div>

        <!-- Row 2: Secondary Refinement Toolbar (Search + Filters + Reset) -->
        <div class="p-3.5 sm:p-4 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <!-- Left: Search Input -->
            <div class="relative flex-1 max-w-md">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tasks by title, code..."
                       class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200/90 hover:border-slate-300 focus:border-[#c3122e] rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 shadow-2xs transition-all">
            </div>

            <!-- Right: Filter Select Dropdowns + Reset -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Project ▾ -->
                <div class="relative min-w-[155px] max-w-[220px]">
                    <select wire:model.live="projectFilter"
                            class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                        <option value="all">Project: All</option>
                        @foreach($myProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>

                @if($taskScope === 'pm_projects' && $availableAssignees->isNotEmpty())
                    <!-- Assignee ▾ -->
                    <div class="relative min-w-[145px] max-w-[200px]">
                        <select wire:model.live="assigneeFilter"
                                class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                            <option value="all">Assignee: All</option>
                            <option value="unassigned">Unassigned</option>
                            @foreach($availableAssignees as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                @endif

                <!-- Due ▾ -->
                <div class="relative min-w-[125px]">
                    <select wire:model.live="dueDateFilter"
                            class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                        <option value="all">Due: Any Time</option>
                        <option value="today">Due Today</option>
                        <option value="overdue">Overdue</option>
                        <option value="this_week">This Week</option>
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>

                <!-- Reset Button -->
                <button wire:click="clearFilters" type="button" 
                        class="bg-white border border-slate-200/90 hover:border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl px-3 py-2 text-xs font-semibold transition-colors flex items-center gap-1.5 cursor-pointer shadow-2xs flex-shrink-0"
                        title="Reset All Filters">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Reset</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         3. CLEAN EXECUTIVE TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'table')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/60 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 pl-6 pr-4 min-w-[240px]">Task</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Project</th>
                            @if($taskScope === 'pm_projects')
                                <th class="py-3.5 px-4 min-w-[140px]">Assignee</th>
                            @endif
                            <th class="py-3.5 px-4 min-w-[110px]">Priority</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Start Date</th>
                            <th class="py-3.5 px-4 min-w-[140px]">Deadline</th>
                            <th class="py-3.5 px-4 min-w-[120px]">Progress</th>
                            <th class="py-3.5 px-4 min-w-[125px]">Status</th>
                            <th class="py-3.5 pr-6 pl-4 text-right w-14">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
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
                                    $barColor = '#ef4444';
                                } elseif ($isCompleted) {
                                    $barColor = '#10b981';
                                } elseif ($isInProgress || $task->progress > 0) {
                                    $barColor = '#f59e0b';
                                } else {
                                    $barColor = '#94a3b8';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Task Column with Completion Checkbox -->
                                <td class="py-3.5 pl-6 pr-4 align-middle">
                                    <div class="flex items-start gap-3">
                                        <!-- Quick Complete Toggle Circle -->
                                        <button wire:click="toggleTaskComplete({{ $task->id }})" type="button"
                                                class="w-4.5 h-4.5 mt-0.5 rounded-full border-2 flex items-center justify-center transition-all cursor-pointer flex-shrink-0 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-[#c3122e] bg-white' }}"
                                                title="{{ $isCompleted ? 'Mark incomplete' : 'Mark complete' }}">
                                            @if($isCompleted)
                                                <svg class="w-2.5 h-2.5 text-white stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @endif
                                        </button>

                                        <div class="min-w-0">
                                            <span wire:click="openTaskDetail({{ $task->id }})" 
                                                  class="font-semibold text-slate-900 text-xs sm:text-[13px] block leading-snug cursor-pointer hover:text-[#c3122e] transition-colors {{ $isCompleted ? 'line-through text-slate-400 font-normal' : '' }}">
                                                {{ ucwords(strtolower($task->title)) }}
                                            </span>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/60">
                                                    {{ $task->project->code ?? 'PRJ' }}
                                                </span>
                                                <span class="text-slate-400 text-[11px]">
                                                    WBS {{ $task->wbs_code ?? '1.0' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Project -->
                                <td class="py-3.5 px-4 align-middle">
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="font-semibold text-slate-900 text-xs sm:text-[13px] hover:text-[#c3122e] block leading-snug transition-colors truncate max-w-[200px]" title="{{ $task->project->name ?? 'Project' }}">
                                        {{ $task->project->name ?? 'System Integration' }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5 truncate max-w-[200px]">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="truncate">{{ $task->project->subsidiary->name ?? 'George Steuart Group' }}</span>
                                    </div>
                                </td>

                                @if($taskScope === 'pm_projects')
                                    <!-- Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($task->assignedUser)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6.5 h-6.5 rounded-full bg-slate-800 text-white flex items-center justify-center text-[10px] font-bold shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span class="text-xs font-semibold text-slate-800 truncate max-w-[130px]" title="{{ $task->assignedUser->name }}">
                                                    {{ $task->assignedUser->name }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200/60">
                                                Unassigned
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                <!-- Priority -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @if(strtolower($pLabel) === 'high' || strtolower($pLabel) === 'critical')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>High</span>
                                        </span>
                                    @elseif(strtolower($pLabel) === 'medium')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Medium</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Low</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Start Date -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap text-xs text-slate-600 font-medium">
                                    {{ $task->start_date ? $task->start_date->format('M d, Y') : '—' }}
                                </td>

                                <!-- Deadline -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    <span class="text-xs text-slate-900 font-semibold block leading-tight">
                                        {{ $task->end_date ? $task->end_date->format('M d, Y') : '—' }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200/70 mt-1">
                                            <svg class="w-3 h-3 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ max(1, (int)now()->diffInDays($task->end_date)) }}d overdue</span>
                                        </span>
                                    @elseif($isDueToday)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200/70 mt-1">
                                            <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Due today</span>
                                        </span>
                                    @elseif($task->end_date)
                                        <span class="text-[11px] text-slate-400 font-medium mt-0.5 block">
                                            {{ max(1, (int)now()->diffInDays($task->end_date)) }}d left
                                        </span>
                                    @endif
                                </td>

                                <!-- Progress -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    <span class="text-xs font-semibold text-slate-800 block mb-1">
                                        {{ $task->progress }}%
                                    </span>
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-300" 
                                             style="width: {{ max(4, $task->progress) }}%; background-color: {{ $barColor }};"></div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @if($isBlocked)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <span>Blocked</span>
                                        </span>
                                    @elseif($isOverdue)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Overdue</span>
                                        </span>
                                    @elseif($isDueToday)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            <span>Due Today</span>
                                        </span>
                                    @elseif($isInProgress)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                            <span>In Progress</span>
                                        </span>
                                    @elseif($isCompleted)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Completed</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200/70">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span>Not Started</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-3.5 pr-6 pl-4 align-middle text-right whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" type="button" 
                                                class="w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 inline-flex items-center justify-center transition-colors cursor-pointer" title="Options">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition
                                             class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-30 text-xs text-left">
                                            <button wire:click="openTaskDetail({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-semibold flex items-center gap-2.5 cursor-pointer">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Task Details</span>
                                            </button>
                                            <button wire:click="toggleTaskComplete({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-slate-50 text-emerald-600 font-semibold flex items-center gap-2.5 cursor-pointer">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ $isCompleted ? 'Mark Incomplete' : 'Mark Completed' }}</span>
                                            </button>
                                            <button wire:click="openBlockerModal({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-rose-50 text-rose-600 font-semibold flex items-center gap-2.5 cursor-pointer">
                                                <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span>Report Blocker</span>
                                            </button>
                                            <a href="{{ route('projects.show', $task->project_id) }}" class="w-full px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-semibold flex items-center gap-2.5 cursor-pointer no-underline">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                <span>Open Project</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $taskScope === 'pm_projects' ? 9 : 8 }}" class="py-16 text-center text-slate-400 font-medium">
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
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kanban Mode:</span>
                    <div class="inline-flex p-1 rounded-xl bg-slate-100/90 border border-slate-200 text-xs flex-wrap sm:flex-nowrap gap-1">
                        <button 
                            wire:click="setKanbanMode('time')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $kanbanMode === 'time' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $kanbanMode === 'time' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Time Horizon</span>
                        </button>
                        <button 
                            wire:click="setKanbanMode('status')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $kanbanMode === 'status' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $kanbanMode === 'status' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                            <span>Status Pipeline</span>
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

                                    @if($taskScope === 'pm_projects')
                                        <!-- Assignee Badge in Kanban -->
                                        <div class="flex items-center gap-1.5 pt-2 border-t border-slate-100 text-[11px] text-slate-600">
                                            @if($kTask->assignedUser)
                                                <div class="w-5 h-5 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] font-bold shrink-0">
                                                    {{ strtoupper(substr($kTask->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span class="truncate font-semibold text-slate-700 max-w-[150px]" title="{{ $kTask->assignedUser->name }}">
                                                    {{ $kTask->assignedUser->name }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-medium italic">Unassigned</span>
                                            @endif
                                        </div>
                                    @endif
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
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-6 space-y-5">
            <!-- Top Controls Bar: Navigator + Date + Project Filter + View Switcher + Date Picker -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <!-- Left: Navigation Arrows + Today Button + Date Title -->
                <div class="flex items-center gap-3 min-w-0 flex-wrap">
                    <div class="flex items-center gap-1 flex-shrink-0 bg-slate-100/90 p-1 rounded-xl border border-slate-200/70">
                        <button wire:click="goToTimelinePrev" type="button" class="w-7 h-7 rounded-lg bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer shadow-2xs transition-colors" title="Previous Day">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button wire:click="goToTimelineToday" type="button" class="px-3 py-1 rounded-lg text-xs font-semibold cursor-pointer shadow-2xs transition-colors {{ $timelineDateObj->isToday() ? 'bg-white text-[#c3122e] font-bold shadow-xs' : 'bg-transparent text-slate-600 hover:text-slate-900' }}">
                            Today
                        </button>
                        <button wire:click="goToTimelineNext" type="button" class="w-7 h-7 rounded-lg bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer shadow-2xs transition-colors" title="Next Day">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="min-w-0 pl-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-bold text-base sm:text-lg text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                {{ $timelineDateObj->format('l, F j, Y') }}
                            </h2>
                            @if($timelineDateObj->isToday())
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200/70">
                                    Today
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dayTasks->count() > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span>{{ $dayTasks->count() }} {{ Str::plural('deliverable', $dayTasks->count()) }} scheduled</span>
                        </span>
                    </div>
                </div>

                <!-- Right: Project Filter + Day/Week Switcher + Styled Date Button -->
                <div class="flex items-center gap-2.5 flex-shrink-0 self-end lg:self-auto flex-wrap">
                    @if($myProjects->count() > 1)
                        <!-- Project Selector -->
                        <div class="relative min-w-[135px] max-w-[185px]">
                            <select wire:model.live="projectFilter"
                                    class="w-full appearance-none bg-white border border-slate-200/90 hover:border-slate-300 rounded-xl pl-3 pr-7 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer shadow-2xs transition-colors truncate">
                                <option value="all">All Projects</option>
                                @foreach($myProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    @endif

                    <!-- Day Breakdown vs 7-Day Week Switcher -->
                    <div class="inline-flex p-1 rounded-xl border border-slate-200/80 bg-slate-100/90 shadow-2xs">
                        <button 
                            wire:click="setTimelineTimeframe('day')" 
                            type="button" 
                            class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'day' ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $timelineTimeframe === 'day' ? 'text-slate-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Day</span>
                        </button>
                        <button 
                            wire:click="setTimelineTimeframe('week')" 
                            type="button" 
                            class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'week' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $timelineTimeframe === 'week' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Week</span>
                        </button>
                    </div>

                    <!-- Modern Date Picker Button (Overlays native date input cleanly) -->
                    <div class="relative">
                        <input type="date" wire:model.live="timelineDate" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <button type="button" class="px-3 py-1.5 rounded-xl border border-slate-200/90 bg-white hover:border-slate-300 text-xs font-semibold text-slate-700 shadow-2xs flex items-center gap-1.5 cursor-pointer pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $timelineDateObj->format('M j, Y') }}</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 7-Day Interactive Mini Calendar Strip (Only in Day View) -->
            @if($timelineTimeframe === 'day')
                <div class="grid grid-cols-7 gap-2 pb-0.5">
                    @foreach($weekSchedule as $wDateKey => $wInfo)
                        @php
                            $wIsSelected = $wInfo['isSelected'];
                            $wIsToday = $wInfo['isToday'];
                            $wTaskCount = $wInfo['tasks']->count();
                        @endphp
                        <button 
                            wire:click="selectTimelineDay('{{ $wDateKey }}')" 
                            type="button" 
                            class="py-2 px-1.5 sm:px-2 rounded-xl text-center transition-all duration-150 cursor-pointer flex flex-col items-center justify-center gap-1 {{ $wIsSelected ? 'bg-[#c3122e] text-white shadow-xs' : ($wIsToday ? 'bg-rose-50/70 border border-[#c3122e]/30 text-slate-900 hover:bg-rose-50' : 'bg-slate-50/80 hover:bg-slate-100/90 border border-slate-200/70 text-slate-700') }}"
                        >
                            <span class="text-[10px] font-bold uppercase tracking-wider leading-none {{ $wIsSelected ? 'text-white/80' : ($wIsToday ? 'text-[#c3122e]' : 'text-slate-400') }}">
                                {{ $wInfo['date']->format('D') }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm font-extrabold leading-none {{ $wIsSelected ? 'text-white' : 'text-slate-800' }}">
                                    {{ $wInfo['date']->format('j') }}
                                </span>
                                @if($wTaskCount > 0)
                                    <span class="text-[9.5px] font-bold px-1.5 py-0.2 rounded-full leading-none {{ $wIsSelected ? 'bg-white/25 text-white' : ($wIsToday ? 'bg-[#c3122e] text-white' : 'bg-slate-200 text-slate-700') }}">
                                        {{ $wTaskCount }}
                                    </span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- ── TIMEFRAME 1: DAY BREAKDOWN (Morning / Afternoon / Evening) ── -->
            @if($timelineTimeframe === 'day')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- 1. Morning Block -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Morning</h3>
                                    <span class="text-[11px] font-medium text-slate-400">08:30 AM – 12:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $morningTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($morningTasks as $mTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $mTask])
                            @empty
                                <div class="py-8 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/50 border border-dashed border-slate-200/80 flex flex-col items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No morning deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 2. Afternoon Block -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Afternoon</h3>
                                    <span class="text-[11px] font-medium text-slate-400">12:30 PM – 05:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $afternoonTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($afternoonTasks as $aTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $aTask])
                            @empty
                                <div class="py-8 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/50 border border-dashed border-slate-200/80 flex flex-col items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No afternoon deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Evening & All-Day -->
                    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Evening &amp; All-Day</h3>
                                    <span class="text-[11px] font-medium text-slate-400">After 05:30 PM / Flexible</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $eveningTasks->count() + $allDayTasks->count() }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($eveningTasks->concat($allDayTasks) as $eTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $eTask])
                            @empty
                                <div class="py-8 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/50 border border-dashed border-slate-200/80 flex flex-col items-center justify-center gap-1.5">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No evening deliverables</span>
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
                        <div class="w-[280px] min-w-[280px] flex-shrink-0 snap-start rounded-2xl p-3.5 border flex flex-col gap-3 min-h-[480px] bg-white border-slate-200/80 shadow-2xs {{ $wIsToday ? 'ring-2 ring-[#c3122e]/30' : '' }}">
                            <!-- Column Header -->
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">
                                        {{ $wInfo['date']->format('l') }}
                                    </h4>
                                    <span class="text-[11px] font-medium text-slate-400">
                                        {{ $wInfo['date']->format('M d') }}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $wIsToday ? 'bg-rose-50 text-[#c3122e]' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $wDayTasks->count() }}
                                </span>
                            </div>

                            <!-- Cards -->
                            <div class="flex-1 flex flex-col gap-2.5 overflow-y-auto">
                                @forelse($wDayTasks as $wt)
                                    @include('livewire.my-tasks-timeline-card', ['t' => $wt])
                                @empty
                                    <div class="py-12 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/60 border border-slate-100">
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
                                <svg class="w-3.5 h-3.5 text-rose-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>Project Deliverable Overview</span>
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
                                <svg class="w-3.5 h-3.5 text-rose-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
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
                            <span class="text-[10.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Timeline &amp; Schedule</span>
                            </span>
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
                            <span class="text-[10.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span>Assignment &amp; Effort</span>
                            </span>
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
                            <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
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
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-[#c3122e] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                        <span>Open <span class="font-extrabold text-slate-900 group-hover:text-[#c3122e]">{{ $detailTask->project->name ?? 'Project' }}</span> Workspace</span>
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
