@props(['item', 'level' => 1, 'collapsedIds' => []])

@php
    $isSubTask = ($level > 1) || !empty($item->parent_id);
    $hasChildren = $item->children && $item->children->count() > 0;
    $isCollapsed = in_array($item->id, $collapsedIds);

    $wbsCode = (string)($item->wbs_code ?: '1');

    $currentStatus = $item->status ? $item->status->value : 'not_started';
    $isOverdue = $item->isOverdue();
    $progressVal = (int) ($item->progress ?? 0);
    $progressBarColor = match($currentStatus) {
        'completed'          => 'background: linear-gradient(90deg, #10b981 0%, #059669 100%);',
        'blocked'            => 'background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);',
        'at_risk', 'on_hold' => 'background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);',
        'in_progress'        => 'background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);',
        default              => ($isOverdue ? 'background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);' : 'background: linear-gradient(90deg, #cbd5e1 0%, #94a3b8 100%);'),
    };

    $avatarBg = match(($item->assigned_user_id ?? 0) % 5) {
        0 => 'bg-[#c3122e]',
        1 => 'bg-[#ea580c]',
        2 => 'bg-[#059669]',
        3 => 'bg-[#7c3aed]',
        default => 'bg-[#2563eb]',
    };

    // Subtask count for subtitle
    $subtasksTotal = $item->children ? $item->children->count() : 0;
    $subtasksDone = $item->children ? $item->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() : 0;

    // Level-specific theme styling
    $isMainTask = ($level === 1);
    $levelConfig = match($level) {
        1 => [
            'iconBg' => 'bg-gradient-to-br from-amber-50 to-orange-50 text-amber-600 border border-amber-200/80 shadow-2xs',
            'badge'  => 'bg-slate-100 text-slate-700 border border-slate-200/80',
            'dot'    => 'bg-amber-500',
            'title'  => 'font-black text-slate-900 text-sm tracking-tight',
            'rowBg'  => 'bg-white hover:bg-slate-50/70',
        ],
        2 => [
            'iconBg' => 'bg-sky-50 text-sky-600 border border-sky-200/70 shadow-2xs',
            'badge'  => 'bg-slate-100 text-slate-600 border border-slate-200/60',
            'dot'    => 'bg-sky-500',
            'title'  => 'font-bold text-slate-800 text-xs',
            'rowBg'  => 'bg-slate-50/30 hover:bg-slate-50/80',
        ],
        3 => [
            'iconBg' => 'bg-indigo-50 text-indigo-600 border border-indigo-200/70 shadow-2xs',
            'badge'  => 'bg-slate-100 text-slate-600 border border-slate-200/60',
            'dot'    => 'bg-indigo-500',
            'title'  => 'font-medium text-slate-700 text-xs',
            'rowBg'  => 'bg-slate-50/50 hover:bg-slate-50/90',
        ],
        default => [
            'iconBg' => 'bg-emerald-50 text-emerald-600 border border-emerald-200/70 shadow-2xs',
            'badge'  => 'bg-slate-100 text-slate-600 border border-slate-200/60',
            'dot'    => 'bg-emerald-500',
            'title'  => 'font-normal text-slate-600 text-[11px]',
            'rowBg'  => 'bg-slate-50/50 hover:bg-slate-50/90',
        ],
    };

    // Clean title from any encoding glitch
    $cleanTitle = str_replace(['???', '??'], '–', $item->title);
@endphp

