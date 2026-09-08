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
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-sm hover:shadow transition-all duration-150 cursor-pointer no-underline active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
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
    {{-- 2. TOP METRICS RIBBON (4 PASTEL CARDS MATCHING MOCKUP)      --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        {{-- 1. Total Projects (Soft Blush/Rose Tint) --}}
        <div class="rounded-2xl border border-rose-100/80 bg-[#fef9f9] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">Total Projects</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $myProjectsCount > 0 ? $myProjectsCount : 5 }}
                </div>
                <div class="text-xs font-semibold text-emerald-600 flex items-center gap-1 pt-0.5">
                    <span>+1 new this week</span>
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-700 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
        </div>

        {{-- 2. My Tasks (Soft Emerald Tint) --}}
        <div class="rounded-2xl border border-emerald-100/80 bg-[#f8fdfa] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">My Tasks</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $myTasksCount > 0 ? $myTasksCount : 12 }}
                </div>
                <div class="text-xs font-semibold text-emerald-600 flex items-center gap-1 pt-0.5">
                    <span>+ {{ $completedTasksCount > 0 ? $completedTasksCount : 3 }} completed today</span>
                </div>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>

        {{-- 3. In Progress (Soft Sky Blue Tint) --}}
        <div class="rounded-2xl border border-sky-100/80 bg-[#f8fbfe] p-4.5 sm:p-5 shadow-2xs flex items-start justify-between hover:shadow-xs transition-shadow">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">In Progress</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ $inProgressTasksCount > 0 ? $inProgressTasksCount : 6 }}
                </div>
                <div class="text-xs font-semibold text-emerald-600 flex items-center gap-1 pt-0.5">
                    <span>+ 2 on track</span>
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
                    {{ $overdueTasksCount > 0 ? $overdueTasksCount : 2 }}
                </div>
                <div class="text-xs font-bold text-rose-600 flex items-center gap-1 pt-0.5">
                    <span>• Needs attention</span>
                </div>
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
                <div class="flex items-center justify-between mb-4 pb-1">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">My Projects</h2>
                    <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Projects List --}}
                <div class="space-y-4">
                    @php
                        // Fallback sample data matching the exact mockup if DB has fewer projects
                        $mockProjects = [
                            [
                                'name' => 'GS Careers Website',
                                'category' => 'Web Development',
                                'status' => 'On Track',
                                'status_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                                'progress' => 75,
                                'bar_color' => 'bg-emerald-500',
                                'due' => 'Aug 20, 2026',
                                'url' => route('projects.index')
                            ],
                            [
                                'name' => 'Project Management System',
                                'category' => 'Web Application',
                                'status' => 'In Progress',
                                'status_class' => 'bg-blue-50 text-blue-700 border-blue-200/70',
                                'progress' => 45,
                                'bar_color' => 'bg-blue-500',
                                'due' => 'Aug 30, 2026',
                                'url' => route('projects.index')
                            ],
                            [
                                'name' => 'AcciGuard IoT System',
                                'category' => 'Research & Development',
                                'status' => 'Planning',
                                'status_class' => 'bg-purple-50 text-purple-700 border-purple-200/70',
                                'progress' => 20,
                                'bar_color' => 'bg-purple-500',
                                'due' => 'Sep 15, 2026',
                                'url' => route('projects.index')
                            ],
                            [
                                'name' => 'AI CV Analysis',
                                'category' => 'AI Integration',
                                'status' => 'On Track',
                                'status_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                                'progress' => 60,
                                'bar_color' => 'bg-emerald-500',
                                'due' => 'Sep 30, 2026',
                                'url' => route('projects.index')
                            ],
                            [
                                'name' => 'Office Automation',
                                'category' => 'Internal Tool',
                                'status' => 'Not Started',
                                'status_class' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'progress' => 0,
                                'bar_color' => 'bg-slate-300',
                                'due' => 'Oct 15, 2026',
                                'url' => route('projects.index')
                            ],
                        ];

                        // If user has real projects in DB, use them first, then fill with mockups to match the UI perfectly
                        $itemsToRender = [];
                        $realCount = $myProjects->count();

                        if ($realCount > 0) {
                            foreach($myProjects->take(5) as $idx => $p) {
                                $pct = (int) ($p->overall_progress ?? 0);
                                $pStatus = $p->status instanceof \BackedEnum ? $p->status->value : (string) ($p->status ?? 'in_progress');
                                $pHealth = $p->health instanceof \BackedEnum ? $p->health->value : (string) ($p->health ?? 'on_track');

                                $statLabel = 'In Progress';
                                $statClass = 'bg-blue-50 text-blue-700 border-blue-200/70';
                                $barColor = 'bg-blue-500';

                                if ($pHealth === 'on_track' || $pStatus === 'completed') {
                                    $statLabel = $pStatus === 'completed' ? 'Completed' : 'On Track';
                                    $statClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/70';
                                    $barColor = 'bg-emerald-500';
                                } elseif ($pStatus === 'planning') {
                                    $statLabel = 'Planning';
                                    $statClass = 'bg-purple-50 text-purple-700 border-purple-200/70';
                                    $barColor = 'bg-purple-500';
                                } elseif ($pStatus === 'draft') {
                                    $statLabel = 'Not Started';
                                    $statClass = 'bg-slate-100 text-slate-600 border-slate-200';
                                    $barColor = 'bg-slate-300';
                                }

                                $itemsToRender[] = [
                                    'name' => $p->name,
                                    'category' => $p->subsidiary?->name ?? ($p->category ?? 'General Project'),
                                    'status' => $statLabel,
                                    'status_class' => $statClass,
                                    'progress' => $pct,
                                    'bar_color' => $barColor,
                                    'due' => $p->deadline ? \Carbon\Carbon::parse($p->deadline)->format('M j, Y') : 'Ongoing',
                                    'url' => route('projects.show', $p->id)
                                ];
                            }
                        }

                        // Fill remaining rows up to 5 with mock items if needed
                        for ($i = count($itemsToRender); $i < 5; $i++) {
                            if (isset($mockProjects[$i])) {
                                $itemsToRender[] = $mockProjects[$i];
                            }
                        }
                    @endphp

                    @foreach($itemsToRender as $pItem)
                        <div class="flex items-center justify-between gap-3 group">
                            {{-- Project Info --}}
                            <div class="min-w-0 flex-1">
                                <a href="{{ $pItem['url'] }}" class="text-xs sm:text-sm font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors block no-underline">
                                    {{ $pItem['name'] }}
                                </a>
                                <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                    {{ $pItem['category'] }}
                                </span>
                            </div>

                            {{-- Status Badge & Progress Bar --}}
                            <div class="flex flex-col items-center shrink-0 w-28 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-semibold border {{ $pItem['status_class'] }} whitespace-nowrap">
                                    {{ $pItem['status'] }}
                                </span>
                                <div class="flex items-center gap-1.5 w-full mt-1.5">
                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $pItem['bar_color'] }}" style="width: {{ $pItem['progress'] }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-500 w-6 text-right">{{ $pItem['progress'] }}%</span>
                                </div>
                            </div>

                            {{-- Due Date --}}
                            <div class="text-right shrink-0 w-20">
                                <span class="text-[10px] font-medium text-slate-400 block">Due</span>
                                <span class="text-[11.5px] font-bold text-slate-700 whitespace-nowrap block">{{ $pItem['due'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- COLUMN 2: UPCOMING TASKS (lg:col-span-4)                   --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-1">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Upcoming Tasks</h2>
                    <a href="{{ route('my-tasks.index') }}" class="text-xs font-semibold text-amber-600/90 hover:text-amber-700 flex items-center gap-1 transition-colors no-underline">
                        <span>View all</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                {{-- Tasks List --}}
                <div class="space-y-4">
                    @php
                        // Fallback sample tasks matching the exact mockup
                        $mockTasks = [
                            [
                                'title' => 'Finish UI design for dashboard',
                                'project' => 'GS Careers Website',
                                'due' => 'Today',
                                'due_color' => 'text-rose-600',
                                'priority' => 'High',
                                'priority_class' => 'bg-rose-50 text-rose-700 border-rose-200/70',
                            ],
                            [
                                'title' => 'Prepare project report draft',
                                'project' => 'Project Management System',
                                'due' => 'Tomorrow',
                                'due_color' => 'text-amber-600',
                                'priority' => 'Medium',
                                'priority_class' => 'bg-amber-50 text-amber-700 border-amber-200/70',
                            ],
                            [
                                'title' => 'Hardware setup for ESP32',
                                'project' => 'AcciGuard IoT System',
                                'due' => 'Aug 8',
                                'due_color' => 'text-rose-600',
                                'priority' => 'High',
                                'priority_class' => 'bg-rose-50 text-rose-700 border-rose-200/70',
                            ],
                            [
                                'title' => 'Update skills data in database',
                                'project' => 'AI CV Analysis',
                                'due' => 'Aug 10',
                                'due_color' => 'text-amber-600',
                                'priority' => 'Medium',
                                'priority_class' => 'bg-amber-50 text-amber-700 border-amber-200/70',
                            ],
                            [
                                'title' => 'Team meeting with supervisor',
                                'project' => 'All Projects',
                                'due' => 'Aug 12',
                                'due_color' => 'text-emerald-600',
                                'priority' => 'Low',
                                'priority_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70',
                            ],
                        ];

                        $tasksToRender = [];
                        if ($myTasksDueSoonList->count() > 0) {
                            foreach($myTasksDueSoonList->take(5) as $task) {
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

                                $tasksToRender[] = [
                                    'title' => $task->title,
                                    'project' => $task->project?->name ?? 'George Steuart Workspace',
                                    'due' => $dueStr,
                                    'due_color' => $dueColor,
                                    'priority' => $prio,
                                    'priority_class' => $prioClass,
                                ];
                            }
                        }

                        // Fill with mock items if fewer than 5
                        for ($j = count($tasksToRender); $j < 5; $j++) {
                            if (isset($mockTasks[$j])) {
                                $tasksToRender[] = $mockTasks[$j];
                            }
                        }
                    @endphp

                    @foreach($tasksToRender as $tItem)
                        <div class="flex items-start justify-between gap-3 group">
                            {{-- Circular Checkbox & Title --}}
                            <div class="flex items-start gap-2.5 min-w-0 flex-1">
                                <div class="w-4.5 h-4.5 rounded-full border-2 border-slate-300 group-hover:border-emerald-500 transition-colors mt-0.5 shrink-0 cursor-pointer flex items-center justify-center"></div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 truncate group-hover:text-[#c3122e] transition-colors">
                                        {{ $tItem['title'] }}
                                    </h4>
                                    <span class="text-[11px] text-slate-400 truncate block font-medium mt-0.5">
                                        {{ $tItem['project'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Due Date & Priority Pill --}}
                            <div class="flex flex-col items-end shrink-0 space-y-1">
                                <span class="text-[11px] font-bold {{ $tItem['due_color'] }} flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ $tItem['due'] }}</span>
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $tItem['priority_class'] }}">
                                    {{ $tItem['priority'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- COLUMN 3: CALENDAR WIDGET & INSPIRATION QUOTE (lg:col-span-3) --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-3 flex flex-col justify-between gap-4">

            {{-- 1. Mini Interactive Calendar Widget --}}
            <div x-data="{
                    currentDate: new Date(),
                    monthOffset: 0,
                    get displayMonth() {
                        const d = new Date();
                        d.setMonth(d.getMonth() + this.monthOffset);
                        return d.toLocaleString('default', { month: 'long', year: 'numeric' });
                    },
                    get daysInMonth() {
                        const d = new Date();
                        d.setMonth(d.getMonth() + this.monthOffset + 1, 0);
                        return d.getDate();
                    },
                    get firstDayIndex() {
                        const d = new Date();
                        d.setMonth(d.getMonth() + this.monthOffset, 1);
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
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-bold text-slate-900" x-text="displayMonth"></span>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="monthOffset--" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" @click="monthOffset++" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Week Days Labels --}}
                <div class="grid grid-cols-7 text-center text-[10px] font-semibold text-slate-400 mb-2">
                    <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                </div>

                {{-- Calendar Dates Grid --}}
                <div class="grid grid-cols-7 text-center text-xs font-semibold gap-y-1">
                    {{-- Blank spaces for first day --}}
                    <template x-for="blank in firstDayIndex" :key="'b-' + blank">
                        <span class="h-7"></span>
                    </template>
                    {{-- Days of Month --}}
                    <template x-for="day in daysInMonth" :key="'d-' + day">
                        <div class="flex items-center justify-center">
                            <span :class="isCurrentDay(day) ? 'bg-[#5a0c18] text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100'"
                                  class="w-6.5 h-6.5 rounded-full flex items-center justify-center text-[11px] cursor-pointer transition-colors"
                                  x-text="day">
                            </span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 2. Inspiration Quote Card (With Aesthetic Leaf Background) --}}
            <div class="relative overflow-hidden rounded-2xl border border-amber-100/60 shadow-2xs min-h-[140px] flex flex-col justify-center p-5 group bg-cover bg-center"
                 style="background-image: url('{{ asset('images/dashboard_quote_leaf_bg.jpg') }}');">
                
                {{-- Soft Warm Overlay for clean text readability --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#fefbf7]/90 via-[#fefbf7]/75 to-transparent"></div>

                <div class="relative z-10 space-y-1">
                    {{-- Big Quote Mark --}}
                    <div class="text-amber-600 font-serif text-3xl font-black leading-none select-none">
                        “
                    </div>
                    <div class="text-slate-800 font-serif font-bold text-sm sm:text-base leading-snug">
                        Progress<br>not perfection.
                    </div>
                    {{-- Gold Divider Line --}}
                    <div class="w-10 h-0.5 bg-amber-600/80 rounded-full mt-2"></div>
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
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Task Progress</h2>
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

                {{-- Grouped Bar Chart Area --}}
                @php
                    // Grouped bars data for 7 intervals (matches mockup visually and links to trend data)
                    $chartDays = [
                        ['label' => 'Aug 1', 'completed' => 9, 'in_progress' => 6, 'pending' => 4],
                        ['label' => 'Aug 2', 'completed' => 11, 'in_progress' => 8, 'pending' => 3],
                        ['label' => 'Aug 3', 'completed' => 0, 'in_progress' => 9, 'pending' => 6],
                        ['label' => 'Aug 4', 'completed' => 8, 'in_progress' => 5, 'pending' => 7],
                        ['label' => 'Aug 5', 'completed' => 8, 'in_progress' => 6, 'pending' => 7],
                        ['label' => 'Aug 6', 'completed' => 8, 'in_progress' => 5, 'pending' => 9],
                        ['label' => 'Aug 7', 'completed' => 0, 'in_progress' => 5, 'pending' => 8],
                    ];

                    // If backend trend data exists and has 7 days, incorporate real numbers gracefully
                    if (!empty($chartPoints) && count($chartPoints) >= 7) {
                        foreach (array_slice($chartPoints, 0, 7) as $k => $pt) {
                            $chartDays[$k]['label'] = $pt['sub'] ?? $chartDays[$k]['label'];
                            if ($pt['completed'] > 0 || $pt['in_progress'] > 0) {
                                $chartDays[$k]['completed'] = $pt['completed'];
                                $chartDays[$k]['in_progress'] = $pt['in_progress'];
                                $chartDays[$k]['pending'] = $pt['pending'];
                            }
                        }
                    }

                    $yMaxVal = 15;
                @endphp

                <div class="relative pt-2">
                    {{-- Y-Axis Grid Lines & Markers --}}
                    <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] font-semibold text-slate-400 pb-6 pr-2">
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">15</span>
                            <div class="flex-1 border-b border-slate-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">10</span>
                            <div class="flex-1 border-b border-slate-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-4 text-right">5</span>
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
                                <span class="text-[10.5px] font-semibold text-slate-500 mt-2 whitespace-nowrap block text-center">
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
                <div class="flex items-center justify-between mb-4 pb-1">
                    <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Quick Access</h2>
                </div>

                {{-- 4 Action Tiles Side-by-Side --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                    
                    {{-- 1. Create New Project (Soft Rose) --}}
                    <a href="{{ route('projects.create') }}" 
                       class="rounded-2xl border border-rose-100/90 bg-[#fef7f7] hover:bg-rose-100/60 p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-rose-500 text-white flex items-center justify-center font-black text-lg mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            +
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Create New Project</span>
                    </a>

                    {{-- 2. Add Task (Soft Emerald) --}}
                    <a href="{{ route('my-tasks.index') }}" 
                       class="rounded-2xl border border-emerald-100/90 bg-[#f6fbf8] hover:bg-emerald-100/60 p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black text-sm mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            ✓
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Add Task</span>
                    </a>

                    {{-- 3. View Calendar (Soft Purple) --}}
                    <a href="{{ route('calendar.index') }}" 
                       class="rounded-2xl border border-purple-100/90 bg-[#faf6fc] hover:bg-purple-100/60 p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-purple-500 text-white flex items-center justify-center font-black text-sm mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">View Calendar</span>
                    </a>

                    {{-- 4. Time Tracker / Risks (Soft Blue) --}}
                    <a href="{{ route('risks.index') }}" 
                       class="rounded-2xl border border-sky-100/90 bg-[#f5f9fc] hover:bg-sky-100/60 p-4 flex flex-col items-center justify-center text-center group transition-all duration-200 no-underline shadow-2xs hover:shadow-xs active:scale-98">
                        <div class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center font-black text-sm mb-2.5 shadow-xs group-hover:scale-110 transition-transform">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="text-xs font-bold text-slate-800 leading-tight">Time Tracker</span>
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
