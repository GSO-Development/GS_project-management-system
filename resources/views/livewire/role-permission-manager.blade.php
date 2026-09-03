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

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/40 shadow-xl p-5 sm:p-6 lg:p-7 text-white" style="background: linear-gradient(135deg, #18060c 0%, #2e0915 50%, #18060c 100%);">
        <!-- Top Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e]"></div>

        <!-- Ambient Glow Elements -->
        <div class="absolute -right-10 -bottom-10 w-80 h-80 rounded-full bg-rose-600/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-60 h-60 rounded-full bg-amber-500/10 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left Side: App Icon + Title + Meta -->
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-lg flex-shrink-0 border border-white/20 ring-4 ring-amber-500/20 flex items-center justify-center p-2.5" style="background: linear-gradient(135deg, #f59e0b 0%, #c3122e 65%, #800a1d 100%);">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Roles &amp; Permissions Hub
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black text-amber-200 border border-amber-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(245, 158, 11, 0.25);">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                            <span>{{ $totalRolesCount }} Active Roles · {{ $totalPermsCount }} Granular Rights</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-300 font-medium mt-1">
                        Enterprise RBAC governance, capability matrices, and granular access control across GS NexusPM
                    </p>
                </div>
            </div>

            <!-- Right Side: Top Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap flex-shrink-0 self-start lg:self-center">
                <button
                    wire:click="openCreatePermissionModal"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-200 bg-white/10 hover:bg-white/15 border border-white/20 transition-all cursor-pointer active:scale-95 backdrop-blur-sm shadow-xs"
                >
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    <span>Add Permission</span>
                </button>

                <button
                    wire:click="openCreateRoleModal"
                    type="button"
                    class="inline-flex items-center gap-1.5 px-4.5 py-2.5 rounded-xl text-xs font-black text-white shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer active:scale-95"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.25);"
                >
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>Create New Role</span>
                </button>
            </div>
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
            <table class="w-full text-left border-collapse min-w-[1150px]">
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
                        <th class="py-4 pl-3 pr-6 text-right min-w-[160px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-xs">
                    @forelse($roles as $roleKey => $roleDef)
                        @php
                            $isSystem = $roleDef['is_system'] ?? false;
                            $userCount = $roleDef['users_count'] ?? 0;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Sticky Role Column -->
                            <td class="py-4 pl-6 pr-4 sticky left-0 z-10 bg-white group-hover:bg-slate-50/90 transition-colors shadow-r">
                                <div class="flex items-center gap-3">
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
                                            <span>{{ $roleKey }}</span>
                                            @if($userCount > 0)
                                                <span>&bull;</span>
                                                <span class="text-slate-600 font-bold">👥 {{ $userCount }} user{{ $userCount !== 1 ? 's' : '' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- 9 Functional Modules Columns -->
                            @foreach(['project', 'scope', 'task', 'team', 'budget', 'risks', 'approvals', 'reports', 'settings'] as $mKey)
                                <td class="py-4 px-3 text-center">
                                    @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, $mKey); @endphp
                                    <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                        {{ $s['label'] }}
                                    </span>
                                </td>
                            @endforeach

                            <!-- Actions Column -->
                            <td class="py-4 pl-3 pr-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(!$isSystem)
                                        <button
                                            wire:click="deleteCustomRole('{{ $roleKey }}')"
                                            wire:confirm="Are you sure you want to delete the custom role '{{ $roleDef['name'] }}'?"
                                            type="button"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Delete Custom Role"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif

                                    <button 
                                        wire:click="openManageModal('{{ $roleKey }}')" 
                                        type="button" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black text-[#c3122e] bg-rose-50 hover:bg-[#c3122e] hover:text-white border border-rose-200 transition-all cursor-pointer shadow-2xs hover:shadow-xs active:scale-95"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Manage</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-12 text-slate-400 text-xs font-semibold">
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

        <div class="fixed inset-0 z-50 overflow-hidden">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity" wire:click="closeManageModal"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-8">
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
                            <button wire:click="selectAllGlobal" type="button" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 cursor-pointer px-2 py-1 bg-slate-100 rounded-lg">Select All</button>
                            <button wire:click="clearAllGlobal" type="button" class="text-[11px] font-bold text-slate-600 hover:text-slate-900 cursor-pointer px-2 py-1 bg-slate-100 rounded-lg">Clear All</button>
                            <button wire:click="closeManageModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer ml-1">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Drawer Permissions Body (Scrollable Modules Accordion) -->
                    <div class="p-6 overflow-y-auto space-y-6 flex-1 bg-slate-50/40">
                        @foreach($allModules as $modKey => $modDef)
                            @php
                                $modPerms = $modDef['permissions'];
                                $modPermKeys = array_keys($modPerms);
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
                                        <label class="p-2.5 rounded-xl border transition-all flex items-start gap-2.5 cursor-pointer {{ $checked ? 'bg-rose-50/40 border-rose-200 text-slate-900' : 'bg-white border-slate-200/80 text-slate-600 hover:bg-slate-50' }}">
                                            <input 
                                                type="checkbox" 
                                                wire:click="togglePermission('{{ $pCode }}')"
                                                @checked($checked)
                                                class="rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e] mt-0.5"
                                            >
                                            <div class="min-w-0 text-xs">
                                                <span class="font-bold block leading-tight">{{ $pLabel }}</span>
                                                <span class="text-[10px] font-mono text-slate-400 block mt-0.5 truncate">{{ $pCode }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Sticky Drawer Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between gap-3 bg-white flex-shrink-0">
                        <button 
                            wire:click="resetRoleToDefault('{{ $selectedRole }}')"
                            wire:confirm="Reset this role to default permissions?"
                            type="button" 
                            class="text-xs font-bold text-slate-500 hover:text-slate-800 cursor-pointer"
                        >
                            Reset Defaults
                        </button>

                        <div class="flex items-center gap-2.5">
                            <button wire:click="closeManageModal" type="button" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 cursor-pointer">
                                Cancel
                            </button>
                            <button 
                                wire:click="savePermissions" 
                                type="button" 
                                class="px-5 py-2 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg transition-all cursor-pointer"
                                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                            >
                                💾 Save Permissions
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs" wire:click="$set('showCreateRoleModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-4 z-10">
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs" wire:click="$set('showCreatePermissionModal', false)"></div>

            <div class="relative bg-white rounded-3xl border border-slate-200 max-w-lg w-full p-6 shadow-2xl space-y-4 z-10">
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

</div>
