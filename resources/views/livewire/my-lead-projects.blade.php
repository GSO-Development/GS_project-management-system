<div>
    {{-- ══════════════════════════════════════════════════════════
         1. HERO BANNER — MY PROJECTS
         ══════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e]"></div>
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75"/>
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5"/>
            </svg>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-amber-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #f59e0b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">My Projects</h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-amber-200 border border-amber-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(245, 158, 11, 0.25);">
                            <span>📁</span>
                            <span>{{ $totalCount }} Project{{ $totalCount !== 1 ? 's' : '' }}</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>George Steuart Project Portal</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">All projects you are involved in — across all roles</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                @if(auth()->user()->canCreateProject())
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Create New Project</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         2. KPI TILES
         ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4 mb-4 sm:mb-6">
        @php
            $metricTiles = [
                ['label' => 'Total Projects', 'count' => $totalCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>', 'iconBg' => 'bg-slate-100 text-slate-700', 'border' => 'hover:border-slate-400', 'active' => $statusFilter === 'all', 'action' => '$set("statusFilter", "all")'],
                ['label' => 'In Progress', 'count' => $activeCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', 'iconBg' => 'bg-blue-50 text-blue-600', 'border' => 'hover:border-blue-400', 'active' => $statusFilter === 'in_progress', 'action' => '$set("statusFilter", "in_progress")'],
                ['label' => 'Overdue Attention', 'count' => $overdueCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>', 'iconBg' => 'bg-rose-50 text-rose-600', 'border' => 'hover:border-rose-400', 'active' => false, 'action' => ''],
                ['label' => 'Completed', 'count' => $completedCount, 'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', 'iconBg' => 'bg-emerald-50 text-emerald-600', 'border' => 'hover:border-emerald-400', 'active' => $statusFilter === 'completed', 'action' => '$set("statusFilter", "completed")'],
            ];
        @endphp
        @foreach($metricTiles as $tile)
            <button type="button" @if($tile['action']) wire:click="{{ $tile['action'] }}" @endif
                class="group text-left bg-white border border-slate-200/90 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 transition-all duration-200 hover:shadow-md cursor-pointer {{ $tile['active'] ? 'ring-2 ring-[#c3122e] border-[#c3122e] bg-rose-50/30 shadow-sm' : 'shadow-xs ' . $tile['border'] }}"
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
            {{-- Role Filter --}}
            <div class="min-w-[160px]">
                <select wire:model.live="roleFilter"
                    class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                    <option value="all">👥 All My Roles</option>
                    <option value="lead">⭐ Project Manager (PM)</option>
                    <option value="sponsor">💼 Project Sponsor</option>
                    <option value="owner">🏛️ Project Owner</option>
                    <option value="steering_committee">🎖️ Steering Committee</option>
                    <option value="member">🤝 Team Member</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="min-w-[155px]">
                <select wire:model.live="statusFilter"
                    class="w-full py-2.5 px-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-xs font-bold text-slate-800 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer transition-all">
                    <option value="all">📋 All Statuses</option>
                    <option value="planning">⏳ Planning</option>
                    <option value="in_progress">⚡ In Progress</option>
                    <option value="on_hold">⏸️ On Hold</option>
                    <option value="completed">✅ Completed</option>
                    <option value="cancelled">🚫 Cancelled</option>
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
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/90 text-[10px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-5">Project &amp; Code</th>
                        <th class="py-4 px-4 hidden md:table-cell">Subsidiary</th>
                        <th class="py-4 px-4">Your Role</th>
                        <th class="py-4 px-4 hidden lg:table-cell">Status</th>
                        <th class="py-4 px-4 hidden lg:table-cell">Progress</th>
                        <th class="py-4 px-4 hidden xl:table-cell">Timeline</th>
                        <th class="py-4 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @foreach($projects as $p)
                        @php
                            $progress  = $p->overall_progress ?? 0;
                            $daysLeft  = $p->deadline ? (int) now()->today()->diffInDays($p->deadline, false) : null;
                            $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($p->status->value, ['completed','cancelled']);
                            $userRole  = $userRoles[$p->id] ?? 'member';

                            $statusConfig = match($p->status->value) {
                                'in_progress' => ['dot' => 'bg-blue-500',    'text' => 'text-blue-700',    'bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'label' => 'In Progress'],
                                'completed'   => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'label' => 'Completed'],
                                'on_hold'     => ['dot' => 'bg-amber-500',   'text' => 'text-amber-700',   'bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'label' => 'On Hold'],
                                'cancelled'   => ['dot' => 'bg-slate-400',   'text' => 'text-slate-500',   'bg' => 'bg-slate-100',  'border' => 'border-slate-200',   'label' => 'Cancelled'],
                                default       => ['dot' => 'bg-indigo-500',  'text' => 'text-indigo-700',  'bg' => 'bg-indigo-50',  'border' => 'border-indigo-200',  'label' => 'Planning'],
                            };

                            $roleConfig = match($userRole) {
                                'pmo_admin'          => ['label' => 'PMO Admin',          'icon' => '🛡️', 'bg' => 'bg-slate-900',    'text' => 'text-white',         'border' => 'border-slate-700'],
                                'lead'               => ['label' => 'Project Manager',     'icon' => '⭐', 'bg' => 'bg-[#c3122e]',    'text' => 'text-white',         'border' => 'border-rose-700'],
                                'sponsor'            => ['label' => 'Project Sponsor',    'icon' => '💼', 'bg' => 'bg-amber-100',    'text' => 'text-amber-800',     'border' => 'border-amber-300'],
                                'owner'              => ['label' => 'Project Owner',      'icon' => '🏛️', 'bg' => 'bg-purple-100',   'text' => 'text-purple-800',    'border' => 'border-purple-300'],
                                'steering_committee' => ['label' => 'Steering Committee', 'icon' => '🎖️', 'bg' => 'bg-blue-100',     'text' => 'text-blue-800',      'border' => 'border-blue-300'],
                                default              => ['label' => 'Team Member',         'icon' => '🤝', 'bg' => 'bg-slate-100',    'text' => 'text-slate-700',     'border' => 'border-slate-300'],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            {{-- Project Name & Code --}}
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
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-black text-slate-600 bg-slate-100 border border-slate-200/80">{{ $p->code }}</span>
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

                            {{-- Subsidiary --}}
                            <td class="py-4 px-4 hidden md:table-cell">
                                <span class="text-xs text-slate-700 font-bold flex items-center gap-1.5">
                                    <span class="text-slate-400">🏢</span>
                                    <span class="truncate max-w-[160px]">{{ $p->subsidiary->name ?? 'George Steuart' }}</span>
                                </span>
                            </td>

                            {{-- Your Role Badge --}}
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black {{ $roleConfig['bg'] }} {{ $roleConfig['text'] }} border {{ $roleConfig['border'] }} shadow-xs">
                                    <span>{{ $roleConfig['icon'] }}</span>
                                    <span>{{ $roleConfig['label'] }}</span>
                                </span>
                                {{-- Pending acceptance badge for Lead role --}}
                                @if($userRole === 'lead' && !$p->isPmAccepted())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-50 text-amber-700 border border-amber-300 mt-1 animate-pulse">
                                        ⏳ Pending Accept
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="py-4 px-4 hidden lg:table-cell">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                                    <span>{{ $statusConfig['label'] }}</span>
                                </span>
                            </td>

                            {{-- Progress --}}
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

                            {{-- Timeline --}}
                            <td class="py-4 px-4 hidden xl:table-cell">
                                <div class="space-y-1">
                                    <div class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span class="text-slate-400">📅</span>
                                        <span>{{ $p->deadline ? $p->deadline->format('M d, Y') : 'No Deadline' }}</span>
                                    </div>
                                    @if($isOverdue)
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-black bg-rose-50 text-rose-600 border border-rose-200">⚠️ {{ abs($daysLeft) }}d overdue</span>
                                    @elseif($daysLeft !== null && $daysLeft >= 0)
                                        <span class="inline-block text-[10px] font-bold text-slate-400 font-mono">{{ $daysLeft }}d left</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Action --}}
                            <td class="py-4 px-5 text-right">
                                @if($userRole === 'lead' && !$p->isPmAccepted() && ($p->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin()))
                                    <button type="button" wire:click="openReviewModal({{ $p->id }})"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-300 shadow-sm cursor-pointer transition-all active:scale-95">
                                        <span>Review &amp; Accept</span>
                                        <span>➔</span>
                                    </button>
                                @else
                                    <a href="{{ route('projects.show', $p) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-white shadow-md shadow-rose-950/20 hover:scale-105 transition-all duration-200 no-underline"
                                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
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
    <!-- 5. Review Project Assignment Modal -->
    @if($showReviewModal && $reviewProject)
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
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                        {{ $phases->count() }} Phases
                                    </span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
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
                                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-black bg-emerald-100 text-emerald-800">Milestone</span>
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
    @endif
</div>
