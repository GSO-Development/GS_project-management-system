<div>
    <!-- ===== PROJECT HEADER HERO CARD ===== -->
    <div class="relative rounded-3xl overflow-hidden mb-7 shadow-2xl border border-slate-800"
         style="background: #0f0407;">

        <!-- Executive Boardroom Image Overlay (100% Crystal Clear Right Side) -->
        <div class="absolute top-0 right-0 bottom-0 w-full lg:w-2/3 z-0 bg-cover bg-right bg-no-repeat opacity-100"
             style="background-image: url('{{ asset('images/george_steuart_executive_boardroom.png') }}');">
        </div>

        <!-- Left Dark Burgundy Gradient Mask (Soft gradient edge that leaves right side 100% bright & clear) -->
        <div class="absolute inset-0 z-0 pointer-events-none"
             style="background: linear-gradient(90deg, #120306 0%, #1a0509 35%, rgba(22, 4, 8, 0.7) 52%, rgba(0, 0, 0, 0) 72%);">
        </div>

        <!-- Ambient Glow FX -->
        <div class="absolute -top-24 -left-24 w-80 h-80 bg-[#c3122e]/30 rounded-full blur-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 p-5 sm:p-8 md:p-10 pt-8 sm:pt-11 md:pt-14">
            <!-- Top Controls Row: Code Badge + Status + Health + Priority + All Projects -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-1 rounded-xl text-xs font-mono font-black border border-[#e8556a]/50 bg-[#c3122e]/40 text-white shadow-md">
                        {{ $project->code }}
                    </span>
                    
                    <!-- Interactive Project Status Selector -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
                        <select
                            wire:change="updateProjectStatus($event.target.value)"
                            class="px-2.5 py-1 rounded-xl text-xs font-extrabold border bg-black/75 border-white/20 text-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e] shadow-md transition-all"
                            title="Update Project Status"
                        >
                            @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                <option value="{{ $st->value }}" @selected($project->status->value === $st->value) class="bg-[#1a0a0d] text-white font-bold">
                                    Status: {{ $st->label() }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold border bg-black/50 border-white/20 text-slate-200 shadow-md">
                            Status: {{ $project->status->label() }}
                        </span>
                    @endif

                    <!-- Interactive Project Health Selector -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
                        <select
                            wire:change="updateProjectHealth($event.target.value)"
                            class="px-2.5 py-1 rounded-xl text-xs font-extrabold border bg-black/75 border-white/20 text-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e] shadow-md transition-all"
                            title="Update Project Health"
                        >
                            @foreach(\App\Enums\ProjectHealth::cases() as $hl)
                                <option value="{{ $hl->value }}" @selected($project->health->value === $hl->value) class="bg-[#1a0a0d] text-white font-bold">
                                    Health: {{ $hl->label() }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold border bg-black/50 border-white/20 text-slate-200 shadow-md">
                            Health: {{ $project->health->label() }}
                        </span>
                    @endif

                    <span class="px-2.5 py-1 rounded-xl text-xs font-black border uppercase tracking-wider shadow-md
                        {{ match($project->priority->value) {
                            'critical' => 'text-red-200 border-red-400/50 bg-red-600/30',
                            'high' => 'text-rose-200 border-rose-400/50 bg-rose-600/30',
                            'medium' => 'text-amber-200 border-amber-400/50 bg-amber-600/30',
                            'low' => 'text-emerald-200 border-emerald-400/50 bg-emerald-600/30',
                            default => 'text-slate-200 border-slate-400/40 bg-white/10',
                        } }}">{{ ucfirst($project->priority->value) }} Priority</span>
                </div>

                @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                    <a href="{{ route('projects.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-white/90 hover:text-white border border-white/20 hover:bg-white/10 backdrop-blur-md transition-all self-start shadow-md">
                        <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        <span>← All Projects</span>
                    </a>
                @endif
            </div>

            <!-- Title & Description Section -->
            <div class="max-w-3xl mb-8 pt-2">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight drop-shadow-md leading-tight" style="font-family: Georgia, 'Times New Roman', serif;">
                        {{ $project->name }}
                    </h1>
                    @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                        <button wire:click="openEditProjectModal" type="button" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-amber-300 hover:text-white bg-amber-500/20 hover:bg-amber-500/40 border border-amber-400/30 backdrop-blur-md transition-all flex items-center gap-1.5 cursor-pointer shadow-md" title="Edit Project Details">
                            <span>✏️ Edit Details</span>
                        </button>
                    @endif
                </div>

                @if($project->description)
                    @php
                        $cleanDesc = preg_replace('/^(Project Scope & Objectives|Project Description|\s*)+/i', '', strip_tags($project->description));
                        $cleanDesc = trim(preg_replace('/\s+/', ' ', $cleanDesc));
                    @endphp
                    <p class="text-xs sm:text-sm text-slate-200/90 leading-relaxed font-medium drop-shadow-xs line-clamp-3">
                        {{ Str::words($cleanDesc, 35, '...') }}
                    </p>
                @else
                    @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                        <button wire:click="openEditProjectModal" type="button" class="text-xs font-bold text-amber-300 hover:text-amber-200 underline flex items-center gap-1 mt-1 cursor-pointer">
                            <span>+ Add Project Description &amp; Scope</span>
                        </button>
                    @endif
                @endif
            </div>

            <!-- 4 High-End Executive Dark Glass Metrics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Project Manager Card -->
                <div class="p-4 rounded-2xl border transition-all hover:border-white/30 shadow-md" style="background: rgba(18, 5, 8, 0.75); border-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(16px);">
                    <div class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-2">PROJECT MANAGER</div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#c3122e] to-[#7a091c] text-white text-xs font-black flex items-center justify-center flex-shrink-0 shadow-md border border-white/20">
                            {{ strtoupper(substr($project->projectManager->name ?? 'M', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-black text-white truncate leading-tight">{{ $project->projectManager->name ?? 'Unassigned' }}</p>
                            <p class="text-[10px] text-slate-400 font-medium truncate mt-0.5">{{ $project->projectManager->email ?? 'Project Lead' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Subsidiary Entity Card -->
                <div class="p-4 rounded-2xl border transition-all hover:border-white/30 shadow-md" style="background: rgba(18, 5, 8, 0.75); border-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(16px);">
                    <div class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-2">SUBSIDIARY</div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#c3122e] to-[#800a1d] text-white text-xs font-black flex items-center justify-center flex-shrink-0 shadow-md border border-white/10">
                            {{ strtoupper(substr($project->subsidiary->code ?? 'GS', 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-black text-[#fbd5da] truncate leading-tight">{{ $project->subsidiary->name ?? 'George Steuart Group' }}</p>
                            <p class="text-[10px] text-slate-400 font-mono font-bold mt-0.5">{{ $project->subsidiary->code ?? 'GST' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Target Deadline Card -->
                <div class="p-4 rounded-2xl border transition-all hover:border-white/30 shadow-md" style="background: rgba(18, 5, 8, 0.75); border-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(16px);">
                    <div class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-2">DEADLINE</div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-mono font-black text-white leading-tight">{{ $project->deadline ? $project->deadline->format('M d, Y') : 'Not Set' }}</p>
                            @if($project->deadline && $project->deadline->isPast())
                                <span class="text-[10px] text-rose-300 font-black mt-0.5 block">⚠️ Overdue</span>
                            @elseif($project->deadline)
                                <span class="text-[10px] text-amber-300 font-bold mt-0.5 block">{{ $project->deadline->diffForHumans() }}</span>
                            @endif
                        </div>
                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                        <button wire:click="openTimelineModal" type="button" class="w-8 h-8 rounded-xl bg-amber-500/20 hover:bg-amber-500/40 text-amber-300 flex items-center justify-center border border-amber-400/30 flex-shrink-0 transition-all cursor-pointer" title="Set Project Deadline">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </button>
                        @else
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center border border-amber-400/30 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Overall Progress Card -->
                <div class="p-4 rounded-2xl border transition-all hover:border-white/30 shadow-md" style="background: rgba(18, 5, 8, 0.75); border-color: rgba(255, 255, 255, 0.15); backdrop-filter: blur(16px);">
                    <div class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-2">OVERALL PROGRESS</div>
                    <div>
                        <div class="flex items-center gap-3 mb-1.5">
                            <div class="flex-1 h-2 bg-black/60 rounded-full overflow-hidden border border-white/10">
                                <div class="h-full bg-gradient-to-r from-[#c3122e] via-amber-400 to-emerald-400 rounded-full transition-all duration-500 shadow-md" style="width: {{ $project->overall_progress }}%"></div>
                            </div>
                            <p class="text-sm font-black text-emerald-400 flex-shrink-0">{{ $project->overall_progress }}%</p>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold">
                            {{ $project->wbsItems->where('status', \App\Enums\WbsStatus::COMPLETED)->count() }} / {{ $project->wbsItems->count() }} Tasks Completed
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs Bar (Flex-wrap ensures ALL 8 tabs including Approvals are 100% visible on all screens) -->
        <div class="relative z-10 flex flex-wrap items-center gap-2 px-4 sm:px-6 md:px-9 pb-4 pt-4 border-t border-white/10 bg-black/35 backdrop-blur-md">
            @foreach([
                'overview'  => ['Overview', 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'],
                'wbs'       => ['Tasks List', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                'kanban'    => ['Kanban', 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7'],
                'updates'   => ['Status Updates', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                'risks'     => ['Risks & Blockers', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                'approvals' => ['Approvals', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $tabKey => [$tabLabel, $tabIcon])
                @php
                    $pendingCount = $tabKey === 'approvals' ? $project->approvalRequests->where('status', \App\Enums\ApprovalStatus::PENDING)->count() : 0;
                @endphp
                <button
                    wire:click="$set('activeTab', '{{ $tabKey }}')"
                    class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer {{ $activeTab === $tabKey ? 'bg-white text-[#8b0d1f] shadow-lg font-black scale-105' : 'text-slate-200 hover:bg-white/15 hover:text-white' }}"
                >
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcon }}"/></svg>
                    <span>{{ $tabLabel }}</span>
                    @if($pendingCount > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-[#c3122e] text-white shadow-xs">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- ===== TAB CONTENTS ===== -->

    <!-- 1. OVERVIEW TAB -->
    @if($activeTab === 'overview')
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- LEFT: Main Content -->
            <div class="xl:col-span-2 space-y-6">

                <!-- Financial KPI Cards -->
                <div class="card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Financial Summary & Hours</h3>
                        </div>
                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                            <button wire:click="openFinancialsModal" class="px-2.5 py-1 rounded-lg text-xs font-bold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#fceaea] border border-[#faeaea] transition-colors flex items-center gap-1 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Update Metrics
                            </button>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-[#fdf4f4] to-[#fdf4f4] border border-[#faeaea]">
                            <span class="text-[10px] text-[#c3122e] font-bold uppercase tracking-wide">Est. Budget</span>
                            <p class="text-xl font-extrabold text-slate-900 mt-1">Rs. {{ number_format($project->estimated_budget ?? 0, 0) }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gradient-to-br from-rose-50 to-rose-50/50 border border-rose-100">
                            <span class="text-[10px] text-rose-500 font-bold uppercase tracking-wide">Actual Cost</span>
                            <p class="text-xl font-extrabold text-[#c3122e] mt-1">Rs. {{ number_format($project->actual_cost ?? 0, 0) }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-amber-50/50 border border-amber-100">
                            <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wide">Est. Hours</span>
                            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($project->estimated_hours ?? 0, 0) }}<span class="text-sm text-slate-500 font-semibold">h</span></p>
                        </div>
                        <div class="p-4 rounded-xl bg-gradient-to-br from-[#fdf8e8] to-[#fdf8e8]/50 border border-[#fdf0c8]">
                            <span class="text-[10px] text-[#b8860b] font-bold uppercase tracking-wide">Actual Hours</span>
                            <p class="text-xl font-extrabold text-[#8a6508] mt-1">{{ number_format($project->actual_hours ?? 0, 0) }}<span class="text-sm text-[#d4a017] font-semibold">h</span></p>
                        </div>
                    </div>

                    <!-- Budget Progress Bar -->
                    @if(($project->estimated_budget ?? 0) > 0)
                        @php $budgetUsed = min(100, round(($project->actual_cost / $project->estimated_budget) * 100)); @endphp
                        <div class="mt-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-slate-700">Budget Utilization</span>
                                <span class="text-xs font-bold {{ $budgetUsed > 85 ? 'text-rose-600' : 'text-slate-900' }}">{{ $budgetUsed }}%</span>
                            </div>
                            <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all {{ $budgetUsed > 85 ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-[#c3122e] to-[#8b0d1f]' }}" style="width: {{ $budgetUsed }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Project Description (Full) -->
                @if($project->description)
                <div class="card">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm">Project Scope & Objectives</h3>
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed space-y-2 max-h-48 overflow-y-auto pr-2">
                        @foreach(explode("\n", $project->description) as $line)
                            @if(trim($line))
                                @if(str_starts_with(trim($line), '##'))
                                    <p class="font-bold text-slate-900 text-sm">{{ ltrim(trim($line), '#') }}</p>
                                @elseif(str_starts_with(trim($line), '#'))
                                    <p class="font-semibold text-slate-800">{{ ltrim(trim($line), '#') }}</p>
                                @elseif(str_starts_with(trim($line), '*'))
                                    <p class="flex items-start gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-[#c3122e] mt-1.5 flex-shrink-0"></span><span>{{ ltrim(trim($line), '* ') }}</span></p>
                                @else
                                    <p>{{ $line }}</p>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Status Updates Timeline -->
                <div class="card">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm">Latest Status Updates</h3>
                        </div>
                        <button wire:click="$set('activeTab', 'updates')" class="text-xs font-semibold text-[#c3122e] hover:text-[#a00e24]">View All →</button>
                    </div>
                    <div class="space-y-3">
                        @forelse($project->statusUpdates->take(3) as $update)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <span class="font-bold text-slate-900 text-xs leading-tight">{{ $update->title }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono flex-shrink-0 mt-0.5">{{ $update->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ Str::limit($update->summary, 120) }}</p>
                                @if($update->work_completed)
                                    <div class="text-[11px] text-emerald-800 bg-emerald-50 px-2.5 py-1.5 rounded-lg mt-2 border border-emerald-200/80 flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $update->work_completed }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-xs font-medium">No status updates yet</p>
                                <button wire:click="$set('activeTab', 'updates')" class="mt-2 text-xs text-[#c3122e] font-semibold hover:underline">Post First Update →</button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- RIGHT: Sidebar -->
            <div class="space-y-6">
                <!-- Progress Donut Card -->
                <div class="card text-center">
                    <h3 class="font-bold text-slate-900 text-sm mb-5">Overall Progress</h3>
                    <div class="flex items-center justify-center">
                        <div class="relative w-32 h-32">
                            @php
                                $progress = $project->overall_progress ?? 0;
                                $circumference = 2 * M_PI * 54;
                                $dashOffset = $circumference - ($progress / 100) * $circumference;
                            @endphp
                            <svg class="w-32 h-32 -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#e2e8f0" stroke-width="10"/>
                                <circle cx="60" cy="60" r="54" fill="none"
                                    stroke="url(#progressGrad)" stroke-width="10"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $dashOffset }}"
                                    style="transition: stroke-dashoffset 1s ease-in-out;"/>
                                <defs>
                                    <linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#c3122e"/>
                                        <stop offset="100%" stop-color="#10b981"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-extrabold text-slate-900">{{ $progress }}%</span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                            <div class="text-[10px] text-slate-500 font-semibold uppercase mb-1">Start</div>
                            <div class="text-xs font-bold text-slate-900 font-mono">{{ $project->start_date ? $project->start_date->format('M d, Y') : '—' }}</div>
                        </div>
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center">
                            <div class="text-[10px] text-slate-500 font-semibold uppercase mb-1">Deadline</div>
                            <div class="text-xs font-bold {{ ($project->deadline && $project->deadline->isPast()) ? 'text-rose-600' : 'text-slate-900' }} font-mono">{{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Project Collaborators Card -->
                <div class="card">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Project Participants</h3>
                            <p class="text-[10px] text-slate-500 font-medium">Team members attached to this project</p>
                        </div>
                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                            <button wire:click="openCollaboratorsModal" type="button" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-[#c3122e] bg-[#fdf4f4] hover:bg-[#faeaea] border border-[#faeaea] shadow-2xs transition-all flex items-center gap-1 cursor-pointer">
                                <span>+ Add Participants</span>
                            </button>
                        @endif
                    </div>

                    <div class="space-y-2.5">
                        <!-- Project Owner / Manager -->
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-[#fdf4f4]/80 border border-[#faeaea]">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white font-black text-sm flex items-center justify-center flex-shrink-0 shadow-xs border border-white/20">
                                {{ strtoupper(substr($project->projectManager->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-extrabold text-slate-900 truncate leading-tight">{{ $project->projectManager->name ?? 'Unassigned' }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-[#faeaea] text-[#a00e24] mt-0.5">
                                    🛡️ Project Manager (Lead)
                                </span>
                            </div>
                        </div>

                        @php
                            $additionalCollaborators = $project->members->reject(fn($m) => $m->id === $project->project_manager_id);
                        @endphp

                        @forelse($additionalCollaborators as $member)
                            <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 hover:bg-slate-100/70 transition-all">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-extrabold text-slate-900 truncate leading-tight">{{ $member->name }}</p>
                                        <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $member->email }}</p>
                                    </div>
                                </div>

                                @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="removeCollaborator({{ $member->id }})" wire:confirm="Remove {{ $member->name }} from this project?" type="button" class="text-slate-400 hover:text-rose-600 p-1 text-xs font-bold transition-colors cursor-pointer" title="Remove Collaborator">
                                        ✕
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="p-4 rounded-2xl bg-slate-50/80 border border-dashed border-slate-200 text-center space-y-1.5">
                                <p class="text-xs font-extrabold text-slate-700">No additional collaborators attached</p>
                                <p class="text-[11px] text-slate-500">Click <strong>+ Add Collaborators</strong> above to attach team members to this project.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card">
                    <h3 class="font-bold text-slate-900 text-sm mb-4">Quick Navigation</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['wbs', 'WBS Plan', 'text-[#c3122e]', 'bg-[#fdf4f4] border-[#faeaea]'],
                            ['kanban', 'Kanban', 'text-[#c3122e]', 'bg-[#fdf4f4] border-[#faeaea]'],
                            ['updates', 'Updates', 'text-[#b8860b]', 'bg-[#fdf8e8] border-[#fdf0c8]'],
                            ['risks', 'Risks', 'text-rose-600', 'bg-rose-50 border-rose-100'],
                        ] as [$tab, $label, $textColor, $bgColor])
                            <button wire:click="$set('activeTab', '{{ $tab }}')" class="p-3 rounded-xl border text-xs font-bold {{ $textColor }} {{ $bgColor }} hover:opacity-80 transition-opacity text-center">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. WBS PLAN TAB -->
    @if($activeTab === 'wbs')
        <livewire:wbs-tree :project="$project" />
    @endif

    <!-- 3. KANBAN TAB -->
    @if($activeTab === 'kanban')
        <livewire:kanban-board :project="$project" />
    @endif

    <!-- 5. STATUS UPDATES TAB -->
    @if($activeTab === 'updates')
        <div class="space-y-6">
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
            <div class="card">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Publish New Status Update</h3>
                </div>
                <form wire:submit="publishStatusUpdate" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Update Title</label>
                        <input type="text" wire:model="statusTitle" placeholder="e.g. Sprint 4 Completion Report" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Summary</label>
                        <textarea wire:model="statusSummary" rows="3" placeholder="Describe current progress and status..." class="form-input"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Work Completed</label>
                            <textarea wire:model="workCompleted" rows="2" placeholder="Achievements this period..." class="form-input"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Next Steps</label>
                            <textarea wire:model="nextSteps" rows="2" placeholder="Planned activities..." class="form-input"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Publish Status Update</button>
                </form>
            </div>
            @endif

            <div class="card">
                <h3 class="font-bold text-slate-900 text-sm mb-4">Status Update Timeline</h3>
                <div class="space-y-4">
                    @forelse($project->statusUpdates as $u)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-slate-900 text-xs">{{ $u->title }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $u->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mb-2">{{ $u->summary }}</p>
                            @if($u->work_completed)
                                <p class="text-[11px] text-emerald-700 bg-emerald-50 p-2 rounded-lg border border-emerald-100"><strong>✓ Completed:</strong> {{ $u->work_completed }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs">No status updates recorded yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- 6. DOCUMENTS TAB -->
    @if($activeTab === 'documents')
        <div class="space-y-6">
            <div class="card">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Upload Document</h3>
                </div>
                <form wire:submit="uploadDocument" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">File</label>
                        <input type="file" wire:model="documentFile" class="form-input text-xs">
                        @error('documentFile') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description (optional)</label>
                        <input type="text" wire:model="docDescription" placeholder="Brief description of this document..." class="form-input">
                    </div>
                    <button type="submit" class="btn-primary">Upload to Secured Storage</button>
                </form>
            </div>

            <div class="card">
                <h3 class="font-bold text-slate-900 text-sm mb-4">Project Files</h3>
                <div class="space-y-3">
                    @forelse($project->documents as $doc)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-3">
                                <div wire:click="openPreview({{ $doc->id }})" class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 cursor-pointer hover:scale-105 transition-transform" title="Click to View Document">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <button wire:click="openPreview({{ $doc->id }})" class="text-xs font-bold text-slate-900 hover:text-[#c3122e] hover:underline cursor-pointer block text-left">
                                        {{ $doc->original_name }}
                                    </button>
                                    <p class="text-[10px] text-slate-500">Uploaded by {{ $doc->uploader->name ?? 'User' }} • {{ round($doc->file_size/1024, 1) }} KB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="openPreview({{ $doc->id }})" class="btn-secondary btn-sm text-xs flex items-center gap-1 cursor-pointer" title="View Document without downloading">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </button>
                                <a href="{{ route('documents.download', $doc) }}" class="btn-primary btn-sm text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                                @if($doc->uploaded_by === auth()->id() || auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="deleteDocument({{ $doc->id }})"
                                            wire:confirm="Are you sure you want to delete this document?"
                                            class="p-2 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Delete Document">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <p class="text-xs">No documents uploaded yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- 7. DISCUSSION TAB -->
    @if($activeTab === 'comments')
        <div class="card">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Project Discussion Thread</h3>
            </div>

            <form wire:submit="postComment" class="mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <textarea wire:model="commentContent" rows="2" placeholder="Write a comment, question, or update..." class="form-input mb-2 text-xs"></textarea>
                        <button type="submit" class="btn-primary btn-sm text-xs">Post Comment</button>
                    </div>
                </div>
            </form>

            <div class="space-y-4">
                @forelse($project->comments as $c)
                    <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-600 to-slate-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($c->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-900 text-xs">{{ $c->user->name ?? 'User' }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $c->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No comments yet — start the discussion!</div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- 8. RISKS & BLOCKERS TAB -->
    @if($activeTab === 'risks')
        @php
            $wbsItemIds = $project->wbsItems->pluck('id');
            $projectBlockers = \App\Models\TaskBlocker::whereIn('wbs_item_id', $wbsItemIds)
                ->with(['wbsItem', 'reporter', 'resolver'])
                ->latest()
                ->get();
            $openBlockersCount = $projectBlockers->where('status', '!=', 'resolved')->count();
        @endphp

        <div class="space-y-7">
            <!-- 1. Active Task Blockers Summary Banner & List -->
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-black text-sm shadow-2xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">Active Task Blockers & Execution Issues</h3>
                            <p class="text-xs text-slate-500 font-medium">Real-time execution blockers reported by team members on specific WBS tasks</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $openBlockersCount > 0 ? 'bg-rose-100 text-rose-900 border border-rose-300' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                        {{ $openBlockersCount }} Open Blocker(s)
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($projectBlockers as $b)
                        @php
                            $sevColors = [
                                'low' => 'bg-slate-100 text-slate-700 border-slate-300',
                                'medium' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
                                'high' => 'bg-rose-50 text-rose-800 border-rose-200 font-bold',
                                'critical' => 'bg-red-100 text-red-900 border-red-300 font-black',
                            ];
                        @endphp
                        <div class="p-4 rounded-2xl border transition-all {{ $b->status === 'resolved' ? 'bg-slate-50/70 border-slate-200/80 opacity-75' : 'bg-white border-rose-200/90 shadow-2xs' }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2.5">
                                <!-- Affected Component Identifier -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>📍 Component: {{ $b->wbsItem->title ?? 'General Scope' }} (Code: {{ $b->wbsItem->wbs_code ?? '-' }})</span>
                                    </span>

                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider font-extrabold border {{ $sevColors[$b->severity] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $b->severity }} Severity
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($b->status === 'resolved')
                                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                            ✓ Resolved
                                        </span>
                                    @else
                                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                            <button wire:click="openResolveBlockerModal({{ $b->id }})" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition-all cursor-pointer flex items-center gap-1">
                                                <span>Resolve Blocker</span>
                                            </button>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                Open
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <p class="text-xs font-semibold text-slate-800 leading-relaxed mb-3">{{ $b->description }}</p>

                            <div class="flex items-center justify-between text-[10px] text-slate-500 font-medium pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9px]">
                                        {{ strtoupper(substr($b->reporter->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>Reported by <strong>{{ $b->reporter->name ?? 'Team Member' }}</strong> • {{ $b->created_at->diffForHumans() }}</span>
                                </div>

                                @if($b->status === 'resolved' && $b->resolution)
                                    <div class="text-emerald-800 font-semibold italic truncate max-w-sm">
                                        Resolution: "{{ $b->resolution }}" (by {{ $b->resolver->name ?? 'PM' }})
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-xs font-extrabold text-slate-800">No Active Task Blockers</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">All tasks are currently executing cleanly without reported blockers.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Risk Register Form Card -->
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black text-sm shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Report New Project Risk</h3>
                        <p class="text-xs text-slate-500 font-medium">Identify risks and link them directly to specific WBS tasks or scope modules</p>
                    </div>
                </div>

                <form wire:submit="addRisk" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Payment Gateway Integration Delay" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                            @error('riskTitle') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Category</label>
                            <select wire:model="riskCategory" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Component / WBS Task Identification Selector -->
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs flex items-center justify-between">
                            <span>📍 Affected Scope Component / WBS Task (Identity Location)</span>
                            <span class="text-[10px] text-[#c3122e] font-black uppercase">Links Risk to Exact Section</span>
                        </label>
                        <select wire:model="riskWbsItemId" class="form-select text-xs font-extrabold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e] bg-slate-50">
                            <option value="">🌐 [General Project Level Scope]</option>
                            @foreach($project->wbsItems as $wbs)
                                <option value="{{ $wbs->id }}">
                                    📍 Code: {{ $wbs->wbs_code }} — {{ $wbs->title }} ({{ $wbs->item_type->value }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-500 mt-1 font-medium">Select the specific WBS task or module where this risk originates, or choose General Project Scope.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Probability & Impact</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block mb-1">Probability</span>
                                    <select wire:model="riskProbability" class="form-select text-xs font-bold py-2 rounded-xl">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block mb-1">Impact</span>
                                    <select wire:model="riskImpact" class="form-select text-xs font-bold py-2 rounded-xl">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Mitigation Plan (Optional)</label>
                            <input type="text" wire:model="riskMitigation" placeholder="Outline steps to prevent or minimize impact..." class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Risk Description & Impact Analysis <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" placeholder="Detailed analysis of what could go wrong and potential consequences..." class="form-input text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                        @error('riskDescription') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all cursor-pointer">
                            <span>Add Risk to Project Log</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Risk Log List Card -->
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base">Project Risk Register Log</h3>
                    <a href="{{ route('risks.index', ['project' => $project->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] border border-[#faeaea] hover:bg-[#faeaea] transition-all flex items-center gap-1.5">
                        <span>Open Executive Risk Matrix &amp; Hub</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($project->risks as $r)
                        @php
                            $riskStatusColors = [
                                'open' => 'bg-rose-50 text-rose-900 border-rose-300',
                                'monitoring' => 'bg-amber-50 text-amber-900 border-amber-300',
                                'mitigated' => 'bg-emerald-50 text-emerald-900 border-emerald-300',
                                'closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                            ];
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/90 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="flex-1 min-w-0 space-y-2">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-black text-slate-900 text-xs">{{ $r->title }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 text-slate-700">{{ $r->category }}</span>
                                    
                                    <!-- AFFECTED COMPONENT BADGE -->
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                                        @if($r->wbsItem)
                                            📍 Component: {{ $r->wbsItem->title }} (Code: {{ $r->wbsItem->wbs_code }})
                                        @else
                                            🌐 Scope: General Project Level
                                        @endif
                                    </span>
                                </div>

                                <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $r->description }}</p>

                                @if($r->mitigation_plan || $r->contingency_plan)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                                        @if($r->mitigation_plan)
                                            <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900">
                                                <strong>Mitigation:</strong> {{ $r->mitigation_plan }}
                                            </div>
                                        @endif
                                        @if($r->contingency_plan)
                                            <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-900">
                                                <strong>Contingency:</strong> {{ $r->contingency_plan }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0 self-end sm:self-start">
                                <div class="text-center">
                                    <span class="px-3 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-900 border border-amber-200 block">
                                        Score: {{ $r->risk_score }}/12
                                    </span>
                                </div>

                                <select
                                    wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                    class="text-xs font-extrabold rounded-full px-3 py-1 border cursor-pointer {{ $riskStatusColors[$r->status] ?? 'bg-slate-100 text-slate-700' }}"
                                >
                                    <option value="open" @selected($r->status === 'open')>Open</option>
                                    <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                    <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                    <option value="closed" @selected($r->status === 'closed')>Closed</option>
                                </select>

                                @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="openEditRiskModal({{ $r->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Edit Risk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <button wire:click="deleteRisk({{ $r->id }})" wire:confirm="Are you sure you want to delete this risk record?" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Risk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">No risks recorded in the project log.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Blocker Resolution Modal -->
        <div x-data="{ open: @entangle('showResolveBlockerModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display:none">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showResolveBlockerModal = false"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6 sm:p-8">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Resolve Task Blocker</h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Provide resolution details to close this execution blocker</p>
                    </div>
                </div>

                <form wire:submit="saveBlockerResolution" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Resolution Summary <span class="text-rose-500">*</span></label>
                        <textarea wire:model="blockerResolutionInput" rows="4" placeholder="Detail the resolution steps taken to unblock the team..." class="form-input text-xs leading-relaxed rounded-xl"></textarea>
                        @error('blockerResolutionInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showResolveBlockerModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md">Mark Blocker Resolved</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Risk Modal -->
        <div x-data="{ open: @entangle('showEditRiskModal') }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        >
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showEditRiskModal = false"></div>

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 border border-slate-200/90 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-medium">Update assessment ratings and mitigation plans</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false; $wire.showEditRiskModal = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="updateRisk" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Category</label>
                            <select wire:model="riskCategory" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Probability</label>
                            <select wire:model="riskProbability" class="form-select text-xs font-bold py-2 rounded-xl">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Impact</label>
                            <select wire:model="riskImpact" class="form-select text-xs font-bold py-2 rounded-xl">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                                <option value="critical">Critical (4)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label font-extrabold text-slate-800 text-xs">Risk Description <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" class="form-input text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Mitigation Strategy</label>
                            <input type="text" wire:model="riskMitigation" class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Contingency Plan</label>
                            <input type="text" wire:model="riskContingency" class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showEditRiskModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-slate-900 hover:bg-black shadow-md">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- 9. APPROVALS TAB -->
    @if($activeTab === 'approvals')
        <div class="space-y-6">
            {{-- Submit Approval Request Form (Available to all 3 roles: Admin, PM, Team Member) --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black text-sm shadow-2xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">Submit Formal Approval Request</h3>
                            <p class="text-xs text-slate-500 font-medium">Request sign-off for task completion, deadline extension, baseline, or scope changes</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submitApprovalRequest" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label text-xs font-bold text-slate-800">Request Type <span class="text-rose-500">*</span></label>
                            <select wire:model.live="reqType" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                @foreach(\App\Enums\ApprovalType::cases() as $t)
                                    <option value="{{ $t->value }}">{{ $t->label() }}</option>
                                @endforeach
                            </select>
                            @error('reqType') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        {{-- Conditional: Deadline Extension --}}
                        @if($reqType === 'deadline_extension')
                            <div class="form-group">
                                <label class="form-label text-xs font-bold text-slate-800">Requested New Deadline <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="reqValue" min="{{ now()->addDay()->format('Y-m-d') }}" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                @if($project->deadline)
                                    <p class="text-[10px] text-slate-400 mt-1">Current deadline: <strong>{{ $project->deadline->format('M d, Y') }}</strong></p>
                                @endif
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        {{-- Conditional: Budget Change --}}
                        @if($reqType === 'budget_change')
                            <div class="form-group">
                                <label class="form-label text-xs font-bold text-slate-800">Requested New Budget (LKR) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs font-bold">Rs.</span>
                                    <input type="number" wire:model="reqValue" min="0" step="1000" placeholder="0" class="form-input pl-9 text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                </div>
                                @if($project->estimated_budget)
                                    <p class="text-[10px] text-slate-400 mt-1">Current budget: <strong>Rs. {{ number_format($project->estimated_budget, 0) }}</strong></p>
                                @endif
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        {{-- Conditional: Scope Change --}}
                        @if($reqType === 'scope_change')
                            <div class="form-group sm:col-span-2">
                                <label class="form-label text-xs font-bold text-slate-800">Scope Change Description <span class="text-rose-500">*</span></label>
                                <textarea wire:model="reqValue" rows="2" placeholder="Describe the scope change in detail..." class="form-input text-xs rounded-xl border-slate-200"></textarea>
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs font-bold text-slate-800">Reason / Justification <span class="text-rose-500">*</span></label>
                        <textarea wire:model="reqReason" rows="3" placeholder="Provide clear reasoning for this approval request..." class="form-input text-xs rounded-xl border-slate-200"></textarea>
                        @error('reqReason') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <p class="text-[11px] text-slate-400 font-medium">Submitted requests will be logged and routed for governance sign-off</p>
                        <button type="submit" wire:loading.attr="disabled" class="btn text-xs font-bold text-white px-4 py-2.5 rounded-xl shadow-md cursor-pointer" style="background:#c3122e">
                            <span>Submit Approval Request</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Approval Requests History Card --}}
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base">Project Approval Requests &amp; Governance History</h3>
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700">
                        {{ $project->approvalRequests->count() }} Request(s)
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($project->approvalRequests->sortByDesc('created_at') as $req)
                        @php
                            $rv = $req->requested_value ?? [];
                            $reqValueStr = match($req->request_type->value) {
                                'deadline_extension' => isset($rv['new_deadline']) ? '→ New Deadline: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y') : null,
                                'budget_change'      => isset($rv['new_budget']) ? '→ New Budget: Rs. ' . number_format($rv['new_budget'], 0) : null,
                                'scope_change'       => isset($rv['scope_description']) ? $rv['scope_description'] : null,
                                default              => null,
                            };
                            $statusCls = match($req->status->value ?? '') {
                                'approved'          => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected'          => 'bg-rose-50 text-rose-700 border-rose-200',
                                'revision_required' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'cancelled'         => 'bg-slate-50 text-slate-500 border-slate-200',
                                default             => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3 hover:border-slate-300 transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                                            {{ $req->request_type->label() }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-800 font-semibold leading-relaxed mt-1">{{ $req->reason }}</p>
                                    @if($reqValueStr)
                                        <div class="mt-1.5 inline-block text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-lg">
                                            {{ $reqValueStr }}
                                        </div>
                                    @endif
                                    <p class="text-[10px] text-slate-500 mt-2 font-medium">
                                        Submitted by <strong>{{ $req->requester->name ?? 'User' }}</strong> • {{ $req->created_at->format('M d, Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusCls }}">
                                        {{ $req->status->label() }}
                                    </span>

                                    {{-- Actions for 3 roles: Approve/Reject for Admin & PM; Withdraw for Requester --}}
                                    @if($req->status->value === 'pending')
                                        @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                                            <button wire:click="approveRequestInWorkspace({{ $req->id }})"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs cursor-pointer transition-all">
                                                Approve
                                            </button>
                                            <button wire:click="rejectRequestInWorkspace({{ $req->id }})"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-2xs cursor-pointer transition-all">
                                                Reject
                                            </button>
                                        @elseif($req->requested_by === auth()->id())
                                            <button wire:click="cancelRequestInWorkspace({{ $req->id }})"
                                                    wire:confirm="Withdraw this approval request?"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 cursor-pointer">
                                                Withdraw
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Show reviewer feedback if reviewed --}}
                            @if($req->reviewer && $req->review_comment)
                                <div class="pt-2 border-t border-slate-200/80 text-[11px]">
                                    <span class="font-bold text-slate-700">
                                        {{ $req->status->value === 'approved' ? '✅ Approved' : '❌ Rejected' }} by {{ $req->reviewer->name }}:
                                    </span>
                                    <span class="text-slate-600 italic ml-1">"{{ $req->review_comment }}"</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-xs font-extrabold text-slate-800">No Approval Requests for this Project</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Use the form above to submit a new formal sign-off or change request.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         IN-APP DOCUMENT PREVIEW MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showPreviewModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.closePreview()"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-3xl z-10 overflow-y-auto max-h-[92vh]"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            @if($previewDoc)
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-xs uppercase">
                            {{ $previewFileType }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 truncate max-w-md">{{ $previewDoc->original_name }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Project: <strong class="text-slate-700">{{ $previewDoc->project->name ?? 'Global' }}</strong> • Uploaded by {{ $previewDoc->uploader->name ?? 'User' }} • {{ round($previewDoc->file_size/1024, 1) }} KB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('documents.download', $previewDoc) }}" class="btn-primary btn-sm text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                        <button type="button" @click="open = false; $wire.closePreview()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Content Preview Body --}}
                <div class="p-6">
                    @if(in_array($previewFileType, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg']))
                        <div class="flex justify-center bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <img src="{{ route('documents.view', $previewDoc) }}" alt="{{ $previewDoc->original_name }}" class="max-h-[60vh] object-contain rounded-lg shadow-sm">
                        </div>
                    @elseif($previewFileType === 'pdf')
                        <iframe src="{{ route('documents.view', $previewDoc) }}" class="w-full h-[68vh] rounded-xl border border-slate-200"></iframe>
                    @elseif(in_array($previewFileType, ['docx', 'doc']))
                        <div x-init="window.renderDocxPreview('{{ route('documents.view', $previewDoc) }}', 'docx-preview-pws-{{ $previewDoc->id }}')"
                             class="bg-slate-100 p-4 rounded-xl border border-slate-200 max-h-[68vh] overflow-y-auto">
                            <div id="docx-preview-pws-{{ $previewDoc->id }}" class="w-full min-h-[400px]"></div>
                        </div>
                    @elseif($previewContent)
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 max-h-[60vh] overflow-y-auto font-sans text-slate-800 text-xs leading-relaxed space-y-3 whitespace-pre-wrap select-text">
                            <div class="flex items-center gap-2 pb-3 mb-3 border-b border-slate-200 text-slate-500 font-semibold text-[11px]">
                                <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Extracted Document Content Preview
                            </div>
                            {{ $previewContent }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] mx-auto mb-3 font-bold text-sm uppercase">
                                {{ $previewFileType }}
                            </div>
                            <p class="text-slate-800 font-bold text-sm mb-1">{{ $previewDoc->original_name }}</p>
                            <p class="text-slate-500 text-xs mb-4">Direct in-app visual preview not available for this binary file format.</p>
                            <a href="{{ route('documents.download', $previewDoc) }}" class="btn-primary btn-sm inline-flex items-center gap-1.5">
                                Download File to View
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Financial Summary & Hours Modal -->
    <div x-data="{ open: @entangle('showFinancialsModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showFinancialsModal = false"></div>

        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Update Financial Summary & Hours</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Directly update project expenditure and labor hours tracking</p>
                </div>
            </div>

            <form wire:submit="saveFinancialsAndHours" class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Actual Cost (LKR / Rs.) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                        <input type="number" step="0.01" wire:model="actualCostInput" placeholder="0.00" class="form-input pl-10 text-xs font-semibold">
                    </div>
                    @error('actualCostInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Estimated Hours (h)</label>
                        <input type="number" step="0.5" wire:model="estimatedHoursInput" placeholder="0" class="form-input text-xs font-semibold">
                        @error('estimatedHoursInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Actual Hours (h)</label>
                        <input type="number" step="0.5" wire:model="actualHoursInput" placeholder="0" class="form-input text-xs font-semibold">
                        @error('actualHoursInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                    <p class="font-bold text-slate-800">Automatic Sync Note:</p>
                    <p>• Estimated and Actual Hours also automatically aggregate from tasks created in the <strong>WBS Plan</strong>.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="open = false; $wire.showFinancialsModal = false" class="btn-secondary text-xs font-bold">Cancel</button>
                    <button type="submit" class="btn-primary text-xs font-bold flex items-center gap-1.5">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Timeline & Target Deadline Modal -->
    <div x-data="{ open: @entangle('showTimelineModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showTimelineModal = false"></div>

        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Set Project Timeline &amp; Target Deadline</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Project Owner governance to set or extend project completion target</p>
                </div>
            </div>

            <form wire:submit="saveTimeline" class="space-y-4">
                <div class="form-group">
                    <label class="form-label text-xs font-bold text-slate-800">Project Start Date</label>
                    <input type="date" wire:model="startDateInput" class="form-input text-xs font-semibold rounded-xl">
                    @error('startDateInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold text-slate-800">Target Completion Deadline <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="deadlineInput" class="form-input text-xs font-semibold rounded-xl" required>
                    @error('deadlineInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                    <p class="font-bold text-slate-800">Project Owner Governance:</p>
                    <p>• Setting an accurate target deadline helps track project velocity and alerts your team of upcoming due dates.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="open = false; $wire.showTimelineModal = false" class="btn-secondary text-xs font-bold">Cancel</button>
                    <button type="submit" class="btn-primary text-xs font-bold flex items-center gap-1.5">
                        Save Deadline
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Details Modal -->
    <div x-data="{ open: @entangle('showEditProjectModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showEditProjectModal = false"></div>

        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg z-10 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-sm">
                    ✏️
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Edit Project Details &amp; Scope</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Project Owner can update project title, scope description, priority, &amp; budget</p>
                </div>
            </div>

            <form wire:submit="saveProjectDetails" class="space-y-4">
                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Project Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="editName" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none" required>
                    @error('editName') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Project Scope &amp; Detailed Objectives</label>
                    <textarea wire:model="editDescription" rows="4" placeholder="Enter detailed project scope, milestones, and strategic deliverables..." class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none"></textarea>
                    @error('editDescription') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Priority Level <span class="text-rose-500">*</span></label>
                        <select wire:model="editPriority" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none" required>
                            @foreach(\App\Enums\Priority::cases() as $p)
                                <option value="{{ $p->value }}">{{ $p->label() }} Priority</option>
                            @endforeach
                        </select>
                        @error('editPriority') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Estimated Budget (LKR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                            <input type="number" step="1" wire:model="editEstimatedBudget" placeholder="0" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none">
                        </div>
                        @error('editEstimatedBudget') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showEditProjectModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25">
                        <span>Save Project Details</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage & Add Collaborators Modal -->
    <div x-data="{ open: @entangle('showCollaboratorsModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showCollaboratorsModal = false"></div>

        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-xl z-10 p-6 sm:p-7 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-sm">
                        👥
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Project Participants</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Project Owner can attach team members &amp; assign them to tasks</p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.showCollaboratorsModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">✕</button>
            </div>

            <!-- Action Bar: Search & Quick Create Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="collaboratorSearch" placeholder="Search team members by name or email..." class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button wire:click="$toggle('showCreateCollaboratorSection')" type="button" class="px-3.5 py-2 rounded-xl text-xs font-extrabold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer flex-shrink-0">
                    <span>{{ $showCreateCollaboratorSection ? '✕ Cancel Create' : '✨ + Create New Member' }}</span>
                </button>
            </div>

            <!-- Optional: Quick Create Collaborator Form -->
            @if($showCreateCollaboratorSection)
                <div class="mb-5 p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 space-y-3">
                    <h4 class="font-extrabold text-amber-900 text-xs flex items-center gap-1.5">
                        <span>🚀 Add New Collaborator Account</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="newCollabName" placeholder="e.g. Ruwan Perera" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]">
                            @error('newCollabName') <span class="text-[10px] text-rose-600 font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" wire:model="newCollabEmail" placeholder="e.g. ruwan@georgesteuart.com" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]">
                            @error('newCollabEmail') <span class="text-[10px] text-rose-600 font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Temporary Login Password</label>
                            <input type="text" wire:model="newCollabTempPassword" placeholder="Password@123" class="w-full px-3 py-2 rounded-xl border border-amber-300 bg-amber-50/50 text-xs font-mono font-bold text-amber-900 outline-none focus:border-[#c3122e]">
                            <span class="text-[10px] text-amber-800 font-medium mt-0.5 block">🔑 Default: <strong class="font-bold font-mono">Password@123</strong> (Editable)</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Role Type</label>
                            <select wire:model="newCollabRole" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none">
                                <option value="team_member">Team Member / Collaborator</option>
                                <option value="project_manager">Project Manager / Lead</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button wire:click="quickCreateCollaborator" type="button" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <span>Create &amp; Attach to Project</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Collaborators Selection List -->
            <form wire:submit="saveCollaborators" class="space-y-4">
                <div class="text-xs font-extrabold text-slate-700 mb-2">Select Participants for this Project:</div>

                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @php
                        $filteredUsers = $availableUsers->filter(function($u) {
                            if ($this->collaboratorSearch) {
                                return str_contains(strtolower($u->name), strtolower($this->collaboratorSearch)) || str_contains(strtolower($u->email), strtolower($this->collaboratorSearch));
                            }
                            return true;
                        });
                    @endphp

                    @forelse($filteredUsers as $u)
                        @php
                            $isOwner = ($u->id === $project->project_manager_id);
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-2xl border transition-all {{ in_array($u->id, $selectedCollaboratorIds) || $isOwner ? 'bg-[#fdf4f4]/60 border-[#faeaea]' : 'bg-slate-50/70 border-slate-200/80 hover:bg-slate-100/70' }}">
                            <label class="flex items-center gap-3 flex-1 cursor-pointer">
                                <input type="checkbox" wire:model="selectedCollaboratorIds" value="{{ $u->id }}" @if($isOwner) checked disabled @endif class="w-4 h-4 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900 leading-tight">{{ $u->name }} @if($isOwner)<span class="text-[10px] text-[#c3122e] font-black ml-1">(Project Owner)</span>@endif</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $u->email }}</p>
                                </div>
                            </label>

                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $u->hasRole('project_manager') ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $u->getRoleNames()->first() ?? 'Member' }}
                                </span>

                                @if(!$isOwner && auth()->id() !== $u->id)
                                    <button wire:click="deleteUserFromSystem({{ $u->id }})" wire:confirm="Permanently delete user '{{ $u->name }}' from system &amp; remove from all project lists?" type="button" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete User from System">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-6">No matching users found</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <p class="text-[11px] text-slate-500 font-medium">Selected: <strong class="text-slate-900">{{ count($selectedCollaboratorIds) }} collaborators</strong></p>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="open = false; $wire.showCollaboratorsModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25">
                            <span>Save Participants</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
