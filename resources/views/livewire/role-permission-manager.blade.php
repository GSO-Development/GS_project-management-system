<div class="space-y-6 sm:space-y-7 pb-12" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
    
    <!-- ===== FLOATING TOAST NOTIFICATION ===== -->
    @if($successToast)
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => { show = false; $wire.set('successToast', null); }, 4500)"
            class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 bg-emerald-600 text-white rounded-2xl shadow-2xl border border-emerald-400/40 animate-in slide-in-from-top-4 duration-300"
        >
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center font-bold text-base flex-shrink-0">
                ✓
            </div>
            <div>
                <h5 class="text-xs font-black tracking-tight">Operation Successful</h5>
                <p class="text-[11px] font-medium text-emerald-100 mt-0.5">{{ $successToast }}</p>
            </div>
            <button @click="show = false; $wire.set('successToast', null)" class="ml-3 text-white/70 hover:text-white cursor-pointer p-1">
                ✕
            </button>
        </div>
    @endif

    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                Roles &amp; Permissions Hub
            </h1>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0">
            <button
                wire:click="openCreatePermissionModal"
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 shadow-2xs hover:shadow-xs transition-all cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                <span>Add Permission</span>
            </button>

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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- System Governance Roles -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">System Governance</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">2</span>
                    <span class="text-[11px] sm:text-xs font-semibold text-rose-600">Admin Roles</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Super Admin &bull; PMO Admin</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
        </div>

        <!-- Project Leadership Roles -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Project Leadership</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">4</span>
                    <span class="text-[11px] sm:text-xs font-semibold text-amber-600">Executive Roles</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">PM, Sponsor, Owner, Board</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <!-- Squad & Collaborator Roles -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Delivery Squad</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ max(0, $totalRolesCount - 6) + 2 }}</span>
                    <span class="text-[11px] sm:text-xs font-semibold text-blue-600">Squad Roles</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Team members &amp; custom roles</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>

        <!-- Granular Rights Count -->
        <div class="p-4 sm:p-5 bg-white rounded-2xl border border-slate-200/90 shadow-2xs flex items-center justify-between transition-all hover:shadow-md">
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider truncate">Access Controls</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <span class="text-xl sm:text-2xl font-black text-emerald-600 font-mono">{{ $totalPermsCount }}</span>
                    <span class="text-[11px] sm:text-xs font-bold text-emerald-600">Permissions</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 truncate">Across {{ count($allModules) }} functional modules</p>
            </div>
            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════════
         3. DEDICATED GOVERNANCE MATRIX TABLE VIEW
         ═══════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
        
        <!-- Table Top Control Bar -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-[#c3122e] flex items-center justify-center font-bold text-sm border border-rose-200 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-black text-slate-900 text-sm sm:text-base tracking-tight">Role Access Governance Matrix</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                            {{ count($roles) }} Roles Active
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Granular capability privileges across all 9 functional project modules</p>
                </div>
            </div>

            <!-- Legend & Search -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <!-- Legend Badges -->
                <div class="flex items-center gap-1.5 flex-wrap text-[10px] font-black">
                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">Full Access</span>
                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200">View Only</span>
                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200">Assigned Only</span>
                    <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">Custom</span>
                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">No Access</span>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-56">
                    <input 
                        type="text" 
                        wire:model.live.debounce.250ms="searchRole" 
                        placeholder="Search roles..." 
                        class="w-full text-xs font-semibold rounded-xl border border-slate-300 pl-8 pr-3 py-2 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e] shadow-2xs"
                        style="border: 1px solid #cbd5e1;"
                    >
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                @if($searchRole)
                    <button wire:click="$set('searchRole', '')" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer flex-shrink-0">
                        Reset
                    </button>
                @endif
            </div>
        </div>

        <!-- Table View -->
        <div class="w-full overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-[1260px]">
                <thead>
                    <tr class="bg-slate-100/90 border-b border-slate-200 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <th class="py-4 pl-6 pr-4 sticky left-0 z-20 bg-slate-100 min-w-[240px] shadow-r">Project Role</th>
                        <th class="py-4 px-3 text-center min-w-[105px]">Project</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">Scope</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">Tasks / WBS</th>
                        <th class="py-4 px-3 text-center min-w-[100px]">Team</th>
                        <th class="py-4 px-3 text-center min-w-[120px]">Budget</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">Risks</th>
                        <th class="py-4 px-3 text-center min-w-[110px]">Approvals</th>
                        <th class="py-4 px-3 text-center min-w-[105px]">Reports</th>
                        <th class="py-4 px-3 text-center min-w-[115px]">Settings</th>
                        <th class="py-4 px-3 text-center min-w-[115px]">Calendar</th>
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
                        @endphp
                        <tr wire:key="role-row-{{ $roleCode }}" class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Sticky Role Column (Clean Identity, No Duplicate Manage Button) -->
                            <td class="py-4 pl-6 pr-4 sticky left-0 z-10 bg-white group-hover:bg-slate-50/90 transition-colors shadow-r">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold border shadow-2xs flex-shrink-0 {{ $roleDef['badge'] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ strtoupper(substr($roleDef['name'], 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h4 class="font-black text-slate-900 text-xs sm:text-sm tracking-tight leading-tight truncate">
                                                {{ $roleDef['name'] }}
                                            </h4>
                                            @if(!$isSystem)
                                                <span class="px-1.5 py-0.2 rounded text-[8.5px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200">
                                                    Custom
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 text-[10px] font-mono text-slate-400 mt-0.5">
                                            <span class="font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $roleCode }}</span>
                                            @if($userCount > 0)
                                                <span>&bull;</span>
                                                <span class="text-slate-600 font-bold">👥 {{ $userCount }} user{{ $userCount !== 1 ? 's' : '' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 10 Functional Modules Columns -->
                            @foreach(['project', 'scope', 'task', 'team', 'budget', 'risks', 'approvals', 'reports', 'settings', 'calendar'] as $mKey)
                                <td class="py-4 px-3 text-center">
                                    @php $s = \App\Services\RbacService::computeModuleSummary($roleCode, $mKey); @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                            @endforeach

                            <!-- Actions Column: 1 Manage Button + Delete Option -->
                            <td class="py-4 pl-3 pr-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Single Primary Manage Button -->
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

                                    <!-- Delete Button Option -->
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
                                    @else
                                        <button 
                                            wire:key="btn-protected-{{ $roleCode }}"
                                            wire:click="cannotDeleteSystemRole('{{ $roleDef['name'] }}')" 
                                            type="button" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-400 bg-slate-50 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 border border-slate-200 transition-all cursor-pointer shadow-2xs active:scale-95"
                                            title="Core Protected Governance Role (Cannot be deleted)"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Delete</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-12 text-slate-400 text-xs font-semibold">
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

    <!-- ═══════════════════════════════════════════════════════════════
         5. MANAGE PERMISSIONS DRAWER / SLIDE-OVER MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showManageDrawer && $selectedRole)
        @php
            $currentRoleInfo = $allRoles[$selectedRole] ?? ['name' => $selectedRole, 'icon' => '🏷️', 'badge' => 'bg-slate-100'];
            $hasUnsaved = $this->hasUnsavedChanges;
            $selectedCount = count(array_filter($rolePermissions));
        @endphp

        <div class="fixed inset-0 overflow-hidden" style="z-index: 9999;">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" style="z-index: 9999;" wire:click="closeManageModal"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-8" style="z-index: 10000;">
                <div class="w-screen max-w-2xl bg-white shadow-2xl flex flex-col border-l border-slate-200">
                    
                    <!-- Top Brand Line -->
                    <div class="h-1.5 w-full flex-shrink-0" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, #c3122e 100%);"></div>

                    <!-- Drawer Header -->
                    <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between gap-4 flex-shrink-0 bg-white">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl border shadow-2xs flex-shrink-0 {{ $currentRoleInfo['badge'] }}">
                                {{ $currentRoleInfo['icon'] }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-black text-slate-900 tracking-tight leading-tight">
                                        {{ $currentRoleInfo['name'] }}
                                    </h3>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-slate-100 text-slate-600">
                                        {{ $selectedRole }}
                                    </span>
                                    @if($hasUnsaved)
                                        <span class="px-2 py-0.5 rounded-full text-[9.5px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">
                                            UNSAVED CHANGES
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    <strong class="text-slate-800">{{ $selectedCount }}</strong> of {{ $totalPermsCount }} permissions granted
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button wire:click="selectAllGlobal" type="button" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 cursor-pointer px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Select All</button>
                            <button wire:click="clearAllGlobal" type="button" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 cursor-pointer px-2.5 py-1 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Clear All</button>
                            <button wire:click="closeManageModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer ml-1">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Drawer Search Bar & Progress Bar -->
                    <div class="px-6 py-3 bg-slate-50 border-b border-slate-100 flex flex-col gap-2.5 flex-shrink-0">
                        <div class="relative w-full">
                            <input 
                                type="text" 
                                wire:model.live.debounce.150ms="searchDrawerPermission"
                                placeholder="Filter permissions in this role (e.g. create, edit, delete, budget, task)..." 
                                class="w-full text-xs font-semibold rounded-xl border border-slate-300 pl-8 pr-3 py-2 bg-white focus:border-[#c3122e] focus:ring-1 focus:ring-[#c3122e] shadow-2xs"
                                style="border: 1px solid #cbd5e1;"
                            >
                            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            @if($searchDrawerPermission)
                                <button wire:click="$set('searchDrawerPermission', '')" class="absolute right-2.5 top-2 text-xs font-bold text-slate-400 hover:text-slate-700">✕</button>
                            @endif
                        </div>
                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300 {{ $selectedCount === $totalPermsCount ? 'bg-emerald-500' : 'bg-[#c3122e]' }}" style="width: {{ $totalPermsCount > 0 ? round(($selectedCount / $totalPermsCount) * 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Drawer Permissions Body (Scrollable Modules Accordion) -->
                    <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-slate-50/40">
                        @php $matchingModulesCount = 0; @endphp
                        @foreach($allModules as $modKey => $modDef)
                            @php
                                $modPerms = $modDef['permissions'];
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
                                $modPermKeys = array_keys($modDef['permissions']);
                                $grantedInMod = count(array_filter(array_intersect_key($rolePermissions, array_flip($modPermKeys))));
                                $totalInMod = count($modPermKeys);
                            @endphp

                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
                                <!-- Module Header -->
                                <div class="px-4.5 py-3 border-b border-slate-100 bg-slate-50/80 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
                                        <span>{{ $modDef['icon'] ?? '📁' }}</span>
                                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wide">{{ $modDef['name'] }}</h4>
                                        <span class="px-2 py-0.2 rounded text-[10px] font-bold {{ $grantedInMod === $totalInMod ? 'bg-emerald-100 text-emerald-800' : ($grantedInMod > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500') }}">
                                            {{ $grantedInMod }}/{{ $totalInMod }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 text-[10.5px]">
                                        <button wire:click="selectAllForModule('{{ $modKey }}')" type="button" class="font-bold text-[#c3122e] hover:underline cursor-pointer">All</button>
                                        <span class="text-slate-300">|</span>
                                        <button wire:click="clearAllForModule('{{ $modKey }}')" type="button" class="font-bold text-slate-500 hover:underline cursor-pointer">None</button>
                                    </div>
                                </div>

                                <!-- Module Permissions Checkboxes -->
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach($modPerms as $pCode => $pLabel)
                                        @php $checked = !empty($rolePermissions[$pCode]); @endphp
                                        <div 
                                            wire:click="togglePermission('{{ $pCode }}')"
                                            class="p-2.5 rounded-xl border transition-all flex items-start gap-2.5 cursor-pointer select-none {{ $checked ? 'bg-rose-50/50 border-rose-300 text-slate-900 shadow-xs' : 'bg-white border-slate-200/80 text-slate-600 hover:bg-slate-50' }}"
                                        >
                                            <input 
                                                type="checkbox" 
                                                @checked($checked)
                                                class="pointer-events-none rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e] mt-0.5 flex-shrink-0"
                                            >
                                            <div class="min-w-0 text-xs">
                                                <span class="font-bold block leading-tight {{ $checked ? 'text-[#c3122e]' : 'text-slate-800' }}">{{ $pLabel }}</span>
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
                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between gap-3 bg-white flex-shrink-0">
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
                            <button wire:click="closeManageModal" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">
                                Cancel
                            </button>
                            <button 
                                wire:click="savePermissions" 
                                type="button" 
                                class="px-5 py-2 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-1.5"
                                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="savePermissions">💾 Save Permissions</span>
                                <span wire:loading wire:target="savePermissions" class="flex items-center gap-1.5">
                                    <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Saving...
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         6. CREATE NEW ROLE MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreateRoleModal)
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999;">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs" style="z-index: 9999;" wire:click="$set('showCreateRoleModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4 mx-3 sm:mx-auto" style="z-index: 10000;">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-bold text-lg">
                            🏷️
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Create New Project Role</h3>
                            <p class="text-xs text-slate-500 font-medium">Define a custom governance or squad role</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateRoleModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createRole" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Role Display Name <span class="text-[#c3122e]">*</span></label>
                        <input type="text" wire:model.live="newRoleName" placeholder="e.g. Quality Assurance Lead" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                        @error('newRoleName') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">System Role Code (Slug) <span class="text-[#c3122e]">*</span></label>
                        <input type="text" wire:model="newRoleCode" placeholder="e.g. qa_lead" class="w-full text-xs font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                        @error('newRoleCode') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Clone Base Permissions From (Optional)</label>
                        <select wire:model="clonePermissionsFrom" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                            <option value="">Start with no permissions</option>
                            @foreach($allRoles as $rKey => $rDef)
                                <option value="{{ $rKey }}">{{ $rDef['name'] }} ({{ $rKey }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Role Description</label>
                        <textarea wire:model="newRoleDescription" rows="2" placeholder="Responsibilities and scope of this role..." class="w-full text-xs rounded-xl p-2.5 border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800 leading-normal" style="border: 1px solid #cbd5e1;"></textarea>
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateRoleModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm cursor-pointer">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         7. CREATE NEW PERMISSION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showCreatePermissionModal)
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 9999;">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs" style="z-index: 9999;" wire:click="$set('showCreatePermissionModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4 mx-3 sm:mx-auto" style="z-index: 10000;">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-bold text-lg">
                            🔐
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Add Granular Permission</h3>
                            <p class="text-xs text-slate-500 font-medium">Define a new capability code in the system</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreatePermissionModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="createPermission" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Module <span class="text-[#c3122e]">*</span></label>
                        <select wire:model.live="newPermissionModule" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                            @foreach($allModules as $mKey => $mDef)
                                <option value="{{ $mKey }}">{{ $mDef['name'] }} ({{ $mKey }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Permission Display Name <span class="text-[#c3122e]">*</span></label>
                        <input type="text" wire:model.live="newPermissionName" placeholder="e.g. Export Audit Report" class="w-full text-xs font-semibold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                        @error('newPermissionName') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Permission Code (Spatie Name) <span class="text-[#c3122e]">*</span></label>
                        <input type="text" wire:model="newPermissionCode" placeholder="e.g. project.export_audit" class="w-full text-xs font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-300 bg-white focus:border-[#c3122e] text-slate-800" style="border: 1px solid #cbd5e1;">
                        @error('newPermissionCode') <span class="text-[11px] text-rose-500 font-bold block mt-0.5">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreatePermissionModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm cursor-pointer">Register Permission</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════
         8. DELETE ROLE CONFIRMATION MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showDeleteRoleModal)
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 10001;">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="cancelDeleteRole"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4" style="z-index: 10002;">
                <div class="flex items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-2xs">
                        🗑️
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-black text-slate-900 tracking-tight">Delete Role</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">This action will permanently delete this role.</p>
                    </div>
                    <button wire:click="cancelDeleteRole" class="text-slate-400 hover:text-slate-700 cursor-pointer -mt-1 -mr-1 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-4 rounded-2xl bg-rose-50/60 border border-rose-100 text-xs text-slate-700 space-y-2">
                    <p class="font-semibold text-slate-900">
                        Are you sure you want to permanently delete the role <span class="font-black text-[#c3122e]">"{{ $roleNameToDelete }}"</span> <code class="font-mono font-bold bg-white px-1.5 py-0.5 rounded border border-rose-200 text-slate-700">({{ $roleToDelete }})</code>?
                    </p>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        All permissions assigned to this role will be detached and removed. This operation cannot be undone.
                    </p>
                    @if($roleUsersCountToDelete > 0)
                        <div class="p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-bold flex items-center gap-2">
                            <span>⚠️</span>
                            <span>Warning: {{ $roleUsersCountToDelete }} user(s) are currently assigned to this role and must be reassigned.</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        wire:click="cancelDeleteRole" 
                        type="button" 
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="confirmDeleteRole" 
                        type="button" 
                        class="px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center gap-1.5"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="confirmDeleteRole">Yes, Permanently Delete</span>
                        <span wire:loading wire:target="confirmDeleteRole" class="flex items-center gap-1.5">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
