@props(['item', 'level' => 1, 'collapsedIds' => []])

@php
    $isSubTask = ($level > 1) || !empty($item->parent_id);
    $hasChildren = $item->children && $item->children->count() > 0;

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

<tr class="border-b border-slate-100 hover:bg-[#fdf4f4]/40 transition-colors {{ $isSubTask ? 'bg-slate-50/40' : '' }}">
    <!-- 1. TASK NUMBER (#) -->
    <td class="py-4 pl-6 pr-4 font-mono text-xs font-black text-[#c3122e] whitespace-nowrap align-middle">
        @if($isSubTask)
            <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-amber-50 text-amber-800 border border-amber-200 shadow-2xs">
                ↳ {{ $taskOrdinalLabel }}
            </span>
        @else
            <span>{{ $taskOrdinalLabel }}</span>
        @endif
    </td>

    <!-- 2. TASK NAME -->
    <td class="py-4 px-4 align-middle min-w-[220px]" style="padding-left: {{ ($level - 1) * 24 + 16 }}px">
        <div class="flex items-center gap-2">
            @if($hasChildren)
                <button wire:click="toggleCollapse({{ $item->id }})" type="button" class="w-5 h-5 rounded bg-slate-100 text-slate-700 hover:bg-[#c3122e] hover:text-white font-bold text-[10px] flex items-center justify-center transition-all cursor-pointer shadow-2xs">
                    {{ in_array($item->id, $collapsedIds) ? '▶' : '▼' }}
                </button>
            @endif

            @if($isSubTask)
                <span class="text-slate-400 font-bold text-xs">↳</span>
            @endif

            <span class="text-xs font-extrabold text-slate-900">
                {{ $item->title }}
            </span>

            @if($isSubTask)
                <span class="text-[9px] font-extrabold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">Sub-task</span>
            @endif
        </div>
    </td>

    <!-- 3. DEADLINE -->
    <td class="py-4 px-4 text-xs text-slate-700 font-mono align-middle whitespace-nowrap">
        @if($item->end_date)
            <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-extrabold border border-slate-200/90 text-slate-800 flex items-center gap-1.5 w-fit">
                <span>📅</span>
                <span>{{ $item->end_date->format('M d, Y') }}</span>
            </span>
        @else
            <span class="text-slate-400 font-normal italic">No deadline set</span>
        @endif
    </td>

    <!-- 4. STATUS (Interactive Dropdown) -->
    <td class="py-4 px-4 align-middle whitespace-nowrap">
        <select
            wire:change="updateItemStatus({{ $item->id }}, $event.target.value)"
            class="text-[11px] font-extrabold rounded-xl px-3 py-1.5 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#c3122e]/30 transition-all shadow-2xs
                {{ match($item->status->value) {
                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'in_progress' => 'bg-[#fdf8e8] text-[#8a6508] border-[#f0e0a0]',
                    'blocked' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'under_review' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'on_hold' => 'bg-slate-100 text-slate-700 border-slate-200',
                    default => 'bg-slate-50 text-slate-600 border-slate-200',
                } }}"
            title="Update Task Status"
        >
            @foreach(\App\Enums\WbsStatus::cases() as $st)
                <option value="{{ $st->value }}" @selected($item->status->value === $st->value) class="bg-white text-slate-900 font-medium">
                    {{ $st->label() }}
                </option>
            @endforeach
        </select>
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

    <!-- 6. ACTIONS (Sub-task, Log Update, Edit & Delete) -->
    <td class="py-4 pl-4 pr-6 text-right align-middle whitespace-nowrap">
        <div class="flex items-center justify-end gap-2">
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $item->project->project_manager_id === auth()->id() || $item->assigned_user_id === auth()->id() || $item->project->members->contains(auth()->id()))
                <button wire:click="openAddItemModal({{ $item->id }}, 'subtask')" class="px-2.5 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-extrabold transition-all flex items-center gap-1 cursor-pointer shadow-2xs" title="Create Sub-task under this task">
                    <span>➕ Sub-task</span>
                </button>
            @endif

            <a href="{{ route('daily-updates.index', ['project' => $item->project_id, 'task' => $item->id, 'create' => 1]) }}" class="px-3 py-1.5 rounded-xl bg-[#fdf4f4] hover:bg-[#faeaea] text-[#c3122e] border border-[#faeaea] text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs no-underline">
                <span>📝 Log Update</span>
            </a>

            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $item->assigned_user_id === auth()->id())
                <button wire:click="openEditItemModal({{ $item->id }})" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-[#fdf4f4] text-slate-700 hover:text-[#c3122e] border border-slate-200/90 text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                    <span>✏️ Edit</span>
                </button>
                <button wire:click="deleteItem({{ $item->id }})" wire:confirm="Are you sure you want to delete task '{{ addslashes($item->title) }}'?" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 hover:text-rose-800 border border-rose-200 text-xs font-extrabold transition-all flex items-center gap-1.5 cursor-pointer shadow-2xs">
                    <span>🗑️ Delete</span>
                </button>
            @endif
        </div>
    </td>
</tr>

@if(!in_array($item->id, $collapsedIds))
    @foreach($item->children as $child)
        @include('livewire.wbs-tree-row', ['item' => $child, 'level' => $level + 1, 'collapsedIds' => $collapsedIds])
    @endforeach
@endif
