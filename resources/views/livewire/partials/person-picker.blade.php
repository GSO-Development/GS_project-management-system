{{--
    One-row people picker: a single search box whose dropdown lists
      1. people already in the system (filtered as you type), then
      2. matching people from Azure AD (when enabled) with a one-click "Add & assign".

    Uses the App\Livewire\Concerns\SearchesAzureDirectory trait (pickLocalUser / addAzureUser).

    Expects:
      $roleKey      picker key: pm | sponsor | owner | steering | member
      $people       Collection of User (already-in-system candidates)
      $excludeIds   int[] people to hide (already chosen for this role)
      $azureEnabled whether Azure AD search is allowed for the current user
      $inputId      optional id for the <input> (so a <label for> works)
      $inputClass   css classes for the input
      $placeholder  placeholder text
      $selected     optional User: single-select mode, shows them as a card instead of the search box
      $clearAction  wire expression that clears the single selection, e.g. "$set('reassignPmId', null)"
--}}
@php
    $azureEnabled = $azureEnabled ?? false;
    $excludeIds   = array_map('intval', $excludeIds ?? []);
    $inputId      = $inputId ?? null;
    $inputClass   = $inputClass ?? 'w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:border-[#c3122e] outline-none transition-all';
    $placeholder  = $placeholder ?? 'Search by name or e-mail...';
    $selected     = $selected ?? null;
    $clearAction  = $clearAction ?? null;

    $pickerQuery   = mb_strtolower(trim($azureSearch[$roleKey] ?? ''));
    $localMatches  = collect($people)
        ->reject(fn($p) => in_array((int) $p->id, $excludeIds, true))
        ->when($pickerQuery !== '', fn($c) => $c->filter(
            fn($p) => str_contains(mb_strtolower((string) $p->name), $pickerQuery)
                || str_contains(mb_strtolower((string) $p->email), $pickerQuery)
        ))
        ->take(40);

    $azureActive = $azureEnabled && ($azureRole ?? null) === $roleKey && mb_strlen(trim($azureQuery ?? '')) >= 2;

    // Azure rows for people the dropdown already lists (or that are already chosen) would only be duplicates.
    $knownEmails = collect($people)->pluck('email')->filter()->map(fn($e) => mb_strtolower($e))->all();
    $azureRows = $azureActive
        ? collect($azureResults)->filter(fn($az) => !in_array(mb_strtolower((string) ($az['email'] ?? '')), $knownEmails, true))
        : collect();

    $azureTargets = 'azureSearch,addAzureUser,pickLocalUser';
@endphp

<div wire:key="person-picker-{{ $roleKey }}">
@if($selected)
    <div class="flex items-center justify-between gap-2 px-3 py-2 rounded-xl border border-rose-200 bg-rose-50/40">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="w-7 h-7 rounded-full bg-[#c3122e] text-white font-bold text-[10px] flex items-center justify-center shrink-0">{{ strtoupper(substr($selected->name, 0, 1)) }}</div>
            <div class="min-w-0">
                <div class="text-xs font-bold text-slate-900 truncate">{{ $selected->name }}</div>
                <div class="text-[10px] text-slate-500 truncate">{{ $selected->email }}</div>
            </div>
        </div>
        @if($clearAction)
            <button type="button" wire:click="{{ $clearAction }}" class="shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-extrabold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer">Change</button>
        @endif
    </div>