<tr class="transition-colors duration-150 group border-b border-slate-100/90 hover:bg-slate-50/60 {{ $levelConfig['rowBg'] }}">
    <!-- 1. TASK NUMBER (#) -->
    <td class="py-3.5 pl-4 pr-1 text-left whitespace-nowrap align-middle" style="width: 48px;">
        <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-slate-800 transition-colors">
            #{{ $wbsCode }}
        </span>
    </td>

    <!-- 2. TASK DELIVERABLE (Clean Modern Tree Hierarchy) -->
    <td class="py-3.5 px-3 align-middle">
        <div class="flex items-center gap-2.5" style="padding-left: {{ ($level - 1) * 20 }}px;">

            {{-- Expand / Collapse Button or Tree Spacer --}}
            @if($hasChildren)
                <button 
                    wire:click="toggleCollapse({{ $item->id }})" 
                    type="button" 
                    class="w-5 h-5 rounded-md hover:bg-slate-200/60 text-slate-400 hover:text-slate-800 flex items-center justify-center transition-all cursor-pointer shrink-0"
                    title="{{ $isCollapsed ? 'Expand subtasks' : 'Collapse subtasks' }}"
                >
                    <svg class="w-3.5 h-3.5 transition-transform duration-200 {{ $isCollapsed ? '-rotate-90 text-slate-400' : 'rotate-0 text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            @elseif($level > 1)
                <div class="w-5 flex items-center justify-center shrink-0 text-slate-300 text-xs font-mono select-none">└</div>
            @endif

            {{-- Status & Level Icons --}}
            @if($hasChildren)
                <div class="w-6.5 h-6.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            @elseif($currentStatus === 'completed' || $progressVal === 100)
                <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200/90 flex items-center justify-center shrink-0 shadow-2xs" title="Completed">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            @elseif($currentStatus === 'in_progress')
                <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 border border-blue-200/90 flex items-center justify-center shrink-0 shadow-2xs" title="In Progress">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                    </svg>
                </div>
            @elseif($isOverdue || $currentStatus === 'blocked')
                <div class="w-6 h-6 rounded-full bg-rose-50 text-rose-600 border border-rose-200/90 flex items-center justify-center shrink-0 shadow-2xs" title="{{ $isOverdue ? 'Overdue' : 'Blocked' }}">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            @else
                <div class="w-6 h-6 rounded-full bg-slate-50 text-slate-400 border border-slate-200 flex items-center justify-center shrink-0 shadow-2xs" title="Not Started">
                    <div class="w-1.5 h-1.5 rounded-full border border-slate-300"></div>
                </div>
            @endif

            {{-- Title (Display full title nicely) --}}
            <span class="whitespace-normal break-words font-semibold text-slate-900 text-xs hover:text-[#c3122e] transition-colors leading-snug cursor-pointer" title="{{ $cleanTitle }}">
                {{ $cleanTitle }}
            </span>

            {{-- Subtasks Badge --}}
            @if($hasChildren)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>{{ $item->children->count() }}</span>
                </span>
            @endif

            {{-- Milestone Tag --}}
            @if($item->is_milestone)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-bold bg-purple-50 text-purple-700 border border-purple-200/90 shadow-2xs shrink-0">
                    <span class="w-2 h-2 bg-purple-600 rotate-45 rounded-2xs inline-block"></span>
                    <span>Milestone</span>
                </span>
            @endif

            {{-- Risks Tag --}}
            @if($item->risks && $item->risks->count() > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs shrink-0" title="{{ $item->risks->count() }} Associated Risk(s)">
                    <span>⚠️</span>
                    <span>{{ $item->risks->count() }}</span>
                </span>
            @endif

            {{-- Predecessors --}}
            @if($item->predecessors && $item->predecessors->count() > 0)
                @foreach($item->predecessors as $dep)
                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-blue-50 text-blue-800 border border-blue-200 shrink-0">
                        #{{ $dep->predecessor->wbs_code ?? '?' }}
                        <button wire:click="removeDependency({{ $dep->id }})" wire:confirm="Remove this dependency?" class="text-blue-400 hover:text-rose-600 cursor-pointer ml-0.5">&times;</button>
                    </span>
                @endforeach
            @endif
        </div>
    </td>

    <!-- 3. ASSIGNED TO (Clean Pill with Generous Width) -->
    <td class="py-3.5 px-3 text-xs align-middle whitespace-nowrap" style="width: 195px;">
        @if($item->assignedUser)
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-slate-50/90 hover:bg-slate-100 border border-slate-200/80 shadow-2xs max-w-[190px] transition-colors" title="{{ $item->assignedUser->name }}">
                <div class="w-5.5 h-5.5 rounded-full {{ $avatarBg }} text-white text-[9.5px] font-bold flex items-center justify-center shrink-0 shadow-2xs">
                    {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                </div>
                <span class="truncate font-semibold text-slate-800 text-xs">{{ $item->assignedUser->name }}</span>
            </div>
        @else
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-slate-400 text-xs bg-slate-50/50 border border-dashed border-slate-200 select-none" title="Unassigned">
                <svg class="w-3.5 h-3.5 text-slate-400/80 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-slate-400 text-[11px] font-medium">Unassigned</span>
            </div>
        @endif
    </td>

    <!-- 4. START SCHEDULE (Clean Date & Time) -->
    <td class="py-3.5 px-3 text-xs align-middle whitespace-nowrap" style="width: 130px;">
        @if($item->start_date)
            <div class="flex flex-col leading-tight">
                <span class="text-xs font-semibold text-slate-800">{{ $item->start_date->format('M d, Y') }}</span>
                <span class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $item->start_time_formatted ?: '09:00 AM' }}</span>
            </div>
        @else
            <span class="text-slate-300 font-mono text-xs">—</span>
        @endif
    </td>

    <!-- 5. TARGET DEADLINE (Clear Date, Time & Overdue Indicator) -->
    <td class="py-3.5 px-3 text-xs align-middle whitespace-nowrap" style="width: 135px;">
        @if($item->end_date)
            <div class="flex flex-col leading-tight">
                <span class="text-xs {{ $isOverdue ? 'font-bold text-rose-600' : 'font-semibold text-slate-800' }}">
                    {{ $item->end_date->format('M d, Y') }}
                </span>
                @if($isOverdue)
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-rose-600 uppercase tracking-tight mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <span>Overdue</span>
                    </span>
                @else
                    <span class="text-[10px] font-mono text-slate-400 mt-0.5">{{ $item->end_time_formatted ?: '05:00 PM' }}</span>
                @endif
            </div>
        @else
            <span class="text-slate-300 font-mono text-xs">—</span>
        @endif
    </td>

    <!-- 6. PROGRESS (Refined Bar + %) -->
    <td class="py-3.5 px-2 text-xs align-middle whitespace-nowrap" style="width: 120px;">
        <div class="flex items-center gap-2">
            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden border border-slate-200/60 p-[0.5px] flex items-center shrink-0">
                <div 
                    class="h-full rounded-full transition-all duration-300"
                    style="width: {{ $progressVal }}%; {{ $progressBarColor }}"
                ></div>
            </div>
            <span class="text-xs font-bold font-mono {{ $progressVal === 100 ? 'text-emerald-600' : 'text-slate-600' }} tabular-nums w-8 text-right">{{ $progressVal }}%</span>
        </div>
    </td>

    <!-- 7. STATUS (Modern Interactive Dropdown) -->
    <td class="py-3.5 px-3 align-middle whitespace-nowrap" style="width: 145px;">
        <div class="relative inline-block w-full max-w-[140px]">
            @php
                $stBadge = match($currentStatus) {
                    'completed'    => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100/70', 'dot' => 'bg-emerald-500'],
                    'in_progress'  => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100/70', 'dot' => 'bg-blue-500'],
                    'at_risk'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100/70', 'dot' => 'bg-amber-500'],
                    'under_review' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100/70', 'dot' => 'bg-purple-500'],
                    'blocked'      => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100/70', 'dot' => 'bg-rose-500'],
                    'on_hold'      => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100/70', 'dot' => 'bg-amber-500'],
                    default        => ($isOverdue 
                        ? ['bg' => 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100/70', 'dot' => 'bg-rose-500'] 
                        : ['bg' => 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100/70', 'dot' => 'bg-slate-400']),
                };
            @endphp
            <div class="relative flex items-center">
                <span class="pointer-events-none absolute left-3 w-1.5 h-1.5 rounded-full {{ $stBadge['dot'] }}"></span>
                <select
                    wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                    class="text-xs font-bold rounded-full pl-6.5 pr-6 py-1.5 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-slate-200 transition-all shadow-2xs w-full appearance-none truncate {{ $stBadge['bg'] }}"
                    title="Update Task Status"
                >
                    <option value="not_started" @selected($currentStatus === 'not_started' || $currentStatus === 'backlog') class="bg-white text-slate-900 font-semibold">Not Started</option>
                    <option value="in_progress" @selected($currentStatus === 'in_progress') class="bg-white text-slate-900 font-semibold">In Progress</option>
                    <option value="at_risk" @selected($currentStatus === 'at_risk') class="bg-white text-amber-700 font-bold">At Risk</option>
                    <option value="under_review" @selected($currentStatus === 'under_review') class="bg-white text-purple-700 font-semibold">Review</option>
                    <option value="blocked" @selected($currentStatus === 'blocked') class="bg-white text-rose-700 font-bold">Blocked</option>
                    <option value="on_hold" @selected($currentStatus === 'on_hold') class="bg-white text-amber-700 font-semibold">On Hold</option>
                    <option value="completed" @selected($currentStatus === 'completed') class="bg-white text-emerald-700 font-bold">Done</option>
                </select>
                <div class="pointer-events-none absolute right-2.5 text-current opacity-60">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>
    </td>

    <!-- 8. ACTIONS (Compact Kebab ⋮ Menu) -->
    <td class="py-3.5 pr-4 pl-1 text-center align-middle whitespace-nowrap" style="width: 50px;">
        <div x-data="{ open: false }" class="relative inline-block text-left">
            <button 
                @click="open = !open" 
                @click.outside="open = false" 
                type="button" 
                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-800 hover:bg-slate-100 flex items-center justify-center transition-all cursor-pointer mx-auto active:scale-95 shadow-2xs"
                title="Task Actions"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                </svg>
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
                class="absolute right-0 mt-1 w-44 rounded-2xl bg-white border border-slate-200/90 shadow-xl z-50 py-1.5 text-left focus:outline-none"
            >
                @if($item->project->userCan(auth()->user(), 'task.create_subtask') || $item->project->userCan(auth()->user(), 'task.create'))
                    <button wire:click="openAddItemModal({{ $item->id }}, 'subtask')" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        <span>Add Sub-task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openEditItemModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span>Edit Task</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.comment'))
                    <a href="{{ route('daily-updates.index', ['project' => $item->project_id, 'task' => $item->id, 'create' => 1]) }}" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left no-underline block">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span>Daily Update</span>
                    </a>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                    <button wire:click="openDepModal({{ $item->id }})" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 flex items-center gap-2 cursor-pointer text-left">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        <span>Link Dependency</span>
                    </button>
                @endif

                @if($item->project->userCan(auth()->user(), 'task.delete', $item))
                    <div class="border-t border-slate-100 my-1"></div>
                    <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete task '{{ addslashes($item->title) }}'?" @click="open = false" type="button" class="w-full px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2 cursor-pointer text-left">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
