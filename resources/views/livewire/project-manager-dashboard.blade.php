<div wire:poll.30s class="space-y-6 max-w-[1600px] mx-auto pb-10">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP GREETING & STATUS                                   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $userName = auth()->user()->name ?? 'User';
        $firstName = explode(' ', $userName)[0];

        // Format dates & fallback project listings
        $displayProjects = $myProjects->take(5);
        $displayTasks = $myTasksDueSoonList->take(5);
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $greeting }}, {{ $firstName }}
            </h1>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 mt-1">
                <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Workspace</span>
                </span>
                <span class="text-slate-300">•</span>
                <span>{{ now()->format('l, F j, Y') }}</span>
                <span class="text-slate-300 hidden sm:inline">•</span>
                <span class="font-semibold text-slate-600 hidden sm:inline">George Steuart Group</span>
            </div>
        </div>

        {{-- Quick User Action shortcuts --}}
        <div class="flex items-center gap-2">
            @if(auth()->user()?->canCreateProject())
                <a href="{{ route('projects.create') }}" 
                   wire:navigate.hover
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-sm hover:shadow transition-all duration-150 cursor-pointer no-underline active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            @else
                <a href="{{ route('daily-updates.index') }}" 
                   wire:navigate.hover
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-sm hover:shadow transition-all duration-150 cursor-pointer no-underline active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Daily Update</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Pending Leadership Invitation (If any) --}}
    @if($pendingInvitations->count() > 0)
        <div class="rounded-2xl border border-rose-200 bg-rose-50/40 p-4 shadow-2xs flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-100 text-[#c3122e] flex items-center justify-center text-base shrink-0">
                    👑
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Project Leadership Assignment Required ({{ $pendingInvitations->count() }})</h3>
                    <p class="text-xs text-slate-500 font-medium">You have been assigned as Project Leader for pending projects.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @foreach($pendingInvitations->take(2) as $pInv)
                    <button wire:click="openReviewModal({{ $pInv->id }})" type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] cursor-pointer transition-colors shadow-2xs">
                        Review {{ $pInv->code }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. TOP METRICS RIBBON (4 PASTEL CARDS)                     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- 1. Total Projects (Soft Blush/Rose Tint) --}}
        <div class="rounded-2xl border border-rose-100/80 bg-[#fef9f9] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">Total Projects</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $myProjectsCount }}
                </div>
                <div class="text-xs font-semibold {{ $newProjectsThisWeekCount > 0 ? 'text-emerald-600' : 'text-slate-500' }} flex items-center gap-1 pt-0.5">
                    @if($newProjectsThisWeekCount > 0)
                        <span>+{{ $newProjectsThisWeekCount }} new this week</span>
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    @else
                        <span>{{ $inProgressProjectsCount }} in progress</span>
                    @endif
                </div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-700 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
        </div>

        {{-- 2. My Tasks (Soft Emerald Tint) --}}
        <div class="rounded-2xl border border-emerald-100/80 bg-[#f8fdfa] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-500 hover:text-slate-900 block transition-colors">
                    My Tasks
                </a>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="hover:text-[#c3122e] transition-colors">
                        {{ $myAssignedTasksCount > 0 ? $myAssignedTasksCount : $myTasksCount }}
                    </a>
                </div>
                <div class="text-xs font-semibold text-emerald-600 flex items-center gap-1 pt-0.5">
                    @if($completedTodayCount > 0)
                        <span>+ {{ $completedTodayCount }} completed today</span>
                    @elseif($teamTasksCount > $myAssignedTasksCount && $myAssignedTasksCount > 0)
                        <a href="{{ route('my-tasks.index', ['scope' => 'pm_projects']) }}" wire:navigate.hover class="hover:underline flex items-center gap-1 font-bold">
                            <span>{{ $teamTasksCount }} total in projects</span>
                            <span>→</span>
                        </a>
                    @else
                        <span>Assigned to you</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-600 shrink-0 hover:scale-105 transition-transform" title="Go to My Tasks">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </a>
        </div>

        {{-- 3. In Progress (Soft Sky Blue Tint) --}}
        <div class="rounded-2xl border border-sky-100/80 bg-[#f8fbfe] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">In Progress</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $inProgressTasksCount }}
                </div>
                <div class="text-xs font-semibold text-sky-600 flex items-center gap-1 pt-0.5">
                    <span>{{ $onTrackProjectsCount }} on track</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-sky-50 border border-sky-200/60 flex items-center justify-center text-sky-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- 4. Overdue (Soft Amber/Orange Tint) --}}
        <div class="rounded-2xl border border-amber-100/80 bg-[#fffbf6] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">Overdue</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $overdueTasksCount }}
                </div>
                @if($overdueTasksCount > 0)
                    <div class="text-xs font-bold text-rose-600 flex items-center gap-1 pt-0.5">
                        <span>• Needs attention</span>
                    </div>
                @else
                    <div class="text-xs font-semibold text-emerald-600 flex items-center gap-1 pt-0.5">
                        <span>✓ All on schedule</span>
                    </div>
                @endif
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. MIDDLE SECTION (3 COLUMNS: PROJECTS, TASKS, CALENDAR)   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- COLUMN 1: MY PROJECTS (lg:col-span-5)                      --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-1 border-b border-slate-100">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">My Projects</h2>
                    <a href="{{ route('projects.my-leads') }}" wire:navigate.hover class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Projects List: 100% Real User Projects --}}
                <div class="space-y-3">
                    @forelse($myProjects->take(5) as $p)
                        @php
                            $pct = (int) ($p->overall_progress ?? 0);
                            $pStatus = $p->status instanceof \BackedEnum ? $p->status->value : (string) ($p->status ?? 'in_progress');
                            $pHealth = $p->health instanceof \BackedEnum ? $p->health->value : (string) ($p->health ?? 'on_track');

                            $statLabel = 'In Progress';
                            $statClass = 'bg-blue-50 text-blue-700 border-blue-200/70';
                            $barColor = 'bg-blue-500';

                            if ($pStatus === 'completed') {
                                $statLabel = 'Completed';
                                $statClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                                $barColor = 'bg-emerald-500';
                            } elseif ($pHealth === 'on_track' || $pStatus === 'in_progress') {
                                $statLabel = 'On Track';
                                $statClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                                $barColor = 'bg-emerald-500';
                            } elseif ($pStatus === 'planning') {
                                $statLabel = 'Planning';
                                $statClass = 'bg-purple-50 text-purple-700 border-purple-200/70';
                                $barColor = 'bg-purple-500';
                            } elseif ($pStatus === 'on_hold') {
                                $statLabel = 'On Hold';
                                $statClass = 'bg-amber-50 text-amber-700 border-amber-200/70';
                                $barColor = 'bg-amber-500';
                            } elseif ($pStatus === 'draft') {
                                $statLabel = 'Not Started';
                                $statClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                $barColor = 'bg-slate-300';
                            }
                        @endphp
                        <div class="flex items-center justify-between gap-3 group py-2 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 rounded-xl px-2 transition-colors">
                            {{-- Project Info --}}
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('projects.show', $p->id) }}" wire:navigate.hover class="text-xs sm:text-sm font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors block no-underline">
                                    {{ $p->name }}
                                </a>
                                <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                    {{ $p->subsidiary?->name ?? 'George Steuart Group' }}
                                </span>
                            </div>

                            {{-- Status Badge & Progress Bar --}}
                            <div class="flex flex-col items-center shrink-0 w-24 sm:w-28 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statClass }} whitespace-nowrap">
                                    {{ $statLabel }}
                                </span>
                                <div class="flex items-center gap-1.5 w-full mt-1.5">
                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ max(2, $pct) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 w-6 text-right">{{ $pct }}%</span>
                                </div>
                            </div>

                            {{-- Due Date --}}
                            <div class="text-right shrink-0 w-20">
                                <span class="text-[10px] font-medium text-slate-400 block">Due</span>
                                <span class="text-[11px] font-bold text-slate-700 whitespace-nowrap block">
                                    {{ $p->deadline ? \Carbon\Carbon::parse($p->deadline)->format('M j, Y') : 'Ongoing' }}
                                </span>
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

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- COLUMN 2: UPCOMING TASKS (lg:col-span-4)                   --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-1 border-b border-slate-100">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Upcoming Tasks</h2>
                    <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-xs font-semibold text-amber-600/90 hover:text-amber-700 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Tasks List: 100% Real User Tasks with Interactive Checkbox --}}
                <div class="space-y-3">
                    @forelse($myTasksDueSoonList as $task)
                        @php
                            $isCompleted = ($task->status === \App\Enums\WbsStatus::COMPLETED || $task->status?->value === 'completed');
                            $isToday = $task->end_date && $task->end_date->isToday();
                            $isTmrw = $task->end_date && $task->end_date->isTomorrow();
                            $isPast = $task->end_date && $task->end_date->isPast() && !$isToday;

                            $dueStr = $isToday ? 'Today' : ($isTmrw ? 'Tomorrow' : ($task->end_date ? $task->end_date->format('M j') : 'Soon'));
                            $dueColor = ($isToday || $isPast) ? 'text-rose-600' : ($isTmrw ? 'text-amber-600' : 'text-slate-600');

                            $rawPrio = $task->priority instanceof \BackedEnum 
                                ? $task->priority->value 
                                : (is_string($task->priority) ? $task->priority : ($task->priority?->name ?? 'medium'));
                            $prioVal = strtolower((string)$rawPrio);
                            $prio = ucfirst($prioVal);
                            $prioClass = 'bg-amber-50 text-amber-700 border-amber-200/70';

                            if (in_array($prioVal, ['critical', 'high'])) {
                                $prioClass = 'bg-rose-50 text-rose-700 border-rose-200/70';
                                $prio = 'High';
                            } elseif ($prioVal === 'low') {
                                $prioClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                                $prio = 'Low';
                            } else {
                                $prio = 'Medium';
                            }
                        @endphp
                        <div class="flex items-start justify-between gap-2.5 group py-1.5 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 rounded-xl px-2 transition-colors">
                            {{-- Circular Checkbox & Title --}}
                            <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                <button type="button" 
                                        wire:click="toggleTaskComplete({{ $task->id }})" 
                                        title="{{ $isCompleted ? 'Mark Incomplete' : 'Mark Completed' }}"
                                        class="w-5 h-5 rounded-full border-2 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-emerald-500 bg-white text-transparent' }} transition-colors mt-0.5 shrink-0 cursor-pointer flex items-center justify-center">
                                    <svg class="w-3 h-3 {{ $isCompleted ? 'block' : 'hidden group-hover:block text-slate-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 truncate group-hover:text-[#c3122e] transition-colors {{ $isCompleted ? 'line-through text-slate-400' : '' }}">
                                        {{ $task->title }}
                                    </h4>
                                    <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                        {{ $task->project?->name ?? 'George Steuart Workspace' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Due Date & Priority Pill --}}
                            <div class="flex flex-col items-end shrink-0 space-y-1">
                                <span class="text-[11px] font-bold {{ $dueColor }} flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $dueStr }}</span>
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $prioClass }}">
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

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- COLUMN 3: CALENDAR WIDGET & INSPIRATION QUOTE (lg:col-span-3) --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-3 flex flex-col justify-between gap-4">

            {{-- 1. Mini Interactive Calendar Widget with 100% Reliable 7-Column Grid --}}
            <div x-data="{
                    monthOffset: 0,
                    get displayMonth() {
                        const d = new Date();
                        d.setDate(1);
                        d.setMonth(d.getMonth() + this.monthOffset);
                        return d.toLocaleString('default', { month: 'long', year: 'numeric' });
                    },
                    get daysInMonth() {
                        const d = new Date();
                        d.setDate(1);
                        d.setMonth(d.getMonth() + this.monthOffset + 1, 0);
                        return d.getDate();
                    },
                    get firstDayIndex() {
                        const d = new Date();
                        d.setDate(1);
                        d.setMonth(d.getMonth() + this.monthOffset);
                        return d.getDay();
                    },
                    isCurrentDay(day) {
                        if (this.monthOffset !== 0) return false;
                        const today = new Date();
                        return today.getDate() === day;
                    }
                 }" 
                 class="bg-white rounded-2xl border border-slate-200/80 p-4.5 shadow-2xs">
                
                {{-- Calendar Header with Prev/Next Navigation --}}
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <span class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight" x-text="displayMonth"></span>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="monthOffset--" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition cursor-pointer" title="Previous Month">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" @click="monthOffset++" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition cursor-pointer" title="Next Month">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Week Days Labels --}}
                <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 2px; text-align: center;" class="text-[10px] font-bold text-slate-400 mb-2 select-none">
                    <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                </div>

                {{-- Calendar Dates Grid --}}
                <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 2px; text-align: center;" class="text-xs font-semibold select-none">
                    {{-- Blank spaces for first day --}}
                    <template x-for="blank in firstDayIndex" :key="'blank-' + blank">
                        <div class="h-7"></div>
                    </template>
                    {{-- Days of Month --}}
                    <template x-for="day in daysInMonth" :key="'day-' + day">
                        <div class="flex items-center justify-center">
                            <button type="button"
                                    :class="isCurrentDay(day) ? 'bg-[#c3122e] text-white font-black shadow-xs ring-2 ring-rose-200' : 'text-slate-700 hover:bg-rose-50 hover:text-[#c3122e]'"
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] cursor-pointer transition-colors"
                                    x-text="day">
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. Inspiration Quote Card (With Aesthetic Warm Styling) --}}
            <div class="relative overflow-hidden rounded-2xl border border-amber-200/60 shadow-2xs min-h-[130px] flex flex-col justify-center p-5 group bg-cover bg-center"
                 style="background-image: url('{{ asset('images/dashboard_quote_leaf_bg.jpg') }}'); background-color: #fffbf5;">
                
                {{-- Soft Warm Overlay for clean text readability --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#fffbf5]/95 via-[#fffbf5]/85 to-[#fffbf5]/60 pointer-events-none"></div>

                <div class="relative z-10 space-y-1">
                    {{-- Big Quote Mark --}}
                    <div class="text-amber-600 font-serif text-3xl font-black leading-none select-none">
                        “
                    </div>
                    <div class="text-slate-800 font-serif font-bold text-base sm:text-lg leading-snug">
                        Progress<br>not perfection.
                    </div>
                    {{-- Gold Divider Line --}}
                    <div class="w-10 h-0.5 bg-amber-500/80 rounded-full mt-2"></div>
                </div>
            </div>

        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. BOTTOM SECTION (TASK PROGRESS CHART & QUICK ACCESS)     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- CARD 1: TASK PROGRESS 7-DAY BAR CHART (lg:col-span-7)      --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Header & Legend --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Task Progress</h2>
                        <span class="text-[11px] font-medium text-slate-400">Current Week Performance</span>
                    </div>
                    <div class="flex items-center gap-3.5 text-xs font-semibold text-slate-500">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#14b8a6]"></span>
                            <span>Completed</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#0284c7]"></span>
                            <span>In Progress</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#cbd5e1]"></span>
                            <span>Pending</span>
                        </span>
                    </div>
                </div>

                {{-- Grouped Bar Chart Area (Real Current September Data) --}}
                @php
                    $startOfWeek = now()->startOfWeek();
                    $chartDays = [];
                    $maxRecorded = 5;

                    for ($d = 0; $d < 7; $d++) {
                        $dayDate = $startOfWeek->copy()->addDays($d);
                        $comp = $chartPoints[$d]['completed'] ?? 0;
                        $inProg = $chartPoints[$d]['in_progress'] ?? 0;
                        $pend = $chartPoints[$d]['pending'] ?? 0;

                        $maxRecorded = max($maxRecorded, $comp, $inProg, $pend);

                        $chartDays[] = [
                            'label' => $dayDate->isToday() ? 'Today' : $dayDate->format('D'),
                            'sub' => $dayDate->format('M j'),
                            'isToday' => $dayDate->isToday(),
                            'completed' => $comp,
                            'in_progress' => $inProg,
                            'pending' => $pend,
                        ];
                    }

                    $yMaxVal = max(5, (int)(ceil($maxRecorded / 5) * 5));
                @endphp

                <div class="relative pt-2">
                    {{-- Y-Axis Grid Lines & Markers --}}
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] font-semibold text-slate-400 pb-6 pr-2">
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">{{ $yMaxVal }}</span>
                            <div class="flex-1 border-b border-slate-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">{{ (int) round($yMaxVal * 0.66) }}</span>
                            <div class="flex-1 border-b border-slate-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">{{ (int) round($yMaxVal * 0.33) }}</span>
                            <div class="flex-1 border-b border-slate-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">0</span>
                            <div class="flex-1 border-b border-slate-200"></div>
                        </div>
                    </div>

                    {{-- Bars Grouped Columns --}}
                    <div class="h-44 pl-8 pr-2 flex items-end justify-between gap-2 sm:gap-4 relative z-10 pb-6">
                        @foreach($chartDays as $dayBar)
                            @php
                                $compHeight = min(100, round(($dayBar['completed'] / $yMaxVal) * 100));
                                $inProgHeight = min(100, round(($dayBar['in_progress'] / $yMaxVal) * 100));
                                $pendHeight = min(100, round(($dayBar['pending'] / $yMaxVal) * 100));
                            @endphp
                            <div class="flex-1 flex flex-col items-center h-full justify-end group cursor-pointer">
                                {{-- 3 Bars Side-by-Side --}}
                                <div class="flex items-end gap-1 w-full justify-center h-full">
                                    {{-- Completed (Teal) --}}
                                    <div class="w-2.5 sm:w-3.5 bg-[#14b8a6] rounded-t-sm transition-all duration-300 hover:brightness-110" 
                                         style="height: {{ max(4, $compHeight) }}%;"
                                         title="Completed: {{ $dayBar['completed'] }}"></div>
                                    
                                    {{-- In Progress (Sky Blue) --}}
                                    <div class="w-2.5 sm:w-3.5 bg-[#0284c7] rounded-t-sm transition-all duration-300 hover:brightness-110" 
                                         style="height: {{ max(4, $inProgHeight) }}%;"
                                         title="In Progress: {{ $dayBar['in_progress'] }}"></div>
                                    
                                    {{-- Pending (Light Gray) --}}
                                    <div class="w-2.5 sm:w-3.5 bg-[#cbd5e1] rounded-t-sm transition-all duration-300 hover:brightness-95" 
                                         style="height: {{ max(4, $pendHeight) }}%;"
                                         title="Pending: {{ $dayBar['pending'] }}"></div>
                                </div>
                                {{-- Day Label below baseline --}}
                                <span class="text-[10.5px] font-semibold {{ $dayBar['isToday'] ? 'text-[#c3122e] font-bold' : 'text-slate-500' }} mt-2 whitespace-nowrap block text-center">
                                    {{ $dayBar['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- CARD 2: QUICK ACCESS (lg:col-span-5)                       --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 pb-1 border-b border-slate-100">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Quick Access</h2>
                    <span class="text-[11px] font-medium text-slate-400">Essential Tools</span>
                </div>

                {{-- 4 Action Tiles Side-by-Side Tailored for System Role --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    
                    {{-- 1. Daily Update OR Create Project (Based on Permissions) --}}
                    @if(auth()->user()?->canCreateProject())
                        <a href="{{ route('projects.create') }}" 
                           wire:navigate.hover
                           class="rounded-2xl border border-rose-100/90 bg-[#fef7f7] hover:bg-rose-100/60 p-3.5 sm:p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                            <div class="w-10 h-10 rounded-full bg-[#c3122e] text-white flex items-center justify-center font-black text-lg mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 leading-tight">Create Project</span>
                            <span class="text-[10px] text-slate-400 font-medium mt-0.5 hidden sm:block">New initiative</span>
                        </a>
                    @else
                        <a href="{{ route('daily-updates.index') }}" 
                           wire:navigate.hover
                           class="rounded-2xl border border-rose-100/90 bg-[#fef7f7] hover:bg-rose-100/60 p-3.5 sm:p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                            <div class="w-10 h-10 rounded-full bg-[#c3122e] text-white flex items-center justify-center mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            <span class="text-xs font-bold text-slate-800 leading-tight">Daily Update</span>
                            <span class="text-[10px] text-slate-400 font-medium mt-0.5 hidden sm:block">Status report</span>
                        </a>
                    @endif

                    {{-- 2. My Tasks (Soft Emerald) --}}
                    <a href="{{ route('my-tasks.index') }}" 
                       wire:navigate.hover
                       class="rounded-2xl border border-emerald-100/90 bg-[#f6fbf8] hover:bg-emerald-100/60 p-3.5 sm:p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-[#10b981] text-white flex items-center justify-center font-black text-sm mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">My Tasks</span>
                        <span class="text-[10px] text-slate-400 font-medium mt-0.5 hidden sm:block">Task board</span>
                    </a>

                    {{-- 3. View Calendar (Soft Purple) --}}
                    <a href="{{ route('calendar.index') }}" 
                       wire:navigate.hover
                       class="rounded-2xl border border-purple-100/90 bg-[#faf6fc] hover:bg-purple-100/60 p-3.5 sm:p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-[#8b5cf6] text-white flex items-center justify-center mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">View Calendar</span>
                        <span class="text-[10px] text-slate-400 font-medium mt-0.5 hidden sm:block">Schedule</span>
                    </a>

                    {{-- 4. Risks & Blockers (Soft Blue - Correct System Feature) --}}
                    <a href="{{ route('risks.index') }}" 
                       wire:navigate.hover
                       class="rounded-2xl border border-sky-100/90 bg-[#f5f9fc] hover:bg-sky-100/60 p-3.5 sm:p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-[#0284c7] text-white flex items-center justify-center mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Risks &amp; Blockers</span>
                        <span class="text-[10px] text-slate-400 font-medium mt-0.5 hidden sm:block">Issue tracking</span>
                    </a>

                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. LIVEWIRE MODALS (PRESERVED FUNCTIONALITY)               --}}
    {{-- ══════════════════════════════════════════════════════════ --}}

    {{-- A. Review Project Assignment Modal --}}
    @if($showReviewModal && $reviewProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-xl p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-mono font-bold text-[#c3122e]">{{ $reviewProject->code }}</span>
                        <h3 class="text-base font-bold text-slate-900">{{ $reviewProject->name }}</h3>
                    </div>
                    <button wire:click="closeReviewModal" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="text-xs text-slate-600 space-y-2">
                    <p>{{ $reviewProject->description ?? 'No description provided.' }}</p>
                    <div class="grid grid-cols-2 gap-2 pt-2 text-[11px]">
                        <div><strong class="text-slate-700">Department:</strong> {{ $reviewProject->subsidiary?->name ?? 'N/A' }}</div>
                        <div><strong class="text-slate-700">Target Date:</strong> {{ $reviewProject->deadline ? \Carbon\Carbon::parse($reviewProject->deadline)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                </div>

                @if($showRejectModal)
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <label class="text-xs font-bold text-rose-700">Reason for Declining:</label>
                        <textarea wire:model="rejectionReasonInput" class="w-full text-xs p-2.5 rounded-xl border border-rose-200 focus:ring-1 focus:ring-rose-500" rows="3" placeholder="Please state reasons for declining this assignment..."></textarea>
                        @error('rejectionReasonInput') <span class="text-xs text-rose-600 block">{{ $message }}</span> @enderror
                        <div class="flex justify-end gap-2">
                            <button wire:click="$set('showRejectModal', false)" type="button" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                            <button wire:click="submitRejection" type="button" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700">Confirm Decline</button>
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                        <button wire:click="openRejectForm({{ $reviewProject->id }})" type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50">Decline</button>
                        <button wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm">Accept Leadership</button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- B. Resolve Blocker Modal --}}
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Resolve Task Blocker</h3>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-700 block mb-1">Resolution Summary:</label>
                    <textarea wire:model="blockerResolutionInput" rows="3" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 focus:ring-1 focus:ring-emerald-500" placeholder="Describe how this blocker was resolved..."></textarea>
                    @error('blockerResolutionInput') <span class="text-xs text-rose-600">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button wire:click="resolveBlocker" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700">Mark Resolved</button>
                </div>
            </div>
        </div>
    @endif

    {{-- C. Add Risk Modal --}}
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md p-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Log Project Risk</h3>
                    <button wire:click="$set('showAddRiskModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Project</label>
                        <select wire:model="riskProjectId" class="w-full p-2 rounded-xl border border-slate-200">
                            @foreach($assignedProjects as $ap)
                                <option value="{{ $ap->id }}">{{ $ap->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Risk Title</label>
                        <input wire:model="riskTitle" type="text" class="w-full p-2 rounded-xl border border-slate-200" placeholder="e.g. Delays in API approval">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="font-semibold text-slate-700 block mb-1">Probability</label>
                            <select wire:model="riskProbability" class="w-full p-2 rounded-xl border border-slate-200">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-semibold text-slate-700 block mb-1">Impact</label>
                            <select wire:model="riskImpact" class="w-full p-2 rounded-xl border border-slate-200">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-slate-700 block mb-1">Mitigation Plan</label>
                        <textarea wire:model="riskMitigation" rows="2" class="w-full p-2 rounded-xl border border-slate-200" placeholder="Planned actions to minimize risk..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showAddRiskModal', false)" type="button" class="px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button wire:click="createRisk" type="button" class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27]">Log Risk</button>
                </div>
            </div>
        </div>
    @endif

</div>
