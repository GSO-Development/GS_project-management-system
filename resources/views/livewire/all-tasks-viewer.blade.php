<div class="space-y-5 pt-1">
<style>
    .at-table-row:hover { background: #f8fafc; }
    .at-table-row:hover .at-task-title { color: #c3122e; }
    .at-status-dot { width:7px; height:7px; border-radius:50%; display:inline-block; flex-shrink:0; }
    .at-avatar { width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; flex-shrink:0; }
    .at-filter-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 11px;
        padding-right: 30px !important;
    }
    .kpi-tile { transition: all 0.15s ease; }
    .kpi-tile:hover { transform: translateY(-1px); }
    .kpi-tile.active { box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
</style>

    {{-- ══════════════════════ HEADER ══════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#1e293b 0%,#475569 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(30,41,59,.25);">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight" style="font-family:'Plus Jakarta Sans',system-ui,sans-serif;">All Tasks</h1>
                <p class="text-xs text-slate-500 mt-0.5">Organisation-wide task view across all projects</p>
            </div>
        </div>
        <a href="{{ route('projects.index') }}" class="no-underline inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors px-3 py-2 rounded-xl bg-white border border-slate-200 hover:border-slate-300 self-start sm:self-auto">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Projects
        </a>
    </div>

    {{-- ══════════════════════ KPI TILES ══════════════════════ --}}
    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:10px;">
        {{-- Total --}}
        <button wire:click="clearFilters" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='all' ? 'active border-slate-800' : 'bg-white border-slate-200 hover:border-slate-300' }}"
            style="{{ $statusFilter==='all' ? 'background:linear-gradient(135deg,#1e293b,#475569);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='all' ? 'text-white' : 'text-slate-900' }}">{{ $kpi['total'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='all' ? 'text-slate-300' : 'text-slate-400' }}">Total</div>
        </button>
        {{-- In Progress --}}
        <button wire:click="$set('statusFilter','in_progress')" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='in_progress' ? 'active border-blue-500' : 'bg-white border-slate-200 hover:border-blue-200' }}"
            style="{{ $statusFilter==='in_progress' ? 'background:linear-gradient(135deg,#3b82f6,#2563eb);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='in_progress' ? 'text-white' : 'text-blue-600' }}">{{ $kpi['in_progress'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='in_progress' ? 'text-blue-100' : 'text-slate-400' }}">Progress</div>
        </button>
        {{-- Not Started --}}
        <button wire:click="$set('statusFilter','not_started')" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='not_started' ? 'active border-slate-500' : 'bg-white border-slate-200 hover:border-slate-300' }}"
            style="{{ $statusFilter==='not_started' ? 'background:linear-gradient(135deg,#64748b,#475569);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='not_started' ? 'text-white' : 'text-slate-600' }}">{{ $kpi['not_started'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='not_started' ? 'text-slate-200' : 'text-slate-400' }}">Not Started</div>
        </button>
        {{-- On Hold --}}
        <button wire:click="$set('statusFilter','on_hold')" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='on_hold' ? 'active border-amber-400' : 'bg-white border-slate-200 hover:border-amber-200' }}"
            style="{{ $statusFilter==='on_hold' ? 'background:linear-gradient(135deg,#f59e0b,#d97706);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='on_hold' ? 'text-white' : 'text-amber-600' }}">{{ $kpi['on_hold'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='on_hold' ? 'text-amber-100' : 'text-slate-400' }}">On Hold</div>
        </button>
        {{-- Blocked --}}
        <button wire:click="$set('statusFilter','blocked')" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='blocked' ? 'active border-rose-500' : 'bg-white border-slate-200 hover:border-rose-200' }}"
            style="{{ $statusFilter==='blocked' ? 'background:linear-gradient(135deg,#ef4444,#dc2626);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='blocked' ? 'text-white' : 'text-rose-600' }}">{{ $kpi['blocked'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='blocked' ? 'text-rose-100' : 'text-slate-400' }}">Blocked</div>
        </button>
        {{-- Completed --}}
        <button wire:click="$set('statusFilter','completed')" type="button"
            class="kpi-tile rounded-2xl border py-3.5 px-2 text-center cursor-pointer {{ $statusFilter==='completed' ? 'active border-emerald-500' : 'bg-white border-slate-200 hover:border-emerald-200' }}"
            style="{{ $statusFilter==='completed' ? 'background:linear-gradient(135deg,#10b981,#059669);' : '' }}">
            <div class="text-2xl font-black font-mono leading-none {{ $statusFilter==='completed' ? 'text-white' : 'text-emerald-600' }}">{{ $kpi['completed'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 {{ $statusFilter==='completed' ? 'text-emerald-100' : 'text-slate-400' }}">Completed</div>
        </button>
        {{-- Overdue (no filter — just info) --}}
        <div class="kpi-tile rounded-2xl border bg-white border-slate-200 py-3.5 px-2 text-center">
            <div class="text-2xl font-black font-mono leading-none {{ $kpi['overdue'] > 0 ? 'text-rose-500' : 'text-slate-400' }}">{{ $kpi['overdue'] }}</div>
            <div class="text-[9.5px] font-bold uppercase tracking-widest mt-1.5 text-slate-400">Overdue</div>
        </div>
    </div>

    {{-- ══════════════════════ FILTER BAR ══════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">

        {{-- SEARCH + FILTER ROW --}}
        <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;flex-wrap:wrap;">
            {{-- Search --}}
            <div class="relative" style="flex:1;min-width:180px;">
                <svg style="position:absolute;left:11px;top:50%;transform:translateY(-50%);width:13px;height:13px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/>
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tasks, projects, assignees…"
                    style="width:100%;padding:7px 14px 7px 32px;font-size:11.5px;font-weight:500;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;color:#1e293b;outline:none;transition:border .15s;"
                    onfocus="this.style.borderColor='#94a3b8'" onblur="this.style.borderColor='#e2e8f0'"/>
            </div>
            {{-- Project --}}
            <select wire:model.live="projectFilter" class="at-filter-select" style="font-size:11.5px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;color:#475569;padding:7px 30px 7px 12px;outline:none;cursor:pointer;">
                <option value="all">All Projects</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}">{{ Str::limit($proj->name, 28) }}</option>
                @endforeach
            </select>
            {{-- Priority --}}
            <select wire:model.live="priorityFilter" class="at-filter-select" style="font-size:11.5px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;color:#475569;padding:7px 30px 7px 12px;outline:none;cursor:pointer;">
                <option value="all">All Priorities</option>
                <option value="high">🔴 High</option>
                <option value="medium">🟡 Medium</option>
                <option value="low">🟢 Low</option>
            </select>
            {{-- Sort --}}
            <select wire:model.live="sortBy" class="at-filter-select" style="font-size:11.5px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;color:#475569;padding:7px 30px 7px 12px;outline:none;cursor:pointer;">
                <option value="end_date">Sort: Due Date</option>
                <option value="priority">Sort: Priority</option>
                <option value="project">Sort: Project</option>
                <option value="title">Sort: Title A–Z</option>
            </select>
            {{-- Clear --}}
            @if($search || $statusFilter !== 'all' || $projectFilter !== 'all' || $priorityFilter !== 'all')
                <button wire:click="clearFilters" type="button"
                    style="display:flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;color:#94a3b8;cursor:pointer;white-space:nowrap;padding:0 4px;background:none;border:none;"
                    onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear all
                </button>
            @endif
        </div>
    </div>


    {{-- ══════════════════════ TABLE ══════════════════════ --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
        <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
            {{-- THead --}}
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                    <th style="width:36%;padding:10px 16px;text-align:left;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Task</th>
                    <th style="width:22%;padding:10px 12px;text-align:left;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Project</th>
                    <th style="width:14%;padding:10px 12px;text-align:left;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Assignee</th>
                    <th style="width:10%;padding:10px 12px;text-align:center;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Priority</th>
                    <th style="width:10%;padding:10px 12px;text-align:center;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Status</th>
                    <th style="width:8%;padding:10px 12px;text-align:center;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                    @php
                        $today     = now()->today();
                        $daysLeft  = $task->end_date ? (int) $today->diffInDays($task->end_date, false) : null;
                        $isOverdue = $daysLeft !== null && $daysLeft < 0 && !in_array($task->status->value, ['completed','cancelled']);
                        $isDueToday = $daysLeft === 0 && !in_array($task->status->value, ['completed','cancelled']);

                        [$priText, $priDot, $priBg, $priBorder] = match($task->priority?->value ?? 'medium') {
                            'high'  => ['High',   '#ef4444', '#fff5f5', '#fecaca'],
                            'low'   => ['Low',    '#10b981', '#f0fdf4', '#a7f3d0'],
                            default => ['Medium', '#f59e0b', '#fffbeb', '#fde68a'],
                        };

                        $statusBadge = $task->status->badgeClass();
                        $statusLabel = $task->status->label();

                        $dotColor = match($task->status->value) {
                            'in_progress'  => '#3b82f6',
                            'completed'    => '#10b981',
                            'on_hold'      => '#f59e0b',
                            'blocked'      => '#ef4444',
                            'under_review' => '#8b5cf6',
                            default        => '#94a3b8',
                        };

                        $initials = strtoupper(substr($task->assignedUser?->name ?? '?', 0, 1));
                        $avatarBgs = ['#e0e7ff','#fce7f3','#d1fae5','#fef3c7','#ede9fe','#fee2e2','#e0f2fe'];
                        $avatarBg  = $avatarBgs[crc32($task->assignedUser?->name ?? '') % 7];
                    @endphp
                    <tr class="at-table-row" style="border-bottom:1px solid #f8fafc;transition:background .12s;cursor:pointer;" onclick="window.location='{{ route('projects.show', $task->project_id) }}'">

                        {{-- Task --}}
                        <td style="padding:11px 16px;vertical-align:middle;">
                            <div style="display:flex;align-items:center;gap:9px;min-width:0;">
                                <span class="at-status-dot" style="background:{{ $dotColor }};flex-shrink:0;"></span>
                                <div style="min-width:0;">
                                    <div class="at-task-title" style="font-size:12px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;transition:color .12s;" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </div>
                                    @if($task->wbs_code)
                                        <div style="font-size:10px;font-family:monospace;color:#cbd5e1;margin-top:1px;">{{ $task->wbs_code }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Project --}}
                        <td style="padding:11px 12px;vertical-align:middle;">
                            <div style="font-size:11.5px;font-weight:600;color:#334155;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $task->project->name ?? '' }}">
                                {{ $task->project->name ?? '—' }}
                            </div>
                            @if($task->project?->subsidiary?->name)
                                <div style="font-size:10px;color:#94a3b8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-top:1px;">
                                    {{ $task->project->subsidiary->name }}
                                </div>
                            @endif
                        </td>

                        {{-- Assignee --}}
                        <td style="padding:11px 12px;vertical-align:middle;">
                            @if($task->assignedUser)
                                <div style="display:flex;align-items:center;gap:7px;">
                                    <div class="at-avatar" style="background:{{ $avatarBg }};color:#475569;">{{ $initials }}</div>
                                    <span style="font-size:11px;font-weight:600;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ explode(' ', $task->assignedUser->name)[0] }}
                                    </span>
                                </div>
                            @else
                                <span style="font-size:11px;color:#cbd5e1;font-weight:500;">Unassigned</span>
                            @endif
                        </td>

                        {{-- Priority --}}
                        <td style="padding:11px 12px;vertical-align:middle;text-align:center;">
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:9.5px;font-weight:800;padding:3px 8px;border-radius:8px;background:{{ $priBg }};border:1px solid {{ $priBorder }};">
                                <span style="width:5px;height:5px;border-radius:50%;background:{{ $priDot }};flex-shrink:0;"></span>
                                {{ $priText }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td style="padding:11px 12px;vertical-align:middle;text-align:center;">
                            <span class="{{ $statusBadge }}" style="display:inline-block;font-size:9.5px;font-weight:800;padding:3px 8px;border-radius:8px;border:1px solid;white-space:nowrap;">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Due Date --}}
                        <td style="padding:11px 12px;vertical-align:middle;text-align:center;">
                            @if($task->end_date)
                                <div style="font-size:11px;font-weight:800;font-family:monospace;color:{{ $isOverdue ? '#ef4444' : ($isDueToday ? '#d97706' : '#475569') }};">
                                    {{ $task->end_date->format('d M') }}
                                </div>
                                @if($isOverdue)
                                    <div style="font-size:9px;font-weight:700;color:#ef4444;margin-top:1px;">{{ abs($daysLeft) }}d late</div>
                                @elseif($isDueToday)
                                    <div style="font-size:9px;font-weight:700;color:#d97706;margin-top:1px;">Today</div>
                                @elseif($daysLeft !== null && $daysLeft <= 3)
                                    <div style="font-size:9px;font-weight:700;color:#f59e0b;margin-top:1px;">{{ $daysLeft }}d left</div>
                                @endif
                            @else
                                <span style="font-size:11px;color:#cbd5e1;">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:64px 20px;text-align:center;">
                            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                                <div style="width:48px;height:48px;border-radius:14px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div>
                                    <p style="font-size:13px;font-weight:700;color:#64748b;margin:0;">No tasks found</p>
                                    <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">Try adjusting your search or filters</p>
                                </div>
                                <button wire:click="clearFilters" type="button" style="font-size:11.5px;font-weight:700;color:#c3122e;cursor:pointer;background:none;border:none;text-decoration:underline;">
                                    Clear all filters
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer --}}
        @if($tasks->total() > 0)
            <div style="padding:12px 16px;border-top:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:11.5px;color:#64748b;font-weight:500;">
                        Showing <strong style="color:#1e293b;">{{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}</strong>
                        of <strong style="color:#1e293b;">{{ $tasks->total() }}</strong> tasks
                    </span>
                    <select wire:model.live="perPage" class="at-filter-select" style="font-size:11px;font-weight:700;border-radius:8px;border:1px solid #e2e8f0;background:white;color:#475569;padding:5px 24px 5px 10px;outline:none;cursor:pointer;">
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                        <option value="100">100 / page</option>
                    </select>
                </div>
                <div>{{ $tasks->links() }}</div>
            </div>
        @endif
    </div>

</div>
