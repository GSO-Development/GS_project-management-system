<div data-ui-page="all-tasks" class="ui-page ui-page-work space-y-5 pt-1">
<style>
    .at-table-row:hover { background: #f8fafc; }
    .at-table-row:hover .at-task-title { color: #c3122e; }
    .at-status-dot { width:7px; height:7px; border-radius:50%; display:inline-block; flex-shrink:0; }
    .at-avatar { width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; }
    [data-ui-page="all-tasks"] .at-table-scroll {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        overflow-x: auto;
        cursor: grab;
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 #f1f5f9;
    }
    [data-ui-page="all-tasks"] .at-table-scroll::-webkit-scrollbar {
        height: 10px;
    }
    [data-ui-page="all-tasks"] .at-table-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    [data-ui-page="all-tasks"] .at-table-scroll::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border: 2px solid #f1f5f9;
        border-radius: 6px;
    }
    [data-ui-page="all-tasks"] .at-table-scroll:focus-visible {
        outline: 2px solid #c3122e;
        outline-offset: -2px;
    }
    [data-ui-page="all-tasks"] .at-table-scroll.is-dragging,
    [data-ui-page="all-tasks"] .at-table-scroll.is-dragging * {
        cursor: grabbing !important;
        user-select: none;
    }
    [data-ui-page="all-tasks"] .at-stuck-table {
        min-width: 1200px;
        table-layout: fixed;
        font-variant-numeric: tabular-nums;
    }
    [data-ui-page="all-tasks"] .at-stuck-table :is(th, td) {
        padding-left: 12px;
        padding-right: 12px;
        vertical-align: middle;
        white-space: normal;
    }
    [data-ui-page="all-tasks"] .at-stuck-table th {
        white-space: nowrap;
    }
    [data-ui-page="all-tasks"] .at-stuck-table :is(th, td):nth-child(6) {
        text-align: center;
    }
    [data-ui-page="all-tasks"] .at-stuck-table td:last-child {
        padding-right: 20px;
    }
    [data-ui-page="all-tasks"] .at-stuck-table .at-stuck-text {
        min-width: 0;
        overflow-wrap: anywhere;
        line-height: 1.5;
    }
    [data-ui-page="all-tasks"] .at-stuck-table .at-stuck-date,
    [data-ui-page="all-tasks"] .at-stuck-table .at-stuck-delay {
        white-space: nowrap;
    }
    .kpi-tile { transition: all 0.15s ease; }
    .kpi-tile:hover { transform: translateY(-1px); }
    .kpi-tile.active { box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-kpi-scroll {
        width: 100%;
        overflow-x: auto;
        padding: 2px 2px 8px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis {
        display: grid !important;
        grid-template-columns: repeat(7, minmax(120px, 1fr)) !important;
        gap: 12px !important;
        width: 100%;
        min-width: 900px;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile {
        min-width: 0;
        min-height: 108px;
        padding: 18px 16px !important;
        align-items: flex-start;
        justify-content: space-between;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        background: #ffffff !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, .03) !important;
        text-align: left;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile::before {
        display: none !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile:hover {
        transform: none !important;
        border-color: #cbd5e1 !important;
        background: #f8fafc !important;
        box-shadow: 0 2px 6px rgba(15, 23, 42, .04) !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile.active {
        border-color: #94a3b8 !important;
        background: #f8fafc !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, .05) !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile > div:first-child {
        font-size: 1.75rem !important;
        line-height: 1 !important;
        color: #0f172a !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile.active > div:first-child {
        color: #0f172a !important;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile > div:last-child {
        margin-top: 16px !important;
        color: #64748b !important;
        font-size: .65rem !important;
        letter-spacing: .08em;
    }
    .app-clean [data-ui-page="all-tasks"] .all-tasks-status-kpis .kpi-tile.active > div:last-child {
        color: #334155 !important;
    }
    @keyframes slideDownFade {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .filter-toolbar-enter {
        animation: slideDownFade 0.18s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & CONTROLS
         ═══════════════════════════════════════════════════════════════ -->
    <div class="all-tasks-header space-y-4 mb-5">
        <!-- Top Row: Page Title & Action Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Task Operations
                </h1>
            </div>

            <!-- Top Right Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <!-- Project Filter Dropdown -->
                <div class="relative min-w-[170px] sm:min-w-[210px]">
                    <select wire:model.live="projectFilter"
                            class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-all shadow-2xs truncate">
                        <option value="all">All Projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <!-- Filter Toggle Button -->
                <button wire:click="toggleFilters" type="button"
                        class="px-3.5 py-2 border rounded-xl text-xs font-bold flex items-center gap-2 transition-all cursor-pointer active:scale-95 {{ $showFilters ? 'bg-slate-100 border-slate-300 text-slate-900 shadow-xs' : 'bg-white border-slate-200 hover:border-slate-300 text-slate-700 hover:text-slate-900 shadow-2xs' }}">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                    @if($hasActiveFilters)
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    @endif
                </button>

                <!-- Back to Projects CTA Button -->
                <a href="{{ auth()->user()?->isPmoAdmin() ? route('projects.index') : route('projects.my-leads') }}" 
                   class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all cursor-pointer active:scale-95 shrink-0 no-underline">
                    <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Back to Projects</span>
                </a>
            </div>
        </div>

        <!-- View Tabs Line (Crimson underline for active tab) -->
        <div class="all-tasks-tabs border-b border-slate-200 flex items-center justify-between gap-4 overflow-x-auto" style="scrollbar-width: none;">
            <div class="flex items-center gap-6 text-xs whitespace-nowrap">
                <!-- All Tasks View Tab -->
                <button wire:key="tab-all-tasks" wire:click="setViewMode('all')" type="button"
                        class="{{ $viewMode === 'all' ? 'border-b-2 border-[#c3122e] text-[#c3122e] font-bold' : 'text-slate-500 hover:text-slate-700 font-semibold border-b-2 border-transparent' }} py-2.5 px-1 flex items-center gap-2 cursor-pointer transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>All Tasks</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $viewMode === 'all' ? 'bg-[#c3122e]/10 text-[#c3122e]' : 'bg-slate-100 text-slate-600' }}">{{ $kpi['total'] }}</span>
                </button>

                <!-- Stuck Tasks View Tab -->
                <button wire:key="tab-stuck-tasks" wire:click="setViewMode('stuck')" type="button"
                        class="{{ $viewMode === 'stuck' ? 'border-b-2 border-[#c3122e] text-[#c3122e] font-bold' : 'text-slate-500 hover:text-slate-700 font-semibold border-b-2 border-transparent' }} py-2.5 px-1 flex items-center gap-2 cursor-pointer transition-colors">
                    <span class="w-2 h-2 rounded-full bg-rose-500 {{ $totalStuckTasksCount > 0 ? 'animate-pulse' : '' }}"></span>
                    <span>Stuck &amp; Blocked Tasks</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $viewMode === 'stuck' ? 'bg-[#c3122e] text-white' : 'bg-rose-100 text-rose-700' }}">{{ $totalStuckTasksCount }}</span>
                </button>
            </div>
        </div>

        <!-- Collapsible Filter Toolbar (matching my-tasks design pattern) -->
        @if($showFilters)
            <div wire:key="filter-toolbar-{{ $viewMode }}" class="pt-1 filter-toolbar-enter">
                @if($viewMode === 'all')
                    <div wire:key="filter-bar-all-tasks" class="all-tasks-filter flex flex-col md:flex-row items-stretch md:items-center gap-2.5 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
                        {{-- Search Input --}}
                        <div class="relative flex-1 min-w-[200px]">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                   placeholder="Search tasks by title, code, assignee..."
                                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                        </div>

                        {{-- Filter Dropdowns --}}
                        <div class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                            {{-- Status Dropdown --}}
                            <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                                <select wire:model.live="statusFilter"
                                        class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                                    <option value="all">All Statuses ({{ $kpi['total'] }})</option>
                                    <option value="not_started">Not Started ({{ $kpi['not_started'] }})</option>
                                    <option value="in_progress">In Progress ({{ $kpi['in_progress'] }})</option>
                                    <option value="completed">Completed ({{ $kpi['completed'] }})</option>
                                    <option value="on_hold">On Hold ({{ $kpi['on_hold'] }})</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Priority Dropdown --}}
                            <div class="relative flex-1 sm:flex-initial min-w-[125px]">
                                <select wire:model.live="priorityFilter"
                                        class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                                    <option value="all">All Priorities</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Sort Dropdown --}}
                            <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                                <select wire:model.live="sortBy"
                                        class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                                    <option value="end_date">Sort: Due Date (Overdue First)</option>
                                    <option value="priority">Sort: Priority</option>
                                    <option value="project">Sort: Project</option>
                                    <option value="title">Sort: Title A–Z</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Clear Filters Button --}}
                            @if($search !== '' || $statusFilter !== 'all' || $dueFilter !== 'all' || $riskFilter !== 'all' || $projectFilter !== 'all' || $priorityFilter !== 'all' || $sortBy !== 'end_date')
                                <button wire:click="clearFilters" type="button"
                                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 border border-rose-200/80 transition-all flex items-center justify-center gap-1.5 cursor-pointer shrink-0 active:scale-95 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Clear</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @else
                    <div wire:key="filter-bar-stuck-tasks" class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
                        {{-- Search Input --}}
                        <div class="relative flex-1 min-w-[200px]">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                   placeholder="Search task, project, reason, or assignee..."
                                   class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                        </div>

                        {{-- Filter Dropdowns --}}
                        <div class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                            {{-- Assignee Dropdown --}}
                            <div class="relative flex-1 sm:flex-initial min-w-[140px] max-w-[180px]">
                                <select wire:model.live="stuckAssigneeFilter"
                                        class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                                    <option value="all">All Assignees</option>
                                    @foreach($stuckAssigneesList as $sa)
                                        <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Priority Dropdown --}}
                            <div class="relative flex-1 sm:flex-initial min-w-[120px]">
                                <select wire:model.live="stuckPriorityFilter"
                                        class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                                    <option value="all">All Priority</option>
                                    <option value="critical">Critical</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>

                            {{-- Clear Filters Button --}}
                            @if($search !== '' || $stuckTypeFilter !== 'all' || $stuckProjectFilter !== 'all' || $projectFilter !== 'all' || $stuckAssigneeFilter !== 'all' || $stuckPriorityFilter !== 'all')
                                <button wire:click="resetStuckFilters" type="button"
                                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 border border-rose-200/80 transition-all flex items-center justify-center gap-1.5 cursor-pointer shrink-0 active:scale-95 shadow-2xs">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Clear</span>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MODE 1: ALL TASKS DIRECTORY VIEW
         ═══════════════════════════════════════════════════════════════ --}}
    @if($viewMode === 'all')
        <div wire:key="view-content-all-tasks" class="space-y-4">
            {{-- KPI TILES --}}
            <div class="all-tasks-kpi-scroll">
            <div class="all-tasks-kpi-grid all-tasks-status-kpis">
            {{-- Total --}}
            <button wire:click="setStatusFilter('all')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $statusFilter==='all' && $dueFilter==='all' && $riskFilter==='all' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['total'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">Total</div>
            </button>
            {{-- Not Started --}}
            <button wire:click="setStatusFilter('not_started')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $statusFilter==='not_started' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['not_started'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">Not Started</div>
            </button>
            {{-- In Progress --}}
            <button wire:click="setStatusFilter('in_progress')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $statusFilter==='in_progress' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['in_progress'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">In Progress</div>
            </button>
            {{-- Completed --}}
            <button wire:click="setStatusFilter('completed')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $statusFilter==='completed' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['completed'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">Completed</div>
            </button>
            {{-- On Hold --}}
            <button wire:click="setStatusFilter('on_hold')" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $statusFilter==='on_hold' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['on_hold'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">On Hold</div>
            </button>
            {{-- At Risk --}}
            <button wire:click="setAtRiskFilter" type="button"
                aria-label="Filter at-risk tasks: {{ $kpi['at_risk'] }}"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $riskFilter==='at_risk' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['at_risk'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">At Risk</div>
            </button>
            {{-- Overdue --}}
            <button wire:click="setOverdueFilter" type="button"
                class="kpi-tile rounded-2xl border py-3.5 px-2 text-left cursor-pointer {{ $dueFilter==='overdue' ? 'active border-slate-400' : 'bg-white border-slate-200 hover:border-slate-300' }}">
                <div class="text-2xl font-black font-mono leading-none text-slate-900">{{ $kpi['overdue'] }}</div>
                <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-500">Overdue</div>
            </button>
            </div>
        </div>



        {{-- TABLE --}}
        <div class="all-tasks-table bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <colgroup>
                        <col class="w-[28%]">
                        <col class="w-[19%]">
                        <col class="w-[17%]">
                        <col class="w-[12%]">
                        <col class="w-[13%]">
                        <col class="w-[11%]">
                    </colgroup>
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[10.5px] font-bold text-slate-400 uppercase tracking-wider select-none">
                            <th class="py-3.5 pl-6 pr-4">Task</th>
                            <th class="py-3.5 px-4">Project</th>
                            <th class="py-3.5 px-4">Assignee</th>
                            <th class="py-3.5 px-4 text-center">Priority</th>
                            <th class="py-3.5 px-4 text-center">Status</th>
                            <th class="py-3.5 pl-4 pr-6 text-right">Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tasks as $task)
                            @php
                                $today       = now()->today();
                                $daysLeft    = $task->end_date ? (int) $today->diffInDays($task->end_date, false) : null;
                                $taskStatus  = $task->status?->value ?? (string)$task->status;
                                $isCompleted = in_array($taskStatus, ['completed','cancelled']);
                                $isOverdue   = $daysLeft !== null && $daysLeft < 0 && !$isCompleted;
                                $isDueToday  = $daysLeft === 0 && !$isCompleted;

                                [$priText, $priDot, $priBg, $priBorder, $priTextCls] = match($task->priority?->value ?? 'medium') {
                                    'high'  => ['High',   'bg-rose-500',   'bg-rose-50',   'border-rose-200',   'text-rose-700'],
                                    'low'   => ['Low',    'bg-emerald-500','bg-emerald-50','border-emerald-200','text-emerald-700'],
                                    default => ['Medium', 'bg-amber-500',  'bg-amber-50',  'border-amber-200',  'text-amber-700'],
                                };

                                [$statusLabel, $statusBadge] = match($taskStatus) {
                                    'completed' => ['Completed', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    'on_hold', 'blocked' => ['On Hold', 'bg-amber-50 text-amber-800 border-amber-200'],
                                    'in_progress', 'under_review', 'at_risk' => ['In Progress', 'bg-blue-50 text-blue-700 border-blue-200'],
                                    default => ['Not Started', 'bg-slate-50 text-slate-700 border-slate-200'],
                                };

                                $dotColorCls = match($taskStatus) {
                                    'in_progress'  => 'bg-blue-600',
                                    'completed'    => 'bg-emerald-500',
                                    'on_hold'      => 'bg-amber-500',
                                    'blocked', 'on_hold' => 'bg-amber-500',
                                    'under_review', 'at_risk' => 'bg-blue-600',
                                    default        => 'bg-slate-400',
                                };

                                $initials = strtoupper(substr($task->assignedUser?->name ?? '?', 0, 1));
                                $avatarBgs = ['bg-indigo-100 text-indigo-700', 'bg-pink-100 text-pink-700', 'bg-emerald-100 text-emerald-700', 'bg-amber-100 text-amber-700', 'bg-purple-100 text-purple-700', 'bg-rose-100 text-rose-700', 'bg-sky-100 text-sky-700'];
                                $avatarStyle = $avatarBgs[crc32($task->assignedUser?->name ?? '') % count($avatarBgs)];
                            @endphp
                            <tr class="at-table-row hover:bg-slate-50/70 transition-colors cursor-pointer {{ $isDueToday ? 'bg-amber-50/30' : '' }}" onclick="window.location='{{ route('projects.show', $task->project_id) }}'">
                                {{-- Task --}}
                                <td class="py-3.5 pl-6 pr-4 align-middle">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <span class="w-2 h-2 rounded-full {{ $dotColorCls }} shrink-0"></span>
                                        <div class="min-w-0">
                                            <div class="at-task-title text-xs font-bold text-slate-900 whitespace-normal break-words transition-colors leading-snug" title="{{ $task->title }}">
                                                {{ $task->title }}
                                            </div>
                                            @if($task->wbs_code)
                                                <span class="text-[10px] font-mono font-medium text-slate-400 block mt-0.5">{{ $task->wbs_code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Project --}}
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    <div class="font-bold text-xs text-slate-800 whitespace-normal break-words leading-snug" title="{{ $task->project->name ?? '' }}">
                                        {{ $task->project->name ?? '—' }}
                                    </div>
                                    @if($task->project?->subsidiary?->name)
                                        <div class="text-[10.5px] text-slate-400 font-medium truncate max-w-[180px] mt-0.5">
                                            {{ $task->project->subsidiary->name }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Assignee --}}
                                <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                    @if($task->assignedUser)
                                        <span class="text-xs font-bold text-slate-800">
                                            {{ $task->assignedUser->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium">Unassigned</span>
                                    @endif
                                </td>

                                {{-- Priority --}}
                                <td class="py-3.5 px-4 align-middle text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10.5px] font-extrabold border {{ $priBg }} {{ $priBorder }} {{ $priTextCls }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $priDot }} shrink-0"></span>
                                        <span>{{ $priText }}</span>
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-4 align-middle text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10.5px] font-extrabold border {{ $statusBadge }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Due Date --}}
                                <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                    @if($task->end_date)
                                        <div class="text-xs font-bold {{ $isCompleted ? 'text-slate-500' : ($isOverdue ? 'text-rose-600' : ($isDueToday ? 'text-amber-600 font-extrabold' : 'text-slate-800')) }}">
                                            {{ $task->end_date->format('M d, Y') }}
                                        </div>
                                        @if($isCompleted)
                                            <div class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 mt-0.5">
                                                <svg class="w-3 h-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <span>Completed</span>
                                            </div>
                                        @elseif($isOverdue)
                                            <div class="inline-flex items-center gap-1 text-[10px] font-extrabold text-rose-600 mt-0.5">
                                                <svg class="w-2.5 h-2.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>{{ abs($daysLeft) }}d late</span>
                                            </div>
                                        @elseif($isDueToday)
                                            <div class="inline-flex items-center gap-1 text-[10px] font-extrabold text-amber-600 mt-0.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse shrink-0"></span>
                                                <span>Due today</span>
                                            </div>
                                        @elseif($daysLeft !== null && $daysLeft > 0 && $daysLeft <= 3)
                                            <div class="text-[10px] font-bold text-amber-600 mt-0.5">{{ $daysLeft }}d left</div>
                                        @elseif($daysLeft !== null && $daysLeft > 3)
                                            <div class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $daysLeft }}d left</div>
                                        @else
                                            <div class="text-[10px] font-medium text-slate-400 mt-0.5">Past due</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 font-light">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700">No tasks found</p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Try adjusting your search or active filters</p>
                                        </div>
                                        <button wire:click="clearFilters" type="button" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">
                                            Clear all filters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            @if($tasks->total() > 0)
                <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-500 font-medium">
                            Showing <strong class="text-slate-900 font-bold">{{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}</strong>
                            of <strong class="text-slate-900 font-bold">{{ $tasks->total() }}</strong> tasks
                        </span>
                        <select wire:model.live="perPage" class="at-filter-select px-2.5 py-1 text-xs font-bold rounded-lg border border-slate-200 bg-white text-slate-700 focus:outline-none cursor-pointer">
                            <option value="25">25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="100">100 / page</option>
                        </select>
                    </div>
                    <div>{{ $tasks->links() }}</div>
                </div>
            @endif
        </div>
        </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MODE 2: STUCK & BLOCKED TASKS RADAR VIEW
         ═══════════════════════════════════════════════════════════════ --}}
    @elseif($viewMode === 'stuck')
        <div wire:key="view-content-stuck-tasks" class="space-y-4 min-w-0">
            <!-- Stuck Tasks Table -->
            <div class="all-tasks-table bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Stuck &amp; Delayed Deliverables
                        </h3>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/70 font-mono">
                            {{ $stuckTasks->count() }} Items
                        </span>
                    </div>
                </div>

                <div
                    class="at-table-scroll"
                    role="region"
                    aria-label="Stuck tasks table. Drag left or right, or use Shift and the mouse wheel to scroll horizontally."
                    tabindex="0"
                    x-data="{
                        pressed: false,
                        dragging: false,
                        moved: false,
                        startX: 0,
                        startScroll: 0,
                        startDrag(event) {
                            this.stopDrag();
                            this.moved = false;
                            if (event.pointerType !== 'mouse' || event.button !== 0) return;
                            if (!event.target.closest('table')) return;
                            if (event.target.closest('button, input, select, textarea, [contenteditable]')) return;
                            if (this.$el.scrollWidth <= this.$el.clientWidth) return;
                            this.pressed = true;
                            this.startX = event.clientX;
                            this.startScroll = this.$el.scrollLeft;
                        },
                        moveDrag(event) {
                            if (!this.pressed) return;
                            if (event.buttons !== 1) {
                                this.stopDrag();
                                return;
                            }
                            const distance = event.clientX - this.startX;
                            if (!this.moved && Math.abs(distance) <= 5) return;
                            this.dragging = true;
                            this.moved = true;
                            event.preventDefault();
                            this.$el.scrollLeft = this.startScroll - distance;
                        },
                        stopDrag() {
                            this.pressed = false;
                            this.dragging = false;
                        },
                        cancelDragClick(event) {
                            if (!this.moved || event.detail === 0) return;
                            this.moved = false;
                            event.preventDefault();
                            event.stopImmediatePropagation();
                        },
                        scrollWithWheel(event) {
                            if (!event.shiftKey || event.ctrlKey || event.deltaX !== 0) return;
                            const unit = event.deltaMode === 1 ? 16 : (event.deltaMode === 2 ? this.$el.clientWidth : 1);
                            const previous = this.$el.scrollLeft;
                            this.$el.scrollLeft += event.deltaY * unit;
                            if (this.$el.scrollLeft !== previous) event.preventDefault();
                        }
                    }"
                    :class="{ 'is-dragging': dragging }"
                    @pointerdown="startDrag($event)"
                    @pointermove.window="moveDrag($event)"
                    @pointerup.window="stopDrag()"
                    @pointercancel.window="stopDrag()"
                    @blur.window="stopDrag()"
                    @dragstart.prevent
                    @click.capture="cancelDragClick($event)"
                    @wheel="scrollWithWheel($event)"
                >
                    <table class="at-stuck-table w-full text-left border-collapse text-xs">
                        <caption class="sr-only">Stuck and blocked tasks with deadlines and delay days.</caption>
                        <colgroup>
                            <col style="width: 18%">
                            <col style="width: 15%">
                            <col style="width: 19%">
                            <col style="width: 10%">
                            <col style="width: 13%">
                            <col style="width: 8%">
                            <col style="width: 8%">
                            <col style="width: 9%">
                        </colgroup>
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider select-none">
                                <th scope="col" class="py-3.5 pl-6 pr-4">Task / Deliverable</th>
                                <th scope="col" class="py-3.5 px-4">Project</th>
                                <th scope="col" class="py-3.5 px-4">Assignee</th>
                                <th scope="col" class="py-3.5 px-4">Status</th>
                                <th scope="col" class="py-3.5 px-4">Deadline</th>
                                <th scope="col" class="py-3.5 px-4">Delay</th>
                                <th scope="col" class="py-3.5 px-4">Priority</th>
                                <th scope="col" class="py-3.5 pl-4 pr-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($stuckTasks as $task)
                                @php
                                    $pObj = $task->project;
                                    $assignee = $task->assignedUser;
                                    $isBlocked = $task->status?->value === 'blocked' || ($task->blockers && $task->blockers->where('status', 'open')->count() > 0);
                                    
                                    $today = now()->today();
                                    $daysOverdue = ($task->end_date && $task->end_date->lt($today)) ? (int) $task->end_date->diffInDays($today) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- 1. Task Title & Code -->
                                    <td class="py-3.5 pl-6 pr-4 align-middle">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            @if($isBlocked)
                                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0" title="Blocked Task"></span>
                                            @endif
                                            <div class="at-stuck-text">
                                                <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" title="{{ $task->title }}" class="font-bold text-slate-900 hover:text-[#c3122e] no-underline line-clamp-2 leading-tight">
                                                    {{ $task->title }}
                                                </a>
                                                <span class="text-[10px] font-mono font-semibold text-slate-400">{{ $task->wbs_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Project -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-bold text-xs text-slate-800 hover:text-[#c3122e] no-underline block">
                                            {{ $pObj->code ?? 'PRJ' }}
                                        </a>
                                        <span class="text-[10.5px] text-slate-400 block truncate font-medium" title="{{ $pObj->name }}">{{ $pObj->name ?? '—' }}</span>
                                    </td>

                                    <!-- 3. Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($assignee)
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-6 h-6 shrink-0 rounded-full bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">
                                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                </span>
                                                <span class="at-stuck-text font-semibold text-slate-800 text-xs" title="{{ $assignee->name }}">{{ $assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                        @endif
                                    </td>

                                    <!-- 4. Status -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($isBlocked)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-rose-50 text-rose-800 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Blocked</span>
                                            </span>
                                        @elseif($task->status?->value === 'on_hold')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-amber-50 text-amber-800 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span>On Hold</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold text-[10.5px] bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                <span>Overdue</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Deadline -->
                                    <td class="py-3.5 px-4 align-middle">
                                        @if($task->end_date)
                                            <time datetime="{{ $task->end_date->toDateString() }}"
                                                  class="at-stuck-date block text-xs font-semibold {{ $daysOverdue > 0 ? 'text-rose-700' : 'text-slate-800' }}">
                                                {{ $task->end_date->format('M d, Y') }}
                                            </time>
                                        @else
                                            <span class="text-slate-400 text-[11px]">Not set</span>
                                        @endif
                                    </td>

                                    <!-- 6. Delay Days -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap font-mono font-bold text-slate-700">
                                        @if($daysOverdue > 0)
                                            <span class="at-stuck-delay inline-flex items-center px-2 py-1 rounded-md bg-rose-50 text-rose-700 text-[11px] font-bold" title="{{ $daysOverdue }} days past the deadline">{{ $daysOverdue }} {{ $daysOverdue === 1 ? 'day' : 'days' }}</span>
                                        @else
                                            <span class="text-slate-400 font-normal">—</span>
                                        @endif
                                    </td>

                                    <!-- 7. Priority -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold capitalize
                                            {{ strtolower($task->priority?->value ?? '') === 'critical' ? 'bg-rose-100 text-rose-800' : (strtolower($task->priority?->value ?? '') === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $task->priority?->label() }}
                                        </span>
                                    </td>

                                    <!-- 8. Action -->
                                    <td class="py-3.5 pl-4 pr-6 align-middle text-right whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:border-slate-300 transition-all no-underline" title="Inspect in Project Workspace">
                                            <span>Open</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-14 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                                                ✓
                                            </div>
                                            <span class="text-sm font-bold text-slate-700">No stuck tasks found</span>
                                            <span class="text-xs text-slate-400">All organization deliverables are on track without blockers.</span>
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

</div>
