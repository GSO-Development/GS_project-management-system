<div class="space-y-6 pb-12">
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER (RISKS & BLOCKERS HUB)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Risks &amp; Blockers Hub
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-normal">
                Track project risks, evaluate threat scores, and resolve active delivery blockers.
            </p>
        </div>

        <!-- Right Side: Action Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0 self-start sm:self-center">
            <button
                wire:click="openAddBlockerModal()"
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs hover:shadow-xs transition-all cursor-pointer active:scale-98"
            >
                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Report Blocker</span>
            </button>

            <button
                wire:click="openAddRiskModal()"
                type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white shadow-2xs hover:shadow-sm hover:opacity-95 active:scale-98 transition-all cursor-pointer"
                style="background: linear-gradient(135deg, #c3122e 0%, #a80f26 100%);"
            >
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Log Project Risk</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI SUMMARY METRICS (4 Clean Modern Cards)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Active Project Risks -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all flex items-center justify-between">
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block truncate">Project Risks</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $totalRisksCount }}</span>
                    <span class="text-xs font-semibold {{ $openRisksCount > 0 ? 'text-amber-600' : 'text-slate-400' }}">({{ $openRisksCount }} active)</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5 truncate">Across {{ $projects->count() }} projects</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>

        <!-- High Threat Risks -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all flex items-center justify-between">
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block truncate">High Threats</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black {{ $criticalHighRiskCount > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight">{{ $criticalHighRiskCount }}</span>
                    <span class="text-xs font-semibold text-rose-500">Critical / High</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5 truncate">Score &ge; 6 / 12</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
        </div>

        <!-- Pending Task Blockers -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all flex items-center justify-between">
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block truncate">Task Blockers</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black {{ $openBlockersCount > 0 ? 'text-[#c3122e]' : 'text-slate-900' }} tracking-tight">{{ $openBlockersCount }}</span>
                    <span class="text-xs font-semibold {{ $openBlockersCount > 0 ? 'text-rose-500' : 'text-slate-400' }}">Pending</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5 truncate">Execution impediments</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-orange-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            </div>
        </div>

        <!-- Resolved Blockers -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-xs transition-all flex items-center justify-between">
            <div class="min-w-0">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block truncate">Resolved</span>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-2xl font-black text-emerald-600 tracking-tight">{{ $resolvedBlockersCount }}</span>
                    <span class="text-xs font-semibold text-emerald-600">Cleared</span>
                </div>
                <p class="text-[11px] text-slate-400 mt-0.5 truncate">Unblocked deliverables</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CONTROLS, SEGMENTED TABS & FILTERS BAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-2xs space-y-3.5">
        <!-- Top Row: Tab Switcher & Active Count -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <!-- Segmented Pill Tabs -->
            <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 border border-slate-200/60">
                <button
                    type="button"
                    wire:click="$set('activeTab', 'risks')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'risks' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $activeTab === 'risks' ? 'bg-amber-500' : 'bg-slate-300' }}"></span>
                    <span>Project Risks</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $activeTab === 'risks' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-slate-200/80 text-slate-600' }}">{{ $risks->count() }}</span>
                </button>

                <button
                    type="button"
                    wire:click="$set('activeTab', 'blockers')"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-2 {{ $activeTab === 'blockers' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-800' }}"
                >
                    <span class="w-2 h-2 rounded-full {{ $activeTab === 'blockers' ? 'bg-[#c3122e]' : 'bg-slate-300' }}"></span>
                    <span>Task Blockers</span>
                    <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $activeTab === 'blockers' ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-slate-200/80 text-slate-600' }}">{{ $blockers->count() }}</span>
                </button>
            </div>

            <!-- Active Filters Reset -->
            <div class="text-xs font-medium text-slate-500 flex items-center gap-3">
                <span>Showing <strong class="text-slate-800 font-semibold">{{ $activeTab === 'risks' ? $risks->count() . ' Risks' : $blockers->count() . ' Blockers' }}</strong></span>
                @if($searchQuery || $selectedProjectId || ($activeTab === 'risks' && ($selectedCategory || $selectedStatus !== 'all')) || ($activeTab === 'blockers' && $selectedSeverity !== 'all'))
                    <button wire:click="$set('searchQuery', ''); $set('selectedProjectId', null); $set('selectedCategory', ''); $set('selectedStatus', 'all'); $set('selectedSeverity', 'all');" class="text-xs font-semibold text-[#c3122e] hover:underline cursor-pointer flex items-center gap-1">
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
                    class="w-full text-xs font-medium py-2 pl-8.5 pr-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all text-slate-800 placeholder:text-slate-400"
                >
                <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Project Selector Dropdown -->
            <div>
                <select
                    wire:model.live="selectedProjectId"
                    class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all text-slate-700 cursor-pointer"
                >
                    <option value="">All Accessible Projects</option>
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
                        class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all text-slate-700 cursor-pointer"
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
                        class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all text-slate-700 cursor-pointer"
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
                        class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all text-slate-700 cursor-pointer"
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
        <div class="space-y-3.5">
            @forelse($risks as $r)
                @php
                    $score = $r->risk_score;
                    if ($score >= 8) {
                        $threatBadge = 'bg-rose-50 text-rose-700 border-rose-200/80';
                        $threatDot = 'bg-rose-500';
                        $threatLabel = 'Critical Threat';
                    } elseif ($score >= 6) {
                        $threatBadge = 'bg-orange-50 text-orange-700 border-orange-200/80';
                        $threatDot = 'bg-orange-500';
                        $threatLabel = 'High Threat';
                    } elseif ($score >= 4) {
                        $threatBadge = 'bg-amber-50 text-amber-800 border-amber-200/80';
                        $threatDot = 'bg-amber-500';
                        $threatLabel = 'Medium Threat';
                    } else {
                        $threatBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                        $threatDot = 'bg-emerald-500';
                        $threatLabel = 'Low Threat';
                    }
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-slate-300 shadow-2xs hover:shadow-xs transition-all p-5 sm:p-6 space-y-3.5">
                    <!-- Top Row: Project & Context Info on Left, Threat Pill & Actions on Right -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- Project Badge -->
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>{{ $r->project->name ?? 'Project' }}</span>
                            </span>

                            <!-- Category Badge -->
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200/70">
                                {{ $r->category }}
                            </span>

                            <!-- Scope Task (if linked) -->
                            @if($r->wbsItem)
                                <span class="inline-flex items-center gap-1 text-xs text-slate-500 font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <span>Task: {{ $r->wbsItem->title }} ({{ $r->wbsItem->wbs_code }})</span>
                                </span>
                            @endif
                        </div>

                        <!-- Right Side: Threat Score Pill & Action Buttons -->
                        <div class="flex items-center gap-2 flex-shrink-0 self-start sm:self-center">
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold border {{ $threatBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $threatDot }}"></span>
                                <span>{{ $threatLabel }}</span>
                                <span class="text-slate-300 font-normal">&bull;</span>
                                <span class="font-bold">{{ $score }}/12</span>
                            </div>

                            <select
                                wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                class="text-xs font-semibold rounded-lg px-2.5 py-1 border transition-all cursor-pointer shadow-2xs
                                    {{ $r->status === 'open' ? 'bg-amber-50 text-amber-800 border-amber-200' : '' }}
                                    {{ $r->status === 'monitoring' ? 'bg-sky-50 text-sky-800 border-sky-200' : '' }}
                                    {{ $r->status === 'mitigated' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : '' }}
                                    {{ $r->status === 'closed' ? 'bg-slate-50 text-slate-600 border-slate-200' : '' }}"
                            >
                                <option value="open" @selected($r->status === 'open')>Open</option>
                                <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                <option value="closed" @selected($r->status === 'closed')>Closed</option>
                            </select>

                            <button
                                wire:click="openEditRiskModal({{ $r->id }})"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                                title="Edit Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <button
                                wire:click="deleteRisk({{ $r->id }})"
                                wire:confirm="Are you sure you want to remove this risk record?"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                title="Delete Risk"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Middle: Title & Description -->
                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900 tracking-tight leading-snug">{{ $r->title }}</h3>
                        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed font-normal">{{ $r->description }}</p>
                    </div>

                    <!-- Matrix Factors (Probability & Impact) -->
                    <div class="text-[11px] text-slate-500 font-medium flex items-center gap-3">
                        <span>Probability: <strong class="text-slate-700 font-semibold">{{ ucfirst($r->probability) }}</strong></span>
                        <span class="text-slate-300">&bull;</span>
                        <span>Impact: <strong class="text-slate-700 font-semibold">{{ ucfirst($r->impact) }}</strong></span>
                    </div>

                    <!-- Mitigation & Contingency Strategy Plans -->
                    @if($r->mitigation_plan || $r->contingency_plan)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 pt-1">
                            @if($r->mitigation_plan)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs space-y-1">
                                    <div class="font-semibold text-slate-700 flex items-center gap-1.5 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        <span>Mitigation Strategy</span>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed text-xs">{{ $r->mitigation_plan }}</p>
                                </div>
                            @endif

                            @if($r->contingency_plan)
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 text-xs space-y-1">
                                    <div class="font-semibold text-slate-700 flex items-center gap-1.5 text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Contingency Plan</span>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed text-xs">{{ $r->contingency_plan }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Footer: Owner & Timestamp -->
                    <div class="flex items-center justify-between text-xs text-slate-500 font-medium pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-[9.5px]">
                                {{ strtoupper(substr($r->owner->name ?? 'P', 0, 1)) }}
                            </div>
                            <span>Owner: <strong class="text-slate-700 font-semibold">{{ $r->owner->name ?? 'Project Manager' }}</strong></span>
                        </div>
                        <span class="text-slate-400 text-[11px]">{{ $r->created_at->format('M d, Y') }} ({{ $r->created_at->diffForHumans() }})</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-500 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">No Project Risks Recorded</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">There are no open risks matching your filter criteria. You can log potential project threats anytime.</p>
                    </div>
                    <div class="pt-1">
                        <button
                            wire:click="openAddRiskModal()"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-white shadow-2xs hover:shadow-xs transition-all cursor-pointer"
                            style="background: linear-gradient(135deg, #c3122e 0%, #a80f26 100%);"
                        >
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            <span>Log Project Risk</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <!-- TASK BLOCKERS CARDS -->
        <div class="space-y-3.5">
            @forelse($blockers as $b)
                @php
                    $sevBadge = match($b->severity) {
                        'critical' => 'bg-rose-50 text-rose-700 border-rose-200/80',
                        'high' => 'bg-orange-50 text-orange-700 border-orange-200/80',
                        'medium' => 'bg-amber-50 text-amber-800 border-amber-200/80',
                        default => 'bg-slate-50 text-slate-600 border-slate-200/70',
                    };
                @endphp

                <div class="bg-white rounded-2xl border border-slate-200/80 hover:border-slate-300 shadow-2xs hover:shadow-xs transition-all p-5 sm:p-6 space-y-3.5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            <!-- Project Badge -->
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                <span>{{ $b->wbsItem->project->name ?? 'Project' }}</span>
                            </span>

                            <!-- Task Badge -->
                            <span class="inline-flex items-center gap-1 text-xs text-slate-500 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span>Task: {{ $b->wbsItem->title ?? 'Scope Task' }} ({{ $b->wbsItem->wbs_code ?? '-' }})</span>
                            </span>

                            <!-- Severity Pill -->
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold border {{ $sevBadge }}">
                                {{ ucfirst($b->severity) }} Severity
                            </span>
                        </div>

                        <!-- Status Action -->
                        <div>
                            @if($b->status === 'resolved')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Resolved
                                </span>
                            @else
                                <button
                                    wire:click="openResolveBlockerModal({{ $b->id }})"
                                    type="button"
                                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs hover:shadow-xs transition-all cursor-pointer flex items-center gap-1.5"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Resolve Blocker</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <p class="text-xs sm:text-[13px] font-normal text-slate-700 leading-relaxed">{{ $b->description }}</p>

                    @if($b->status === 'resolved' && $b->resolution)
                        <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-200/70 text-xs space-y-1">
                            <div class="font-semibold text-emerald-800 flex items-center gap-1.5 text-[11px]">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Resolution (by {{ $b->resolver->name ?? 'PM' }})</span>
                            </div>
                            <p class="italic text-slate-600 leading-relaxed text-xs">"{{ $b->resolution }}"</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between text-xs text-slate-500 font-medium pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-[9.5px]">
                                {{ strtoupper(substr($b->reporter->name ?? 'T', 0, 1)) }}
                            </div>
                            <span>Reported by <strong class="text-slate-700 font-semibold">{{ $b->reporter->name ?? 'Team Member' }}</strong> &bull; {{ $b->created_at->diffForHumans() }}</span>
                        </div>

                        @if($b->resolved_at)
                            <span class="text-[11px] text-slate-400">Resolved {{ $b->resolved_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-500 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">No Active Task Blockers</h3>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">All project deliverables and scope tasks are executing smoothly without blocking impediments.</p>
                    </div>
                    <div class="pt-1">
                        <button
                            wire:click="openAddBlockerModal()"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Report a Blocker</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODALS (CLEAN DESIGN: ADD RISK, EDIT RISK, ADD/RESOLVE BLOCKER)
         ═══════════════════════════════════════════════════════════════ -->

    <!-- 1. ADD RISK MODAL -->
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showAddRiskModal', false)"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Log Project Risk</h3>
                            <p class="text-xs text-slate-500 font-normal">Record risk threat, probability x impact matrix, and mitigation strategy</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddRiskModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form id="addRiskForm" wire:submit="addRisk" class="space-y-4">
                        <!-- Project & Scope Section -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">Select Project...</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                    @error('riskProjectId') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
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
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">General Project Scope</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">{{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
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
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Integration Delay" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Threat Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" placeholder="Detailed breakdown of potential risk factors, root causes, and project impacts..." class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800 leading-relaxed"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Assessment -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                                    $calcLabel = 'Critical Threat';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-50 text-orange-700 border-orange-200 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-50 text-amber-800 border-amber-200 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-semibold text-slate-700">Probability &amp; Impact Matrix Rating</label>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $calcBadge }}">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
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
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Preventative Mitigation Plan</label>
                                <textarea wire:model="riskMitigation" rows="2" placeholder="Proactive steps to prevent risk..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]/10 text-slate-800"></textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Contingency Backup Plan</label>
                                <textarea wire:model="riskContingency" rows="2" placeholder="Action plan if risk materializes..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]/10 text-slate-800"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0 bg-slate-50">
                    <button type="button" wire:click="$set('showAddRiskModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="addRiskForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white shadow-xs hover:shadow transition-all cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #a80f26 100%);">
                        Log Risk
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. EDIT RISK MODAL -->
    @if($showEditRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showEditRiskModal', false)"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-normal">Update probability, impact ratings, owner, and action plans</p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditRiskModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form id="editRiskForm" wire:submit="updateRisk" class="space-y-4">
                        <!-- Project & Scope Section -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
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
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">General Project Scope</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">{{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
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
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Threat Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800 leading-relaxed"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Assessment -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                                    $calcLabel = 'Critical Threat';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-50 text-orange-700 border-orange-200 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-50 text-amber-800 border-amber-200 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-semibold text-slate-700">Probability &amp; Impact Matrix Rating</label>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $calcBadge }}">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
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
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Preventative Mitigation Plan</label>
                                <textarea wire:model="riskMitigation" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800"></textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Contingency Backup Plan</label>
                                <textarea wire:model="riskContingency" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0 bg-slate-50">
                    <button type="button" wire:click="$set('showEditRiskModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="editRiskForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white shadow-xs hover:shadow transition-all cursor-pointer" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 3. ADD BLOCKER MODAL -->
    @if($showAddBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showAddBlockerModal', false)"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 max-w-lg w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col mx-3 sm:mx-auto">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200/80 text-[#c3122e] flex items-center justify-center font-bold shadow-2xs">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Report Task Blocker</h3>
                            <p class="text-xs text-slate-500 font-normal">Log an active impediment preventing task execution</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddBlockerModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-3.5">
                    <form id="addBlockerForm" wire:submit="addBlocker" class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                            <select wire:model.live="blockerProjectId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                <option value="">Select Project...</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                @endforeach
                            </select>
                            @error('blockerProjectId') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Blocked Scope Task <span class="text-[#c3122e]">*</span></label>
                            <select wire:model="blockerWbsItemId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                <option value="">Select Scope Task...</option>
                                @foreach($modalBlockerWbsItems as $wbs)
                                    <option value="{{ $wbs->id }}">{{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                @endforeach
                            </select>
                            @error('blockerWbsItemId') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Blocker Severity <span class="text-[#c3122e]">*</span></label>
                            <select wire:model="blockerSeverity" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                <option value="low">Low Severity</option>
                                <option value="medium">Medium Severity</option>
                                <option value="high">High Severity</option>
                                <option value="critical">Critical Severity (Delivery Stop)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Impediment Description <span class="text-[#c3122e]">*</span></label>
                            <textarea wire:model="blockerDescription" rows="3" placeholder="Explain the root cause blocking this deliverable..." class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800 leading-relaxed"></textarea>
                            @error('blockerDescription') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-slate-50 flex-shrink-0">
                    <button type="button" wire:click="$set('showAddBlockerModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer">Cancel</button>
                    <button type="submit" form="addBlockerForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white shadow-xs hover:shadow cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #a80f26 100%);">
                        Submit Blocker
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 4. RESOLVE BLOCKER MODAL -->
    @if($showResolveBlockerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showResolveBlockerModal', false)"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 max-w-lg w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-600 flex items-center justify-center font-bold shadow-2xs">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Resolve Task Blocker</h3>
                            <p class="text-xs text-slate-500 font-normal">Record resolution steps and clear this execution impediment</p>
                        </div>
                    </div>
                    <button wire:click="$set('showResolveBlockerModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-6 space-y-3.5">
                    <form id="resolveBlockerForm" wire:submit="saveBlockerResolution" class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Resolution Summary <span class="text-[#c3122e]">*</span></label>
                            <textarea wire:model="blockerResolutionInput" rows="4" placeholder="Detail the resolution steps taken to unblock task execution..." class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 text-slate-800 leading-relaxed"></textarea>
                            @error('blockerResolutionInput') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 bg-slate-50 flex-shrink-0">
                    <button type="button" wire:click="$set('showResolveBlockerModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer">Cancel</button>
                    <button type="submit" form="resolveBlockerForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-xs hover:shadow cursor-pointer transition-all">
                        Mark Resolved
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
