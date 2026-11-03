@props(['t'])

@php
    $isDone = $t->status === \App\Enums\WbsStatus::COMPLETED;
    $isOverdue = $t->isOverdue();
    $hasTime = !empty($t->start_time_formatted) && !empty($t->end_time_formatted);
@endphp

<div x-data="{ currentStatus: '{{ $t->status->value }}', pendingStatus: '{{ $t->status->value }}', confirmingStatus: false }" @keydown.escape.window="pendingStatus = currentStatus; confirmingStatus = false" class="bg-white rounded-2xl p-3.5 border border-slate-200/90 shadow-2xs hover:shadow-xs hover:border-slate-300 transition-all duration-200 space-y-2.5 group relative {{ $isDone ? 'bg-emerald-50/20 border-emerald-200/80' : ($isOverdue ? 'bg-rose-50/25 border-rose-200' : '') }}">
    <!-- Top Row: Project Code Pill & Priority -->
    <div class="flex items-center justify-between gap-1.5">
        <span class="text-[9.5px] font-mono font-black px-2 py-0.5 rounded-lg bg-rose-50 text-[#c3122e] border border-rose-100 truncate max-w-[140px]" title="{{ $t->project->name ?? '' }}">
            {{ $t->project->code ?? 'PRJ' }}
        </span>
        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md {{ $t->priority->badgeClass() }}">
            {{ $t->priority->label() }}
        </span>
    </div>

    <!-- Middle: Task Title & Hour Capsule -->
    <div class="space-y-1.5">
        <div class="flex items-start gap-1.5 font-bold text-xs text-slate-900 group-hover:text-[#c3122e] transition-colors leading-snug">
            <span class="font-mono text-slate-400 font-extrabold flex-shrink-0 text-[11px]">{{ $t->wbs_code }}</span>
            <span class="{{ $isDone ? 'line-through text-slate-400' : 'text-slate-800' }}">{{ $t->title }}</span>
        </div>

        @if($hasTime)
            <div class="pt-0.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.8 rounded-lg text-[10px] font-mono font-bold text-violet-700 bg-violet-50 border border-violet-200/70 shadow-2xs">
                    <svg class="w-3 h-3 text-violet-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $t->start_time_formatted }} – {{ $t->end_time_formatted }}</span>
                </span>
            </div>
        @endif
    </div>

    <!-- Bottom Row: Assignee & Status Selector -->
    <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100 gap-2">
        @if($t->assignedUser)
            <div class="flex min-w-0 items-center gap-1.5" title="Assigned to {{ $t->assignedUser->name }}">
                <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-slate-800 text-[8.5px] font-extrabold text-white">
                    {{ strtoupper(substr($t->assignedUser->name, 0, 1)) }}
                </span>
                <span class="min-w-0 truncate text-[10px] font-semibold text-slate-600">
                    <span class="text-slate-400">Assigned:</span> {{ $t->assignedUser->name }}
                </span>
            </div>
        @else
            <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-400" title="No assignee">
                <span class="h-2 w-2 rounded-full border border-slate-300 bg-slate-100"></span>
                Unassigned
            </span>
        @endif
        <div class="relative flex items-center flex-shrink-0">
            <select x-model="pendingStatus" @change="if (pendingStatus !== currentStatus) confirmingStatus = true" class="text-[9.5px] font-bold px-2 py-0.5 rounded-lg border border-slate-200 bg-white cursor-pointer shadow-2xs outline-none focus:border-[#c3122e] {{ $t->status->badgeClass() }}" aria-label="Change task status">
                @foreach(\App\Enums\WbsStatus::selectableCases() as $st)
                    <option value="{{ $st->value }}">
                        {{ $st->label() }}
                    </option>
                @endforeach
            </select>
            <div x-cloak x-show="confirmingStatus" x-transition @click.outside="pendingStatus = currentStatus; confirmingStatus = false" class="absolute z-30 right-0 bottom-[calc(100%+8px)] w-[230px] rounded-xl border border-slate-200 bg-white p-3 text-left shadow-2xl" role="alertdialog" aria-label="Confirm status change">
                <div class="flex items-start gap-2.5">
                    <span class="inline-flex w-7 h-7 shrink-0 items-center justify-center rounded-full border border-blue-200 bg-blue-50 text-blue-700">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </span>
                    <div><p class="text-[11px] font-extrabold text-slate-900">Change task status?</p><p class="mt-0.5 text-[9.5px] leading-relaxed text-slate-500">The selected status will be applied to this task.</p></div>
                </div>
                <div class="mt-3 flex items-center justify-end gap-2">
                    <button @click="pendingStatus = currentStatus; confirmingStatus = false" type="button" class="h-[30px] px-2.5 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-100 cursor-pointer">Cancel</button>
                    <button @click="$wire.updateStatus({{ $t->id }}, pendingStatus); currentStatus = pendingStatus; confirmingStatus = false" type="button" class="inline-flex h-[30px] items-center justify-center rounded-lg bg-[#c3122e] px-3 text-[10px] font-bold text-white hover:bg-[#9f1239] cursor-pointer">Confirm change</button>
                </div>
            </div>
        </div>
    </div>
</div>
