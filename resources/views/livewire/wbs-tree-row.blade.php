@props(['item', 'level' => 1, 'collapsedIds' => []])

@php
    $isSubTask = ($level > 1) || !empty($item->parent_id);
    $hasChildren = $item->children && $item->children->count() > 0;
    $isCollapsed = in_array($item->id, $collapsedIds);

    $wbsCode = (string)($item->wbs_code ?: '1');

    if (ctype_digit($wbsCode)) {
        $num = (int)$wbsCode;
        $ordinal = match($num % 10) {
            1 => $num . ($num % 100 == 11 ? 'th' : 'st'),
            2 => $num . ($num % 100 == 12 ? 'th' : 'nd'),
            3 => $num . ($num % 100 == 13 ? 'th' : 'rd'),
            default => $num . 'th',
        };
        $taskOrdinalLabel = $ordinal . ' Task';
    } else {
        $taskOrdinalLabel = $wbsCode;
    }
@endphp

@php
    $health = $item->traffic_light;
    $progressVal = (int) ($item->progress ?? 0);
    $progressBarColor = match($health['status']) {
        'red' => 'background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);',
        'amber' => 'background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);',
        'green' => 'background: linear-gradient(90deg, #10b981 0%, #059669 100%);',
        default => 'background: linear-gradient(90deg, #cbd5e1 0%, #94a3b8 100%);',
    };

    $avatarBg = match(($item->assigned_user_id ?? 0) % 5) {
        0 => 'bg-[#c3122e]',
        1 => 'bg-[#ea580c]',
        2 => 'bg-[#059669]',
        3 => 'bg-[#7c3aed]',
        default => 'bg-[#2563eb]',
    };

    $priorityEnum = $item->priority ? $item->priority->value : 'medium';
    $currentStatus = $item->status ? $item->status->value : 'not_started';
    $isOverdue = $health['is_overdue'] ?? false;

    // Subtask count for subtitle (Only for parent tasks that actually have subtasks)
    $subtasksTotal = $item->children ? $item->children->count() : 0;
    $subtasksDone = $item->children ? $item->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() : 0;
    $progressSubtitle = $subtasksTotal > 0 ? "({$subtasksDone}/{$subtasksTotal} Subtasks)" : null;

    $hasTime = !empty($item->start_time) || !empty($item->end_time) || (bool) preg_match('/\d{1,2}:\d{2}\s*(?:AM|PM)/i', $item->title);
    $isTimeSlot = $hasTime
        && $item->item_type->value !== 'phase'
        && $item->item_type->value !== 'work_package';
    $isPhase = ($item->item_type->value === 'phase') || (!$item->parent_id && !$isTimeSlot);
@endphp

