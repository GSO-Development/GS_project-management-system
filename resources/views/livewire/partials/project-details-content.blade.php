@php
    $currPm = $project->projectManager;
    $tpl = $project->template;
    $budget = $project->estimated_budget;
    $actualBudget = $project->actual_cost;
    $remainingBudget = $budget !== null && $actualBudget !== null ? (float) $budget - (float) $actualBudget : null;
    $budgetUsage = (float) $budget > 0 && $actualBudget !== null ? round((float) $actualBudget / (float) $budget * 100, 1) : null;
    $assignmentStatus = $project->isPmAccepted() ? 'Accepted' : ($project->isPmRejected() ? 'Declined' : 'Pending acceptance');
    $taskItems = $project->wbsItems->filter(fn ($item) => in_array($item->item_type, [\App\Enums\ItemType::TASK, \App\Enums\ItemType::SUBTASK, \App\Enums\ItemType::MILESTONE], true));
    $canViewDetailsTeam = auth()->user()?->isPmoAdmin() || $project->userCan(auth()->user(), 'team.view');
    $teamGroups = $canViewDetailsTeam
        ? $project->members->reject(fn ($member) => $member->id === $project->project_manager_id)->groupBy(fn ($member) => $member->pivot->role ?? 'member')
        : collect();
    $teamRoleLabels = ['sponsor' => 'Project Sponsor', 'owner' => 'Project Owner', 'steering_committee' => 'Steering Committee', 'member' => 'Team Members'];
