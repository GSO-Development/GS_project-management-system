<div>
    <!-- Page Header & Quick Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-7">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Notification Center</h1>
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#fdf4f4] to-[#fceaea] border border-[#faeaea] flex items-center justify-center text-[#c3122e] shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Categorized real-time hub for Updates, Baseline Approvals, and Task Completion alerts</p>
        </div>

        <div class="flex items-center gap-3">
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="px-4 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Mark All as Read</span>
                </button>
            @endif

            @if($readCount > 0)
                <button wire:click="deleteAllRead" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-all flex items-center gap-1.5 hover:border-slate-300 cursor-pointer">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Clear Read</span>
                </button>
            @endif
        </div>
    </div>

    <!-- 3 MAIN CATEGORY SIDES (UPDATES, APPROVALS, TASK COMPLETED) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-7">

        <!-- SIDE 1: UPDATES -->
        <div 
            wire:click="setCategoryTab('updates')" 
            class="card p-5 flex items-center justify-between cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $categoryTab === 'updates' ? 'ring-2 ring-blue-500 border-blue-400 bg-blue-50/30 shadow-md' : 'bg-white border-slate-200 hover:border-blue-300' }}"
        >
            <div class="min-w-0 pr-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Updates</span>
                </div>
                <div class="text-3xl font-black text-blue-600 mt-2">{{ $updatesCount }}</div>
                <p class="text-[11px] font-semibold text-slate-400 mt-1 line-clamp-1">Daily status logs & project updates</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shadow-2xs flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
        </div>

        <!-- SIDE 2: APPROVALS -->
        <div 
            wire:click="setCategoryTab('approvals')" 
            class="card p-5 flex items-center justify-between cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $categoryTab === 'approvals' ? 'ring-2 ring-[#c3122e] border-[#c3122e] bg-[#fdf4f4]/40 shadow-md' : 'bg-white border-slate-200 hover:border-rose-300' }}"
        >
            <div class="min-w-0 pr-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#c3122e]"></span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Approvals</span>
                </div>
                <div class="text-3xl font-black text-[#c3122e] mt-2">{{ $approvalsCount }}</div>
                <p class="text-[11px] font-semibold text-slate-400 mt-1 line-clamp-1">WBS baseline & budget review requests</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] shadow-2xs flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- SIDE 3: TASK COMPLETED -->
        <div 
            wire:click="setCategoryTab('task_completed')" 
            class="card p-5 flex items-center justify-between cursor-pointer transition-all duration-200 group relative overflow-hidden {{ $categoryTab === 'task_completed' ? 'ring-2 ring-emerald-500 border-emerald-400 bg-emerald-50/30 shadow-md' : 'bg-white border-slate-200 hover:border-emerald-300' }}"
        >
            <div class="min-w-0 pr-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wider">Task Completed</span>
                </div>
                <div class="text-3xl font-black text-emerald-600 mt-2">{{ $taskCompletedCount }}</div>
                <p class="text-[11px] font-semibold text-slate-400 mt-1 line-clamp-1">WBS task completions & milestones</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-2xs flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9m12 0a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h2m2 4l2 2 4-4"/></svg>
            </div>
        </div>

    </div>

    <!-- Category Filter Tabs & Unread Status Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-3 border-b border-slate-200">
        <!-- Category Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto">
            <button 
                wire:click="setCategoryTab('all')" 
                class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap {{ $categoryTab === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
            >
                All Notifications ({{ $totalCount }})
            </button>

            <button 
                wire:click="setCategoryTab('updates')" 
                class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-2 {{ $categoryTab === 'updates' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-blue-50 border border-slate-200' }}"
            >
                <span>📣 Updates</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $categoryTab === 'updates' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800' }}">{{ $updatesCount }}</span>
            </button>

            <button 
                wire:click="setCategoryTab('approvals')" 
                class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-2 {{ $categoryTab === 'approvals' ? 'bg-[#c3122e] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-rose-50 border border-slate-200' }}"
            >
                <span>🛡️ Approvals</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $categoryTab === 'approvals' ? 'bg-white/20 text-white' : 'bg-[#fdf4f4] text-[#c3122e]' }}">{{ $approvalsCount }}</span>
            </button>

            <button 
                wire:click="setCategoryTab('task_completed')" 
                class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer whitespace-nowrap flex items-center gap-2 {{ $categoryTab === 'task_completed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-emerald-50 border border-slate-200' }}"
            >
                <span>✅ Task Completed</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $categoryTab === 'task_completed' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $taskCompletedCount }}</span>
            </button>
        </div>

        <!-- Status Filter Tabs (Unread / Read) -->
        <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl self-start sm:self-auto">
            <button 
                wire:click="setStatusFilter('all')" 
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
            >
                All
            </button>
            <button 
                wire:click="setStatusFilter('unread')" 
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $statusFilter === 'unread' ? 'bg-white text-rose-600 shadow-2xs font-extrabold' : 'text-slate-600 hover:text-slate-900' }}"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                Unread ({{ $unreadCount }})
            </button>
            <button 
                wire:click="setStatusFilter('read')" 
                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $statusFilter === 'read' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
            >
                Read ({{ $readCount }})
            </button>
        </div>
    </div>

    <!-- Notifications List Container -->
    <div class="card p-0 overflow-hidden shadow-sm border border-slate-200/90 rounded-2xl bg-white">
        <div class="divide-y divide-slate-100">
            @forelse($notifications as $n)
                @php
                    $isUnread = is_null($n->read_at);
                    $msg = $n->data['message'] ?? $n->data['title'] ?? 'System Notification';
                    $title = $n->data['title'] ?? null;
                    $url = $n->data['url'] ?? null;

                    // Categorize icon and style
                    $cat = $n->data['category'] ?? null;
                    $lowerMsg = strtolower($msg);

                    if ($cat === 'approvals' || str_contains($lowerMsg, 'approval') || str_contains($lowerMsg, 'request') || str_contains($lowerMsg, 'baseline')) {
                        $catName = 'Approval Request';
                        $pillStyle = 'bg-[#fdf4f4] text-[#c3122e] border-[#faeaea]';
                        $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
                    } elseif ($cat === 'task_completed' || str_contains($lowerMsg, 'completed') || str_contains($lowerMsg, 'done') || str_contains($lowerMsg, 'milestone')) {
                        $catName = 'Task Completed';
                        $pillStyle = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    } else {
                        $catName = 'System Update';
                        $pillStyle = 'bg-blue-50 text-blue-700 border-blue-200';
                        $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>';
                    }
                @endphp

                <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors duration-150 {{ $isUnread ? 'bg-white border-l-4 border-l-[#c3122e]' : 'bg-slate-50/50' }}">
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-11 h-11 rounded-2xl border flex items-center justify-center flex-shrink-0 shadow-2xs {{ $pillStyle }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $iconSvg !!}</svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $pillStyle }}">
                                    {{ $catName }}
                                </span>
                                @if($isUnread)
                                    <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-pulse"></span>
                                    <span class="text-[9px] font-black text-[#c3122e] uppercase">New</span>
                                @endif
                            </div>
                            <p class="text-xs font-extrabold text-slate-900 leading-snug mt-1">{{ $msg }}</p>
                            <span class="text-[10px] text-slate-400 font-semibold mt-1 block">
                                {{ $n->created_at->diffForHumans() }} ({{ $n->created_at->format('M d, Y — g:i A') }})
                            </span>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="flex items-center gap-2 self-end sm:self-center flex-shrink-0">
                        @if($url)
                            <a href="{{ $url }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#fceaea] border border-[#faeaea] transition-all flex items-center gap-1 cursor-pointer">
                                <span>View Details</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif

                        @if($isUnread)
                            <button wire:click="markAsRead('{{ $n->id }}')" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer">
                                Mark Read
                            </button>
                        @endif

                        <button wire:click="deleteNotification('{{ $n->id }}')" class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Notification">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <h4 class="text-sm font-black text-slate-900">No Notifications in this Category</h4>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto font-medium">There are no active notifications matching your selected category tab.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination Footer -->
        <div class="p-4 border-t border-slate-100 bg-white">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
