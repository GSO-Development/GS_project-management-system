<div wire:poll.30s class="space-y-6 max-w-[1600px] mx-auto pb-10">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. TOP GREETING & STATUS                                   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $currentUser = auth()->user();
        $userName = $currentUser->name ?? 'User';
        $firstName = explode(' ', $userName)[0];
        $userRole = $currentUser->roles->first()?->name ?? 'Project Manager';
        $subsidiaryName = $currentUser->subsidiary?->name ?? 'George Steuart Group';

        // Format dates & fallback project listings
        $displayProjects = $myProjects->take(5);
        $displayTasks = $myTasksDueSoonList->take(5);
    @endphp
    

    {{-- Clean & Simple Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1 pb-1">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $greeting }}, {{ $firstName }}
            </h1>
            <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                <span class="inline-flex items-center gap-1.5 font-semibold text-slate-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Workspace</span>
                </span>
                <span class="text-slate-300">•</span>
                <span>{{ now()->format('l, F j, Y') }}</span>
                <span class="text-slate-300 hidden sm:inline">•</span>
                <span class="font-medium text-slate-600 hidden sm:inline">{{ $subsidiaryName }}</span>
            </div>
        </div>

        {{-- Quick User Action Shortcuts --}}
        <div class="flex items-center gap-2.5">
            <a href="{{ route('my-tasks.index') }}" 
               wire:navigate.hover
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors no-underline">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span>My Tasks</span>
                @if(($myAssignedTasksCount > 0 ? $myAssignedTasksCount : $myTasksCount) > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                        {{ $myAssignedTasksCount > 0 ? $myAssignedTasksCount : $myTasksCount }}
                    </span>
                @endif
            </a>

            @if(auth()->user()?->canCreateProject())
                <a href="{{ route('projects.create') }}" 
                   wire:navigate.hover
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer no-underline active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            @endif

            <a href="{{ route('daily-updates.index') }}" 
               wire:navigate.hover
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold {{ auth()->user()?->canCreateProject() ? 'text-slate-700 bg-white hover:bg-slate-50 border border-slate-200' : 'text-white bg-[#c3122e] hover:bg-[#a90f27]' }} shadow-2xs hover:shadow-xs transition-all duration-150 cursor-pointer no-underline active:scale-98">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Daily Update</span>
            </a>
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

        {{-- 2. My Project Roles (Soft Violet/Indigo Tint — Multi-Role Portfolio) --}}
        <div class="rounded-2xl border border-indigo-100/80 bg-[#fbfaff] p-4.5 sm:p-5 shadow-2xs flex flex-col justify-between hover:shadow-xs transition-shadow">
            <div class="flex items-start justify-between gap-2">
                <div class="space-y-0.5">
                    <span class="text-xs font-semibold text-slate-500 block">My Project Roles</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight flex items-baseline gap-2">
                        <span>{{ $distinctActiveRolesCount }}</span>
                        <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">
                            {{ Str::plural('Role', $distinctActiveRolesCount) }}
                        </span>
                    </div>
                    <div class="text-[11px] font-semibold text-slate-400">
                        Across {{ $myProjectsCount }} assigned {{ Str::plural('project', $myProjectsCount) }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-200/60 flex items-center justify-center text-indigo-600 shrink-0" title="Project Roles Portfolio">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            {{-- Interactive Role Badges / Pills --}}
            <div class="flex items-center gap-1.5 flex-wrap pt-2.5 mt-1 border-t border-indigo-100/60">
                @if($leadProjectsCount > 0)
                    <a href="{{ route('projects.my-leads', ['role' => 'lead']) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 transition-colors no-underline" title="View {{ $leadProjectsCount }} project(s) as Project Manager">
                        <span>⭐</span>
                        <span>{{ $leadProjectsCount }} PM</span>
                    </a>
                @endif
                @if($sponsorProjectsCount > 0)
                    <a href="{{ route('projects.my-leads', ['role' => 'sponsor']) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 transition-colors no-underline" title="View {{ $sponsorProjectsCount }} project(s) as Sponsor">
                        <span>💼</span>
                        <span>{{ $sponsorProjectsCount }} Sponsor</span>
                    </a>
                @endif
                @if($ownerProjectsCount > 0)
                    <a href="{{ route('projects.my-leads', ['role' => 'owner']) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-blue-50 hover:bg-blue-100 text-blue-900 border border-blue-200 transition-colors no-underline" title="View {{ $ownerProjectsCount }} project(s) as Owner">
                        <span>🏛️</span>
                        <span>{{ $ownerProjectsCount }} Owner</span>
                    </a>
                @endif
                @if($steeringCommitteeProjectsCount > 0)
                    <a href="{{ route('projects.my-leads', ['role' => 'steering_committee']) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-900 border border-rose-200 transition-colors no-underline" title="View {{ $steeringCommitteeProjectsCount }} project(s) in Steering Committee">
                        <span>🎖️</span>
                        <span>{{ $steeringCommitteeProjectsCount }} Committee</span>
                    </a>
                @endif
                @if($coreMemberProjectsCount > 0)
                    <a href="{{ route('projects.my-leads', ['role' => 'member']) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10.5px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 transition-colors no-underline" title="View {{ $coreMemberProjectsCount }} project(s) as Core Team Member">
                        <span>🤝</span>
                        <span>{{ $coreMemberProjectsCount }} Member</span>
                    </a>
                @endif
                @if($totalRoleAssignmentsCount === 0)
                    <span class="text-[11px] font-medium text-slate-400">No active project roles</span>
                @endif
            </div>
        </div>

        {{-- 3. My Tasks (Soft Emerald Tint) --}}
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
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ route('projects.show', $p->id) }}" wire:navigate.hover class="text-xs sm:text-sm font-bold text-slate-900 truncate hover:text-[#c3122e] transition-colors block no-underline">
                                        {{ $p->name }}
                                    </a>
                                    @if(isset($p->user_assigned_role))
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-md text-[10px] font-bold border {{ $p->user_role_badge }} shrink-0">
                                            <span>{{ $p->user_role_icon }}</span>
                                            <span>{{ $p->user_role_short }}</span>
                                        </span>
                                    @endif
                                </div>
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
        {{-- COLUMN 3: RECENT ACTIVITIES (lg:col-span-3)                 --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="flex items-center justify-between mb-4 pb-1 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Recent Activities</h2>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>Live
                        </span>
                    </div>
                    @can('view audit logs')
                        <a href="{{ route('audit-logs.index') }}" wire:navigate.hover class="text-xs font-semibold text-slate-500 hover:text-slate-900 flex items-center gap-1 transition-colors no-underline">
                            <span>View all</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endcan
                </div>

                {{-- Activity List --}}
                <div class="space-y-3">
                    @forelse($recentActivities as $act)
                        @php
                            $action = $act->action;
                            $module = $act->module;
                            $newVals = is_array($act->new_values) ? $act->new_values : [];
                            $prevVals = is_array($act->previous_values) ? $act->previous_values : [];

                            $actionDesc = 'updated item';
                            $targetName = $newVals['name'] ?? $newVals['title'] ?? $newVals['code'] ?? '';
                            $iconBg = 'bg-slate-100 text-slate-600 border border-slate-200/60';
                            $accentColor = 'text-slate-600';

                            // Icons
                            $projectIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>';
                            $taskIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                            $approvalIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                            $alertIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                            $userIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>';
                            $defaultIcon = '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>';

                            $iconSvg = $defaultIcon;

                            if (str_contains($action, 'project')) {
                                $iconSvg = $projectIcon;
                                $iconBg = 'bg-blue-50 text-blue-600 border border-blue-200/60';
                                $accentColor = 'text-blue-700';
                                if ($action === 'created_project') $actionDesc = 'created project';
                                elseif ($action === 'updated_project') $actionDesc = 'updated project';
                                elseif ($action === 'accepted_project_assignment') $actionDesc = 'accepted project';
                                elseif ($action === 'declined_project_assignment') $actionDesc = 'declined project';
                                elseif ($action === 'deleted_project') $actionDesc = 'archived project';
                            } elseif (str_contains($action, 'wbs') || str_contains($action, 'task') || $module === 'wbs') {
                                $iconSvg = $taskIcon;
                                $iconBg = 'bg-emerald-50 text-emerald-600 border border-emerald-200/60';
                                $accentColor = 'text-emerald-700';
                                if ($action === 'completed_wbs_item') $actionDesc = 'completed task';
                                elseif ($action === 'updated_wbs_item') $actionDesc = 'updated task';
                                elseif ($action === 'moved_wbs_item') $actionDesc = 'reordered task';
                                elseif ($action === 'cascade_schedule_update') $actionDesc = 'rescheduled task';
                                else $actionDesc = 'updated task';
                            } elseif (str_contains($action, 'approval') || $module === 'approvals') {
                                $iconSvg = $approvalIcon;
                                $iconBg = 'bg-purple-50 text-purple-600 border border-purple-200/60';
                                $accentColor = 'text-purple-700';
                                if ($action === 'submitted_approval') $actionDesc = 'submitted approval';
                                elseif ($action === 'approved_approval_request') $actionDesc = 'approved request';
                                elseif ($action === 'rejected_approval_request') $actionDesc = 'rejected request';
                                else $actionDesc = 'approval action';
                            } elseif (str_contains($action, 'risk') || str_contains($action, 'blocker') || $module === 'risks') {
                                $iconSvg = $alertIcon;
                                $iconBg = 'bg-amber-50 text-amber-600 border border-amber-200/60';
                                $accentColor = 'text-amber-700';
                                if ($action === 'created_risk') $actionDesc = 'logged risk';
                                elseif ($action === 'resolved_blocker') $actionDesc = 'resolved blocker';
                                else $actionDesc = 'risk update';
                            } elseif (str_contains($action, 'role') || str_contains($action, 'user') || $module === 'roles_permissions') {
                                $iconSvg = $userIcon;
                                $iconBg = 'bg-sky-50 text-sky-600 border border-sky-200/60';
                                $accentColor = 'text-sky-700';
                                if ($action === 'created_role') $actionDesc = 'created role';
                                elseif ($action === 'deleted_role') $actionDesc = 'deleted role';
                                elseif ($action === 'updated_role') $actionDesc = 'updated role';
                                elseif ($action === 'created_user') $actionDesc = 'added user';
                                else $actionDesc = 'security update';
                                if (empty($targetName) && !empty($prevVals['name'])) $targetName = $prevVals['name'];
                            }

                            if (empty($targetName)) {
                                if ($act->record_type === \App\Models\Project::class && isset($projectNamesMap[$act->record_id])) {
                                    $pCode = $projectCodesMap[$act->record_id] ?? '';
                                    $targetName = $projectNamesMap[$act->record_id] . ($pCode ? ' (' . $pCode . ')' : '');
                                } elseif ($act->record_type === \App\Models\WbsItem::class && isset($wbsTitlesMap[$act->record_id])) {
                                    $targetName = $wbsTitlesMap[$act->record_id];
                                } elseif (!empty($newVals['summary'])) {
                                    $targetName = \Illuminate\Support\Str::limit($newVals['summary'], 35);
                                } else {
                                    $targetName = ucwords(str_replace('_', ' ', $action));
                                }
                            }
                        @endphp
                        <div class="flex items-start gap-2.5 group py-1.5 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 rounded-xl px-1.5 transition-colors">
                            {{-- Action Icon Badge --}}
                            <div class="w-7 h-7 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0 mt-0.5 shadow-2xs">
                                {!! $iconSvg !!}
                            </div>

                            {{-- Content --}}
                            <div class="min-w-0 flex-1">
                                <div class="text-xs text-slate-800 leading-snug">
                                    <span class="font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors">{{ $act->user?->name ?? 'System' }}</span>
                                    <span class="font-medium text-slate-500">{{ $actionDesc }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-1 mt-0.5">
                                    <span class="text-[11px] font-semibold {{ $accentColor }} truncate max-w-[130px] sm:max-w-[150px] block" title="{{ $targetName }}">
                                        {{ $targetName }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 shrink-0 font-medium whitespace-nowrap">
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
    {{-- 4. BOTTOM SECTION (TASK PROGRESS CHART & QUICK ACCESS)     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- CARD 1: TASK PROGRESS 7-DAY BAR CHART (lg:col-span-7)      --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col justify-between">
            <div>
                {{-- Header & Real Metric Badges --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-bold text-slate-800 tracking-tight">Task Progress</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $totalProjectTasksCount }} Total Tasks
                            </span>
                        </div>
                        <span class="text-[11px] font-medium text-slate-400">Current Week Performance & Status Breakdown</span>
                    </div>

                    {{-- Accurate Status Badges --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-teal-50 text-teal-700 border border-teal-200/60 font-bold" title="Completed Tasks">
                            <span class="w-2 h-2 rounded-full bg-[#14b8a6]"></span>
                            <span>Completed: {{ $completedTasksCount }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-700 border border-sky-200/60 font-bold" title="In Progress Tasks">
                            <span class="w-2 h-2 rounded-full bg-[#0284c7]"></span>
                            <span>In Progress: {{ $inProgressTasksCount }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 border border-slate-200/80 font-bold" title="Pending Tasks">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>Pending: {{ $pendingTasksCount }}</span>
                        </span>
                    </div>
                </div>

                {{-- Segmented Visual Progress Track across all tasks --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1.5">
                        <span>Overall Project Task Completion</span>
                        <span class="text-slate-900 font-extrabold">{{ $completedTasksPct }}% completed</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden flex items-center p-0.5 gap-0.5">
                        @if($completedTasksCount > 0)
                            <div class="h-full bg-[#14b8a6] rounded-full transition-all duration-500" style="width: {{ max(2, $completedTasksPct) }}%" title="Completed: {{ $completedTasksCount }} ({{ $completedTasksPct }}%)"></div>
                        @endif
                        @if($inProgressTasksCount > 0)
                            <div class="h-full bg-[#0284c7] rounded-full transition-all duration-500" style="width: {{ max(2, $inProgressTasksPct) }}%" title="In Progress: {{ $inProgressTasksCount }} ({{ $inProgressTasksPct }}%)"></div>
                        @endif
                        <div class="h-full bg-slate-300 rounded-full transition-all duration-500 flex-1" title="Pending: {{ $pendingTasksCount }} ({{ $pendingTasksPct }}%)"></div>
                    </div>
                </div>

                {{-- 7-Day Performance Bar Chart Area (Real System Data) --}}
                @php
                    $chartDays = $chartPoints;
                    $maxRecorded = 5;

                    foreach ($chartDays as $pt) {
                        $comp = $pt['completed'] ?? 0;
                        $inProg = $pt['in_progress'] ?? 0;
                        $pend = $pt['pending'] ?? 0;
                        $maxRecorded = max($maxRecorded, $comp, $inProg, $pend);
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
                                $compVal = $dayBar['completed'] ?? 0;
                                $inProgVal = $dayBar['in_progress'] ?? 0;
                                $pendVal = $dayBar['pending'] ?? 0;
                                $isFuture = $dayBar['isFuture'] ?? false;

                                $compHeight = $compVal > 0 ? max(6, min(100, round(($compVal / $yMaxVal) * 100))) : 0;
                                $inProgHeight = $inProgVal > 0 ? max(6, min(100, round(($inProgVal / $yMaxVal) * 100))) : 0;
                                $pendHeight = $pendVal > 0 ? max(6, min(100, round(($pendVal / $yMaxVal) * 100))) : 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center h-full justify-end group cursor-pointer relative"
                                 title="{{ $dayBar['label'] }} ({{ $dayBar['sub'] }}): Completed: {{ $compVal }} | In Progress: {{ $inProgVal }} | {{ $isFuture ? 'Scheduled: ' : 'Pending/Due: ' }}{{ $pendVal }}">
                                {{-- 3 Bars Side-by-Side --}}
                                <div class="flex items-end gap-1 w-full justify-center h-full">
                                    {{-- Completed (Teal) --}}
                                    <div class="w-2.5 sm:w-3.5 bg-[#14b8a6] rounded-t-sm transition-all duration-300 group-hover:brightness-110" 
                                         style="height: {{ $compHeight }}%;"></div>
                                    
                                    {{-- In Progress (Sky Blue) --}}
                                    <div class="w-2.5 sm:w-3.5 bg-[#0284c7] rounded-t-sm transition-all duration-300 group-hover:brightness-110" 
                                         style="height: {{ $inProgHeight }}%;"></div>
                                    
                                    {{-- Pending / Scheduled (Slate) --}}
                                    <div class="w-2.5 sm:w-3.5 {{ $isFuture ? 'bg-slate-200 border border-dashed border-slate-300' : 'bg-slate-300' }} rounded-t-sm transition-all duration-300 group-hover:brightness-95" 
                                         style="height: {{ $pendHeight }}%;"></div>
                                </div>
                                {{-- Day Label below baseline --}}
                                <span class="text-[10.5px] font-semibold {{ $dayBar['isToday'] ? 'text-[#c3122e] font-black' : 'text-slate-500' }} mt-2 whitespace-nowrap block text-center">
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

    {{-- A. Review Project Assignment Modal (Rich Sign-Off Detail Card) --}}
    @if($showReviewModal && $reviewProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200/90 w-full max-w-2xl p-6 sm:p-7 space-y-4 my-8 max-h-[90vh] flex flex-col">
                
                {{-- Header --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center font-bold text-xl shadow-2xs shrink-0">
                            ⭐
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-black text-slate-900 truncate tracking-tight">
                                Project Manager Assignment Sign-Off
                            </h3>
                            <span class="text-xs text-slate-400 font-medium block mt-0.5">
                                Request Reference #{{ $reviewApproval->id ?? $reviewProject->id }} · {{ $reviewProject->code }}
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors text-lg font-bold cursor-pointer shrink-0" title="Close">
                        &times;
                    </button>
                </div>

                {{-- Scrollable Modal Body --}}
                <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                    
                    {{-- 1. Request Overview Box --}}
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 space-y-2.5 text-xs shadow-2xs">
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-bold">Project:</span>
                            <span class="font-black text-slate-900">{{ $reviewProject->name }} ({{ $reviewProject->code }})</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-bold">Request Type:</span>
                            <span class="font-black text-[#c3122e]">New Project Plan</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-bold">Requested By:</span>
                            <span class="font-bold text-slate-900">{{ $reviewApproval->requester->name ?? ($reviewProject->creator->name ?? 'Super Administrator') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5">
                            <span class="text-slate-500 font-bold">Current Status:</span>
                            <span class="font-black text-amber-700">Pending Review</span>
                        </div>
                    </div>

                    {{-- 2. Four Key Parameter Tiles --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">🏛️ START DATE</span>
                            <span class="font-mono font-bold text-slate-900 block mt-1 text-xs">
                                {{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}
                            </span>
                        </div>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">🏁 TARGET DEADLINE</span>
                            <span class="font-mono font-bold text-slate-900 block mt-1 text-xs">
                                {{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'TBD' }}
                            </span>
                        </div>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">⏳ EST. DURATION</span>
                            <span class="font-bold text-slate-900 block mt-1 text-xs">
                                {{ $reviewDurationDays ? "{$reviewDurationDays} Days" : '30 Days' }}
                            </span>
                        </div>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">💰 EST. BUDGET</span>
                            <span class="font-mono font-bold text-slate-900 block mt-1 text-xs">
                                {{ $reviewProject->estimated_budget > 0 ? 'Rs. ' . number_format($reviewProject->estimated_budget, 0) : 'Not Set' }}
                            </span>
                        </div>
                    </div>

                    {{-- 3. WBS Execution Structure --}}
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 space-y-3 text-xs shadow-2xs">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm">📋</span>
                                <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">WBS EXECUTION STRUCTURE</h5>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/70">
                                    {{ $reviewWbsItems->count() }} Items
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/70">
                                    {{ $reviewPhases->count() }} Phases
                                </span>
                                @if($reviewMilestonesCount > 0)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                                        🎯 {{ $reviewMilestonesCount }} Milestones
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($reviewWbsItems->count() > 0)
                            <div class="max-h-52 overflow-y-auto space-y-1.5 pr-1 scrollbar-thin rounded-xl bg-white p-1">
                                @foreach($reviewWbsItems as $item)
                                    @php 
                                        $isPhase = ($item->item_type?->value === 'phase' || !$item->parent_id); 
                                    @endphp
                                    <div class="flex items-center justify-between gap-2 p-2 rounded-xl text-xs {{ $isPhase ? 'bg-slate-50/80 font-bold text-slate-900' : 'pl-6 text-slate-600 font-medium hover:bg-slate-50/50' }}">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="text-xs font-mono {{ $isPhase ? 'text-[#c3122e] font-bold' : 'text-slate-400' }}">
                                                {{ $isPhase ? '📁' : '↳' }}
                                            </span>
                                            <span class="truncate">{{ $item->title }}</span>
                                        </div>
                                        <span class="text-slate-400 font-mono text-xs shrink-0 font-medium">
                                            {{ $item->duration ? $item->duration . 'd' : ($item->estimated_hours ? $item->estimated_hours . 'h' : '') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-3.5 rounded-xl bg-slate-50 text-center text-slate-400 text-xs font-medium border border-slate-200/60">
                                No pre-configured WBS breakdown. Project blueprint will be structured in workspace.
                            </div>
                        @endif
                    </div>

                    {{-- 4. Governance Team Roster --}}
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 space-y-3 text-xs shadow-2xs">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm">👥</span>
                                <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">GOVERNANCE TEAM ROSTER</h5>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold font-mono bg-rose-50 text-[#c3122e] border border-rose-200/70">
                                {{ $reviewTotalTeam }} Assigned
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            {{-- PM --}}
                            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-[#c3122e]/30 shadow-2xs">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white font-black text-[11px] shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    {{ strtoupper(substr($reviewProject->projectManager->name ?? 'PM', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="font-black text-slate-900 block truncate text-xs">{{ $reviewProject->projectManager->name ?? 'You' }}</span>
                                    <span class="text-[9.5px] text-[#c3122e] font-bold">⭐ Designated Project Manager</span>
                                </div>
                            </div>
                            {{-- Sponsors --}}
                            @foreach($reviewSponsors as $sp)
                                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-purple-200 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg bg-purple-600 text-white font-black text-[11px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($sp->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-black text-slate-900 block truncate text-xs">{{ $sp->name }}</span>
                                        <span class="text-[9.5px] text-purple-700 font-bold">💼 Project Sponsor</span>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Owners --}}
                            @foreach($reviewOwners as $ow)
                                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-blue-200 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white font-black text-[11px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($ow->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-black text-slate-900 block truncate text-xs">{{ $ow->name }}</span>
                                        <span class="text-[9.5px] text-blue-700 font-bold">🏛️ Project Owner</span>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Committee --}}
                            @foreach($reviewCommittee as $cm)
                                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-rose-200 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg bg-rose-600 text-white font-black text-[11px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($cm->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-black text-slate-900 block truncate text-xs">{{ $cm->name }}</span>
                                        <span class="text-[9.5px] text-rose-700 font-bold">🎖️ Steering Committee</span>
                                    </div>
                                </div>
                            @endforeach
                            {{-- Members --}}
                            @foreach($reviewMembers->take(4) as $mb)
                                <div class="flex items-center gap-2.5 p-2 rounded-xl bg-white border border-emerald-200 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-black text-[11px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($mb->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-black text-slate-900 block truncate text-xs">{{ $mb->name }}</span>
                                        <span class="text-[9.5px] text-emerald-700 font-bold">🤝 Core Team Member</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 5. Decline Reason Sub-Form (If showRejectModal is toggled) --}}
                    @if($showRejectModal)
                        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-2.5">
                            <label class="text-xs font-bold text-rose-800 block">
                                Reason for Declining Assignment: <span class="text-rose-600">*</span>
                            </label>
                            <textarea wire:model="rejectionReasonInput" class="w-full text-xs p-3 rounded-xl border border-rose-300 bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/30" rows="3" placeholder="Please state reasons or issues preventing you from accepting leadership of this project..."></textarea>
                            @error('rejectionReasonInput') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            <div class="flex justify-end gap-2 pt-1">
                                <button wire:click="$set('showRejectModal', false)" type="button" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 bg-white border border-slate-200 cursor-pointer">
                                    Back
                                </button>
                                <button wire:click="submitRejection" type="button" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 cursor-pointer shadow-xs">
                                    Confirm Decline
                                </button>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Modal Action Footer --}}
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 flex-shrink-0 gap-3">
                    <div>
                        @if(!$showRejectModal)
                            <button
                                wire:click="openRejectForm({{ $reviewProject->id }})"
                                type="button"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#e11d48] hover:bg-[#be123c] transition-all shadow-2xs flex items-center gap-1.5 cursor-pointer active:scale-95"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span>Decline Assignment</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            wire:click="closeReviewModal"
                            type="button"
                            class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)"
                            type="button"
                            class="px-6 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#8b1424] hover:bg-[#70101d] transition-all shadow-sm flex items-center gap-2 cursor-pointer active:scale-98"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Accept Assignment &amp; Launch &rarr;</span>
                        </button>
                    </div>
                </div>

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
