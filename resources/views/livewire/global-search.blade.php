<div class="card p-4 shadow-2xl border-slate-700/60">
    <div class="relative mb-3">
        <input
            type="text"
            wire:model.live.debounce.250ms="query"
            placeholder="Search projects, WBS items, staff, files..."
            class="form-input text-sm pl-9"
            autofocus
        >
        <svg class="w-4 h-4 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>

    @if(strlen($query) >= 2)
        <div class="max-h-80 overflow-y-auto space-y-4 text-xs">
            <!-- Projects -->
            @if(count($results['projects']) > 0)
                <div>
                    <h4 class="font-bold text-[10px] uppercase text-[#e8556a] mb-1">Projects</h4>
                    @foreach($results['projects'] as $p)
                        <a href="{{ route('projects.show', $p) }}" class="block p-2 rounded-lg hover:bg-slate-800 transition-colors">
                            <span class="font-bold text-slate-200">{{ $p->name }}</span>
                            <span class="text-slate-500 font-mono">({{ $p->code }})</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- WBS Items -->
            @if(count($results['wbs']) > 0)
                <div>
                    <h4 class="font-bold text-[10px] uppercase text-cyan-400 mb-1">WBS Items</h4>
                    @foreach($results['wbs'] as $w)
                        <div class="p-2 rounded-lg hover:bg-slate-800 transition-colors flex items-center justify-between">
                            <div>
                                <span class="font-mono text-[#e8556a] font-bold">{{ $w->wbs_code }}</span>
                                <span class="text-slate-200 ml-1">{{ $w->title }}</span>
                            </div>
                            <span class="badge-slate text-[9px]">{{ $w->item_type->label() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Users -->
            @if(count($results['users']) > 0)
                <div>
                    <h4 class="font-bold text-[10px] uppercase text-emerald-400 mb-1">Users</h4>
                    @foreach($results['users'] as $u)
                        <div class="p-2 rounded-lg hover:bg-slate-800 transition-colors">
                            <span class="font-bold text-slate-200">{{ $u->name }}</span>
                            <span class="text-slate-500 font-mono">({{ $u->email }})</span>
                        </div>
                    @endforeach
                </div>
            @endif

            @if(empty($results['projects']) && empty($results['wbs']) && empty($results['users']))
                <div class="text-center py-6 text-slate-500">No matching records found for "{{ $query }}".</div>
            @endif
        </div>
    @endif
</div>
