<div class="space-y-6 pb-16">

    {{-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER  (matches other page style)
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-xl p-5 sm:p-6 lg:p-7 text-white"
         style="background: linear-gradient(135deg, #18060c 0%, #2e0915 50%, #18060c 100%);">

        {{-- Top Glowing Gold/Crimson Accent Line --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50 z-20"></div>

        {{-- Right Background Banner Image (same as other pages) --}}
        <div class="absolute right-0 top-0 bottom-0 w-1/2 pointer-events-none opacity-20 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-luxury-2x.jpg') }}"
                 alt=""
                 class="h-full w-full object-cover object-center"
                 style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 60%); mask-image: linear-gradient(to right, transparent 0%, black 60%);">
        </div>

        {{-- Gold/Crimson wave swoosh vector --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-25 hidden md:block">
            <svg viewBox="0 0 1200 300" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 400 0 C 520 120 580 200 750 300" stroke="#f59e0b" stroke-width="2" opacity="0.6"/>
                <path d="M 420 0 C 540 120 600 200 770 300" stroke="#c3122e" stroke-width="1.5" opacity="0.35"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">

            {{-- LEFT: Icon + Title + Meta --}}
            <div class="flex items-center gap-4 min-w-0">

                {{-- Icon badge --}}
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-xl flex-shrink-0 border border-white/25 ring-4 ring-rose-500/20 flex items-center justify-center p-2.5"
                     style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 60%, #4a0410 100%);">
                    <svg class="w-7 h-7 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>

                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap mb-0.5">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md"
                            style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Activity &amp; Notifications
                        </h1>
                        @if($unreadCount > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black inline-flex items-center gap-1.5 backdrop-blur-md"
                                  style="background: rgba(195,18,46,0.35); color:#fecaca; border:1px solid rgba(195,18,46,0.5);">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                                {{ $unreadCount }} Unread
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black inline-flex items-center gap-1.5 backdrop-blur-md"
                                  style="background: rgba(16,185,129,0.2); color:#a7f3d0; border:1px solid rgba(16,185,129,0.35);">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                All Caught Up
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-300 flex-wrap mt-1">
                        <span class="text-rose-200 font-bold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ now()->format('D, M d, Y') }}
                        </span>
                        <span class="text-slate-500">|</span>
                        <span class="text-slate-400 font-medium hidden sm:inline">Real-time alerts, approvals &amp; team updates</span>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Stats + Actions --}}
            <div class="flex items-center gap-3 flex-shrink-0 flex-wrap sm:flex-nowrap">

                {{-- Mini stat chips --}}
                <div class="flex items-center gap-2">
                    <div class="px-3 py-1.5 rounded-xl border border-white/15 backdrop-blur-md flex items-center gap-2"
                         style="background:rgba(0,0,0,0.4);">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Total</span>
                        <span class="text-base font-black text-white font-mono leading-none">{{ $totalCount }}</span>
                    </div>
                    <div class="px-3 py-1.5 rounded-xl border border-rose-500/30 backdrop-blur-md flex items-center gap-2"
                         style="background:rgba(195,18,46,0.2);">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 {{ $unreadCount > 0 ? 'animate-pulse' : '' }}"></span>
                        <span class="text-base font-black text-rose-300 font-mono leading-none">{{ $unreadCount }}</span>
                    </div>
                    <div class="px-3 py-1.5 rounded-xl border border-amber-500/25 backdrop-blur-md flex items-center gap-2"
                         style="background:rgba(245,158,11,0.12);">
                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-400">Approvals</span>
                        <span class="text-base font-black text-amber-300 font-mono leading-none">{{ $approvalsCount }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer active:scale-95"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mark All Read
                    </button>
                @endif

                @if($readCount > 0)
                    <button wire:click="deleteAllRead"
                        wire:confirm="Clear all read notifications? This cannot be undone."
                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold cursor-pointer transition-all hover:bg-white/10"
                        style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.15); color:#cbd5e1;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Clear Read
                    </button>
                @endif
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         2. FILTER & CONTROL TOOLBAR
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs space-y-3">

        {{-- Category Tabs + Status Toggle --}}
        <div class="flex items-center justify-between gap-3 flex-wrap border-b border-slate-100 pb-3">

            {{-- Category tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none flex-wrap">
                <button wire:click="setCategoryTab('all')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200/70' }}">
                    All ({{ $totalCount }})
                </button>

                <button wire:click="setCategoryTab('updates')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5 {{ $categoryTab === 'updates' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Updates</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ $categoryTab === 'updates' ? 'bg-white/25 text-white' : 'bg-blue-100 text-blue-700' }}">{{ $updatesCount }}</span>
                </button>

                <button wire:click="setCategoryTab('approvals')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5 {{ $categoryTab === 'approvals' ? 'bg-[#c3122e] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-[#c3122e]' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Approvals &amp; Governance</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ $categoryTab === 'approvals' ? 'bg-white/25 text-white' : 'bg-rose-100 text-[#c3122e]' }}">{{ $approvalsCount }}</span>
                </button>

                <button wire:click="setCategoryTab('task_completed')"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-1.5 {{ $categoryTab === 'task_completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Tasks &amp; Milestones</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ $categoryTab === 'task_completed' ? 'bg-white/25 text-white' : 'bg-emerald-100 text-emerald-700' }}">{{ $taskCompletedCount }}</span>
                </button>
            </div>

            {{-- Status Segment --}}
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl flex-shrink-0">
                <button wire:click="setStatusFilter('all')"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    All
                </button>
                <button wire:click="setStatusFilter('unread')"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'unread' ? 'bg-white text-rose-600 shadow font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Unread ({{ $unreadCount }})
                </button>
                <button wire:click="setStatusFilter('read')"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $statusFilter === 'read' ? 'bg-white text-slate-900 shadow font-black' : 'text-slate-600 hover:text-slate-900' }}">
                    Read ({{ $readCount }})
                </button>
            </div>
        </div>

        {{-- Search + Project Filter + Select All --}}
        <div class="flex items-center gap-3 flex-wrap sm:flex-nowrap">

            {{-- Search --}}
            <div class="relative flex-1" style="min-width:200px;">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Search by keywords, tasks, or projects..."
                    class="w-full pl-9 pr-8 py-2 rounded-xl border border-slate-200 bg-slate-50/80 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                @if($search)
                    <button wire:click="$set('search', '')"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer font-bold text-base">
                        &times;
                    </button>
                @endif
            </div>

            {{-- Project Filter --}}
            @if($projectsList->count() > 0)
                <div class="relative flex-shrink-0" style="min-width:170px;">
                    <select wire:model.live="projectFilter"
                        class="w-full pl-3 pr-8 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all cursor-pointer appearance-none">
                        <option value="all">📁 All Projects</option>
                        @foreach($projectsList as $projName)
                            <option value="{{ $projName }}">{{ $projName }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Select All --}}
            <label class="flex items-center gap-2 flex-shrink-0 cursor-pointer select-none px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                <input type="checkbox" wire:model.live="selectAll"
                    class="w-4 h-4 rounded cursor-pointer"
                    style="accent-color:#c3122e;">
                <span class="text-xs font-bold text-slate-600">Select All</span>
            </label>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════
         3. FLOATING BATCH ACTIONS BAR
         ═══════════════════════════════════════════════════════════════ --}}
    @if(count($selectedIds) > 0)
        <div class="sticky top-4 z-40 rounded-2xl p-3 px-5 shadow-xl flex items-center justify-between gap-4 flex-wrap"
             style="background: #ffffff; border: 1.5px solid #cbd5e1; box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.15), 0 4px 6px -2px rgba(15, 23, 42, 0.05);">
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-full text-white text-xs font-black flex items-center justify-center shadow-md flex-shrink-0"
                      style="background: #c3122e;">
                    {{ count($selectedIds) }}
                </span>
                <span class="text-xs font-black tracking-wide" style="color: #0f172a;">
                    notification{{ count($selectedIds) > 1 ? 's' : '' }} selected
                </span>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button wire:click="batchMarkRead"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs hover:scale-102"
                    style="background: #ecfdf5; color: #047857; border: 1.5px solid #a7f3d0;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark Read
                </button>
                <button wire:click="batchMarkUnread"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs hover:scale-102"
                    style="background: #fffbeb; color: #b45309; border: 1.5px solid #fde68a;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Mark Unread
                </button>
                <button wire:click="batchDelete"
                    wire:confirm="Delete {{ count($selectedIds) }} selected notifications?"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs hover:scale-102"
                    style="background: #fff1f2; color: #be123c; border: 1.5px solid #fecdd3;">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete
                </button>
                <button wire:click="clearSelection"
                    class="ml-2 px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer hover:bg-slate-100"
                    style="color: #64748b; background: #f1f5f9; border: 1px solid #e2e8f0;">
                    Cancel
                </button>
            </div>
        </div>
    @endif


    {{-- ═══════════════════════════════════════════════════════════════
         4. NOTIFICATION STREAM FEED
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">

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

                    // Build a smart fallback URL when none is stored
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

                    // Category mapping
                    if ($isProjectLeaderAssignment) {
                        $catName   = '👑 Project Leadership';
                        $pillStyle = 'bg-amber-50 text-amber-900 border-amber-200';
                        $iconBg    = 'background: linear-gradient(135deg, #f59e0b, #d97706);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>';
                    } elseif ($role === 'steering_committee' || str_contains($lowerMsg, 'steering committee')) {
                        $catName   = '🏛️ Steering Committee';
                        $pillStyle = 'bg-purple-50 text-purple-800 border-purple-200';
                        $iconBg    = 'background: linear-gradient(135deg, #7c3aed, #4f46e5);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>';
                    } elseif ($role === 'sponsor' || $role === 'owner') {
                        $catName   = '💼 Governance';
                        $pillStyle = 'bg-indigo-50 text-indigo-800 border-indigo-200';
                        $iconBg    = 'background: linear-gradient(135deg, #4f46e5, #2563eb);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>';
                    } elseif ($cat === 'blocker' || str_contains($lowerMsg, 'blocker') || str_contains($lowerMsg, 'delay')) {
                        $catName   = '🚨 Blocker Alert';
                        $pillStyle = 'bg-rose-50 text-rose-700 border-rose-200';
                        $iconBg    = 'background: linear-gradient(135deg, #dc2626, #b91c1c);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
                    } elseif ($cat === 'task_assigned' || str_contains($lowerMsg, 'assigned you to task')) {
                        $catName   = '📌 Task Assigned';
                        $pillStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                        $iconBg    = 'background: linear-gradient(135deg, #2563eb, #0ea5e9);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>';
                    } elseif (str_contains($lowerMsg, 'commented')) {
                        $catName   = '💬 Comment';
                        $pillStyle = 'bg-purple-50 text-purple-700 border-purple-200';
                        $iconBg    = 'background: linear-gradient(135deg, #a855f7, #ec4899);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>';
                    } elseif ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'baseline') || str_contains($lowerMsg, 'designated') || str_contains($lowerMsg, 'declined') || str_contains($lowerMsg, 'accepted')) {
                        $catName   = '🛡️ Approval Request';
                        $pillStyle = 'bg-[#fdf4f4] text-[#c3122e] border-[#faeaea]';
                        $iconBg    = 'background: linear-gradient(135deg, #c3122e, #a00e24);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
                    } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done') || str_contains($lowerMsg, 'milestone') || str_contains($lowerMsg, 'finished')) {
                        $catName   = '✅ Completed';
                        $pillStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        $iconBg    = 'background: linear-gradient(135deg, #059669, #0d9488);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    } elseif ($cat === 'schedule_change' || $actionType === 'project_schedule_updated' || str_contains($lowerMsg, 'deadline updated') || str_contains($lowerMsg, 'schedule updated') || str_contains($lowerMsg, 'start date updated') || str_contains($lowerMsg, 'timeline updated')) {
                        $catName   = '📅 Schedule Update';
                        $pillStyle = 'bg-amber-50 text-amber-900 border-amber-200';
                        $iconBg    = 'background: linear-gradient(135deg, #f59e0b, #d97706);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
                    } else {
                        $catName   = '📣 Daily Update';
                        $pillStyle = 'bg-sky-50 text-sky-700 border-sky-200';
                        $iconBg    = 'background: linear-gradient(135deg, #0ea5e9, #2563eb);';
                        $iconSvg   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                    }

                    $isSelected = in_array($n->id, $selectedIds);
                @endphp

                {{-- Notification Row --}}
                <div class="transition-all duration-150 flex flex-col sm:flex-row sm:items-center justify-between gap-0
                    {{ $isSelected ? 'bg-rose-50/40' : ($isUnread ? 'bg-[#fffbfc] border-l-[3px] border-l-[#c3122e]' : 'bg-white') }}
                    group hover:bg-slate-50/90 cursor-pointer"
                    wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')"
                >

                    <div class="flex items-start gap-3.5 sm:gap-4 min-w-0 flex-1 p-4 sm:p-5">

                        {{-- Checkbox — stop click propagation so checking doesn't navigate --}}
                        <div class="pt-1.5 flex-shrink-0" wire:click.stop>
                            <input type="checkbox"
                                value="{{ $n->id }}"
                                wire:model.live="selectedIds"
                                class="w-4 h-4 rounded cursor-pointer"
                                style="accent-color:#c3122e;">
                        </div>

                        {{-- Category Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm ring-1 ring-black/5 relative overflow-hidden transition-transform group-hover:scale-105"
                             style="{{ $iconBg }}">
                            {{-- Gloss sheen --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent pointer-events-none"></div>
                            <svg class="w-5 h-5 text-white drop-shadow-sm relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                {!! $iconSvg !!}
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            {{-- Badge Row --}}
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-black uppercase tracking-wider border shadow-xs {{ $pillStyle }}">
                                    {{ $catName }}
                                </span>

                                @if($isUnread)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[9px] font-black bg-rose-50 text-rose-600 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        NEW
                                    </span>
                                @endif

                                @if(isset($n->data['project_name']))
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-bold text-[10px] border border-slate-200/80">
                                        📁 {{ Str::limit($n->data['project_name'], 28) }}
                                        @if(isset($n->data['project_code']))
                                            <span class="text-slate-400 font-mono text-[9px]">({{ $n->data['project_code'] }})</span>
                                        @endif
                                    </span>
                                @endif

                                <span class="text-[10.5px] text-slate-400 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $n->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Title (if separate from message) --}}
                            @if($title && $title !== $msg)
                                <p class="text-xs font-black text-[#c3122e] mb-0.5 tracking-tight">{{ $title }}</p>
                            @endif

                            {{-- Main message --}}
                            <p class="text-xs sm:text-[13px] font-semibold leading-snug {{ $isUnread ? 'text-slate-800' : 'text-slate-600' }}">
                                {{ $msg }}
                            </p>

                            {{-- Timestamp --}}
                            <div class="text-[10px] text-slate-400 font-medium mt-1">
                                📅 {{ $n->created_at->format('M d, Y · g:i A') }}
                                @if(!$isUnread && $n->read_at)
                                    · Read {{ $n->read_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Action Buttons — wire:click.stop prevents bubbling to row click --}}
                    <div class="flex items-center gap-2 self-end sm:self-center flex-shrink-0 pr-4 sm:pr-5 pb-4 sm:pb-0"
                         wire:click.stop>

                        {{-- Accept Leadership --}}
                        @if($isProjectLeaderAssignment && $isUnread)
                            <button wire:click="acceptProjectAssignment('{{ $n->id }}', {{ $projectId }})"
                                type="button"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-black text-white shadow-md hover:brightness-110 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer"
                                style="background: linear-gradient(135deg, #c3122e 0%, #a00e24 100%); border: 1px solid rgba(255,255,255,0.15);">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Accept Leadership &rarr;
                            </button>

                        {{-- View Details button --}}
                        @else
                            <button wire:click="markAsReadAndRedirect('{{ $n->id }}', '{{ addslashes($url) }}')"
                                type="button"
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#faeaea] border border-[#faeaea] transition-all flex items-center gap-1 cursor-pointer shadow-xs">
                                View Details
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        @endif

                        {{-- Mark Read / Unread --}}
                        @if($isUnread && !$isProjectLeaderAssignment)
                            <button wire:click="markAsRead('{{ $n->id }}')"
                                class="px-2.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all cursor-pointer shadow-xs">
                                Mark Read
                            </button>
                        @elseif(!$isUnread)
                            <button wire:click="markAsUnread('{{ $n->id }}')"
                                class="px-2 py-1.5 rounded-xl text-[11px] font-semibold text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all cursor-pointer">
                                Mark Unread
                            </button>
                        @endif

                        {{-- Delete --}}
                        <button wire:click="deleteNotification('{{ $n->id }}')"
                            class="p-1.5 rounded-lg text-slate-300 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all cursor-pointer"
                            title="Delete notification">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                </div>

            @empty
                {{-- Empty State --}}
                <div class="py-16 text-center px-4">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 shadow-inner">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        {{ $search ? 'No Notifications Found' : "All Clear — Inbox Zero! 🎉" }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-medium">
                        {{ $search ? "No notifications match \"{$search}\"." : "You're all caught up! There are no notifications in this stream." }}
                    </p>
                    @if($search)
                        <button wire:click="$set('search', '')"
                            class="mt-3 px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition-all cursor-pointer">
                            Clear Search Filter
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="px-6 py-3.5 bg-slate-50/60 border-t border-slate-200/80">
                {{ $notifications->links('vendor.livewire.tailwind') }}
            </div>
        @endif

    </div>

</div>
