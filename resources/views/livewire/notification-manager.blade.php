<div class="max-w-7xl mx-auto space-y-4 pb-20 font-sans">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER                                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Notifications Hub
                </h1>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black text-white bg-[#c3122e] shadow-2xs flex items-center justify-center shrink-0">
                        {{ $unreadCount }} Unread
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold text-[#c3122e] bg-rose-50 border border-rose-200/70 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] animate-ping"></span>
                        <span>Requires Attention</span>
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/70 flex items-center gap-1.5 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>All caught up!</span>
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Real-time governance requests, project updates, and team alerts across all portfolios.
            </p>
        </div>

        {{-- Top Right Actions & Filter Button --}}
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            {{-- Filter Toggle Button --}}
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

            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-all cursor-pointer active:scale-95">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mark all read</span>
                </button>
            @endif

            @if($readCount > 0)
                <button wire:click="deleteAllRead"
                    type="button"
                    wire:confirm="Clear all read notifications? This action cannot be undone."
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-medium text-slate-500 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200/90 transition-all cursor-pointer active:scale-95 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="hidden sm:inline">Clear history</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. COLLAPSIBLE FILTER TOOLBAR                              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if($showFilters)
        <div wire:key="notifications-filter-toolbar" class="animate-in fade-in slide-in-from-top-2 duration-150">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5 bg-slate-50/80 p-3 rounded-2xl border border-slate-200/80 shadow-2xs">
                {{-- Search Box --}}
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input wire:model.live.debounce.250ms="search" type="text"
                           placeholder="Search notification messages, projects, or alerts..."
                           class="w-full pl-9 pr-8 py-2 bg-white border border-slate-200 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none transition-all shadow-2xs">
                    @if($search)
                        <button type="button" wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer text-xs font-bold">✕</button>
                    @endif
                </div>

                {{-- Status Filter Dropdown --}}
                <div class="relative flex-1 sm:flex-initial min-w-[130px]">
                    <select wire:model.live="statusFilter"
                            class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                        <option value="unread">Unread Only ({{ $unreadCount }})</option>
                        <option value="read">Read Only ({{ $readCount }})</option>
                        <option value="all">All Status ({{ $totalCount }})</option>
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>

                {{-- Category Filter Dropdown --}}
                <div class="relative flex-1 sm:flex-initial min-w-[150px]">
                    <select wire:model.live="categoryTab"
                            class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                        <option value="all">All Categories</option>
                        <option value="approvals">📋 Approvals ({{ $approvalsCount }})</option>
                        <option value="task_completed">✅ Tasks ({{ $taskCompletedCount }})</option>
                        <option value="updates">📢 Updates ({{ $updatesCount }})</option>
                    </select>
                    <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>

                {{-- Project Filter Dropdown --}}
                @if($projectsList->count() > 0)
                    <div class="relative flex-1 sm:flex-initial min-w-[150px]">
                        <select wire:model.live="projectFilter"
                                class="w-full appearance-none bg-white border border-slate-200 hover:border-slate-300 rounded-xl pl-3.5 pr-8 py-2 text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/10 focus:border-[#c3122e] cursor-pointer transition-colors shadow-2xs truncate">
                            <option value="all">All Projects</option>
                            @foreach($projectsList as $projName)
                                <option value="{{ $projName }}">{{ Str::limit($projName, 18) }}</option>
                            @endforeach
                        </select>
                        <svg class="w-3.5 h-3.5 text-slate-400 pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                @endif

                {{-- Clear Filters Button --}}
                @if($hasActiveFilters)
                    <button wire:click="clearFilters" type="button"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100/80 border border-rose-200/80 transition-all flex items-center justify-center gap-1.5 cursor-pointer shrink-0 active:scale-95 shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Clear</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. KPI METRIC SUMMARY CARDS (Interactive Category Hub)    --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3.5">
        {{-- 1. Unread Inbox --}}
        <button type="button" wire:click="setStatusFilter('unread')"
                class="group text-left bg-white border rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $statusFilter === 'unread' ? 'ring-2 ring-[#c3122e] border-[#c3122e] bg-rose-50/20 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Unread Inbox</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-rose-50 text-[#c3122e] flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $unreadCount }}</div>
                <span class="text-[10px] font-bold text-slate-400">of {{ $totalCount }}</span>
            </div>
        </button>

        {{-- 2. Governance & Approvals --}}
        <button type="button" wire:click="setCategoryTab('approvals')"
                class="group text-left bg-white border rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $categoryTab === 'approvals' ? 'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50/20 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Approvals</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $approvalsCount }}</div>
                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.2 rounded">Requests</span>
            </div>
        </button>

        {{-- 3. Tasks & Deliverables --}}
        <button type="button" wire:click="setCategoryTab('task_completed')"
                class="group text-left bg-white border rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $categoryTab === 'task_completed' ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Tasks &amp; Milestones</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $taskCompletedCount }}</div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.2 rounded">Completed</span>
            </div>
        </button>

        {{-- 4. Daily Updates --}}
        <button type="button" wire:click="setCategoryTab('updates')"
                class="group text-left bg-white border rounded-2xl p-3.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $categoryTab === 'updates' ? 'ring-2 ring-sky-500 border-sky-500 bg-sky-50/20 shadow-xs' : 'border-slate-200/90 shadow-2xs hover:border-slate-300' }}">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">Updates &amp; Logs</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $updatesCount }}</div>
                <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-1.5 py-0.2 rounded">Briefings</span>
            </div>
        </button>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. FLOATING BATCH ACTIONS TOOLBAR                          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(count($selectedIds) > 0)
        <div class="rounded-2xl px-4 py-2.5 bg-slate-900 text-white shadow-xl flex items-center justify-between gap-3 text-xs animate-in fade-in slide-in-from-bottom-2">
            <div class="flex items-center gap-2.5 font-bold">
                <span class="px-2 py-0.5 rounded-lg bg-white/20 font-mono text-[11px]">
                    {{ count($selectedIds) }}
                </span>
                <span>items selected</span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button wire:click="batchMarkRead" type="button" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 font-bold cursor-pointer transition-colors active:scale-95">
                    Mark Read
                </button>
                <button wire:click="batchMarkUnread" type="button" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 font-bold cursor-pointer transition-colors active:scale-95">
                    Mark Unread
                </button>
                <button wire:click="batchDelete" type="button" wire:confirm="Delete {{ count($selectedIds) }} selected notifications?" class="px-3 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 font-bold cursor-pointer transition-colors active:scale-95 shadow-2xs">
                    Delete
                </button>
                <button wire:click="clearSelection" type="button" class="px-2.5 py-1.5 text-slate-400 hover:text-white font-semibold cursor-pointer ml-1">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 5. NOTIFICATION LIST STREAM                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-2.5">
        {{-- Section Subheader / Select All Bar --}}
        <div class="px-2 flex items-center justify-between text-xs text-slate-500 py-1">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="selectAll"
                    class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                <span class="font-bold text-slate-700 text-xs">Select All Notifications</span>
            </label>

            <div class="flex items-center gap-3">
                @if($categoryTab !== 'all')
                    <button wire:click="setCategoryTab('all')" class="text-[11px] font-bold text-[#c3122e] hover:underline cursor-pointer">
                        Reset Category Filter
                    </button>
                @endif
                <span class="text-[11px] text-slate-400 font-medium font-mono">
                    Showing {{ $notifications->count() }} of {{ $notifications->total() }} items
                </span>
            </div>
        </div>

        {{-- Stream Items --}}
        @forelse($notifications as $n)
            @php
                $isUnread = is_null($n->read_at);
                $rawMsg   = $n->data['message'] ?? $n->data['title'] ?? 'System Notification';
                $rawTitle = $n->data['title'] ?? null;
                
                $cleanTitle = $rawTitle ? trim(str_replace(['🎉', '🚨', '⚠️'], '', $rawTitle)) : null;
                $cleanMsg   = trim(str_replace(['🎉', '🚨', '⚠️'], '', $rawMsg));

                $url       = $n->data['url'] ?? null;
                $role      = $n->data['role'] ?? null;
                $projectId = $n->data['project_id'] ?? null;
                $cat       = $n->data['category'] ?? null;

                if (!$url) {
                    if ($projectId) {
                        $url = route('projects.show', $projectId);
                    } elseif ($cat === 'approvals') {
                        $url = route('approvals.index');
                    } elseif ($cat === 'blocker' || $cat === 'task_delay') {
                        $url = route('risks.index');
                    } elseif ($cat === 'task_completed' || $cat === 'task_assigned') {
                        $url = route('my-tasks.index');
                    } elseif ($cat === 'updates') {
                        $url = route('daily-updates.index');
                    } else {
                        $url = route('notifications.index');
                    }
                }
                $actionType = $n->data['action'] ?? null;
                $lowerMsg = strtolower($rawMsg . ' ' . ($rawTitle ?? ''));

                $isProjectLeaderAssignment = ($actionType === 'project_assignment' || $role === 'lead'
                    || str_contains($lowerMsg, 'designated as project leader')
                    || str_contains($lowerMsg, 'accept leadership to begin'))
                    && !empty($projectId);

                // Category Badges & Iconography
                if ($isProjectLeaderAssignment) {
                    $catLabel = '⭐ Leadership';
                    $badgeStyle = 'bg-amber-50 text-amber-800 border-amber-200';
                    $iconBg = 'bg-amber-100 text-amber-700';
                } elseif ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request')) {
                    $catLabel = '📋 Approval';
                    $badgeStyle = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                    $iconBg = 'bg-indigo-100 text-indigo-700';
                } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done')) {
                    $catLabel = '✅ Task Done';
                    $badgeStyle = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                    $iconBg = 'bg-emerald-100 text-emerald-700';
                } elseif ($cat === 'blocker' || str_contains($lowerMsg, 'blocker') || str_contains($lowerMsg, 'delay') || str_contains($lowerMsg, 'overdue')) {
                    $catLabel = '🚨 Blocker';
                    $badgeStyle = 'bg-rose-50 text-rose-800 border-rose-200';
                    $iconBg = 'bg-rose-100 text-rose-700';
                } else {
                    $catLabel = '📢 Update';
                    $badgeStyle = 'bg-sky-50 text-sky-800 border-sky-200';
                    $iconBg = 'bg-sky-100 text-sky-700';
                }

                $isSelected = in_array($n->id, $selectedIds);
            @endphp

            <div class="group relative p-4 rounded-2xl border transition-all cursor-pointer {{ $isUnread ? 'bg-white border-l-4 border-l-[#c3122e] border-slate-200 shadow-sm' : 'bg-slate-50/70 border-slate-200/80 hover:bg-white hover:border-slate-300' }} {{ $isSelected ? '!bg-rose-50/30 !border-[#c3122e]/40' : '' }}"
                 wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')">

                <div class="flex items-start gap-3.5">
                    {{-- Checkbox --}}
                    <div class="pt-0.5 shrink-0" wire:click.stop>
                        <input type="checkbox"
                            value="{{ $n->id }}"
                            wire:model.live="selectedIds"
                            class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                    </div>

                    {{-- Category Icon Avatar --}}
                    <div class="w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center font-bold text-sm shrink-0 shadow-2xs mt-0.5">
                        @if($isProjectLeaderAssignment)
                            ⭐
                        @elseif($cat === 'approvals' || str_contains($lowerMsg, 'approval'))
                            📋
                        @elseif($cat === 'task_completed' || str_contains($lowerMsg, 'completed'))
                            ✓
                        @elseif($cat === 'blocker' || str_contains($lowerMsg, 'blocker'))
                            ⚠️
                        @else
                            📢
                        @endif
                    </div>

                    {{-- Card Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-lg border {{ $badgeStyle }}">
                                    {{ $catLabel }}
                                </span>

                                @if(isset($n->data['project_name']))
                                    <span class="text-xs font-bold text-slate-800 bg-white px-2 py-0.5 rounded-md border border-slate-200/80 truncate max-w-[240px]">
                                        {{ $n->data['project_name'] }}
                                    </span>
                                @endif

                                @if($isUnread)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                                        <span>New</span>
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium ml-auto" wire:click.stop>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $n->created_at->diffForHumans() }}</span>
                                </span>

                                @if($isUnread)
                                    <button wire:click="markAsRead('{{ $n->id }}')"
                                        type="button"
                                        class="text-xs text-slate-600 hover:text-[#c3122e] font-bold px-2 py-0.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer border border-transparent hover:border-slate-200">
                                        Mark Read
                                    </button>
                                @else
                                    <button wire:click="markAsUnread('{{ $n->id }}')"
                                        type="button"
                                        class="text-xs text-slate-400 hover:text-slate-700 font-medium px-2 py-0.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                                        Mark Unread
                                    </button>
                                @endif

                                <button wire:click="deleteNotification('{{ $n->id }}')"
                                    type="button"
                                    class="text-slate-300 hover:text-rose-600 p-1 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                    title="Delete notification">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Notification Title --}}
                        @if($cleanTitle && $cleanTitle !== $cleanMsg)
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors leading-snug">
                                {{ $cleanTitle }}
                            </h4>
                        @endif

                        {{-- Message Body --}}
                        @php
                            $formattedMsg = e($cleanMsg);
                            $formattedMsg = preg_replace("/'([^']+)'/", '<span class="font-bold text-slate-900 bg-slate-100/80 px-1 rounded">$1</span>', $formattedMsg);
                        @endphp
                        <p class="text-xs text-slate-600 leading-relaxed mt-1">
                            {!! $formattedMsg !!}
                        </p>

                        {{-- Special Action Card for Project Leadership Assignment --}}
                        @if($isProjectLeaderAssignment && $isUnread)
                            <div class="mt-3 p-3 rounded-xl bg-gradient-to-r from-amber-50 to-orange-50/60 border border-amber-200/80 flex items-center justify-between gap-3 flex-wrap shadow-2xs" wire:click.stop>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">⭐</span>
                                    <span class="text-xs text-amber-950 font-bold">
                                        You have been designated as Project Leader. Review details to accept leadership.
                                    </span>
                                </div>
                                <button wire:click="acceptProjectAssignment('{{ $n->id }}', {{ $projectId }})"
                                    type="button"
                                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a90f27] transition-all cursor-pointer shadow-md shadow-[#c3122e]/20 active:scale-95">
                                    Accept Leadership &rarr;
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @empty
            {{-- Empty State Card --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center shadow-xs space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-[#c3122e] flex items-center justify-center mx-auto mb-2 border border-rose-100 shadow-2xs">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    {{ $statusFilter === 'unread' ? 'All caught up!' : ($statusFilter === 'read' ? 'No read notification history' : 'No notifications found') }}
                </h3>
                <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">
                    @if($hasActiveFilters)
                        No notifications match your search or filter criteria. <button wire:click="clearFilters" class="text-[#c3122e] font-bold underline cursor-pointer">Clear filters</button>
                    @else
                        {{ $statusFilter === 'unread' ? 'You have zero pending unread alerts. You are completely up to date!' : 'Your notification stream is currently clear.' }}
                    @endif
                </p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="pt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>
