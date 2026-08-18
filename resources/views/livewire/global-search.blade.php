<div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden p-3 font-sans">
    <div class="relative mb-2">
        <input
            id="global-search-input"
            type="text"
            wire:model.live.debounce.200ms="query"
            placeholder="Search projects, tasks, staff, files (⌘K)..."
            class="w-full text-xs font-semibold pl-9 pr-12 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none text-slate-900 transition-all"
            autofocus
        >
        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-mono font-bold text-slate-400 bg-white border border-slate-200 px-1.5 py-0.5 rounded">
            ESC
        </span>
    </div>

    @if(strlen($query) >= 2)
        <div class="max-h-80 overflow-y-auto space-y-3 p-1 text-xs scrollbar-thin">
            <!-- Projects -->
            @if(count($results['projects']) > 0)
                <div class="space-y-1">
                    <div class="flex items-center justify-between px-2 text-[10px] font-black uppercase tracking-wider text-[#c3122e]">
                        <span>🚀 Projects ({{ count($results['projects']) }})</span>
                    </div>
                    @foreach($results['projects'] as $p)
                        <a href="{{ route('projects.show', $p) }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-rose-50/80 transition-colors group">
                            <div class="min-w-0 pr-2">
                                <span class="font-bold text-slate-900 group-hover:text-[#c3122e] transition-colors block truncate">{{ $p->name }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $p->code }} · {{ $p->subsidiary->name ?? 'George Steuart' }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-700 flex-shrink-0">
                                {{ $p->status->label() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- WBS Items -->
            @if(count($results['wbs']) > 0)
                <div class="space-y-1 pt-1 border-t border-slate-100">
                    <div class="flex items-center justify-between px-2 text-[10px] font-black uppercase tracking-wider text-blue-600">
                        <span>📋 Tasks &amp; Milestones ({{ count($results['wbs']) }})</span>
                    </div>
                    @foreach($results['wbs'] as $w)
                        <a href="{{ route('projects.show', $w->project_id) }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-blue-50/80 transition-colors group">
                            <div class="min-w-0 pr-2">
                                <span class="font-bold text-slate-900 group-hover:text-blue-700 transition-colors block truncate">{{ $w->title }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $w->wbs_code }} · {{ $w->project->name ?? 'Project' }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-mono font-bold bg-blue-50 text-blue-700 border border-blue-100 flex-shrink-0">
                                {{ $w->progress }}%
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Users -->
            @if(count($results['users']) > 0)
                <div class="space-y-1 pt-1 border-t border-slate-100">
                    <div class="flex items-center justify-between px-2 text-[10px] font-black uppercase tracking-wider text-emerald-600">
                        <span>👥 Team Members ({{ count($results['users']) }})</span>
                    </div>
                    @foreach($results['users'] as $u)
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-emerald-50/80 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-full bg-[#c3122e] text-white flex items-center justify-center text-[10px] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-slate-900 block truncate">{{ $u->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono truncate block">{{ $u->email }}</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600">
                                {{ $u->role_name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- No results -->
            @if(empty($results['projects']) && empty($results['wbs']) && empty($results['users']) && empty($results['subsidiaries']) && empty($results['documents']))
                <div class="text-center py-6 text-slate-400">
                    <p class="text-xs font-semibold">No records found for "<strong class="text-slate-700">{{ $query }}</strong>"</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Try searching by project code, manager name, or task title.</p>
                </div>
            @endif
        </div>
    @else
        <div class="px-3 py-4 text-center text-slate-400 space-y-1">
            <p class="text-[11px] font-bold text-slate-600">⚡ Universal Instant Search</p>
            <p class="text-[10px] text-slate-400">Type at least 2 characters to search across projects, WBS tasks, and team members.</p>
        </div>
    @endif
</div>
