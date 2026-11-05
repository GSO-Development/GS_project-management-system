{{--
    Azure AD people search for a people-picker (project team dialog, reassign project screens).

    Expects (all optional except $roleKey):
      $roleKey      picker key: pm | sponsor | owner | steering | member | collab
      $azureEnabled false hides the whole thing (user is not allowed to search Azure AD)
      $showInput    true renders a search box bound to `azureSearch.{roleKey}` above the results
      $inputClass   css classes for that search box
      $placeholder  placeholder for that search box
      $roleOptions  [code => label] renders an "Assign as" role picker bound to `azureAssignRole`
      $addLabel     override for the "Add & assign" button

    The results panel opens by itself while 2+ characters are typed. One click adds the person to the
    system (if they are new) and puts them straight into the role. State lives in the
    App\Livewire\Concerns\SearchesAzureDirectory trait ($azureRole, $azureQuery, $azureResults, $azureError).
--}}
@php
    $azureEnabled  = $azureEnabled ?? false;
    $showInput     = $showInput ?? false;
    $inputClass    = $inputClass ?? 'w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs text-slate-900 focus:border-[#c3122e] outline-none transition-all';
    $placeholder   = $placeholder ?? 'Search by name or e-mail (Azure AD included)...';
    $roleOptions   = $roleOptions ?? null;
    $addLabel      = $addLabel ?? null;
    $azureActive   = $azureEnabled && ($azureRole ?? null) === $roleKey && mb_strlen(trim($azureQuery ?? '')) >= 2;
    $azureTargets  = 'azureSearch,collaboratorSearch,addAzureUser';
@endphp

@if(!empty($pickLocal))
    {{-- One-row picker: people already in the system first, then Azure AD matches, all in a single dropdown. --}}
    @php
        $pickExcludeIds = match($roleKey) {
            'sponsor'  => $reassignSponsorIds ?? [],
            'owner'    => $reassignOwnerIds ?? [],
            'steering' => $reassignSteeringIds ?? [],
            'member'   => $reassignMemberIds ?? [],
            'pm'       => array_filter([$reassignPmId ?? null]),
            default    => [],
        };
    @endphp
    @include('livewire.partials.person-picker', [
        'roleKey'     => $roleKey,
        'people'      => $availablePms ?? collect(),
        'excludeIds'  => $pickExcludeIds,
        'azureEnabled'=> $azureEnabled,
        'inputClass'  => $inputClass,
        'placeholder' => $placeholder,
    ])
@else

@if($azureEnabled)
<div class="space-y-2" wire:key="azure-panel-{{ $roleKey }}">
    @if($showInput)
        <input type="search"
            wire:model.live.debounce.500ms="azureSearch.{{ $roleKey }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            x-on:keydown.enter.prevent
            aria-label="{{ $placeholder }}"
            class="{{ $inputClass }}">
    @endif

    @if($azureActive)
        <div class="rounded-xl border border-rose-200/70 bg-rose-50/30 p-2 space-y-1.5">
            <div class="flex items-center justify-between gap-2 px-1">
                <div class="flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wide text-[#c3122e]">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8.5v8.5H3V3zm9.5 0H21v8.5h-8.5V3zM3 12.5h8.5V21H3v-8.5zm9.5 0H21V21h-8.5v-8.5z"/></svg>
                    From Azure AD
                </div>
                @if(!empty($roleOptions))
                    <label class="flex items-center gap-1.5 text-[10px] font-bold text-slate-500">
                        Assign as
                        <select wire:model.live="azureAssignRole" class="px-2 py-1 rounded-lg border border-slate-200 bg-white text-[11px] font-extrabold text-slate-800 outline-none focus:border-[#c3122e] cursor-pointer">
                            @foreach($roleOptions as $optCode => $optLabel)
                                <option value="{{ $optCode }}">{{ $optLabel }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif
            </div>

            <div wire:loading wire:target="{{ $azureTargets }}" class="flex items-center justify-center gap-2 py-2 text-[11px] font-semibold text-slate-500">
                <svg class="animate-spin w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                Searching Azure AD...
            </div>

            <div wire:loading.remove wire:target="{{ $azureTargets }}" class="space-y-1">
                @if($azureError)
                    <div class="px-3 py-2 rounded-xl bg-rose-50 border border-rose-200 text-[11px] font-semibold text-rose-700">{{ $azureError }}</div>
                @endif

                @forelse($azureResults as $azIndex => $az)
                    @php
                        $azBlocked = $az['blocked_reason'] ?? null;
                        $azName = $az['displayName'] ?? $az['name'] ?? 'Azure User';
                    @endphp
                    <div wire:key="azure-{{ $roleKey }}-{{ $az['id'] ?? $azIndex }}" class="flex items-center justify-between gap-2 px-2.5 py-2 rounded-xl bg-white border border-slate-200/80">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 font-bold text-[10px] flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($azName, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-900 truncate">{{ $azName }}</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ $az['email'] ?? '' }}@if(!empty($az['department'])) · {{ $az['department'] }}@endif</div>
                            </div>
                        </div>

                        @if($azBlocked)
                            <span class="shrink-0 text-[10px] font-semibold text-slate-400 italic text-right">{{ $azBlocked }}</span>
                        @else
                            <button type="button"
                                wire:click="addAzureUser({{ $azIndex }})"
                                wire:loading.attr="disabled"
                                wire:target="addAzureUser"
                                class="shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-extrabold text-white bg-[#c3122e] hover:bg-[#a50e26] transition-all cursor-pointer disabled:opacity-60">
                                {{ $addLabel ?? (!empty($az['in_system']) ? 'Assign' : 'Add & assign') }}
                            </button>
                        @endif
                    </div>
                @empty
                    @if(!$azureError)
                        <div class="py-1.5 text-center text-[11px] text-slate-400 font-medium">No match found in Azure AD.</div>
                    @endif
                @endforelse
            </div>
        </div>
    @elseif(!$showInput)
        <p class="px-1 text-[10px] text-slate-400 font-medium leading-snug">
            Not in the list? Type a name or e-mail &mdash; Azure AD is searched automatically.
        </p>
    @endif
</div>
@endif
@endif
