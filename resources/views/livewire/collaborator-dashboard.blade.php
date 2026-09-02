{{-- ============================================================
     COLLABORATOR / TEAM MEMBER DASHBOARD — GS NexusPM
     ============================================================ --}}
<div class="space-y-6 pb-12 font-sans">

    {{-- ═══════════════════ PENDING PM ASSIGNMENT INVITATIONS ═══════════════════ --}}
    @if(isset($pendingInvitations) && $pendingInvitations->count() > 0)
        <div class="mb-6 rounded-2xl overflow-hidden shadow-md border-2 border-amber-300 bg-white animate-in fade-in duration-300">
            <!-- Header Bar -->
            <div class="px-5 py-4 bg-gradient-to-r from-amber-500 via-amber-600 to-rose-600 flex items-center justify-between gap-4 flex-wrap text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center text-xl flex-shrink-0 shadow-inner">
                        🔔
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-base font-black text-white tracking-tight">Project Leadership Assignment Required</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-white text-rose-700 shadow-xs uppercase tracking-wider">
                                {{ $pendingInvitations->count() }} Action Required
                            </span>
                        </div>
                        <p class="text-xs text-amber-100 font-medium mt-0.5">PMO Admin has designated you as the Project Manager. Please accept to confirm leadership and open the workspace.</p>
                    </div>
                </div>
            </div>

            <!-- Project Cards Grid -->
            <div class="p-5 bg-slate-50/60 space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pendingInvitations as $inv)
                        <div class="p-4 rounded-2xl border-2 border-amber-200 bg-white flex items-center justify-between gap-4 shadow-sm hover:border-rose-400 hover:shadow-md transition-all">
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-black text-[#c3122e] bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-md">
                                        {{ $inv->code }}
                                    </span>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 bg-amber-100 border border-amber-300 px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Pending Acceptance
                                    </span>
                                </div>
                                <h4 class="text-sm font-black text-slate-900 leading-snug truncate">{{ $inv->name }}</h4>
                                <p class="text-xs text-slate-500 font-medium truncate">🏢 {{ $inv->subsidiary->name ?? 'George Steuart' }}</p>
                            </div>

                            <button 
                                wire:click="acceptProjectAssignment({{ $inv->id }})"
                                type="button" 
                                style="background-color: #c3122e; color: #ffffff;"
                                class="px-4 py-2.5 rounded-xl text-xs font-black shadow-sm hover:bg-[#a00e24] hover:shadow-md transition-all flex items-center gap-1.5 cursor-pointer flex-shrink-0 active:scale-95"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Accept &amp; Open</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════ 1. HERO BANNER ═══════════════════ --}}
    <div class="relative rounded-3xl overflow-hidden shadow-xl" style="background: linear-gradient(135deg, #160a0f 0%, #200d14 45%, #13060b 100%); border: 1px solid rgba(195,18,46,0.25);">
        
        {{-- Crimson Wave Vector Lines Background Graphic --}}
        <div class="absolute right-0 top-0 bottom-0 w-full md:w-2/3 pointer-events-none opacity-30 overflow-hidden flex items-center justify-end">
            <svg viewBox="0 0 800 400" class="w-full h-full object-cover" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 350C250 200 400 380 600 220C750 80 850 150 900 100" stroke="#c3122e" stroke-width="1.5" opacity="0.6"/>
                <path d="M150 370C300 220 450 400 650 240C800 100 900 170 950 120" stroke="#ff4d6d" stroke-width="1.2" opacity="0.4"/>
                <path d="M50 330C200 180 350 360 550 200C700 60 800 130 850 80" stroke="#c3122e" stroke-width="1.8" opacity="0.5"/>
                <path d="M200 390C350 240 500 420 700 260C850 120 950 190 1000 140" stroke="#e01e37" stroke-width="1" opacity="0.3"/>
                <path d="M0 310C150 160 300 340 500 180C650 40 750 110 800 60" stroke="#ff758f" stroke-width="0.8" opacity="0.4"/>
            </svg>
        </div>

        {{-- Main Banner Flex Grid --}}
        <div class="relative p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8 z-10">
            
            {{-- LEFT COLUMN: Identity, Greeting & CTAs --}}
            <div class="space-y-4 max-w-xl">
                {{-- Top Badge Pill --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-rose-300" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <svg class="w-3 h-3 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>COLLABORATOR WORKSPACE</span>
                    </span>
                    <span class="text-xs font-semibold text-slate-400">
                        {{ auth()->user()->subsidiary->name ?? 'George Steuart Optimize' }}
                    </span>
                </div>

                {{-- Greeting & Welcome --}}
                <div>
                    <p class="text-xs font-bold text-[#f87171] uppercase tracking-wider mb-1">
                        Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                    </p>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Welcome back, <span class="text-[#f87171]">{{ auth()->user()->name }}</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 font-medium mt-2 leading-relaxed">
                        Track WBS tasks, submit approvals &amp; collaborate with your Project Manager.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 flex-wrap pt-2">
                    <a href="{{ route('approvals.index') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #a00e24 100%);">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Submit Approval</span>
                    </a>
                    
                    <a href="{{ route('calendar.index') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white transition-all flex items-center gap-2 cursor-pointer hover:bg-white/15" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Calendar</span>
                    </a>

                    <a href="{{ route('my-tasks.index') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white transition-all flex items-center gap-2 cursor-pointer hover:bg-white/15" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>My Tasks</span>
                    </a>
                </div>
            </div>

            {{-- RIGHT COLUMN: 2x2 Metric Stat Cards Grid --}}
            <div class="grid grid-cols-2 gap-2 sm:gap-4 w-full lg:w-auto min-w-0 sm:min-w-[340px]">
                {{-- Metric 1: Projects --}}
                <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3.5 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); backdrop-filter: blur(10px);">
                    <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(195,18,46,0.2); border: 1px solid rgba(195,18,46,0.35); color: #f87171;">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-base sm:text-2xl font-black text-white tracking-tight leading-none font-mono">{{ $attachedProjects->count() }}</div>
                        <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-300 mt-0.5 sm:mt-1 truncate">PROJECTS</div>
                        <div class="hidden sm:block text-[9px] text-slate-400 font-medium">Active</div>
                    </div>
                </div>

                {{-- Metric 2: Tasks --}}
                <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3.5 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); backdrop-filter: blur(10px);">
                    <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.35); color: #fbbf24;">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-base sm:text-2xl font-black text-white tracking-tight leading-none font-mono">{{ $totalCount }}</div>
                        <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-300 mt-0.5 sm:mt-1 truncate">TASKS</div>
                        <div class="hidden sm:block text-[9px] text-slate-400 font-medium">{{ $dueToday->count() }} Due today</div>
                    </div>
                </div>

                {{-- Metric 3: In Progress --}}
                <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3.5 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); backdrop-filter: blur(10px);">
                    <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.35); color: #60a5fa;">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-base sm:text-2xl font-black text-white tracking-tight leading-none font-mono">{{ $inProgress->count() }}</div>
                        <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-300 mt-0.5 sm:mt-1 truncate">IN PROGRESS</div>
                        <div class="hidden sm:block text-[9px] text-slate-400 font-medium">Executing</div>
                    </div>
                </div>

                {{-- Metric 4: Completion --}}
                <div class="p-2.5 sm:p-4 rounded-xl sm:rounded-2xl flex items-center gap-2.5 sm:gap-3.5 transition-all" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.09); backdrop-filter: blur(10px);">
                    <div class="w-7 h-7 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.35); color: #34d399;">
                        <svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-base sm:text-2xl font-black text-white tracking-tight leading-none font-mono">{{ $completionPct }}%</div>
                        <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-slate-300 mt-0.5 sm:mt-1 truncate">COMPLETION</div>
                        <div class="hidden sm:block text-[9px] text-slate-400 font-medium">Overall</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════ 2. COLLABORATING PROJECTS SECTION ═══════════════════ --}}
    <div class="space-y-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-[#c3122e] flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Collaborating Projects</h2>
                    <p class="text-xs text-slate-500 font-medium">Projects officially assigned to you by a Project Manager</p>
                </div>
            </div>

            <a href="{{ route('projects.index') }}" class="text-xs font-bold text-[#c3122e] hover:text-[#a00e24] transition-colors flex items-center gap-1 group">
                <span>View All</span>
                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Projects Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($attachedProjects as $prj)
                @php
                    $pct = (int) $prj->overall_progress;
                    $pm = $prj->projectManager;
                    $hs = match($prj->health->value ?? 'on_track') {
                        'on_track' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'ON TRACK'],
                        'at_risk'  => ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'AT RISK'],
                        'delayed'  => ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'text' => 'text-rose-700', 'dot' => 'bg-rose-500', 'label' => 'DELAYED'],
                        default    => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'text' => 'text-slate-600', 'dot' => 'bg-slate-400', 'label' => 'ON TRACK'],
                    };
                @endphp
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                    
                    {{-- Card Top Row: Code Badge & Health Pill --}}
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="shadow-sm flex-shrink-0" style="background: #fff1f2; color: #c3122e; border: 1px solid #ffe4e6; font-family: monospace; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; letter-spacing: 0.5px;">
                                {{ $prj->code ?? 'PRJ' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold {{ $hs['bg'] }} {{ $hs['text'] }} border {{ $hs['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $hs['dot'] }}"></span>
                                <span>{{ $hs['label'] }}</span>
                            </span>
                        </div>

                        {{-- Project Name --}}
                        <h3 class="text-base font-extrabold text-slate-900 mt-3 truncate" title="{{ $prj->name }}">
                            {{ $prj->name }}
                        </h3>

                        {{-- PM Info --}}
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white flex items-center justify-center font-bold text-[9px] shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($pm->name ?? 'P', 0, 1)) }}
                            </div>
                            <span class="text-xs text-slate-500 font-medium truncate">
                                PM: <strong class="text-slate-800 font-bold">{{ $pm->name ?? 'Unassigned' }}</strong>
                            </span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="space-y-1 pt-1">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="font-bold uppercase tracking-wider text-slate-400">PROGRESS</span>
                            <span class="font-extrabold text-xs {{ $pct >= 100 ? 'text-emerald-600' : 'text-[#c3122e]' }}">{{ $pct }}%</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $pct >= 100 ? 'bg-emerald-500' : ($pct > 0 ? 'bg-[#c3122e]' : 'bg-transparent') }}" style="width: {{ $pct }}%;"></div>
                        </div>
                    </div>

                    {{-- Bottom Row: Deadline & Open Button --}}
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">DEADLINE</span>
                            <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $prj->deadline ? $prj->deadline->format('d M Y') : 'Not Set' }}</span>
                            </div>
                        </div>

                        <a href="{{ route('projects.show', $prj->id) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-[#c3122e] bg-white hover:bg-rose-50 border border-rose-200 shadow-sm transition-all flex items-center gap-1 group">
                            <span>Open</span>
                            <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-100 p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800">No Active Collaborations</h3>
                    <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">When a Project Manager attaches you to a project, it will automatically appear here.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════ 3. ASSIGNED TASKS WORKSPACE SECTION ═══════════════════ --}}
    <div class="space-y-4">
        {{-- Section Header --}}
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-[#c3122e] flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Assigned Tasks Workspace</h2>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-[#c3122e] border border-rose-100">
                            {{ $myTasks->count() }} Tasks
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Update completion percentages, statuses, and log sub-task deliverables</p>
                </div>
            </div>

            <a href="{{ route('my-tasks.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-sm transition-all flex items-center gap-1.5 group">
                <span>Full Task Board</span>
                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform group-hover:translate-x-0.5 group-hover:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        {{-- Tasks List --}}
        <div class="space-y-3">
            @forelse($myTasks as $task)
                @php
                    $isOverdue = $task->end_date && $task->end_date->isPast() && $task->status->value !== 'completed';
                    $statusStyles = match($task->status->value ?? 'not_started') {
                        'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'blocked'      => 'bg-amber-50 text-amber-700 border-amber-200',
                        'in_progress'  => 'bg-blue-50 text-blue-700 border-blue-200',
                        'under_review' => 'bg-purple-50 text-purple-700 border-purple-200',
                        default        => 'bg-slate-50 text-slate-700 border-slate-200',
                    };
                @endphp
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm space-y-3">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        {{-- Left Side: Code, Title, Badges & Meta --}}
                        <div class="flex items-start gap-3 min-w-0">
                            <span class="px-2 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-700 font-mono text-xs font-bold flex-shrink-0 mt-0.5">
                                {{ $task->wbs_code ?? '1.1' }}
                            </span>
                            
                            <div class="min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-sm font-extrabold text-slate-900 leading-snug">
                                        {{ $task->title }}
                                    </h4>
                                    
                                    {{-- Status Badge --}}
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border {{ $statusStyles }} flex items-center gap-1">
                                        {{ $task->status->label() }}
                                    </span>

                                    {{-- Overdue Badge if applicable --}}
                                    @if($isOverdue)
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 flex items-center gap-1">
                                            ⚠ Overdue
                                        </span>
                                    @endif
                                </div>

                                {{-- Meta Row --}}
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium flex-wrap">
                                    @if($task->end_date)
                                        <span class="flex items-center gap-1 font-semibold {{ $isOverdue ? 'text-rose-600' : 'text-slate-600' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span>{{ $task->end_date->format('d M Y') }}</span>
                                            @if($isOverdue)
                                                <span class="text-rose-500 font-bold">• {{ $task->end_date->diffForHumans() }}</span>
                                            @endif
                                        </span>
                                    @endif
                                    <span>|</span>
                                    <span>PM: <strong class="text-slate-700 font-bold">{{ $task->project->projectManager->name ?? 'Unassigned' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Controls (Status Select, + Sub-Task, Range Slider) --}}
                        <div class="flex items-center gap-3 flex-wrap lg:justify-end flex-shrink-0">
                            
                            {{-- Status Dropdown Pill --}}
                            <div class="relative">
                                <select 
                                    wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-bold py-1.5 pl-3 pr-7 rounded-xl border border-amber-200 bg-amber-50/70 text-amber-900 focus:ring-2 focus:ring-amber-400/20 outline-none transition-all appearance-none cursor-pointer"
                                >
                                    @foreach(\App\Enums\WbsStatus::cases() as $st)
                                        <option value="{{ $st->value }}" @selected($task->status->value === $st->value)>{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-amber-700">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            {{-- Sub-Task Button --}}
                            <button 
                                wire:click="openSubTaskModal({{ $task->id }})"
                                type="button" 
                                class="px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50/80 hover:bg-indigo-100 border border-indigo-200 transition-all flex items-center gap-1 cursor-pointer"
                            >
                                <span>+ Sub-Task</span>
                            </button>

                            {{-- Progress Range Slider --}}
                            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200/70 rounded-xl px-3 py-1.5 shadow-2xs">
                                <input 
                                    type="range" 
                                    min="0" 
                                    max="100" 
                                    step="5"
                                    value="{{ $task->progress }}"
                                    wire:change="updateTaskProgress({{ $task->id }}, $event.target.value)"
                                    class="w-20 accent-[#c3122e] cursor-pointer"
                                >
                                <span class="text-xs font-mono font-bold text-slate-800 min-w-[32px] text-right">
                                    {{ $task->progress }}%
                                </span>
                            </div>

                        </div>
                    </div>

                    {{-- Nested Sub-tasks if existing --}}
                    @if($task->children && $task->children->count() > 0)
                        <div class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                <span>Sub-Tasks ({{ $task->children->count() }})</span>
                                @php $doneCount = $task->children->filter(fn($s) => $s->status->value === 'completed')->count(); @endphp
                                <span class="text-emerald-600 font-extrabold">{{ $doneCount }}/{{ $task->children->count() }} Completed</span>
                            </div>

                            <div class="space-y-1.5 pl-3 border-l-2 border-slate-100">
                                @foreach($task->children as $sub)
                                    <div class="flex items-center justify-between gap-3 p-2 rounded-xl bg-slate-50/80 border border-slate-100">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-100">
                                                {{ $sub->wbs_code }}
                                            </span>
                                            <span class="text-xs font-semibold text-slate-800 truncate {{ $sub->status->value === 'completed' ? 'line-through text-slate-400' : '' }}">
                                                {{ $sub->title }}
                                            </span>
                                        </div>

                                        <div class="relative flex-shrink-0">
                                            <select 
                                                wire:change="updateTaskStatus({{ $sub->id }}, $event.target.value)"
                                                class="text-[10px] font-bold py-1 pl-2 pr-6 rounded-lg border border-slate-200 bg-white text-slate-700 outline-none appearance-none cursor-pointer"
                                            >
                                                @foreach(\App\Enums\WbsStatus::cases() as $st)
                                                    <option value="{{ $st->value }}" @selected($sub->status->value === $st->value)>{{ $st->label() }}</option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1.5 text-slate-400">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="font-extrabold text-sm text-slate-800">No Assigned Tasks</h3>
                    <p class="text-xs text-slate-400 font-medium max-w-sm mx-auto">When your Project Manager assigns WBS tasks to you, they will appear here with interactive progress sliders.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ═══════════════════ SUB-TASK MODAL ═══════════════════ --}}
    @if($showSubTaskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" wire:click="$set('showSubTaskModal', false)"></div>

            <div class="relative bg-white rounded-3xl max-w-md w-full p-0 overflow-hidden shadow-2xl z-10 border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
                <!-- Modal Header -->
                <div class="px-6 py-4 flex items-center justify-between border-b border-rose-900/20" style="background: linear-gradient(135deg, #7a0b1d 0%, #c3122e 100%); color: #ffffff;">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base shadow-inner" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            📋
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white leading-tight">Create Sub-Task</h3>
                            <p style="color: #fecdd3; font-size: 10px; font-weight: 500; margin-top: 1px;">Break down your assigned task into sub-deliverables</p>
                        </div>
                    </div>
                    <button 
                        wire:click="$set('showSubTaskModal', false)" 
                        type="button" 
                        class="p-1.5 rounded-xl text-white hover:bg-white/20 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createSubTask" class="p-6 space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Sub-Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="subTaskTitle" required placeholder="e.g. Prepare API payload schema" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800">
                        @error('subTaskTitle') <span class="text-rose-500 text-[10px] font-bold block mt-1">⚠ {{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700">Description</label>
                        <textarea wire:model="subTaskDescription" rows="2" placeholder="Details or notes..." class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none transition-all text-slate-800"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Start Date</label>
                            <input type="date" wire:model="subTaskStartDate" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] outline-none text-slate-800">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">End Date</label>
                            <input type="date" wire:model="subTaskEndDate" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] outline-none text-slate-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Priority</label>
                            <select wire:model="subTaskPriority" class="w-full text-xs font-bold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] outline-none text-slate-800">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">Est. Hours</label>
                            <input type="number" step="0.5" wire:model="subTaskEstimatedHours" placeholder="e.g. 4.5" class="w-full text-xs font-semibold py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] outline-none text-slate-800">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSubTaskModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm transition-all cursor-pointer">
                            Save Sub-Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
