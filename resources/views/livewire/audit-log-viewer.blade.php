<div @if($autoRefresh) wire:poll.30s @endif class="space-y-5" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">

    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 11px;
            padding-right: 28px !important;
        }
    </style>

    <!-- ── 1. EXECUTIVE PAGE HEADER ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 shadow-2xs text-[#c3122e]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-none">
                        Security &amp; Activity Audit Logs
                    </h1>
                    
                    <!-- Live Sync Indicator Toggle -->
                    <button wire:click="toggleAutoRefresh" 
                            type="button" 
                            title="{{ $autoRefresh ? 'Live auto-refresh is active (click to pause)' : 'Live auto-refresh is paused (click to resume)' }}"
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold cursor-pointer transition-all border shadow-2xs {{ $autoRefresh ? 'bg-emerald-50 text-emerald-700 border-emerald-200/80 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}">
                        <span class="w-2 h-2 rounded-full {{ $autoRefresh ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                        <span>{{ $autoRefresh ? 'Live Sync Active' : 'Sync Paused' }}</span>
                    </button>
                </div>
                <p class="text-xs text-slate-500 font-semibold mt-1">Real-time immutable audit trail for security compliance, governance &amp; system actions</p>
            </div>
        </div>

        <!-- Right Header Actions -->
        <div class="flex items-center gap-2.5 flex-shrink-0">
            <!-- Export CSV Button -->
            <button wire:click="exportCsv" 
                    type="button" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-extrabold text-white shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer bg-gradient-to-r from-[#c3122e] to-[#a00c24] hover:from-[#a00c24] hover:to-[#800a1c] active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV</span>
            </button>
        </div>
    </div>


    <!-- ── 2. EXECUTIVE METRIC KPI CARDS ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <!-- Card 1: Total Events -->
        <button type="button" wire:click="setCardFilter('all')"
                class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all duration-200 cursor-pointer group hover:-translate-y-0.5 {{ $quickTab === 'all' && $dateFilter === 'all' ? 'ring-2 ring-[#c3122e]/30 border-[#c3122e] bg-rose-50/10' : '' }}">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-2xs">
                <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-[26px] font-black text-slate-900 leading-none tracking-tight">{{ number_format($totalLogsCount) }}</div>
                <div class="text-xs text-slate-500 font-semibold mt-1">Total Audit Events</div>
            </div>
        </button>

        <!-- Card 2: Today's Logs -->
        <button type="button" wire:click="setCardFilter('today')"
                class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all duration-200 cursor-pointer group hover:-translate-y-0.5 {{ $dateFilter === 'today' ? 'ring-2 ring-emerald-500/30 border-emerald-500 bg-emerald-50/10' : '' }}">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-2xs">
                <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-[26px] font-black text-slate-900 leading-none tracking-tight">{{ number_format($todayLogsCount) }}</div>
                <div class="text-xs text-slate-500 font-semibold mt-1">Logged Today</div>
            </div>
        </button>

        <!-- Card 3: Active Actors -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs">
                <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-[26px] font-black text-slate-900 leading-none tracking-tight">{{ number_format($activeActorsCount) }}</div>
                <div class="text-xs text-slate-500 font-semibold mt-1">Active User Actors</div>
            </div>
        </div>

        <!-- Card 4: Security Events -->
        <button type="button" wire:click="setCardFilter('security')"
                class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all duration-200 cursor-pointer group hover:-translate-y-0.5 {{ $quickTab === 'security' ? 'ring-2 ring-purple-500/30 border-purple-500 bg-purple-50/10' : '' }}">
            <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-2xs">
                <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="text-2xl sm:text-[26px] font-black text-slate-900 leading-none tracking-tight">{{ number_format($securityLogsCount) }}</div>
                <div class="text-xs text-slate-500 font-semibold mt-1">Security &amp; Auth Logs</div>
            </div>
        </button>
    </div>


    <!-- ── 3. CATEGORY QUICK TABS ── -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none border-b border-slate-200/80">
        <button wire:click="setQuickTab('all')" type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs {{ $quickTab === 'all' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            <svg class="w-3.5 h-3.5 {{ $quickTab === 'all' ? 'text-slate-300' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            <span>All Activity</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $quickTab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ number_format($tabCounts['all']) }}</span>
        </button>

        <button wire:click="setQuickTab('projects')" type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs {{ $quickTab === 'projects' ? 'bg-[#c3122e] text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            <svg class="w-3.5 h-3.5 {{ $quickTab === 'projects' ? 'text-rose-200' : 'text-rose-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Projects &amp; Subsidiaries</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $quickTab === 'projects' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ number_format($tabCounts['projects']) }}</span>
        </button>

        <button wire:click="setQuickTab('governance')" type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs {{ $quickTab === 'governance' ? 'bg-amber-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            <svg class="w-3.5 h-3.5 {{ $quickTab === 'governance' ? 'text-amber-200' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span>Governance &amp; Pings</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $quickTab === 'governance' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ number_format($tabCounts['governance']) }}</span>
        </button>

        <button wire:click="setQuickTab('security')" type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs {{ $quickTab === 'security' ? 'bg-purple-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            <svg class="w-3.5 h-3.5 {{ $quickTab === 'security' ? 'text-purple-200' : 'text-purple-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span>Security &amp; Users</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $quickTab === 'security' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ number_format($tabCounts['security']) }}</span>
        </button>

        <button wire:click="setQuickTab('tasks')" type="button"
                class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all duration-200 cursor-pointer whitespace-nowrap flex items-center gap-2 shadow-2xs {{ $quickTab === 'tasks' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/80' }}">
            <svg class="w-3.5 h-3.5 {{ $quickTab === 'tasks' ? 'text-blue-200' : 'text-blue-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <span>Tasks &amp; WBS</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ $quickTab === 'tasks' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ number_format($tabCounts['tasks']) }}</span>
        </button>
    </div>


    <!-- ── 4. STREAMLINED SEARCH & FILTER TOOLBAR ── -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-3.5 shadow-2xs space-y-2.5">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[260px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search by action, actor, or record name..." 
                    class="w-full pl-10 pr-9 py-2 text-xs font-semibold text-slate-800 bg-slate-50/80 border border-slate-200/90 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all placeholder:text-slate-400"
                >
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                @endif
            </div>

            <!-- Filter Dropdowns Row -->
            <div class="flex items-center gap-2 flex-wrap">
                
                <!-- Action Type Filter -->
                <select wire:model.live="actionFilter" class="custom-select text-xs font-semibold text-slate-700 bg-slate-50/80 border border-slate-200/90 rounded-xl py-2 pl-3 hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 cursor-pointer transition-all max-w-[155px] truncate">
                    <option value="all">All Actions</option>
                    @foreach($actionsList as $act)
                        <option value="{{ $act }}">{{ ucwords(str_replace('_', ' ', $act)) }}</option>
                    @endforeach
                </select>

                <!-- Module Filter -->
                <select wire:model.live="moduleFilter" class="custom-select text-xs font-semibold text-slate-700 bg-slate-50/80 border border-slate-200/90 rounded-xl py-2 pl-3 hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 cursor-pointer transition-all max-w-[130px] truncate">
                    <option value="all">All Modules</option>
                    @foreach($modulesList as $mod)
                        <option value="{{ $mod }}">{{ ucwords(str_replace('_', ' ', $mod)) }}</option>
                    @endforeach
                </select>

                <!-- Date Range Filter -->
                <select wire:model.live="dateFilter" class="custom-select text-xs font-semibold text-slate-700 bg-slate-50/80 border border-slate-200/90 rounded-xl py-2 pl-3 hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 cursor-pointer transition-all">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>

                <!-- Sort Order Toggle -->
                <button wire:click="toggleSortOrder" 
                        type="button" 
                        title="{{ $sortOrder === 'desc' ? 'Newest first (click for oldest)' : 'Oldest first (click for newest)' }}"
                        class="p-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-50/80 hover:bg-white border border-slate-200/90 transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        @if($sortOrder === 'desc')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4 4m0 0l4-4m-4 4V4"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                        @endif
                    </svg>
                    <span class="hidden sm:inline">{{ $sortOrder === 'desc' ? 'Newest' : 'Oldest' }}</span>
                </button>

                <!-- Per Page Select -->
                <select wire:model.live="perPage" class="custom-select text-xs font-semibold text-slate-700 bg-slate-50/80 border border-slate-200/90 rounded-xl py-2 pl-3 hover:bg-white hover:border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 cursor-pointer transition-all w-[70px]">
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                @if($search || $moduleFilter !== 'all' || $actionFilter !== 'all' || $userFilter !== 'all' || $dateFilter !== 'all' || $quickTab !== 'all')
                    <button wire:click="resetFilters" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Reset</span>
                    </button>
                @endif
            </div>

        </div>

        <!-- Custom Date Range Row (Conditional) -->
        @if($dateFilter === 'custom')
            <div class="flex items-center gap-3 pt-2.5 border-t border-slate-100 text-xs">
                <span class="font-bold text-slate-500 text-[11px] uppercase tracking-wider">Date Window:</span>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="startDate" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20">
                    <span class="text-slate-400 font-bold">to</span>
                    <input type="date" wire:model.live="endDate" class="px-2.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20">
                </div>
            </div>
        @endif
    </div>


    <!-- ── 5. AUDIT ACTIVITY TABLE ── -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 select-none">
                        <th class="py-3.5 pl-6 pr-4 min-w-[200px]">User / Actor</th>
                        <th class="py-3.5 px-4 min-w-[170px]">Action Performed</th>
                        <th class="py-3.5 px-4 min-w-[120px]">Module</th>
                        <th class="py-3.5 px-4 min-w-[240px]">Target Record</th>
                        <th class="py-3.5 px-4 min-w-[110px]">IP Address</th>
                        <th class="py-3.5 px-4 min-w-[150px]">Date &amp; Time</th>
                        <th class="py-3.5 pl-4 pr-6 text-right min-w-[90px]">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($logs as $log)
                        @php
                            $actionName = match($log->action) {
                                'created_project'             => 'Created Project',
                                'updated_project'             => 'Updated Project',
                                'deleted_project'             => 'Deleted Project',
                                'project_deleted'             => 'Deleted Project',
                                'updated_project_schedule'    => 'Updated Schedule',
                                'accepted_project_assignment' => 'Accepted Leadership',
                                'rejected_project_assignment' => 'Declined Leadership',
                                'reassigned_project_leader'   => 'Reassigned Leader',
                                'auto_cascaded_schedule'      => 'Auto-Cascaded Schedule',
                                'cascade_rescheduled'         => 'Cascade Rescheduled',
                                'pmo_nudge_dispatched'        => 'PMO Nudge Sent',
                                'pmo_quick_ping_dispatched'   => 'PMO Quick Ping',
                                'pmo_stuck_task_nudge'        => 'Stuck Task Nudge',
                                'created_wbs_item'            => 'Added WBS Task',
                                'updated_wbs_item'            => 'Updated WBS Task',
                                'created_user'                => 'Created User Account',
                                'updated_user'                => 'Updated User Profile',
                                'provisioned_azure_user'      => 'Azure SSO Provisioned',
                                'updated_role_permissions'    => 'Updated Role Permissions',
                                'created_role'                => 'Created Security Role',
                                'created_subsidiary'          => 'Created Subsidiary',
                                'updated_subsidiary'          => 'Updated Subsidiary',
                                'created_status_update'       => 'Posted Status Update',
                                'created_risk'                => 'Logged Project Risk',
                                'created_blocker'             => 'Reported Blocker',
                                'resolved_blocker'            => 'Resolved Blocker',
                                'approved_request'            => 'Approved Request',
                                'rejected_request'            => 'Rejected Request',
                                'uploaded_document'           => 'Uploaded Document',
                                'deleted_document'            => 'Deleted Document',
                                'updated_system_settings'     => 'Updated System Settings',
                                default                       => ucwords(str_replace('_', ' ', $log->action))
                            };

                            $actionBadge = match(true) {
                                str_contains($log->action, 'create') || str_contains($log->action, 'accept') || str_contains($log->action, 'provision') => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                                str_contains($log->action, 'reject') || str_contains($log->action, 'delete') => 'bg-rose-50 text-rose-700 border-rose-200/80',
                                str_contains($log->action, 'update') || str_contains($log->action, 'edit') || str_contains($log->action, 'reassign') => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                str_contains($log->action, 'approve') => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                str_contains($log->action, 'nudge') || str_contains($log->action, 'ping') => 'bg-purple-50 text-purple-700 border-purple-200/80',
                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                            };

                            $moduleConfig = match($log->module) {
                                'projects'          => ['label' => 'Projects', 'bg' => 'bg-rose-50 text-[#c3122e] border-rose-100'],
                                'wbs', 'wbs_items'  => ['label' => 'Tasks & WBS', 'bg' => 'bg-blue-50 text-blue-700 border-blue-100'],
                                'users'             => ['label' => 'Users & Auth', 'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
                                'roles_permissions' => ['label' => 'Security Roles', 'bg' => 'bg-amber-50 text-amber-800 border-amber-100'],
                                'approvals'         => ['label' => 'Approvals Hub', 'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-100'],
                                'subsidiaries'      => ['label' => 'Subsidiaries', 'bg' => 'bg-purple-50 text-purple-700 border-purple-100'],
                                'documents'         => ['label' => 'Documents', 'bg' => 'bg-teal-50 text-teal-700 border-teal-100'],
                                'settings'          => ['label' => 'Settings', 'bg' => 'bg-slate-100 text-slate-700 border-slate-200'],
                                default             => ['label' => ucwords(str_replace('_', ' ', $log->module)), 'bg' => 'bg-slate-100 text-slate-700 border-slate-200']
                            };

                            $recordName = $log->record_type ? class_basename($log->record_type) : 'Record';
                            $rec = $resolvedRecords[$log->id] ?? null;
                            $recTitle = $rec['title'] ?? null;
                            $recCode = $rec['code'] ?? null;
                            $recUrl = $rec['url'] ?? null;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            
                            <!-- 1. USER / ACTOR -->
                            <td class="py-3.5 pl-6 pr-4 align-middle">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if($log->user)
                                        <div class="w-8.5 h-8.5 rounded-xl bg-gradient-to-br from-[#c3122e] to-[#800a1c] text-white flex items-center justify-center text-xs font-extrabold flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-900 truncate block">{{ $log->user->name }}</span>
                                            <span class="text-[11px] text-slate-400 font-medium truncate block">{{ $log->user->email }}</span>
                                        </div>
                                    @else
                                        <div class="w-8.5 h-8.5 rounded-xl bg-slate-800 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow-2xs">
                                            🤖
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-900 truncate block">System Automated</span>
                                            <span class="text-[11px] text-slate-400 font-medium truncate block">Background Service</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- 2. ACTION -->
                            <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                <button wire:click="filterByAction('{{ $log->action }}')" 
                                        type="button" 
                                        title="Filter by action: {{ $actionName }}"
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border shadow-2xs hover:scale-105 transition-transform cursor-pointer {{ $actionBadge }}">
                                    {{ $actionName }}
                                </button>
                            </td>

                            <!-- 3. MODULE -->
                            <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $moduleConfig['bg'] }}">
                                    {{ $moduleConfig['label'] }}
                                </span>
                            </td>

                            <!-- 4. TARGET RECORD -->
                            <td class="py-3.5 px-4 align-middle">
                                <div class="min-w-0 max-w-[280px]">
                                    @if($recTitle)
                                        <div class="min-w-0">
                                            @if($recUrl)
                                                <a href="{{ $recUrl }}" class="font-bold text-sm text-slate-900 hover:text-[#c3122e] transition-colors block truncate no-underline" title="{{ $recTitle }}">
                                                    {{ $recTitle }}
                                                </a>
                                            @else
                                                <span class="font-bold text-sm text-slate-900 block truncate" title="{{ $recTitle }}">
                                                    {{ $recTitle }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="font-bold text-sm text-slate-900 block truncate">
                                            {{ $recordName === 'SystemSetting' || $log->module === 'settings' ? 'Global System Configuration' : ($recordName . ($log->record_id ? ' #' . $log->record_id : '')) }}
                                        </span>
                                    @endif

                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($recCode)
                                            <span class="font-mono font-bold text-[10.5px] text-[#c3122e] bg-rose-50/90 px-2 py-0.5 rounded-md border border-rose-200/70 whitespace-nowrap shrink-0 inline-flex items-center leading-none">
                                                {{ $recCode }}
                                            </span>
                                            <span class="text-[10px] text-slate-300">•</span>
                                        @endif
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 whitespace-nowrap shrink-0">
                                            <span>{{ $recordName === 'SystemSetting' || $log->module === 'settings' ? 'System Configuration' : $recordName }}</span>
                                            @if($log->record_id && $recordName !== 'SystemSetting')
                                                <button wire:click="filterByRecord('{{ $log->record_id }}')" title="Filter by record #{{ $log->record_id }}" class="hover:text-[#c3122e] font-semibold text-slate-600 cursor-pointer">
                                                    #{{ $log->record_id }}
                                                </button>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- 5. IP ADDRESS -->
                            <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                <span class="text-xs font-mono text-slate-500 bg-slate-50 px-2 py-1 rounded-md border border-slate-200/60">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </span>
                            </td>

                            <!-- 6. DATE & TIME -->
                            <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-800 leading-tight">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ $log->created_at->diffForHumans() }}
                                </div>
                            </td>

                            <!-- 7. INSPECT ACTION -->
                            <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                <button wire:click="viewDetails({{ $log->id }})" 
                                        type="button" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all cursor-pointer shadow-2xs hover:shadow-xs">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span>Inspect</span>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-2">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700">No audit logs found</span>
                                    <p class="text-[11px] text-slate-400 mt-0.5">No log records matched your search or active filter parameters.</p>
                                    <button wire:click="resetFilters" type="button" class="mt-2.5 text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">
                                        Clear All Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>


    <!-- ── 6. INTERACTIVE AUDIT LOG INSPECTOR MODAL ── -->
    @if($showDetailModal && $selectedLog)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-3xl overflow-hidden animate-in fade-in zoom-in duration-150 flex flex-col max-h-[90vh]">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center font-bold shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-900 leading-tight">
                                    Audit Log Inspector
                                </h3>
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-mono text-xs font-bold">#{{ $selectedLog->id }}</span>
                            </div>
                            <span class="text-xs text-slate-400 font-mono">{{ $selectedLog->created_at->format('M d, Y • h:i:s A T') }}</span>
                        </div>
                    </div>
                    <button wire:click="closeDetailModal" type="button" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-800 hover:bg-slate-200 flex items-center justify-center transition-colors cursor-pointer font-bold text-sm">
                        ✕
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 overflow-y-auto flex-1">
                    
                    <!-- Metadata Summary Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Actor</span>
                            <span class="text-xs font-bold text-slate-900 block truncate">{{ $selectedLog->user->name ?? 'System Automated' }}</span>
                            <span class="text-[10.5px] text-slate-500 block truncate font-mono">{{ $selectedLog->user->email ?? 'system@nexus' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Action</span>
                            <span class="text-xs font-mono font-extrabold text-[#c3122e]">{{ $selectedLog->action }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Module</span>
                            <span class="text-xs font-bold text-slate-800">{{ ucwords(str_replace('_', ' ', $selectedLog->module)) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Record Target</span>
                            @if(!empty($selectedLogRecord['title']))
                                <span class="text-xs font-bold text-slate-900 block truncate">{{ $selectedLogRecord['title'] }}</span>
                            @else
                                <span class="text-xs font-bold text-slate-900 block truncate">{{ $selectedLog->module === 'settings' ? 'Global System Configuration' : 'System Record' }}</span>
                            @endif
                            <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5 flex-wrap">
                                <span>{{ class_basename($selectedLog->record_type) }} {{ $selectedLog->record_id ? '#' . $selectedLog->record_id : '' }}</span>
                                @if(!empty($selectedLogRecord['code']))
                                    <span class="text-slate-300">•</span>
                                    <span class="font-mono text-[#c3122e] bg-rose-50 border border-rose-200/70 px-1.5 py-0.2 rounded text-[10px] font-bold">{{ $selectedLogRecord['code'] }}</span>
                                @endif
                                @if(!empty($selectedLogRecord['url']))
                                    <a href="{{ $selectedLogRecord['url'] }}" target="_blank" title="Open project workspace in new tab" class="text-[#c3122e] hover:underline inline-flex items-center ml-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Client IP</span>
                            <span class="text-xs font-mono font-bold text-slate-700">{{ $selectedLog->ip_address ?: '127.0.0.1' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Occurred</span>
                            <span class="text-xs font-bold text-emerald-600">{{ $selectedLog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Filter Shortcuts Row -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($selectedLog->user_id)
                            <button wire:click="filterByUser({{ $selectedLog->user_id }})" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Filter by this User</span>
                            </button>
                        @endif
                        <button wire:click="filterByAction('{{ $selectedLog->action }}')" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span>Filter by this Action</span>
                        </button>
                        @if($selectedLog->record_id)
                            <button wire:click="filterByRecord('{{ $selectedLog->record_id }}')" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                <span>Filter by Record #{{ $selectedLog->record_id }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- User Agent Device Information -->
                    @if($selectedLog->user_agent)
                        <div class="p-3 bg-slate-50/80 rounded-xl border border-slate-100 text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">User Agent &amp; Environment</span>
                            <span class="text-[11px] font-mono text-slate-600 break-all leading-relaxed block">{{ $selectedLog->user_agent }}</span>
                        </div>
                    @endif

                    <!-- Payload Comparison / Values Diff -->
                    @php
                        $hasPayload = $selectedLog->previous_values || $selectedLog->new_values;
                        $allKeys = array_unique(array_merge(
                            array_keys($selectedLog->previous_values ?? []),
                            array_keys($selectedLog->new_values ?? [])
                        ));
                    @endphp

                    @if($hasPayload)
                        <div class="space-y-3">
                            <!-- Diff Mode Switcher -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Payload Changes</span>
                                <div class="flex items-center p-0.5 bg-slate-100 rounded-lg text-xs font-bold">
                                    <button wire:click="setDiffViewMode('visual')" type="button" class="px-2.5 py-1 rounded-md transition-all cursor-pointer {{ $diffViewMode === 'visual' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                                        Visual Diff
                                    </button>
                                    <button wire:click="setDiffViewMode('json')" type="button" class="px-2.5 py-1 rounded-md transition-all cursor-pointer {{ $diffViewMode === 'json' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800' }}">
                                        Raw JSON
                                    </button>
                                </div>
                            </div>

                            @if($diffViewMode === 'visual')
                                <!-- Formatted Visual Diff Table -->
                                <div class="border border-slate-200/90 rounded-xl overflow-hidden bg-white shadow-2xs">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead>
                                            <tr class="bg-slate-50 border-b border-slate-200 text-[10.5px] font-bold uppercase tracking-wider text-slate-500">
                                                <th class="py-2.5 px-4 w-1/3">Field / Attribute</th>
                                                <th class="py-2.5 px-4 w-1/3">Previous Value</th>
                                                <th class="py-2.5 px-4 w-1/3">Applied Value</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($allKeys as $key)
                                                @php
                                                    $oldVal = $selectedLog->previous_values[$key] ?? null;
                                                    $newVal = $selectedLog->new_values[$key] ?? null;
                                                @endphp
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="py-2.5 px-4 font-mono font-bold text-slate-800 align-top">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                        <span class="block text-[10px] text-slate-400 font-mono">{{ $key }}</span>
                                                    </td>
                                                    <td class="py-2.5 px-4 align-top">
                                                        @if($oldVal !== null)
                                                            <div class="font-mono text-xs text-rose-700 bg-rose-50 px-2 py-1 rounded-md border border-rose-100 break-all leading-relaxed">
                                                                {{ is_array($oldVal) ? json_encode($oldVal, JSON_UNESCAPED_SLASHES) : (string)$oldVal }}
                                                            </div>
                                                        @else
                                                            <span class="text-slate-300 font-mono text-xs">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2.5 px-4 align-top">
                                                        @if($newVal !== null)
                                                            <div class="font-mono text-xs text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100 break-all leading-relaxed">
                                                                {{ is_array($newVal) ? json_encode($newVal, JSON_UNESCAPED_SLASHES) : (string)$newVal }}
                                                            </div>
                                                        @else
                                                            <span class="text-slate-300 font-mono text-xs">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <!-- Raw JSON Diff View -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Previous State</span>
                                            <span class="text-[10px] font-mono font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-100">Before</span>
                                        </div>
                                        <pre class="p-3 bg-slate-900 text-rose-300 rounded-xl text-[11px] font-mono overflow-x-auto border border-slate-800 shadow-inner max-h-56">{{ $selectedLog->previous_values ? json_encode($selectedLog->previous_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '// No previous state recorded' }}</pre>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Applied State</span>
                                            <span class="text-[10px] font-mono font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">After</span>
                                        </div>
                                        <pre class="p-3 bg-slate-900 text-emerald-300 rounded-xl text-[11px] font-mono overflow-x-auto border border-slate-800 shadow-inner max-h-56">{{ $selectedLog->new_values ? json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '// No new state recorded' }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-center text-xs text-slate-400">
                            No modified payload values attached to this audit event.
                        </div>
                    @endif

                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-between shrink-0">
                    <span class="text-[11px] text-slate-400 font-mono">Immutable cryptographic hash verified</span>
                    <button wire:click="closeDetailModal" type="button" class="px-5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200/90 shadow-2xs transition-all cursor-pointer">
                        Close
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
