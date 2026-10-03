<div wire:poll.30s class="space-y-6 max-w-[1600px] mx-auto pb-10">

    <style>
        .dash-grid-3col {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 1024px) {
            .dash-grid-3col {
                grid-template-columns: repeat(12, 1fr);
            }
            .dash-col-5 { grid-column: span 5 / span 5; }
            .dash-col-4 { grid-column: span 4 / span 4; }
            .dash-col-3 { grid-column: span 3 / span 3; }
            .dash-col-7 { grid-column: span 7 / span 7; }
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP GREETING & STATUS HEADER                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $currentUser = auth()->user();
        $userName = $currentUser->name ?? 'User';
        $firstName = explode(' ', $userName)[0];
        $userRole = $currentUser->roles->first()?->name ?? 'Project Manager';
        $subsidiaryName = $currentUser->subsidiary?->name ?? 'George Steuart Group';
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1 pb-1">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $greeting }}, {{ $firstName }} <span class="inline-block hover:scale-110 transition-transform cursor-default select-none">👋</span>
            </h1>
            <div class="flex items-center gap-2 text-xs text-slate-400 mt-1">
                <span class="inline-flex items-center gap-1.5 font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Workspace</span>
                </span>
                <span class="text-slate-300">•</span>
                <span class="font-medium text-slate-500">{{ now()->format('l, F j, Y') }}</span>
                <span class="text-slate-300 hidden sm:inline">•</span>
                <span class="font-medium text-slate-500 hidden sm:inline">{{ $subsidiaryName }}</span>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="flex items-center gap-2.5">
            @if(auth()->user()?->canCreateProject())
                <a href="{{ route('projects.create') }}" 
                   wire:navigate.hover
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer no-underline active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Pending Leadership Invitations (If any) --}}
    @if($pendingInvitations->count() > 0)
        <div class="rounded-2xl border border-rose-200/90 bg-rose-50/40 p-4 shadow-2xs flex items-center justify-between gap-3 flex-wrap transition-all">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-100 text-[#c3122e] flex items-center justify-center text-sm shrink-0">
                    👑
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Project Leadership Assignment Required ({{ $pendingInvitations->count() }})</h3>
                    <p class="text-xs text-slate-500 font-medium">You have been assigned as Project Leader for pending projects.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @foreach($pendingInvitations->take(2) as $pInv)
                    <button wire:click="openReviewModal({{ $pInv->id }})" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] cursor-pointer transition-colors shadow-2xs">
                        Review {{ $pInv->code }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. MINIMALIST KPI METRIC TILES                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- 1. Total Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">TOTAL PROJECTS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $myProjectsCount }}</span>
                <span class="text-[11px] font-semibold text-slate-400">{{ $inProgressProjectsCount }} Active</span>
            </div>
        </div>

        {{-- 2. My Project Roles --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">PROJECT ROLES</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $totalRoleAssignmentsCount }}</span>
                <span class="text-[11px] font-semibold text-slate-400">{{ $leadProjectsCount }} PM Lead</span>
            </div>
        </div>

        {{-- 3. My Tasks --}}
        <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200 no-underline group block">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">MY TASKS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $myAssignedTasksCount }}</span>
                <span class="text-[11px] font-semibold text-slate-400">{{ $completedTodayCount }} Done Today</span>
            </div>
        </a>

        {{-- 4. Overdue --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">OVERDUE</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold {{ $overdueTasksCount > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} tracking-tight font-mono">{{ $overdueTasksCount }}</span>
                <span class="text-[11px] font-semibold {{ $overdueTasksCount > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ $overdueTasksCount > 0 ? 'Action Required' : 'All Clear' }}</span>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. MAIN SECTION (MY PROJECTS, UPCOMING TASKS, ACTIVITIES)  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-3col items-stretch">

        {{-- ── COLUMN 1: MY PROJECTS (5 cols) ── --}}
        <div class="dash-col-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">My Projects</h2>
                    <a href="{{ route('projects.my-leads') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Projects List --}}
                <div class="space-y-2.5">
                    @forelse($myProjects->take(5) as $p)
                        @php
                            $pct = (int) ($p->overall_progress ?? 0);
                            $pStatus = $p->status instanceof \BackedEnum ? $p->status->value : (string) ($p->status ?? 'in_progress');

                            $statLabel = match($pStatus) {
                                'completed'              => 'Completed',
                                'delayed'                => 'Delayed',
                                'at_risk'                => 'At Risk',
                                'planning'               => 'Planning',
                                'on_hold'                => 'On Hold',
                                'draft', 'not_started'   => 'Not Started',
                                default                  => 'In Progress',
                            };
                            $statClass = match($pStatus) {
                                'completed'              => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                'delayed'                => 'bg-rose-50 text-rose-700 border-rose-200/60',
                                'at_risk'                => 'bg-amber-50 text-amber-800 border-amber-200/60',
                                'planning'               => 'bg-sky-50 text-sky-700 border-sky-200/60',
                                'on_hold'                => 'bg-amber-50 text-amber-800 border-amber-200/60',
                                'draft', 'not_started'   => 'bg-slate-100 text-slate-600 border-slate-200',
                                default                  => 'bg-blue-50 text-blue-700 border-blue-200/60',
                            };
                            $barColor = match($pStatus) {
                                'completed'              => 'bg-emerald-500',
                                'delayed'                => 'bg-rose-500',
                                'at_risk'                => 'bg-amber-500',
                                'planning'               => 'bg-sky-500',
                                'on_hold'                => 'bg-amber-500',
                                'draft', 'not_started'   => 'bg-slate-300',
                                default                  => 'bg-blue-500',
                            };
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl border border-slate-100 bg-slate-50/40 hover:bg-slate-50 transition-all">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('projects.show', $p->id) }}" wire:navigate.hover class="text-xs sm:text-sm font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors no-underline">
                                        {{ $p->name }}
                                    </a>
                                    @if(isset($p->user_assigned_role))
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-bold border {{ $p->user_role_badge }} shrink-0">
                                            <span>{{ $p->user_role_icon }}</span>
                                            <span>{{ $p->user_role_short }}</span>
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                    {{ $p->subsidiary?->name ?? 'George Steuart Group' }}
                                </span>
                            </div>

                            <div class="flex flex-col items-end shrink-0 w-24 text-right space-y-1">
                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold border {{ $statClass }} whitespace-nowrap">
                                    {{ $statLabel }}
                                </span>
                                <div class="flex items-center gap-1.5 w-full justify-end">
                                    <div class="w-16 h-1.5 bg-slate-200/60 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ min(100, max(0, $pct)) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-extrabold text-slate-500 font-mono">{{ $pct }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-500">No active projects assigned yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── COLUMN 2: UPCOMING TASKS (4 cols) ── --}}
        <div class="dash-col-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Upcoming Tasks</h2>
                    <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Tasks List --}}
                <div class="space-y-2.5">
                    @forelse($myTasksDueSoonList as $task)
                        @php
                            $isCompleted = ($task->status === \App\Enums\WbsStatus::COMPLETED || $task->status?->value === 'completed');
                            $isToday = $task->end_date && $task->end_date->isToday();
                            $isTmrw = $task->end_date && $task->end_date->isTomorrow();
                            $isPast = $task->end_date && $task->end_date->isPast() && !$isToday;

                            $dueStr = $isToday ? 'Today' : ($isTmrw ? 'Tomorrow' : ($task->end_date ? $task->end_date->format('M j') : 'Soon'));
                            $dueColor = ($isToday || $isPast) ? 'text-rose-600 font-bold' : ($isTmrw ? 'text-amber-600 font-semibold' : 'text-slate-500');

                            $rawPrio = $task->priority instanceof \BackedEnum 
                                ? $task->priority->value 
                                : (is_string($task->priority) ? $task->priority : ($task->priority?->name ?? 'medium'));
                            $prioVal = strtolower((string)$rawPrio);
                            $prioClass = 'bg-amber-50 text-amber-700 border-amber-200/60';

                            if (in_array($prioVal, ['critical', 'high'])) {
                                $prioClass = 'bg-rose-50 text-rose-700 border-rose-200/60';
                                $prio = 'High';
                            } elseif ($prioVal === 'low') {
                                $prioClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                                $prio = 'Low';
                            } else {
                                $prio = 'Medium';
                            }
                        @endphp
                        <div class="flex items-start justify-between gap-2.5 p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                <button type="button" 
                                        wire:click="toggleTaskComplete({{ $task->id }})" 
                                        title="{{ $isCompleted ? 'Mark Incomplete' : 'Mark Completed' }}"
                                        class="w-4.5 h-4.5 rounded-full border-2 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-emerald-500 bg-white text-transparent' }} transition-colors mt-0.5 shrink-0 cursor-pointer flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 {{ $isCompleted ? 'block' : 'hidden group-hover:block text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-800 truncate hover:text-[#c3122e] transition-colors {{ $isCompleted ? 'line-through text-slate-400' : '' }}">
                                        {{ $task->title }}
                                    </h4>
                                    <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                        {{ $task->project?->name ?? 'George Steuart Workspace' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end shrink-0 space-y-1">
                                <span class="text-[10.5px] {{ $dueColor }}">
                                    {{ $dueStr }}
                                </span>
                                <span class="px-1.5 py-0.2 rounded text-[9.5px] font-bold border {{ $prioClass }}">
                                    {{ $prio }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-500">All caught up! No upcoming tasks.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── COLUMN 3: RECENT ACTIVITIES (3 cols) ── --}}
        <div class="dash-col-3 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Recent Activity</h2>
                    @can('view audit logs')
                        <a href="{{ route('audit-logs.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                            <span>View all</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endcan
                </div>

                {{-- Activity List --}}
                <div class="space-y-2.5">
                    @forelse($recentActivities as $act)
                        @php
                            $action = $act->action;
                            $module = $act->module;
                            $newVals = is_array($act->new_values) ? $act->new_values : [];

                            $actionDesc = 'updated item';
                            $targetName = $newVals['name'] ?? $newVals['title'] ?? $newVals['code'] ?? '';
                            $iconBg = 'bg-slate-100 text-slate-600 border border-slate-200/60';

                            if (str_contains($action, 'project')) {
                                $iconBg = 'bg-blue-50 text-blue-600 border border-blue-200/60';
                                $actionDesc = 'updated project';
                            } elseif (str_contains($action, 'wbs') || str_contains($action, 'task') || $module === 'wbs') {
                                $iconBg = 'bg-emerald-50 text-emerald-600 border border-emerald-200/60';
                                $actionDesc = 'updated task';
                            } elseif (str_contains($action, 'approval') || $module === 'approvals') {
                                $iconBg = 'bg-purple-50 text-purple-600 border border-purple-200/60';
                                $actionDesc = 'approval action';
                            } elseif (str_contains($action, 'risk') || str_contains($action, 'blocker') || $module === 'risks') {
                                $iconBg = 'bg-amber-50 text-amber-600 border border-amber-200/60';
                                $actionDesc = 'risk update';
                            }

                            if (empty($targetName)) {
                                $targetName = ucwords(str_replace('_', ' ', $action));
                            }
                        @endphp
                        <div class="flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="w-6 h-6 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5 text-[10px] font-bold">
                                •
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="text-xs text-slate-800 leading-snug">
                                    <span class="font-bold text-slate-900">{{ $act->user?->name ?? 'System' }}</span>
                                    <span class="text-slate-500 font-medium">{{ $actionDesc }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-0.5">
                                    <span class="text-[11px] font-medium text-slate-600 truncate max-w-[120px]" title="{{ $targetName }}">
                                        {{ $targetName }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        {{ $act->created_at ? $act->created_at->diffForHumans(null, true) : 'recent' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-500">No recent activities recorded</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. BOTTOM SECTION (PROJECT RISKS & QUICK ACCESS)           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-3col items-stretch">

        {{-- ── CARD 1: PROJECT RISKS (7 cols) ── --}}
        <div class="dash-col-7 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Project Risks</h2>
                        @if($openRisks->count() > 0)
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                {{ $openRisks->count() }} Open
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('risks.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Risks List --}}
                <div class="space-y-2.5">
                    @forelse($openRisks->sortByDesc('risk_score')->take(4) as $risk)
                        @php
                            $score = (int) $risk->risk_score;
                            $level = $score >= 9 ? 'Critical' : ($score >= 6 ? 'High' : ($score >= 3 ? 'Medium' : 'Low'));
                            $levelBadge = match($level) {
                                'Critical' => 'bg-rose-50 text-rose-700 border-rose-200/80',
                                'High'     => 'bg-orange-50 text-orange-700 border-orange-200/80',
                                'Medium'   => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                'Low'      => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                            };
                        @endphp
                        <div class="p-3 rounded-xl bg-slate-50/50 border border-slate-100 hover:border-slate-200 transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">
                                            {{ $risk->title }}
                                        </h4>
                                        @if($risk->category)
                                            <span class="text-[9.5px] font-bold uppercase tracking-wider px-1.5 py-0.2 rounded bg-slate-200/60 text-slate-600">
                                                {{ $risk->category }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-[11px] text-slate-400 flex items-center gap-2 flex-wrap font-medium">
                                        @if($risk->project)
                                            <a href="{{ route('projects.show', $risk->project_id) }}" wire:navigate.hover class="font-semibold text-slate-600 hover:text-[#c3122e] transition-colors">
                                                {{ $risk->project->name }}
                                            </a>
                                        @endif
                                        @if($risk->owner)
                                            <span class="text-slate-300">•</span>
                                            <span>Owner: <strong class="font-semibold text-slate-600">{{ $risk->owner->name }}</strong></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md border {{ $levelBadge }}">
                                        {{ $level }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 font-medium">
                            No active risks logged.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ── CARD 2: QUICK ACCESS (5 cols) ── --}}
        <div class="dash-col-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Quick Access</h2>
                    <span class="text-[11px] font-medium text-slate-400">Shortcuts</span>
                </div>

                {{-- Action Tiles --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    
                    @if(auth()->user()?->canCreateProject())
                        <a href="{{ route('projects.create') }}" 
                           wire:navigate.hover
                           class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-slate-100 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-bold text-sm mb-2 shadow-xs group-hover:bg-[#c3122e] transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 leading-tight">Create Project</span>
                        </a>
                    @else
                        <a href="{{ route('daily-updates.index') }}" 
                           wire:navigate.hover
                           class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-slate-100 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center mb-2 shadow-xs group-hover:bg-[#c3122e] transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 leading-tight">Daily Update</span>
                        </a>
                    @endif

                    <a href="{{ route('my-tasks.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-slate-100 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center font-bold text-sm mb-2 shadow-2xs group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">My Tasks</span>
                    </a>

                    <a href="{{ route('calendar.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-slate-100 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center mb-2 shadow-2xs group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Calendar</span>
                    </a>

                    <a href="{{ route('risks.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-slate-100 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center mb-2 shadow-2xs group-hover:bg-slate-900 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Risks Hub</span>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 6. LIVEWIRE MODALS                                         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}

    {{-- A. Review Project Assignment Modal --}}
    @if($showReviewModal && $reviewProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm overflow-y-auto">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200/90 w-full max-w-2xl p-6 sm:p-7 space-y-4 my-8 max-h-[90vh] flex flex-col">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                            ⭐
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 truncate tracking-tight">
                                Project Leadership Sign-Off
                            </h3>
                            <span class="text-xs text-slate-400 font-medium block mt-0.5">
                                {{ $reviewProject->name }} ({{ $reviewProject->code }})
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors text-lg font-bold cursor-pointer shrink-0" title="Close">
                        &times;
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                    <div class="p-4 rounded-2xl bg-slate-50/60 border border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-medium">Project Name:</span>
                            <span class="font-bold text-slate-900">{{ $reviewProject->name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-medium">Requested By:</span>
                            <span class="font-bold text-slate-900">{{ $reviewApproval->requester->name ?? ($reviewProject->creator->name ?? 'PMO Admin') }}</span>
                        </div>
                    </div>

                    @if($showRejectModal)
                        <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-200 space-y-2.5">
                            <label class="text-xs font-bold text-rose-800 block">
                                Reason for Declining Assignment: <span class="text-rose-600">*</span>
                            </label>
                            <textarea wire:model="rejectionReasonInput" class="w-full text-xs p-3 rounded-xl border border-rose-300 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/30" rows="3" placeholder="Please state reasons preventing you from accepting leadership..."></textarea>
                            @error('rejectionReasonInput') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            <div class="flex justify-end gap-2 pt-1">
                                <button wire:click="$set('showRejectModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 bg-white border border-slate-200 cursor-pointer">
                                    Back
                                </button>
                                <button wire:click="submitRejection" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 cursor-pointer shadow-xs">
                                    Confirm Decline
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100 flex-shrink-0 gap-3">
                    <div>
                        @if(!$showRejectModal)
                            <button
                                wire:click="openRejectForm({{ $reviewProject->id }})"
                                type="button"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 transition-all border border-rose-200/60 cursor-pointer"
                            >
                                Decline Assignment
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            wire:click="closeReviewModal"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer"
                        >
                            Cancel
                        </button>
                        @if(!$showRejectModal)
                            <button
                                wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)"
                                type="button"
                                class="px-5 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a80f27] transition-all shadow-xs flex items-center gap-2 cursor-pointer active:scale-98"
                            >
                                <span>Accept &amp; Open Workspace &rarr;</span>
                            </button>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endif

    {{-- B. Resolve Blocker Modal --}}
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Resolve Task Blocker</h3>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-700 block mb-1">Resolution Summary:</label>
                    <textarea wire:model="blockerResolutionInput" rows="3" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500" placeholder="Describe how this blocker was resolved..."></textarea>
                    @error('blockerResolutionInput') <span class="text-xs text-rose-600 block mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button wire:click="resolveBlocker" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs">Mark Resolved</button>
                </div>
            </div>
        </div>
    @endif

    {{-- C. Add Risk Modal --}}
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Log Project Risk</h3>
                    <button wire:click="$set('showAddRiskModal', false)" class="text-slate-400 hover:text-slate-600 cursor-pointer">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Project</label>
                        <select wire:model="riskProjectId" class="w-full p-2.5 rounded-xl border border-slate-200 bg-white">
                            @foreach($assignedProjects as $ap)
                                <option value="{{ $ap->id }}">{{ $ap->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Risk Title</label>
                        <input wire:model="riskTitle" type="text" class="w-full p-2.5 rounded-xl border border-slate-200" placeholder="e.g. Delays in API approval">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="font-semibold text-slate-700 block mb-1">Probability</label>
                            <select wire:model="riskProbability" class="w-full p-2.5 rounded-xl border border-slate-200 bg-white">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-semibold text-slate-700 block mb-1">Impact</label>
                            <select wire:model="riskImpact" class="w-full p-2.5 rounded-xl border border-slate-200 bg-white">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Mitigation Plan</label>
                        <textarea wire:model="riskMitigation" rows="2" class="w-full p-2.5 rounded-xl border border-slate-200" placeholder="Planned actions to minimize risk..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showAddRiskModal', false)" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button wire:click="createRisk" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-xs">Log Risk</button>
                </div>
            </div>
        </div>
    @endif

</div>
