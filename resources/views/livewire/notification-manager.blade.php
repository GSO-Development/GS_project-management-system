<div class="space-y-5 pb-16 max-w-[1400px] mx-auto">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER                                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Notifications
                </h1>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200/70">
                        {{ $unreadCount }} Unread
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                        All Caught Up
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Stay updated on project deliverables, governance approvals, and team activities.
            </p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors cursor-pointer active:scale-98">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mark all as read</span>
                </button>
            @endif

            @if($readCount > 0)
                <button wire:click="deleteAllRead"
                    type="button"
                    wire:confirm="Clear all read notifications? This cannot be undone."
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-medium text-slate-500 hover:text-slate-800 bg-transparent hover:bg-slate-100 transition-colors cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Clear read</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. TABS & FILTER BAR                                       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-1">
        
        {{-- Category Tabs (Clean Modern Pills) --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 lg:pb-0 scrollbar-none">
            {{-- All --}}
            <button wire:click="setCategoryTab('all')"
                type="button"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'all' ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70' }}">
                All <span class="ml-1 opacity-70">({{ $totalCount }})</span>
            </button>

            {{-- Approvals --}}
            <button wire:click="setCategoryTab('approvals')"
                type="button"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'approvals' ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70' }}">
                Approvals <span class="ml-1 opacity-70">({{ $approvalsCount }})</span>
            </button>

            {{-- Updates --}}
            <button wire:click="setCategoryTab('updates')"
                type="button"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'updates' ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70' }}">
                Updates <span class="ml-1 opacity-70">({{ $updatesCount }})</span>
            </button>

            {{-- Tasks & Milestones --}}
            <button wire:click="setCategoryTab('task_completed')"
                type="button"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'task_completed' ? 'bg-slate-900 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70' }}">
                Tasks &amp; Milestones <span class="ml-1 opacity-70">({{ $taskCompletedCount }})</span>
            </button>
        </div>

        {{-- Controls: Status Segment + Search + Project Dropdown --}}
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
            {{-- Status Segment (All / Unread / Read) --}}
            <div class="inline-flex items-center p-0.5 rounded-xl bg-slate-100 border border-slate-200/60 text-xs">
                <button wire:click="setStatusFilter('all')"
                    type="button"
                    class="px-2.5 py-1 rounded-lg font-semibold transition-all cursor-pointer {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                    All
                </button>
                <button wire:click="setStatusFilter('unread')"
                    type="button"
                    class="px-2.5 py-1 rounded-lg font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'unread' ? 'bg-white text-[#c3122e] shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                    Unread
                </button>
                <button wire:click="setStatusFilter('read')"
                    type="button"
                    class="px-2.5 py-1 rounded-lg font-semibold transition-all cursor-pointer {{ $statusFilter === 'read' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800' }}">
                    Read
                </button>
            </div>

            {{-- Search Bar --}}
            <div class="relative flex-1 sm:w-60">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search notifications..."
                    class="w-full pl-8 pr-7 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer font-bold text-xs">
                        ✕
                    </button>
                @endif
            </div>

            {{-- Project Dropdown Filter --}}
            @if($projectsList->count() > 0)
                <div class="relative">
                    <select wire:model.live="projectFilter"
                        class="pl-3 pr-7 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all cursor-pointer appearance-none">
                        <option value="all">All Projects</option>
                        @foreach($projectsList as $projName)
                            <option value="{{ $projName }}">{{ Str::limit($projName, 22) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. FLOATING BATCH ACTIONS BAR (WHEN SELECTED)              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(count($selectedIds) > 0)
        <div class="sticky top-4 z-40 rounded-2xl px-4 py-2.5 bg-white border border-slate-200 shadow-md flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200/70">
                    {{ count($selectedIds) }}
                </span>
                <span class="text-xs font-semibold text-slate-700">
                    notification{{ count($selectedIds) > 1 ? 's' : '' }} selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="batchMarkRead"
                    type="button"
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                    Mark as read
                </button>
                <button wire:click="batchMarkUnread"
                    type="button"
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer">
                    Mark as unread
                </button>
                <button wire:click="batchDelete"
                    type="button"
                    wire:confirm="Delete {{ count($selectedIds) }} selected notifications?"
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-700 transition-colors cursor-pointer">
                    Delete
                </button>
                <button wire:click="clearSelection"
                    type="button"
                    class="px-2.5 py-1.5 rounded-xl text-xs font-medium text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. NOTIFICATION FEED LIST                                  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        
        {{-- List Header Row --}}
        <div class="px-4 sm:px-5 py-3 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between text-xs text-slate-500 font-medium">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="selectAll"
                    class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                <span class="font-semibold text-slate-700">Select All</span>
            </label>

            <span>{{ $notifications->total() }} total</span>
        </div>

        {{-- Stream Rows --}}
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $n)
                @php
                    $isUnread = is_null($n->read_at);
                    $msg      = $n->data['message'] ?? $n->data['title'] ?? 'System Notification';
                    $title    = $n->data['title'] ?? null;
                    $url      = $n->data['url'] ?? null;
                    $role     = $n->data['role'] ?? null;
                    $projectId = $n->data['project_id'] ?? null;
                    $cat      = $n->data['category'] ?? null;

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
                    $lowerMsg = strtolower($msg . ' ' . ($title ?? ''));

                    $isProjectLeaderAssignment = ($actionType === 'project_assignment' || $role === 'lead'
                        || str_contains($lowerMsg, 'designated as project leader')
                        || str_contains($lowerMsg, 'accept leadership to begin'))
                        && !empty($projectId);

                    // Clean & Soft Category Styles
                    if ($isProjectLeaderAssignment) {
                        $catLabel  = 'Leadership';
                        $iconClass = 'bg-amber-50 text-amber-700 border border-amber-200/60';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>';
                    } elseif ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'baseline')) {
                        $catLabel  = 'Approval';
                        $iconClass = 'bg-rose-50 text-[#c3122e] border border-rose-200/60';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
                    } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done') || str_contains($lowerMsg, 'milestone')) {
                        $catLabel  = 'Completed';
                        $iconClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200/60';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    } elseif ($cat === 'blocker' || str_contains($lowerMsg, 'blocker') || str_contains($lowerMsg, 'delay')) {
                        $catLabel  = 'Blocker';
                        $iconClass = 'bg-red-50 text-red-700 border border-red-200/60';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    } elseif ($cat === 'schedule_change' || str_contains($lowerMsg, 'schedule') || str_contains($lowerMsg, 'timeline') || str_contains($lowerMsg, 'deadline')) {
                        $catLabel  = 'Schedule';
                        $iconClass = 'bg-amber-50 text-amber-700 border border-amber-200/60';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                    } else {
                        $catLabel  = 'Update';
                        $iconClass = 'bg-slate-100 text-slate-700 border border-slate-200/70';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                    }

                    $isSelected = in_array($n->id, $selectedIds);
                @endphp

                <div class="px-4 py-3.5 sm:px-5 sm:py-4 transition-colors flex items-start gap-3.5 group hover:bg-slate-50/80 cursor-pointer {{ $isSelected ? 'bg-rose-50/30' : ($isUnread ? 'bg-white' : 'bg-slate-50/20') }}"
                     wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')">

                    {{-- Checkbox --}}
                    <div class="pt-1 flex-shrink-0" wire:click.stop>
                        <input type="checkbox"
                            value="{{ $n->id }}"
                            wire:model.live="selectedIds"
                            class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                    </div>

                    {{-- Unread Dot Indicator --}}
                    <div class="pt-2 flex-shrink-0">
                        @if($isUnread)
                            <span class="block w-2 h-2 rounded-full bg-[#c3122e] shadow-2xs" title="Unread"></span>
                        @else
                            <span class="block w-2 h-2 rounded-full bg-transparent"></span>
                        @endif
                    </div>

                    {{-- Category Icon Container --}}
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconClass }}">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {!! $iconSvg !!}
                        </svg>
                    </div>

                    {{-- Message & Details --}}
                    <div class="min-w-0 flex-1">
                        {{-- Meta line: Category badge, Project pill, Time --}}
                        <div class="flex items-center gap-2 flex-wrap text-xs mb-1">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $iconClass }}">
                                {{ $catLabel }}
                            </span>

                            @if(isset($n->data['project_name']))
                                <span class="text-slate-500 font-semibold text-[11px] flex items-center gap-1">
                                    <span class="text-slate-300">•</span>
                                    <span>{{ Str::limit($n->data['project_name'], 32) }}</span>
                                    @if(isset($n->data['project_code']))
                                        <span class="text-slate-400 font-mono text-[10px]">({{ $n->data['project_code'] }})</span>
                                    @endif
                                </span>
                            @endif

                            <span class="text-slate-400 text-[11px] flex items-center gap-1">
                                <span class="text-slate-300">•</span>
                                <span>{{ $n->created_at->diffForHumans() }}</span>
                            </span>
                        </div>

                        {{-- Title if exists --}}
                        @if($title && $title !== $msg)
                            <h4 class="text-xs sm:text-sm font-bold {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }} leading-snug">
                                {{ $title }}
                            </h4>
                        @endif

                        {{-- Body Message --}}
                        <p class="text-xs sm:text-[13px] {{ $isUnread ? 'text-slate-800 font-medium' : 'text-slate-500' }} leading-relaxed mt-0.5">
                            {{ $msg }}
                        </p>
                    </div>

                    {{-- Actions (Right side) --}}
                    <div class="flex items-center gap-2 flex-shrink-0 self-center" wire:click.stop>
                        @if($isProjectLeaderAssignment && $isUnread)
                            <button wire:click="acceptProjectAssignment('{{ $n->id }}', {{ $projectId }})"
                                type="button"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-2xs transition-all flex items-center gap-1 cursor-pointer">
                                Accept Leadership &rarr;
                            </button>
                        @endif

                        {{-- Mark Read / Unread toggle icon --}}
                        <button wire:click="{{ $isUnread ? "markAsRead('{$n->id}')" : "markAsUnread('{$n->id}')" }}"
                            type="button"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer sm:opacity-0 sm:group-hover:opacity-100"
                            title="{{ $isUnread ? 'Mark as read' : 'Mark as unread' }}">
                            @if($isUnread)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </button>

                        {{-- Delete icon --}}
                        <button wire:click="deleteNotification('{{ $n->id }}')"
                            type="button"
                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer sm:opacity-0 sm:group-hover:opacity-100"
                            title="Delete notification">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

            @empty
                {{-- Clean & Friendly Empty State --}}
                <div class="py-16 text-center px-4">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        {{ $search ? 'No notifications match your search' : 'All caught up!' }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        {{ $search ? "No results found for \"{$search}\". Try different keywords." : "You have no unread notifications at the moment." }}
                    </p>
                    @if($search)
                        <button wire:click="$set('search', '')"
                            type="button"
                            class="mt-3.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-all cursor-pointer">
                            Clear search filter
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination Footer --}}
        @if($notifications->hasPages())
            <div class="px-5 py-3.5 bg-slate-50/60 border-t border-slate-100">
                {{ $notifications->links('vendor.livewire.tailwind') }}
            </div>
        @endif

    </div>

</div>
