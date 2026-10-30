<div class="project-reassign-backdrop" role="dialog" aria-modal="true" aria-labelledby="reassign-project-title" aria-describedby="reassign-project-context" x-data x-trap.inert.noscroll="true">
    <style>
        .project-reassign-backdrop {
            position: fixed; inset: 0; z-index: 100; display: flex; align-items: center;
            justify-content: center; padding: 24px; background: rgba(15, 23, 42, .5); backdrop-filter: blur(4px);
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif; color: #0f172a;
        }
        .project-reassign-dialog {
            width: 100%; max-width: 840px; max-height: min(860px, calc(100dvh - 48px));
            display: flex; flex-direction: column; min-height: 0; margin: 0; overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .65); border-radius: 20px; background: #fff;
            box-shadow: 0 28px 80px rgba(15, 23, 42, .24);
        }
        .project-reassign-dialog .reassign-header {
            display: flex; align-items: flex-start; gap: 14px; padding: 24px 26px 20px;
            border-bottom: 1px solid #edf0f4; flex-shrink: 0;
        }
        .project-reassign-dialog .reassign-header-icon {
            display: grid; place-items: center; width: 44px; height: 44px; flex-shrink: 0;
            border: 1px solid #f7dce2; border-radius: 13px; background: #fff2f5; color: #c3122e;
        }
        .project-reassign-dialog .reassign-header-icon svg { width: 22px; height: 22px; }
        .project-reassign-dialog .reassign-heading { min-width: 0; flex: 1; }
        .project-reassign-dialog .reassign-eyebrow { display: block; color: #94a3b8; font-size: 10px; font-weight: 700; letter-spacing: .09em; text-transform: uppercase; margin-bottom: 4px; }
        .project-reassign-dialog h2 { margin: 0; font-size: 22px; font-weight: 750; line-height: 1.25; letter-spacing: -.7px; }
        .project-reassign-dialog .reassign-context { margin: 7px 0 0; font-size: 12px; line-height: 1.6; color: #64748b; }
        .project-reassign-dialog .reassign-context span { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .project-reassign-dialog .reassign-context code { font-size: 10px; color: #94a3b8; }
        .project-reassign-dialog .reassign-close {
            display: grid; place-items: center; flex-shrink: 0; width: 32px; height: 32px; min-height: 32px;
            border: 1px solid #e8edf3; border-radius: 9px; background: #fff; color: #94a3b8; cursor: pointer;
        }
        .project-reassign-dialog .reassign-close:hover { color: #475569; background: #f8fafc; }
        .project-reassign-dialog .reassign-close svg { width: 16px; height: 16px; }
        .project-reassign-dialog .reassign-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overscroll-behavior: contain; padding: 22px 26px 26px; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        .project-reassign-dialog .reassign-body > * + * { margin-top: 22px; }
        .project-reassign-dialog .reassign-decline { padding: 14px 16px; border-radius: 12px; border: 1px solid #f6dce2; background: #fff6f8; }
        .project-reassign-dialog .reassign-decline-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px 18px; flex-wrap: wrap; }
        .project-reassign-dialog .reassign-decline-title { display: flex; align-items: center; gap: 7px; margin: 0; font-size: 12px; font-weight: 650; color: #a7102b; }
        .project-reassign-dialog .reassign-decline-title svg { width: 15px; height: 15px; flex-shrink: 0; }
        .project-reassign-dialog .reassign-previous-manager { margin: 0; font-size: 11px; line-height: 1.6; color: #9c6672; overflow-wrap: anywhere; }
        .project-reassign-dialog .reassign-decline-message { max-height: 96px; overflow-y: auto; margin: 9px 0 0; color: #854053; font-size: 12px; line-height: 1.7; white-space: pre-line; overflow-wrap: anywhere; }
        .project-reassign-dialog .reassign-leader { padding: 16px; background: #fff9fa; border: 1px solid #f1dce2; border-radius: 12px; }
        .project-reassign-dialog .reassign-field { min-width: 0; }
        .project-reassign-dialog label { display: block; margin-bottom: 7px; font-size: 12px; line-height: 1.4; font-weight: 650; color: #334155; }
        .project-reassign-dialog .reassign-required { color: #c3122e; }
        .project-reassign-dialog .reassign-optional { float: right; margin-left: 8px; color: #94a3b8; font-size: 10px; font-weight: 500; }
        .project-reassign-dialog .reassign-input {
            display: block; width: 100%; max-width: 100%; height: 43px; padding: 0 12px;
            border: 1px solid #dfe5ed; border-radius: 9px; background-color: #fff; color: #1e293b;
            font-family: inherit; font-size: 12px; font-weight: 500; line-height: 1.5;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .02); transition: border-color .15s, box-shadow .15s;
        }
        .project-reassign-dialog select.reassign-input { padding-right: 32px; cursor: pointer; text-overflow: ellipsis; }
        .project-reassign-dialog textarea.reassign-input { height: auto; min-height: 86px; padding: 11px 12px; resize: vertical; }
        .project-reassign-dialog .reassign-input::placeholder { color: #94a3b8; font-weight: 400; }
        .project-reassign-dialog .reassign-input:hover { border-color: #b8c4d3; }
        .project-reassign-dialog .reassign-input:focus { outline: none; border-color: #c3122e; box-shadow: 0 0 0 3px rgba(195, 18, 46, .08); }
        .project-reassign-dialog .reassign-field:has(.reassign-error) .reassign-input { border-color: #e28394; }
        .project-reassign-dialog .reassign-code { font-family: ui-monospace, monospace; text-transform: uppercase; background: #fbfcfd; }
        .project-reassign-dialog .reassign-helper { margin: 8px 0 0; font-size: 11px; color: #8490a1; line-height: 1.6; }
        .project-reassign-dialog .reassign-error { display: block; margin-top: 5px; font-size: 11px; line-height: 1.5; color: #be123c; }
        .project-reassign-dialog .reassign-errors { padding: 12px 14px; border: 1px solid #fecdd3; border-radius: 10px; color: #9f1239; background: #fff1f2; font-size: 12px; }
        .project-reassign-dialog .reassign-errors p { margin: 0; font-weight: 650; }
        .project-reassign-dialog .reassign-errors ul { margin: 6px 0 0; padding-left: 18px; list-style: disc; line-height: 1.6; }
        .project-reassign-dialog h3 { margin: 0 0 14px; font-size: 13px; font-weight: 700; letter-spacing: -.15px; }
        .project-reassign-dialog .reassign-section { padding-top: 21px; border-top: 1px solid #edf0f4; }
        .project-reassign-dialog .reassign-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .project-reassign-dialog .reassign-grid + .reassign-grid { margin-top: 16px; }
        .project-reassign-dialog .reassign-identity { grid-template-columns: minmax(0, 1fr) minmax(0, 2fr); }
        .project-reassign-dialog .reassign-team-toggle { display: flex; align-items: center; justify-content: space-between; gap: 12px; width: 100%; padding: 0; background: transparent; border: 0; text-align: left; cursor: pointer; color: #334155; }
        .project-reassign-dialog .reassign-team-toggle strong { display: block; font-size: 13px; font-weight: 700; }
        .project-reassign-dialog .reassign-team-toggle small { display: block; margin-top: 5px; font-size: 11px; line-height: 1.5; font-weight: 400; color: #94a3b8; }
        .project-reassign-dialog .reassign-team-toggle svg { width: 18px; height: 18px; flex-shrink: 0; color: #94a3b8; transition: transform .15s; }
        .project-reassign-dialog .reassign-team-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 18px; padding-top: 20px; }
        .project-reassign-dialog .reassign-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 9px; }
        .project-reassign-dialog .reassign-chip { display: inline-flex; align-items: center; gap: 7px; max-width: 100%; padding: 4px 5px 4px 9px; border: 1px solid #e2e8f0; border-radius: 7px; background: #f8fafc; color: #475569; font-size: 11px; line-height: 1.5; }
        .project-reassign-dialog .reassign-chip-name { min-width: 0; overflow-wrap: anywhere; }
        .project-reassign-dialog .reassign-chip-remove { display: grid; place-items: center; flex-shrink: 0; width: 24px; height: 24px; min-height: 24px; padding: 0; border: 0; border-radius: 5px; color: #94a3b8; background: transparent; cursor: pointer; font-size: 16px; }
        .project-reassign-dialog .reassign-chip-remove:hover { color: #be123c; background: #ffe4e6; }
        .project-reassign-dialog .reassign-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 26px; border-top: 1px solid #edf0f4; background: #fbfcfd; flex-shrink: 0; }
        .project-reassign-dialog .reassign-footer-note { margin: 0; font-size: 11px; color: #94a3b8; }
        .project-reassign-dialog .reassign-footer-actions { display: flex; gap: 9px; }
        .project-reassign-dialog .reassign-button { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 40px; padding: 0 17px; border: 1px solid #dfe5ed; border-radius: 9px; background: #fff; color: #475569; font-family: inherit; font-size: 12px; font-weight: 650; line-height: 1.4; cursor: pointer; }
        .project-reassign-dialog .reassign-button:hover { background: #f1f5f9; }
        .project-reassign-dialog .reassign-submit { background: #c3122e; color: #fff; border-color: #c3122e; box-shadow: 0 2px 5px rgba(195, 18, 46, .12); }
        .project-reassign-dialog .reassign-submit:hover { background: #a81029; border-color: #a81029; }
        .project-reassign-dialog button:disabled { opacity: .55; cursor: wait; }
        .project-reassign-dialog button:focus-visible { outline: 2px solid #c3122e; outline-offset: 3px; }
        .project-reassign-dialog .reassign-spinner { width: 14px; height: 14px; animation: reassign-spin .8s linear infinite; }
        @keyframes reassign-spin { to { transform: rotate(360deg); } }
        @media (max-width: 639px) {
            .project-reassign-backdrop { padding: 12px; }
            .project-reassign-dialog { max-height: calc(100dvh - 24px); border-radius: 16px; }
            .project-reassign-dialog .reassign-header { padding: 19px 18px 16px; gap: 10px; }
            .project-reassign-dialog .reassign-header-icon { width: 36px; height: 36px; border-radius: 10px; }
            .project-reassign-dialog h2 { font-size: 19px; }
            .project-reassign-dialog .reassign-body { padding: 18px; }
            .project-reassign-dialog .reassign-grid, .project-reassign-dialog .reassign-identity, .project-reassign-dialog .reassign-team-grid { grid-template-columns: minmax(0, 1fr); }
            .project-reassign-dialog .reassign-footer { padding: 14px 18px; }
            .project-reassign-dialog .reassign-footer-note { display: none; }
            .project-reassign-dialog .reassign-footer-actions { width: 100%; }
            .project-reassign-dialog .reassign-footer-actions > * { flex: 1; }
            .project-reassign-dialog .reassign-decline-message { max-height: 80px; }
        }
        @media (prefers-reduced-motion: reduce) { .project-reassign-dialog .reassign-spinner { animation: none; } }
    </style>

    <form wire:submit="submitReassignPm" class="project-reassign-dialog">
        <header class="reassign-header">
            <span class="reassign-header-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-9m5-4 4 4-4 4M4 17h9m-5 4-4-4 4-4M4 8a8 8 0 0 1 13-5m3 13a8 8 0 0 1-13 5"/></svg>
            </span>
            <div class="reassign-heading">
                <span class="reassign-eyebrow">Project leadership</span>
                <h2 id="reassign-project-title">Reassign project</h2>
                <p id="reassign-project-context" class="reassign-context">
                    <span title="{{ $reassignProject->name }}">{{ $reassignProject->name }}</span>
                    <code>{{ $reassignProject->code }}</code>
                </p>
            </div>
            <button type="button" wire:click="closeReassignModal" wire:loading.attr="disabled" wire:target="submitReassignPm" class="reassign-close" aria-label="Close reassign project">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
            </button>
        </header>

        <div class="reassign-body">
            @if($errors->any())
                <div class="reassign-errors" role="alert" tabindex="-1" x-data x-init="$nextTick(() => { $el.focus({ preventScroll: true }); $el.scrollIntoView({ block: 'nearest' }); })">
                    <p>Please review the highlighted fields.</p>
                    <ul>@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul>
                </div>
            @endif

            @if($reassignDeclineReason)
                <div class="reassign-decline">
                    <div class="reassign-decline-heading">
                        <p class="reassign-decline-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m10.3 3.9-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3.1l-8-14a2 2 0 0 0-3.4 0ZM12 9v4m0 4h.01"/></svg>
                            Previous assignment declined
                        </p>
                        @if($reassignProject->projectManager)
                            <p class="reassign-previous-manager">Previous manager: <strong>{{ $reassignProject->projectManager->name }}</strong></p>
                        @endif
                    </div>
                    <p class="reassign-decline-message">{{ $reassignDeclineReason }}</p>
                </div>
            @endif

            <div class="reassign-leader reassign-field">
                <label for="reassign-manager">New Project Manager <span class="reassign-required">*</span></label>
                <select id="reassign-manager" wire:model.live="reassignPmId" class="reassign-input" required>
                    <option value="">Choose a project manager</option>
                    @foreach($availablePms as $person)
                        <option value="{{ $person->id }}">{{ $person->name }} ({{ $person->subsidiary->code ?? 'GS' }})</option>
                    @endforeach
                </select>
                @error('reassignPmId') <span class="reassign-error">{{ $message }}</span> @enderror
                <p class="reassign-helper">
                    @if($reassignPmId === auth()->id())
                        You are assigning this project to yourself.
                    @else
                        The selected manager will receive a new assignment to review and approve.
                    @endif
                </p>
            </div>

            <section class="reassign-section" aria-labelledby="reassign-details-heading">
                <h3 id="reassign-details-heading">Project details</h3>
                <div class="reassign-grid reassign-identity">
                    <div class="reassign-field">
                        <label for="reassign-code">Project code <span class="reassign-required">*</span></label>
                        <input id="reassign-code" type="text" wire:model="reassignCode" class="reassign-input reassign-code" maxlength="30" required>
                        @error('reassignCode') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="reassign-field">
                        <label for="reassign-name">Project name <span class="reassign-required">*</span></label>
                        <input id="reassign-name" type="text" wire:model="reassignName" class="reassign-input" maxlength="255" required>
                        @error('reassignName') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="reassign-grid">
                    <div class="reassign-field">
                        <label for="reassign-subsidiary">Subsidiary <span class="reassign-required">*</span></label>
                        <select id="reassign-subsidiary" wire:model.live="reassignSubsidiaryId" class="reassign-input" required>
                            <option value="">Select a subsidiary</option>
                            @foreach($subsidiaries as $subsidiary)
                                <option value="{{ $subsidiary->id }}">{{ $subsidiary->name }} ({{ $subsidiary->code }})</option>
                            @endforeach
                        </select>
                        @error('reassignSubsidiaryId') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="reassign-field">
                        <label for="reassign-category">Category <span class="reassign-optional">Optional</span></label>
                        <input id="reassign-category" type="text" wire:model="reassignCategory" class="reassign-input" maxlength="100" placeholder="e.g. Digital transformation">
                        @error('reassignCategory') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="reassign-field">
                        <label for="reassign-priority">Priority <span class="reassign-required">*</span></label>
                        <select id="reassign-priority" wire:model="reassignPriority" class="reassign-input" required>
                            @foreach(\App\Enums\Priority::cases() as $priorityOption)
                                <option value="{{ $priorityOption->value }}">{{ $priorityOption->label() }}</option>
                            @endforeach
                        </select>
                        @error('reassignPriority') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <section class="reassign-section" aria-labelledby="reassign-schedule-heading">
                <h3 id="reassign-schedule-heading">Schedule &amp; budget</h3>
                <div class="reassign-grid">
                    <div class="reassign-field">
                        <label for="reassign-start-date">Start date <span class="reassign-required">*</span></label>
                        <input id="reassign-start-date" type="date" wire:model="reassignStartDate" class="reassign-input" required>
                        @error('reassignStartDate') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="reassign-field">
                        <label for="reassign-deadline">Target deadline <span class="reassign-optional">Optional</span></label>
                        <input id="reassign-deadline" type="date" wire:model="reassignDeadline" class="reassign-input">
                        @error('reassignDeadline') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="reassign-field">
                        <label for="reassign-budget">Estimated budget (LKR)</label>
                        <input id="reassign-budget" type="number" wire:model="reassignEstimatedBudget" class="reassign-input" min="0" step="0.01" placeholder="0.00">
                        @error('reassignEstimatedBudget') <span class="reassign-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <div class="reassign-field">
                <label for="reassign-description">Project description <span class="reassign-optional">Optional</span></label>
                <textarea id="reassign-description" wire:model="reassignDescription" class="reassign-input" rows="3" placeholder="Add context, objectives, or an updated project scope."></textarea>
                @error('reassignDescription') <span class="reassign-error">{{ $message }}</span> @enderror
            </div>

            <section class="reassign-section" x-data="{ expanded: false }">
                @php
                    $reassignRoles = [
                        ['key' => 'sponsors', 'label' => 'Project sponsors', 'ids' => $reassignSponsorIds, 'add' => 'addReassignSponsor', 'remove' => 'removeReassignSponsor'],
                        ['key' => 'owners', 'label' => 'Project owners', 'ids' => $reassignOwnerIds, 'add' => 'addReassignOwner', 'remove' => 'removeReassignOwner'],
                        ['key' => 'committee', 'label' => 'Steering committee', 'ids' => $reassignSteeringIds, 'add' => 'addReassignSteering', 'remove' => 'removeReassignSteering'],
                        ['key' => 'members', 'label' => 'Team members', 'ids' => $reassignMemberIds, 'add' => 'addReassignMember', 'remove' => 'removeReassignMember'],
                    ];
                    $roleAssignmentCount = count($reassignSponsorIds) + count($reassignOwnerIds) + count($reassignSteeringIds) + count($reassignMemberIds);
                @endphp
                <button type="button" class="reassign-team-toggle" @click="expanded = !expanded" :aria-expanded="expanded" aria-controls="reassign-team-settings">
                    <span>
                        <strong>Project team</strong>
                        <small>{{ $roleAssignmentCount ? $roleAssignmentCount . ' role assignments' : 'Optional team and governance settings' }}</small>
                    </span>
                    <svg :style="expanded ? 'transform: rotate(180deg)' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="reassign-team-settings" class="reassign-team-grid" x-show="expanded" x-cloak>
                    @foreach($reassignRoles as $role)
                        <div class="reassign-field" wire:key="reassign-role-{{ $role['key'] }}">
                            <label for="reassign-{{ $role['key'] }}">{{ $role['label'] }}</label>
                            <select id="reassign-{{ $role['key'] }}" class="reassign-input" wire:change="{{ $role['add'] }}($event.target.value)" x-on:change="$nextTick(() => { $el.value = '' })">
                                <option value="">Add a person</option>
                                @foreach($availablePms->reject(fn($person) => in_array((string) $person->id, array_map('strval', $role['ids']), true)) as $person)
                                    <option value="{{ $person->id }}">{{ $person->name }} ({{ $person->subsidiary->code ?? 'GS' }})</option>
                                @endforeach
                            </select>
                            @if(count($role['ids']))
                                <div class="reassign-chips">
                                    @foreach($availablePms->whereIn('id', array_map('intval', $role['ids'])) as $person)
                                        <span class="reassign-chip">
                                            <span class="reassign-chip-name">{{ $person->name }}</span>
                                            <button type="button" class="reassign-chip-remove" wire:click="{{ $role['remove'] }}('{{ $person->id }}')" aria-label="Remove {{ $person->name }} from {{ strtolower($role['label']) }}" title="Remove {{ $person->name }}">&times;</button>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <footer class="reassign-footer">
            <p class="reassign-footer-note"><span class="reassign-required">*</span> Required fields</p>
            <div class="reassign-footer-actions">
                <button type="button" wire:click="closeReassignModal" wire:loading.attr="disabled" wire:target="submitReassignPm" class="reassign-button">Cancel</button>
                <button type="submit" wire:loading.attr="disabled" class="reassign-button reassign-submit">
                    <span wire:loading.remove wire:target="submitReassignPm">Save &amp; reassign</span>
                    <span wire:loading.inline-flex wire:target="submitReassignPm" class="items-center gap-2" style="display: none;" role="status">
                        <svg class="reassign-spinner" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" opacity=".3"/><path d="M12 3a9 9 0 0 1 9 9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                        Saving...
                    </span>
                </button>
            </div>
        </footer>
    </form>
</div>
