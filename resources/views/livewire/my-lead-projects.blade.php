<div>
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (LEAD PROJECTS PORTFOLIO)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold Elegant Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: 3D Crown Icon + Title + Actionable Pill + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-amber-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Lead Projects
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-amber-200 border border-amber-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(245, 158, 11, 0.25);">
                            <span>👑</span>
                            <span>{{ $totalLeadCount }} Designated Projects</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Enterprise PM Leadership Cockpit</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Projects where you are the designated Project Manager &amp; Delivery Lead</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Quick Action or Status -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Create New Project</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. SLEEK EXECUTIVE KPI COCKPIT (4 INTERACTIVE METRIC TILES)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @php
            $metricTiles = [
                [
                    'label' => 'Total Lead Projects',
                    'count' => $totalLeadCount,
                    'icon' => '<svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'iconBg' => 'bg-slate-100 text-slate-700',
                    'border' => 'hover:border-slate-400',
                    'active' => $statusFilter === 'all',
                    'action' => '$set("statusFilter", "all")'
                ],
                [
                    'label' => 'Active In Progress',
                    'count' => $activeLeadCount,
                    'icon' => '<svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    'iconBg' => 'bg-blue-50 text-blue-600',
                    'border' => 'hover:border-blue-400',
                    'active' => $statusFilter === 'in_progress',
                    'action' => '$set("statusFilter", "in_progress")'
                ],
                [
                    'label' => 'Overdue Attention',
                    'count' => $overdueLeadCount,
                    'icon' => '<svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
                    'iconBg' => 'bg-rose-50 text-rose-600',
                    'border' => 'hover:border-rose-400',
                    'active' => false,
                    'action' => ''
                ],
                [
                    'label' => 'Delivered / Completed',
                    'count' => $completedLeadCount,
                    'icon' => '<svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'iconBg' => 'bg-emerald-50 text-emerald-600',
                    'border' => 'hover:border-emerald-400',
                    'active' => $statusFilter === 'completed',
                    'action' => '$set("statusFilter", "completed")'
                ],
            ];
        @endphp

        @foreach($metricTiles as $tile)
            <button 
                type="button" 
                @if($tile['action']) wire:click="{!! $tile['action'] !!}" @endif
                class="group text-left bg-white border border-slate-200/90 rounded-2xl p-4.5 transition-all duration-200 hover:shadow-md cursor-pointer {{ $tile['active'] ? 'ring-2 ring-slate-800 border-slate-800 bg-slate-50/50 shadow-xs' : 'shadow-2xs ' . $tile['border'] }}"
            >
                <div class="flex items-center justify-between gap-2 mb-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate group-hover:text-slate-700 transition-colors">{{ $tile['label'] }}</span>
                    <div class="w-8 h-8 rounded-xl {{ $tile['iconBg'] }} flex items-center justify-center flex-shrink-0 shadow-2xs">
                        {!! $tile['icon'] !!}
                    </div>
                </div>
                <div class="flex items-baseline justify-between gap-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $tile['count'] }}</span>
                    @if($tile['count'] > 0 && $tile['label'] === 'Overdue Attention')
                        <span class="text-[9px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">Action Req</span>
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
                placeholder="Search lead projects by name, code, or subsidiary..."
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
                        <th class="py-4 px-4 hidden md:table-cell">Subsidiary</th>
                        <th class="py-4 px-4">Leadership Status</th>
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
                                'in_progress' => ['dot' => 'bg-blue-500', 'text' => 'text-blue-700', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'label' => 'In Progress'],
                                'completed'   => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'label' => 'Completed'],
                                'on_hold'     => ['dot' => 'bg-amber-500', 'text' => 'text-amber-700', 'bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'label' => 'On Hold'],
                                'cancelled'   => ['dot' => 'bg-slate-400', 'text' => 'text-slate-500', 'bg' => 'bg-slate-100', 'border' => 'border-slate-200', 'label' => 'Cancelled'],
                                default       => ['dot' => 'bg-indigo-500', 'text' => 'text-indigo-700', 'bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'label' => 'Planning'],
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
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black text-slate-600 bg-slate-100 border border-slate-200/80">
                                                {{ $p->code }}
                                            </span>
                                            @if($p->members && $p->members->count() > 0)
                                                <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                                    <span>👥</span>
                                                    <span>{{ $p->members->count() }} Members</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Subsidiary -->
                            <td class="py-4 px-4 hidden md:table-cell">
                                <span class="text-xs text-slate-700 font-bold flex items-center gap-1.5">
                                    <span class="text-slate-400">🏢</span>
                                    <span class="truncate max-w-[160px]">{{ $p->subsidiary->name ?? 'George Steuart' }}</span>
                                </span>
                            </td>

                            <!-- Leadership Acceptance Status -->
                            <td class="py-4 px-4">
                                @if($p->isPmAccepted())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>👑 Accepted / Active</span>
                                    </span>
                                @elseif($p->isPmRejected())
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs" title="{{ $p->pm_rejection_reason }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span>⚠️ Declined (Issue Raised)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-300 shadow-2xs animate-pulse">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span>⏳ Pending Acceptance</span>
                                    </span>
                                @endif
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
                                        <div class="h-full rounded-full transition-all duration-500" style="width:{{ max(2, $progress) }}%; background: linear-gradient(90deg, #c3122e, #e02d4b);"></div>
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
                                @if(!$p->isPmAccepted() && ($p->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin()))
                                    <button
                                        type="button"
                                        wire:click="openReviewModal({{ $p->id }})"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-300 shadow-sm cursor-pointer transition-all active:scale-95"
                                    >
                                        <span>Review &amp; Accept</span>
                                        <span>➔</span>
                                    </button>
                                @else
                                    <a
                                        href="{{ route('projects.show', $p) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-white shadow-md shadow-rose-950/20 hover:scale-105 transition-all duration-200 no-underline"
                                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                                    >
                                        <span>Open Workspace</span>
                                        <span>➔</span>
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
    <div class="bg-white border border-slate-200/90 rounded-3xl p-14 text-center shadow-2xs space-y-3">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center mx-auto mb-2 shadow-xs">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <h3 class="text-base font-black text-slate-900">No Lead Projects Found</h3>
        <p class="text-xs text-slate-500 font-medium max-w-sm mx-auto">
            @if($search || $statusFilter !== 'all')
                No lead projects match your selected filters. <button wire:click="clearFilters" class="text-[#c3122e] font-black underline cursor-pointer">Clear Filters</button>
            @else
                You have not been assigned as designated Project Leader on any project yet.
            @endif
        </p>
    </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         5. PROJECT LEADERSHIP REVIEW & ACCEPTANCE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showReviewModal && $reviewProject)
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-xl max-h-[92vh] flex flex-col overflow-hidden my-auto animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header (Fixed at top) -->
            <div class="flex-shrink-0 px-5 sm:px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-rose-50/80 via-white to-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl text-white flex items-center justify-center shadow-md shadow-rose-950/20 flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm sm:text-base font-black text-slate-900 tracking-tight">Project Leadership Assignment</h3>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                                {{ $reviewProject->code }}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium">Assigned by PMO Administration</p>
                    </div>
                </div>
                <button type="button" wire:click="closeReviewModal" class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Modal Scrollable Body -->
            <div class="flex-1 overflow-y-auto px-5 sm:px-6 py-4 sm:py-5 space-y-4 scrollbar-thin">
                @if(!$showRejectModal)
                    <!-- Project Title & Scope Card -->
                    <div class="space-y-3.5">
                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Project Name</span>
                            <h4 class="text-sm sm:text-base font-black text-slate-900 leading-snug">{{ $reviewProject->name }}</h4>
                            @if($reviewProject->description)
                                <div class="mt-2.5 pt-2.5 border-t border-slate-200/70">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-0.5">Scope &amp; Objectives</span>
                                    <p class="text-xs text-slate-600 leading-relaxed font-medium">{{ $reviewProject->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- 2x2 Meta Attributes Grid -->
                        <div class="grid grid-cols-2 gap-2.5 text-xs">
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Subsidiary Entity</span>
                                <span class="font-extrabold text-slate-900 block mt-1 truncate">{{ $reviewProject->subsidiary->name ?? 'George Steuart Group' }}</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Team Size</span>
                                <span class="font-extrabold text-slate-900 block mt-1">{{ $reviewProject->members->count() }} Collaborators</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Kick-off Date</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">{{ $reviewProject->start_date ? $reviewProject->start_date->format('M d, Y') : 'Immediate' }}</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Target Delivery</span>
                                <span class="font-mono font-bold text-slate-900 block mt-1">{{ $reviewProject->deadline ? $reviewProject->deadline->format('M d, Y') : 'TBD' }}</span>
                            </div>
                        </div>

                        <!-- Configured Blueprint Template & WBS Structure -->
                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="text-xl flex-shrink-0">📋</span>
                                    <div class="min-w-0">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">WBS Delivery Blueprint</span>
                                        <h5 class="text-xs font-black text-slate-900 truncate">
                                            {{ $reviewProject->template->name ?? ($reviewProject->wbs_breakdown_type === 'template' ? 'Standard WBS Delivery Blueprint' : 'Custom Agile Blueprint') }}
                                        </h5>
                                    </div>
                                </div>
                                @if($reviewProject->wbsItems->count() > 0)
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-200 flex-shrink-0">
                                        {{ $reviewProject->wbsItems->whereNull('parent_id')->count() }} Phases · {{ $reviewProject->wbsItems->count() }} Tasks
                                    </span>
                                @endif
                            </div>

                            @if($reviewProject->wbsItems->whereNull('parent_id')->count() > 0)
                                <div class="pt-2.5 border-t border-slate-200 space-y-1.5 max-h-40 overflow-y-auto pr-1 scrollbar-thin">
                                    @foreach($reviewProject->wbsItems->whereNull('parent_id') as $phase)
                                        <div class="p-2.5 rounded-xl bg-white border border-slate-200 flex items-center justify-between text-[11px] shadow-2xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-2 h-2 rounded-full bg-[#c3122e] flex-shrink-0"></span>
                                                <span class="font-bold text-slate-900 truncate">{{ $phase->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0 text-[10px]">
                                                <span class="text-slate-400 font-mono">{{ $phase->duration_days ?? 0 }}d</span>
                                                <span class="px-2 py-0.5 rounded-md font-bold bg-slate-100 text-slate-700">
                                                    {{ $phase->children->count() }} Subtasks
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if($reviewProject->isPmRejected())
                        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 space-y-1">
                            <span class="text-[10px] font-black uppercase tracking-wider block text-rose-700">Previous Reported Feedback:</span>
                            <p class="font-medium italic">"{{ $reviewProject->pm_rejection_reason }}"</p>
                            <span class="text-[9px] text-rose-500 font-mono block">Declined on {{ $reviewProject->pm_rejected_at?->format('M d, Y h:i A') }}</span>
                        </div>
                        @endif
                    </div>
                @else
                    <!-- Rejection Reason Form -->
                    <div class="space-y-3.5">
                        <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                            <strong class="font-black block mb-0.5">Declining Project Leadership</strong>
                            Please provide the specific reason, capacity constraints, or scope feedback so PMO Administration can review and adjust requirements.
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-600 uppercase tracking-wider block">
                                Reason / Issue Report <span class="text-rose-500">*</span>
                            </label>
                            <textarea
                                wire:model="rejectionReasonInput"
                                rows="4"
                                placeholder="State why you cannot accept this assignment (e.g., Schedule conflicts, scope clarifications needed)..."
                                class="w-full p-3.5 rounded-2xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 transition-all"
                            ></textarea>
                            @error('rejectionReasonInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal Sticky Footer (Always Visible) -->
            <div class="flex-shrink-0 px-5 sm:px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 flex-wrap">
                @if(!$showRejectModal)
                    <button
                        type="button"
                        wire:click="openRejectForm({{ $reviewProject->id }})"
                        class="px-4 py-2.5 rounded-xl text-xs font-black text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer flex-shrink-0"
                    >
                        ✕ Decline &amp; Report
                    </button>

                    <button
                        type="button"
                        wire:click="acceptProject({{ $reviewProject->id }})"
                        class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 transition-all cursor-pointer flex items-center gap-1.5 flex-shrink-0"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Accept Leadership &amp; Unlock</span>
                    </button>
                @else
                    <button
                        type="button"
                        wire:click="$set('showRejectModal', false)"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 transition-colors cursor-pointer"
                    >
                        Back
                    </button>

                    <button
                        type="button"
                        wire:click="submitRejection"
                        class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md bg-rose-600 hover:bg-rose-700 transition-all cursor-pointer flex items-center gap-1.5"
                    >
                        <span>Submit &amp; Decline</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
