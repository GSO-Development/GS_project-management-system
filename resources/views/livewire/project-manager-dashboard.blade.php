<div wire:poll.15s class="pm-exec-root space-y-6 sm:space-y-8">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700;800&display=swap');

        .pm-exec-root {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #0f172a;
        }

        .pm-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Ambient Glow & Gradients */
        .pm-hero-gradient {
            background: linear-gradient(135deg, #0b0f19 0%, #151a2e 45%, #2a0b14 100%);
            position: relative;
            overflow: hidden;
        }

        .pm-hero-glow-1 {
            position: absolute;
            top: -20%;
            right: 10%;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(195, 18, 46, 0.28) 0%, rgba(195, 18, 46, 0) 70%);
            filter: blur(50px);
            pointer-events: none;
        }

        .pm-hero-glow-2 {
            position: absolute;
            bottom: -30%;
            left: 5%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.18) 0%, rgba(59, 130, 246, 0) 70%);
            filter: blur(60px);
            pointer-events: none;
        }

        /* Glassmorphic Surfaces */
        .pm-glass-pill {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .pm-glass-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pm-glass-btn:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        /* Creative Card Styles */
        .pm-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pm-kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.1rem 1.15rem;
            position: relative;
            overflow: hidden;
            transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pm-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -6px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        /* Custom Scrollbars */
        .pm-custom-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .pm-custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }
        .pm-custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        .pm-custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animated Pulsing Dot */
        @keyframes pm-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(1.15); }
        }
        .pm-pulse-live {
            animation: pm-pulse 2s infinite ease-in-out;
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. EXECUTIVE COMMAND COCKPIT (CREATIVE HERO HEADER)        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="pm-hero-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-800/80">
        <div class="pm-hero-glow-1"></div>
        <div class="pm-hero-glow-2"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 lg:gap-8">
            {{-- Left Info Block --}}
            <div class="space-y-4 max-w-2xl">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="pm-glass-pill px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-rose-300 flex items-center gap-1.5 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 pm-pulse-live"></span>
                        <span>Executive Cockpit</span>
                    </span>
                    <span class="text-xs font-semibold text-slate-300/80">
                        {{ now()->format('l, F d, Y') }}
                    </span>
                    <span class="text-slate-500 text-xs">&bull;</span>
                    <span class="pm-glass-pill px-2.5 py-0.5 rounded-full text-[11px] font-bold text-slate-200">
                        George Steuart Group
                    </span>
                </div>

                <div>
                    @php
                        $currentHour = (int) now()->format('H');
                        $greeting = $currentHour < 12 ? 'Good Morning' : ($currentHour < 17 ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white leading-tight">
                        {{ $greeting }}, <span class="bg-gradient-to-r from-white via-rose-100 to-rose-300 bg-clip-text text-transparent">{{ auth()->user()->name }}</span> 👋
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300/90 font-medium mt-1.5 leading-relaxed">
                        You have <strong class="text-white font-bold">{{ $myProjectsCount }} projects</strong> under active leadership with <strong class="text-rose-300 font-bold">{{ $tasksDueSoonCount }} deliverables</strong> scheduled this week.
                    </p>
                </div>

                {{-- Quick Metrics Chips --}}
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap pt-1">
                    <div class="pm-glass-pill px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        <span class="text-slate-300 font-medium">In Progress:</span>
                        <span class="font-black text-white pm-mono">{{ $inProgressProjectsCount }} Projects</span>
                    </div>
                    <div class="pm-glass-pill px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span class="text-slate-300 font-medium">Pending Tasks:</span>
                        <span class="font-black text-white pm-mono">{{ $inProgressTasksCount + $pendingTasksCount }}</span>
                    </div>
                    <div class="pm-glass-pill px-3 py-1.5 rounded-xl flex items-center gap-2 text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="text-slate-300 font-medium">Completed:</span>
                        <span class="font-black text-white pm-mono">{{ $completedTasksCount }}</span>
                    </div>
                </div>

                {{-- Action Buttons Strip --}}
                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap pt-2">
                    @if(auth()->user()?->canCreateProject())
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-lg hover:shadow-rose-900/50 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer no-underline flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Initiate Project</span>
                        </a>
                    @endif

                    <a href="{{ route('my-tasks.index') }}" class="pm-glass-btn inline-flex items-center justify-center gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs font-bold text-white cursor-pointer no-underline flex-shrink-0">
                        <svg class="w-4 h-4 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>My Tasks ({{ $myTasksCount }})</span>
                    </a>

                    <a href="{{ route('calendar.index') }}" class="pm-glass-btn inline-flex items-center justify-center gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs font-bold text-white cursor-pointer no-underline flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Calendar</span>
                    </a>

                    <a href="{{ route('risks.index') }}" class="pm-glass-btn inline-flex items-center justify-center gap-2 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs font-bold text-white cursor-pointer no-underline flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Risks Hub</span>
                    </a>
                </div>
            </div>

            {{-- Right Portfolio Delivery Speedometer / Circular Gauge --}}
            <div class="pm-glass-pill rounded-2xl p-5 sm:p-6 flex items-center justify-center sm:justify-between gap-6 flex-shrink-0 lg:w-80 shadow-inner">
                <div class="flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-rose-300 block">Portfolio Health</span>
                        <h4 class="text-sm font-black text-white mt-0.5">Overall Delivery</h4>
                        <p class="text-[11px] text-slate-400 mt-1 leading-snug">Average execution across projects</p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center gap-3 text-xs">
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase">On-Track</span>
                            <span class="text-emerald-400 font-mono font-bold">{{ $onTrackProjectsCount }}</span>
                        </div>
                        <div class="w-px h-6 bg-white/10"></div>
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase">Done</span>
                            <span class="text-white font-mono font-bold">{{ $completedProjectsCount }}</span>
                        </div>
                        <div class="w-px h-6 bg-white/10"></div>
                        <div>
                            <span class="text-[9px] text-slate-400 block font-bold uppercase">Velocity</span>
                            <span class="text-rose-300 font-mono font-bold">{{ $overallAvgProgress }}%</span>
                        </div>
                    </div>
                </div>

                {{-- SVG Circular Gauge --}}
                <div class="relative w-24 h-24 sm:w-28 sm:h-28 flex items-center justify-center flex-shrink-0">
                    <svg class="w-full h-full -rotate-90 transform" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)" stroke-width="8" fill="transparent"/>
                        @php
                            $circumference = 2 * pi() * 40;
                            $strokeOffset = $circumference - (($overallAvgProgress / 100) * $circumference);
                        @endphp
                        <circle cx="50" cy="50" r="40" stroke="url(#pmGaugeGrad)" stroke-width="8" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $strokeOffset }}" stroke-linecap="round" fill="transparent" style="transition: stroke-dashoffset 1s ease-in-out;"/>
                        <defs>
                            <linearGradient id="pmGaugeGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#c3122e"/>
                                <stop offset="50%" stop-color="#f43f5e"/>
                                <stop offset="100%" stop-color="#38bdf8"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-lg sm:text-xl font-black text-white pm-mono leading-none">{{ $overallAvgProgress }}%</span>
                        <span class="text-[8px] font-extrabold uppercase tracking-wider text-slate-300 mt-1">RATE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 👑 PENDING PROJECT LEADERSHIP ASSIGNMENT ACTION CENTER     --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($pendingInvitations->count() > 0)
        <div class="rounded-3xl overflow-hidden shadow-lg border border-rose-200 bg-white animate-in fade-in duration-300">
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
                        <p class="text-xs text-white/90 font-medium mt-0.5">PMO Administration has assigned you as Project Leader. Review project blueprint to accept leadership.</p>
                    </div>
                </div>
            </div>

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
                                <button wire:click="openReviewModal({{ $proj->id }})" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-slate-100 border border-slate-200 cursor-pointer shadow-xs transition-all">
                                    Review Blueprint
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
    {{-- 2. 5-PILLAR METRIC STRIP (CREATIVE REFINED CARDS)          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        {{-- Card 1: My Projects --}}
        <a href="{{ route('projects.index') }}" class="pm-kpi-card group no-underline text-inherit flex flex-col justify-between border-t-2 border-t-blue-500">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-blue-600 transition-colors">Portfolio</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-2xs group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 pm-mono tracking-tight leading-none">{{ $myProjectsCount }}</div>
                <div class="text-xs font-bold text-slate-600 mt-1">My Projects</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                <span class="text-blue-600 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span>{{ $inProgressProjectsCount }} Active</span>
                </span>
                <span class="text-slate-400 font-mono">{{ $onTrackProjectsCount }} On Track</span>
            </div>
        </a>

        {{-- Card 2: My Tasks --}}
        <a href="{{ route('my-tasks.index') }}" class="pm-kpi-card group no-underline text-inherit flex flex-col justify-between border-t-2 border-t-emerald-500">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-emerald-600 transition-colors">Deliverables</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-2xs group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 pm-mono tracking-tight leading-none">{{ $myTasksCount }}</div>
                <div class="text-xs font-bold text-slate-600 mt-1">Assigned Tasks</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                <span class="text-emerald-600 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ $completedTasksCount }} Done</span>
                </span>
                <span class="text-slate-400 font-mono">{{ $myTasksCount > 0 ? round(($completedTasksCount / $myTasksCount) * 100) : 0 }}% Finished</span>
            </div>
        </a>

        {{-- Card 3: Due in 7 Days --}}
        <a href="{{ route('my-tasks.index') }}" class="pm-kpi-card group no-underline text-inherit flex flex-col justify-between border-t-2 border-t-purple-500">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-purple-600 transition-colors">Urgent</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center shadow-2xs group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 pm-mono tracking-tight leading-none">{{ $tasksDueSoonCount }}</div>
                <div class="text-xs font-bold text-slate-600 mt-1">Due in 7 Days</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                @if($overdueTasksCount > 0)
                    <span class="text-rose-600 flex items-center gap-1 font-black">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 pm-pulse-live"></span>
                        <span>{{ $overdueTasksCount }} Overdue</span>
                    </span>
                @else
                    <span class="text-purple-600">📅 Sprints On Track</span>
                @endif
                <span class="text-slate-400 font-mono">Deadlines</span>
            </div>
        </a>

        {{-- Card 4: Approvals --}}
        <a href="{{ route('approvals.index') }}" class="pm-kpi-card group no-underline text-inherit flex flex-col justify-between border-t-2 border-t-amber-500">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 group-hover:text-amber-600 transition-colors">Governance</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-2xs group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 pm-mono tracking-tight leading-none">{{ $pendingApprovalsCount }}</div>
                <div class="text-xs font-bold text-slate-600 mt-1">Pending Approvals</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                @if($pendingApprovalsCount > 0)
                    <span class="text-amber-700 flex items-center gap-1 font-black">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 pm-pulse-live"></span>
                        <span>Action Required</span>
                    </span>
                @else
                    <span class="text-slate-500">✓ All Clear</span>
                @endif
                <span class="text-slate-400 font-mono">Sign-offs</span>
            </div>
        </a>

        {{-- Card 5: Logged Hours --}}
        <div class="pm-kpi-card flex flex-col justify-between border-t-2 border-t-[#c3122e] col-span-2 sm:col-span-1">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Timesheets</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 pm-mono tracking-tight leading-none">{{ $hoursThisWeekFormatted }}</div>
                <div class="text-xs font-bold text-slate-600 mt-1">Hours This Week</div>
            </div>
            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between text-[10px] font-bold">
                <span class="text-[#c3122e] flex items-center gap-1">
                    <span>⏱️ Logged Effort</span>
                </span>
                <span class="text-slate-400 font-mono">Active Sprint</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. BENTO GRID ARCHITECTURE (8-COL CORE + 4-COL INTEL)      --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- LEFT CORE COLUMN (8 COLS ON DESKTOP)                       --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- 1. ACTIVE PROJECTS PORTFOLIO SHOWCASE --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Active Projects Focus</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 pm-mono">{{ $myProjectsCount }}</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Projects currently under your leadership and oversight</p>
                        </div>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-black text-[#c3122e] hover:text-[#9c0d23] hover:underline flex items-center gap-1">
                        <span>View all</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="space-y-3.5">
                    @forelse($myProjects->take(5) as $proj)
                        @php
                            $progress = (int) round($proj->overall_progress ?? 0);
                            $healthVal = $proj->health?->value ?? 'on_track';
                            $healthLabel = match($healthVal) {
                                'at_risk' => 'At Risk',
                                'needs_attention' => 'Needs Attention',
                                default => 'On Track',
                            };
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
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/60 border border-slate-200/80 hover:bg-white hover:border-slate-300 hover:shadow-md transition-all group">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-mono text-[10px] font-black px-2 py-0.5 rounded-md bg-rose-50 text-[#c3122e] border border-rose-200/80">
                                            {{ $proj->code }}
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-500 truncate">
                                            {{ $proj->subsidiary->name ?? 'George Steuart Group' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $healthBadgeClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $healthDotClass }}"></span>
                                            <span>{{ $healthLabel }}</span>
                                        </span>
                                    </div>
                                    <h4 class="text-sm font-black text-slate-900 group-hover:text-[#c3122e] transition-colors mt-1 truncate">
                                        {{ $proj->name }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('projects.show', $proj) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 shadow-2xs hover:shadow-xs transition-all no-underline">
                                        <span>Workspace</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Progress Bar & Metrics Strip --}}
                            <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-slate-600 mb-1">
                                        <span class="text-slate-400">Milestone Progress</span>
                                        <span class="pm-mono text-slate-900">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200/70 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-500" style="width: {{ max(4, $progress) }}%; background: linear-gradient(90deg, #c3122e 0%, #f43f5e 100%);"></div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500 flex-shrink-0">
                                    <span>📅 {{ $proj->deadline ? $proj->deadline->format('M d') : 'No deadline' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <svg class="w-9 h-9 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <p class="font-bold text-slate-600">No active projects assigned yet.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Projects assigned to you by PMO will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 2. TASKS DUE & SPRINT DEADLINES MATRIX --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Tasks Due &amp; Deadlines</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200 pm-mono">{{ $myTasksDueSoonList->count() }}</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">High-priority deliverables due within current sprint horizon</p>
                        </div>
                    </div>
                    <a href="{{ route('my-tasks.index') }}" class="text-xs font-black text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                        <span>All tasks</span>
                        <span>&rarr;</span>
                    </a>
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
                        <a href="{{ route('projects.show', ['project' => $task->project_id]) }}" class="flex items-center justify-between gap-3 p-3 sm:p-3.5 rounded-2xl bg-white hover:bg-slate-50/90 transition-all group border border-slate-200/80 hover:border-slate-300 shadow-2xs hover:shadow-xs no-underline text-inherit">
                            <div class="flex items-center gap-3.5 min-w-0 flex-1">
                                {{-- Date Badge --}}
                                <div class="text-center px-2.5 py-1.5 rounded-xl {{ $isOverdue ? 'bg-rose-50 border border-rose-200 text-rose-700' : ($isDueToday ? 'bg-amber-50 border border-amber-200 text-amber-800' : 'bg-slate-100 border border-slate-200 text-slate-700') }} flex-shrink-0 min-w-12">
                                    <span class="text-[8.5px] font-black uppercase block leading-none opacity-80 pm-mono">{{ $task->end_date ? $task->end_date->format('M') : 'DUE' }}</span>
                                    <span class="text-xs sm:text-sm font-black pm-mono block leading-tight mt-0.5">{{ $task->end_date ? $task->end_date->format('d') : '--' }}</span>
                                </div>

                                {{-- Details --}}
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors leading-snug" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap text-[10.5px]">
                                        <span class="text-slate-500 font-semibold truncate max-w-[130px]">
                                            📁 {{ $task->project->name ?? 'Project' }}
                                        </span>
                                        <span class="text-slate-300">&bull;</span>
                                        <span class="text-slate-600 font-medium truncate max-w-[120px]">
                                            👤 {{ $task->assignedUser->name ?? 'Unassigned' }}
                                        </span>
                                        <span class="text-slate-300">&bull;</span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold border {{ $priCls }}">
                                            {{ $pri }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Countdown Badge --}}
                            <div class="flex-shrink-0">
                                @if($isOverdue)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 pm-pulse-live"></span>
                                        <span>Overdue ({{ abs($daysLeft) }}d)</span>
                                    </span>
                                @elseif($isDueToday)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 pm-pulse-live"></span>
                                        <span>Due Today</span>
                                    </span>
                                @elseif($daysLeft === 1)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span>Tomorrow</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span>In {{ $daysLeft }} days</span>
                                    </span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="font-bold text-slate-600">All deliverables clear!</p>
                            <p class="text-[11px] text-slate-400">No overdue or pending tasks due in the next 7 days.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 3. UPCOMING MILESTONES ROADMAP --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Upcoming Milestones</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-200 pm-mono">{{ $upcomingMilestones->count() }}</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Critical project checkpoints, governance sign-offs, and deliverables</p>
                        </div>
                    </div>
                    <a href="{{ route('calendar.index') }}" class="text-xs font-black text-purple-700 hover:text-purple-800 hover:underline flex items-center gap-1">
                        <span>Roadmap</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($upcomingMilestones as $idx => $m)
                        @php
                            $daysLeft = (int) now()->today()->diffInDays($m->end_date, false);
                            $colors = ['rose', 'blue', 'emerald', 'purple'];
                            $cColor = $colors[$idx % count($colors)];
                        @endphp
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 hover:border-slate-300 hover:shadow-xs transition-all flex items-center justify-between gap-3 group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-{{ $cColor }}-50 border border-{{ $cColor }}-100 text-{{ $cColor }}-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $m->title }}">
                                        {{ $m->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block mt-0.5">
                                        {{ $m->project->name ?? 'George Steuart' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="text-center px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200">
                                    <span class="text-[8px] font-black text-slate-400 uppercase block leading-tight">{{ $m->end_date->format('M') }}</span>
                                    <span class="text-xs font-black text-slate-800 pm-mono leading-tight block">{{ $m->end_date->format('d') }}</span>
                                </div>
                                <span class="text-[10px] font-bold {{ $daysLeft <= 3 ? 'text-rose-600 font-black' : 'text-slate-500' }} min-w-14 text-right">
                                    {{ $daysLeft < 0 ? 'Overdue' : ($daysLeft === 0 ? 'Today' : ($daysLeft === 1 ? 'Tomorrow' : "In {$daysLeft}d")) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-xs text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                            <p class="font-bold text-slate-600">No upcoming milestones scheduled.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Add milestones in your project WBS to track key horizon events.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ────────────────────────────────────────────────────────── --}}
        {{-- RIGHT INTELLIGENCE COLUMN (4 COLS ON DESKTOP)              --}}
        {{-- ────────────────────────────────────────────────────────── --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- 1. SPRINT VELOCITY & TASK TREND CHART (INTERACTIVE!) --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Sprint Velocity</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Real-time completion rhythm</p>
                    </div>

                    {{-- Period Toggle Buttons --}}
                    <div class="flex items-center p-1 rounded-xl bg-slate-100 border border-slate-200/80 text-[10px] font-bold">
                        <button wire:click="setChartPeriod('week')" type="button" class="px-2.5 py-1 rounded-lg cursor-pointer transition-all {{ $chartPeriod === 'week' ? 'bg-white text-slate-900 shadow-2xs font-black' : 'text-slate-500 hover:text-slate-800' }}">
                            Week
                        </button>
                        <button wire:click="setChartPeriod('month')" type="button" class="px-2.5 py-1 rounded-lg cursor-pointer transition-all {{ $chartPeriod === 'month' ? 'bg-white text-slate-900 shadow-2xs font-black' : 'text-slate-500 hover:text-slate-800' }}">
                            Month
                        </button>
                    </div>
                </div>

                {{-- Chart Legend --}}
                <div class="flex items-center justify-between text-[10px] font-bold text-slate-500 px-1 mb-4">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Completed</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <span>In Progress</span>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        <span>Backlog</span>
                    </span>
                </div>

                {{-- Chart Visual Container --}}
                <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-200/80">
                    <div class="h-44 flex items-end justify-between gap-2 pt-4 px-1">
                        @foreach($chartPoints as $pt)
                            @php
                                $cVal = $pt['completed'];
                                $ipVal = $pt['in_progress'];
                                $pVal = $pt['pending'];
                                $tot = max(1, $cVal + $ipVal + $pVal);
                                
                                // Heights proportional to yMax
                                $cH = min(100, max(6, round(($cVal / max(1, $chartYMax)) * 100)));
                                $ipH = min(100, max(6, round(($ipVal / max(1, $chartYMax)) * 100)));
                                $pH = min(100, max(4, round(($pVal / max(1, $chartYMax)) * 100)));
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end group relative">
                                {{-- Hover Tooltip --}}
                                <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 px-2 py-1 rounded-lg bg-slate-900 text-white text-[9px] font-bold whitespace-nowrap shadow-md">
                                    <div>{{ $pt['label'] }} ({{ $pt['sub'] }})</div>
                                    <div class="text-emerald-400">{{ $cVal }} done · {{ $ipVal }} in-prog</div>
                                </div>

                                {{-- Bar Bars Stack --}}
                                <div class="w-full max-w-[22px] flex items-end justify-center gap-0.5 h-32 rounded-lg bg-slate-200/40 p-0.5">
                                    <div class="w-1/3 rounded-t-sm bg-emerald-500 transition-all duration-300 group-hover:bg-emerald-400" style="height: {{ $cH }}%;"></div>
                                    <div class="w-1/3 rounded-t-sm bg-blue-500 transition-all duration-300 group-hover:bg-blue-400" style="height: {{ $ipH }}%;"></div>
                                    <div class="w-1/3 rounded-t-sm bg-slate-300 transition-all duration-300 group-hover:bg-slate-400" style="height: {{ $pH }}%;"></div>
                                </div>

                                {{-- Label --}}
                                <div class="text-center">
                                    <span class="text-[10px] font-black {{ $pt['isToday'] ? 'text-[#c3122e] underline' : 'text-slate-500' }} block leading-none">
                                        {{ $pt['label'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. RISKS & BLOCKERS WATCH (RADAR CENTER) --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Risks &amp; Blockers</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">{{ $openRisks->count() }} Open Risks · {{ $currentBlockers->count() }} Blockers</p>
                        </div>
                    </div>
                    <a href="{{ route('risks.index') }}" class="text-xs font-black text-rose-700 hover:underline">Hub &rarr;</a>
                </div>

                <div class="space-y-2.5 max-h-[260px] overflow-y-auto pr-0.5 pm-custom-scroll">
                    {{-- Active Blockers --}}
                    @foreach($currentBlockers->take(3) as $blocker)
                        <div class="p-3 rounded-2xl bg-rose-50/70 border border-rose-200/80 hover:bg-rose-50 transition-all space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 pm-pulse-live flex-shrink-0"></span>
                                    <span class="text-xs font-black text-slate-900 truncate" title="{{ $blocker->description ?? $blocker->reason ?? 'Task Blocker' }}">
                                        {{ $blocker->description ?? $blocker->reason ?? 'Task Blocker' }}
                                    </span>
                                </div>
                                <button wire:click="openResolveBlockerModal({{ $blocker->id }})" type="button" class="px-2 py-0.5 rounded text-[9px] font-black bg-rose-600 text-white hover:bg-rose-700 cursor-pointer shadow-2xs flex-shrink-0">
                                    Resolve
                                </button>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-500 pl-3.5">
                                <span class="truncate max-w-[170px] font-semibold text-slate-700">{{ $blocker->wbsItem->project->name ?? 'Project Task' }}</span>
                                <span class="text-rose-600 font-bold pm-mono">{{ $blocker->created_at ? $blocker->created_at->diffForHumans(null, true) : 'Active' }}</span>
                            </div>
                        </div>
                    @endforeach

                    {{-- Open Project Risks --}}
                    @foreach($openRisks->take(3) as $risk)
                        @php
                            $probColor = match(strtolower($risk->probability ?? 'medium')) {
                                'high' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-blue-50 text-blue-700 border-blue-200',
                            };
                        @endphp
                        <div class="p-3 rounded-2xl bg-slate-50/70 border border-slate-200/70 hover:bg-white hover:border-slate-300 transition-all space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                    <span class="text-xs font-bold text-slate-900 truncate" title="{{ $risk->title }}">
                                        {{ $risk->title }}
                                    </span>
                                </div>
                                <span class="px-1.5 py-0.2 rounded text-[8.5px] font-bold border {{ $probColor }} uppercase pm-mono">
                                    {{ $risk->probability ?? 'Risk' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400 pl-3">
                                <span class="truncate max-w-[160px] text-slate-500 font-medium">{{ $risk->project->name ?? 'General Project' }}</span>
                                <span class="font-mono text-[9px]">{{ $risk->category ?? 'Gov' }}</span>
                            </div>
                        </div>
                    @endforeach

                    @if($currentBlockers->isEmpty() && $openRisks->isEmpty())
                        <div class="py-6 text-center text-xs text-slate-400 space-y-1">
                            <div class="w-9 h-9 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto shadow-2xs">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="font-black text-slate-800 text-xs">Clean Radar Horizon</p>
                            <p class="text-[10.5px] text-slate-400">Zero active blockers reported across your projects.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                    <button wire:click="openAddRiskModal" type="button" class="text-xs font-black text-[#c3122e] hover:underline flex items-center gap-1 cursor-pointer">
                        <span>+ Log Project Risk</span>
                    </button>
                    <a href="{{ route('risks.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                        View Matrix &rarr;
                    </a>
                </div>
            </div>

            {{-- 3. RECENT ACTIVITY STREAM (REAL-TIME FEED) --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Recent Activity</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">Live operational timeline</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pm-pulse-live"></span>
                        <span>Live</span>
                    </span>
                </div>

                <div class="space-y-3 max-h-[250px] overflow-y-auto pr-0.5 pm-custom-scroll">
                    @forelse($recentActivities->take(5) as $act)
                        @php
                            $actionRaw = strtolower($act->action ?? '');
                            $isCheck = str_contains($actionRaw, 'accept') || str_contains($actionRaw, 'complete');
                            $isDoc = str_contains($actionRaw, 'create') || str_contains($actionRaw, 'document') || str_contains($actionRaw, 'upload');
                            $isChat = str_contains($actionRaw, 'comment') || str_contains($actionRaw, 'update');
                            $isClock = str_contains($actionRaw, 'hour') || str_contains($actionRaw, 'time') || str_contains($actionRaw, 'log');

                            $aColor = $isCheck ? 'emerald' : ($isDoc ? 'blue' : ($isChat ? 'purple' : ($isClock ? 'amber' : 'emerald')));
                            $actionTitle = $isCheck ? 'Accepted project assignment' : ($isDoc ? 'New project initiated' : (ucwords(str_replace('_', ' ', $act->action))));
                            $authorName = $act->user->name ?? 'PMO Admin';
                        @endphp
                        <div class="flex items-start gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 transition-colors group">
                            <div class="w-7 h-7 rounded-lg bg-{{ $aColor }}-50 border border-{{ $aColor }}-100 text-{{ $aColor }}-600 flex items-center justify-center flex-shrink-0 shadow-2xs mt-0.5">
                                @if($isCheck)
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @elseif($isDoc)
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-xs font-bold text-slate-800 truncate group-hover:text-[#c3122e] transition-colors leading-snug">
                                    {{ $actionTitle }}
                                </h4>
                                <div class="flex items-center justify-between gap-1 text-[10px] text-slate-400 mt-0.5">
                                    <span class="truncate">{{ $authorName }}</span>
                                    <span class="pm-mono flex-shrink-0">{{ $act->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            No recent logs recorded.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 4. MY APPROVALS QUICK HUB --}}
            <div class="pm-card p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shadow-2xs flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 tracking-tight">Approvals Hub</h3>
                            <p class="text-[10.5px] text-slate-400 font-medium">Pending governance reviews</p>
                        </div>
                    </div>
                    <a href="{{ route('approvals.index') }}" class="text-xs font-black text-amber-700 hover:underline">View all &rarr;</a>
                </div>

                <div class="space-y-2.5 max-h-[220px] overflow-y-auto pr-0.5 pm-custom-scroll">
                    @forelse($myApprovalsList->take(4) as $app)
                        @php
                            $titleText = ($app->request_type?->label() ?? 'Project Approval') . ' - ' . ($app->project->name ?? 'George Steuart');
                        @endphp
                        <div class="p-2.5 rounded-xl bg-slate-50/70 border border-slate-200/70 hover:bg-white transition-all space-y-1 group">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors" title="{{ $titleText }}">
                                    {{ $titleText }}
                                </h4>
                                <span class="px-1.5 py-0.2 rounded text-[8.5px] font-bold bg-amber-50 text-amber-800 border border-amber-200 uppercase flex-shrink-0 pm-mono">
                                    {{ $app->created_at->format('M d') }}
                                </span>
                            </div>
                            <div class="text-[10px] text-slate-400 flex items-center justify-between">
                                <span class="truncate">By {{ $app->requester->name ?? 'PMO Admin' }}</span>
                                <a href="{{ route('approvals.index') }}" class="text-[#c3122e] font-bold hover:underline">Review &rarr;</a>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            No approval requests pending.
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
                <div style="background: linear-gradient(135deg, #18080c 0%, #300c16 50%, #1a080e 100%); color: #ffffff;" class="p-5 sm:p-6 flex items-center justify-between border-b border-rose-950/40 flex-shrink-0">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-xl shadow-inner flex-shrink-0">
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
                    <button wire:click="closeReviewModal" type="button" class="p-2 text-white/70 hover:text-white rounded-xl hover:bg-white/10 cursor-pointer transition-colors flex-shrink-0">
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
                <div class="flex-shrink-0 p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-wrap">
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
