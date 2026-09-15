<div @if($autoRefresh) wire:poll.30s @endif style="font-family: 'Inter', system-ui, sans-serif; --brand: #c3122e; --brand-dark: #9b0e24;">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap');

        .audit-page {
            min-height: 100vh;
        }

        /* Clean white hero header */
        .audit-hero {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        /* Metric cards */
        .metric-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            transition: all 0.15s ease;
            cursor: pointer;
            position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        .metric-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        .metric-card.active-red {
            border-color: #c3122e !important;
            background: #fef2f2 !important;
            box-shadow: 0 0 0 1px #c3122e;
        }
        .metric-card.active-emerald {
            border-color: #10b981 !important;
            background: #ecfdf5 !important;
            box-shadow: 0 0 0 1px #10b981;
        }
        .metric-card.active-purple {
            border-color: #8b5cf6 !important;
            background: #f5f3ff !important;
            box-shadow: 0 0 0 1px #8b5cf6;
        }

        /* Clean icon containers */
        .icon-box-red {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #c3122e;
        }
        .icon-box-emerald {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #059669;
        }
        .icon-box-blue {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #2563eb;
        }
        .icon-box-purple {
            background: #f5f3ff;
            border: 1px solid #ede9fe;
            color: #7c3aed;
        }

        /* Quick tabs */
        .tab-btn {
            padding: 6px 13px;
            border-radius: 9px;
            font-size: 11.5px;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            transition: all 0.15s ease;
            cursor: pointer;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .tab-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #cbd5e1;
        }
        .tab-btn.active-tab {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 1px 3px rgba(15,23,42,0.15);
        }
        .tab-btn.active-tab .tab-count {
            background: rgba(255,255,255,0.2);
            color: #ffffff;
        }

        .tab-count {
            padding: 1px 7px;
            border-radius: 20px;
            font-size: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            background: #e2e8f0;
            color: #475569;
        }

        /* Filter toolbar */
        .filter-toolbar {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        /* Custom select styling */
        .filter-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 9px center;
            background-size: 11px;
            padding-right: 28px !important;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 7px 28px 7px 11px;
            cursor: pointer;
            transition: all 0.15s ease;
            outline: none;
        }
        .filter-select:hover {
            background-color: #fff;
            border-color: #cbd5e1;
        }
        .filter-select:focus {
            background-color: #fff;
            border-color: #c3122e;
            box-shadow: 0 0 0 3px rgba(195,18,46,0.1);
        }

        /* Table styles */
        .audit-table-wrap {
            background: #fff;
            border: 1px solid #e8ecf0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.04);
        }
        .audit-table th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            padding: 13px 16px;
            white-space: nowrap;
        }
        .audit-table td {
            padding: 13px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .audit-table tr:last-child td {
            border-bottom: none;
        }
        .audit-table tbody tr {
            transition: background 0.12s ease;
        }
        .audit-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Actor avatar */
        .actor-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            flex-shrink: 0;
        }

        /* Action badges */
        .badge-create { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .badge-delete { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .badge-update { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
        .badge-approve { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .badge-nudge { background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe; }
        .badge-default { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Module pills */
        .mod-projects { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
        .mod-wbs { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .mod-users { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .mod-roles { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .mod-approvals { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .mod-subsidiaries { background: #faf5ff; color: #6d28d9; border: 1px solid #ddd6fe; }
        .mod-documents { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
        .mod-settings { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        .mod-default { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }

        /* Inspect button */
        .inspect-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11.5px;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .inspect-btn:hover {
            background: #0f172a;
            color: #fff;
            border-color: #0f172a;
            box-shadow: 0 2px 8px rgba(15,23,42,0.25);
        }

        /* Export button */
        .export-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 700;
            background: #c3122e;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(195,18,46,0.2);
        }
        .export-btn:hover {
            background: #9b0e24;
            box-shadow: 0 2px 6px rgba(195,18,46,0.3);
        }
        .export-btn:active { transform: translateY(0); }

        /* Record code chip & links */
        .code-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 10.5px;
            font-weight: 800;
            font-family: 'JetBrains Mono', monospace;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            white-space: nowrap;
        }
        .code-chip-red {
            background: #fff1f2;
            color: #c3122e;
            border: 1px solid #fecdd3;
        }
        .record-link {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .record-link:hover {
            color: #c3122e;
            text-decoration: underline;
        }
        .record-subtext {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .record-id-btn {
            color: #475569;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-size: 11px;
            transition: color 0.12s ease;
        }
        .record-id-btn:hover {
            color: #c3122e;
            text-decoration: underline;
        }

        /* Live sync indicator */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .live-badge.active {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .live-badge.paused {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }
        .live-dot.pulsing {
            background: #10b981;
            animation: livePulse 1.4s ease-in-out infinite;
        }
        .live-dot.stopped { background: #94a3b8; }
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        /* Modal */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15,23,42,0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            animation: fadeIn 0.15s ease;
        }
        .modal-panel {
            background: #fff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 760px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.18s ease;
            overflow: hidden;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px) scale(0.98); } to { opacity: 1; transform: none; } }

        /* Diff table */
        .diff-row-changed td:nth-child(2) { background: #fff7ed; }
        .diff-row-changed td:nth-child(3) { background: #f0fdf4; }

        /* Empty state */
        .empty-state {
            padding: 60px 24px;
            text-align: center;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* IP chip */
        .ip-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10.5px;
            font-weight: 500;
            color: #475569;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 3px 8px;
            border-radius: 5px;
        }

        /* Code chip */
        .code-chip {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            color: #be123c;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            padding: 2px 7px;
            border-radius: 5px;
        }

        /* Reset button */
        .reset-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 11px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 700;
            color: #be123c;
            background: #fff1f2;
            border: 1px solid #fecdd3;
            cursor: pointer;
            transition: all 0.15s;
        }
        .reset-btn:hover { background: #ffe4e6; }

        /* Sort button */
        .sort-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 11px;
            border-radius: 10px;
            font-size: 11.5px;
            font-weight: 700;
            color: #374151;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.15s;
        }
        .sort-btn:hover { background: #fff; border-color: #cbd5e1; }

        /* Hero number counter style */
        .metric-number {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1;
            color: #0f172a;
        }

        /* Timeline mode dot */
        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            border: 2px solid;
            flex-shrink: 0;
        }
        .timeline-line {
            width: 2px;
            flex: 1;
            min-height: 20px;
            background: linear-gradient(180deg, #e2e8f0 0%, transparent 100%);
        }

        /* Record title link */
        .record-link {
            font-weight: 700;
            font-size: 12.5px;
            color: #0f172a;
            text-decoration: none;
            transition: color 0.15s;
        }
        .record-link:hover { color: #c3122e; }

        /* Filter search input */
        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 240px;
        }
        .search-input {
            width: 100%;
            padding: 8px 36px 8px 38px;
            font-size: 12.5px;
            font-weight: 500;
            color: #1e293b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            transition: all 0.15s;
            outline: none;
        }
        .search-input:focus {
            background: #fff;
            border-color: #c3122e;
            box-shadow: 0 0 0 3px rgba(195,18,46,0.08);
        }
        .search-input::placeholder { color: #94a3b8; }

        /* Hover row action quick filters */
        .row-quick-filter {
            opacity: 0;
            transition: opacity 0.1s;
        }
        tr:hover .row-quick-filter { opacity: 1; }

        /* Per page select */
        .perpage-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 7px center;
            background-size: 10px;
            padding: 7px 26px 7px 11px;
            font-size: 12px;
            font-weight: 700;
            color: #374151;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            outline: none;
            width: 72px;
        }
    </style>

    {{-- ══════════════════════════════════════════════════════════
         1.  HERO HEADER (Clean & Minimal)
    ══════════════════════════════════════════════════════════ --}}
    <div class="audit-hero p-5 mb-5">
        <div>
            {{-- Top row --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-3.5">
                    {{-- Shield icon --}}
                    <div class="w-11 h-11 rounded-xl icon-box-red flex items-center justify-center flex-shrink-0">
                        <svg style="width:22px;height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 style="font-size:20px;font-weight:800;color:#0f172a;letter-spacing:-0.02em;line-height:1.2;">
                                Security & Audit Logs
                            </h1>
                            {{-- Live badge --}}
                            <button wire:click="toggleAutoRefresh" type="button"
                                     class="live-badge {{ $autoRefresh ? 'active' : 'paused' }}"
                                     title="{{ $autoRefresh ? 'Auto-refresh on — click to pause' : 'Paused — click to resume' }}">
                                <span class="live-dot {{ $autoRefresh ? 'pulsing' : 'stopped' }}"></span>
                                <span>{{ $autoRefresh ? 'Live' : 'Paused' }}</span>
                            </button>
                        </div>
                        <p style="font-size:12px;color:#64748b;margin-top:2px;font-weight:500;">
                            Immutable audit trail &bull; Real-time compliance &bull; Security governance
                        </p>
                    </div>
                </div>

                {{-- Export button --}}
                <button wire:click="exportCsv" type="button" class="export-btn flex-shrink-0">
                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </button>
            </div>

            {{-- KPI Metric Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Total Events --}}
                <button type="button" wire:click="setCardFilter('all')"
                        class="metric-card p-3.5 text-left {{ ($quickTab === 'all' && $dateFilter === 'all') ? 'active-red' : '' }}">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="w-8 h-8 rounded-lg icon-box-red flex items-center justify-center">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <svg style="width:14px;height:14px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ number_format($totalLogsCount) }}</div>
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:4px;">Total Audit Events</div>
                </button>

                {{-- Today --}}
                <button type="button" wire:click="setCardFilter('today')"
                        class="metric-card p-3.5 text-left {{ $dateFilter === 'today' ? 'active-emerald' : '' }}">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="w-8 h-8 rounded-lg icon-box-emerald flex items-center justify-center">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <svg style="width:14px;height:14px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ number_format($todayLogsCount) }}</div>
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:4px;">Logged Today</div>
                </button>

                {{-- Active Actors --}}
                <div class="metric-card p-3.5">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="w-8 h-8 rounded-lg icon-box-blue flex items-center justify-center">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="metric-number">{{ number_format($activeActorsCount) }}</div>
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:4px;">Active Users</div>
                </div>

                {{-- Security Events --}}
                <button type="button" wire:click="setCardFilter('security')"
                        class="metric-card p-3.5 text-left {{ $quickTab === 'security' ? 'active-purple' : '' }}">
                    <div class="flex items-center justify-between mb-2.5">
                        <div class="w-8 h-8 rounded-lg icon-box-purple flex items-center justify-center">
                            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <svg style="width:14px;height:14px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="metric-number">{{ number_format($securityLogsCount) }}</div>
                    <div style="font-size:11px;color:#64748b;font-weight:600;margin-top:4px;">Security Events</div>
                </button>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════
         2.  FILTER TOOLBAR
    ══════════════════════════════════════════════════════════ --}}
    <div class="filter-toolbar p-3.5 mb-4">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">

            {{-- Search --}}
            <div class="search-input-wrap">
                <div style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;">
                    <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search by action, actor, IP, or record…"
                       class="search-input">
                @if($search)
                    <button wire:click="$set('search', '')"
                            style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;background:none;border:none;padding:0;">
                        <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endif
            </div>

            {{-- Filter controls --}}
            <div class="flex items-center gap-2 flex-wrap">
                <select wire:model.live="actionFilter" class="filter-select">
                    <option value="all">All Actions</option>
                    @foreach($actionsList as $act)
                        <option value="{{ $act }}">{{ ucwords(str_replace('_', ' ', $act)) }}</option>
                    @endforeach
                </select>

                <select wire:model.live="moduleFilter" class="filter-select">
                    <option value="all">All Modules</option>
                    @foreach($modulesList as $mod)
                        <option value="{{ $mod }}">{{ ucwords(str_replace('_', ' ', $mod)) }}</option>
                    @endforeach
                </select>

                <select wire:model.live="dateFilter" class="filter-select">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="custom">Custom Range</option>
                </select>

                <select wire:model.live="userFilter" class="filter-select" style="max-width:150px;">
                    <option value="all">All Users</option>
                    @foreach($usersList as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>

                {{-- Sort toggle --}}
                <button wire:click="toggleSortOrder" type="button" class="sort-btn"
                        title="{{ $sortOrder === 'desc' ? 'Newest first' : 'Oldest first' }}">
                    <svg style="width:13px;height:13px;color:#64748b;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        @if($sortOrder === 'desc')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4 4m0 0l4-4m-4 4V4"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                        @endif
                    </svg>
                    <span class="hidden sm:inline" style="font-size:11.5px;">{{ $sortOrder === 'desc' ? 'Newest' : 'Oldest' }}</span>
                </button>

                {{-- Per page --}}
                <select wire:model.live="perPage" class="perpage-select">
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                @if($search || $moduleFilter !== 'all' || $actionFilter !== 'all' || $userFilter !== 'all' || $dateFilter !== 'all' || $quickTab !== 'all')
                    <button wire:click="resetFilters" type="button" class="reset-btn">
                        <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset
                    </button>
                @endif
            </div>
        </div>

        {{-- Custom date range --}}
        @if($dateFilter === 'custom')
            <div class="flex items-center gap-3 mt-3 pt-3" style="border-top:1px solid #f1f5f9;">
                <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;">Range:</span>
                <input type="date" wire:model.live="startDate"
                       style="padding:6px 10px;font-size:12px;font-weight:600;color:#374151;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:9px;outline:none;">
                <span style="font-size:12px;color:#94a3b8;font-weight:700;">→</span>
                <input type="date" wire:model.live="endDate"
                       style="padding:6px 10px;font-size:12px;font-weight:600;color:#374151;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:9px;outline:none;">
            </div>
        @endif
    </div>


    {{-- ══════════════════════════════════════════════════════════
         3.  AUDIT LOG TABLE
    ══════════════════════════════════════════════════════════ --}}
    <div class="audit-table-wrap">
        <div style="overflow-x:auto;">
            <table class="audit-table w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th style="padding-left:20px;min-width:200px;">User / Actor</th>
                        <th style="min-width:160px;">Action</th>
                        <th style="min-width:120px;">Module</th>
                        <th style="min-width:230px;">Target Record</th>
                        <th style="min-width:110px;">IP Address</th>
                        <th style="min-width:150px;">Timestamp</th>
                        <th style="text-align:right;padding-right:20px;min-width:100px;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        @php
                            $actionName = match($log->action) {
                                'created_project'             => 'Created Project',
                                'updated_project'             => 'Updated Project',
                                'deleted_project'             => 'Deleted Project',
                                'project_deleted'             => 'Deleted Project',
                                'updated_project_schedule'    => 'Updated Schedule',
                                'accepted_project_assignment' => 'Accepted Leadership',
                                'rejected_project_assignment' => 'Declined Leadership',
                                'reassigned_project_leader'   => 'Reassigned Leader',
                                'auto_cascaded_schedule'      => 'Auto-Cascaded',
                                'cascade_rescheduled'         => 'Cascade Rescheduled',
                                'pmo_nudge_dispatched'        => 'PMO Nudge',
                                'pmo_quick_ping_dispatched'   => 'Quick Ping',
                                'pmo_stuck_task_nudge'        => 'Stuck Task Nudge',
                                'created_wbs_item'            => 'Added Task',
                                'updated_wbs_item'            => 'Updated Task',
                                'created_user'                => 'Created User',
                                'updated_user'                => 'Updated User',
                                'provisioned_azure_user'      => 'Azure SSO',
                                'updated_role_permissions'    => 'Updated Permissions',
                                'created_role'                => 'Created Role',
                                'created_subsidiary'          => 'Created Subsidiary',
                                'updated_subsidiary'          => 'Updated Subsidiary',
                                'deleted_subsidiary'          => 'Deleted Subsidiary',
                                'created_status_update'       => 'Status Update',
                                'created_risk'                => 'Logged Risk',
                                'created_blocker'             => 'Reported Blocker',
                                'resolved_blocker'            => 'Resolved Blocker',
                                'approved_request'            => 'Approved Request',
                                'rejected_request'            => 'Rejected Request',
                                'uploaded_document'           => 'Uploaded Doc',
                                'deleted_document'            => 'Deleted Doc',
                                'updated_system_settings'     => 'Updated Settings',
                                default                       => ucwords(str_replace('_', ' ', $log->action))
                            };

                            $badgeClass = match(true) {
                                str_contains($log->action, 'create') || str_contains($log->action, 'accept') || str_contains($log->action, 'provision') || str_contains($log->action, 'upload') => 'badge-create',
                                str_contains($log->action, 'reject') || str_contains($log->action, 'delete') => 'badge-delete',
                                str_contains($log->action, 'update') || str_contains($log->action, 'edit') || str_contains($log->action, 'reassign') || str_contains($log->action, 'cascade') => 'badge-update',
                                str_contains($log->action, 'approve') => 'badge-approve',
                                str_contains($log->action, 'nudge') || str_contains($log->action, 'ping') => 'badge-nudge',
                                default => 'badge-default'
                            };

                            $moduleClass = match($log->module) {
                                'projects'          => 'mod-projects',
                                'wbs', 'wbs_items'  => 'mod-wbs',
                                'users'             => 'mod-users',
                                'roles_permissions' => 'mod-roles',
                                'approvals'         => 'mod-approvals',
                                'subsidiaries'      => 'mod-subsidiaries',
                                'documents'         => 'mod-documents',
                                'settings'          => 'mod-settings',
                                default             => 'mod-default'
                            };

                            $moduleLabel = match($log->module) {
                                'projects'          => 'Projects',
                                'wbs', 'wbs_items'  => 'Tasks & WBS',
                                'users'             => 'Users & Auth',
                                'roles_permissions' => 'Security Roles',
                                'approvals'         => 'Approvals',
                                'subsidiaries'      => 'Subsidiaries',
                                'documents'         => 'Documents',
                                'settings'          => 'Settings',
                                default             => ucwords(str_replace('_', ' ', $log->module))
                            };

                            $rawRecordName = $log->record_type ? class_basename($log->record_type) : 'Record';
                            $recordLabel = match($rawRecordName) {
                                'WbsItem'          => 'WBS Task',
                                'Project'          => 'Project',
                                'User'             => 'User Profile',
                                'Subsidiary'       => 'Subsidiary',
                                'ApprovalRequest'  => 'Approval Request',
                                'SystemSetting'    => 'System Config',
                                'ActivityLog'      => 'Audit Trail',
                                'ProjectTemplate'  => 'Blueprint',
                                'Role'             => 'Security Role',
                                'Document'         => 'Document',
                                'WbsBaseline'      => 'Schedule Baseline',
                                default            => ucwords(str_replace('_', ' ', preg_replace('/(?<!^)[A-Z]/', ' $0', $rawRecordName)))
                            };

                            $rec       = $resolvedRecords[$log->id] ?? null;
                            $recTitle  = $rec['title'] ?? null;
                            $recCode   = $rec['code'] ?? null;
                            $recUrl    = $rec['url'] ?? null;
                            $isDeleted = !empty($rec['is_deleted']);

                            // Compute Actor Role label
                            $actorRole = 'System Worker';
                            if ($log->user) {
                                if ($log->user->isSuperAdmin() || $log->user->hasRole('pmo_admin')) {
                                    $actorRole = 'PMO Admin';
                                } elseif ($log->user->hasRole('project_manager') || $log->user->isProjectManager()) {
                                    $actorRole = 'Project Leader';
                                } elseif ($log->user->roles && $log->user->roles->isNotEmpty()) {
                                    $actorRole = ucwords(str_replace('_', ' ', $log->user->roles->first()->name));
                                } else {
                                    $actorRole = 'User';
                                }
                            }

                            // Avatar initials & colors
                            $avatarColors = [
                                '#c3122e', '#7c3aed', '#1d4ed8', '#0f766e', '#92400e',
                                '#9d174d', '#1e40af', '#065f46', '#78350f', '#5b21b6',
                            ];
                            $colorIdx = $log->user_id ? ($log->user_id % count($avatarColors)) : (count($avatarColors) - 1);
                            $avatarBg = $avatarColors[$colorIdx];
                            $initials = $log->user ? strtoupper(substr($log->user->name, 0, 1)) : '⚙';
                        @endphp
                        <tr>
                            {{-- ACTOR --}}
                            <td style="padding-left:20px;">
                                <div class="flex items-center gap-2.5">
                                    <div class="actor-avatar"
                                         style="background:{{ $log->user ? $avatarBg : '#1e293b' }};">
                                        <span style="color:#fff;">{{ $initials }}</span>
                                    </div>
                                    <div style="min-width:0;">
                                        <div style="font-size:12.5px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">
                                            {{ $log->user->name ?? 'System Automated' }}
                                        </div>
                                        <div style="font-size:11px;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;display:flex;align-items:center;gap:5px;margin-top:1px;">
                                            <span>{{ $log->user->email ?? 'background service' }}</span>
                                        </div>
                                        <div style="margin-top:2px;">
                                            <span style="display:inline-flex;align-items:center;padding:1px 6px;border-radius:4px;font-size:9.5px;font-weight:800;background:{{ $actorRole === 'PMO Admin' ? '#fef2f2' : ($actorRole === 'Project Leader' ? '#eff6ff' : '#f1f5f9') }};color:{{ $actorRole === 'PMO Admin' ? '#c3122e' : ($actorRole === 'Project Leader' ? '#1d4ed8' : '#475569') }};border:1px solid {{ $actorRole === 'PMO Admin' ? '#fee2e2' : ($actorRole === 'Project Leader' ? '#bfdbfe' : '#e2e8f0') }};white-space:nowrap;">
                                                {{ $actorRole }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- ACTION --}}
                            <td>
                                <button wire:click="filterByAction('{{ $log->action }}')"
                                        type="button"
                                        title="Filter by: {{ $actionName }}"
                                        class="{{ $badgeClass }}"
                                        style="display:inline-flex;align-items:center;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;white-space:nowrap;transition:all 0.12s;">
                                    {{ $actionName }}
                                </button>
                            </td>

                            {{-- MODULE --}}
                            <td>
                                <span class="{{ $moduleClass }}"
                                      style="display:inline-flex;align-items:center;padding:3px 8px;border-radius:5px;font-size:10.5px;font-weight:700;white-space:nowrap;">
                                    {{ $moduleLabel }}
                                </span>
                            </td>

                            {{-- TARGET RECORD (Clean & Structured) --}}
                            <td style="padding-top:12px;padding-bottom:12px;">
                                <div style="min-width:0;max-width:290px;display:flex;flex-direction:column;gap:4px;">
                                    {{-- Primary Title / Name --}}
                                    <div style="display:flex;align-items:center;gap:6px;min-width:0;">
                                        @if($isDeleted && !$recUrl)
                                            <span style="width:6px;height:6px;border-radius:50%;background:#ef4444;flex-shrink:0;" title="Record deleted from database"></span>
                                        @endif
                                        @if($recTitle)
                                            @if($recUrl)
                                                <a href="{{ $recUrl }}" class="record-link" title="{{ $recTitle }}"
                                                   style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;max-width:260px;font-size:12.5px;font-weight:700;color:#0f172a;">
                                                    {{ $recTitle }}
                                                </a>
                                            @else
                                                <span style="font-size:12.5px;font-weight:700;color:{{ $isDeleted ? '#94a3b8' : '#0f172a' }};overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;max-width:260px;{{ $isDeleted ? 'text-decoration:line-through;' : '' }}"
                                                      title="{{ $recTitle }}">
                                                    {{ $recTitle }}
                                                </span>
                                            @endif
                                        @else
                                            <span style="font-size:12.5px;font-weight:700;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;max-width:260px;">
                                                {{ $recordLabel === 'System Config' || $log->module === 'settings' ? 'Global System Settings' : ($recordLabel . ($log->record_id ? ' #' . $log->record_id : '')) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Secondary Metadata Badges & ID --}}
                                    <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;line-height:1.2;">
                                        @if($recCode)
                                            <span class="code-chip {{ str_contains($recCode, 'PRJ') ? 'code-chip-red' : '' }}">{{ $recCode }}</span>
                                        @endif
                                        <span style="display:inline-flex;align-items:center;padding:2px 7px;border-radius:5px;font-size:10px;font-weight:700;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;white-space:nowrap;">
                                            {{ $recordLabel }}
                                        </span>
                                        @if($log->record_id && $rawRecordName !== 'SystemSetting')
                                            <button wire:click="filterByRecord('{{ $log->record_id }}')"
                                                    title="Filter record #{{ $log->record_id }}"
                                                    class="record-id-btn"
                                                    style="font-size:10px;padding:1px 5px;border-radius:4px;background:#f8fafc;border:1px solid #e2e8f0;">
                                                #{{ $log->record_id }}
                                            </button>
                                        @endif
                                        @if($isDeleted)
                                            <span style="font-size:9px;font-weight:800;color:#dc2626;background:#fef2f2;border:1px solid #fecdd3;padding:1px 5px;border-radius:4px;text-transform:uppercase;letter-spacing:0.04em;">Deleted</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- IP --}}
                            <td>
                                <span class="ip-chip">{{ $log->ip_address ?: '127.0.0.1' }}</span>
                            </td>

                            {{-- TIMESTAMP --}}
                            <td>
                                <div style="font-size:12px;font-weight:700;color:#1e293b;">
                                    {{ $log->created_at->format('Y-m-d') }}
                                </div>
                                <div style="font-size:10.5px;color:#94a3b8;margin-top:2px;">
                                    {{ $log->created_at->format('H:i:s') }} &bull; {{ $log->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- INSPECT --}}
                            <td style="text-align:right;padding-right:20px;">
                                <button wire:click="viewDetails({{ $log->id }})"
                                        type="button"
                                        class="inspect-btn">
                                    <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Inspect
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div style="width:56px;height:56px;border-radius:18px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                        <svg style="width:26px;height:26px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div style="font-size:14px;font-weight:800;color:#374151;">No audit logs found</div>
                                    <p style="font-size:12px;color:#94a3b8;margin-top:5px;">No records matched your filters.</p>
                                    <button wire:click="resetFilters" type="button"
                                            style="margin-top:12px;font-size:12px;font-weight:700;color:#c3122e;cursor:pointer;background:none;border:none;text-decoration:underline;">
                                        Clear all filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
            <div style="padding:14px 20px;border-top:1px solid #f1f5f9;background:#fafafa;">
                {{ $logs->links() }}
            </div>
        @endif
    </div>


    {{-- ══════════════════════════════════════════════════════════
         4.  AUDIT INSPECTOR MODAL
    ══════════════════════════════════════════════════════════ --}}
    @if($showDetailModal && $selectedLog)
        <div class="modal-backdrop" wire:click.self="closeDetailModal">
            <div class="modal-panel">

                {{-- Modal Header --}}
                <div style="padding:20px 24px 16px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:42px;height:42px;border-radius:13px;background:#fff1f2;border:1px solid #fecdd3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg style="width:20px;height:20px;color:#be123c;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <h3 style="font-size:16px;font-weight:900;color:#0f172a;letter-spacing:-0.02em;">Audit Inspector</h3>
                                <span style="padding:2px 8px;border-radius:6px;background:#f1f5f9;color:#475569;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;">#{{ $selectedLog->id }}</span>
                            </div>
                            <span style="font-size:11px;color:#94a3b8;font-family:'JetBrains Mono',monospace;">
                                {{ $selectedLog->created_at->format('M d, Y · h:i:s A') }}
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeDetailModal" type="button"
                            style="width:32px;height:32px;border-radius:10px;background:#f1f5f9;color:#64748b;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;font-size:14px;font-weight:800;transition:all 0.15s;"
                            onmouseover="this.style.background='#e2e8f0';this.style.color='#0f172a';"
                            onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b';">✕</button>
                </div>

                {{-- Modal Body --}}
                <div style="padding:20px 24px;overflow-y:auto;flex:1;space-y:16px;">

                    {{-- Metadata grid --}}
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:14px;padding:16px;margin-bottom:16px;">
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Actor</span>
                            <span style="font-size:12.5px;font-weight:700;color:#0f172a;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $selectedLog->user->name ?? 'System Automated' }}</span>
                            <span style="font-size:10.5px;color:#64748b;font-family:'JetBrains Mono',monospace;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $selectedLog->user->email ?? 'system@nexus' }}</span>
                        </div>
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Action</span>
                            <span style="font-size:11.5px;font-family:'JetBrains Mono',monospace;font-weight:700;color:#be123c;">{{ $selectedLog->action }}</span>
                        </div>
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Module</span>
                            <span style="font-size:12.5px;font-weight:700;color:#1e293b;">{{ ucwords(str_replace('_', ' ', $selectedLog->module)) }}</span>
                        </div>
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Record</span>
                            <span style="font-size:12px;font-weight:700;color:#0f172a;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                @if(!empty($selectedLogRecord['title']))
                                    {{ $selectedLogRecord['title'] }}
                                @else
                                    {{ $selectedLog->module === 'settings' ? 'System Config' : 'System Record' }}
                                @endif
                            </span>
                            <div style="display:flex;align-items:center;gap:5px;margin-top:2px;">
                                <span style="font-size:10.5px;color:#94a3b8;">{{ class_basename($selectedLog->record_type) }} {{ $selectedLog->record_id ? '#'.$selectedLog->record_id : '' }}</span>
                                @if(!empty($selectedLogRecord['code']))
                                    <span class="code-chip">{{ $selectedLogRecord['code'] }}</span>
                                @endif
                                @if(!empty($selectedLogRecord['url']))
                                    <a href="{{ $selectedLogRecord['url'] }}" target="_blank" style="color:#be123c;display:inline-flex;align-items:center;">
                                        <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Client IP</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:700;color:#374151;">{{ $selectedLog->ip_address ?: '127.0.0.1' }}</span>
                        </div>
                        <div>
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:3px;">Occurred</span>
                            <span style="font-size:12px;font-weight:700;color:#059669;">{{ $selectedLog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Quick filter shortcuts --}}
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
                        @if($selectedLog->user_id)
                            <button wire:click="filterByUser({{ $selectedLog->user_id }})" type="button"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;font-size:11.5px;font-weight:700;color:#475569;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;transition:all 0.15s;">
                                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Filter by User
                            </button>
                        @endif
                        <button wire:click="filterByAction('{{ $selectedLog->action }}')" type="button"
                                style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;font-size:11.5px;font-weight:700;color:#475569;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;transition:all 0.15s;">
                            <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            Filter by Action
                        </button>
                        @if($selectedLog->record_id)
                            <button wire:click="filterByRecord('{{ $selectedLog->record_id }}')" type="button"
                                    style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;font-size:11.5px;font-weight:700;color:#475569;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;transition:all 0.15s;">
                                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                                Record #{{ $selectedLog->record_id }}
                            </button>
                        @endif
                    </div>

                    {{-- User Agent --}}
                    @if($selectedLog->user_agent)
                        <div style="padding:11px 14px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:11px;margin-bottom:16px;">
                            <span style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:4px;">User Agent</span>
                            <span style="font-size:10.5px;font-family:'JetBrains Mono',monospace;color:#64748b;word-break:break-all;line-height:1.5;display:block;">{{ $selectedLog->user_agent }}</span>
                        </div>
                    @endif

                    {{-- Payload diff --}}
                    @php
                        $hasPayload = $selectedLog->previous_values || $selectedLog->new_values;
                        $allKeys = array_unique(array_merge(
                            array_keys($selectedLog->previous_values ?? []),
                            array_keys($selectedLog->new_values ?? [])
                        ));
                    @endphp

                    @if($hasPayload)
                        <div>
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                <span style="font-size:12px;font-weight:800;color:#1e293b;text-transform:uppercase;letter-spacing:0.07em;">Payload Changes</span>
                                <div style="display:flex;align-items:center;background:#f1f5f9;border-radius:8px;padding:3px;">
                                    <button wire:click="setDiffViewMode('visual')" type="button"
                                            style="padding:4px 12px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all 0.12s;{{ $diffViewMode === 'visual' ? 'background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'background:transparent;color:#64748b;' }}">
                                        Visual Diff
                                    </button>
                                    <button wire:click="setDiffViewMode('json')" type="button"
                                            style="padding:4px 12px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;border:none;transition:all 0.12s;{{ $diffViewMode === 'json' ? 'background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'background:transparent;color:#64748b;' }}">
                                        Raw JSON
                                    </button>
                                </div>
                            </div>

                            @if($diffViewMode === 'visual')
                                <div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                                    <table style="width:100%;border-collapse:collapse;font-size:11.5px;">
                                        <thead>
                                            <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                                                <th style="padding:10px 14px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.07em;color:#64748b;text-align:left;width:30%;">Field</th>
                                                <th style="padding:10px 14px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.07em;color:#ef4444;text-align:left;width:35%;">Before</th>
                                                <th style="padding:10px 14px;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:0.07em;color:#059669;text-align:left;width:35%;">After</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allKeys as $key)
                                                @php
                                                    $oldVal = $selectedLog->previous_values[$key] ?? null;
                                                    $newVal = $selectedLog->new_values[$key] ?? null;
                                                    $changed = $oldVal !== $newVal;
                                                @endphp
                                                <tr style="border-bottom:1px solid #f8fafc;{{ $changed ? '' : 'opacity:0.6;' }}">
                                                    <td style="padding:10px 14px;font-weight:700;color:#374151;font-family:'JetBrains Mono',monospace;font-size:10.5px;vertical-align:top;">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                        <span style="display:block;font-size:9.5px;color:#94a3b8;font-weight:500;">{{ $key }}</span>
                                                    </td>
                                                    <td style="padding:10px 14px;vertical-align:top;">
                                                        @if($oldVal !== null)
                                                            <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:#be123c;background:#fff1f2;padding:5px 8px;border-radius:7px;border:1px solid #fecdd3;word-break:break-all;line-height:1.5;">
                                                                {{ is_array($oldVal) ? json_encode($oldVal, JSON_UNESCAPED_SLASHES) : (string)$oldVal }}
                                                            </div>
                                                        @else
                                                            <span style="color:#cbd5e1;font-size:12px;">—</span>
                                                        @endif
                                                    </td>
                                                    <td style="padding:10px 14px;vertical-align:top;">
                                                        @if($newVal !== null)
                                                            <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:#059669;background:#f0fdf4;padding:5px 8px;border-radius:7px;border:1px solid #bbf7d0;word-break:break-all;line-height:1.5;">
                                                                {{ is_array($newVal) ? json_encode($newVal, JSON_UNESCAPED_SLASHES) : (string)$newVal }}
                                                            </div>
                                                        @else
                                                            <span style="color:#cbd5e1;font-size:12px;">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                    <div>
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                                            <span style="font-size:10.5px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:0.07em;">Previous</span>
                                            <span style="font-size:9.5px;font-weight:700;color:#be123c;background:#fff1f2;border:1px solid #fecdd3;padding:1px 7px;border-radius:4px;">BEFORE</span>
                                        </div>
                                        <pre style="padding:12px;background:#0f172a;color:#fca5a5;border-radius:11px;font-size:10.5px;font-family:'JetBrains Mono',monospace;overflow-x:auto;border:1px solid #1e293b;max-height:220px;line-height:1.6;">{{ $selectedLog->previous_values ? json_encode($selectedLog->previous_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '// No previous state' }}</pre>
                                    </div>
                                    <div>
                                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                                            <span style="font-size:10.5px;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:0.07em;">Applied</span>
                                            <span style="font-size:9.5px;font-weight:700;color:#059669;background:#f0fdf4;border:1px solid #bbf7d0;padding:1px 7px;border-radius:4px;">AFTER</span>
                                        </div>
                                        <pre style="padding:12px;background:#0f172a;color:#86efac;border-radius:11px;font-size:10.5px;font-family:'JetBrains Mono',monospace;overflow-x:auto;border:1px solid #1e293b;max-height:220px;line-height:1.6;">{{ $selectedLog->new_values ? json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '// No new state' }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div style="padding:20px;background:#f8fafc;border:1px solid #f1f5f9;border-radius:12px;text-align:center;font-size:12px;color:#94a3b8;font-weight:500;">
                            No payload changes recorded for this audit event.
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div style="padding:14px 24px;border-top:1px solid #f1f5f9;background:#fafafa;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <svg style="width:12px;height:12px;color:#94a3b8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span style="font-size:10.5px;color:#94a3b8;font-family:'JetBrains Mono',monospace;">Immutable audit record verified</span>
                    </div>
                    <button wire:click="closeDetailModal" type="button"
                            style="padding:8px 20px;border-radius:10px;font-size:12px;font-weight:700;color:#374151;background:#fff;border:1px solid #e2e8f0;cursor:pointer;transition:all 0.15s;box-shadow:0 1px 2px rgba(0,0,0,0.06);"
                            onmouseover="this.style.background='#f1f5f9';" onmouseout="this.style.background='#fff';">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
