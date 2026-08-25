<div>
    <!-- ===== FLOATING TOAST NOTIFICATION ===== -->
    @if($successToast)
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => { show = false; $wire.set('successToast', null); }, 4500)"
            class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-4 bg-emerald-600 text-white rounded-2xl shadow-2xl border border-emerald-400/40 animate-in slide-in-from-top-4 duration-300"
        >
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center font-bold text-base flex-shrink-0">
                ✓
            </div>
            <div>
                <h5 class="text-xs font-black tracking-tight">Permissions Saved</h5>
                <p class="text-[11px] font-medium text-emerald-100 mt-0.5">{{ $successToast }}</p>
            </div>
            <button @click="show = false; $wire.set('successToast', null)" class="ml-3 text-white/70 hover:text-white cursor-pointer p-1">
                ✕
            </button>
        </div>
    @endif

    <!-- ===== 1. TOP EXECUTIVE HERO BANNER ===== -->
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 shadow-sm mb-6 p-5 sm:p-7 bg-white">
        <!-- Top Crimson Brand Accent Strip -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#8b0d1f]"></div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left Side: App Icon + Title + Subtitle -->
            <div class="flex items-start sm:items-center gap-4 min-w-0">
                <div class="w-13 h-13 sm:w-15 sm:h-15 rounded-2xl flex items-center justify-center text-white text-2xl shadow-md flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                    🛡️
                </div>
                <div class="min-w-0 space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-50 text-[#c3122e] border border-rose-200 shadow-2xs">
                            PMO GOVERNANCE RBAC
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            5 Project Roles Active
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                        Roles &amp; Permissions
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">
                        Configure what each project role can view, create, edit, assign, approve, and manage across GS NexusPM.
                    </p>
                </div>
            </div>

            <!-- Right Side: Top Controls & Search -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-shrink-0">
                <!-- Search Roles Input -->
                <div class="relative w-full sm:w-56">
                    <input 
                        type="text" 
                        wire:model.live.debounce.250ms="searchRole" 
                        placeholder="Search roles..." 
                        class="w-full text-xs font-semibold rounded-xl border border-slate-200 pl-9 pr-3.5 py-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none bg-slate-50 focus:bg-white shadow-2xs transition-all"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Search Permissions Input -->
                <div class="relative w-full sm:w-60">
                    <input 
                        type="text" 
                        wire:model.live.debounce.250ms="searchPermission" 
                        placeholder="Search permissions..." 
                        class="w-full text-xs font-semibold rounded-xl border border-slate-200 pl-9 pr-3.5 py-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none bg-slate-50 focus:bg-white shadow-2xs transition-all"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast Alert -->
    @if($successToast)
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-xs">
                    ✓
                </div>
                <div>
                    <h4 class="text-xs font-black text-emerald-950">Success</h4>
                    <p class="text-xs font-semibold text-emerald-800">{{ $successToast }}</p>
                </div>
            </div>
            <button wire:click="$set('successToast', null)" type="button" class="text-emerald-700 hover:text-emerald-950 p-1.5 rounded-lg hover:bg-emerald-100 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- ===== 2. MAIN ROLE PERMISSION MATRIX CARD ===== -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden mb-8">
        <!-- Matrix Top Info Bar -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <span class="text-base">📊</span>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Role Access Governance Matrix</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Summary of permission levels across all 9 modules for project participants</p>
                </div>
            </div>

            <!-- Legend Pills -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Full Access
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                    View Only
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                    Assigned Only
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">
                    Custom / Limited
                </span>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                    No Access
                </span>
            </div>
        </div>

        <!-- Table Container with Horizontal Scrolling -->
        <div class="w-full overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-[1100px]">
                <thead>
                    <tr class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-black text-slate-700 uppercase tracking-wider">
                        <!-- Sticky Role Column -->
                        <th class="py-4 pl-6 pr-4 sticky left-0 z-20 bg-slate-100 min-w-[220px] shadow-r">
                            Project Role
                        </th>
                        <th class="py-4 px-3 text-center min-w-[110px]">Project</th>
                        <th class="py-4 px-3 text-center min-w-[130px]">Scope / Details</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">WBS &amp; Tasks</th>
                        <th class="py-4 px-3 text-center min-w-[105px]">Team</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">Budget / Finance</th>
                        <th class="py-4 px-3 text-center min-w-[125px]">Risks &amp; Blockers</th>
                        <th class="py-4 px-3 text-center min-w-[110px]">Approvals</th>
                        <th class="py-4 px-3 text-center min-w-[105px]">Reports</th>
                        <th class="py-4 px-3 text-center min-w-[120px]">Project Settings</th>
                        <th class="py-4 pl-3 pr-6 text-right min-w-[150px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-xs">
                    @forelse($roles as $roleKey => $roleDef)
                        <tr class="hover:bg-slate-50/70 transition-colors group">
                            <!-- Sticky Role Column Cell -->
                            <td class="py-4 pl-6 pr-4 sticky left-0 z-10 bg-white group-hover:bg-slate-50/90 transition-colors shadow-r">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base border shadow-2xs flex-shrink-0 {{ $roleDef['badge'] }}">
                                        {{ $roleDef['icon'] }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm tracking-tight leading-tight">
                                            {{ $roleDef['name'] }}
                                        </h4>
                                        <p class="text-[10px] text-slate-400 font-medium truncate max-w-[160px]" title="{{ $roleDef['description'] }}">
                                            {{ $roleDef['description'] }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- 1. Project Module -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'project'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 2. Scope / Details -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'scope'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 3. WBS & Tasks -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'task'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 4. Team -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'team'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 5. Budget / Finance -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'budget'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 6. Risks & Blockers -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'risks'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 7. Approvals -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'approvals'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 8. Reports -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'reports'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- 9. Project Settings -->
                            <td class="py-4 px-3 text-center">
                                @php $s = \App\Services\RbacService::computeModuleSummary($roleKey, 'settings'); @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10.5px] border inline-block whitespace-nowrap {{ $s['badgeClass'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>

                            <!-- Actions Column Cell -->
                            <td class="py-4 pl-3 pr-6 text-right">
                                <button 
                                    wire:click="openManageModal('{{ $roleKey }}')" 
                                    type="button" 
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black text-[#c3122e] bg-rose-50 hover:bg-[#c3122e] hover:text-white border border-rose-200/90 transition-all cursor-pointer shadow-2xs group-hover:shadow-xs active:scale-95"
                                >
                                    <span>⚙️</span>
                                    <span>Manage Permissions</span>
                                </button>
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

        <!-- Footer Note -->
        <div class="px-6 py-4 bg-slate-50/60 border-t border-slate-100 text-[11px] text-slate-500 font-medium flex items-center justify-between">
            <span>🔒 PMO Super Admin retains full, unrestricted governance access across all operations.</span>
            <span class="font-mono text-slate-400">Total 73 Granular Capabilities</span>
        </div>
    </div>

    <!-- ===== 3. RIGHT-SIDE DRAWER / MODAL FOR GRANULAR PERMISSION CHECKBOXES ===== -->
    @if($showManageDrawer && $selectedRole && isset(\App\Services\RbacService::ROLES[$selectedRole]))
        @php
            $currentRoleInfo = \App\Services\RbacService::ROLES[$selectedRole];
            $hasUnsaved = $this->hasUnsavedChanges;
        @endphp

        <div class="fixed inset-0 z-50 overflow-hidden" x-data x-trap="true">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeManageModal"></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <div class="w-screen max-w-2xl bg-white shadow-2xl flex flex-col border-l border-slate-200 animate-in slide-in-from-right duration-300">
                    
                    <!-- Drawer Header -->
                    <div class="px-6 py-5 bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200 flex items-center justify-between gap-4 flex-shrink-0">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-xl border shadow-2xs flex-shrink-0 {{ $currentRoleInfo['badge'] }}">
                                {{ $currentRoleInfo['icon'] }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-base font-black text-slate-900 tracking-tight leading-tight">
                                        Manage Permissions – {{ $currentRoleInfo['name'] }}
                                    </h3>
                                    @if($hasUnsaved)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">
                                            UNSAVED CHANGES
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 font-medium mt-0.5 truncate">
                                    {{ $currentRoleInfo['description'] }}
                                </p>
                            </div>
                        </div>

                        <!-- Close Button -->
                        <button 
                            wire:click="closeManageModal" 
                            type="button" 
                            class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 border border-slate-200 transition-all flex items-center justify-center flex-shrink-0 cursor-pointer active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- In-Drawer Success Alert -->
                    @if($successToast)
                        <div class="px-6 py-3 bg-emerald-50 border-b border-emerald-200 text-emerald-900 text-xs font-bold flex items-center justify-between animate-in fade-in duration-200">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[10px]">✓</span>
                                <span>{{ $successToast }}</span>
                            </div>
                            <span class="text-[10px] uppercase tracking-wider text-emerald-700 font-extrabold bg-emerald-100 px-2 py-0.5 rounded-md">SAVED</span>
                        </div>
                    @endif

                    <!-- Drawer Scrollable Content: 9 Permission Modules -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6 scrollbar-thin bg-slate-50/50">
                        @foreach($modules as $mKey => $mDef)
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all hover:border-slate-300">
                                <!-- Module Header with Select All / Clear All -->
                                <div class="px-5 py-3.5 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base">{{ $mDef['icon'] }}</span>
                                        <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">
                                            {{ $mDef['name'] }}
                                        </h4>
                                        <span class="px-2 py-0.2 rounded-md text-[10px] font-mono font-bold bg-white text-slate-600 border border-slate-200">
                                            {{ count($mDef['permissions']) }} Actions
                                        </span>
                                    </div>

                                    <!-- Quick Batch Actions -->
                                    <div class="flex items-center gap-1.5">
                                        <button 
                                            wire:click="selectAllForModule('{{ $mKey }}')" 
                                            type="button" 
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-slate-700 hover:text-[#c3122e] hover:bg-rose-50 border border-slate-200 bg-white transition-all cursor-pointer"
                                        >
                                            Select All
                                        </button>
                                        <button 
                                            wire:click="clearAllForModule('{{ $mKey }}')" 
                                            type="button" 
                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold text-slate-700 hover:text-rose-700 hover:bg-rose-50 border border-slate-200 bg-white transition-all cursor-pointer"
                                        >
                                            Clear All
                                        </button>
                                    </div>
                                </div>

                                <!-- Granular Checkboxes Grid -->
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach($mDef['permissions'] as $permCode => $permLabel)
                                        @php
                                            $isChecked = !empty($rolePermissions[$permCode]);
                                        @endphp
                                        <label class="flex items-start gap-3 p-2.5 rounded-xl border transition-all cursor-pointer select-none {{ $isChecked ? 'bg-rose-50/50 border-rose-200 text-slate-900' : 'bg-slate-50/40 border-slate-200/70 text-slate-600 hover:bg-slate-50' }}">
                                            <input 
                                                type="checkbox" 
                                                wire:click="togglePermission('{{ $permCode }}')" 
                                                @checked($isChecked)
                                                class="mt-0.5 w-4 h-4 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300 cursor-pointer"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <span class="text-xs font-bold block leading-tight {{ $isChecked ? 'text-slate-900 font-extrabold' : 'text-slate-700' }}">
                                                    {{ $permLabel }}
                                                </span>
                                                <span class="text-[9.5px] font-mono text-slate-400 block mt-0.5">
                                                    {{ $permCode }}
                                                </span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Drawer Footer Controls -->
                    <div class="p-4 sm:p-5 bg-white border-t border-slate-200 flex items-center justify-between gap-3 flex-shrink-0">
                        <button 
                            wire:click="resetRoleToDefault('{{ $selectedRole }}')" 
                            wire:confirm="Reset all permissions for {{ $currentRoleInfo['name'] }} to system defaults?" 
                            wire:loading.attr="disabled"
                            type="button" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all cursor-pointer disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="resetRoleToDefault">Reset to Default</span>
                            <span wire:loading wire:target="resetRoleToDefault">Resetting...</span>
                        </button>

                        <div class="flex items-center gap-2.5">
                            <button 
                                wire:click="closeManageModal" 
                                type="button" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer"
                            >
                                Cancel
                            </button>

                            <button 
                                wire:click="savePermissions" 
                                wire:loading.attr="disabled"
                                type="button" 
                                class="px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all flex items-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                            >
                                <span wire:loading.remove wire:target="savePermissions" class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Save Permissions</span>
                                </span>
                                <span wire:loading wire:target="savePermissions" class="flex items-center gap-1.5">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                    <span>Saving Changes...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
