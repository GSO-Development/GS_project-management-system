<div wire:poll.10s class="space-y-6 sm:space-y-7">
    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 1. TOP WELCOME HERO BANNER (Full-Width Panoramic Skyline)  -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl py-12 sm:py-16 lg:py-20 px-8 sm:px-12 lg:px-14 min-h-[220px] sm:min-h-[250px] lg:min-h-[280px] text-white flex flex-col justify-center" style="background: #2b040a;">
        <!-- Full Banner Background Image (Sunset City Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Welcome Banner" 
                class="w-full h-full object-cover object-center scale-[1.03] transform transition-transform duration-700"
            >
            <!-- Left Deep Crimson Velvet Gradient Scrim (Extends smoothly across) -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#180206] via-[#2d040c]/95 via-42% to-transparent w-full md:w-3/5"></div>

            <!-- Elegant Golden Curved Wave Divider Overlay -->
            <svg class="absolute inset-0 w-full h-full pointer-events-none hidden md:block" viewBox="0 0 1200 300" preserveAspectRatio="none" fill="none">
                <path d="M 440 0 Q 580 90 520 300" stroke="#f59e0b" stroke-width="3" opacity="0.85" />
                <path d="M 445 0 Q 585 90 525 300" stroke="#fbbf24" stroke-width="1.5" opacity="0.5" />
            </svg>

            <!-- Sunset Lighting & Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20"></div>
        </div>

        <!-- Top Ambient Glowing Gold Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-md shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 max-w-3xl space-y-2">
            <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-black text-white tracking-tight leading-tight drop-shadow-[0_4px_14px_rgba(0,0,0,0.95)]" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-sm sm:text-base lg:text-lg font-medium text-slate-200/95 drop-shadow-[0_2px_6px_rgba(0,0,0,0.9)]">
                Here's what's happening with your projects today.
            </p>
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
                                <button wire:click="openReviewModal({{ $proj->id }})" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 cursor-pointer shadow-xs transition-all">
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
    {{-- 1. TOP ROW: 6 COMPACT KPI CARDS                           --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
        <!-- 1. My Projects -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">My Projects</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $myProjectsCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                <span class="truncate">{{ $inProgressProjectsCount }} in progress</span>
            </div>
        </div>

        <!-- 2. My Tasks -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">My Tasks</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $myTasksCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                <span class="truncate">{{ $completedTasksCount }} completed</span>
            </div>
        </div>

        <!-- 3. Tasks Due Soon -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">Tasks Due Soon</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $tasksDueSoonCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                <span class="truncate">Due in next 7 days</span>
            </div>
        </div>

        <!-- 4. Pending Approvals -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">Pending Approvals</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $pendingApprovalsCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 flex-shrink-0"></span>
                <span class="truncate">Awaiting your action</span>
            </div>
        </div>

        <!-- 5. Hours This Week -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">Hours This Week</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $hoursThisWeekFormatted }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                <span class="truncate">of 40h logged</span>
            </div>
        </div>

        <!-- 6. Overall Progress -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[104px]">
            <div class="flex items-start justify-between gap-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[11px] font-semibold text-slate-500 block truncate leading-tight">Overall Progress</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none mt-1 block">{{ $overallAvgProgress }}%</span>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                <span class="truncate">Across all projects</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. MIDDLE ROW: 3 MAJOR CARDS (Overview, Progress, Milestones) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. My Projects Overview (Donut Chart & Legend) -->
        @php
            $circ = 238.76;
            $lenInProg = round(($inProgressPct / 100) * $circ, 2);
            $lenOnTrack = round(($onTrackPct / 100) * $circ, 2);
            $lenOnHold = round(($onHoldPct / 100) * $circ, 2);
            $lenComp = round(($completedPct / 100) * $circ, 2);

            $offInProg = 0;
            $offOnTrack = -$lenInProg;
            $offOnHold = -($lenInProg + $lenOnTrack);
            $offComp = -($lenInProg + $lenOnTrack + $lenOnHold);
        @endphp

        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        My Projects Overview
                    </h3>
                    <a href="{{ route('projects.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all projects</a>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-6 py-2">
                    <!-- Clean Vector Donut Chart -->
                    <div class="relative w-36 h-36 flex items-center justify-center flex-shrink-0">
                        <svg class="w-full h-full -rotate-90 overflow-visible" viewBox="0 0 100 100">
                            <!-- Background Track -->
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#f1f5f9" stroke-width="12" />
                            
                            <!-- 1. In Progress (Blue) -->
                            @if($lenInProg > 0)
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#3b82f6" stroke-width="12" stroke-dasharray="{{ $lenInProg }} 238.76" stroke-dashoffset="{{ $offInProg }}" stroke-linecap="round" />
                            @endif

                            <!-- 2. On Track (Emerald) -->
                            @if($lenOnTrack > 0)
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#10b981" stroke-width="12" stroke-dasharray="{{ $lenOnTrack }} 238.76" stroke-dashoffset="{{ $offOnTrack }}" stroke-linecap="round" />
                            @endif

                            <!-- 3. On Hold (Amber/Orange) -->
                            @if($lenOnHold > 0)
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#f59e0b" stroke-width="12" stroke-dasharray="{{ $lenOnHold }} 238.76" stroke-dashoffset="{{ $offOnHold }}" stroke-linecap="round" />
                            @endif

                            <!-- 4. Completed (Slate) -->
                            @if($lenComp > 0)
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#94a3b8" stroke-width="12" stroke-dasharray="{{ $lenComp }} 238.76" stroke-dashoffset="{{ $offComp }}" stroke-linecap="round" />
                            @endif
                        </svg>

                        <!-- Center Label -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                            <span class="text-2xl font-black text-slate-900 font-mono leading-none block">{{ $myProjectsCount }}</span>
                            <span class="text-[10px] font-semibold text-slate-400 mt-0.5 block">Total Projects</span>
                        </div>
                    </div>

                    <!-- Right Legend List -->
                    <div class="space-y-2.5 w-full sm:w-auto text-xs">
                        <div class="flex items-center justify-between gap-4">
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                                <span>In Progress</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $inProgressProjectsCount }} <span class="text-slate-400 font-normal">({{ $inProgressPct }}%)</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span>On Track</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $onTrackProjectsCount }} <span class="text-slate-400 font-normal">({{ $onTrackPct }}%)</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                <span>On Hold</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $onHoldProjectsCount }} <span class="text-slate-400 font-normal">({{ $onHoldPct }}%)</span>
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400 flex-shrink-0"></span>
                                <span>Completed</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $completedProjectsCount }} <span class="text-slate-400 font-normal">({{ $completedPct }}%)</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Tasks Progress (Interactive Multi-Line Chart) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Tasks Progress
                    </h3>
                    <select class="px-2.5 py-1 rounded-lg border border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-700 outline-none cursor-pointer">
                        <option>This Week</option>
                        <option>This Month</option>
                    </select>
                </div>

                <!-- Legend Top -->
                <div class="flex items-center justify-center gap-4 text-[11px] font-bold text-slate-600 mb-2">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Completed</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>In Progress</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Pending</span>
                    </span>
                </div>

                <!-- Multi-Line Chart SVG -->
                @php
                    $yMax = $chartYMax ?? 10;
                    $yMid2 = (int) round($yMax * 0.66);
                    $yMid1 = (int) round($yMax * 0.33);

                    // Compute baseline Y mapping: y = 110 - (val / yMax) * 90
                    $getY = fn($val) => 110 - min(90, max(0, (int) round(($val / max(1, $yMax)) * 90)));

                    $yProg = $getY($inProgressTasksCount);
                    $yComp = $getY($completedTasksCount);
                    $yPend = $getY($pendingTasksCount);
                @endphp
                <div class="w-full">
                    <svg viewBox="0 0 340 140" class="w-full h-auto select-none" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <!-- Grid Lines -->
                        <text x="18" y="24" text-anchor="end" font-size="8.5" font-weight="700" fill="#94a3b8">{{ $yMax }}</text>
                        <line x1="24" y1="20" x2="330" y2="20" stroke="#f1f5f9" stroke-width="1" />

                        <text x="18" y="54" text-anchor="end" font-size="8.5" font-weight="700" fill="#94a3b8">{{ $yMid2 }}</text>
                        <line x1="24" y1="50" x2="330" y2="50" stroke="#f1f5f9" stroke-width="1" />

                        <text x="18" y="84" text-anchor="end" font-size="8.5" font-weight="700" fill="#94a3b8">{{ $yMid1 }}</text>
                        <line x1="24" y1="80" x2="330" y2="80" stroke="#f1f5f9" stroke-width="1" />

                        <text x="18" y="114" text-anchor="end" font-size="8.5" font-weight="700" fill="#94a3b8">0</text>
                        <line x1="24" y1="110" x2="330" y2="110" stroke="#cbd5e1" stroke-width="1.2" />

                        <!-- Line 1: In Progress (Blue) -->
                        <path d="M 45,{{ $yProg }} L 90,{{ max(20, $yProg - 2) }} L 135,{{ max(20, $yProg - 6) }} L 180,{{ max(20, $yProg - 3) }} L 225,{{ max(20, $yProg - 8) }} L 270,{{ max(20, $yProg - 4) }} L 315,{{ $yProg }}" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" />
                        <circle cx="45" cy="{{ $yProg }}" r="3" fill="#3b82f6" />
                        <circle cx="90" cy="{{ max(20, $yProg - 2) }}" r="3" fill="#3b82f6" />
                        <circle cx="135" cy="{{ max(20, $yProg - 6) }}" r="3" fill="#3b82f6" />
                        <circle cx="180" cy="{{ max(20, $yProg - 3) }}" r="3" fill="#3b82f6" />
                        <circle cx="225" cy="{{ max(20, $yProg - 8) }}" r="3" fill="#3b82f6" />
                        <circle cx="270" cy="{{ max(20, $yProg - 4) }}" r="3" fill="#3b82f6" />
                        <circle cx="315" cy="{{ $yProg }}" r="3" fill="#3b82f6" />

                        <!-- Line 2: Completed (Emerald) -->
                        <path d="M 45,{{ min(110, $yComp + 8) }} L 90,{{ min(110, $yComp + 5) }} L 135,{{ $yComp }} L 180,{{ min(110, $yComp + 2) }} L 225,{{ min(110, $yComp + 4) }} L 270,{{ min(110, $yComp + 2) }} L 315,{{ $yComp }}" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" />
                        <circle cx="45" cy="{{ min(110, $yComp + 8) }}" r="3" fill="#10b981" />
                        <circle cx="90" cy="{{ min(110, $yComp + 5) }}" r="3" fill="#10b981" />
                        <circle cx="135" cy="{{ $yComp }}" r="3" fill="#10b981" />
                        <circle cx="180" cy="{{ min(110, $yComp + 2) }}" r="3" fill="#10b981" />
                        <circle cx="225" cy="{{ min(110, $yComp + 4) }}" r="3" fill="#10b981" />
                        <circle cx="270" cy="{{ min(110, $yComp + 2) }}" r="3" fill="#10b981" />
                        <circle cx="315" cy="{{ $yComp }}" r="3" fill="#10b981" />

                        <!-- Line 3: Pending (Amber) -->
                        <path d="M 45,{{ max(20, $yPend - 10) }} L 90,{{ max(20, $yPend - 6) }} L 135,{{ max(20, $yPend - 4) }} L 180,{{ $yPend }} L 225,{{ min(110, $yPend + 2) }} L 270,{{ min(110, $yPend + 4) }} L 315,{{ $yPend }}" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" />
                        <circle cx="45" cy="{{ max(20, $yPend - 10) }}" r="3" fill="#f59e0b" />
                        <circle cx="90" cy="{{ max(20, $yPend - 6) }}" r="3" fill="#f59e0b" />
                        <circle cx="135" cy="{{ max(20, $yPend - 4) }}" r="3" fill="#f59e0b" />
                        <circle cx="180" cy="{{ $yPend }}" r="3" fill="#f59e0b" />
                        <circle cx="225" cy="{{ min(110, $yPend + 2) }}" r="3" fill="#f59e0b" />
                        <circle cx="270" cy="{{ min(110, $yPend + 4) }}" r="3" fill="#f59e0b" />
                        <circle cx="315" cy="{{ $yPend }}" r="3" fill="#f59e0b" />

                        <!-- X-Axis Days -->
                        <text x="45" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Mon</text>
                        <text x="90" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Tue</text>
                        <text x="135" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Wed</text>
                        <text x="180" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Thu</text>
                        <text x="225" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Fri</text>
                        <text x="270" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Sat</text>
                        <text x="315" y="128" text-anchor="middle" font-size="8.5" font-weight="700" fill="#64748b">Sun</text>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. Upcoming Milestones -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Upcoming Milestones
                    </h3>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($upcomingMilestones as $idx => $m)
                        @php
                            $daysLeft = (int) now()->today()->diffInDays($m->end_date, false);
                            $colors = ['rose', 'blue', 'emerald', 'purple'];
                            $cColor = $colors[$idx % count($colors)];
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
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

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <div class="text-center px-2 py-0.5 rounded-lg bg-slate-100/80 border border-slate-200/80">
                                    <span class="text-[8px] font-black text-slate-400 uppercase block leading-tight">{{ $m->end_date->format('M') }}</span>
                                    <span class="text-xs font-black text-slate-800 font-mono leading-tight block">{{ $m->end_date->format('d') }}</span>
                                </div>
                                <span class="text-[10px] font-bold {{ $daysLeft <= 3 ? 'text-rose-600' : 'text-slate-500' }} min-w-14 text-right">
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
    {{-- 3. BOTTOM ROW: 3 MAJOR CARDS (Tasks Due, Activity, Approvals) --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. My Tasks Due Soon -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        My Tasks Due Soon
                    </h3>
                    <a href="{{ route('my-tasks.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($myTasksDueSoonList as $task)
                        @php
                            $pri = ucfirst($task->priority?->value ?? 'medium');
                            $priCls = $pri === 'High' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($pri === 'Medium' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-7 h-7 rounded-full bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        {{ $task->project->name ?? 'George Steuart' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider border {{ $priCls }}">
                                    {{ $pri }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-500 font-mono">
                                    {{ $task->end_date ? $task->end_date->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>All clear! No tasks due soon.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 2. Recent Activity -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Recent Activity
                    </h3>
                    <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentActivities as $act)
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
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
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
        </div>

        <!-- 3. My Approvals -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        My Approvals
                    </h3>
                    <a href="{{ route('approvals.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3">
                    @forelse($myApprovalsList as $app)
                        @php
                            $typeStr = strtolower($app->request_type?->value ?? $app->request_type?->name ?? 'approval');
                            $shortTag = 'Approval';
                            $tagColor = 'blue';

                            if (str_contains($typeStr, 'budget')) {
                                $shortTag = 'Budget';
                                $tagColor = 'emerald';
                            } elseif (str_contains($typeStr, 'scope')) {
                                $shortTag = 'Scope Change';
                                $tagColor = 'purple';
                            } elseif (str_contains($typeStr, 'resource')) {
                                $shortTag = 'Resource';
                                $tagColor = 'amber';
                            } elseif (str_contains($typeStr, 'plan') || str_contains($typeStr, 'wbs')) {
                                $shortTag = 'Project Plan';
                                $tagColor = 'blue';
                            }

                            $bgCls = match($tagColor) {
                                'emerald' => 'background-color: #ecfdf5; color: #047857; border-color: #a7f3d0;',
                                'amber' => 'background-color: #fffbeb; color: #b45309; border-color: #fde68a;',
                                'purple' => 'background-color: #faf5ff; color: #6b21a8; border-color: #e9d5ff;',
                                default => 'background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;',
                            };
                            $iconBg = match($tagColor) {
                                'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
                                'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
                                default => 'bg-blue-50 text-blue-600 border-blue-100',
                            };
                            $titleText = ($app->request_type?->label() ?? 'Project Approval') . ' - ' . ($app->project->name ?? 'George Steuart');
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 rounded-xl {{ $iconBg }} border flex items-center justify-center flex-shrink-0 shadow-2xs">
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
                                        <span style="{{ $bgCls }}; font-size: 9px; line-height: 1.2; padding: 2px 6px; border-radius: 4px; font-weight: 700; border-width: 1px; display: inline-block; white-space: nowrap; flex-shrink: 0;">
                                            {{ $shortTag }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 font-mono flex-shrink-0">
                                {{ $app->created_at->format('M d, Y') }}
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

    <!-- 1. Review Leadership Modal -->
    @if($showReviewModal && $reviewProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95">
                <div style="background: linear-gradient(135deg, #18080c 0%, #300c16 50%, #1a080e 100%); color: #ffffff;" class="p-6 flex items-center justify-between border-b border-rose-950/40">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl">
                            👑
                        </div>
                        <div>
                            <h3 class="text-base font-black text-white">Project Leadership Review</h3>
                            <p class="text-xs text-rose-200/80">{{ $reviewProject->code }} · {{ $reviewProject->subsidiary->name ?? 'George Steuart' }}</p>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" type="button" class="p-2 text-white/70 hover:text-white rounded-xl hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-black text-slate-900">{{ $reviewProject->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $reviewProject->description ?: 'No detailed scope description provided.' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs font-medium">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Category</span>
                            <span class="font-bold text-slate-800">{{ $reviewProject->category ?: 'General' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Priority</span>
                            <span class="font-bold text-slate-800">{{ ucfirst($reviewProject->priority?->value ?? 'medium') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button wire:click="closeReviewModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200">
                            Cancel
                        </button>
                        <button wire:click="openRejectForm({{ $reviewProject->id }})" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200">
                            Decline Assignment
                        </button>
                        <button wire:click="acceptProjectAssignment({{ $reviewProject->id }}, true)" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25">
                            Accept Leadership &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Decline Leadership Modal -->
    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-md p-6 animate-in fade-in zoom-in-95">
                <h3 class="text-base font-black text-slate-900 mb-1">Decline Leadership Assignment</h3>
                <p class="text-xs text-slate-500 mb-4">Please explain the constraint, capacity bottleneck or reason for declining.</p>

                <div class="space-y-4">
                    <textarea wire:model="rejectionReasonInput" rows="4" placeholder="Explain reason (e.g. Current workload over capacity, skill domain mismatch)..." class="w-full p-3 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:border-[#c3122e]"></textarea>
                    @error('rejectionReasonInput') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button wire:click="closeReviewModal" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button wire:click="submitRejection" type="button" class="btn-danger text-xs">Confirm &amp; Notify PMO</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
