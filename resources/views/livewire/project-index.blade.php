<div>
    <!-- Top Title & Primary Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Projects Directory</h1>
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-1 font-medium">Manage, monitor, and track progress across all corporate projects</p>
        </div>

        @if(auth()->user()?->hasAnyRole(['super_admin', 'project_manager']) || auth()->user()?->email === 'admin@nexuspm.local')
            <a
                href="{{ route('projects.create') }}"
                class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/20 transition-all flex items-center gap-2 cursor-pointer active:scale-95 sm:w-auto justify-center"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Create New Project</span>
            </a>
        @endif
    </div>

    <!-- 5 Key Summary Cards Bar -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <!-- Total Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">Total Projects</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalCount }}</div>
                <div class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    <span>↑ Portfolio Scope</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">Active Projects</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $activeCount }}</div>
                <div class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    <span>↑ Execution Phase</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">Completed</span>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $completedCount }}</div>
                <div class="text-[10px] font-bold text-emerald-600 mt-1 flex items-center gap-0.5">
                    <span>✓ Delivered</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-[#fdf8e8] border border-[#fdf0c8] flex items-center justify-center text-[#b8860b] flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
        </div>

        <!-- Overdue Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">Overdue</span>
                <div class="text-2xl font-black text-rose-600 mt-1">{{ $overdueCount }}</div>
                <div class="text-[10px] font-bold text-rose-600 mt-1 flex items-center gap-0.5">
                    <span>⚠️ Past Deadline</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- On Hold Projects -->
        <div class="card p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">On Hold</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $onHoldCount }}</div>
                <div class="text-[10px] font-bold text-amber-600 mt-1">
                    <span>⏸️ Paused</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Unified Filters & Export Toolbar Card -->
    <div class="card mb-6 p-4 space-y-3.5">
        <!-- Search & Quick Action Row -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by project name, code..." class="form-input pl-9 text-xs font-semibold w-full">
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('reports.export-csv') }}" class="btn-secondary btn-sm text-xs font-bold flex items-center gap-1.5 border-slate-200 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export CSV
                </a>
                <a href="{{ route('reports.export-pdf') }}" class="btn-secondary btn-sm text-xs font-bold text-[#c3122e] border-rose-200 bg-rose-50/50 hover:bg-rose-50">
                    <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- 5 Dropdown Filters Grid (Responsive 5-Column Alignment) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-2 border-t border-slate-100">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Subsidiary</span>
                <select wire:model.live="subsidiaryFilter" class="form-select text-xs font-bold py-1.5">
                    <option value="all">All Subsidiaries</option>
                    @foreach($subsidiaries as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Project Manager</span>
                <select wire:model.live="managerFilter" class="form-select text-xs font-bold py-1.5">
                    <option value="all">All Managers</option>
                    @foreach($pms as $pm)
                        <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Status</span>
                <select wire:model.live="statusFilter" class="form-select text-xs font-bold py-1.5">
                    <option value="all">All Statuses</option>
                    @foreach(\App\Enums\ProjectStatus::cases() as $st)
                        <option value="{{ $st->value }}">{{ $st->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Priority</span>
                <select wire:model.live="priorityFilter" class="form-select text-xs font-bold py-1.5">
                    <option value="all">All Priorities</option>
                    @foreach(\App\Enums\Priority::cases() as $pr)
                        <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-500 ml-1 mb-1">Health</span>
                <select wire:model.live="healthFilter" class="form-select text-xs font-bold py-1.5">
                    <option value="all">All Health Levels</option>
                    @foreach(\App\Enums\ProjectHealth::cases() as $h)
                        <option value="{{ $h->value }}">{{ $h->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Projects Data Table -->
    <div class="card p-0 overflow-hidden shadow-sm mb-6 border border-slate-200/90 rounded-2xl bg-white">
        <div class="overflow-x-auto">
            <table class="data-table w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200">
                        <th class="w-10 py-4 px-4 text-center"><input type="checkbox" class="rounded border-slate-300 text-[#c3122e]"></th>
                        <th class="py-4 px-4 font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">PROJECT</th>
                        <th class="py-4 px-4 font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">SUBSIDIARY</th>
                        <th class="py-4 px-4 font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">PROJECT MANAGER</th>
                        <th class="py-4 px-4 font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">STATUS</th>
                        <th class="py-4 px-4 font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">DEADLINE</th>
                        <th class="py-4 px-4 text-right font-black text-slate-800 text-xs uppercase tracking-wider whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $project)
                        @php
                            $daysLeft = $project->deadline ? (int) now()->today()->diffInDays($project->deadline, false) : null;
                        @endphp
                        <tr class="hover:bg-[#fdf4f4]/40 transition-colors group">
                            <td class="w-10 py-4 px-4 text-center"><input type="checkbox" class="rounded border-slate-300 text-[#c3122e]"></td>

                            <!-- PROJECT NAME & CODE -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="min-w-0">
                                    <a href="{{ route('projects.show', $project) }}" class="font-extrabold text-slate-900 text-xs group-hover:text-[#c3122e] leading-snug block truncate max-w-52">
                                        {{ $project->name }}
                                    </a>
                                    <div class="text-[11px] font-mono font-black text-[#c3122e] mt-0.5">{{ $project->code }}</div>
                                </div>
                            </td>

                            <!-- SUBSIDIARY -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-xs font-bold text-slate-800">
                                    {{ $project->subsidiary->name ?? '-' }}
                                </span>
                            </td>

                            <!-- PROJECT MANAGER -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="avatar-sm w-7 h-7 bg-[#c3122e] text-xs font-bold text-white flex items-center justify-center rounded-full flex-shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($project->projectManager->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">{{ $project->projectManager->name ?? 'Unassigned' }}</span>
                                </div>
                            </td>

                            <!-- STATUS -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($project->status->value === 'in_progress')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200/90 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        In Progress
                                    </span>
                                @elseif($project->status->value === 'planning')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-900 border border-blue-200/90 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Planning
                                    </span>
                                @elseif($project->status->value === 'completed')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-900 border border-emerald-200/90 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Completed
                                    </span>
                                @elseif($project->status->value === 'on_hold')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-900 border border-orange-200/90 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                        On Hold
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200 whitespace-nowrap">
                                        {{ $project->status->label() }}
                                    </span>
                                @endif
                            </td>

                            <!-- DEADLINE -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-xs font-extrabold text-slate-900">
                                    {{ $project->deadline ? $project->deadline->format('M d, Y') : '-' }}
                                </div>
                                @if($project->deadline)
                                    <div class="text-[10px] font-extrabold mt-0.5 {{ $project->status->value === 'completed' ? 'text-emerald-700' : ($daysLeft < 0 ? 'text-rose-700' : ($daysLeft === 0 ? 'text-amber-700 font-black' : 'text-slate-500')) }}">
                                        @if($project->status->value === 'completed')
                                            ✓ Completed
                                        @elseif($daysLeft < 0)
                                            ⚠️ {{ abs($daysLeft) }}d overdue
                                        @elseif($daysLeft === 0)
                                            ⏰ Due Today
                                        @else
                                            📅 {{ $daysLeft }}d left
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <!-- ACTIONS -->
                            <td class="py-4 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('projects.show', $project) }}" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-2xs hover:shadow-md transition-all inline-flex items-center gap-1">
                                        <span>Workspace</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </a>

                                    @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->email === 'admin@nexuspm.local' || auth()->user()?->id === 1)
                                        <button wire:click="edit({{ $project->id }})" class="p-1.5 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all border border-slate-200" title="Edit Project">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="deleteProject({{ $project->id }})" wire:confirm="Are you sure you want to delete '{{ $project->name }}'? This will move the project to trash." class="p-1.5 rounded-xl text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 transition-all" title="Delete Project (Super Admin Only)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-12 px-4">
                                <div class="max-w-md mx-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 00-2-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium mb-4 leading-relaxed">
                                        @if(auth()->user()?->hasRole('team_member') && !auth()->user()?->hasRole('super_admin'))
                                            You have not been assigned to any projects yet. Please contact your Project Manager to assign you to a project workspace.
                                        @else
                                            No corporate projects match your active search or assigned role permissions.
                                        @endif
                                    </p>
                                    @if($search || $subsidiaryFilter !== 'all' || $managerFilter !== 'all' || $statusFilter !== 'all' || $priorityFilter !== 'all' || $healthFilter !== 'all')
                                        <button wire:click="$set('search', ''); $set('subsidiaryFilter', 'all'); $set('managerFilter', 'all'); $set('statusFilter', 'all'); $set('priorityFilter', 'all'); $set('healthFilter', 'all')"
                                                class="btn-secondary btn-xs font-bold text-[#c3122e]">
                                            Reset All Filters
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="p-4 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
            <div>
                Showing <span class="font-bold text-slate-900">{{ $projects->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $projects->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-900">{{ $projects->total() }}</span> projects
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span>Per page:</span>
                    <select wire:model.live="perPage" class="form-select text-xs font-bold py-1 px-2">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                    </select>
                </div>
                {{ $projects->links() }}
            </div>
        </div>
    </div>

    <!-- Edit / Create Project Modal -->
    <div x-data="{ open: @entangle('showModal') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
    >
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.closeModal()"></div>

        <div class="relative bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-5 border border-slate-200/90 z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">{{ $editingId ? 'Edit Project Settings' : 'Create New Project' }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Update project scope, owner, status, and target deadline</p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.closeModal()" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Name <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]">
                        @error('name') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Code <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="code" class="w-full text-xs font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                        @error('code') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Subsidiary <span class="text-rose-500">*</span></label>
                        <select wire:model="subsidiary_id" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:border-[#c3122e]">
                            <option value="">Select Subsidiary...</option>
                            @foreach($subsidiaries as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('subsidiary_id') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Manager / Lead <span class="text-rose-500">*</span></label>
                        <select wire:model="project_manager_id" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:border-[#c3122e]">
                            <option value="">Select Manager...</option>
                            @foreach($pms as $pm)
                                <option value="{{ $pm->id }}">{{ $pm->name }}</option>
                            @endforeach
                        </select>
                        @error('project_manager_id') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Status</label>
                        <select wire:model="status" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                            @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                <option value="{{ $st->value }}">{{ $st->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Priority</label>
                        <select wire:model="priority" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:border-[#c3122e]">
                            @foreach(\App\Enums\Priority::cases() as $pr)
                                <option value="{{ $pr->value }}">{{ $pr->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Start Date</label>
                        <input type="date" wire:model="start_date" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200">
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-800 mb-1">Deadline Date</label>
                        <input type="date" wire:model="deadline" class="w-full text-xs font-bold py-2.5 px-3 rounded-xl border border-slate-200">
                        @error('deadline') <span class="text-[11px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-800 mb-1">Project Description & Scope</label>
                    <textarea wire:model="description" rows="3" placeholder="Overview of project objectives..." class="w-full text-xs rounded-xl p-3 border border-slate-200 focus:border-[#c3122e]"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.closeModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md cursor-pointer">Save Project Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
