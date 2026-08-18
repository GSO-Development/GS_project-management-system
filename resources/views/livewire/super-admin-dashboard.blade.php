<div wire:poll.10s>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER & DASHBOARD CONTROLS HUB
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-6 sm:p-7 lg:p-8 text-white mb-6" style="background: #2b040a;">
        <!-- Full Banner Background Image (User's Luxury Crimson & Gold Skyline Artwork) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Executive Dashboard Banner" 
                class="w-full h-full object-cover object-center"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#180206]/95 via-[#2a040b]/65 to-transparent md:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/40 via-black/20 to-transparent hidden md:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <!-- Left Side: 3D Command Icon + Title + Meta -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <!-- 3D Luxury App Icon Container with Dark Bezel -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-4 ring-black/40 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/project-app-icon.jpg') }}" alt="PMO Command Center" class="w-full h-full object-cover rounded-xl shadow-inner">
                </div>

                <div class="min-w-0 space-y-1.5">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold text-white tracking-tight leading-tight drop-shadow-[0_2px_6px_rgba(0,0,0,0.8)]" style="font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;">
                            PMO Executive Command Center
                        </h1>
                        <span class="px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Live Real-Time Governance</span>
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <span class="inline-flex items-center gap-1.5 text-amber-300 font-bold">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-white/30 text-xs">•</span>
                        <span class="text-slate-200/90 font-medium">Portfolio oversight, governance decisions, and operational delivery metrics</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Live Portfolio Health Gauge & Quick Actions (Executive Obsidian Smoked HUD) -->
            <div class="flex items-center gap-3 flex-wrap self-start xl:self-center">
                <!-- Floating Mini Gauge Card -->
                <div class="px-4 py-2.5 rounded-2xl border border-white/20 ring-1 ring-black/40 shadow-2xl backdrop-blur-2xl flex items-center gap-3.5 bg-slate-950/80">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-300 uppercase tracking-widest leading-none">ACTIVE EXECUTION</span>
                        <span class="text-sm font-black text-white leading-tight font-mono mt-1">
                            {{ $activeProjects }} <span class="text-slate-400 font-normal">/</span> {{ $totalProjects }} <span class="text-[10px] font-bold text-slate-300">Projects</span>
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-400 flex-shrink-0 shadow-inner">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>

                <!-- Primary New Project CTA -->
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>New Project</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 2. TOP ROW: 6 COMPACT & NEAT KPI SPARKLINE CARDS          -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-3.5 mb-6">
        <!-- 1. Total Subsidiaries -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Total Subsidiaries</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight leading-none mt-0.5 block">{{ $totalSubsidiaries }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600">
                <span>&uarr; {{ $subsTrendCount > 0 ? $subsTrendCount : 1 }} active</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradBlue" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,14 Q25,4 50,12 T100,6 L100,20 L0,20 Z" fill="url(#gradBlue)"/>
                    <path d="M0,14 Q25,4 50,12 T100,6" fill="none" stroke="#3b82f6" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- 2. Total Projects -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Total Projects</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight leading-none mt-0.5 block">{{ $totalProjects }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600">
                <span>&uarr; {{ $projTrendCount > 0 ? $projTrendCount : 1 }} this month</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradGreen" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,15 Q30,6 60,13 T100,4 L100,20 L0,20 Z" fill="url(#gradGreen)"/>
                    <path d="M0,15 Q30,6 60,13 T100,4" fill="none" stroke="#10b981" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- 3. Projects In Progress -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Projects In Progress</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight leading-none mt-0.5 block">{{ $activeProjects }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600">
                <span>&uarr; {{ $activeProjects }} active</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradPurple" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#8b5cf6" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,12 Q25,18 50,8 T100,11 L100,20 L0,20 Z" fill="url(#gradPurple)"/>
                    <path d="M0,12 Q25,18 50,8 T100,11" fill="none" stroke="#8b5cf6" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- 4. Total Tasks -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Total Tasks</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight leading-none mt-0.5 block">{{ $totalTasksCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold text-emerald-600">
                <span>&uarr; {{ $completedTasksCount }} completed</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradAmber" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,15 Q35,6 70,15 T100,7 L100,20 L0,20 Z" fill="url(#gradAmber)"/>
                    <path d="M0,15 Q35,6 70,15 T100,7" fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- 5. Overdue Tasks -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Overdue Tasks</span>
                    <span class="text-lg sm:text-xl font-black {{ $overdueTasksCount > 0 ? 'text-rose-600' : 'text-slate-900' }} font-mono tracking-tight leading-none mt-0.5 block">{{ $overdueTasksCount }}</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold {{ $overdueTasksCount > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                <span>{{ $overdueTasksCount > 0 ? 'Needs Attention' : '✓ Zero Overdue' }}</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradRose" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f43f5e" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#f43f5e" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,11 Q30,17 65,9 T100,14 L100,20 L0,20 Z" fill="url(#gradRose)"/>
                    <path d="M0,11 Q30,17 65,9 T100,14" fill="none" stroke="#f43f5e" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

        <!-- 6. Budget Utilization -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-3 sm:p-3.5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between h-[108px] relative overflow-hidden group">
            <div class="flex items-center justify-between gap-1.5">
                <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-700 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-right min-w-0">
                    <span class="text-[10px] font-semibold text-slate-500 block truncate leading-tight">Budget Utilization</span>
                    <span class="text-lg sm:text-xl font-black text-slate-900 font-mono tracking-tight leading-none mt-0.5 block">{{ $budgetUtilization }}%</span>
                </div>
            </div>
            <div class="flex items-center gap-1 text-[9px] font-bold text-slate-500">
                <span>Avg Progress</span>
            </div>
            <!-- Sparkline Wave Graph -->
            <div class="w-full h-4 overflow-hidden">
                <svg viewBox="0 0 100 20" class="w-full h-full" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="gradGold" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#eab308" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#eab308" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,16 Q30,10 60,14 T100,6 L100,20 L0,20 Z" fill="url(#gradGold)"/>
                    <path d="M0,16 Q30,10 60,14 T100,6" fill="none" stroke="#eab308" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 3. MIDDLE ROW: 3 MAJOR CARDS (Health, Status, Approvals)   -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- 1. Project Health Summary (100% Real Database Metrics with Interactive Tooltip) -->
        @php
            $onTrackPct = $healthSummary['on_track']['pct'];
            $atRiskPct = $healthSummary['at_risk']['pct'];
            $delayedPct = $healthSummary['delayed']['pct'];
            $notStartedPct = $healthSummary['not_started']['pct'];

            $circumference = 238.76;
            $onTrackLen = round(($onTrackPct / 100) * $circumference, 2);
            $atRiskLen = round(($atRiskPct / 100) * $circumference, 2);
            $delayedLen = round(($delayedPct / 100) * $circumference, 2);
            $notStartedLen = round(($notStartedPct / 100) * $circumference, 2);

            $onTrackOffset = 0;
            $atRiskOffset = -$onTrackLen;
            $delayedOffset = -($onTrackLen + $atRiskLen);
            $notStartedOffset = -($onTrackLen + $atRiskLen + $delayedLen);
        @endphp

        <div 
            x-data="{ 
                activeSegment: null,
                details: {
                    on_track: { label: 'On Track', count: '{{ $healthSummary['on_track']['count'] }}', pct: '{{ $onTrackPct }}%', color: '#10b981', textCls: 'text-emerald-600', desc: 'Projects proceeding smoothly on schedule within budget without blockers.' },
                    at_risk: { label: 'At Risk', count: '{{ $healthSummary['at_risk']['count'] }}', pct: '{{ $atRiskPct }}%', color: '#f59e0b', textCls: 'text-amber-600', desc: 'Potential delays, resource bottlenecks, or risks requiring PMO attention.' },
                    delayed: { label: 'Delayed', count: '{{ $healthSummary['delayed']['count'] }}', pct: '{{ $delayedPct }}%', color: '#ef4444', textCls: 'text-rose-600', desc: 'Overdue milestones or deadline breaches requiring executive intervention.' },
                    not_started: { label: 'Not Started', count: '{{ $healthSummary['not_started']['count'] }}', pct: '{{ $notStartedPct }}%', color: '#94a3b8', textCls: 'text-slate-600', desc: 'Projects in planning, charter approval, or awaiting leader kickoff.' }
                }
            }"
            class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between group"
        >
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Project Health Summary
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">Live Data</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-6 py-1">
                    <!-- Interactive Dynamic Vector Donut Chart -->
                    <div class="relative w-36 h-36 flex items-center justify-center flex-shrink-0">
                        <svg class="w-full h-full -rotate-90 overflow-visible" viewBox="0 0 100 100">
                            <!-- Background Track Circle -->
                            <circle cx="50" cy="50" r="38" fill="none" stroke="#f1f5f9" stroke-width="12" />
                            
                            @if($totalProjects > 0)
                                <!-- 1. On Track -->
                                @if($onTrackLen > 0)
                                <circle 
                                    cx="50" cy="50" r="38" fill="none" stroke="#10b981" 
                                    :stroke-width="activeSegment === 'on_track' ? 15 : 12"
                                    :stroke-opacity="activeSegment && activeSegment !== 'on_track' ? 0.4 : 1"
                                    stroke-dasharray="{{ $onTrackLen }} 238.76" stroke-dashoffset="{{ $onTrackOffset }}" stroke-linecap="round"
                                    class="transition-all duration-200 cursor-pointer"
                                    @mouseenter="activeSegment = 'on_track'"
                                    @mouseleave="activeSegment = null"
                                >
                                    <title>🟢 On Track: {{ $healthSummary['on_track']['count'] }} Projects ({{ $onTrackPct }}%)</title>
                                </circle>
                                @endif
                                
                                <!-- 2. At Risk -->
                                @if($atRiskLen > 0)
                                <circle 
                                    cx="50" cy="50" r="38" fill="none" stroke="#f59e0b" 
                                    :stroke-width="activeSegment === 'at_risk' ? 15 : 12"
                                    :stroke-opacity="activeSegment && activeSegment !== 'at_risk' ? 0.4 : 1"
                                    stroke-dasharray="{{ $atRiskLen }} 238.76" stroke-dashoffset="{{ $atRiskOffset }}" stroke-linecap="round"
                                    class="transition-all duration-200 cursor-pointer"
                                    @mouseenter="activeSegment = 'at_risk'"
                                    @mouseleave="activeSegment = null"
                                >
                                    <title>🟡 At Risk: {{ $healthSummary['at_risk']['count'] }} Projects ({{ $atRiskPct }}%)</title>
                                </circle>
                                @endif
                                
                                <!-- 3. Delayed -->
                                @if($delayedLen > 0)
                                <circle 
                                    cx="50" cy="50" r="38" fill="none" stroke="#ef4444" 
                                    :stroke-width="activeSegment === 'delayed' ? 15 : 12"
                                    :stroke-opacity="activeSegment && activeSegment !== 'delayed' ? 0.4 : 1"
                                    stroke-dasharray="{{ $delayedLen }} 238.76" stroke-dashoffset="{{ $delayedOffset }}" stroke-linecap="round"
                                    class="transition-all duration-200 cursor-pointer"
                                    @mouseenter="activeSegment = 'delayed'"
                                    @mouseleave="activeSegment = null"
                                >
                                    <title>🔴 Delayed: {{ $healthSummary['delayed']['count'] }} Projects ({{ $delayedPct }}%)</title>
                                </circle>
                                @endif
                                
                                <!-- 4. Not Started -->
                                @if($notStartedLen > 0)
                                <circle 
                                    cx="50" cy="50" r="38" fill="none" stroke="#94a3b8" 
                                    :stroke-width="activeSegment === 'not_started' ? 15 : 12"
                                    :stroke-opacity="activeSegment && activeSegment !== 'not_started' ? 0.4 : 1"
                                    stroke-dasharray="{{ $notStartedLen }} 238.76" stroke-dashoffset="{{ $notStartedOffset }}" stroke-linecap="round"
                                    class="transition-all duration-200 cursor-pointer"
                                    @mouseenter="activeSegment = 'not_started'"
                                    @mouseleave="activeSegment = null"
                                >
                                    <title>⚪ Not Started: {{ $healthSummary['not_started']['count'] }} Projects ({{ $notStartedPct }}%)</title>
                                </circle>
                                @endif
                            @endif
                        </svg>

                        <!-- Dynamic Interactive Center Label -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none transition-all duration-200">
                            <template x-if="!activeSegment">
                                <div>
                                    <span class="text-2xl font-black text-slate-900 font-mono leading-none block">{{ $totalProjects }}</span>
                                    <span class="text-[10px] font-semibold text-slate-400 mt-0.5 block">Total Projects</span>
                                </div>
                            </template>
                            <template x-if="activeSegment">
                                <div>
                                    <span class="text-2xl font-black font-mono leading-none block" :class="details[activeSegment].textCls" x-text="details[activeSegment].count"></span>
                                    <span class="text-[9.5px] font-bold text-slate-600 mt-0.5 block truncate max-w-[70px]" x-text="details[activeSegment].label"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Interactive Legend List with Hover Trigger -->
                    <div class="space-y-2 w-full sm:w-auto">
                        <!-- 1. On Track -->
                        <div 
                            class="flex items-center justify-between gap-4 text-xs p-1.5 rounded-xl cursor-pointer transition-all"
                            :class="activeSegment === 'on_track' ? 'bg-emerald-50 border border-emerald-200 shadow-2xs' : 'hover:bg-slate-50'"
                            @mouseenter="activeSegment = 'on_track'"
                            @mouseleave="activeSegment = null"
                        >
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                <span>On Track</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $healthSummary['on_track']['count'] }} <span class="text-slate-400 font-normal">({{ $onTrackPct }}%)</span>
                            </span>
                        </div>

                        <!-- 2. At Risk -->
                        <div 
                            class="flex items-center justify-between gap-4 text-xs p-1.5 rounded-xl cursor-pointer transition-all"
                            :class="activeSegment === 'at_risk' ? 'bg-amber-50 border border-amber-200 shadow-2xs' : 'hover:bg-slate-50'"
                            @mouseenter="activeSegment = 'at_risk'"
                            @mouseleave="activeSegment = null"
                        >
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 flex-shrink-0"></span>
                                <span>At Risk</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $healthSummary['at_risk']['count'] }} <span class="text-slate-400 font-normal">({{ $atRiskPct }}%)</span>
                            </span>
                        </div>

                        <!-- 3. Delayed -->
                        <div 
                            class="flex items-center justify-between gap-4 text-xs p-1.5 rounded-xl cursor-pointer transition-all"
                            :class="activeSegment === 'delayed' ? 'bg-rose-50 border border-rose-200 shadow-2xs' : 'hover:bg-slate-50'"
                            @mouseenter="activeSegment = 'delayed'"
                            @mouseleave="activeSegment = null"
                        >
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 flex-shrink-0"></span>
                                <span>Delayed</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $healthSummary['delayed']['count'] }} <span class="text-slate-400 font-normal">({{ $delayedPct }}%)</span>
                            </span>
                        </div>

                        <!-- 4. Not Started -->
                        <div 
                            class="flex items-center justify-between gap-4 text-xs p-1.5 rounded-xl cursor-pointer transition-all"
                            :class="activeSegment === 'not_started' ? 'bg-slate-100 border border-slate-300 shadow-2xs' : 'hover:bg-slate-50'"
                            @mouseenter="activeSegment = 'not_started'"
                            @mouseleave="activeSegment = null"
                        >
                            <span class="flex items-center gap-2 font-bold text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400 flex-shrink-0"></span>
                                <span>Not Started</span>
                            </span>
                            <span class="font-bold text-slate-800 font-mono">
                                {{ $healthSummary['not_started']['count'] }} <span class="text-slate-400 font-normal">({{ $notStartedPct }}%)</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Hover Explanation Badge -->
                <div class="mt-3 min-h-[38px] flex items-center">
                    <template x-if="activeSegment">
                        <div class="w-full p-2 rounded-xl text-[11px] font-semibold border flex items-center gap-2 animate-in fade-in duration-200"
                            :class="{
                                'bg-emerald-50/80 border-emerald-200 text-emerald-800': activeSegment === 'on_track',
                                'bg-amber-50/80 border-amber-200 text-amber-800': activeSegment === 'at_risk',
                                'bg-rose-50/80 border-rose-200 text-rose-800': activeSegment === 'delayed',
                                'bg-slate-100 border-slate-200 text-slate-700': activeSegment === 'not_started'
                            }"
                        >
                            <span class="font-black flex-shrink-0" x-text="details[activeSegment].label + ' (' + details[activeSegment].pct + '):'"></span>
                            <span class="truncate" x-text="details[activeSegment].desc"></span>
                        </div>
                    </template>
                    <template x-if="!activeSegment">
                        <div class="w-full text-center text-[10.5px] font-medium text-slate-400 py-1">
                            <span>Hover over any donut segment or legend to inspect status meaning</span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-3 mt-1 border-t border-slate-100 text-right">
                <a href="{{ route('projects.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View all projects</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 2. Projects by Status (100% Real-Time Scaled Vector Bar Chart) -->
        @php
            $cNotStarted = $statusChartData['not_started'];
            $cPlanning = $statusChartData['planning'];
            $cInProgress = $statusChartData['in_progress'];
            $cOnHold = $statusChartData['on_hold'];
            $cCompleted = $statusChartData['completed'];

            // Bar Heights in pixels out of 115px maximum height
            $hNotStarted = $cNotStarted > 0 ? max(8, round(($cNotStarted / $maxStatusVal) * 115)) : 0;
            $hPlanning = $cPlanning > 0 ? max(8, round(($cPlanning / $maxStatusVal) * 115)) : 0;
            $hInProgress = $cInProgress > 0 ? max(8, round(($cInProgress / $maxStatusVal) * 115)) : 0;
            $hOnHold = $cOnHold > 0 ? max(8, round(($cOnHold / $maxStatusVal) * 115)) : 0;
            $hCompleted = $cCompleted > 0 ? max(8, round(($cCompleted / $maxStatusVal) * 115)) : 0;

            // Y coordinate for tops of bars
            $yNotStarted = 139 - $hNotStarted;
            $yPlanning = 139 - $hPlanning;
            $yInProgress = 139 - $hInProgress;
            $yOnHold = 139 - $hOnHold;
            $yCompleted = 139 - $hCompleted;
        @endphp

        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Projects by Status
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">Live Scale (Max: {{ $maxStatusVal }})</span>
                </div>

                <!-- Structured SVG Bar Chart -->
                <div class="w-full pt-2">
                    <svg viewBox="0 0 320 175" class="w-full h-auto select-none" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <!-- Grid Lines & Y-Axis Labels -->
                        <!-- 100% -->
                        <text x="20" y="27" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">{{ $maxStatusVal }}</text>
                        <line x1="28" y1="24" x2="310" y2="24" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 80% -->
                        <text x="20" y="50" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">{{ (int) round($maxStatusVal * 0.8) }}</text>
                        <line x1="28" y1="47" x2="310" y2="47" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 60% -->
                        <text x="20" y="73" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">{{ (int) round($maxStatusVal * 0.6) }}</text>
                        <line x1="28" y1="70" x2="310" y2="70" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 40% -->
                        <text x="20" y="96" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">{{ (int) round($maxStatusVal * 0.4) }}</text>
                        <line x1="28" y1="93" x2="310" y2="93" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 20% -->
                        <text x="20" y="119" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">{{ (int) round($maxStatusVal * 0.2) }}</text>
                        <line x1="28" y1="116" x2="310" y2="116" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 0 (Baseline) -->
                        <text x="20" y="142" text-anchor="end" font-size="9.5" font-weight="700" fill="#94a3b8" font-family="monospace">0</text>
                        <line x1="28" y1="139" x2="310" y2="139" stroke="#cbd5e1" stroke-width="1.5" />

                        <!-- BARS (Real-time database counts growing upward) -->
                        <!-- Bar 1: Not Started -->
                        <g class="transition-all hover:opacity-85 cursor-pointer">
                            <text x="60" y="{{ max(20, $yNotStarted - 6) }}" text-anchor="middle" font-size="10.5" font-weight="800" fill="#475569" font-family="monospace">{{ $cNotStarted }}</text>
                            @if($hNotStarted > 0)
                                <rect x="46" y="{{ $yNotStarted }}" width="28" height="{{ $hNotStarted }}" rx="4" ry="4" fill="#94a3b8" />
                            @else
                                <line x1="46" y1="139" x2="74" y2="139" stroke="#94a3b8" stroke-width="3" />
                            @endif
                            <text x="60" y="158" text-anchor="middle" font-size="9.5" font-weight="700" fill="#64748b">Not Started</text>
                        </g>

                        <!-- Bar 2: Planning -->
                        <g class="transition-all hover:opacity-85 cursor-pointer">
                            <text x="115" y="{{ max(20, $yPlanning - 6) }}" text-anchor="middle" font-size="10.5" font-weight="800" fill="#2563eb" font-family="monospace">{{ $cPlanning }}</text>
                            @if($hPlanning > 0)
                                <rect x="101" y="{{ $yPlanning }}" width="28" height="{{ $hPlanning }}" rx="4" ry="4" fill="#3b82f6" />
                            @else
                                <line x1="101" y1="139" x2="129" y2="139" stroke="#3b82f6" stroke-width="3" />
                            @endif
                            <text x="115" y="158" text-anchor="middle" font-size="9.5" font-weight="700" fill="#64748b">Planning</text>
                        </g>

                        <!-- Bar 3: In Progress -->
                        <g class="transition-all hover:opacity-85 cursor-pointer">
                            <text x="170" y="{{ max(20, $yInProgress - 6) }}" text-anchor="middle" font-size="11.5" font-weight="900" fill="#059669" font-family="monospace">{{ $cInProgress }}</text>
                            @if($hInProgress > 0)
                                <rect x="156" y="{{ $yInProgress }}" width="28" height="{{ $hInProgress }}" rx="4" ry="4" fill="#10b981" />
                            @else
                                <line x1="156" y1="139" x2="184" y2="139" stroke="#10b981" stroke-width="3" />
                            @endif
                            <text x="170" y="158" text-anchor="middle" font-size="9.5" font-weight="700" fill="#64748b">In Progress</text>
                        </g>

                        <!-- Bar 4: On Hold -->
                        <g class="transition-all hover:opacity-85 cursor-pointer">
                            <text x="225" y="{{ max(20, $yOnHold - 6) }}" text-anchor="middle" font-size="10.5" font-weight="800" fill="#d97706" font-family="monospace">{{ $cOnHold }}</text>
                            @if($hOnHold > 0)
                                <rect x="211" y="{{ $yOnHold }}" width="28" height="{{ $hOnHold }}" rx="4" ry="4" fill="#f59e0b" />
                            @else
                                <line x1="211" y1="139" x2="239" y2="139" stroke="#f59e0b" stroke-width="3" />
                            @endif
                            <text x="225" y="158" text-anchor="middle" font-size="9.5" font-weight="700" fill="#64748b">On Hold</text>
                        </g>

                        <!-- Bar 5: Completed -->
                        <g class="transition-all hover:opacity-85 cursor-pointer">
                            <text x="280" y="{{ max(20, $yCompleted - 6) }}" text-anchor="middle" font-size="10.5" font-weight="800" fill="#7c3aed" font-family="monospace">{{ $cCompleted }}</text>
                            @if($hCompleted > 0)
                                <rect x="266" y="{{ $yCompleted }}" width="28" height="{{ $hCompleted }}" rx="4" ry="4" fill="#8b5cf6" />
                            @else
                                <line x1="266" y1="139" x2="294" y2="139" stroke="#8b5cf6" stroke-width="3" />
                            @endif
                            <text x="280" y="158" text-anchor="middle" font-size="9.5" font-weight="700" fill="#64748b">Completed</text>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-right">
                <a href="{{ route('reports.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View full report</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 3. Pending Approvals (100% Real Database List) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Pending Approvals
                    </h3>
                    <a href="{{ route('approvals.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all ({{ $pendingApprovals }})</a>
                </div>

                <div class="space-y-3.5">
                    @forelse($pendingApprovalList as $app)
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors">
                                        {{ $app->request_type->label() }} - {{ $app->project->name ?? 'Project' }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        {{ $app->project->subsidiary->name ?? 'George Steuart' }} &bull; {{ $app->requester->name ?? 'Leader' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                    Pending
                                </span>
                                <span class="text-[9px] text-slate-400 font-medium mt-1">{{ $app->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-7 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs font-bold text-slate-700">No Pending Approvals</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">All governance decision requests are cleared</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-right">
                <a href="{{ route('approvals.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View all approvals</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 4. BOTTOM ROW: 3 MAJOR CARDS (Due Soon, Subs, Activity)    -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- 1. Projects Due Soon (Real Upcoming Project Deadlines) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Projects Due Soon
                    </h3>
                    <a href="{{ route('projects.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3.5">
                    @forelse($projectsDueSoon as $proj)
                        @php
                            $daysLeft = $proj->deadline ? (int) now()->today()->diffInDays($proj->deadline, false) : null;
                            $badgeCls = ($daysLeft !== null && $daysLeft <= 7) ? 'bg-rose-50 text-rose-700 border-rose-200' : (($daysLeft !== null && $daysLeft <= 14) ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                            $badgeText = $daysLeft === null ? 'No Deadline' : ($daysLeft <= 0 ? 'Overdue' : ($daysLeft == 1 ? '1 day left' : "{$daysLeft} days left"));
                        @endphp
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('projects.show', $proj) }}" class="text-xs font-bold text-slate-900 truncate block group-hover:text-[#c3122e] transition-colors">
                                        {{ $proj->name }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        {{ $proj->subsidiary->name ?? 'George Steuart' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end flex-shrink-0">
                                <span class="text-[10px] font-bold text-slate-600 font-mono">{{ $proj->deadline ? $proj->deadline->format('M d, Y') : 'N/A' }}</span>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold border {{ $badgeCls }} mt-1">
                                    {{ $badgeText }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-7 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs font-bold text-slate-700">No Projects Due Soon</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">All active project timelines are on track</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-right">
                <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View calendar</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 2. Top Subsidiaries Overview (Real Database Subsidiaries) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Top Subsidiaries Overview
                    </h3>
                    <a href="{{ route('subsidiaries.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-[9px] font-black uppercase text-slate-400 tracking-wider border-b border-slate-100 pb-2">
                            <tr>
                                <th class="pb-2">Subsidiary</th>
                                <th class="pb-2 text-center">Projects</th>
                                <th class="pb-2 text-center">In Progress</th>
                                <th class="pb-2 text-center">Completed</th>
                                <th class="pb-2 text-right">Health</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($topSubsidiaries as $sub)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 font-bold text-slate-800 truncate max-w-32">{{ $sub->name }}</td>
                                    <td class="py-2.5 text-center font-mono text-slate-700">{{ $sub->projects_count }}</td>
                                    <td class="py-2.5 text-center font-mono text-slate-700">{{ $sub->in_progress_count }}</td>
                                    <td class="py-2.5 text-center font-mono text-slate-700">{{ $sub->completed_count }}</td>
                                    <td class="py-2.5 text-right">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Good</span>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400 text-xs">No Subsidiaries Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-right">
                <a href="{{ route('subsidiaries.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View all subsidiaries</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 3. Recent Activity (Real Activity Log Stream) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Recent Activity
                    </h3>
                    <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-blue-600 hover:underline">View all</a>
                </div>

                <div class="space-y-3.5">
                    @forelse($recentActivityLogs as $log)
                        <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-slate-900 truncate group-hover:text-[#c3122e] transition-colors">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }} {{ $log->module ? '(' . ucfirst($log->module) . ')' : '' }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 font-medium truncate block">
                                        By {{ $log->user->name ?? 'System' }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-[9px] text-slate-400 font-medium flex-shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="py-7 text-center text-slate-400">
                            <p class="text-xs font-bold text-slate-700">No Recent Activity Logs</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-2 border-t border-slate-100 text-right">
                <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline inline-flex items-center gap-1">
                    <span>View all logs</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 5. FOOTER BRAND STRIP                                      -->
    <!-- ══════════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between flex-wrap gap-4 pt-6 border-t border-slate-200/80 text-xs text-slate-400 font-medium mb-6">
        <div>
            &copy; 2026 PMO Project Management System. All rights reserved.
        </div>
        <div class="flex items-center gap-1.5 text-slate-500">
            <span>Made with</span>
            <span class="text-rose-500">❤️</span>
            <span>for efficient project management</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════ -->
    <!-- 6. DASHBOARD ACTION MODALS                                 -->
    <!-- ══════════════════════════════════════════════════════════ -->
    
    <!-- 1. Approval Review Modal -->
    @if($showReviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-md p-6">
                <h3 class="font-black text-slate-900 text-base mb-1">Review Approval Request</h3>
                <p class="text-xs text-slate-500 mb-4">Provide optional executive review feedback for the audit log.</p>

                <div class="space-y-4">
                    <textarea wire:model="reviewComment" rows="3" placeholder="Enter review note or rationale..." class="w-full p-3 rounded-xl border border-slate-200 text-xs outline-none focus:border-[#c3122e]"></textarea>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showReviewModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button wire:click="submitReview(false)" type="button" class="btn-danger text-xs">Reject Request</button>
                        <button wire:click="submitReview(true)" type="button" class="btn-success text-xs">Approve Request</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Resolve Blocker Modal -->
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-md p-6">
                <h3 class="font-black text-slate-900 text-base mb-1">Resolve Task Blocker</h3>
                <p class="text-xs text-slate-500 mb-4">Explain how this blocker was removed so the task can resume.</p>

                <div class="space-y-4">
                    <textarea wire:model="blockerResolutionInput" rows="3" placeholder="Resolution details (e.g. Approved extra resources, unblocked dependency)..." class="w-full p-3 rounded-xl border border-slate-200 text-xs outline-none focus:border-[#c3122e]"></textarea>
                    @error('blockerResolutionInput') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showResolveBlockerModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button wire:click="resolveBlocker" type="button" class="btn-success text-xs">✓ Confirm Resolution</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. Add Project Risk Modal -->
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg p-6">
                <h3 class="font-black text-slate-900 text-base mb-1">Log Project Risk</h3>
                <p class="text-xs text-slate-500 mb-4">Add a new risk to the organizational risk registry.</p>

                <div class="space-y-3.5">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Project</label>
                        <select wire:model="riskProjectId" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800">
                            @foreach($allProjectsList as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Risk Title</label>
                        <input type="text" wire:model="riskTitle" placeholder="e.g. Delay in material shipment" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800">
                        @error('riskTitle') <span class="text-xs text-rose-600 font-bold block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Category</label>
                            <select wire:model="riskCategory" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="Technical">Technical</option>
                                <option value="Operational">Operational</option>
                                <option value="Financial">Financial</option>
                                <option value="External">External</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Probability</label>
                            <select wire:model="riskProbability" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Impact</label>
                            <select wire:model="riskImpact" class="w-full p-2.5 rounded-xl border border-slate-200 text-xs font-bold">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-1">Mitigation Plan</label>
                        <textarea wire:model="riskMitigation" rows="2" placeholder="Actionable steps to mitigate this risk..." class="w-full p-2.5 rounded-xl border border-slate-200 text-xs"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button wire:click="$set('showAddRiskModal', false)" type="button" class="btn-secondary text-xs">Cancel</button>
                        <button wire:click="createRisk" type="button" class="btn-primary text-xs">Save Risk</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. Reassign Project Leader Modal -->
    @if($showReassignModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm">
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div style="background: linear-gradient(135deg, #18080c 0%, #300c16 50%, #1a080e 100%); color: #ffffff;" class="p-6 flex items-center justify-between border-b border-rose-950/40">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md flex items-center justify-center text-rose-300 flex-shrink-0 shadow-inner">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-white tracking-tight">Reassign Project Leader</h3>
                        <p class="text-xs text-rose-200/80 font-medium">Designate a new project leader to resolve constraints &amp; launch workspace</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showReassignModal', false)" class="p-2 text-white/70 hover:text-white rounded-xl hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="submitReassign" class="p-6 space-y-5">
                @if($reassignProject)
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-xs font-black px-2.5 py-0.5 rounded-lg bg-rose-50 text-[#c3122e] border border-rose-200">
                                {{ $reassignProject->code }}
                            </span>
                            <span class="text-xs font-bold text-slate-500">{{ $reassignProject->subsidiary->name ?? 'George Steuart' }}</span>
                        </div>
                        <h4 class="text-sm font-black text-slate-900">{{ $reassignProject->name }}</h4>
                        
                        @if($reassignProject->pm_rejection_reason)
                            <div class="p-3 rounded-xl bg-rose-50/80 border border-rose-200 text-xs text-rose-950 space-y-1">
                                <span class="font-black text-[10px] uppercase tracking-wider text-rose-700 block">Declined by {{ $reassignProject->projectManager->name ?? 'Leader' }}:</span>
                                <p class="italic font-medium pl-2">"{{ $reassignProject->pm_rejection_reason }}"</p>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-700 uppercase tracking-wider block">
                        Select New Designated Project Leader <span class="text-[#c3122e]">*</span>
                    </label>
                    <select wire:model="newLeaderId" class="w-full p-3.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all cursor-pointer shadow-2xs" required>
                        <option value="">Choose a leader...</option>
                        @foreach($allUsersList as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} · {{ $user->email }} ({{ $user->subsidiary->code ?? 'GS' }})</option>
                        @endforeach
                    </select>
                    @error('newLeaderId') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showReassignModal', false)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all cursor-pointer active:scale-95 flex items-center gap-1.5">
                        <span>Confirm &amp; Notify Leader</span>
                        <span>&rarr;</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
