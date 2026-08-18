<div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6" x-data="{ viewMode: 'grid' }">

    <!-- Flash Message Notification -->
    @if(session()->has('message'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs animate-fadeIn">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold">{{ session('message') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold p-1">✕</button>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (PROJECT TEMPLATES)
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
            <!-- Left Side: 3D Blueprint Icon + Title + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Project Blueprint Templates
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>📋</span>
                            <span>Standardized WBS Models</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Enterprise Blueprint Repository</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Build, standardize, and instantiate enterprise Work Breakdown Structures across subsidiaries</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Primary Action -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                @if(auth()->user()?->isSuperAdmin())
                    <button
                        wire:click="$set('showModal', true)"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Create New Template</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Statistics Console (4 Clean Responsive Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Active Templates -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 flex items-center justify-between shadow-xs hover:border-slate-300 transition-all">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Active Templates</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight leading-none">{{ $totalTemplates }}</div>
                <div class="text-[10px] font-bold text-emerald-600">Master Workflows</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
        </div>

        <!-- Card 2: Root WBS Phases -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 flex items-center justify-between shadow-xs hover:border-slate-300 transition-all">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Root WBS Phases</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight leading-none">{{ $totalPhases }}</div>
                <div class="text-[10px] font-bold text-indigo-600">Stage Gate Milestones</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>

        <!-- Card 3: Configured Deliverables -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-4.5 flex items-center justify-between shadow-xs hover:border-slate-300 transition-all">
            <div class="space-y-1">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Configured Deliverables</span>
                <div class="text-2xl font-black text-slate-900 tracking-tight leading-none">{{ $totalTasks }}</div>
                <div class="text-[10px] font-bold text-emerald-600">Avg {{ $avgTasks }} Tasks / Template</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
        </div>

        <!-- Card 4: WBS Engine Banner -->
        <div class="bg-gradient-to-br from-amber-50/80 to-white border border-amber-200/70 rounded-2xl p-4.5 flex items-center justify-between shadow-xs">
            <div class="space-y-0.5 min-w-0 pr-1">
                <span class="text-[10px] font-extrabold text-amber-700 uppercase tracking-wider block">WBS Engine</span>
                <div class="text-xs font-black text-slate-900 tracking-tight">Auto Gantt Launch</div>
                <div class="text-[10px] font-medium text-slate-500 truncate">Sequential date alignment</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-100/80 border border-amber-200 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Search, Filter & Controls Toolbar -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-3.5">
        <!-- Search Input -->
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                   wire:model.live.debounce.250ms="search" 
                   placeholder="Search templates..." 
                   class="w-full pl-9 pr-8 py-2 text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all placeholder:text-slate-400">
            @if($search)
                <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-xs font-bold">
                    ✕
                </button>
            @endif
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3 w-full sm:w-auto">
            <!-- Sort Selector -->
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider hidden sm:inline">Sort:</span>
                <select wire:model.live="sortBy" class="py-1.5 px-3 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e]">
                    <option value="latest">Latest Created</option>
                    <option value="name_asc">Name (A - Z)</option>
                    <option value="tasks_desc">Most Deliverables</option>
                    <option value="oldest">Oldest First</option>
                </select>
            </div>

            <!-- View Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60">
                <button @click="viewMode = 'grid'" 
                        :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="p-1.5 rounded-lg text-xs transition-all flex items-center gap-1"
                        title="Grid View">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
                <button @click="viewMode = 'list'" 
                        :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="p-1.5 rounded-lg text-xs transition-all flex items-center gap-1"
                        title="List View">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Counter Badge -->
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200/60">
                {{ count($templates) }} {{ Str::plural('Template', count($templates)) }}
            </span>
        </div>
    </div>

    <!-- Template Cards Grid (Grid View) -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
            <div class="bg-white border border-slate-200/90 rounded-2xl p-5 hover:border-[#c3122e]/40 hover:shadow-lg transition-all duration-200 group flex flex-col justify-between relative overflow-hidden shadow-2xs min-h-[350px]">
                <!-- Top Header & Quick Actions -->
                <div class="space-y-3.5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#c3122e] to-[#9e0c23] text-white flex items-center justify-center font-black text-sm shadow-xs flex-shrink-0">
                                {{ strtoupper(substr($template->name, 0, 2)) }}
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-100">
                                    WBS Master
                                </span>
                            </div>
                        </div>

                        <!-- Card Action Icons (PMO Admin Only) -->
                        @if(auth()->user()?->isSuperAdmin())
                            <div class="flex items-center gap-1 text-slate-400">
                                <button wire:click="duplicateTemplate({{ $template->id }})" 
                                        class="p-1.5 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer" 
                                        title="Duplicate Template">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                                <button wire:click="editTemplate({{ $template->id }})" 
                                        class="p-1.5 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" 
                                        title="Edit Details">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button wire:click="deleteTemplate({{ $template->id }})" 
                                        wire:confirm="Are you sure you want to delete this template?" 
                                        class="p-1.5 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" 
                                        title="Delete Template">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Title & Description -->
                    <div class="space-y-1">
                        <h3 class="text-base font-black text-slate-900 leading-snug group-hover:text-[#c3122e] transition-colors line-clamp-1" title="{{ $template->name }}">
                            {{ $template->name }}
                        </h3>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-medium">
                            {{ $template->description ?: 'No description provided. View WBS to inspect phase structure.' }}
                        </p>
                    </div>

                    <!-- Clean Metrics Strip -->
                    <div class="grid grid-cols-2 gap-2 py-2.5 px-3 bg-slate-50 rounded-xl border border-slate-100 text-xs mt-3">
                        <div class="flex items-center gap-2">
                            <div class="w-6.5 h-6.5 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider block">Phases</span>
                                <span class="text-[11px] font-black text-slate-800 truncate block">{{ $template->phases_count }} Root Gates</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6.5 h-6.5 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 font-bold">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-wider block">Deliverables</span>
                                <span class="text-[11px] font-black text-slate-800 truncate block">{{ $template->tasks_count }} Tasks</span>
                            </div>
                        </div>
                    </div>

                    <!-- Phase Structure Pills -->
                    <div class="space-y-1 mt-3">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Phase Breakdown</span>
                        @if($template->tasks->count() > 0)
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($template->tasks as $phase)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold text-slate-700 bg-slate-100 border border-slate-200/80 truncate max-w-[130px]" title="{{ $phase->name }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] mr-1.5 flex-shrink-0"></span>
                                        {{ $phase->name }}
                                    </span>
                                @endforeach
                                @if($template->phases_count > 3)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-semibold text-slate-400 bg-slate-50 border border-slate-200">
                                        +{{ $template->phases_count - 3 }} more
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-md inline-block border border-amber-100">⚡ Pending WBS structure setup</span>
                        @endif
                    </div>
                </div>

                <!-- Footer & Action Button (Pushed to bottom via mt-auto) -->
                <div class="pt-3.5 mt-4 border-t border-slate-100 flex items-center justify-between gap-3 text-xs">
                    <span class="text-slate-400 font-medium text-[11px] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $template->created_at->diffForHumans() }}
                    </span>

                    <a href="{{ route('templates.manage', $template->id) }}" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#c3122e] hover:text-white border border-[#faeaea] transition-all shadow-2xs group/btn">
                        <span>{{ auth()->user()?->isSuperAdmin() ? 'Configure WBS' : 'View WBS' }}</span>
                        <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white rounded-2xl border border-dashed border-slate-200 shadow-2xs">
                <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center text-[#c3122e] mb-3 border border-rose-100">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1">No Templates Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4 leading-relaxed">
                    @if($search)
                        No templates matching "{{ $search }}". Try clearing search filter.
                    @else
                        Project templates define standard execution workflows across the group.
                    @endif
                </p>
                @if(auth()->user()?->isSuperAdmin())
                    <button wire:click="$set('showModal', true)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-2 cursor-pointer">
                        + Add New Template
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Template Cards Container (List View with Horizontal Scroll Container) -->
    <div x-show="viewMode === 'list'" class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black uppercase text-slate-400 tracking-wider">
                        <th class="py-3.5 px-4">Template Name</th>
                        <th class="py-3.5 px-4">Description</th>
                        <th class="py-3.5 px-4 text-center">Phases</th>
                        <th class="py-3.5 px-4 text-center">Deliverables</th>
                        <th class="py-3.5 px-4">Created</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($templates as $template)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#c3122e] to-[#9e0c23] text-white flex items-center justify-center font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($template->name, 0, 2)) }}
                                    </div>
                                    <span>{{ $template->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate font-medium">
                                {{ $template->description ?: 'No description provided.' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-extrabold text-indigo-700 bg-indigo-50 border border-indigo-100">
                                    {{ $template->phases_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-extrabold text-emerald-700 bg-emerald-50 border border-emerald-100">
                                    {{ $template->tasks_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400 text-[11px] font-medium">
                                {{ $template->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('templates.manage', $template->id) }}" 
                                       class="px-2.5 py-1 rounded-lg text-xs font-bold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#c3122e] hover:text-white border border-[#faeaea] transition-all">
                                        {{ auth()->user()?->isSuperAdmin() ? 'Configure WBS' : 'View WBS' }}
                                    </a>
                                    @if(auth()->user()?->isSuperAdmin())
                                        <button wire:click="duplicateTemplate({{ $template->id }})" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg" title="Duplicate">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        </button>
                                        <button wire:click="editTemplate({{ $template->id }})" class="p-1.5 text-slate-400 hover:text-blue-600 rounded-lg" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Are you sure?" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 text-xs font-medium">No templates found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create / Edit Template Modal (PMO Admin Only) -->
    @if($showModal && auth()->user()?->isSuperAdmin())
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                <form wire:submit.prevent="createTemplate">
                    <div class="bg-white px-5 pt-5 pb-4 sm:p-6 sm:pb-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-xl bg-[#fdf4f4] sm:mx-0 text-[#c3122e] border border-[#faeaea]">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-extrabold text-slate-900" id="modal-title">
                                    {{ $editId ? 'Edit Template Info' : 'Create New Template' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Template Name <span class="text-[#c3122e]">*</span></label>
                                        <input type="text" wire:model="name" id="name" class="w-full px-3 py-2 text-xs font-semibold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e]" placeholder="e.g. IT Software Implementation Framework" required autofocus>
                                        @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                                        <textarea wire:model="description" id="description" rows="3" class="w-full px-3 py-2 text-xs font-medium text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] h-24" placeholder="Brief description of this template..."></textarea>
                                        @error('description') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100 gap-2">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-[#c3122e] text-xs font-bold text-white hover:bg-[#a00e24] focus:outline-none sm:w-auto transition-colors cursor-pointer">
                            {{ $editId ? 'Save Changes' : 'Create Template' }}
                        </button>
                        <button type="button" wire:click="$set('showModal', false); $set('editId', null)" class="mt-2 sm:mt-0 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-2 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 focus:outline-none sm:w-auto transition-colors cursor-pointer">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
