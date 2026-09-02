@props(['t'])

@php
    $isDone = $t->status === \App\Enums\WbsStatus::COMPLETED;
    $isOverdue = $t->isOverdue();
    $hasTime = !empty($t->start_time_formatted) && !empty($t->end_time_formatted);
    $titleIsPureTime = (bool) preg_match('/^\d{1,2}:\d{2}\s*(?:AM|PM)\s*[-–—\s]+\s*\d{1,2}:\d{2}\s*(?:AM|PM)$/iu', trim($t->title));
@endphp

<div class="bg-white rounded-2xl p-3.5 border border-slate-200 shadow-2xs hover:shadow-md transition-all duration-200 space-y-2.5 group relative {{ $isDone ? 'bg-emerald-50/20 border-emerald-200/80' : ($isOverdue ? 'bg-rose-50/20 border-rose-200' : '') }}">
    <!-- Top Row: Project Code Pill & Priority -->
    <div class="flex items-center justify-between gap-1.5">
        <span class="text-[10px] font-mono font-black px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[150px]" title="{{ $t->project->name ?? '' }}">
            {{ $t->project->code ?? 'PRJ' }}
        </span>
        <span class="text-[9.5px] font-extrabold uppercase px-2 py-0.5 rounded-md {{ $t->priority->badgeClass() }}">
            {{ $t->priority->label() }}
        </span>
    </div>

    <!-- Middle: Task Title & Hour Capsule -->
    <div class="cursor-pointer space-y-1.5" wire:click="openTaskDetail({{ $t->id }})">
        <div class="flex items-start gap-1.5 font-extrabold text-xs text-slate-900 group-hover:text-[#c3122e] transition-colors leading-snug">
            <span class="font-mono text-slate-400 flex-shrink-0">{{ $t->wbs_code }}</span>
            <span class="{{ $isDone ? 'line-through text-slate-400' : '' }}">{{ $t->title }}</span>
        </div>

        @if($hasTime)
            <div class="pt-0.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10.5px] font-mono font-bold text-violet-700 bg-violet-50/90 border border-violet-200/80 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $t->start_time_formatted }} – {{ $t->end_time_formatted }}</span>
                </span>
            </div>
        @endif
    </div>

    <!-- Bottom Row: Project Name & Status Selector -->
    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100 gap-2">
        <span class="text-[10.5px] font-semibold text-slate-500 truncate max-w-[130px]" title="{{ $t->project->name ?? '' }}">
            {{ $t->project->name ?? '' }}
        </span>
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <select wire:change="updateStatus({{ $t->id }}, $event.target.value)" class="text-[10px] font-extrabold px-2 py-0.5 rounded-lg border border-slate-200 bg-white cursor-pointer shadow-2xs outline-none focus:border-[#c3122e] {{ $t->status->badgeClass() }}">
                @foreach(\App\Enums\WbsStatus::cases() as $st)
                    <option value="{{ $st->value }}" {{ $t->status === $st ? 'selected' : '' }}>
                        {{ $st->label() }}
                    </option>
                @endforeach
            </select>
            <button wire:click="openTaskDetail({{ $t->id }})" type="button" class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-[#c3122e] hover:text-white text-slate-600 flex items-center justify-center text-[10px] font-bold transition-all shadow-2xs cursor-pointer border border-slate-200" title="Open Task Details">
                ✏️
            </button>
        </div>
    </div>
</div>
