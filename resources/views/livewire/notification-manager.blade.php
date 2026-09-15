<div class="space-y-5 pb-16 max-w-[1280px] mx-auto font-sans">

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 1. PAGE HEADER                                             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-100">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    Notifications
                </h1>
                @if($unreadCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] shadow-2xs">
                        {{ $unreadCount }} unread
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                        ✓ All caught up
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Stay informed on project updates, governance approvals, and team alerts.
            </p>
        </div>

        {{-- Top Right Actions --}}
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-2xs hover:border-slate-300 transition-all cursor-pointer active:scale-98">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mark all read</span>
                </button>
            @endif

            @if($readCount > 0)
                <button wire:click="deleteAllRead"
                    type="button"
                    wire:confirm="Clear all read notifications? This cannot be undone."
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-500 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 transition-all cursor-pointer shadow-2xs">
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
    <div class="bg-white p-2 sm:p-2.5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        
        {{-- Primary Inbox Mode Tabs (Light pastel styling) --}}
        <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
            {{-- Unread Inbox Mode --}}
            <button wire:click="setStatusFilter('unread')"
                type="button"
                class="px-4 py-1.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer flex items-center gap-2 {{ $statusFilter === 'unread' ? 'bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <span class="w-2 h-2 rounded-full bg-[#c3122e]"></span>
                <span>Unread Inbox</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $statusFilter === 'unread' ? 'bg-rose-100/90 text-[#c3122e]' : 'bg-slate-200 text-slate-600' }} font-mono">
                    {{ $unreadCount }}
                </span>
            </button>

            {{-- Read History Mode --}}
            <button wire:click="setStatusFilter('read')"
                type="button"
                class="px-4 py-1.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer flex items-center gap-2 {{ $statusFilter === 'read' ? 'bg-slate-100 text-slate-900 border border-slate-300 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span>Read History</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $statusFilter === 'read' ? 'bg-slate-200 text-slate-800' : 'bg-slate-200 text-slate-600' }} font-mono">
                    {{ $readCount }}
                </span>
            </button>

            {{-- All Mode --}}
            <button wire:click="setStatusFilter('all')"
                type="button"
                class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'all' ? 'bg-slate-100 text-slate-900 border border-slate-300 shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                <span>All Items</span>
                <span class="font-mono text-[11px] opacity-70">({{ $totalCount }})</span>
            </button>
        </div>

        {{-- Filter Controls --}}
        <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
            {{-- Category Filter Select --}}
            <div class="relative">
                <select wire:model.live="categoryTab"
                    class="pl-3 pr-7 py-1.5 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-medium text-slate-700 focus:outline-none focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all cursor-pointer appearance-none">
                    <option value="all">All Categories</option>
                    <option value="approvals">Approvals ({{ $approvalsCount }})</option>
                    <option value="updates">Updates ({{ $updatesCount }})</option>
                    <option value="task_completed">Tasks ({{ $taskCompletedCount }})</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Project Dropdown Filter --}}
            @if($projectsList->count() > 0)
                <div class="relative">
                    <select wire:model.live="projectFilter"
                        class="pl-3 pr-7 py-1.5 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-medium text-slate-700 focus:outline-none focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all cursor-pointer appearance-none">
                        <option value="all">All Projects</option>
                        @foreach($projectsList as $projName)
                            <option value="{{ $projName }}">{{ Str::limit($projName, 18) }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Search Input --}}
            <div class="relative flex-1 sm:w-44">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search..."
                    class="w-full pl-8 pr-7 py-1.5 rounded-xl border border-slate-200/90 bg-slate-50/50 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-slate-900/10 focus:border-slate-400 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer font-bold text-xs">
                        ✕
                    </button>
                @endif
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 3. FLOATING BATCH ACTIONS BAR (WHEN SELECTED)              --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    @if(count($selectedIds) > 0)
        <div class="rounded-2xl px-4 py-2.5 bg-white border border-slate-200/90 text-slate-800 shadow-lg flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-rose-50 text-[#c3122e] border border-rose-200/70">
                    {{ count($selectedIds) }}
                </span>
                <span class="text-xs font-bold text-slate-700">
                    notification{{ count($selectedIds) > 1 ? 's' : '' }} selected
                </span>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="batchMarkRead"
                    type="button"
                    class="px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer border border-slate-200">
                    Mark as read
                </button>
                <button wire:click="batchMarkUnread"
                    type="button"
                    class="px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer border border-slate-200">
                    Mark as unread
                </button>
                <button wire:click="batchDelete"
                    type="button"
                    wire:confirm="Delete {{ count($selectedIds) }} selected notifications?"
                    class="px-3 py-1 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 transition-colors cursor-pointer border border-rose-200">
                    Delete
                </button>
                <button wire:click="clearSelection"
                    type="button"
                    class="px-2 py-1 rounded-xl text-xs font-medium text-slate-400 hover:text-slate-700 transition-colors cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- 4. ELEGANT CARD STREAM FEED                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="space-y-3">
        
        {{-- Section Subheader --}}
        <div class="px-1 flex items-center justify-between text-xs text-slate-500 font-medium">
            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model.live="selectAll"
                    class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                <span class="font-semibold text-slate-700 text-xs">Select All</span>
            </label>

            <span class="text-[11px] text-slate-500 font-medium">
                @if($statusFilter === 'unread')
                    Showing <strong class="text-slate-800 font-bold">{{ $notifications->count() }}</strong> New Unread Items
                @elseif($statusFilter === 'read')
                    Showing <strong class="text-slate-800 font-bold">{{ $notifications->count() }}</strong> Read History Items
                @else
                    Showing <strong class="text-slate-800 font-bold">{{ $notifications->count() }}</strong> of {{ $notifications->total() }} Notifications
                @endif
            </span>
        </div>

        {{-- Stream Cards --}}
        @forelse($notifications as $n)
            @php
                $isUnread = is_null($n->read_at);
                $rawMsg   = $n->data['message'] ?? $n->data['title'] ?? 'System Notification';
                $rawTitle = $n->data['title'] ?? null;
                
                // Clean emojis for clean presentation
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

                // Category SVG & Labels (Full pastel colors for BOTH read and unread items!)
                if ($isProjectLeaderAssignment) {
                    $catLabel = 'Leadership';
                    $iconStyle = 'bg-amber-50 text-amber-700 border-amber-200/60';
                    $iconSvg  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>';
                } elseif ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'baseline')) {
                    $catLabel = 'Approval';
                    $iconStyle = 'bg-indigo-50 text-indigo-700 border-indigo-200/60';
                    $iconSvg  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
                } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done') || str_contains($lowerMsg, 'milestone')) {
                    $catLabel = 'Task';
                    $iconStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200/60';
                    $iconSvg  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                } elseif ($cat === 'blocker' || str_contains($lowerMsg, 'blocker') || str_contains($lowerMsg, 'delay') || str_contains($lowerMsg, 'overdue')) {
                    $catLabel = 'Alert';
                    $iconStyle = 'bg-amber-50 text-amber-800 border-amber-200/70';
                    $iconSvg  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                } else {
                    $catLabel = 'Update';
                    $iconStyle = 'bg-sky-50 text-sky-700 border-sky-200/60';
                    $iconSvg  = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                }

                $isSelected = in_array($n->id, $selectedIds);
            @endphp

            <div class="p-4 sm:p-4.5 rounded-2xl transition-all duration-200 group cursor-pointer border bg-white border-slate-200/80 shadow-2xs hover:shadow-xs hover:border-slate-300 {{ $isSelected ? 'ring-2 ring-slate-300 bg-slate-50/50' : '' }}"
                 wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')">

                {{-- Header Row inside Item Card: Checkbox + Icon + Badges + Project + Time + Action Button --}}
                <div class="flex items-center justify-between gap-3 mb-2 flex-wrap">
                    
                    {{-- Left Group --}}
                    <div class="flex items-center gap-2.5 min-w-0 flex-wrap">
                        {{-- Checkbox --}}
                        <div wire:click.stop>
                            <input type="checkbox"
                                value="{{ $n->id }}"
                                wire:model.live="selectedIds"
                                class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 cursor-pointer">
                        </div>

                        {{-- Status Indicator Dot --}}
                        <div class="w-2.5 h-2.5 flex items-center justify-center shrink-0">
                            @if($isUnread)
                                <span class="w-2 h-2 rounded-full bg-[#c3122e] shrink-0" title="Unread"></span>
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 shrink-0" title="Read"></span>
                            @endif
                        </div>

                        {{-- Icon Box (28x28) --}}
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 border text-xs font-bold {{ $iconStyle }}">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {!! $iconSvg !!}
                            </svg>
                        </div>

                        {{-- Status Pill --}}
                        @if($isUnread)
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold text-[#c3122e] bg-[#fdf4f4] border border-[#faeaea]">
                                Unread
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold text-slate-600 bg-slate-100 border border-slate-200/70 flex items-center gap-1">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Read</span>
                            </span>
                        @endif

                        {{-- Category Label Chip --}}
                        <span class="text-[10px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-md border bg-slate-100 text-slate-700 border-slate-200/80">
                            {{ $catLabel }}
                        </span>

                        {{-- Project Tag --}}
                        @if(isset($n->data['project_name']))
                            <span class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-700 font-bold">{{ Str::limit($n->data['project_name'], 30) }}</span>
                                @if(isset($n->data['project_code']))
                                    <span class="text-slate-400 font-mono text-[10.5px]">({{ $n->data['project_code'] }})</span>
                                @endif
                            </span>
                        @endif
                    </div>

                    {{-- Right Group: Relative Time & Action Button --}}
                    <div class="flex items-center gap-3 shrink-0 ml-auto" wire:click.stop>
                        <span class="text-xs font-medium text-slate-500 flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $n->created_at->diffForHumans() }}</span>
                        </span>

                        @if($isUnread)
                            <button wire:click="markAsRead('{{ $n->id }}')"
                                type="button"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all cursor-pointer flex items-center gap-1 shadow-2xs">
                                <span>Mark Read</span>
                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        @endif

                        <button wire:click="deleteNotification('{{ $n->id }}')"
                            type="button"
                            class="p-1 rounded-lg text-slate-300 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                            title="Delete">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                </div>

                {{-- Bottom Row inside Item Card: Clean Message & Title --}}
                <div class="pl-9 pr-2">
                    @if($cleanTitle && $cleanTitle !== $cleanMsg)
                        <h4 class="text-sm sm:text-[15px] font-bold text-slate-900 tracking-tight leading-snug mb-1 group-hover:text-[#c3122e] transition-colors flex items-center gap-2" style="font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">
                            <span>{{ $cleanTitle }}</span>
                        </h4>
                    @endif

                    @php
                        $formattedMsg = e($cleanMsg);
                        $formattedMsg = preg_replace("/'([^']+)'/", '<span class="font-semibold text-slate-900 bg-slate-100/90 px-1.5 py-0.5 rounded-md border border-slate-200/70 font-sans text-xs inline-block">$1</span>', $formattedMsg);
                        $formattedMsg = preg_replace("/\(Overdue by ([^\)]+)\)/i", '<span class="font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200/80 text-[11px] inline-flex items-center gap-1.5 ml-1"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>Overdue by $1</span>', $formattedMsg);
                        $formattedMsg = preg_replace("/Current progress is ([0-9]+%)/i", 'Current progress is <span class="font-bold text-slate-800">$1</span>', $formattedMsg);
                    @endphp

                    <p class="text-xs sm:text-[13px] text-slate-600 font-normal leading-relaxed" style="font-family: 'Inter', system-ui, sans-serif;">
                        {!! $formattedMsg !!}
                    </p>

                    @if($isProjectLeaderAssignment && $isUnread)
                        <div class="mt-3 pt-2 border-t border-slate-100 flex items-center justify-between" wire:click.stop>
                            <span class="text-xs text-amber-800 font-medium">Designated as PM. Accept leadership to initialize project execution.</span>
                            <button wire:click="acceptProjectAssignment('{{ $n->id }}', {{ $projectId }})"
                                type="button"
                                class="px-4 py-1.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-xs transition-all cursor-pointer">
                                Accept Leadership &rarr;
                            </button>
                        </div>
                    @endif
                </div>

            </div>

        @empty
            {{-- Clean Empty State --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                    {{ $statusFilter === 'unread' ? 'All caught up! 🎉' : ($statusFilter === 'read' ? 'No read notifications history' : ($search ? 'No notifications found' : 'No notifications')) }}
                </h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                    {{ $statusFilter === 'unread' ? 'You have read all new notifications in your unread inbox.' : ($statusFilter === 'read' ? 'You have cleared or have no read history.' : ($search ? "No results matching \"{$search}\"." : "You have no notifications right now.")) }}
                </p>
                @if($search || $statusFilter !== 'unread')
                    <div class="mt-3 flex items-center justify-center gap-2">
                        @if($search)
                            <button wire:click="$set('search', '')"
                                type="button"
                                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-all cursor-pointer">
                                Clear search
                            </button>
                        @endif
                        @if($statusFilter !== 'unread')
                            <button wire:click="setStatusFilter('unread'); setCategoryTab('all')"
                                type="button"
                                class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition-all cursor-pointer border border-slate-200">
                                Go to Unread Inbox
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @endforelse

        {{-- Pagination Footer --}}
        @if($notifications->hasPages())
            <div class="px-5 py-3.5 bg-white rounded-2xl border border-slate-200/80">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

</div>
