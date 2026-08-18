<div>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (PROJECTS DIRECTORY)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold Elegant Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: 3D Folder Icon + Title + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Projects Directory
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>📁</span>
                            <span>{{ $totalCount }} Enterprise Projects</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Corporate Portfolio Directory</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Manage, monitor, and track delivery progress across all corporate projects</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Primary Action -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                @if(auth()->user()?->hasAnyRole(['super_admin', 'project_manager']) || auth()->user()?->email === 'admin@nexuspm.local')
                    <a
                        href="{{ route('projects.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer no-underline"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Create New Project</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. INTERACTIVE 4-METRIC PORTFOLIO COCKPIT
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 mb-6">
        <!-- Metric 1: Total Projects -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'all')"
            class="text-left bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group {{ $statusFilter === 'all' ? 'ring-2 ring-slate-800 border-slate-800 bg-slate-50/50 shadow-xs' : 'shadow-2xs hover:border-[#c3122e]' }}"
        >
            <div class="flex items-center justify-between gap-2 mb-2.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Total Projects</span>
                <div class="w-8 h-8 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $totalCount }}</span>
                <span class="text-[10px] font-bold text-slate-500">Portfolio</span>
            </div>
        </button>

        <!-- Metric 2: Active in Progress -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'in_progress')"
            class="text-left bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group {{ $statusFilter === 'in_progress' ? 'ring-2 ring-[#c3122e] border-[#c3122e] bg-[#fdf4f4]/40 shadow-xs' : 'shadow-2xs hover:border-[#c3122e]' }}"
        >
            <div class="flex items-center justify-between gap-2 mb-2.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Active In Progress</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $activeCount }}</span>
                <span class="text-[10px] font-bold text-amber-700">Execution</span>
            </div>
        </button>

        <!-- Metric 3: Delivered / Completed -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'completed')"
            class="text-left bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group {{ $statusFilter === 'completed' ? 'ring-2 ring-emerald-600 border-emerald-600 bg-emerald-50/40 shadow-xs' : 'shadow-2xs hover:border-emerald-400' }}"
        >
            <div class="flex items-center justify-between gap-2 mb-2.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Delivered / Completed</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $completedCount }}</span>
                <span class="text-[10px] font-bold text-emerald-600">Delivered</span>
            </div>
        </button>

        <!-- Metric 4: Overdue Attention -->
        <button 
            type="button" 
            wire:click="$set('statusFilter', 'all')"
            class="text-left bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 transition-all duration-200 hover:shadow-md cursor-pointer group shadow-2xs hover:border-rose-400"
        >
            <div class="flex items-center justify-between gap-2 mb-2.5">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Overdue Attention</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl sm:text-3xl font-black {{ $overdueCount > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight font-mono leading-none">{{ $overdueCount }}</span>
                @if($overdueCount > 0)
                    <span class="text-[9px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">Action Req</span>
                @else
                    <span class="text-[10px] font-bold text-slate-400">On Track</span>
                @endif
            </div>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. SMART, INVITING SEARCH & FILTER TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs mb-6 space-y-3" x-data="{ moreFilters: false }">
        <!-- Main Search & Primary Filters Row -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
            <!-- Search Input Bar -->
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.250ms="search" 
                    placeholder="Search projects by title, code, subsidiary, or leader..."
                    class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all"
                >
                @if($search)
                    <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            <!-- Primary Filters & Actions -->
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap flex-shrink-0">
                <!-- Status Filter -->
                <div class="min-w-[150px] sm:w-44">
                    <select wire:model.live="statusFilter" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                        <option value="all">📋 All Statuses</option>
                        <option value="planning">⏳ Planning</option>
                        <option value="in_progress">⚡ In Progress</option>
                        <option value="on_hold">⏸️ On Hold</option>
                        <option value="completed">✅ Completed</option>
                        <option value="cancelled">🚫 Cancelled</option>
                    </select>
                </div>

                <!-- Subsidiary Filter -->
                <div class="min-w-[160px] sm:w-48">
                    <select wire:model.live="subsidiaryFilter" class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                        <option value="all">🏢 All Subsidiaries</option>
                        @foreach($subsidiaries as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- More Filters Toggle Button -->
                <button 
                    type="button" 
                    @click="moreFilters = !moreFilters"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-extrabold border transition-all cursor-pointer shadow-2xs"
                    :class="moreFilters || {{ ($managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all') ? 'true' : 'false' }} ? 'bg-[#fdf4f4] text-[#c3122e] border-rose-200' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filters</span>
                    @if($managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    @endif
                </button>

                <!-- Export Controls -->
                <div class="flex items-center gap-1">
                    <a href="{{ route('reports.export-csv') }}" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 border border-slate-200 transition-all" title="Export CSV">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </a>
                    <a href="{{ route('reports.export-pdf') }}" class="p-2 rounded-xl text-[#c3122e] hover:bg-rose-50 border border-rose-200 transition-all" title="Export PDF">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </a>
                </div>

                <!-- Reset Filter Button -->
                @if($search || $subsidiaryFilter !== 'all' || $managerFilter !== 'all' || $statusFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                    <button 
                        type="button" 
                        wire:click="resetFilters" 
                        class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer flex-shrink-0 shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Collapsible Advanced Filters Row (Smooth Expansion) -->
        <div x-show="moreFilters" x-collapse class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Project Manager Filter -->
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Project Leader / Manager</span>
                <select wire:model.live="managerFilter" class="w-full py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
                    <option value="all">All Project Leaders</option>
                    @foreach($pms as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Strategic Priority</span>
                <select wire:model.live="priorityFilter" class="w-full py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
                    <option value="all">All Priorities</option>
                    @foreach(\App\Enums\Priority::cases() as $pr)
                        <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Health Filter -->
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Delivery Health</span>
                <select wire:model.live="healthFilter" class="w-full py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] outline-none">
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
    <div class="bg-white border border-slate-200/90 rounded-3xl overflow-hidden shadow-xs mb-6">
        @if($projects->count() > 0)
            <div class="overflow-x-auto scrollbar-thin">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/90 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-5">Project &amp; Code</th>
                            <th class="py-4 px-4 hidden md:table-cell">Subsidiary</th>
                            <th class="py-4 px-4">Project Leader</th>
                            <th class="py-4 px-4">Status &amp; Health</th>
                            <th class="py-4 px-4 hidden lg:table-cell">Timeline</th>
                            <th class="py-4 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs bg-white">
                        @foreach($projects as $project)
                            @php
                                $daysLeft = $project->deadline ? (int) now()->today()->diffInDays($project->deadline, false) : null;
                                $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($project->status->value, ['completed','cancelled']);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Project Name & Code -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-black text-xs shadow-md shadow-rose-950/15" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                            {{ strtoupper(substr($project->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-slate-900 text-xs sm:text-sm group-hover:text-[#c3122e] leading-snug block truncate max-w-64 transition-colors">
                                                {{ $project->name }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[10px] font-mono font-black text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">{{ $project->code }}</span>
                                                <span class="text-[10px] text-slate-400 md:hidden">• {{ $project->subsidiary->code ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subsidiary -->
                                <td class="py-4 px-4 hidden md:table-cell">
                                    <span class="text-xs font-bold text-slate-800 block">
                                        {{ $project->subsidiary->name ?? '-' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $project->subsidiary->code ?? '' }}</span>
                                </td>

                                <!-- Project Manager -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 bg-slate-100 border border-slate-200 text-slate-700 text-xs font-black flex items-center justify-center rounded-xl flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($project->projectManager->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-extrabold text-slate-900 truncate max-w-40">{{ $project->projectManager->name ?? 'Unassigned' }}</div>
                                            @if($project->isPmRejected())
                                                <span class="text-[9px] font-extrabold text-rose-700 bg-rose-50 border border-rose-200 px-1.5 py-0.2 rounded inline-block" title="{{ $project->pm_rejection_reason }}">
                                                    ❌ Declined
                                                </span>
                                            @elseif($project->isPendingPmAcceptance())
                                                <span class="text-[9px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.2 rounded inline-block">
                                                    ⏳ Awaiting Sign-off
                                                </span>
                                            @else
                                                <span class="text-[9px] font-black text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded inline-block">
                                                    👑 Project Leader
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status & Health -->
                                <td class="py-4 px-4">
                                    @php
                                        $statusStyles = match($project->status->value) {
                                            'in_progress' => 'bg-amber-50 text-amber-900 border-amber-200',
                                            'completed'   => 'bg-emerald-50 text-emerald-900 border-emerald-200',
                                            'on_hold'     => 'bg-orange-50 text-orange-900 border-orange-200',
                                            'cancelled'   => 'bg-slate-100 text-slate-600 border-slate-200',
                                            default       => 'bg-rose-50 text-[#c3122e] border-rose-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black border {{ $statusStyles }} whitespace-nowrap shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $project->status->value === 'completed' ? 'bg-emerald-500' : ($project->status->value === 'in_progress' ? 'bg-amber-500 animate-pulse' : 'bg-rose-500') }}"></span>
                                        {{ $project->status->label() }}
                                    </span>
                                </td>

                                <!-- Deadline -->
                                <td class="py-4 px-4 hidden lg:table-cell">
                                    <div class="text-xs font-black text-slate-900">
                                        {{ $project->deadline ? $project->deadline->format('M d, Y') : 'TBD' }}
                                    </div>
                                    @if($project->deadline)
                                        <div class="text-[10px] font-extrabold mt-0.5 {{ $project->status->value === 'completed' ? 'text-emerald-700' : ($isOverdue ? 'text-rose-700' : 'text-slate-400') }}">
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
                                <td class="py-4 px-5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 justify-end">
                                        <button 
                                            wire:click="openQuickDrawer({{ $project->id }})" 
                                            type="button" 
                                            class="px-2.5 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-rose-50 hover:text-[#c3122e] border border-slate-200 transition-all inline-flex items-center gap-1 cursor-pointer shadow-2xs" 
                                            title="Quick Overview"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span class="hidden sm:inline">Preview</span>
                                        </button>

                                        @if($project->isPmAccepted())
                                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <span>Workspace</span>
                                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                            </a>
                                        @elseif(auth()->id() === $project->project_manager_id)
                                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-black text-white shadow-md bg-amber-600 hover:bg-amber-700 transition-all">
                                                <span>Review &amp; Accept</span>
                                            </a>
                                        @else
                                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200/80 border border-slate-200 transition-all">
                                                <span>Brief</span>
                                            </a>
                                        @endif

                                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->email === 'admin@nexuspm.local' || auth()->user()?->id === 1)
                                            <button wire:click="edit({{ $project->id }})" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition-all border border-transparent hover:border-slate-200" title="Edit Project">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button wire:click="deleteProject({{ $project->id }})" wire:confirm="Are you sure you want to delete '{{ $project->name }}'?" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-all" title="Delete Project">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            <div class="p-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
                <div>
                    Showing <span class="font-bold text-slate-900">{{ $projects->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $projects->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-900">{{ $projects->total() }}</span> projects
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span>Per page:</span>
                        <select wire:model.live="perPage" class="text-xs font-bold py-1 px-2.5 rounded-lg border border-slate-200 bg-slate-50">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                    {{ $projects->links() }}
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
                    @elseif(auth()->user()?->hasAnyRole(['super_admin', 'project_manager']) || auth()->user()?->email === 'admin@nexuspm.local')
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
</div>
