<div wire:poll.15s class="pm-exec-root space-y-6">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap');

        .pm-exec-root {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #0f172a;
        }

        .pm-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Custom Scrollbars */
        .pm-custom-scroll::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }
        .pm-custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .pm-custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        /* Animated Pulsing Dot */
        @keyframes pm-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(1.15); }
        }
        .pm-pulse-live {
            animation: pm-pulse 2s infinite ease-in-out;
        }

        /* Unified KPI Ribbon Strip */
        .pm-kpi-ribbon-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .pm-kpi-ribbon {
            display: grid !important;
            grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            width: 100% !important;
            min-width: 780px !important;
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP GREETING HEADER (SIMPLE, LIGHT, EXECUTIVE)          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-0.5">
        <div>
            @php
                $hour = now()->hour;
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
                $firstName = explode(' ', auth()->user()->name ?? 'Manager')[0];
            @endphp
            <div class="flex items-center gap-2 mb-1 text-xs text-slate-500 font-medium">
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 pm-pulse-live"></span>
                    <span>Workspace</span>
                </span>
                <span class="text-slate-300">&bull;</span>
                <span>{{ now()->format('l, F j, Y') }}</span>
                <span class="text-slate-300 hidden sm:inline">&bull;</span>
                <span class="font-semibold text-slate-600 hidden sm:inline">George Steuart Group</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $greeting }}, {{ $firstName }}
            </h1>
        </div>

        {{-- Quick Actions --}}
        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
            @if(auth()->user()?->canCreateProject())
                <a href="{{ route('projects.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white shadow-2xs hover:shadow-xs hover:opacity-95 active:scale-[0.98] transition-all no-underline shrink-0" 
                   style="background: #c3122e;">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            @endif

            <a href="{{ route('my-tasks.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all no-underline shrink-0 shadow-2xs">
                <span>My Tasks</span>
                <span class="px-1.5 py-0.2 rounded-md bg-slate-100 text-slate-700 font-bold text-[10px] pm-mono">{{ $myTasksCount }}</span>
            </a>

            <a href="{{ route('calendar.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all no-underline shrink-0 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Calendar</span>
            </a>

            <a href="{{ route('risks.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all no-underline shrink-0 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Risks</span>
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 👑 PENDING PROJECT LEADERSHIP ASSIGNMENT (IF ANY)         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($pendingInvitations->count() > 0)
        <div class="rounded-2xl border border-rose-200 bg-white p-4 shadow-2xs space-y-3">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-[#c3122e] flex items-center justify-center text-sm font-bold">👑</span>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900">Project Leadership Assignment Required</h3>
                        <p class="text-[11px] text-slate-500">PMO Administration assigned you as Project Leader.</p>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-[#c3122e] border border-rose-200">
                    {{ $pendingInvitations->count() }} Pending
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                @foreach($pendingInvitations as $proj)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-[10px] font-mono font-bold text-[#c3122e]">{{ $proj->code }}</span>
                            <h4 class="text-xs font-bold text-slate-900 truncate">{{ $proj->name }}</h4>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button wire:click="openReviewModal({{ $proj->id }})" type="button" class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-100 cursor-pointer">
                                Review
                            </button>
                            <button wire:click="acceptProjectAssignment({{ $proj->id }}, true)" type="button" class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] cursor-pointer">
                                Accept
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. UNIFIED METRIC RIBBON (CLEAN, MINIMAL, NO CARD OVERLOAD) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="pm-kpi-ribbon-wrapper">
        <div class="pm-kpi-ribbon bg-white rounded-2xl border border-slate-200/80 shadow-2xs divide-x divide-slate-100" style="display: grid !important; grid-template-columns: repeat(5, minmax(0, 1fr)) !important; width: 100% !important; min-width: 780px !important;">
            
            {{-- 1. Active Projects --}}
            <a href="{{ route('projects.index') }}" class="p-4 hover:bg-slate-50/80 transition-colors no-underline group block min-w-0">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-blue-600 transition-colors">Projects</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight pm-mono leading-none">
                    {{ $myProjectsCount }}
                </div>
                <div class="text-[11px] font-medium text-slate-500 mt-2 truncate">
                    <span class="text-blue-600 font-semibold">{{ $inProgressProjectsCount }} active</span> · {{ $onTrackProjectsCount }} on track
                </div>
            </a>

            {{-- 2. Assigned Tasks --}}
            <a href="{{ route('my-tasks.index') }}" class="p-4 hover:bg-slate-50/80 transition-colors no-underline group block min-w-0">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Deliverables</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight pm-mono leading-none">
                    {{ $myTasksCount }}
                </div>
                <div class="text-[11px] font-medium text-slate-500 mt-2 truncate">
                    <span class="text-emerald-600 font-semibold">{{ $completedTasksCount }} done</span> · {{ $myTasksCount > 0 ? round(($completedTasksCount / $myTasksCount) * 100) : 0 }}% completed
                </div>
            </a>

            {{-- 3. Due in 7 Days / Urgent --}}
            <a href="{{ route('my-tasks.index') }}" class="p-4 hover:bg-slate-50/80 transition-colors no-underline group block min-w-0">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-purple-600 transition-colors">Due 7 Days</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight pm-mono leading-none">
                    {{ $tasksDueSoonCount }}
                </div>
                <div class="text-[11px] font-medium mt-2 truncate">
                    @if($overdueTasksCount > 0)
                        <span class="text-rose-600 font-bold">{{ $overdueTasksCount }} overdue</span> · Action needed
                    @else
                        <span class="text-emerald-600 font-semibold">On schedule</span> · All clear
                    @endif
                </div>
            </a>

            {{-- 4. Governance / Approvals --}}
            <a href="{{ route('approvals.index') }}" class="p-4 hover:bg-slate-50/80 transition-colors no-underline group block min-w-0">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-amber-600 transition-colors">Approvals</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight pm-mono leading-none">
                    {{ $pendingApprovalsCount }}
                </div>
                <div class="text-[11px] font-medium mt-2 truncate">
                    @if($pendingApprovalsCount > 0)
                        <span class="text-amber-600 font-bold">Action required</span>
                    @else
                        <span class="text-slate-400">All clear</span>
                    @endif
                </div>
            </a>

            {{-- 5. Timesheets / Effort --}}
            <div class="p-4 min-w-0">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Timesheets</span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight pm-mono leading-none">
                    {{ $hoursThisWeekFormatted }}
                </div>
                <div class="text-[11px] font-medium text-slate-400 mt-2 truncate">
                    ⏱ Logged effort this week
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. MAIN DASHBOARD CONTENT (CLEAN, STRUCTURED, MINIMAL)    --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- LEFT COLUMN: PROJECTS & DELIVERABLES (8 COLS)              --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- 1. ACTIVE PROJECTS FOCUS --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-4 sm:p-5 flex items-center justify-between border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-slate-900">Active Projects</h2>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold pm-mono">{{ $myProjectsCount }}</span>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-[#c3122e] hover:underline no-underline">
                        View all &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($myProjects->take(5) as $proj)
                        @php
                            $progress = (int) round($proj->overall_progress ?? 0);
                            $healthVal = $proj->health?->value ?? 'on_track';
                            $healthBadgeClass = match($healthVal) {
                                'at_risk' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'needs_attention' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };
                            $healthDotClass = match($healthVal) {
                                'at_risk' => 'bg-rose-500',
                                'needs_attention' => 'bg-amber-500',
                                default => 'bg-emerald-500',
                            };
                            $healthLabel = match($healthVal) {
                                'at_risk' => 'At Risk',
                                'needs_attention' => 'Needs Attention',
                                default => 'On Track',
                            };
                        @endphp
                        <div class="p-4 sm:p-5 hover:bg-slate-50/60 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                        {{ $proj->code }}
                                    </span>
                                    <span class="text-xs text-slate-500 truncate">
                                        {{ $proj->subsidiary->name ?? 'George Steuart Group' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $healthBadgeClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $healthDotClass }}"></span>
                                        <span>{{ $healthLabel }}</span>
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 truncate">
                                    {{ $proj->name }}
                                </h3>
                            </div>

                            <div class="flex items-center gap-4 sm:gap-6 shrink-0">
                                {{-- Progress --}}
                                <div class="w-28 sm:w-32">
                                    <div class="flex items-center justify-between text-[11px] mb-1">
                                        <span class="text-slate-400 font-medium">Progress</span>
                                        <span class="font-bold text-slate-800 pm-mono">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full bg-[#c3122e]" style="width: {{ max(4, $progress) }}%;"></div>
                                    </div>
                                </div>

                                {{-- Deadline --}}
                                <div class="text-right text-xs font-semibold text-slate-500 min-w-16 hidden sm:block">
                                    <span>{{ $proj->deadline ? $proj->deadline->format('M d') : 'Flexible' }}</span>
                                </div>

                                {{-- Action --}}
                                <a href="{{ route('projects.show', $proj) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-colors no-underline">
                                    <span>Workspace</span>
                                    <span class="text-slate-400">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            No active projects assigned yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 2. TASKS DUE & UPCOMING DELIVERABLES --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-4 sm:p-5 flex items-center justify-between border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold text-slate-900">Priority Deliverables</h2>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold pm-mono">{{ $myTasksDueSoonList->count() }}</span>
                    </div>
                    <a href="{{ route('my-tasks.index') }}" class="text-xs font-semibold text-[#c3122e] hover:underline no-underline">
                        View all tasks &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($myTasksDueSoonList as $task)
                        @php
                            $daysLeft = $task->end_date ? (int) now()->today()->diffInDays($task->end_date, false) : 0;
                            $isOverdue = $daysLeft < 0;
                            $isDueToday = $daysLeft === 0;
                            $pri = ucfirst($task->priority?->value ?? 'medium');
                            $priCls = match($pri) {
                                'High' => 'text-rose-700 bg-rose-50 border-rose-200',
                                'Low'  => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                default => 'text-amber-700 bg-amber-50 border-amber-200'
                            };
                        @endphp
                        <a href="{{ route('projects.show', ['project' => $task->project_id]) }}" class="p-3.5 sm:p-4 hover:bg-slate-50/60 transition-colors flex items-center justify-between gap-3 text-inherit no-underline block">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="text-center px-2 py-1 rounded-lg {{ $isOverdue ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700' }} shrink-0 min-w-10">
                                    <span class="text-[9px] font-bold uppercase block leading-none opacity-80">{{ $task->end_date ? $task->end_date->format('M') : 'DUE' }}</span>
                                    <span class="text-xs font-bold pm-mono block leading-tight mt-0.5">{{ $task->end_date ? $task->end_date->format('d') : '--' }}</span>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                        {{ $task->title }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-500">
                                        <span class="truncate max-w-[140px]">{{ $task->project->name ?? 'Project' }}</span>
                                        <span>&bull;</span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold border {{ $priCls }}">{{ $pri }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 text-right">
                                @if($isOverdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 pm-pulse-live"></span>
                                        <span>Overdue ({{ abs($daysLeft) }}d)</span>
                                    </span>
                                @elseif($isDueToday)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span>Due Today</span>
                                    </span>
                                @else
                                    <span class="text-xs text-slate-500 font-medium">In {{ $daysLeft }} days</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            All deliverables clear! No tasks due in the next 7 days.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- RIGHT COLUMN: SPRINT RHYTHM & RADAR (4 COLS)               --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- 1. SPRINT VELOCITY --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Sprint Velocity</h3>
                        <p class="text-[11px] text-slate-400">Task completion rhythm</p>
                    </div>

                    <div class="flex items-center p-0.5 rounded-lg bg-slate-100 text-[10px] font-bold">
                        <button wire:click="setChartPeriod('week')" type="button" class="px-2 py-0.5 rounded-md cursor-pointer transition-colors {{ $chartPeriod === 'week' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500' }}">
                            Week
                        </button>
                        <button wire:click="setChartPeriod('month')" type="button" class="px-2 py-0.5 rounded-md cursor-pointer transition-colors {{ $chartPeriod === 'month' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500' }}">
                            Month
                        </button>
                    </div>
                </div>

                {{-- Visual Bars --}}
                <div class="pt-2">
                    <div class="h-32 flex items-end justify-between gap-1.5 px-1">
                        @foreach($chartPoints as $pt)
                            @php
                                $cVal = $pt['completed'];
                                $ipVal = $pt['in_progress'];
                                $pVal = $pt['pending'];
                                $cH = min(100, max(6, round(($cVal / max(1, $chartYMax)) * 100)));
                                $ipH = min(100, max(6, round(($ipVal / max(1, $chartYMax)) * 100)));
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-1 h-full justify-end group relative">
                                <div class="w-full max-w-[14px] flex items-end justify-center gap-0.5 h-24 rounded bg-slate-100 p-0.5">
                                    <div class="w-1/2 rounded-t-xs bg-emerald-500" style="height: {{ $cH }}%;"></div>
                                    <div class="w-1/2 rounded-t-xs bg-blue-500" style="height: {{ $ipH }}%;"></div>
                                </div>
                                <span class="text-[10px] font-bold {{ $pt['isToday'] ? 'text-[#c3122e]' : 'text-slate-400' }}">
                                    {{ substr($pt['label'], 0, 3) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-center gap-4 text-[10px] text-slate-500 pt-1 border-t border-slate-100">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Done</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span>In Progress</span>
                </div>
            </div>

            {{-- 2. ATTENTION RADAR (BLOCKERS & RISKS) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Radar &amp; Attention</h3>
                        <p class="text-[11px] text-slate-400">{{ $currentBlockers->count() }} Blockers · {{ $openRisks->count() }} Risks</p>
                    </div>
                    <button wire:click="openAddRiskModal" type="button" class="text-xs font-semibold text-[#c3122e] hover:underline cursor-pointer">
                        + Log Risk
                    </button>
                </div>

                <div class="space-y-2">
                    @forelse($currentBlockers->take(2) as $blocker)
                        <div class="p-3 rounded-xl bg-rose-50/60 border border-rose-200/70 text-xs space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-bold text-slate-900 truncate">{{ $blocker->description ?? 'Task Blocker' }}</span>
                                <button wire:click="openResolveBlockerModal({{ $blocker->id }})" type="button" class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-600 text-white cursor-pointer hover:bg-rose-700 shrink-0">
                                    Resolve
                                </button>
                            </div>
                            <span class="text-[10px] text-slate-500 block truncate">{{ $blocker->wbsItem->project->name ?? 'Project Task' }}</span>
                        </div>
                    @empty
                        @if($openRisks->isNotEmpty())
                            @foreach($openRisks->take(2) as $risk)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 text-xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-semibold text-slate-800 truncate">{{ $risk->title }}</span>
                                        <span class="text-[10px] font-bold uppercase text-amber-700 pm-mono">{{ $risk->probability ?? 'Med' }}</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-0.5 truncate">{{ $risk->project->name ?? 'Project' }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="py-4 text-center text-xs text-slate-400">
                                <span class="text-emerald-600 font-bold">✓ Radar clear</span>
                                <p class="text-[11px] text-slate-400 mt-0.5">No active blockers reported.</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            {{-- 3. UPCOMING MILESTONES --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Milestones</h3>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 no-underline">
                        Roadmap &rarr;
                    </a>
                </div>

                <div class="space-y-2">
                    @forelse($upcomingMilestones->take(3) as $m)
                        @php
                            $daysLeft = (int) now()->today()->diffInDays($m->end_date, false);
                        @endphp
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-900 truncate">{{ $m->title }}</h4>
                                <span class="text-[10px] text-slate-400 truncate block">{{ $m->project->name ?? 'George Steuart' }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-mono text-[11px] font-bold text-slate-700 block">{{ $m->end_date->format('M d') }}</span>
                                <span class="text-[9px] font-semibold text-slate-400">{{ $daysLeft <= 0 ? 'Today' : "In {$daysLeft}d" }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-4 text-center text-xs text-slate-400">
                            No upcoming milestones scheduled.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. MODALS & POPUPS SYSTEM (100% PRESERVED & FUNCTIONAL)   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}

    {{-- 1. Review Project Assignment Modal --}}
    @if($showReviewModal && $reviewProject)
        @php
            $wbsItems = $reviewProject->wbsItems ?? collect();
            $phases = $wbsItems->where('item_type.value', 'phase');
            if ($phases->isEmpty()) {
                $phases = $wbsItems->where('parent_id', null);
            }
            $tasksCount = $wbsItems->where('item_type.value', 'task')->count();
            $milestonesCount = $wbsItems->where('is_milestone', true)->count();
            
            $sponsors = $reviewProject->members->where('pivot.role', 'sponsor');
            $owners = $reviewProject->members->where('pivot.role', 'owner');
            $committee = $reviewProject->members->where('pivot.role', 'steering_committee');
            $members = $reviewProject->members->where('pivot.role', 'member');
            $totalTeam = $sponsors->count() + $owners->count() + $committee->count() + $members->count() + 1;

            $durationDays = ($reviewProject->start_date && $reviewProject->deadline) 
                ? (int) $reviewProject->start_date->diffInDays($reviewProject->deadline) + 1 
                : null;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-950/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200">
                
                {{-- Modal Header --}}
                <div style="background: linear-gradient(135deg, #18080c 0%, #300c16 50%, #1a080e 100%); color: #ffffff;" class="p-5 sm:p-6 flex items-center justify-between border-b border-rose-950/40 shrink-0">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl shadow-inner shrink-0">
                            ⭐
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base sm:text-lg font-black text-white tracking-tight">Project Leadership Assignment Review</h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black bg-rose-500/30 text-rose-200 border border-rose-400/30 whitespace-nowrap">{{ $reviewProject->code }}</span>
                            </div>
                            <p class="text-xs text-rose-200/80 font-medium mt-0.5 truncate">{{ $reviewProject->subsidiary->name ?? 'George Steuart Group' }} · PMO Assigned</p>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" type="button" class="p-2 text-white/70 hover:text-white rounded-xl hover:bg-white/10 cursor-pointer transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Scrollable Body --}}
                <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4 pm-custom-scroll">
                    @if(!$showRejectModal)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Project Title</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ ucfirst($reviewProject->priority?->value ?? 'medium') }} Priority
                                </span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 leading-snug">{{ $reviewProject->name }}</h4>
                            @if($reviewProject->description)
                                <div class="pt-2 border-t border-slate-200/70">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-0.5">Scope &amp; Objectives</span>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $reviewProject->description }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">📅 Start Date</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">🏁 Deadline</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'Flexible' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">⏳ Duration</span>
                                <span class="font-bold text-slate-900 block mt-1">
                                    {{ $durationDays ? "{$durationDays} Days" : 'Flexible' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">💰 Budget</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">
                                    {{ $reviewProject->estimated_budget > 0 ? 'Rs. ' . number_format($reviewProject->estimated_budget, 0) : 'Not Specified' }}
                                </span>
                            </div>
                        </div>

                        {{-- WBS Preview --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📋</span>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">WBS Execution Structure</h5>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $wbsItems->count() }} Items
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $phases->count() }} Phases
                                    </span>
                                </div>
                            </div>

                            @if($wbsItems->count() > 0)
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 pm-custom-scroll rounded-xl bg-white p-2.5 border border-slate-200/70">
                                    @foreach($wbsItems as $item)
                                        @php
                                            $isPhase = ($item->item_type?->value === 'phase' || !$item->parent_id);
                                        @endphp
                                        <div class="flex items-center justify-between gap-2 p-2 rounded-lg text-xs {{ $isPhase ? 'bg-slate-50 font-black text-slate-900 border border-slate-200/60' : 'pl-6 text-slate-700 font-medium' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[10px] font-mono {{ $isPhase ? 'text-[#c3122e] font-black' : 'text-slate-400' }}">
                                                    {{ $isPhase ? '📁 Phase:' : '↳ Task:' }}
                                                </span>
                                                <span class="truncate">{{ $item->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0 text-[10px]">
                                                @if($item->duration)
                                                    <span class="text-slate-400 font-mono">{{ $item->duration }}d</span>
                                                @endif
                                                @if($item->is_milestone)
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-emerald-100 text-emerald-800">Milestone</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Decline Reason Form --}}
                        <div class="space-y-3.5">
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed space-y-1">
                                <strong class="font-black block text-sm">⚠️ Declining Project Leadership Assignment</strong>
                                <p class="text-amber-800 font-medium">Please provide specific feedback or capacity blockers so PMO Administration can review.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-700 uppercase tracking-wider block">
                                    Reason / Feedback <span class="text-rose-500">*</span>
                                </label>
                                <textarea wire:model="rejectionReasonInput" rows="4"
                                    placeholder="Explain why you cannot take leadership of this project..."
                                    class="w-full p-3.5 rounded-2xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all"></textarea>
                                @error('rejectionReasonInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Action Footer --}}
                <div class="shrink-0 p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-wrap">
                    @if(!$showRejectModal)
                        <button wire:click="closeReviewModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 cursor-pointer transition-colors">
                            Cancel
                        </button>
                        <div class="flex items-center gap-2">
                            <button wire:click="openRejectForm({{ $reviewProject->id }})" type="button" class="px-4 py-2.5 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 cursor-pointer transition-colors">
                                Decline
                            </button>
                            <button wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all cursor-pointer flex items-center gap-1.5" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                <span>Accept Leadership &amp; Launch →</span>
                            </button>
                        </div>
                    @else
                        <button wire:click="$set('showRejectModal', false)" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 cursor-pointer transition-colors">
                            Back
                        </button>
                        <button wire:click="submitRejection" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md bg-rose-600 hover:bg-rose-700 cursor-pointer transition-colors">
                            Confirm Decline
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 2. Resolve Blocker Modal --}}
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-md p-6 space-y-4 animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center">
                            ⚡
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Resolve Task Blocker</h3>
                            <p class="text-[11px] text-slate-500">Provide resolution details to unblock deliverable</p>
                        </div>
                    </div>
                    <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider">Resolution Notes *</label>
                    <textarea wire:model="blockerResolutionInput" rows="3" placeholder="Describe how the impediment was resolved..." class="w-full p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/10"></textarea>
                    @error('blockerResolutionInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                    <button wire:click="resolveBlocker" type="button" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 cursor-pointer shadow-xs">Resolve &amp; Unblock</button>
                </div>
            </div>
        </div>
    @endif

    {{-- 3. Add Risk Modal --}}
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg p-6 space-y-4 animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Log Project Risk</h3>
                            <p class="text-[11px] text-slate-500">Record threat &amp; mitigation strategy</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddRiskModal', false)" type="button" class="text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider block mb-1">Target Project *</label>
                        <select wire:model="riskProjectId" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold outline-none focus:border-rose-500">
                            @foreach($myProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                            @endforeach
                        </select>
                        @error('riskProjectId') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider block mb-1">Risk Title *</label>
                        <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API rate limit bottlenecks" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold outline-none focus:border-rose-500"/>
                        @error('riskTitle') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider block mb-1">Probability *</label>
                            <select wire:model="riskProbability" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold outline-none focus:border-rose-500">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider block mb-1">Impact *</label>
                            <select wire:model="riskImpact" class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold outline-none focus:border-rose-500">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-700 tracking-wider block mb-1">Mitigation Plan</label>
                        <textarea wire:model="riskMitigation" rows="2" placeholder="Actionable contingency steps..." class="w-full p-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs outline-none focus:border-rose-500"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button wire:click="$set('showAddRiskModal', false)" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                    <button wire:click="createRisk" type="button" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#9f0d22] cursor-pointer shadow-xs">Save Risk</button>
                </div>
            </div>
        </div>
    @endif
</div>
