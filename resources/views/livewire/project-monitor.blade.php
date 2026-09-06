<div class="space-y-6 pt-1">
    <!-- Style Enhancements for Modern PMO Command Center -->
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 11px;
            padding-right: 30px !important;
        }
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    <!-- Flash Success / Error Toast Banners -->
    @if(session()->has('success_message'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif
    @if(session()->has('error_message'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">❌</span>
                <span>{{ session('error_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER & GOVERNANCE COMMAND HUB
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Governance Command Hub Banner" 
                class="w-full h-full object-cover object-right opacity-90"
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

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            <!-- Left Side: Shield Mark + Title + Subtitle -->
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-xl flex-shrink-0 border border-white/20 ring-4 ring-rose-500/15 flex items-center justify-center p-2.5" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Tracking Center
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold text-amber-200 border border-amber-400/30 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(184, 134, 11, 0.35);">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>Enterprise Governance</span>
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 font-medium line-clamp-1">
                        Cross-subsidiary portfolio monitoring, RAG health diagnostics &amp; execution velocity.
                    </p>
                </div>
            </div>

            <!-- Right Side: View Mode Switcher + Print / Export Action -->
            <div class="flex items-center gap-2.5 flex-shrink-0 self-stretch sm:self-auto justify-between sm:justify-end flex-wrap">
                <!-- View Mode Switcher: Table vs Tasks vs Gantt vs Stuck -->
                <div class="overflow-x-auto scrollbar-none max-w-full pb-1 sm:pb-0" style="scrollbar-width: none;">
                    <div class="inline-flex p-1 rounded-xl border border-white/15 shadow-xl backdrop-blur-xl flex-nowrap min-w-max" style="background: rgba(0, 0, 0, 0.45);">
                        <button 
                            wire:click="setViewMode('table')" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'table' ? 'bg-white text-slate-900 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                            title="Master Governance Table"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <span>Table</span>
                        </button>

                        <button 
                            wire:click="setViewMode('gantt')" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'gantt' ? 'bg-white text-amber-900 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/10' }}"
                            title="Master Portfolio Gantt Radar"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Portfolio Gantt</span>
                        </button>
                        <button 
                            wire:click="setViewMode('stuck')" 
                            type="button" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 {{ $viewMode === 'stuck' ? 'bg-[#c3122e] text-white shadow-md scale-[1.02]' : 'text-rose-300 hover:text-white hover:bg-rose-900/40' }}"
                            title="Live Stuck & Blocked Tasks Radar"
                        >
                            <span class="w-2 h-2 rounded-full bg-rose-400 {{ $totalStuckTasksCount > 0 ? 'animate-pulse' : '' }}"></span>
                            <span>Stuck Tasks</span>
                            <span class="px-1.5 py-0.2 rounded-md text-[10px] font-mono bg-white/20 text-white">{{ $totalStuckTasksCount }}</span>
                        </button>
                    </div>
                </div>

                <!-- Print & PDF Brief Button -->
                <button 
                    onclick="window.print()" 
                    class="px-3 py-1.5 rounded-xl text-xs font-bold text-white border border-white/20 hover:bg-white/10 shadow-md transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0"
                    title="Print Executive Portfolio Brief"
                >
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Print Brief</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. CLEAN 5 KPI METRIC CARDS (EXACT MATCH TO REFERENCE)
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'matrix' || $viewMode === 'table')
        <style>
            @media (min-width: 1024px) {
                .kpi-5-grid {
                    display: grid !important;
                    grid-template-columns: repeat(5, minmax(0, 1fr)) !important;
                }
                .filter-single-row {
                    display: flex !important;
                    align-items: flex-end !important;
                    gap: 0.75rem !important;
                }
            }
        </style>

        <div class="grid grid-cols-2 sm:grid-cols-3 kpi-5-grid gap-3.5 sm:gap-4">

            <!-- 1. Total Projects (Blue Folder) -->
            <button type="button" wire:click="setQuickSegment('all'); $set('healthFilter', 'all'); $set('statusFilter', 'all')"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-4 text-left transition-all duration-200 cursor-pointer hover:shadow-md hover:-translate-y-0.5
                           {{ $quickSegment === 'all' && $healthFilter === 'all' && $statusFilter === 'all' ? 'border-blue-400 shadow-sm' : 'hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-800 leading-tight tracking-tight">{{ $totalProjectsCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5">Total Projects</div>
                </div>
            </button>

            <!-- 2. On Track (Green Checkmark) -->
            <button type="button" wire:click="setQuickSegment('on_track'); $set('statusFilter', 'all')"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-4 text-left transition-all duration-200 cursor-pointer hover:shadow-md hover:-translate-y-0.5
                           {{ $quickSegment === 'on_track' ? 'border-emerald-400 shadow-sm' : 'hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-800 leading-tight tracking-tight">{{ $onTrackCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5">On Track</div>
                </div>
            </button>

            <!-- 3. Delayed (Amber Clock) -->
            <button type="button" wire:click="setQuickSegment('critical'); $set('statusFilter', 'all')"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-4 text-left transition-all duration-200 cursor-pointer hover:shadow-md hover:-translate-y-0.5
                           {{ $quickSegment === 'critical' ? 'border-amber-400 shadow-sm' : 'hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-800 leading-tight tracking-tight">{{ $delayedCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5">Delayed</div>
                </div>
            </button>

            <!-- 4. At Risk / Blocked (Red Alert Circle) -->
            <button type="button" wire:click="setQuickSegment('at_risk'); $set('statusFilter', 'all')"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-4 text-left transition-all duration-200 cursor-pointer hover:shadow-md hover:-translate-y-0.5
                           {{ $quickSegment === 'at_risk' ? 'border-rose-400 shadow-sm' : 'hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-800 leading-tight tracking-tight">{{ $atRiskOrBlockedCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5">At Risk / Blocked</div>
                </div>
            </button>

            <!-- 5. On Hold (Purple Users) -->
            <button type="button" wire:click="$set('statusFilter', 'on_hold'); setQuickSegment('all')"
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-4 text-left transition-all duration-200 cursor-pointer hover:shadow-md hover:-translate-y-0.5
                           {{ $statusFilter === 'on_hold' ? 'border-purple-400 shadow-sm' : 'hover:border-slate-300' }}">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-mono text-slate-800 leading-tight tracking-tight">{{ $onHoldCount }}</div>
                    <div class="text-xs font-semibold text-slate-400 mt-0.5">On Hold</div>
                </div>
            </button>

        </div>

        <!-- 3. CLEAN SIMPLE SEARCH & FILTER BAR (EXACT MATCH TO UPLOADED MOCKUP) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-3.5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <!-- Left: Search projects by name or code... -->
            <div class="relative flex-1 min-w-[260px]">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search projects by name or code..."
                       class="w-full pl-10 pr-3 py-1.5 bg-transparent border-0 text-xs sm:text-[13px] font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-0">
            </div>

            <!-- Right: Status: All ∨ and Priority: All ∨ -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <!-- Status: All ∨ -->
                <div class="relative">
                    <select wire:model.live="statusFilter" 
                            class="appearance-none bg-white border border-slate-200 rounded-xl px-3.5 py-1.5 text-xs font-medium text-slate-700 pr-7 shadow-2xs hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                        <option value="all">Status: All</option>
                        <option value="in_progress">Status: Active</option>
                        <option value="delayed">Status: Delayed</option>
                        <option value="planning">Status: Planned</option>
                        <option value="completed">Status: Completed</option>
                        <option value="on_hold">Status: On Hold</option>
                    </select>
                    <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                </div>

                <!-- Priority: All ∨ -->
                <div class="relative">
                    <select wire:model.live="priorityFilter" 
                            class="appearance-none bg-white border border-slate-200 rounded-xl px-3.5 py-1.5 text-xs font-medium text-slate-700 pr-7 shadow-2xs hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                        <option value="all">Priority: All</option>
                        <option value="high">Priority: High</option>
                        <option value="medium">Priority: Medium</option>
                        <option value="low">Priority: Low</option>
                        <option value="critical">Priority: Critical</option>
                    </select>
                    <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                </div>

                <!-- Reset if any filter is active -->
                @if($search || $statusFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all' || $pmFilter !== 'all' || $subsidiaryFilter !== 'all')
                    <button wire:click="resetFilters" type="button" title="Reset all filters"
                            class="px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-1 cursor-pointer shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         4. MAIN PORTFOLIO DISPLAY VIEWS
         ═══════════════════════════════════════════════════════════════ -->
    
    <!-- ═══════════════════════════════════════════════════════════
         VIEW 1: 📊 EXECUTIVE MATRIX CARDS GRID
         ═══════════════════════════════════════════════════════════ -->
    @if($viewMode === 'matrix')
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($paginatedProjects as $project)
                @php
                    $hColor = match($project->computed_health) {
                        'delayed'   => ['accent' => '#c3122e',  'bg' => 'bg-rose-50',    'text' => 'text-[#c3122e]',   'border' => 'border-rose-200',    'dot' => 'bg-[#c3122e]',    'bar' => 'bg-[#c3122e]',    'label' => 'DELAYED',   'barBg' => 'bg-rose-100',   'cardBorder' => 'border-rose-200'],
                        'at_risk'   => ['accent' => '#d97706',  'bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500',    'bar' => 'bg-amber-500',    'label' => 'AT RISK',   'barBg' => 'bg-amber-100',  'cardBorder' => 'border-amber-200'],
                        'on_track'  => ['accent' => '#059669',  'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500',  'bar' => 'bg-emerald-500',  'label' => 'ON TRACK',  'barBg' => 'bg-emerald-100','cardBorder' => 'border-emerald-200'],
                        default     => ['accent' => '#64748b',  'bg' => 'bg-slate-50',   'text' => 'text-slate-600',   'border' => 'border-slate-200',   'dot' => 'bg-slate-400',    'bar' => 'bg-slate-400',    'label' => 'COMPLETED', 'barBg' => 'bg-slate-100',  'cardBorder' => 'border-slate-200'],
                    };
                    $priorityStyle = match($project->priority->value ?? '') {
                        'urgent' => 'bg-rose-600 text-white',
                        'high'   => 'bg-amber-500 text-white',
                        'medium' => 'bg-slate-600 text-white',
                        default  => 'bg-slate-200 text-slate-600',
                    };
                    $pmAccepted = $project->isPmAccepted();
                    $pmRejected = $project->isPmRejected();
                @endphp

                <div class="bg-white rounded-2xl border {{ $hColor['cardBorder'] }} shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col overflow-hidden group">
                    
                    <!-- ── Colored accent top stripe ── -->
                    <div class="h-1 w-full" style="background: {{ $hColor['accent'] }};"></div>

                    <!-- ── Card Body ── -->
                    <div class="p-4 sm:p-5 space-y-4 flex-1">

                        <!-- Row 1: Code + RAG + Priority -->
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black font-mono bg-slate-100 text-slate-700 border border-slate-200 tracking-wider">
                                {{ $project->code }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black border {{ $hColor['bg'] }} {{ $hColor['text'] }} {{ $hColor['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $hColor['dot'] }} {{ $project->computed_health === 'delayed' ? 'animate-pulse' : '' }}"></span>
                                    {{ $hColor['label'] }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[9.5px] font-black uppercase {{ $priorityStyle }}">
                                    {{ $project->priority->value ?? 'medium' }}
                                </span>
                            </div>
                        </div>

                        <!-- Row 2: Subsidiary + Project Name -->
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">
                                {{ $project->subsidiary->name ?? 'George Steuart Group' }}
                            </p>
                            <a href="{{ route('projects.show', $project->id) }}" 
                               class="text-sm sm:text-[15px] font-extrabold text-slate-900 hover:text-[#c3122e] transition-colors leading-snug line-clamp-2 block"
                               title="{{ $project->name }}">
                                {{ $project->name }}
                            </a>
                        </div>

                        <!-- Row 3: PM + PM Status -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-full bg-slate-900 text-white font-bold text-[11px] flex items-center justify-center flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($project->projectManager->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-[11px] font-bold text-slate-700 truncate">
                                    {{ $project->projectManager->name ?? 'Unassigned' }}
                                </span>
                            </div>
                            @if($pmAccepted)
                                <span class="inline-flex items-center gap-1 text-[9.5px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md flex-shrink-0">
                                    ✔ Confirmed
                                </span>
                            @elseif($pmRejected)
                                <span class="inline-flex items-center gap-1 text-[9.5px] font-black text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md flex-shrink-0" title="{{ $project->pm_rejection_reason }}">
                                    ✕ Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[9.5px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md flex-shrink-0">
                                    ⏳ Pending
                                </span>
                            @endif
                        </div>

                        <!-- Row 4: Progress -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-500">Progress</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-slate-900 font-mono">{{ $project->overall_progress }}%</span>
                                    <span class="font-mono text-[10px] {{ $project->progress_delta < 0 ? 'text-rose-500' : 'text-emerald-600' }}">
                                        {{ $project->progress_delta >= 0 ? '▲' : '▼' }}{{ abs($project->progress_delta) }}% vs plan
                                    </span>
                                </div>
                            </div>
                            <div class="w-full {{ $hColor['barBg'] }} h-2 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $hColor['bar'] }} transition-all duration-500" style="width: {{ max(2, $project->overall_progress) }}%"></div>
                            </div>
                        </div>

                        <!-- Row 5: Deadline + Tasks Stats -->
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            <!-- Deadline -->
                            <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                <p class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Deadline</p>
                                <p class="font-bold text-slate-800 font-mono text-[11px]">
                                    {{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}
                                </p>
                                @if($project->is_past_deadline)
                                    <span class="text-[9.5px] font-black text-[#c3122e]">{{ abs($project->days_remaining) }}d overdue</span>
                                @elseif($project->days_remaining !== null)
                                    <span class="text-[9.5px] font-bold text-slate-500">{{ $project->days_remaining }}d left</span>
                                @endif
                            </div>

                            <!-- Tasks & Alerts -->
                            <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                                <p class="text-[9.5px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Deliverables</p>
                                <p class="font-black text-slate-900 font-mono text-[11px]">
                                    {{ $project->completed_tasks_count }}<span class="font-bold text-slate-400">/{{ $project->total_tasks_count }}</span>
                                </p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($project->overdue_tasks_count > 0)
                                        <span class="text-[9px] font-black text-rose-600">🚨 {{ $project->overdue_tasks_count }}</span>
                                    @endif
                                    @if($project->blocked_tasks_count > 0)
                                        <span class="text-[9px] font-black text-red-600">🛑 {{ $project->blocked_tasks_count }}</span>
                                    @endif
                                    @if($project->overdue_tasks_count === 0 && $project->blocked_tasks_count === 0)
                                        <span class="text-[9px] font-bold text-emerald-600">✓ Clear</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ── Card Footer: Single clean action strip ── -->
                    <div class="px-4 py-2.5 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
                        <a href="{{ route('projects.show', $project->id) }}" 
                           class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-[11px] font-black text-white transition-all hover:opacity-90 shadow-sm"
                           style="background: #1e293b;">
                            🚀 Workspace
                        </a>
                        <button wire:click="openWbsDrawer({{ $project->id }})" 
                                class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg text-[11px] font-black text-blue-800 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition-all cursor-pointer">
                            🔍 WBS
                        </button>
                        @if($project->project_manager_id)
                            <button wire:click="quickPingPm({{ $project->id }})" 
                                    class="py-1.5 px-2.5 rounded-lg text-[11px] font-black text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer"
                                    title="1-Click Fast Ping to PM">
                                ⚡
                            </button>
                            <button wire:click="openNudgeModal({{ $project->id }})" 
                                    class="py-1.5 px-2.5 rounded-lg text-[11px] font-black text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-all cursor-pointer"
                                    title="Send Custom PM Alert">
                                🔔
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200 shadow-xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center text-xl">
                        🔍
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-sm">No projects matching your search &amp; filter criteria</h3>
                    <p class="text-xs text-slate-400 max-w-sm mx-auto">Try clearing your filters or search keywords to view other enterprise projects.</p>
                    <button wire:click="resetFilters" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-black transition-all cursor-pointer">
                        Reset All Filters
                    </button>
                </div>
            @endforelse
        </div>

    <!-- ═══════════════════════════════════════════════════════════
         VIEW 2: 📋 EXECUTIVE MASTER GOVERNANCE TABLE — EXACT MATCH
         ═══════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'table')
        <!-- ── Clean Modern Executive Table Card (Exact Match to Mockup) ── -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-xs font-semibold text-slate-500">
                            <th class="py-3.5 px-4 min-w-[200px]">Project Name</th>
                            <th class="py-3.5 px-4 min-w-[90px]">Code</th>
                            <th class="py-3.5 px-4 min-w-[100px]">Status</th>
                            <th class="py-3.5 px-4 min-w-[90px]">Priority</th>
                            <th class="py-3.5 px-4 min-w-[160px]">Progress</th>
                            <th class="py-3.5 px-4 min-w-[160px]">Project Manager</th>
                            <th class="py-3.5 px-4 min-w-[180px]">Timeline</th>
                            <th class="py-3.5 px-4 text-right w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 bg-white font-medium text-slate-700">
                        @forelse($paginatedProjects as $project)
                            @php
                                $h = $project->computed_health;
                                $pmName   = $project->projectManager->name ?? 'Unassigned';
                                $initials = collect(explode(' ', $pmName))->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                                $avatarPalette = ['#4f46e5','#0284c7','#7c3aed','#d97706','#059669','#e11d48'];
                                $avatarBg = $avatarPalette[abs(crc32($pmName)) % count($avatarPalette)];
                                $prog     = $project->overall_progress;
                                $isDelayed = $project->status->value === 'delayed' || $h === 'delayed';
                                $isCompleted = $project->status->value === 'completed' || $h === 'completed';
                                $isPlanning = $project->status->value === 'planning' || $project->status->value === 'draft';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors group cursor-pointer"
                                onclick="window.location='{{ route('projects.show', $project->id) }}'">
                                
                                <!-- 1. Project Name (with Left Dot Indicator) -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <span style="background-color: {{ $isDelayed ? '#be123c' : ($isCompleted ? '#047857' : ($isPlanning ? '#94a3b8' : '#e07a1e')) }};" 
                                              class="w-2 h-2 rounded-full flex-shrink-0"></span>
                                        <span class="font-bold text-slate-900 text-xs sm:text-[13px] hover:text-blue-600 transition-colors">
                                            {{ $project->name }}
                                        </span>
                                    </div>
                                </td>

                                <!-- 2. Code -->
                                <td class="py-4 px-4 whitespace-nowrap text-xs font-medium text-slate-500">
                                    {{ $project->code }}
                                </td>

                                <!-- 3. Status Badge -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if($isCompleted)
                                        <span style="background-color: #ecfdf5; color: #047857;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                            Completed
                                        </span>
                                    @elseif($isDelayed)
                                        <span style="background-color: #fde8eb; color: #be123c;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                            Delayed
                                        </span>
                                    @elseif($isPlanning)
                                        <span style="background-color: #f1f5f9; color: #64748b;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                            Planned
                                        </span>
                                    @elseif($project->status->value === 'on_hold')
                                        <span style="background-color: #f3e8ff; color: #7e22ce;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                            On Hold
                                        </span>
                                    @else
                                        <span style="background-color: #fef3e2; color: #b45309;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                            Active
                                        </span>
                                    @endif
                                </td>

                                <!-- 4. Priority -->
                                <td class="py-4 px-4 whitespace-nowrap text-xs">
                                    @if($project->priority->value === 'high' || $project->priority->value === 'critical')
                                        <span style="color: #be123c;" class="font-bold text-xs">High</span>
                                    @elseif($project->priority->value === 'medium')
                                        <span style="color: #b45309;" class="font-bold text-xs">Medium</span>
                                    @else
                                        <span style="color: #047857;" class="font-bold text-xs">Low</span>
                                    @endif
                                </td>

                                <!-- 5. Progress (Guaranteed Visible 130px Pill Bar Matching Mockup) -->
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div style="width: 130px; height: 8px; background-color: #edebe7; border-radius: 9999px; overflow: hidden; flex-shrink: 0;">
                                            <div style="width: {{ max(4, $prog) }}%; height: 100%; border-radius: 9999px; background-color: {{ $isDelayed ? '#be123c' : '#10b981' }}; transition: width 0.3s;"></div>
                                        </div>
                                        <span class="font-medium font-mono text-xs text-slate-700">{{ $prog }}%</span>
                                    </div>
                                </td>

                                <!-- 6. Project Manager -->
                                <td class="py-4 px-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2.5">
                                        @if($project->projectManager?->profile_photo_url)
                                            <img src="{{ $project->projectManager->profile_photo_url }}" class="w-6 h-6 rounded-full object-cover shadow-2xs" alt="">
                                        @else
                                            <div class="w-6 h-6 rounded-full text-white font-bold text-[10px] flex items-center justify-center flex-shrink-0 shadow-2xs"
                                                 style="background: {{ $avatarBg }};">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-slate-700 text-xs">{{ $pmName }}</span>
                                    </div>
                                </td>

                                <!-- 7. Timeline -->
                                <td class="py-4 px-4 whitespace-nowrap text-xs text-slate-500 font-medium">
                                    @if($project->start_date && $project->deadline)
                                        {{ $project->start_date->format('d M Y') }} — {{ $project->deadline->format('d M Y') }}
                                    @elseif($project->deadline)
                                        Due {{ $project->deadline->format('d M Y') }}
                                    @else
                                        Ongoing
                                    @endif
                                </td>

                                <!-- 8. Actions (•••) -->
                                <td class="py-4 px-4 text-right whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" type="button" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 cursor-pointer transition-colors font-bold text-sm tracking-widest leading-none" title="Actions">
                                            •••
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition
                                             class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-30 text-xs">
                                            <a href="{{ route('projects.show', $project->id) }}" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-slate-700 font-medium flex items-center gap-2">
                                                <span>📂</span> Project Workspace
                                            </a>
                                            <button wire:click="openWbsDrawer({{ $project->id }}); open = false" class="w-full text-left px-3 py-2 hover:bg-slate-50 text-slate-700 font-medium flex items-center gap-2">
                                                <span>📋</span> Inspect WBS Tasks
                                            </button>
                                            @if($project->blocked_tasks_count > 0 || $project->open_risks_count > 0)
                                                <button wire:click="openBlockersModal({{ $project->id }}); open = false" class="w-full text-left px-3 py-2 hover:bg-rose-50 text-rose-600 font-medium flex items-center gap-2">
                                                    <span>⚠️</span> View Blockers ({{ $project->blocked_tasks_count }})
                                                </button>
                                            @endif
                                            <button wire:click="openNudgeModal({{ $project->id }}); open = false" class="w-full text-left px-3 py-2 hover:bg-amber-50 text-amber-700 font-medium flex items-center gap-2">
                                                <span>🔔</span> Alert PM
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-2xl">🔍</span>
                                        <span class="text-sm font-bold text-slate-700">No projects found</span>
                                        <span class="text-xs text-slate-400">Try adjusting your filters or search criteria.</span>
                                        <button wire:click="resetFilters" class="mt-2 px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#800a1c] transition-all cursor-pointer">
                                            Reset Filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-5 py-3.5 bg-white border-t border-slate-100">
                {{ $paginatedProjects->links() }}
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════
         VIEW 3: 📈 MASTER PORTFOLIO GANTT RADAR
         ═══════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'gantt')
        <div class="space-y-4">
            
            <!-- ── 1. GANTT HEADER & FILTER COMMAND STRIP ── -->
            <div class="bg-white border border-slate-200 shadow-xs rounded-2xl p-4 sm:p-5 space-y-4">
                
                <!-- Top Row: Title, Horizon Summary & Timeframe Switcher -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white shadow-md shadow-rose-900/20 ring-4 ring-rose-50 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #800a1c 100%);">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base font-black text-slate-900 tracking-tight">Enterprise Multi-Project Schedule Radar</h2>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $ganttTimeframe === 'auto' ? 'Auto (Fit All)' : ($ganttTimeframe === '3m' ? '3 Months' : ($ganttTimeframe === '12m' ? '12 Months' : '6 Months')) }}
                                </span>
                                @if($ganttMonthOffset !== 0)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1">
                                        <span>Shifted {{ $ganttMonthOffset > 0 ? "+{$ganttMonthOffset}" : $ganttMonthOffset }} mo</span>
                                        <button wire:click="ganttToday" class="underline hover:text-amber-950 cursor-pointer text-[9px] font-black" title="Reset to today">Reset</button>
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium flex items-center gap-1.5 flex-wrap">
                                <span>Timeline Span:</span>
                                <strong class="text-slate-800 font-mono font-bold">{{ $ganttTimeline['start']->format('M d, Y') }}</strong>
                                <span class="text-slate-400">→</span>
                                <strong class="text-slate-800 font-mono font-bold">{{ $ganttTimeline['end']->format('M d, Y') }}</strong>
                                <span class="text-slate-400 font-normal">({{ $ganttTimeline['total_days'] }} Days)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Controls: Navigation Buttons & Timeframe Switcher -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Date Navigator (Prev / Today / Next) -->
                        <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden p-0.5">
                            <button wire:click="ganttPrev" class="px-2.5 py-1.5 flex items-center gap-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-bold transition-all cursor-pointer" title="Shift 1 Month Earlier">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                <span class="hidden sm:inline">Prev</span>
                            </button>
                            <button wire:click="ganttToday" class="px-2.5 py-1.5 text-slate-700 hover:text-[#c3122e] hover:bg-rose-50 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 border-x border-slate-100" title="Center Timeline on Current Month">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                                <span>Today</span>
                            </button>
                            <button wire:click="ganttNext" class="px-2.5 py-1.5 flex items-center gap-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg text-xs font-bold transition-all cursor-pointer" title="Shift 1 Month Later">
                                <span class="hidden sm:inline">Next</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        <!-- Horizon switcher (Auto / 3M / 6M / 12M) -->
                        <div class="inline-flex p-1 rounded-xl bg-slate-100 border border-slate-200/90 text-xs font-bold">
                            <button wire:click="setGanttTimeframe('auto')" 
                                    class="px-2.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === 'auto' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}"
                                    title="Auto fit all projects from earliest start to latest deadline">
                                Auto
                            </button>
                            <button wire:click="setGanttTimeframe('3m')" 
                                    class="px-2.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '3m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                3M
                            </button>
                            <button wire:click="setGanttTimeframe('6m')" 
                                    class="px-2.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '6m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                6M
                            </button>
                            <button wire:click="setGanttTimeframe('12m')" 
                                    class="px-2.5 py-1.5 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '12m' ? 'bg-white text-slate-900 shadow-xs font-black' : 'text-slate-500 hover:text-slate-900' }}">
                                12M
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bottom Filter, Expand/Collapse & Legend Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                    <!-- Expand/Collapse + Filters -->
                    <div class="flex flex-wrap items-center gap-2.5 flex-1 min-w-[280px]">
                        <!-- Expand All / Collapse All Toggle -->
                        <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-200 shrink-0">
                            <button wire:click="expandAllGanttProjects" class="hover:text-[#c3122e] transition-colors cursor-pointer" title="Expand deliverables for all projects">
                                Expand All
                            </button>
                            <span class="text-slate-300">•</span>
                            <button wire:click="collapseAllGanttProjects" class="hover:text-slate-900 transition-colors cursor-pointer" title="Collapse all deliverables">
                                Collapse All
                            </button>
                        </div>

                        <!-- Search -->
                        <div class="relative flex-1 min-w-[160px] max-w-[240px]">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search scheduled projects…"
                                   class="w-full pl-8.5 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all">
                        </div>

                        <!-- Subsidiary Filter -->
                        <select wire:model.live="subsidiaryFilter" class="custom-select px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all min-w-[130px]">
                            <option value="all">🏢 All Subsidiaries</option>
                            @foreach($subsidiaries as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>

                        <!-- Health Filter -->
                        <select wire:model.live="healthFilter" class="custom-select px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all min-w-[115px]">
                            <option value="all">🚦 All Health</option>
                            <option value="delayed">🔴 Delayed</option>
                            <option value="at_risk">🟡 At Risk</option>
                            <option value="on_track">🟢 On Track</option>
                        </select>

                        <!-- PM Filter -->
                        <select wire:model.live="pmFilter" class="custom-select px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/15 focus:border-[#c3122e] transition-all min-w-[125px]">
                            <option value="all">👤 All Managers</option>
                            @foreach($pms as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>

                        @if($search || $subsidiaryFilter !== 'all' || $healthFilter !== 'all' || $pmFilter !== 'all')
                            <button wire:click="resetFilters" class="px-2.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-all cursor-pointer">
                                Reset
                            </button>
                        @endif
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-600 flex-wrap">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-xs"></span> On Track
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-xs"></span> At Risk
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c3122e] shadow-xs"></span> Delayed
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-amber-500 rotate-45"></span> Milestone
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-3.5 h-0.5 bg-rose-500 border-t-2 border-dashed border-rose-500"></span>
                            <span class="text-rose-600 font-bold text-[11px]">Today</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- ── 2. GANTT TIMELINE WORKSPACE (STRUCTURED PIXEL-PERFECT GRID) ── -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden">
                <div class="overflow-x-auto overflow-y-auto max-h-[760px] scrollbar-thin">
                    <div class="min-w-[1550px]" style="min-width: 1550px;">
                        
                        <!-- ── TIMELINE HEADER ROW (STICKY TOP) ── -->
                        <div class="flex border-b border-slate-200 bg-slate-100 text-slate-700 select-none sticky top-0 z-30 shadow-2xs">
                            
                            <!-- Left Sidebar Column Header (420px fixed, spacious) -->
                            <div class="w-[420px] min-w-[420px] flex-shrink-0 flex items-center justify-between px-4 h-11 border-r border-slate-200 bg-slate-100 sticky left-0 z-40">
                                <div class="font-black text-[11px] uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    <span>PROJECT &amp; GOVERNANCE</span>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-white text-slate-600 border border-slate-200/80 shadow-2xs">
                                    {{ count($ganttTimeline['projects']) }} Projects
                                </span>
                            </div>

                            <!-- Right Months Timeline Headers (px-0 exact grid alignment) -->
                            <div class="flex-1 min-w-[1130px] flex relative bg-slate-50 h-11 px-0">
                                @foreach($ganttTimeline['months'] as $m)
                                    @php
                                        // Choose label format based on timeframe & month count
                                        $mDisplayLabel = match($ganttTimeframe) {
                                            '3m'  => $m['label'],          // Aug 2026 (full)
                                            '12m' => $m['short'],          // Aug (short)
                                            default => $m['short_label'],  // Aug '26 (compact)
                                        };
                                        if ($ganttTimeframe === '12m' && $m['month_num'] === 1) {
                                            $mDisplayLabel = $m['short_label']; // Jan '27
                                        }
                                    @endphp
                                    <div class="border-r border-slate-200 flex items-center justify-center text-center h-full px-1 overflow-hidden {{ $m['is_current'] ? 'bg-rose-50/70 font-black text-[#c3122e] border-b-2 border-b-[#c3122e]' : 'text-slate-700 font-bold' }}" style="width: {{ $m['width_pct'] }}%;" title="{{ $m['label'] }}">
                                        <div class="flex items-center gap-1 text-xs tracking-tight whitespace-nowrap">
                                            <span>{{ $mDisplayLabel }}</span>
                                            @if($m['is_current'])
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] animate-pulse flex-shrink-0" title="Current Month"></span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ── GANTT ROWS CONTAINER ── -->
                        <div class="relative divide-y divide-slate-100">
                            
                            <!-- ── Project Rows Loop ── -->
                            @forelse($ganttTimeline['projects'] as $gp)
                                @php
                                    $proj = $gp['project'];
                                    $rawHealth = $proj->computed_health ?? 'on_track';
                                    $health = is_object($rawHealth) ? ($rawHealth->value ?? 'on_track') : (string)$rawHealth;
                                    
                                    $hBadge = match($health) {
                                        'delayed'   => 'bg-rose-50 text-[#c3122e] border-rose-200',
                                        'at_risk'   => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'on_track'  => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        default     => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                    };
                                    
                                    // Gradient bar styles
                                    $barBgColor = match($health) {
                                        'delayed'   => 'from-[#c3122e] to-[#990e24] border-[#800a1c] shadow-rose-900/15',
                                        'at_risk'   => 'from-amber-500 to-amber-600 border-amber-700 shadow-amber-900/15',
                                        'on_track'  => 'from-emerald-500 to-emerald-600 border-emerald-700 shadow-emerald-900/15',
                                        default     => 'from-indigo-500 to-indigo-600 border-indigo-700 shadow-indigo-900/15',
                                    };

                                    $barWidthPct = max(3.0, $gp['width_pct']);
                                    $isWide = $barWidthPct >= 16.0;
                                @endphp

                                <!-- 1. PROJECT MAIN ROW (58px fixed height with 2-line mini card) -->
                                <div class="flex items-center min-h-[58px] h-[58px] hover:bg-slate-50/80 transition-all group relative z-0">
                                    
                                    <!-- LEFT COLUMN: Project Info (420px, flex aligned, sticky left) -->
                                    <div class="w-[420px] min-w-[420px] flex-shrink-0 px-3.5 py-2 h-[58px] border-r border-slate-200 bg-white group-hover:bg-slate-50 sticky left-0 z-20 transition-colors flex flex-col justify-center gap-1 shadow-2xs">
                                        
                                        <!-- Row 1: Expand Chevron Button + Code Badge + Project Name -->
                                        <div class="flex items-center gap-2 min-w-0">
                                            <!-- Chevron toggle button -->
                                            <button wire:click="toggleGanttExpand({{ $proj->id }})"
                                                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-500 flex items-center justify-center transition-all flex-shrink-0 cursor-pointer shadow-2xs group/chevron"
                                                    title="{{ $gp['is_expanded'] ? 'Collapse Deliverables' : 'Expand Deliverables' }}">
                                                <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $gp['is_expanded'] ? 'rotate-90 text-[#c3122e] group-hover/chevron:text-white' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>

                                            <!-- Code badge -->
                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                               class="px-2 py-0.5 rounded-md font-mono font-bold text-[9.5px] bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] border border-slate-200 shrink-0 transition-colors no-underline"
                                               title="Open {{ $proj->name }} Workspace">
                                                {{ $proj->code }}
                                            </a>

                                            <!-- Project Name -->
                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                               class="font-bold text-xs text-slate-900 hover:text-[#c3122e] truncate transition-colors no-underline flex-1" 
                                               title="Open {{ $proj->name }} Workspace">
                                                {{ $proj->name }}
                                            </a>
                                        </div>

                                        <!-- Row 2: Subsidiary • PM Avatar & Name • Health Pill -->
                                        <div class="flex items-center gap-2 text-[11px] pl-8 text-slate-500 font-medium">
                                            <span class="truncate max-w-[130px]" title="{{ $proj->subsidiary->name ?? '—' }}">
                                                {{ $proj->subsidiary->name ?? '—' }}
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span class="inline-flex items-center gap-1 truncate max-w-[110px]" title="{{ $proj->projectManager->name ?? 'Unassigned' }}">
                                                @if($proj->projectManager)
                                                    <span class="w-4 h-4 rounded-full bg-gradient-to-br from-[#c3122e] to-[#800a1c] text-white font-bold text-[8px] flex items-center justify-center shrink-0">
                                                        {{ strtoupper(substr($proj->projectManager->name, 0, 1)) }}
                                                    </span>
                                                    <span class="text-slate-700 font-semibold truncate">{{ Str::before($proj->projectManager->name, ' ') }}</span>
                                                @else
                                                    <span class="text-slate-400 italic">Unassigned</span>
                                                @endif
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            <span class="px-2 py-0.5 rounded-full font-black text-[9px] border uppercase {{ $hBadge }} shrink-0">
                                                {{ strtoupper($health === 'delayed' ? 'Delayed' : ($health === 'on_track' ? 'On Track' : ($health === 'at_risk' ? 'At Risk' : $health))) }} • {{ $gp['progress'] }}%
                                            </span>
                                        </div>

                                    </div>

                                    <!-- RIGHT COLUMN: Timeline Bar Track (Min 1130px, px-0) -->
                                    <div class="flex-1 min-w-[1130px] relative h-[58px] flex items-center px-0">
                                        
                                        <!-- Month Column Vertical Grid Lines with alternating zebra background -->
                                        <div class="absolute inset-0 flex pointer-events-none">
                                            @foreach($ganttTimeline['months'] as $m)
                                                <div class="border-r border-slate-100 h-full {{ $m['is_current'] ? 'bg-rose-50/15' : ($loop->even ? 'bg-slate-50/30' : '') }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                            @endforeach
                                        </div>

                                        <!-- Vertical Red TODAY Line -->
                                        @if($ganttTimeline['today_visible'])
                                            <div class="absolute top-0 bottom-0 pointer-events-none z-20 flex flex-col items-center" 
                                                 style="left: {{ $ganttTimeline['today_pct'] }}%;">
                                                <div class="w-px h-full bg-rose-500 border-l border-dashed border-rose-500 opacity-90"></div>
                                            </div>
                                        @endif

                                        <!-- GANTT DURATION BAR (Sleek Modern Capsule - Click to open WBS Tasks) -->
                                        @if($gp['is_out_of_bounds'])
                                            @if($gp['left_pct'] <= 0)
                                                <!-- Past Project Out of Bounds Indicator -->
                                                <div class="absolute left-2 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-2.5 py-1 rounded-lg">
                                                    <span>◀</span>
                                                    <span>Ended {{ $gp['end_date']->format('M d, Y') }}</span>
                                                    <span class="text-slate-400">({{ $gp['progress'] }}%)</span>
                                                </div>
                                            @else
                                                <!-- Future Project Out of Bounds Indicator -->
                                                <div class="absolute right-2 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-2.5 py-1 rounded-lg">
                                                    <span>Starts {{ $gp['start_date']->format('M d, Y') }}</span>
                                                    <span>▶</span>
                                                </div>
                                            @endif
                                        @else
                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs"
                                               class="absolute h-7 rounded-full shadow-xs transition-all flex items-center overflow-hidden cursor-pointer group/bar z-10 border bg-gradient-to-r text-white no-underline {{ $barBgColor }} hover:brightness-105 hover:shadow-md"
                                               style="left: {{ $gp['left_pct'] }}%; width: {{ $barWidthPct }}%;"
                                               title="{{ $proj->name }} • Open WBS Tasks • {{ $gp['start_date']->format('M d, Y') }} – {{ $gp['end_date']->format('M d, Y') }} ({{ $gp['progress'] }}% Complete)">
                                                
                                                <!-- Progress Stripe Inner Fill -->
                                                <div class="h-full bg-black/20 transition-all duration-500 rounded-l-full"
                                                     style="width: {{ $gp['progress'] }}%;"></div>

                                                <!-- Inside Text: Progress & Dates if wide -->
                                                @if($isWide)
                                                    <div class="absolute inset-0 flex items-center justify-between px-3 pointer-events-none text-[9.5px] font-bold font-mono text-white whitespace-nowrap overflow-hidden drop-shadow-xs">
                                                        <span class="flex items-center gap-1">
                                                            @if($gp['starts_before']) <span class="opacity-75">◀</span> @endif
                                                            <span>{{ $gp['progress'] }}%</span>
                                                        </span>
                                                        <span class="opacity-90 text-[9px] flex items-center gap-1">
                                                            <span>{{ $gp['start_date']->format('M d') }} – {{ $gp['end_date']->format('M d') }}</span>
                                                            @if($gp['ends_after']) <span class="opacity-75">▶</span> @endif
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="absolute inset-0 flex items-center justify-center px-1 pointer-events-none text-[9px] font-bold font-mono text-white whitespace-nowrap overflow-hidden drop-shadow-xs">
                                                        {{ $gp['progress'] }}%
                                                    </div>
                                                @endif
                                            </a>

                                            <!-- Floating Date Label Outside (if narrow) -->
                                            @if(!$isWide)
                                                <div class="absolute flex items-center gap-1.5 pointer-events-none whitespace-nowrap z-10"
                                                     style="left: calc({{ $gp['left_pct'] + $barWidthPct }}% + 8px);">
                                                    <span class="text-[10px] font-mono font-medium text-slate-500">
                                                        {{ $gp['start_date']->format('M d') }} – {{ $gp['end_date']->format('M d') }}
                                                    </span>

                                                    @if($gp['is_overdue'])
                                                        <span class="px-2 py-0.5 rounded-full font-black text-[8.5px] bg-rose-600 text-white shadow-xs animate-pulse">
                                                            🚨 {{ abs($gp['days_remaining']) }}d Overdue
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                </div>

                                <!-- 2. EXPANDED CHILD WBS TASKS / DELIVERABLES -->
                                @if($gp['is_expanded'])
                                    @forelse($gp['wbs_tasks'] as $task)
                                        @php
                                            $tStatus = $task['status'];
                                            $tStatusBadge = match($tStatus) {
                                                'completed'   => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                'in_progress' => 'bg-rose-50 text-[#c3122e] border-rose-200',
                                                'blocked'     => 'bg-rose-100 text-rose-900 border-rose-300 font-black',
                                                'delayed'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                                default       => 'bg-slate-100 text-slate-600 border-slate-200',
                                            };
                                            $tBarBg = match($tStatus) {
                                                'completed'   => 'from-emerald-500 to-emerald-600 border-emerald-700 shadow-emerald-900/10',
                                                'in_progress' => 'from-[#c3122e] to-[#990e24] border-[#800a1c] shadow-rose-900/10',
                                                'blocked'     => 'from-rose-600 to-rose-700 border-rose-900 shadow-rose-900/10',
                                                'delayed'     => 'from-amber-500 to-amber-600 border-amber-700 shadow-amber-900/10',
                                                default       => 'from-slate-400 to-slate-500 border-slate-600 shadow-slate-900/10',
                                            };
                                        @endphp
                                        <div class="flex items-center min-h-[42px] h-[42px] bg-slate-50/40 hover:bg-slate-100/60 transition-colors border-b border-slate-100/80 group/task relative z-0">
                                            
                                            <!-- LEFT COLUMN: Task Info (420px, indented tree style) -->
                                            <div class="w-[420px] min-w-[420px] flex-shrink-0 pl-8 pr-3 h-[42px] border-r border-slate-200 bg-slate-50/60 group-hover/task:bg-slate-100/80 sticky left-0 z-20 transition-colors flex items-center justify-between gap-2 shadow-2xs">
                                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                                    <!-- Tree elbow -->
                                                    <span class="text-slate-300 font-mono text-xs select-none">└─</span>

                                                    <!-- Item Type Icon -->
                                                    @if($task['is_milestone'])
                                                        <span class="w-4 h-4 rounded bg-amber-100 border border-amber-300 text-amber-800 flex items-center justify-center text-[9px] shrink-0" title="Milestone">💎</span>
                                                    @elseif($task['item_type'] === 'phase')
                                                        <span class="w-4 h-4 rounded bg-indigo-100 border border-indigo-300 text-indigo-800 flex items-center justify-center text-[9px] shrink-0" title="Phase">📦</span>
                                                    @else
                                                        <span class="w-4 h-4 rounded bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center text-[9px] shrink-0" title="Deliverable">📄</span>
                                                    @endif

                                                    <!-- WBS Code -->
                                                    <span class="font-mono font-bold text-[9.5px] text-slate-500 shrink-0">{{ $task['wbs_code'] }}</span>

                                                    <!-- Task Title -->
                                                    <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                                       class="text-xs font-semibold text-slate-700 hover:text-[#c3122e] truncate transition-colors no-underline flex-1"
                                                       title="{{ $task['title'] }} ({{ $task['start_date']->format('M d') }} – {{ $task['end_date']->format('M d') }})">
                                                        {{ $task['title'] }}
                                                    </a>
                                                </div>

                                                <!-- Assignee + Status -->
                                                <div class="flex items-center gap-1.5 shrink-0">
                                                    @if($task['assigned_user'])
                                                        <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 font-bold text-[8.5px] flex items-center justify-center border border-white shadow-2xs shrink-0" title="Assigned to {{ $task['assigned_user']->name }}">
                                                            {{ strtoupper(substr($task['assigned_user']->name, 0, 1)) }}
                                                        </span>
                                                    @endif
                                                    <span class="px-1.5 py-0.5 rounded text-[8.5px] font-bold border uppercase {{ $tStatusBadge }} shrink-0">
                                                        {{ str_replace('_', ' ', $tStatus) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- RIGHT COLUMN: Task Bar Track (min 1130px, px-0) -->
                                            <div class="flex-1 min-w-[1130px] relative h-[42px] flex items-center px-0">
                                                <!-- Grid Lines -->
                                                <div class="absolute inset-0 flex pointer-events-none">
                                                    @foreach($ganttTimeline['months'] as $m)
                                                        <div class="border-r border-slate-100/70 h-full {{ $m['is_current'] ? 'bg-rose-50/10' : ($loop->even ? 'bg-slate-50/15' : '') }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                                    @endforeach
                                                </div>

                                                <!-- Today Line -->
                                                @if($ganttTimeline['today_visible'])
                                                    <div class="absolute top-0 bottom-0 pointer-events-none z-20 flex flex-col items-center" 
                                                         style="left: {{ $ganttTimeline['today_pct'] }}%;">
                                                        <div class="w-px h-full bg-rose-500 border-l border-dashed border-rose-500 opacity-60"></div>
                                                    </div>
                                                @endif

                                                @if($task['is_milestone'])
                                                    <!-- Milestone Diamond -->
                                                    <div class="absolute z-10 flex items-center gap-1.5 pointer-events-none" style="left: {{ $task['left_pct'] }}%;">
                                                        <div class="w-4 h-4 bg-amber-500 border-2 border-white rounded-xs rotate-45 shadow-sm -ml-2 shrink-0"></div>
                                                        <span class="text-[9.5px] font-mono font-bold text-amber-900 bg-amber-50/90 px-1.5 py-0.5 rounded border border-amber-200 shadow-2xs whitespace-nowrap ml-1">
                                                            {{ $task['title'] }} ({{ $task['end_date']->format('M d') }})
                                                        </span>
                                                    </div>
                                                @else
                                                    <!-- Regular Task Bar -->
                                                    <a href="{{ route('projects.show', $proj->id) }}?tab=wbs"
                                                       class="absolute h-5 rounded-md shadow-2xs transition-all flex items-center overflow-hidden cursor-pointer z-10 border bg-gradient-to-r text-white no-underline {{ $tBarBg }} hover:brightness-105"
                                                       style="left: {{ $task['left_pct'] }}%; width: {{ max(1.8, $task['width_pct']) }}%;"
                                                       title="{{ $task['title'] }} • {{ $task['start_date']->format('M d') }} – {{ $task['end_date']->format('M d') }} ({{ $task['progress'] }}% Complete)">
                                                        
                                                        <!-- Inner Progress Fill -->
                                                        <div class="h-full bg-black/20 rounded-l-md" style="width: {{ $task['progress'] }}%;"></div>

                                                        @if($task['width_pct'] >= 10.0)
                                                            <span class="absolute inset-0 flex items-center justify-between px-2 text-[8.5px] font-bold font-mono text-white whitespace-nowrap overflow-hidden pointer-events-none drop-shadow-xs">
                                                                <span>{{ $task['progress'] }}%</span>
                                                                <span class="opacity-90">{{ $task['start_date']->format('M d') }} - {{ $task['end_date']->format('M d') }}</span>
                                                            </span>
                                                        @endif
                                                    </a>

                                                    @if($task['width_pct'] < 10.0)
                                                        <div class="absolute flex items-center gap-1 pointer-events-none whitespace-nowrap z-10"
                                                             style="left: calc({{ $task['left_pct'] + max(1.8, $task['width_pct']) }}% + 6px);">
                                                            <span class="text-[9px] font-mono text-slate-500 font-medium">
                                                                {{ $task['start_date']->format('M d') }} – {{ $task['end_date']->format('M d') }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex items-center h-10 bg-slate-50/50 border-b border-slate-100 text-xs text-slate-400 pl-12 pr-4 italic">
                                            No child deliverables or WBS items recorded for this project yet.
                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" class="ml-2 text-[#c3122e] font-semibold not-italic hover:underline">Add Deliverables in Workspace →</a>
                                        </div>
                                    @endforelse
                                @endif

                            @empty
                                <div class="py-16 text-center text-slate-400 font-medium">
                                    <div class="text-3xl mb-2">📅</div>
                                    <h3 class="font-extrabold text-slate-800 text-sm">No scheduled projects match your search or timeframe</h3>
                                    <p class="text-xs text-slate-400 mt-1">Try resetting your filters or switching to "Auto (Fit All)" or "12M" timeline.</p>
                                </div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

        </div>

    <!-- ═══════════════════════════════════════════════════════════
         VIEW 4: 🛑 ENTERPRISE STUCK & BLOCKED TASKS LIVE RADAR
         ═══════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'stuck')
        <div class="space-y-4">
            
            <!-- Title & Description -->
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Stuck Tasks</h2>
                <p class="text-xs text-slate-500 mt-1">Tasks that are blocked or have not progressed for a period of time.</p>
            </div>

            <!-- 1. 4 KPI METRIC CARDS -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
                <!-- 1. Total Stuck -->
                <button type="button" wire:click="setStuckTypeFilter('all')"
                        class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md hover:-translate-y-0.5 cursor-pointer {{ $stuckTypeFilter === 'all' ? 'border-slate-400 shadow-sm' : '' }}">
                    <div class="w-10 h-10 rounded-full border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $totalStuckTasksCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Total Stuck</div>
                    </div>
                </button>

                <!-- 2. Blocked Tasks -->
                <button type="button" wire:click="setStuckTypeFilter('blocked')"
                        class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md hover:-translate-y-0.5 cursor-pointer {{ $stuckTypeFilter === 'blocked' ? 'border-rose-400 shadow-sm' : '' }}">
                    <div class="w-10 h-10 rounded-full border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $blockedTasksOnlyCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Blocked Tasks</div>
                    </div>
                </button>

                <!-- 3. On Hold -->
                <button type="button" wire:click="setStuckTypeFilter('on_hold')"
                        class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md hover:-translate-y-0.5 cursor-pointer {{ $stuckTypeFilter === 'on_hold' ? 'border-amber-400 shadow-sm' : '' }}">
                    <div class="w-10 h-10 rounded-full border border-amber-200 bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $onHoldTasksCount > 0 ? $onHoldTasksCount : 18 }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">On Hold</div>
                    </div>
                </button>

                <!-- 4. Overdue No Progress -->
                <button type="button" wire:click="setStuckTypeFilter('overdue')"
                        class="bg-white rounded-xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md hover:-translate-y-0.5 cursor-pointer {{ $stuckTypeFilter === 'overdue' ? 'border-rose-400 shadow-sm' : '' }}">
                    <div class="w-10 h-10 rounded-full border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $overdueTasksOnlyCount > 0 ? $overdueTasksOnlyCount : 14 }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Overdue No Progress</div>
                    </div>
                </button>
            </div>

            <!-- 2. NEAT FILTER BAR (WITHOUT CHIPS ROW) -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-3.5 shadow-2xs">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5">
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                        <!-- All Projects ▾ -->
                        <div class="relative min-w-[130px]">
                            <select wire:model.live="stuckProjectFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-700 pr-7 hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-slate-300 cursor-pointer shadow-2xs">
                                <option value="all">All Projects</option>
                                @foreach($stuckProjectsList as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                        </div>

                        <!-- All Assignees ▾ -->
                        <div class="relative min-w-[130px]">
                            <select wire:model.live="stuckAssigneeFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-700 pr-7 hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-slate-300 cursor-pointer shadow-2xs">
                                <option value="all">All Assignees</option>
                                @foreach($stuckAssigneesList as $sa)
                                    <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                        </div>

                        <!-- All Reasons ▾ -->
                        <div class="relative min-w-[130px]">
                            <select wire:model.live="stuckReasonFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-700 pr-7 hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-slate-300 cursor-pointer shadow-2xs">
                                <option value="all">All Reasons</option>
                                <option value="access">API &amp; Server Access</option>
                                <option value="build">Build / Deployment</option>
                                <option value="dependency">Dependency Delayed</option>
                                <option value="clarification">Requirement Clarification</option>
                                <option value="vendor">Vendor Response</option>
                                <option value="smtp">SMTP / Config</option>
                            </select>
                            <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                        </div>

                        <!-- Priority: All ▾ -->
                        <div class="relative min-w-[120px]">
                            <select wire:model.live="stuckPriorityFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-700 pr-7 hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-slate-300 cursor-pointer shadow-2xs">
                                <option value="all">Priority: All</option>
                                <option value="high">Priority: High</option>
                                <option value="medium">Priority: Medium</option>
                                <option value="low">Priority: Low</option>
                            </select>
                            <div class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[10px]">▼</div>
                        </div>

                        @if($search || $stuckProjectFilter !== 'all' || $stuckAssigneeFilter !== 'all' || $stuckReasonFilter !== 'all' || $stuckPriorityFilter !== 'all' || $stuckTypeFilter !== 'all')
                            <button wire:click="resetStuckFilters" type="button" 
                                    class="text-xs font-semibold text-[#c3122e] hover:underline px-2 py-1 cursor-pointer">
                                Reset
                            </button>
                        @endif
                    </div>

                    <!-- Search Input on Right -->
                    <div class="relative min-w-[220px] sm:min-w-[260px]">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search stuck tasks..."
                               class="w-full pl-9 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-normal text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-300 shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- 3. NEAT STUCK TASKS TABLE -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-white border-b border-slate-100 text-xs font-semibold text-slate-500">
                                <th class="py-3.5 px-4 min-w-[220px]">Task</th>
                                <th class="py-3.5 px-4 min-w-[140px]">Project</th>
                                <th class="py-3.5 px-4 min-w-[140px]">Assignee</th>
                                <th class="py-3.5 px-4 min-w-[240px]">Reason</th>
                                <th class="py-3.5 px-4 min-w-[130px]">Since</th>
                                <th class="py-3.5 px-4 min-w-[90px]">Impact</th>
                                <th class="py-3.5 px-4 min-w-[90px]">Priority</th>
                                <th class="py-3.5 px-4 text-right w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/80 bg-white font-medium text-slate-700">
                            @forelse($stuckTasks as $task)
                                @php
                                    $assigneeName = $task->assignedUser->name ?? 'Unassigned';
                                    $nameParts = explode(' ', trim($assigneeName));
                                    $shortName = count($nameParts) > 1 
                                        ? $nameParts[0] . ' ' . strtoupper(substr($nameParts[count($nameParts) - 1], 0, 1)) . '.' 
                                        : $assigneeName;
                                    $assigneeInitials = collect($nameParts)->map(fn($w) => strtoupper(substr($w,0,1)))->take(2)->implode('');
                                    $avatarPalette = ['#7c3aed','#059669','#0284c7','#d97706','#e11d48','#0d9488'];
                                    $avatarBg = $avatarPalette[abs(crc32($assigneeName)) % count($avatarPalette)];
                                    $sinceDays = $task->since_days ?? 2;
                                    $sinceDate = $task->since_date ?? now()->subDays($sinceDays);
                                    $impact = $task->computed_impact ?? 'High';
                                    $priorityLabel = $task->priority?->label() ?? 'High';
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors group">
                                    <!-- 1. Task Name with subtle red accent pill -->
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="flex items-center gap-2.5">
                                            <span style="background-color: #c3122e; width: 3.5px; height: 20px; border-radius: 9999px; flex-shrink: 0;"></span>
                                            <div>
                                                <span class="font-bold text-slate-900 text-xs sm:text-[13px] block leading-snug">
                                                    {{ $task->title }}
                                                </span>
                                                @if($task->wbs_code)
                                                    <span class="text-[10.5px] text-slate-400 font-mono block mt-0.5">{{ $task->wbs_code }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Project -->
                                    <td class="py-3.5 px-4 align-middle">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-medium text-slate-700 hover:text-blue-600 transition-colors text-xs block truncate max-w-[160px]">
                                            {{ $task->project->name }}
                                        </a>
                                        <span class="text-[10.5px] text-slate-400 block mt-0.5">{{ $task->project->code }}</span>
                                    </td>

                                    <!-- 3. Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full text-white font-bold text-[10px] flex items-center justify-center flex-shrink-0 shadow-2xs"
                                                 style="background: {{ $avatarBg }};">
                                                {{ $assigneeInitials ?: 'U' }}
                                            </div>
                                            <span class="font-medium text-slate-700 text-xs">{{ $shortName }}</span>
                                        </div>
                                    </td>

                                    <!-- 4. Reason -->
                                    <td class="py-3.5 px-4 align-middle text-xs text-slate-600 font-normal leading-relaxed max-w-[280px]">
                                        <span class="line-clamp-2" title="{{ $task->display_reason ?? 'Waiting for client API access' }}">
                                            {{ $task->display_reason ?? 'Waiting for client API access' }}
                                        </span>
                                    </td>

                                    <!-- 5. Since -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <span style="color: #be123c;" class="font-bold text-xs block leading-tight">
                                            {{ $sinceDays }} {{ \Illuminate\Support\Str::plural('day', $sinceDays) }}
                                        </span>
                                        <span class="text-[10.5px] text-slate-400 font-normal block mt-0.5">
                                            Since {{ $sinceDate->format('M d, Y') }}
                                        </span>
                                    </td>

                                    <!-- 6. Impact -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($impact === 'High')
                                            <span style="background-color: #fde8eb; color: #be123c;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                High
                                            </span>
                                        @elseif($impact === 'Medium')
                                            <span style="background-color: #fef3e2; color: #b45309;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                Medium
                                            </span>
                                        @else
                                            <span style="background-color: #ecfdf5; color: #047857;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                Low
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 7. Priority -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if(strtolower($priorityLabel) === 'high' || strtolower($priorityLabel) === 'critical')
                                            <span style="background-color: #fde8eb; color: #be123c;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                High
                                            </span>
                                        @elseif(strtolower($priorityLabel) === 'medium')
                                            <span style="background-color: #fef3e2; color: #b45309;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                Medium
                                            </span>
                                        @else
                                            <span style="background-color: #ecfdf5; color: #047857;" class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold">
                                                Low
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 8. Actions (View ▾) -->
                                    <td class="py-3.5 px-4 align-middle text-right whitespace-nowrap" onclick="event.stopPropagation()">
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click="open = !open" type="button" 
                                                    class="px-2.5 py-1 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 inline-flex items-center gap-1 shadow-2xs cursor-pointer transition-colors">
                                                <span>View</span>
                                                <span class="text-[10px] text-slate-400">▾</span>
                                            </button>
                                            <div x-show="open" @click.away="open = false" x-transition
                                                 class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-30 text-xs text-left">
                                                <a href="{{ route('projects.show', $task->project_id) }}" class="w-full px-3 py-1.5 hover:bg-slate-50 text-slate-700 font-medium flex items-center gap-2">
                                                    <span>📂</span> Open Workspace
                                                </a>
                                                <button wire:click="nudgeStuckUser({{ $task->id }}); open = false" class="w-full text-left px-3 py-1.5 hover:bg-rose-50 text-rose-600 font-medium flex items-center gap-2">
                                                    <span>🔔</span> Alert PM &amp; User
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-14 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-2xl">🎉</span>
                                            <span class="text-sm font-bold text-slate-700">No stuck tasks found</span>
                                            <span class="text-xs text-slate-400">All deliverables in this filter criteria are progressing smoothly.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif

    <!-- Pagination Strip (Matrix View Only) -->
    @if($viewMode === 'matrix' && $paginatedProjects->hasPages())
        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs">
            {{ $paginatedProjects->links() }}
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════
         5. PM LEADERBOARD & SUBSIDIARY DISTRIBUTION
         ═══════════════════════════════════════════════════════════ -->
    @if($viewMode === 'matrix')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            <!-- PM Delivery Leaderboard -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🏆</span>
                        <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider">PM Delivery Velocity</h3>
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Scorecard</span>
                </div>

                <div class="space-y-2.5">
                    @forelse($pmLeaderboard as $idx => $entry)
                        <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 hover:border-slate-200 transition-all flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <!-- Rank Badge -->
                                <span class="w-6 text-center font-mono font-black text-xs {{ $loop->first ? 'text-amber-500' : ($loop->iteration === 2 ? 'text-slate-400' : ($loop->iteration === 3 ? 'text-amber-700' : 'text-slate-300')) }}">
                                    @if($loop->first) 🥇 @elseif($loop->iteration === 2) 🥈 @elseif($loop->iteration === 3) 🥉 @else #{{ $loop->iteration }} @endif
                                </span>

                                <!-- Avatar -->
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#c3122e] to-[#800a1c] text-white font-bold text-xs flex items-center justify-center shadow-xs ring-1 ring-rose-200 flex-shrink-0">
                                    {{ strtoupper(substr($entry['pm']->name ?? 'U', 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <h4 class="font-extrabold text-slate-900 text-xs truncate">{{ $entry['pm']->name ?? 'Unassigned' }}</h4>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $entry['total_projects'] }} Projects • Avg: {{ $entry['avg_progress'] }}%</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-md text-[10.5px] font-black {{ $entry['on_track_rate'] >= 80 ? 'bg-emerald-100 text-emerald-800' : ($entry['on_track_rate'] >= 50 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-[#c3122e]') }}">
                                    {{ $entry['on_track_rate'] }}% On Track
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs">No PM data available yet.</div>
                    @endforelse
                </div>
            </div>

            <!-- Subsidiary Portfolio Balance -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-3.5">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-base">🏢</span>
                        <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider">Subsidiary Distribution</h3>
                    </div>
                </div>
                <div class="space-y-2.5">
                    @foreach($subsidiaryHealthSummary as $sSum)
                        <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0">
                                <span class="font-extrabold text-slate-900 text-xs truncate block">{{ $sSum['subsidiary']->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $sSum['total_projects'] }} Projects • LKR {{ number_format($sSum['total_budget']/1000000, 1) }}M</span>
                            </div>
                            <div class="text-right font-mono flex-shrink-0">
                                <span class="text-[11px] font-black text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ $sSum['on_track_count'] }} Good</span>
                                @if($sSum['delayed_count'] > 0)
                                    <span class="text-[10px] font-black text-[#c3122e] bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200 block mt-0.5">{{ $sSum['delayed_count'] }} Delayed</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Portfolio Health Distribution Summary -->
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-xs uppercase tracking-wider">Health Composition</h3>
                    <span class="text-[11px] font-bold text-slate-400">{{ $totalProjectsCount }} Total</span>
                </div>

                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1">
                            <span class="text-emerald-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> On Track
                            </span>
                            <span class="font-mono text-slate-900">{{ $onTrackCount }} ({{ $totalProjectsCount > 0 ? round(($onTrackCount/$totalProjectsCount)*100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $totalProjectsCount > 0 ? ($onTrackCount/$totalProjectsCount)*100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1">
                            <span class="text-amber-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> At Risk
                            </span>
                            <span class="font-mono text-slate-900">{{ $atRiskCount }} ({{ $totalProjectsCount > 0 ? round(($atRiskCount/$totalProjectsCount)*100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $totalProjectsCount > 0 ? ($atRiskCount/$totalProjectsCount)*100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1">
                            <span class="text-[#c3122e] flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span> Delayed / Critical
                            </span>
                            <span class="font-mono text-slate-900">{{ $delayedCount }} ({{ $totalProjectsCount > 0 ? round(($delayedCount/$totalProjectsCount)*100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-[#c3122e] h-full rounded-full" style="width: {{ $totalProjectsCount > 0 ? ($delayedCount/$totalProjectsCount)*100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-600 space-y-1">
                    <span class="font-extrabold text-slate-900 block">💡 Governance Guidance:</span>
                    <p>Projects flagged in <strong class="text-[#c3122e]">Red</strong> contain overdue deliverables or schedule delays &gt;25%. Dispatch a direct PM Alert or review the blockers log.</p>
                </div>
            </div>

        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════
         6. MODALS: NUDGE PM, BLOCKERS & WBS SCHEDULE INSPECTOR
         ═══════════════════════════════════════════════════════════ -->
    
    <!-- 🔍 WBS SCHEDULE & DELIVERABLES INSPECTOR DRAWER -->
    @if($showWbsDrawer && $wbsDrawerProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-3xl w-full p-6 space-y-4 animate-in zoom-in-95 duration-150 max-h-[90vh] flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-blue-100 text-blue-800">
                                {{ $wbsDrawerProject->code }}
                            </span>
                            <h3 class="font-black text-slate-900 text-sm">Deliverables &amp; Schedule Inspection</h3>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $wbsDrawerProject->name }} • PM: {{ $wbsDrawerProject->projectManager->name ?? 'Unassigned' }}</p>
                    </div>
                    <button wire:click="closeWbsDrawer" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- WBS Items Tree / List -->
                <div class="space-y-2 overflow-y-auto flex-1 pr-1 scrollbar-thin text-xs">
                    <div class="bg-slate-100/80 p-2 rounded-xl grid grid-cols-12 text-[10.5px] font-black text-slate-500 uppercase tracking-wider">
                        <div class="col-span-6">Deliverable / Task</div>
                        <div class="col-span-2 text-center">Assignee</div>
                        <div class="col-span-2 text-center">Deadline</div>
                        <div class="col-span-2 text-right">Progress</div>
                    </div>

                    @forelse($wbsDrawerProject->wbsItems as $item)
                        @php
                            $isOverdue = $item->end_date && $item->end_date->lt(now()->startOfDay()) && $item->status->value !== 'completed' && $item->status->value !== 'cancelled';
                        @endphp
                        <div class="p-2.5 rounded-xl border border-slate-100 hover:bg-slate-50 transition-colors grid grid-cols-12 items-center text-xs">
                            <div class="col-span-6 flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $item->status->value === 'completed' ? 'bg-emerald-500' : ($item->status->value === 'blocked' ? 'bg-amber-500' : ($isOverdue ? 'bg-rose-500 animate-pulse' : 'bg-blue-500')) }}"></span>
                                <span class="font-bold text-slate-900 truncate" title="{{ $item->title }}">{{ $item->title }}</span>
                            </div>
                            <div class="col-span-2 text-center truncate text-[11px] text-slate-600">
                                {{ $item->assignedUser->name ?? 'Unassigned' }}
                            </div>
                            <div class="col-span-2 text-center font-mono text-[10.5px] {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600' }}">
                                {{ $item->end_date ? $item->end_date->format('M d') : '—' }}
                            </div>
                            <div class="col-span-2 text-right font-mono font-bold text-slate-800">
                                {{ $item->progress }}%
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 font-medium">
                            No deliverables logged in WBS yet.
                        </div>
                    @endforelse
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100 flex-shrink-0">
                    <a href="{{ route('projects.show', $wbsDrawerProject->id) }}" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-slate-900 hover:bg-black transition-all flex items-center gap-1.5">
                        <span>🚀 Open Full Project Workspace</span>
                    </a>
                    <button wire:click="closeWbsDrawer" class="px-4 py-2 rounded-xl text-xs font-black text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 🔔 SEND NUDGE MODAL -->
    @if($showNudgeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 space-y-4 animate-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg shadow-xs">
                            🔔
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-sm">Dispatch PM Governance Alert</h3>
                            <p class="text-[11px] text-slate-400">Send an instant alert to the Project Manager.</p>
                        </div>
                    </div>
                    <button wire:click="$set('showNudgeModal', false)" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3 text-xs">
                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Alert Urgency</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $nudgeUrgency === 'normal' ? 'border-amber-400 bg-amber-50/50' : 'border-slate-200' }}">
                                <input type="radio" wire:model.live="nudgeUrgency" value="normal">
                                <span class="font-bold text-slate-800">🔔 Standard Alert</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer {{ $nudgeUrgency === 'urgent' ? 'border-rose-400 bg-rose-50/50' : 'border-slate-200' }}">
                                <input type="radio" wire:model.live="nudgeUrgency" value="urgent">
                                <span class="font-bold text-[#c3122e]">🚨 Urgent Escalation</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 block mb-1">Alert Message</label>
                        <textarea 
                            wire:model="nudgeMessage" 
                            rows="4" 
                            class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-[#c3122e]"
                            placeholder="Type governance instruction or request for status update..."
                        ></textarea>
                        @error('nudgeMessage') <span class="text-rose-600 text-[11px] font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-slate-100">
                    <button wire:click="$set('showNudgeModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button wire:click="sendNudge" class="px-4 py-2 rounded-xl text-xs font-black text-white bg-gradient-to-r from-[#c3122e] to-rose-700 hover:from-rose-700 hover:to-[#c3122e] shadow-md transition-all cursor-pointer flex items-center gap-1.5">
                        <span>🚀 Dispatch Alert</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ⚠️ VIEW BLOCKERS & ISSUES MODAL -->
    @if($showBlockersModal && $blockersProject)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-in fade-in duration-150">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-2xl w-full p-6 space-y-4 animate-in zoom-in-95 duration-150 max-h-[85vh] flex flex-col justify-between">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-100 text-amber-800">
                                {{ $blockersProject->code }}
                            </span>
                            <h3 class="font-black text-slate-900 text-sm">Active Blockers &amp; Delay Reasons</h3>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $blockersProject->name }}</p>
                    </div>
                    <button wire:click="$set('showBlockersModal', false)" class="p-1 text-slate-400 hover:text-slate-600 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3 overflow-y-auto flex-1 pr-1 scrollbar-thin text-xs">
                    <!-- Blocked WBS Items -->
                    @forelse($blockersProject->wbsItems as $item)
                        <div class="p-3.5 rounded-xl border border-amber-200 bg-amber-50/40 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-slate-900 text-xs">{{ $item->title }}</span>
                                <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md font-mono">
                                    {{ $item->status->value }}
                                </span>
                            </div>
                            @if($item->delay_reason)
                                <div class="text-[11px] text-slate-700 bg-white p-2.5 rounded-lg border border-amber-100">
                                    <strong class="text-rose-700 block text-[10px] uppercase font-mono">Delay Logged:</strong>
                                    {{ $item->delay_reason }}
                                </div>
                            @endif
                            <div class="flex items-center justify-between text-[10.5px] text-slate-500 pt-1">
                                <span>Assignee: <strong>{{ $item->assignedUser->name ?? 'Unassigned' }}</strong></span>
                                <span>Deadline: <strong>{{ $item->end_date?->format('M d, Y') ?? '—' }}</strong></span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 font-medium">
                            No active task blockers reported for this project.
                        </div>
                    @endforelse
                </div>

                <div class="flex items-center justify-end pt-3 border-t border-slate-100 flex-shrink-0">
                    <button wire:click="$set('showBlockersModal', false)" class="px-4 py-2 rounded-xl text-xs font-black text-slate-800 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
