@props([
    'title',
    'statusKey' => 'not_started',
    'badge' => 'bg-slate-100 text-slate-700',
    'accentColor' => '#64748b',
    'tasks' => collect(),
    'emptyText' => 'No tasks',
    'colType' => 'default'
])

<div class="flex-1 min-w-[260px] max-w-[320px] bg-slate-50/70 rounded-2xl border border-slate-200/90 flex flex-col shadow-2xs transition-all">
    <!-- Top Accent Line -->
    @if($accentColor !== '#64748b')
        <div class="h-1 rounded-t-2xl flex-shrink-0" style="background: {{ $accentColor }};"></div>
    @endif

    <!-- Column Header -->
    <div class="p-3.5 flex items-center justify-between gap-2 border-b border-slate-200/60 {{ $accentColor === '#64748b' ? 'rounded-t-2xl' : '' }}">
        <div class="flex items-center gap-2 min-w-0">
            <h3 class="text-xs font-black text-slate-900 tracking-tight truncate" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $title }}
            </h3>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $badge }} border border-black/5 flex-shrink-0 font-mono">
                {{ $tasks->count() }}
            </span>
        </div>
        <div class="text-slate-400 hover:text-slate-600 cursor-pointer p-1 rounded-lg">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
            </svg>
        </div>
    </div>

    <!-- Cards Container -->
    <div class="p-3 space-y-2.5 overflow-y-auto max-h-[calc(100vh-320px)] scrollbar-thin flex-1">
        @forelse($tasks as $t)
            @php
                $user = auth()->user();
                $assignee = $t->assignedUser ?? $user;
                $assigneeName = $assignee->name ?? 'User';
                $nameParts = explode(' ', trim($assigneeName));
                $initials = count($nameParts) >= 2 
                    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts) - 1], 0, 1))
                    : strtoupper(substr($assigneeName, 0, 2));

                $priStr = ucfirst($t->priority->value ?? 'medium');
                $priBadge = match(strtolower($priStr)) {
                    'critical', 'high' => 'bg-rose-50 text-rose-600 border border-rose-100',
                    'low' => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
                    default => 'bg-amber-50 text-amber-700 border border-amber-100',
                };

                $isCompleted = $t->status->value === 'completed';
                $isBlocked = $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0);
            @endphp

            <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 space-y-2.5 group relative">
                <!-- Top Row: Title + Priority -->
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-1.5 min-w-0 flex-1">
                        @if($isCompleted)
                            <div class="w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        @endif
                        <h4 class="text-xs font-bold text-slate-900 leading-snug {{ $isCompleted ? 'text-slate-500 line-through' : 'group-hover:text-[#c3122e] transition-colors' }} truncate" title="{{ $t->title }}">
                            {{ $t->title }}
                        </h4>
                    </div>

                    <div class="flex items-center gap-1 flex-shrink-0">
                        @if($isBlocked)
                            <span class="text-rose-600 text-xs" title="Task Blocked">🚫</span>
                        @endif
                        <span class="text-[9.5px] font-extrabold px-2 py-0.5 rounded-md {{ $priBadge }}">
                            {{ $priStr }}
                        </span>
                    </div>
                </div>

                <!-- Subtitle: Project Name -->
                <p class="text-[11px] text-slate-400 font-medium truncate">
                    {{ $t->project->name ?? 'General Delivery' }}
                </p>

                <!-- Bottom Row: Due Date + Assignee Avatar Circle -->
                <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100">
                    <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 font-mono">
                        <svg class="w-3.5 h-3.5 {{ $colType === 'blocked' ? 'text-rose-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $t->end_date ? $t->end_date->format('M d, Y') : ($t->start_date ? $t->start_date->format('M d, Y') : 'No Date') }}</span>
                    </div>

                    <!-- Assignee Circle -->
                    <div class="w-6 h-6 rounded-full bg-slate-900 text-white text-[9px] font-black flex items-center justify-center shadow-2xs flex-shrink-0" title="{{ $assigneeName }}">
                        {{ $initials }}
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-400 text-xs font-medium">
                {{ $emptyText }}
            </div>
        @endforelse
    </div>

    <!-- Column Footer: + Add Task -->
    <div class="p-2 border-t border-slate-200/60">
        <button 
            wire:click="openAddTaskModal('{{ $statusKey }}')" 
            type="button" 
            class="w-full py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-white border border-transparent hover:border-slate-200 transition-all flex items-center justify-center gap-1 cursor-pointer"
        >
            <span class="text-sm leading-none">+</span>
            <span>Add Task</span>
        </button>
    </div>
</div>
