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

<tr class="border-b border-slate-100 hover:bg-[#fdf4f4]/40 transition-colors {{ $isSubTask ? 'bg-slate-50/50' : 'bg-white' }}">
    <!-- 1. TASK NUMBER (#) -->
    <td class="py-4 pl-6 pr-4 font-mono text-xs font-black whitespace-nowrap align-middle">
        @if($isSubTask)
            <span class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-bold bg-amber-50 text-amber-800 border border-amber-200/90 shadow-2xs inline-flex items-center gap-1">
                <span>↳</span>
                <span>{{ $taskOrdinalLabel }}</span>
            </span>
        @else
            <span class="text-[#c3122e] font-black text-xs">{{ $taskOrdinalLabel }}</span>
        @endif
    </td>

    <!-- 2. TASK NAME -->
    <td class="py-4 px-4 align-middle min-w-[240px]" style="padding-left: {{ ($level - 1) * 24 + 16 }}px">
        <div class="flex items-center gap-2 flex-wrap">
            @if($hasChildren)
                <button 
                    wire:click="toggleCollapse({{ $item->id }})" 
                    type="button" 
                    class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-700 font-extrabold text-[11px] flex items-center gap-1.5 transition-all cursor-pointer shadow-2xs border border-slate-200"
                    title="{{ $isCollapsed ? 'Click to expand subtasks' : 'Click to collapse subtasks' }}"
                >
                    <span class="text-[10px]">{{ $isCollapsed ? '▶' : '▼' }}</span>
                    <span class="text-[10px] font-bold font-sans">{{ $item->children->count() }} Subtasks</span>
                </button>
            @endif

            @if($isSubTask)
                <span class="text-slate-400 font-bold text-xs">↳</span>
            @endif

            <span class="text-xs font-black text-slate-900 {{ !$isSubTask ? 'text-sm' : '' }}">
                {{ $item->title }}
            </span>

            @if($isSubTask)
                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 border border-slate-200">Sub-task</span>
            @endif

            @if($item->is_milestone)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-black bg-amber-100 text-amber-800 border border-amber-300 shadow-2xs">
                    🏁 Milestone
                </span>
            @endif

            @if($item->risks && $item->risks->count() > 0)
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9.5px] font-black bg-amber-50 text-amber-800 border border-amber-300 shadow-2xs" title="{{ $item->risks->count() }} Associated Risk(s)">
                    <span>⚠️</span>
                    <span>{{ $item->risks->count() }} {{ Str::plural('Risk', $item->risks->count()) }}</span>
                </span>
            @endif
        </div>

        {{-- Predecessor dependency pills --}}
        @if($item->predecessors && $item->predecessors->count() > 0)
            <div class="flex flex-wrap items-center gap-1 mt-1.5">
                @foreach($item->predecessors as $dep)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9.5px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        After: {{ $dep->predecessor->wbs_code ?? '?' }} {{ Str::limit($dep->predecessor->title ?? 'Unknown', 22) }}
                        <button wire:click="removeDependency({{ $dep->id }})" wire:confirm="Remove this dependency?" class="ml-0.5 text-blue-400 hover:text-rose-600 transition-colors cursor-pointer" title="Remove dependency">&times;</button>
                    </span>
                @endforeach
            </div>
        @endif
    </td>

    <!-- 3. START DATE -->
    <td class="py-4 px-4 text-xs font-mono align-middle whitespace-nowrap">
        @if($item->start_date)
            @php
                $isStartedOrPast = $item->start_date->lte(now()->today());
            @endphp
            <span class="px-2.5 py-1 rounded-lg {{ $isStartedOrPast ? 'bg-emerald-50 font-black text-emerald-800 border border-emerald-200/90' : 'bg-slate-100 font-extrabold text-slate-800 border border-slate-200/90' }} flex items-center gap-1.5 w-fit shadow-2xs">
                <span>🚀</span>
                <span>{{ $item->start_date->format('M d, Y') }}</span>
            </span>
        @else
            <span class="text-slate-400 font-normal italic">No start date</span>
        @endif
    </td>

    <!-- 4. DEADLINE -->
    <td class="py-4 px-4 text-xs font-mono align-middle whitespace-nowrap">
        @if($item->end_date)
            @php
                $isOverdue = $item->end_date->lt(now()->today()) && $item->status->value !== 'completed';
            @endphp
            @if($isOverdue)
                <div class="flex flex-col gap-1">
                    <span class="px-2.5 py-1 rounded-lg bg-rose-50 font-black border border-rose-200 text-rose-700 flex items-center gap-1.5 w-fit shadow-2xs">
                        <span>📅</span>
                        <span>{{ $item->end_date->format('M d, Y') }}</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-md text-[9px] font-black bg-[#c3122e] text-white tracking-wider uppercase w-fit shadow-2xs flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        <span>Deadline Over</span>
                    </span>
                </div>
            @else
                <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-extrabold border border-slate-200/90 text-slate-800 flex items-center gap-1.5 w-fit">
                    <span>📅</span>
                    <span>{{ $item->end_date->format('M d, Y') }}</span>
                </span>
            @endif
        @else
            <span class="text-slate-400 font-normal italic">No deadline set</span>
        @endif
    </td>

    <!-- 4. STATUS (Interactive 3-Option Dropdown: Incomplete, In Progress, Completed) -->
    <td class="py-4 px-4 align-middle whitespace-nowrap">
        @php
            $isOverdue = $item->end_date && $item->end_date->lt(now()->today()) && $item->status->value !== 'completed';
        @endphp
        <div class="flex flex-col gap-1">
            <select
                wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
                class="text-[11px] font-extrabold rounded-xl px-3 py-1.5 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30 transition-all shadow-2xs
                    {{ match($item->status->value) {
                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'in_progress' => 'bg-[#fdf8e8] text-[#8a6508] border-[#f0e0a0]',
                        default => ($isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-slate-50 text-slate-600 border-slate-200'),
                    } }}"
                title="Update Task Status"
            >
                <option value="not_started" @selected($item->status->value === 'not_started' || $item->status->value === 'backlog' || $item->status->value === 'on_hold') class="bg-white text-slate-900 font-medium">
                    Incomplete
                </option>
                <option value="in_progress" @selected($item->status->value === 'in_progress' || $item->status->value === 'under_review' || $item->status->value === 'blocked') class="bg-white text-slate-900 font-medium">
                    In Progress
                </option>
                <option value="completed" @selected($item->status->value === 'completed') class="bg-white text-slate-900 font-medium">
                    Completed
                </option>
            </select>
        </div>
    </td>

    <!-- 5. ASSIGNED USER -->
    <td class="py-4 px-4 text-xs text-slate-700 font-semibold align-middle whitespace-nowrap">
        @if($item->assignedUser)
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-[#c3122e] text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-2xs">
                    {{ strtoupper(substr($item->assignedUser->name, 0, 1)) }}
                </div>
                <span class="truncate font-extrabold text-slate-800 max-w-[140px]">{{ $item->assignedUser->name }}</span>
            </div>
        @else
            <span class="text-slate-400 font-normal italic">Unassigned</span>
        @endif
    </td>

    <!-- 6. ACTIONS (Sub-task, Edit, Log Update, Link Dep & Delete) -->
    <td class="py-4 pl-4 pr-6 text-right align-middle whitespace-nowrap">
        <div class="flex items-center justify-end gap-2">
            @if($item->project->userCan(auth()->user(), 'task.create_subtask') || $item->project->userCan(auth()->user(), 'task.create'))
                <button wire:click="openAddItemModal({{ $item->id }}, 'subtask')" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-extrabold transition-all flex items-center gap-1 cursor-pointer shadow-2xs" title="Create Sub-task under this task">
                    <span>➕ Sub-task</span>
                </button>
            @endif

            @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                <button wire:click="openEditItemModal({{ $item->id }})" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#fdf4f4] text-slate-700 hover:text-[#c3122e] border border-slate-200/90 text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                    <span>✏️ Edit</span>
                </button>
            @endif

            @if($item->project->userCan(auth()->user(), 'task.comment'))
                <a href="{{ route('daily-updates.index', ['project' => $item->project_id, 'task' => $item->id, 'create' => 1]) }}" class="px-3 py-1.5 rounded-xl bg-[#fdf4f4] hover:bg-[#faeaea] text-[#c3122e] border border-[#faeaea] text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs no-underline">
                    <span>📝 Log Update</span>
                </a>
            @endif

            {{-- Dependency Link Button --}}
            @if($item->project->userCan(auth()->user(), 'task.edit', $item))
                <button wire:click="openDepModal({{ $item->id }})" class="px-2.5 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-extrabold transition-all flex items-center gap-1 cursor-pointer shadow-2xs" title="Link a predecessor task">
                    <span>🔗 Link Dep</span>
                </button>
            @endif

            @if($item->project->userCan(auth()->user(), 'task.delete', $item))
                <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete task '{{ addslashes($item->title) }}'?" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 border border-rose-200 text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                    <span>🗑️ Delete</span>
                </button>
            @endif
        </div>
    </td>
</tr>

@if(!$isCollapsed)
    @foreach($item->children as $child)
        @include('livewire.wbs-tree-row', ['item' => $child, 'level' => $level + 1, 'collapsedIds' => $collapsedIds])
    @endforeach
@endif
