<div class="space-y-5 pt-1">
<style>
    .at-table-row:hover { background: #f8fafc; }
    .at-table-row:hover .at-task-title { color: #c3122e; }
    .at-status-dot { width:7px; height:7px; border-radius:50%; display:inline-block; flex-shrink:0; }
    .at-avatar { width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; }
    .at-filter-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 11px;
        padding-right: 30px !important;
    }
    .kpi-tile { transition: all 0.15s ease; }
    .kpi-tile:hover { transform: translateY(-1px); }
    .kpi-tile.active { box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
</style>

    {{-- ══════════════════════ HEADER ══════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#1e293b 0%,#475569 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(30,41,59,.25);">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    @if($viewMode === 'stuck')
                        Stuck &amp; Blocked Tasks Radar
                    @else
                        All Tasks Directory
                    @endif
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    @if($viewMode === 'stuck')
                        Live governance radar for blocked deliverables, overdue milestones, and delay escalations
                    @else
                        Organisation-wide task view, scheduling status, and progress across all projects
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            <!-- View Mode Switcher: All Tasks vs Stuck Tasks -->
            <div class="inline-flex p-1 rounded-xl border border-slate-200/90 bg-slate-100/90 shadow-2xs flex-nowrap min-w-max">
                <button 
                    wire:click="setViewMode('all')" 
                    type="button" 
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'all' ? 'bg-white text-slate-900 shadow-xs scale-[1.02]' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                    title="All Tasks View"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>All Tasks</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px] font-mono {{ $viewMode === 'all' ? 'bg-slate-100 text-slate-700' : 'bg-slate-200/80 text-slate-600' }}">{{ $kpi['total'] }}</span>
                </button>

                <button 
                    wire:click="setViewMode('stuck')" 
                    type="button" 
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'stuck' ? 'bg-[#c3122e] text-white shadow-xs scale-[1.02]' : 'text-rose-700 hover:text-rose-900 hover:bg-rose-50' }}"
                    title="Live Stuck & Blocked Tasks Radar"
                >
                    <span class="w-2 h-2 rounded-full bg-rose-400 {{ $totalStuckTasksCount > 0 ? 'animate-pulse' : '' }}"></span>
                    <span>Stuck Tasks</span>
                    <span class="px-1.5 py-0.2 rounded-md text-[10px] font-mono {{ $viewMode === 'stuck' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $totalStuckTasksCount }}</span>
                </button>
            </div>

            <a href="{{ auth()->user()?->isPmoAdmin() ? route('projects.index') : route('projects.my-leads') }}" class="no-underline inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors px-3 py-2 rounded-xl bg-white border border-slate-200 hover:border-slate-300 self-start sm:self-auto flex-shrink-0">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to Projects
            </a>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MODE 1: ALL TASKS DIRECTORY VIEW
         ═══════════════════════════════════════════════════════════════ --}}
    @if($viewMode === 'all')
        {{-- KPI TILES --}}
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:10px;">
            {{-- Total --}}
            <button wire:click="clearFilters" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='all' ? 'active border-slate-800' : 'bg-white border-slate-200 hover:border-slate-300' }}"
                style="{{ $statusFilter==='all' ? 'background:linear-gradient(135deg,#1e293b,#475569);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='all' ? 'text-white' : 'text-slate-900' }}">{{ $kpi['total'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='all' ? 'text-slate-300' : 'text-slate-400' }}">Total</div>
            </button>
            {{-- In Progress --}}
            <button wire:click="$set('statusFilter','in_progress')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='in_progress' ? 'active border-blue-600' : 'bg-white border-slate-200 hover:border-blue-300' }}"
                style="{{ $statusFilter==='in_progress' ? 'background:linear-gradient(135deg,#2563eb,#1d4ed8);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='in_progress' ? 'text-white' : 'text-blue-600' }}">{{ $kpi['in_progress'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='in_progress' ? 'text-blue-100' : 'text-slate-400' }}">Progress</div>
            </button>
            {{-- Not Started --}}
            <button wire:click="$set('statusFilter','not_started')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='not_started' ? 'active border-slate-500' : 'bg-white border-slate-200 hover:border-slate-300' }}"
                style="{{ $statusFilter==='not_started' ? 'background:linear-gradient(135deg,#64748b,#475569);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='not_started' ? 'text-white' : 'text-slate-600' }}">{{ $kpi['not_started'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='not_started' ? 'text-slate-200' : 'text-slate-400' }}">Not Started</div>
            </button>
            {{-- On Hold --}}
            <button wire:click="$set('statusFilter','on_hold')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='on_hold' ? 'active border-amber-400' : 'bg-white border-slate-200 hover:border-amber-200' }}"
                style="{{ $statusFilter==='on_hold' ? 'background:linear-gradient(135deg,#f59e0b,#d97706);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='on_hold' ? 'text-white' : 'text-amber-600' }}">{{ $kpi['on_hold'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='on_hold' ? 'text-amber-100' : 'text-slate-400' }}">On Hold</div>
            </button>
            {{-- Blocked --}}
            <button wire:click="$set('statusFilter','blocked')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='blocked' ? 'active border-rose-500' : 'bg-white border-slate-200 hover:border-rose-200' }}"
                style="{{ $statusFilter==='blocked' ? 'background:linear-gradient(135deg,#ef4444,#dc2626);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='blocked' ? 'text-white' : 'text-rose-600' }}">{{ $kpi['blocked'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='blocked' ? 'text-rose-100' : 'text-slate-400' }}">Blocked</div>
            </button>
            {{-- Completed --}}
            <button wire:click="$set('statusFilter','completed')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='completed' ? 'active border-emerald-500' : 'bg-white border-slate-200 hover:border-emerald-200' }}"
                style="{{ $statusFilter==='completed' ? 'background:linear-gradient(135deg,#10b981,#059669);' : '' }}">
                <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='completed' ? 'text-white' : 'text-emerald-600' }}">{{ $kpi['completed'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='completed' ? 'text-emerald-100' : 'text-slate-400' }}">Completed</div>
            </button>
            {{-- Overdue (no filter — just info) --}}
            <div class="kpi-tile rounded-2xl border bg-white border-slate-200 py-3.5 px-2 text-center">
                <div class="text-2xl font-black font-mono leading-none {{ $kpi['overdue'] > 0 ? 'text-rose-500' : 'text-slate-400' }}">{{ $kpi['overdue'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-400">Overdue</div>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-3.5 sm:p-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 flex-1 flex-wrap">
                    {{-- Search --}}
                    <div class="relative min-w-[200px] flex-1">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tasks, projects, assignees…"
                            class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all" />
                    </div>
                    {{-- Project Filter --}}
                    <select wire:model.live="projectFilter" class="at-filter-select px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 focus:bg-white focus:outline-none cursor-pointer max-w-[200px] truncate">
                        <option value="all">All Projects</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ Str::limit($proj->name, 28) }}</option>
                        @endforeach
                    </select>
                    {{-- Priority Filter --}}
                    <select wire:model.live="priorityFilter" class="at-filter-select px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 focus:bg-white focus:outline-none cursor-pointer">
                        <option value="all">All Priorities</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    {{-- Sort Filter --}}
                    <select wire:model.live="sortBy" class="at-filter-select px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 focus:bg-white focus:outline-none cursor-pointer">
                        <option value="end_date">Sort: Due Date</option>
                        <option value="priority">Sort: Priority</option>
                        <option value="project">Sort: Project</option>
                        <option value="title">Sort: Title A–Z</option>
                    </select>
                </div>
                {{-- Clear Filters --}}
                @if($search || $statusFilter !== 'all' || $projectFilter !== 'all' || $priorityFilter !== 'all')
                    <button wire:click="clearFilters" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Clear all</span>
                    </button>
                @endif
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[10.5px] font-bold text-slate-400 uppercase tracking-wider select-none">
                            <th class="py-3.5 pl-6 pr-4">Task</th>
                            <th class="py-3.5 px-4">Project</th>
                            <th class="py-3.5 px-4">Assignee</th>
                            <th class="py-3.5 px-4 text-center">Priority</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 pl-4 pr-6 text-right">Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tasks as $task)
                            @php
                                $today     = now()->today();
                                $daysLeft  = $task->end_date ? (int) $today->diffInDays($task->end_date, false) : null;
                                $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($task->status->value, ['completed','cancelled']);
                                $isDueToday = $daysLeft === 0 && !in_array($task->status->value, ['completed','cancelled']);

                                [$priText, $priDot, $priBg, $priBorder, $priTextCls] = match($task->priority?->value ?? 'medium') {
                                    'high'  => ['High',   'bg-rose-500',   'bg-rose-50',   'border-rose-200',   'text-rose-700'],
                                    'low'   => ['Low',    'bg-emerald-500','bg-emerald-50','border-emerald-200','text-emerald-700'],
                                    default => ['Medium', 'bg-amber-500',  'bg-amber-50',  'border-amber-200',  'text-amber-700'],
                                };

                                $statusBadge = $task->status->badgeClass();
                                $statusLabel = $task->status->label();

                                $dotColorCls = match($task->status->value) {
                                    'in_progress'  => 'bg-blue-600',
                                    'completed'    => 'bg-emerald-500',
                                    'on_hold'      => 'bg-amber-500',
                                    'blocked'      => 'bg-rose-500',
                                    'under_review' => 'bg-purple-500',
                                    default        => 'bg-slate-400',
                                };

                                $initials = strtoupper(substr($task->assignedUser?->name ?? '?', 0, 1));
                                $avatarBgs = ['bg-indigo-100 text-indigo-700', 'bg-pink-100 text-pink-700', 'bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-purple-100 text-purple-700', 'bg-rose-100 text-rose-700', 'bg-sky-100 text-sky-700'];
                                $avatarStyle = $avatarBgs[crc32($task->assignedUser?->name ?? '') % count($avatarBgs)];
                            @endphp
                            <tr class="at-table-row hover:bg-slate-50/70 transition-colors cursor-pointer" onclick="window.location='{{ route('projects.show', $task->project_id) }}'">
                                {{-- Task --}}
                                <td class="py-3.5 pl-6 pr-4 align-middle">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-2 h-2 rounded-full {{ $dotColorCls }} shrink-0"></span>
                                        <div class="min-w-0">
                                            <div class="at-task-title text-xs font-bold text-slate-900 truncate transition-colors leading-tight" title="{{ $task->title }}">
                                                {{ $task->title }}
                                            </div>
                                            @if($task->wbs_code)
                                                <span class="text-[10px] font-mono font-medium text-slate-400 block mt-0.5">{{ $task->wbs_code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Project --}}
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    <div class="font-bold text-xs text-slate-800 truncate max-w-[180px]" title="{{ $task->project->name ?? '' }}">
                                        {{ $task->project->name ?? '—' }}
                                    </div>
                                    @if($task->project?->subsidiary?->name)
                                        <div class="text-[10.5px] text-slate-400 font-medium truncate max-w-[180px] mt-0.5">
                                            {{ $task->project->subsidiary->name }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Assignee --}}
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @if($task->assignedUser)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full {{ $avatarStyle }} font-extrabold text-[10px] flex items-center justify-center shrink-0">
                                                {{ $initials }}
                                            </div>
                                            <span class="text-xs font-semibold text-slate-700 truncate">
                                                {{ explode(' ', $task->assignedUser->name)[0] }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic font-normal">Unassigned</span>
                                    @endif
                                </td>

                                {{-- Priority --}}
                                <td class="py-3.5 px-4 align-middle text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10.5px] font-extrabold border {{ $priBg }} {{ $priBorder }} {{ $priTextCls }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $priDot }} shrink-0"></span>
                                        <span>{{ $priText }}</span>
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-4 align-middle text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-extrabold border {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Due Date --}}
                                <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                    @if($task->end_date)
                                        <div class="font-mono font-bold text-xs {{ $isOverdue ? 'text-rose-600' : ($isDueToday ? 'text-amber-600' : 'text-slate-700') }}">
                                            {{ $task->end_date->format('d M') }}
                                        </div>
                                        @if($isOverdue)
                                            <div class="text-[10px] font-extrabold text-rose-600 mt-0.5">{{ abs($daysLeft) }}d late</div>
                                        @elseif($isDueToday)
                                            <div class="text-[10px] font-extrabold text-amber-600 mt-0.5">Today</div>
                                        @elseif($daysLeft !== null && $daysLeft <= 3)
                                            <div class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $daysLeft }}d left</div>
                                        @else
                                            <div class="text-[10px] font-medium text-emerald-600 mt-0.5">{{ $daysLeft }}d left</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 font-light">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700">No tasks found</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Try adjusting your search or active filters</p>
                                        </div>
                                        <button wire:click="clearFilters" type="button" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">
                                            Clear all filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            @if($tasks->total() > 0)
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500 font-medium">
                            Showing <strong class="text-slate-900 font-bold">{{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}</strong>
                            of <strong class="text-slate-900 font-bold">{{ $tasks->total() }}</strong> tasks
                        </span>
                        <select wire:model.live="perPage" class="at-filter-select px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-700 focus:outline-none cursor-pointer">
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>
                    </div>
                    <div>{{ $tasks->links() }}</div>
                </div>
            @endif
        </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MODE 2: STUCK & BLOCKED TASKS RADAR VIEW
         ═══════════════════════════════════════════════════════════════ --}}
    @elseif($viewMode === 'stuck')
        <div class="space-y-4">
            <!-- 1. 4 KPI Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
                <!-- 1. Total Stuck -->
                <button type="button" wire:click="setStuckTypeFilter('all')"
                        class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'all' ? 'ring-2 ring-slate-800 border-slate-800 bg-slate-50/50 shadow-sm' : 'shadow-xs hover:border-slate-400' }}">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Total Stuck</span>
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $totalStuckTasksCount }}</div>
                </button>

                <!-- 2. Blocked Tasks -->
                <button type="button" wire:click="setStuckTypeFilter('blocked')"
                        class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'blocked' ? 'ring-2 ring-rose-500 border-rose-500 bg-rose-50/30 shadow-sm' : 'shadow-xs hover:border-rose-400' }}">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Blocked Tasks</span>
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $blockedTasksOnlyCount }}</div>
                </button>

                <!-- 3. On Hold -->
                <button type="button" wire:click="setStuckTypeFilter('on_hold')"
                        class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'on_hold' ? 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/30 shadow-sm' : 'shadow-xs hover:border-amber-400' }}">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">On Hold</span>
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $onHoldTasksCount }}</div>
                </button>

                <!-- 4. Overdue -->
                <button type="button" wire:click="setStuckTypeFilter('overdue')"
                        class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'overdue' ? 'ring-2 ring-rose-600 border-rose-600 bg-rose-50/30 shadow-sm' : 'shadow-xs hover:border-rose-400' }}">
                    <div class="flex items-center justify-between gap-1 mb-2">
                        <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Overdue Deliverables</span>
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $overdueTasksOnlyCount }}</div>
                </button>
            </div>

            <!-- 2. Search & Filter Bar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-3.5 sm:p-4">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 flex-1 flex-wrap">
                        <div class="relative min-w-[240px] flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search task, project, reason, or assignee..."
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all">
                        </div>

                        <select wire:model.live="stuckProjectFilter" class="at-filter-select px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-100 focus:outline-none cursor-pointer max-w-[170px] truncate">
                            <option value="all">All Projects</option>
                            @foreach($stuckProjectsList as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->code }} - {{ $sp->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckAssigneeFilter" class="at-filter-select px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-100 focus:outline-none cursor-pointer max-w-[160px] truncate">
                            <option value="all">All Assignees</option>
                            @foreach($stuckAssigneesList as $sa)
                                <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckPriorityFilter" class="at-filter-select px-3 py-2 bg-slate-50/80 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-100 focus:outline-none cursor-pointer">
                            <option value="all">All Priority</option>
                            <option value="critical">Critical</option>
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
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Stuck &amp; Delayed Deliverables
                        </h3>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/70 font-mono">
                            {{ $stuckTasks->count() }} Items
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider select-none">
                                <th class="py-3.5 pl-6 pr-4">Task / Deliverable</th>
                                <th class="py-3.5 px-4">Project</th>
                                <th class="py-3.5 px-4">Assignee</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Delay</th>
                                <th class="py-3.5 px-4">Reason / Blocker</th>
                                <th class="py-3.5 px-4">Priority</th>
                                <th class="py-3.5 pl-4 pr-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($stuckTasks as $task)
                                @php
                                    $pObj = $task->project;
                                    $assignee = $task->assignedUser;
                                    $isBlocked = $task->status?->value === 'blocked' || ($task->blockers && $task->blockers->where('status', 'open')->count() > 0);
                                    
                                    $today = now()->today();
                                    $daysOverdue = ($task->end_date && $task->end_date->lt($today)) ? (int) $task->end_date->diffInDays($today) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- 1. Task Title & Code -->
                                    <td class="py-3.5 pl-6 pr-4 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            @if($isBlocked)
                                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0" title="Blocked Task"></span>
                                            @endif
                                            <div>
                                                <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="font-bold text-slate-900 hover:text-[#c3122e] no-underline block leading-tight">
                                                    {{ $task->title }}
                                                </a>
                                                <span class="text-[10px] font-mono font-semibold text-slate-400">{{ $task->wbs_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Project -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-bold text-xs text-slate-800 hover:text-[#c3122e] no-underline block">
                                            {{ $pObj->code ?? 'PRJ' }}
                                        </a>
                                        <span class="text-[10.5px] text-slate-400 block truncate max-w-[140px] font-medium">{{ $pObj->name ?? '—' }}</span>
                                    </td>

                                    <!-- 3. Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($assignee)
                                            <div class="flex items-center gap-2">
                                                <span class="w-6 h-6 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">
                                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                </span>
                                                <span class="font-semibold text-slate-800 text-xs">{{ $assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                        @endif
                                    </td>

                                    <!-- 4. Status -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($isBlocked)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-rose-50 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Blocked</span>
                                            </span>
                                        @elseif($task->status?->value === 'on_hold')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span>On Hold</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Overdue</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Delay Days -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap font-mono font-bold text-slate-700">
                                        @if($daysOverdue > 0)
                                            <span class="text-rose-600 font-black">+{{ $daysOverdue }}d</span>
                                        @else
                                            <span class="text-slate-400 font-normal">—</span>
                                        @endif
                                    </td>

                                    <!-- 6. Blocker / Reason -->
                                    <td class="py-3.5 px-4 align-middle">
                                        @if(!empty($task->delay_reason))
                                            <span class="text-[11px] text-slate-600 italic block line-clamp-1 font-medium" title="{{ $task->delay_reason }}">
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
                                            {{ strtolower($task->priority?->value ?? '') === 'critical' ? 'bg-rose-100 text-rose-800' : (strtolower($task->priority?->value ?? '') === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $task->priority?->label() }}
                                        </span>
                                    </td>

                                    <!-- 8. Action -->
                                    <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:border-slate-300 transition-all no-underline" title="Inspect in Project Workspace">
                                            <span>Open</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-14 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                                                ✓
                                            </div>
                                            <span class="text-sm font-bold text-slate-700">No stuck tasks found</span>
                                            <span class="text-xs text-slate-400">All organization deliverables are on track without blockers.</span>
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

</div>
