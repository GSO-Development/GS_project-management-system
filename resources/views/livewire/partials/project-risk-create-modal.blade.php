<div class="project-risk-backdrop" role="dialog" aria-modal="true" aria-labelledby="project-risk-create-title" aria-describedby="project-risk-create-context"
    x-data x-trap.inert.noscroll="true" x-init="$nextTick(() => $refs.riskTitle.focus())"
    @keydown.escape.window="$wire.closeCreateRiskModal()" wire:click.self="closeCreateRiskModal">
    <style>
        .project-risk-backdrop { position:fixed;inset:0;z-index:2147483000;display:flex;align-items:center;justify-content:center;padding:24px;background:rgba(15,23,42,.5);backdrop-filter:blur(4px);font-family:'Plus Jakarta Sans','Inter',system-ui,sans-serif;color:#1e293b; }
        .project-risk-dialog { width:100%;max-width:760px;max-height:min(850px,calc(100dvh - 48px));display:flex;flex-direction:column;min-height:0;overflow:hidden;border:1px solid #e2e8f0;border-radius:18px;background:#fff;box-shadow:0 24px 72px rgba(15,23,42,.24); }
        .project-risk-dialog .risk-modal-header { display:flex;align-items:flex-start;gap:12px;padding:22px 24px;border-bottom:1px solid #edf0f4;flex-shrink:0; }
        .project-risk-dialog .risk-modal-icon { display:grid;place-items:center;flex-shrink:0;width:40px;height:40px;border:1px solid #fde5b8;border-radius:11px;background:#fffbeb;color:#b45309; }
        .project-risk-dialog .risk-modal-icon svg { width:20px;height:20px; }
        .project-risk-dialog .risk-modal-heading { flex:1;min-width:0; }
        .project-risk-dialog h2 { margin:0;color:#0f172a;font-size:18px;font-weight:750;letter-spacing:-.4px; }
        .project-risk-dialog .risk-modal-context { margin:5px 0 0;color:#64748b;font-size:11px;line-height:1.6;overflow-wrap:anywhere; }
        .project-risk-dialog .risk-modal-close { display:grid;place-items:center;flex-shrink:0;width:32px;height:32px;border:1px solid #e2e8f0;border-radius:8px;background:#fff;color:#94a3b8;cursor:pointer; }
        .project-risk-dialog .risk-modal-close:hover { background:#f8fafc;color:#475569; }
        .project-risk-dialog .risk-modal-close svg { width:15px;height:15px; }
        .project-risk-dialog .risk-modal-form { display:flex;flex-direction:column;flex:1;min-height:0;overflow:hidden;margin:0; }
        .project-risk-dialog .risk-modal-body { display:flex;flex-direction:column;gap:18px;padding:22px 24px;overflow-y:auto;overscroll-behavior:contain;flex:1;min-height:0;scrollbar-width:thin;scrollbar-gutter:stable;scrollbar-color:#cbd5e1 transparent; }
        .project-risk-dialog .risk-modal-grid { display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px; }
        .project-risk-dialog .risk-modal-field { min-width:0; }
        .project-risk-dialog label { display:block;margin-bottom:7px;color:#334155;font-size:12px;font-weight:650;line-height:1.4; }
        .project-risk-dialog .risk-modal-required { color:#c3122e; }
        .project-risk-dialog .risk-modal-optional { color:#94a3b8;font-size:10px;font-weight:400; }
        .project-risk-dialog .risk-modal-input { display:block;width:100%;max-width:100%;height:42px;padding:0 12px;border:1px solid #dce3eb;border-radius:8px;background:#fff;color:#1e293b;font-family:inherit;font-size:12px;font-weight:500;outline:none;box-shadow:none;transition:border-color .15s,box-shadow .15s; }
        .project-risk-dialog select.risk-modal-input { padding-right:30px;text-overflow:ellipsis;cursor:pointer; }
        .project-risk-dialog textarea.risk-modal-input { height:auto;min-height:90px;padding:10px 12px;line-height:1.7;resize:vertical; }
        .project-risk-dialog textarea.risk-modal-mitigation { min-height:70px; }
        .project-risk-dialog .risk-modal-input::placeholder { color:#94a3b8;font-weight:400; }
        .project-risk-dialog .risk-modal-input:hover { border-color:#bcc8d6; }
        .project-risk-dialog .risk-modal-input:focus { border-color:#c3122e;box-shadow:0 0 0 3px rgba(195,18,46,.08); }
        .project-risk-dialog .risk-modal-input[aria-invalid="true"] { border-color:#e28394; }
        .project-risk-dialog .risk-modal-helper { margin:7px 0 0;color:#94a3b8;font-size:11px;line-height:1.6; }
        .project-risk-dialog .risk-modal-error { display:block;margin-top:5px;color:#be123c;font-size:11px;line-height:1.5; }
        .project-risk-dialog .risk-modal-footer { display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 24px;border-top:1px solid #edf0f4;background:#fbfcfd;flex-shrink:0; }
        .project-risk-dialog .risk-modal-footer p { margin:0;color:#94a3b8;font-size:11px; }
        .project-risk-dialog .risk-modal-actions { display:flex;gap:9px; }
        .project-risk-dialog .risk-modal-button { display:inline-flex;align-items:center;justify-content:center;min-height:40px;padding:0 16px;border:1px solid #dfe5ed;border-radius:8px;background:#fff;color:#475569;font-family:inherit;font-size:12px;font-weight:650;cursor:pointer; }
        .project-risk-dialog .risk-modal-button:hover { background:#f1f5f9; }
        .project-risk-dialog .risk-modal-submit { border-color:#c3122e;background:#c3122e;color:#fff; }
        .project-risk-dialog .risk-modal-submit:hover { border-color:#a81029;background:#a81029; }
        .project-risk-dialog button:focus-visible { outline:2px solid #c3122e;outline-offset:2px; }
        .project-risk-dialog button:disabled { opacity:.6;cursor:wait; }
        @media(max-width:560px) { .project-risk-backdrop { padding:12px; } .project-risk-dialog { max-height:calc(100dvh - 24px);border-radius:14px; } .project-risk-dialog .risk-modal-header,.project-risk-dialog .risk-modal-body { padding:18px; } .project-risk-dialog .risk-modal-grid { grid-template-columns:minmax(0,1fr); } .project-risk-dialog .risk-modal-footer { padding:14px 18px; } .project-risk-dialog .risk-modal-footer p { display:none; } .project-risk-dialog .risk-modal-actions { width:100%; } .project-risk-dialog .risk-modal-actions > button { flex:1; } }
    </style>
    <div class="project-risk-dialog">
        <header class="risk-modal-header">
            <span class="risk-modal-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M10.3 3.9 2.1 18a2 2 0 001.7 3h16.4a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z"/></svg></span>
            <div class="risk-modal-heading">
                <h2 id="project-risk-create-title">Log Project Risk</h2>
                <p id="project-risk-create-context" class="risk-modal-context">{{ $project->code }} &middot; {{ $project->name }}</p>
            </div>
            <button type="button" wire:click="closeCreateRiskModal" class="risk-modal-close" aria-label="Close risk form"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M6 18 18 6"/></svg></button>
        </header>
        <form wire:submit="addRisk" class="risk-modal-form">
            <div class="risk-modal-body">
                <div class="risk-modal-field">
                    <label for="project-risk-title">Risk Title <span class="risk-modal-required">*</span></label>
                    <input id="project-risk-title" x-ref="riskTitle" type="text" wire:model="riskTitle" maxlength="255" placeholder="Give the risk a short, clear title" class="risk-modal-input" required @error('riskTitle') aria-invalid="true" @enderror>
                    @error('riskTitle') <span class="risk-modal-error">{{ $message }}</span> @enderror
                </div>
                <div class="risk-modal-field">
                    <label for="project-risk-wbs">Affected Scope / WBS Task</label>
                    <select id="project-risk-wbs" wire:model="riskWbsItemId" class="risk-modal-input" aria-describedby="project-risk-wbs-help">
                        <option value="">General Project Scope</option>
                        @php
                            $modalUser = auth()->user();
                            $isProjManager = $modalUser && ($modalUser->isSuperAdmin() || $modalUser->isPmoAdmin() || $modalUser->id === 1 || $project->project_manager_id === $modalUser->id);
                            $canViewAllWbs = $project->userCan($modalUser, 'task.view_all');
                            $userWbsItems = ($isProjManager || $canViewAllWbs)
                                ? $project->wbsItems
                                : $project->wbsItems->filter(fn($w) => $w->assigned_user_id === $modalUser?->id);
                        @endphp
                        @foreach($userWbsItems as $wbs)
                            <option value="{{ $wbs->id }}">{{ $wbs->wbs_code ? $wbs->wbs_code.' - ' : '' }}{{ $wbs->title }}</option>
                        @endforeach
                    </select>
                    <p id="project-risk-wbs-help" class="risk-modal-helper">Choose a task or leave this risk at project level.</p>
                    @error('riskWbsItemId') <span class="risk-modal-error">{{ $message }}</span> @enderror
                </div>
                <div class="risk-modal-grid">
                    <div class="risk-modal-field">
                        <label for="project-risk-category">Category</label>
                        <select id="project-risk-category" wire:model="riskCategory" class="risk-modal-input">
                            <option value="Technical">Technical</option><option value="Financial">Financial</option><option value="Schedule">Schedule</option><option value="Resource">Resource</option><option value="External">External Vendor</option>
                        </select>
                    </div>
                    <div class="risk-modal-field">
                        <label for="project-risk-probability">Probability</label>
                        <select id="project-risk-probability" wire:model="riskProbability" class="risk-modal-input"><option value="low">Low (1)</option><option value="medium">Medium (2)</option><option value="high">High (3)</option></select>
                    </div>
                    <div class="risk-modal-field">
                        <label for="project-risk-impact">Impact</label>
                        <select id="project-risk-impact" wire:model="riskImpact" class="risk-modal-input"><option value="low">Low (1)</option><option value="medium">Medium (2)</option><option value="high">High (3)</option><option value="critical">Critical (4)</option></select>
                    </div>
                </div>
                <div class="risk-modal-field">
                    <label for="project-risk-description">Risk Description &amp; Impact Analysis <span class="risk-modal-required">*</span></label>
                    <textarea id="project-risk-description" wire:model="riskDescription" rows="3" placeholder="Describe what could go wrong and its potential impact" class="risk-modal-input" required @error('riskDescription') aria-invalid="true" @enderror></textarea>
                    @error('riskDescription') <span class="risk-modal-error">{{ $message }}</span> @enderror
                </div>
                <div class="risk-modal-field">
                    <label for="project-risk-mitigation">Mitigation Plan <span class="risk-modal-optional">(Optional)</span></label>
                    <textarea id="project-risk-mitigation" wire:model="riskMitigation" rows="2" placeholder="Outline steps to prevent or reduce the impact" class="risk-modal-input risk-modal-mitigation"></textarea>
                    @error('riskMitigation') <span class="risk-modal-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <footer class="risk-modal-footer">
                <p><span class="risk-modal-required">*</span> Required fields</p>
                <div class="risk-modal-actions">
                    <button type="button" wire:click="closeCreateRiskModal" class="risk-modal-button" wire:loading.attr="disabled" wire:target="addRisk">Cancel</button>
                    <button type="submit" class="risk-modal-button risk-modal-submit" wire:loading.attr="disabled" wire:target="addRisk"><span wire:loading.remove wire:target="addRisk">Add Risk to Project Log</span><span wire:loading wire:target="addRisk" style="display:none;">Saving risk...</span></button>
                </div>
            </footer>
        </form>
    </div>
</div>
