@props(['item', 'level' => 1, 'collapsedIds' => []])

@php
    $isSubTask = ($level > 1) || !empty($item->parent_id);
    $hasChildren = $item->children && $item->children->count() > 0;
    $isCollapsed = in_array($item->id, $collapsedIds);

    $wbsCode = (string)($item->wbs_code ?: '1');

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

    $currentStatus = $item->status ? $item->status->value : 'not_started';
    $isOverdue = $health['is_overdue'] ?? false;

    // Subtask count for subtitle
    $subtasksTotal = $item->children ? $item->children->count() : 0;
    $subtasksDone = $item->children ? $item->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() : 0;

    // Level-specific theme styling
    $levelConfig = match($level) {
        1 => [
            'iconBg' => 'bg-amber-50 text-amber-600 border border-amber-200/90',
            'badge'  => 'bg-amber-50 text-amber-800 border-amber-200/90',
            'dot'    => 'bg-amber-500',
            'title'  => 'font-bold text-slate-900 text-[11px] tracking-tight',
            'rowBg'  => 'bg-slate-50/70 hover:bg-slate-100/70 border-b border-slate-200/80',
        ],
        2 => [
            'iconBg' => 'bg-sky-50 text-sky-600 border border-sky-200/90',
            'badge'  => 'bg-sky-50 text-sky-800 border-sky-200/90',
            'dot'    => 'bg-sky-500',
            'title'  => 'font-semibold text-slate-800 text-[11px] tracking-tight',
            'rowBg'  => 'bg-white hover:bg-slate-50/70 border-b border-slate-100',
        ],
        3 => [
            'iconBg' => 'bg-indigo-50 text-indigo-600 border border-indigo-200/90',
            'badge'  => 'bg-indigo-50 text-indigo-800 border-indigo-200/90',
            'dot'    => 'bg-indigo-500',
            'title'  => 'font-medium text-slate-700 text-[11px]',
            'rowBg'  => 'bg-white hover:bg-slate-50/70 border-b border-slate-100',
        ],
        default => [
            'iconBg' => 'bg-emerald-50 text-emerald-600 border border-emerald-200/90',
            'badge'  => 'bg-emerald-50 text-emerald-800 border-emerald-200/90',
            'dot'    => 'bg-emerald-500',
            'title'  => 'font-normal text-slate-600 text-[10.5px]',
            'rowBg'  => 'bg-white hover:bg-slate-50/60 border-b border-slate-100',
        ],
    };

    // Clean title from any encoding glitch
    $cleanTitle = str_replace(['???', '??'], '–', $item->title);
@endphp

