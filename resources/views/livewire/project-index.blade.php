<div data-ui-page="projects" class="ui-page ui-page-projects">
    <style>
        .no-native-arrow {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }
        .custom-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 11px;
            padding-right: 30px !important;
        }
        @keyframes critical-alert-flash {
            0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(225, 29, 72, .45); }
            50% { opacity: .58; box-shadow: 0 0 0 7px rgba(225, 29, 72, 0); }
        }
        .critical-alert-flash { animation: critical-alert-flash 1.25s ease-in-out infinite; }
        @media (prefers-reduced-motion: reduce) {
            .critical-alert-flash { animation: none; }
        }
        html:has(.delay-analysis-backdrop),
        body:has(.delay-analysis-backdrop),
        html:has(.delete-project-backdrop),
        body:has(.delete-project-backdrop) { overflow: hidden !important; }
    </style>

    <!-- Flash Success / Error Toast Banners -->
    @if(session()->has('success_message'))
        <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ session('success_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 cursor-pointer">✕</button>
        </div>
    @endif
    @if(session()->has('error_message'))
        <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] text-xs font-bold flex items-center justify-between shadow-xs animate-in fade-in duration-200">
            <div class="flex items-center gap-2">
                <span class="text-base">❌</span>
                <span>{{ session('error_message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP HEADER & NAVIGATION TABS (Matching Uploaded UI Mockup)
         ═══════════════════════════════════════════════════════════════ -->
    <div x-data="{ showFilters: {{ ($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all' || $managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all') ? 'true' : 'false' }} }" class="projects-command-bar space-y-4 mb-6">
        <!-- Top Row: Page Title, Subtitle & Top Right Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Projects Portfolio
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-1">Governance, delivery health and schedule visibility across active initiatives.</p>
            </div>

            <!-- Top Right Action Controls -->
            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                <!-- Subsidiary Filter Dropdown -->
                <div class="relative min-w-[170px] sm:min-w-[210px]">
                    <select wire:model.live="subsidiaryFilter"
                            class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-all shadow-2xs truncate">
                        <option value="all">All Subsidiaries</option>
                        @foreach($subsidiaries as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
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
                    @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all' || $managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                        <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                    @endif
                </button>

                <!-- New Project CTA Button -->
                @if(auth()->user()?->canCreateProject())
                    <a href="{{ route('projects.create') }}"
                       class="px-4 py-2 bg-[#c3122e] hover:bg-[#a00e25] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all shadow-md shadow-[#c3122e]/20 cursor-pointer active:scale-95 shrink-0 no-underline">
                        <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Add Project</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- View Tabs Line (Matching Uploaded UI Mockup with bottom border underline active indicator) -->
        <div class="projects-view-tabs border-b border-slate-200 flex items-center justify-between gap-4 overflow-x-auto" style="scrollbar-width: none;">
            <div class="flex items-center gap-6 text-xs whitespace-nowrap">
                <!-- Table View Tab -->
                <button wire:click="setViewMode('table')" type="button"
                        class="py-3 px-1 border-b-2 transition-all cursor-pointer flex items-center gap-2 {{ $viewMode === 'table' ? 'border-[#c3122e] text-[#c3122e] font-bold' : 'border-transparent text-slate-500 hover:text-slate-900 font-semibold' }}">
                    <span>Table View</span>
                </button>

                <!-- Portfolio Gantt Tab -->
                <button wire:click="setViewMode('gantt')" type="button"
                        class="py-3 px-1 border-b-2 transition-all cursor-pointer flex items-center gap-2 {{ $viewMode === 'gantt' ? 'border-[#c3122e] text-[#c3122e] font-bold' : 'border-transparent text-slate-500 hover:text-slate-900 font-semibold' }}">
                    <span>Portfolio Gantt</span>
                </button>

                @if(auth()->user()?->isSuperAdmin() || auth()->user()?->hasRole('pmo_admin'))
                    <!-- All Tasks Tab (Admin / PMO) -->
                    <a href="{{ route('all-tasks.index') }}"
                       class="py-3 px-1 border-b-2 border-transparent text-slate-500 hover:text-slate-900 font-semibold transition-all cursor-pointer flex items-center gap-1.5 no-underline">
                        <span>All Tasks</span>
                        @if($totalStuckTasksCount > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-rose-50 text-[#c3122e] border border-rose-200/80">
                                {{ $totalStuckTasksCount }}
                            </span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        <!-- Collapsible Search & Filter Bar -->
        <div x-show="showFilters" transition:enter="transition ease-out duration-200" transition:leave="transition ease-in duration-150" class="pt-1">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search projects by title, code, manager..."
                           class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all">
                </div>

                <!-- Filter Dropdowns -->
                <div class="flex items-center gap-2 flex-wrap md:flex-nowrap">
                    <!-- Project Role Filter Dropdown -->
                    <div class="relative flex-1 sm:flex-initial min-w-[150px]">
                        <select wire:model.live="roleFilter"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                            <option value="all">All Project Roles</option>
                            <option value="project_manager">Project Manager</option>
                            <option value="steering_committee">Steering Committee</option>
                            <option value="owner">Project Owner</option>
                            <option value="sponsor">Project Sponsor</option>
                            <option value="team_member">Team Member</option>
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Status Filter -->
                    <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                        <select wire:model.live="statusFilter"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                            <option value="all">All Statuses</option>
                            @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Priority Filter -->
                    <div class="relative flex-1 sm:flex-initial min-w-[120px]">
                        <select wire:model.live="priorityFilter"
                                class="w-full appearance-none bg-white border border-slate-200 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors truncate">
                            <option value="all">All Priorities</option>
                            @foreach(\App\Enums\Priority::cases() as $pr)
                                <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>

                    <!-- Clear Filters Button -->
                    @if($search || $subsidiaryFilter !== 'all' || $statusFilter !== 'all' || $managerFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all' || $roleFilter !== 'all')
                        <button type="button" wire:click="resetFilters"
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

    @if($viewMode === 'table')
        {{-- ── Projects Table Container ── --}}

        {{-- ── Projects Table Container ── --}}
        <div class="projects-directory-table bg-white rounded-2xl border border-slate-200/70 shadow-xs overflow-hidden">
            <div class="projects-drag-scroll w-full overflow-x-auto scrollbar-thin"
                x-data="{
                    drag: null,
                    dragging: false,
                    beginPan(event) {
                        if (event.pointerType !== 'mouse' || event.button !== 0 || this.$el.scrollWidth &lt;= this.$el.clientWidth) return;
                        if (event.target.closest('a, button, input, select, textarea, [contenteditable], [role=button]')) return;
                        const rect = this.$el.getBoundingClientRect();
                        if (event.clientX >= rect.left + this.$el.clientWidth || event.clientY >= rect.top + this.$el.clientHeight) return;
                        this.drag = { id: event.pointerId, x: event.clientX, left: this.$el.scrollLeft };
                        event.preventDefault();
                    },
                    pan(event) {
                        if (!this.drag || event.pointerId !== this.drag.id) return;
                        if (!(event.buttons &amp; 1)) { this.endPan(); return; }
                        const delta = event.clientX - this.drag.x;
                        if (!this.dragging &amp;&amp; Math.abs(delta) &lt; 4) return;
                        if (!this.dragging) this.$el.setPointerCapture(event.pointerId);
                        this.dragging = true;
                        event.preventDefault();
                        this.$el.scrollLeft = this.drag.left - delta;
                    },
                    endPan() {
                        if (this.drag &amp;&amp; this.$el.hasPointerCapture(this.drag.id)) this.$el.releasePointerCapture(this.drag.id);
                        this.drag = null;
                        this.dragging = false;
                    }
                }"
                @pointerdown="beginPan($event)" @pointermove.window="pan($event)" @pointerup.window="endPan()"
                @pointercancel.window="endPan()" @blur.window="endPan()" @lostpointercapture="drag = null; dragging = false"
                :class="{ 'is-panning': dragging }" tabindex="0" role="region" aria-label="Projects table, drag empty space to scroll horizontally">
                <style>
                    .projects-drag-scroll { cursor: grab; }
                    .projects-drag-scroll.is-panning, .projects-drag-scroll.is-panning * { cursor: grabbing !important; user-select: none; }
                    .projects-drag-scroll a, .projects-drag-scroll button { cursor: pointer; }
                    .projects-drag-scroll:focus-visible { outline: 2px solid #c3122e; outline-offset: -2px; }
                </style>
                <table class="w-full text-left border-collapse min-w-[720px] lg:min-w-full">
                    <colgroup>
                        <col class="w-[4%]">
                        <col class="w-[19%]">
                        <col class="w-[13%]">
                        <col class="w-[15%]">
                        <col class="w-[10%]">
                        <col class="w-[12%]">
                        <col class="w-[20%]">
                        <col class="w-[5%]">
                    </colgroup>
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[10.5px] font-bold text-slate-400 uppercase tracking-wider select-none">
                            <th class="py-3.5 pl-5 sm:pl-6 pr-2 text-center whitespace-nowrap">#</th>
                            <th class="py-3.5 pl-2 pr-3 w-[30%] sm:w-[26%] lg:w-[25%]">Project</th>
                            <th class="py-3.5 px-3 w-[18%] lg:w-[15%] hidden md:table-cell">Subsidiary</th>
                            <th class="py-3.5 px-3 w-[20%] sm:w-[18%] lg:w-[16%]">Project Manager</th>
                            <th class="py-3.5 px-3 w-[14%] lg:w-[12%] hidden sm:table-cell">Status</th>
                            <th class="py-3.5 px-3 w-[14%] lg:w-[12%] hidden lg:table-cell">Progress</th>
                            <th class="py-3.5 px-3 w-[20%] hidden lg:table-cell whitespace-nowrap">Deadline</th>
                            <th class="py-3.5 pl-2 pr-5 sm:pr-6 text-right w-[10%] whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs bg-white">
                        @forelse($projects as $project)
                            @php $delaySummary = $delaySummaries[$project->id] ?? ['is_delayed' => false, 'issues' => []]; @endphp
                            <tr class="{{ $delaySummary['is_delayed'] ? 'project-row-delayed' : '' }} hover:bg-slate-50/60 transition-colors duration-150 group">
                                <!-- Sequential project number (continues across pagination) -->
                                <td class="py-3.5 pl-5 sm:pl-6 pr-2 text-center align-middle">
                                    <span class="font-mono text-[11px] font-semibold text-slate-400 tabular-nums">
                                        {{ ($projects->firstItem() ?? 1) + $loop->index }}
                                    </span>
                                </td>

                                <!-- Project Name & Clean Metadata -->
                                <td class="py-3.5 pl-2 pr-3 align-middle">
                                    <div class="min-w-0 space-y-1">
                                        <a href="{{ route('projects.show', $project->id) }}" class="font-bold text-sm text-slate-900 hover:text-[#c3122e] transition-colors truncate block leading-snug no-underline" title="Open {{ $project->name }} workspace">
                                            {{ $project->name }}
                                        </a>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500 whitespace-nowrap">
                                            <span class="font-mono text-[10px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/60 leading-none">
                                                {{ $project->code }}
                                            </span>
                                            <span class="text-slate-300">•</span>
                                            @php
                                                $priorityDot = match($project->priority->value ?? 'medium') {
                                                    'urgent', 'critical' => 'bg-rose-500',
                                                    'high'               => 'bg-amber-500',
                                                    'medium'             => 'bg-amber-400',
                                                    default              => 'bg-slate-400',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-500 capitalize">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $priorityDot }}"></span>
                                                {{ $project->priority->label() }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Subsidiary -->
                                <td class="py-3.5 px-3 hidden md:table-cell align-middle">
                                    <span class="text-xs font-medium text-slate-600 truncate block" title="{{ $project->subsidiary->name ?? '—' }}">
                                        {{ $project->subsidiary->name ?? '—' }}
                                    </span>
                                </td>

                                <!-- Project Manager -->
                                <td class="py-3.5 px-3 align-middle">
                                    @if($project->projectManager)
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-6 h-6 rounded-full bg-rose-50 text-[#c3122e] font-extrabold text-[10px] flex items-center justify-center shrink-0 border border-rose-100 shadow-2xs">
                                                {{ strtoupper(substr($project->projectManager->name, 0, 1)) }}
                                            </div>
                                            <span class="text-xs font-semibold text-slate-700 truncate" title="{{ $project->projectManager->name }}">
                                                {{ $project->projectManager->name }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Unassigned</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-3 hidden sm:table-cell align-middle">
                                    @php
                                        $stConfig = match($project->status->value) {
                                            'completed'    => ['cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'dot' => 'bg-emerald-500', 'bar' => 'bg-emerald-500'],
                                            'in_progress'  => ['cls' => 'bg-blue-50 text-blue-700 border-blue-200/60',         'dot' => 'bg-blue-500 animate-pulse', 'bar' => 'bg-blue-500'],
                                            'at_risk'      => ['cls' => 'bg-amber-50 text-amber-800 border-amber-200/70',       'dot' => 'bg-amber-500 animate-pulse', 'bar' => 'bg-amber-500'],
                                            'planning'     => ['cls' => 'bg-sky-50 text-sky-700 border-sky-200/60',             'dot' => 'bg-sky-500', 'bar' => 'bg-sky-500'],
                                            'on_hold'      => ['cls' => 'bg-slate-100 text-slate-600 border-slate-200/60',       'dot' => 'bg-slate-400', 'bar' => 'bg-slate-400'],
                                            'under_review' => ['cls' => 'bg-purple-50 text-purple-700 border-purple-200/60',    'dot' => 'bg-purple-500', 'bar' => 'bg-purple-500'],
                                            'delayed'      => ['cls' => 'bg-rose-50 text-rose-700 border-rose-200/60',          'dot' => 'bg-rose-500 animate-pulse', 'bar' => 'bg-rose-500'],
                                            default        => ['cls' => 'bg-slate-100 text-slate-500 border-slate-200/60',       'dot' => 'bg-slate-400', 'bar' => 'bg-slate-400'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $stConfig['cls'] }} whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $stConfig['dot'] }} shrink-0"></span>
                                        {{ $project->status->label() }}
                                    </span>
                                </td>

                                <!-- Progress -->
                                <td class="py-3.5 px-3 hidden lg:table-cell align-middle">
                                    <div class="space-y-1 w-full min-w-[120px] max-w-[160px]">
                                        <div class="flex items-center justify-between text-xs font-semibold">
                                            <span class="text-slate-900 font-extrabold">{{ $project->overall_progress }}%</span>
                                            <span class="text-slate-400 text-[11px] font-medium truncate pl-1">{{ $project->wbsItems->count() }} Tasks</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all duration-500 {{ $project->overall_progress >= 100 ? 'bg-emerald-500' : $stConfig['bar'] }}" style="width: {{ min(100, max(0, $project->overall_progress)) }}%"></div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Deadline -->
                                <td class="py-3.5 px-3 hidden lg:table-cell align-middle whitespace-nowrap">
                                    @if($project->deadline)
                                        @php $isOverdue = $project->deadline->isPast() && $project->overall_progress < 100; @endphp
                                        <span class="block text-xs font-extrabold tracking-tight {{ $isOverdue ? 'text-[#d10a32]' : 'text-slate-700' }}">
                                            {{ $project->deadline->format('M d, Y') }}
                                        </span>
                                        @if($delaySummary['is_delayed'])
                                            <a
                                                href="{{ route('projects.index', array_merge(request()->query(), ['delay_project' => $project->id])) }}"
                                                class="group/delay mt-1.5 inline-flex items-center gap-1.5 text-[10px] font-bold text-rose-600 no-underline transition-colors hover:text-[#a80e27]"
                                                title="Review delayed tasks and reasons"
                                                aria-label="Review {{ $delaySummary['project_overdue_days'] ?? 0 }} overdue days and {{ count($delaySummary['issues'] ?? []) }} delay issues for {{ $project->name }}"
                                            >
                                                <span class="relative flex h-2.5 w-2.5 shrink-0" aria-hidden="true">
                                                    <span class="absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-60 critical-alert-flash"></span>
                                                    <span class="relative m-auto inline-flex h-2 w-2 rounded-full bg-[#d10a32] ring-2 ring-rose-100"></span>
                                                </span>
                                                <span>{{ $delaySummary['project_overdue_days'] ?? 0 }}d overdue</span>
                                                <span class="text-rose-300">&bull;</span>
                                                <span>{{ count($delaySummary['issues'] ?? []) }} {{ Str::plural('issue', count($delaySummary['issues'] ?? [])) }}</span>
                                                <svg class="h-3 w-3 translate-x-0 opacity-50 transition-all group-hover/delay:translate-x-0.5 group-hover/delay:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        @elseif(!$isOverdue)
                                            <span class="mt-1.5 inline-flex items-center gap-1.5 text-[10px] font-semibold text-emerald-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                On schedule
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-3.5 pl-2 pr-5 sm:pr-6 align-middle text-right whitespace-nowrap">
                                    <div class="inline-flex items-center justify-end gap-1">
                                        @if($delaySummary['is_delayed'])
                                            <a
                                                href="{{ route('projects.index', array_merge(request()->query(), ['delay_project' => $project->id])) }}"
                                                x-on:click.stop
                                                class="group/critical relative flex h-8 w-8 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-[#d10a32] shadow-sm shadow-rose-100 transition-all hover:-translate-y-0.5 hover:border-rose-300 hover:bg-[#d10a32] hover:text-white hover:shadow-md hover:shadow-rose-200"
                                                title="Open Critical Delay Analysis"
                                                aria-label="Open critical delay analysis for {{ $project->name }}"
                                            >
                                                <span class="absolute right-0.5 top-0.5 h-1.5 w-1.5 rounded-full bg-[#d10a32] critical-alert-flash group-hover/critical:bg-white" aria-hidden="true"></span>
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 15l3-3 3 2 5-7"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7h3v3"/>
                                                </svg>
                                            </a>
                                        @endif
                                        <a
                                            href="{{ route('projects.show', $project->id) }}"
                                            x-on:click.stop
                                            class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all flex items-center justify-center cursor-pointer"
                                            title="Open Project Workspace"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                        @if(auth()->user()?->isPmoAdmin() || auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('pmo_admin') || auth()->user()?->id === 1)
                                            <button
                                                wire:click="confirmDelete({{ $project->id }})"
                                                type="button"
                                                class="group/delete relative flex h-8 w-8 items-center justify-center rounded-lg border border-transparent text-slate-400 transition-all hover:-translate-y-0.5 hover:border-rose-200 hover:bg-rose-50 hover:text-[#c3122e] hover:shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#c3122e]/30 cursor-pointer"
                                                title="Delete {{ $project->name }}"
                                                aria-label="Delete project {{ $project->name }}"
                                            >
                                                <svg class="h-4 w-4 transition-transform group-hover/delete:scale-105" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-16 text-center">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-1">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">No projects found</p>
                                        <p class="text-xs text-slate-400">Try adjusting your search or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/40">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         VIEW 2: PORTFOLIO GANTT TIMELINE RADAR
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'gantt')
        <div class="space-y-4">
            <!-- ── 1. GANTT CONTROL & FILTER TOOLBAR (CLEAN SINGLE ROW) ── -->
            <div class="bg-white rounded-2xl border border-slate-200/70 p-3 shadow-2xs flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 mb-3">
                <!-- Left: Timeline Navigation & Horizon Selector -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <!-- Navigation Pills -->
                    <div class="inline-flex items-center rounded-xl bg-slate-100/80 p-1 gap-0.5 shrink-0">
                        <button wire:click="ganttPrev" type="button" class="px-2.5 py-1 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Shift 1 Month Earlier">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            <span>Prev</span>
                        </button>
                        <button wire:click="ganttToday" type="button" class="px-3 py-1 text-slate-900 hover:text-[#c3122e] hover:bg-white rounded-lg text-xs font-extrabold transition-all cursor-pointer flex items-center gap-1.5" title="Center Timeline on Current Month">
                            <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                            <span>Today</span>
                        </button>
                        <button wire:click="ganttNext" type="button" class="px-2.5 py-1 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1" title="Shift 1 Month Later">
                            <span>Next</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>

                    <!-- Timeframe Horizon Switcher -->
                    <div class="inline-flex p-1 rounded-xl bg-slate-100/80 text-xs font-bold shrink-0 gap-0.5">
                        <button wire:click="setGanttTimeframe('auto')" type="button" class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === 'auto' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">Auto</button>
                        <button wire:click="setGanttTimeframe('3m')" type="button" class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '3m' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">3M</button>
                        <button wire:click="setGanttTimeframe('6m')" type="button" class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '6m' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">6M</button>
                        <button wire:click="setGanttTimeframe('12m')" type="button" class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $ganttTimeframe === '12m' ? 'bg-white text-slate-900 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}">12M</button>
                    </div>
                </div>

                <!-- Right: Search & Filters Toolbar -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <!-- Search Input -->
                    <div class="relative w-44">
                        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..."
                               class="w-full pl-8 pr-7 py-1.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#c3122e] transition-all">
                        @if($search)
                            <button wire:click="$set('search', '')" type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                        @endif
                    </div>

                    <!-- Subsidiary Filter -->
                    <select wire:model.live="subsidiaryFilter" 
                            class="custom-select px-3 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-[#c3122e] shadow-2xs cursor-pointer max-w-[140px] truncate">
                        <option value="all">All Subsidiaries</option>
                        @foreach($subsidiaries as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ── 2. GANTT TIMELINE WORKSPACE ── -->
            <div class="bg-white border border-slate-200/70 shadow-xs rounded-2xl overflow-hidden">
                <div class="overflow-x-auto overflow-y-auto max-h-[720px] scrollbar-thin">
                    <div class="min-w-[1380px]" style="min-width: 1380px;">
                        <!-- Timeline Header Row -->
                        <div class="flex border-b border-slate-200/80 bg-slate-50/90 text-slate-700 select-none sticky top-0 z-30 shadow-2xs backdrop-blur-xs">
                            <!-- Left Header -->
                            <div class="w-[360px] min-w-[360px] flex-shrink-0 flex items-center justify-between px-4 h-11 border-r border-slate-200/80 bg-slate-50 sticky left-0 z-40">
                                <div class="font-bold text-xs uppercase tracking-wider text-slate-500 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                                    <span>PROJECT &amp; GOVERNANCE</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white text-slate-600 border border-slate-200/80 shadow-2xs">
                                    {{ count($ganttTimeline['projects']) }} Projects
                                </span>
                            </div>

                            <!-- Right Months Timeline Headers -->
                            <div class="flex-1 min-w-[1060px] flex relative bg-slate-50 h-11 px-0">
                                @foreach($ganttTimeline['months'] as $m)
                                    @php
                                        $mDisplayLabel = match($ganttTimeframe) {
                                             '3m'  => $m['label'],
                                             '12m' => $m['short'],
                                             default => $m['short_label'],
                                        };
                                        if ($ganttTimeframe === '12m' && $m['month_num'] === 1) {
                                             $mDisplayLabel = $m['short_label'];
                                        }
                                    @endphp
                                    <div class="border-r border-slate-200/70 flex items-center justify-center text-center h-full px-1 overflow-hidden relative {{ $m['is_current'] ? 'bg-rose-50/80 font-extrabold text-[#c3122e] border-b-[3.5px] border-b-[#c3122e]' : 'text-slate-600 font-semibold' }}" style="width: {{ $m['width_pct'] }}%;" title="{{ $m['label'] }}">
                                        <div class="flex items-center gap-1.5 text-xs tracking-tight whitespace-nowrap">
                                            <span>{{ $mDisplayLabel }}</span>
                                            @if($m['is_current'])
                                                <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse flex-shrink-0 shadow-xs" title="Current Month"></span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Gantt Rows Container -->
                        <div class="relative divide-y divide-slate-100">
                            @forelse($ganttTimeline['projects'] as $gp)
                                @php
                                    $proj = $gp['project'];
                                    $health = $proj->computed_health ?? ($gp['health'] ?? 'in_progress');
                                    
                                    $hBadge = match($health) {
                                        'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                        'planning'    => 'bg-sky-50 text-sky-700 border-sky-200/80',
                                        'at_risk'     => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                        'delayed'     => 'bg-rose-50 text-rose-700 border-rose-200/80',
                                        'on_hold'     => 'bg-slate-100 text-slate-600 border-slate-200/80',
                                        default       => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                    };

                                    $hDot = match($health) {
                                        'completed'   => 'bg-emerald-500',
                                        'in_progress' => 'bg-blue-500 animate-pulse',
                                        'planning'    => 'bg-sky-500',
                                        'at_risk'     => 'bg-amber-500 animate-pulse',
                                        'delayed'     => 'bg-rose-500 animate-pulse',
                                        'on_hold'     => 'bg-slate-400',
                                        default       => 'bg-blue-500',
                                    };

                                    $healthLabel = match($health) {
                                        'completed'   => 'Completed',
                                        'in_progress' => 'In Progress',
                                        'planning'    => 'Planning',
                                        'at_risk'     => 'At Risk',
                                        'delayed'     => 'Delayed',
                                        'on_hold'     => 'On Hold',
                                        default       => 'In Progress',
                                    };
                                    
                                    // Soft Modern Capsule Bar Styles
                                    $barBgStyle = match($health) {
                                        'completed'   => 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 2px 10px rgba(5,150,105,0.22);',
                                        'in_progress' => 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 2px 10px rgba(37,99,235,0.22);',
                                        'planning'    => 'background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%); box-shadow: 0 2px 10px rgba(2,132,199,0.22);',
                                        'at_risk'     => 'background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 2px 10px rgba(217,119,6,0.22);',
                                        'delayed'     => 'background: linear-gradient(135deg, #ef4444 0%, #c3122e 100%); box-shadow: 0 2px 10px rgba(195,18,46,0.22);',
                                        'on_hold'     => 'background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%); box-shadow: 0 2px 10px rgba(100,116,139,0.22);',
                                        default       => 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 2px 10px rgba(37,99,235,0.22);',
                                    };

                                    $dateRangeLabel = $gp['start_date']->format('M d') . ' – ' . $gp['end_date']->format('M d');
                                    $barWidthPct = max(13.0, $gp['width_pct']);
                                    $barLeftPct = max(0.0, min(100.0 - $barWidthPct, $gp['left_pct']));
                                @endphp

                                <!-- Project Main Row -->
                                <div class="flex items-center min-h-[64px] h-[64px] hover:bg-slate-50/60 transition-all group relative z-0">
                                    <!-- Left Column: Spacious 2-Row Project Info -->
                                    <div class="w-[360px] min-w-[360px] flex-shrink-0 px-4 py-2.5 h-[64px] border-r border-slate-200/80 bg-white group-hover:bg-slate-50 sticky left-0 z-20 transition-colors flex flex-col justify-center gap-1">
                                        <!-- Row 1: Expand Button + Project Name -->
                                        <div class="flex items-center gap-2 min-w-0">
                                            <button wire:click="toggleGanttExpand({{ $proj->id }})"
                                                    class="w-4 h-4 rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors shrink-0 cursor-pointer"
                                                    title="{{ $gp['is_expanded'] ? 'Collapse Deliverables' : 'Expand Deliverables' }}">
                                                <svg class="w-3 h-3 transition-transform duration-200 {{ $gp['is_expanded'] ? 'rotate-90 text-[#c3122e]' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </button>

                                            <a href="{{ route('projects.show', $proj->id) }}?tab=wbs" 
                                               class="font-bold text-sm text-slate-900 hover:text-[#c3122e] truncate transition-colors no-underline leading-tight flex-1 min-w-0"
                                               title="{{ $proj->name }}">
                                                {{ $proj->name }}
                                            </a>
                                        </div>

                                        <!-- Row 2: Status Pill & PM Name -->
                                        <div class="flex items-center gap-2 pl-6 whitespace-nowrap overflow-hidden">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10.5px] font-bold border {{ $hBadge }} shrink-0">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $hDot }}"></span>
                                                <span>{{ $healthLabel }} ({{ $gp['progress'] }}%)</span>
                                            </span>

                                            @if($proj->projectManager)
                                                <span class="text-slate-300">•</span>
                                                <span class="truncate text-slate-600 text-[11.5px] font-medium" title="{{ $proj->projectManager->name }}">
                                                    {{ $proj->projectManager->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right Column: Timeline Track & Capsule Bar -->
                                    <div class="flex-1 min-w-[1060px] relative h-[64px] flex items-center px-0">
                                        <!-- Vertical Month Grid -->
                                        <div class="absolute inset-0 flex pointer-events-none">
                                            @foreach($ganttTimeline['months'] as $m)
                                                 <div class="border-r border-slate-100 h-full {{ $m['is_current'] ? 'bg-rose-50/10' : '' }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                            @endforeach
                                        </div>

                                        <!-- Red Vertical Today Line -->
                                        @if($ganttTimeline['today_visible'])
                                            <div class="absolute top-0 bottom-0 pointer-events-none z-10 flex flex-col items-center" 
                                                 style="left: {{ $ganttTimeline['today_pct'] }}%;">
                                                <div class="w-[2px] h-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></div>
                                            </div>
                                        @endif

                                        @if($gp['is_out_of_bounds'])
                                            @if($gp['left_pct'] <= 0)
                                                <div class="absolute left-3 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full shadow-2xs">
                                                    <span>Ended {{ $gp['end_date']->format('M d') }} ({{ $gp['progress'] }}%)</span>
                                                </div>
                                            @else
                                                <div class="absolute right-3 z-10 flex items-center gap-1.5 text-[10px] font-mono text-slate-500 font-semibold bg-slate-100/90 border border-slate-200 px-3 py-1 rounded-full shadow-2xs">
                                                    <span>Starts {{ $gp['start_date']->format('M d') }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Full-width Outer Track Container for modern Gantt look -->
                                            <div class="w-full h-9 rounded-xl bg-slate-100/70 border border-slate-200/60 flex items-center relative overflow-hidden px-0.5 mx-3 shadow-inner">
                                                <!-- Modern Capsule Bar -->
                                                <a href="{{ route('projects.show', $proj->id) }}?tab=wbs"
                                                   class="absolute h-full rounded-lg transition-all duration-200 flex items-center justify-center overflow-hidden cursor-pointer z-20 text-white no-underline hover:brightness-105 shadow-xs"
                                                   style="left: {{ $barLeftPct }}%; width: {{ $barWidthPct }}%; {{ $barBgStyle }}"
                                                   title="{{ $proj->name }} • {{ $gp['start_date']->format('M d, Y') }} – {{ $gp['end_date']->format('M d, Y') }} ({{ $gp['progress'] }}% Complete)">
                                                    
                                                    @if($gp['progress'] > 0)
                                                        <div class="absolute inset-y-0 left-0 bg-white/25 rounded-l-lg pointer-events-none transition-all"
                                                             style="width: {{ $gp['progress'] }}%;"></div>
                                                    @endif

                                                    <!-- Date Range & Percentage -->
                                                    <div class="flex items-center justify-center gap-1.5 px-3.5 w-full text-white font-extrabold text-[11.5px] tracking-tight whitespace-nowrap overflow-hidden select-none pointer-events-none drop-shadow-xs">
                                                        <span>{{ $dateRangeLabel }}</span>
                                                        <span class="opacity-95 font-mono text-xs font-black">({{ $gp['progress'] }}%)</span>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Expanded WBS Tasks Loop -->
                                @if($gp['is_expanded'])
                                    @forelse($gp['wbs_tasks'] as $task)
                                        @php
                                            $tStatus = $task['status'];
                                            $tStatusBadge = match($tStatus) {
                                                'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'blocked'     => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                                                'delayed'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default       => 'bg-slate-50 text-slate-700 border-slate-200',
                                            };
                                            $tBarBgStyle = match($tStatus) {
                                                'completed'   => 'background: linear-gradient(135deg, #10b981 0%, #059669 100%);',
                                                'in_progress' => 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);',
                                                'blocked'     => 'background: linear-gradient(135deg, #ef4444 0%, #c3122e 100%);',
                                                'delayed'     => 'background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);',
                                                default       => 'background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);',
                                            };
                                            $tWidthPct = max(6.0, $task['width_pct']);
                                            $tLeftPct = max(0.0, min(100.0 - $tWidthPct, $task['left_pct']));
                                        @endphp
                                        <div class="flex items-center min-h-[36px] h-[36px] bg-slate-50/40 hover:bg-slate-100/50 transition-colors border-b border-slate-100/80 group/task relative z-0">
                                            <div class="w-[360px] min-w-[360px] flex-shrink-0 pl-8 pr-3 h-[36px] border-r border-slate-200/80 bg-slate-50/60 group-hover/task:bg-slate-100/70 sticky left-0 z-20 transition-colors flex items-center justify-between gap-2">
                                                <div class="flex items-center gap-1.5 min-w-0 flex-1">
                                                    <span class="text-slate-300 font-mono text-xs select-none">└─</span>
                                                    <span class="font-mono font-bold text-[9.5px] text-slate-500 shrink-0">{{ $task['wbs_code'] }}</span>
                                                    <span class="text-xs font-semibold text-slate-700 truncate" title="{{ $task['title'] }}">{{ $task['title'] }}</span>
                                                </div>

                                                <span class="px-1.5 py-0.2 rounded text-[8.5px] font-bold border uppercase shrink-0 {{ $tStatusBadge }}">
                                                    {{ strtoupper(str_replace('_', ' ', $task['status'])) }}
                                                </span>
                                            </div>

                                            <div class="flex-1 min-w-[1060px] relative h-[38px] flex items-center px-0">
                                                <div class="absolute inset-0 flex pointer-events-none">
                                                    @foreach($ganttTimeline['months'] as $m)
                                                        <div class="border-r border-slate-100/60 h-full {{ $m['is_current'] ? 'bg-rose-50/10' : '' }}" style="width: {{ $m['width_pct'] }}%;"></div>
                                                    @endforeach
                                                </div>

                                                @if(!$task['is_out_of_bounds'])
                                                    <div class="w-full h-6.5 rounded-lg bg-slate-100/60 border border-slate-200/50 flex items-center relative overflow-hidden px-0.5 mx-3">
                                                        <div class="absolute h-full rounded-md text-white flex items-center justify-between px-2.5 text-[10px] font-bold shadow-2xs"
                                                             style="left: {{ $tLeftPct }}%; width: {{ $tWidthPct }}%; {{ $tBarBgStyle }}"
                                                             title="{{ $task['title'] }} • {{ $task['start_date']->format('M d') }} – {{ $task['end_date']->format('M d') }}">
                                                            <span class="truncate">{{ $task['title'] }}</span>
                                                            <span class="font-mono ml-1 opacity-90">{{ $task['progress'] }}%</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="py-2 px-10 bg-slate-50/30 text-xs text-slate-400 italic">
                                            No WBS tasks recorded.
                                        </div>
                                    @endforelse
                                @endif
                            @empty
                                <div class="py-16 text-center text-slate-400 font-medium">
                                    <span class="text-3xl block mb-1">📊</span>
                                    <span class="text-sm font-bold text-slate-700">No projects match the timeline filter</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- ═══════════════════════════════════════════════════════════════
         VIEW 3: STUCK & BLOCKED TASKS LIVE RADAR
         ═══════════════════════════════════════════════════════════════ -->
    @elseif($viewMode === 'stuck')
        <div class="space-y-4">
            <!-- 1. 4 KPI Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 sm:gap-4">
                <button type="button" wire:click="setStuckTypeFilter('all')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'all' ? 'border-slate-800 ring-2 ring-slate-800/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $totalStuckTasksCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Total Stuck</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('blocked')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'blocked' ? 'border-rose-500 ring-2 ring-rose-500/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $blockedTasksOnlyCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Blocked Tasks</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('on_hold')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'on_hold' ? 'border-amber-500 ring-2 ring-amber-500/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-amber-200 bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $onHoldTasksCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">On Hold</div>
                    </div>
                </button>

                <button type="button" wire:click="setStuckTypeFilter('overdue')"
                        class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4 sm:p-5 flex items-center gap-3.5 text-left transition-all hover:shadow-md cursor-pointer {{ $stuckTypeFilter === 'overdue' ? 'border-rose-600 ring-2 ring-rose-600/10 shadow-xs' : '' }}">
                    <div class="w-10 h-10 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl sm:text-[26px] font-bold text-slate-900 leading-none">{{ $overdueTasksOnlyCount }}</div>
                        <div class="text-xs text-slate-500 font-medium mt-1">Overdue Deliverables</div>
                    </div>
                </button>
            </div>

            <!-- 2. Search & Filter Bar -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs p-4">
                <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 flex-1 flex-wrap">
                        <div class="relative min-w-[240px] flex-1">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search task, project, reason, or assignee..."
                                   class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-1.5 focus:ring-slate-400 transition-all">
                        </div>

                        <select wire:model.live="stuckProjectFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer max-w-[170px] truncate">
                            <option value="all">📁 All Projects</option>
                            @foreach($stuckProjectsList as $sp)
                                <option value="{{ $sp->id }}">{{ $sp->code }} - {{ $sp->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckAssigneeFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer max-w-[160px] truncate">
                            <option value="all">👤 All Assignees</option>
                            @foreach($stuckAssigneesList as $sa)
                                <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="stuckPriorityFilter" class="custom-select px-3 py-2 bg-white border border-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 shadow-2xs hover:bg-slate-50 focus:outline-none cursor-pointer">
                            <option value="all">⚡ All Priority</option>
                            <option value="critical">Critical / Urgent</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>

                    @if($search || $stuckTypeFilter !== 'all' || $stuckProjectFilter !== 'all' || $stuckAssigneeFilter !== 'all' || $stuckPriorityFilter !== 'all')
                        <button wire:click="resetStuckFilters" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all flex items-center justify-center gap-1 cursor-pointer shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Clear Filters</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- 3. Stuck Tasks Table -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider select-none">
                                <th class="py-3.5 px-4">Task / Deliverable</th>
                                <th class="py-3.5 px-4">Project</th>
                                <th class="py-3.5 px-4">Assignee</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Delay Days</th>
                                <th class="py-3.5 px-4">Reason / Blocker</th>
                                <th class="py-3.5 px-4">Priority</th>
                                <th class="py-3.5 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($stuckTasks as $task)
                                @php
                                    $pObj = $task->project;
                                    $assignee = $task->assignedUser;
                                    $isBlocked = $task->status->value === 'blocked' || ($task->blockers && $task->blockers->where('status', 'open')->count() > 0);
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <!-- 1. Task Title & Code -->
                                    <td class="py-3.5 px-4 align-middle">
                                        <div class="flex items-center gap-2">
                                            @if($isBlocked)
                                                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0"></span>
                                            @endif
                                            <div>
                                                <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="font-bold text-slate-900 hover:text-[#c3122e] no-underline block leading-tight">
                                                    {{ $task->title }}
                                                </a>
                                                <span class="text-[10px] font-mono text-slate-400">{{ $task->wbs_code }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Project -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-semibold text-slate-700 hover:text-[#c3122e] no-underline">
                                            {{ $pObj->code ?? 'PRJ' }}
                                        </a>
                                        <span class="text-[10.5px] text-slate-400 block truncate max-w-[120px]">{{ $pObj->name ?? '—' }}</span>
                                    </td>

                                    <!-- 3. Assignee -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($assignee)
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center">
                                                    {{ strtoupper(substr($assignee->name, 0, 1)) }}
                                                </span>
                                                <span class="font-medium text-slate-800">{{ $assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">Unassigned</span>
                                        @endif
                                    </td>

                                    <!-- 4. Status -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        @if($isBlocked)
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-100 text-rose-800 border border-rose-200">
                                                🛑 Blocked
                                            </span>
                                        @elseif($task->status->value === 'on_hold')
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-amber-100 text-amber-800 border border-amber-200">
                                                ⏸ On Hold
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">
                                                ⚠️ Overdue
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 5. Delay Days -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap font-mono font-bold text-slate-700">
                                        @if($task->computed_overdue_days > 0)
                                            <span class="text-rose-600 font-extrabold">+{{ $task->computed_overdue_days }}d</span>
                                        @else
                                            <span class="text-slate-400 font-normal">—</span>
                                        @endif
                                    </td>

                                    <!-- 6. Blocker / Reason -->
                                    <td class="py-3.5 px-4 align-middle">
                                        @if(!empty($task->delay_reason))
                                            <span class="text-[11px] text-slate-600 italic block line-clamp-1" title="{{ $task->delay_reason }}">
                                                "{{ $task->delay_reason }}"
                                            </span>
                                        @elseif($task->blockers && $task->blockers->where('status', 'open')->count() > 0)
                                            <span class="text-[11px] text-rose-700 font-medium block line-clamp-1">
                                                {{ $task->blockers->where('status', 'open')->first()->description ?? 'Active blocker logged' }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-light text-[11px]">—</span>
                                        @endif
                                    </td>

                                    <!-- 7. Priority -->
                                    <td class="py-3.5 px-4 align-middle whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold capitalize
                                            {{ strtolower($task->priority->value ?? '') === 'critical' ? 'bg-rose-100 text-rose-800' : (strtolower($task->priority->value ?? '') === 'high' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $task->priority->label() }}
                                        </span>
                                    </td>

                                    <!-- 8. Action -->
                                    <td class="py-3.5 px-4 align-middle text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            <button wire:click="nudgeStuckUser({{ $task->id }})" type="button" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 transition-all cursor-pointer flex items-center gap-1 shadow-2xs" title="Send PMO alert to assignee & PM">
                                                <span>🔔 Alert</span>
                                            </button>
                                            <a href="{{ route('projects.show', $task->project_id) }}?tab=wbs" class="p-1 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 transition-colors inline-block no-underline" title="Open Workspace">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-14 text-center text-slate-400 font-medium">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-2xl">🎉</span>
                                            <span class="text-sm font-bold text-slate-700">No stuck tasks found</span>
                                            <span class="text-xs text-slate-400">All deliverables are progressing smoothly without delays.</span>
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

    <!-- ═══════════════════════════════════════════════════════════════
         🔍 WBS DELIVERABLES & TASKS INSPECTOR SLIDE-OVER DRAWER
         ═══════════════════════════════════════════════════════════════ -->
    @if($showQuickDrawer && $selectedDrawerProject)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" wire:click="closeQuickDrawer"></div>
            <div class="fixed inset-y-0 right-0 pl-6 sm:pl-10 max-w-full flex">
                <div class="w-screen max-w-3xl lg:max-w-4xl bg-white shadow-2xl border-l border-slate-200 flex flex-col justify-between animate-in slide-in-from-right duration-200">
                    
                    <!-- 1. Drawer Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/70 flex items-start justify-between gap-4 flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2 py-0.5 rounded-md font-mono font-bold text-[10px] text-[#c3122e] bg-rose-50 border border-rose-200/80">
                                    {{ $selectedDrawerProject->code }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border 
                                    {{ match($selectedDrawerProject->status->value) {
                                        'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'at_risk'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'delayed'     => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default       => 'bg-slate-100 text-slate-700 border-slate-200',
                                    } }}">
                                    {{ $selectedDrawerProject->status->label() }}
                                </span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs font-semibold text-slate-500">
                                    {{ $selectedDrawerProject->subsidiary->name ?? 'George Steuart Group' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 truncate" id="slide-over-title">
                                {{ $selectedDrawerProject->name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                <span>👤 PM: <strong class="text-slate-700">{{ $selectedDrawerProject->projectManager->name ?? 'Unassigned' }}</strong></span>
                                <span class="text-slate-300">•</span>
                                <span>👥 <strong class="text-slate-700">{{ $selectedDrawerProject->members->count() }}</strong> Team Members</span>
                            </p>
                        </div>

                        <!-- Right Header Actions & Close -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" 
                               class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 shadow-2xs transition-all no-underline">
                                <span>Full Workspace</span>
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                            <button wire:click="closeQuickDrawer" type="button" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer" title="Close Drawer">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- 2. Project Executive Status & KPI Summary Strip -->
                    <div class="px-4 sm:px-6 py-3 bg-white border-b border-slate-100 flex flex-wrap items-center justify-between gap-3 flex-shrink-0">
                        <!-- Progress -->
                        <div class="flex items-center gap-3 min-w-[180px]">
                            <div class="w-28 sm:w-36">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 mb-1">
                                    <span>Progress</span>
                                    <span class="font-mono text-[#c3122e]">{{ $selectedDrawerProject->overall_progress }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#c3122e] to-rose-600 rounded-full transition-all duration-300" style="width: {{ $selectedDrawerProject->overall_progress }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Deadline -->
                        <div class="text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Target Deadline</span>
                            <span class="font-bold text-slate-800 {{ $selectedDrawerProject->deadline && $selectedDrawerProject->deadline->isPast() && $selectedDrawerProject->overall_progress < 100 ? 'text-rose-600 font-extrabold' : '' }}">
                                {{ $selectedDrawerProject->deadline ? $selectedDrawerProject->deadline->format('M d, Y') : 'Not Specified' }}
                            </span>
                        </div>

                        <!-- WBS KPI Pills -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <strong>{{ $drawerWbsStats['total'] }}</strong> Tasks
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                <strong>{{ $drawerWbsStats['completed'] }}</strong> Done
                            </span>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200">
                                <strong>{{ $drawerWbsStats['in_progress'] }}</strong> Active
                            </span>
                            @if($drawerWbsStats['blocked'] > 0)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-rose-100 text-rose-900 border border-rose-300 animate-pulse">
                                    🚨 <strong>{{ $drawerWbsStats['blocked'] }}</strong> Blocked
                                </span>
                            @endif
                            @if($drawerWbsStats['overdue'] > 0)
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                    ⚠️ <strong>{{ $drawerWbsStats['overdue'] }}</strong> Overdue
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 3. WBS Search & Filter Toolbar -->
                    <div class="px-4 sm:px-6 py-2.5 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5 flex-shrink-0">
                        <!-- Search Deliverables -->
                        <div class="relative flex-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input 
                                wire:model.live.debounce.250ms="drawerWbsSearch"
                                type="text" 
                                placeholder="Search deliverables, WBS code, assignee..."
                                class="w-full pl-8.5 pr-3 py-1.5 text-xs font-medium bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1.5 focus:ring-[#c3122e]/20 text-slate-800 placeholder-slate-400"
                            >
                        </div>

                        <!-- Status Filter Segment Buttons -->
                        <div class="inline-flex p-0.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold shrink-0 overflow-x-auto">
                            <button 
                                wire:click="setDrawerWbsStatusFilter('all')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'all' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                All ({{ $drawerWbsStats['total'] }})
                            </button>
                            <button 
                                wire:click="setDrawerWbsStatusFilter('in_progress')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'in_progress' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Active ({{ $drawerWbsStats['in_progress'] }})
                            </button>
                            @if($drawerWbsStats['blocked'] > 0 || $drawerWbsStats['overdue'] > 0)
                                <button 
                                    wire:click="setDrawerWbsStatusFilter('blocked')" 
                                    type="button" 
                                    class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'blocked' ? 'bg-rose-600 text-white font-bold shadow-2xs' : 'text-rose-700 hover:bg-rose-50' }}"
                                >
                                    Blocked ({{ $drawerWbsStats['blocked'] }})
                                </button>
                            @endif
                            <button 
                                wire:click="setDrawerWbsStatusFilter('completed')" 
                                type="button" 
                                class="px-2.5 py-1 rounded-lg transition-all cursor-pointer {{ $drawerWbsStatusFilter === 'completed' ? 'bg-[#c3122e] text-white font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                            >
                                Done ({{ $drawerWbsStats['completed'] }})
                            </button>
                        </div>
                    </div>

                    <!-- 4. WBS Deliverables Inspection Table / List Body -->
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1 space-y-2.5 scrollbar-thin">
                        @forelse($drawerWbsItems as $item)
                            @php
                                $itemStatus = is_object($item->status) ? $item->status->value : (string)$item->status;
                                $itemType = is_object($item->item_type) ? $item->item_type->value : (string)$item->item_type;
                                $isExpanded = $selectedInspectorTaskId === $item->id;
                                
                                $statusBadge = match($itemStatus) {
                                    'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'in_progress' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'blocked'     => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                                    'on_hold'     => 'bg-amber-50 text-amber-800 border-amber-200',
                                    default       => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                            @endphp

                            <div class="rounded-2xl border transition-all duration-150 {{ $isExpanded ? 'border-[#c3122e] bg-rose-50/15 shadow-sm ring-1 ring-[#c3122e]/20' : 'border-slate-200/80 bg-white hover:border-slate-300 hover:bg-slate-50/40 shadow-2xs' }}">
                                <!-- Deliverable Row Main Strip -->
                                <div class="p-3.5 flex items-center justify-between gap-3 cursor-pointer select-none" wire:click="selectInspectorTask({{ $item->id }})">
                                    <!-- Left: Checkbox + WBS Code + Title + Assignee -->
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <!-- Checkbox toggle -->
                                        <input 
                                            type="checkbox" 
                                            wire:click.stop="toggleDrawerTask({{ $item->id }})" 
                                            @checked($itemStatus === 'completed')
                                            class="w-4.5 h-4.5 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300 cursor-pointer flex-shrink-0"
                                            title="Mark as Completed"
                                        >

                                        <!-- WBS Code & Type Icon -->
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            @if($item->is_milestone)
                                                <span class="w-5 h-5 rounded-md bg-purple-100 border border-purple-300 text-purple-800 flex items-center justify-center text-[10px]" title="Milestone">💎</span>
                                            @elseif($itemType === 'phase')
                                                <span class="w-5 h-5 rounded-md bg-indigo-100 border border-indigo-300 text-indigo-800 flex items-center justify-center text-[10px]" title="Phase">📦</span>
                                            @else
                                                <span class="w-5 h-5 rounded-md bg-slate-100 border border-slate-300 text-slate-600 flex items-center justify-center text-[10px]" title="Deliverable">📄</span>
                                            @endif
                                            <span class="font-mono font-bold text-[10.5px] text-slate-500 bg-slate-100 px-1.5 py-0.2 rounded border border-slate-200/70">
                                                {{ $item->wbs_code }}
                                            </span>
                                        </div>

                                        <!-- Title & Assignee -->
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-xs text-slate-900 truncate {{ $itemStatus === 'completed' ? 'line-through text-slate-400' : '' }}" title="{{ $item->title }}">
                                                    {{ $item->title }}
                                                </span>
                                                @if($item->has_active_blocker)
                                                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse flex-shrink-0" title="Blocker active"></span>
                                                @endif
                                                @if($item->is_overdue)
                                                    <span class="px-1.5 py-0.2 rounded text-[9.5px] font-black bg-rose-600 text-white shrink-0">
                                                        +{{ $item->overdue_days }}d Overdue
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 text-[10.5px] text-slate-500 mt-0.5">
                                                <span>👤 {{ $item->assignedUser->name ?? 'Unassigned' }}</span>
                                                @if($item->end_date)
                                                    <span class="text-slate-300">•</span>
                                                    <span>📅 Due {{ $item->end_date->format('M d, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right: Progress + Status Badge + Chevron -->
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <!-- Mini Progress Bar -->
                                        <div class="hidden sm:flex items-center gap-2 w-24">
                                            <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-[#c3122e] to-rose-600 rounded-full" style="width: {{ $item->progress }}%"></div>
                                            </div>
                                            <span class="font-mono font-bold text-[10.5px] text-slate-700 w-7 text-right">{{ $item->progress }}%</span>
                                        </div>

                                        <!-- Status Badge -->
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] border uppercase {{ $statusBadge }} shrink-0">
                                            {{ strtoupper(str_replace('_', ' ', $itemStatus)) }}
                                        </span>

                                        <!-- Expand Chevron -->
                                        <button type="button" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $isExpanded ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Expanded WBS Task Detailed Inspection Card -->
                                @if($isExpanded)
                                    <div class="px-4 pb-4 pt-2 border-t border-slate-100/90 bg-slate-50/50 space-y-3 animate-in fade-in duration-150">
                                        <!-- Description -->
                                        @if($item->description)
                                            <div class="p-3 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-700 leading-relaxed">
                                                <strong class="text-slate-900 block text-[10.5px] uppercase tracking-wider mb-1">Description / Deliverable Scope:</strong>
                                                {{ $item->description }}
                                            </div>
                                        @endif

                                        <!-- Details Grid -->
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Start Date</span>
                                                <span class="font-bold text-slate-800">{{ $item->start_date ? $item->start_date->format('M d, Y') : '—' }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">End Date / Deadline</span>
                                                <span class="font-bold {{ $item->is_overdue ? 'text-rose-600' : 'text-slate-800' }}">{{ $item->end_date ? $item->end_date->format('M d, Y') : '—' }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Priority</span>
                                                <span class="font-bold text-slate-800 capitalize">{{ $item->priority->label() }}</span>
                                            </div>
                                            <div class="p-2.5 rounded-xl bg-white border border-slate-200/80">
                                                <span class="text-[10px] font-bold text-slate-400 block">Estimated Hours</span>
                                                <span class="font-bold text-slate-800">{{ $item->estimated_hours ? $item->estimated_hours . ' hrs' : '—' }}</span>
                                            </div>
                                        </div>

                                        <!-- Active Blocker / Delay Reason Alert if any -->
                                        @if(!empty($item->delay_reason) || ($item->blockers && $item->blockers->where('status', 'open')->count() > 0))
                                            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                                                <div class="flex items-center gap-1.5 font-bold text-[#c3122e]">
                                                    <span>🚨</span>
                                                    <span>Active Blocker / Delay Reported:</span>
                                                </div>
                                                <p class="text-[11.5px] italic pl-5">
                                                    "{{ $item->delay_reason ?: $item->blockers->where('status', 'open')->first()->description }}"
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Action Link to Workspace -->
                                        <div class="flex items-center justify-between pt-2">
                                            <span class="text-[11px] text-slate-500 font-medium">Deliverable ID: #{{ $item->id }}</span>
                                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" class="inline-flex items-center gap-1 text-xs font-bold text-[#c3122e] hover:underline no-underline">
                                                <span>Edit &amp; Manage in Project Workspace</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-16 text-center text-slate-400 font-medium bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                <span class="text-3xl block mb-2">📋</span>
                                <span class="text-sm font-bold text-slate-700">No deliverables found</span>
                                <p class="text-xs text-slate-400 mt-0.5">Try changing your search query or filter options.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- 5. Drawer Footer -->
                    <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-shrink-0">
                        <div class="text-xs text-slate-500 font-medium">
                            Showing <strong class="text-slate-800">{{ $drawerWbsItems->count() }}</strong> of <strong class="text-slate-800">{{ $drawerWbsStats['total'] }}</strong> deliverables
                        </div>

                        <div class="flex items-center gap-2.5">
                            <button wire:click="closeQuickDrawer" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors cursor-pointer">
                                Close
                            </button>
                            <a href="{{ route('projects.show', $selectedDrawerProject->id) }}?tab=wbs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); color: #ffffff;" class="px-5 py-2 rounded-xl text-xs font-black shadow-md hover:shadow-lg transition-all flex items-center gap-1.5 cursor-pointer no-underline">
                                <span>🚀 Open Project Workspace</span>
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    @if($showDelayAnalysis)
        @teleport('body')
        <div
            x-data
            @keydown.escape.window="$wire.closeDelayAnalysis()"
            class="delay-analysis-backdrop"
            style="position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(51, 65, 85, 0.42); backdrop-filter: blur(10px);"
            wire:click.self="closeDelayAnalysis"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delay-analysis-title"
        >
            <div
                wire:key="delay-analysis-{{ $delayProjectId }}"
                x-data
                x-init="$nextTick(() => { $refs.delayList.scrollTop = 0 })"
                style="width: min(1040px, calc(100vw - 32px)); height: min(760px, calc(100dvh - 40px)); max-height: calc(100dvh - 40px); overflow: hidden; border-radius: 22px; background: #ffffff; box-shadow: 0 28px 70px rgba(15, 23, 42, 0.22); border: 1px solid #e2e8f0; display: flex; flex-direction: column;"
            >
                <div class="px-5 sm:px-6 py-4 text-slate-900 bg-white border-b border-slate-200 flex items-center justify-between gap-4 shrink-0">
                    <div>
                        <div class="text-[10px] uppercase tracking-[.18em] font-black text-[#c3122e]">Critical delay analysis</div>
                        <div class="flex items-center gap-2.5 mt-1 flex-wrap">
                            <h2 id="delay-analysis-title" class="text-base font-black">{{ $delayAnalysis['project_name'] ?? '' }}</h2>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $delayAnalysis['project_code'] ?? '' }}</span>
                            <span class="text-[11px] text-slate-500">{{ $delayAnalysis['critical_issue_count'] ?? 0 }} critical issue(s)</span>
                            <span class="text-[11px] text-slate-500">&bull; {{ count($delayAnalysis['issues'] ?? []) }} delayed task(s)</span>
                        </div>
                    </div>
                    <button type="button" wire:click="closeDelayAnalysis" class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-[#c3122e] font-black cursor-pointer inline-flex items-center justify-center transition-colors" aria-label="Close delay analysis">&times;</button>
                </div>

                <div x-ref="delayList" class="min-h-0 flex-1 p-3 sm:p-4 overflow-y-auto overscroll-contain space-y-2.5 bg-slate-50/70 scroll-smooth" style="min-height: 0;">
                    @php $delaySummary = $delayAnalysis['summary'] ?? []; @endphp
                    @if(!empty($delaySummary['reasons']) || !empty($delaySummary['root_causes']))
                        <section class="rounded-xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-white px-4 py-3.5">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div class="text-[10px] font-black uppercase tracking-[.16em] text-[#c3122e]">Why is this project delayed?</div>
                                @if(($delayAnalysis['project_overdue_days'] ?? 0) > 0)
                                    <span class="px-2.5 py-1 rounded-full bg-rose-100 text-[11px] font-black text-rose-700">Project {{ $delayAnalysis['project_overdue_days'] }} days past deadline</span>
                                @endif
                            </div>

                            <div class="mt-2.5 grid grid-cols-1 lg:grid-cols-2 gap-3">
                                <div class="space-y-2">
                                    @foreach(($delaySummary['reasons'] ?? []) as $reason)
                                        <div class="flex items-start gap-2">
                                            <span class="mt-1.5 w-1.5 h-1.5 rounded-full shrink-0 {{ in_array($reason['type'], ['deadline', 'blocker'], true) ? 'bg-rose-500' : ($reason['type'] === 'dependency' ? 'bg-amber-500' : 'bg-slate-400') }}"></span>
                                            <p class="text-[11px] text-slate-600 leading-relaxed"><strong class="text-slate-800">{{ $reason['label'] }}.</strong> {{ $reason['detail'] }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-3">
                                    @if(!empty($delaySummary['root_causes']))
                                        <div>
                                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Root-cause tasks</div>
                                            <div class="mt-1.5 space-y-1.5">
                                                @foreach($delaySummary['root_causes'] as $root)
                                                    <div class="flex items-center justify-between gap-2 rounded-lg bg-white border border-slate-200 px-2.5 py-1.5">
                                                        <div class="min-w-0">
                                                            <p class="text-[11px] font-bold text-slate-800 truncate"><span class="font-mono text-slate-500">{{ $root['wbs_code'] }}</span> {{ $root['task'] }}</p>
                                                            <p class="text-[10px] text-slate-500 truncate">Owner: {{ $root['responsible'] }}</p>
                                                        </div>
                                                        <div class="text-right shrink-0">
                                                            @if($root['overdue_days'] > 0)<p class="text-[10px] font-black text-rose-700">{{ $root['overdue_days'] }}d late</p>@endif
                                                            <p class="text-[10px] text-slate-500">causes {{ $root['affected'] }} {{ \Illuminate\Support\Str::plural('task', $root['affected']) }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(!empty($delaySummary['responsible_people']))
                                        <div>
                                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Responsible people</div>
                                            <div class="mt-1.5 flex flex-wrap gap-1.5">
                                                @foreach($delaySummary['responsible_people'] as $person)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-700" title="{{ $person['email'] }}">
                                                        <span class="w-4 h-4 rounded-full bg-slate-800 text-white text-[9px] font-black inline-flex items-center justify-center">{{ strtoupper(substr($person['name'], 0, 1)) }}</span>
                                                        {{ $person['name'] }}
                                                        <span class="text-rose-600">{{ $person['task_count'] }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif
                    @forelse(($delayAnalysis['issues'] ?? []) as $issue)
                        <article class="bg-white rounded-xl border border-slate-200 px-4 py-3.5 shadow-xs">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black {{ $issue['is_critical'] ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-800' }}">{{ $issue['is_critical'] ? 'CRITICAL PATH IMPACT' : 'DELAY' }}</span>
                                        @if($issue['wbs_code'])<span class="text-[11px] font-mono font-bold text-slate-500">{{ $issue['wbs_code'] }}</span>@endif
                                    </div>
                                    <h3 class="text-sm font-extrabold text-slate-900 mt-1 leading-snug break-words">{{ $issue['task'] }}</h3>
                                </div>
                                @if($issue['overdue_days'] > 0)<span class="px-2.5 py-1 rounded-full bg-rose-50 text-xs font-black text-rose-700 whitespace-nowrap">{{ $issue['overdue_days'] }} days late</span>@endif
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-[1fr_250px] gap-2.5 mt-2.5 items-start">
                                <div class="rounded-lg bg-slate-50 border border-slate-200 px-3 py-2.5 min-w-0 self-start">
                                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Why this task is delayed</div>
                                    <div class="mt-2 space-y-2">
                                        @forelse(($issue['causes'] ?? []) as $cause)
                                            <div class="flex items-start gap-2">
                                                <span class="mt-1.5 w-1.5 h-1.5 rounded-full shrink-0 {{ in_array($cause['type'], ['dependency', 'root'], true) ? 'bg-amber-500' : (in_array($cause['type'], ['blocker', 'status'], true) ? 'bg-rose-500' : 'bg-slate-400') }}"></span>
                                                <p class="text-[11px] text-slate-600 leading-relaxed"><strong class="text-slate-800">{{ $cause['label'] }}:</strong> {{ $cause['detail'] }}@if(!empty($cause['responsible'])) <span class="inline-block ml-1 px-1.5 py-px rounded bg-slate-200/70 text-[10px] font-bold text-slate-600 whitespace-nowrap">Owner: {{ $cause['responsible'] }}</span>@endif</p>
                                            </div>
                                        @empty
                                            <p class="text-[11px] text-slate-600 leading-relaxed">{{ $issue['reason'] }}</p>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="rounded-lg bg-white border border-slate-200 px-3 py-2.5 min-w-0 self-start">
                                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Responsible person</div>
                                    <p class="text-xs font-black text-slate-900 mt-1 truncate" title="{{ $issue['responsible'] }}">{{ $issue['responsible'] }}</p>
                                    @if($issue['responsible_email'])<p class="text-[11px] text-slate-500 mt-0.5 truncate" title="{{ $issue['responsible_email'] }}">{{ $issue['responsible_email'] }}</p>@endif
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 grid grid-cols-2 gap-1.5 text-[10px]">
                                        <div><span class="text-slate-400 block">Progress</span><strong class="text-slate-700">{{ $issue['progress'] ?? 0 }}%</strong></div>
                                        <div><span class="text-slate-400 block">Status</span><strong class="text-slate-700">{{ \Illuminate\Support\Str::headline($issue['status'] ?? '') }}</strong></div>
                                        @if(!empty($issue['end_date']))<div class="col-span-2"><span class="text-slate-400 block">Planned end</span><strong class="text-slate-700">{{ $issue['end_date'] }}</strong></div>@endif
                                    </div>
                                    @if($issue['downstream_count'] > 0)
                                        <p class="text-[10px] font-bold text-rose-600 mt-2">Blocks {{ $issue['downstream_count'] }} downstream {{ \Illuminate\Support\Str::plural('task', $issue['downstream_count']) }}</p>
                                        @foreach(($issue['downstream_tasks'] ?? []) as $down)
                                            <p class="text-[10px] text-slate-500 truncate" title="{{ $down['task'] }}"><span class="font-mono">{{ $down['wbs_code'] }}</span> {{ $down['task'] }}</p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="py-12 text-center text-slate-500 font-semibold">No task-level delay reason has been detected.</div>
                    @endforelse
                </div>

                <div class="px-5 py-2.5 border-t border-slate-200 bg-white flex justify-end gap-2 shrink-0">
                    <button type="button" wire:click="closeDelayAnalysis" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 cursor-pointer transition-colors">Close</button>
                    @if($delayProjectId)<a href="{{ route('projects.show', $delayProjectId) }}?tab=wbs" class="px-5 py-2 rounded-xl text-xs font-black bg-rose-700 text-white no-underline">Open WBS workspace</a>@endif
                </div>
            </div>
        </div>
        @endteleport
    @endif

    <!-- ===== DELETE PROJECT CONFIRMATION MODAL ===== -->
    @if($showDeleteModal)
        @teleport('body')
        <div
            x-data
            @keydown.escape.window="$wire.cancelDelete()"
            wire:click.self="cancelDelete"
            class="delete-project-backdrop"
            style="position: fixed; inset: 0; z-index: 2147483640; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: rgba(51, 65, 85, 0.48); backdrop-filter: blur(10px); box-sizing: border-box;"
            role="dialog"
            aria-modal="true"
            aria-labelledby="delete-project-title"
        >

            <div style="position: relative; width: min(520px, calc(100vw - 32px)); max-height: calc(100dvh - 40px); background: #ffffff; border-radius: 22px; box-shadow: 0 28px 70px rgba(15, 23, 42, 0.24); border: 1px solid #e2e8f0; overflow-y: auto; z-index: 10; padding: 22px;" class="space-y-4.5 overscroll-contain">
                <div class="flex items-start justify-between gap-3.5 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-sm">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="delete-project-title" class="text-base font-extrabold text-slate-900 tracking-tight">Delete Project Permanently?</h3>
                            <p class="text-xs text-slate-500 font-medium">This action cannot be undone</p>
                        </div>
                    </div>
                    <button wire:click="cancelDelete" type="button" class="w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center font-bold text-sm transition-all cursor-pointer">
                        ✕
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4">
                        <div class="text-[11px] text-slate-500 font-bold uppercase tracking-wider mb-1.5">Target Project</div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 text-xs font-black tracking-wide">{{ $projectToDeleteCode }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ $projectToDeleteName }}</span>
                        </div>
                        <div class="flex items-center gap-4 mt-3 pt-3 border-t border-slate-200/60 text-xs text-slate-600 font-medium">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <strong>{{ $projectToDeleteTasksCount }}</strong> Tasks / WBS Items
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                <strong>{{ $projectToDeleteDocsCount }}</strong> Documents
                            </span>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-1.5">
                        <div class="flex items-center gap-2 font-black text-[#c3122e]">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Permanent System Purge Warning</span>
                        </div>
                        <p class="text-[11.5px] leading-relaxed text-rose-950 font-medium">
                            All associated WBS tasks, dependencies, blockers, documents, risks, status updates, and records will be permanently removed from the system. A PMO audit log entry will be preserved.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button wire:click="cancelDelete" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button 
                        wire:click="deleteProject" 
                        wire:loading.attr="disabled"
                        type="button" 
                        class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-gradient-to-r from-[#c3122e] to-rose-700 hover:from-rose-700 hover:to-rose-800 shadow-md shadow-rose-600/20 hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="deleteProject" class="animate-spin w-4 h-4 text-white" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteProject">Permanently Delete Project</span>
                        <span wire:loading wire:target="deleteProject">Deleting Project...</span>
                    </button>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
