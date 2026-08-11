<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Project Manager Workspace</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Overview of your assigned projects, WBS progress, risks, and blockers</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('daily-updates.index') }}" class="btn-primary text-xs flex items-center gap-1.5 shadow-2xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Daily Project Updates &rarr;</span>
            </a>
            <a href="{{ route('projects.index') }}" class="btn-secondary text-xs">
                View All Projects
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card p-4">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Assigned Projects</span>
            <div class="text-2xl font-bold text-slate-900 mt-2">{{ $assignedProjects->count() }}</div>
        </div>
        <div class="card p-4">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Active Projects</span>
            <div class="text-2xl font-bold text-[#c3122e] mt-2">{{ $activeProjectsCount }}</div>
        </div>
        <div class="card p-4">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Tasks Due Today</span>
            <div class="text-2xl font-bold text-amber-600 mt-2">{{ $tasksDueToday->count() }}</div>
        </div>
        <div class="card p-4">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Overdue Tasks</span>
            <div class="text-2xl font-bold text-rose-600 mt-2">{{ $overdueTasksCount }}</div>
        </div>
    </div>

    <!-- Assigned Projects Summary Table -->
    <div class="card mb-6 overflow-hidden">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-900 text-base">My Assigned Projects</h3>
                <p class="text-xs text-slate-500">Corporate projects led and managed by you</p>
            </div>
            <a href="{{ route('daily-updates.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline">
                Post Daily Update &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Project Code &amp; Name</th>
                        <th>Subsidiary</th>
                        <th>Status</th>
                        <th>Health</th>
                        <th>Deadline</th>
                        <th>Progress</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignedProjects as $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td>
                                <div>
                                    <a href="{{ route('projects.show', $p) }}" class="font-extrabold text-[#c3122e] hover:underline text-sm">{{ $p->name }}</a>
                                    <div class="text-[10px] text-slate-400 font-mono font-bold">{{ $p->code }}</div>
                                </div>
                            </td>
                            <td class="text-xs font-medium text-slate-600">
                                {{ $p->subsidiary->name ?? '-' }}
                            </td>
                            <td>
                                <span class="badge-{{ $p->status->color() }} text-[10px]">{{ $p->status->label() }}</span>
                            </td>
                            <td>
                                <span class="text-xs health-{{ str_replace('_', '-', $p->health->value) }} font-bold">{{ $p->health->label() }}</span>
                            </td>
                            <td class="text-xs font-mono text-slate-600">
                                {{ $p->deadline ? $p->deadline->format('M d, Y') : '-' }}
                            </td>
                            <td class="w-36">
                                <div class="flex items-center gap-2">
                                    <div class="progress-bar-container flex-1">
                                        <div class="progress-bar-fill bg-[#c3122e]" style="width: {{ $p->overall_progress }}%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">{{ $p->overall_progress }}%</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('projects.show', $p) }}" class="btn-ghost btn-sm text-xs">Workspace &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-slate-400 py-8 text-xs">No assigned projects found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Incomplete Task Issues & Delay Reasons Widget -->
    @if($teamTaskIssues->count() > 0)
        <div class="card mb-6 border-amber-200 bg-amber-50/30 p-5">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-amber-200/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-700 flex-shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Team Member &amp; Task Delay Reasons</h3>
                        <p class="text-[10px] text-amber-700 font-medium">Reasons logged by team members why tasks could not be completed on time</p>
                    </div>
                </div>
                <a href="{{ route('my-tasks.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline">Manage Tasks &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($teamTaskIssues as $item)
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

    <!-- Blockers & Risks Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Current Blockers -->
        <div class="card">
            <h3 class="font-bold text-slate-900 text-base mb-4">Active Task Blockers</h3>
            <div class="space-y-3">
                @forelse($currentBlockers as $b)
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200/80">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-rose-800">{{ $b->wbsItem->title ?? 'Task' }}</span>
                            <span class="badge-rose text-[10px]">{{ ucfirst($b->severity) }}</span>
                        </div>
                        <p class="text-xs text-slate-700">{{ $b->description }}</p>
                        <p class="text-[10px] text-slate-400 mt-1 font-mono">Reported by {{ $b->reporter->name ?? 'User' }} • {{ $b->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs">No active task blockers reported</div>
                @endforelse
            </div>
        </div>

        <!-- Open Risks -->
        <div class="card">
            <h3 class="font-bold text-slate-900 text-base mb-4">High Impact Risks</h3>
            <div class="space-y-3">
                @forelse($openRisks as $r)
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200/80">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-semibold text-amber-900">{{ $r->title }}</span>
                            <span class="badge-amber text-[10px]">Score: {{ $r->risk_score }}</span>
                        </div>
                        <p class="text-xs text-slate-700">{{ $r->description }}</p>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs">No high impact risks open</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
