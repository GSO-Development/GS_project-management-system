<div class="space-y-6 sm:space-y-7 pb-12">
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (RISKS & BLOCKERS HUB)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Risks & Blockers Hub Banner" 
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
            <!-- Left Side: Shield Icon + Title + Meta -->
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-lg flex-shrink-0 border border-white/20 ring-4 ring-amber-500/20 flex items-center justify-center p-2.5" style="background: linear-gradient(135deg, #f59e0b 0%, #c3122e 65%, #800a1d 100%);">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Risks &amp; Blockers Hub
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black text-amber-200 border border-amber-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(245, 158, 11, 0.25);">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 {{ ($openRisksCount + $openBlockersCount) > 0 ? 'animate-pulse' : '' }}"></span>
                            <span>{{ $openRisksCount }} Active Risks · {{ $openBlockersCount }} Open Blockers</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 font-medium mt-1">
                        Enterprise risk matrix, mitigation tracking, and task blocker resolutions
                    </p>
                </div>
            </div>

            <!-- Right Side: Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0 self-start lg:self-center">
                <button
                    wire:click="openAddBlockerModal()"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-200 bg-white/10 hover:bg-white/15 border border-white/20 transition-all cursor-pointer active:scale-95 backdrop-blur-sm"
                >
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Report Blocker</span>
                </button>

                <button
                    wire:click="openAddRiskModal()"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4.5 py-2.5 rounded-xl text-xs font-black text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer active:scale-95"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.25);"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Log Project Risk</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI SUMMARY METRICS (1 Clean Responsive Row)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Active Project Risks -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Project Risks</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $totalRisksCount }}</span>
                    <span class="text-[11px] sm:text-xs font-semibold {{ $openRisksCount > 0 ? 'text-amber-600' : 'text-slate-400' }}">({{ $openRisksCount }} Active)</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Across {{ $projects->count() }} projects</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- High Threat Risks -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">High Threats</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-rose-600 font-mono">{{ $criticalHighRiskCount }}</span>
                    <span class="text-[11px] sm:text-xs font-bold text-rose-500">Critical / High</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Impact score &ge; 6 / 12</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 border border-rose-200/80 text-[#c3122e] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>

        <!-- Pending Task Blockers -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Task Blockers</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black {{ $openBlockersCount > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} font-mono">{{ $openBlockersCount }}</span>
                    <span class="text-[11px] sm:text-xs font-extrabold {{ $openBlockersCount > 0 ? 'text-rose-600' : 'text-slate-400' }}">Pending</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Execution impediments</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-red-50 border border-red-200/80 text-[#c3122e] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>

        <!-- Resolved Blockers -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Resolved</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-emerald-600 font-mono">{{ $resolvedBlockersCount }}</span>
                    <span class="text-[11px] sm:text-xs font-bold text-emerald-600">Cleared</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Unblocked deliverables</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CONTROLS, SEGMENTED TABS & FILTERS BAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-2xs space-y-4">
        <!-- Top Row: Tab Switcher & Active Count -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Segmented Pill Tabs -->
            <div class="inline-flex items-center gap-1.5 p-1 rounded-xl bg-slate-100/90 border border-slate-200/70">
                <button
                    type="button"
                    wire:click="$set('activeTab', 'risks')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-black transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'risks' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <div class="w-2 h-2 rounded-full {{ $activeTab === 'risks' ? 'bg-amber-500 ring-2 ring-amber-400/40' : 'bg-slate-300' }}"></div>
                    <span>Project Risks Register</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-black {{ $activeTab === 'risks' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-600' }}">{{ $risks->count() }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('activeTab', 'blockers')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-black transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'blockers' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <div class="w-2 h-2 rounded-full {{ $activeTab === 'blockers' ? 'bg-[#c3122e] ring-2 ring-rose-400/40' : 'bg-slate-300' }}"></div>
                    <span>Task Blockers</span>
                    <span class="px-1.5 py-0.2 rounded text-[10px] font-black {{ $activeTab === 'blockers' ? 'bg-rose-100 text-[#c3122e]' : 'bg-slate-200 text-slate-600' }}">{{ $blockers->count() }}</span>
                </button>
            </div>

            <!-- Active Filters Reset -->
            <div class="text-xs font-medium text-slate-500 flex items-center gap-3">
                <span>Showing: <strong class="text-slate-800">{{ $activeTab === 'risks' ? $risks->count() . ' Risks' : $blockers->count() . ' Blockers' }}</strong></span>
                @if($searchQuery || $selectedProjectId || ($activeTab === 'risks' && ($selectedCategory || $selectedStatus !== 'all')) || ($activeTab === 'blockers' && $selectedSeverity !== 'all'))
                    <button wire:click="$set('searchQuery', ''); $set('selectedProjectId', null); $set('selectedCategory', ''); $set('selectedStatus', 'all'); $set('selectedSeverity', 'all');" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset Filters</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Filter Controls Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
            <!-- Search Input -->
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Search by keyword, code, title..."
                    class="w-full text-xs font-medium py-2.5 pl-9 pr-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all text-slate-800"
                    style="border: 1px solid #cbd5e1;"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Project Selector Dropdown -->
            <div>
                <select
                    wire:model.live="selectedProjectId"
                    class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all text-slate-800"
                    style="border: 1px solid #cbd5e1;"
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
                        class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all text-slate-800"
                        style="border: 1px solid #cbd5e1;"
                    >
                        <option value="">All Risk Categories</option>
                        <option value="Technical">Technical</option>
                        <option value="Financial">Financial</option>
                        <option value="Schedule">Schedule</option>
                        <option value="Resource">Resource</option>
                        <option value="Operational">Operational</option>
                        <option value="External">External Vendor</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select
                        wire:model.live="selectedStatus"
                        class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all text-slate-800"
                        style="border: 1px solid #cbd5e1;"
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
                        class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all text-slate-800"
                        style="border: 1px solid #cbd5e1;"
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

    <!-- ═══════════════════════════════════════════════════════════════
         4. MAIN CONTENT (RISKS REGISTER OR BLOCKERS LIST)
         ═══════════════════════════════════════════════════════════════ -->
    @if($activeTab === 'risks')
        <!-- RISKS REGISTER CARDS -->
        <div class="space-y-4">
            @forelse($risks as $r)
                @php
                    $riskStatusColors = [
                        'open' => 'bg-rose-50 text-rose-800 border-rose-200 font-extrabold',
                        'monitoring' => 'bg-amber-50 text-amber-800 border-amber-200 font-extrabold',
                        'mitigated' => 'bg-emerald-50 text-emerald-800 border-emerald-200 font-extrabold',
                        'closed' => 'bg-slate-100 text-slate-600 border-slate-200 font-medium',
                    ];

                    $borderAccent = 'border-l-4 border-l-emerald-500';
                    $scoreColor = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                    $severityLabel = 'Low';

                    if ($r->risk_score >= 8) {
                        $borderAccent = 'border-l-4 border-l-[#c3122e]';
                        $scoreColor = 'bg-rose-50 text-[#c3122e] border-rose-200 font-black';
                        $severityLabel = 'Critical';
                    } elseif ($r->risk_score >= 6) {
                        $borderAccent = 'border-l-4 border-l-orange-500';
                        $scoreColor = 'bg-orange-50 text-orange-800 border-orange-200 font-black';
                        $severityLabel = 'High';
                    } elseif ($r->risk_score >= 4) {
                        $borderAccent = 'border-l-4 border-l-amber-500';
                        $scoreColor = 'bg-amber-50 text-amber-800 border-amber-200 font-bold';
                        $severityLabel = 'Medium';
                    }
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/90 {{ $borderAccent }} p-5 sm:p-6 shadow-2xs hover:shadow-md transition-all space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-3.5">
                        <div class="flex-1 space-y-2">
                            <!-- Badges Header -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-black uppercase tracking-wider bg-slate-900 text-white shadow-xs">
                                    {{ $r->project->name ?? 'Project' }}
                                </span>

                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    🏷️ {{ $r->category }}
                                </span>

                                @if($r->wbsItem)
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-rose-50 text-[#c3122e] border border-rose-100">
                                        📍 Task: {{ $r->wbsItem->title }} ({{ $r->wbsItem->wbs_code }})
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                        🌐 General Project Scope
                                    </span>
                                @endif

                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] uppercase font-bold border {{ $scoreColor }} shadow-2xs">
                                    Score: {{ $r->risk_score }}/12 &bull; {{ $severityLabel }} (Prob: {{ ucfirst($r->probability) }} &bull; Imp: {{ ucfirst($r->impact) }})
                                </span>
                            </div>

                            <h3 class="font-extrabold text-slate-900 text-base sm:text-lg pt-0.5">{{ $r->title }}</h3>
                            <p class="text-xs sm:text-[13px] text-slate-600 font-normal leading-relaxed">{{ $r->description }}</p>
                        </div>

                        <!-- Right Actions & Quick Status Changer -->
                        <div class="flex items-center gap-2 flex-shrink-0 self-start">
                            <select
                                wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                class="text-xs rounded-xl px-3 py-1.5 border cursor-pointer {{ $riskStatusColors[$r->status] ?? 'bg-slate-100' }} shadow-2xs"
                                style="border: 1px solid #cbd5e1;"
                            >
                                <option value="open" @selected($r->status === 'open')>Open</option>
                                <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                <option value="closed" @selected($r->status === 'closed')>Closed</option>
                            </select>

                            <button
                                wire:click="openEditRiskModal({{ $r->id }})"
                                class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors cursor-pointer border border-slate-200"
                                title="Edit Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <button
                                wire:click="deleteRisk({{ $r->id }})"
                                wire:confirm="Are you sure you want to remove this risk record?"
                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer border border-slate-200"
                                title="Delete Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Mitigation & Contingency Strategy Box -->
                    @if($r->mitigation_plan || $r->contingency_plan)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-slate-100 text-xs">
                            <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 text-emerald-950 space-y-1">
                                <div class="font-extrabold flex items-center gap-1.5 text-emerald-800 text-xs">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Preventative Mitigation Strategy</span>
                                </div>
                                <p class="font-medium leading-relaxed text-xs text-slate-700 pl-5.5">{{ $r->mitigation_plan ?: 'Standard operational monitoring procedure.' }}</p>
                            </div>

                            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-amber-950 space-y-1">
                                <div class="font-extrabold flex items-center gap-1.5 text-amber-800 text-xs">
                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Contingency Backup Plan</span>
                                </div>
                                <p class="font-medium leading-relaxed text-xs text-slate-700 pl-5.5">{{ $r->contingency_plan ?: 'Immediate escalation to PMO lead upon risk trigger.' }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Footer: Owner & Timestamp -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium pt-1">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9.5px] shadow-xs">
                                {{ strtoupper(substr($r->owner->name ?? 'P', 0, 1)) }}
                            </div>
                            <span class="text-slate-600">Risk Owner: <strong class="text-slate-900">{{ $r->owner->name ?? 'Project Manager' }}</strong></span>
                        </div>
                        <span class="text-slate-400">{{ $r->created_at->format('M d, Y') }} ({{ $r->created_at->diffForHumans() }})</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">No Project Risks Recorded</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">There are no open risks matching your filter criteria. You can log potential project threats anytime.</p>
                    </div>
                    <div class="pt-2">
                        <button
                            wire:click="openAddRiskModal()"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white shadow-sm hover:shadow transition-all cursor-pointer"
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
        <!-- TASK BLOCKERS CARDS -->
        <div class="space-y-4">
            @forelse($blockers as $b)
                @php
                    $sevColors = [
                        'low' => 'bg-slate-100 text-slate-700 border-slate-200',
                        'medium' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
                        'high' => 'bg-orange-50 text-orange-800 border-orange-200 font-bold',
                        'critical' => 'bg-rose-50 text-[#c3122e] border-rose-200 font-black',
                    ];
                @endphp

                <div class="bg-white rounded-2xl border transition-all p-5 sm:p-6 shadow-2xs {{ $b->status === 'resolved' ? 'border-slate-200 bg-slate-50/40' : 'border-rose-200/90 border-l-4 border-l-[#c3122e] hover:shadow-md' }} space-y-3.5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-black uppercase tracking-wider bg-slate-900 text-white shadow-xs">
                                {{ $b->wbsItem->project->name ?? 'Project' }}
                            </span>

                            <span class="px-2.5 py-1 rounded-lg text-[10.5px] font-bold bg-rose-50 text-[#c3122e] border border-rose-100">
                                📍 Task: {{ $b->wbsItem->title ?? 'Scope Task' }} ({{ $b->wbsItem->wbs_code ?? '-' }})
                            </span>

                            <span class="px-2.5 py-1 rounded-lg text-[10.5px] uppercase tracking-wider border {{ $sevColors[$b->severity] ?? 'bg-slate-100' }}">
                                {{ $b->severity }} Severity
                            </span>
                        </div>

                        <div>
                            @if($b->status === 'resolved')
                                <span class="px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Resolved
                                </span>
                            @else
                                <button
                                    wire:click="openResolveBlockerModal({{ $b->id }})"
                                    type="button"
                                    class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all cursor-pointer flex items-center gap-1.5"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Resolve Blocker</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs sm:text-[13px] font-semibold text-slate-800 leading-relaxed">{{ $b->description }}</p>

                    @if($b->status === 'resolved' && $b->resolution)
                        <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 text-xs text-emerald-950 space-y-1">
                            <div class="font-bold text-emerald-800 flex items-center gap-1.5 text-xs">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Resolution (by {{ $b->resolver->name ?? 'PM' }})</span>
                            </div>
                            <p class="font-normal italic leading-relaxed text-xs text-slate-700 pl-5.5">"{{ $b->resolution }}"</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium pt-1 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9.5px]">
                                {{ strtoupper(substr($b->reporter->name ?? 'T', 0, 1)) }}
                            </div>
                            <span class="text-slate-600">Reported by <strong class="text-slate-900">{{ $b->reporter->name ?? 'Team Member' }}</strong> &bull; {{ $b->created_at->diffForHumans() }}</span>
                        </div>

                        @if($b->resolved_at)
                            <span>Resolved {{ $b->resolved_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/90 shadow-2xs space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-500 flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800">No Active Task Blockers</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">All project deliverables and scope tasks are executing smoothly without blocking impediments.</p>
                    </div>
                    <div class="pt-2">
                        <button
                            wire:click="openAddBlockerModal()"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Report a Blocker</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODALS (HIGH-END DESIGN: ADD RISK, EDIT RISK, ADD/RESOLVE BLOCKER)
         ═══════════════════════════════════════════════════════════════ -->

    <!-- 1. ADD RISK MODAL -->
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="$set('showAddRiskModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200/90 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Top Crimson Brand Strip -->
                <div class="h-1.5 w-full flex-shrink-0" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, #c3122e 100%);"></div>

                <!-- Modal Header -->
                <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-md flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-tight">Log Project Risk</h3>
                            <p class="text-xs text-slate-500 font-medium">Record risk threat, probability x impact matrix, and mitigation strategy</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddRiskModal', false)" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4.5 flex-1">
                    <form id="addRiskForm" wire:submit="addRisk" class="space-y-4">
                        <!-- Project & Scope Section Card -->
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="">Select Project...</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                    @error('riskProjectId') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="Technical">Technical</option>
                                        <option value="Financial">Financial</option>
                                        <option value="Schedule">Schedule</option>
                                        <option value="Resource">Resource</option>
                                        <option value="Operational">Operational</option>
                                        <option value="External">External Vendor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">📍 WBS Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="">🌐 [General Project Scope]</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Title & Description -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Payment Gateway Integration Delay" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Threat Analysis &amp; Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" placeholder="Detailed breakdown of potential risk factors, root causes, and project impacts..." class="w-full text-xs rounded-xl p-3 border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800 leading-relaxed" style="border: 1px solid #cbd5e1;"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Matrix Assessment Card -->
                        <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-100 text-[#c3122e] border-rose-300 font-black';
                                    $calcLabel = 'Critical Severe';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-100 text-orange-800 border-orange-300 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-100 text-amber-800 border-amber-300 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span>Probability &amp; Impact Matrix Rating</span>
                                </label>
                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] uppercase font-bold border {{ $calcBadge }} shadow-2xs">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Mitigation & Contingency Strategy Plans -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-1.5">
                                <label class="block text-[11px] font-extrabold text-emerald-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Preventative Mitigation</span>
                                </label>
                                <textarea wire:model="riskMitigation" rows="2" placeholder="Proactive steps to prevent risk occurrence..." class="w-full text-xs p-2.5 rounded-xl border border-emerald-300 bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500 text-slate-800 leading-normal" style="border: 1px solid #a7f3d0;"></textarea>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-1.5">
                                <label class="block text-[11px] font-extrabold text-amber-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Contingency Backup Plan</span>
                                </label>
                                <textarea wire:model="riskContingency" rows="2" placeholder="Action plan to execute if risk materializes..." class="w-full text-xs p-2.5 rounded-xl border border-amber-300 bg-white focus:border-amber-600 focus:ring-1 focus:ring-amber-500 text-slate-800 leading-normal" style="border: 1px solid #fde68a;"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sticky Modal Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 flex-shrink-0 bg-slate-50/80">
                    <button type="button" wire:click="$set('showAddRiskModal', false)" class="px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="addRiskForm" class="px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg hover:scale-102 transition-all cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        Log Risk Record
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. EDIT RISK MODAL -->
    @if($showEditRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="$set('showEditRiskModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200/90 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Top Crimson Brand Strip -->
                <div class="h-1.5 w-full flex-shrink-0" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, #c3122e 100%);"></div>

                <!-- Modal Header -->
                <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-md flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-tight">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-medium">Update probability, impact ratings, owner, and action plans</p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditRiskModal', false)" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4.5 flex-1">
                    <form id="editRiskForm" wire:submit="updateRisk" class="space-y-4">
                        <!-- Project & Scope Section Card -->
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="Technical">Technical</option>
                                        <option value="Financial">Financial</option>
                                        <option value="Schedule">Schedule</option>
                                        <option value="Resource">Resource</option>
                                        <option value="Operational">Operational</option>
                                        <option value="External">External Vendor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">📍 WBS Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="">🌐 [General Project Scope]</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Title & Description -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800" style="border: 1px solid #cbd5e1;">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Threat Analysis &amp; Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" class="w-full text-xs rounded-xl p-3 border border-slate-300 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 text-slate-800 leading-relaxed" style="border: 1px solid #cbd5e1;"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Matrix Assessment Card -->
                        <div class="p-4 rounded-2xl bg-slate-50/90 border border-slate-200 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-100 text-emerald-800 border-emerald-300';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-100 text-[#c3122e] border-rose-300 font-black';
                                    $calcLabel = 'Critical Severe';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-100 text-orange-800 border-orange-300 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-100 text-amber-800 border-amber-300 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-bold text-slate-800">Probability &amp; Impact Matrix Rating</label>
                                <span class="px-2.5 py-0.5 rounded-full text-[10.5px] uppercase font-bold border {{ $calcBadge }} shadow-2xs">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Mitigation & Contingency Strategy Plans -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-1.5">
                                <label class="block text-[11px] font-extrabold text-emerald-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Preventative Mitigation</span>
                                </label>
                                <textarea wire:model="riskMitigation" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-emerald-300 bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500 text-slate-800 leading-normal" style="border: 1px solid #a7f3d0;"></textarea>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-amber-50/50 border border-amber-200/80 space-y-1.5">
                                <label class="block text-[11px] font-extrabold text-amber-800 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Contingency Backup Plan</span>
                                </label>
                                <textarea wire:model="riskContingency" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-amber-300 bg-white focus:border-amber-600 focus:ring-1 focus:ring-amber-500 text-slate-800 leading-normal" style="border: 1px solid #fde68a;"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sticky Modal Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 flex-shrink-0 bg-slate-50/80">
                    <button type="button" wire:click="$set('showEditRiskModal', false)" class="px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="editRiskForm" class="px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg hover:scale-102 transition-all cursor-pointer" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. ADD BLOCKER MODAL -->
    @if($showAddBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="$set('showAddBlockerModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200/90 max-w-lg w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col">
                <!-- Top Crimson Brand Strip -->
                <div class="h-1.5 w-full flex-shrink-0" style="background: linear-gradient(90deg, #c3122e 0%, #e11d48 50%, #c3122e 100%);"></div>

                <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-tight">Report Task Blocker</h3>
                            <p class="text-xs text-slate-500 font-medium">Log an active impediment preventing deliverable execution</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddBlockerModal', false)" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <form id="addBlockerForm" wire:submit="addBlocker" class="space-y-3.5">
                        <!-- Project Selector -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                            <select wire:model.live="blockerProjectId" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                <option value="">Select Project...</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                @endforeach
                            </select>
                            @error('blockerProjectId') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <!-- WBS Task Selector -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Blocked Scope Task <span class="text-[#c3122e]">*</span></label>
                            <select wire:model="blockerWbsItemId" class="w-full text-xs font-medium py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                <option value="">Select Scope Task...</option>
                                @foreach($modalBlockerWbsItems as $wbs)
                                    <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                @endforeach
                            </select>
                            @error('blockerWbsItemId') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <!-- Severity -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Blocker Severity <span class="text-[#c3122e]">*</span></label>
                            <select wire:model="blockerSeverity" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                                <option value="low">Low Severity</option>
                                <option value="medium">Medium Severity</option>
                                <option value="high">High Severity</option>
                                <option value="critical">Critical Severity (Blocker Stop)</option>
                            </select>
                        </div>

                        <!-- Blocker Description -->
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Impediment Description <span class="text-[#c3122e]">*</span></label>
                            <textarea wire:model="blockerDescription" rows="3" placeholder="Explain the root cause blocking this deliverable and what actions are required..." class="w-full text-xs rounded-xl p-3 border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800 leading-relaxed" style="border: 1px solid #cbd5e1;"></textarea>
                            @error('blockerDescription') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>

                <!-- Sticky Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/80 flex-shrink-0">
                    <button type="button" wire:click="$set('showAddBlockerModal', false)" class="px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 cursor-pointer">Cancel</button>
                    <button type="submit" form="addBlockerForm" class="px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        Submit Blocker
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. RESOLVE BLOCKER MODAL -->
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="$set('showResolveBlockerModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col">
                <!-- Top Emerald Brand Strip -->
                <div class="h-1.5 w-full flex-shrink-0" style="background: linear-gradient(90deg, #059669 0%, #10b981 50%, #059669 100%);"></div>

                <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold shadow-xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 leading-tight">Resolve Task Blocker</h3>
                            <p class="text-xs text-slate-500 font-medium">Record resolution steps and clear this execution impediment</p>
                        </div>
                    </div>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <form id="resolveBlockerForm" wire:submit="saveBlockerResolution" class="space-y-3.5">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-1">Resolution Summary <span class="text-[#c3122e]">*</span></label>
                            <textarea wire:model="blockerResolutionInput" rows="4" placeholder="Detail the resolution steps taken to unblock task execution..." class="w-full text-xs rounded-xl p-3 border border-slate-300 bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 text-slate-800 leading-relaxed" style="border: 1px solid #cbd5e1;"></textarea>
                            @error('blockerResolutionInput') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>

                <!-- Sticky Footer -->
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/80 flex-shrink-0">
                    <button type="button" wire:click="$set('showResolveBlockerModal', false)" class="px-4.5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 cursor-pointer">Cancel</button>
                    <button type="submit" form="resolveBlockerForm" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md hover:shadow-lg cursor-pointer transition-all">
                        Mark Resolved
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
