<div data-ui-page="risks" class="ui-page ui-page-governance risk-hub-v2">
    <style>
        .app-clean [data-ui-page="risks"].risk-hub-v2 {
            max-width: 1640px;
            margin-inline: auto;
            padding: 6px 0 32px;
            color: #172033;
        }
        .app-clean .risk-hub-v2 .risk-page-header::before { display: none; }
        .risk-hub-v2 .risk-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 22px;
        }
        .app-clean .risk-hub-v2 .risk-page-header h1 {
            margin: 0;
            font-size: 1.55rem !important;
            font-weight: 700 !important;
            line-height: 1.3;
            letter-spacing: -.035em !important;
        }
        .risk-hub-v2 .risk-page-subtitle { margin-top: 6px; color: #667085; font-size: 12px; }
        .risk-hub-v2 .risk-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 36px;
            padding: 8px 13px;
            border: 1px solid #dce2e9;
            border-radius: 7px;
            background: #fff;
            color: #344054;
            font: inherit;
            font-size: 12px;
            font-weight: 600;
            line-height: 18px;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color 150ms ease, border-color 150ms ease;
        }
        .risk-hub-v2 .risk-button:hover { background: #f8fafc; border-color: #b8c2cf; }
        .risk-hub-v2 .risk-button-primary { background: #c3122e; border-color: #c3122e; color: #fff; }
        .risk-hub-v2 .risk-button-primary:hover { background: #a80f26; border-color: #a80f26; }
        .risk-hub-v2 .risk-button svg { width: 15px; height: 15px; }
        .risk-hub-v2 :is(button, a):focus-visible { outline: 2px solid #c3122e; outline-offset: 3px; }
        .risk-hub-v2 .risk-page-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 22px;
        }
        .risk-hub-v2 .risk-kpi-card { min-width: 0; padding: 16px 18px; border: 1px solid #e1e6ed; border-radius: 10px; background: #fff; }
        .risk-hub-v2 .risk-kpi-label { color: #667085; font-size: 10px; font-weight: 600; letter-spacing: .07em; line-height: 16px; text-transform: uppercase; }
        .risk-hub-v2 .risk-kpi-value { margin-top: 8px; color: #101828; font-family: 'Plus Jakarta Sans', system-ui, sans-serif; font-size: 28px; font-weight: 700; font-variant-numeric: tabular-nums; letter-spacing: -.04em; line-height: 34px; }
        .risk-hub-v2 .risk-kpi-context { margin-top: 5px; color: #667085; font-size: 11px; line-height: 17px; }
        .risk-hub-v2 .risk-kpi-alert { border-color: #edcbd2; }
        .risk-hub-v2 .risk-kpi-alert .risk-kpi-value { color: #c3122e; }
        .risk-hub-v2 .risk-table-shell { overflow: hidden; border: 1px solid #e1e6ed; border-radius: 10px; background: #fff; }
        .risk-hub-v2 .risk-register-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; padding: 18px 20px 14px; }
        .risk-hub-v2 .risk-register-title { display: flex; align-items: center; gap: 9px; }
        .risk-hub-v2 .risk-register-title h2 { font-size: 14px !important; font-weight: 650; line-height: 22px; letter-spacing: -.015em; }
        .risk-hub-v2 .risk-register-count { min-width: 24px; padding: 1px 7px; border: 1px solid #e4e7ec; border-radius: 5px; background: #f9fafb; color: #475467; text-align: center; font-size: 10px; font-weight: 600; line-height: 18px; }
        .risk-hub-v2 .risk-register-tools { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .risk-hub-v2 .risk-search { position: relative; width: 260px; }
        .risk-hub-v2 .risk-search > svg { position: absolute; left: 11px; top: 11px; width: 15px; height: 15px; color: #98a2b3; pointer-events: none; }
        .risk-hub-v2 .risk-search input { width: 100%; height: 36px; padding: 8px 12px 8px 34px; border: 1px solid #dce2e9; border-radius: 7px; color: #344054; font-size: 12px; }
        .risk-hub-v2 .risk-search input::placeholder { color: #98a2b3; }
        .risk-hub-v2 .risk-filter-button[aria-expanded="true"] { background: #f2f4f7; border-color: #b8c2cf; }
        .risk-hub-v2 .risk-filter-indicator { width: 5px; height: 5px; border-radius: 50%; background: #c3122e; }
        .risk-hub-v2 .risk-reset-button { padding: 4px 0; border: 0; background: transparent; color: #b1102c; font-size: 11px; font-weight: 600; white-space: nowrap; cursor: pointer; }
        .risk-hub-v2 .risk-reset-button:hover { text-decoration: underline; }
        .risk-hub-v2 .risk-status-tabs { display: flex; gap: 20px; overflow-x: auto; padding: 0 20px; border-bottom: 1px solid #e4e7ec; scrollbar-width: thin; }
        .risk-hub-v2 .risk-status-tab { flex: 0 0 auto; padding: 10px 0 11px; border: 0; border-bottom: 2px solid transparent; background: transparent; color: #667085; font-size: 11px; font-weight: 500; line-height: 18px; cursor: pointer; }
        .risk-hub-v2 .risk-status-tab:hover { color: #172033; }
        .risk-hub-v2 .risk-status-tab[aria-pressed="true"] { border-bottom-color: #c3122e; color: #b1102c; font-weight: 600; }
        .risk-hub-v2 .risk-page-filter { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; padding: 16px 20px; border-bottom: 1px solid #e4e7ec; background: #fafbfc; }
        .risk-hub-v2 .risk-filter-field label { display: block; margin-bottom: 6px; color: #475467; font-size: 11px; font-weight: 600; }
        .risk-hub-v2 .risk-filter-field select { width: 100%; min-height: 36px; padding: 7px 30px 7px 10px; border: 1px solid #dce2e9; border-radius: 7px; color: #344054; font-size: 12px; }
        .risk-hub-v2 .risk-table-scroll { overflow-x: auto; }
        .risk-hub-v2 .risk-register-table { width: 100%; min-width: 1040px; border-collapse: separate; border-spacing: 0; text-align: left; }
        .app-clean .risk-hub-v2 .risk-register-table thead th { padding: 11px 14px !important; border-bottom: 1px solid #e4e7ec; background: #f8fafc !important; color: #667085 !important; font-size: 10px !important; font-weight: 600 !important; letter-spacing: .04em !important; text-transform: uppercase; white-space: nowrap; }
        .risk-hub-v2 .risk-register-table th:first-child, .risk-hub-v2 .risk-register-table td:first-child { padding-left: 20px !important; }
        .risk-hub-v2 .risk-register-table th:last-child, .risk-hub-v2 .risk-register-table td:last-child { padding-right: 20px !important; }
        .risk-hub-v2 .risk-register-table tbody td { padding: 14px; border-bottom: 1px solid #edf0f4; vertical-align: middle; color: #344054; font-size: 11px; line-height: 18px; }
        .risk-hub-v2 .risk-register-table tbody tr:last-child td { border-bottom: 0; }
        .risk-hub-v2 .risk-register-table tbody tr:hover > td { background: #fafbfc; }
        .risk-hub-v2 .risk-table-project, .risk-hub-v2 .risk-table-title, .risk-hub-v2 .risk-table-owner { max-width: 230px; color: #344054; font-weight: 600; white-space: normal; overflow-wrap: anywhere; }
        .risk-hub-v2 .risk-table-subtext { max-width: 250px; margin-top: 3px; overflow: hidden; color: #667085; font-size: 10px; line-height: 16px; text-overflow: ellipsis; white-space: nowrap; }
        .risk-hub-v2 .risk-table-category { display: inline-flex; padding: 2px 7px; border: 1px solid #e4e7ec; border-radius: 5px; background: #f9fafb; color: #475467; font-size: 10px; font-weight: 500; line-height: 18px; white-space: nowrap; }
        .risk-hub-v2 .risk-table-task-category { background: #fffaeb; border-color: #fedf89; color: #93370d; }
        .risk-hub-v2 .risk-table-factors { display: flex; gap: 14px; }
        .risk-hub-v2 .risk-table-factors > span { display: flex; flex-direction: column; min-width: 48px; gap: 2px; font-size: 10px; }
        .risk-hub-v2 .risk-table-factors small { color: #667085; font-size: 8px; letter-spacing: .04em; }
        .risk-hub-v2 .risk-exposure { display: inline-flex; align-items: center; gap: 5px; padding: 2px 7px; border: 1px solid; border-radius: 5px; font-size: 10px; font-weight: 500; line-height: 18px; white-space: nowrap; }
        .risk-hub-v2 .risk-exposure i { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .risk-hub-v2 .risk-exposure b { margin-left: 3px; font-size: 9px; font-weight: 600; }
        .risk-hub-v2 .risk-exposure-critical { background: #fff1f3; border-color: #fecdd3; color: #b1102c; }
        .risk-hub-v2 .risk-exposure-high { background: #fff6ed; border-color: #fed7aa; color: #9a3412; }
        .risk-hub-v2 .risk-exposure-medium { background: #fffaeb; border-color: #fedf89; color: #93370d; }
        .risk-hub-v2 .risk-exposure-low { background: #ecfdf3; border-color: #abefc6; color: #067647; }
        .risk-hub-v2 .risk-table-status, .risk-hub-v2 .risk-status-static { display: inline-flex; align-items: center; min-height: 30px; padding: 4px 8px; border: 1px solid #dce2e9; border-radius: 6px; background: #fff; color: #344054; font-size: 10px; line-height: 18px; white-space: nowrap; }
        .risk-hub-v2 select.risk-table-status { min-width: 106px; padding-right: 25px; }
        .risk-hub-v2 .risk-table-actions { display: flex; justify-content: flex-end; gap: 3px; }
        .risk-hub-v2 .risk-table-icon-button { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border: 1px solid transparent; border-radius: 6px; background: transparent; color: #667085; cursor: pointer; }
        .risk-hub-v2 .risk-table-icon-button svg { width: 14px; height: 14px; }
        .risk-hub-v2 .risk-table-icon-button:hover { border-color: #e4e7ec; background: #f2f4f7; color: #344054; }
        .risk-hub-v2 .risk-table-delete:hover { border-color: #fecdd3; background: #fff1f3; color: #b1102c; }
        .risk-hub-v2 .risk-table-footer { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; padding: 12px 20px; border-top: 1px solid #e4e7ec; color: #667085; font-size: 10px; line-height: 17px; }
        .risk-hub-v2 .risk-table-footer strong { color: #344054; font-weight: 600; }
        .risk-hub-v2 .risk-register-table:has(.risk-register-empty) { min-width: 0; }
        .risk-hub-v2 .risk-register-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 225px; gap: 8px; padding: 30px 20px; color: #667085; text-align: center; white-space: normal; }
        .risk-hub-v2 .risk-register-empty strong { color: #344054; font-size: 14px; font-weight: 600; }
        .risk-hub-v2 .risk-register-empty p { max-width: 350px; font-size: 12px; }
        .risk-hub-v2 .risk-register-empty .risk-button { margin-top: 8px; }
        .risk-hub-v2 .risk-modal { border-radius: 12px; }
        @media (max-width: 1100px) { .risk-hub-v2 .risk-register-toolbar { align-items: flex-start; } }
        @media (max-width: 800px) {
            .risk-hub-v2 .risk-page-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .risk-hub-v2 .risk-register-table:has(.risk-register-empty) thead { display: none; }
            .risk-hub-v2 .risk-register-tools { flex: 1 1 100%; }
            .risk-hub-v2 .risk-search { flex: 1; width: auto; min-width: 140px; }
        }
        @media (max-width: 600px) {
            .risk-hub-v2 .risk-page-header { align-items: flex-start; flex-direction: column; gap: 14px; margin-bottom: 18px; }
            .risk-hub-v2 .risk-page-kpis { gap: 10px; margin-bottom: 18px; }
            .risk-hub-v2 .risk-kpi-card { padding: 14px; }
            .risk-hub-v2 .risk-register-toolbar { padding: 16px 14px 10px; }
            .risk-hub-v2 .risk-status-tabs { gap: 18px; padding-inline: 14px; }
            .risk-hub-v2 .risk-page-filter { grid-template-columns: 1fr; padding: 14px; }
            .risk-hub-v2 .risk-table-footer { padding: 12px 14px; }
            .risk-hub-v2 .risk-reset-button { flex-basis: 100%; text-align: left; }
        }
        @media (prefers-reduced-motion: reduce) { .risk-hub-v2 .risk-button { transition: none; } }
    </style>

    <header class="risk-page-header">
        <div>
            <h1>Project Risks</h1>
            <p class="risk-page-subtitle">Monitor exposure, assign ownership, and track mitigation across your projects.</p>
        </div>
        <button wire:click="openAddRiskModal()" type="button" class="risk-button risk-button-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
            Log project risk
        </button>
    </header>

    <dl class="risk-page-kpis" aria-label="Risk overview">
        <div class="risk-kpi-card">
            <dt class="risk-kpi-label">Total risks</dt>
            <dd class="risk-kpi-value">{{ $totalRisksCount }}</dd>
            <dd class="risk-kpi-context">Project and task risks</dd>
        </div>
        <div class="risk-kpi-card">
            <dt class="risk-kpi-label">Active risks</dt>
            <dd class="risk-kpi-value">{{ $openRisksCount }}</dd>
            <dd class="risk-kpi-context">Open or under monitoring</dd>
        </div>
        <div class="risk-kpi-card {{ $criticalHighRiskCount > 0 ? 'risk-kpi-alert' : '' }}">
            <dt class="risk-kpi-label">High-impact risks</dt>
            <dd class="risk-kpi-value">{{ $criticalHighRiskCount }}</dd>
            <dd class="risk-kpi-context">High or critical impact / priority</dd>
        </div>
        <div class="risk-kpi-card">
            <dt class="risk-kpi-label">Resolved risks</dt>
            <dd class="risk-kpi-value">{{ $totalRisksCount - $openRisksCount }}</dd>
            <dd class="risk-kpi-context">Mitigated or closed</dd>
        </div>
    </dl>

    <section class="risk-table-shell" aria-labelledby="risk-register-heading">
        <div class="risk-register-toolbar">
            <div class="risk-register-title">
                <h2 id="risk-register-heading">Risk register</h2>
                <span class="risk-register-count">{{ $risks->count() + $taskRisks->count() }}</span>
            </div>
            <div class="risk-register-tools">
                <div class="risk-search">
                    <label for="risk-register-search" class="sr-only">Search risks</label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path stroke-linecap="round" d="m16 16 4.5 4.5"/></svg>
                    <input id="risk-register-search" type="search" wire:model.live.debounce.300ms="searchQuery" placeholder="Search risks or tasks...">
                </div>
                <button wire:click="toggleFilters" type="button" class="risk-button risk-filter-button" aria-expanded="{{ $showFilters ? 'true' : 'false' }}" aria-controls="risk-register-filters">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M7 12h10M10 17h4"/></svg>
                    Filters
                    @if($hasActiveFilters)<span class="risk-filter-indicator" aria-label="Filters applied"></span>@endif
                </button>
                @if($hasActiveFilters)
                    <button wire:click="resetAllFilters" type="button" class="risk-reset-button">Clear filters</button>
                @endif
            </div>
        </div>
        <div class="risk-status-tabs" role="group" aria-label="Filter risks by status">
            @foreach(['all' => 'All risks', 'open' => 'Open', 'monitoring' => 'Monitoring', 'mitigated' => 'Mitigated', 'closed' => 'Closed'] as $statusValue => $statusLabel)
                <button wire:key="risk-status-{{ $statusValue }}" wire:click="$set('selectedStatus', '{{ $statusValue }}')" type="button" class="risk-status-tab" aria-pressed="{{ $selectedStatus === $statusValue ? 'true' : 'false' }}">{{ $statusLabel }}</button>
            @endforeach
        </div>

        @if($showFilters)
            <div id="risk-register-filters" class="risk-page-filter">
                <div class="risk-filter-field">
                    <label for="risk-project-filter">Project</label>
                    <select id="risk-project-filter" wire:model.live="selectedProjectId">
                        <option value="">All accessible projects</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="risk-filter-field">
                    <label for="risk-category-filter">Category</label>
                    <select id="risk-category-filter" wire:model.live="selectedCategory">
                        <option value="">All categories</option>
                        <option value="technical">Technical</option>
                        <option value="financial">Financial</option>
                        <option value="schedule">Schedule</option>
                        <option value="resource">Resource</option>
                        <option value="operational">Operational</option>
                        <option value="external_vendor">External Vendor</option>
                    </select>
                </div>
                <div class="risk-filter-field">
                    <label for="risk-status-filter">Status</label>
                    <select id="risk-status-filter" wire:model.live="selectedStatus">
                        <option value="all">All statuses</option>
                        <option value="open">Open</option>
                        <option value="monitoring">Monitoring</option>
                        <option value="mitigated">Mitigated</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
        @endif

        <div class="risk-table-scroll" tabindex="0" role="region" aria-label="Risk register table">
            <table class="risk-register-table">
                <caption class="sr-only">Project and task risks with exposure, status, ownership, and actions.</caption>
                <thead>
                    <tr>
                        <th scope="col">Project</th>
                        <th scope="col">Risk / task</th>
                        <th scope="col">Category</th>
                        <th scope="col">Probability &amp; impact</th>
                        <th scope="col">Exposure</th>
                        <th scope="col">Status</th>
                        <th scope="col">Owner</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                    <tbody>
                        @if($risks->isEmpty() && $taskRisks->isEmpty())
                            <tr>
                                <td colspan="8">
                                    <div class="risk-register-empty">
                                        <strong>{{ $hasActiveFilters ? 'No matching risks' : 'No risks recorded' }}</strong>
                                        <p>{{ $hasActiveFilters ? 'Adjust your search or filters to find the risks you need.' : 'Log a project risk to assign an owner and start tracking its mitigation.' }}</p>
                                        @if($hasActiveFilters)
                                            <button wire:click="resetAllFilters" type="button" class="risk-button">Clear filters</button>
                                        @else
                                            <button wire:click="openAddRiskModal()" type="button" class="risk-button risk-button-primary">Log project risk</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @else
                            @foreach($risks as $r)
                                @php
                                    $score = (int) $r->risk_score;
                                    $threatLabel = $score >= 9 ? 'Critical' : ($score >= 6 ? 'High' : ($score >= 3 ? 'Medium' : 'Low'));
                                    $threatClass = match($threatLabel) {
                                        'Critical' => 'risk-exposure-critical',
                                        'High' => 'risk-exposure-high',
                                        'Medium' => 'risk-exposure-medium',
                                        default => 'risk-exposure-low',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="risk-table-project">{{ $r->project->name ?? 'Project' }}</div>
                                        <div class="risk-table-subtext">{{ $r->project->code ?? 'Project risk' }}</div>
                                    </td>
                                    <td>
                                        <div class="risk-table-title">{{ $r->title }}</div>
                                        @if($r->wbsItem)
                                            <div class="risk-table-subtext">Task: {{ $r->wbsItem->title }} <span>({{ $r->wbsItem->wbs_code }})</span></div>
                                        @elseif($r->description)
                                            <div class="risk-table-subtext risk-table-description">{{ $r->description }}</div>
                                        @endif
                                    </td>
                                    <td><span class="risk-table-category">{{ $r->category ?: 'General' }}</span></td>
                                    <td>
                                        <div class="risk-table-factors">
                                            <span><small>PROB.</small>{{ ucfirst($r->probability) }}</span>
                                            <span><small>IMPACT</small>{{ ucfirst($r->impact) }}</span>
                                        </div>
                                    </td>
                                    <td><span class="risk-exposure {{ $threatClass }}"><i></i>{{ $threatLabel }} <b>{{ $score }}/12</b></span></td>
                                    <td>
                                        <select wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)" class="risk-table-status">
                                            <option value="open" @selected($r->status === 'open')>Open</option>
                                            <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                            <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                            <option value="closed" @selected($r->status === 'closed')>Closed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="risk-table-owner">{{ $r->owner->name ?? 'Unassigned' }}</div>
                                        <div class="risk-table-subtext">{{ $r->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        <div class="risk-table-actions">
                                            <button wire:click="openEditRiskModal({{ $r->id }})" type="button" title="Edit risk" aria-label="Edit risk" class="risk-table-icon-button">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.12 2.12 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/></svg>
                                            </button>
                                            <button wire:click="deleteRisk({{ $r->id }})" wire:confirm="Are you sure you want to remove this risk record?" type="button" title="Delete risk" aria-label="Delete risk" class="risk-table-icon-button risk-table-delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18m-2 0-.9 14H5.9L5 6m4 0V4h6v2m-5 4v6m4-6v6"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach($taskRisks as $taskRisk)
                                @php
                                    $taskPriority = $taskRisk->priority?->value ?? 'medium';
                                    $taskPriorityLabel = ucfirst($taskPriority);
                                    $taskExposureClass = match($taskPriority) {
                                        'critical' => 'risk-exposure-critical',
                                        'high' => 'risk-exposure-high',
                                        'low' => 'risk-exposure-low',
                                        default => 'risk-exposure-medium',
                                    };
                                @endphp
                                <tr class="risk-table-task-row">
                                    <td>
                                        <div class="risk-table-project">{{ $taskRisk->project->name ?? 'Project' }}</div>
                                        <div class="risk-table-subtext">Task risk</div>
                                    </td>
                                    <td>
                                        <div class="risk-table-title">{{ $taskRisk->title }}</div>
                                        <div class="risk-table-subtext">Task <span>{{ $taskRisk->wbs_code }}</span></div>
                                    </td>
                                    <td><span class="risk-table-category risk-table-task-category">Task at risk</span></td>
                                    <td>
                                        <div class="risk-table-factors">
                                            <span><small>PRIORITY</small>{{ $taskPriorityLabel }}</span>
                                            <span><small>TASK STATUS</small>At Risk</span>
                                        </div>
                                    </td>
                                    <td><span class="risk-exposure {{ $taskExposureClass }}"><i></i>At Risk <b>{{ $taskPriorityLabel }}</b></span></td>
                                    <td><span class="risk-status-static">At Risk</span></td>
                                    <td>
                                        <div class="risk-table-owner">{{ $taskRisk->assignedUser->name ?? 'Unassigned' }}</div>
                                        <div class="risk-table-subtext">Updated {{ $taskRisk->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        <div class="risk-table-actions">
                                            <a href="{{ route('projects.show', $taskRisk->project_id) }}" wire:navigate.hover title="Open project" aria-label="Open project" class="risk-table-icon-button">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M14 3h7v7m-1-6L10 14M19 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="risk-table-footer">
                <span>Showing <strong>{{ $risks->count() + $taskRisks->count() }}</strong> risk items</span>
                <span>Project risks and task-level risks</span>
            </div>
    </section>
    <!-- ---------------------------------------------------------------
         3. MODALS (ADD RISK AND EDIT RISK)
         --------------------------------------------------------------- -->

    <!-- 1. ADD RISK MODAL -->
    @if($showAddRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showAddRiskModal', false)"></div>

            <div class="risk-modal relative bg-white rounded-2xl border border-slate-200 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Log Project Risk</h3>
                            <p class="text-xs text-slate-500 font-normal">Record risk threat, probability x impact matrix, and mitigation strategy</p>
                        </div>
                    </div>
                    <button wire:click="$set('showAddRiskModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form id="addRiskForm" wire:submit="addRisk" class="space-y-4">
                        <!-- Project & Scope Section -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">Select Project...</option>
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                    @error('riskProjectId') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="Technical">Technical</option>
                                        <option value="Financial">Financial</option>
                                        <option value="Schedule">Schedule</option>
                                        <option value="Resource">Resource</option>
                                        <option value="Operational">Operational</option>
                                        <option value="External">External Vendor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">General Project Scope</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">{{ $wbs->wbs_code }} - {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Title & Description -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Integration Delay" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Threat Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" placeholder="Detailed breakdown of potential risk factors, root causes, and project impacts..." class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800 leading-relaxed"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Assessment -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                                    $calcLabel = 'Critical Threat';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-50 text-orange-700 border-orange-200 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-50 text-amber-800 border-amber-200 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-semibold text-slate-700">Probability &amp; Impact Matrix Rating</label>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $calcBadge }}">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Mitigation & Contingency Strategy Plans -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Preventative Mitigation Plan</label>
                                <textarea wire:model="riskMitigation" rows="2" placeholder="Proactive steps to prevent risk..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]/10 text-slate-800"></textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Contingency Backup Plan</label>
                                <textarea wire:model="riskContingency" rows="2" placeholder="Action plan if risk materializes..." class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e]/10 text-slate-800"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0 bg-slate-50">
                    <button type="button" wire:click="$set('showAddRiskModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="addRiskForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white shadow-xs hover:shadow transition-all cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #a80f26 100%);">
                        Log Risk
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. EDIT RISK MODAL -->
    @if($showEditRiskModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div class="fixed inset-0 bg-slate-950/50 backdrop-blur-xs transition-opacity" wire:click="$set('showEditRiskModal', false)"></div>

            <div class="risk-modal relative bg-white rounded-2xl border border-slate-200 max-w-2xl w-full shadow-2xl z-10 overflow-hidden my-6 flex flex-col max-h-[90vh]">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0 bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white shadow-xs flex-shrink-0" style="background: linear-gradient(135deg, #c3122e, #8b0d1f);">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-normal">Update probability, impact ratings, owner, and action plans</p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditRiskModal', false)" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Close">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <form id="editRiskForm" wire:submit="updateRisk" class="space-y-4">
                        <!-- Project & Scope Section -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Target Project <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model.live="riskProjectId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        @foreach($projects as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code ?? 'PRJ' }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Category <span class="text-[#c3122e]">*</span></label>
                                    <select wire:model="riskCategory" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="Technical">Technical</option>
                                        <option value="Financial">Financial</option>
                                        <option value="Schedule">Schedule</option>
                                        <option value="Resource">Resource</option>
                                        <option value="Operational">Operational</option>
                                        <option value="External">External Vendor</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Scope Task (Optional)</label>
                                    <select wire:model="riskWbsItemId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        <option value="">General Project Scope</option>
                                        @foreach($modalWbsItems as $wbs)
                                            <option value="{{ $wbs->id }}">{{ $wbs->wbs_code }} - {{ $wbs->title }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Owner</label>
                                    <select wire:model="riskOwnerId" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->getRoleNameAttribute() ?? 'User' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Risk Title & Description -->
                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Risk Title <span class="text-[#c3122e]">*</span></label>
                                <input type="text" wire:model="riskTitle" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800">
                                @error('riskTitle') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Threat Description <span class="text-[#c3122e]">*</span></label>
                                <textarea wire:model="riskDescription" rows="2.5" class="w-full text-xs rounded-xl p-3 border border-slate-200 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 text-slate-800 leading-relaxed"></textarea>
                                @error('riskDescription') <span class="text-[11px] text-rose-500 font-medium block mt-0.5">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Probability & Impact Assessment -->
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-2.5">
                            @php
                                $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
                                $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
                                $probVal = $probScores[$riskProbability] ?? 2;
                                $impVal = $impScores[$riskImpact] ?? 2;
                                $calcScore = $probVal * $impVal;
                                $calcBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                $calcLabel = 'Low Threat';
                                if ($calcScore >= 8) {
                                    $calcBadge = 'bg-rose-50 text-rose-700 border-rose-200 font-bold';
                                    $calcLabel = 'Critical Threat';
                                } elseif ($calcScore >= 6) {
                                    $calcBadge = 'bg-orange-50 text-orange-700 border-orange-200 font-bold';
                                    $calcLabel = 'High Threat';
                                } elseif ($calcScore >= 4) {
                                    $calcBadge = 'bg-amber-50 text-amber-800 border-amber-200 font-bold';
                                    $calcLabel = 'Medium Threat';
                                }
                            @endphp

                            <div class="flex items-center justify-between">
                                <label class="text-xs font-semibold text-slate-700">Probability &amp; Impact Matrix Rating</label>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $calcBadge }}">
                                    Score: {{ $calcScore }} / 12 &bull; {{ $calcLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Probability</span>
                                    <select wire:model.live="riskProbability" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[11px] font-semibold text-slate-500 block mb-1">Impact</span>
                                    <select wire:model.live="riskImpact" class="w-full text-xs font-medium py-2 px-3 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Mitigation & Contingency Strategy Plans -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Preventative Mitigation Plan</label>
                                <textarea wire:model="riskMitigation" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800"></textarea>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Contingency Backup Plan</label>
                                <textarea wire:model="riskContingency" rows="2" class="w-full text-xs p-2.5 rounded-xl border border-slate-200 bg-white focus:border-[#c3122e] text-slate-800"></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0 bg-slate-50">
                    <button type="button" wire:click="$set('showEditRiskModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all">Cancel</button>
                    <button type="submit" form="editRiskForm" class="px-4.5 py-2 rounded-xl text-xs font-semibold text-white shadow-xs hover:shadow transition-all cursor-pointer" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
