<div wire:poll.30s class="space-y-4 sm:space-y-5 pb-12 text-slate-800" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">

    {{-- ═══════════════════════════════════════════════════════════
         TOP BANNER (Sunset Skyline Panorama)
         ═══════════════════════════════════════════════════════════ --}}
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

        {{-- Sub-Header Status Line inside Hero --}}
        <div class="mt-4 pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3 text-[11px] text-white/70">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span><strong class="text-white">{{ $healthSummary['on_track']['count'] }}</strong> On Track</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span><strong class="text-white">{{ $healthSummary['at_risk']['count'] }}</strong> At Risk</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                    <span><strong class="text-white">{{ $healthSummary['delayed']['count'] }}</strong> Delayed</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <span><strong class="text-white">{{ $healthSummary['not_started']['count'] }}</strong> Staged</span>
                </div>
            </div>

            <div class="flex items-center gap-3 text-white/60 text-[11px]">
                <span>Subsidiaries: <strong class="text-white font-mono">{{ $totalSubsidiaries }}</strong></span>
                <span>&bull;</span>
                <span>Total Deliverables: <strong class="text-white font-mono">{{ $totalTasksCount }}</strong></span>
                <span>&bull;</span>
                <span>Stakeholders: <strong class="text-white font-mono">{{ $totalUsers }}</strong></span>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════
         ROW 1: 5 COMPACT KPI METRIC STRIP CARDS
         ═══════════════════════════════════════════════════════════ --}}
    <style>
        .kpi-strip { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 0.6rem; }
        @media (min-width: 640px)  { .kpi-strip { grid-template-columns: repeat(3, minmax(0,1fr)); } }
        @media (min-width: 900px)  { .kpi-strip { grid-template-columns: repeat(5, minmax(0,1fr)); gap: 0.6rem; } }
        .kpi-card { background:#fff; border-radius:10px; padding: 8px 12px; display:flex; align-items:center; gap:10px; transition: box-shadow .15s; border: 1px solid #e2e8f0; }
        .kpi-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.07); }
        .kpi-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .kpi-icon svg { width:14px; height:14px; }
        .kpi-num { font-size:18px; font-weight:900; font-family:ui-monospace,monospace; color:#0f172a; line-height:1; }
        .kpi-pct { font-size:9.5px; font-weight:700; }
        .kpi-label { font-size:10.5px; font-weight:600; color:#64748b; margin-top:1px; }
    </style>
    <div class="kpi-strip w-full">

        {{-- 1. Total Projects --}}
        <a href="{{ route('projects.index') }}" class="no-underline">
            <div class="kpi-card" style="border-left: 3px solid #c3122e;">
                <div class="kpi-icon" style="background:#fef2f2; color:#c3122e;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:baseline; gap:5px; flex-wrap:wrap;">
                        <span class="kpi-num">{{ $totalProjects }}</span>
                        <span class="kpi-pct" style="color:#94a3b8;">/ {{ $totalSubsidiaries }} subs</span>
                    </div>
                    <div class="kpi-label">Total Projects</div>
                </div>
            </div>
        </a>

        {{-- 2. On Track --}}
        <a href="{{ route('projects.index') }}" class="no-underline">
            <div class="kpi-card" style="border-left: 3px solid #16a34a;">
                <div class="kpi-icon" style="background:#f0fdf4; color:#16a34a;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:baseline; gap:5px; flex-wrap:wrap;">
                        <span class="kpi-num">{{ $healthSummary['on_track']['count'] }}</span>
                        <span class="kpi-pct" style="color:#16a34a;">{{ $healthSummary['on_track']['pct'] }}%</span>
                    </div>
                    <div class="kpi-label">On Track</div>
                </div>
            </div>
        </a>

        {{-- 3. At Risk --}}
        <a href="{{ route('project-monitor.index') }}?viewMode=stuck" class="no-underline">
            <div class="kpi-card" style="border-left: 3px solid #d97706;">
                <div class="kpi-icon" style="background:#fffbeb; color:#d97706;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:baseline; gap:5px; flex-wrap:wrap;">
                        <span class="kpi-num">{{ $healthSummary['at_risk']['count'] }}</span>
                        <span class="kpi-pct" style="color:#d97706;">{{ $healthSummary['at_risk']['pct'] }}%</span>
                    </div>
                    <div class="kpi-label">At Risk</div>
                </div>
            </div>
        </a>

        {{-- 4. Delayed --}}
        <a href="{{ route('project-monitor.index') }}?viewMode=stuck" class="no-underline">
            <div class="kpi-card" style="border-left: 3px solid #dc2626;">
                <div class="kpi-icon" style="background:#fef2f2; color:#dc2626;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:baseline; gap:5px; flex-wrap:wrap;">
                        <span class="kpi-num">{{ $healthSummary['delayed']['count'] }}</span>
                        <span class="kpi-pct" style="color:#dc2626;">{{ $healthSummary['delayed']['pct'] }}%</span>
                    </div>
                    <div class="kpi-label">Delayed</div>
                </div>
            </div>
        </a>

        {{-- 5. Open Blockers --}}
        <button wire:click="setDashboardTab('risks')" type="button" style="text-align:left; width:100%; cursor:pointer; background:none; border:none; padding:0;">
            <div class="kpi-card" style="border-left: 3px solid #9333ea; width:100%;">
                <div class="kpi-icon" style="background:#faf5ff; color:#9333ea;">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="display:flex; align-items:baseline; gap:5px; flex-wrap:wrap;">
                        <span class="kpi-num">{{ $activeBlockersCount }}</span>
                        @if($activeBlockersCount > 0)
                            <span class="kpi-pct" style="color:#9333ea; animation: pulse 2s infinite;">Needs attn.</span>
                        @else
                            <span class="kpi-pct" style="color:#94a3b8;">All clear</span>
                        @endif
                    </div>
                    <div class="kpi-label">Open Blockers</div>
                </div>
            </div>
        </button>

    </div>


    {{-- ═══════════════════════════════════════════════════════════
         ROW 2: 3 CARDS (Portfolio Health + Gantt Preview + Tasks Summary)
         ═══════════════════════════════════════════════════════════ --}}
    <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- 1. Portfolio Health Donut Chart (27%) --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Portfolio Health</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help" title="Overall health distribution">ⓘ</span>
                </div>
            </div>

            @php
                $c = 238.76; // Circumference for r=38
                $tot = max(1, $totalProjects);
                $pOnTrack = ($healthSummary['on_track']['count'] / $tot) * $c;
                $pAtRisk  = ($healthSummary['at_risk']['count'] / $tot) * $c;
                $pDelayed = ($healthSummary['delayed']['count'] / $tot) * $c;
                $pStaged  = ($healthSummary['not_started']['count'] / $tot) * $c;

                $offOnTrack = 0;
                $offAtRisk  = -$pOnTrack;
                $offDelayed = -($pOnTrack + $pAtRisk);
                $offStaged  = -($pOnTrack + $pAtRisk + $pDelayed);
            @endphp

            <div class="my-4 flex items-center justify-between gap-3">
                {{-- Donut SVG --}}
                <div class="relative w-24 h-24 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 96 96">
                        <circle cx="48" cy="48" r="38" stroke="#f1f5f9" stroke-width="12" fill="transparent" />
                        @if($pOnTrack > 0)
                            <circle cx="48" cy="48" r="38" stroke="#10b981" stroke-width="12" fill="transparent"
                                    stroke-dasharray="{{ $pOnTrack }} {{ $c }}" stroke-dashoffset="{{ $offOnTrack }}"/>
                        @endif
                        @if($pAtRisk > 0)
                            <circle cx="48" cy="48" r="38" stroke="#f59e0b" stroke-width="12" fill="transparent"
                                    stroke-dasharray="{{ $pAtRisk }} {{ $c }}" stroke-dashoffset="{{ $offAtRisk }}"/>
                        @endif
                        @if($pDelayed > 0)
                            <circle cx="48" cy="48" r="38" stroke="#ef4444" stroke-width="12" fill="transparent"
                                    stroke-dasharray="{{ $pDelayed }} {{ $c }}" stroke-dashoffset="{{ $offDelayed }}"/>
                        @endif
                        @if($pStaged > 0)
                            <circle cx="48" cy="48" r="38" stroke="#94a3b8" stroke-width="12" fill="transparent"
                                    stroke-dasharray="{{ $pStaged }} {{ $c }}" stroke-dashoffset="{{ $offStaged }}"/>
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
                        <span class="text-xl font-black font-mono text-slate-900 leading-none">{{ $totalProjects }}</span>
                        <span class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Projects</span>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="space-y-1.5 text-xs flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                            <span class="text-slate-600 font-medium text-[11px] truncate">On Track</span>
                        </div>
                        <span class="font-mono font-bold text-slate-900 text-[11px] shrink-0">{{ $healthSummary['on_track']['count'] }} ({{ $healthSummary['on_track']['pct'] }}%)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span>
                            <span class="text-slate-600 font-medium text-[11px] truncate">At Risk</span>
                        </div>
                        <span class="font-mono font-bold text-slate-900 text-[11px] shrink-0">{{ $healthSummary['at_risk']['count'] }} ({{ $healthSummary['at_risk']['pct'] }}%)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            <span class="text-slate-600 font-medium text-[11px] truncate">Delayed</span>
                        </div>
                        <span class="font-mono font-bold text-slate-900 text-[11px] shrink-0">{{ $healthSummary['delayed']['count'] }} ({{ $healthSummary['delayed']['pct'] }}%)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="w-2 h-2 rounded-full bg-slate-400 shrink-0"></span>
                            <span class="text-slate-600 font-medium text-[11px] truncate">Not Started</span>
                        </div>
                        <span class="font-mono font-bold text-slate-900 text-[11px] shrink-0">{{ $healthSummary['not_started']['count'] }} ({{ $healthSummary['not_started']['pct'] }}%)</span>
                    </div>
                </div>
            </div>

            <div class="pt-2.5 border-t border-slate-100 text-center">
                <a href="{{ route('reports.index') }}" class="text-xs font-bold text-slate-700 hover:text-[#c3122e] flex items-center justify-center gap-1 group no-underline">
                    <span>View full report</span>
                    <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                </a>
            </div>
        </div>


        {{-- 2. Project Timeline (Gantt Preview) (46%) --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-wrap gap-2">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Project Timeline (Gantt Preview)</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center text-xs font-semibold gap-3">
                        <button wire:click="setTimelineScale('today')" type="button" class="transition-all cursor-pointer {{ $timelineScale === 'today' ? 'text-[#c3122e] font-bold border-b-2 border-[#c3122e] pb-0.5' : 'text-slate-500 hover:text-slate-900' }}">
                            Today
                        </button>
                        <button wire:click="setTimelineScale('week')" type="button" class="transition-all cursor-pointer {{ $timelineScale === 'week' ? 'text-[#c3122e] font-bold border-b-2 border-[#c3122e] pb-0.5' : 'text-slate-500 hover:text-slate-900' }}">
                            Week
                        </button>
                        <button wire:click="setTimelineScale('month')" type="button" class="transition-all cursor-pointer {{ $timelineScale === 'month' ? 'text-[#c3122e] font-bold border-b-2 border-[#c3122e] pb-0.5' : 'text-slate-500 hover:text-slate-900' }}">
                            Month
                        </button>
                    </div>
                    <span class="text-slate-200">|</span>
                    <a href="{{ route('project-monitor.index') }}?viewMode=gantt" class="text-xs font-bold text-[#c3122e] hover:underline flex items-center gap-1 group no-underline">
                        <span>View Full Gantt</span>
                        <span class="group-hover:translate-x-0.5 transition-transform">→</span>
                    </a>
                </div>
            </div>

            {{-- Timeline Header & Grid --}}
            <div class="my-3">
                @if($timelineScale === 'today')
                    {{-- Today Hours Header --}}
                    <div style="display: flex; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 0.75rem; font-size: 10px; font-weight: bold; color: #94a3b8;">
                        <div style="width: 32%; text-transform: uppercase; letter-spacing: 0.05em;">Project / Phase</div>
                        <div style="width: 68%; display: grid; grid-template-columns: repeat(4, 1fr); text-align: center;">
                            <div>09:00 AM</div>
                            <div style="color: #c3122e; font-weight: 700;">
                                <span>12:00 PM</span>
                                <span style="display: block; font-size: 8px; font-weight: normal; color: #f43f5e;">Now</span>
                            </div>
                            <div>03:00 PM</div>
                            <div>06:00 PM</div>
                        </div>
                    </div>

                    {{-- Today Project Timeline Rows --}}
                    <div style="position: relative; display: flex; flex-direction: column; gap: 0.75rem; min-height: 100px;">
                        <div style="position: absolute; top: 0; bottom: 0; left: calc(32% + 25%); width: 1px; border-right: 1px dashed #f87171; z-index: 10; pointer-events: none;"></div>

                        @forelse($overviewProjects as $idx => $proj)
                            @php
                                $pHealth = $proj->health ?? 'on_track';
                                $barBg = match($pHealth) {
                                    'delayed' => '#c3122e',
                                    'at_risk' => '#f59e0b',
                                    default => '#10b981',
                                };
                                $leftOffset = $idx == 0 ? 5 : 20;
                                $barWidth = $idx == 0 ? 80 : 65;
                            @endphp
                            <div style="display: flex; align-items: center; font-size: 12px;">
                                <div style="width: 32%; padding-right: 0.5rem; min-width: 0;">
                                    <a href="{{ route('projects.show', $proj->id) }}" class="font-bold text-slate-900 hover:text-[#c3122e] truncate block text-[11px] no-underline">
                                        {{ $proj->name }}
                                    </a>
                                    <span class="text-[9.5px] text-slate-400 block truncate">{{ $proj->subsidiary->name ?? 'George Steuart' }}</span>
                                </div>
                                <div style="width: 68%; height: 1.5rem; position: relative; display: flex; align-items: center;">
                                    <div style="width: 100%; height: 1.25rem; background: #f8fafc; border-radius: 6px; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                                        <div style="position: absolute; left: {{ $leftOffset }}%; width: {{ $barWidth }}%; top: 2px; bottom: 2px; background: {{ $barBg }}; color: #ffffff; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap; padding: 0 4px;">
                                            {{ now()->format('M d') }} Active Execution
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">No active projects found in timeline.</div>
                        @endforelse
                    </div>

                @elseif($timelineScale === 'week')
                    {{-- Week Days Header --}}
                    @php
                        $startOfWeek = now()->startOfWeek();
                    @endphp
                    <div style="display: flex; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 0.75rem; font-size: 10px; font-weight: bold; color: #94a3b8;">
                        <div style="width: 32%; text-transform: uppercase; letter-spacing: 0.05em;">Project / Phase</div>
                        <div style="width: 68%; display: grid; grid-template-columns: repeat(4, 1fr); text-align: center;">
                            <div>{{ $startOfWeek->format('D, M d') }}</div>
                            <div style="color: #c3122e; font-weight: 700;">
                                <span>{{ $startOfWeek->copy()->addDays(2)->format('D, M d') }}</span>
                                <span style="display: block; font-size: 8px; font-weight: normal; color: #f43f5e;">Today</span>
                            </div>
                            <div>{{ $startOfWeek->copy()->addDays(4)->format('D, M d') }}</div>
                            <div>{{ $startOfWeek->copy()->addDays(6)->format('D, M d') }}</div>
                        </div>
                    </div>

                    {{-- Week Project Timeline Rows --}}
                    <div style="position: relative; display: flex; flex-direction: column; gap: 0.75rem; min-height: 100px;">
                        <div style="position: absolute; top: 0; bottom: 0; left: calc(32% + 25%); width: 1px; border-right: 1px dashed #f87171; z-index: 10; pointer-events: none;"></div>

                        @forelse($overviewProjects as $idx => $proj)
                            @php
                                $pHealth = $proj->health ?? 'on_track';
                                $barBg = match($pHealth) {
                                    'delayed' => '#c3122e',
                                    'at_risk' => '#f59e0b',
                                    default => '#10b981',
                                };
                                $leftOffset = $idx == 0 ? 10 : 25;
                                $barWidth = $idx == 0 ? 75 : 60;
                            @endphp
                            <div style="display: flex; align-items: center; font-size: 12px;">
                                <div style="width: 32%; padding-right: 0.5rem; min-width: 0;">
                                    <a href="{{ route('projects.show', $proj->id) }}" class="font-bold text-slate-900 hover:text-[#c3122e] truncate block text-[11px] no-underline">
                                        {{ $proj->name }}
                                    </a>
                                    <span class="text-[9.5px] text-slate-400 block truncate">{{ $proj->subsidiary->name ?? 'George Steuart' }}</span>
                                </div>
                                <div style="width: 68%; height: 1.5rem; position: relative; display: flex; align-items: center;">
                                    <div style="width: 100%; height: 1.25rem; background: #f8fafc; border-radius: 6px; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                                        <div style="position: absolute; left: {{ $leftOffset }}%; width: {{ $barWidth }}%; top: 2px; bottom: 2px; background: {{ $barBg }}; color: #ffffff; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap; padding: 0 4px;">
                                            Sprint Deliverables (W{{ now()->weekOfYear }})
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">No active projects found in timeline.</div>
                        @endforelse
                    </div>

                @else
                    {{-- Default: Month View Header --}}
                    <div style="display: flex; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 0.75rem; font-size: 10px; font-weight: bold; color: #94a3b8;">
                        <div style="width: 32%; text-transform: uppercase; letter-spacing: 0.05em;">Project / Phase</div>
                        <div style="width: 68%; display: grid; grid-template-columns: repeat(4, 1fr); text-align: center;">
                            <div style="color: #c3122e; font-weight: 700;">
                                <span>{{ now()->format('M Y') }}</span>
                                <span style="display: block; font-size: 8px; font-weight: normal; color: #f43f5e;">Today</span>
                            </div>
                            <div>{{ now()->addMonth(1)->format('M Y') }}</div>
                            <div>{{ now()->addMonth(2)->format('M Y') }}</div>
                            <div>{{ now()->addMonth(3)->format('M Y') }}</div>
                        </div>
                    </div>

                    {{-- Month Project Timeline Rows --}}
                    <div style="position: relative; display: flex; flex-direction: column; gap: 0.75rem; min-height: 100px;">
                        <div style="position: absolute; top: 0; bottom: 0; left: calc(32% + 8.5%); width: 1px; border-right: 1px dashed #f87171; z-index: 10; pointer-events: none;"></div>

                        @forelse($overviewProjects as $idx => $proj)
                            @php
                                $pHealth = $proj->health ?? 'on_track';
                                $barBg = match($pHealth) {
                                    'delayed' => '#c3122e',
                                    'at_risk' => '#f59e0b',
                                    'completed' => '#10b981',
                                    default => '#10b981',
                                };
                                $barText = match($pHealth) {
                                    'at_risk' => '#78350f',
                                    default => '#ffffff',
                                };
                                $dStart = $proj->start_date ? $proj->start_date->format('M d') : ($proj->created_at ? $proj->created_at->format('M d') : 'Sep 01');
                                $dEnd = $proj->deadline ? $proj->deadline->format('M d') : 'Nov 30';

                                $leftOffset = $idx == 0 ? 5 : 12;
                                $barWidth = $idx == 0 ? 70 : 50;
                            @endphp
                            <div style="display: flex; align-items: center; font-size: 12px;">
                                <div style="width: 32%; padding-right: 0.5rem; min-width: 0;">
                                    <a href="{{ route('projects.show', $proj->id) }}" class="font-bold text-slate-900 hover:text-[#c3122e] truncate block text-[11px] no-underline">
                                        {{ $proj->name }}
                                    </a>
                                    <span class="text-[9.5px] text-slate-400 block truncate">{{ $proj->subsidiary->name ?? 'George Steuart' }}</span>
                                </div>
                                <div style="width: 68%; height: 1.5rem; position: relative; display: flex; align-items: center;">
                                    <div style="width: 100%; height: 1.25rem; background: #f8fafc; border-radius: 6px; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                                        <div style="position: absolute; left: {{ $leftOffset }}%; width: {{ $barWidth }}%; top: 2px; bottom: 2px; background: {{ $barBg }}; color: {{ $barText }}; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: bold; box-shadow: 0 1px 2px rgba(0,0,0,0.05); white-space: nowrap; padding: 0 4px;">
                                            {{ $dStart }} - {{ $dEnd }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-xs text-slate-400">No active projects found in timeline.</div>
                        @endforelse
                    </div>
                @endif
            </div>

            {{-- Bottom Legend --}}
            <div class="pt-2.5 border-t border-slate-100 flex items-center gap-4 text-[10.5px] text-slate-500 font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>On Track</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>At Risk</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    <span>Delayed</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                    <span>Not Started</span>
                </div>
            </div>
        </div>


        {{-- 3. Tasks Summary (27%) --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Tasks Summary</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>
                <a href="{{ route('project-monitor.index') }}?viewMode=table" class="text-xs font-bold text-[#c3122e] hover:underline no-underline">View all tasks →</a>
            </div>

            <div class="divide-y divide-slate-100 my-1 text-xs">
                {{-- Total Tasks --}}
                <div class="py-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Total Tasks</span>
                    </div>
                    <span class="font-mono font-black text-slate-900">{{ $totalTasksCount }}</span>
                </div>

                {{-- Completed --}}
                <div class="py-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Completed</span>
                    </div>
                    <span class="font-mono font-bold text-slate-900">
                        {{ $completedTasksCount }} <span class="text-slate-400 font-normal">({{ $totalTasksCount > 0 ? (int)round(($completedTasksCount / $totalTasksCount) * 100) : 0 }}%)</span>
                    </span>
                </div>

                {{-- In Progress --}}
                <div class="py-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>In Progress</span>
                    </div>
                    <span class="font-mono font-bold text-slate-900">
                        {{ $inProgressTasksCount }} <span class="text-slate-400 font-normal">({{ $totalTasksCount > 0 ? (int)round(($inProgressTasksCount / $totalTasksCount) * 100) : 0 }}%)</span>
                    </span>
                </div>

                {{-- On Hold --}}
                <div class="py-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>On Hold</span>
                    </div>
                    <span class="font-mono font-bold text-slate-900">
                        {{ $onHoldTasksCount }} <span class="text-slate-400 font-normal">({{ $totalTasksCount > 0 ? (int)round(($onHoldTasksCount / $totalTasksCount) * 100) : 0 }}%)</span>
                    </span>
                </div>

                {{-- Not Started --}}
                <div class="py-2 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg>
                        <span>Not Started</span>
                    </div>
                    <span class="font-mono font-bold text-slate-900">
                        {{ $notStartedTasksCount }} <span class="text-slate-400 font-normal">({{ $totalTasksCount > 0 ? (int)round(($notStartedTasksCount / $totalTasksCount) * 100) : 0 }}%)</span>
                    </span>
                </div>
            </div>

            <div class="pt-2 text-[10.5px] text-slate-400 text-center font-medium">
                Live delivery breakdown across active projects
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════════════════════
         ROW 3: 4 BOTTOM CARDS (Top Risks, Pending Approvals, Milestones, Recent Activity)
         ═══════════════════════════════════════════════════════════ --}}
    <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- 1. Top Risks --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Top Risks</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>
                <button wire:click="setDashboardTab('risks')" type="button" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">View all risks →</button>
            </div>

            <div class="my-2 divide-y divide-slate-100 text-xs flex-1">
                @forelse($allRisks->take(4) as $rsk)
                    @php
                        $score = (int)($rsk->risk_score ?? 1);
                        $sev = $score >= 6 ? 'High' : ($score >= 4 ? 'Medium' : 'Low');
                        $sevPill = match($sev) {
                            'High' => 'bg-rose-50 text-rose-700 border-rose-100',
                            'Medium' => 'bg-amber-50 text-amber-800 border-amber-100',
                            default => 'bg-slate-50 text-slate-600 border-slate-100',
                        };
                    @endphp
                    <div class="py-2 flex items-center justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-slate-900 truncate text-[11px]" title="{{ $rsk->title }}">{{ $rsk->title }}</h4>
                            <span class="text-[10px] text-slate-400 block truncate">{{ $rsk->project->name ?? 'Project' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="px-2 py-0.2 rounded-full text-[9px] font-bold border {{ $sevPill }}">{{ $sev }}</span>
                            <span class="text-[10px] font-medium text-slate-400 capitalize">{{ $rsk->status ?? 'Open' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400 flex flex-col items-center justify-center gap-1">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium text-slate-600">No active risks logged</span>
                        <span class="text-[10px]">All projects operating normally</span>
                    </div>
                @endforelse
            </div>
            
            <div class="pt-2 text-[10.5px] text-slate-400 border-t border-slate-100">
                Enterprise Risk Register Active
            </div>
        </div>


        {{-- 2. Pending Approvals --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Pending Approvals</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>
                <button wire:click="setDashboardTab('approvals')" type="button" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">View all approvals →</button>
            </div>

            <div class="divide-y divide-slate-100 my-1 text-xs flex-1">
                @php
                    $displayApprovals = $pendingApprovalList->isNotEmpty() ? $pendingApprovalList : $allApprovals->take(4);
                @endphp

                @forelse($displayApprovals as $idx => $req)
                    @php
                        $icons = [
                            0 => ['bg' => 'bg-amber-50 text-amber-600', 'badge' => 'text-rose-600 bg-rose-50'],
                            1 => ['bg' => 'bg-blue-50 text-blue-600', 'badge' => 'text-amber-700 bg-amber-50'],
                            2 => ['bg' => 'bg-orange-50 text-orange-600', 'badge' => 'text-rose-600 bg-rose-50'],
                            3 => ['bg' => 'bg-sky-50 text-sky-600', 'badge' => 'text-slate-600 bg-slate-100'],
                        ];
                        $st = $icons[$idx % 4];
                        $rawReqType = $req->request_type instanceof \BackedEnum ? $req->request_type->value : (string)($req->request_type ?? '');
                        $reqTitle = match($rawReqType) {
                            'deadline_extension' => 'Deadline Extension',
                            'budget_variation' => 'Budget Variation',
                            'scope_change' => 'Scope Change',
                            'new_project_plan' => 'Project Plan Sign-off',
                            'member_addition' => 'Resource Addition',
                            default => ucwords(str_replace('_', ' ', $rawReqType ?: 'Approval Request'))
                        };
                        $isPending = ($req->status instanceof \BackedEnum ? $req->status->value : (string)$req->status) === 'pending';
                    @endphp
                    <div class="py-2 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-lg {{ $st['bg'] }} flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 truncate text-[11px]">{{ $reqTitle }}</h4>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $req->project->name ?? 'Enterprise Project' }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full font-mono text-[10px] font-black {{ $isPending ? 'text-rose-600 bg-rose-50' : 'text-emerald-700 bg-emerald-50' }}">
                            {{ $isPending ? 'Pending' : 'Done' }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400 flex flex-col items-center justify-center gap-1">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-slate-600">All approvals clear</span>
                        <span class="text-[10px]">No pending requests</span>
                    </div>
                @endforelse
            </div>

            <div class="pt-2 text-[10.5px] text-slate-400 border-t border-slate-100">
                Governance Workflow Active
            </div>
        </div>


        {{-- 3. Upcoming Milestones --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Upcoming Milestones</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>
                <a href="{{ route('calendar.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline no-underline">View calendar →</a>
            </div>

            <div class="divide-y divide-slate-100 my-1 text-xs flex-1">
                @forelse($upcomingTasks->take(4) as $task)
                    @php
                        $dl = $task->end_date ? (int)now()->today()->diffInDays($task->end_date, false) : 0;
                        $isOvr = $dl < 0;
                        $labelDays = $isOvr ? abs($dl).'d overdue' : ($dl === 0 ? 'Today' : "{$dl} days left");
                        $dayColor = $isOvr ? 'text-rose-600 font-bold' : ($dl <= 7 ? 'text-rose-600 font-semibold' : 'text-slate-500 font-medium');
                    @endphp
                    <div class="py-2 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 text-center flex-shrink-0">
                                <span class="text-[8.5px] font-black uppercase text-[#c3122e] block leading-none font-mono">
                                    {{ $task->end_date ? $task->end_date->format('M') : 'SEP' }}
                                </span>
                                <span class="text-xs font-black font-mono text-slate-900 block leading-tight">
                                    {{ $task->end_date ? $task->end_date->format('d') : '02' }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 truncate text-[11px]" title="{{ $task->title }}">{{ $task->title }}</h4>
                                <span class="text-[10px] text-slate-400 block truncate">{{ $task->project->name ?? 'System Integration' }}</span>
                            </div>
                        </div>
                        <span class="text-[10px] {{ $dayColor }} font-mono whitespace-nowrap">
                            {{ $labelDays }}
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">No upcoming milestone deadlines.</div>
                @endforelse
            </div>

            <div class="pt-2 text-[10.5px] text-slate-400 border-t border-slate-100">
                WBS Delivery Schedule Synced
            </div>
        </div>


        {{-- 4. Recent Activity --}}
        <div class="min-w-0 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-sm font-bold text-slate-900">Recent Activity</h2>
                    <span class="text-slate-300 text-xs font-serif italic cursor-help">ⓘ</span>
                </div>
                <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-[#c3122e] hover:underline no-underline">View all activity →</a>
            </div>

            <div class="divide-y divide-slate-100 my-1 text-xs flex-1">
                @forelse($recentActivityLogs->take(4) as $idx => $act)
                    @php
                        $icons = [
                            0 => ['color' => 'text-emerald-500', 'bg' => 'bg-emerald-50'],
                            1 => ['color' => 'text-rose-500', 'bg' => 'bg-rose-50'],
                            2 => ['color' => 'text-amber-500', 'bg' => 'bg-amber-50'],
                            3 => ['color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
                        ];
                        $st = $icons[$idx % 4];
                        $aTitle = match($act->action) {
                            'created_project'          => 'created project',
                            'updated_project'          => 'updated project details',
                            'created_wbs_item'         => 'added task deliverable',
                            'updated_wbs_item'         => 'updated task progress',
                            'created_status_update'    => 'submitted daily update',
                            'created_risk'             => 'reported a risk item',
                            default                    => str_replace('_', ' ', $act->action)
                        };
                        $who = $act->user_id === auth()->id() ? 'You' : ($act->user->name ?? 'System');
                    @endphp
                    <div class="py-2 flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-full {{ $st['bg'] }} {{ $st['color'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-slate-800 leading-snug line-clamp-2 text-[11px]">
                                <strong class="text-slate-900 font-semibold">{{ $who }}</strong> {{ $aTitle }}
                            </p>
                            <span class="text-[9.5px] text-slate-400 mt-0.5 block">{{ $act->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-400">No recent activity recorded.</div>
                @endforelse
            </div>

            <div class="pt-2 text-[10.5px] text-slate-400 border-t border-slate-100">
                Live Audit Logs Active
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════════════════════
         MODALS FOR ACTIONS (Approvals, Risks, Blockers)
         ═══════════════════════════════════════════════════════════ --}}
    @if($showReviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-in fade-in duration-200">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Executive Approval Sign-Off</h3>
                    </div>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-700 text-lg cursor-pointer">&times;</button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Executive Notes / Sign-off Comments</label>
                        <textarea wire:model="reviewComment" rows="3"
                                  class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-[#c3122e] focus:border-transparent outline-hidden"
                                  placeholder="Specify any conditions, governance notes, or reasons for decision..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button wire:click="$set('showReviewModal', false)" type="button"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button wire:click="submitReview(false)" type="button"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
                            Reject Request
                        </button>
                        <button wire:click="submitReview(true)" type="button"
                                class="px-5 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all cursor-pointer">
                            ✓ Approve Sign-Off
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-slate-200">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-base font-black text-slate-900 tracking-tight">Resolve Work Blocker</h3>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-700 text-lg cursor-pointer">&times;</button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Resolution Summary</label>
                        <textarea wire:model="blockerResolutionInput" rows="3"
                                  class="w-full rounded-xl border border-slate-200 p-3 text-xs focus:ring-2 focus:ring-[#c3122e] focus:border-transparent outline-hidden"
                                  placeholder="Describe how the obstacle was cleared or resource provided..."></textarea>
                        @error('blockerResolutionInput') <span class="text-rose-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button wire:click="$set('showResolveBlockerModal', false)" type="button"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button wire:click="resolveBlocker" type="button"
                                class="px-5 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition-all cursor-pointer">
                            Clear &amp; Mark Resolved
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
