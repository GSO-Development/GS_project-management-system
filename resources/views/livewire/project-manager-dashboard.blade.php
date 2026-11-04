<div wire:poll.30s data-ui-page="dashboard-manager" class="ui-page ui-page-dashboard manager-dashboard space-y-5 max-w-[1600px] mx-auto pb-10">

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
        .manager-head { border-bottom: 1px solid #e6ebf0; padding-bottom: 16px; }
        .manager-head h1 { font-size: 27px !important; font-weight: 700 !important; letter-spacing: -.035em; }
        .manager-head h1 span { display: none; }
        .manager-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .manager-kpis .manager-kpi-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 18px 20px;
            border: 1px solid #e2e7ed;
            border-radius: 12px;
            background: #fff;
            color: #101828;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(16, 24, 40, .02);
        }
        .manager-kpi-card .manager-kpi-label {
            color: #667085;
            font-size: 11px;
            font-weight: 600;
            line-height: 16px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .manager-kpi-card .manager-kpi-value {
            display: block;
            margin-top: 8px;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-size: 30px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            line-height: 36px;
            letter-spacing: -.04em;
        }
        .manager-kpi-card .manager-kpi-footer {
            display: flex;
            align-items: center;
            min-height: 29px;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid #eef0f3;
            color: #667085;
            font-size: 11px;
            font-weight: 500;
            line-height: 18px;
        }
        .manager-kpi-footer strong {
            margin-right: 4px;
            color: #344054;
            font-weight: 600;
        }
        .manager-kpis .manager-kpi-link {
            transition: border-color 150ms ease, background-color 150ms ease;
        }
        .manager-kpis .manager-kpi-link:hover {
            border-color: #b8c2cf;
            background: #fcfcfd;
        }
        .manager-kpis .manager-kpi-link:focus-visible {
            outline: 2px solid #c3122e;
            outline-offset: 3px;
        }
        .manager-kpis .manager-kpi-card.is-overdue { border-color: #edcbd2; }
        .manager-kpi-card.is-overdue .manager-kpi-value { color: #c3122e; }
        .manager-kpi-card .manager-kpi-status {
            padding: 1px 7px;
            border-radius: 4px;
            background: #f2f4f7;
            color: #475467;
            font-size: 10px;
            font-weight: 600;
            line-height: 16px;
        }
        .manager-kpi-card.is-overdue .manager-kpi-status {
            background: #fff1f3;
            color: #b1102c;
        }
        .manager-panels { gap: 16px; }
        .manager-panels > div { border-color: #e1e7ed !important; border-radius: 16px !important; box-shadow: 0 16px 38px -34px rgba(15,23,42,.36) !important; padding: 18px !important; }
        .manager-panels .space-y-2\.5 > div { background: #fbfcfd !important; border-color: #e9edf2 !important; border-radius: 10px !important; }
        .manager-panels h2 { font-weight:700 !important; letter-spacing:-.025em !important; }
        @media (min-width: 1024px) {
            .manager-primary-panels { grid-template-columns: repeat(3, minmax(0, 1fr)) !important; }
            .manager-primary-panels > div { grid-column: span 1 / span 1 !important; }
        }
        @media (max-width:1023px) { .manager-kpis { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:639px) { .manager-head h1{font-size:23px!important}.manager-kpis{grid-template-columns:1fr} }

        /* Project leadership review dialog */
        .leadership-review-dialog {
            width: min(980px, calc(100vw - 32px)) !important;
            max-width: 980px !important;
            max-height: calc(100dvh - 32px) !important;
            margin: auto !important;
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            overflow: visible !important;
        }
        .leadership-review-shell {
            width: 100%;
            height: min(720px, calc(100dvh - 32px));
            max-height: calc(100dvh - 32px);
            overflow: hidden;
            border: 1px solid rgba(226,232,240,.95);
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 36px 90px -30px rgba(15,23,42,.72), 0 14px 32px -22px rgba(15,23,42,.38);
        }
        .leadership-review-header {
            min-height: 70px;
            padding: 13px 20px;
            border-bottom: 1px solid #e5eaf0;
            background: #fff;
        }
        .leadership-review-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.75);
        }
        .leadership-review-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 16px 20px;
            background: #f6f8fa;
            scrollbar-gutter: stable;
        }
        .leadership-summary-card,
        .leadership-section-card {
            border: 1px solid #e1e7ee !important;
            border-radius: 12px !important;
            background: #fff !important;
            box-shadow: 0 1px 2px rgba(15,23,42,.025);
        }
        .leadership-metric-card {
            min-height: 68px;
            border-radius: 11px !important;
            border-color: #e1e7ee !important;
            box-shadow: 0 1px 2px rgba(15,23,42,.025) !important;
        }
        .leadership-review-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(300px, .8fr);
            align-items: start;
            gap: 14px;
        }
        .leadership-wbs-list {
            max-height: 220px;
            padding: 4px !important;
            background: #f8fafc !important;
        }
        .leadership-wbs-list > div {
            border-radius: 8px !important;
            box-shadow: none !important;
        }
        .leadership-roster-grid {
            grid-template-columns: minmax(0, 1fr) !important;
        }
        .leadership-roster-grid > div {
            border-width: 1px !important;
            border-color: #e2e8f0 !important;
            border-radius: 10px !important;
            box-shadow: none !important;
        }
        .leadership-review-footer {
            flex: 0 0 auto;
            padding: 12px 20px;
            border-top: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 -14px 28px -30px rgba(15,23,42,.5);
        }
        .leadership-review-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;
            width: 100%;
        }
        .leadership-review-action {
            min-height: 40px;
            border-radius: 10px !important;
        }
        @media (max-width: 899px) {
            .leadership-review-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 639px) {
            .leadership-review-dialog {
                width: calc(100vw - 16px) !important;
                max-height: calc(100dvh - 16px) !important;
            }
            .leadership-review-shell {
                height: calc(100dvh - 16px);
                max-height: calc(100dvh - 16px);
                border-radius: 16px;
            }
            .leadership-review-header { min-height: 68px; padding: 12px 14px; }
            .leadership-review-body { padding: 12px; }
            .leadership-review-footer { padding: 12px; }
            .leadership-review-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .leadership-review-actions .leadership-accept-action { grid-column: 1 / -1; grid-row: 1; }
            .leadership-review-action { width: 100%; padding-inline: 12px !important; }
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

    <div class="manager-head flex flex-col sm:flex-row sm:items-end justify-between gap-4 pt-1">
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
    <div class="manager-kpis" role="group" aria-label="Dashboard metrics">
        <div class="manager-kpi-card">
            <span class="manager-kpi-label">Total projects</span>
            <span class="manager-kpi-value">{{ $myProjectsCount }}</span>
            <div class="manager-kpi-footer">
                <strong>{{ $inProgressProjectsCount }}</strong> Active
            </div>
        </div>

        <div class="manager-kpi-card">
            <span class="manager-kpi-label">Project roles</span>
            <span class="manager-kpi-value">{{ $totalRoleAssignmentsCount }}</span>
            <div class="manager-kpi-footer">
                <strong>{{ $leadProjectsCount }}</strong> PM Lead
            </div>
        </div>

        <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="manager-kpi-card manager-kpi-link" aria-label="View my tasks">
            <span class="manager-kpi-label">My tasks</span>
            <span class="manager-kpi-value">{{ $myAssignedTasksCount }}</span>
            <div class="manager-kpi-footer">
                <strong>{{ $completedTodayCount }}</strong> Done today
            </div>
        </a>

        <div class="manager-kpi-card {{ $overdueTasksCount > 0 ? 'is-overdue' : '' }}">
            <span class="manager-kpi-label">Overdue</span>
            <span class="manager-kpi-value">{{ $overdueTasksCount }}</span>
            <div class="manager-kpi-footer">
                <span class="manager-kpi-status">{{ $overdueTasksCount > 0 ? 'Action required' : 'All clear' }}</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. MAIN SECTION (MY PROJECTS, UPCOMING TASKS, ACTIVITIES)  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-3col manager-panels manager-primary-panels items-stretch">

        {{-- ── COLUMN 1: MY PROJECTS (4 cols / 1 of 3) ── --}}
        <div class="dash-col-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
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
                        @endphp
                        <div class="flex items-center justify-between gap-2.5 p-2 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors">
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

                            <div class="flex items-center shrink-0 self-center">
                                <span class="text-[11px] {{ $dueColor }}">
                                    {{ $dueStr }}
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

        {{-- ── COLUMN 3: TODAY'S SCHEDULE (TASKS & MEETINGS) (4 cols / 1 of 3) ── --}}
        <div class="dash-col-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Today's Schedule</h2>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">{{ now()->format('l, M j') }}</p>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-rose-50 text-[#c3122e] border border-rose-200/60" title="Tasks & Meetings for Today">
                            {{ $todayScheduleItems->count() }} Items
                        </span>
                    </div>
                </div>

                {{-- Schedule List (Day-by-Day updating) --}}
                <div class="space-y-2.5">
                    @forelse($todayScheduleItems->take(5) as $item)
                        <div class="p-2.5 rounded-xl bg-slate-50/60 hover:bg-rose-50/20 border border-slate-100 hover:border-rose-100 transition-all space-y-1.5 group">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2 min-w-0">
                                    {{-- Type Icon --}}
                                    <div class="w-7 h-7 rounded-lg {{ $item['type'] === 'meeting' ? 'bg-purple-100 text-purple-700 border border-purple-200/60' : 'bg-rose-100 text-[#c3122e] border border-rose-200/60' }} flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold shadow-2xs">
                                        @if($item['type'] === 'meeting')
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <a href="{{ $item['link'] }}" wire:navigate.hover class="text-xs font-bold text-slate-900 hover:text-[#c3122e] transition-colors truncate no-underline block leading-snug" title="{{ $item['title'] }}">
                                            {{ $item['title'] }}
                                        </a>
                                        <div class="flex items-center gap-1.5 text-[10.5px] font-medium text-slate-400 mt-0.5">
                                            <span class="truncate max-w-[130px]">{{ $item['project'] }}</span>
                                            @if($item['type'] === 'task' && $item['progress'] !== null)
                                                <span class="text-slate-300">•</span>
                                                <span class="font-mono font-bold text-slate-600">{{ $item['progress'] }}%</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Time / Status Badge --}}
                                <div class="shrink-0 flex flex-col items-end gap-1">
                                    <span class="text-[9.5px] px-2 py-0.5 rounded-md border {{ $item['badge_bg'] }}">
                                        {{ $item['time_label'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                ✓
                            </div>
                            <p class="text-xs font-bold text-slate-800">No tasks or meetings today</p>
                            <p class="text-[11px] text-slate-400 font-medium">Your schedule for {{ now()->format('l') }} is completely clear.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer links --}}
            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('calendar.index') }}" wire:navigate.hover class="text-[11px] font-bold text-slate-600 hover:text-[#c3122e] flex items-center gap-1.5 transition-colors no-underline">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Open Calendar</span>
                </a>
                <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-[11px] font-bold text-slate-600 hover:text-[#c3122e] flex items-center gap-1.5 transition-colors no-underline">
                    <span>Task Board</span>
                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. BOTTOM SECTION (PROJECT RISKS & QUICK ACCESS)           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="dash-grid-3col manager-panels items-stretch">

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
                                            <span>{{ ($risk->is_task_risk ?? false) ? 'Assigned to' : 'Owner' }}: <strong class="font-semibold text-slate-600">{{ $risk->owner->name }}</strong></span>
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
                           class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-rose-50/30 hover:border-rose-200 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-[#c3122e] text-white flex items-center justify-center font-bold text-sm mb-2 shadow-2xs group-hover:bg-[#a80f27] transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#c3122e] transition-colors leading-tight">Create Project</span>
                        </a>
                    @else
                        <a href="{{ route('daily-updates.index') }}" 
                           wire:navigate.hover
                           class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-rose-50/30 hover:border-rose-200 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                            <div class="w-8 h-8 rounded-lg bg-[#c3122e] text-white flex items-center justify-center mb-2 shadow-2xs group-hover:bg-[#a80f27] transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#c3122e] transition-colors leading-tight">Latest Updates</span>
                        </a>
                    @endif

                    <a href="{{ route('my-tasks.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-rose-50/30 hover:border-rose-200 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center font-bold text-sm mb-2 shadow-2xs group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-[#c3122e] transition-colors leading-tight">My Tasks</span>
                    </a>

                    <a href="{{ route('calendar.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-rose-50/30 hover:border-rose-200 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center mb-2 shadow-2xs group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-[#c3122e] transition-colors leading-tight">Calendar</span>
                    </a>

                    <a href="{{ route('risks.index') }}" 
                       wire:navigate.hover
                       class="rounded-xl border border-slate-200/70 bg-slate-50/50 hover:bg-rose-50/30 hover:border-rose-200 p-3 flex flex-col items-center justify-center text-center group transition-all duration-150 no-underline shadow-2xs">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center justify-center mb-2 shadow-2xs group-hover:bg-[#c3122e] group-hover:text-white group-hover:border-[#c3122e] transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-[#c3122e] transition-colors leading-tight">Risks Hub</span>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 6. LIVEWIRE MODALS                                         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}

    {{-- A. Review Project Assignment Modal --}}
    @if($showReviewModal && $reviewProject && !$showRejectModal)
        @teleport('body')
        @php
            $wbsItems = $reviewWbsItems ?? ($reviewProject->wbsItems ?? collect());
            $phases = $reviewPhases ?? $wbsItems->filter(fn($i) => ($i->item_type?->value === 'phase' || !$i->parent_id));
            $milestonesCount = $reviewMilestonesCount ?? $wbsItems->where('is_milestone', true)->count();
            $tasksCount = $wbsItems->filter(fn($i) => ($i->item_type?->value === 'task' || $i->parent_id))->count();
            
            $sponsors = $reviewSponsors ?? ($reviewProject->members ? $reviewProject->members->where('pivot.role', 'sponsor') : collect());
            $owners = $reviewOwners ?? ($reviewProject->members ? $reviewProject->members->where('pivot.role', 'owner') : collect());
            $committee = $reviewCommittee ?? ($reviewProject->members ? $reviewProject->members->where('pivot.role', 'steering_committee') : collect());
            $members = $reviewMembers ?? ($reviewProject->members ? $reviewProject->members->where('pivot.role', 'member') : collect());
            $customRoleMembers = $reviewProject->members ? $reviewProject->members->whereNotIn('pivot.role', ['sponsor', 'owner', 'steering_committee', 'member', 'lead']) : collect();
            
            $durationDays = $reviewDurationDays ?? (($reviewProject->start_date && $reviewProject->deadline) ? max(1, (int)$reviewProject->start_date->diffInDays($reviewProject->deadline)) : null);
            $totalTeam = $reviewTotalTeam ?? (1 + $sponsors->count() + $owners->count() + $committee->count() + $members->count() + $customRoleMembers->count());
            $requesterName = $reviewApproval->requester->name ?? ($reviewProject->creator->name ?? 'Super Administrator (PMO Admin)');
        @endphp

        <dialog
            x-data
            x-init="$nextTick(() => { if (!$el.open) $el.showModal() })"
            x-on:cancel.prevent="$wire.closeReviewModal()"
            class="leadership-review-dialog backdrop:bg-slate-950/60 backdrop:backdrop-blur-sm"
            style="position:fixed !important;inset:0 !important;"
            aria-labelledby="leadership-signoff-title"
            wire:click.self="closeReviewModal"
        >
            <div class="leadership-review-shell relative flex flex-col animate-in fade-in zoom-in-95 duration-200">
                
                {{-- Header --}}
                <div class="leadership-review-header flex flex-shrink-0 items-center justify-between">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="leadership-review-icon shrink-0 flex items-center justify-center border border-rose-200 bg-rose-50 text-[#c3122e]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.142-3.358 7.5-7.5 8.25-4.142-.75-7.5-4.108-7.5-8.25V6.75l7.5-3Z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 id="leadership-signoff-title" class="text-base sm:text-lg font-black text-slate-900 truncate tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                    Project Leadership Sign-Off
                                </h3>
                                <span class="px-2 py-0.5 rounded-md text-[10.5px] font-mono font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $reviewProject->code }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-500 font-medium block mt-0.5 truncate">
                                {{ $reviewProject->subsidiary->name ?? 'George Steuart Group' }} · PMO Assigned Leadership
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" class="w-8 h-8 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors text-xl font-bold flex items-center justify-center cursor-pointer shrink-0" title="Close">
                        &times;
                    </button>
                </div>

                {{-- Modal Body Container --}}
                <div class="leadership-review-body space-y-4 scrollbar-thin overscroll-contain">
                    @if(!$showRejectModal)
                        {{-- 1. Project Title, Requester & Scope Card --}}
                        <div class="leadership-summary-card space-y-2.5 p-4">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Project Initiation Details</span>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                        {{ ucfirst($reviewProject->priority?->value ?? 'medium') }} Priority
                                    </span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200/70 text-slate-700">
                                        {{ $reviewProject->isPendingPmAcceptance() ? 'Planning' : ucfirst(str_replace('_', ' ', $reviewProject->status?->value ?? 'planning')) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-base font-extrabold text-slate-900 leading-snug" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                    {{ $reviewProject->name }}
                                </h4>
                                <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                                    <span>Requested By:</span>
                                    <strong class="text-slate-900 font-bold">{{ $requesterName }}</strong>
                                </div>
                            </div>

                            @if($reviewProject->description)
                                <div class="pt-2 border-t border-slate-200/70">
                                    <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider block mb-0.5">Scope &amp; Objectives</span>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium bg-white p-3 rounded-xl border border-slate-200/60">
                                        {{ $reviewProject->description }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- 2. Timeline & Schedule Parameters Grid (4 Cards) --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                            <div class="leadership-metric-card p-3 bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5"><span class="text-slate-500">01</span> Start Date</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>
                            <div class="leadership-metric-card p-3 bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5"><span class="text-slate-500">02</span> Target Deadline</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'TBD' }}
                                </span>
                            </div>
                            <div class="leadership-metric-card p-3 bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5"><span class="text-slate-500">03</span> Est. Duration</span>
                                <span class="font-bold text-slate-900 block mt-1">
                                    {{ $durationDays ? "{$durationDays} Days" : 'Flexible' }}
                                </span>
                            </div>
                            <div class="leadership-metric-card p-3 bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5"><span class="text-slate-500">04</span> Est. Budget</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">
                                    {{ $reviewProject->estimated_budget > 0 ? 'Rs. ' . number_format($reviewProject->estimated_budget, 0) : 'Not Specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="leadership-review-grid">
                        {{-- 3. WBS Breakdown & Execution Blueprint Section --}}
                        <div class="leadership-section-card p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5.25H6.75A2.25 2.25 0 0 0 4.5 7.5v11.25A2.25 2.25 0 0 0 6.75 21h10.5a2.25 2.25 0 0 0 2.25-2.25V7.5a2.25 2.25 0 0 0-2.25-2.25H15M9 5.25A2.25 2.25 0 0 1 11.25 3h1.5A2.25 2.25 0 0 1 15 5.25M9 5.25A2.25 2.25 0 0 0 11.25 7.5h1.5A2.25 2.25 0 0 0 15 5.25" /></svg>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">
                                            WBS Structure &amp; Execution Blueprint
                                        </h5>
                                        <span class="text-[10px] text-slate-500 font-medium">
                                            Blueprint: <strong class="text-indigo-900 font-bold">{{ $reviewProject->template?->name ?? ($reviewProject->wbs_breakdown_type === 'template' ? 'Standard Template Blueprint' : 'Custom Agile WBS') }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $wbsItems->count() }} Total Items
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $phases->count() }} Phases
                                    </span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                            🎯 {{ $milestonesCount }} Milestones
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- WBS Phases & Tasks Preview List --}}
                            @if($wbsItems->count() > 0)
                                <div class="leadership-wbs-list overflow-y-auto space-y-1.5 pr-1 scrollbar-thin rounded-xl bg-slate-50/60 p-2.5 border border-slate-200/70">
                                    @foreach($wbsItems as $item)
                                        @php
                                            $isPhase = ($item->item_type?->value === 'phase' || !$item->parent_id);
                                        @endphp
                                        <div class="flex items-center justify-between gap-2 p-2 rounded-lg text-xs {{ $isPhase ? 'bg-slate-50 font-black text-slate-900 border border-slate-200/60' : 'pl-6 text-slate-700 font-medium' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[10px] font-mono {{ $isPhase ? 'text-[#c3122e] font-black' : 'text-slate-400' }}">
                                                    {{ $isPhase ? '📁 Phase:' : '↳ Task:' }}
                                                </span>
                                                <span class="truncate">{{ $item->title ?? ($item->name ?? 'Deliverable') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0 text-[10px]">
                                                @if($item->duration)
                                                    <span class="text-slate-400 font-mono">{{ $item->duration }}d</span>
                                                @endif
                                                @if(!empty($item->is_milestone))
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-purple-50 text-purple-800 border border-purple-200">Milestone</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3.5 bg-white rounded-xl border border-slate-200/60 text-center text-xs font-semibold text-slate-500">
                                    Custom Agile Workspace initialized. You can build and structure your WBS upon accepting leadership.
                                </div>
                            @endif
                        </div>

                        {{-- 4. Governance & Team Structure Section --}}
                        <div class="leadership-section-card p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-rose-50 text-[#c3122e] flex items-center justify-center">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.203-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                    </div>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">Governance &amp; Assigned Team Roster</h5>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black font-mono bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $totalTeam }} Assigned
                                </span>
                            </div>

                            <div class="leadership-roster-grid grid grid-cols-1 gap-2 text-xs">
                                {{-- Project Manager Card (Self) --}}
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border-2 border-[#c3122e]/40 shadow-2xs">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-black text-[11px] flex-shrink-0 shadow-2xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($reviewProject->projectManager->name ?? auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-black text-slate-900 block truncate">{{ $reviewProject->projectManager->name ?? auth()->user()->name }}</span>
                                        <span class="text-[9.5px] text-[#c3122e] font-black uppercase">⭐ Designated Project Leader (You)</span>
                                    </div>
                                </div>

                                {{-- Sponsors --}}
                                @foreach($sponsors as $sp)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-amber-200 shadow-2xs">
                                        <div class="w-8 h-8 rounded-xl bg-amber-500 text-white font-black text-[11px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($sp->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $sp->name }}</span>
                                            <span class="text-[9.5px] text-amber-800 font-bold">💼 Project Sponsor</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Owners --}}
                                @foreach($owners as $ow)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-emerald-200 shadow-2xs">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-[11px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($ow->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $ow->name }}</span>
                                            <span class="text-[9.5px] text-emerald-800 font-bold">👑 Project Owner</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Steering Committee --}}
                                @foreach($committee as $cm)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-violet-200 shadow-2xs">
                                        <div class="w-8 h-8 rounded-xl bg-violet-600 text-white font-black text-[11px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($cm->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $cm->name }}</span>
                                            <span class="text-[9.5px] text-violet-800 font-bold">🏛️ Steering Committee</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Core Members --}}
                                @foreach($members as $mb)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-blue-200 shadow-2xs">
                                        <div class="w-8 h-8 rounded-xl bg-blue-500 text-white font-black text-[11px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($mb->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $mb->name }}</span>
                                            <span class="text-[9.5px] text-blue-700 font-bold">🤝 Team Member</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Custom Roles --}}
                                @foreach($customRoleMembers as $cr)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                        <div class="w-8 h-8 rounded-xl bg-slate-700 text-white font-black text-[11px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($cr->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $cr->name }}</span>
                                            <span class="text-[9.5px] text-slate-600 font-bold capitalize">{{ str_replace('_', ' ', $cr->pivot->role ?? 'Role') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>

                        {{-- Previous Rejection Note (if any) --}}
                        @if($reviewProject->isPmRejected())
                            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-wider block text-rose-700">Previously Reported Reason:</span>
                                <p class="font-medium italic">"{{ $reviewProject->pm_rejection_reason }}"</p>
                                <span class="text-[9.5px] text-rose-500 font-mono block">Declined on {{ $reviewProject->pm_rejected_at?->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif
                    @else
                        {{-- Decline Reason Form --}}
                        <div class="p-5 sm:p-6 rounded-2xl bg-white border border-slate-200 space-y-5 shadow-2xs">
                            <div>
                                <h4 class="text-sm font-black text-slate-900">Decline leadership assignment</h4>
                                <p class="mt-1.5 text-xs leading-5 text-slate-500">This decision will be sent to the PMO team for review and reassignment. Your reason will be recorded in the project history.</p>
                            </div>
                            <label class="text-xs font-bold text-slate-700 block">
                                Reason for Declining Assignment: <span class="text-rose-600">*</span>
                            </label>
                            <textarea wire:model="rejectionReasonInput" class="w-full min-h-32 resize-y text-sm leading-6 p-3.5 rounded-xl border border-slate-300 bg-white shadow-inner focus:outline-none focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100" rows="4" maxlength="1000" placeholder="Explain why you cannot accept this project leadership assignment..."></textarea>
                            @error('rejectionReasonInput') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 pt-2 border-t border-slate-100">
                                <button wire:click="$set('showRejectModal', false)" type="button" class="min-h-11 px-5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 bg-white border border-slate-300 cursor-pointer transition-colors">
                                    Back
                                </button>
                                <button wire:click="submitRejection" wire:loading.attr="disabled" type="button" class="min-h-11 px-5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] cursor-pointer shadow-md shadow-rose-900/15 disabled:opacity-60 transition-all active:scale-[.98]">
                                    <span wire:loading.remove wire:target="submitRejection">Confirm Decline</span>
                                    <span wire:loading wire:target="submitRejection">Submitting...</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Premium Action Footer --}}
                @unless($showRejectModal)
                <div class="leadership-review-footer">
                    <div class="leadership-review-actions">
                        <button wire:click="closeReviewModal" type="button" class="leadership-review-action transition-all cursor-pointer active:scale-[.98]"
                            style="height:42px;padding:0 19px;border-radius:11px;border:1px solid #cbd5e1;background:#fff;color:#475569;font-size:13px;font-weight:700;box-shadow:0 1px 2px rgba(15,23,42,.04);">
                            Cancel
                        </button>

                        @if(!$showRejectModal)
                            <button wire:click="openRejectForm({{ $reviewProject->id }})" type="button" class="leadership-review-action transition-all cursor-pointer active:scale-[.98]"
                                style="height:42px;padding:0 19px;border-radius:11px;border:1px solid #fecdd3;background:#fff5f6;color:#be123c;font-size:13px;font-weight:750;display:inline-flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 1px 2px rgba(15,23,42,.03);">
                                <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Decline Assignment</span>
                            </button>

                            <button wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)" wire:loading.attr="disabled" type="button" class="leadership-review-action leadership-accept-action transition-all cursor-pointer active:scale-[.98] disabled:opacity-60"
                                style="height:42px;padding:0 22px;border-radius:11px;border:1px solid #a80f27;background:#c3122e;color:#fff;font-size:13px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;gap:9px;box-shadow:0 8px 18px -12px rgba(195,18,46,.75);">
                                <svg wire:loading.remove wire:target="acceptProjectAssignment" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <svg wire:loading wire:target="acceptProjectAssignment" class="animate-spin" style="width:16px;height:16px;" viewBox="0 0 24 24" fill="none"><circle opacity=".3" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"/><path fill="currentColor" d="M21 12a9 9 0 00-9-9v3a6 6 0 016 6h3z"/></svg>
                                <span>Accept &amp; Open Workspace</span>
                                <svg wire:loading.remove wire:target="acceptProjectAssignment" style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
                @endunless
            </div>
        </dialog>
        @endteleport
    @endif

    {{-- A2. Dedicated Leadership Decline Confirmation --}}
    @if($showReviewModal && $reviewProject && $showRejectModal)
        @teleport('body')
        <div
            class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm"
            style="position:fixed !important;inset:0 !important;z-index:99999 !important;width:100vw !important;height:100dvh !important;"
            wire:click.self="$set('showRejectModal', false)"
            role="presentation"
        >
            <section
                class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl"
                style="box-shadow:0 32px 90px -24px rgba(15,23,42,.75);"
                role="dialog"
                aria-modal="true"
                aria-labelledby="decline-leadership-title"
                x-data
                x-on:keydown.escape.window="$wire.set('showRejectModal', false)"
            >
                <header class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3.5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 text-rose-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.7 2.6 17a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h2 id="decline-leadership-title" class="text-base font-black tracking-tight text-slate-900 sm:text-lg">Decline project leadership</h2>
                            <p class="mt-0.5 truncate text-xs font-medium text-slate-500">
                                {{ $reviewProject->name }} <span class="text-slate-300">&middot;</span> {{ $reviewProject->code }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="$set('showRejectModal', false)" type="button" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-xl font-bold text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" aria-label="Close decline dialog">&times;</button>
                </header>

                <div class="space-y-5 px-5 py-5 sm:px-6 sm:py-6">
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3.5">
                        <p class="text-xs font-bold leading-5 text-amber-900">The PMO team will be notified and this decision will be recorded in the project history for reassignment.</p>
                    </div>

                    <div>
                        <label for="leadership-decline-reason" class="mb-2 block text-xs font-extrabold text-slate-700">
                            Reason for declining <span class="text-rose-600">*</span>
                        </label>
                        <textarea
                            id="leadership-decline-reason"
                            wire:model="rejectionReasonInput"
                            rows="5"
                            maxlength="1000"
                            autofocus
                            class="min-h-36 w-full resize-y rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm leading-6 text-slate-800 shadow-inner outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100"
                            placeholder="Explain why you cannot accept this project leadership assignment..."
                        ></textarea>
                        <div class="mt-2 flex items-start justify-between gap-3">
                            <div>
                                @error('rejectionReasonInput') <p class="text-xs font-bold text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <span class="shrink-0 text-[10px] font-medium text-slate-400">Maximum 1,000 characters</span>
                        </div>
                    </div>
                </div>

                <footer class="flex flex-col-reverse gap-2.5 border-t border-slate-200 bg-slate-50/80 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                    <button wire:click="$set('showRejectModal', false)" type="button" class="min-h-11 rounded-xl border border-slate-300 bg-white px-5 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-50">Back to review</button>
                    <button wire:click="submitRejection" wire:loading.attr="disabled" wire:target="submitRejection" type="button" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[#c3122e] px-5 text-xs font-extrabold text-white shadow-md shadow-rose-900/15 transition-all hover:bg-[#a50e26] active:scale-[.98] disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="submitRejection">Confirm decline</span>
                        <span wire:loading wire:target="submitRejection">Submitting...</span>
                    </button>
                </footer>
            </section>
        </div>
        @endteleport
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
