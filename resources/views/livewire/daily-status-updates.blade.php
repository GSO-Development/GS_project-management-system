<div data-ui-page="daily-updates" class="ui-page ui-page-collaboration space-y-6 pb-12">
    <style>
        html:has(.daily-history-backdrop), body:has(.daily-history-backdrop),
        html:has(.daily-history-dialog[open]), body:has(.daily-history-dialog[open]) { overflow: hidden; }
        .daily-history-backdrop { position: fixed; inset: 0; margin: 0; width: 100%; height: 100dvh; }
        .daily-history-panel { width: min(100%, 1080px); max-height: min(820px, calc(100dvh - 48px)); min-height: 0; margin: auto; box-sizing: border-box; border-radius: 18px; box-shadow: 0 32px 80px -24px rgba(15,23,42,.42); }
        .daily-history-body { min-height: 0; overscroll-behavior: contain; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; overflow-wrap: anywhere; }
        .daily-history-header > div { min-width: 0; }
        .daily-history-header button { flex-shrink: 0; }
        .daily-history-panel button:focus-visible { outline: 2px solid #c3122e; outline-offset: 3px; }
        .daily-history-header { min-height: 82px; }
        .daily-history-title-icon { width: 42px; height: 42px; border: 1px solid #fecdd3; border-radius: 12px; color: #c3122e; background: #fff1f2; }
        .daily-history-close { display: inline-flex; width: 38px; height: 38px; align-items: center; justify-content: center; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; }
        .daily-history-tabs { gap: 4px; background: #fff; }
        .daily-history-tabs > button { position: relative; border-radius: 0 !important; box-shadow: none !important; }
        .daily-discussion-heading { padding: 0 2px; }
        .daily-comment-composer { border-color: #dbe3ec; border-radius: 14px; box-shadow: 0 8px 24px -22px rgba(15,23,42,.55); }
        .daily-visibility { background: #f1f5f9; border-color: #e2e8f0; }
        .daily-comment-form input { min-height: 42px; border-color: #d8e0e9; background: #fff; box-shadow: 0 1px 2px rgba(15,23,42,.03); }
        .daily-comment-submit { min-height: 42px; box-shadow: 0 8px 18px -12px rgba(195,18,46,.65); }
        .daily-comment-entry { border-color: #e2e8f0; border-radius: 14px; box-shadow: 0 8px 22px -24px rgba(15,23,42,.6); transition: border-color .15s ease, box-shadow .15s ease; }
        .daily-comment-entry:hover { border-color: #cbd5e1; box-shadow: 0 12px 26px -24px rgba(15,23,42,.65); }
        @media (max-width: 640px) {
            dialog.daily-history-dialog { padding: 12px; }
            .daily-history-panel { max-height: calc(100dvh - 24px); }
            .daily-history-header, .daily-history-body { padding: 16px; }
            .daily-history-header { min-height: 72px; }
            .daily-history-tabs { padding-left: 12px !important; padding-right: 12px !important; }
            .daily-history-tabs > button { padding-left: 12px !important; padding-right: 12px !important; }
            .daily-comment-composer { padding: 14px !important; }
            .daily-comment-form { flex-direction: column; }
            .daily-comment-submit { width: 100%; }
            .daily-comment-entry > div:first-child { align-items: flex-start; gap: 10px; }
            .daily-comment-entry > div:first-child > div:last-child { width: 100%; justify-content: space-between; }
        }

        .daily-update-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .daily-update-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .daily-update-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .daily-update-scroll::-webkit-scrollbar-thumb:hover {
            background: #c3122e;
        }
        @keyframes slideDownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .filter-toolbar-enter {
            animation: slideDownFade 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes unreadLogCountPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(195, 18, 46, .28); }
            50% { transform: scale(1.12); box-shadow: 0 0 0 5px rgba(195, 18, 46, 0); }
        }
        .dur-count.has-unread-logs {
            border-color: #fb7185 !important;
            background: #c3122e !important;
            color: #fff !important;
            animation: unreadLogCountPulse 1.25s ease-in-out infinite;
        }
        .dur-btn--logs.has-unread-logs {
            border-color: #fda4af !important;
            background: #fff1f2 !important;
            color: #9f1239 !important;
        }
        @media (prefers-reduced-motion: reduce) {
            .dur-count.has-unread-logs { animation: none; }
        }
        .daily-page-overview { padding-top: 2px; }
        .daily-metric-grid { display: grid; grid-template-columns: repeat(3, minmax(92px, 1fr)); gap: 8px; }
        .daily-metric { min-width: 0; min-height: 54px; padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 11px; background: #fff; }
        .daily-metric__value { color: #111827; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; font-size: 16px; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
        .daily-metric__label { margin-top: 5px; color: #7c8798; font-size: 9px; font-weight: 750; letter-spacing: .07em; text-transform: uppercase; }
        .daily-metric.is-unread .daily-metric__value { color: #c3122e; }
        .daily-filter-label { display: block; margin: 0 0 6px 2px; color: #64748b; font-size: 9.5px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
        .daily-register-scroll { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        .daily-register-scroll::-webkit-scrollbar { height: 8px; }
        .daily-register-scroll::-webkit-scrollbar-thumb { border-radius: 999px; background: #cbd5e1; }
        .daily-register-scroll::-webkit-scrollbar-track { background: transparent; }
        .daily-updates-ledger { box-shadow: 0 1px 2px rgba(15,23,42,.02), 0 12px 28px -26px rgba(15,23,42,.32) !important; }
        .daily-updates-ledger__toolbar { background: #fff; }
        .daily-updates-table tbody td { padding-top: 14px !important; padding-bottom: 14px !important; border-bottom: 1px solid #eef2f6; }
        .daily-updates-table tbody tr:last-child td { border-bottom: 0; }
        .daily-updates-table tbody tr { transition: background-color .16s ease, box-shadow .16s ease; }
        .daily-updates-table tbody tr:hover { background: #f8fafc !important; }
        .daily-updates-table tbody tr.has-unread-row { background: #fffafb; box-shadow: inset 3px 0 0 #c3122e; }
        .daily-updates-table tbody tr.has-unread-row:hover { background: #fff5f6 !important; }
        .daily-updates-table thead th { position: sticky; top: 0; z-index: 2; }
        .daily-updates-table th:nth-child(1) { width: 25% !important; }
        .daily-updates-table th:nth-child(2) { width: 17% !important; }
        .daily-updates-table th:nth-child(3) { width: 24% !important; }
        .daily-updates-table th:nth-child(4) { width: 13% !important; }
        .daily-updates-table th:nth-child(5) { width: 21% !important; }
        .daily-updates-table :is(th, td):last-child { padding-left: 12px !important; padding-right: 14px !important; }
        .daily-updates-table .dur-actions { display: flex !important; width: 100%; justify-content: flex-end !important; gap: 8px !important; }
        .daily-updates-table .dur-actions .dur-btn { width: auto !important; min-width: 0 !important; height: 34px !important; padding: 0 11px !important; border-radius: 9px !important; font-size: 11px !important; line-height: 1 !important; }
        .daily-updates-table .dur-actions .dur-btn--add { flex: 0 0 92px; justify-content: center; }
        .daily-updates-table .dur-actions .dur-btn--logs { flex: 0 0 72px; justify-content: center; }
        .daily-updates-table .dur-actions .dur-btn svg { width: 14px !important; height: 14px !important; flex: 0 0 14px; }
        .dur-project__headline { display: flex; align-items: center; gap: 8px; min-width: 0; }
        .dur-new-activity { display: inline-flex; align-items: center; gap: 5px; flex: 0 0 auto; padding: 3px 7px; border: 1px solid #fecdd3; border-radius: 999px; color: #be123c; background: #fff1f2; font-size: 9px; font-weight: 800; line-height: 1; text-transform: uppercase; letter-spacing: .04em; }
        .dur-new-activity__dot { width: 5px; height: 5px; border-radius: 999px; background: #e11d48; }
        .dur-owner__identity { display: flex; flex-direction: column; min-width: 0; }
        .dur-owner__role { margin-top: 2px; color: #94a3b8; font-size: 9.5px; font-weight: 600; }
        .daily-updates-table .dur-project__code {
            display: inline-flex !important;
            flex: 0 0 auto !important;
            width: max-content !important;
            max-width: none !important;
            white-space: nowrap !important;
            word-break: keep-all !important;
            overflow-wrap: normal !important;
        }
        .daily-update-button { box-shadow: none !important; }
        .daily-history-button { box-shadow: none !important; }
        @media (max-width: 767px) {
            .daily-metric-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .daily-updates-ledger__toolbar { padding: 14px 16px !important; }
            .daily-register-scroll { overflow-x: visible; }
            .daily-updates-table { min-width: 0 !important; table-layout: auto !important; }
            .daily-updates-table thead { display: none; }
            .daily-updates-table tbody { display: block; background: #f8fafc; }
            .daily-updates-table .dur-row {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(112px, .72fr);
                border-left: 0 !important;
                border-bottom: 1px solid #e2e8f0;
                background: #fff;
                box-shadow: none !important;
            }
            .daily-updates-table .dur-row.has-unread-row { box-shadow: inset 3px 0 0 #c3122e !important; background: #fffafb; }
            .daily-updates-table .dur-row > td { display: block; min-width: 0; height: auto; padding: 11px 16px !important; border: 0 !important; }
            .daily-updates-table .dur-row > td:first-child { grid-column: 1 / -1; padding-top: 16px !important; padding-bottom: 13px !important; border-bottom: 1px solid #f1f5f9 !important; }
            .daily-updates-table .dur-row > td:nth-child(2),
            .daily-updates-table .dur-row > td:nth-child(4) { padding-top: 12px !important; padding-bottom: 8px !important; }
            .daily-updates-table .dur-row > td:nth-child(3) { grid-column: 1 / -1; padding-top: 8px !important; padding-bottom: 12px !important; }
            .daily-updates-table .dur-row > td:nth-child(5) { grid-column: 1 / -1; padding-top: 4px !important; padding-bottom: 16px !important; }
            .daily-updates-table .dur-row > td[data-label]::before { content: attr(data-label); display: block; margin-bottom: 6px; color: #94a3b8; font-size: 9px; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; }
            .daily-updates-table .dur-project { align-items: flex-start; }
            .daily-updates-table .dur-project__text { width: calc(100% - 43px); }
            .daily-updates-table .dur-project__name { max-width: 100%; }
            .daily-updates-table .dur-project__meta { width: 100%; }
            .daily-updates-table .dur-project__code { white-space: nowrap !important; }
            .daily-updates-table .dur-project__sub { max-width: none; }
            .daily-updates-table .dur-entry__summary { max-width: 100%; }
            .daily-updates-table .dur-actions { justify-content: flex-start !important; }
            .daily-updates-table .dur-actions .dur-btn--add { flex-basis: 104px; }
            .daily-updates-table .dur-actions .dur-btn--logs { flex-basis: 78px; }
        }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & ACTION CONTROLS
         ═══════════════════════════════════════════════════════════════ -->
    @php $unreadUpdateCount = $allGroupedProjectUpdates->sum('unreadCount'); @endphp
    <section class="daily-page-overview" aria-labelledby="daily-updates-title">
    <div class="daily-updates-heading flex flex-col xl:flex-row xl:items-center justify-between gap-5">
        <div class="min-w-0">
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 id="daily-updates-title" class="text-2xl font-extrabold text-slate-950 tracking-[-.03em] leading-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Latest Updates
                </h1>
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1.5">
                Review project activity, delivery notes and team discussions.
            </p>
        </div>

        <!-- Top Right Action Controls -->
        <div class="w-full xl:w-auto flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="daily-metric-grid flex-1 xl:flex-none xl:w-[318px]">
                <div class="daily-metric"><div class="daily-metric__value">{{ $totalUpdatesCount }}</div><div class="daily-metric__label">Total logs</div></div>
                <div class="daily-metric"><div class="daily-metric__value">{{ $updatedTodayCount }}</div><div class="daily-metric__label">Today</div></div>
                <div class="daily-metric {{ $unreadUpdateCount > 0 ? 'is-unread' : '' }}"><div class="daily-metric__value">{{ $unreadUpdateCount }}</div><div class="daily-metric__label">Unread</div></div>
            </div>
            <!-- Filter Toggle Button -->
            <button wire:click="toggleFilters" type="button" aria-expanded="{{ $showFilters ? 'true' : 'false' }}" aria-controls="daily-update-filters"
                    class="h-11 px-4 border rounded-xl text-xs font-bold inline-flex items-center justify-center gap-2 transition-all cursor-pointer active:scale-95 shrink-0 {{ $showFilters ? 'bg-slate-900 border-slate-900 text-white shadow-md' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700 hover:text-slate-950 shadow-sm' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span>{{ $showFilters ? 'Hide filters' : 'Filter view' }}</span>
                @if($hasActiveFilters)
                    <span class="w-2 h-2 rounded-full {{ $showFilters ? 'bg-rose-300' : 'bg-[#c3122e]' }}"></span>
                @endif
            </button>
        </div>
    </div>    <!-- ═══════════════════════════════════════════════════════════════
         2. COLLAPSIBLE FILTER TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    </section>
    @if($showFilters)
        <div id="daily-update-filters" wire:key="daily-updates-filter-toolbar" class="daily-updates-filters bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-sm filter-toolbar-enter">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div><h2 class="text-xs font-extrabold text-slate-900">Refine update register</h2><p class="text-[10.5px] text-slate-500 mt-0.5">Combine filters to narrow operational activity.</p></div>
                @if($hasActiveFilters)<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 border border-rose-200 text-[10px] font-bold text-rose-700"><span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>Active</span>@endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                {{-- Search Box --}}
                <div class="relative">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="searchQuery" type="text"
                           placeholder="Search project, task, reporter..."
                           class="w-full pl-9 pr-8 py-2.5 bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all">
                    @if($searchQuery)
                        <button type="button" wire:click="$set('searchQuery', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer p-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    @endif
                </div>

                {{-- Subsidiary Selector Dropdown --}}
                <div>
                    <select wire:model.live="selectedSubsidiaryId"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="">All Subsidiaries</option>
                        @foreach($subsidiaries as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Project Selector --}}
                <div>
                    <select wire:model.live="selectedProjectId"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="">All Accessible Projects</option>
                        @foreach($accessibleProjects as $p)
                            @if(!$selectedSubsidiaryId || $p->subsidiary_id == $selectedSubsidiaryId)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Scope Filter Dropdown --}}
                <div>
                    <select wire:model.live="selectedScope"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="all">All Scopes</option>
                        <option value="task">Task Logs Only</option>
                        <option value="project">Project Summaries Only</option>
                    </select>
                </div>

                {{-- Date Filter Dropdown --}}
                <div>
                    <select wire:model.live="dateFilter"
                            class="w-full bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl px-3 py-2.5 text-xs font-medium text-slate-700 cursor-pointer transition-all">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="this_week">This Week</option>
                    </select>
                </div>
            </div>

            {{-- Filter Footer --}}
            @if($hasActiveFilters)
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-medium">Filtered results applied</span>
                    <button wire:click="clearFilters" type="button" class="text-[#c3122e] font-bold hover:underline cursor-pointer flex items-center gap-1 active:scale-95 transition-transform">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Clear All Filters</span>
                    </button>
                </div>
            @endif
        </div>
    @endif    <!-- ═══════════════════════════════════════════════════════════════
         3. PROJECTS DAILY UPDATES MATRIX TABLE
         ═══════════════════════════════════════════════════════════════ -->
    <div class="daily-updates-ledger w-full rounded-2xl bg-white border border-slate-200 overflow-hidden transition-all">
        <!-- Section Bar / Table Header Toolbar -->
        <div class="daily-updates-ledger__toolbar px-5 sm:px-6 py-4 border-b border-slate-200 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 shrink-0">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Project activity register
                    </h2>
                </div>
            </div>
            
            <div class="flex items-center gap-3 text-xs">
                @if($hasActiveFilters)
                    <div class="flex items-center gap-2 bg-rose-50/80 border border-rose-200/70 px-3 py-1.5 rounded-xl shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                        <span class="text-xs font-bold text-rose-900">Filtered view</span>
                        <button wire:click="clearFilters" type="button" class="ml-1.5 text-xs text-[#c3122e] font-bold hover:underline cursor-pointer flex items-center gap-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Reset</span>
                        </button>
                    </div>
                @else
                    <span class="daily-project-count hidden sm:inline-flex items-center gap-2 text-[11px] font-semibold text-slate-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $groupedProjectUpdates->total() }} projects
                    </span>
                @endif
            </div>
        </div>

        <div class="daily-register-scroll" x-data x-init="$el.scrollLeft = 0" tabindex="0" role="region" aria-label="Project updates table">
            <table class="daily-updates-table w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-[.09em] text-slate-500" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <th scope="col" class="py-3.5 pl-6 pr-4 w-[25%]">Project</th>
                        <th scope="col" class="py-3.5 px-4 w-[17%]">Project Manager</th>
                        <th scope="col" class="py-3.5 px-4 w-[24%]">Latest activity</th>
                        <th scope="col" class="py-3.5 px-4 w-[13%]">Last updated</th>
                        <th scope="col" class="py-3.5 pl-3 pr-6 text-right w-[21%] whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs bg-white">
                    @forelse($groupedProjectUpdates as $group)
                        @php
                            $project = $group['project'];
                            $update = $group['latestUpdate'];
                            $pastUpdates = $group['pastUpdates'];
                            $allUpdatesCount = $group['allUpdates']->count();
                            $unreadUpdatesCount = $group['unreadCount'] ?? 0;
                            $pm = $project->projectManager;

                            $pmName = $pm->name ?? 'Unassigned';
                            $pmParts = explode(' ', trim($pmName));
                            $pmInitials = count($pmParts) >= 2 
                                ? strtoupper(substr($pmParts[0], 0, 1) . substr($pmParts[count($pmParts) - 1], 0, 1))
                                : strtoupper(substr($pmName, 0, 2));

                            $projInitial = strtoupper(substr($project->name, 0, 1));
                            $subsidiaryLabel = null;
                            if ($project->subsidiary) {
                                $subsidiaryWords = preg_split('/\s+/', trim($project->subsidiary->name));
                                $subsidiaryLabel = collect($subsidiaryWords)
                                    ->map(fn ($word, $index) => $index < 2 ? $word : \Illuminate\Support\Str::substr($word, 0, 3))
                                    ->implode(' ');
                            }
                        @endphp

                        <tr
                            class="daily-update-row dur-row group {{ $update?->current_blockers ? 'has-blocker' : '' }}"
                            :class="{ 'has-unread-row': unreadLogs > 0 }"
                            x-data="{ unreadLogs: {{ $unreadUpdatesCount }} }"
                            @daily-updates-read.window="if (Number($event.detail.projectId) === {{ $project->id }}) unreadLogs = Number($event.detail.projectUnreadCount)"
                        >
                            <!-- 1. Project & Code -->
                            <td class="py-3.5 pl-6 pr-4 align-middle" data-label="Project">
                                <div class="dur-project">
                                    <span class="dur-folder" aria-hidden="true">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                        </svg>
                                    </span>
                                    <div class="dur-project__text min-w-0">
                                        <div class="dur-project__headline">
                                            <a href="{{ route('projects.show', $project) }}" class="dur-project__name" title="{{ $project->name }}">{{ \Illuminate\Support\Str::limit($project->name, 32) }}</a>
                                            <span x-cloak x-show="unreadLogs > 0" class="dur-new-activity">
                                                <span class="dur-new-activity__dot"></span>
                                                <span x-text="`${unreadLogs} new`"></span>
                                            </span>
                                        </div>
                                        <div class="dur-project__meta">
                                            <span class="dur-project__code">{{ preg_replace('/\s+/', '', $project->code ?? 'PRJ-' . $project->id) }}</span>
                                            @if($project->subsidiary)
                                                <span class="dur-project__sub truncate" title="{{ $project->subsidiary->name }}">&bull; {{ $subsidiaryLabel }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 2. Project Manager -->
                            <td class="py-3.5 px-4 align-middle" data-label="Project manager">
                                <div class="dur-owner">
                                    <span class="dur-owner__avatar">{{ $pmInitials }}</span>
                                    <span class="dur-owner__identity">
                                        <span class="dur-owner__name" title="{{ $pmName }}">{{ $pmName }}</span>
                                        <span class="dur-owner__role">{{ $pm ? 'Project manager' : 'Not assigned' }}</span>
                                    </span>
                                </div>
                            </td>

                            <!-- 3. Latest Update Summary -->
                            <td class="py-3.5 px-4 align-middle" data-label="Latest activity">
                                @if($update)
                                    <div class="dur-entry">
                                        <p class="dur-entry__summary" title="{{ $update->summary ?: $update->title }}">
                                            {{ \Illuminate\Support\Str::limit($update->summary ?: $update->title, 48) }}
                                        </p>
                                        @php
                                            $visibleRowCommentsCount = $this->getVisibleCommentsForUpdate($update, $project, auth()->user())->count();
                                        @endphp
                                        @if($update->current_blockers || $update->wbsItem || $visibleRowCommentsCount > 0)
                                            <div class="dur-entry__meta">
                                                @if($update->current_blockers)
                                                    <span class="dur-blocker" title="Blocker reported">
                                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                        Blocker
                                                    </span>
                                                @endif
                                                @if($update->wbsItem)
                                                    <span class="dur-task" title="WBS Task: {{ $update->wbsItem->title }}">
                                                        <span class="dur-task__label">Task</span>
                                                        <span class="dur-task__title">{{ \Illuminate\Support\Str::limit($update->wbsItem->title, 25) }}</span>
                                                    </span>
                                                @endif
                                                @if($visibleRowCommentsCount > 0)
                                                    <span class="dur-comments-pill" title="{{ $visibleRowCommentsCount }} comment(s)">
                                                        💬 {{ $visibleRowCommentsCount }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @else
                                    <div class="dur-empty">
                                        <span class="dur-empty__dot"></span>
                                        <span>Awaiting first update</span>
                                    </div>
                                @endif
                            </td>

                            <!-- 4. Last Activity -->
                            <td class="py-3.5 px-4 align-middle whitespace-nowrap" data-label="Last updated">
                                @if($update)
                                    <div class="dur-time">
                                        <span class="dur-time__relative">{{ $update->created_at->diffForHumans() }}</span>
                                        <span class="dur-time__date">{{ $update->created_at->format('M d, Y') }}</span>
                                    </div>
                                @else
                                    <span class="dur-time__none">—</span>
                                @endif
                            </td>

                            <!-- 5. Actions -->
                            <td class="py-3.5 pl-3 pr-6 align-middle text-right whitespace-nowrap" data-label="Actions">
                                <div class="dur-actions">
                                    @unless(auth()->user()?->isAdminViewer())
                                    <button
                                        wire:click="openStatusUpdateModal({{ $project->id }})"
                                        type="button"
                                        class="dur-btn dur-btn--add"
                                        title="Log latest progress update"
                                    >
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        <span>Add update</span>
                                    </button>
                                    @endunless

                                    <button
                                        wire:click="openHistoryModal({{ $project->id }})"
                                        type="button"
                                        class="dur-btn dur-btn--logs {{ $allUpdatesCount > 0 ? 'is-active-logs' : 'is-zero-logs' }}"
                                        :class="{ 'has-unread-logs': unreadLogs > 0 }"
                                        :title="unreadLogs > 0 ? `${unreadLogs} new log or comment notification${unreadLogs === 1 ? '' : 's'}` : 'View project logs history'"
                                    >
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Logs</span>
                                        <span
                                            class="dur-count {{ $allUpdatesCount > 0 ? 'has-logs' : '' }}"
                                            :class="{ 'has-unread-logs': unreadLogs > 0 }"
                                            :aria-label="unreadLogs > 0 ? `${unreadLogs} new; ${@js($allUpdatesCount)} total logs` : `${@js($allUpdatesCount)} total logs`"
                                        >{{ $allUpdatesCount }}</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-xs text-slate-400">
                                <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No Projects Found</h3>
                                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">No accessible projects match your current search and filter criteria.</p>
                                @if($hasActiveFilters)
                                    <div class="pt-3">
                                        <button wire:click="clearFilters" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#c3122e] bg-rose-50 border border-rose-100 hover:bg-rose-100 active:scale-95 transition-all cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>Clear All Filters</span>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-6 py-3.5 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                {{ $groupedProjectUpdates->links() }}
            </div>

            <div class="flex items-center gap-2 flex-shrink-0 self-end sm:self-center">
                <span class="text-xs text-slate-500 font-medium hidden sm:inline">Per page:</span>
                <select wire:model.live="perPage" class="text-xs font-bold py-1.5 px-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white text-slate-700 focus:outline-none focus:border-[#c3122e] transition-all cursor-pointer">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         5. MODAL: PROJECT STATUS UPDATE HISTORY & FEEDBACK LOOP POPUP
         ═══════════════════════════════════════════════════════════════ -->
    @if($showHistoryModal && $historyProjectId)
        @php
            $historyProject = \App\Models\Project::with(['projectManager', 'subsidiary'])->find($historyProjectId);
            $authUser = auth()->user();
            $isPmOrAdminForHistory = $authUser && $historyProject && $this->canViewAllProjectUpdates($historyProject, $authUser);

            $historyQuery = \App\Models\ProjectStatusUpdate::with(['creator.roles', 'comments.user.roles', 'wbsItem'])
                ->where('project_id', $historyProjectId);

            $historyAllUpdates = $historyProject ? $historyQuery->latest()->get() : collect();
            $historyLatestUpdate = $selectedHistoryUpdateId 
                ? ($historyAllUpdates->firstWhere('id', $selectedHistoryUpdateId) ?? $historyAllUpdates->first())
                : $historyAllUpdates->first();
            $historyUpdatesCount = $historyAllUpdates->count();
            $visibleComments = $historyLatestUpdate
                ? $this->getVisibleCommentsForUpdate($historyLatestUpdate, $historyProject, $authUser)
                : collect();
        @endphp

        @if($historyProject)
            @teleport('body')
            <div
                class="daily-history-backdrop fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-6 overflow-y-auto"
                style="position: fixed; inset: 0; z-index: 99999; background: rgba(15, 23, 42, 0.55); backdrop-filter: blur(6px);"
                role="dialog"
                aria-modal="true"
                aria-labelledby="daily-history-title"
                x-data="{ modalTab: @entangle('historyTab').live }"
                @keydown.escape.window="$wire.closeHistoryModal()"
                wire:key="daily-history-{{ $historyProjectId }}"
            >
                <!-- Backdrop click to close -->
                <div class="fixed inset-0 -z-10" @click="$wire.closeHistoryModal()"></div>

                <!-- Popup Content Box -->
                <div class="daily-history-panel bg-white rounded-2xl w-full shadow-2xl flex flex-col overflow-hidden border border-slate-200 relative z-10 m-auto"
                     style="margin: auto;"
                     @click.stop>
                    <!-- Modal Header -->
                    <div class="daily-history-header px-6 py-4 flex items-center justify-between gap-3 border-b border-slate-200 bg-white flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="daily-history-title-icon flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 id="daily-history-title" class="text-sm sm:text-base font-bold text-slate-900 leading-tight">
                                        Project activity
                                    </h3>
                                    @if($historyProject->code)
                                        <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            {{ $historyProject->code }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 font-normal mt-0.5 truncate">
                                    {{ $historyProject->name }} &bull; <span class="text-slate-700 font-semibold">{{ $historyUpdatesCount }} progress log(s)</span>
                                </p>
                            </div>
                        </div>
                        <button 
                            wire:click="closeHistoryModal" 
                            type="button" 
                            class="daily-history-close text-slate-400 hover:text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer"
                            title="Close Window" aria-label="Close project activity logs" autofocus
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Navigation Tabs Bar -->
                    <div class="daily-history-tabs px-6 pt-2 pb-0 border-b border-slate-200 flex items-center overflow-x-auto scrollbar-none flex-shrink-0">
                        <button 
                            @click="modalTab = 'report'" 
                            :class="modalTab === 'report' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>📌 Progress Report</span>
                        </button>

                        <button 
                            @click="modalTab = 'discussion'" 
                            :class="modalTab === 'discussion' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>💬 Team Discussion</span>
                            @php $commentsCnt = $visibleComments->count(); @endphp
                            <span :class="modalTab === 'discussion' ? 'bg-rose-50 text-[#c3122e] border-rose-200' : 'bg-slate-200/70 text-slate-600 border-slate-300'" class="px-2 py-0.2 rounded-full text-[10px] font-bold border">
                                {{ $commentsCnt }}
                            </span>
                        </button>

                        <button 
                            @click="modalTab = 'history'" 
                            :class="modalTab === 'history' ? 'border-[#c3122e] text-[#c3122e] font-black bg-white shadow-2xs' : 'border-transparent text-slate-600 hover:text-slate-900 font-bold hover:bg-white/50'"
                            class="px-4 py-2.5 rounded-t-xl text-xs border-b-2 transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap"
                        >
                            <span>📜 Log History</span>
                            <span :class="modalTab === 'history' ? 'bg-rose-50 text-[#c3122e] border-rose-200' : 'bg-slate-200/70 text-slate-600 border-slate-300'" class="px-2 py-0.2 rounded-full text-[10px] font-bold border">
                                {{ $historyUpdatesCount }}
                            </span>
                        </button>
                    </div>

                    <!-- Modal Body Container -->
                    <div class="daily-history-body flex-1 overflow-y-auto p-6 bg-[#f7f9fc]">
                        @if($historyLatestUpdate)
                            <!-- TAB 1: PROGRESS REPORT -->
                            <div x-show="modalTab === 'report'" class="daily-report space-y-4">
                                <div class="daily-discussion-heading flex items-center justify-between">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span>Latest progress report</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                                        Log #{{ $historyUpdatesCount }}
                                    </span>
                                </div>

                                <div class="daily-report-card p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
                                    <!-- Author Row -->
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-9 h-9 rounded-xl font-bold flex items-center justify-center text-xs text-white shadow-2xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                {{ strtoupper(substr($historyLatestUpdate->creator->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-sm text-slate-900 block leading-tight">{{ $historyLatestUpdate->creator->name ?? 'User' }}</span>
                                                <span class="text-xs text-slate-500 font-medium block mt-0.5">{{ $historyLatestUpdate->created_at->format('M d, Y · h:i A') }} ({{ $historyLatestUpdate->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                        <button @click="modalTab = 'discussion'" class="text-xs font-bold text-[#c3122e] hover:underline flex items-center gap-1 cursor-pointer">
                                            <span>💬 {{ $visibleComments->count() }} comment(s)</span>
                                            <span>→</span>
                                        </button>
                                    </div>

                                    <!-- Content -->
                                    <div class="space-y-2">
                                        <h4 class="font-bold text-base text-slate-900 leading-snug">{{ $historyLatestUpdate->title }}</h4>
                                        
                                        <!-- Summary Box -->
                                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                                            {{ $historyLatestUpdate->summary }}
                                        </div>
                                    </div>

                                    <!-- Work Completed & Blockers -->
                                    @if($historyLatestUpdate->work_completed || $historyLatestUpdate->current_blockers)
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                                            @if($historyLatestUpdate->work_completed)
                                                <div class="p-4 rounded-xl bg-emerald-50/80 text-emerald-900 border border-emerald-200/70 font-medium flex items-start gap-2.5">
                                                    <span class="text-emerald-600 font-bold text-sm">✓</span>
                                                    <div>
                                                        <strong class="font-bold text-emerald-950 block mb-0.5">Work Completed</strong> 
                                                        <span class="leading-relaxed">{{ $historyLatestUpdate->work_completed }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($historyLatestUpdate->current_blockers)
                                                <div class="p-4 rounded-xl bg-rose-50/80 text-rose-900 border border-rose-200/70 font-medium flex items-start gap-2.5">
                                                    <span class="text-rose-600 font-bold text-sm">⚠️</span>
                                                    <div>
                                                        <strong class="font-bold text-rose-950 block mb-0.5">Reported Blockers</strong> 
                                                        <span class="leading-relaxed">{{ $historyLatestUpdate->current_blockers }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- TAB 2: TEAM DISCUSSION -->
                            <div x-cloak x-show="modalTab === 'discussion'" class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span>💬 Team Discussion &amp; Feedback Thread</span>
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium">{{ $visibleComments->count() }} comment(s)</span>
                                </div>

                                <!-- Add Comment Form -->
                                <div x-data="{ commentVis: '{{ $commentVisibility }}' }" class="daily-comment-composer p-5 bg-white border space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <label class="text-xs font-bold text-slate-800 block">Add Feedback or Comment</label>

                                        @if($isPmOrAdminForHistory)
                                            <div class="daily-visibility inline-flex items-center gap-1.5 p-1 rounded-xl border select-none">
                                                <span class="text-[10px] font-bold text-slate-500 pl-2 pr-1">Send to:</span>
                                                @if($isSuperAdmin)
                                                    <button
                                                        type="button"
                                                        @click="commentVis = 'pmo_pm'"
                                                        :class="commentVis === 'pmo_pm' ? 'bg-white text-[#c3122e] shadow-2xs ring-1 ring-rose-300 font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                                                        class="px-2.5 py-1 rounded-lg text-[11px] transition-all cursor-pointer flex items-center gap-1.5"
                                                        title="Visible only to the Project Manager and PMO Admin"
                                                    >
                                                        <span class="w-1.5 h-1.5 rounded-full transition-colors" :class="commentVis === 'pmo_pm' ? 'bg-[#c3122e]' : 'bg-slate-400'"></span>
                                                        <span>PM</span>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="commentVis = 'team'"
                                                        :class="commentVis === 'team' ? 'bg-white text-emerald-700 shadow-2xs ring-1 ring-emerald-300 font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                                                        class="px-2.5 py-1 rounded-lg text-[11px] transition-all cursor-pointer flex items-center gap-1.5"
                                                        title="Visible to both Project Manager and Team Members"
                                                    >
                                                        <span class="w-1.5 h-1.5 rounded-full transition-colors" :class="commentVis === 'team' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                                        <span>Both</span>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        @click="commentVis = 'team'"
                                                        :class="commentVis === 'team' ? 'bg-white text-emerald-700 shadow-2xs ring-1 ring-emerald-300 font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                                                        class="px-2.5 py-1 rounded-lg text-[11px] transition-all cursor-pointer flex items-center gap-1.5"
                                                        title="Feedback visible to the team member and project manager"
                                                    >
                                                        <span class="w-1.5 h-1.5 rounded-full transition-colors" :class="commentVis === 'team' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                                        <span>Team Member</span>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="commentVis = 'pmo_pm'"
                                                        :class="commentVis === 'pmo_pm' ? 'bg-white text-[#c3122e] shadow-2xs ring-1 ring-rose-300 font-black' : 'text-slate-600 hover:text-slate-900 font-bold'"
                                                        class="px-2.5 py-1 rounded-lg text-[11px] transition-all cursor-pointer flex items-center gap-1.5"
                                                        title="Private comment strictly between PMO Admin & Project Manager"
                                                    >
                                                        <span class="w-1.5 h-1.5 rounded-full transition-colors" :class="commentVis === 'pmo_pm' ? 'bg-[#c3122e]' : 'bg-slate-400'"></span>
                                                        <span>PMO Admin Only</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="daily-comment-form flex gap-2.5">
                                        <input
                                            type="text"
                                            wire:model="newCommentContent.{{ $historyLatestUpdate->id }}"
                                            @keydown.enter.prevent="$wire.addComment({{ $historyLatestUpdate->id }}, commentVis)"
                                            :placeholder="commentVis === 'pmo_pm' ? '{{ $isSuperAdmin ? 'Write a comment visible only to the Project Manager...' : 'Write a private comment between Project Manager & PMO Admin...' }}' : '{{ $isSuperAdmin ? 'Write a comment visible to both Project Manager & Team Members...' : 'Write feedback visible to the Team Member...' }}'"
                                            placeholder="{{ $isPmOrAdminForHistory ? ($commentVisibility === 'pmo_pm' ? ($isSuperAdmin ? 'Write a comment visible only to the Project Manager...' : 'Write a private comment between Project Manager & PMO Admin...') : ($isSuperAdmin ? 'Write a comment visible to both Project Manager & Team Members...' : 'Write feedback visible to the Team Member...')) : 'Type a feedback comment or reply...' }}"
                                            class="w-full text-xs font-medium py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all flex-1"
                                        >
                                        <button
                                            type="button"
                                            @click="$wire.addComment({{ $historyLatestUpdate->id }}, commentVis)"
                                            class="daily-comment-submit px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-[#c3122e] hover:bg-[#8b0d1f] active:scale-95 transition-all cursor-pointer flex-shrink-0"
                                        >
                                            Post Comment
                                        </button>
                                    </div>
                                </div>

                                <!-- Comments Thread List -->
                                <div class="space-y-3 max-h-[360px] overflow-y-auto pr-1">
                                    @forelse($visibleComments as $comment)
                                        @php
                                            $cAuthor = $comment->user;
                                            $cRoleLabel = 'Team Member';
                                            $cRoleBadge = 'bg-slate-100 text-slate-600 border-slate-200';
                                            if ($cAuthor) {
                                                if ($this->isSuperAdminUser($cAuthor)) {
                                                    $cRoleLabel = 'PMO Admin';
                                                    $cRoleBadge = 'bg-rose-50 text-[#c3122e] border-rose-200';
                                                } elseif ($historyProject && $this->isProjectManagerOf($historyProject, $cAuthor)) {
                                                    $cRoleLabel = 'Project Manager';
                                                    $cRoleBadge = 'bg-amber-50 text-amber-800 border-amber-200';
                                                }
                                            }
                                            $cVis = $comment->visibility;
                                        @endphp
                                        <div class="daily-comment-entry p-4 bg-white border text-xs space-y-2">
                                            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <div class="w-6 h-6 rounded-lg bg-slate-900 text-white font-bold text-[10px] flex items-center justify-center">
                                                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span class="font-bold text-slate-900">{{ $comment->user->name ?? 'User' }}</span>
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $cRoleBadge }}">{{ $cRoleLabel }}</span>
                                                    @if($isPmOrAdminForHistory)
                                                        @if($cVis === 'pmo_pm')
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                                🔒 {{ $isSuperAdmin ? 'PM Only' : 'PMO Admin & PM Only' }}
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                👥 {{ $isSuperAdmin ? 'Both (PM & Team)' : 'Team Member Visible' }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[10px] text-slate-500 font-semibold whitespace-nowrap">
                                                        {{ $comment->created_at->format('M d, Y · h:i A') }} ({{ $comment->created_at->diffForHumans() }})
                                                    </span>
                                                    @if($isSuperAdmin || $comment->user_id === auth()->id() || ($historyProject && $this->isProjectManagerOf($historyProject, auth()->user())))
                                                        <button
                                                            wire:click="deleteComment({{ $comment->id }})"
                                                            wire:confirm="Are you sure you want to delete this comment?"
                                                            type="button"
                                                            class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                                            title="Delete Comment"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-slate-700 leading-relaxed pt-1 font-medium">{{ $comment->content }}</p>
                                        </div>
                                    @empty
                                        <div class="py-12 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200 p-6 space-y-1">
                                            <div class="text-2xl">💬</div>
                                            <p class="font-bold text-slate-700 text-sm">No feedback comments yet</p>
                                            <p class="text-slate-400 text-xs">Be the first to share feedback or ask a question for this project update!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @else
                            <div class="py-10 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200">
                                No status update logs posted yet for this project.
                            </div>
                        @endif

                        <!-- TAB 3: LOG HISTORY -->
                        <div x-cloak x-show="modalTab === 'history'" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <span>📜 Execution History &amp; Past Log Updates</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-medium italic">Click any report below to load its full details</span>
                            </div>

                            <div class="space-y-3">
                                @forelse($historyAllUpdates as $pIndex => $pUpdate)
                                    @php
                                        $pCreator = $pUpdate->creator;
                                        $pCreatorRole = 'Collaborator';
                                        $pBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';

                                        if ($pCreator) {
                                            if ($this->isSuperAdminUser($pCreator)) {
                                                $pCreatorRole = 'PMO Admin';
                                                $pBadgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                            } elseif ($historyProject && $this->isProjectManagerOf($historyProject, $pCreator)) {
                                                $pCreatorRole = 'Project Manager';
                                                $pBadgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                                            }
                                        }
                                        $isSelectedLog = ($historyLatestUpdate && $historyLatestUpdate->id === $pUpdate->id);
                                    @endphp
                                    <div @click="modalTab = 'report'"
                                         wire:click="selectHistoryUpdate({{ $pUpdate->id }})" 
                                         class="p-4 rounded-xl bg-white border {{ $isSelectedLog ? 'border-[#c3122e] bg-rose-50/20 shadow-xs' : 'border-slate-200 hover:border-slate-300' }} transition-all cursor-pointer space-y-2"
                                         title="Click to view details for this log">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                    #{{ $historyUpdatesCount - $pIndex }}
                                                </span>
                                                <h6 class="font-bold text-xs text-slate-900">{{ $pUpdate->title }}</h6>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="font-bold text-slate-800">{{ $pCreator->name ?? 'User' }}</span>
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $pBadgeClass }}">{{ $pCreatorRole }}</span>
                                                <span class="text-slate-500 text-[10px] font-medium">{{ $pUpdate->created_at->format('M d, Y · h:i A') }} ({{ $pUpdate->created_at->diffForHumans() }})</span>
                                            </div>
                                        </div>
                                        
                                        <div class="p-2.5 rounded-lg text-xs bg-slate-50 text-slate-700 leading-relaxed font-medium">
                                            {{ $pUpdate->summary }}
                                        </div>
                                        
                                        @if($pUpdate->work_completed || $pUpdate->current_blockers)
                                            <div class="flex flex-wrap gap-2 text-[10px] pt-0.5">
                                                @if($pUpdate->work_completed)
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">
                                                        ✓ {{ $pUpdate->work_completed }}
                                                    </span>
                                                @endif
                                                @if($pUpdate->current_blockers)
                                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-800 border border-rose-200 font-medium">
                                                        ⚠️ {{ $pUpdate->current_blockers }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-xs font-medium text-slate-400 bg-white rounded-xl border border-slate-200">
                                        No past status update logs found.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endteleport

        @endif
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         6. MODAL: POST DAILY STATUS UPDATE
         ═══════════════════════════════════════════════════════════════ -->
    @if($showUpdateModal && !auth()->user()?->isAdminViewer())
        @teleport('body')
        <dialog class="daily-publish-dialog" x-data x-init="$nextTick(() => $el.showModal())" @cancel.prevent="$wire.set('showUpdateModal', false)" @click="if ($event.target === $el) $wire.set('showUpdateModal', false)" aria-labelledby="daily-publish-title">
            <div class="daily-publish-panel">
                <!-- Modal Header -->
                <div class="daily-publish-header px-6 py-4 flex items-center justify-between border-b border-slate-100 flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 id="daily-publish-title" class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Publish Latest Progress Log</h3>
                            <p class="text-xs text-slate-500 font-normal mt-0.5">Log task execution progress or overall project status update</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showUpdateModal', false)" 
                        type="button" 
                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                        title="Close Window" aria-label="Close latest update" autofocus
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveStatusUpdate" class="daily-publish-form">
                    <div class="daily-publish-fields space-y-4">
                    <!-- Target Project Select -->
                    <div class="space-y-1.5">
                        <label for="daily-updateProjectId" class="block text-xs font-black text-slate-700">Target Project <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select
                                id="daily-updateProjectId" wire:model.live="updateProjectId"
                                class="no-native-arrow w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                            >
                                @foreach($accessibleProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('updateProjectId') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Scope Selector (Task vs Project Overall) -->
                    @php
                        $canPostFullProjectReport = $this->isSuperAdminUser(auth()->user()) || ($updateProjectId && $this->isProjectManagerOf((int) $updateProjectId, auth()->user()));
                    @endphp
                    @if($canPostFullProjectReport)
                        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-2xl border border-slate-200/60 shadow-inner">
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'task')"
                                class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'task' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-900' }}"
                            >
                                📍 Task-Specific Log
                            </button>
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'project')"
                                class="flex-1 py-2 px-3 rounded-xl text-xs font-black transition-all duration-200 cursor-pointer text-center {{ $updateScopeType === 'project' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-500 hover:text-slate-900' }}"
                            >
                                🌐 Full Project Report
                            </button>
                        </div>
                    @endif

                    <!-- Task Linker if Task Scope -->
                    @if($updateScopeType === 'task')
                        <div class="space-y-1.5">
                            <label class="block text-xs font-black text-slate-700">📍 WBS Scope Task</label>
                            <div class="relative">
                                <select
                                    id="daily-updateWbsItemId" wire:model="updateWbsItemId"
                                    class="no-native-arrow w-full text-xs font-bold py-2.5 pl-3 pr-8 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all cursor-pointer text-slate-800 shadow-2xs"
                                >
                                    <option value="">Select Task...</option>
                                    @foreach($modalWbsItems as $wbs)
                                        <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <p class="text-[9px] font-bold text-slate-400 mt-1">Select the specific task you worked on today.</p>
                        </div>
                    @endif

                    <!-- Log Title -->
                    <div class="space-y-1.5">
                        <label for="daily-updateTitle" class="block text-xs font-black text-slate-700">Log Title <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="daily-updateTitle" wire:model="updateTitle"
                            placeholder="e.g. Completed Payment Gateway API integration tests"
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                        >
                        @error('updateTitle') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <!-- Latest Executive Summary -->
                    <div class="space-y-1.5">
                        <label for="daily-updateSummary" class="block text-xs font-black text-slate-700">Latest Executive Summary <span class="text-rose-500">*</span></label>
                        <textarea
                            id="daily-updateSummary" wire:model="updateSummary"
                            rows="3"
                            placeholder="Detail main work completed, progress made, or key highlights..."
                            class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800 shadow-2xs"
                        ></textarea>
                        @error('updateSummary') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    </div>
                    <!-- Action Buttons -->
                    <div class="daily-publish-footer flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button
                            type="button"
                            wire:click="$set('showUpdateModal', false)"
                            class="px-4 py-2.5 rounded-xl text-xs font-black text-slate-600 hover:bg-slate-100 transition-all cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit" wire:loading.attr="disabled" wire:target="saveStatusUpdate"
                            class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#8b0d1f] shadow-2xs hover:shadow-xs transition-all cursor-pointer hover:-translate-y-0.5"
                        >
                            <span wire:loading.remove wire:target="saveStatusUpdate">Publish update</span><span wire:loading wire:target="saveStatusUpdate">Publishing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
        @endteleport
    @endif
</div>