<tr class="border-b border-slate-100 hover:bg-[#fdf4f4]/30 transition-colors {{ $isSubTask ? 'bg-slate-50/40' : 'bg-white' }} {{ !$isSubTask ? ($item->wbs_code == '1' ? 'border-l-4 border-l-rose-500' : ($item->wbs_code == '2' ? 'border-l-4 border-l-purple-500' : 'border-l-4 border-l-slate-300')) : '' }}">
    
    <!-- 1. TASK NUMBER (#) -->
    <td class="py-3.5 pl-6 pr-3 font-mono text-xs whitespace-nowrap align-middle">
        @if($isSubTask)
            <div class="flex items-center gap-1.5 text-slate-400 font-bold" style="padding-left: {{ ($level - 1) * 12 }}px">
                <span class="text-slate-300 font-normal">↳</span>
                <span class="font-mono text-xs {{ $isTimeSlot ? 'text-violet-600 font-black' : 'text-slate-600' }}">{{ $item->wbs_code }}</span>
            </div>
        @else
            <span class="font-black text-sm {{ $item->wbs_code == '1' ? 'text-rose-600' : ($item->wbs_code == '2' ? 'text-slate-900' : 'text-slate-900') }}">
                {{ $item->wbs_code ?: '1' }}
            </span>
        @endif
    </td>

    <!-- 2. TASK DELIVERABLE -->
    <td class="py-3.5 px-4 align-middle min-w-[240px]" style="padding-left: {{ $isSubTask ? ($level - 1) * 16 + 16 : 16 }}px">
        <div class="flex items-center gap-2 flex-wrap">
            @if($hasChildren)
                <button 
                    wire:click="toggleCollapse({{ $item->id }})" 
                    type="button" 
                    class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-700 font-black text-[10px] flex items-center justify-center transition-all cursor-pointer shadow-2xs border border-slate-200"
                    title="{{ $isCollapsed ? 'Expand subtasks' : 'Collapse subtasks' }}"
                >
                    <svg class="w-3 h-3 transition-transform {{ $isCollapsed ? '-rotate-90' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </button>
            @elseif($isSubTask)
                <span class="text-slate-300 font-bold text-xs">›</span>
            @endif

            @if($isPhase)
                <div class="w-5 h-5 rounded-md bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <span class="font-black text-slate-900 text-sm">
                    {{ $item->title }}
                </span>
            @elseif($isTimeSlot)
                @php
                    $titleHasTime = (bool) preg_match('/\d{1,2}:\d{2}\s*(?:AM|PM)/i', $item->title);
                @endphp
                @if($titleHasTime)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-xs font-bold text-violet-700 bg-violet-50 border border-violet-200 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $item->title }}</span>
                    </span>
                @else
                    <span class="font-bold text-slate-900 text-xs">
                        {{ $item->title }}
                    </span>
                    @if($item->start_time_formatted && $item->end_time_formatted)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-mono font-bold text-violet-700 bg-violet-50 border border-violet-200/80 shadow-2xs">
                            <svg class="w-3 h-3 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $item->start_time_formatted }} – {{ $item->end_time_formatted }}</span>
                        </span>
                    @endif
                @endif
            @else
                <span class="font-bold text-slate-800 text-xs">
                    {{ $item->title }}
                </span>
            @endif

            @if($hasChildren)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    <span>›</span>
                    <span>{{ $item->children->count() }} Subtasks</span>
                </span>
            @endif

            @if($item->is_milestone)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-black bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">
                    <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/></svg>
                    <span>Milestone</span>
                </span>
            @endif

            @if($item->risks && $item->risks->count() > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-black bg-amber-50 text-amber-800 border border-amber-300 shadow-2xs" title="{{ $item->risks->count() }} Associated Risk(s)">
                    <svg class="w-3 h-3 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ $item->risks->count() }}</span>
                </span>
            @endif
        </div>

        {{-- Predecessor dependency pills --}}
        @if($item->predecessors && $item->predecessors->count() > 0)
            <div class="flex flex-wrap items-center gap-1 mt-1">
                @foreach($item->predecessors as $dep)
                    <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        After: {{ $dep->predecessor->wbs_code ?? '?' }} {{ Str::limit($dep->predecessor->title ?? 'Unknown', 18) }}
                        <button wire:click="removeDependency({{ $dep->id }})" wire:confirm="Remove this dependency?" class="ml-0.5 text-blue-400 hover:text-rose-600 transition-colors cursor-pointer">&times;</button>
                    </span>
                @endforeach
            </div>
        @endif
    </td>

    <!-- 3. ASSIGNED TO -->
    <td class="py-3.5 px-4 text-xs text-slate-700 font-semibold align-middle whitespace-nowrap min-w-[150px]">
        @if($item->assignedUser)
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full {{ $avatarBg }} text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-2xs">
                    {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                </div>
                <span class="truncate font-bold text-slate-800 text-xs max-w-[130px]">{{ $item->assignedUser->name }}</span>
            </div>
        @else
            <span class="text-slate-400 font-normal italic text-xs">Unassigned</span>
        @endif
    </td>

    <!-- 4. START SCHEDULE -->
    <td class="py-3.5 px-4 text-xs align-middle whitespace-nowrap min-w-[140px]">
        @if($item->start_date)
            <div class="flex flex-col gap-0.5">
                <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/70 text-[11px] font-bold flex items-center gap-1.5 w-fit shadow-2xs font-mono">
                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $item->start_date->format('M d, Y') }}</span>
                </span>
                @if($item->start_time_formatted)
                    <span class="text-[10px] font-mono font-bold text-violet-600 pl-1 flex items-center gap-1">
                        <svg class="w-2.5 h-2.5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $item->start_time_formatted }}</span>
                    </span>
                @endif
            </div>
        @else
            <span class="text-slate-400 font-normal italic text-xs">No start set</span>
        @endif
    </td>

    <!-- 5. TARGET DEADLINE -->
    <td class="py-3.5 px-4 text-xs align-middle whitespace-nowrap min-w-[140px]">
        @if($item->end_date)
            <div class="flex flex-col gap-0.5">
                <span class="px-2.5 py-0.5 rounded-md {{ $isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200/70' }} text-[11px] font-bold flex items-center gap-1.5 w-fit shadow-2xs font-mono">
                    <svg class="w-3 h-3 {{ $isOverdue ? 'text-rose-600' : 'text-emerald-600' }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $item->end_date->format('M d, Y') }}</span>
                </span>
                @if($item->end_time_formatted)
                    <span class="text-[10px] font-mono font-bold text-violet-600 pl-1 flex items-center gap-1">
                        <svg class="w-2.5 h-2.5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $item->end_time_formatted }}</span>
                    </span>
                @endif
            </div>
        @else
            <span class="text-slate-400 font-normal italic text-xs">No deadline set</span>
        @endif
    </td>

    <!-- 6. TRAFFIC LIGHT (Glowing LED Circular Dot - Placed before Progress) -->
    <td class="py-3.5 px-3 text-center align-middle whitespace-nowrap min-w-[100px]">
        <div class="inline-flex items-center justify-center cursor-help" title="{{ $health['reason'] }}">
            @if($health['status'] === 'red')
                <span class="w-3.5 h-3.5 rounded-full bg-[#ef4444] inline-block shadow-sm ring-4 ring-rose-100 animate-pulse" title="Critical / Delayed: {{ $health['reason'] }}"></span>
            @elseif($health['status'] === 'amber')
                <span class="w-3.5 h-3.5 rounded-full bg-[#f59e0b] inline-block shadow-sm ring-4 ring-amber-100" title="At Risk / Due Soon: {{ $health['reason'] }}"></span>
            @elseif($health['status'] === 'green')
                <span class="w-3.5 h-3.5 rounded-full bg-[#10b981] inline-block shadow-sm ring-4 ring-emerald-100" title="On Track: {{ $health['reason'] }}"></span>
            @else
                <span class="w-3.5 h-3.5 rounded-full bg-[#9ca3af] inline-block shadow-sm ring-4 ring-slate-100" title="Upcoming / Not Started: {{ $health['reason'] }}"></span>
            @endif
        </div>
    </td>

    <!-- 7. PROGRESS (Horizontal Bar + Percentage + Ratio Subtitle) -->
    <td class="py-3.5 px-4 align-middle whitespace-nowrap min-w-[150px]">
        <div class="flex flex-col gap-0.5">
            <div class="flex items-center gap-2.5">
                <div class="w-20 sm:w-24 h-2 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60 shadow-inner">
                    <div 
                        class="h-full rounded-full transition-all duration-300"
                        style="width: {{ $progressVal }}%; {{ $progressBarColor }}"
                    ></div>
                </div>
                <span class="text-xs font-black text-slate-900 font-mono w-9">{{ $progressVal }}%</span>
            </div>
            @if($progressSubtitle)
                <span class="text-[10px] font-mono text-slate-400 pl-1">{{ $progressSubtitle }}</span>
            @endif
        </div>
    </td>

    <!-- 8. STATUS (Interactive Pill Badge Dropdown) -->
    <td class="py-3.5 px-4 align-middle whitespace-nowrap min-w-[130px]">
        <div class="flex flex-col">
            <select
                wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                class="text-[11px] font-bold rounded-full px-3 py-1 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30 transition-all shadow-2xs
                    {{ match($currentStatus) {
                        'completed'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'in_progress'  => 'bg-blue-50 text-blue-700 border-blue-200',
                        'under_review' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'blocked'      => 'bg-rose-50 text-rose-700 border-rose-200 font-extrabold',
                        'on_hold'      => 'bg-amber-50 text-amber-800 border-amber-200',
                        default        => ($isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-100 text-slate-600 border-slate-200'),
                    } }}"
                title="Update Task Status"
            >
                <option value="not_started" @selected($currentStatus === 'not_started' || $currentStatus === 'backlog') class="bg-white text-slate-900 font-medium">
                    ⚪ Not Started
                </option>
                <option value="in_progress" @selected($currentStatus === 'in_progress') class="bg-white text-slate-900 font-medium">
                    🔄 In Progress
                </option>
                <option value="under_review" @selected($currentStatus === 'under_review') class="bg-white text-slate-900 font-medium">
                    🟣 Under Review
                </option>
                <option value="blocked" @selected($currentStatus === 'blocked') class="bg-white text-rose-700 font-bold">
                    🔴 Blocked
                </option>
                <option value="on_hold" @selected($currentStatus === 'on_hold') class="bg-white text-amber-700 font-medium">
                    🟡 On Hold
                </option>
                <option value="completed" @selected($currentStatus === 'completed') class="bg-white text-emerald-700 font-bold">
                    ✔ Completed
                </option>
            </select>
        </div>
    </td>

    <!-- 10. ACTIONS (Circular Kebab Menu ⋮ Dropdown) -->
    <td class="py-3.5 pl-4 pr-6 text-right align-middle whitespace-nowrap min-w-[70px]">
        <div x-data="{ open: false }" class="relative inline-block text-left">
            <button 
                @click="open = !open" 
                @click.outside="open = false" 
                type="button" 
                class="w-7 h-7 rounded-full border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-100 hover:border-slate-300 flex items-center justify-center font-bold text-base transition-all cursor-pointer shadow-2xs"
                title="Task Actions"
            >
                ⋮
            </button>

            <!-- Actions Popup Menu -->
            <div 
                x-show="open" 
                x-cloak 
                x-transition:enter="transition ease-out duration-100" 
                x-transition:enter-start="transform opacity-0 scale-95" 
                x-transition:enter-end="transform opacity-100 scale-100" 
                x-transition:leave="transition ease-in duration-75" 
                x-transition:leave-start="transform opacity-100 scale-100" 
                x-transition:leave-end="transform opacity-0 scale-95" 
                class="absolute right-0 mt-1.5 w-44 rounded-2xl bg-white border border-slate-200 shadow-xl z-50 py-1.5 text-left focus:outline-none"
            >
                @if($item->project->userCan(auth()->user(), 'task.create_subtask') || $item->project->userCan(auth()->user(), 'task.create'))
                    <button wire:click="openAddItemModal({{ $item->id }}, 'subtask')" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-colors cursor-pointer text-left">
                        <span>➕</span>
                        <span>Add Sub-task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openEditItemModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-colors cursor-pointer text-left">
                        <span>✏️</span>
                        <span>Edit Task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.comment'))
                    <a href="{{ route('daily-updates.index', ['project' => $item->project_id, 'task' => $item->id, 'create' => 1]) }}" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-colors cursor-pointer text-left no-underline block">
                        <span>📝</span>
                        <span>Log Daily Update</span>
                    </a>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openDepModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition-colors cursor-pointer text-left">
                        <span>🔗</span>
                        <span>Link Predecessor</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.delete', $item))
                    <div class="border-t border-slate-100 my-1"></div>
                    <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete task '{{ addslashes($item->title) }}'?" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition-colors cursor-pointer text-left">
                        <span>🗑️</span>
                        <span>Delete Task</span>
                    </button>
                @endif
            </div>
        </div>
    </td>
</tr>

@if(!$isCollapsed)
    @foreach($item->children as $child)
        @include('livewire.wbs-tree-row', ['item' => $child, 'level' => $level + 1, 'collapsedIds' => $collapsedIds])
    @endforeach
@endif
