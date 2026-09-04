<div>
    <style>
        .no-native-arrow {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (PROJECTS DIRECTORY)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-7 lg:p-8 text-white mb-6" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Projects Directory Banner" 
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
            <!-- Left Side: Icon + Title + Meta Hierarchy -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0">
                <!-- 3D App Icon Container -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-2 ring-black/60 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/project-app-icon.jpg') }}" alt="Projects Directory" class="w-full h-full object-cover rounded-xl shadow-inner">
                </div>

                <div class="min-w-0 space-y-2 flex-1">
                    <!-- Suite Breadcrumb & Live Pill -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[10px] font-black uppercase tracking-widest text-amber-300 font-mono">
                            🏛️ ENTERPRISE PORTFOLIO
                        </span>
                        <span class="text-white/30 text-xs">•</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-slate-950/80 text-amber-300 border border-amber-400/50 shadow-xs backdrop-blur-md inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>{{ $totalCount }} {{ \Illuminate\Support\Str::plural('Project', $totalCount) }}</span>
                        </span>
                    </div>

                    <!-- Main Title -->
                    <h1 class="text-xl sm:text-2xl lg:text-[27px] font-black text-white tracking-tight leading-tight drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Projects Directory
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

            <!-- Right Side: HUD Gauge + New Project CTA (Obsidian Glass) -->
            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 w-full lg:w-auto pt-3 lg:pt-0 border-t lg:border-t-0 border-white/10">
                <!-- Floating Mini Metric Card -->
                <div class="px-4 py-2.5 rounded-2xl border border-white/20 ring-1 ring-black/50 shadow-2xl backdrop-blur-2xl flex items-center justify-between sm:justify-start gap-3.5 bg-slate-950/85 hover:bg-slate-950 transition-all flex-shrink-0">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-300 uppercase tracking-widest leading-none">ACTIVE PORTFOLIO</span>
                        <span class="text-xs sm:text-sm font-black text-white leading-tight font-mono mt-1">
                            <span class="text-white text-base">{{ $activeCount }}</span> <span class="text-slate-400 font-normal">/</span> <span class="text-slate-300">{{ $totalCount }}</span>
                            <span class="text-[10.5px] font-bold text-slate-300 ml-0.5">Active</span>
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-amber-400 flex-shrink-0 shadow-inner">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>

                <!-- Primary New Project CTA -->
                @if(auth()->user()?->canCreateProject())
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:brightness-110 transition-all duration-200 cursor-pointer no-underline active:scale-95 hover:scale-105 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                        <svg class="w-4 h-4 text-amber-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>New Project</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. INTERACTIVE 4-METRIC PORTFOLIO COCKPIT (CLEAN & SLEEK)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
        <!-- Metric 1: Total Projects -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'all')"
            class="text-left bg-white border rounded-xl sm:rounded-2xl p-2.5 sm:p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $statusFilter === 'all' ? 'border-[#c3122e] ring-2 ring-[#c3122e]/15 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}"
        >
            <div class="flex items-center gap-2 sm:gap-3.5 min-w-0">
                <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-lg sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $totalCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 block mt-1 truncate">Total Projects</span>
                </div>
            </div>
            <span class="hidden sm:inline-block px-2.5 py-1 rounded-full text-[10px] font-black text-rose-700 bg-rose-50 border border-rose-200/70 flex-shrink-0">
                Portfolio
            </span>
        </button>

        <!-- Metric 2: Active in Progress -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'in_progress')"
            class="text-left bg-white border rounded-xl sm:rounded-2xl p-2.5 sm:p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $statusFilter === 'in_progress' ? 'border-amber-500 ring-2 ring-amber-500/15 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}"
        >
            <div class="flex items-center gap-2 sm:gap-3.5 min-w-0">
                <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-lg sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $activeCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 block mt-1 truncate">In Progress</span>
                </div>
            </div>
            <span class="hidden sm:inline-block px-2.5 py-1 rounded-full text-[10px] font-black text-amber-700 bg-amber-50 border border-amber-200/70 flex-shrink-0">
                Execution
            </span>
        </button>

        <!-- Metric 3: Delivered / Completed -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'completed')"
            class="text-left bg-white border rounded-xl sm:rounded-2xl p-2.5 sm:p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $statusFilter === 'completed' ? 'border-emerald-500 ring-2 ring-emerald-500/15 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}"
        >
            <div class="flex items-center gap-2 sm:gap-3.5 min-w-0">
                <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-lg sm:text-2xl font-black text-slate-900 leading-none block font-mono tracking-tight">{{ $completedCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 block mt-1 truncate">Delivered</span>
                </div>
            </div>
            <span class="hidden sm:inline-block px-2.5 py-1 rounded-full text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200/70 flex-shrink-0">
                Delivered
            </span>
        </button>

        <!-- Metric 4: Overdue Attention -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'all')"
            class="text-left bg-white border rounded-xl sm:rounded-2xl p-2.5 sm:p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer group flex items-center justify-between {{ $overdueCount > 0 ? 'border-rose-300 shadow-2xs hover:border-rose-400' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}"
        >
            <div class="flex items-center gap-2 sm:gap-3.5 min-w-0">
                <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-lg sm:rounded-2xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center flex-shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="text-lg sm:text-2xl font-black {{ $overdueCount > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} leading-none block font-mono tracking-tight">{{ $overdueCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 block mt-1 truncate">Overdue Tasks</span>
                </div>
            </div>
            @if($overdueCount > 0)
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black text-rose-700 bg-rose-50 border border-rose-200/70 flex-shrink-0 animate-pulse">
                    Action Req
                </span>
            @else
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black text-slate-600 bg-slate-100 border border-slate-200 flex-shrink-0">
                    On Track
                </span>
            @endif
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. SMART SEARCH & FILTER TOOLBAR (CLEAN & MODERN)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-3.5 sm:p-4 shadow-xs mb-6 space-y-3" x-data="{ moreFilters: false }">
        <!-- Main Search & Primary Filters Row -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Search Input Bar -->
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.250ms="search" 
                    placeholder="Search projects by title, code, subsidiary, or leader..."
                    class="w-full h-10 pl-10 pr-10 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all"
                >
                @if($search)
                    <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            <!-- Primary Filters & Actions -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap flex-shrink-0">
                <!-- Status Filter -->
                <div class="relative min-w-[140px] sm:w-40">
                    <select wire:model.live="statusFilter" class="no-native-arrow w-full h-10 pl-3 pr-8 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all shadow-2xs">
                        <option value="all">🌐 All Statuses</option>
                        <option value="planning">⏳ Planning</option>
                        <option value="in_progress">⚡ In Progress</option>
                        <option value="on_hold">⏸️ On Hold</option>
                        <option value="completed">✅ Completed</option>
                        <option value="cancelled">🚫 Cancelled</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- Subsidiary Filter -->
                <div class="relative min-w-[160px] sm:w-48">
                    <select wire:model.live="subsidiaryFilter" class="no-native-arrow w-full h-10 pl-3 pr-8 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all shadow-2xs">
                        <option value="all">🏢 All Subsidiaries</option>
                        @foreach($subsidiaries as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <!-- More Filters Toggle Button -->
                <button 
                    type="button" 
                    @click="moreFilters = !moreFilters"
                    class="h-10 inline-flex items-center gap-1.5 px-3.5 rounded-xl text-xs font-extrabold border transition-all cursor-pointer shadow-2xs"
                    :class="moreFilters || {{ ($managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all') ? 'true' : 'false' }} ? 'bg-rose-50 text-[#c3122e] border-rose-200' : 'bg-slate-50/70 hover:bg-slate-100 text-slate-700 border-slate-200'"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filters</span>
                    @if($managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    @endif
                </button>

                <!-- Export Controls -->
                <div class="flex items-center gap-1">
                    <a href="{{ route('reports.export-csv') }}" class="h-10 w-10 flex items-center justify-center rounded-xl text-slate-600 hover:text-slate-900 bg-slate-50/70 hover:bg-slate-100 border border-slate-200 transition-all shadow-2xs" title="Export CSV">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </a>
                    <a href="{{ route('reports.export-pdf') }}" class="h-10 w-10 flex items-center justify-center rounded-xl text-[#c3122e] bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all shadow-2xs" title="Export PDF">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </a>
                </div>

                <!-- Reset Filter Button -->
                @if($search || $subsidiaryFilter !== 'all' || $managerFilter !== 'all' || $statusFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                    <button 
                        type="button" 
                        wire:click="resetFilters" 
                        class="h-10 inline-flex items-center gap-1.5 px-3 rounded-xl text-xs font-black text-[#c3122e] bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer flex-shrink-0 shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Collapsible Advanced Filters Row (Smooth Expansion) -->
        <div x-show="moreFilters" x-collapse class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Project Manager Filter -->
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Project Leader / Manager</span>
                <select wire:model.live="managerFilter" class="no-native-arrow w-full h-10 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
                    <option value="all">All Project Leaders</option>
                    @foreach($pms as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Strategic Priority</span>
                <select wire:model.live="priorityFilter" class="no-native-arrow w-full h-10 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
                    <option value="all">All Priorities</option>
                    @foreach(\App\Enums\Priority::cases() as $pr)
                        <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Health Filter -->
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Delivery Health</span>
                <select wire:model.live="healthFilter" class="no-native-arrow w-full h-10 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
                    <option value="all">All Health Levels</option>
                    @foreach(\App\Enums\ProjectHealth::cases() as $h)
                        <option value="{{ $h->value }}">{{ $h->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         4. HIGH-END EXECUTIVE PROJECTS DIRECTORY TABLE
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs mb-6">
        <!-- Table Header Bar / Title -->
        <div class="px-5 sm:px-6 py-4 border-b border-slate-200/80 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-100 flex items-center justify-center font-black text-xs shadow-2xs">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">
                            Corporate Projects Directory
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-black text-rose-700 bg-rose-50 border border-rose-200/70">
                            {{ $projects->total() }} {{ \Illuminate\Support\Str::plural('Project', $projects->total()) }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">
                        Comprehensive portfolio directory and delivery status overview
                    </p>
                </div>
            </div>

            <!-- Right side live status badge -->
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-[11px] text-emerald-800 font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Live Portfolio Stream</span>
                </span>
            </div>
        </div>

        @if($projects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60 text-[10.5px] font-black text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5 pl-6 pr-4">PROJECT &amp; CODE</th>
                            <th class="py-3.5 px-4 hidden md:table-cell">SUBSIDIARY</th>
                            <th class="py-3.5 px-4">PROJECT LEADER</th>
                            <th class="py-3.5 px-4">STATUS &amp; HEALTH</th>
                            <th class="py-3.5 px-4 hidden lg:table-cell">TIMELINE</th>
                            <th class="py-3.5 pl-4 pr-6 text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs bg-white">
                        @foreach($projects as $project)
                            @php
                                $daysLeft = $project->deadline ? (int) now()->today()->diffInDays($project->deadline, false) : null;
                                $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($project->status->value, ['completed','cancelled']);

                                $pmName = $project->projectManager->name ?? 'Unassigned';
                                $pmParts = explode(' ', trim($pmName));
                                $pmInitials = count($pmParts) >= 2 
                                    ? strtoupper(substr($pmParts[0], 0, 1) . substr($pmParts[count($pmParts) - 1], 0, 1))
                                    : strtoupper(substr($pmName, 0, 2));
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Project Name & Code -->
                                <td class="py-4 pl-6 pr-4 align-middle">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-black text-sm shadow-xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                            {{ strtoupper(substr($project->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-slate-900 text-[13px] group-hover:text-[#c3122e] leading-tight block truncate max-w-xs transition-colors">
                                                {{ $project->name }}
                                            </a>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <span class="inline-flex items-center text-[10px] font-mono font-bold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100 whitespace-nowrap">{{ $project->code }}</span>
                                                @if(!empty($project->subsidiary->code))
                                                    <span class="text-[10.5px] text-slate-400 font-semibold whitespace-nowrap">&bull; {{ $project->subsidiary->code }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subsidiary -->
                                <td class="py-4 px-4 hidden md:table-cell align-middle whitespace-nowrap">
                                    <span class="text-xs font-bold text-slate-800 block">
                                        {{ $project->subsidiary->name ?? '-' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $project->subsidiary->code ?? '' }}</span>
                                </td>

                                <!-- Project Manager -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-slate-900 text-white text-[9px] font-black flex items-center justify-center rounded-full flex-shrink-0 shadow-2xs">
                                            {{ $pmInitials }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-extrabold text-slate-900 truncate max-w-40">{{ $pmName }}</div>
                                            @if($project->isPmRejected())
                                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-block mt-0.5">
                                                    ❌ Declined
                                                </span>
                                            @elseif($project->isPendingPmAcceptance())
                                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-amber-50 text-amber-700 border border-amber-200 inline-block mt-0.5">
                                                    ⏳ Awaiting Sign-off
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-block mt-0.5">
                                                    👑 Project Leader
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status & Health -->
                                <td class="py-4 px-4 align-middle whitespace-nowrap">
                                    @php
                                        $statusClass = match($project->status->value) {
                                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'on_hold'     => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'cancelled'   => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default       => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                        $dotColor = match($project->status->value) {
                                            'completed'   => 'bg-emerald-500',
                                            'in_progress' => 'bg-amber-500 animate-pulse',
                                            default       => 'bg-rose-500',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusClass }} inline-flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        <span>{{ $project->status->label() }}</span>
                                    </span>
                                </td>

                                <!-- Deadline -->
                                <td class="py-4 px-4 hidden lg:table-cell align-middle whitespace-nowrap">
                                    <div class="text-xs font-bold text-slate-800">
                                        {{ $project->deadline ? $project->deadline->format('M d, Y') : 'TBD' }}
                                    </div>
                                    @if($project->deadline)
                                        <div class="text-[10.5px] font-bold mt-0.5 {{ $project->status->value === 'completed' ? 'text-emerald-600' : ($isOverdue ? 'text-rose-600' : 'text-slate-500') }}">
                                            @if($project->status->value === 'completed')
                                                ✓ Completed
                                            @elseif($isOverdue)
                                                ⚠️ {{ abs($daysLeft) }}d overdue
                                            @else
                                                ⏳ {{ $daysLeft }}d remaining
                                            @endif
                                        </div>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        @if($project->isPmAccepted())
                                            <a href="{{ route('projects.show', $project) }}" class="h-8 inline-flex items-center gap-1.5 px-3.5 rounded-xl text-xs font-black text-white shadow-xs hover:shadow transition-all" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <span>Workspace</span>
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                            </a>
                                        @elseif(auth()->id() === $project->project_manager_id)
                                            <a href="{{ route('projects.show', $project) }}" class="h-8 inline-flex items-center gap-1 px-3.5 rounded-xl text-xs font-bold text-white shadow-xs bg-amber-600 hover:bg-amber-700 transition-all">
                                                <span>Review &amp; Accept</span>
                                            </a>
                                        @else
                                            <a href="{{ route('projects.show', $project) }}" class="h-8 inline-flex items-center gap-1 px-3.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all">
                                                <span>Brief</span>
                                            </a>
                                        @endif

                                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->email === 'admin@nexuspm.local' || auth()->user()?->id === 1)
                                            <button wire:click="edit({{ $project->id }})" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all cursor-pointer shadow-2xs" title="Edit Project Settings">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click="confirmDeleteProject({{ $project->id }})" class="w-8 h-8 flex items-center justify-center rounded-lg text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200/80 hover:border-rose-600 transition-all duration-200 cursor-pointer shadow-2xs hover:shadow" title="Delete Project">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            <div class="p-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1">
                    {{ $projects->links() }}
                </div>
                <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium flex-shrink-0 self-end sm:self-center">
                    <span>Per page:</span>
                    <select wire:model.live="perPage" class="text-xs font-bold py-1 px-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-700">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        @else
            <!-- 🌟 INSPIRING & WELCOMING EMPTY STATE -->
            <div class="py-16 px-6 text-center max-w-lg mx-auto space-y-4">
                <div class="w-20 h-20 rounded-3xl mx-auto flex items-center justify-center shadow-xl" style="background: linear-gradient(135deg, #fdf4f4 0%, #faeaea 100%); border: 2px solid #f5d0d6;">
                    <span class="text-3xl">🚀</span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">
                        @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all')
                            No Matching Projects Found
                        @else
                            Start Your Corporate Project Portfolio
                        @endif
                    </h3>
                    <p class="text-xs text-slate-500 font-medium mt-1.5 leading-relaxed">
                        @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all')
                            No corporate projects matched your active search query or filter selection. Try clearing filters to see the full directory.
                        @else
                            Create your first enterprise project to unlock automated WBS blueprints, stakeholder collaboration, milestone tracking, and formal sign-offs.
                        @endif
                    </p>
                </div>
                <div class="pt-2">
                    @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all')
                        <button wire:click="resetFilters" type="button" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] hover:bg-rose-100 border border-rose-200 transition-all cursor-pointer shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Clear All Active Filters</span>
                        </button>
                    @elseif(auth()->user()?->canCreateProject())
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Launch Your First Project</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Edit / Create Project Modal -->
    <div x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
    >
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.closeModal()"></div>

        <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-5 border border-slate-200/90 z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">{{ $editingId ? 'Edit Project Settings' : 'Create New Project' }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Update project scope, owner, status, and target deadline</p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.closeModal()" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Name <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]">
                        @error('name') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Code <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="code" class="w-full text-xs font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                        @error('code') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Subsidiary <span class="text-rose-500">*</span></label>
                        <select wire:model="subsidiary_id" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:border-[#c3122e]">
                            <option value="">Select Subsidiary...</option>
                            @foreach($subsidiaries as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('subsidiary_id') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Manager / Lead <span class="text-rose-500">*</span></label>
                        <select wire:model="project_manager_id" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:border-[#c3122e]">
                            <option value="">Select Manager...</option>
                            @foreach($pms as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                        @error('project_manager_id') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Status</label>
                        <select wire:model="status" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                            @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Priority</label>
                        <select wire:model="priority" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                            @foreach(\App\Enums\Priority::cases() as $pr)
                                <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Start Date</label>
                        <input type="date" wire:model="start_date" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Deadline Date</label>
                        <input type="date" wire:model="deadline" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200">
                        @error('deadline') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Description & Scope</label>
                    <textarea wire:model="description" rows="3" placeholder="Overview of project objectives..." class="w-full text-xs rounded-xl p-3 border border-slate-200 focus:border-[#c3122e]"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.closeModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md cursor-pointer">Save Project Settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         ⚡ SLIDE-OVER QUICK PREVIEW DRAWER (Zero-Click Summaries)
         ============================================================ --}}
    @if($showQuickDrawer && $selectedDrawerProject)
        <div class="fixed inset-0 z-50 overflow-hidden animate-fade-in">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" wire:click="closeQuickDrawer"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <div class="w-screen max-w-md sm:max-w-lg bg-white shadow-2xl border-l border-slate-200 flex flex-col justify-between animate-in slide-in-from-right duration-300">
                    
                    {{-- Drawer Header --}}
                    <div class="p-5 sm:p-6 border-b border-slate-100" style="background: linear-gradient(135deg, #1a0a0d 0%, #2e1318 100%); color: #ffffff;">
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-black px-2.5 py-0.5 rounded-md bg-rose-500/30 text-rose-200 border border-rose-400/40">
                                    {{ $selectedDrawerProject->code }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-white/10 text-slate-200 border border-white/20">
                                    {{ $selectedDrawerProject->status->label() }}
                                </span>
                            </div>

                            <button wire:click="closeQuickDrawer" type="button" class="p-1.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/15 transition-colors cursor-pointer" title="Close Preview (Esc)">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <h3 class="text-lg font-black text-white leading-snug truncate" title="{{ $selectedDrawerProject->name }}">
                            {{ $selectedDrawerProject->name }}
                        </h3>
                        <p class="text-xs text-rose-200/80 font-medium mt-0.5">
                            🏢 {{ $selectedDrawerProject->subsidiary->name ?? 'George Steuart Group' }}
                        </p>
                    </div>

                    {{-- Drawer Scrollable Content --}}
                    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6 scrollbar-thin">
                        
                        {{-- 1. Key Metrics 2x2 Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Overall Progress</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="text-xl font-black text-[#c3122e]">{{ $selectedDrawerProject->overall_progress }}%</div>
                                    <span class="text-[10px] font-bold text-slate-500">Completed</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden mt-1.5">
                                    <div class="h-full bg-[#c3122e] rounded-full" style="width: {{ $selectedDrawerProject->overall_progress }}%"></div>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Deadline</span>
                                <div class="text-sm font-extrabold text-slate-900 mt-1">
                                    {{ $selectedDrawerProject->deadline ? $selectedDrawerProject->deadline->format('M d, Y') : 'Not Specified' }}
                                </div>
                                <span class="text-[10px] font-medium text-slate-500 block mt-0.5">
                                    {{ $selectedDrawerProject->deadline ? $selectedDrawerProject->deadline->diffForHumans() : '-' }}
                                </span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Project Manager</span>
                                <div class="text-xs font-bold text-slate-900 mt-1 truncate">
                                    {{ $selectedDrawerProject->projectManager->name ?? 'Unassigned' }}
                                </div>
                                <span class="text-[10px] font-medium text-slate-400 truncate block">{{ $selectedDrawerProject->projectManager->email ?? '' }}</span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Team Collaborators</span>
                                <div class="text-sm font-black text-slate-900 mt-1">
                                    {{ $selectedDrawerProject->members->count() }} Members
                                </div>
                                <span class="text-[10px] font-bold text-indigo-600 block mt-0.5">Attached</span>
                            </div>
                        </div>

                        {{-- 2. Fast Inline Task Completion List --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                                    <span>📋 Active Tasks</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                        {{ $selectedDrawerProject->wbsItems->count() }}
                                    </span>
                                </h4>
                                <span class="text-[10px] text-slate-400 font-medium">Click to toggle done</span>
                            </div>

                            <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                                @forelse($selectedDrawerProject->wbsItems->take(8) as $t)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl border {{ $t->status->value === 'completed' ? 'bg-emerald-50/50 border-emerald-100' : 'bg-slate-50/70 border-slate-100' }} transition-colors">
                                        <label class="flex items-center gap-2.5 min-w-0 flex-1 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                wire:click="toggleDrawerTask({{ $t->id }})" 
                                                @checked($t->status->value === 'completed')
                                                class="w-4 h-4 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300 cursor-pointer"
                                            >
                                            <span class="text-xs font-semibold text-slate-800 truncate {{ $t->status->value === 'completed' ? 'line-through text-slate-400' : '' }}">
                                                {{ $t->title }}
                                            </span>
                                        </label>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md flex-shrink-0 {{ $t->status->value === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200/60 text-slate-700' }}">
                                            {{ $t->status->label() }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic py-2 text-center">No tasks initialized in WBS yet.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- 3. 1-Click Fast Daily Status Log Note --}}
                        <div class="p-4 rounded-2xl bg-rose-50/50 border border-rose-100 space-y-2.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm">💬</span>
                                <h4 class="text-xs font-black text-[#c3122e]">1-Click Quick Update Log</h4>
                            </div>
                            <textarea 
                                wire:model="drawerQuickNote" 
                                rows="2" 
                                placeholder="Log a quick execution note, blocker, or update without opening workspace..."
                                class="w-full text-xs font-medium p-2.5 rounded-xl border border-rose-200/80 bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 text-slate-800"
                            ></textarea>
                            @error('drawerQuickNote') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            <div class="flex justify-end">
                                <button 
                                    wire:click="saveDrawerQuickUpdate" 
                                    type="button" 
                                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-xs transition-all cursor-pointer active:scale-95"
                                >
                                    Log Note &rarr;
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Drawer Footer: Full Workspace Jump --}}
                    <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3">
                        <button wire:click="closeQuickDrawer" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer">
                            Close
                        </button>

                        @if($selectedDrawerProject->isPmAccepted())
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}" style="background: #c3122e; color: #ffffff;" class="px-5 py-2 rounded-xl text-xs font-black shadow-md hover:bg-[#a00e24] transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Open Workspace</span>
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @elseif(auth()->id() === $selectedDrawerProject->project_manager_id)
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}" style="background: #d97706; color: #ffffff;" class="px-5 py-2 rounded-xl text-xs font-black shadow-md hover:bg-amber-700 transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Review &amp; Accept Leadership</span>
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}" style="background: #475569; color: #ffffff;" class="px-5 py-2 rounded-xl text-xs font-black shadow-md hover:bg-slate-700 transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>View Project Charter</span>
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Executive Delete Project Confirmation Modal -->
    @if($showDeleteModal)
    <div style="position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); box-sizing: border-box;">
        <div style="position: relative; width: 100%; max-width: 480px; background: #ffffff; border-radius: 20px; box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.4); border: 1px solid #e2e8f0; overflow: hidden; animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
            
            <!-- Modal Header / Icon -->
            <div style="padding: 22px 24px 16px 24px; display: flex; align-items: flex-start; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.15);">
                    🗑️
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a; line-height: 1.3;">
                        Delete Project?
                    </h3>
                    <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b; font-weight: 500; line-height: 1.4;">
                        Are you sure you want to delete <strong style="color: #0f172a;">{{ $projectToDeleteName }}</strong> (<code style="font-size: 11px; color: #c3122e; background: #fff1f2; padding: 1px 5px; border-radius: 4px; font-weight: 700;">{{ $projectToDeleteCode }}</code>)?
                    </p>
                </div>
                <button type="button" wire:click="cancelDelete" style="width: 28px; height: 28px; border-radius: 7px; border: 1px solid #e2e8f0; background: #ffffff; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    ✕
                </button>
            </div>

            <!-- Warning Notice Card -->
            <div style="margin: 0 24px 16px 24px; padding: 12px 14px; background: #fff1f2; border-radius: 12px; border: 1px solid #fecdd3; display: flex; align-items: flex-start; gap: 10px;">
                <span style="font-size: 15px; flex-shrink: 0;">🚨</span>
                <p style="margin: 0; font-size: 11.5px; color: #9f1239; font-weight: 600; line-height: 1.45;">
                    This will permanently delete this project and all its WBS items, deliverables, documents, risks, and records from the entire system. An audit log entry will be permanently recorded.
                </p>
            </div>

            <!-- Modal Footer Actions -->
            <div style="padding: 14px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                <button type="button" wire:click="cancelDelete" style="padding: 8px 16px; border-radius: 9px; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    Cancel
                </button>
                <button type="button" wire:click="executeDeleteProject" style="padding: 8px 20px; border-radius: 9px; border: none; background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); color: #ffffff; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(225, 29, 72, 0.35); transition: all 0.15s; display: flex; align-items: center; gap: 6px;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1.0'">
                    <span>Permanently Delete Project</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
