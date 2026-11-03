<div data-ui-page="my-tasks" class="ui-page ui-page-work tasks-workspace space-y-5 pt-1">
    <style>
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 11px;
            padding-right: 34px !important;
        }
        .task-checkbox:checked {
            background-color: #059669;
            border-color: #059669;
        }
        .kanban-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .kanban-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 9999px;
        }
        .kanban-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .kanban-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .my-kanban-task-list {
            height: 714px;
            max-height: 714px !important;
            overflow-y: auto;
            overscroll-behavior-y: contain;
            scrollbar-gutter: stable;
        }
        .my-kanban-task-card {
            flex: 0 0 230px;
            min-height: 230px;
        }
        @media (max-width: 680px) {
            .my-kanban-task-list { height: 654px; max-height: 654px !important; }
            .my-kanban-task-card { flex-basis: 210px; min-height: 210px; }
        }
        .kanban-quick-complete {
            display: flex;
            width: 100%;
            min-height: 34px;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 6px 9px;
            border: 1px solid #d1fae5;
            border-radius: 9px;
            color: #047857;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            font-size: 10.5px;
            font-weight: 750;
            transition: border-color .16s ease, background-color .16s ease, color .16s ease, transform .16s ease, box-shadow .16s ease;
        }
        .kanban-quick-complete:hover {
            border-color: #6ee7b7;
            color: #065f46;
            background: #d1fae5;
            box-shadow: 0 7px 16px -12px rgba(5,150,105,.8);
            transform: translateY(-1px);
        }
        .kanban-quick-complete:active { transform: scale(.985); }
        .kanban-quick-complete__check {
            display: inline-flex;
            width: 20px;
            height: 20px;
            align-items: center;
            justify-content: center;
            flex: 0 0 20px;
            border: 1px solid #a7f3d0;
            border-radius: 999px;
            background: #fff;
        }
        .kanban-quick-complete:hover .kanban-quick-complete__check { color: #fff; border-color: #059669; background: #059669; }
        .kanban-completed-state {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #047857;
            font-size: 10.5px;
            font-weight: 750;
        }
        .kanban-complete-popover {
            position: absolute;
            z-index: 30;
            right: 0;
            bottom: calc(100% + 8px);
            width: 238px;
            padding: 12px;
            border: 1px solid #dbe3ec;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 18px 40px -18px rgba(15,23,42,.45);
        }
        .kanban-complete-popover::after {
            content: '';
            position: absolute;
            right: 20px;
            bottom: -6px;
            width: 11px;
            height: 11px;
            border-right: 1px solid #dbe3ec;
            border-bottom: 1px solid #dbe3ec;
            background: #fff;
            transform: rotate(45deg);
        }
        .kanban-complete-popover__confirm {
            display: inline-flex;
            min-height: 30px;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            border-radius: 8px;
            color: #fff;
            background: #059669;
            font-size: 10px;
            font-weight: 750;
        }
        .tasks-command { padding-bottom: 2px; }
        .tasks-page-head { padding-bottom: 15px; border-bottom: 1px solid #e4e9ef; }
        .tasks-page-head h1 { font-size: 28px !important; font-weight: 700 !important; letter-spacing: -.04em; }
        .tasks-view-nav { border: 1px solid #e1e7ed; border-radius: 14px; background: #fff; padding: 7px; box-shadow: 0 12px 28px -26px rgba(15,23,42,.35); }
        .task-view-group { display:flex; align-items:center; gap:4px; padding:3px; border-radius:11px; background:#f4f6f8; }
        .tasks-view-nav .task-view-tab { min-width:108px; min-height:38px; justify-content:center; padding:8px 13px !important; border:0 !important; border-radius:9px !important; color:#64748b; }
        .tasks-view-nav .task-view-tab:hover { color:#0f172a; background:rgba(255,255,255,.72); }
        .tasks-view-nav .task-view-tab.is-active { color:#be123c !important; background:#fff; box-shadow:0 2px 7px rgba(15,23,42,.08), inset 0 0 0 1px #fecdd3; }
        .tasks-view-nav .task-view-tab svg { width:15px; height:15px; color:#94a3b8; }
        .tasks-view-nav .task-view-tab.is-active svg { color:#c3122e; }
        .task-scope-group { display:flex; align-items:center; gap:5px; padding:3px; border-left:1px solid #e2e8f0; margin-left:6px; padding-left:13px; }
        .tasks-view-nav .task-scope-tab { min-height:38px; padding:7px 11px !important; border:1px solid transparent !important; border-radius:9px !important; background:transparent; }
        .tasks-view-nav .task-scope-tab:hover { background:#f8fafc; color:#0f172a; }
        .tasks-view-nav .task-scope-tab.is-active { border-color:#e2e8f0 !important; background:#f8fafc; color:#0f172a !important; box-shadow:0 1px 2px rgba(15,23,42,.04); }
        .task-scope-count { display:inline-flex; min-width:22px; height:22px; align-items:center; justify-content:center; padding:0 6px; border-radius:999px; background:#e9eef4; color:#475569; font-size:10px; font-weight:800; }
        .task-scope-tab.is-active .task-scope-count { background:#fff; color:#c3122e; box-shadow:inset 0 0 0 1px #fecdd3; }
        .tasks-filter-panel { background:#fff !important; border-color:#e1e7ed !important; border-radius:14px !important; box-shadow:0 12px 28px -27px rgba(15,23,42,.35); }
        .tasks-table-card { border-color:#dfe5ec !important; border-radius:16px !important; box-shadow:0 18px 42px -36px rgba(15,23,42,.42) !important; }
        .tasks-table-card table { table-layout:auto; }
        .tasks-table-card .hide-from-register { display:none !important; }
        .tasks-table-card thead tr { background:#f7f9fb !important; }
        .tasks-table-card th { color:#667287 !important; font-size:9.5px !important; font-weight:700 !important; letter-spacing:.07em !important; padding-top:13px !important; padding-bottom:13px !important; }
        .tasks-table-card .task-table-row { border-left:3px solid transparent; }
        .tasks-table-card .task-table-row:hover { background:#fafbfd !important; }
        .tasks-table-card .task-table-row.is-overdue { border-left-color:#d94a5f; background:#fffdfd; }
        .tasks-table-card .task-table-row.is-due-today { border-left-color:#f59e0b; background:#fffdfa; }
        .tasks-table-card .task-table-row td { padding-top:15px !important; padding-bottom:15px !important; }
        .tasks-table-card .task-title { font-size:13px !important; font-weight:650 !important; white-space:normal; line-height:1.35; }
        .task-meta-priority { display:inline-flex; align-items:center; gap:5px; color:#64748b; font-size:10px; font-weight:600; }
        .task-meta-priority i { width:5px; height:5px; border-radius:999px; background:#94a3b8; }
        .task-meta-priority.is-high i { background:#e05267; }
        .task-meta-priority.is-medium i { background:#e7a62f; }
        .task-meta-priority.is-low i { background:#48a787; }
        .tasks-table-card tbody { color:#475569; }
        .tasks-pagination > div:first-child { display:none; }
        .tasks-pagination > div:last-child { width:100%; }
        .tasks-pagination > div:last-child > div:first-child { flex:1; }
        .schedule-workspace { --schedule-border:#e2e7ed; --schedule-muted:#7d899d; }
        .schedule-toolbar { border-color:var(--schedule-border) !important; border-radius:16px !important; padding:14px 16px !important; box-shadow:0 14px 34px -31px rgba(15,23,42,.38) !important; }
        .schedule-toolbar h2 { font-size:17px !important; font-weight:700 !important; letter-spacing:-.025em !important; }
        .schedule-week-strip { gap:6px !important; padding:7px; border:1px solid var(--schedule-border); border-radius:16px; background:#fff; box-shadow:0 12px 30px -30px rgba(15,23,42,.45); }
        .schedule-day { position:relative; min-height:78px; padding:9px 8px !important; border:1px solid transparent !important; border-radius:11px !important; background:transparent !important; box-shadow:none !important; }
        .schedule-day:hover { border-color:#e2e8f0 !important; background:#f8fafc !important; transform:translateY(-1px); }
        .schedule-day.is-selected { border-color:#f1b8c2 !important; background:#fff5f6 !important; color:#b51630 !important; box-shadow:none !important; }
        .schedule-day__weekday { color:#94a3b8; font-size:9px; font-weight:800; letter-spacing:.09em; text-transform:uppercase; }
        .schedule-day__date { display:flex; align-items:baseline; justify-content:center; gap:3px; color:#0f172a; line-height:1; }
        .schedule-day__month { color:#64748b; font-size:10px; font-weight:700; }
        .schedule-day__number { font-size:18px; font-weight:800; letter-spacing:-.035em; }
        .schedule-day__count { display:inline-flex; align-items:center; justify-content:center; gap:5px; color:#64748b; font-size:9px; font-weight:650; line-height:1; white-space:nowrap; }
        .schedule-day__count-dot { width:5px; height:5px; border-radius:999px; background:#94a3b8; }
        .schedule-day.is-selected :is(.schedule-day__weekday,.schedule-day__month,.schedule-day__date) { color:#b51630 !important; }
        .schedule-day.is-selected .schedule-day__weekday { color:#9f1239 !important; }
        .schedule-day.is-selected .schedule-day__count { color:#7f1d2d !important; }
        .schedule-day.is-selected .schedule-day__count-dot { background:#c3122e; }
        .schedule-periods { gap:12px !important; }
        .schedule-period-card { min-height:245px; padding:16px !important; border-color:var(--schedule-border) !important; border-radius:16px !important; box-shadow:0 15px 34px -32px rgba(15,23,42,.38) !important; }
        .schedule-period-card h3 { font-size:11px !important; letter-spacing:.055em !important; }
        .schedule-empty { min-height:140px; padding:24px 12px !important; border:1px dashed #e0e6ed !important; background:#fafbfd !important; color:#94a3b8 !important; }
        @media(max-width:767px){ .tasks-page-head h1{font-size:24px!important}.tasks-view-nav{overflow-x:auto;justify-content:flex-start}.task-view-group,.task-scope-group{flex-shrink:0}.task-scope-group{margin-left:0}.tasks-table-card{border-radius:14px!important}.schedule-week-strip{grid-template-columns:repeat(7,minmax(92px,1fr))!important;overflow-x:auto;scroll-snap-type:x mandatory}.schedule-day{scroll-snap-align:start} }
    </style>

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & NAVIGATION TABS (Matching Uploaded UI Mockup)
         ═══════════════════════════════════════════════════════════════ -->
    <div x-data="{ showFilters: {{ ($search || $statusFilter !== 'all' || $dueDateFilter !== 'all' || ($assigneeFilter ?? 'all') !== 'all') ? 'true' : 'false' }} }" class="tasks-command space-y-3 mb-4">
        <!-- Top Row: Page Title, Subtitle & Top Right Controls -->
        <div class="tasks-page-head flex flex-col md:flex-row md:items-end justify-between gap-4">
            <!-- Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    My work
                </h1>
            </div>

            <!-- Top Right Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <!-- Project Filter Dropdown -->
                <div class="relative min-w-[170px] sm:min-w-[210px]">
                    <select wire:model.live="projectFilter"
                            class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-all shadow-2xs truncate">
                        <option value="all">All Projects</option>
                        @foreach($myProjects as $p)
                            <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>

                <!-- Filter Toggle Button -->
                <button @click="showFilters = !showFilters" type="button"
                        class="px-3.5 py-2 bg-white border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 flex items-center gap-2 transition-all shadow-2xs cursor-pointer active:scale-95">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                    @if($search || $statusFilter !== 'all' || $dueDateFilter !== 'all' || ($assigneeFilter ?? 'all') !== 'all')
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    @endif
                </button>
            </div>
        </div>

        <!-- View Tabs Line (Matching Uploaded UI Image with bottom border underline indicator) -->
        <div class="tasks-view-nav flex items-center gap-2 overflow-x-auto" style="scrollbar-width: none;">
            <div class="task-view-group text-xs whitespace-nowrap">
                <!-- Kanban View Tab -->
                <button wire:click="setViewMode('kanban')" type="button"
                        class="task-view-tab transition-all cursor-pointer flex items-center gap-2 font-bold {{ $viewMode === 'kanban' ? 'is-active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V7m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <span>Kanban View</span>
                </button>

                <!-- Table View Tab -->
                <button wire:click="setViewMode('table')" type="button"
                        class="task-view-tab transition-all cursor-pointer flex items-center gap-2 font-bold {{ $viewMode === 'table' ? 'is-active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span>Table View</span>
                </button>

                <!-- Schedule View Tab -->
                <button wire:click="setViewMode('timeline')" type="button"
                        class="task-view-tab transition-all cursor-pointer flex items-center gap-2 font-bold {{ $viewMode === 'timeline' ? 'is-active' : '' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Schedule</span>
                </button>
            </div>

            @if($isProjectManager)
                <div class="task-scope-group text-xs whitespace-nowrap">
                    <!-- My Tasks Scope Tab -->
                    <button wire:click="setTaskScope('assigned')" type="button"
                            class="task-scope-tab transition-all cursor-pointer flex items-center gap-2 font-bold text-slate-500 {{ $taskScope === 'assigned' ? 'is-active' : '' }}">
                        <span>My Tasks</span>
                        <span class="task-scope-count">
                            {{ $myAssignedCount }}
                        </span>
                    </button>

                    <!-- Managed Projects Scope Tab -->
                    <button wire:click="setTaskScope('pm_projects')" type="button"
                            class="task-scope-tab transition-all cursor-pointer flex items-center gap-2 font-bold text-slate-500 {{ $taskScope === 'pm_projects' ? 'is-active' : '' }}">
                        <span>Managed Projects</span>
                        <span class="task-scope-count">
                            {{ $pmProjectsTasksCount }}
                        </span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Filter Bar (Collapsible or visible when toggled) -->
        <div x-show="showFilters" transition:enter="transition ease-out duration-200" transition:leave="transition ease-in duration-150" class="pt-1">
            <div class="tasks-filter-panel flex flex-col md:flex-row items-stretch md:items-center gap-2 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tasks by title, code..."
                           class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all">
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                    <!-- Status Dropdown ▾ -->
                    <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                        <select wire:model.live="statusFilter"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                            <option value="all">All Statuses ({{ $totalCount }})</option>
                            <option value="not_started">Not Started ({{ $notStartedCount }})</option>
                            <option value="in_progress">In Progress ({{ $inProgressCount }})</option>
                            <option value="blocked">Blocked ({{ $blockedCount }})</option>
                            <option value="completed">Completed ({{ $completedCount }})</option>
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    @if($taskScope === 'pm_projects' && $availableAssignees->isNotEmpty())
                        <!-- Assignee ▾ -->
                        <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                            <select wire:model.live="assigneeFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                                <option value="all">All Assignees</option>
                                <option value="unassigned">Unassigned</option>
                                @foreach($availableAssignees as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    @endif

                    <!-- Due Date ▾ -->
                    <div class="relative flex-1 sm:flex-initial min-w-[120px]">
                        <select wire:model.live="dueDateFilter"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                            <option value="all">All Dates</option>
                            <option value="today">Due Today</option>
                            <option value="overdue">Overdue</option>
                            <option value="this_week">This Week</option>
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Clear Button -->
                    @if($search || $projectFilter !== 'all' || $statusFilter !== 'all' || $dueDateFilter !== 'all' || ($assigneeFilter ?? 'all') !== 'all')
                        <button wire:click="clearFilters" type="button" 
                                class="bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-xl px-3 py-2 text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer shrink-0"
                                title="Reset Filters">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>Clear</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. CLEAN EXECUTIVE TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    <!-- ═══════════════════════════════════════════════════════════════
         3. CLEAN EXECUTIVE TABLE VIEW (Matching Example UI)
         ═══════════════════════════════════════════════════════════════ -->
    @if($viewMode === 'table')
        <div class="tasks-table-card bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="tasks-data-table w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-[10.5px] font-extrabold uppercase tracking-wider text-slate-400" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            <th class="py-3.5 pl-5 pr-2 w-10 text-center"></th>
                            <th class="py-3.5 px-3 min-w-[200px] cursor-pointer hover:text-slate-700 transition-colors select-none" wire:click="sortBy('title')">
                                <div class="flex items-center gap-1.5">
                                    <span>Task</span>
                                    @if($sortField === 'title')
                                        <svg class="w-3 h-3 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDirection === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="py-3.5 px-3 min-w-[150px]">Project</th>
                            @if($taskScope === 'pm_projects')
                                <th class="py-3.5 px-3 min-w-[130px]">Assignee</th>
                            @endif
                            <th class="hide-from-register py-3.5 px-3 min-w-[90px]">Priority</th>
                            <th class="hide-from-register py-3.5 px-3 min-w-[100px]">Start Date</th>
                            <th class="py-3.5 px-3 min-w-[120px] cursor-pointer hover:text-slate-700 transition-colors select-none" wire:click="sortBy('date')">
                                <div class="flex items-center gap-1.5">
                                    <span>Deadline</span>
                                    @if($sortField === 'date')
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-[#c3122e] border border-rose-200/60 lowercase tracking-normal">
                                            <span>overdue first</span>
                                        </span>
                                    @elseif($sortField === 'deadline')
                                        <svg class="w-3 h-3 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDirection === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="py-3.5 px-3 min-w-[90px] cursor-pointer hover:text-slate-700 transition-colors select-none" wire:click="sortBy('progress')">
                                <div class="flex items-center gap-1.5">
                                    <span>Progress</span>
                                    @if($sortField === 'progress')
                                        <svg class="w-3 h-3 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDirection === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="py-3.5 px-3 min-w-[110px] cursor-pointer hover:text-slate-700 transition-colors select-none" wire:click="sortBy('status')">
                                <div class="flex items-center gap-1.5">
                                    <span>Status</span>
                                    @if($sortField === 'status')
                                        <svg class="w-3 h-3 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sortDirection === 'asc' ? 'M4.5 15.75l7.5-7.5 7.5 7.5' : 'M19.5 8.25l-7.5 7.5-7.5-7.5' }}"/></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="py-3.5 pl-2 pr-5 text-right w-12 whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                        @php
                            $todayStr = now()->toDateString();
                        @endphp
                        @forelse($paginatedTasks as $task)
                            @php
                                $isOverdue = $task->end_date && $task->end_date->toDateString() < $todayStr && !in_array($task->status->value, ['completed', 'cancelled']);
                                $isDueToday = ($task->end_date && $task->end_date->toDateString() === $todayStr) || ($task->start_date && $task->start_date->toDateString() === $todayStr);
                                $isBlocked = $task->status->value === 'blocked';
                                $isCompleted = $task->status->value === 'completed';
                                $isInProgress = $task->status->value === 'in_progress';
                                $pLabel = $task->priority?->label() ?? 'Medium';
                                $pLower = strtolower($pLabel);

                                // Progress bar color
                                if ($isCompleted) {
                                    $barColor = 'bg-emerald-500';
                                } elseif ($isOverdue || $isBlocked) {
                                    $barColor = 'bg-rose-500';
                                } elseif ($isInProgress || $task->progress > 0) {
                                    $barColor = 'bg-blue-500';
                                } else {
                                    $barColor = 'bg-slate-300';
                                }

                                // Status badge
                                $stVal = is_object($task->status) ? $task->status->value : $task->status;
                                $stLabel = match($stVal) {
                                    'in_progress'  => 'In Progress',
                                    'completed'    => 'Completed',
                                    'under_review' => 'Under Review',
                                    'blocked'      => 'Blocked',
                                    'at_risk', 'on_hold' => 'At Risk',
                                    default        => ($isOverdue ? 'Overdue' : ($isDueToday ? 'Due Today' : 'Not Started')),
                                };
                                $stBadgeClass = match($stVal) {
                                    'in_progress'  => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                    'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                                    'under_review' => 'bg-sky-50 text-sky-700 border-sky-200/80',
                                    'blocked'      => 'bg-rose-50 text-rose-700 border-rose-200/80',
                                    'at_risk', 'on_hold' => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                    default        => ($isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200/80' : ($isDueToday ? 'bg-amber-50 text-amber-800 border-amber-200/80' : 'bg-slate-100 text-slate-600 border-slate-200')),
                                };
                            @endphp
                            <tr class="task-table-row {{ $isOverdue ? 'is-overdue' : '' }} {{ $isDueToday ? 'is-due-today' : '' }} hover:bg-slate-50/70 transition-colors group">
                                <!-- Checkbox Column -->
                                <td class="py-3.5 pl-5 pr-2 align-middle text-center" onclick="event.stopPropagation()">
                                    <button wire:click="toggleTaskComplete({{ $task->id }})" type="button"
                                            class="w-4.5 h-4.5 rounded-md border flex items-center justify-center transition-all cursor-pointer mx-auto {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-[#c3122e] bg-white' }}"
                                            title="{{ $isCompleted ? 'Mark incomplete' : 'Mark complete' }}">
                                        @if($isCompleted)
                                            <svg class="w-3 h-3 text-white stroke-[3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @endif
                                    </button>
                                </td>

                                <!-- Task Name & Code -->
                                <td class="py-3.5 px-3 align-middle">
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="task-title font-bold text-xs sm:text-[13px] text-slate-900 block leading-snug {{ $isCompleted ? 'line-through text-slate-400 font-normal' : '' }}" title="{{ $task->title }}">
                                            {{ ucwords(strtolower($task->title)) }}
                                        </span>
                                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9.5px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-200/80 shrink-0 whitespace-nowrap leading-none">
                                                {{ $task->project->code ?? 'PRJ' }}
                                            </span>
                                            <span class="text-slate-400 text-[10.5px] font-semibold whitespace-nowrap shrink-0">
                                                • WBS {{ $task->wbs_code ?? '1.0' }}
                                            </span>
                                            <span class="task-meta-priority {{ in_array($pLower, ['high', 'urgent', 'critical']) ? 'is-high' : ($pLower === 'medium' ? 'is-medium' : 'is-low') }}">
                                                <i></i>{{ in_array($pLower, ['high', 'urgent', 'critical']) ? 'High' : ucfirst($pLower) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Project -->
                                <td class="py-3.5 px-3 align-middle" onclick="event.stopPropagation()">
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="font-bold text-xs text-slate-900 hover:text-[#c3122e] block leading-snug transition-colors truncate max-w-[170px]" title="{{ $task->project->name ?? 'Project' }}">
                                        {{ $task->project->name ?? 'Project' }}
                                    </a>
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5 truncate max-w-[170px]">
                                        {{ $task->project->subsidiary->name ?? 'George Steuart Group' }}
                                    </div>
                                </td>

                                @if($taskScope === 'pm_projects')
                                    <!-- Assignee -->
                                    <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                        @if($task->assignedUser)
                                            <span class="text-xs font-bold text-slate-800" title="{{ $task->assignedUser->name }}">
                                                {{ $task->assignedUser->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10.5px] font-medium bg-slate-100 text-slate-400 border border-slate-200/60">
                                                Unassigned
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                <!-- Priority -->
                                <td class="hide-from-register py-3.5 px-3 align-middle whitespace-nowrap">
                                    @if(in_array($pLower, ['high', 'urgent', 'critical']))
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>High</span>
                                        </span>
                                    @elseif($pLower === 'medium')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Medium</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Low</span>
                                        </span>
                                    @endif
                                </td>

                                <!-- Start Date -->
                                <td class="hide-from-register py-3.5 px-3 align-middle whitespace-nowrap text-xs text-slate-600 font-medium">
                                    {{ $task->start_date ? $task->start_date->format('M d, Y') : '—' }}
                                </td>

                                <!-- Deadline -->
                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                    <span class="text-xs text-slate-900 font-semibold block leading-tight">
                                        {{ $task->end_date ? $task->end_date->format('M d, Y') : '—' }}
                                    </span>
                                    @if($isOverdue)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/70 mt-0.5">
                                            <svg class="w-3 h-3 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Overdue</span>
                                        </span>
                                    @elseif($isDueToday)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200/70 mt-0.5">
                                            <svg class="w-3 h-3 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Due today</span>
                                        </span>
                                    @elseif($isCompleted)
                                        <span class="text-[10px] text-emerald-600 font-semibold mt-0.5 block">
                                            Completed
                                        </span>
                                    @elseif($task->end_date)
                                        @php
                                            $daysLeft = (int)now()->startOfDay()->diffInDays($task->end_date->startOfDay(), false);
                                        @endphp
                                        @if($daysLeft > 0)
                                            <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">
                                                {{ $daysLeft }}d left
                                            </span>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-medium mt-0.5 block">
                                                Past due
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Progress -->
                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                    <span class="text-xs font-extrabold text-slate-800 block mb-1">
                                        {{ $task->progress }}%
                                    </span>
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60">
                                        <div class="h-full rounded-full transition-all duration-300 {{ $barColor }}" 
                                             style="width: {{ min(100, max(0, $task->progress)) }}%;"></div>
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                    <div class="relative inline-block" onclick="event.stopPropagation()">
                                        <select wire:change="updateStatus({{ $task->id }}, $event.target.value)" 
                                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-300 transition-all shadow-2xs {{ $stBadgeClass }}"
                                                style="-webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important;"
                                                title="Update Task Status">
                                            @foreach(\App\Enums\WbsStatus::cases() as $st)
                                                <option value="{{ $st->value }}" @selected($stVal === $st->value) class="bg-white text-slate-900 font-semibold">
                                                    {{ $st->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>

                                <!-- Actions (3-dots vertical icon button) -->
                                <td class="py-3.5 pr-5 pl-2 align-middle text-right whitespace-nowrap relative" onclick="event.stopPropagation()">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click.prevent.stop="open = !open" type="button" 
                                                class="w-7 h-7 rounded-full bg-slate-100/80 hover:bg-[#c3122e] text-slate-500 hover:text-white inline-flex items-center justify-center transition-all cursor-pointer shadow-2xs active:scale-95" title="Task Options">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                             class="absolute right-0 top-full mt-1.5 w-48 bg-white rounded-xl shadow-2xl border border-slate-200/90 py-1.5 z-[99] text-xs text-left divide-y divide-slate-100">
                                            <div class="py-0.5">
                                                <button wire:click="toggleTaskComplete({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-emerald-50 text-emerald-700 font-bold flex items-center gap-2.5 cursor-pointer">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    <span>{{ $isCompleted ? 'Mark Incomplete' : 'Mark Completed' }}</span>
                                                </button>
                                                <button wire:click="openBlockerModal({{ $task->id }}); open = false" type="button" class="w-full text-left px-3.5 py-2 hover:bg-rose-50 text-rose-700 font-bold flex items-center gap-2.5 cursor-pointer">
                                                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    <span>Report Blocker</span>
                                                </button>
                                            </div>
                                            <div class="py-0.5">
                                                <a href="{{ route('projects.show', $task->project_id) }}" class="w-full px-3.5 py-2 hover:bg-slate-50 text-slate-700 font-bold flex items-center gap-2.5 cursor-pointer no-underline block">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                                    <span>Open Project</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $taskScope === 'pm_projects' ? 10 : 9 }}" class="py-16 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center gap-2.5">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                            📋
                                        </div>
                                        <span class="text-sm font-bold text-slate-800">No tasks found</span>
                                        <span class="text-xs text-slate-400 max-w-sm">There are currently no tasks matching your active filters. Try resetting the filters or switching views.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bottom Pagination (Matching Example UI) -->
            <div class="tasks-pagination px-6 py-3.5 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-slate-500 font-medium">
                <div>
                    Showing {{ $paginatedTasks->firstItem() ?? 0 }}–{{ $paginatedTasks->lastItem() ?? 0 }} of {{ $paginatedTasks->total() }} tasks
                </div>

                <div class="flex items-center gap-4">
                    <div>
                        {{ $paginatedTasks->links() }}
                    </div>

                    <!-- Per-page Selector -->
                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-medium shrink-0">
                        <span>Per page:</span>
                        <select wire:model.live="perPage" class="text-xs font-bold py-1 px-2 rounded-lg border border-slate-200 bg-slate-50 text-slate-700 outline-none">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


    <!-- ═══════════════════════════════════════════════════════════════
         5. VIEW MODE 2: CROSS-PROJECT KANBAN BOARD (TIME HORIZON & STATUS)
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'kanban')
        <div class="space-y-4">
            <!-- Kanban Sub-Navigation Toolbar -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-3 sm:p-4 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 flex-wrap">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kanban Mode:</span>
                    <div class="inline-flex p-1 rounded-xl bg-slate-100/90 border border-slate-200 text-xs flex-wrap sm:flex-nowrap gap-1">
                        <button 
                            wire:click="setKanbanMode('time')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $kanbanMode === 'time' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $kanbanMode === 'time' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Time Horizon</span>
                        </button>
                        <button 
                            wire:click="setKanbanMode('status')" 
                            type="button" 
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $kanbanMode === 'status' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $kanbanMode === 'status' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                            <span>Status Pipeline</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500 font-semibold self-end md:self-auto">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 border border-slate-200 font-bold text-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ $allFilteredTasks->count() }} Total Tasks</span>
                    </span>
                </div>
            </div>

            <!-- Kanban Columns Horizontal Scroll Canvas -->
            <div class="flex gap-4 overflow-x-auto pb-5 pt-1 items-start snap-x snap-mandatory kanban-scroll" style="scrollbar-width: thin;">
                @foreach($kanbanColumns as $colKey => $col)
                    <div class="w-[280px] sm:w-[300px] min-w-[280px] sm:min-w-[300px] flex-shrink-0 snap-start bg-slate-100/80 rounded-2xl p-3.5 border border-slate-200/90 border-t-4 {{ $col['accent'] }} flex flex-col gap-3 min-h-[540px] shadow-2xs">
                        <!-- Column Header -->
                        <div class="flex items-center justify-between px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-base flex-shrink-0">{{ $col['icon'] ?? '📋' }}</span>
                                <h3 class="font-black text-xs text-slate-800 uppercase tracking-wider truncate">{{ $col['title'] }}</h3>
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-full border {{ $col['badge'] }} flex-shrink-0 font-mono">
                                    {{ $col['tasks']->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Column Cards List -->
                        <div class="my-kanban-task-list flex-1 flex flex-col gap-3 pr-1">
                            @forelse($col['tasks'] as $kTask)
                                @php
                                    $isKDone = $kTask->status === \App\Enums\WbsStatus::COMPLETED;
                                    $isKOverdue = $kTask->isOverdue();
                                    $hasHourSlot = !empty($kTask->start_time_formatted) && !empty($kTask->end_time_formatted);
                                @endphp

                                <div class="my-kanban-task-card bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-md transition-all duration-200 space-y-2.5 group relative {{ $isKDone ? 'bg-emerald-50/20' : ($isKOverdue ? 'bg-rose-50/20 border-rose-200' : '') }}">
                                    <!-- Top Row: Project Code Pill & Priority -->
                                    <div class="flex items-center justify-between gap-1.5">
                                        <span class="text-[10px] font-black font-mono px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[150px]" title="{{ $kTask->project->name ?? '' }}">
                                            {{ $kTask->project->code ?? 'PRJ' }}
                                        </span>
                                        <span class="text-[9.5px] font-extrabold uppercase px-2 py-0.5 rounded-md {{ $kTask->priority->badgeClass() }}">
                                            {{ $kTask->priority->label() }}
                                        </span>
                                    </div>

                                    <!-- Middle Row: Task Title & Hour Pill -->
                                    <div>
                                        <div class="flex items-start gap-1.5 font-extrabold text-xs text-slate-900 group-hover:text-[#c3122e] transition-colors leading-relaxed">
                                            <span class="font-mono text-slate-400 flex-shrink-0">{{ $kTask->wbs_code }}</span>
                                            <span class="{{ $isKDone ? 'line-through text-slate-400' : '' }}">{{ $kTask->title }}</span>
                                        </div>

                                        @if($hasHourSlot)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold text-violet-700 bg-violet-50/90 border border-violet-200/80 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>{{ $kTask->start_time_formatted }} – {{ $kTask->end_time_formatted }}</span>
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Bottom Row: Due Date & Progress -->
                                    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                                        <span class="flex items-center gap-1.5 font-bold {{ $isKOverdue ? 'text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md' : 'text-slate-600' }}">
                                            <span>📅</span>
                                            <span>{{ $kTask->end_date ? $kTask->end_date->format('M d') : 'No date' }}</span>
                                        </span>
                                        @if($kTask->progress > 0)
                                            <span class="text-[10px] font-mono font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">{{ $kTask->progress }}%</span>
                                        @endif
                                    </div>

                                    @if($taskScope === 'assigned' && $kTask->assigned_user_id === auth()->id())
                                        @if(!$isKDone && $kTask->status !== \App\Enums\WbsStatus::CANCELLED && !auth()->user()?->isAdminViewer())
                                            <div class="relative" x-data="{ confirmingComplete: false }" @keydown.escape.window="confirmingComplete = false">
                                                <button
                                                    @click.stop="confirmingComplete = true"
                                                    type="button"
                                                    class="kanban-quick-complete cursor-pointer"
                                                    title="Mark this task as completed"
                                                    :aria-expanded="confirmingComplete"
                                                >
                                                    <span class="inline-flex items-center gap-2">
                                                        <span class="kanban-quick-complete__check">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        </span>
                                                        <span>Complete task</span>
                                                    </span>
                                                    <svg class="w-3.5 h-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                </button>

                                                <div
                                                    x-cloak
                                                    x-show="confirmingComplete"
                                                    x-transition:enter="transition ease-out duration-150"
                                                    x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                    x-transition:leave="transition ease-in duration-100"
                                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                    x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                                    @click.outside="confirmingComplete = false"
                                                    @click.stop
                                                    class="kanban-complete-popover"
                                                    role="alertdialog"
                                                    aria-label="Confirm task completion"
                                                >
                                                    <div class="flex items-start gap-2.5">
                                                        <span class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        </span>
                                                        <div class="min-w-0">
                                                            <p class="text-[11px] font-extrabold text-slate-900">Complete this task?</p>
                                                            <p class="mt-0.5 text-[9.5px] leading-relaxed text-slate-500">Status will change to Completed and progress to 100%.</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 flex items-center justify-end gap-2">
                                                        <button @click.stop="confirmingComplete = false" type="button" class="h-[30px] px-2.5 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-100 cursor-pointer">Cancel</button>
                                                        <button
                                                            wire:click.stop="completeKanbanTask({{ $kTask->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="completeKanbanTask({{ $kTask->id }})"
                                                            @click="confirmingComplete = false"
                                                            type="button"
                                                            class="kanban-complete-popover__confirm cursor-pointer disabled:cursor-wait disabled:opacity-70"
                                                        >
                                                            <span wire:loading.remove wire:target="completeKanbanTask({{ $kTask->id }})">Yes, complete</span>
                                                            <span wire:loading wire:target="completeKanbanTask({{ $kTask->id }})">Completing...</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="kanban-completed-state">
                                                <span class="inline-flex w-5 h-5 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </span>
                                                <span>Completed</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if($taskScope === 'pm_projects')
                                        <!-- Assignee Badge in Kanban -->
                                        <div class="flex items-center gap-1.5 pt-2 border-t border-slate-100 text-[11px] text-slate-600">
                                            @if($kTask->assignedUser)
                                                <div class="w-5 h-5 rounded-full bg-slate-800 text-white flex items-center justify-center text-[9px] font-bold shrink-0">
                                                    {{ strtoupper(substr($kTask->assignedUser->name, 0, 1)) }}
                                                </div>
                                                <span class="truncate font-semibold text-slate-700 max-w-[150px]" title="{{ $kTask->assignedUser->name }}">
                                                    {{ $kTask->assignedUser->name }}
                                                </span>
                                            @else
                                                <span class="text-[10px] text-slate-400 font-medium italic">Unassigned</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400 text-xs font-semibold border-2 border-dashed border-slate-200 rounded-2xl bg-white/50">
                                    No tasks
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         6. VIEW MODE 3: INTRADAY HOURLY & SCHEDULE TIMELINE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'timeline')
        <div class="schedule-workspace space-y-3">
            <!-- Top Controls Bar: Navigator + Date + Project Filter + View Switcher + Date Picker -->
            <div class="schedule-toolbar bg-white rounded-2xl border border-slate-200/80 p-4 shadow-2xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Left: Navigation Arrows + Today Button + Date Title -->
                <div class="flex items-center gap-3 min-w-0 flex-wrap">
                    <div class="flex items-center gap-1 flex-shrink-0 bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <button wire:click="goToTimelinePrev" type="button" class="w-7 h-7 rounded-lg bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer transition-colors" title="Previous Day">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button wire:click="goToTimelineToday" type="button" class="px-3 py-1 rounded-lg text-xs font-bold cursor-pointer transition-colors {{ $timelineDateObj->isToday() ? 'bg-white text-[#c3122e] shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Today
                        </button>
                        <button wire:click="goToTimelineNext" type="button" class="w-7 h-7 rounded-lg bg-white hover:bg-slate-200 text-slate-600 flex items-center justify-center cursor-pointer transition-colors" title="Next Day">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <div class="min-w-0 pl-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-extrabold text-base sm:text-lg text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                {{ $timelineDateObj->format('l, F j, Y') }}
                            </h2>
                            @if($timelineDateObj->isToday())
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200">
                                    Today
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 font-medium mt-0.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dayTasks->count() > 0 ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span>{{ $dayTasks->count() }} {{ Str::plural('deliverable', $dayTasks->count()) }} scheduled</span>
                        </span>
                    </div>
                </div>

                <!-- Right: Project Filter + Day/Week Switcher + Styled Date Button -->
                <div class="flex items-center gap-2 flex-shrink-0 self-end lg:self-auto flex-wrap">
                    @if($myProjects->count() > 1)
                        <!-- Project Selector -->
                        <div class="relative min-w-[135px] max-w-[185px]">
                            <select wire:model.live="projectFilter"
                                    class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3 pr-7 py-1.5 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                                <option value="all">All Projects</option>
                                @foreach($myProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    @endif

                    <!-- Day Breakdown vs 7-Day Week Switcher -->
                    <div class="inline-flex p-1 rounded-xl border border-slate-200 bg-slate-100/80">
                        <button 
                            wire:click="setTimelineTimeframe('day')" 
                            type="button" 
                            class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'day' ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $timelineTimeframe === 'day' ? 'text-slate-700' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Day</span>
                        </button>
                        <button 
                            wire:click="setTimelineTimeframe('week')" 
                            type="button" 
                            class="px-3 py-1 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $timelineTimeframe === 'week' ? 'bg-white text-[#c3122e] font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}"
                        >
                            <svg class="w-3.5 h-3.5 {{ $timelineTimeframe === 'week' ? 'text-[#c3122e]' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Week</span>
                        </button>
                    </div>


                </div>
            </div>

            <!-- 7-Day Interactive Mini Calendar Strip (Only in Day View) -->
            @if($timelineTimeframe === 'day')
                <div class="schedule-week-strip grid grid-cols-7 gap-2">
                    @foreach($weekSchedule as $wDateKey => $wInfo)
                        @php
                            $wIsSelected = $wInfo['isSelected'];
                            $wIsToday = $wInfo['isToday'];
                            $wTaskCount = $wInfo['tasks']->count();
                        @endphp
                        <button 
                            wire:click="selectTimelineDay('{{ $wDateKey }}')" 
                            type="button" 
                            class="schedule-day {{ $wIsSelected ? 'is-selected' : '' }} text-center transition-all cursor-pointer flex flex-col items-center justify-center gap-1.5"
                        >
                            <span class="schedule-day__weekday">
                                {{ $wInfo['date']->format('D') }}
                            </span>
                            <div class="schedule-day__date" title="{{ $wInfo['date']->format('F j, Y') }}">
                                <span class="schedule-day__month">{{ $wInfo['date']->format('M') }}</span>
                                <span class="schedule-day__number">{{ $wInfo['date']->format('j') }}</span>
                            </div>
                            <div class="schedule-day__count" title="{{ $wTaskCount }} scheduled {{ \Illuminate\Support\Str::plural('task', $wTaskCount) }}">
                                <span class="schedule-day__count-dot"></span>
                                <span>{{ $wTaskCount }} scheduled</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- ── TIMEFRAME 1: DAY BREAKDOWN (Morning / Afternoon / Evening) ── -->
            @if($timelineTimeframe === 'day')
                <div class="schedule-periods grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- 1. Morning Block -->
                    <div class="schedule-period-card bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Morning</h3>
                                    <span class="text-[11px] font-medium text-slate-400">08:30 AM – 12:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $morningTasks->count() }} {{ \Illuminate\Support\Str::plural('task', $morningTasks->count()) }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($morningTasks as $mTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $mTask])
                            @empty
                                <div class="schedule-empty py-10 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/60 border border-slate-100 flex flex-col items-center justify-center gap-1">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No morning deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 2. Afternoon Block -->
                    <div class="schedule-period-card bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Afternoon</h3>
                                    <span class="text-[11px] font-medium text-slate-400">12:30 PM – 05:30 PM</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $afternoonTasks->count() }} {{ \Illuminate\Support\Str::plural('task', $afternoonTasks->count()) }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($afternoonTasks as $aTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $aTask])
                            @empty
                                <div class="schedule-empty py-10 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/60 border border-slate-100 flex flex-col items-center justify-center gap-1">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No afternoon deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. Evening & All-Day -->
                    <div class="schedule-period-card bg-white rounded-2xl p-4 border border-slate-200/80 space-y-3 shadow-2xs flex flex-col">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-200/60">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Evening &amp; All-Day</h3>
                                    <span class="text-[11px] font-medium text-slate-400">After 05:30 PM / Flexible</span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                {{ $eveningTasks->count() + $allDayTasks->count() }} {{ \Illuminate\Support\Str::plural('task', $eveningTasks->count() + $allDayTasks->count()) }}
                            </span>
                        </div>

                        <div class="space-y-2.5 flex-1">
                            @forelse($eveningTasks->concat($allDayTasks) as $eTask)
                                @include('livewire.my-tasks-timeline-card', ['t' => $eTask])
                            @empty
                                <div class="schedule-empty py-10 px-4 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/60 border border-slate-100 flex flex-col items-center justify-center gap-1">
                                    <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>No evening deliverables</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            <!-- ── TIMEFRAME 2: 7-DAY WEEK HORIZON BOARD ── -->
            @else
                <div class="flex gap-4 overflow-x-auto pb-4 pt-1 items-start snap-x kanban-scroll" style="scrollbar-width: thin;">
                    @foreach($weekSchedule as $wDateKey => $wInfo)
                        @php
                            $wDayTasks = $wInfo['tasks'];
                            $wIsToday = $wInfo['isToday'];
                        @endphp
                        <div class="w-[280px] min-w-[280px] flex-shrink-0 snap-start rounded-2xl p-3.5 border flex flex-col gap-3 min-h-[480px] bg-white border-slate-200/80 shadow-2xs {{ $wIsToday ? 'ring-2 ring-[#c3122e]/30' : '' }}">
                            <!-- Column Header -->
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900 uppercase tracking-wider">
                                        {{ $wInfo['date']->format('l') }}
                                    </h4>
                                    <span class="text-[11px] font-medium text-slate-400">
                                        {{ $wInfo['date']->format('M d') }}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $wIsToday ? 'bg-rose-50 text-[#c3122e]' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $wDayTasks->count() }}
                                </span>
                            </div>

                            <!-- Cards -->
                            <div class="flex-1 flex flex-col gap-2.5 overflow-y-auto">
                                @forelse($wDayTasks as $wt)
                                    @include('livewire.my-tasks-timeline-card', ['t' => $wt])
                                @empty
                                    <div class="py-12 text-center text-slate-400 text-xs font-medium rounded-xl bg-slate-50/60 border border-slate-100">
                                        No tasks scheduled
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         7. TASK DETAILS & COMPLETION
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 520px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="font-extrabold text-sm text-slate-900">Add New Personal Deliverable</h3>
                    <button type="button" wire:click="$set('showCreateTaskModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="saveNewTask" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Target Project <span class="text-rose-500">*</span></label>
                        <select wire:model="createTaskProjectId" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50" required>
                            @foreach($myProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->code }} — {{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="createTaskTitle" placeholder="e.g. Design system integration" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Date</label>
                            <input type="date" wire:model="createTaskStartDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Due Deadline</label>
                            <input type="date" wire:model="createTaskEndDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Time (Optional)</label>
                            <input type="time" wire:model="createTaskStartTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">End Time (Optional)</label>
                            <input type="time" wire:model="createTaskEndTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateTaskModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-emerald-700 transition-colors">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         9. SUB-TASK CREATION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showSubTaskModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 520px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <h3 class="font-extrabold text-sm text-slate-900">Add Sub-Task</h3>
                    <button type="button" wire:click="$set('showSubTaskModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="createSubTask" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Sub-Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="subTaskTitle" placeholder="e.g. Prepare client review notes" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Due Deadline</label>
                            <input type="date" wire:model="subTaskEndDate" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">Start Time (Optional)</label>
                            <input type="time" wire:model="subTaskStartTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-600 uppercase mb-1">End Time (Optional)</label>
                            <input type="time" wire:model="subTaskEndTime" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-bold">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSubTaskModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-slate-900 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-black transition-colors">Add Sub-Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         10. BLOCKER / ISSUE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showBlockerModal)
        <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 16px; background-color: rgba(15, 23, 42, 0.65); backdrop-filter: blur(6px);">
            <div style="position: relative; width: 100%; max-width: 480px; background: #ffffff; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45); border: 1px solid #cbd5e1; overflow: hidden;">
                <div style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                    <div class="flex items-center gap-2 text-rose-600 font-extrabold text-sm">
                        <span>⚠️</span>
                        <span>Report Task Blocker / Issue</span>
                    </div>
                    <button type="button" wire:click="$set('showBlockerModal', false)" class="text-slate-400 hover:text-slate-700 font-bold text-sm cursor-pointer">✕</button>
                </div>
                <form wire:submit="reportBlocker" style="padding: 20px 24px; display: flex; flex-direction: column; gap: 14px;">
                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Issue / Blocker Description <span class="text-rose-500">*</span></label>
                        <textarea wire:model="blockerDescription" rows="3" placeholder="Explain what is blocking this task..." class="w-full p-3 rounded-xl border border-slate-200 text-xs font-semibold outline-none" required></textarea>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-700 uppercase mb-1">Severity</label>
                        <select wire:model="blockerSeverity" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                            <option value="low">🟢 Low (Minor delay)</option>
                            <option value="medium">🟡 Medium (Requires attention)</option>
                            <option value="high">🔴 High (Critical blocker)</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showBlockerModal', false)" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-rose-600 text-white text-xs font-black rounded-xl shadow-md cursor-pointer hover:bg-rose-700 transition-colors">Submit to Project Manager</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