<tr class="transition-colors duration-150 group {{ $levelConfig['rowBg'] }}">
    <!-- 1. TASK NUMBER (#) -->
    <td class="py-3 pl-3 pr-1 font-mono text-center whitespace-nowrap align-middle w-12">
        @if($level === 1)
            <span class="inline-flex items-center justify-center min-w-[24px] h-5 px-1.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[9.5px] font-bold border border-slate-200/80 shadow-2xs">
                #{{ $wbsCode }}
            </span>
        @elseif($level === 2)
            <span class="inline-flex items-center justify-center min-w-[22px] h-5 px-1 rounded-md bg-slate-50 font-mono text-[9.5px] font-semibold text-slate-600 border border-slate-200/70">
                {{ $wbsCode }}
            </span>
        @else
            <span class="font-mono text-[9.5px] font-medium text-slate-400">
                {{ $wbsCode }}
            </span>
        @endif
    </td>

    <!-- 2. TASK DELIVERABLE (Clean Modern Tree Hierarchy) -->
    <td class="py-3 px-3 align-middle min-w-[220px]">
        <div class="flex items-center gap-2" style="padding-left: {{ ($level - 1) * 16 }}px;">

            {{-- Expand / Collapse Button or Spacer --}}
            @if($hasChildren)
                <button 
                    wire:click="toggleCollapse({{ $item->id }})" 
                    type="button" 
                    class="w-5 h-5 rounded-md bg-white hover:bg-slate-100 border border-slate-200/80 text-slate-500 flex items-center justify-center transition-all cursor-pointer shadow-2xs shrink-0 active:scale-95"
                    title="{{ $isCollapsed ? 'Expand subtasks' : 'Collapse subtasks' }}"
                >
                    <svg class="w-3 h-3 transition-transform duration-200 {{ $isCollapsed ? '-rotate-90 text-slate-400' : 'text-slate-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            @elseif($level > 1)
                <div class="w-5 h-5 flex items-center justify-center shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                </div>
            @endif

            {{-- Level Icons --}}
            @if($level === 1)
                <div class="w-5 h-5 rounded-md {{ $levelConfig['iconBg'] }} shadow-2xs flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            @elseif($level === 2)
                <div class="w-5 h-5 rounded-md {{ $levelConfig['iconBg'] }} shadow-2xs flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            @elseif($level === 3)
                <div class="w-5 h-5 rounded-md {{ $levelConfig['iconBg'] }} shadow-2xs flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @else
                <div class="w-5 h-5 rounded-md {{ $levelConfig['iconBg'] }} shadow-2xs flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            @endif

            {{-- Title --}}
            <span class="truncate max-w-[220px] lg:max-w-[320px] {{ $levelConfig['title'] }}" title="{{ $cleanTitle }}">
                {{ $cleanTitle }}
            </span>

            {{-- Subtasks Badge --}}
            @if($hasChildren)
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold {{ $levelConfig['badge'] }} shrink-0 font-mono shadow-2xs">
                    <span class="w-1 h-1 rounded-full {{ $levelConfig['dot'] }}"></span>
                    <span>{{ $item->children->count() }}</span>
                </span>
            @endif

            {{-- Milestone Tag --}}
            @if($item->is_milestone)
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8.5px] font-black bg-amber-50 text-amber-900 border border-amber-300/80 shrink-0">
                    <span>🏁</span>
                </span>
            @endif

            {{-- Risks Tag --}}
            @if($item->risks && $item->risks->count() > 0)
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-rose-50 text-rose-800 border border-rose-300/80 shrink-0" title="{{ $item->risks->count() }} Associated Risk(s)">
                    <span class="text-rose-600 font-bold">⚠️ {{ $item->risks->count() }}</span>
                </span>
            @endif

            {{-- Predecessors --}}
            @if($item->predecessors && $item->predecessors->count() > 0)
                @foreach($item->predecessors as $dep)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[8.5px] font-bold bg-blue-50 text-blue-800 border border-blue-200 shrink-0">
                        #{{ $dep->predecessor->wbs_code ?? '?' }}
                        <button wire:click="removeDependency({{ $dep->id }})" wire:confirm="Remove this dependency?" class="text-blue-400 hover:text-rose-600 cursor-pointer ml-0.5">&times;</button>
                    </span>
                @endforeach
            @endif
        </div>
    </td>

    <!-- 3. ASSIGNED TO (Clean Pill) -->
    <td class="py-3 px-2.5 text-xs text-slate-700 align-middle whitespace-nowrap w-[110px]">
        @if($item->assignedUser)
            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-slate-50 border border-slate-200/80 shadow-2xs max-w-[105px]" title="{{ $item->assignedUser->name }}">
                <div class="w-4 h-4 rounded-full {{ $avatarBg }} text-white text-[8.5px] font-black flex items-center justify-center shrink-0 shadow-2xs">
                    {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                </div>
                <span class="truncate font-bold text-slate-800 text-[10.5px]">{{ $item->assignedUser->name }}</span>
            </div>
        @else
            <span class="text-slate-400 font-medium text-[10.5px]">Unassigned</span>
        @endif
    </td>

    <!-- 4. START SCHEDULE (Clear Date & Time) -->
    <td class="py-3 px-2.5 text-xs align-middle whitespace-nowrap min-w-[120px]">
        @if($item->start_date)
            <div class="flex flex-col leading-snug">
                <span class="text-[11px] font-mono font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span>{{ $item->start_date->format('M d, Y') }}</span>
                </span>
                @if($item->start_time_formatted)
                    <span class="text-[10px] font-mono font-semibold text-slate-400 pl-3">{{ $item->start_time_formatted }}</span>
                @endif
            </div>
        @else
            <span class="text-slate-300 font-mono text-[10.5px]">--</span>
        @endif
    </td>

    <!-- 5. TARGET DEADLINE (Clear Date, Time & Overdue Tag) -->
    <td class="py-3 px-2.5 text-xs align-middle whitespace-nowrap min-w-[120px]">
        @if($item->end_date)
            <div class="flex flex-col leading-snug">
                <span class="text-[11px] font-mono font-bold flex items-center gap-1.5 {{ $isOverdue ? 'text-rose-700' : 'text-slate-800' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $isOverdue ? 'bg-rose-600 animate-pulse' : 'bg-slate-400' }} shrink-0"></span>
                    <span>{{ $item->end_date->format('M d, Y') }}</span>
                </span>
                @if($isOverdue)
                    <span class="text-[9px] font-mono font-black text-rose-600 pl-3 uppercase tracking-wider">OVERDUE</span>
                @elseif($item->end_time_formatted)
                    <span class="text-[10px] font-mono font-semibold text-slate-400 pl-3">{{ $item->end_time_formatted }}</span>
                @endif
            </div>
        @else
            <span class="text-slate-300 font-mono text-[10.5px]">--</span>
        @endif
    </td>

    <!-- 6. TRAFFIC LIGHT / HEALTH (Refined Health Pill) -->
    <td class="py-3 px-1.5 text-center align-middle whitespace-nowrap w-[85px]">
        <div class="inline-flex items-center justify-center cursor-help" title="{{ $health['reason'] }}">
            @if($health['status'] === 'red')
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200/90 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <span>Delayed</span>
                </span>
            @elseif($health['status'] === 'amber')
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>At Risk</span>
                </span>
            @elseif($health['status'] === 'green')
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/90 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>On Track</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200/90 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Plan</span>
                </span>
            @endif
        </div>
    </td>

    <!-- 7. PROGRESS (Refined Bar + %) -->
    <td class="py-3 px-2 align-middle whitespace-nowrap w-[90px]">
        <div class="flex items-center gap-2">
            <div class="w-12 h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200/80 shrink-0">
                <div 
                    class="h-full rounded-full transition-all duration-300"
                    style="width: {{ $progressVal }}%; {{ $progressBarColor }}"
                ></div>
            </div>
            <span class="text-[10px] font-bold font-mono text-slate-700 w-6 text-right">{{ $progressVal }}%</span>
        </div>
    </td>

    <!-- 8. STATUS (Modern Interactive Dropdown) -->
    <td class="py-3 px-2 align-middle whitespace-nowrap w-[115px] min-w-[115px]">
        <div class="relative inline-block w-full">
            <select
                wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                class="text-[10px] font-bold rounded-lg pl-2.5 pr-5 py-1 border cursor-pointer focus:outline-none focus:ring-1 focus:ring-slate-400 transition-all shadow-2xs w-full appearance-none
                    {{ match($currentStatus) {
                        'completed'    => 'bg-emerald-50 text-emerald-800 border-emerald-200/90 hover:bg-emerald-100/70',
                        'in_progress'  => 'bg-sky-50 text-sky-800 border-sky-200/90 hover:bg-sky-100/70',
                        'under_review' => 'bg-purple-50 text-purple-800 border-purple-200/90 hover:bg-purple-100/70',
                        'blocked'      => 'bg-rose-50 text-rose-800 border-rose-200/90 font-extrabold hover:bg-rose-100/70',
                        'on_hold'      => 'bg-amber-50 text-amber-900 border-amber-200/90 hover:bg-amber-100/70',
                        default        => ($isOverdue ? 'bg-rose-50 text-rose-800 border-rose-200/90' : 'bg-slate-50 text-slate-700 border-slate-200/90 hover:bg-slate-100/70'),
                    } }}"
                title="Update Task Status"
            >
                <option value="not_started" @selected($currentStatus === 'not_started' || $currentStatus === 'backlog') class="bg-white text-slate-900">Not Started</option>
                <option value="in_progress" @selected($currentStatus === 'in_progress') class="bg-white text-slate-900">In Progress</option>
                <option value="under_review" @selected($currentStatus === 'under_review') class="bg-white text-slate-900">Review</option>
                <option value="blocked" @selected($currentStatus === 'blocked') class="bg-white text-rose-700 font-bold">Blocked</option>
                <option value="on_hold" @selected($currentStatus === 'on_hold') class="bg-white text-amber-700">On Hold</option>
                <option value="completed" @selected($currentStatus === 'completed') class="bg-white text-emerald-700 font-bold">Done</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-1.5 text-slate-400">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </td>

    <!-- 9. ACTIONS (Compact Kebab ⋮ Menu) -->
    <td class="py-3 pl-1 pr-3 text-center align-middle whitespace-nowrap w-[40px]">
        <div x-data="{ open: false }" class="relative inline-block text-left">
            <button 
                @click="open = !open" 
                @click.outside="open = false" 
                type="button" 
                class="w-6 h-6 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center font-bold text-xs transition-all cursor-pointer shadow-2xs mx-auto"
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
                class="absolute right-0 mt-1 w-40 rounded-xl bg-white border border-slate-200 shadow-xl z-50 py-1 text-left focus:outline-none"
            >
                @if($item->project->userCan(auth()->user(), 'task.create_subtask') || $item->project->userCan(auth()->user(), 'task.create'))
                    <button wire:click="openAddItemModal({{ $item->id }}, 'subtask')" @click="open = false" type="button" class="w-full px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <span>➕</span>
                        <span>Add Sub-task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openEditItemModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <span>✏️</span>
                        <span>Edit Task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.comment'))
                    <a href="{{ route('daily-updates.index', ['project' => $item->project_id, 'task' => $item->id, 'create' => 1]) }}" class="w-full px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left no-underline block">
                        <span>📝</span>
                        <span>Daily Update</span>
                    </a>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openDepModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <span>🔗</span>
                        <span>Link Dependency</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.delete', $item))
                    <div class="border-t border-slate-100 my-0.5"></div>
                    <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete task '{{ addslashes($item->title) }}'?" @click="open = false" type="button" class="w-full px-3 py-1.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 cursor-pointer text-left">
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
