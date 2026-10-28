<div data-ui-page="lead-projects" class="ui-page ui-page-projects">
    {{-- ══════════════════════════════════════════════════════════
         1. TOP HEADER — MY PROJECTS
         ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                My Projects
            </h1>
        </div>
        @if(auth()->user()->canCreateProject())
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4.5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer active:scale-95 no-underline" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Create New Project</span>
                </a>
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════
         2. KPI TILES
         ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
        @php
            $metricTiles = [
                ['label' => 'Total Projects', 'count' => $totalCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>', 'iconBg' => 'bg-slate-100 text-slate-700', 'border' => 'hover:border-slate-400', 'active' => $statusFilter === 'all', 'action' => '$set("statusFilter", "all")'],
                ['label' => 'In Progress', 'count' => $activeCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', 'iconBg' => 'bg-blue-50 text-blue-600', 'border' => 'hover:border-blue-400', 'active' => $statusFilter === 'in_progress', 'action' => '$set("statusFilter", "in_progress")'],
                ['label' => 'Overdue Attention', 'count' => $overdueCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>', 'iconBg' => 'bg-rose-50 text-rose-600', 'border' => 'hover:border-rose-400', 'active' => $statusFilter === 'overdue', 'action' => '$set("statusFilter", "overdue")'],
                ['label' => 'Completed', 'count' => $completedCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'iconBg' => 'bg-emerald-50 text-emerald-600', 'border' => 'hover:border-emerald-400', 'active' => $statusFilter === 'completed', 'action' => '$set("statusFilter", "completed")'],
            ];
        @endphp
        @foreach($metricTiles as $tile)
            <button type="button" @if($tile['action']) wire:click="{{ $tile['action'] }}" @endif
                class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $tile['active'] ? 'border-rose-300 bg-rose-50/40 shadow-sm ring-2 ring-rose-500/10' : 'shadow-xs ' . $tile['border'] }}"
            >
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-2.5">
                    <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">{{ $tile['label'] }}</span>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl {{ $tile['iconBg'] }} flex items-center justify-center flex-shrink-0 shadow-xs">{!! $tile['icon'] !!}</div>
                </div>
                <div class="flex items-baseline justify-between gap-1 sm:gap-2">
                    <span class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $tile['count'] }}</span>
                    @if($tile['count'] > 0 && $tile['label'] === 'Overdue Attention')
                        <span class="text-[8px] sm:text-[9px] font-black text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200">Action</span>
                    @endif
                </div>
            </button>
        @endforeach
    </div>

    {{-- ══════════════════════════════════════════════════════════
         3. SEARCH & FILTER TOOLBAR
         ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3.5">
        {{-- Search --}}
        <div class="relative flex-1 min-w-0">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="text" wire:model.live.debounce.250ms="search" placeholder="Search projects by name, code, or subsidiary..."
                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all">
            @if($search)
                <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            @endif
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap flex-shrink-0">
            {{-- Status Filter --}}
            <div class="min-w-[160px]">
                <select wire:model.live="statusFilter"
                    class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                    <option value="all">📊 All Statuses</option>
                    <option value="in_progress">🔵 In Progress</option>
                    <option value="overdue">🔴 Overdue Attention</option>
                    <option value="completed">🟢 Completed</option>
                    <option value="planning">🟣 Planning</option>
                    <option value="at_risk">🟠 At Risk</option>
                    <option value="on_hold">🟡 On Hold</option>
                    <option value="under_review">🟣 Under Review</option>
                </select>
            </div>

            {{-- Role Filter --}}
            <div class="min-w-[180px]">
                <select wire:model.live="roleFilter"
                    class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                    <option value="all">👥 All My Roles ({{ $totalCount }})</option>
                    <option value="lead">⭐ Project Manager (PM) ({{ $roleCounts['lead'] ?? 0 }})</option>
                    <option value="sponsor">💼 Project Sponsor ({{ $roleCounts['sponsor'] ?? 0 }})</option>
                    <option value="owner">🏛️ Project Owner ({{ $roleCounts['owner'] ?? 0 }})</option>
                    <option value="steering_committee">🎖️ Steering Committee ({{ $roleCounts['steering_committee'] ?? 0 }})</option>
                    <option value="member">🤝 Team Member ({{ $roleCounts['member'] ?? 0 }})</option>
                </select>
            </div>

            {{-- Reset --}}
            @if($search || $statusFilter !== 'all' || $roleFilter !== 'all')
                <button type="button" wire:click="clearFilters"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] hover:bg-rose-100/70 border border-rose-200 transition-colors cursor-pointer flex-shrink-0 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         4. PROJECTS TABLE
         ══════════════════════════════════════════════════════════ --}}
    @if($projects->count() > 0)
    <div class="bg-white border border-slate-200/90 rounded-3xl overflow-hidden shadow-sm mb-6">
        <div class="my-projects-drag-scroll overflow-x-auto scrollbar-thin"
            x-data="{
                panStart: null,
                isPanning: false,
                beginPan(event) {
                    if (event.pointerType !== 'mouse' || event.button !== 0 || this.$el.scrollWidth &lt;= this.$el.clientWidth) return;
                    if (event.target.closest('a, button, input, select, textarea, [contenteditable], [role=button]')) return;
                    const rect = this.$el.getBoundingClientRect();
                    if (event.clientX >= rect.left + this.$el.clientWidth || event.clientY >= rect.top + this.$el.clientHeight) return;
                    this.panStart = { id: event.pointerId, x: event.clientX, left: this.$el.scrollLeft };
                    event.preventDefault();
                },
                movePan(event) {
                    if (!this.panStart || event.pointerId !== this.panStart.id) return;
                    if (!(event.buttons &amp; 1)) { this.endPan(); return; }
                    const delta = event.clientX - this.panStart.x;
                    if (!this.isPanning &amp;&amp; Math.abs(delta) &lt; 4) return;
                    if (!this.isPanning) this.$el.setPointerCapture(event.pointerId);
                    this.isPanning = true;
                    event.preventDefault();
                    this.$el.scrollLeft = this.panStart.left - delta;
                },
                endPan() {
                    if (this.panStart &amp;&amp; this.$el.hasPointerCapture(this.panStart.id)) this.$el.releasePointerCapture(this.panStart.id);
                    this.panStart = null;
                    this.isPanning = false;
                }
            }"
            @pointerdown="beginPan($event)" @pointermove.window="movePan($event)" @pointerup.window="endPan()"
            @pointercancel.window="endPan()" @blur.window="endPan()" @lostpointercapture="panStart = null; isPanning = false"
            :class="{ 'is-panning': isPanning }" tabindex="0" role="region" aria-label="My Projects table, drag empty space to scroll horizontally">
            <style>
                .my-projects-drag-scroll { cursor: grab; }
                .my-projects-drag-scroll.is-panning, .my-projects-drag-scroll.is-panning * { cursor: grabbing !important; user-select: none; }
                .my-projects-drag-scroll a, .my-projects-drag-scroll button { cursor: pointer; }
                .my-projects-drag-scroll:focus-visible { outline: 2px solid #c3122e; outline-offset: -2px; }
            </style>
            <table class="w-full text-left border-collapse min-w-[1040px]">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/90 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 pl-5 sm:pl-6 pr-3 w-[35%] sm:w-[30%] lg:w-[26%]">Project &amp; Code</th>
                        <th class="py-3.5 px-3 w-[18%] lg:w-[15%] hidden md:table-cell">Subsidiary</th>
                        <th class="py-3.5 px-3 w-[22%] sm:w-[18%] lg:w-[15%]">Your Role</th>
                        <th class="py-3.5 px-3 w-[12%]">Status</th>
                        <th class="py-3.5 px-3 w-[13%]">Progress</th>
                        <th class="py-3.5 px-3 w-[15%]">Schedule Health</th>
                        <th class="py-3.5 px-3 w-[11%]">Deadline</th>
                        <th class="py-3.5 pl-2 pr-5 sm:pr-6 text-right w-[12%]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs bg-white">
                    @foreach($projects as $p)
                        @php
                            $progress  = $p->overall_progress ?? 0;
                            $daysLeft  = $p->deadline ? (int) now()->today()->diffInDays($p->deadline, false) : null;
                            $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($p->status->value, ['completed','cancelled']);
                            $userRole  = $userRoles[$p->id] ?? 'member';

                            $statusConfig = match($p->status->value) {
                                'in_progress'  => ['dot' => 'bg-blue-600',   'text' => 'text-blue-700',   'bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'bar' => 'bg-blue-500',   'label' => 'In Progress'],
                                'completed'    => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'bar' => 'bg-emerald-500', 'label' => 'Completed'],
                                'at_risk'      => ['dot' => 'bg-amber-500',  'text' => 'text-amber-800',  'bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'bar' => 'bg-amber-500',  'label' => 'At Risk'],
                                'delayed'      => ['dot' => 'bg-rose-500',   'text' => 'text-rose-700',   'bg' => 'bg-rose-50',   'border' => 'border-rose-200',   'bar' => 'bg-rose-500',   'label' => 'Delayed'],
                                'planning'     => ['dot' => 'bg-sky-500',    'text' => 'text-sky-700',    'bg' => 'bg-sky-50',    'border' => 'border-sky-200',    'bar' => 'bg-sky-500',    'label' => 'Planning'],
                                'under_review' => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'bar' => 'bg-purple-500', 'label' => 'Under Review'],
                                'on_hold'      => ['dot' => 'bg-amber-500',  'text' => 'text-amber-800',  'bg' => 'bg-amber-50',  'border' => 'border-amber-200',  'bar' => 'bg-amber-500',  'label' => 'On Hold'],
                                'cancelled'    => ['dot' => 'bg-rose-500',   'text' => 'text-rose-700',   'bg' => 'bg-rose-50',   'border' => 'border-rose-200',   'bar' => 'bg-rose-500',   'label' => 'Cancelled'],
                                default        => ['dot' => 'bg-slate-400',  'text' => 'text-slate-700',  'bg' => 'bg-slate-50',  'border' => 'border-slate-200',  'bar' => 'bg-slate-400',  'label' => $p->status->label()],
                            };

                            $progressBarColor = ($progress >= 100) ? 'bg-emerald-500' : $statusConfig['bar'];

                            $allRoles = \App\Services\RbacService::getAllRoles();
                            $roleMeta = $allRoles[$userRole] ?? null;

                            $roleConfig = match($userRole) {
                                'pmo_admin'          => ['label' => 'PMO Admin',          'icon' => '🛡️', 'bg' => 'bg-slate-100',    'text' => 'text-slate-800',     'border' => 'border-slate-300'],
                                'lead'               => ['label' => 'Project Manager',     'icon' => '⭐', 'bg' => 'bg-amber-50',     'text' => 'text-amber-800',     'border' => 'border-amber-200/90'],
                                'sponsor'            => ['label' => 'Project Sponsor',    'icon' => '💼', 'bg' => 'bg-purple-50',   'text' => 'text-purple-800',    'border' => 'border-purple-200/90'],
                                'owner'              => ['label' => 'Project Owner',      'icon' => '🏛️', 'bg' => 'bg-blue-50',     'text' => 'text-blue-800',      'border' => 'border-blue-200/90'],
                                'steering_committee' => ['label' => 'Steering Committee', 'icon' => '🎖️', 'bg' => 'bg-rose-50',     'text' => 'text-rose-800',      'border' => 'border-rose-200/90'],
                                'member'             => ['label' => 'Team Member',         'icon' => '🤝', 'bg' => 'bg-emerald-50',  'text' => 'text-emerald-800',   'border' => 'border-emerald-200/90'],
                                default              => [
                                    'label'  => $roleMeta['name'] ?? ucwords(str_replace(['_', '-'], ' ', $userRole)),
                                    'icon'   => $roleMeta['icon'] ?? '🏷️',
                                    'bg'     => 'bg-slate-50',
                                    'text'   => 'text-slate-700',
                                    'border' => 'border-slate-200',
                                ],
                            };
                        @endphp
                        @php $delaySummary = $delaySummaries[$p->id] ?? ['is_delayed' => false, 'issues' => []]; @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            {{-- Project Name & Code --}}
                            <td class="py-3 pl-5 sm:pl-6 pr-3 align-middle">
                                <div class="min-w-0 space-y-0.5">
                                    <a href="{{ route('projects.show', $p) }}" class="font-extrabold text-xs sm:text-[13px] text-slate-900 hover:text-[#c3122e] transition-colors truncate block leading-snug no-underline group-hover:text-[#c3122e]" title="{{ $p->name }}">
                                        {{ $p->name }}
                                    </a>
                                    <div class="flex items-center gap-2 whitespace-nowrap">
                                        <span class="inline-flex items-center px-1.5 py-0.2 rounded-md text-[9.5px] font-mono font-bold text-[#c3122e] bg-rose-50 border border-rose-200/80 shrink-0 whitespace-nowrap shadow-2xs">
                                            {{ $p->code }}
                                        </span>
                                        @if($p->members && $p->members->count() > 0)
                                            <span class="text-[10px] font-medium text-slate-400 whitespace-nowrap shrink-0">
                                                • {{ $p->members->count() }} {{ Str::plural('Member', $p->members->count()) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Subsidiary --}}
                            <td class="py-3 px-3 hidden md:table-cell align-middle">
                                <span class="text-xs text-slate-700 font-bold flex items-center gap-1.5">
                                    <span class="text-slate-400 text-xs">🏢</span>
                                    <span class="truncate max-w-[150px]">{{ $p->subsidiary->name ?? 'George Steuart' }}</span>
                                </span>
                            </td>

                            {{-- Your Role Badge --}}
                            <td class="py-3 px-3 align-middle">
                                <div class="flex flex-col items-start gap-1 justify-center min-h-[32px]">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} border {{ $roleConfig['border'] }} shadow-2xs whitespace-nowrap shrink-0">
                                        <span>{{ $roleConfig['icon'] }}</span>
                                        <span>{{ $roleConfig['label'] }}</span>
                                    </span>
                                    {{-- Pending acceptance badge for Lead role --}}
                                    @if($userRole === 'lead' && !$p->isPmAccepted())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-full text-[9px] font-black bg-amber-50 text-amber-700 border border-amber-300 animate-pulse whitespace-nowrap">
                                            ⏳ Pending Accept
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="py-3 px-3 align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }} whitespace-nowrap shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    <span>{{ $statusConfig['label'] }}</span>
                                </span>
                            </td>

                            {{-- Progress --}}
                            <td class="py-3 px-3 align-middle">
                                <div class="space-y-1 w-full min-w-[135px] max-w-[180px]">
                                    <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-500">
                                        <span class="font-mono text-slate-900 font-black">{{ $progress }}%</span>
                                        <span class="text-slate-400 text-[9.5px] font-medium truncate pl-1">{{ $p->wbsItems->count() }} Tasks</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $progressBarColor }}" style="width:{{ min(100, max(0, $progress)) }}%;"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Schedule Health --}}
                            <td class="py-3 px-3 align-middle">
                                @if($delaySummary['is_delayed'] ?? false)
                                    <button type="button" wire:click="openDelayAnalysis({{ $p->id }})"
                                        class="inline-flex min-w-[136px] items-center gap-2 rounded-xl border border-rose-200 bg-rose-50/90 hover:bg-rose-100 hover:border-rose-300 px-2.5 py-1.5 text-left text-rose-700 transition cursor-pointer shadow-2xs hover:shadow-xs group/health"
                                        aria-label="Review delay reasons for {{ $p->name }}">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-rose-600 text-white shadow-2xs group-hover/health:scale-105 transition-transform">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 4.5h.008v.008H12V16.5z"/></svg>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-[11px] font-extrabold leading-tight text-rose-800">Review delay</span>
                                            <span class="mt-0.5 block text-[9.5px] font-bold leading-tight text-rose-600">{{ $delaySummary['project_overdue_days'] ?? 0 }}d late · {{ count($delaySummary['issues'] ?? []) }} {{ Str::plural('issue', count($delaySummary['issues'] ?? [])) }}</span>
                                        </span>
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10.5px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        On schedule
                                    </span>
                                @endif
                            </td>

                            {{-- Deadline --}}
                            <td class="py-3 px-3 align-middle">
                                <div class="flex flex-col gap-1 whitespace-nowrap">
                                    <span class="text-xs font-extrabold {{ $isOverdue ? 'text-rose-600' : 'text-slate-700' }}">{{ $p->deadline ? $p->deadline->format('M d, Y') : 'No deadline' }}</span>
                                    @if($isOverdue)
                                        <span class="text-[9.5px] font-extrabold text-rose-500">{{ abs($daysLeft) }}d overdue</span>
                                    @elseif($daysLeft !== null && $daysLeft >= 0)
                                        <span class="text-[9.5px] font-bold text-slate-400">{{ $daysLeft }}d left</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="py-3 pl-2 pr-5 sm:pr-6 text-right align-middle">
                                @if($userRole === 'lead' && !$p->isPmAccepted() && ($p->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin()))
                                    <button type="button" wire:click="openReviewModal({{ $p->id }})"
                                        class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-xl text-xs font-black text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-300 shadow-2xs cursor-pointer transition-all active:scale-95 whitespace-nowrap">
                                        <span>Review &amp; Accept</span>
                                        <span class="text-[10px]">➔</span>
                                    </button>
                                @else
                                    <a href="{{ route('projects.show', $p) }}"
                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-[#c3122e] hover:text-white border border-slate-200 shadow-2xs transition-all duration-150 no-underline whitespace-nowrap shrink-0 active:scale-98 group">
                                        <span>Open Workspace</span>
                                        <span class="text-[10px] text-slate-400 group-hover:text-white transition-colors">➔</span>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>

    @else
    <div class="bg-white border border-slate-200/90 rounded-3xl p-14 text-center shadow-xs space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center mx-auto mb-2 shadow-xs">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <h3 class="text-base font-black text-slate-900">No Projects Found</h3>
        <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">
            @if($search || $statusFilter !== 'all' || $roleFilter !== 'all')
                No projects match your selected filters. <button wire:click="clearFilters" class="text-[#c3122e] font-black underline cursor-pointer">Clear Filters</button>
            @else
                You have not been assigned to any project yet. Contact your PMO Admin.
            @endif
        </p>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         5. PROJECT LEADERSHIP REVIEW & ACCEPTANCE MODAL
         ══════════════════════════════════════════════════════════ --}}
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
            aria-labelledby="delay-analysis-title-myleads"
        >
            <div
                wire:key="lead-delay-analysis-{{ $delayProjectId }}"
                x-data
                x-init="$nextTick(() => { if ($refs.delayList) $refs.delayList.scrollTop = 0 })"
                style="width: min(1040px, calc(100vw - 32px)); height: min(760px, calc(100dvh - 40px)); max-height: calc(100dvh - 40px); overflow: hidden; border-radius: 22px; background: #ffffff; box-shadow: 0 28px 70px rgba(15, 23, 42, 0.22); border: 1px solid #e2e8f0; display: flex; flex-direction: column;"
            >
                {{-- Header --}}
                <div class="px-5 sm:px-6 py-4 text-slate-900 bg-white border-b border-slate-200 flex items-center justify-between gap-4 shrink-0">
                    <div>
                        <div class="text-[10px] uppercase tracking-[.18em] font-black text-[#c3122e]">Critical delay analysis</div>
                        <div class="flex items-center gap-2.5 mt-1 flex-wrap">
                            <h2 id="delay-analysis-title-myleads" class="text-base font-black">{{ $delayAnalysis['project_name'] ?? '' }}</h2>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $delayAnalysis['project_code'] ?? '' }}</span>
                            <span class="text-[11px] text-slate-500">{{ $delayAnalysis['critical_issue_count'] ?? 0 }} critical issue(s)</span>
                            <span class="text-[11px] text-slate-500">&bull; {{ count($delayAnalysis['issues'] ?? []) }} delayed task(s)</span>
                        </div>
                    </div>
                    <button type="button" wire:click="closeDelayAnalysis" class="w-9 h-9 rounded-xl bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-[#c3122e] font-black cursor-pointer inline-flex items-center justify-center transition-colors" aria-label="Close delay analysis">&times;</button>
                </div>

                {{-- Body List --}}
                <div x-ref="delayList" class="min-h-0 flex-1 p-3 sm:p-4 overflow-y-auto overscroll-contain space-y-2.5 bg-slate-50/70 scroll-smooth" style="min-height: 0;">
                    @php $delaySummary = $delayAnalysis['summary'] ?? []; @endphp
                    @if(!empty($delaySummary['reasons']) || !empty($delaySummary['root_causes']))
                        <section class="rounded-xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-white px-4 py-3.5 shadow-2xs">
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
                                                    <div class="flex items-center justify-between gap-2 rounded-lg bg-white border border-slate-200 px-2.5 py-1.5 shadow-2xs">
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
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-slate-200 text-[11px] font-bold text-slate-700 shadow-2xs" title="{{ $person['email'] }}">
                                                        <span class="w-4 h-4 rounded-full bg-slate-800 text-white text-[9px] font-black inline-flex items-center justify-center">{{ strtoupper(substr($person['name'], 0, 1)) }}</span>
                                                        {{ $person['name'] }}
                                                        <span class="text-rose-600 font-black">{{ $person['task_count'] }}</span>
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
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="w-6 h-6 rounded-full bg-slate-800 text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($issue['responsible'] ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-black text-slate-900 truncate" title="{{ $issue['responsible'] }}">{{ $issue['responsible'] }}</p>
                                            @if($issue['responsible_email'])<p class="text-[10px] text-slate-500 truncate" title="{{ $issue['responsible_email'] }}">{{ $issue['responsible_email'] }}</p>@endif
                                        </div>
                                    </div>
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

                {{-- Footer --}}
                <div class="px-5 py-2.5 border-t border-slate-200 bg-white flex justify-end gap-2 shrink-0">
                    <button type="button" wire:click="closeDelayAnalysis" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 cursor-pointer transition-colors">Close</button>
                    @if($delayProjectId)<a href="{{ route('projects.show', $delayProjectId) }}?tab=wbs" class="px-5 py-2 rounded-xl text-xs font-black bg-[#c3122e] hover:bg-[#a50f27] text-white no-underline shadow-xs hover:shadow transition-all flex items-center gap-1.5"><span>Open WBS workspace</span><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></a>@endif
                </div>
            </div>
        </div>
        @endteleport
    @endif

    <!-- 5. Review Project Assignment Modal -->
    @if($showReviewModal && $reviewProject)
        @teleport('body')
        @php
            $wbsItems = $reviewProject->wbsItems ?? collect();
            $phases = $wbsItems->where('item_type.value', 'phase');
            if ($phases->isEmpty()) {
                $phases = $wbsItems->where('parent_id', null);
            }
            $tasksCount = $wbsItems->where('item_type.value', 'task')->count();
            $milestonesCount = $wbsItems->where('is_milestone', true)->count();
            
            $sponsors = $reviewProject->members->where('pivot.role', 'sponsor');
            $owners = $reviewProject->members->where('pivot.role', 'owner');
            $committee = $reviewProject->members->where('pivot.role', 'steering_committee');
            $members = $reviewProject->members->where('pivot.role', 'member');
            $totalTeam = $sponsors->count() + $owners->count() + $committee->count() + $members->count() + 1; // +1 PM

            $durationDays = ($reviewProject->start_date && $reviewProject->deadline) 
                ? (int) $reviewProject->start_date->diffInDays($reviewProject->deadline) + 1 
                : null;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
            <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl max-h-[92vh] flex flex-col overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200">
                
                {{-- Header --}}
                <div class="flex-shrink-0 px-5 sm:px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-rose-50/80 via-white to-white flex items-center justify-between">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl text-white flex items-center justify-center shadow-md shadow-rose-950/20 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">Project Manager Assignment Review</h3>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black bg-rose-50 text-[#c3122e] border border-rose-200">{{ $reviewProject->code }}</span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium truncate">{{ $reviewProject->subsidiary->name ?? 'George Steuart Group' }} · PMO Assigned</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeReviewModal" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-5 sm:px-6 py-4 sm:py-5 space-y-4 scrollbar-thin">
                    @if(!$showRejectModal)
                        {{-- 1. Project Title & Scope Card --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Project Title</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ ucfirst($reviewProject->priority?->value ?? 'medium') }} Priority
                                </span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 leading-snug">{{ $reviewProject->name }}</h4>
                            @if($reviewProject->description)
                                <div class="pt-2 border-t border-slate-200/70">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-0.5">Scope &amp; Objectives</span>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $reviewProject->description }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- 2. Timeline & Parameters Grid --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 text-xs">
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">📅 Start Date</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">🏁 Target Deadline</span>
                                <span class="font-mono font-black text-slate-900 block mt-1">
                                    {{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'TBD' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">⏳ Est. Duration</span>
                                <span class="font-bold text-slate-900 block mt-1">
                                    {{ $durationDays ? "{$durationDays} Days" : 'Flexible' }}
                                </span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">💰 Est. Budget</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">
                                    {{ $reviewProject->estimated_budget > 0 ? 'Rs. ' . number_format($reviewProject->estimated_budget, 0) : 'Not Specified' }}
                                </span>
                            </div>
                        </div>

                        {{-- 3. Selected WBS Structure / Blueprint Section --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs">
                                        📋
                                    </div>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">WBS Execution Structure</h5>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">
                                        {{ $wbsItems->count() }} Total Items
                                    </span>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-sky-50 text-sky-700 border border-sky-200">
                                        {{ $phases->count() }} Phases
                                    </span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                            🎯 {{ $milestonesCount }} Milestones
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- WBS Phases & Tasks Preview List --}}
                            @if($wbsItems->count() > 0)
                                <div class="max-h-48 overflow-y-auto space-y-1.5 pr-1 scrollbar-thin rounded-xl bg-white p-2.5 border border-slate-200/70">
                                    @foreach($wbsItems as $item)
                                        @php
                                            $isPhase = ($item->item_type?->value === 'phase' || !$item->parent_id);
                                        @endphp
                                        <div class="flex items-center justify-between gap-2 p-2 rounded-lg text-xs {{ $isPhase ? 'bg-slate-50 font-black text-slate-900 border border-slate-200/60' : 'pl-6 text-slate-700 font-medium' }}">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[10px] font-mono {{ $isPhase ? 'text-[#c3122e] font-black' : 'text-slate-400' }}">
                                                    {{ $isPhase ? '📁 Phase:' : '↳ Task:' }}
                                                </span>
                                                <span class="truncate">{{ $item->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0 text-[10px]">
                                                @if($item->duration)
                                                    <span class="text-slate-400 font-mono">{{ $item->duration }}d</span>
                                                @endif
                                                @if($item->is_milestone)
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-purple-50 text-purple-800 border border-purple-200">Milestone</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-3 bg-white rounded-xl border border-slate-200/60 text-center text-xs font-bold text-slate-400">
                                    Custom Agile Workspace initialized. You can build and structure your WBS upon accepting leadership.
                                </div>
                            @endif
                        </div>

                        {{-- 4. Governance & Team Structure Section --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-[#c3122e] flex items-center justify-center font-bold text-xs">
                                        👥
                                    </div>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">Governance &amp; Team Roster</h5>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black font-mono bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $totalTeam }} Assigned
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                {{-- Project Manager Card (Self) --}}
                                <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border-2 border-[#c3122e]/40 shadow-2xs">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center text-white font-black text-[10px] flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($reviewProject->projectManager->name ?? auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-black text-slate-900 block truncate">{{ $reviewProject->projectManager->name ?? auth()->user()->name }}</span>
                                        <span class="text-[9px] text-[#c3122e] font-black uppercase">⭐ Designated Project Manager (You)</span>
                                    </div>
                                </div>

                                {{-- Sponsors --}}
                                @foreach($sponsors as $sp)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-amber-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-amber-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($sp->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $sp->name }}</span>
                                            <span class="text-[9px] text-amber-800 font-bold">💼 Project Sponsor</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Owners --}}
                                @foreach($owners as $ow)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-emerald-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($ow->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $ow->name }}</span>
                                            <span class="text-[9px] text-emerald-800 font-bold">👑 Project Owner</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Steering Committee --}}
                                @foreach($committee as $cm)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-violet-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-violet-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($cm->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $cm->name }}</span>
                                            <span class="text-[9px] text-violet-800 font-bold">🏛️ Steering Committee</span>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Core Members --}}
                                @foreach($members as $mb)
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white border border-blue-200 shadow-2xs">
                                        <div class="w-7 h-7 rounded-lg bg-blue-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($mb->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $mb->name }}</span>
                                            <span class="text-[9px] text-blue-700 font-bold">🤝 Team Member</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if($reviewProject->isPmRejected())
                            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                                <span class="text-[10px] font-black uppercase tracking-wider block text-rose-700">Previously Reported Reason:</span>
                                <p class="font-medium italic">"{{ $reviewProject->pm_rejection_reason }}"</p>
                                <span class="text-[9px] text-rose-500 font-mono block">Declined on {{ $reviewProject->pm_rejected_at?->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif
                    @else
                        {{-- Decline Reason Form --}}
                        <div class="space-y-3.5">
                            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed space-y-1">
                                <strong class="font-black block text-sm">⚠️ Declining Project Assignment</strong>
                                <p class="text-amber-800 font-medium">Please provide specific feedback, capacity constraints, or scope blockers so PMO Administration can review and re-assign.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-700 uppercase tracking-wider block">
                                    Reason / Issue Report <span class="text-rose-500">*</span>
                                </label>
                                <textarea wire:model="rejectionReasonInput" rows="4"
                                    placeholder="State why you cannot accept this assignment (e.g. resource bottlenecks, conflicting timelines, technical scope mismatch)..."
                                    class="w-full p-3.5 rounded-2xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all"></textarea>
                                @error('rejectionReasonInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Action Footer --}}
                <div class="flex-shrink-0 px-5 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-wrap">
                    @if(!$showRejectModal)
                        <button type="button" wire:click="openRejectForm({{ $reviewProject->id }})"
                            class="px-4 py-2.5 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer flex-shrink-0">
                            ✕ Decline Assignment
                        </button>
                        <button type="button" wire:click="acceptProject({{ $reviewProject->id }})"
                            class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all cursor-pointer flex items-center gap-1.5 flex-shrink-0"
                            style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Accept &amp; Launch Workspace →</span>
                        </button>
                    @else
                        <button type="button" wire:click="$set('showRejectModal', false)"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 transition-colors cursor-pointer">
                            Back
                        </button>
                        <button type="button" wire:click="submitRejection"
                            class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md bg-rose-600 hover:bg-rose-700 transition-all cursor-pointer flex items-center gap-1.5">
                            <span>Submit &amp; Decline</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
