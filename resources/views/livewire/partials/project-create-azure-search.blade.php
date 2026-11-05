{{--
    Inline Azure AD results for the Project Team dropdowns on the Create Project page.

    Expects:  $roleKey  one of pm | owner | sponsor | steering | member | custom:{role_code}

    The panel appears automatically while the user types (2+ characters) in the dropdown's search box
    and lists people who are NOT already in the dropdown. One click adds the person to the system
    (if new) and assigns them to this role.
    State lives on App\Livewire\ProjectCreate ($azureRole, $azureQuery, $azureResults, $azureError).
--}}
@php
    $azureActive = ($azureRole ?? null) === $roleKey && mb_strlen(trim($azureQuery ?? '')) >= 2;
    $azureTargets = 'leaderSearch,ownerSearch,sponsorSearch,steeringSearch,participantSearch,customRoleSearch,addAzureUser';
@endphp

<div class="border-t border-slate-100 pt-2" wire:key="azure-panel-{{ $roleKey }}">
    @if($azureActive)
        <div class="rounded-xl border border-rose-200/70 bg-rose-50/30 p-2 space-y-1.5">
            <div class="flex items-center gap-1.5 px-1 text-[10px] font-extrabold uppercase tracking-wide text-[#c3122e]">
                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8.5v8.5H3V3zm9.5 0H21v8.5h-8.5V3zM3 12.5h8.5V21H3v-8.5zm9.5 0H21V21h-8.5v-8.5z"/></svg>
                From Azure AD
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
                                {{ !empty($az['in_system']) ? 'Assign' : 'Add & assign' }}
                            </button>
                        @endif
                    </div>
                @empty
                    @if(!$azureError)
                        <div class="py-1.5 text-center text-[11px] text-slate-400 font-medium">No other match in Azure AD.</div>
                    @endif
                @endforelse
            </div>
        </div>
    @else
        <p class="px-1 text-[10px] text-slate-400 font-medium leading-snug">
            Not in the list? Keep typing the name or e-mail &mdash; Azure AD is searched automatically.
        </p>
    @endif
</div>
