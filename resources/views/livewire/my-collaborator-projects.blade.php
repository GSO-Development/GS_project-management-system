<div>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (COLLABORATOR PROJECTS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold & Crimson Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: Team Icon + Title + Actionable Pill + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 60%, #4a0510 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Collaborator Projects
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.25);">
                            <span>👥</span>
                            <span>{{ $totalCollabCount }} Contributing Projects</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Team Member Workspace</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Projects where you are an assigned team member &amp; deliverable contributor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. SLEEK EXECUTIVE KPI COCKPIT (4 INTERACTIVE METRIC TILES)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
        @php
            $collabTiles = [
                [
                    'label' => 'Total Collaborations',
                    'count' => $totalCollabCount,
                    'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'iconBg' => 'bg-slate-100 text-slate-700',
                    'border' => 'hover:border-slate-400',
                    'active' => $statusFilter === 'all',
                    'action' => '$set("statusFilter", "all")'
                ],
                [
                    'label' => 'Active Delivery',
                    'count' => $activeCollabCount,
                    'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    'iconBg' => 'bg-rose-50 text-[#c3122e]',
                    'border' => 'hover:border-rose-400',
                    'active' => $statusFilter === 'in_progress',
                    'action' => '$set("statusFilter", "in_progress")'
                ],
                [
                    'label' => 'Overdue Attention',
                    'count' => $overdueCollabCount,
                    'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    'iconBg' => 'bg-rose-50 text-rose-600',
                    'border' => 'hover:border-rose-400',
                    'active' => false,
                    'action' => ''
                ],
                [
                    'label' => 'Delivered / Completed',
                    'count' => $completedCollabCount,
                    'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'iconBg' => 'bg-emerald-50 text-emerald-600',
                    'border' => 'hover:border-emerald-400',
                    'active' => $statusFilter === 'completed',
                    'action' => '$set("statusFilter", "completed")'
                ],
            ];
        @endphp

        @foreach($collabTiles as $tile)
            <button 
                type="button" 
                @if($tile['action']) wire:click="{!! $tile['action'] !!}" @endif
                class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-2.5 sm:p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer {{ $tile['active'] ? 'ring-2 ring-[#c3122e] border-[#c3122e] bg-[#fdf4f4]/40 shadow-xs' : 'shadow-2xs ' . $tile['border'] }}"
            >
                <div class="flex items-center justify-between gap-1 mb-1 sm:mb-2.5">
                    <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">{{ $tile['label'] }}</span>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl {{ $tile['iconBg'] }} flex items-center justify-center flex-shrink-0 shadow-2xs">
                        {!! $tile['icon'] !!}
                    </div>
                </div>
                <div class="flex items-baseline justify-between gap-1 sm:gap-2">
                    <span class="text-lg sm:text-2xl font-black {{ $tile['label'] === 'Overdue Attention' && $tile['count'] > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight font-mono leading-none">{{ $tile['count'] }}</span>
                    @if($tile['count'] > 0 && $tile['label'] === 'Overdue Attention')
                        <span class="text-[8px] sm:text-[9px] font-black text-rose-600 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200">Action</span>
                    @endif
                </div>
            </button>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. SMART SEARCH & FILTER CONTROLS TOOLBAR
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 shadow-2xs mb-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3.5">
        <!-- Search Input Bar -->
        <div class="relative flex-1 min-w-0">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search" 
                placeholder="Search collaborator projects by name, code, or leader..."
                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all"
            >
            @if($search)
                <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            @endif
        </div>

        <!-- Filter Controls Container -->
        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap flex-shrink-0">
            <!-- Status Filter Dropdown -->
            <div class="min-w-[180px] sm:w-48">
                <select 
                    wire:model.live="statusFilter" 
                    class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all"
                >
                    <option value="all">📋 All Statuses</option>
                    <option value="planning">⏳ Planning</option>
                    <option value="in_progress">⚡ In Progress</option>
                    <option value="on_hold">⏸️ On Hold</option>
                    <option value="completed">✅ Completed</option>
                    <option value="cancelled">🚫 Cancelled</option>
                </select>
            </div>

            <!-- Clear Filter Button -->
            @if($search || $statusFilter !== 'all')
                <button 
                    type="button" 
                    wire:click="clearFilters" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] hover:bg-rose-100/70 border border-rose-200 transition-colors cursor-pointer flex-shrink-0 shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset</span>
                </button>
            @endif
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         4. HIGH-END EXECUTIVE PROJECTS MATRIX (TABLE VIEW)
         ═══════════════════════════════════════════════════════════════ -->
    @if($projects->count() > 0)
    <div class="bg-white border border-slate-200/90 rounded-3xl overflow-hidden shadow-sm mb-6">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/90 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-5">Project &amp; Code</th>
                        <th class="py-4 px-4 hidden md:table-cell">Project Leader</th>
                        <th class="py-4 px-4 hidden md:table-cell">Subsidiary</th>
                        <th class="py-4 px-4 hidden lg:table-cell">Delivery Status</th>
                        <th class="py-4 px-4 hidden lg:table-cell">Progress</th>
                        <th class="py-4 px-4 hidden xl:table-cell">Timeline</th>
                        <th class="py-4 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($projects as $p)
                        @php
                            $progress = $p->overall_progress ?? 0;
                            $daysLeft = $p->deadline ? (int) now()->today()->diffInDays($p->deadline, false) : null;
                            $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($p->status->value, ['completed','cancelled']);
                            $statusConfig = match($p->status->value) {
                                'in_progress' => ['dot' => 'bg-amber-500', 'text' => 'text-amber-800', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'label' => 'In Progress'],
                                'completed'   => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'label' => 'Completed'],
                                'on_hold'     => ['dot' => 'bg-slate-500', 'text' => 'text-slate-700', 'bg' => 'bg-slate-100', 'border' => 'border-slate-200', 'label' => 'On Hold'],
                                'cancelled'   => ['dot' => 'bg-rose-500', 'text' => 'text-rose-700', 'bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'label' => 'Cancelled'],
                                default       => ['dot' => 'bg-rose-500', 'text' => 'text-rose-700', 'bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'label' => 'Planning'],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Project Name & Code -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-black text-xs shadow-md shadow-rose-950/15" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($p->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('projects.show', $p) }}" class="font-black text-slate-900 hover:text-[#c3122e] transition-colors truncate block max-w-xs text-sm no-underline">
                                            {{ $p->name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black text-rose-700 bg-rose-50 border border-rose-200/80">
                                                {{ $p->code }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Project Leader -->
                            <td class="py-4 px-4 hidden md:table-cell">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-xl bg-rose-50 text-[#c3122e] border border-rose-200 font-black text-[10px] flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($p->projectManager->name ?? 'L', 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 truncate max-w-[120px]">{{ $p->projectManager->name ?? '—' }}</span>
                                </div>
                            </td>

                            <!-- Subsidiary -->
                            <td class="py-4 px-4 hidden md:table-cell">
                                <span class="text-xs text-slate-700 font-bold flex items-center gap-1.5">
                                    <span class="text-slate-400">🏢</span>
                                    <span class="truncate max-w-[150px]">{{ $p->subsidiary->name ?? 'George Steuart' }}</span>
                                </span>
                            </td>

                            <!-- Delivery Status -->
                            <td class="py-4 px-4 hidden lg:table-cell">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    <span>{{ $statusConfig['label'] }}</span>
                                </span>
                            </td>

                            <!-- Progress Bar -->
                            <td class="py-4 px-4 hidden lg:table-cell">
                                <div class="space-y-1.5 w-28">
                                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-500">
                                        <span class="font-mono text-slate-900 font-black">{{ $progress }}%</span>
                                        <span>{{ $p->wbsItems->count() }} Tasks</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 bg-[#c3122e]" style="width:{{ max(2, $progress) }}%;"></div>
                                    </div>
                                </div>
                            </td>

                            <!-- Timeline & Deadline -->
                            <td class="py-4 px-4 hidden xl:table-cell">
                                <div class="space-y-1">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="text-slate-400">📅</span>
                                        <span>{{ $p->deadline ? $p->deadline->format('M d, Y') : 'No Deadline' }}</span>
                                    </div>
                                    @if($isOverdue)
                                        <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-black bg-rose-50 text-rose-600 border border-rose-200">
                                            ⚠️ {{ abs($daysLeft) }}d overdue
                                        </span>
                                    @elseif($daysLeft !== null && $daysLeft >= 0)
                                        <span class="inline-block text-[10px] font-bold text-slate-400 font-mono">
                                            {{ $daysLeft }}d left
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-5 text-right">
                                <a
                                    href="{{ route('projects.show', $p) }}"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-white shadow-md shadow-rose-950/20 hover:scale-105 transition-all duration-200 no-underline cursor-pointer"
                                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                                >
                                    <span>Open Workspace</span>
                                    <span>➔</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>

    @else
    <div class="bg-white border border-slate-200/90 rounded-3xl p-14 text-center shadow-2xs space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center mx-auto mb-2 shadow-xs">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h3 class="text-base font-black text-slate-900">No Collaborator Projects Found</h3>
        <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">
            @if($search || $statusFilter !== 'all')
                No collaborator projects match your selected filters. <button wire:click="clearFilters" class="text-[#c3122e] font-black underline cursor-pointer">Clear Filters</button>
            @else
                You have not been added as an assigned team member or collaborator on any project yet.
            @endif
        </p>
    </div>
    @endif
</div>
