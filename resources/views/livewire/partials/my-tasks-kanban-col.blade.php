@props([
    'title',
    'icon' => null,
    'statusKey' => 'not_started',
    'badge' => 'bg-slate-100 text-slate-700',
    'accentColor' => '#64748b',
    'tasks' => collect(),
    'emptyText' => 'No tasks in this schedule.',
    'colType' => 'default'
])

<div class="min-w-[290px] w-[290px] sm:min-w-[320px] sm:w-[320px] flex-shrink-0 bg-slate-50/90 rounded-2xl border border-slate-200/90 flex flex-col shadow-xs transition-all overflow-hidden">
    <!-- Top Accent Bar -->
    <div class="h-1.5 w-full flex-shrink-0" style="background: {{ $accentColor }};"></div>

    <!-- Column Header -->
    <div class="p-3.5 px-4 flex items-center justify-between gap-2 border-b border-slate-200/80 bg-white">
        <div class="flex items-center gap-2 min-w-0">
            @if($icon)
                <span class="text-sm flex-shrink-0">{{ $icon }}</span>
            @endif
            <h3 class="text-xs font-extrabold text-slate-900 tracking-tight whitespace-nowrap" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                {{ $title }}
            </h3>
            <span class="px-2 py-0.5 rounded-full text-[10.5px] font-black {{ $badge }} border border-black/5 flex-shrink-0 font-mono shadow-2xs">
                {{ $tasks->count() }}
            </span>
        </div>
        <div class="w-2.5 h-2.5 rounded-full" style="background: {{ $accentColor }};"></div>
    </div>

    <!-- Cards Container -->
    <div class="p-3 space-y-3 overflow-y-auto max-h-[calc(100vh-270px)] scrollbar-thin flex-1">
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
                $priClass = match(strtolower($priStr)) {
                    'critical', 'high' => 'background: #fff1f2; color: #be123c; border: 1px solid #fecdd3;',
                    'low' => 'background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;',
                    default => 'background: #fffbeb; color: #b45309; border: 1px solid #fde68a;',
                };

                $isCompleted = $t->status->value === 'completed';
                $isBlocked = $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0);
                $isOverdue = $t->end_date && $t->end_date->isPast() && !$isCompleted;
                $subtasksCount = $t->children ? $t->children->count() : 0;
                $subtasksDone = $t->children ? $t->children->where('status.value', 'completed')->count() : 0;
            @endphp

            <div 
                wire:click="openTaskDetail({{ $t->id }})" 
                class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all duration-200 space-y-2.5 group relative cursor-pointer {{ $isCompleted ? 'bg-slate-50/70 opacity-75' : '' }}"
            >
                <!-- Top Row: Checkbox + Title + Badges + Context Menu -->
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-start gap-2.5 min-w-0 flex-1">
                        <!-- Fast Toggle Complete Checkbox -->
                        <button 
                            wire:click.stop="toggleTaskComplete({{ $t->id }})" 
                            type="button" 
                            class="w-4.5 h-4.5 mt-0.5 rounded-full border transition-all flex items-center justify-center flex-shrink-0 cursor-pointer {{ $isCompleted ? 'bg-emerald-500 border-emerald-500 text-white shadow-2xs' : 'border-slate-300 hover:border-emerald-500 hover:bg-emerald-50 text-transparent hover:text-emerald-600' }}"
                            title="{{ $isCompleted ? 'Mark as Incomplete' : 'Mark as Completed' }}"
                        >
                            <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            <!-- Eyebrow Badges: WBS Code + Milestone -->
                            <div class="flex items-center gap-1.5 flex-wrap mb-1">
                                <span class="font-mono text-[9.5px] font-extrabold px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 border border-slate-200/80">
                                    {{ $t->wbs_code ?? 'TASK' }}
                                </span>
                                @if($t->is_milestone)
                                    <span style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 3px;">
                                        🏁 Milestone
                                    </span>
                                @endif
                                @if($isBlocked)
                                    <span style="background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 4px;">
                                        🚫 Blocked
                                    </span>
                                @endif
                            </div>

                            <!-- Task Title -->
                            <h4 
                                class="text-xs font-bold text-slate-800 leading-snug line-clamp-2 {{ $isCompleted ? 'text-slate-400 line-through' : 'group-hover:text-[#c3122e] transition-colors' }}" 
                                title="{{ $t->title }}"
                                style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;"
                            >
                                {{ $t->title }}
                            </h4>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 flex-shrink-0">
                        <span style="{{ $priClass }}; font-size: 9.5px; font-weight: 800; padding: 2px 6px; border-radius: 6px;">
                            {{ $priStr }}
                        </span>

                        <!-- Quick Move / Action Dropdown -->
                        <div x-data="{ openCardMenu: false }" class="relative inline-block" @click.stop>
                            <button 
                                @click="openCardMenu = !openCardMenu" 
                                type="button" 
                                class="text-slate-400 hover:text-slate-700 p-0.5 rounded transition-colors opacity-0 group-hover:opacity-100 cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                            <div 
                                x-show="openCardMenu" 
                                @click.away="openCardMenu = false" 
                                class="absolute right-0 top-full mt-1 w-44 bg-white rounded-xl shadow-xl border border-slate-200 p-1.5 z-50 text-xs font-semibold space-y-0.5 text-slate-700"
                                style="display: none;"
                            >
                                <div class="px-2 py-1 text-[9px] font-black text-slate-400 uppercase tracking-wider">Quick Reschedule</div>
                                <button wire:click.stop="quickMoveTask({{ $t->id }}, 'today'); openCardMenu = false" class="w-full text-left px-2 py-1.5 hover:bg-slate-50 rounded-lg flex items-center gap-2 cursor-pointer">
                                    <span>⚡</span> Today
                                </button>
                                <button wire:click.stop="quickMoveTask({{ $t->id }}, 'tomorrow'); openCardMenu = false" class="w-full text-left px-2 py-1.5 hover:bg-slate-50 rounded-lg flex items-center gap-2 cursor-pointer">
                                    <span>🌅</span> Tomorrow
                                </button>
                                <button wire:click.stop="quickMoveTask({{ $t->id }}, 'next_week'); openCardMenu = false" class="w-full text-left px-2 py-1.5 hover:bg-slate-50 rounded-lg flex items-center gap-2 cursor-pointer">
                                    <span>📅</span> Next Week
                                </button>
                                <div class="border-t border-slate-100 my-1"></div>
                                <button wire:click.stop="openDelayReasonModal({{ $t->id }}); openCardMenu = false" class="w-full text-left px-2 py-1.5 hover:bg-amber-50 text-amber-700 rounded-lg flex items-center gap-2 cursor-pointer">
                                    <span>⚠️</span> Log Delay
                                </button>
                                <button wire:click.stop="openBlockerModal({{ $t->id }}); openCardMenu = false" class="w-full text-left px-2 py-1.5 hover:bg-rose-50 text-rose-700 rounded-lg flex items-center gap-2 cursor-pointer">
                                    <span>🚫</span> Report Blocker
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subtitle: Project Name + Subtasks Badge -->
                <div class="flex items-center justify-between gap-1.5 text-[11px] text-slate-500 font-medium">
                    <span class="truncate hover:text-slate-800 transition-colors flex items-center gap-1" title="{{ $t->project->name ?? 'General Delivery' }}">
                        <span>🏢</span>
                        <span>{{ $t->project->name ?? 'General Delivery' }}</span>
                    </span>
                    @if($subtasksCount > 0)
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold flex-shrink-0" title="Subtasks: {{ $subtasksDone }}/{{ $subtasksCount }} Completed">
                            📋 {{ $subtasksDone }}/{{ $subtasksCount }}
                        </span>
                    @endif
                </div>

                <!-- Progress Bar if In Progress -->
                @if($t->progress > 0 && !$isCompleted)
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-500 font-semibold">
                            <span>Progress</span>
                            <span class="font-mono font-bold text-slate-700">{{ $t->progress }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-500 to-[#c3122e] h-full rounded-full transition-all duration-300" style="width: {{ $t->progress }}%"></div>
                        </div>
                    </div>
                @endif

                <!-- Bottom Row: Due Date + Assignee Avatar Circle + Log Update -->
                <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold {{ $isOverdue ? 'text-rose-600' : ($colType === 'today' ? 'text-amber-700' : 'text-slate-500') }}" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        <svg class="w-3.5 h-3.5 {{ ($colType === 'blocked' || $colType === 'overdue' || $isOverdue) ? 'text-rose-500' : ($colType === 'today' ? 'text-amber-500' : 'text-slate-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $t->end_date ? $t->end_date->format('M d, Y') : ($t->start_date ? $t->start_date->format('M d, Y') : 'No Date') }}</span>
                    </div>

                    <!-- Assignee Circle -->
                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-slate-900 to-slate-700 text-white text-[9px] font-black flex items-center justify-center shadow-2xs flex-shrink-0" title="{{ $assigneeName }}">
                        {{ $initials }}
                    </div>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-400 text-xs font-medium space-y-1.5">
                <div class="w-10 h-10 rounded-2xl bg-slate-100/80 text-slate-400 flex items-center justify-center mx-auto text-base">☕</div>
                <p class="text-slate-500 font-semibold">{{ $emptyText }}</p>
            </div>
        @endforelse
    </div>

    <!-- Add Task Button -->
    <div class="p-2.5 border-t border-slate-200/70 bg-white rounded-b-2xl">
        <button 
            wire:click="openAddTaskModal('{{ $statusKey }}')" 
            type="button" 
            class="w-full py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs hover:shadow-xs active:scale-95"
        >
            <span class="text-sm leading-none font-bold text-[#c3122e]">+</span>
            <span>Add Task</span>
        </button>
    </div>
</div>