@else
    <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" class="space-y-1.5">
        <input type="search"
            @if($inputId) id="{{ $inputId }}" @endif
            wire:model.live.debounce.400ms="azureSearch.{{ $roleKey }}"
            @focus="open = true"
            @input="open = true"
            x-on:keydown.enter.prevent
            autocomplete="off"
            placeholder="{{ $placeholder }}"
            aria-label="{{ $placeholder }}"
            class="{{ $inputClass }}">

        <div x-show="open" x-cloak class="rounded-xl border border-slate-200 bg-white shadow-sm max-h-64 overflow-y-auto p-1.5 space-y-1">
            @forelse($localMatches as $p)
                <button type="button" wire:key="pp-{{ $roleKey }}-{{ $p->id }}"
                    wire:click="pickLocalUser('{{ $roleKey }}', {{ $p->id }})" @click="open = false"
                    class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-left hover:bg-slate-100 transition-colors cursor-pointer">
                    <span class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center shrink-0">{{ strtoupper(substr($p->name, 0, 1)) }}</span>
                    <span class="min-w-0">
                        <span class="block text-xs font-bold text-slate-900 truncate">{{ $p->name }} <span class="font-semibold text-slate-400">({{ $p->subsidiary->code ?? 'GS' }})</span></span>
                        <span class="block text-[10px] text-slate-400 truncate">{{ $p->email }}</span>
                    </span>
                </button>
            @empty
                @if(!$azureEnabled || mb_strlen($pickerQuery) < 2)
                    <div class="py-2 text-center text-[11px] text-slate-400 font-medium">{{ $pickerQuery === '' ? 'No more people available.' : 'No match in the system.' }}</div>
                @endif
            @endforelse

            @if($azureEnabled && mb_strlen($pickerQuery) >= 2)
                <div class="border-t border-slate-100 pt-1.5 mt-1">
                    <div class="flex items-center gap-1.5 px-1.5 pb-1 text-[10px] font-extrabold uppercase tracking-wide text-[#c3122e]">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8.5v8.5H3V3zm9.5 0H21v8.5h-8.5V3zM3 12.5h8.5V21H3v-8.5zm9.5 0H21V21h-8.5v-8.5z"/></svg>
                        Not in the system? From Azure AD
                    </div>

                    <div wire:loading wire:target="{{ $azureTargets }}" class="flex items-center justify-center gap-2 py-2 text-[11px] font-semibold text-slate-500">
                        <svg class="animate-spin w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        Searching Azure AD...
                    </div>

                    <div wire:loading.remove wire:target="{{ $azureTargets }}" class="space-y-1">
                        @if($azureActive && $azureError)
                            <div class="px-3 py-2 rounded-xl bg-rose-50 border border-rose-200 text-[11px] font-semibold text-rose-700">{{ $azureError }}</div>
                        @endif

                        @foreach($azureRows as $azIndex => $az)
                            @php
                                $azBlocked = $az['blocked_reason'] ?? null;
                                $azName = $az['displayName'] ?? $az['name'] ?? 'Azure User';
                            @endphp
                            <div wire:key="az-{{ $roleKey }}-{{ $az['id'] ?? $azIndex }}" class="flex items-center justify-between gap-2 px-2.5 py-2 rounded-lg bg-rose-50/30 border border-rose-100">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center shrink-0">{{ strtoupper(substr($azName, 0, 1)) }}</span>
                                    <span class="min-w-0">
                                        <span class="block text-xs font-bold text-slate-900 truncate">{{ $azName }}</span>
                                        <span class="block text-[10px] text-slate-400 truncate">{{ $az['email'] ?? '' }}@if(!empty($az['department'])) · {{ $az['department'] }}@endif</span>
                                    </span>
                                </div>
                                @if($azBlocked)
                                    <span class="shrink-0 text-[10px] font-semibold text-slate-400 italic text-right">{{ $azBlocked }}</span>
                                @else
                                    <button type="button" wire:click="addAzureUser({{ $azIndex }})" @click="open = false" wire:loading.attr="disabled" wire:target="addAzureUser"
                                        class="shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-extrabold text-white bg-[#c3122e] hover:bg-[#a50e26] transition-all cursor-pointer disabled:opacity-60">
                                        {{ !empty($az['in_system']) ? 'Assign' : 'Add & assign' }}
                                    </button>
                                @endif
                            </div>
                        @endforeach

                        @if($azureActive && $azureRows->isEmpty() && !$azureError)
                            <div class="py-1.5 text-center text-[11px] text-slate-400 font-medium">No other match in Azure AD.</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
</div>

