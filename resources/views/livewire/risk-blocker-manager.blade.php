<div class="space-y-6 sm:space-y-8 pb-12">
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (RISKS & BLOCKERS HUB)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-xl p-5 sm:p-6 lg:p-7 text-white mb-5" style="background: linear-gradient(135deg, #18060c 0%, #2e0915 50%, #18060c 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-1/2 pointer-events-none opacity-20 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 50%); mask-image: linear-gradient(to right, transparent 0%, black 50%);">
        </div>

        <!-- Gold Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-25 hidden md:block">
            <svg viewBox="0 0 1200 300" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 400 0 C 520 120 580 200 750 300" stroke="#f59e0b" stroke-width="2" opacity="0.7" />
                <path d="M 420 0 C 540 120 600 200 770 300" stroke="#c3122e" stroke-width="1.5" opacity="0.4" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left Side: 3D Warning Shield Icon + Title + Meta -->
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-xl flex-shrink-0 border border-white/25 ring-4 ring-amber-500/20 flex items-center justify-center p-2.5" style="background: linear-gradient(135deg, #f59e0b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-7 h-7 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Risks &amp; Blockers Hub
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black text-amber-200 border border-amber-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(245, 158, 11, 0.3);">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            <span>{{ $openRisksCount }} Active Risks · {{ $openBlockersCount }} Open Blockers</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 mt-1 flex-wrap">
                        <span class="text-rose-200 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('D, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Enterprise risk registry, mitigation tracking, and escalation resolutions</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Primary Action -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                <button
                    wire:click="openAddRiskModal()"
                    class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer active:scale-95"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.25);"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Log Project Risk</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. KPI SUMMARY METRICS -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-5">
        <!-- Total Risks Card -->
        <div class="p-3 sm:p-5 bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[9.5px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Project Risks</p>
                <div class="flex items-baseline gap-1 sm:gap-2 mt-0.5 sm:mt-1.5">
                    <span class="text-lg sm:text-2xl font-black text-slate-900 font-mono">{{ $totalRisksCount }}</span>
                    <span class="text-[10px] sm:text-xs text-slate-500 font-medium">({{ $openRisksCount }} Active)</span>
                </div>
                <p class="hidden sm:block text-[10px] text-slate-400 mt-1">Across {{ $projects->count() }} projects</p>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- High Threat Risks Card -->
        <div class="p-3 sm:p-5 bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[9.5px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">High Threats</p>
                <div class="flex items-baseline gap-1 sm:gap-2 mt-0.5 sm:mt-1.5">
                    <span class="text-lg sm:text-2xl font-black text-rose-600 font-mono">{{ $criticalHighRiskCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-rose-500">Focus</span>
                </div>
                <p class="hidden sm:block text-[10px] text-slate-400 mt-1">Impact score &ge; 6 / 12</p>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>

        <!-- Active Task Blockers Card -->
        <div class="p-3 sm:p-5 bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[9.5px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Task Blockers</p>
                <div class="flex items-baseline gap-1 sm:gap-2 mt-0.5 sm:mt-1.5">
                    <span class="text-lg sm:text-2xl font-black {{ $openBlockersCount > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} font-mono">{{ $openBlockersCount }}</span>
                    <span class="text-[10px] sm:text-xs font-extrabold text-amber-600">Pending</span>
                </div>
                <p class="hidden sm:block text-[10px] text-slate-400 mt-1">Execution impediments</p>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-red-50 border border-red-200 text-[#c3122e] flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>

        <!-- Resolved Blockers Rate Card -->
        <div class="p-3 sm:p-5 bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[9.5px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Resolved</p>
                <div class="flex items-baseline gap-1 sm:gap-2 mt-0.5 sm:mt-1.5">
                    <span class="text-lg sm:text-2xl font-black text-emerald-600 font-mono">{{ $resolvedBlockersCount }}</span>
                    <span class="text-[10px] sm:text-xs font-bold text-emerald-600">Cleared</span>
                </div>
                <p class="hidden sm:block text-[10px] text-slate-400 mt-1">Task execution unblocked</p>
            </div>
            <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- 3. CONTROLS, SEARCH & TAB SWITCHER -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-5 shadow-xs space-y-4">
        <!-- Top Row: Tab Switcher (Full Width & Uncramped) + Action Stats -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Segmented Tab Navigation -->
            <div class="inline-flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100/90 border border-slate-200/60 shadow-inner">
                <button
                    type="button"
                    wire:click="$set('activeTab', 'risks')"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'risks' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80 scale-[1.02]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/60' }}"
                >
                    <div class="w-2 h-2 rounded-full {{ $activeTab === 'risks' ? 'bg-amber-500 ring-2 ring-amber-400/40' : 'bg-slate-400' }}"></div>
                    <span>Project Risks Register</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $activeTab === 'risks' ? 'bg-amber-50 text-amber-800 border border-amber-200/80' : 'bg-slate-200 text-slate-600' }}">{{ $risks->count() }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('activeTab', 'blockers')"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'blockers' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80 scale-[1.02]' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-200/60' }}"
                >
                    <div class="w-2 h-2 rounded-full {{ $activeTab === 'blockers' ? 'bg-[#c3122e] ring-2 ring-rose-400/40' : 'bg-slate-400' }}"></div>
                    <span>Active Task Blockers</span>
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $activeTab === 'blockers' ? 'bg-rose-50 text-[#c3122e] border border-rose-200/80' : 'bg-slate-200 text-slate-600' }}">{{ $blockers->count() }}</span>
                </button>
            </div>

            <!-- Quick Summary & Filter Reset Action -->
            <div class="text-xs font-bold text-slate-500 flex items-center gap-2.5">
                <span>Showing: <strong class="text-slate-800">{{ $activeTab === 'risks' ? $risks->count() . ' Risks' : $blockers->count() . ' Blockers' }}</strong></span>
                @if($searchQuery || $selectedProjectId || ($activeTab === 'risks' && ($selectedCategory || $selectedStatus !== 'all')) || ($activeTab === 'blockers' && $selectedSeverity !== 'all'))
                    <button wire:click="$set('searchQuery', ''); $set('selectedProjectId', null); $set('selectedCategory', ''); $set('selectedStatus', 'all'); $set('selectedSeverity', 'all');" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset Filters</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Bottom Row: Clean Filter Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Search Box -->
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search by title, keyword, code..."
                    class="w-full text-xs font-medium py-2.5 pl-9 pr-3 rounded-xl border border-slate-200 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e] bg-slate-50/50"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Project Selector Dropdown -->
            <div>
                <select
                    wire:model.live="selectedProjectId"
                    class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:border-[#c3122e]"
                >
                    <option value="">🌐 All Accessible Projects</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                    @endforeach
                </select>
            </div>

            @if($activeTab === 'risks')
                <!-- Category Dropdown -->
                <div>
                    <select
                        wire:model.live="selectedCategory"
                        class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:border-[#c3122e]"
                    >
                        <option value="">All Risk Categories</option>
                        <option value="Technical">Technical</option>
                        <option value="Financial">Financial</option>
                        <option value="Schedule">Schedule</option>
                        <option value="Resource">Resource</option>
                        <option value="External">External Vendor</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select
                        wire:model.live="selectedStatus"
                        class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:border-[#c3122e]"
                    >
                        <option value="all">All Risk Statuses</option>
                        <option value="open">Open (Active)</option>
                        <option value="monitoring">Monitoring</option>
                        <option value="mitigated">Mitigated</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            @else
                <!-- Severity Filter for Blockers -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <select
                        wire:model.live="selectedSeverity"
                        class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:border-[#c3122e]"
                    >
                        <option value="all">All Blocker Severities</option>
                        <option value="critical">Critical Severity</option>
                        <option value="high">High Severity</option>
                        <option value="medium">Medium Severity</option>
                        <option value="low">Low Severity</option>
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- 5. MAIN CONTENT TAB CONTENT -->
    @if($activeTab === 'risks')
        <!-- RISKS REGISTER LIST VIEW -->
        <div class="space-y-4">
            @forelse($risks as $r)
                @php
                    $riskStatusColors = [
                        'open' => 'bg-rose-50 text-rose-900 border-rose-300 font-extrabold',
                        'monitoring' => 'bg-amber-50 text-amber-900 border-amber-300 font-extrabold',
                        'mitigated' => 'bg-emerald-50 text-emerald-900 border-emerald-300 font-extrabold',
                        'closed' => 'bg-slate-100 text-slate-600 border-slate-300 font-bold',
                    ];

                    $scoreColor = 'bg-emerald-100 text-emerald-900 border-emerald-300';
                    if ($r->risk_score >= 8) {
                        $scoreColor = 'bg-red-100 text-red-900 border-red-300 font-black';
                    } elseif ($r->risk_score >= 4) {
                        $scoreColor = 'bg-amber-100 text-amber-900 border-amber-300 font-black';
                    }
                @endphp

                <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-md transition-all space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                        <div class="flex-1 space-y-2">
                            <!-- Badges Header Row -->
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white">
                                    {{ $r->project->name ?? 'General Project' }}
                                </span>

                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                    Category: {{ $r->category }}
                                </span>

                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-rose-50 text-[#c3122e] border border-rose-200">
                                    @if($r->wbsItem)
                                        📍 Scope Task: {{ $r->wbsItem->title }} (Code: {{ $r->wbsItem->wbs_code }})
                                    @else
                                        🌐 General Project Scope
                                    @endif
                                </span>

                                <span class="px-2.5 py-1 rounded-xl text-[10px] uppercase font-extrabold border {{ $scoreColor }}">
                                    Score: {{ $r->risk_score }} / 12 (Prob: {{ strtoupper($r->probability) }} &bull; Imp: {{ strtoupper($r->impact) }})
                                </span>
                            </div>

                            <h3 class="font-black text-slate-900 text-base pt-1">{{ $r->title }}</h3>
                            <p class="text-xs text-slate-600 font-medium leading-relaxed">{{ $r->description }}</p>
                        </div>

                        <!-- Right Actions & Status Changer -->
                        <div class="flex items-center gap-3 flex-shrink-0 self-end lg:self-start">
                            <select
                                wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                class="text-xs rounded-full px-3.5 py-1.5 border cursor-pointer {{ $riskStatusColors[$r->status] ?? 'bg-slate-100' }}"
                            >
                                <option value="open" @selected($r->status === 'open')>Open</option>
                                <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                <option value="closed" @selected($r->status === 'closed')>Closed</option>
                            </select>

                            <button
                                wire:click="openEditRiskModal({{ $r->id }})"
                                class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer"
                                title="Edit Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <button
                                wire:click="deleteRisk({{ $r->id }})"
                                wire:confirm="Are you sure you want to delete this risk record?"
                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                title="Delete Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mitigation & Contingency Plans Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-slate-100 text-xs">
                        <div class="p-3 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900 space-y-1">
                            <div class="font-extrabold flex items-center gap-1.5 text-emerald-800">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Preventative Mitigation Strategy</span>
                            </div>
                            <p class="font-medium text-emerald-950 leading-normal">{{ $r->mitigation_plan ?: 'No explicit mitigation strategy outlined.' }}</p>
                        </div>

                        <div class="p-3 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-900 space-y-1">
                            <div class="font-extrabold flex items-center gap-1.5 text-amber-800">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Contingency Backup Plan</span>
                            </div>
                            <p class="font-medium text-amber-950 leading-normal">{{ $r->contingency_plan ?: 'No fallback contingency plan specified.' }}</p>
                        </div>
                    </div>

                    <!-- Footer Details: Risk Owner & Timestamp -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1 font-medium">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9px]">
                                {{ strtoupper(substr($r->owner->name ?? 'U', 0, 1)) }}
                            </div>
                            <span>Risk Owner: <strong>{{ $r->owner->name ?? 'Project Manager' }}</strong></span>
                        </div>

                        <span>Recorded: {{ $r->created_at->format('M d, Y') }} ({{ $r->created_at->diffForHumans() }})</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">No Project Risks Recorded</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto font-medium mt-1">No active risks match your current filter criteria. You can log a new potential threat or reset filters.</p>
                    </div>
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            wire:click="openAddRiskModal()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all cursor-pointer active:scale-95"
                            style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Log Project Risk</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <!-- TASK BLOCKERS LIST VIEW -->
        <div class="space-y-4">
            @forelse($blockers as $b)
                @php
                    $sevColors = [
                        'low' => 'bg-slate-100 text-slate-700 border-slate-300',
                        'medium' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
                        'high' => 'bg-rose-50 text-rose-800 border-rose-200 font-extrabold',
                        'critical' => 'bg-red-100 text-red-900 border-red-300 font-black',
                    ];
                @endphp

                <div class="bg-white rounded-3xl border transition-all p-6 shadow-xs {{ $b->status === 'resolved' ? 'bg-slate-50/60 border-slate-200 opacity-80' : 'border-rose-200/90 hover:shadow-md' }} space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white">
                                {{ $b->wbsItem->project->name ?? 'Project' }}
                            </span>

                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-rose-50 text-[#c3122e] border border-rose-200">
                                📍 Task: {{ $b->wbsItem->title ?? 'General Scope' }} (Code: {{ $b->wbsItem->wbs_code ?? '-' }})
                            </span>

                            <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider border {{ $sevColors[$b->severity] ?? 'bg-slate-100' }}">
                                {{ $b->severity }} Severity
                            </span>
                        </div>

                        <div>
                            @if($b->status === 'resolved')
                                <span class="px-3.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Resolved
                                </span>
                            @else
                                <button
                                    wire:click="openResolveBlockerModal({{ $b->id }})"
                                    class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all cursor-pointer flex items-center gap-1.5"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Resolve Blocker</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs font-semibold text-slate-800 leading-relaxed">{{ $b->description }}</p>

                    @if($b->status === 'resolved' && $b->resolution)
                        <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-950 space-y-1">
                            <div class="font-extrabold text-emerald-800 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Resolution Details (by {{ $b->resolver->name ?? 'Project Manager' }})
                            </div>
                            <p class="font-medium italic leading-relaxed">"{{ $b->resolution }}"</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-[10px] text-slate-500 font-medium pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9px]">
                                {{ strtoupper(substr($b->reporter->name ?? 'U', 0, 1)) }}
                            </div>
                            <span>Reported by <strong>{{ $b->reporter->name ?? 'Team Member' }}</strong> &bull; {{ $b->created_at->diffForHumans() }}</span>
                        </div>

                        @if($b->resolved_at)
                            <span>Resolved {{ $b->resolved_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center mx-auto shadow-xs">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">No Active Task Blockers</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto font-medium mt-1">All project deliverables and scope tasks are currently executing smoothly without active blockers.</p>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- ================= MODALS ================= -->

    <!-- 1. ADD RISK MODAL -->
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-md" wire:click="$set('showAddRiskModal', false)"></div>

            <div class="relative bg-white/95 backdrop-blur-xl border border-white/20 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-5 z-10 overflow-y-auto max-h-[90vh]">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Log New Project Risk</h3>
                            <p class="text-xs text-slate-500 font-medium">Identify threat, assess probability x impact, and define mitigation steps</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddRiskModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="addRisk" class="space-y-4">
                    <!-- Project Selector -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Target Project <span class="text-rose-500">*</span></label>
                        <select wire:model.live="riskProjectId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                            <option value="">Select Project...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                            @endforeach
                        </select>
                        @error('riskProjectId') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Payment Gateway Integration Delay" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                            @error('riskTitle') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Category</label>
                            <select wire:model="riskCategory" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <!-- WBS Item Scope Linker & Owner -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">📍 WBS Scope Task (Optional)</label>
                            <select wire:model="riskWbsItemId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e] bg-slate-50">
                                <option value="">🌐 [General Project Scope]</option>
                                @foreach($modalWbsItems as $wbs)
                                    <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Owner</label>
                            <select wire:model="riskOwnerId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Probability & Impact Selection -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                        <label class="block text-xs font-extrabold text-slate-800">Probability & Impact Assessment</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">Probability</span>
                                <select wire:model.live="riskProbability" class="w-full text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                </select>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">Impact</span>
                                <select wire:model.live="riskImpact" class="w-full text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                    <option value="critical">Critical (4)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Description & Threat Analysis <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" placeholder="Detailed breakdown of potential risk factors, root causes, and project impacts..." class="w-full text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                        @error('riskDescription') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Mitigation Strategy & Contingency Plan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Preventative Mitigation Strategy</label>
                            <input type="text" wire:model="riskMitigation" placeholder="Steps taken to prevent risk occurring..." class="w-full text-xs py-2.5 rounded-xl border-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Contingency Plan</label>
                            <input type="text" wire:model="riskContingency" placeholder="Action plan if risk materializes..." class="w-full text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showAddRiskModal', false)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md cursor-pointer">Log Risk Record</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- 2. EDIT RISK MODAL -->
    @if($showEditRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-md" wire:click="$set('showEditRiskModal', false)"></div>

            <div class="relative bg-white/95 backdrop-blur-xl border border-white/20 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-5 z-10 overflow-y-auto max-h-[90vh]">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-medium">Update assessment ratings, owner, and mitigation plans</p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditRiskModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="updateRisk" class="space-y-4">
                    <!-- Project Selector -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Target Project <span class="text-rose-500">*</span></label>
                        <select wire:model.live="riskProjectId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Category</label>
                            <select wire:model="riskCategory" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">📍 WBS Scope Task (Optional)</label>
                            <select wire:model="riskWbsItemId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e] bg-slate-50">
                                <option value="">🌐 [General Project Scope]</option>
                                @foreach($modalWbsItems as $wbs)
                                    <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Owner</label>
                            <select wire:model="riskOwnerId" class="w-full text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                        <label class="block text-xs font-extrabold text-slate-800">Probability & Impact Assessment</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">Probability</span>
                                <select wire:model.live="riskProbability" class="w-full text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                </select>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">Impact</span>
                                <select wire:model.live="riskImpact" class="w-full text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                    <option value="critical">Critical (4)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Risk Description & Threat Analysis <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" class="w-full text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Preventative Mitigation Strategy</label>
                            <input type="text" wire:model="riskMitigation" class="w-full text-xs py-2.5 rounded-xl border-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-800 mb-1">Contingency Plan</label>
                            <input type="text" wire:model="riskContingency" class="w-full text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showEditRiskModal', false)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-slate-900 hover:bg-black shadow-md cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- 3. RESOLVE BLOCKER MODAL -->
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/40 backdrop-blur-md" wire:click="$set('showResolveBlockerModal', false)"></div>

            <div class="relative bg-white/95 backdrop-blur-xl border border-white/20 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Resolve Task Blocker</h3>
                            <p class="text-xs text-slate-500 font-medium">Provide resolution notes to clear this execution impediment</p>
                        </div>
                    </div>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveBlockerResolution" class="space-y-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Resolution Summary <span class="text-rose-500">*</span></label>
                        <textarea wire:model="blockerResolutionInput" rows="4" placeholder="Detail the resolution steps taken to unblock task execution..." class="w-full text-xs leading-relaxed rounded-xl p-3 border-slate-200 focus:border-emerald-600"></textarea>
                        @error('blockerResolutionInput') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showResolveBlockerModal', false)" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md cursor-pointer">Mark Blocker Resolved</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
