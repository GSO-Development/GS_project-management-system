<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Executive Dashboard</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Organization-wide portfolio, day-by-day project status monitoring, and governance overview</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('daily-updates.index') }}" class="btn-secondary text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Daily Updates Feed
            </a>
            <a href="{{ route('projects.create') }}" class="btn-primary text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Project
            </a>
        </div>
    </div>

    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-6">
        <a href="{{ route('projects.index') }}" class="card p-4 hover:border-[#f0a0ab] transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Projects</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors">{{ $totalProjects }}</span>
                <span class="badge-gs text-[10px]">All</span>
            </div>
        </a>

        <a href="{{ route('projects.index', ['statusFilter' => 'in_progress']) }}" class="card p-4 hover:border-[#f0a0ab] transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Active</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-[#c3122e]">{{ $activeProjects }}</span>
                <span class="badge-gs text-[10px]">Running</span>
            </div>
        </a>

        <a href="{{ route('projects.index', ['statusFilter' => 'completed']) }}" class="card p-4 hover:border-emerald-300 transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Completed</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-emerald-600">{{ $completedProjects }}</span>
                <span class="badge-emerald text-[10px]">Done</span>
            </div>
        </a>

        <a href="{{ route('projects.index', ['healthFilter' => 'delayed']) }}" class="card p-4 hover:border-rose-300 transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Overdue</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-rose-600">{{ $overdueProjects }}</span>
                <span class="badge-rose text-[10px]">Critical</span>
            </div>
        </a>

        <a href="{{ route('projects.index', ['statusFilter' => 'on_hold']) }}" class="card p-4 hover:border-amber-300 transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">On Hold</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-amber-600">{{ $onHoldProjects }}</span>
                <span class="badge-amber text-[10px]">Paused</span>
            </div>
        </a>

        <a href="{{ route('subsidiaries.index') }}" class="card p-4 hover:border-sky-300 transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Subsidiaries</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-[#b8860b]">{{ $totalSubsidiaries }}</span>
                <span class="badge-cyan text-[10px]">Entities</span>
            </div>
        </a>

        <a href="{{ route('users.index') }}" class="card p-4 hover:border-slate-400 transition-all group">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Users</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-slate-800">{{ $totalUsers }}</span>
                <span class="badge-slate text-[10px]">Staff</span>
            </div>
        </a>

        <a href="{{ route('approvals.index') }}" class="card p-4 border-amber-200 bg-amber-50/40 hover:bg-amber-50/70 transition-all group">
            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Approvals</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-2xl font-bold text-amber-700">{{ $pendingApprovals }}</span>
                <span class="badge-amber text-[10px]">Action Req</span>
            </div>
        </a>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- DAILY PROJECTS STATUS TRACKER ════════════════════════════ --}}
    <div class="card p-6 mb-6 border border-slate-200/80 rounded-2xl bg-white shadow-xs overflow-hidden relative">
        <!-- Decorative brand accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-rose-500 to-[#b8860b]"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-50 to-rose-100/80 border border-rose-200/80 text-[#c3122e] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Active Projects Day-by-Day Status Tracker</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Real-time daily project status &amp; work completed updates submitted by Project Managers</p>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/80 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    Most Recent Project Update
                </span>
                <a href="{{ route('daily-updates.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] transition-all cursor-pointer shadow-xs flex items-center gap-1.5 active:scale-[0.98]">
                    <span>View All Updates</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200/80 bg-white shadow-2xs">
            <table class="w-full text-left border-collapse min-w-[980px]">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-5 font-extrabold w-[30%]">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>Project &amp; Manager</span>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 text-center font-extrabold w-[13%]">Status</th>
                        <th class="py-3.5 px-4 font-extrabold w-[23%]">Execution Progress</th>
                        <th class="py-3.5 px-4 font-extrabold w-[24%]">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                <span>Latest PM Daily Status Log</span>
                            </div>
                        </th>
                        <th class="py-3.5 px-5 text-right font-extrabold w-[10%]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($dailyProjectTracker as $item)
                        @php
                            $proj = $item['project'];
                            $latest = $item['latestUpdate'];
                            $pmName = $proj->projectManager->name ?? 'Unassigned';
                            $pmInitial = strtoupper(substr($pmName, 0, 1));
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group align-middle">
                            {{-- 1. Project & Manager --}}
                            <td class="py-4 px-5 align-middle">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-900 text-white font-mono text-[10px] font-bold tracking-wider shadow-2xs">
                                            {{ $proj->code }}
                                        </span>
                                        @if($item['isUpdatedToday'])
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-2xs">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                </span>
                                                PM Updated Today
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('projects.show', $proj) }}" class="font-extrabold text-slate-900 group-hover:text-[#c3122e] text-sm leading-snug block transition-colors">
                                        {{ $proj->name }}
                                    </a>

                                    <div class="flex items-center gap-2.5 text-[11px] text-slate-500 font-medium flex-wrap pt-0.5">
                                        <span class="inline-flex items-center gap-1.5 text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/60">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0V9m0 0h-2m2 0h2"/></svg>
                                            <span class="font-semibold text-slate-700">{{ $proj->subsidiary->name ?? '-' }}</span>
                                        </span>
                                        <span class="text-slate-300">•</span>
                                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                                            <span class="w-5 h-5 rounded-full bg-slate-800 text-white text-[10px] font-extrabold flex items-center justify-center flex-shrink-0 shadow-2xs">{{ $pmInitial }}</span>
                                            <span class="font-semibold text-slate-700">{{ $pmName }}</span>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Status --}}
                            <td class="py-4 px-4 align-middle text-center">
                                @if($proj->status->value === 'in_progress')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        In Progress
                                    </span>
                                @elseif($proj->status->value === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Completed
                                    </span>
                                @elseif($proj->status->value === 'on_hold')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-800 border border-orange-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        On Hold
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-800 border border-sky-200/80 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                        {{ $proj->status->label() }}
                                    </span>
                                @endif
                            </td>

                            {{-- 3. Execution Progress --}}
                            <td class="py-4 px-4 align-middle">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-extrabold text-slate-600">Progress</span>
                                        <span class="text-slate-900 font-extrabold text-xs bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/80">{{ $proj->overall_progress }}%</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60 p-[1px]">
                                        <div class="h-full bg-gradient-to-r from-[#c3122e] via-rose-500 to-rose-600 rounded-full transition-all duration-500" style="width: {{ $proj->overall_progress }}%"></div>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ $item['completedTasks'] }} Done
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md {{ $item['blockedTasks'] > 0 ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-50 text-slate-500 border border-slate-200/40' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $item['blockedTasks'] > 0 ? 'bg-rose-500' : 'bg-slate-300' }}"></span>
                                            {{ $item['blockedTasks'] }} Blocked
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md {{ $item['overdueTasks'] > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-slate-50 text-slate-500 border border-slate-200/40' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $item['overdueTasks'] > 0 ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
                                            {{ $item['overdueTasks'] }} Overdue
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 4. Latest PM Daily Status Log --}}
                            <td class="py-4 px-4 align-middle">
                                @if($latest)
                                    <div class="p-3 rounded-xl bg-slate-50/80 hover:bg-slate-100/90 border border-slate-200/80 shadow-2xs space-y-2 transition-all">
                                        <div class="flex items-center justify-between gap-2 text-[11px] font-bold">
                                            <span class="text-[#c3122e] font-extrabold truncate max-w-[180px] inline-flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                                <span class="truncate">{{ $latest->title }}</span>
                                            </span>
                                            <span class="text-slate-400 font-mono text-[10px] font-normal flex-shrink-0">{{ $latest->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="border-l-2 border-[#c3122e]/60 pl-2.5 text-xs text-slate-700 font-medium italic line-clamp-2 leading-relaxed bg-white/80 py-1.5 px-2 rounded-r-lg border border-slate-200/40">
                                            "{{ $latest->summary }}"
                                        </div>
                                        @if($latest->creator)
                                            <div class="text-[10px] text-slate-500 pt-1.5 border-t border-slate-200/60 flex items-center justify-between font-medium">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    Logged by <strong class="text-slate-700 font-semibold">{{ $latest->creator->name }}</strong>
                                                </span>
                                                <span class="font-mono text-[9px] text-slate-400">{{ $latest->created_at->format('h:i A') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="p-3 rounded-xl bg-slate-50/50 border border-dashed border-slate-200 text-center flex items-center justify-center gap-2 text-slate-400 text-xs font-medium py-3.5">
                                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                        <span>No status updates logged yet</span>
                                    </div>
                                @endif
                            </td>

                            {{-- 5. Action --}}
                            <td class="py-4 px-5 align-middle text-right whitespace-nowrap">
                                <a href="{{ route('projects.show', $proj) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-[#c3122e] hover:text-white border border-slate-200 hover:border-[#c3122e] shadow-2xs transition-all duration-200 group/btn">
                                    <span>Workspace</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover/btn:text-white group-hover/btn:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs font-medium">
                                No active corporate projects currently monitored.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-end">
            <a href="{{ route('daily-updates.index') }}" class="text-xs font-bold text-[#c3122e] hover:text-[#a00e24] inline-flex items-center gap-1.5 transition-colors group">
                <span>View All Daily Status Logs &amp; Updates</span>
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>

    <!-- Main Grid: Charts & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Projects by Subsidiary -->
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base">Projects by Subsidiary</h3>
                <a href="{{ route('subsidiaries.index') }}" class="text-xs font-semibold text-[#c3122e] hover:text-[#a00e24]">View subsidiaries &rarr;</a>
            </div>
            <div class="space-y-4">
                @foreach($projectsBySubsidiary as $sub)
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $sub->name }}</span>
                                <span class="badge-slate text-[10px]">{{ $sub->code }}</span>
                            </div>
                            <span class="text-xs text-slate-500 font-mono font-semibold">{{ $sub->projects_count }} Projects</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill bg-[#c3122e]"
                                 style="width: {{ $totalProjects > 0 ? min(100, ($sub->projects_count / $totalProjects) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pending Approval Requests Widget with Direct Actions -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base">Pending Approvals</h3>
                <a href="{{ route('approvals.index') }}" class="text-xs font-semibold text-[#c3122e] hover:text-[#a00e24]">Manage all &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingApprovalList as $app)
                    <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200/80 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="badge-amber text-[10px] font-bold">{{ $app->request_type->label() }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $app->created_at->diffForHumans() }}</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 truncate">{{ $app->project->name ?? 'Project' }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">Requested by <span class="font-medium text-slate-700">{{ $app->requester->name }}</span></p>
                        </div>
                        <!-- Direct Action Buttons -->
                        <div class="flex items-center gap-2 mt-1">
                            <button wire:click="approveRequest({{ $app->id }})" class="btn-success btn-sm flex-1 text-[11px] py-1">Approve</button>
                            <button wire:click="rejectRequest({{ $app->id }})" class="btn-danger btn-sm flex-1 text-[11px] py-1">Reject</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No pending approval requests requiring action</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Incomplete Task Issues & Delay Reasons Widget -->
    @if($delayedTaskIssues->count() > 0)
        <div class="card mb-6 border-amber-200 bg-amber-50/30 p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-amber-200/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-700 flex-shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Incomplete Task Issues &amp; Delay Reasons</h3>
                        <p class="text-[10px] text-amber-700 font-medium">Daily reported task issues &amp; delay reasons from Project Managers and Team Members</p>
                    </div>
                </div>
                <a href="{{ route('my-tasks.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline">View All Tasks &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($delayedTaskIssues as $item)
                    @php
                        $reporter = $item->delayReporter ?? $item->assignedUser;
                        $roleLabel = $reporter?->hasRole('project_manager') ? 'Project Manager' : ($reporter?->hasRole('super_admin') ? 'Super Admin' : 'Team Member');
                        $roleBadgeCls = $reporter?->hasRole('project_manager') ? 'bg-purple-100 text-purple-800 border-purple-200' : 'bg-blue-100 text-blue-800 border-blue-200';
                    @endphp
                    <div class="p-3.5 rounded-xl bg-white border border-amber-200 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="text-xs font-bold text-slate-900 truncate max-w-44" title="{{ $item->title }}">{{ $item->title }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold border {{ $roleBadgeCls }}">
                                    {{ $roleLabel }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium mb-2">
                                Project: <strong class="text-slate-700">{{ $item->project->name ?? '-' }}</strong>
                            </p>
                            <p class="text-xs text-slate-800 font-medium bg-amber-50/80 p-2.5 rounded-lg border border-amber-200/60 leading-relaxed italic">
                                "{{ $item->delay_reason }}"
                            </p>
                        </div>
                        <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span>By <strong>{{ $reporter->name ?? 'User' }}</strong></span>
                            <span>{{ $item->delay_reason_at?->diffForHumans() ?? 'Recently' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Bottom Grid: Activity Stream & Recent Documents -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Status Updates Stream -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base">Recent Status Updates Stream</h3>
                <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-[#c3122e] hover:text-[#a00e24]">All Projects &rarr;</a>
            </div>
            <div class="space-y-4">
                @forelse($recentUpdates as $update)
                    <div class="flex items-start gap-3 border-b border-slate-100 pb-3.5 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full bg-[#1c1917] text-white flex items-center justify-center font-black text-xs flex-shrink-0 mt-0.5">
                            {{ strtoupper(substr($update->creator->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('projects.show', $update->project) }}" class="text-xs font-bold text-[#c3122e] hover:underline truncate">{{ $update->project->name ?? 'Project' }}</a>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $update->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $update->title }}</p>
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 italic">"{{ $update->summary }}"</p>
                            @if($update->creator)
                                <p class="text-[10px] text-slate-400 mt-1 font-medium">Logged by {{ $update->creator->name }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No status updates published yet</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-900 text-base">Recent Uploaded Documents</h3>
                <a href="{{ route('documents.index') }}" class="text-xs font-semibold text-[#c3122e] hover:text-[#a00e24]">View Document Vault &rarr;</a>
            </div>
            <div class="space-y-3">
                @forelse($recentDocuments as $doc)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center flex-shrink-0">
                                <svg class="w-4-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $doc->original_name }}</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ $doc->project->name ?? 'Global' }} • {{ round($doc->file_size / 1024, 1) }} KB</p>
                            </div>
                        </div>
                        <a href="{{ route('documents.download', $doc) }}" class="btn-secondary btn-sm text-[10px]">Download</a>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No documents uploaded yet</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
