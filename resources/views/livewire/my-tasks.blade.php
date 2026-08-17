<div class="space-y-6 pt-2">
    <!-- Style Override for Custom Select Arrow & Smooth Hover Effects -->
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.5' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 12px;
            padding-right: 38px !important;
        }
    </style>

    <!-- 1. Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-200/80">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center flex-shrink-0 shadow-2xs">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">My Tasks Workspace</h1>
                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 border border-slate-200/60 text-[10px] font-bold text-slate-500 font-mono">
                        {{ now()->format('M d, Y') }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Track daily deliverables, update task status, and log delay issues in real time.</p>
            </div>
        </div>

        <!-- Superadmin Mode & Quick Views -->
        <div class="flex items-center gap-2.5 flex-wrap">
            @if(auth()->user()->hasRole('super_admin'))
                <button wire:click="$set('saView', '{{ $saView === 'team' ? 'mine' : 'team' }}')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold border transition-all cursor-pointer shadow-2xs {{ $saView === 'team' ? 'border-[#c3122e] bg-[#c3122e] text-white' : 'border-indigo-100 bg-indigo-50 hover:bg-indigo-100/80 text-indigo-700' }} active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>{{ $saView === 'team' ? 'My Tasks Workspace' : 'Team Monitor Console' }}</span>
                </button>
            @endif
        </div>
    </div>

    <!-- 2. Interactive Segmented Metrics Dashboard -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $metricData = [
                [
                    'label' => 'Total Tasks',
                    'val' => $totalCount,
                    'icon' => '<svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                    'iconBg' => 'bg-slate-50 border-slate-200/60',
                    'border' => 'border-l-4 border-l-slate-400',
                    'action' => '$set("statusFilter", "all"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'all' && $dueDateFilter === 'all'
                ],
                [
                    'label' => 'In Progress',
                    'val' => $inProgressCount,
                    'icon' => '<svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18v3z"/></svg>',
                    'iconBg' => 'bg-blue-50 border-blue-100',
                    'border' => 'border-l-4 border-l-blue-500',
                    'action' => '$set("statusFilter", "in_progress"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'in_progress'
                ],
                [
                    'label' => 'Due Today',
                    'val' => $dueTodayCount,
                    'icon' => '<svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                    'iconBg' => 'bg-orange-50 border-orange-100',
                    'border' => 'border-l-4 border-l-orange-500',
                    'action' => '$set("dueDateFilter", "today"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'today'
                ],
                [
                    'label' => 'Overdue',
                    'val' => $overdueCount,
                    'icon' => '<svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    'iconBg' => 'bg-rose-50 border-rose-100',
                    'border' => 'border-l-4 border-l-rose-500',
                    'action' => '$set("dueDateFilter", "overdue"); $set("statusFilter", "all")',
                    'active' => $dueDateFilter === 'overdue'
                ],
                [
                    'label' => 'Completed',
                    'val' => $completedCount,
                    'icon' => '<svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'iconBg' => 'bg-emerald-50 border-emerald-100',
                    'border' => 'border-l-4 border-l-emerald-500',
                    'action' => '$set("statusFilter", "completed"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'completed'
                ],
                [
                    'label' => 'Blocked',
                    'val' => $blockedCount,
                    'icon' => '<svg class="w-5 h-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>',
                    'iconBg' => 'bg-red-50 border-red-100 text-red-700',
                    'border' => 'border-l-4 border-l-red-600',
                    'action' => '$set("statusFilter", "blocked"); $set("dueDateFilter", "all")',
                    'active' => $statusFilter === 'blocked'
                ],
            ];
        @endphp

        @foreach($metricData as $md)
            <button type="button"
                    wire:click="{!! $md['action'] !!}"
                    class="w-full text-left bg-white border border-slate-200/80 rounded-2xl p-4 flex items-center gap-3.5 transition-all hover:border-slate-300 hover:shadow-md duration-200 {{ $md['border'] }} cursor-pointer focus:outline-none {{ $md['active'] ? 'ring-2 ring-[#c3122e]/40 shadow-xs' : 'shadow-2xs' }}">
                <div class="w-10 h-10 rounded-xl {{ $md['iconBg'] }} border flex items-center justify-center flex-shrink-0 shadow-2xs">
                    {!! $md['icon'] !!}
                </div>
                <div class="space-y-0.5 min-w-0">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block truncate">{{ $md['label'] }}</span>
                    <span class="text-2xl font-black text-slate-900 tracking-tight leading-none block">{{ $md['val'] }}</span>
                </div>
            </button>
        @endforeach
    </div>

    <!-- 3. TEAM MONITOR SYSTEM VIEW (Active for PMO Admins) -->
    @if(auth()->user()->hasRole('super_admin') && $saView === 'team')
        <div class="space-y-6">
            <!-- Delayed/Blockers List -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5 flex-wrap gap-2">
                    <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center font-bold">
                            ⚠️
                        </span>
                        <span>Reported Task Issues & Delay Reasons Log</span>
                    </h3>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-100">
                        {{ $loggedIssues->count() }} Issue(s) Reported
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($loggedIssues as $issue)
                        <div class="bg-rose-50/40 border border-rose-100 rounded-2xl p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="font-mono text-[9px] font-black bg-rose-100 text-[#c3122e] px-1.5 py-0.5 rounded border border-rose-200">
                                        {{ $issue->wbsItem->wbs_code ?? '' }}
                                    </span>
                                    <h4 class="font-black text-xs text-slate-900 mt-1.5 leading-snug">
                                        {{ $issue->wbsItem->title ?? 'No Task Name' }}
                                    </h4>
                                    <p class="text-[10px] text-slate-500 font-bold mt-1">
                                        📁 {{ $issue->wbsItem->project->name ?? '' }}
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-100/80 text-rose-800 border border-rose-200 flex-shrink-0">
                                    {{ ucfirst($issue->severity) }}
                                </span>
                            </div>

                            <div class="text-[11px] leading-relaxed font-semibold text-slate-600 bg-white border border-rose-100/50 p-2.5 rounded-xl">
                                <span class="font-bold text-[#c3122e]">Blockage:</span> "{{ $issue->description }}"
                            </div>

                            <div class="flex items-center justify-between text-[9px] font-bold text-slate-400">
                                <span>Reported by: <strong>{{ $issue->reporter->name ?? 'System' }}</strong></span>
                                <span>{{ $issue->created_at ? $issue->created_at->diffForHumans() : '' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-10 text-center font-bold text-xs text-slate-400">
                            No team blockage issues have been reported.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Team Stats List -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-2xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            👥
                        </span>
                        <span>Team Members & PM Work Execution Monitor</span>
                    </h3>
                    <button wire:click="$set('saView', 'mine')" class="text-xs font-black text-[#c3122e] hover:underline cursor-pointer">
                        ← Back to My Tasks
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($teamDailyStats as $stat)
                        @php $u = $stat['user']; @endphp
                        <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-xs text-slate-900 truncate flex items-center gap-1.5">
                                        <span>{{ $u->name }}</span>
                                        @if($u->hasRole('project_manager'))
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black bg-amber-100 text-amber-800 border border-amber-200">PM</span>
                                        @else
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black bg-indigo-100 text-indigo-800 border border-indigo-200">Member</span>
                                        @endif
                                    </h4>
                                    <p class="text-[9px] text-slate-500 font-mono mt-0.5 truncate">{{ $u->email }}</p>
                                </div>
                                <span class="text-[10px] font-black text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                    {{ $stat['pct'] }}%
                                </span>
                            </div>

                            <div class="grid grid-cols-4 gap-2 text-center bg-white p-2.5 rounded-xl border border-slate-200/80">
                                <div>
                                    <span class="text-xs font-black text-emerald-600 block">{{ $stat['done'] }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase">Done</span>
                                </div>
                                <div>
                                    <span class="text-xs font-black text-amber-600 block">{{ $stat['in_progress'] }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase">In Prog</span>
                                </div>
                                <div>
                                    <span class="text-xs font-black text-rose-600 block">{{ $stat['overdue'] }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase">Overdue</span>
                                </div>
                                <div>
                                    <span class="text-xs font-black text-red-800 block">{{ $stat['blocked'] }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase">Blocked</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- 4. MAIN TASK VIEWS AND FILTERS -->
    @if($saView === 'mine' || !auth()->user()->hasRole('super_admin'))
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            
            <!-- Left Pane: Tasks Workspace (Width: flex-1) -->
            <div class="flex-1 min-w-0 w-full space-y-5">
                
                <!-- Consolidated Search and Filter Bar -->
                <div class="bg-white border border-slate-200/85 rounded-2xl p-5 shadow-2xs space-y-4">
                    <!-- Filters Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Search</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tasks..."
                                       class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none placeholder:text-slate-400">
                            </div>
                        </div>

                        <!-- Project -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Project</label>
                            <select wire:model.live="projectFilter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none cursor-pointer custom-select">
                                <option value="all">All Projects</option>
                                @foreach($myProjects as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
                            </select>
                        </div>

                        <!-- Status Selector -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Status</label>
                            <select wire:model.live="statusFilter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none cursor-pointer custom-select">
                                <option value="incomplete">Incomplete (default)</option>
                                <option value="all">All Statuses</option>
                                @foreach(\App\Enums\WbsStatus::cases() as $st)<option value="{{ $st->value }}">{{ $st->label() }}</option>@endforeach
                            </select>
                        </div>

                        <!-- Priority -->
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider">Priority</label>
                            <select wire:model.live="priorityFilter" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20 transition-all outline-none cursor-pointer custom-select">
                                <option value="all">All Priorities</option>
                                @foreach(\App\Enums\Priority::cases() as $pr)<option value="{{ $pr->value }}">{{ $pr->label() }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Control Footer (Quick Segmented Filter Tabs + Reset Controls) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3.5 border-t border-slate-100 flex-wrap">
                        <!-- Horizontal Status Tabs Inside Filters -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <button type="button" wire:click="$set('statusFilter', 'all'); $set('dueDateFilter', 'all')"
                                    class="px-3.5 py-2 rounded-xl text-xs font-black border transition-all cursor-pointer {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? 'border-slate-800 bg-slate-900 text-white shadow-sm' : 'border-slate-200 hover:border-slate-300 text-slate-600 bg-white hover:bg-slate-50 shadow-2xs' }}">
                                <span>All</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black ml-1.5 {{ $statusFilter === 'all' && $dueDateFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $totalCount }}</span>
                            </button>

                            <button type="button" wire:click="setDueDateFilter('today')"
                                    class="px-3.5 py-2 rounded-xl text-xs font-black border transition-all cursor-pointer {{ $dueDateFilter === 'today' ? 'border-orange-500 bg-orange-600 text-white shadow-sm' : 'border-slate-200 hover:border-slate-300 text-slate-600 bg-white hover:bg-slate-50 shadow-2xs' }}">
                                <span>Due Today</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black ml-1.5 {{ $dueDateFilter === 'today' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $dueTodayCount }}</span>
                            </button>

                            <button type="button" wire:click="$set('statusFilter', 'in_progress')"
                                    class="px-3.5 py-2 rounded-xl text-xs font-black border transition-all cursor-pointer {{ $statusFilter === 'in_progress' ? 'border-blue-500 bg-blue-600 text-white shadow-sm' : 'border-slate-200 hover:border-slate-300 text-slate-600 bg-white hover:bg-slate-50 shadow-2xs' }}">
                                <span>In Progress</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black ml-1.5 {{ $statusFilter === 'in_progress' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $inProgressCount }}</span>
                            </button>

                            <button type="button" wire:click="setDueDateFilter('overdue')"
                                    class="px-3.5 py-2 rounded-xl text-xs font-black border transition-all cursor-pointer {{ $dueDateFilter === 'overdue' ? 'border-rose-500 bg-rose-600 text-white shadow-sm' : 'border-slate-200 hover:border-slate-300 text-slate-600 bg-white hover:bg-slate-50 shadow-2xs' }}">
                                <span>Overdue</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black ml-1.5 {{ $dueDateFilter === 'overdue' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $overdueCount }}</span>
                            </button>

                            <button type="button" wire:click="$set('statusFilter', 'completed')"
                                    class="px-3.5 py-2 rounded-xl text-xs font-black border transition-all cursor-pointer {{ $statusFilter === 'completed' ? 'border-emerald-500 bg-emerald-600 text-white shadow-sm' : 'border-slate-200 hover:border-slate-300 text-slate-600 bg-white hover:bg-slate-50 shadow-2xs' }}">
                                <span>Completed</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black ml-1.5 {{ $statusFilter === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $completedCount }}</span>
                            </button>
                        </div>

                        <!-- Active filters reset action -->
                        @if($search || $projectFilter !== 'all' || $priorityFilter !== 'all' || $statusFilter !== 'incomplete' || $dueDateFilter !== 'all')
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-slate-400">
                                    Found <strong class="text-[#c3122e]">{{ $tasks->total() }}</strong>
                                </span>
                                <button type="button" wire:click="clearFilters" class="px-3.5 py-2 text-xs font-black bg-rose-50 text-[#c3122e] rounded-xl border border-rose-100 hover:bg-rose-100 transition-colors flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Reset Filters</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tasks Listing -->
                <div class="space-y-4">
                    @forelse($tasks as $t)
                        @php
                            $daysLeft   = $t->end_date ? (int) now()->today()->diffInDays($t->end_date, false) : null;
                            $isOverdue  = $daysLeft !== null && $daysLeft < 0 && !in_array($t->status->value, ['completed','cancelled']);
                            $isDueToday = $daysLeft === 0 && !in_array($t->status->value, ['completed','cancelled']);

                            $sc = match($t->status->value) {
                                'in_progress'  => ['bg'=>'bg-blue-50/40','text'=>'text-blue-800','border'=>'border-blue-100','dot'=>'bg-blue-500','left'=>'border-l-blue-500'],
                                'completed'    => ['bg'=>'bg-emerald-50/40','text'=>'text-emerald-800','border'=>'border-emerald-100','dot'=>'bg-emerald-500','left'=>'border-l-emerald-500'],
                                'blocked'      => ['bg'=>'bg-rose-50/40','text'=>'text-rose-800','border'=>'border-rose-100','dot'=>'bg-rose-600','left'=>'border-l-rose-600'],
                                'under_review' => ['bg'=>'bg-amber-50/40','text'=>'text-amber-800','border'=>'border-amber-100','dot'=>'bg-amber-500','left'=>'border-l-amber-500'],
                                default        => ['bg'=>'bg-slate-50/40','text'=>'text-slate-700','border'=>'border-slate-200','dot'=>'bg-slate-400','left'=>'border-l-slate-300'],
                            };

                            $pc = match($t->priority->value) {
                                'critical','high' => ['bg'=>'bg-rose-50','text'=>'text-rose-700','border'=>'border-rose-200/60'],
                                'medium'          => ['bg'=>'bg-amber-50','text'=>'text-amber-700','border'=>'border-amber-200/60'],
                                default           => ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200/60'],
                            };
                        @endphp

                        <!-- Redesigned Task Card with Left Accent Priority/Status Border -->
                        <div class="bg-white border border-slate-200 border-l-4 {{ $sc['left'] }} rounded-2xl p-5 shadow-2xs hover:shadow-xs transition-all duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0 space-y-2">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                                        <code class="font-mono text-[9px] font-black px-2 py-0.5 rounded bg-slate-50 text-slate-500 border border-slate-200/80">{{ $t->wbs_code }}</code>

                                        @if($isOverdue)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-50 text-rose-600 border border-rose-200 flex items-center gap-1">⚠️ {{ abs($daysLeft) }}d overdue</span>
                                        @elseif($isDueToday)
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-orange-50 text-orange-600 border border-orange-200 flex items-center gap-1">⏰ Due Today</span>
                                        @endif

                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black {{ $pc['bg'] }} {{ $pc['text'] }} border {{ $pc['border'] }}">
                                            {{ ucfirst($t->priority->value) }}
                                        </span>

                                        @if($t->assignedUser && (auth()->user()->hasRole('super_admin') || (auth()->user()->email === 'admin@nexuspm.local') || (auth()->user()->id === 1)))
                                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold text-slate-500 bg-slate-50 border border-slate-200">
                                                👤 {{ $t->assignedUser->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-sm font-black text-slate-900 leading-snug {{ $t->status->value === 'completed' ? 'line-through opacity-40' : '' }}">
                                        {{ $t->title }}
                                    </h3>

                                    <div class="flex items-center gap-3 text-[11px] font-bold text-slate-400">
                                        @if($t->project)
                                            <span class="truncate flex items-center gap-1">📁 {{ $t->project->name }}</span>
                                        @endif
                                        @if($t->end_date)
                                            <span class="flex items-center gap-1 {{ $isOverdue ? 'text-rose-600 font-extrabold' : '' }}">📅 {{ $t->end_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>

                                    @if($t->delay_reason)
                                        <div class="mt-2.5 bg-rose-50/60 border border-rose-100 rounded-xl p-3 text-[11px] font-bold text-rose-700 leading-relaxed">
                                            <strong>Logged Blockage Reason:</strong> "{{ $t->delay_reason }}"
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 justify-start sm:justify-end flex-wrap flex-shrink-0">
                                    <select wire:change="updateStatus({{ $t->id }}, $event.target.value)"
                                            class="text-xs font-black px-3 py-2 rounded-xl border {{ $sc['border'] }} {{ $sc['bg'] }} {{ $sc['text'] }} outline-none cursor-pointer custom-select shadow-2xs">
                                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                                            <option value="{{ $st->value }}" {{ $t->status->value === $st->value ? 'selected' : '' }}>
                                                {{ $t->status->value === $st->value ? '✓ ' : '' }}{{ $st->label() }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <a href="{{ route('daily-updates.index', ['project' => $t->project_id, 'task' => $t->id, 'create' => 1]) }}" title="Post daily progress log for this task"
                                       class="bg-[#fdf4f4] border border-[#faeaea] hover:bg-[#c3122e] hover:text-white text-[#c3122e] px-3.5 py-2 rounded-xl inline-flex items-center gap-1.5 text-xs font-black transition-colors shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2-2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Daily Log</span>
                                    </a>

                                    <button type="button" wire:click="openDelayReasonModal({{ $t->id }})" title="Log issue / delay reason"
                                            class="bg-amber-50 border border-amber-200/80 hover:bg-amber-500 hover:text-white text-amber-700 px-3.5 py-2 rounded-xl cursor-pointer inline-flex items-center gap-1.5 text-xs font-black transition-colors shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>Issue</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Subtasks Section -->
                            @if($t->children && $t->children->count() > 0)
                                <div class="mt-4 pt-4 border-t border-dashed border-slate-200">
                                    <div class="flex items-center justify-between gap-3 mb-3 flex-wrap">
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-wider flex items-center gap-1.5">
                                            📋 Sub-Tasks Breakdown ({{ $t->children->count() }})
                                        </span>
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                                            {{ $t->children->where('status.value', 'completed')->count() }} / {{ $t->children->count() }} Completed
                                        </span>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach($t->children as $sub)
                                            <div class="flex items-center justify-between gap-4 p-3 bg-slate-50/50 border border-slate-200/80 rounded-xl">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <code class="font-mono text-[9px] font-black bg-indigo-50 border border-indigo-100 text-indigo-600 px-1.5 py-0.5 rounded">{{ $sub->wbs_code }}</code>
                                                    <span class="text-xs font-bold text-slate-700 truncate {{ $sub->status->value === 'completed' ? 'line-through opacity-40' : '' }}">
                                                        {{ $sub->title }}
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center gap-2 flex-shrink-0">
                                                    <select wire:change="updateStatus({{ $sub->id }}, $event.target.value)"
                                                            class="text-[10px] font-black px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 outline-none cursor-pointer custom-select">
                                                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                                                            <option value="{{ $st->value }}" {{ $sub->status->value === $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white border border-slate-200 rounded-3xl p-12 text-center flex flex-col items-center justify-center shadow-2xs w-full">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-2xs mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-black text-slate-800">Workspace is Clear</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto font-semibold">No deliverables found matching your search or filters. Try adjusting your filter parameters.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div>
                    {{ $tasks->links() }}
                </div>
            </div>

            <!-- Right Pane: Sidebar Panels (Width: 320px) -->
            <div class="w-full lg:w-80 flex-shrink-0 space-y-6">
                <!-- Focus Progress Dial Card -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-2xs flex flex-col items-center text-center">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-4 self-start">Focus for Today</h3>
                    <div class="relative w-28 h-28 flex items-center justify-center">
                        <svg viewBox="0 0 100 100" class="w-full h-full transform -rotate-90">
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#f1f5f9" stroke-width="8"/>
                            <circle cx="50" cy="50" r="40" fill="none"
                                    stroke="url(#focusGradient)" stroke-width="8"
                                    stroke-linecap="round"
                                    stroke-dasharray="251.3"
                                    stroke-dashoffset="{{ 251.3 * (1 - ($totalCount > 0 ? $completedCount / $totalCount : 0)) }}"/>
                            <defs>
                                <linearGradient id="focusGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#c3122e" />
                                    <stop offset="100%" stop-color="#ef4444" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-2xl font-black text-slate-800 tracking-tight leading-none">
                                {{ $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0 }}%
                            </span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider mt-1">Done</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-500 mt-4">Deliverables Progress Dial</span>
                </div>

                <!-- Today's Schedule -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            ⏰ Today's Schedule
                        </h3>
                        <span class="text-[10px] font-extrabold text-slate-400">{{ now()->format('d M') }}</span>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($todaySchedule as $ts)
                            <div class="p-3.5 rounded-xl bg-slate-50/50 border border-slate-200/80 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-800 truncate leading-snug">{{ $ts->title }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5 truncate font-medium">{{ $ts->project->name ?? '' }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black bg-amber-50 text-amber-700 border border-amber-200/80 flex-shrink-0">
                                    {{ $ts->status->label() }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4 font-bold">No tasks scheduled for today.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                            📅 Upcoming Deadlines
                        </h3>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($upcomingDeadlines as $ud)
                            @php $dl = $ud->end_date ? (int) now()->today()->diffInDays($ud->end_date, false) : null; @endphp
                            <div class="p-3.5 rounded-xl bg-slate-50/50 border border-slate-200/80 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-800 truncate leading-snug">{{ $ud->title }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5 truncate font-medium">{{ $ud->project->name ?? '' }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black bg-rose-50 text-[#c3122e] border border-rose-100 flex-shrink-0">
                                    {{ $dl }}d left
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-4 font-bold">No upcoming deadlines.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delay / Reason Modal Popup -->
    @if($showDelayReasonModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="relative bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-md p-6 sm:p-7 z-10">
                <div class="flex items-center gap-3 mb-5 pb-3 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-500 flex items-center justify-center font-black text-sm flex-shrink-0 shadow-2xs">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Log Issue or Delay Reason</h3>
                        <p class="text-xs text-slate-500 font-medium">Explain why this task cannot be completed or is blocked</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-xs text-slate-500 leading-relaxed font-semibold">
                        Providing a delay reason will automatically flag the task and alert the Project Manager.
                    </p>

                    <div class="form-group">
                        <textarea wire:model="delayReasonText" rows="4" placeholder="Explain the blocker or delay reason in detail..."
                                  class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none"></textarea>
                        @error('delayReasonText') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 mt-5">
                        <button type="button" wire:click="$set('showDelayReasonModal', false)" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancel</button>
                        <button type="button" wire:click="submitDelayReason" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-colors">Submit Reason &amp; Report</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