@endphp
<div class="project-details-view {{ $detailsInModal ? 'is-modal' : '' }}">
    <style>
        .project-details-view { display:flex;flex-direction:column;flex:1;min-height:0;background:#fff;color:#334155;font-family:'Plus Jakarta Sans',system-ui,sans-serif; }
        .project-details-view:not(.is-modal) { max-height:clamp(360px,calc(100dvh - 200px),780px);overflow:hidden; }
        .project-details-view .details-heading { display:flex;align-items:center;justify-content:space-between;gap:16px;padding:24px 28px;border-bottom:1px solid #edf0f4;flex-shrink:0; }
        .project-details-view .details-heading-copy { display:flex;align-items:center;gap:13px;min-width:0; }
        .project-details-view .details-heading-icon { display:grid;place-items:center;width:42px;height:42px;flex-shrink:0;border:1px solid #f5dce2;border-radius:12px;background:#fff3f6;color:#c3122e; }
        .project-details-view .details-heading-icon svg { width:21px;height:21px; }
        .project-details-view .details-heading h3 { margin:0;color:#0f172a;font-size:18px;font-weight:750;letter-spacing:-.4px; }
        .project-details-view .details-heading p { margin:4px 0 0;color:#64748b;font-size:12px;line-height:1.6;overflow-wrap:anywhere; }
        .project-details-view .details-close { display:grid;place-items:center;width:32px;height:32px;flex-shrink:0;border:1px solid #e2e8f0;border-radius:8px;color:#64748b;background:#fff;cursor:pointer; }
        .project-details-view .details-close:hover { background:#f8fafc;color:#0f172a; }
        .project-details-view .details-close svg { width:15px;height:15px; }
        .project-details-view .details-body { padding:24px 28px;overflow-y:auto;overscroll-behavior:contain;flex:1;min-height:0;background:#fcfcfd;scrollbar-gutter:stable;scrollbar-width:thin;scrollbar-color:#cbd5e1 #f8fafc; }
        .project-details-view .details-body:focus-visible { outline:2px solid #c3122e;outline-offset:-2px; }
        .project-details-view .details-body::-webkit-scrollbar { width:8px; }
        .project-details-view .details-body::-webkit-scrollbar-track { background:#f8fafc; }
        .project-details-view .details-body::-webkit-scrollbar-thumb { background:#cbd5e1;border:2px solid #f8fafc;border-radius:8px; }
        .project-details-view .details-summary { display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin:0 0 22px;padding:0; }
        .project-details-view .details-summary > div { min-width:0;padding:16px 18px;background:#fff;border:1px solid #e7ecf2;border-radius:12px;box-shadow:0 2px 4px rgba(15,23,42,.015); }
        .project-details-view dt { margin:0 0 7px;color:#7a899d;font-size:11px;font-weight:500;line-height:1.5; }
        .project-details-view dd { margin:0;color:#1e293b;font-size:13px;font-weight:650;line-height:1.7;overflow-wrap:anywhere; }
        .project-details-view .details-grid { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));align-items:start;gap:18px; }
        .project-details-view .details-section { min-width:0;padding:22px;background:#fff;border:1px solid #e7ecf2;border-radius:14px;box-shadow:0 2px 4px rgba(15,23,42,.015); }
        .project-details-view .details-section h4 { display:flex;align-items:center;gap:9px;margin:0 0 20px;padding-bottom:14px;border-bottom:1px solid #f0f3f6;color:#172033;font-size:13px;font-weight:750; }
        .project-details-view .details-section h4::before { content:'';display:block;width:3px;height:14px;background:#c3122e;border-radius:3px;flex-shrink:0; }
        .project-details-view .details-fields { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 24px;margin:0; }
        .project-details-view .details-wide { grid-column:1/-1; }
        .project-details-view .details-description { padding:11px 14px;border-left:2px solid #eed9df;border-radius:0 8px 8px 0;background:#fcfafb;color:#526174;font-size:12px;font-weight:400;line-height:1.85;white-space:pre-line; }
        .project-details-view .details-muted { color:#94a3b8;font-weight:400; }
        .project-details-view .details-number { font-family:ui-monospace,SFMono-Regular,Consolas,monospace;font-variant-numeric:tabular-nums;letter-spacing:-.25px; }
        .project-details-view .details-summary .details-number { font-size:16px;font-weight:750; }
        .project-details-view .details-progress { display:block;height:4px;margin-top:8px;border-radius:8px;overflow:hidden;background:#edf1f6; }
        .project-details-view .details-progress::after { content:'';display:block;width:var(--details-progress);height:100%;border-radius:8px;background:#c3122e; }
        .project-details-view .details-status { display:inline-flex;align-items:center;gap:6px;padding:3px 10px;border:1px solid;border-radius:999px;font-size:11px;font-weight:650; }
        .project-details-view .details-dot { width:6px;height:6px;border-radius:50%; }
        .project-details-view .details-financial { background:linear-gradient(145deg,#fff 30%,#fff8fa);border-color:#efdee4; }
        .project-details-view .details-financial .details-number { font-size:15px; }
        .project-details-view .details-over-budget { color:#be123c; }
        .project-details-view .details-team { display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px 24px;margin:0; }
        .project-details-view .details-team ul { display:flex;flex-wrap:wrap;gap:6px;margin:0;padding:0;list-style:none; }
        .project-details-view .details-team li { padding:4px 9px;border:1px solid #e7ecf2;border-radius:7px;background:#f8fafc;color:#475569;font-size:12px;font-weight:550; }
        .project-details-view .details-footer { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding:16px 28px;border-top:1px solid #edf0f4;background:#fff;color:#94a3b8;font-size:11px;line-height:1.6;flex-shrink:0; }
        .project-details-view .details-done { padding:9px 18px;border:0;border-radius:9px;background:#c3122e;color:#fff;font:inherit;font-weight:650;cursor:pointer; }
        .project-details-view.is-modal .details-grid { grid-template-columns:minmax(0,1fr); }
        .project-details-view.is-modal .details-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }
        @media (max-width:960px) { .project-details-view .details-grid { grid-template-columns:minmax(0,1fr); } .project-details-view .details-summary { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media (max-width:560px) { .project-details-view .details-heading,.project-details-view .details-body { padding:18px; } .project-details-view .details-section { padding:18px; } .project-details-view .details-summary { gap:9px; } .project-details-view .details-summary > div { padding:13px; } .project-details-view .details-fields,.project-details-view .details-team { grid-template-columns:minmax(0,1fr); } .project-details-view .details-footer { padding:14px 18px; } }
    </style>

    <header class="details-heading">
        <div class="details-heading-copy">
            <span class="details-heading-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-3M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M8 12h8m-8 4h5"/></svg>
            </span>
            <div>
                <h3 id="{{ $detailsInModal ? 'project-overview-title' : 'project-details-title' }}">Project Details</h3>
                <p>{{ $project->code }} &middot; {{ $project->name }}</p>
            </div>
        </div>
        @if($detailsInModal)
            <button type="button" wire:click="closeProjectDetailsModal" class="details-close" aria-label="Close project details">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="m6 6 12 12M6 18 18 6"/></svg>
            </button>
        @endif
    </header>

    <div class="details-body" tabindex="0" role="region" aria-label="Project details content">
        <div class="details-grid">
            <section class="details-section">
                <h4>Project Information</h4>
                <dl class="details-fields">
                    <div class="details-wide">
                        <dt>Project Description &amp; Scope</dt>
                        <dd class="details-description {{ empty(trim($project->description ?? '')) ? 'details-muted' : '' }}">{{ trim($project->description ?? '') !== '' ? $project->description : 'No project description specified.' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="details-section">
                <h4>Ownership &amp; Assignment</h4>
                <dl class="details-fields">
                    <div><dt>Project Manager</dt><dd>{{ $currPm->name ?? 'Unassigned' }}</dd></div>
                    <div><dt>Assignment Status</dt><dd>{{ $assignmentStatus }}</dd></div>
                    <div><dt>Created By</dt><dd>{{ $project->creator->name ?? 'Not available' }}</dd></div>
                    <div><dt>Created On</dt><dd>{{ $project->created_at?->format('M d, Y') ?? 'Not available' }}</dd></div>
                    @if($project->pm_accepted_at)
                        <div class="details-wide"><dt>Accepted On</dt><dd>{{ $project->pm_accepted_at->format('M d, Y h:i A') }}</dd></div>
                    @endif
                    @if($project->isPmRejected())
                        <div><dt>Declined On</dt><dd>{{ $project->pm_rejected_at?->format('M d, Y h:i A') ?? 'Not recorded' }}</dd></div>
                        <div class="details-wide"><dt>Decline Reason</dt><dd class="details-description">{{ $project->pm_rejection_reason }}</dd></div>
                    @endif
                </dl>
            </section>

            <section class="details-section">
                <h4>Schedule &amp; Delivery</h4>
                <dl class="details-fields">
                    <div><dt>Kick-off Date</dt><dd>{{ $project->start_date?->format('M d, Y') ?? 'Not set' }}</dd></div>
                    <div><dt>Target Completion Deadline</dt><dd>{{ $project->deadline?->format('M d, Y') ?? 'Not set' }}</dd></div>
                    <div><dt>Task Count</dt><dd class="details-number">{{ $taskItems->count() }}</dd></div>
                </dl>
            </section>

            <section class="details-section details-financial">
                <h4>Financial Overview</h4>
                <dl class="details-fields">
                    <div><dt>Budget (LKR)</dt><dd class="details-number">{{ $budget !== null ? 'Rs. '.number_format((float) $budget, 2) : 'Not set' }}</dd></div>
                    <div><dt>Actual Budget (LKR)</dt><dd class="details-number">{{ $actualBudget !== null ? 'Rs. '.number_format((float) $actualBudget, 2) : 'Not set' }}</dd></div>
                    <div><dt>Remaining Budget (LKR)</dt><dd class="details-number {{ $remainingBudget !== null && $remainingBudget < 0 ? 'details-over-budget' : '' }}">{{ $remainingBudget !== null ? 'Rs. '.number_format($remainingBudget, 2) : 'Not set' }}</dd></div>
                    <div><dt>Budget Utilization</dt><dd class="details-number {{ $budgetUsage !== null && $budgetUsage > 100 ? 'details-over-budget' : '' }}">{{ $budgetUsage !== null ? $budgetUsage.'%' : 'Not applicable' }}</dd></div>
                </dl>
            </section>

            <section class="details-section">
                <h4>Delivery Blueprint Template</h4>
                <dl class="details-fields">
                    <div class="details-wide"><dt>Template</dt><dd>{{ $tpl->name ?? 'Blank Slate Canvas (Custom WBS)' }}</dd></div>
                    <div><dt>Breakdown Type</dt><dd>{{ $project->wbs_breakdown_type ? ucwords(str_replace('_', ' ', $project->wbs_breakdown_type)) : 'Not specified' }}</dd></div>
                    <div><dt>WBS Items</dt><dd class="details-number">{{ $project->wbsItems->count() }}</dd></div>
                    @if($tpl)
                        <div><dt>Template Tasks</dt><dd class="details-number">{{ $tpl->tasks->count() }}</dd></div>
                        <div><dt>Time Breakdown</dt><dd>{{ $tpl->divide_by_hours ? 'Hourly Breakdown' : 'Daily Breakdown' }}</dd></div>
                        <div><dt>Work Starts</dt><dd>{{ $tpl->work_start_time ? \Carbon\Carbon::parse($tpl->work_start_time)->format('g:i A') : 'Not set' }}</dd></div>
                        <div><dt>Work Ends</dt><dd>{{ $tpl->work_end_time ? \Carbon\Carbon::parse($tpl->work_end_time)->format('g:i A') : 'Not set' }}</dd></div>
                        <div class="details-wide"><dt>Working Days</dt><dd>{{ !empty($tpl->work_days) ? implode(', ', array_map('ucfirst', $tpl->work_days)) : 'Not set' }}</dd></div>
                        @if(trim($tpl->description ?? '') !== '')
                            <div class="details-wide"><dt>Template Description</dt><dd class="details-description">{{ $tpl->description }}</dd></div>
                        @endif
                    @endif
                </dl>
            </section>

            @if($canViewDetailsTeam)
                <section class="details-section">
                    <h4>Governance &amp; Project Team</h4>
                    <dl class="details-team">
                        <div><dt>Project Manager</dt><dd>{{ $currPm->name ?? 'Unassigned' }}</dd></div>
                        @foreach($teamGroups as $roleCode => $roleMembers)
                            <div>
                                <dt>{{ $teamRoleLabels[$roleCode] ?? ($allRoles[$roleCode]['name'] ?? ucwords(str_replace(['_', '-'], ' ', $roleCode))) }}</dt>
                                <dd><ul>@foreach($roleMembers as $member)<li>{{ $member->name }}</li>@endforeach</ul></dd>
                            </div>
                        @endforeach
                        @if($teamGroups->isEmpty())
                            <div><dt>Other Participants</dt><dd class="details-muted">No additional participants assigned.</dd></div>
                        @endif
                    </dl>
                </section>
            @endif
        </div>
    </div>

    <footer class="details-footer">
        <span>Last updated {{ $project->updated_at?->format('M d, Y h:i A') ?? 'Not available' }}</span>
        @if($detailsInModal)
            <button type="button" wire:click="closeProjectDetailsModal" class="details-done">Done</button>
        @endif
    </footer>
</div>
