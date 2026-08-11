<div class="pt-3 space-y-6 sm:space-y-8 pb-12" style="font-family: 'Inter', system-ui, sans-serif;">
    <!-- 1. HEADER BANNER — ULTRA LUXURY EXECUTIVE COMMAND CENTER -->
    <div class="relative rounded-3xl p-6 sm:p-8 text-white shadow-2xl overflow-hidden" style="background: radial-gradient(circle at 85% 20%, rgba(225, 29, 72, 0.3) 0%, rgba(184, 134, 11, 0.18) 45%, transparent 75%), linear-gradient(135deg, #180e1b 0%, #580a18 45%, #9f1239 100%); border: 1px solid rgba(255,255,255,0.18); box-shadow: 0 20px 45px -10px rgba(159,18,57,0.35);">
        <!-- Decorative Ambient Light Orbs & Grid -->
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-rose-500/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-amber-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:24px_24px] opacity-[0.04] pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-white/10 backdrop-blur-md border border-white/20 text-rose-100 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    <span>Daily Execution &amp; Feedback Command Workspace</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white drop-shadow-sm">
                        Daily Project &amp; Task Updates
                    </h1>
                </div>

                <p class="text-xs sm:text-sm max-w-2xl leading-relaxed text-slate-200/90 font-medium">
                    Participants submit daily task execution progress for Project Managers. Project Managers publish project status updates for Super Admins. Real-time feedback loops and threaded comments.
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap flex-shrink-0">
                <!-- Date & Status Glass Widget -->
                <div class="px-4 py-3 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-lg flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center text-amber-300 flex-shrink-0">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-rose-200 block">Today's Date</span>
                        <span class="text-xs font-black text-white font-mono">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>

                @if(!$this->isSuperAdminUser(auth()->user()))
                    <button
                        wire:click="openStatusUpdateModal()"
                        class="px-6 py-3.5 rounded-2xl text-xs font-black transition-all cursor-pointer flex items-center justify-center gap-2 bg-gradient-to-r from-rose-500 to-[#c3122e] text-white shadow-lg hover:shadow-rose-500/40 border border-white/20 hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Publish Daily Log</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. KPI SUMMARY METRICS (CARDS WITH HIGH CONTRAST & RESPONSIVE GRID) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Reports Logged Today -->
        <div class="p-5 rounded-2xl flex items-center justify-between transition-all" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider" style="color: #64748b;">Reports Logged Today</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black" style="color: #0f172a;">{{ $updatedTodayCount }}</span>
                    <span class="text-xs font-bold" style="color: #16a34a;">✓ Today's Feed</span>
                </div>
                <p class="text-[10px] font-medium mt-1" style="color: #94a3b8;">Day-by-day progress stream</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Participant Task Logs -->
        <div class="p-5 rounded-2xl flex items-center justify-between transition-all" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider" style="color: #64748b;">Participant Task Logs</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black" style="color: #c3122e;">{{ $taskUpdatesCount }}</span>
                    <span class="text-xs font-bold" style="color: #475569;">Task Items</span>
                </div>
                <p class="text-[10px] font-medium mt-1" style="color: #94a3b8;">Visible to Project Managers</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background: #fdf4f4; border: 1px solid #faeaea; color: #c3122e;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
        </div>

        <!-- PM Overall Reports -->
        <div class="p-5 rounded-2xl flex items-center justify-between transition-all" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider" style="color: #64748b;">PM Project Reports</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black" style="color: #d97706;">{{ $projectUpdatesCount }}</span>
                    <span class="text-xs font-bold" style="color: #b45309;">PM Overall</span>
                </div>
                <p class="text-[10px] font-medium mt-1" style="color: #94a3b8;">Visible to Super Admins</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background: #fffbeb; border: 1px solid #fde68a; color: #d97706;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>

        <!-- Pending Review / Uncommented Logs -->
        <div class="p-5 rounded-2xl flex items-center justify-between transition-all" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider" style="color: #64748b;">Pending Feedback</p>
                <div class="flex items-baseline gap-2 mt-1.5">
                    <span class="text-2xl sm:text-3xl font-black" style="color: #e11d48;">{{ $uncommentedUpdatesCount }}</span>
                    <span class="text-xs font-bold" style="color: #e11d48;">Needs Comment</span>
                </div>
                <p class="text-[10px] font-medium mt-1" style="color: #94a3b8;">Unanswered status logs</p>
            </div>
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background: #fff1f2; border: 1px solid #fecdd3; color: #e11d48;">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            </div>
        </div>
    </div>

    <!-- 3. CONTROLS, SEARCH & FILTER TOOLBAR — FULLY RESPONSIVE -->
    <div class="rounded-3xl p-4 sm:p-5 space-y-4" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <!-- Left: Scope Pills -->
            <div class="flex items-center gap-2 p-1.5 rounded-2xl flex-wrap" style="background: #f1f5f9;">
                <button
                    wire:click="$set('selectedScope', 'all')"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer"
                    style="{{ $selectedScope === 'all' ? 'background:#ffffff; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08);' : 'color:#64748b;' }}"
                >
                    <span>All Reports ({{ $totalUpdatesCount }})</span>
                </button>
                <button
                    wire:click="$set('selectedScope', 'task')"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5"
                    style="{{ $selectedScope === 'task' ? 'background:#ffffff; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08);' : 'color:#64748b;' }}"
                >
                    <svg class="w-3.5 h-3.5" style="color:#c3122e;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Participant Task Logs</span>
                </button>
                <button
                    wire:click="$set('selectedScope', 'project')"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all cursor-pointer flex items-center gap-1.5"
                    style="{{ $selectedScope === 'project' ? 'background:#ffffff; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08);' : 'color:#64748b;' }}"
                >
                    <svg class="w-3.5 h-3.5" style="color:#d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>PM Overall Project Reports</span>
                </button>
            </div>

            <!-- Right: Project Selector, Date & Search -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1 xl:justify-end">
                <!-- Search Box -->
                <div class="relative flex-1 min-w-[200px]">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="searchQuery"
                        placeholder="Search logs, reporter, task..."
                        class="w-full text-xs font-semibold py-2.5 pl-9 pr-4 rounded-xl"
                        style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;"
                    >
                    <svg class="w-4 h-4 absolute left-3 top-3" style="color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Project Dropdown -->
                <select
                    wire:model.live="selectedProjectId"
                    class="text-xs font-bold py-2.5 px-3 rounded-xl min-w-[180px]"
                    style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a;"
                >
                    <option value="">🌐 All Accessible Projects</option>
                    @foreach($accessibleProjects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                    @endforeach
                </select>

                <!-- Date Filter -->
                <select
                    wire:model.live="dateFilter"
                    class="text-xs font-bold py-2.5 px-3 rounded-xl"
                    style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a;"
                >
                    <option value="all">All Time</option>
                    <option value="today">Logged Today</option>
                    <option value="this_week">This Week</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 4. EXECUTIVE PROJECTS DAILY UPDATES TABLE WORKSPACE -->
    <div class="w-full rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-wider text-slate-600 bg-slate-50/90 border-b border-slate-200">
                        <th class="py-3.5 pl-4 pr-2 text-left min-w-[200px]">Project Code & Name</th>
                        <th class="py-3.5 px-2 text-left min-w-[140px]">Subsidiary</th>
                        <th class="py-3.5 px-2 text-left min-w-[130px]">Project Manager</th>
                        <th class="py-3.5 px-2 text-left min-w-[200px]">Latest Update Log</th>
                        <th class="py-3.5 px-2 text-left whitespace-nowrap min-w-[100px]">Last Log Date</th>
                        <th class="py-3.5 pl-2 pr-4 text-right whitespace-nowrap min-w-[160px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($groupedProjectUpdates as $group)
                        @php
                            $project = $group['project'];
                            $update = $group['latestUpdate'];
                            $pastUpdates = $group['pastUpdates'];
                            $allUpdatesCount = $group['allUpdates']->count();
                            $pm = $project->projectManager;
                            $subsidiary = $project->subsidiary;
                        @endphp

                        <tr x-data="{ openForm: false }" class="hover:bg-slate-50/80 transition-colors">
                            <!-- Project Code & Name -->
                            <td class="py-3.5 pl-4 pr-2 align-middle">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-extrabold uppercase tracking-wider flex-shrink-0 bg-[#fdf4f4] text-[#c3122e] border border-[#f5c6cb] shadow-2xs">
                                        {{ $project->code ?? 'PRJ' }}
                                    </span>
                                    <div class="min-w-0">
                                        <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-xs text-slate-900 hover:text-[#c3122e] transition-colors truncate block max-w-[220px]" title="{{ $project->name }}">
                                            📁 {{ $project->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono font-bold">{{ $allUpdatesCount }} Log(s) recorded</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Subsidiary Name -->
                            <td class="py-3.5 px-2 align-middle">
                                @if($subsidiary)
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="text-xs flex-shrink-0">🏢</span>
                                        <span class="font-extrabold text-xs text-slate-800 truncate block max-w-[160px]" title="{{ $subsidiary->name }}">{{ $subsidiary->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>

                            <!-- Project Manager -->
                            <td class="py-3.5 px-2 align-middle">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <div class="w-6.5 h-6.5 rounded-full font-black flex items-center justify-center text-[10px] flex-shrink-0 shadow-2xs" style="background: #c3122e; color: #ffffff;">
                                        {{ strtoupper(substr($pm->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate text-xs max-w-[120px]" title="{{ $pm->name ?? 'Unassigned' }}">{{ $pm->name ?? 'Unassigned' }}</span>
                                        <span class="px-1.5 py-0.2 rounded-full text-[9px] font-black bg-amber-100 text-amber-800 border border-amber-200 inline-block">Project Manager</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Latest Update Summary -->
                            <td class="py-3.5 px-2 align-middle max-w-xs">
                                @if($update)
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="font-bold text-slate-900 block truncate text-xs max-w-[220px]" title="{{ $update->title }}">{{ $update->title }}</span>
                                        <p class="text-[11px] text-slate-500 italic truncate max-w-[220px]">"{{ $update->summary }}"</p>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">No status updates logged yet</span>
                                @endif
                            </td>

                            <!-- Last Log Date -->
                            <td class="py-3.5 px-2 align-middle font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                @if($update)
                                    {{ $update->created_at->diffForHumans() }}
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>

                            <!-- Actions & Toggle Arrow Button -->
                            <td class="py-3.5 pl-2 pr-4 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if(!$this->isSuperAdminUser(auth()->user()))
                                    <button
                                        wire:click="openStatusUpdateModal({{ $project->id }})"
                                        type="button"
                                        class="px-3.5 py-2 rounded-xl text-xs font-black transition-all cursor-pointer shadow-sm flex-shrink-0"
                                        style="background: #c3122e; color: #ffffff;"
                                    >
                                        + Log Update
                                    </button>
                                    @endif

                                    <!-- Arrow Expand Button: Clicking opens status updates history popup modal -->
                                    <button
                                        wire:click="openHistoryModal({{ $project->id }})"
                                        type="button"
                                        class="px-3.5 py-2 rounded-xl text-xs font-extrabold transition-all flex items-center justify-center cursor-pointer shadow-2xs border border-rose-200 bg-rose-50 text-[#c3122e] hover:bg-[#c3122e] hover:text-white hover:border-[#c3122e] flex-shrink-0 group"
                                        title="Click to view status update history for {{ $project->name }}"
                                    >
                                        <span class="text-[10px] font-bold mr-1">Updates ({{ $allUpdatesCount }})</span>
                                        <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs font-bold text-slate-400">
                                No Projects Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL: PROJECT STATUS UPDATE HISTORY & FEEDBACK LOOP POPUP -->
    @if($showHistoryModal && $historyProjectId)
        @php
            $historyGroup = collect($groupedProjectUpdates)->firstWhere(fn($g) => $g['project']->id === $historyProjectId);
        @endphp

        @if($historyGroup)
            @php
                $historyProject = $historyGroup['project'];
                $historyLatestUpdate = $historyGroup['latestUpdate'];
                $historyAllUpdates = $historyGroup['allUpdates'];
                $historyUpdatesCount = $historyAllUpdates->count();
            @endphp

            <style>
                .custom-modal-scrollbar::-webkit-scrollbar {
                    width: 6px;
                    height: 6px;
                }
                .custom-modal-scrollbar::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 10px;
                }
                .custom-modal-scrollbar::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 10px;
                }
                .custom-modal-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            </style>

            <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 md:p-10">
                <!-- Overlay Backdrop with blur -->
                <div class="fixed inset-0 backdrop-blur-sm transition-opacity" style="background: rgba(15,23,42,0.6);" wire:click="closeHistoryModal"></div>

                <!-- Popup Content Box -->
                <div class="relative bg-white rounded-3xl max-w-4xl w-full shadow-2xl z-10 flex flex-col max-h-[90vh] md:max-h-[85vh] overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-200">
                    <!-- Modal Header -->
                    <div class="px-5 py-4 flex items-center justify-between border-b border-slate-100 flex-shrink-0" style="background: linear-gradient(135deg, #7a0b1d, #c3122e);">
                        <div class="flex items-center gap-3">
                            <span class="rounded-xl text-white font-black flex items-center justify-center text-sm shadow-sm" style="width: 32px; height: 32px; background: #f43f5e;">
                                📊
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-xs sm:text-sm font-black text-white leading-tight truncate">
                                    Project Execution Logs: {{ $historyProject->name }}
                                </h3>
                                <p class="text-[10px] text-slate-300 font-medium leading-none mt-0.5">
                                    {{ $historyProject->code }} • {{ $historyUpdatesCount }} Log(s) recorded
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeHistoryModal" type="button" class="text-white hover:text-rose-200 text-xs font-black transition-colors cursor-pointer flex items-center gap-1">
                            <span>✕</span> <span class="hidden sm:inline">Close</span>
                        </button>
                    </div>

                    <!-- Modal Body Grid (Custom Scrollbar applied) -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 custom-modal-scrollbar">
                        @if($historyLatestUpdate)
                            <!-- LATEST UPDATE DETAIL & COMMENTS SECTION -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">
                                <!-- Left side: Latest Update Details -->
                                <div class="space-y-3">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-mono font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                        Latest Status Update Log
                                    </div>

                                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 shadow-2xs space-y-3">
                                        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80 flex-wrap gap-2">
                                            <div class="flex items-center gap-2">
                                                <div class="rounded-full font-black flex items-center justify-center text-[10px] text-white flex-shrink-0" style="width: 28px; height: 28px; background: #c3122e;">
                                                    {{ strtoupper(substr($historyLatestUpdate->creator->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="font-extrabold text-xs text-slate-900 block leading-tight">{{ $historyLatestUpdate->creator->name ?? 'User' }}</span>
                                                    <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $historyLatestUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[9px] font-mono font-black uppercase tracking-wider bg-[#c3122e] text-white">
                                                #{{ $historyUpdatesCount }}
                                            </span>
                                        </div>

                                        <h4 class="font-black text-xs sm:text-sm text-slate-900">{{ $historyLatestUpdate->title }}</h4>
                                        <div class="p-3.5 rounded-xl italic text-xs leading-relaxed font-semibold bg-white border border-slate-200 text-slate-800">
                                            "{{ $historyLatestUpdate->summary }}"
                                        </div>

                                        @if($historyLatestUpdate->work_completed || $historyLatestUpdate->current_blockers)
                                            <div class="space-y-2 text-xs pt-1">
                                                @if($historyLatestUpdate->work_completed)
                                                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-medium">
                                                        ✓ <strong>Completed:</strong> {{ $historyLatestUpdate->work_completed }}
                                                    </div>
                                                @endif
                                                @if($historyLatestUpdate->current_blockers)
                                                    <div class="p-2.5 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 font-medium">
                                                        ⚠ <strong>Blockers:</strong> {{ $historyLatestUpdate->current_blockers }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Right side: Comments & Feedback Loop -->
                                <div class="space-y-3">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-mono font-black uppercase tracking-wider text-rose-800 bg-rose-50 border border-rose-200">
                                        💬 PM & Super Admin Feedback Loop
                                    </div>

                                    @php
                                        $visibleComments = $historyLatestUpdate->comments;
                                    @endphp

                                    <div class="space-y-2.5 max-h-[220px] overflow-y-auto p-2 bg-slate-50 border border-slate-200 rounded-2xl custom-modal-scrollbar">
                                        @forelse($visibleComments as $comment)
                                            @php
                                                $commentUser = $comment->user;
                                                $commenterRole = 'Team Member';
                                                $commentBadgeStyle = 'background:#e2e8f0; color:#334155;';

                                                if ($commentUser) {
                                                    if ($commentUser->hasRole('super_admin')) {
                                                        $commenterRole = 'Super Admin';
                                                        $commentBadgeStyle = 'background:#ffe4e6; color:#9f1239; border:1px solid #fecdd3; font-weight:800;';
                                                    } elseif ($commentUser->hasRole('project_manager') || $historyProject->project_manager_id === $commentUser->id) {
                                                        $commenterRole = 'Project Manager';
                                                        $commentBadgeStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:800;';
                                                    }
                                                }
                                            @endphp
                                            <div class="flex items-start gap-2.5 p-3 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                                <div class="rounded-full font-bold flex items-center justify-center text-[10px] flex-shrink-0 mt-0.5 text-white" style="width: 24px; height: 24px; background: #c3122e;">
                                                    {{ strtoupper(substr($commentUser->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0 space-y-0.5">
                                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-xs font-black text-slate-900">{{ $commentUser->name ?? 'User' }}</span>
                                                            <span class="px-1.5 py-0.2 rounded text-[8px]" style="{{ $commentBadgeStyle }}">{{ $commenterRole }}</span>
                                                        </div>
                                                        <span class="text-[9px] font-medium text-slate-400 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs font-semibold text-slate-700 leading-relaxed">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-6 text-center text-xs font-medium text-slate-500 italic">
                                                No feedback comments posted yet for this update.
                                            </div>
                                        @endforelse
                                    </div>

                                    <!-- Add Comment Form -->
                                    <div class="flex gap-2">
                                        <input
                                            type="text"
                                            wire:model="newCommentContent.{{ $historyLatestUpdate->id }}"
                                            wire:keydown.enter="addComment({{ $historyLatestUpdate->id }})"
                                            placeholder="Write feedback comment..."
                                            class="flex-1 text-xs font-bold py-2.5 px-3.5 rounded-xl border border-slate-300 outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/20"
                                        >
                                        <button
                                            wire:click="addComment({{ $historyLatestUpdate->id }})"
                                            class="px-4 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] transition-colors flex items-center justify-center cursor-pointer flex-shrink-0 shadow-sm"
                                        >
                                            Send
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-6 text-center text-xs font-medium text-slate-500 bg-slate-50 rounded-2xl border border-slate-200">
                                No status update logs posted yet for this project.
                            </div>
                        @endif

                        <!-- ALL UPDATES LOG TIMELINE (PAST HISTORY) -->
                        <div class="space-y-4 pt-4 border-t border-slate-200">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-mono font-black uppercase tracking-wider text-slate-800 bg-slate-100 border border-slate-200">
                                📜 All Execution Logs Timeline ({{ $historyUpdatesCount }})
                            </div>

                            <div class="relative pl-6 space-y-5 before:content-[''] before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-200">
                                @forelse($historyAllUpdates as $pIndex => $pUpdate)
                                    @php
                                        $pCreator = $pUpdate->creator;
                                        $pCreatorRole = 'Collaborator';
                                        $pRoleBadgeStyle = 'background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-weight:700;';

                                        if ($pCreator) {
                                            if ($pCreator->hasRole('super_admin')) {
                                                $pCreatorRole = 'Super Admin';
                                                $pRoleBadgeStyle = 'background:#ffe4e6; color:#9f1239; border:1px solid #fecdd3; font-weight:900;';
                                            } elseif ($pCreator->hasRole('project_manager') || $historyProject->project_manager_id === $pCreator->id) {
                                                $pCreatorRole = 'Project Manager';
                                                $pRoleBadgeStyle = 'background:#fef3c7; color:#92400e; border:1px solid #fde68a; font-weight:900;';
                                            }
                                        }
                                    @endphp
                                    <div class="relative">
                                        <!-- Timeline Dot Node -->
                                        <span class="absolute -left-[19px] top-3.5 w-3.5 h-3.5 rounded-full border-2 border-white shadow-sm flex-shrink-0" style="background: {{ $pIndex === 0 ? '#c3122e' : '#64748b' }};"></span>

                                        <!-- Log Card Content -->
                                        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-xs transition-shadow space-y-2">
                                            <div class="flex items-center justify-between pb-2 border-b border-slate-100 flex-wrap gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold bg-[#c3122e] text-white">
                                                        #{{ $historyUpdatesCount - $pIndex }}
                                                    </span>
                                                    <h6 class="font-black text-xs text-slate-900">{{ $pUpdate->title }}</h6>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs flex-wrap">
                                                    <span class="font-bold text-slate-700">{{ $pCreator->name ?? 'User' }}</span>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px]" style="{{ $pRoleBadgeStyle }}">{{ $pCreatorRole }}</span>
                                                    <span class="text-slate-400 font-mono text-[10px]">{{ $pUpdate->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <div class="p-3 rounded-lg italic text-xs bg-slate-50 text-slate-800 border border-slate-200/60 leading-relaxed font-semibold">
                                                "{{ $pUpdate->summary }}"
                                            </div>
                                            @if($pUpdate->work_completed || $pUpdate->current_blockers)
                                                <div class="flex flex-wrap gap-2 text-[10px] pt-1">
                                                    @if($pUpdate->work_completed)
                                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold">
                                                            ✓ {{ $pUpdate->work_completed }}
                                                        </span>
                                                    @endif
                                                    @if($pUpdate->current_blockers)
                                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-800 border border-rose-200 font-bold">
                                                            ⚠ {{ $pUpdate->current_blockers }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-5 text-center text-xs font-semibold text-slate-400 bg-slate-50 rounded-xl border border-slate-200">
                                        No past status update logs.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- MODAL: POST DAILY STATUS UPDATE -->
    @if($showUpdateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 backdrop-blur-sm" style="background: rgba(15,23,42,0.6);" wire:click="$set('showUpdateModal', false)"></div>

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-5 z-10 max-h-[90vh] overflow-y-auto" style="border: 1px solid #e2e8f0;">
                <div class="flex items-center justify-between pb-4" style="border-bottom: 1px solid #f1f5f9;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-black" style="background: #fdf4f4; border: 1px solid #faeaea; color: #c3122e;">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black" style="color: #0f172a;">Publish Daily Progress Log</h3>
                            <p class="text-xs font-medium" style="color: #64748b;">Log task execution progress or overall project status update</p>
                        </div>
                    </div>
                    <button wire:click="$set('showUpdateModal', false)" class="cursor-pointer font-bold" style="color: #94a3b8;">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveStatusUpdate" class="space-y-4">
                    <!-- Target Project Select -->
                    <div>
                        <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">Target Project <span class="text-rose-500">*</span></label>
                        <select wire:model.live="updateProjectId" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;">
                            @foreach($accessibleProjects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                            @endforeach
                        </select>
                        @error('updateProjectId') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Scope Selector (Task vs Project Overall) -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                        <div class="grid grid-cols-2 gap-3 p-1.5 rounded-2xl" style="background: #f1f5f9;">
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'task')"
                                class="py-2 px-3 rounded-xl text-xs font-black transition-all cursor-pointer"
                                style="{{ $updateScopeType === 'task' ? 'background:#ffffff; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08);' : 'color:#64748b;' }}"
                            >
                                📍 Task-Specific Log
                            </button>
                            <button
                                type="button"
                                wire:click="$set('updateScopeType', 'project')"
                                class="py-2 px-3 rounded-xl text-xs font-black transition-all cursor-pointer"
                                style="{{ $updateScopeType === 'project' ? 'background:#ffffff; color:#0f172a; box-shadow:0 2px 6px rgba(0,0,0,0.08);' : 'color:#64748b;' }}"
                            >
                                🌐 Full Project Report
                            </button>
                        </div>
                    @endif

                    <!-- Task Linker if Task Scope -->
                    @if($updateScopeType === 'task')
                        <div>
                            <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">📍 WBS Scope Task</label>
                            <select wire:model="updateWbsItemId" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #0f172a;">
                                <option value="">Select Task...</option>
                                @foreach($modalWbsItems as $wbs)
                                    <option value="{{ $wbs->id }}">Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] font-medium mt-1" style="color: #94a3b8;">Select the specific task you worked on today.</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">Log Title <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="updateTitle" placeholder="e.g. Completed Payment Gateway API integration tests" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;">
                        @error('updateTitle') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">Daily Executive Summary <span class="text-rose-500">*</span></label>
                        <textarea wire:model="updateSummary" rows="3" placeholder="Detail main work completed, progress made, or key highlights..." class="w-full text-xs rounded-xl p-3" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;"></textarea>
                        @error('updateSummary') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">Work Completed Today</label>
                            <input type="text" wire:model="updateWorkCompleted" placeholder="Deliverables finished..." class="w-full text-xs py-2.5 px-3 rounded-xl" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;">
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold mb-1" style="color: #0f172a;">Blockers / Issues (If Any)</label>
                            <input type="text" wire:model="updateBlockers" placeholder="Execution blockers faced..." class="w-full text-xs py-2.5 px-3 rounded-xl" style="background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a;">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3" style="border-top: 1px solid #f1f5f9;">
                        <button type="button" wire:click="$set('showUpdateModal', false)" class="px-4 py-2.5 rounded-xl text-xs font-bold cursor-pointer" style="background: #f1f5f9; color: #475569;">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black cursor-pointer shadow-md" style="background: #c3122e; color: #ffffff;">Publish Daily Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
