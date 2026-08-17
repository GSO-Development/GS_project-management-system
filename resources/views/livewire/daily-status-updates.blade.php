<div class="py-6 space-y-6 pb-12">
    
    <!-- 1. HEADER & NAVIGATION AREA -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                <a href="{{ route('dashboard') }}" class="hover:text-[#c3122e] transition-colors">Dashboard</a>
                <svg class="w-2.5 h-2.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-slate-600">Daily Updates</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Daily Project Updates</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Track, review, and collaborate on project execution logs across your teams.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Date Widget -->
            <div class="px-3.5 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-[#c3122e] flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block leading-none">Today</span>
                    <span class="text-xs font-bold text-slate-800 font-mono mt-0.5 block">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>

            @if(!$this->isSuperAdminUser(auth()->user()))
                <button
                    wire:click="openStatusUpdateModal()"
                    class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm hover:shadow transition-all flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Publish Daily Log</span>
                </button>
            @endif
        </div>
    </div>

    <!-- 2. KPI METRICS SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Reports Logged Today -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-300 flex items-start gap-4 group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-emerald-50 text-emerald-600 border border-emerald-100 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="space-y-0.5 flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Logged Today</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $updatedTodayCount }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Active
                    </span>
                </div>
                <p class="text-[10px] text-slate-400 font-medium truncate">Day-by-day progress stream</p>
            </div>
        </div>

        <!-- Participant Task Logs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-rose-200 transition-all duration-300 flex items-start gap-4 group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-rose-50 text-[#c3122e] border border-rose-100 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div class="space-y-0.5 flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Task Logs</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $taskUpdatesCount }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-[#c3122e] border border-rose-100">Tasks</span>
                </div>
                <p class="text-[10px] text-slate-400 font-medium truncate">Visible to Project Managers</p>
            </div>
        </div>

        <!-- PM Overall Reports -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-amber-200 transition-all duration-300 flex items-start gap-4 group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-amber-50 text-amber-600 border border-amber-100 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div class="space-y-0.5 flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Overall Reports</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $projectUpdatesCount }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-100">Overall</span>
                </div>
                <p class="text-[10px] text-slate-400 font-medium truncate">Visible to PMO Admins</p>
            </div>
        </div>

        <!-- Pending Review / Uncommented Logs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all duration-300 flex items-start gap-4 group">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-indigo-50 text-indigo-600 border border-indigo-100 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div class="space-y-0.5 flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Pending Feedback</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $uncommentedUpdatesCount }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">Review</span>
                </div>
                <p class="text-[10px] text-slate-400 font-medium truncate">Unanswered status logs</p>
            </div>
        </div>
    </div>

    <!-- 3. CONTROLS, SEARCH & FILTER TOOLBAR -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm space-y-4">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <!-- Left: Scope Pills Segment Controller -->
            <div class="flex items-center gap-1 p-1 bg-slate-100/80 rounded-xl flex-wrap max-w-max">
                <button
                    wire:click="$set('selectedScope', 'all')"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5 {{ $selectedScope === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <span>All Reports ({{ $totalUpdatesCount }})</span>
                </button>
                <button
                    wire:click="$set('selectedScope', 'task')"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5 {{ $selectedScope === 'task' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                    <span>Participant Task Logs</span>
                </button>
                <button
                    wire:click="$set('selectedScope', 'project')"
                    class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5 {{ $selectedScope === 'project' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-[#b8860b]"></span>
                    <span>PM Overall Project Reports</span>
                </button>
            </div>

            <!-- Right: Search box, Project & Date Selectors -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 xl:justify-end">
                <!-- Search Box -->
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchQuery"
                        placeholder="Search logs, reporter, task..."
                        class="w-full text-xs font-semibold py-2.5 pl-9 pr-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Project Selector Dropdown -->
                <div class="relative min-w-[180px]">
                    <select
                        wire:model.live="selectedProjectId"
                        class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all appearance-none cursor-pointer text-slate-800"
                    >
                        <option value="">🌐 All Accessible Projects</option>
                        @foreach($accessibleProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Date Filter Dropdown -->
                <div class="relative">
                    <select
                        wire:model.live="dateFilter"
                        class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all appearance-none cursor-pointer text-slate-800"
                    >
                        <option value="all">📅 All Time</option>
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. PROJECTS DAILY UPDATES TABLE -->
    <div class="w-full rounded-2xl bg-white shadow-sm overflow-hidden" style="border: 1px solid #e2e8f0;">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th class="py-3.5 pl-6 pr-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[260px]">Project Code & Name</th>
                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[170px]">Subsidiary</th>
                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[180px]">Project Manager</th>
                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[260px]">Latest Update Log</th>
                        <th class="py-3.5 px-4 text-[11px] font-bold uppercase tracking-wider text-slate-500 min-w-[130px]">Last Log Date</th>
                        <th class="py-3.5 pl-4 pr-6 text-[11px] font-bold uppercase tracking-wider text-slate-500 text-right min-w-[200px]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($groupedProjectUpdates as $group)
                        @php
                            $project = $group['project'];
                            $update = $group['latestUpdate'];
                            $pastUpdates = $group['pastUpdates'];
                            $allUpdatesCount = $group['allUpdates']->count();
                            $pm = $project->projectManager;
                            $subsidiary = $project->subsidiary;
                        @endphp

                        <tr class="hover:bg-slate-50/80 transition-colors" style="border-bottom: 1px solid #f1f5f9;">
                            <!-- Project Code & Name -->
                            <td class="py-4 pl-6 pr-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <span class="shadow-sm flex-shrink-0" style="background: #fff1f2; color: #c3122e; border: 1px solid #ffe4e6; font-family: monospace; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;">
                                        {{ $project->code ?? 'PRJ' }}
                                    </span>
                                    <div class="min-w-0 space-y-0.5">
                                        <a href="{{ route('projects.show', $project) }}" class="font-bold text-xs text-slate-900 hover:text-[#c3122e] transition-colors truncate block max-w-[200px]" title="{{ $project->name }}">
                                            {{ $project->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-medium block">{{ $allUpdatesCount }} Log(s) recorded</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Subsidiary Name -->
                            <td class="py-4 px-4 align-middle">
                                @if($subsidiary)
                                    <div class="flex items-center gap-2 min-w-0 text-slate-700">
                                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        <span class="font-semibold text-xs text-slate-700 truncate max-w-[150px]" title="{{ $subsidiary->name }}">{{ $subsidiary->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium italic text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Project Manager -->
                            <td class="py-4 px-4 align-middle">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #c3122e, #a00e24);">
                                        {{ strtoupper(substr($pm->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 space-y-0.5">
                                        <span class="font-bold text-slate-800 block truncate text-xs max-w-[120px]" title="{{ $pm->name ?? 'Unassigned' }}">{{ $pm->name ?? 'Unassigned' }}</span>
                                        <span style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 8px; font-weight: 700; padding: 1px 6px; border-radius: 4px; display: inline-block; letter-spacing: 0.5px;">PM</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Latest Update Summary -->
                            <td class="py-4 px-4 align-middle max-w-xs">
                                @if($update)
                                    <div class="min-w-0 space-y-0.5">
                                        <span class="font-bold text-slate-800 block truncate text-xs max-w-[240px]" title="{{ $update->title }}">{{ $update->title }}</span>
                                        <p class="text-[11px] text-slate-450 italic font-medium truncate max-w-[240px]">"{{ $update->summary }}"</p>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-slate-400 italic text-[11px] font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <span>No status updates logged</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Last Log Date -->
                            <td class="py-4 px-4 align-middle font-mono text-[10px] text-slate-400 font-semibold whitespace-nowrap">
                                @if($update)
                                    <div class="flex items-center gap-1.5 text-slate-500">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $update->created_at->diffForHumans() }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-300 font-light">-</span>
                                @endif
                            </td>

                            <!-- Actions & Toggle Arrow Button -->
                            <td class="py-4 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-2">
                                    @if(!$this->isSuperAdminUser(auth()->user()))
                                        <button
                                            wire:click="openStatusUpdateModal({{ $project->id }})"
                                            type="button"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm transition-all cursor-pointer"
                                        >
                                            + Log Update
                                        </button>
                                    @endif

                                    <!-- View History Modal Trigger -->
                                    <button
                                        wire:click="openHistoryModal({{ $project->id }})"
                                        type="button"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 hover:text-[#c3122e] hover:border-slate-300 transition-all cursor-pointer flex items-center gap-1 group shadow-sm"
                                        style="border: 1px solid #e2e8f0;"
                                        title="Click to view status update history for {{ $project->name }}"
                                    >
                                        <span class="text-[11px] font-semibold">Updates ({{ $allUpdatesCount }})</span>
                                        <svg class="w-3 h-3 text-slate-400 transition-transform group-hover:translate-x-0.5 group-hover:text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-xs font-bold text-slate-400 space-y-2">
                                <svg class="w-10 h-10 text-slate-200 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="text-slate-500 font-bold">No Projects Found</p>
                                <p class="text-slate-400 font-medium text-[11px]">We couldn't find any projects matching your current query.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: PROJECT STATUS UPDATE HISTORY & FEEDBACK LOOP POPUP -->
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
                <div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-slate-900/40" wire:click="closeHistoryModal"></div>

                <!-- Popup Content Box -->
                <div class="relative bg-white rounded-3xl max-w-4xl w-full shadow-2xl z-10 flex flex-col max-h-[90vh] md:max-h-[85vh] overflow-hidden border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 flex items-center justify-between border-b border-rose-900/20 flex-shrink-0" style="background: linear-gradient(135deg, #7a0b1d 0%, #c3122e 100%); color: #ffffff;">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-inner" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                                📊
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-white leading-tight truncate">
                                    Project Execution Logs: {{ $historyProject->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span style="background: rgba(255,255,255,0.2); color: #ffffff; padding: 1px 7px; border-radius: 6px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px;">
                                        {{ $historyProject->code }}
                                    </span>
                                    <span style="color: #fecdd3; font-size: 11px; font-weight: 600;">
                                        • {{ $historyUpdatesCount }} Log(s) recorded
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button 
                            wire:click="closeHistoryModal" 
                            type="button" 
                            class="px-3 py-1.5 rounded-xl text-white font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 hover:bg-white/25"
                            style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);"
                            title="Close Window"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Close</span>
                        </button>
                    </div>

                    <!-- Modal Body Grid -->
                    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6 scrollbar-thin bg-slate-50/40">
                        @if($historyLatestUpdate)
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <!-- Left side: Latest Update Details (58%) -->
                                <div class="w-full md:w-[58%] space-y-4">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Latest Status Update Log
                                    </div>

                                    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm space-y-5">
                                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-2.5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full font-bold flex items-center justify-center text-xs text-white shadow-sm" style="background: linear-gradient(135deg, #c3122e, #a00e24);">
                                                    {{ strtoupper(substr($historyLatestUpdate->creator->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-bold text-xs text-slate-900 block leading-tight">{{ $historyLatestUpdate->creator->name ?? 'User' }}</span>
                                                    <span class="text-[10px] font-bold text-slate-400 font-mono tracking-wide block mt-0.5">{{ $historyLatestUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-mono font-bold bg-[#c3122e] text-white shadow-sm">
                                                Log #{{ $historyUpdatesCount }}
                                            </span>
                                        </div>

                                        <div class="space-y-3">
                                            <h4 class="font-bold text-sm text-slate-900 leading-snug">{{ $historyLatestUpdate->title }}</h4>
                                            
                                            <!-- Beautiful Quote Box with straight left border & GS Crimson tint -->
                                            <div class="relative p-4 rounded-r-xl rounded-l-none text-slate-800 font-semibold text-xs leading-relaxed shadow-sm" style="background: #fff1f2; border-left: 4px solid #c3122e;">
                                                <span class="absolute top-2 left-2 text-2xl text-[#c3122e]/10 font-serif leading-none select-none">“</span>
                                                <p class="pl-2 pr-1 italic">"{{ $historyLatestUpdate->summary }}"</p>
                                            </div>
                                        </div>

                                        @if($historyLatestUpdate->work_completed || $historyLatestUpdate->current_blockers)
                                            <div class="space-y-2 text-xs pt-1">
                                                @if($historyLatestUpdate->work_completed)
                                                    <div class="p-3 rounded-xl bg-emerald-50/80 text-emerald-800 border border-emerald-100 font-semibold flex items-start gap-2">
                                                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        <div>
                                                            <strong class="text-emerald-950 font-bold">Work Completed:</strong> 
                                                            <span>{{ $historyLatestUpdate->work_completed }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($historyLatestUpdate->current_blockers)
                                                    <div class="p-3 rounded-xl bg-rose-50/80 text-rose-800 border border-rose-100 font-semibold flex items-start gap-2">
                                                        <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        <div>
                                                            <strong class="text-rose-950 font-bold">Current Blockers:</strong> 
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
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider text-rose-800 bg-rose-50 border border-rose-200">
                                        💬 PM & PMO Admin Feedback Loop
                                    </div>

                                    @php
                                        $visibleComments = $historyLatestUpdate->comments;
                                    @endphp

                                    <div class="space-y-3 max-h-[300px] overflow-y-auto p-4 bg-white border border-slate-100 rounded-2xl shadow-sm scrollbar-thin">
                                        @forelse($visibleComments as $comment)
                                            @php
                                                $commentUser = $comment->user;
                                                $commenterRole = 'Team Member';
                                                $commentBadgeStyle = 'background:#e2e8f0; color:#334155; border:1px solid #cbd5e1;';

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
                                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-100 shadow-sm">
                                                <div class="w-6.5 h-6.5 rounded-full flex items-center justify-center font-bold text-[9px] text-white shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #a00e24);">
                                                    {{ strtoupper(substr($commentUser->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0 space-y-0.5">
                                                    <div class="flex items-center justify-between gap-1 flex-wrap">
                                                        <div class="flex items-center gap-1.5 flex-wrap">
                                                            <span class="text-xs font-bold text-slate-900">{{ $commentUser->name ?? 'User' }}</span>
                                                            <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase tracking-wide" style="{{ $commentBadgeStyle }}">{{ $commenterRole }}</span>
                                                        </div>
                                                        <span class="text-[9px] font-bold text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs font-semibold text-slate-700 leading-relaxed">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-14 text-center text-xs font-semibold text-slate-400 italic space-y-2.5">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
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
                                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all flex-1"
                                        >
                                        <button
                                            wire:click="addComment({{ $historyLatestUpdate->id }})"
                                            class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm transition-all cursor-pointer flex-shrink-0"
                                        >
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="py-12 text-center text-xs font-medium text-slate-400 bg-slate-50 rounded-2xl border border-slate-100">
                                No status update logs posted yet for this project.
                            </div>
                        @endif

                        <!-- ALL UPDATES LOG TIMELINE (PAST HISTORY) -->
                        <div class="space-y-4 pt-5 border-t border-slate-100">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider text-slate-600 bg-slate-100 border border-slate-200/60">
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
                                        <span class="absolute -left-[24px] top-1.5 w-3.5 h-3.5 rounded-full border-[3px] border-white shadow-sm flex-shrink-0 transition-transform group-hover:scale-110" style="background: {{ $pIndex === 0 ? '#c3122e' : '#cbd5e1' }};"></span>

                                        <!-- Log Card Content -->
                                        <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-slate-200 transition-all duration-200 space-y-3">
                                            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 flex-wrap gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#c3122e] text-white shadow-sm">
                                                        #{{ $historyUpdatesCount - $pIndex }}
                                                    </span>
                                                    <h6 class="font-bold text-xs text-slate-900 leading-snug">{{ $pUpdate->title }}</h6>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs flex-wrap">
                                                    <span class="font-bold text-slate-800">{{ $pCreator->name ?? 'User' }}</span>
                                                    <span class="px-1.5 py-0.2 rounded text-[7px] font-bold uppercase tracking-wide shadow-sm" style="{{ $pRoleBadgeStyle }}">{{ $pCreatorRole }}</span>
                                                    <span class="text-slate-400 font-mono text-[9px] font-bold">{{ $pUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Quote Panel inside timeline card with straight left border -->
                                            <div class="p-3.5 rounded-r-xl rounded-l-none italic text-xs bg-slate-50 text-slate-800 leading-relaxed font-semibold shadow-sm" style="border-left: 4px solid #cbd5e1;">
                                                "{{ $pUpdate->summary }}"
                                            </div>
                                            
                                            @if($pUpdate->work_completed || $pUpdate->current_blockers)
                                                <div class="flex flex-wrap gap-2.5 text-[10px] pt-1">
                                                    @if($pUpdate->work_completed)
                                                        <span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 border border-emerald-100 font-bold flex items-center gap-1 shadow-sm">
                                                            ✓ {{ $pUpdate->work_completed }}
                                                        </span>
                                                    @endif
                                                    @if($pUpdate->current_blockers)
                                                        <span class="px-2.5 py-1 rounded bg-rose-50 text-rose-800 border border-rose-100 font-bold flex items-center gap-1 shadow-sm">
                                                            ⚠ {{ $pUpdate->current_blockers }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center text-xs font-semibold text-slate-400 bg-slate-50 rounded-xl border border-slate-100">
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

    <!-- MODAL: POST DAILY STATUS UPDATE -->
    @if($showUpdateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 md:p-10 animate-fade-in">
            <!-- Overlay Backdrop with soft blur -->
            <div class="fixed inset-0 backdrop-blur-sm transition-opacity bg-slate-900/40" wire:click="$set('showUpdateModal', false)"></div>

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-0 overflow-hidden shadow-2xl z-10 border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
                <!-- Modal Header -->
                <div class="px-6 py-4 flex items-center justify-between border-b border-rose-900/20 flex-shrink-0" style="background: linear-gradient(135deg, #7a0b1d 0%, #c3122e 100%); color: #ffffff;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-inner" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            📝
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white leading-tight">Publish Daily Progress Log</h3>
                            <p style="color: #fecdd3; font-size: 11px; font-weight: 500; margin-top: 2px;">Log task execution progress or overall project status update</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showUpdateModal', false)" 
                        type="button" 
                        class="px-3 py-1.5 rounded-xl text-white font-bold text-xs transition-all cursor-pointer flex items-center gap-1.5 hover:bg-white/25"
                        style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);"
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
                        <label class="block text-xs font-bold text-slate-700">Target Project <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select
                                wire:model.live="updateProjectId"
                                class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all appearance-none cursor-pointer text-slate-800"
                            >
                                @foreach($accessibleProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('updateProjectId') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Scope Selector (Task vs Project Overall) -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'task')"
                                class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'task' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
                            >
                                📍 Task-Specific Log
                            </button>
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'project')"
                                class="flex-1 py-2 px-3 rounded-lg text-xs font-bold transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'project' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}"
                            >
                                🌐 Full Project Report
                            </button>
                        </div>
                    @endif

                    <!-- Task Linker if Task Scope -->
                    @if($updateScopeType === 'task')
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">📍 WBS Scope Task</label>
                            <div class="relative">
                                <select
                                    wire:model="updateWbsItemId"
                                    class="w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all appearance-none cursor-pointer text-slate-800"
                                >
                                    <option value="">Select Task...</option>
                                    @foreach($modalWbsItems as $wbs)
                                        <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1">Select the specific task you worked on today.</p>
                        </div>
                    @endif

                    <!-- Log Title -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Log Title <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            wire:model="updateTitle"
                            placeholder="e.g. Completed Payment Gateway API integration tests"
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800"
                        >
                        @error('updateTitle') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Daily Executive Summary -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Daily Executive Summary <span class="text-rose-500">*</span></label>
                        <textarea
                            wire:model="updateSummary"
                            rows="3"
                            placeholder="Detail main work completed, progress made, or key highlights..."
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800"
                        ></textarea>
                        @error('updateSummary') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Deliverables and Blockers -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Work Completed Today</label>
                            <input
                                type="text"
                                wire:model="updateWorkCompleted"
                                placeholder="Deliverables finished..."
                                class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800"
                            >
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Blockers / Issues (If Any)</label>
                            <input
                                type="text"
                                wire:model="updateBlockers"
                                placeholder="Execution blockers faced..."
                                class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800"
                            >
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="$set('showUpdateModal', false)"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm transition-all cursor-pointer"
                        >
                            Publish Daily Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
