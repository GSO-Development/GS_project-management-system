<div data-ui-page="roles" class="ui-page ui-page-admin space-y-6 sm:space-y-7 pb-12" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
    <style>
        .rpm-drawer-overlay {
            position: fixed !important;
            inset: 0 !important;
            z-index: 2147483640 !important;
            overflow: hidden !important;
            display: flex !important;
            justify-content: flex-end !important;
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif !important;
        }
        .rpm-drawer-backdrop,
        .rpm-modal-backdrop {
            position: fixed !important;
            inset: 0 !important;
            background: rgba(15, 23, 42, 0.55) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
        }
        .rpm-drawer-container {
            position: relative !important;
            z-index: 10 !important;
            width: 100% !important;
            max-width: 42rem !important;
            height: 100dvh !important;
            max-height: 100dvh !important;
            display: flex !important;
            padding-left: 2rem !important;
            pointer-events: none !important;
        }
        @media (max-width: 640px) {
            .rpm-drawer-container {
                padding-left: 0 !important;
            }
        }
        .rpm-drawer-panel {
            width: 100% !important;
            height: 100dvh !important;
            max-height: 100dvh !important;
            background: #ffffff !important;
            border-left: 1px solid #e2e8f0 !important;
            box-shadow: -20px 0 60px -15px rgba(15, 23, 42, 0.28) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            pointer-events: auto !important;
        }
        .rpm-drawer-header {
            padding: 16px 24px !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 16px !important;
            flex-shrink: 0 !important;
        }
        .rpm-role-avatar {
            width: 42px !important;
            height: 42px !important;
            min-width: 42px !important;
            min-height: 42px !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 12px !important;
            font-weight: 800 !important;
            flex-shrink: 0 !important;
        }
        .rpm-header-btn {
            padding: 6px 11px !important;
            border-radius: 8px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            color: #475569 !important;
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
            line-height: 1.2 !important;
        }
        .rpm-header-btn:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .rpm-close-btn {
            width: 34px !important;
            height: 34px !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #64748b !important;
            background: transparent !important;
            border: 1px solid transparent !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        .rpm-close-btn:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }
        .rpm-drawer-subheader {
            padding: 12px 24px !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
            flex-shrink: 0 !important;
        }
        .rpm-search-wrap {
            position: relative !important;
            width: 100% !important;
        }
        .rpm-search-input {
            width: 100% !important;
            padding: 9px 32px 9px 36px !important;
            border-radius: 9px !important;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #1e293b !important;
            line-height: 1.4 !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03) !important;
        }
        .rpm-search-input:focus {
            outline: none !important;
            border-color: #c3122e !important;
            box-shadow: 0 0 0 3px rgba(195, 18, 46, 0.1) !important;
        }
        .rpm-search-icon {
            position: absolute !important;
            left: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 15px !important;
            height: 15px !important;
            color: #94a3b8 !important;
            pointer-events: none !important;
        }
        .rpm-drawer-body {
            flex: 1 1 0% !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            overscroll-behavior: contain !important;
            scrollbar-gutter: stable !important;
            padding: 20px 24px !important;
            background: #f8fafc !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 16px !important;
        }
        .rpm-drawer-body::-webkit-scrollbar {
            width: 7px !important;
        }
        .rpm-drawer-body::-webkit-scrollbar-track {
            background: #f1f5f9 !important;
        }
        .rpm-drawer-body::-webkit-scrollbar-thumb {
            background: #cbd5e1 !important;
            border-radius: 999px !important;
        }
        .rpm-drawer-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8 !important;
        }
        .rpm-module-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03) !important;
            flex-shrink: 0 !important;
        }
        .rpm-module-header {
            padding: 11px 16px !important;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
        }
        .rpm-module-grid {
            padding: 14px 16px !important;
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
        }
        @media (max-width: 540px) {
            .rpm-module-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }
        .rpm-perm-item {
            display: flex !important;
            align-items: flex-start !important;
            gap: 10px !important;
            padding: 10px 12px !important;
            border-radius: 9px !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            cursor: pointer !important;
            user-select: none !important;
            transition: all 0.15s ease !important;
        }
        .rpm-perm-item:hover {
            border-color: #cbd5e1 !important;
            background: #f8fafc !important;
        }
        .rpm-perm-item.is-checked {
            background: #fff1f2 !important;
            border-color: #fda4af !important;
            box-shadow: 0 1px 2px rgba(195, 18, 46, 0.05) !important;
        }
        .rpm-perm-item.is-checked:hover {
            background: #ffe4e6 !important;
            border-color: #fb7185 !important;
        }
        .rpm-perm-checkbox {
            width: 15px !important;
            height: 15px !important;
            margin-top: 2px !important;
            border-radius: 4px !important;
            accent-color: #c3122e !important;
            flex-shrink: 0 !important;
            pointer-events: none !important;
        }
        .rpm-drawer-footer {
            padding: 14px 24px !important;
            border-top: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
            flex-shrink: 0 !important;
        }
        .rpm-btn-cancel {
            padding: 8px 16px !important;
            border-radius: 9px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #475569 !important;
            background: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            cursor: pointer !important;
            transition: all 0.15s ease !important;
        }
        .rpm-btn-cancel:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .rpm-btn-save {
            padding: 8px 18px !important;
            border-radius: 9px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            background: #c3122e !important;
            border: 1px solid #c3122e !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            box-shadow: 0 2px 6px rgba(195, 18, 46, 0.25) !important;
            transition: all 0.15s ease !important;
        }
        .rpm-btn-save:hover {
            background: #a50e26 !important;
            border-color: #a50e26 !important;
        }
        .rpm-modal-overlay {
            position: fixed !important;
            inset: 0 !important;
            z-index: 2147483640 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 16px !important;
            overflow-y: auto !important;
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif !important;
        }
        .rpm-modal-overlay .rounded-2xl {
            border-radius: 14px !important;
        }
        .rpm-modal-overlay .rounded-xl {
            border-radius: 9px !important;
        }
    </style>

    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Roles &amp; Permissions Governance Hub
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Manage project-level contextual roles &amp; system-wide granular capabilities</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
            <button
                wire:click="openCreateRoleModal"
                type="button"
                class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 cursor-pointer"
                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
            >
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <span>Create New Role</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         2. KPI SUMMARY METRICS (1 Clean Responsive Row)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate">SYSTEM GOVERNANCE</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-rose-50 text-[#c3122e] flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
            </div>
            <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $systemRolesCount }}</div>
        </div>

        <!-- Project Leadership Roles -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate">PROJECT LEADERSHIP</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>
            <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $leadershipRolesCount }}</div>
        </div>

        <!-- Squad & Collaborator Roles -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate">DELIVERY SQUAD</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $squadRolesCount }}</div>
        </div>

        <!-- Access Controls / Permissions -->
        <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3.5 sm:p-4 shadow-2xs hover:shadow-md transition-all">
            <div class="flex items-center justify-between gap-1 mb-2">
                <span class="text-[9.5px] sm:text-[10px] font-black text-slate-400 uppercase tracking-wider block truncate">ACCESS CONTROLS</span>
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
            </div>
            <div class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight font-mono leading-none">{{ $totalPermsCount }}</div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. DEDICATED GOVERNANCE MATRIX TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        <!-- Table Top Control Bar -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex flex-wrap sm:flex-nowrap items-center justify-between gap-3">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-[#c3122e] flex items-center justify-center font-bold text-sm border border-rose-200/80 shadow-2xs shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="flex items-center gap-2.5 flex-wrap min-w-0">
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base tracking-tight truncate">Role Access Governance Matrix</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-200/80 shadow-2xs shrink-0">
                        {{ count($roles) }} Roles Active
                    </span>
                </div>
            </div>

            <!-- Search Control -->
            <div class="relative w-full sm:w-64 max-w-xs shrink-0">
                <input 
                    type="text" 
                    wire:model.live.debounce.250ms="searchRole" 
                    placeholder="Search roles..." 
                    class="w-full text-xs font-semibold rounded-xl border border-slate-300/90 pl-8 pr-7 py-2 bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/15 shadow-2xs transition-all"
                    style="padding-left: 34px !important;"
                >
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if($searchRole)
                    <button wire:click="$set('searchRole', '')" type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                @endif
            </div>
        </div>

        <!-- Table View -->
        <div class="w-full overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-100/90 border-b border-slate-200 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <th class="py-4 pl-6 pr-4 sticky left-0 z-20 bg-slate-100 min-w-[220px] shadow-r">Role</th>
                        @foreach(\App\Services\RbacService::getAllModules() as $mKey => $mDef)
                            <th class="py-4 px-3 text-center min-w-[130px]">{{ $mDef['name'] }}</th>
                        @endforeach
                        <th class="py-4 pl-3 pr-6 text-right min-w-[160px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-xs">
                    @forelse($roles as $roleKey => $roleDef)
                        @php
                            $roleCode = $roleDef['code'] ?? (is_string($roleKey) && !is_numeric($roleKey) ? $roleKey : '');
                            if (!$roleCode && isset($roleDef['name'])) {
                                $roleCode = strtolower(str_replace(' ', '_', $roleDef['name']));
                            }
                            $isProtected = $roleDef['is_protected'] ?? \App\Services\RbacService::isProtectedRole($roleCode);
                            $isSystem = $roleDef['is_system'] ?? false;
                            $userCount = $roleDef['users_count'] ?? 0;
                            $isPmoAdminRole = in_array($roleCode, ['super_admin', 'pmo_admin'], true);
                            $isSystemLocked = \App\Services\RbacService::isSystemLockedRole($roleCode);

                            $abbr = match($roleCode) {
                                'pmo_admin' => 'PA', 'admin_viewer' => 'AV',
                                'lead' => 'PM',
                                'sponsor' => 'PS',
                                'owner' => 'PO',
                                'steering_committee' => 'SC',
                                'member' => 'CT',
                                'collaborator' => 'CS',
                                default => strtoupper(substr($roleDef['name'], 0, 2))
                            };
                        @endphp
                        <tr wire:key="role-row-{{ $roleCode }}" class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Sticky Role Column -->
                            <td class="py-4 pl-6 pr-4 sticky left-0 z-10 bg-white group-hover:bg-slate-50/90 transition-colors shadow-r">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black border shadow-2xs flex-shrink-0 {{ $roleDef['badge'] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ $abbr }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h4 class="font-black text-slate-900 text-xs sm:text-sm tracking-tight leading-tight truncate">
                                                {{ $roleDef['name'] }}
                                            </h4>
                                            @if(!$isSystem)
                                                <span class="px-1.5 py-0.5 rounded text-[8.5px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                                    Custom
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Dynamic Active Functional Modules Columns -->
                            @foreach(array_keys(\App\Services\RbacService::getAllModules()) as $mKey)
                                <td class="py-4 px-3 text-center">
                                    @php $s = \App\Services\RbacService::computeModuleSummary($roleCode, $mKey); @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                            @endforeach

                            <!-- Actions Column: Omit Manage button for PMO admin -->
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @if($isSystemLocked)
                                        <span class="px-3 py-1.5 rounded-xl text-[11px] font-black border shadow-2xs inline-flex items-center gap-1.5 {{ $isPmoAdminRole ? 'bg-emerald-50 text-emerald-800 border-emerald-200/90' : 'bg-indigo-50 text-indigo-800 border-indigo-200/90' }}">
                                            <span>{{ $isPmoAdminRole ? '⭐' : '👁️' }}</span>
                                            <span>{{ $isPmoAdminRole ? 'Full System Access' : 'Read-only Access' }}</span>
                                        </span>
                                        <button
                                            wire:key="btn-view-{{ $roleCode }}"
                                            wire:click="openManageModal('{{ $roleCode }}')"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black text-slate-700 bg-slate-50 hover:bg-slate-700 hover:text-white border border-slate-200 transition-all cursor-pointer shadow-2xs active:scale-95"
                                            title="View {{ $roleDef['name'] }} permissions"
                                        >
                                            <span>View Access</span>
                                        </button>
                                    @else
                                        <!-- Single Primary Manage Button for Non-Admin Roles -->
                                        <button 
                                            wire:key="btn-manage-{{ $roleCode }}"
                                            wire:click="openManageModal('{{ $roleCode }}')" 
                                            type="button" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black text-[#c3122e] bg-rose-50 hover:bg-[#c3122e] hover:text-white border border-rose-200 transition-all cursor-pointer shadow-2xs hover:shadow-xs active:scale-95"
                                            title="Manage {{ $roleDef['name'] }} Permissions"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span>Manage</span>
                                        </button>

                                        <!-- Delete Button Option (Only rendered for non-protected roles) -->
                                        @if(!$isProtected)
                                            <button 
                                                wire:key="btn-del-{{ $roleCode }}"
                                                wire:click="promptDeleteRole('{{ $roleCode }}')" 
                                                type="button" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 bg-white hover:bg-rose-600 hover:text-white border border-rose-300 hover:border-rose-600 transition-all cursor-pointer shadow-2xs hover:shadow-xs active:scale-95"
                                                title="Delete Role"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Delete</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count(\App\Services\RbacService::getAllModules()) + 2 }}" class="text-center py-12 text-slate-400 text-xs font-semibold">
                                No matching roles found matching search criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-6 py-4 bg-white border-t border-slate-100">
            {{ $roles->links() }}
        </div>
    </div>

    <!-- Platform-level access: PMO Administrator vs Admin Viewer vs Project Manager -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60">
            <h3 class="font-extrabold text-slate-900 text-sm sm:text-base tracking-tight">Platform Access Comparison</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Page-level and administrative access that is enforced by the platform itself, for PMO Administrator, Admin Viewer and Project Manager.</p>
        </div>
        <div class="w-full overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-full">
                <thead>
                    <tr class="bg-slate-100/90 border-b border-slate-200 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <th class="py-3.5 pl-6 pr-4 min-w-[280px]">Capability</th>
                        <th class="py-3.5 px-3 text-center min-w-[150px]">PMO Administrator</th>
                        <th class="py-3.5 px-3 text-center min-w-[150px]">Admin Viewer</th>
                        <th class="py-3.5 pl-3 pr-6 text-center min-w-[150px]">Project Manager</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-xs">
                    @foreach($systemAccess as $row)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 pl-6 pr-4">
                                <div class="font-black text-slate-900 text-xs sm:text-[13px] leading-tight">{{ $row['label'] }}</div>
                                <div class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $row['note'] }}</div>
                            </td>
                            @foreach(['pmo_admin', 'admin_viewer', 'lead'] as $accessRole)
                                @php
                                    $level = $row['access'][$accessRole] ?? 'no';
                                    [$levelLabel, $levelClass] = match($level) {
                                        'yes' => ['Allowed', 'bg-emerald-50 text-emerald-700 border-emerald-200 font-extrabold'],
                                        'view' => ['View only', 'bg-blue-50 text-blue-700 border-blue-200 font-bold'],
                                        'scoped' => [$row['scoped_note'] ?? 'Conditional', 'bg-amber-50 text-amber-800 border-amber-200 font-bold'],
                                        default => ['Not allowed', 'bg-slate-100 text-slate-500 border-slate-200 font-bold'],
                                    };
                                @endphp
                                <td class="py-3.5 px-3 text-center">
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block {{ $level === 'scoped' ? 'whitespace-normal leading-snug max-w-[200px]' : 'whitespace-nowrap' }} {{ $levelClass }}">{{ $levelLabel }}</span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         5. MANAGE PERMISSIONS DRAWER / SLIDE-OVER MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showManageDrawer && $selectedRole)
        @php
            $currentRoleInfo = $allRoles[$selectedRole] ?? ['name' => $selectedRole, 'icon' => '🏷️', 'badge' => 'bg-slate-100'];
            $hasUnsaved = $this->hasUnsavedChanges;
            $isPmoAdminRole = in_array($selectedRole, ['super_admin', 'pmo_admin'], true);

            $activeRolePermissions = $rolePermissions;
            if (!$isPmoAdminRole) {
                unset($activeRolePermissions['project.create']);
            }
            $selectedCount = count(array_filter($activeRolePermissions));
            $visibleTotalPermsCount = count($activeRolePermissions);

            $roleAbbr = match($selectedRole) {
                'pmo_admin' => 'PA', 'admin_viewer' => 'AV',
                'lead' => 'PM',
                'sponsor' => 'PS',
                'owner' => 'PO',
                'steering_committee' => 'SC',
                'member' => 'CT',
                'collaborator' => 'CS',
                default => strtoupper(substr($currentRoleInfo['name'] ?? $selectedRole, 0, 2))
            };
        @endphp

        @teleport('body')
        <div 
            x-data 
            @keydown.escape.window="$wire.closeManageModal()" 
            class="rpm-drawer-overlay" 
            role="dialog" 
            aria-modal="true"
        >
            <div 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="rpm-drawer-backdrop transition-opacity" 
                wire:click="closeManageModal"
            ></div>

            <div class="rpm-drawer-container">
                <div 
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-300"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="rpm-drawer-panel"
                >
                    
                    <!-- Top Brand Line -->
                    <div class="h-1.5 w-full flex-shrink-0" style="height: 5px; background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, #c3122e 100%);"></div>

                    <!-- Drawer Header -->
                    <div class="rpm-drawer-header">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="rpm-role-avatar border shadow-2xs {{ $currentRoleInfo['badge'] }}">
                                {{ $roleAbbr }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-black text-slate-900 tracking-tight leading-tight">
                                        {{ $currentRoleInfo['name'] }}
                                    </h3>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $selectedRole }}
                                    </span>
                                    @if($drawerReadOnly)
                                        <span class="px-2 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-800 border border-indigo-200">
                                            READ-ONLY
                                        </span>
                                    @endif
                                    @if($hasUnsaved)
                                        <span class="px-2 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">
                                            UNSAVED CHANGES
                                         </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    <strong class="text-slate-800">{{ $selectedCount }}</strong> of {{ $visibleTotalPermsCount }} permissions granted
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            @unless($drawerReadOnly)
                                <button wire:click="selectAllGlobal" type="button" class="rpm-header-btn">Select All</button>
                                <button wire:click="clearAllGlobal" type="button" class="rpm-header-btn">Clear All</button>
                            @endunless
                            <button wire:click="closeManageModal" type="button" class="rpm-close-btn ml-1" title="Close">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Drawer Search Bar & Progress Bar -->
                    <div class="rpm-drawer-subheader">
                        <div class="rpm-search-wrap">
                            <svg class="rpm-search-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input 
                                type="text" 
                                wire:model.live.debounce.150ms="searchDrawerPermission"
                                placeholder="Filter permissions in this role (e.g. create, edit, delete, budget, task)..." 
                                class="rpm-search-input"
                            >
                            @if($searchDrawerPermission)
                                <button wire:click="$set('searchDrawerPermission', '')" type="button" class="absolute right-2.5 top-2 text-xs font-bold text-slate-400 hover:text-slate-700 cursor-pointer">✕</button>
                            @endif
                        </div>
                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-200 rounded-full overflow-hidden" style="height: 6px;">
                            <div class="h-full rounded-full transition-all duration-300 {{ $selectedCount === $visibleTotalPermsCount ? 'bg-emerald-500' : 'bg-[#c3122e]' }}" style="width: {{ $visibleTotalPermsCount > 0 ? round(($selectedCount / $visibleTotalPermsCount) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Drawer Permissions Body (Scrollable Modules Accordion) -->
                    <div class="rpm-drawer-body">
                        @if($drawerReadOnly)
                            <div class="rounded-xl border border-indigo-200 bg-indigo-50/70 px-4 py-3 text-xs font-semibold text-indigo-900 leading-relaxed flex-shrink-0">
                                @if($isPmoAdminRole)
                                    <strong>PMO Administrator</strong> has unrestricted access: every permission below is granted automatically. This access is enforced by the platform and cannot be edited here.
                                @else
                                    <strong>Admin Viewer</strong> is an organisation-wide, read-only observer: every <em>View</em> permission is granted. It can never create, edit or delete anything. This is enforced by the platform and cannot be edited here.
                                @endif
                            </div>
                        @endif
                        @php $matchingModulesCount = 0; @endphp
                        @foreach($allModules as $modKey => $modDef)
                            @php
                                $modPerms = $modDef['permissions'];

                                if ($modKey === 'project') {
                                    if (!$isPmoAdminRole) {
                                        unset($modPerms['project.create']);
                                    }

                                    if (in_array($selectedRole, ['lead', 'project_manager'], true)) {
                                        unset($modPerms['project.view_all']);
                                    }
                                }

                                if ($modKey === 'calendar') {
                                    if (!$isPmoAdminRole) {
                                        unset($modPerms['calendar.create']);
                                    }
                                }

                                if (!empty($searchDrawerPermission)) {
                                    $pQ = strtolower($searchDrawerPermission);
                                    $modPerms = array_filter($modPerms, function($label, $code) use ($pQ) {
                                        return str_contains(strtolower($label), $pQ) || str_contains(strtolower($code), $pQ);
                                    }, ARRAY_FILTER_USE_BOTH);
                                }

                                if (empty($modPerms)) {
                                    continue;
                                }

                                $matchingModulesCount++;
                                $grantedInMod = count(array_filter(array_intersect_key($rolePermissions, array_flip(array_keys($modPerms)))));
                                $totalInMod = count($modPerms);
                            @endphp

                            <div class="rpm-module-card">
                                <!-- Module Header -->
                                <div class="rpm-module-header">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-700 text-xs font-bold shrink-0">
                                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                        </div>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $modDef['name'] }}</h4>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $grantedInMod === $totalInMod ? 'bg-emerald-100 text-emerald-800' : ($grantedInMod > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500') }}">
                                            {{ $grantedInMod }}/{{ $totalInMod }}
                                        </span>
                                    </div>

                                    @unless($drawerReadOnly)
                                    <div class="flex items-center gap-2 text-[11px] shrink-0">
                                        <button wire:click="selectAllForModule('{{ $modKey }}')" type="button" class="font-bold text-[#c3122e] hover:underline cursor-pointer">All</button>
                                        <span class="text-slate-300">|</span>
                                        <button wire:click="clearAllForModule('{{ $modKey }}')" type="button" class="font-bold text-slate-500 hover:underline cursor-pointer">None</button>
                                    </div>
                                    @endunless
                                </div>

                                <!-- Module Permissions Grid -->
                                <div class="rpm-module-grid">
                                    @foreach($modPerms as $pCode => $pLabel)
                                        @php $checked = !empty($rolePermissions[$pCode]); @endphp
                                        <div 
                                            @if(!$drawerReadOnly) wire:click="togglePermission('{{ $pCode }}')" @endif
                                            class="rpm-perm-item {{ $checked ? 'is-checked' : '' }}"
                                            @if($drawerReadOnly) style="cursor: default;" @endif
                                        >
                                            <input 
                                                type="checkbox" 
                                                @checked($checked)
                                                @disabled($drawerReadOnly)
                                                class="rpm-perm-checkbox"
                                            >
                                            <div class="min-w-0 text-xs">
                                                <span class="font-bold block leading-snug {{ $checked ? 'text-[#c3122e]' : 'text-slate-800' }}">{{ $pLabel }}</span>
                                                <span class="text-[10px] font-mono text-slate-400 block mt-0.5 truncate">{{ $pCode }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @if($matchingModulesCount === 0)
                            <div class="text-center py-12 text-slate-400 text-xs font-semibold">
                                No permissions found matching "{{ $searchDrawerPermission }}".
                            </div>
                        @endif
                    </div>

                    <!-- Sticky Drawer Footer -->
                    <div class="rpm-drawer-footer">
                        @if($drawerReadOnly)
                            <p class="text-[11px] font-semibold text-slate-500">Read-only &middot; this role is enforced by the platform and cannot be edited.</p>
                            <button wire:click="closeManageModal" type="button" class="rpm-btn-cancel">Close</button>
                        @else
                        <div class="flex items-center gap-3">
                            <button 
                                wire:click="resetRoleToDefault('{{ $selectedRole }}')"
                                wire:confirm="Reset this role to default permissions?"
                                type="button" 
                                class="text-xs font-bold text-slate-500 hover:text-slate-800 cursor-pointer"
                            >
                                Reset Defaults
                            </button>

                            @php
                                $selectedIsProtected = isset($allRoles[$selectedRole]) 
                                    ? ($allRoles[$selectedRole]['is_protected'] ?? \App\Services\RbacService::isProtectedRole($selectedRole)) 
                                    : \App\Services\RbacService::isProtectedRole($selectedRole);
                            @endphp
                            @if(!$selectedIsProtected)
                                <span class="text-slate-300">|</span>
                                <button 
                                    wire:click="promptDeleteRole('{{ $selectedRole }}')" 
                                    type="button" 
                                    class="text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline cursor-pointer flex items-center gap-1"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Delete Role</span>
                                </button>
                            @endif
                        </div>

                        <div class="flex items-center gap-2.5">
                            <button wire:click="closeManageModal" type="button" class="rpm-btn-cancel">
                                Cancel
                            </button>
                            <button 
                                wire:click="savePermissions" 
                                type="button" 
                                class="rpm-btn-save"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="savePermissions" class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                    <span>Save Permissions</span>
                                </span>
                                <span wire:loading wire:target="savePermissions" class="flex items-center gap-1.5">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    <span>Saving...</span>
                                </span>
                            </button>
                        </div>
                    @endif
                    </div>

                </div>
            </div>
        </div>
        @endteleport
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         6. CREATE NEW ROLE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateRoleModal)
        @teleport('body')
        <div 
            x-data 
            x-trap.inert.noscroll="true" 
            @keydown.escape.window="$wire.set('showCreateRoleModal', false)" 
            class="rpm-modal-overlay" 
            role="dialog" 
            aria-modal="true" 
            aria-labelledby="role-modal-title"
        >
            <div 
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="rpm-modal-backdrop transition-opacity" 
                wire:click="$set('showCreateRoleModal', false)"
            ></div>

            <div 
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                class="relative z-10 w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl transition-all"
            >
                <!-- Top Brand Line -->
                <div class="h-1 w-full bg-gradient-to-r from-[#c3122e] to-[#8b0d1f]"></div>

                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-5 h-5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 id="role-modal-title" class="text-base font-extrabold text-slate-900 tracking-tight">Create Project Role</h3>
                            <p class="mt-0.5 text-xs text-slate-500 font-medium">Define a reusable governance or delivery role.</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showCreateRoleModal', false)" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700 cursor-pointer" aria-label="Close role form">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createRole" class="space-y-4 px-5 py-5 sm:px-6">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-700">Role display name <span class="text-rose-600">*</span></label>
                        <input type="text" wire:model.live.debounce.150ms="newRoleName" maxlength="100" placeholder="e.g. Quality Assurance Lead" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100" autofocus>
                        @error('newRoleName') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between gap-3">
                            <label class="text-xs font-bold text-slate-700">System role code <span class="text-rose-600">*</span></label>
                            <div class="flex items-center gap-2">
                                @if($isRoleCodeManuallyEdited)
                                    <button type="button" wire:click="resetRoleCodeToAuto" class="text-[10px] font-bold text-[#c3122e] hover:underline cursor-pointer">
                                        ↻ Auto-generate
                                    </button>
                                @endif
                                <span class="text-[10px] text-slate-400 font-mono">Unique slug</span>
                            </div>
                        </div>
                        <input type="text" wire:model.live.debounce.150ms="newRoleCode" maxlength="50" placeholder="e.g. qa_lead" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 font-mono text-sm font-bold text-slate-800 outline-none transition focus:border-[#c3122e] focus:bg-white focus:ring-4 focus:ring-rose-100">
                        <p class="mt-1 text-[10px] text-slate-400 font-medium">Use lowercase letters, numbers and underscores only.</p>
                        @error('newRoleCode') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-700">Base permissions <span class="font-medium text-slate-400">(optional)</span></label>
                        <select wire:model="clonePermissionsFrom" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-800 outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100 cursor-pointer">
                            <option value="">Start with no permissions</option>
                            @foreach($allRoles as $rKey => $rDef)
                                <option value="{{ $rKey }}">{{ $rDef['name'] }} ({{ $rKey }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-700">Role description <span class="font-medium text-slate-400">(optional)</span></label>
                        <textarea wire:model="newRoleDescription" rows="3" maxlength="500" placeholder="Responsibilities and scope of this role..." class="w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm leading-relaxed text-slate-800 outline-none transition focus:border-[#c3122e] focus:ring-4 focus:ring-rose-100"></textarea>
                    </div>

                    <div class="-mx-5 -mb-5 mt-5 grid grid-cols-2 gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-4 sm:-mx-6 sm:px-6">
                        <button type="button" wire:click="$set('showCreateRoleModal', false)" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-700 transition-colors hover:bg-slate-50 cursor-pointer">Cancel</button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="createRole" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[#c3122e] px-4 text-xs font-bold text-white transition-colors hover:bg-[#a50e26] disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer shadow-sm">
                            <span wire:loading.remove wire:target="createRole">Create role</span>
                            <span wire:loading wire:target="createRole" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>Creating...</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endteleport
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8. DELETE ROLE CONFIRMATION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showDeleteRoleModal)
        @teleport('body')
        <div 
            x-data
            @keydown.escape.window="$wire.cancelDeleteRole()"
            class="rpm-modal-overlay"
            role="dialog" 
            aria-modal="true"
            aria-labelledby="delete-role-modal-title"
        >
            <!-- Backdrop with blur -->
            <div 
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="rpm-modal-backdrop transition-opacity" 
                wire:click="cancelDeleteRole"
            ></div>

            <!-- Modal Content Card -->
            <div 
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl transition-all z-10 space-y-4"
            >
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 via-[#c3122e] to-rose-600"></div>

                <!-- Header with Icon & Close Button -->
                <div class="flex items-start justify-between gap-3.5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] shadow-xs">
                            <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 id="delete-role-modal-title" class="text-base font-extrabold text-slate-900 tracking-tight leading-snug">
                                Delete Role
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">This action cannot be undone.</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="cancelDeleteRole" 
                        class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors cursor-pointer"
                        aria-label="Close dialog"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Confirmation Alert Card -->
                <div class="p-4 rounded-xl bg-rose-50/70 border border-rose-100/90 text-xs text-slate-700 space-y-3">
                    <p class="font-medium text-slate-700 leading-relaxed">
                        Are you sure you want to permanently delete this role?
                    </p>

                    <!-- Dedicated Role Name & Slug Badge -->
                    <div class="p-3 rounded-lg bg-white border border-rose-200/90 flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#c3122e] shrink-0 ring-4 ring-rose-50"></span>
                            <span class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                {{ $roleNameToDelete ?: $roleToDelete }}
                            </span>
                        </div>
                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-md bg-slate-50 border border-rose-200 text-[#c3122e] shrink-0">
                            {{ $roleToDelete }}
                        </span>
                    </div>

                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        All permissions assigned to this role will be detached and removed. This operation cannot be undone.
                    </p>

                    @if($roleUsersCountToDelete > 0)
                        <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-[11px] font-semibold flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="leading-snug">
                                <strong>{{ $roleUsersCountToDelete }} user(s)</strong> currently assigned to this role will be unassigned automatically upon deletion.
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        wire:click="cancelDeleteRole" 
                        type="button" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="confirmDeleteRole" 
                        wire:loading.attr="disabled"
                        wire:target="confirmDeleteRole"
                        type="button" 
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-1.5 bg-[#c3122e] hover:bg-[#a50e26] disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="confirmDeleteRole" class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Yes, Permanently Delete</span>
                        </span>
                        <span wire:loading wire:target="confirmDeleteRole" class="flex items-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Deleting...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        @endteleport
    @endif

</div>
