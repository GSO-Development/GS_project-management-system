<div class="space-y-5 pb-12">
    <!-- ═══════════════════════════════════════════════════════════════
         1. EXECUTIVE BANNER WITH LUXURY SKYLINE PANORAMA
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50 z-20"></div>

        <!-- Luxury Cityscape Background Image -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Project Calendar Skyline" 
                class="w-full h-full object-cover object-right opacity-90"
            >
            <!-- Left Crimson Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#120307] via-[#1f050d]/95 to-transparent lg:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/40 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignette -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left: Calendar Icon + Title + Status Badges -->
            <div class="flex items-start sm:items-center gap-4 min-w-0">
                <div class="w-13 h-13 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] border border-white/20 text-white flex items-center justify-center shadow-lg shadow-rose-950/40 flex-shrink-0">
                    <svg class="w-7 h-7 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Project Calendar
                        </h1>
                        <span class="px-3 py-1 rounded-full text-xs font-black text-amber-300 border border-amber-400/40 bg-black/45 backdrop-blur-md shadow-inner font-mono tracking-tight">
                            📅 {{ $currentDate->format('F Y') }}
                        </span>

                        <!-- Dynamic Role Scope Pill -->
                        @if($isPmoAdmin)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-rose-200 border border-rose-400/30 bg-rose-950/70 backdrop-blur-md shadow-2xs flex items-center gap-1">
                                <span>🛡️</span>
                                <span>Organization-Wide</span>
                            </span>
                        @elseif($isProjectManager)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-amber-200 border border-amber-400/30 bg-black/50 backdrop-blur-md shadow-2xs flex items-center gap-1">
                                <span>👔</span>
                                <span>Portfolio Scope</span>
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-emerald-200 border border-emerald-400/30 bg-emerald-950/70 backdrop-blur-md shadow-2xs flex items-center gap-1">
                                <span>👤</span>
                                <span>My Assigned</span>
                            </span>
                        @endif
                    </div>
                    <p class="text-xs font-medium text-slate-300 mt-1">
                        @if($isPmoAdmin)
                            Organization-wide deliverable tracking, milestones, and deadlines across all subsidiaries.
                        @elseif($isProjectManager)
                            Work stream across your managed projects and personal assigned deliverables.
                        @else
                            Dedicated schedule of deliverables and milestones assigned directly to you.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Right: Month Nav & View Mode Switcher -->
            <div class="flex items-center gap-3 flex-wrap self-start lg:self-center">
                <!-- Month Navigator (Frosted Glass Pill) -->
                <div class="inline-flex items-center p-1 rounded-2xl border border-white/20 shadow-xl backdrop-blur-xl bg-black/40">
                    <button wire:click="prevMonth" type="button" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/15 transition-all cursor-pointer" title="Previous Month">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button wire:click="today" type="button" class="px-3.5 py-1.5 text-xs font-black text-white hover:bg-white/15 rounded-xl transition-all cursor-pointer flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Today</span>
                    </button>
                    <button wire:click="nextMonth" type="button" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/15 transition-all cursor-pointer" title="Next Month">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>

                <!-- View Switcher (Frosted Segmented iOS/Linear Style) -->
                <div class="inline-flex p-1 rounded-2xl border border-white/20 shadow-xl backdrop-blur-xl bg-black/40">
                    <button wire:click="setViewMode('calendar')" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5 {{ $viewMode === 'calendar' ? 'bg-white text-slate-950 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/15' }}">
                        <span>📅</span>
                        <span>Month Grid</span>
                    </button>
                    <button wire:click="setViewMode('day')" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5 {{ $viewMode === 'day' ? 'bg-white text-slate-950 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/15' }}">
                        <span>📆</span>
                        <span>Day Focus</span>
                    </button>
                    <button wire:click="setViewMode('list')" type="button" class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5 {{ $viewMode === 'list' ? 'bg-white text-slate-950 shadow-md scale-[1.02]' : 'text-slate-200 hover:text-white hover:bg-white/15' }}">
                        <span>📋</span>
                        <span>Timeline List</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. CLEAN CONTROL BAR (SCOPE TABS, SEARCH & FILTERS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs p-5 space-y-4">
        <!-- Top Row: Scope Switcher + Search Bar -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <!-- Role Scope Tabs -->
            <div class="flex items-center gap-3 flex-wrap">
                @if($isProjectManager)
                    <!-- PM Scope Switcher: All Tasks vs My Tasks -->
                    <div class="inline-flex p-1 rounded-2xl bg-slate-100/90 border border-slate-200/80 shadow-2xs">
                        <button
                            wire:click="setScope('all')"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $scopeFilter === 'all' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>All Tasks (Projects I Manage)</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $scopeFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $allTasksScopeCount }}
                            </span>
                        </button>

                        <button
                            wire:click="setScope('my_tasks')"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $scopeFilter === 'my_tasks' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>My Tasks (Assigned to Me)</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $scopeFilter === 'my_tasks' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $myTasksScopeCount }}
                            </span>
                        </button>
                    </div>
                @elseif($isPmoAdmin)
                    <!-- PMO Admin Scope Switcher -->
                    <div class="inline-flex p-1 rounded-2xl bg-slate-100/90 border border-slate-200/80 shadow-2xs">
                        <button
                            wire:click="setScope('all')"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $scopeFilter === 'all' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                            <span>All Organization Tasks</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $scopeFilter === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $allTasksScopeCount }}
                            </span>
                        </button>

                        <button
                            wire:click="setScope('my_tasks')"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-2 cursor-pointer {{ $scopeFilter === 'my_tasks' ? 'bg-[#c3122e] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' }}"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>My Assigned Tasks</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $scopeFilter === 'my_tasks' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                {{ $myTasksScopeCount }}
                            </span>
                        </button>
                    </div>
                @else
                    <!-- Regular User: Dedicated Personal Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-black shadow-2xs">
                        <span>👤</span>
                        <span>My Assigned Tasks</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-200 text-emerald-950 text-[10px] font-mono font-bold">
                            {{ $myTasksScopeCount }}
                        </span>
                    </div>
                @endif
            </div>

            <!-- Instant Search Field -->
            <div class="w-full lg:w-80 relative">
                <input
                    wire:model.live.debounce.300ms="search"
                    type="text"
                    placeholder="Search tasks, codes, or projects..."
                    class="w-full text-xs font-medium pl-10 pr-9 py-2.5 rounded-2xl border border-slate-200 bg-slate-50/70 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#c3122e] focus:bg-white transition-all shadow-inner"
                >
                <div class="absolute left-3.5 top-3 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(!empty($search))
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Bottom Row: Filter Dropdowns & Visual Legend -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Subsidiary Filter -->
                <div class="relative">
                    <select wire:model.live="subsidiaryFilter" class="form-select text-xs min-w-36 py-2 px-3 rounded-xl border-slate-200 bg-slate-50/80 font-bold text-slate-700 cursor-pointer shadow-2xs hover:border-slate-300">
                        <option value="all">🏢 All Subsidiaries</option>
                        @foreach($subsidiaries as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Project Filter -->
                <div class="relative">
                    <select wire:model.live="projectFilter" class="form-select text-xs min-w-40 py-2 px-3 rounded-xl border-slate-200 bg-slate-50/80 font-bold text-slate-700 cursor-pointer shadow-2xs hover:border-slate-300">
                        <option value="all">📁 All Projects</option>
                        @foreach($projectsList as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="relative">
                    <select wire:model.live="statusFilter" class="form-select text-xs min-w-32 py-2 px-3 rounded-xl border-slate-200 bg-slate-50/80 font-bold text-slate-700 cursor-pointer shadow-2xs hover:border-slate-300">
                        <option value="all">⚡ All Statuses</option>
                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                            <option value="{{ $st->value }}">{{ $st->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Filter -->
                <div class="relative">
                    <select wire:model.live="priorityFilter" class="form-select text-xs min-w-30 py-2 px-3 rounded-xl border-slate-200 bg-slate-50/80 font-bold text-slate-700 cursor-pointer shadow-2xs hover:border-slate-300">
                        <option value="all">🎯 All Priorities</option>
                        @foreach(\App\Enums\Priority::cases() as $pr)
                            <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Item Type Filter -->
                <div class="relative">
                    <select wire:model.live="typeFilter" class="form-select text-xs min-w-30 py-2 px-3 rounded-xl border-slate-200 bg-slate-50/80 font-bold text-slate-700 cursor-pointer shadow-2xs hover:border-slate-300">
                        <option value="all">📋 All Types</option>
                        <option value="task">Tasks Only</option>
                        <option value="milestone">Milestones Only</option>
                    </select>
                </div>

                @if($subsidiaryFilter !== 'all' || $projectFilter !== 'all' || $statusFilter !== 'all' || $priorityFilter !== 'all' || $typeFilter !== 'all' || !empty($search))
                    <button
                        wire:click="clearFilters"
                        type="button"
                        class="px-3 py-2 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer shadow-2xs flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset</span>
                    </button>
                @endif
            </div>

            <!-- Visual Legend Badges -->
            <div class="flex items-center gap-3 text-xs font-bold text-slate-600 flex-wrap">
                <span class="flex items-center gap-1.5 text-[11px]"><span class="w-2.5 h-2.5 rounded-full bg-rose-500 ring-2 ring-rose-200"></span> Deadline</span>
                <span class="flex items-center gap-1.5 text-[11px]"><span class="w-2.5 h-2.5 rounded-full bg-purple-600 ring-2 ring-purple-200"></span> Milestone</span>
                <span class="flex items-center gap-1.5 text-[11px]"><span class="w-2.5 h-2.5 rounded-full bg-[#c3122e] ring-2 ring-rose-200"></span> Task</span>
                <span class="flex items-center gap-1.5 text-[11px]"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-emerald-200"></span> Completed</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. KPI EXECUTIVE METRIC TILES (INTERACTIVE FILTERS)
         ═══════════════════════════════════════════════════════════════ -->
    <!-- ═══════════════════════════════════════════════════════════════
         3. KPI EXECUTIVE METRIC TILES (INTERACTIVE FILTERS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4.5 shadow-xs flex items-center gap-4 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5 transition-all group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-slate-400 to-slate-600"></div>
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                📋
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider block">Total Scheduled</span>
                <span class="text-2xl font-black text-slate-900 font-mono tracking-tight">{{ $totalMonthEvents }}</span>
            </div>
        </div>

        <!-- In Progress -->
        <div
            wire:click="setStatusFilter('in_progress')"
            class="bg-white rounded-2xl border p-4.5 shadow-xs flex items-center gap-4 transition-all cursor-pointer hover:shadow-md hover:-translate-y-0.5 group relative overflow-hidden {{ $statusFilter === 'in_progress' ? 'border-blue-500 ring-2 ring-blue-100 bg-blue-50/30' : 'border-blue-200/80 hover:border-blue-300' }}"
            title="Click to filter In Progress tasks"
        >
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                ⚡
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <span class="text-[10px] font-black uppercase text-blue-600 tracking-wider block">In Progress</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                </div>
                <span class="text-2xl font-black text-blue-950 font-mono tracking-tight">{{ $inProgressMonthEvents }}</span>
            </div>
        </div>

        <!-- Completed -->
        <div
            wire:click="setStatusFilter('completed')"
            class="bg-white rounded-2xl border p-4.5 shadow-xs flex items-center gap-4 transition-all cursor-pointer hover:shadow-md hover:-translate-y-0.5 group relative overflow-hidden {{ $statusFilter === 'completed' ? 'border-emerald-500 ring-2 ring-emerald-100 bg-emerald-50/30' : 'border-emerald-200/80 hover:border-emerald-300' }}"
            title="Click to filter Completed tasks"
        >
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-600"></div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                ✅
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-emerald-600 tracking-wider block">Completed</span>
                <span class="text-2xl font-black text-emerald-950 font-mono tracking-tight">{{ $completedMonthEvents }}</span>
            </div>
        </div>

        <!-- Milestones & Deadlines -->
        <div class="bg-white rounded-2xl border border-purple-200/80 p-4.5 shadow-xs flex items-center gap-4 hover:border-purple-300 hover:shadow-md hover:-translate-y-0.5 transition-all group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                ⭐
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-purple-600 tracking-wider block">Milestones &amp; DLs</span>
                <span class="text-2xl font-black text-purple-950 font-mono tracking-tight">{{ $milestoneMonthEvents }}</span>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white rounded-2xl border border-rose-200/80 p-4.5 shadow-xs flex items-center gap-4 col-span-2 sm:col-span-1 hover:border-rose-300 hover:shadow-md hover:-translate-y-0.5 transition-all group relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-red-600"></div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-inner group-hover:scale-105 transition-transform">
                ⚠️
            </div>
            <div class="min-w-0">
                <span class="text-[10px] font-black uppercase text-rose-600 tracking-wider block">Overdue Action</span>
                <span class="text-2xl font-black text-rose-950 font-mono tracking-tight">{{ $overdueMonthEvents }}</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         4. MAIN CONTENT VIEWS
         ═══════════════════════════════════════════════════════════════ -->

    @if($viewMode === 'calendar')
        <!-- ─────────────────────────────────────────────────────────────
             VIEW 1: MODERN MONTH GRID + INTERACTIVE DAY FOCUS PANEL
             ───────────────────────────────────────────────────────────── -->
        <div class="space-y-6">
            <!-- 7-Column Modern Calendar Grid -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden overflow-x-auto scrollbar-thin">
                <div class="min-w-[850px] lg:min-w-0">
                    <!-- Weekday Header Ribbon -->
                    <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50/90 text-center font-black text-xs text-slate-500 uppercase tracking-wider">
                        <div class="py-3.5 text-rose-700 bg-rose-50/40">Sun</div>
                        <div class="py-3.5">Mon</div>
                        <div class="py-3.5">Tue</div>
                        <div class="py-3.5">Wed</div>
                        <div class="py-3.5">Thu</div>
                        <div class="py-3.5">Fri</div>
                        <div class="py-3.5 text-rose-700 bg-rose-50/40">Sat</div>
                    </div>

                    <!-- 7-Column Calendar Days Grid -->
                    <div class="divide-y divide-slate-200">
                        @foreach($weeks as $week)
                            <div class="grid grid-cols-7 divide-x divide-slate-200 min-h-36">
                                @foreach($week as $day)
                                    @php
                                        $eventCount = count($day['events']);
                                        $isWeekend = in_array(\Carbon\Carbon::parse($day['date'])->dayOfWeek, [0, 6]);
                                        $cellBg = match(true) {
                                            $day['isSelected'] => 'bg-rose-50/50 ring-2 ring-[#c3122e] ring-inset z-10 shadow-xs',
                                            $day['isToday'] => 'bg-amber-50/30 ring-1 ring-amber-400 ring-inset',
                                            !$day['isCurrentMonth'] => 'bg-slate-50/70 text-slate-400',
                                            $isWeekend => 'bg-slate-50/40',
                                            default => 'bg-white',
                                        };
                                    @endphp
                                    <div
                                        wire:click="selectDate('{{ $day['date'] }}')"
                                        class="p-2.5 transition-all relative cursor-pointer hover:bg-slate-100/80 group flex flex-col justify-between {{ $cellBg }}"
                                        title="Click to view schedule for {{ $day['date'] }}"
                                    >
                                        <div>
                                            <!-- Day Top: Number + Badge + Plus Button -->
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-1.5">
                                                    @if($day['isToday'])
                                                        <span class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#c3122e] to-[#e02d4b] text-white font-black text-xs flex items-center justify-center shadow-md font-mono ring-2 ring-rose-200">
                                                            {{ $day['dayNumber'] }}
                                                        </span>
                                                    @elseif($day['isSelected'])
                                                        <span class="w-7 h-7 rounded-full bg-slate-900 text-white font-black text-xs flex items-center justify-center shadow-md font-mono">
                                                            {{ $day['dayNumber'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-xs font-black font-mono {{ $day['isCurrentMonth'] ? 'text-slate-800' : 'text-slate-400' }}">
                                                            {{ $day['dayNumber'] }}
                                                        </span>
                                                    @endif

                                                    @if($day['isSelected'])
                                                        <span class="text-[9px] font-black uppercase tracking-wider text-[#c3122e] hidden sm:inline">Active</span>
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-1">
                                                    @if($eventCount > 0)
                                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $day['isSelected'] ? 'bg-[#c3122e] text-white shadow-2xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                            {{ $eventCount }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Micro Event Chips (Max 3 visible per cell) -->
                                            <div class="space-y-1 overflow-y-auto max-h-26">
                                                @foreach(array_slice($day['events'], 0, 3) as $evt)
                                                    @php
                                                        $chipStyle = match(true) {
                                                            $evt['type'] === 'project_deadline' => 'bg-rose-50 text-rose-900 border-rose-200 hover:bg-rose-100',
                                                            $evt['type'] === 'milestone' => 'bg-purple-50 text-purple-900 border-purple-200 hover:bg-purple-100',
                                                            $evt['status'] === 'completed' => 'bg-emerald-50 text-emerald-900 border-emerald-200 hover:bg-emerald-100',
                                                            $evt['status'] === 'in_progress' => 'bg-blue-50 text-blue-900 border-blue-200 hover:bg-blue-100',
                                                            default => 'bg-slate-50 text-slate-800 border-slate-200 hover:bg-slate-100',
                                                        };
                                                    @endphp
                                                    <div
                                                        wire:click.stop="showEventDetails({{ json_encode($evt) }})"
                                                        class="p-1 rounded-lg text-[10px] leading-tight border font-medium truncate cursor-pointer transition-all active:scale-95 shadow-2xs {{ $chipStyle }}"
                                                        title="{{ $evt['title'] }} &bull; {{ $evt['project'] }} ({{ $evt['time_range'] ?: 'All Day' }})"
                                                    >
                                                        <div class="flex items-center gap-1">
                                                            @if($evt['type'] === 'project_deadline')
                                                                <span class="text-rose-600 font-bold">🎯</span>
                                                            @elseif($evt['type'] === 'milestone')
                                                                <span class="text-purple-600 font-bold">⭐</span>
                                                            @elseif($evt['status'] === 'completed')
                                                                <span class="text-emerald-600 font-bold">✓</span>
                                                            @else
                                                                <span class="w-1.5 h-1.5 rounded-full {{ $evt['status'] === 'in_progress' ? 'bg-blue-500' : 'bg-slate-400' }}"></span>
                                                            @endif

                                                            @if($evt['start_time'])
                                                                <span class="font-bold opacity-75 font-mono text-[9px]">{{ $evt['start_time'] }}</span>
                                                            @endif
                                                            <span class="truncate font-bold">{{ $evt['title'] }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                @if($eventCount > 3)
                                                    <div class="text-[9px] font-black text-[#c3122e] hover:underline cursor-pointer pt-0.5 text-center">
                                                        +{{ $eventCount - 3 }} more &rarr;
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════════════
                 SELECTED DAY AGENDA & WORK SCHEDULE PANEL
                 ═══════════════════════════════════════════════════════════════ -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Day Header -->
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4" style="background: linear-gradient(to right, #ffffff, #faf9f9);">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#7f0b1a] text-white flex flex-col items-center justify-center font-bold text-xl shadow-md flex-shrink-0">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-200 leading-none">{{ $selectedDateObj->format('M') }}</span>
                            <span class="text-xl font-black leading-tight">{{ $selectedDateObj->format('d') }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                                    {{ $selectedDateObj->format('l, F j, Y') }}
                                </h2>
                                @if($selectedDateObj->isToday())
                                    <span class="px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#c3122e] text-white shadow-2xs">Today</span>
                                @elseif($selectedDateObj->isPast())
                                    <span class="text-xs text-slate-400 font-semibold">({{ $selectedDateObj->diffForHumans() }})</span>
                                @else
                                    <span class="text-xs text-rose-600 font-semibold">({{ $selectedDateObj->diffForHumans() }})</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-1">
                                Showing <strong>{{ count($selectedDayEvents) }}</strong> deliverable{{ count($selectedDayEvents) === 1 ? '' : 's' }} scheduled for this day
                            </p>
                        </div>
                    </div>

                    <!-- Day Navigation Buttons & Actions -->
                    <div class="flex items-center gap-2.5 flex-wrap self-stretch sm:self-auto justify-between sm:justify-end">
                        <div class="inline-flex items-center p-1 rounded-2xl bg-slate-100 border border-slate-200">
                            <button wire:click="prevDay" type="button" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer" title="Previous Day">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button wire:click="selectDate('{{ now()->format('Y-m-d') }}')" type="button" class="px-3.5 py-1 text-xs font-bold text-slate-700 hover:text-slate-900 cursor-pointer">
                                Today
                            </button>
                            <button wire:click="nextDay" type="button" class="p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white transition-all cursor-pointer" title="Next Day">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        <button
                            wire:click="setViewMode('day')"
                            type="button"
                            class="px-4 py-2 rounded-2xl text-xs font-bold text-slate-700 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all cursor-pointer flex items-center gap-1.5"
                        >
                            <span>Full Day Focus</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Day Schedule Task Cards -->
                <div class="p-6">
                    @if(count($selectedDayEvents) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($selectedDayEvents as $evt)
                                @php
                                    $isCompleted = ($evt['status'] === 'completed');
                                    $isDeadline = ($evt['type'] === 'project_deadline');
                                    $isMilestone = ($evt['type'] === 'milestone');

                                    $cardBorder = match(true) {
                                        $isDeadline => 'border-l-rose-500',
                                        $isMilestone => 'border-l-purple-600',
                                        $isCompleted => 'border-l-emerald-500',
                                        default => 'border-l-[#c3122e]',
                                    };

                                    $typeBadge = match(true) {
                                        $isDeadline => ['🎯 Project Deadline', 'bg-rose-50 text-rose-700 border-rose-200'],
                                        $isMilestone => ['⭐ Milestone', 'bg-purple-50 text-purple-700 border-purple-200'],
                                        default => ['📋 Task', 'bg-slate-100 text-slate-700 border-slate-200'],
                                    };
                                @endphp
                                <div class="bg-white rounded-2xl border border-slate-200 border-l-4 {{ $cardBorder }} p-4 sm:p-5 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between gap-3 group">
                                    <!-- Top Badges & Time -->
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black border shadow-2xs {{ $typeBadge[1] }}">
                                                {{ $typeBadge[0] }}
                                            </span>
                                            @if($evt['code'])
                                                <span class="font-mono text-[10px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                                    {{ $evt['code'] }}
                                                </span>
                                            @endif
                                            @if($evt['is_my_task'])
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                    Assigned to You
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Time Badge -->
                                        <div class="flex items-center gap-1.5 text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-xl border border-slate-200 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $evt['time_range'] ?: 'All Day' }}</span>
                                        </div>
                                    </div>

                                    <!-- Title & Project Context -->
                                    <div>
                                        <h3
                                            wire:click="showEventDetails({{ json_encode($evt) }})"
                                            class="text-sm sm:text-base font-black text-slate-900 group-hover:text-[#c3122e] transition-colors cursor-pointer line-clamp-2 leading-snug"
                                            title="{{ $evt['title'] }}"
                                        >
                                            {{ $evt['title'] }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1.5 text-xs font-bold text-slate-500 flex-wrap">
                                            <a
                                                href="{{ route('projects.show', $evt['project_id']) }}"
                                                class="text-slate-700 hover:text-[#c3122e] hover:underline flex items-center gap-1 transition-colors"
                                            >
                                                <span>📁</span>
                                                <span class="truncate max-w-48">{{ $evt['project'] }}</span>
                                            </a>
                                            @if($evt['subsidiary'])
                                                <span class="text-slate-300">&bull;</span>
                                                <span class="text-[11px] text-slate-400 font-semibold truncate">{{ $evt['subsidiary'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Assignee & Action -->
                                    <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <!-- Assignee Info -->
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] text-xs font-black flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($evt['assigned_user'] ?? 'U', 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-extrabold text-slate-700 truncate max-w-36">{{ $evt['assigned_user'] }}</span>
                                        </div>

                                        <!-- Status & Actions -->
                                        <div class="flex items-center gap-2 justify-between sm:justify-end">
                                            @if($evt['type'] === 'task')
                                                <select
                                                    wire:change="updateTaskStatusFromCalendar({{ $evt['id'] }}, $event.target.value)"
                                                    class="text-xs font-black rounded-xl px-2.5 py-1.5 border border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#c3122e] cursor-pointer"
                                                >
                                                    @foreach(\App\Enums\WbsStatus::cases() as $st)
                                                        <option value="{{ $st->value }}" @selected($evt['status'] === $st->value)>
                                                            {{ $st->label() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold border {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                                    {{ $evt['status_label'] }}
                                                </span>
                                            @endif

                                            <button
                                                wire:click="showEventDetails({{ json_encode($evt) }})"
                                                type="button"
                                                class="px-3 py-1.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer shadow-2xs"
                                            >
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Clean Empty State -->
                        <div class="p-10 text-center rounded-3xl bg-slate-50/70 border border-dashed border-slate-200 space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center mx-auto text-2xl shadow-2xs">
                                ☕
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">No deliverables scheduled for this day</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-0.5">
                                    No tasks, milestones, or deadlines recorded on <strong>{{ $selectedDateObj->format('F j, Y') }}</strong>.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @elseif($viewMode === 'day')
        <!-- ─────────────────────────────────────────────────────────────
             VIEW 2: FULL-PAGE DAY FOCUS / TIMELINE MODE
             ───────────────────────────────────────────────────────────── -->
        <div class="space-y-6">
            <!-- Day Focus Header Bar -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#7f0b1a] text-white flex flex-col items-center justify-center font-mono shadow-lg flex-shrink-0">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-200">{{ $selectedDateObj->format('M') }}</span>
                        <span class="text-2xl font-black leading-none">{{ $selectedDateObj->format('d') }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                                {{ $selectedDateObj->format('l, F j, Y') }}
                            </h2>
                            @if($selectedDateObj->isToday())
                                <span class="px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#c3122e] text-white shadow-2xs">Today</span>
                            @endif
                        </div>
                        <p class="text-xs font-bold text-slate-500 mt-1">
                            Chronological execution block for {{ count($selectedDayEvents) }} deliverable{{ count($selectedDayEvents) === 1 ? '' : 's' }}
                        </p>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-2 flex-wrap">
                    <button wire:click="prevDay" type="button" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors flex items-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span>Previous Day</span>
                    </button>
                    <button wire:click="selectDate('{{ now()->format('Y-m-d') }}')" type="button" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                        Today
                    </button>
                    <button wire:click="nextDay" type="button" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors flex items-center gap-1 cursor-pointer">
                        <span>Next Day</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Deliverables Cards -->
            @if(count($selectedDayEvents) > 0)
                <div class="space-y-4">
                    @foreach($selectedDayEvents as $evt)
                        @php
                            $isCompleted = ($evt['status'] === 'completed');
                            $isDeadline = ($evt['type'] === 'project_deadline');
                            $isMilestone = ($evt['type'] === 'milestone');
                        @endphp
                        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                            <div class="w-full md:w-48 flex-shrink-0 flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-bold text-base shadow-2xs">
                                    ⏰
                                </div>
                                <div>
                                    <span class="text-xs font-black text-slate-800 block font-mono">{{ $evt['time_range'] ?: 'All Day' }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">{{ $evt['type'] === 'project_deadline' ? 'Deadline' : ($evt['type'] === 'milestone' ? 'Milestone' : 'Task') }}</span>
                                </div>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3
                                        wire:click="showEventDetails({{ json_encode($evt) }})"
                                        class="text-base font-black text-slate-900 hover:text-[#c3122e] transition-colors cursor-pointer"
                                    >
                                        {{ $evt['title'] }}
                                    </h3>
                                    @if($evt['code'])
                                        <span class="font-mono text-xs font-bold text-[#c3122e] bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                            {{ $evt['code'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-xs font-bold text-slate-500 flex-wrap">
                                    <a href="{{ route('projects.show', $evt['project_id']) }}" class="text-slate-700 hover:text-[#c3122e] hover:underline flex items-center gap-1">
                                        <span>📁</span>
                                        <span>{{ $evt['project'] }}</span>
                                    </a>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>Assignee: <strong class="text-slate-700">{{ $evt['assigned_user'] }}</strong></span>
                                    <span class="text-slate-300">&bull;</span>
                                    <span>Priority: <strong class="capitalize text-slate-700">{{ $evt['priority'] }}</strong></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                                @if($evt['type'] === 'task')
                                    <select
                                        wire:change="updateTaskStatusFromCalendar({{ $evt['id'] }}, $event.target.value)"
                                        class="text-xs font-black rounded-xl px-3 py-1.5 border border-slate-200 bg-slate-50 text-slate-800 cursor-pointer"
                                    >
                                        @foreach(\App\Enums\WbsStatus::cases() as $st)
                                            <option value="{{ $st->value }}" @selected($evt['status'] === $st->value)>
                                                {{ $st->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <span class="px-3 py-1.5 rounded-xl text-xs font-bold border {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                        {{ $evt['status_label'] }}
                                    </span>
                                @endif

                                <button
                                    wire:click="showEventDetails({{ json_encode($evt) }})"
                                    type="button"
                                    class="px-4 py-2 rounded-xl text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer shadow-2xs"
                                >
                                    Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center mx-auto text-2xl">
                        ☕
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">No events or tasks on this day</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                            There are no deliverables scheduled on {{ $selectedDateObj->format('l, F j, Y') }}.
                        </p>
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- ─────────────────────────────────────────────────────────────
             VIEW 3: CREATIVE CARDLESS TIMELINE EXECUTION STREAM
             ───────────────────────────────────────────────────────────── -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-8">
            <!-- Timeline Stream Master Header -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#7f0b1a] text-white flex items-center justify-center font-bold text-xl shadow-md flex-shrink-0">
                        ⚡
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">
                            Executive Timeline &amp; Deliverable Stream
                        </h2>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            Connected chronological execution track for <strong>{{ $currentDate->format('F Y') }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Stream Stats Pills -->
                <div class="flex items-center gap-2 flex-wrap text-xs font-black">
                    <div class="px-3.5 py-1.5 rounded-xl bg-slate-100 text-slate-800 border border-slate-200/80 shadow-2xs">
                        <span>Total:</span> <span class="font-mono text-slate-950 font-black">{{ count($monthListEvents) }}</span>
                    </div>
                    <div class="px-3.5 py-1.5 rounded-xl bg-purple-50 text-purple-800 border border-purple-200/80 shadow-2xs">
                        <span>⭐ Milestones:</span> <span class="font-mono font-black">{{ $milestoneMonthEvents }}</span>
                    </div>
                    <div class="px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-800 border border-blue-200/80 shadow-2xs">
                        <span>⚡ In Progress:</span> <span class="font-mono font-black">{{ $inProgressMonthEvents }}</span>
                    </div>
                    <div class="px-3.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200/80 shadow-2xs">
                        <span>✅ Completed:</span> <span class="font-mono font-black">{{ $completedMonthEvents }}</span>
                    </div>
                </div>
            </div>

            <!-- Connected Continuous Vertical Timeline Track -->
            @forelse($groupedListEvents as $dateStr => $eventsOnDate)
                @php
                    $dateObj = \Carbon\Carbon::parse($dateStr);
                    $isToday = $dateObj->isToday();
                    $isPast = $dateObj->isPast() && !$isToday;
                @endphp

                <div class="relative pl-8 sm:pl-12 before:absolute before:left-3 sm:before:left-4 before:top-4 before:bottom-0 before:w-0.5 before:bg-gradient-to-b before:from-[#c3122e] before:via-slate-200 before:to-slate-200 last:before:hidden space-y-4">
                    <!-- Date Anchor Node (Integrated on Spine) -->
                    <div class="flex items-center gap-3 relative pl-4 sm:pl-6 pt-2 pb-1">
                        <!-- Node Circle on Spine -->
                        <div class="absolute -left-[27px] sm:-left-[43px] top-1/2 -translate-y-1/2 w-4 h-4 rounded-full border-2 border-white shadow-md {{ $isToday ? 'bg-[#c3122e] ring-4 ring-rose-200 animate-pulse' : ($isPast ? 'bg-slate-400 ring-2 ring-slate-200' : 'bg-[#c3122e] ring-2 ring-rose-100') }}"></div>

                        <!-- Date Floating Banner -->
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-sm sm:text-base font-black text-slate-900 tracking-tight flex items-center gap-2">
                                <span>📅</span>
                                <span>{{ $dateObj->format('l, F j, Y') }}</span>
                            </span>
                            @if($isToday)
                                <span class="px-2.5 py-0.5 rounded-full bg-[#c3122e] text-white text-[10px] font-black uppercase tracking-wider shadow-2xs">Today</span>
                            @elseif($isPast)
                                <span class="text-xs text-slate-400 font-semibold">({{ $dateObj->diffForHumans() }})</span>
                            @else
                                <span class="text-xs text-rose-600 font-semibold">({{ $dateObj->diffForHumans() }})</span>
                            @endif
                            <span class="text-xs font-bold text-slate-400">&bull; {{ count($eventsOnDate) }} deliverable{{ count($eventsOnDate) > 1 ? 's' : '' }}</span>
                        </div>
                    </div>

                    <!-- Card-less Execution Stream Rows -->
                    <div class="space-y-1 pt-1">
                        @foreach($eventsOnDate as $evt)
                            @php
                                $isDeadline = ($evt['type'] === 'project_deadline');
                                $isMilestone = ($evt['type'] === 'milestone');
                                $isCompleted = ($evt['status'] === 'completed');

                                $dotColor = match(true) {
                                    $isDeadline => 'bg-rose-500 ring-rose-200',
                                    $isMilestone => 'bg-purple-600 ring-purple-200',
                                    $isCompleted => 'bg-emerald-500 ring-emerald-200',
                                    $evt['status'] === 'in_progress' => 'bg-blue-500 ring-blue-200',
                                    default => 'bg-slate-400 ring-slate-200',
                                };

                                $typeBadge = match(true) {
                                    $isDeadline => ['🎯 Deadline', 'bg-rose-50 text-rose-800 border-rose-200'],
                                    $isMilestone => ['⭐ Milestone', 'bg-purple-50 text-purple-800 border-purple-200'],
                                    default => ['📋 Task', 'bg-slate-100 text-slate-700 border-slate-200'],
                                };

                                $priorityBadge = match($evt['priority']) {
                                    'critical', 'urgent' => ['🔥 Critical', 'text-rose-700 bg-rose-50 border-rose-200'],
                                    'high' => ['⚡ High', 'text-amber-800 bg-amber-50 border-amber-200'],
                                    'medium' => ['🔹 Medium', 'text-blue-700 bg-blue-50 border-blue-200'],
                                    default => ['▫️ Low', 'text-slate-600 bg-slate-50 border-slate-200'],
                                };
                            @endphp

                            <div class="group flex flex-col lg:flex-row lg:items-center justify-between gap-3 p-3.5 rounded-2xl hover:bg-slate-50/90 transition-all border-b border-slate-100 last:border-b-0 hover:shadow-2xs">
                                <!-- Left: Time Slot + Status Dot + Title Details -->
                                <div class="flex items-start sm:items-center gap-3 min-w-0 flex-1">
                                    <!-- Status Dot Indicator -->
                                    <span class="w-3 h-3 rounded-full flex-shrink-0 mt-1 sm:mt-0 ring-4 {{ $dotColor }}"></span>

                                    <!-- Time Slot Pill -->
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-mono font-bold text-slate-700 bg-slate-100/90 border border-slate-200/80 shadow-2xs">
                                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>{{ $evt['time_range'] ?: 'All Day' }}</span>
                                        </span>
                                    </div>

                                    <!-- Type & WBS Code Pill -->
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        <span class="px-2 py-0.5 rounded-lg text-[10px] font-black border {{ $typeBadge[1] }}">
                                            {{ $typeBadge[0] }}
                                        </span>
                                        @if($evt['code'])
                                            <span class="font-mono text-[10px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200 px-1.5 py-0.5 rounded-md">
                                                {{ $evt['code'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Deliverable Title & Project Context -->
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3
                                                wire:click="showEventDetails({{ json_encode($evt) }})"
                                                class="text-sm font-black text-slate-900 group-hover:text-[#c3122e] transition-colors cursor-pointer truncate max-w-md"
                                                title="{{ $evt['title'] }}"
                                            >
                                                {{ $evt['title'] }}
                                            </h3>
                                            @if($evt['is_my_task'])
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                    Assigned to You
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mt-0.5 truncate">
                                            <a href="{{ route('projects.show', $evt['project_id']) }}" class="text-slate-700 hover:text-[#c3122e] hover:underline flex items-center gap-1 font-bold">
                                                <span>📁</span>
                                                <span class="truncate">{{ $evt['project'] }}</span>
                                            </a>
                                            @if($evt['subsidiary'])
                                                <span class="text-slate-300">&bull;</span>
                                                <span class="text-[11px] text-slate-400 font-medium truncate">{{ $evt['subsidiary'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Priority + Assignee + Inline Status Dropdown + Actions -->
                                <div class="flex items-center gap-3 flex-wrap justify-between lg:justify-end pl-6 lg:pl-0">
                                    <!-- Priority Pill -->
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black border {{ $priorityBadge[1] }}">
                                        {{ $priorityBadge[0] }}
                                    </span>

                                    <!-- Assignee Avatar & Name -->
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-lg bg-rose-50 border border-rose-200 text-[#c3122e] text-[10px] font-black flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($evt['assigned_user'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 truncate max-w-28">{{ $evt['assigned_user'] }}</span>
                                    </div>

                                    <!-- Inline Status Dropdown -->
                                    @if($evt['type'] === 'task')
                                        <select
                                            wire:change="updateTaskStatusFromCalendar({{ $evt['id'] }}, $event.target.value)"
                                            class="text-xs font-black rounded-xl px-2.5 py-1 border border-slate-200 bg-white text-slate-800 focus:outline-none focus:ring-1 focus:ring-[#c3122e] cursor-pointer shadow-2xs"
                                        >
                                            @foreach(\App\Enums\WbsStatus::cases() as $st)
                                                <option value="{{ $st->value }}" @selected($evt['status'] === $st->value)>
                                                    {{ $st->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold border {{ $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                            {{ $evt['status_label'] }}
                                        </span>
                                    @endif

                                    <!-- Quick Details Button -->
                                    <div class="flex items-center gap-1">
                                        <button
                                            wire:click="showEventDetails({{ json_encode($evt) }})"
                                            type="button"
                                            class="px-2.5 py-1 rounded-xl text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer shadow-2xs"
                                        >
                                            Details
                                        </button>
                                        <a
                                            href="{{ route('projects.show', $evt['project_id']) }}"
                                            class="p-1.5 rounded-xl text-slate-400 hover:text-[#c3122e] hover:bg-rose-50 transition-colors"
                                            title="Open in Project Workspace"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-12 text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-[#c3122e] mx-auto text-2xl shadow-2xs">
                        📅
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">No scheduled deliverables found</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                            There are no deliverables matching your selected filters for {{ $currentDate->format('F Y') }}.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODALS: EVENT DETAILS & CREATE EVENT
         ═══════════════════════════════════════════════════════════════ -->

    <!-- Event Details Modal -->
    <div x-data="{ open: @entangle('showEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none" x-cloak>
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showEventModal = false"></div>
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg p-6 sm:p-8 z-10 space-y-5">
            @if($selectedEvent)
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black font-mono border uppercase tracking-wider {{ $selectedEvent['type'] === 'project_deadline' ? 'bg-rose-50 text-rose-700 border-rose-200' : ($selectedEvent['type'] === 'milestone' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                            {{ strtoupper(str_replace('_', ' ', $selectedEvent['type'])) }}
                        </span>
                        @if($selectedEvent['code'])
                            <span class="font-mono text-xs font-bold text-[#c3122e] bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                {{ $selectedEvent['code'] }}
                            </span>
                        @endif
                    </div>
                    <button type="button" @click="open = false; $wire.showEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div>
                    <h3 class="text-lg font-black text-slate-900 leading-snug">{{ $selectedEvent['title'] }}</h3>
                    @if(!empty($selectedEvent['description']))
                        <p class="text-xs text-slate-500 mt-2 bg-slate-50 p-3.5 rounded-2xl border border-slate-100 leading-relaxed">
                            {{ $selectedEvent['description'] }}
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3.5 p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Project</span>
                        <span class="font-bold text-slate-900 block mt-0.5 truncate">{{ $selectedEvent['project'] }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Target Date</span>
                        <span class="font-mono font-bold text-slate-900 block mt-0.5">{{ $selectedEvent['date'] }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Time Range</span>
                        <span class="font-mono font-bold text-slate-700 block mt-0.5">{{ $selectedEvent['time_range'] ?: 'All Day' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Priority</span>
                        <span class="capitalize font-bold text-[#c3122e] block mt-0.5">{{ $selectedEvent['priority'] }}</span>
                    </div>

                    <div class="col-span-2 pt-2 border-t border-slate-200/60">
                        <span class="text-slate-400 font-bold block text-[10px] uppercase">Assignee</span>
                        <span class="font-bold text-slate-900 block mt-0.5">{{ $selectedEvent['assigned_user'] }}</span>
                    </div>

                    @if(isset($selectedEvent['id']) && $selectedEvent['type'] === 'task')
                        <div class="col-span-2 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-800">Update Status:</span>
                            <select
                                wire:change="updateTaskStatusFromCalendar({{ $selectedEvent['id'] }}, $event.target.value)"
                                class="text-xs font-black rounded-xl px-3 py-1.5 border border-slate-300 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#c3122e] cursor-pointer"
                            >
                                @foreach(\App\Enums\WbsStatus::cases() as $st)
                                    <option value="{{ $st->value }}" @selected(($selectedEvent['status'] ?? 'not_started') === $st->value)>
                                        {{ $st->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showEventModal = false" class="btn-secondary text-xs cursor-pointer">Close</button>
                    @if(isset($selectedEvent['project_id']))
                        <a href="{{ route('projects.show', $selectedEvent['project_id']) }}" class="btn-primary text-xs flex items-center gap-1.5 shadow-md">
                            <span>Open Project Workspace</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Create Event / Task Modal -->
    <div x-data="{ open: @entangle('showCreateEventModal') }" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none" x-cloak>
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" @click="open = false; $wire.showCreateEventModal = false"></div>
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg p-6 sm:p-8 z-10 space-y-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Schedule Calendar Deliverable</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Add a milestone or task to the project schedule</p>
                </div>
                <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="createEvent" class="space-y-4 text-xs">
                <div>
                    <label class="form-label font-bold">Project</label>
                    <select wire:model="newEventProject" class="form-select text-xs rounded-xl">
                        @foreach($projectsList as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                        @endforeach
                    </select>
                    @error('newEventProject') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="form-label font-bold">Title</label>
                    <input type="text" wire:model="newEventTitle" placeholder="e.g. Stakeholder Demo or Sprint Review" class="form-input text-xs rounded-xl">
                    @error('newEventTitle') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label font-bold">Deliverable Type</label>
                        <select wire:model="newEventType" class="form-select text-xs rounded-xl">
                            <option value="task">Task</option>
                            <option value="milestone">Milestone</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label font-bold">Priority</label>
                        <select wire:model="newEventPriority" class="form-select text-xs rounded-xl">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="form-label font-bold">Date</label>
                        <input type="date" wire:model="newEventDate" class="form-input text-xs rounded-xl">
                        @error('newEventDate') <span class="text-rose-600 text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="form-label font-bold">Start Time</label>
                        <input type="time" wire:model="newEventStartTime" class="form-input text-xs rounded-xl">
                    </div>

                    <div>
                        <label class="form-label font-bold">End Time</label>
                        <input type="time" wire:model="newEventEndTime" class="form-input text-xs rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="form-label font-bold">Assignee (Optional)</label>
                    <select wire:model="newEventAssignedUser" class="form-select text-xs rounded-xl">
                        <option value="">Unassigned</option>
                        @foreach($assignableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showCreateEventModal = false" class="btn-secondary text-xs cursor-pointer">Cancel</button>
                    <button type="submit" class="btn-primary text-xs shadow-md">Schedule Deliverable</button>
                </div>
            </form>
        </div>
    </div>
</div>
