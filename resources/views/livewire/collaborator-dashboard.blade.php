{{-- ============================================================
     COLLABORATOR / TEAM MEMBER DASHBOARD — GS NexusPM
     ============================================================ --}}
<div class="space-y-6 pb-12 font-sans">

    {{-- ═══════════════════ PENDING PM ASSIGNMENT INVITATIONS ═══════════════════ --}}
    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
        <div class="mb-6 rounded-2xl border border-rose-200/90 bg-rose-50/40 p-4 shadow-2xs flex items-center justify-between gap-3 flex-wrap transition-all">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-100 text-[#c3122e] flex items-center justify-center text-sm shrink-0 font-bold">
                    👑
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">Project Leadership Assignment Required ({{ $pendingInvitations->count() }})</h3>
                    <p class="text-xs text-slate-500 font-medium">PMO Admin has designated you as Project Manager. Accept to confirm leadership.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @foreach($pendingInvitations as $inv)
                    <button 
                        wire:click="acceptProjectAssignment({{ $inv->id }})"
                        type="button" 
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] cursor-pointer transition-colors shadow-2xs"
                    >
                        Accept {{ $inv->code }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }} <span class="inline-block hover:scale-110 transition-transform cursor-default select-none">👋</span>
            </h1>
            <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('approvals.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-2xs hover:shadow-xs transition-all flex items-center gap-1.5 cursor-pointer no-underline active:scale-98">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Submit Approval</span>
            </a>
            
            <a href="{{ route('calendar.index') }}" class="px-3.5 py-2 rounded-xl font-bold text-xs text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:shadow-xs transition-all flex items-center gap-1.5 cursor-pointer no-underline">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Calendar</span>
            </a>

            <a href="{{ route('my-tasks.index') }}" class="px-3.5 py-2 rounded-xl font-bold text-xs text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:shadow-xs transition-all flex items-center gap-1.5 cursor-pointer no-underline">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>My Tasks</span>
            </a>
        </div>
    </div>

    <!-- 4 Minimalist Metric Stat Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 mb-6">
        {{-- Metric 1: Projects --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">PROJECTS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono leading-none">{{ $attachedProjects->count() }}</div>
        </div>

        {{-- Metric 2: Tasks --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">TASKS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono leading-none">{{ $totalCount }}</div>
        </div>

        {{-- Metric 3: In Progress --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">IN PROGRESS</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono leading-none">{{ $inProgress->count() }}</div>
        </div>

        {{-- Metric 4: Completion --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs hover:border-slate-300 transition-all duration-200">
            <div class="flex items-center justify-between gap-1 mb-3">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block truncate">COMPLETION</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-mono leading-none">{{ $completionPct }}%</div>
        </div>
    </div>

    {{-- ═══════════════════ 2. COLLABORATING PROJECTS SECTION ═══════════════════ --}}
    <div class="space-y-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between gap-4 pb-1 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Collaborating Projects</h2>
                    <p class="text-xs text-slate-400 font-medium">Projects assigned to you</p>
                </div>
            </div>

            <a href="{{ route('projects.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                <span>View all</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Projects Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($attachedProjects as $prj)
                @php
                    $pct = (int) $prj->overall_progress;
                    $pm = $prj->projectManager;
                    $pStatus = $prj->status instanceof \BackedEnum ? $prj->status->value : (string) ($prj->status ?? 'in_progress');
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
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-all flex flex-col justify-between space-y-4">
                    
                    {{-- Card Top Row: Code Badge & Status Pill --}}
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $prj->code ?? 'PRJ' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[9.5px] font-bold {{ $prj->status->badgeClass() }} border">
                                <span class="w-1.5 h-1.5 rounded-full {{ $prj->status->badgeDotClass() }}"></span>
                                <span>{{ $prj->status->label() }}</span>
                            </span>
                        </div>

                        {{-- Project Name --}}
                        <h3 class="text-sm font-bold text-slate-900 mt-3 truncate" title="{{ $prj->name }}">
                            {{ $prj->name }}
                        </h3>

                        {{-- PM Info --}}
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-[9px] shrink-0">
                                {{ strtoupper(substr($pm->name ?? 'P', 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-400 font-medium truncate">
                                PM: <strong class="text-slate-700 font-semibold">{{ $pm->name ?? 'Unassigned' }}</strong>
                            </span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="space-y-1 pt-1">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-extrabold uppercase tracking-wider text-slate-400">PROGRESS</span>
                            <span class="font-bold text-xs text-slate-700 font-mono">{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-200/60 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $barColor }}" style="width: {{ $pct }}%;"></div>
                        </div>
                    </div>

                    {{-- Bottom Row: Deadline & Open Button --}}
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div>
                            <span class="text-[9px] font-extrabold uppercase tracking-wider text-slate-400 block">DEADLINE</span>
                            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-600 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $prj->deadline ? $prj->deadline->format('d M Y') : 'Not Set' }}</span>
                            </div>
                        </div>

                        <a href="{{ route('projects.show', $prj->id) }}" wire:navigate.hover class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-[#c3122e] hover:text-white border border-slate-200 transition-all duration-150 flex items-center gap-1 no-underline">
                            <span>Open</span>
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-10 text-center space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto border border-slate-200/60">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="font-bold text-xs text-slate-800">No Active Collaborations</h3>
                    <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">When a Project Manager attaches you to a project, it will automatically appear here.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════ 3. ASSIGNED TASKS WORKSPACE SECTION ═══════════════════ --}}
    <div class="space-y-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between gap-4 pb-1 border-b border-slate-100 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-slate-100/80 text-slate-600 border border-slate-200/60 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Assigned Tasks Workspace</h2>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            {{ $myTasks->count() }} Tasks
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Update completion percentages, statuses, and log sub-task deliverables</p>
                </div>
            </div>

            <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-400 hover:text-slate-800 flex items-center gap-1 transition-colors no-underline">
                <span>Full Task Board</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Tasks List --}}
        <div class="space-y-3">
            @forelse($myTasks as $task)
                @php
                    $isOverdue = $task->end_date && $task->end_date->isPast() && $task->status->value !== 'completed';
                    $statusStyles = match($task->status->value ?? 'not_started') {
                        'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                        'blocked'      => 'bg-amber-50 text-amber-800 border-amber-200/60',
                        'in_progress'  => 'bg-blue-50 text-blue-700 border-blue-200/60',
                        'under_review' => 'bg-sky-50 text-sky-700 border-sky-200/60',
                        default        => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                @endphp
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs space-y-3 hover:border-slate-300 transition-all">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        {{-- Left Side: Code, Title, Badges & Meta --}}
                        <div class="flex items-start gap-3 min-w-0">
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-mono text-xs font-bold shrink-0 mt-0.5">
                                {{ $task->wbs_code ?? '1.1' }}
                            </span>
                            
                            <div class="min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">
                                        {{ $task->title }}
                                    </h4>
                                    
                                    {{-- Status Badge --}}
                                    <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold border {{ $statusStyles }} flex items-center gap-1">
                                        {{ $task->status->label() }}
                                    </span>

                                    {{-- Overdue Badge if applicable --}}
                                    @if($isOverdue)
                                        <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60 flex items-center gap-1">
                                            ⚠ Overdue
                                        </span>
                                    @endif
                                </div>

                                {{-- Meta Row --}}
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium flex-wrap">
                                    @if($task->end_date)
                                        <span class="flex items-center gap-1 font-semibold {{ $isOverdue ? 'text-rose-600' : 'text-slate-600' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $task->end_date->format('d M Y') }}</span>
                                            @if($isOverdue)
                                                <span class="text-rose-500 font-bold">• {{ $task->end_date->diffForHumans() }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    <span>|</span>
                                    <span>PM: <strong class="text-slate-600 font-semibold">{{ $task->project->projectManager->name ?? 'Unassigned' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Controls (Status Select, + Sub-Task, Range Slider) --}}
                        <div class="flex items-center gap-3 flex-wrap lg:justify-end shrink-0">
                            
                            {{-- Status Dropdown Pill --}}
                            @php
                                $stVal = $task->status->value ?? (string) $task->status;
                                $stStyle = match($stVal) {
                                    'in_progress'  => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200/90', 'dot' => 'bg-blue-500', 'style' => 'background-color: #eff6ff !important; color: #1d4ed8 !important; border-color: #bfdbfe !important;'],
                                    'completed'    => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/90', 'dot' => 'bg-emerald-500', 'style' => 'background-color: #ecfdf5 !important; color: #047857 !important; border-color: #a7f3d0 !important;'],
                                    'at_risk'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                                    'under_review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200/90', 'dot' => 'bg-purple-500', 'style' => 'background-color: #faf5ff !important; color: #6b21a8 !important; border-color: #e9d5ff !important;'],
                                    'blocked'      => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200/90', 'dot' => 'bg-rose-500', 'style' => 'background-color: #fff1f2 !important; color: #be123c !important; border-color: #fecdd3 !important;'],
                                    'on_hold'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                                    default        => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200/90', 'dot' => 'bg-slate-400', 'style' => 'background-color: #f1f5f9 !important; color: #334155 !important; border-color: #cbd5e1 !important;'],
                                };
                            @endphp
                            <div class="relative flex items-center shrink-0">
                                <span class="pointer-events-none absolute left-3 w-1.5 h-1.5 rounded-full z-10 {{ $stStyle['dot'] }}"></span>
                                <select 
                                    wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-bold py-1.5 pl-7 pr-7 rounded-xl border focus:outline-none focus:ring-2 focus:ring-slate-300/30 transition-all appearance-none cursor-pointer shadow-2xs"
                                    style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; {{ $stStyle['style'] }}"
                                >
                                    @foreach(\App\Enums\WbsStatus::selectableCases() as $st)
                                        <option value="{{ $st->value }}" @selected($stVal === $st->value) class="bg-white text-slate-900 font-semibold">{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center text-current opacity-70">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            {{-- Sub-Task Button --}}
                            <button 
                                wire:click="openSubTaskModal({{ $task->id }})"
                                type="button" 
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all flex items-center gap-1 cursor-pointer"
                            >
                                <span>+ Sub-Task</span>
                            </button>

                            {{-- Progress Range Slider --}}
                            <div class="flex items-center gap-2 bg-slate-50/80 border border-slate-200/70 rounded-xl px-3 py-1.5 shadow-2xs">
                                <input 
                                    type="range" 
                                    min="0" 
                                    max="100" 
                                    step="5"
                                    value="{{ $task->progress }}"
                                    wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                    class="w-20 accent-slate-700 cursor-pointer"
                                >
                                <span class="text-xs font-mono font-bold text-slate-800 min-w-[32px] text-right">
                                    {{ $task->progress }}%
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- Nested Sub-tasks if existing --}}
                    @if($task->children && $task->children->count() > 0)
                        <div class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                <span>Sub-Tasks ({{ $task->children->count() }})</span>
                                @php $doneCount = $task->children->filter(fn($s) => $s->status->value === 'completed')->count(); @endphp
                                <span class="text-emerald-600 font-extrabold">{{ $doneCount }}/{{ $task->children->count() }} Completed</span>
                            </div>

                            <div class="space-y-1.5 pl-3 border-l-2 border-slate-100">
                                @foreach($task->children as $sub)
                                    @php
                                        $subStVal = $sub->status->value ?? (string) $sub->status;
                                        $subStStyle = match($subStVal) {
                                            'in_progress'  => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200/90', 'dot' => 'bg-blue-500', 'style' => 'background-color: #eff6ff !important; color: #1d4ed8 !important; border-color: #bfdbfe !important;'],
                                            'completed'    => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200/90', 'dot' => 'bg-emerald-500', 'style' => 'background-color: #ecfdf5 !important; color: #047857 !important; border-color: #a7f3d0 !important;'],
                                            'at_risk'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                                            'under_review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200/90', 'dot' => 'bg-purple-500', 'style' => 'background-color: #faf5ff !important; color: #6b21a8 !important; border-color: #e9d5ff !important;'],
                                            'blocked'      => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200/90', 'dot' => 'bg-rose-500', 'style' => 'background-color: #fff1f2 !important; color: #be123c !important; border-color: #fecdd3 !important;'],
                                            'on_hold'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/90', 'dot' => 'bg-amber-500', 'style' => 'background-color: #fffbeb !important; color: #b45309 !important; border-color: #fde68a !important;'],
                                            default        => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200/90', 'dot' => 'bg-slate-400', 'style' => 'background-color: #f1f5f9 !important; color: #334155 !important; border-color: #cbd5e1 !important;'],
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between gap-3 p-2 rounded-xl bg-slate-50/80 border border-slate-100">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-mono font-bold text-slate-700 bg-slate-200/70 border border-slate-200">
                                                {{ $sub->wbs_code }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-800 truncate {{ $subStVal === 'completed' ? 'line-through text-slate-400' : '' }}">
                                                {{ $sub->title }}
                                            </span>
                                        </div>

                                        <div class="relative shrink-0 flex items-center">
                                            <span class="pointer-events-none absolute left-2 w-1.5 h-1.5 rounded-full z-10 {{ $subStStyle['dot'] }}"></span>
                                            <select 
                                                wire:change="updateTaskStatus({{ $sub->id }}, $event.target.value)"
                                                class="text-[10px] font-bold py-1 pl-5 pr-6 rounded-lg border outline-none appearance-none cursor-pointer"
                                                style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; {{ $subStStyle['style'] }}"
                                            >
                                                @foreach(\App\Enums\WbsStatus::selectableCases() as $st)
                                                    <option value="{{ $st->value }}" @selected($subStVal === $st->value) class="bg-white text-slate-900 font-semibold">{{ $st->label() }}</option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-1.5 flex items-center text-current opacity-70">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/80 p-10 text-center space-y-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto border border-slate-200/60">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="font-bold text-xs text-slate-800">No Assigned Tasks</h3>
                    <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">When your Project Manager assigns WBS tasks to you, they will appear here with interactive progress sliders.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════ SUB-TASK MODAL ═══════════════════ --}}
    @if($showSubTaskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm overflow-y-auto">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200/90 w-full max-w-md p-6 space-y-4 my-8">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-bold shrink-0 border border-slate-200/60">
                            📋
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 leading-tight">Create Sub-Task</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Break down your assigned task</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showSubTaskModal', false)" 
                        type="button" 
                        class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createSubTask" class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Sub-Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="subTaskTitle" required placeholder="e.g. Prepare API payload schema" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 outline-none transition-all text-slate-800">
                        @error('subTaskTitle') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Description</label>
                        <textarea wire:model="subTaskDescription" rows="2" placeholder="Details or notes..." class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 focus:ring-2 focus:ring-slate-400/20 outline-none transition-all text-slate-800"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 outline-none text-slate-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">End Date</label>
                            <input type="date" wire:model="subTaskEndDate" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 outline-none text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Priority</label>
                            <select wire:model="subTaskPriority" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 outline-none text-slate-800">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Est. Hours</label>
                            <input type="number" step="0.5" wire:model="subTaskEstimatedHours" placeholder="e.g. 4.5" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-slate-400 outline-none text-slate-800">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSubTaskModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a80f27] shadow-2xs transition-all cursor-pointer">
                            Save Sub-Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
</div>
