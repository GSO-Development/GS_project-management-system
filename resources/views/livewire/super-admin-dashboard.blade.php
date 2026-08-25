<div wire:poll.30s class="space-y-6">

    {{-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (Luxury Skyline Panorama)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-7 lg:p-8 text-white" style="background: #2b040a;">
        <!-- Full Banner Background Image (Sunset Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Executive Dashboard Banner" 
                class="w-full h-full object-cover object-center"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#140205] via-[#24030a]/90 to-transparent lg:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/50 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5 sm:gap-6">
            <!-- Left Side: App Icon + Title + Status -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-2 ring-black/60 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <div class="w-full h-full rounded-xl flex items-center justify-center text-white font-black text-xl shadow-inner" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        <svg class="w-7 h-7 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>

                <div class="min-w-0 space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-300 font-mono">
                            🏛️ ENTERPRISE GOVERNANCE
                        </span>
                        <span class="text-white/30 text-xs hidden sm:inline">•</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Live Real-Time</span>
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-[27px] font-black text-white tracking-tight leading-tight drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        PMO Executive Command Center
                    </h1>

                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <span class="inline-flex items-center gap-1.5 text-amber-300 font-bold bg-slate-950/70 px-2.5 py-1 rounded-lg border border-white/15 backdrop-blur-md text-[11px] shadow-sm">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-white/40 text-xs hidden sm:inline">&bull;</span>
                        <span class="text-slate-300 text-xs font-medium hidden sm:inline">George Steuart &amp; Company</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: HUD Metrics & Action Buttons -->
            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-white/10">
                <div class="px-4 py-2.5 rounded-2xl border border-white/20 ring-1 ring-black/50 shadow-2xl backdrop-blur-2xl flex items-center gap-3.5 bg-slate-950/85 hover:bg-slate-950 transition-all flex-shrink-0">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-300 uppercase tracking-widest leading-none">ACTIVE PORTFOLIO</span>
                        <span class="text-xs sm:text-sm font-black text-white leading-tight font-mono mt-1">
                            <span class="text-white text-base">{{ $activeProjects }}</span> <span class="text-slate-400 font-normal">/</span> <span class="text-slate-300">{{ $totalProjects }}</span>
                            <span class="text-[10.5px] font-bold text-slate-300 ml-0.5">Projects</span>
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-400 flex-shrink-0 shadow-inner">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>

                <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                    <svg class="w-4 h-4 text-amber-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         2. COMPACT & BALANCED KPI METRICS ROW
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- 1. Total Projects -->
        <a href="{{ route('projects.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:border-slate-300 hover:shadow-xs transition-all flex flex-col justify-between group no-underline">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Projects</span>
                <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black text-slate-900 font-mono leading-none">{{ $totalProjects }}</span>
                <span class="text-[10px] font-semibold text-slate-400">{{ $totalSubsidiaries }} Subs</span>
            </div>
            <div class="mt-2 text-[10px] font-bold text-slate-400 truncate">Total volume</div>
        </a>

        <!-- 2. Active Projects -->
        <a href="{{ route('projects.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:border-rose-300 hover:shadow-xs transition-all flex flex-col justify-between group no-underline">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Active</span>
                <div class="w-7 h-7 rounded-lg bg-rose-50 text-[#c3122e] flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black text-[#c3122e] font-mono leading-none">{{ $activeProjects }}</span>
                <span class="text-[9.5px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 flex-shrink-0">↑ Live</span>
            </div>
            <div class="mt-2 text-[10px] font-bold text-slate-400 truncate">In execution</div>
        </a>

        <!-- 3. Completed Tasks -->
        <a href="{{ route('projects.index') }}" class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition-all flex flex-col justify-between no-underline">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Completed</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black text-emerald-700 font-mono leading-none">{{ $completedTasksCount }}</span>
                <span class="text-[10px] font-bold text-emerald-700">
                    {{ $totalTasksCount > 0 ? (int)round(($completedTasksCount / $totalTasksCount) * 100) : 0 }}%
                </span>
            </div>
            <div class="mt-2 text-[10px] font-bold text-emerald-600 truncate">Tasks delivered</div>
        </a>

        <!-- 4. In Progress Tasks -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:border-amber-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">In Progress</span>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black text-amber-700 font-mono leading-none">{{ $inProgressTasksCount }}</span>
                <span class="text-[10px] font-bold text-amber-600">WIP</span>
            </div>
            <div class="mt-2 text-[10px] font-bold text-slate-400 truncate">Current sprint</div>
        </div>

        <!-- 5. Overdue Tasks -->
        <div class="bg-white border rounded-2xl p-4 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between {{ $overdueTasksCount > 0 ? 'border-rose-300 bg-rose-50/20' : 'border-slate-200/90' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Overdue</span>
                <div class="w-7 h-7 rounded-lg {{ $overdueTasksCount > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black {{ $overdueTasksCount > 0 ? 'text-rose-600' : 'text-slate-900' }} font-mono leading-none">{{ $overdueTasksCount }}</span>
                @if($overdueTasksCount > 0)
                    <span class="text-[9px] font-black text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">Action</span>
                @else
                    <span class="text-[9px] font-bold text-emerald-600">On time</span>
                @endif
            </div>
            <div class="mt-2 text-[10px] font-bold {{ $overdueTasksCount > 0 ? 'text-rose-600' : 'text-slate-400' }} truncate">
                {{ $overdueTasksCount > 0 ? 'Needs attention' : 'No delay items' }}
            </div>
        </div>

        <!-- 6. Pending Approvals -->
        <a href="{{ route('approvals.index') }}" class="bg-white border rounded-2xl p-4 shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between no-underline {{ $pendingApprovals > 0 ? 'border-amber-300 bg-amber-50/20' : 'border-slate-200/90' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10.5px] font-bold text-slate-500 uppercase tracking-wider">Approvals</span>
                <div class="w-7 h-7 rounded-lg {{ $pendingApprovals > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-1">
                <span class="text-2xl font-black {{ $pendingApprovals > 0 ? 'text-amber-700' : 'text-slate-900' }} font-mono leading-none">{{ $pendingApprovals }}</span>
                <span class="text-[9.5px] font-bold {{ $pendingApprovals > 0 ? 'text-amber-700' : 'text-slate-400' }}">Review</span>
            </div>
            <div class="mt-2 text-[10px] font-bold {{ $pendingApprovals > 0 ? 'text-amber-600' : 'text-slate-400' }} truncate">
                {{ $pendingApprovals > 0 ? 'Action required' : 'All approved' }}
            </div>
        </a>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         3. MIDDLE SECTION: ACTIVE PROJECTS & SUBSIDIARY PORTFOLIOS
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- 3A. ACTIVE PROJECTS (Takes 2 Columns) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                Strategic Projects Portfolio
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium">Core enterprise initiatives currently in execution</p>
                        </div>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline flex items-center gap-1">
                        <span>View All Projects</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($overviewProjects as $proj)
                        @php
                            $statusMeta = [
                                'in_progress' => ['badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'In Execution'],
                                'planning'    => ['badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'label' => 'Planning'],
                                'on_hold'     => ['badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'label' => 'On Hold'],
                                'completed'   => ['badge' => 'bg-slate-100 text-slate-600 border-slate-200', 'label' => 'Completed'],
                            ];
                            $sc = $statusMeta[$proj->status->value ?? 'in_progress'] ?? $statusMeta['in_progress'];
                            $prog = (int)($proj->overall_progress ?? 0);
                        @endphp
                        <a href="{{ route('projects.show', $proj->id) }}" class="block p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-white hover:border-slate-200 hover:shadow-2xs transition-all group no-underline">
                            <div class="flex items-center justify-between gap-3 mb-2 flex-wrap sm:flex-nowrap">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $proj->name }}">
                                            {{ $proj->name }}
                                        </h4>
                                        <span class="text-[9.5px] font-black px-2 py-0.5 rounded-md border {{ $sc['badge'] }} flex-shrink-0">
                                            {{ $sc['label'] }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5 truncate">
                                        {{ $proj->subsidiary->name ?? 'George Steuart Group' }}
                                        @if($proj->projectManager)
                                            &bull; Lead: <span class="text-slate-600 font-semibold">{{ $proj->projectManager->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-right flex-shrink-0">
                                    <span class="text-xs font-black text-slate-800 font-mono">{{ $prog }}%</span>
                                </div>
                            </div>
                            <div class="h-2 bg-slate-200/80 rounded-full overflow-hidden">
                                <div style="width: {{ $prog }}%" class="h-full bg-gradient-to-r from-[#c3122e] to-rose-400 rounded-full transition-all duration-500"></div>
                            </div>
                        </a>
                    @empty
                        <div class="py-12 text-center text-xs text-slate-400">No active projects found in the portfolio.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ $totalProjects }} projects registered in database</span>
                <a href="{{ route('projects.create') }}" class="font-bold text-[#c3122e] hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Create New Project</span>
                </a>
            </div>
        </div>

        <!-- 3B. SUBSIDIARY PORTFOLIOS (Takes 1 Column) -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                Subsidiaries
                            </h3>
                            <p class="text-[11px] text-slate-400 font-medium">Business unit distribution</p>
                        </div>
                    </div>
                    <a href="{{ route('subsidiaries.index') }}" class="text-xs font-bold text-blue-600 hover:underline">All &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($topSubsidiaries as $sub)
                        @php
                            $pTotal = max(1, $sub->projects_count);
                            $compPct = (int)round(($sub->completed_count / $pTotal) * 100);
                            $inProgPct = (int)round(($sub->in_progress_count / $pTotal) * 100);
                        @endphp
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-slate-200 transition-all">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <span class="text-xs font-bold text-slate-800 truncate" title="{{ $sub->name }}">{{ $sub->name }}</span>
                                <span class="text-[10px] font-black text-slate-500 font-mono flex-shrink-0">{{ $sub->projects_count }} proj</span>
                            </div>
                            <div class="h-1.5 bg-slate-200 rounded-full overflow-hidden flex mb-2">
                                @if($compPct > 0)<div style="width:{{ $compPct }}%" class="bg-emerald-500 rounded-full"></div>@endif
                                @if($inProgPct > 0)<div style="width:{{ $inProgPct }}%" class="bg-amber-400"></div>@endif
                            </div>
                            <div class="flex items-center gap-1.5 text-[9.5px] font-bold">
                                <span class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">{{ $sub->completed_count }} done</span>
                                @if($sub->in_progress_count > 0)
                                    <span class="text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">{{ $sub->in_progress_count }} active</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-slate-400">No subsidiaries recorded yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">{{ $totalSubsidiaries }} active units</span>
                <a href="{{ route('subsidiaries.index') }}" class="font-bold text-[#c3122e] hover:underline">Manage subsidiaries &rarr;</a>
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         4. BOTTOM ROW: DEADLINES + RECENT ACTIVITY + GOVERNANCE HUB
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- 4A. UPCOMING DEADLINES -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-violet-50 text-violet-600 border border-violet-100 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Upcoming Deadlines</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">Next key milestones</p>
                        </div>
                    </div>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-violet-600 hover:underline">Calendar &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($upcomingTasks as $task)
                        @php
                            $dl = $task->end_date ? (int)now()->today()->diffInDays($task->end_date, false) : 0;
                            $isOvr = $dl < 0; $isTdy = $dl === 0;
                        @endphp
                        <a href="{{ route('projects.show', $task->project_id) }}" class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50 transition-all group no-underline">
                            <div class="text-center w-11 flex-shrink-0 px-1 py-1 rounded-lg border {{ $isOvr ? 'bg-rose-50 border-rose-200 text-rose-700' : ($isTdy ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-slate-100 border-slate-200 text-slate-600') }}">
                                <span class="text-[8px] font-black uppercase block leading-none">{{ $task->end_date ? $task->end_date->format('M') : 'N/A' }}</span>
                                <span class="text-xs font-black font-mono block mt-0.5">{{ $task->end_date ? $task->end_date->format('d') : '--' }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors">{{ $task->title }}</p>
                                <span class="text-[10px] text-slate-400 truncate block">{{ $task->project->name ?? 'Project' }}</span>
                            </div>
                            <span class="text-[10px] font-bold flex-shrink-0 {{ $isOvr ? 'text-rose-600' : ($isTdy ? 'text-amber-700' : 'text-slate-500') }}">
                                {{ $isOvr ? 'Overdue' : ($isTdy ? 'Today' : "In {$dl}d") }}
                            </span>
                        </a>
                    @empty
                        <div class="py-10 text-center text-xs text-slate-400">No upcoming task deadlines found.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3.5 mt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Tracked by WBS schedule</span>
                <a href="{{ route('calendar.index') }}" class="font-bold text-violet-600 hover:underline">Full Calendar &rarr;</a>
            </div>
        </div>

        <!-- 4B. RECENT ACTIVITY LOGS -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Recent Activity</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">Audit logs &amp; updates</p>
                        </div>
                    </div>
                    <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-emerald-600 hover:underline">All &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentActivityLogs as $act)
                        @php
                            $aTitle = match($act->action) {
                                'created_project'          => 'Created new project',
                                'updated_project'          => 'Updated project',
                                'created_wbs_item'         => 'Added new task',
                                'updated_wbs_item'         => 'Updated task status',
                                'created_status_update'    => 'Posted daily update',
                                'created_risk'             => 'Raised a risk',
                                'updated_role_permissions' => 'Updated permissions',
                                default                    => ucwords(str_replace('_', ' ', $act->action))
                            };
                            $who = $act->user_id === auth()->id() ? 'You' : ($act->user->name ?? 'System');
                            $init = strtoupper(substr($who, 0, 1));
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-black flex-shrink-0 border border-emerald-200 shadow-2xs">
                                {{ $init }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 leading-snug">
                                    <span class="text-emerald-700 font-extrabold">{{ $who }}</span> &bull; {{ $aTitle }}
                                </p>
                                <span class="text-[10px] text-slate-400">{{ $act->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-slate-400">No recent activity found.</div>
                    @endforelse
                </div>
            </div>

            <div class="pt-3.5 mt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Real-time system events</span>
                <a href="{{ route('audit-logs.index') }}" class="font-bold text-emerald-600 hover:underline">Full audit log &rarr;</a>
            </div>
        </div>

        <!-- 4C. GOVERNANCE & QUICK SHORTCUTS -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center flex-shrink-0 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Administration Hub</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">Quick management tools</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @php
                        $adminShortcuts = [
                            ['label' => 'Users',        'route' => route('users.index'),             'color' => 'bg-purple-50 text-purple-700 border-purple-100 hover:bg-purple-100', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            ['label' => 'Approvals',    'route' => route('approvals.index'),         'color' => 'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100',    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ['label' => 'Risks & Issues','route' => route('risks.index'),             'color' => 'bg-rose-50 text-rose-700 border-rose-100 hover:bg-rose-100',        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                            ['label' => 'Roles & Perms','route' => route('roles-permissions.index'), 'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100 hover:bg-indigo-100', 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
                            ['label' => 'Reports',      'route' => route('reports.index'),           'color' => 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['label' => 'Settings',     'route' => route('settings.index'),          'color' => 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200',  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ];
                    @endphp
                    @foreach($adminShortcuts as $sc)
                        <a href="{{ $sc['route'] }}" class="flex items-center gap-2 p-2.5 rounded-xl border transition-all no-underline {{ $sc['color'] }}">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sc['icon'] }}"/></svg>
                            <span class="text-xs font-bold truncate">{{ $sc['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="pt-3.5 mt-3.5 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-400 font-medium">Enterprise Suite Controls</span>
                <a href="{{ route('settings.index') }}" class="font-bold text-slate-700 hover:underline">All Settings &rarr;</a>
            </div>
        </div>

    </div>

</div>
