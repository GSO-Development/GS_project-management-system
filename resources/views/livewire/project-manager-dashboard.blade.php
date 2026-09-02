<div wire:poll.15s class="space-y-6 sm:space-y-7">
    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 1. TOP WELCOME HERO BANNER (Full-Width Panoramic Skyline)  -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-7 lg:p-8 text-white" style="background: #2b040a;">
        <!-- Full Banner Background Image (Sunset City Skyline Panorama - Preserved) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Welcome Banner" 
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
            <!-- Left Side: User Initials/Avatar + Welcome Heading + Date -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <!-- User Avatar Squircle with Gold Border -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-2 ring-black/60 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <div class="w-full h-full rounded-xl flex items-center justify-center text-white font-black text-lg sm:text-xl shadow-inner" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

                <div class="min-w-0 space-y-2 flex-1">
                    <!-- Suite Tag & Status Pill -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-300 font-mono">
                            🏛️ GEORGE STEUART WORKSPACE
                        </span>
                        <span class="text-white/30 text-xs hidden sm:inline">•</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Workspace Active</span>
                        </span>
                    </div>

                    <!-- Welcome Title -->
                    <h1 class="text-xl sm:text-2xl lg:text-[27px] font-black text-white tracking-tight leading-tight drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h1>

                    <!-- Date Badge -->
                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <span class="inline-flex items-center gap-1.5 text-amber-300 font-bold bg-slate-950/70 px-2.5 py-1 rounded-lg border border-white/15 backdrop-blur-md text-[11px] shadow-sm">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Active Focus HUD + Action Buttons (Obsidian Glass) -->
            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-white/10">
                <!-- Floating Mini Gauge Card -->
                <div class="px-4 py-2.5 rounded-2xl border border-white/20 ring-1 ring-black/50 shadow-2xl backdrop-blur-2xl flex items-center justify-between sm:justify-start gap-3.5 bg-slate-950/85 hover:bg-slate-950 transition-all flex-shrink-0">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-300 uppercase tracking-widest leading-none">ASSIGNED TASKS</span>
                        <span class="text-xs sm:text-sm font-black text-white leading-tight font-mono mt-1">
                            <span class="text-white text-base">{{ $myTasksCount }}</span> <span class="text-[10.5px] font-bold text-slate-300 ml-0.5">Active</span>
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-400 flex-shrink-0 shadow-inner">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>

                <!-- Action Button: My Tasks -->
                <a href="{{ route('my-tasks.index') }}" class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                    <svg class="w-4 h-4 text-amber-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>My Tasks</span>
                </a>

                @if(auth()->user()?->canCreateProject())
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-black text-slate-950 shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105 flex-shrink-0 bg-amber-400 border border-amber-300">
                        <svg class="w-4 h-4 text-slate-950 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>New Project</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 👑 PENDING PROJECT LEADERSHIP ASSIGNMENT ACTION CENTER     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($pendingInvitations->count() > 0)
        <div class="rounded-3xl overflow-hidden shadow-lg border border-amber-200 bg-white animate-in fade-in duration-300">
            <!-- Header Ribbon -->
            <div style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); color: #ffffff;" class="px-6 py-4 flex items-center justify-between gap-4 flex-wrap shadow-xs">
                <div class="flex items-center gap-3.5">
                    <div style="background: rgba(255,255,255,0.2);" class="w-10 h-10 rounded-2xl backdrop-blur-md flex items-center justify-center text-xl flex-shrink-0 shadow-inner">
                        👑
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-sm sm:text-base font-black text-white tracking-tight">Project Leadership Assignment Required</h3>
                            <span style="background: #ffffff; color: #c3122e;" class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider shadow-xs">
                                {{ $pendingInvitations->count() }} {{ $pendingInvitations->count() === 1 ? 'ACTION NEEDED' : 'ACTIONS NEEDED' }}
                            </span>
                        </div>
                        <p class="text-xs text-white/90 font-medium mt-0.5">PMO Administration has assigned you as Project Leader. Review project details &amp; template to accept leadership.</p>
                    </div>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="p-5 sm:p-6 bg-slate-50/70 space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($pendingInvitations as $proj)
                        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-mono text-xs font-black px-2 py-0.5 rounded-md bg-rose-50 text-[#c3122e] border border-rose-200">
                                                {{ $proj->code }}
                                            </span>
                                            <span class="text-xs font-bold text-slate-500">{{ $proj->subsidiary->name ?? 'George Steuart' }}</span>
                                        </div>
                                        <h4 class="text-sm font-black text-slate-900 mt-1.5">{{ $proj->name }}</h4>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-2 py-2 border-y border-slate-100 text-xs">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Start Date</span>
                                        <span class="font-bold text-slate-700 font-mono">{{ $proj->start_date ? $proj->start_date->format('M d, Y') : 'Immediate' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Deadline</span>
                                        <span class="font-bold text-slate-700 font-mono">{{ $proj->deadline ? $proj->deadline->format('M d, Y') : 'Flexible' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 block uppercase">Assigned By</span>
                                        <span class="font-bold text-slate-700 truncate block">{{ $proj->creator->name ?? 'PMO Admin' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2.5 pt-4">
                                <button wire:click="openRejectForm({{ $proj->id }})" type="button" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 cursor-pointer transition-all">
                                    Decline Assignment
                                </button>
                                <button wire:click="openReviewModal({{ $proj->id }})" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-900 hover:bg-slate-800 cursor-pointer shadow-xs transition-all">
                                    Review Details
                                </button>
                                <button wire:click="acceptProjectAssignment({{ $proj->id }}, true)" type="button" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 cursor-pointer transition-all flex items-center gap-1.5">
                                    <span>Accept Leadership</span>
                                    <span>&rarr;</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. TOP ROW: EXACTLY 5 COMPACT CLEAN KPI METRIC CARDS       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <style>
        @media (min-width: 768px) {
            .pm-kpi-grid {
                display: grid !important;
                grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
            }
        }
    </style>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 sm:gap-3 pm-kpi-grid">
        <!-- 1. My Projects -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-2.5 sm:p-3 shadow-2xs hover:border-blue-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center gap-2 sm:gap-2.5 mb-1">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-black text-slate-900 leading-none block font-mono">{{ $myProjectsCount }}</span>
                    <span class="text-[9.5px] sm:text-[10px] font-bold text-slate-500 block mt-0.5 truncate">My Projects</span>
                </div>
            </div>
            <div class="text-[8.5px] sm:text-[9.5px] font-bold text-blue-600 truncate flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>{{ $inProgressProjectsCount }} in progress</span>
            </div>
        </div>

        <!-- 2. My Tasks -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-2.5 sm:p-3 shadow-2xs hover:border-emerald-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center gap-2 sm:gap-2.5 mb-1">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-black text-slate-900 leading-none block font-mono">{{ $myTasksCount }}</span>
                    <span class="text-[9.5px] sm:text-[10px] font-bold text-slate-500 block mt-0.5 truncate">My Tasks</span>
                </div>
            </div>
            <div class="text-[8.5px] sm:text-[9.5px] font-bold text-emerald-600 truncate flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>{{ $completedTasksCount }} done</span>
            </div>
        </div>

        <!-- 3. Due in 7 Days -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-2.5 sm:p-3 shadow-2xs hover:border-purple-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center gap-2 sm:gap-2.5 mb-1">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-black text-slate-900 leading-none block font-mono">{{ $tasksDueSoonCount }}</span>
                    <span class="text-[9.5px] sm:text-[10px] font-bold text-slate-500 block mt-0.5 truncate">Due 7 Days</span>
                </div>
            </div>
            <div class="text-[8.5px] sm:text-[9.5px] font-bold text-purple-600 truncate flex items-center gap-1">
                <span>📅 Deadlines</span>
            </div>
        </div>

        <!-- 4. Pending Approvals -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-2.5 sm:p-3 shadow-2xs hover:border-amber-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center gap-2 sm:gap-2.5 mb-1">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-black text-slate-900 leading-none block font-mono">{{ $pendingApprovalsCount }}</span>
                    <span class="text-[9.5px] sm:text-[10px] font-bold text-slate-500 block mt-0.5 truncate">Approvals</span>
                </div>
            </div>
            <div class="text-[8.5px] sm:text-[9.5px] font-bold text-amber-600 truncate flex items-center gap-1">
                <span>⚡ Action</span>
            </div>
        </div>

        <!-- 5. Logged Hours -->
        <div class="bg-white border border-slate-200/80 rounded-xl p-2.5 sm:p-3 shadow-2xs hover:border-rose-300 hover:shadow-xs transition-all flex flex-col justify-between">
            <div class="flex items-center gap-2 sm:gap-2.5 mb-1">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-sm sm:text-lg font-black text-slate-900 leading-none block font-mono">{{ $hoursThisWeekFormatted }}</span>
                    <span class="text-[9.5px] sm:text-[10px] font-bold text-slate-500 block mt-0.5 truncate">Hours</span>
                </div>
            </div>
            <div class="text-[8.5px] sm:text-[9.5px] font-bold text-rose-600 truncate flex items-center gap-1">
                <span>⏱️ This week</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. MIDDLE ROW: 3 CREATIVE & PRACTICAL MAJOR WIDGETS        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- CARD 1: Active Projects Focus (Interactive Projects List with Progress & Health) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Active Projects Focus
                        </h3>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-bold text-rose-700 hover:underline">View all &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($myProjects->take(4) as $proj)
                        @php
                            $progress = (int) round($proj->overall_progress ?? 0);
                            $healthLabel = $proj->health?->label() ?? 'On Track';
                            $healthClass = match($proj->health?->value ?? 'on_track') {
                                'at_risk' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'needs_attention' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };
                        @endphp
                        <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-200/70 hover:bg-white hover:border-slate-300 hover:shadow-xs transition-all space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] font-mono font-bold text-[#c3122e] bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">{{ $proj->code }}</span>
                                        <a href="{{ route('projects.show', $proj) }}" class="text-xs font-extrabold text-slate-900 hover:text-[#c3122e] truncate max-w-44 block">
                                            {{ $proj->name }}
                                        </a>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5 truncate">{{ $proj->subsidiary->name ?? 'George Steuart Group' }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[9.5px] font-bold border {{ $healthClass }} flex-shrink-0">
                                    {{ $healthLabel }}
                                </span>
                            </div>

                            <!-- Progress Track -->
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full bg-gradient-to-r from-[#c3122e] to-rose-500" style="width: {{ max(5, $progress) }}%"></div>
                                </div>
                                <span class="text-[10.5px] font-mono font-bold text-slate-700 flex-shrink-0">{{ $progress }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>No active projects assigned yet.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- CARD 2: Recent Activity (Moved to Middle Row) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Recent Activity
                        </h3>
                    </div>
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">View all &rarr;</a>
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse($recentActivities->take(4) as $act)
                        @php
                            $actionRaw = strtolower($act->action ?? '');
                            $isCheck = str_contains($actionRaw, 'accept') || str_contains($actionRaw, 'complete');
                            $isDoc = str_contains($actionRaw, 'create') || str_contains($actionRaw, 'document') || str_contains($actionRaw, 'upload');
                            $isChat = str_contains($actionRaw, 'comment') || str_contains($actionRaw, 'update');
                            $isClock = str_contains($actionRaw, 'hour') || str_contains($actionRaw, 'time') || str_contains($actionRaw, 'log');

                            $aColor = $isCheck ? 'emerald' : ($isDoc ? 'blue' : ($isChat ? 'purple' : ($isClock ? 'amber' : 'emerald')));
                            $actionTitle = $isCheck ? 'Accepted project assignment' : ($isDoc ? 'New project initiated' : (ucwords(str_replace('_', ' ', $act->action))));
                            $authorName = $act->user->name ?? 'PMO Admin';
                            $subText = "By {$authorName}";
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-200/60">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-xl bg-{{ $aColor }}-50 border border-{{ $aColor }}-100 text-{{ $aColor }}-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    @if($isCheck)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @elseif($isDoc)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @elseif($isChat)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @elseif($isClock)
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $actionTitle }}">
                                        {{ $actionTitle }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        {{ $subText }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium flex-shrink-0 font-mono">{{ $act->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>No recent activity recorded yet.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10.5px] font-bold text-slate-400">Live operational activity stream</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Real-time</span>
                </span>
            </div>
        </div>

        <!-- CARD 3: Upcoming Milestones (Polished) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Upcoming Milestones
                        </h3>
                    </div>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-purple-700 hover:underline">View all &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingMilestones as $idx => $m)
                        @php
                            $daysLeft = (int) now()->today()->diffInDays($m->end_date, false);
                            $colors = ['rose', 'blue', 'emerald', 'purple'];
                            $cColor = $colors[$idx % count($colors)];
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-200/60">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-{{ $cColor }}-50 border border-{{ $cColor }}-100 text-{{ $cColor }}-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $m->title }}">
                                        {{ $m->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        {{ $m->project->name ?? 'George Steuart' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 flex-shrink-0">
                                <div class="text-center px-2 py-0.5 rounded-lg bg-slate-100/80 border border-slate-200/80">
                                    <span class="text-[8px] font-black text-slate-400 uppercase block leading-tight">{{ $m->end_date->format('M') }}</span>
                                    <span class="text-xs font-black text-slate-800 font-mono leading-tight block">{{ $m->end_date->format('d') }}</span>
                                </div>
                                <span class="text-[10.5px] font-bold {{ $daysLeft <= 3 ? 'text-rose-600 font-black' : 'text-slate-500' }} min-w-14 text-right">
                                    {{ $daysLeft < 0 ? 'Overdue' : ($daysLeft === 0 ? 'Today' : ($daysLeft === 1 ? 'In 1 day' : "In {$daysLeft} days")) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                            <span>No upcoming milestones scheduled.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. BOTTOM ROW: 3 MAJOR CARDS (Tasks Due, Risks Watch, Approvals) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. My Tasks Due Soon (Ultra Clear & Actionable) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Tasks Due &amp; Deadlines
                        </h3>
                    </div>
                    <a href="{{ route('my-tasks.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all &rarr;</a>
                </div>

                <div class="space-y-2.5">
                    @forelse($myTasksDueSoonList as $task)
                        @php
                            $daysLeft = $task->end_date ? (int) now()->today()->diffInDays($task->end_date, false) : 0;
                            $isOverdue = $daysLeft < 0;
                            $isDueToday = $daysLeft === 0;
                            $pri = ucfirst($task->priority?->value ?? 'medium');
                            $priCls = match($pri) {
                                'High' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'Low'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200'
                            };
                        @endphp
                        <a href="{{ route('projects.show', ['project' => $task->project_id]) }}" class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-white hover:bg-slate-50/80 transition-all group border border-slate-200/70 hover:border-slate-300 shadow-2xs no-underline">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <!-- Date Badge -->
                                <div class="text-center px-2 py-1 rounded-xl {{ $isOverdue ? 'bg-rose-50 border border-rose-200/80 text-rose-700' : ($isDueToday ? 'bg-amber-50 border border-amber-200/80 text-amber-800' : 'bg-slate-100/90 border border-slate-200 text-slate-700') }} flex-shrink-0 min-w-10">
                                    <span class="text-[8px] font-black uppercase block leading-none tracking-wider opacity-80">{{ $task->end_date ? $task->end_date->format('M') : 'DUE' }}</span>
                                    <span class="text-xs font-black font-mono block leading-tight mt-0.5">{{ $task->end_date ? $task->end_date->format('d') : '--' }}</span>
                                </div>

                                <!-- Title & Subtitle Info -->
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors leading-tight" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h4>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="text-[10px] text-slate-400 font-medium truncate max-w-[100px]">
                                            {{ $task->project->name ?? 'Project' }}
                                        </span>
                                        <span class="text-slate-300 text-[10px]">&bull;</span>
                                        <span class="text-[10px] font-semibold text-slate-600 truncate max-w-[110px]">
                                            {{ $task->assignedUser->name ?? 'Unassigned' }}
                                        </span>
                                        <span class="text-slate-300 text-[10px]">&bull;</span>
                                        <span class="inline-flex items-center text-[9px] font-extrabold px-1.5 py-0.2 rounded border {{ $priCls }}">
                                            {{ $pri }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Due Status Badge -->
                            <div class="flex-shrink-0">
                                @if($isOverdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        <span>Overdue ({{ abs($daysLeft) }}d)</span>
                                    </span>
                                @elseif($isDueToday)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                                        <span>⚠️ Due Today</span>
                                    </span>
                                @elseif($daysLeft === 1)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span>⏳ Tomorrow</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/80">
                                        <span>In {{ $daysLeft }} days</span>
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>All clear! No pending tasks due soon.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 2. Risks & Blockers Watch (Moved to Bottom Row) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Risks &amp; Blockers Watch
                        </h3>
                    </div>
                    <a href="{{ route('risks.index') }}" class="text-xs font-bold text-rose-700 hover:underline flex items-center gap-1">
                        <span>View all</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <!-- Combined Blockers and Risks List -->
                @php
                    $totalImpediments = $currentBlockers->count() + $openRisks->count();
                @endphp

                <div class="space-y-2.5 max-h-[280px] overflow-y-auto pr-0.5">
                    @if($totalImpediments > 0)
                        {{-- 1. Show Active Blockers First --}}
                        @foreach($currentBlockers->take(3) as $blocker)
                            <div class="p-3 rounded-2xl bg-rose-50/60 border border-rose-200/80 hover:bg-rose-50 transition-all space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping flex-shrink-0"></span>
                                        <span class="text-xs font-black text-slate-900 truncate block" title="{{ $blocker->description ?? $blocker->reason ?? 'Task Blocker' }}">
                                            {{ $blocker->description ?? $blocker->reason ?? 'Task Blocker' }}
                                        </span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-black bg-rose-100 text-rose-800 border border-rose-300/80 uppercase tracking-wider flex-shrink-0 font-mono">
                                        BLOCKER
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-500 font-medium pl-3.5">
                                    <span class="truncate max-w-[160px] text-slate-600 font-semibold">
                                        {{ $blocker->wbsItem->project->name ?? 'Project Deliverable' }}
                                    </span>
                                    <span class="text-rose-600 font-bold flex-shrink-0">
                                        {{ $blocker->created_at ? $blocker->created_at->diffForHumans(null, true) : 'Active' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        {{-- 2. Show Open Project Risks --}}
                        @foreach($openRisks->take(4) as $risk)
                            @php
                                $probColor = match(strtolower($risk->probability ?? 'medium')) {
                                    'high' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-blue-50 text-blue-700 border-blue-200',
                                };
                            @endphp
                            <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-200/70 hover:bg-white hover:border-slate-300 transition-all space-y-1.5">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                        <span class="text-xs font-bold text-slate-900 truncate block" title="{{ $risk->title }}">
                                            {{ $risk->title }}
                                        </span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold border {{ $probColor }} uppercase tracking-wider flex-shrink-0 font-mono">
                                        {{ $risk->probability ?? 'Risk' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] text-slate-400 font-medium pl-3">
                                    <span class="truncate max-w-[160px] text-slate-500 font-semibold">
                                        {{ $risk->project->name ?? 'General Project' }}
                                    </span>
                                    <span class="text-slate-400 font-mono flex-shrink-0">
                                        {{ $risk->category ?? 'Governance' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-8 text-center text-xs text-slate-400 space-y-1.5">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto shadow-2xs">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <p class="font-black text-slate-800 text-sm">Clean Execution</p>
                            <p class="text-[11px] text-slate-400 max-w-[220px] mx-auto leading-relaxed">No active blockers or open high risks across your projects.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Link -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10.5px] font-bold text-slate-400">
                    {{ $openRisks->count() }} Risks &bull; {{ $currentBlockers->count() }} Blockers
                </span>
                <a href="{{ route('risks.index') }}" class="text-xs font-black text-rose-700 hover:underline flex items-center gap-1">
                    <span>Manage Risks Hub</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 3. My Approvals -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            My Approvals
                        </h3>
                    </div>
                    <a href="{{ route('approvals.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($myApprovalsList as $app)
                        @php
                            $typeStr = strtolower($app->request_type?->value ?? $app->request_type?->name ?? 'approval');
                            $shortTag = 'Approval';
                            $tagClass = 'bg-blue-50 text-blue-700 border-blue-200';

                            if (str_contains($typeStr, 'budget')) {
                                $shortTag = 'Budget';
                                $tagClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            } elseif (str_contains($typeStr, 'scope')) {
                                $shortTag = 'Scope Change';
                                $tagClass = 'bg-purple-50 text-purple-700 border-purple-200';
                            } elseif (str_contains($typeStr, 'resource')) {
                                $shortTag = 'Resource';
                                $tagClass = 'bg-amber-50 text-amber-700 border-amber-200';
                            } elseif (str_contains($typeStr, 'plan') || str_contains($typeStr, 'wbs')) {
                                $shortTag = 'Project Plan';
                                $tagClass = 'bg-blue-50 text-blue-700 border-blue-200';
                            }

                            $titleText = ($app->request_type?->label() ?? 'Project Approval') . ' - ' . ($app->project->name ?? 'George Steuart');
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-colors group border border-transparent hover:border-slate-200/60">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $titleText }}">
                                        {{ $titleText }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] text-slate-400 font-medium truncate">
                                            Requested by: {{ $app->requester->name ?? 'PMO Admin' }}
                                        </span>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold border {{ $tagClass }} whitespace-nowrap">
                                            {{ $shortTag }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 font-mono flex-shrink-0">
                                {{ $app->created_at->format('M d') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>No pending approval requests.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. MODALS & INTERACTIVE POPUPS                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}

    <!-- 1. Review Project Assignment Modal -->
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
            $totalTeam = $sponsors->count() + $owners->count() + $committee->count() + $members->count() + 1; // +1 PM

            $durationDays = ($reviewProject->start_date && $reviewProject->deadline) 
                ? (int) $reviewProject->start_date->diffInDays($reviewProject->deadline) + 1 
                : null;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-950/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200">
                
                {{-- Modal Header --}}
                <div style="background: linear-gradient(135deg, #18080c 0%, #300c16 50%, #1a080e 100%); color: #ffffff;" class="p-5 sm:p-6 flex items-center justify-between border-b border-rose-950/40 flex-shrink-0">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl shadow-inner flex-shrink-0">
                            ⭐
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base sm:text-lg font-black text-white tracking-tight">Project Manager Assignment Review</h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black bg-rose-500/30 text-rose-200 border border-rose-400/30 whitespace-nowrap">{{ $reviewProject->code }}</span>
                            </div>
                            <p class="text-xs text-rose-200/80 font-medium mt-0.5 truncate">{{ $reviewProject->subsidiary->name ?? 'George Steuart Group' }} · PMO Assigned</p>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" type="button" class="p-2 text-white/70 hover:text-white rounded-xl hover:bg-white/10 cursor-pointer transition-colors flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Scrollable Body --}}
                <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-4 scrollbar-thin">
                    @if(!$showRejectModal)
                        {{-- 1. Project Title & Scope Card --}}
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

                        {{-- 2. Timeline & Parameters Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">📅 Start Date</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">🏁 Target Deadline</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'TBD' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">⏳ Est. Duration</span>
                                <span class="font-bold text-slate-900 block mt-1">
                                    {{ $durationDays ? "{$durationDays} Days" : 'Flexible' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">💰 Est. Budget</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">
                                    {{ $reviewProject->estimated_budget > 0 ? 'Rs. ' . number_format($reviewProject->estimated_budget, 0) : 'Not Specified' }}
                                </span>
                            </div>
                        </div>

                        {{-- 3. Selected WBS Structure / Blueprint Section --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        📋
                                    </div>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">WBS Execution Structure</h5>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $wbsItems->count() }} Total Items
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $phases->count() }} Phases
                                    </span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            🎯 {{ $milestonesCount }} Milestones
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- WBS Phases & Tasks Preview List --}}
                            @if($wbsItems->count() > 0)
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 scrollbar-thin rounded-xl bg-white p-2.5 border border-slate-200/70">
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
                                            <div class="flex items-center gap-2 flex-shrink-0 text-[10px]">
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
                            @else
                                <div class="p-3 bg-white rounded-xl border border-slate-200/60 text-center text-xs font-bold text-slate-400">
                                    Custom Agile Workspace initialized. You can build and structure your WBS upon accepting leadership.
                                </div>
                            @endif
                        </div>

                        {{-- 4. Governance & Team Structure Section --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-[#c3122e] flex items-center justify-center font-bold text-xs">
                                        👥
                                    </div>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">Governance &amp; Team Roster</h5>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black font-mono bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $totalTeam }} Assigned
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                {{-- Project Manager Card (Self) --}}
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border-2 border-[#c3122e]/40 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white font-black text-[10px] flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($reviewProject->projectManager->name ?? auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-black text-slate-900 block truncate">{{ $reviewProject->projectManager->name ?? auth()->user()->name }}</span>
                                        <span class="text-[9px] text-[#c3122e] font-black uppercase">⭐ Designated Project Manager (You)</span>
                                    </div>
                                </div>

                                {{-- Sponsors --}}
                                @foreach($sponsors as $sp)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-amber-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-amber-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($sp->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $sp->name }}</span>
                                            <span class="text-[9px] text-amber-800 font-bold">💼 Project Sponsor</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Owners --}}
                                @foreach($owners as $ow)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-emerald-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($ow->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $ow->name }}</span>
                                            <span class="text-[9px] text-emerald-800 font-bold">👑 Project Owner</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Steering Committee --}}
                                @foreach($committee as $cm)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-violet-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-violet-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($cm->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $cm->name }}</span>
                                            <span class="text-[9px] text-violet-800 font-bold">🏛️ Steering Committee</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Core Members --}}
                                @foreach($members as $mb)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-blue-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-blue-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($mb->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $mb->name }}</span>
                                            <span class="text-[9px] text-blue-700 font-bold">🤝 Team Member</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($reviewProject->isPmRejected())
                            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-wider block text-rose-700">Previously Reported Reason:</span>
                                <p class="font-medium italic">"{{ $reviewProject->pm_rejection_reason }}"</p>
                                <span class="text-[9px] text-rose-500 font-mono block">Declined on {{ $reviewProject->pm_rejected_at?->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif

                    @else
                        {{-- Decline Reason Form --}}
                        <div class="space-y-3.5">
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed space-y-1">
                                <strong class="font-black block text-sm">⚠️ Declining Project Assignment</strong>
                                <p class="text-amber-800 font-medium">Please provide specific feedback, capacity constraints, or scope blockers so PMO Administration can review and re-assign.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-700 uppercase tracking-wider block">
                                    Reason / Issue Report <span class="text-rose-500">*</span>
                                </label>
                                <textarea wire:model="rejectionReasonInput" rows="4"
                                    placeholder="Explain why you cannot manage this project (e.g. resource bottlenecks, conflicting timelines, technical scope mismatch)..."
                                    class="w-full p-3.5 rounded-2xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all"></textarea>
                                @error('rejectionReasonInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Action Footer --}}
                <div class="flex-shrink-0 p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-wrap">
                    @if(!$showRejectModal)
                        <button wire:click="closeReviewModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 cursor-pointer transition-colors">
                            Cancel
                        </button>
                        <div class="flex items-center gap-2">
                            <button wire:click="openRejectForm({{ $reviewProject->id }})" type="button" class="px-4 py-2.5 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 cursor-pointer transition-colors">
                                ✕ Decline Assignment
                            </button>
                            <button wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all cursor-pointer flex items-center gap-1.5" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Accept &amp; Launch Workspace →</span>
                            </button>
                        </div>
                    @else
                        <button wire:click="$set('showRejectModal', false)" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 cursor-pointer transition-colors">
                            Back
                        </button>
                        <button wire:click="submitRejection" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md bg-rose-600 hover:bg-rose-700 cursor-pointer transition-colors">
                            Submit &amp; Decline
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
