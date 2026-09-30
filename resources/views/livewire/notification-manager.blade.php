<div class="space-y-4 pb-16 max-w-[1200px] mx-auto font-sans">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER                                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Notifications
                </h1>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#c3122e]/10 text-[#c3122e]">
                        {{ $unreadCount }} unread
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        ✓ All caught up
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Stay updated on project activities, governance approvals, and team alerts.
            </p>
        </div>

        {{-- Top Right Actions --}}
        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mark all as read</span>
                </button>
            @endif

            @if($readCount > 0)
                <button wire:click="deleteAllRead"
                    type="button"
                    wire:confirm="Clear all read notifications? This cannot be undone."
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200/80 transition-all cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Clear read history</span>
                </button>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 2. TABS & FILTER TOOLBAR                                   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        {{-- Inbox Mode Segmented Filter --}}
        <div class="inline-flex items-center bg-slate-100/80 p-1 rounded-xl gap-1 self-start sm:self-auto">
            <button wire:click="setStatusFilter('unread')"
                type="button"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'unread' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Unread</span>
                <span class="px-1.5 py-0.2 rounded-md text-[10px] font-bold {{ $statusFilter === 'unread' ? 'bg-[#c3122e]/10 text-[#c3122e]' : 'bg-slate-200/70 text-slate-600' }}">
                    {{ $unreadCount }}
                </span>
            </button>

            <button wire:click="setStatusFilter('read')"
                type="button"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'read' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                <span>Read</span>
                <span class="px-1.5 py-0.2 rounded-md text-[10px] font-bold bg-slate-200/70 text-slate-600">
                    {{ $readCount }}
                </span>
            </button>

            <button wire:click="setStatusFilter('all')"
                type="button"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                <span>All</span>
                <span class="text-[11px] text-slate-400">({{ $totalCount }})</span>
            </button>
        </div>

        {{-- Filters & Search --}}
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            {{-- Category Filter --}}
            <div class="relative min-w-[120px]">
                <select wire:model.live="categoryTab"
                    class="w-full pl-3 pr-7 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#c3122e] cursor-pointer appearance-none">
                    <option value="all">All Categories</option>
                    <option value="approvals">Approvals ({{ $approvalsCount }})</option>
                    <option value="updates">Updates ({{ $updatesCount }})</option>
                    <option value="task_completed">Tasks ({{ $taskCompletedCount }})</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Project Filter --}}
            @if($projectsList->count() > 0)
                <div class="relative min-w-[120px]">
                    <select wire:model.live="projectFilter"
                        class="w-full pl-3 pr-7 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-[#c3122e] cursor-pointer appearance-none">
                        <option value="all">All Projects</option>
                        @foreach($projectsList as $projName)
                            <option value="{{ $projName }}">{{ Str::limit($projName, 16) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Search Box --}}
            <div class="relative flex-1 sm:w-40">
                <input type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search..."
                    class="w-full pl-7 pr-6 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#c3122e]">
                <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                @if($search)
                    <button wire:click="$set('search', '')"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-2 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer text-xs">
                        ✕
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. FLOATING BATCH ACTIONS BAR                              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(count($selectedIds) > 0)
        <div class="rounded-xl px-4 py-2 bg-slate-900 text-white shadow-md flex items-center justify-between gap-3 text-xs animate-in fade-in">
            <div class="flex items-center gap-2 font-medium">
                <span class="px-2 py-0.5 rounded bg-white/20 font-bold">
                    {{ count($selectedIds) }}
                </span>
                <span>selected</span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="batchMarkRead" type="button" class="px-2.5 py-1 rounded bg-white/10 hover:bg-white/20 font-medium cursor-pointer transition-colors">
                    Mark read
                </button>
                <button wire:click="batchMarkUnread" type="button" class="px-2.5 py-1 rounded bg-white/10 hover:bg-white/20 font-medium cursor-pointer transition-colors">
                    Mark unread
                </button>
                <button wire:click="batchDelete" type="button" wire:confirm="Delete {{ count($selectedIds) }} selected notifications?" class="px-2.5 py-1 rounded bg-rose-500/80 hover:bg-rose-600 font-medium cursor-pointer transition-colors">
                    Delete
                </button>
                <button wire:click="clearSelection" type="button" class="px-2 py-1 text-slate-400 hover:text-white cursor-pointer ml-1">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. CLEAN NOTIFICATION LIST                                 --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-2">
        {{-- Section Subheader / Select All --}}
        <div class="px-1 flex items-center justify-between text-xs text-slate-500 py-1">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="selectAll"
                    class="w-3.5 h-3.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                <span class="font-medium text-slate-600">Select All</span>
            </label>

            <span class="text-[11px] text-slate-400 font-medium">
                Showing {{ $notifications->count() }} items
            </span>
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

                // Simple Category Colors
                if ($isProjectLeaderAssignment) {
                    $catLabel = 'Leadership';
                    $badgeStyle = 'bg-amber-50 text-amber-700 border-amber-200/60';
                } elseif ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request')) {
                    $catLabel = 'Approval';
                    $badgeStyle = 'bg-indigo-50 text-indigo-700 border-indigo-200/60';
                } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done')) {
                    $catLabel = 'Task';
                    $badgeStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                } elseif ($cat === 'blocker' || str_contains($lowerMsg, 'blocker') || str_contains($lowerMsg, 'delay')) {
                    $catLabel = 'Alert';
                    $badgeStyle = 'bg-rose-50 text-rose-700 border-rose-200/60';
                } else {
                    $catLabel = 'Update';
                    $badgeStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                }

                $isSelected = in_array($n->id, $selectedIds);
            @endphp

            <div class="group relative p-3.5 rounded-xl border bg-white transition-all cursor-pointer {{ $isUnread ? 'border-l-4 border-l-[#c3122e] border-t-slate-200/80 border-r-slate-200/80 border-b-slate-200/80 shadow-2xs' : 'border-slate-200/60 opacity-90 hover:opacity-100' }} {{ $isSelected ? 'bg-slate-50 border-slate-300' : 'hover:border-slate-300' }}"
                 wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')">

                <div class="flex items-start gap-3">
                    {{-- Checkbox --}}
                    <div class="pt-0.5" wire:click.stop>
                        <input type="checkbox"
                            value="{{ $n->id }}"
                            wire:model.live="selectedIds"
                            class="w-3.5 h-3.5 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                    </div>

                    {{-- Card Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded border {{ $badgeStyle }}">
                                    {{ $catLabel }}
                                </span>

                                @if(isset($n->data['project_name']))
                                    <span class="text-xs font-semibold text-slate-700 truncate max-w-[200px]">
                                        {{ $n->data['project_name'] }}
                                    </span>
                                @endif

                                @if($isUnread)
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#c3122e]"></span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-xs text-slate-400 font-medium ml-auto" wire:click.stop>
                                <span>{{ $n->created_at->diffForHumans() }}</span>

                                @if($isUnread)
                                    <button wire:click="markAsRead('{{ $n->id }}')"
                                        type="button"
                                        class="text-xs text-slate-500 hover:text-slate-900 font-semibold px-1.5 py-0.5 rounded hover:bg-slate-100 transition-colors cursor-pointer">
                                        Mark Read
                                    </button>
                                @endif

                                <button wire:click="deleteNotification('{{ $n->id }}')"
                                    type="button"
                                    class="text-slate-300 hover:text-rose-600 p-0.5 transition-colors cursor-pointer"
                                    title="Delete">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Notification Title --}}
                        @if($cleanTitle && $cleanTitle !== $cleanMsg)
                            <h4 class="text-xs font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors leading-snug">
                                {{ $cleanTitle }}
                            </h4>
                        @endif

                        {{-- Message Body --}}
                        @php
                            $formattedMsg = e($cleanMsg);
                            $formattedMsg = preg_replace("/'([^']+)'/", '<span class="font-semibold text-slate-800">$1</span>', $formattedMsg);
                        @endphp
                        <p class="text-xs text-slate-600 leading-relaxed mt-0.5">
                            {!! $formattedMsg !!}
                        </p>

                        {{-- Action Block for Leadership Assignment --}}
                        @if($isProjectLeaderAssignment && $isUnread)
                            <div class="mt-2.5 p-2.5 rounded-lg bg-amber-50/70 border border-amber-200/60 flex items-center justify-between gap-3 flex-wrap" wire:click.stop>
                                <span class="text-xs text-amber-900 font-medium">You were assigned as Project Leader. Accept to begin execution.</span>
                                <button wire:click="acceptProjectAssignment('{{ $n->id }}', {{ $projectId }})"
                                    type="button"
                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] transition-all cursor-pointer shadow-2xs">
                                    Accept Leadership &rarr;
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        @empty
            {{-- Empty State --}}
            <div class="bg-white rounded-xl border border-slate-200/70 p-10 text-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-slate-800" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    {{ $statusFilter === 'unread' ? 'All caught up!' : ($statusFilter === 'read' ? 'No read notifications history' : 'No notifications found') }}
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $statusFilter === 'unread' ? 'No unread notifications in your inbox.' : 'No items match your filter criteria.' }}
                </p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="pt-2">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>

