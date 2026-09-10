@props(['task', 'showProject' => false])

@php
    $isDone = $task->status === \App\Enums\WbsStatus::COMPLETED;
    $isOverdue = $task->isOverdue();
    $subtasksCount = $task->children ? $task->children->count() : 0;
    $doneSubtasksCount = $task->children ? $task->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() : 0;

    $todayStr = now()->toDateString();
    $isDueToday = ($task->end_date && $task->end_date->toDateString() === $todayStr) || (!$task->end_date && $task->start_date && $task->start_date->toDateString() === $todayStr);

    $hasHourSlot = !empty($task->start_time_formatted) && !empty($task->end_time_formatted);
    $titleIsPureTime = (bool) preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($task->title));

    $priorityBg = match($task->priority?->value ?? 'medium') {
        'urgent', 'critical' => 'bg-purple-50 text-purple-700 border-purple-200/80',
        'high'               => 'bg-rose-50 text-rose-700 border-rose-200/80',
        'medium'             => 'bg-blue-50 text-blue-700 border-blue-200/80',
        default              => 'bg-slate-50 text-slate-600 border-slate-200/80',
    };
@endphp

<tr class="hover:bg-slate-50/70 transition-colors group {{ $isDone ? 'bg-emerald-50/20' : ($isOverdue ? 'bg-rose-50/15' : ($isDueToday ? 'bg-amber-50/15' : '')) }}">
    <!-- 1. WBS CODE (#) -->
    <td class="py-3 pl-5 pr-2 font-mono text-xs text-slate-400 font-semibold align-middle whitespace-nowrap">
        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[11px] font-bold border border-slate-200/70">
            {{ $task->wbs_code ?: '1' }}
        </span>
    </td>

    <!-- Optional: PROJECT BADGE (shown in Date-Grouped and Flat list) -->
    @if($showProject)
        <td class="py-3 px-3 align-middle whitespace-nowrap">
            <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200" title="{{ $task->project->name ?? '' }}">
                {{ $task->project->code ?? 'PRJ' }}
            </span>
        </td>
    @endif

    <!-- 2. TASK DELIVERABLE & TIME -->
    <td class="py-3 px-4 align-middle min-w-[280px]">
        <div class="space-y-1">
            <!-- Line 1: Title & Main Status Flag -->
            <div class="flex items-center gap-2 flex-wrap">
                @if($titleIsPureTime)
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $task->title }}</span>
                    </span>
                @else
                    <span class="font-bold text-slate-900 text-[13px] {{ $isDone ? 'line-through text-slate-400 font-normal' : '' }} group-hover:text-[#c3122e] transition-colors leading-snug cursor-pointer" wire:click="openTaskDetail({{ $task->id }})">
                        {{ $task->title }}
                    </span>
                @endif

                @if($isDueToday && !$isDone)
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black text-amber-800 bg-amber-50 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>Today</span>
                    </span>
                @elseif($isOverdue && !$isDone)
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-black text-rose-700 bg-rose-50 border border-rose-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Overdue</span>
                    </span>
                @endif

                @if($task->is_milestone)
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9.5px] font-bold bg-purple-50 text-purple-700 border border-purple-200/70">
                        <span>🏁 Milestone</span>
                    </span>
                @endif
            </div>

            <!-- Line 2: Clean Sub-details (Time slot, Subtasks, Blockers) -->
            <div class="flex items-center gap-2 text-[11px] text-slate-500 flex-wrap">
                @if(!$titleIsPureTime && $hasHourSlot)
                    <span class="inline-flex items-center gap-1 font-mono text-[10.5px] text-slate-600 bg-slate-100/80 px-1.5 py-0.5 rounded border border-slate-200/60">
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $task->start_time_formatted }} – {{ $task->end_time_formatted }}</span>
                    </span>
                @endif

                @if($subtasksCount > 0)
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200/60">
                        <span>{{ $doneSubtasksCount }}/{{ $subtasksCount }} Subtasks</span>
                    </span>
                @endif

                @if($task->blockers && $task->blockers->where('status', 'open')->count() > 0)
                    <span class="inline-flex items-center gap-1 text-[9.5px] font-bold text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200/80" title="Active Blocker Reported">
                        <span>⚠️ Blocker</span>
                    </span>
                @endif
            </div>
        </div>
    </td>

    <!-- 3. SCHEDULE DATE & DEADLINE -->
    <td class="py-3 px-4 text-xs align-middle whitespace-nowrap min-w-[150px]">
        @if($task->end_date || $task->start_date)
            <div class="flex flex-col gap-0.5">
                <span class="text-xs font-mono font-bold flex items-center gap-1.5 {{ $isOverdue && !$isDone ? 'text-rose-600' : ($isDueToday && !$isDone ? 'text-amber-700' : 'text-slate-700') }}">
                    <svg class="w-3.5 h-3.5 flex-shrink-0 {{ $isOverdue && !$isDone ? 'text-rose-500' : ($isDueToday && !$isDone ? 'text-amber-500' : 'text-slate-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $task->end_date ? $task->end_date->format('M d, Y') : $task->start_date->format('M d, Y') }}</span>
                </span>
                @if($task->start_date && $task->end_date && !$task->start_date->isSameDay($task->end_date))
                    <span class="text-[10px] text-slate-400 font-mono font-medium pl-5">
                        Starts: {{ $task->start_date->format('M d') }}
                    </span>
                @endif
            </div>
        @else
            <span class="text-slate-400 font-normal italic text-xs">No dates set</span>
        @endif
    </td>

    <!-- 4. PRIORITY -->
    <td class="py-3 px-3 align-middle whitespace-nowrap">
        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $priorityBg }}">
            {{ $task->priority?->label() ?? 'MEDIUM' }}
        </span>
    </td>

    <!-- 5. STATUS (Interactive Dropdown) -->
    <td class="py-3 px-4 align-middle whitespace-nowrap min-w-[140px]">
        <select wire:change="updateStatus({{ $task->id }}, $event.target.value)" 
                class="w-full h-8 px-2.5 py-1 rounded-lg text-xs font-semibold border border-slate-200 bg-white text-slate-700 hover:border-slate-300 focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]/10 shadow-2xs outline-none cursor-pointer custom-select transition-all">
            @foreach(\App\Enums\WbsStatus::cases() as $st)
                <option value="{{ $st->value }}" {{ $task->status === $st ? 'selected' : '' }}>
                    {{ $st->label() }}
                </option>
            @endforeach
        </select>
    </td>

    <!-- 6. PROGRESS -->
    <td class="py-3 px-4 align-middle whitespace-nowrap min-w-[130px]">
        <div class="flex items-center gap-2">
            <div class="w-16 sm:w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60">
                <div class="h-full rounded-full transition-all duration-300 {{ $task->progress >= 100 ? 'bg-emerald-500' : ($task->progress >= 50 ? 'bg-blue-500' : ($task->progress > 0 ? 'bg-amber-500' : 'bg-slate-300')) }}" style="width: {{ max(2, $task->progress) }}%;"></div>
            </div>
            <span class="font-mono text-xs font-bold text-slate-600 w-7">{{ $task->progress }}%</span>
        </div>
    </td>

    <!-- 7. ACTIONS -->
    <td class="py-3 pr-5 pl-2 align-middle text-right whitespace-nowrap">
        <div class="flex items-center justify-end gap-1">
            <!-- Edit / Detail -->
            <button wire:click="openTaskDetail({{ $task->id }})" type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer" title="Quick Detail & Edit">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </button>
            <!-- Add Subtask -->
            <button wire:click="openSubTaskModal({{ $task->id }})" type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer" title="Add Sub-task">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            </button>
            <!-- Report Blocker -->
            <button wire:click="openBlockerModal({{ $task->id }})" type="button" class="w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer" title="Report Blocker">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </button>
        </div>
    </td>
</tr>
