@php
    $status = $detailTask->status;
    $progress = max(0, min(100, (int) $detailTask->progress));
    $isCompleted = $status === \App\Enums\WbsStatus::COMPLETED;
    $isOverdue = $detailTask->end_date?->lt(today()) && !in_array($status, [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED], true);
@endphp
<div class="my-task-detail-backdrop" role="dialog" aria-modal="true" aria-labelledby="my-task-detail-title" aria-describedby="my-task-detail-context"
     x-data x-trap.inert.noscroll="true" @keydown.escape.stop.window="$wire.set('showTaskDetailModal', false)" @click.self="$wire.set('showTaskDetailModal', false)">
    <style>
        .my-task-detail-backdrop { position: fixed; inset: 0; z-index: 2147483000; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(15,23,42,.5); backdrop-filter: blur(4px); font-family: 'Inter', system-ui, sans-serif; color: #0f172a; }
        .my-task-detail-panel { width: 100%; max-width: 740px; max-height: calc(100dvh - 40px); display: flex; flex-direction: column; min-height: 0; overflow: hidden; border: 1px solid #e2e8f0; border-radius: 18px; background: #fff; box-shadow: 0 24px 80px rgba(15,23,42,.24); }
        .my-task-detail-header { display: flex; align-items: flex-start; gap: 13px; flex-shrink: 0; padding: 22px 24px; border-bottom: 1px solid #edf0f4; }
        .my-task-detail-icon { display: grid; place-items: center; flex-shrink: 0; width: 42px; height: 42px; border: 1px solid #fbd8e0; border-radius: 12px; background: #fff3f5; color: #c3122e; }
        .my-task-detail-panel .my-task-detail-icon svg { width: 21px; height: 21px; }
        .my-task-detail-heading { flex: 1; min-width: 0; }
        .my-task-detail-panel h2 { margin: 0; font-size: 19px; font-weight: 750; line-height: 1.4; letter-spacing: -.5px; }
        .my-task-detail-context { margin: 5px 0 0; color: #64748b; font-size: 12px; line-height: 1.6; overflow-wrap: anywhere; }
        .my-task-detail-code { font-family: ui-monospace, monospace; font-size: 11px; }
        .my-task-detail-close { display: grid; place-items: center; flex-shrink: 0; width: 32px; height: 32px; padding: 0; border: 1px solid #e8edf3; border-radius: 8px; background: #fff; color: #94a3b8; cursor: pointer; }
        .my-task-detail-close:hover { background: #f8fafc; color: #334155; }
        .my-task-detail-panel .my-task-detail-close svg { width: 16px; height: 16px; }
        .my-task-detail-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overscroll-behavior: contain; padding: 22px 24px 24px; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        .my-task-detail-body > * + * { margin-top: 20px; }
        .my-task-detail-meta { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; color: #64748b; font-size: 11px; }
        .my-task-detail-meta code { padding: 3px 7px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f8fafc; font-size: 10px; }
        .my-task-detail-panel h3 { margin: 10px 0 0; font-size: 21px; font-weight: 750; line-height: 1.45; letter-spacing: -.5px; overflow-wrap: anywhere; }
        .my-task-detail-progress { padding: 16px; border: 1px solid #e7ecf2; border-radius: 12px; background: #fafbfc; }
        .my-task-detail-status-row { display: flex; gap: 8px 12px; align-items: center; justify-content: space-between; flex-wrap: wrap; }
        .my-task-detail-badges { display: flex; gap: 7px; align-items: center; flex-wrap: wrap; }
        .my-task-detail-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 9px; border: 1px solid; border-radius: 7px; font-size: 11px; font-weight: 650; line-height: 1.5; }
        .my-task-detail-priority { color: #475569; background: #fff; border-color: #e2e8f0; }
        .my-task-detail-overdue { color: #be123c; font-size: 11px; font-weight: 600; }
        .my-task-detail-percent { color: #334155; font-size: 12px; font-weight: 700; white-space: nowrap; }
        .my-task-detail-track { margin-top: 13px; height: 6px; overflow: hidden; border-radius: 99px; background: #e8edf3; }
        .my-task-detail-track > div { height: 100%; border-radius: inherit; background: #c3122e; }
        .my-task-detail-track.is-completed > div { background: #059669; }
        .my-task-detail-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 14px; }
        .my-task-detail-card { min-width: 0; padding: 16px; border: 1px solid #e7ecf2; border-radius: 12px; }
        .my-task-detail-panel h4 { margin: 0 0 12px; color: #334155; font-size: 12px; font-weight: 700; }
        .my-task-detail-card dl { margin: 0; display: grid; gap: 12px; }
        .my-task-detail-card dl > div { display: flex; justify-content: space-between; gap: 10px; font-size: 12px; line-height: 1.6; }
        .my-task-detail-card dt { color: #64748b; }
        .my-task-detail-card dd { margin: 0; color: #1e293b; font-weight: 600; text-align: right; overflow-wrap: anywhere; }
        .my-task-detail-card dd small { display: block; color: #94a3b8; font-size: 10px; font-weight: 400; }
        .my-task-detail-description { margin: 0; color: #475569; font-size: 12px; line-height: 1.8; white-space: pre-line; overflow-wrap: anywhere; }
        .my-task-detail-empty { margin: 0; color: #94a3b8; font-size: 12px; line-height: 1.7; }
        .my-task-detail-subheading { display: flex; gap: 12px; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .my-task-detail-subheading h4 { margin: 0; }
        .my-task-detail-subheading span { color: #64748b; font-size: 11px; }
        .my-task-detail-subtasks { display: grid; gap: 8px; margin: 0; padding: 0; list-style: none; }
        .my-task-detail-subtasks li { display: flex; gap: 10px; align-items: center; padding: 10px 12px; border: 1px solid #e8edf3; border-radius: 9px; background: #fafbfc; font-size: 12px; }
        .my-task-detail-subtasks li > span:nth-child(2) { flex: 1; min-width: 0; overflow-wrap: anywhere; }
        .my-task-detail-subtasks li > small { flex-shrink: 0; color: #64748b; font-size: 10px; }
        .my-task-detail-check { display: grid; place-items: center; width: 18px; height: 18px; flex-shrink: 0; border: 1px solid #cbd5e1; border-radius: 5px; color: #059669; background: #fff; font-weight: 700; }
        .my-task-detail-blockers { padding: 14px 16px; border: 1px solid #fecdd3; border-radius: 10px; background: #fff5f6; }
        .my-task-detail-blockers h4 { margin-bottom: 7px; color: #9f1239; }
        .my-task-detail-blockers p { margin: 6px 0 0; color: #9f1239; font-size: 12px; line-height: 1.7; overflow-wrap: anywhere; }
        .my-task-detail-footer { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-shrink: 0; padding: 16px 24px; border-top: 1px solid #edf0f4; background: #fafbfc; }
        .my-task-detail-workspace { color: #64748b; font-size: 12px; font-weight: 600; text-decoration: none; }
        .my-task-detail-workspace:hover { color: #c3122e; }
        .my-task-detail-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 9px; }
        .my-task-detail-button { display: inline-flex; justify-content: center; align-items: center; gap: 7px; min-height: 39px; padding: 9px 15px; border: 1px solid #dfe5ed; border-radius: 9px; background: #fff; color: #475569; font: 600 12px/1.4 'Inter', system-ui, sans-serif; cursor: pointer; }
        .my-task-detail-button:hover { background: #f1f5f9; }
        .my-task-detail-complete { border-color: #c3122e; background: #c3122e; color: #fff; }
        .my-task-detail-complete:hover { border-color: #a81029; background: #a81029; }
        .my-task-detail-panel .my-task-detail-button svg { width: 16px; height: 16px; }
        .my-task-detail-completed { color: #047857; font-size: 12px; font-weight: 650; }
        .my-task-detail-panel button:disabled { opacity: .55; cursor: wait; }
        .my-task-detail-panel :is(button, a):focus-visible { outline: 3px solid rgba(195,18,46,.25); outline-offset: 3px; }
        @media (max-width: 600px) {
            .my-task-detail-backdrop { padding: 12px; }
            .my-task-detail-panel { max-height: calc(100dvh - 24px); border-radius: 14px; }
            .my-task-detail-header, .my-task-detail-body { padding: 18px; }
            .my-task-detail-grid { grid-template-columns: 1fr; }
            .my-task-detail-panel h3 { font-size: 18px; }
            .my-task-detail-footer { padding: 14px 18px; align-items: stretch; flex-direction: column; gap: 12px; }
            .my-task-detail-actions { justify-content: flex-end; }
        }
    </style>
    <div class="my-task-detail-panel">
        <header class="my-task-detail-header">
            <span class="my-task-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="5" width="14" height="16" rx="2"/><path d="M9 5V3h6v2M9 11h6m-6 4h4"/></svg></span>
            <div class="my-task-detail-heading">
                <h2 id="my-task-detail-title">Task details</h2>
                <p id="my-task-detail-context" class="my-task-detail-context"><span class="my-task-detail-code">{{ $detailTask->project->code ?? 'Project' }}</span> &middot; {{ $detailTask->project->name ?? 'Project' }}</p>
            </div>
            <button type="button" class="my-task-detail-close" wire:click="$set('showTaskDetailModal', false)" aria-label="Close task details"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
        </header>
        <div class="my-task-detail-body" tabindex="0" role="region" aria-label="Task information">
            <section>
                <div class="my-task-detail-meta"><code>WBS {{ $detailTask->wbs_code }}</code><span>{{ $detailTask->project->subsidiary->name ?? 'No subsidiary' }}</span></div>
                <h3>{{ $detailTask->title }}</h3>
            </section>
            <section class="my-task-detail-progress" aria-label="Task progress">
                <div class="my-task-detail-status-row">
                    <div class="my-task-detail-badges">
                        <span class="my-task-detail-badge {{ $status->badgeClass() }}">{{ $status->label() }}</span>
                        <span class="my-task-detail-badge my-task-detail-priority">{{ $detailTask->priority?->label() ?? 'Medium' }} priority</span>
                        @if($isOverdue)<span class="my-task-detail-overdue">Overdue</span>@endif
                    </div>
                    <span class="my-task-detail-percent">{{ $progress }}% complete</span>
                </div>
                <div class="my-task-detail-track {{ $isCompleted ? 'is-completed' : '' }}" role="progressbar" aria-label="Task completion" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $progress }}"><div style="width: {{ $progress }}%"></div></div>
            </section>
            <div class="my-task-detail-grid">
                <section class="my-task-detail-card">
                    <h4>Timeline &amp; schedule</h4>
                    <dl>
                        <div><dt>Start date</dt><dd>{{ $detailTask->start_date?->format('M d, Y') ?? 'Not scheduled' }}@if($detailTask->start_time)<small>{{ \Carbon\Carbon::parse($detailTask->start_time)->format('h:i A') }}</small>@endif</dd></div>
                        <div><dt>Deadline</dt><dd>{{ $detailTask->end_date?->format('M d, Y') ?? 'No deadline' }}@if($detailTask->end_time)<small>{{ \Carbon\Carbon::parse($detailTask->end_time)->format('h:i A') }}</small>@endif</dd></div>
                    </dl>
                </section>
                <section class="my-task-detail-card">
                    <h4>Assignment</h4>
                    <dl><div><dt>Assigned to</dt><dd>{{ $detailTask->assignedUser->name ?? 'Unassigned' }}</dd></div></dl>
                </section>
            </div>
            <section>
                <h4>Description &amp; scope</h4>
                @if($detailTask->description)<p class="my-task-detail-description">{{ $detailTask->description }}</p>@else<p class="my-task-detail-empty">No description has been added for this task.</p>@endif
            </section>
            @if($detailTask->children->isNotEmpty())
                <section>
                    <div class="my-task-detail-subheading"><h4>Subtasks</h4><span>{{ $detailTask->children->where('status', \App\Enums\WbsStatus::COMPLETED)->count() }} of {{ $detailTask->children->count() }} completed</span></div>
                    <ul class="my-task-detail-subtasks">
                        @foreach($detailTask->children as $child)
                            @php $childDone = $child->status === \App\Enums\WbsStatus::COMPLETED; @endphp
                            <li><span class="my-task-detail-check" aria-hidden="true">{{ $childDone ? '✓' : '' }}</span><span>{{ $child->title }}</span><small>{{ $childDone ? 'Completed' : 'Pending' }}</small></li>
                        @endforeach
                    </ul>
                </section>
            @endif
            @if($detailTask->blockers->where('status', 'open')->isNotEmpty())
                <section class="my-task-detail-blockers">
                    <h4>Active blockers</h4>
                    @foreach($detailTask->blockers->where('status', 'open') as $blocker)<p>{{ $blocker->description }} &middot; {{ ucfirst($blocker->severity ?? 'medium') }} impact</p>@endforeach
                </section>
            @endif
        </div>
        <footer class="my-task-detail-footer">
            <a class="my-task-detail-workspace" href="{{ route('projects.show', $detailTask->project_id) }}">Open project workspace &rarr;</a>
            <div class="my-task-detail-actions">
                @if($isCompleted)<span class="my-task-detail-completed" role="status">Task completed</span>@endif
                <button type="button" class="my-task-detail-button" wire:click="$set('showTaskDetailModal', false)">Close</button>
                @if($canCompleteDetailTask)
                    <button type="button" class="my-task-detail-button my-task-detail-complete" wire:click="completeDetailTask" wire:loading.attr="disabled" wire:target="completeDetailTask">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                        <span wire:loading.remove wire:target="completeDetailTask">Mark as complete</span><span wire:loading wire:target="completeDetailTask">Completing...</span>
                    </button>
                @endif
            </div>
        </footer>
    </div>
</div>
