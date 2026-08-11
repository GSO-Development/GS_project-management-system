<div>
    {{-- ══════════════════════════════════════════════════════
         PM TEAM MEMBERS PAGE
    ══════════════════════════════════════════════════════ --}}

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#c3122e,#8b0d1f)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Project Participants</h1>
            </div>
            <p class="text-xs text-slate-500 font-medium">Members across your assigned projects &amp; their task workloads</p>
        </div>

        {{-- Stats chips --}}
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-xs flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                <span class="text-xs font-bold text-slate-700">{{ $activeMembers }} Active</span>
            </div>
            <div class="px-4 py-2 rounded-xl bg-white border border-slate-200 shadow-xs flex items-center gap-2">
                <div class="w-2 h-2 rounded-full" style="background:#c3122e;"></div>
                <span class="text-xs font-bold text-slate-700">{{ $totalMembers }} Total</span>
            </div>
        </div>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="card mb-6 p-3 flex flex-col lg:flex-row gap-3 items-center justify-between">
        {{-- Search --}}
        <div class="relative w-full lg:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input id="pm-member-search" type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search name or email..."
                   class="form-input pl-9 text-xs">
        </div>

        <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
            {{-- Project filter --}}
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Project</span>
                <select id="pm-member-project-filter" wire:model.live="projectFilter" class="form-select text-xs min-w-44 py-1.5">
                    <option value="all">All My Projects</option>
                    @foreach($managedProjects as $p)
                        <option value="{{ $p->id }}">{{ $p->code }} – {{ Str::limit($p->name, 28) }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Status filter --}}
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Status</span>
                <select id="pm-member-status-filter" wire:model.live="statusFilter" class="form-select text-xs min-w-28 py-1.5">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {{-- ── Member Grid ── --}}
    @if($members->isEmpty())
        <div class="card flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-slate-400 text-sm font-medium">No team members found</p>
            <p class="text-slate-300 text-xs mt-1">Try adjusting your filters or assign members to your projects</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
            @foreach($members as $member)
                @php
                    $roleName   = $member->getRoleNames()->first() ?? 'team_member';
                    $roleColor  = $roleName === 'super_admin'
                        ? 'bg-[#fdf4f4] text-[#c3122e] border-[#f0dada]'
                        : ($roleName === 'project_manager'
                            ? 'bg-[#fdf8e8] text-[#b8860b] border-[#f0e0a0]'
                            : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                    $avatarGrad = 'linear-gradient(135deg,'.['#6366f1,#8b5cf6','#0ea5e9,#2563eb','#10b981,#059669','#f59e0b,#d97706','#c3122e,#8b0d1f'][crc32($member->email) % 5].')';
                    $memberProjectCount = $member->projects->whereIn('id', $managedProjects->pluck('id'))->count();
                @endphp

                <div class="card p-0 overflow-hidden hover:shadow-md transition-all duration-200 group cursor-pointer"
                     wire:click="openDetail({{ $member->id }})" id="pm-member-card-{{ $member->id }}">
                    {{-- Top accent bar --}}
                    <div class="h-1.5 w-full" style="background:{{ $avatarGrad }}"></div>

                    <div class="p-5">
                        {{-- Header row --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold text-base flex-shrink-0" style="background:{{ $avatarGrad }}">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 text-sm leading-tight truncate">{{ $member->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono truncate">{{ $member->email }}</div>
                                </div>
                            </div>
                            {{-- Status dot --}}
                            <div class="flex items-center gap-1 mt-0.5">
                                <span class="w-2 h-2 rounded-full {{ $member->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                <span class="text-[10px] font-semibold {{ $member->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                    {{ $member->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        {{-- Role badge --}}
                        <div class="mb-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $roleColor }}">
                                {{ ucwords(str_replace('_', ' ', $roleName)) }}
                            </span>
                        </div>

                        {{-- Info chips --}}
                        <div class="flex flex-wrap gap-2 text-[10px] text-slate-500">
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-50 border border-slate-100">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $member->subsidiary->name ?? 'Global' }}
                            </span>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-50 border border-slate-100">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                {{ $memberProjectCount }} {{ Str::plural('project', $memberProjectCount) }}
                            </span>
                            @if($member->phone_number)
                                <span class="flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-50 border border-slate-100 font-mono">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $member->phone_number }}
                                </span>
                            @endif
                        </div>

                        {{-- View Details & Remove row --}}
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <button wire:click.stop="removeCollaboratorFromMyProjects({{ $member->id }})" wire:confirm="Remove {{ $member->name }} from all your managed projects?" type="button" class="text-[10px] font-extrabold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-2 py-1 rounded-lg border border-rose-200 transition-all flex items-center gap-1 cursor-pointer">
                                <span>🗑️ Remove Collaborator</span>
                            </button>
                            <span class="text-[10px] font-bold text-[#c3122e] group-hover:underline flex items-center gap-0.5">
                                View Profile
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="card p-4 border-t border-slate-100">
            {{ $members->links() }}
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MEMBER DETAIL MODAL
    ══════════════════════════════════════════════════════ --}}
    <div x-data="{ open: @entangle('showDetailModal') }"
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
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
             @click="open = false; $wire.closeDetail()"></div>

        {{-- Modal --}}
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-2xl z-10 overflow-y-auto max-h-[90vh]"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            @if($detailMember)
                @php
                    $dr = $detailMember->getRoleNames()->first() ?? 'team_member';
                    $dg = 'linear-gradient(135deg,'.['#6366f1,#8b5cf6','#0ea5e9,#2563eb','#10b981,#059669','#f59e0b,#d97706','#c3122e,#8b0d1f'][crc32($detailMember->email) % 5].')';
                @endphp

                {{-- Header gradient banner --}}
                <div class="h-28 rounded-t-2xl relative" style="background:{{ $dg }}">
                    <button type="button" @click="open = false; $wire.closeDetail()"
                            class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    {{-- Avatar --}}
                    <div class="absolute -bottom-7 left-6 w-16 h-16 rounded-2xl border-4 border-white shadow-lg flex items-center justify-center text-2xl font-bold text-white" style="background:{{ $dg }}">
                        {{ strtoupper(substr($detailMember->name, 0, 1)) }}
                    </div>
                </div>

                {{-- Body --}}
                <div class="pt-10 px-6 pb-6">
                    {{-- Name & role --}}
                    <div class="flex items-start justify-between mb-1">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $detailMember->name }}</h2>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $detailMember->email }}</p>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 rounded-full {{ $detailMember->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span class="text-xs font-bold {{ $detailMember->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $detailMember->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    {{-- Info grid --}}
                    <div class="grid grid-cols-3 gap-3 mt-4 mb-5">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-xs text-slate-400 mb-1">Role</div>
                            <div class="text-xs font-bold text-slate-700">{{ ucwords(str_replace('_', ' ', $dr)) }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-xs text-slate-400 mb-1">Subsidiary</div>
                            <div class="text-xs font-bold text-slate-700">{{ $detailMember->subsidiary->name ?? 'Global' }}</div>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-center">
                            <div class="text-xs text-slate-400 mb-1">Phone</div>
                            <div class="text-xs font-bold text-slate-700 font-mono">{{ $detailMember->phone_number ?? '—' }}</div>
                        </div>
                    </div>

                    {{-- Projects under this PM --}}
                    @if($memberProjects->count())
                        <div class="mb-5">
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Assigned to Your Projects</h3>
                            <div class="space-y-2">
                                @foreach($memberProjects as $mp)
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#fdf4f4]/60 border border-[#faeaea]">
                                        <div>
                                            <span class="text-xs font-bold text-slate-800">{{ $mp->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono ml-2">{{ $mp->code }}</span>
                                        </div>
                                        <span class="badge-{{ $mp->status->color() }} text-[10px]">{{ $mp->status->label() }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Recent Tasks --}}
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5">Recent Task Assignments</h3>
                        @if($memberTasks->isEmpty())
                            <p class="text-xs text-slate-400 text-center py-4">No tasks assigned in your projects</p>
                        @else
                            <div class="space-y-2">
                                @foreach($memberTasks as $task)
                                    @php
                                        $tStatus = $task->status ?? 'not_started';
                                        $tColor  = match($tStatus) {
                                            'completed'   => 'text-emerald-600 bg-emerald-50 border-emerald-200',
                                            'in_progress' => 'text-blue-600 bg-blue-50 border-blue-200',
                                            'on_hold'     => 'text-amber-600 bg-amber-50 border-amber-200',
                                            'cancelled'   => 'text-slate-400 bg-slate-50 border-slate-200',
                                            default       => 'text-slate-500 bg-slate-50 border-slate-200',
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white border border-slate-100 hover:border-slate-200 transition-colors">
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-semibold text-slate-800 truncate">{{ $task->title }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $task->project->name ?? '—' }} • {{ $task->end_date?->format('M d, Y') ?? 'No deadline' }}</div>
                                        </div>
                                        <span class="ml-3 text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $tColor }} flex-shrink-0">
                                            {{ ucwords(str_replace('_', ' ', $tStatus)) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
