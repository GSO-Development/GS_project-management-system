<div x-data="{ currentTab: 'general' }" class="space-y-6 pb-12" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">

    <!-- ── FLOATING SUCCESS NOTIFICATION ── -->
    @if($successToast)
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => { show = false; $wire.set('successToast', null); }, 4000)"
            class="fixed top-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 bg-emerald-600 text-white rounded-2xl shadow-2xl border border-emerald-400/40 animate-in slide-in-from-top-4 duration-300"
        >
            <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center font-bold text-base flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <h5 class="text-xs font-black tracking-tight">Configuration Saved</h5>
                <p class="text-[11px] font-medium text-emerald-100 mt-0.5">{{ $successToast }}</p>
            </div>
            <button @click="show = false; $wire.set('successToast', null)" class="ml-3 text-white/70 hover:text-white cursor-pointer p-1">
                ✕
            </button>
        </div>
    @endif

    <!-- ── 1. EXECUTIVE PAGE HEADER ── -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shrink-0 shadow-2xs text-[#c3122e]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                        System Settings &amp; Configuration
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>v1.2.0 • Active</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    Configure platform branding, WBS calculation engine, SMTP mail relay, and Azure AD single sign-on.
                </p>
            </div>
        </div>

        <!-- Header Action Controls -->
        <div class="flex items-center gap-2.5 flex-shrink-0">
            <button 
                wire:click="resetToSaved" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs hover:shadow-xs transition-all cursor-pointer active:scale-98"
                title="Discard unsaved changes and reload from database"
            >
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Reset</span>
            </button>

            <button 
                wire:click="saveSettings" 
                type="button" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-xs hover:shadow transition-all duration-200 cursor-pointer bg-[#c3122e] hover:bg-[#a50f27] active:scale-[0.98]"
            >
                <span wire:loading.remove wire:target="saveSettings" class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Save System Settings</span>
                </span>
                <span wire:loading wire:target="saveSettings" class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Saving...</span>
                </span>
            </button>
        </div>
    </div>

    <!-- ── 2. MODERN TAB NAVIGATION ── -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/80 overflow-x-auto select-none">
        
        <!-- Tab 1: General & WBS -->
        <button 
            @click="currentTab = 'general'" 
            :class="currentTab === 'general' ? 'bg-white text-[#c3122e] shadow-xs font-extrabold border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'" 
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap"
        >
            <svg class="w-4 h-4" :class="currentTab === 'general' ? 'text-[#c3122e]' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>General &amp; WBS Engine</span>
        </button>

        <!-- Tab 2: SMTP Server -->
        <button 
            @click="currentTab = 'mail'" 
            :class="currentTab === 'mail' ? 'bg-white text-[#c3122e] shadow-xs font-extrabold border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'" 
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap"
        >
            <svg class="w-4 h-4" :class="currentTab === 'mail' ? 'text-[#c3122e]' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>SMTP Mail Relay</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $enableEmailNotifications ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-600' }}">
                {{ $enableEmailNotifications ? 'Active' : 'Disabled' }}
            </span>
        </button>

        <!-- Tab 3: Security & SSO -->
        <button 
            @click="currentTab = 'security'" 
            :class="currentTab === 'security' ? 'bg-white text-[#c3122e] shadow-xs font-extrabold border border-slate-200/60' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'" 
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap"
        >
            <svg class="w-4 h-4" :class="currentTab === 'security' ? 'text-[#c3122e]' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Security, Sessions &amp; Azure SSO</span>
            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold {{ $enableAzureSso ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-200 text-slate-600' }}">
                {{ $enableAzureSso ? 'Azure AD' : 'Standard' }}
            </span>
        </button>
    </div>

    <!-- ── 3. MAIN WORKSPACE GRID ── -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- ══════════════════════════════════════════════
             LEFT / MAIN CONFIGURATION COLUMN (8 COLS)
             ══════════════════════════════════════════════ -->
        <div class="lg:col-span-8 space-y-6">

            <!-- ── TAB 1: GENERAL & WBS ENGINE ── -->
            <div x-show="currentTab === 'general'" class="space-y-6">

                <!-- Card 1.1: Platform Identity & Branding -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] flex items-center justify-center font-bold text-sm">
                                🏢
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm">Platform Identity &amp; Corporate Branding</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Enterprise naming, branding header and organization code parameters.</p>
                            </div>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                            {{ $appName }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Application Name -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Application Display Name <span class="text-[#c3122e]">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="appName" 
                                class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Displayed on the sidebar branding, browser tabs, and notification headers.</p>
                            @error('appName') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Organization Code -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Organization Code Identifier
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    value="NEXUS-ORG" 
                                    readonly 
                                    class="w-full pl-3.5 pr-8 py-2.5 text-xs font-mono font-bold text-slate-500 bg-slate-100/90 border border-slate-200 rounded-xl cursor-not-allowed select-none"
                                >
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400" title="Locked by system security profile">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Immutable top-level organization key for George Steuart corporate tenants.</p>
                        </div>

                        <!-- Base Timezone -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">System Timezone</label>
                            <input 
                                type="text" 
                                value="Asia/Colombo (UTC +05:30)" 
                                readonly 
                                class="w-full px-3.5 py-2.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Standard corporate operating timezone for project schedules and notifications.</p>
                        </div>

                        <!-- System Currency -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform Base Currency</label>
                            <input 
                                type="text" 
                                value="LKR — Sri Lankan Rupee (Rs.)" 
                                readonly 
                                class="w-full px-3.5 py-2.5 text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-xl cursor-not-allowed"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Default denomination applied across budget tracking &amp; financial reports.</p>
                        </div>
                    </div>
                </div>

                <!-- Card 1.2: WBS Progress Calculation Engine -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                📊
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm">WBS Progress Calculation Engine</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Controls how deliverable percentages roll up hierarchically through parents into overall project progress.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $wbsCalculationMethod === 'weighted' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                            {{ $wbsCalculationMethod === 'weighted' ? 'Weighted Method Active' : 'Equal Weight Active' }}
                        </span>
                    </div>

                    <!-- Strategy Selection Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        <!-- Option 1: Weighted Progress -->
                        <div 
                            wire:click="$set('wbsCalculationMethod', 'weighted')" 
                            class="p-4.5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between {{ $wbsCalculationMethod === 'weighted' ? 'border-[#c3122e] bg-rose-50/20 shadow-xs' : 'border-slate-200/80 bg-white hover:border-slate-300' }}"
                        >
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span>Weighted Progress Strategy</span>
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $wbsCalculationMethod === 'weighted' ? 'bg-[#c3122e] text-white' : 'bg-slate-100 text-slate-600' }}">
                                        Recommended
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                    Each deliverable contributes proportionally to its assigned task weight relative to the sum of sibling weights.
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200/60 font-mono text-[10.5px] text-slate-700 bg-white/80 p-2.5 rounded-xl border border-slate-100">
                                <code>Progress = Σ(Child Progress × Weight) / Σ(Weights)</code>
                            </div>
                        </div>

                        <!-- Option 2: Equal Weight -->
                        <div 
                            wire:click="$set('wbsCalculationMethod', 'equal')" 
                            class="p-4.5 rounded-2xl border-2 transition-all cursor-pointer flex flex-col justify-between {{ $wbsCalculationMethod === 'equal' ? 'border-[#c3122e] bg-rose-50/20 shadow-xs' : 'border-slate-200/80 bg-white hover:border-slate-300' }}"
                        >
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5">
                                        <span>Equal Distribution Strategy</span>
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $wbsCalculationMethod === 'equal' ? 'bg-[#c3122e] text-white' : 'bg-slate-100 text-slate-600' }}">
                                        Simple Split
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                                    Ignores individual weight allocations. Every sub-task contributes uniformly to the parent package.
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-200/60 font-mono text-[10.5px] text-slate-700 bg-white/80 p-2.5 rounded-xl border border-slate-100">
                                <code>Progress = Σ(Child Progress) / Total Subtasks</code>
                            </div>
                        </div>

                    </div>
                </div>

            </div>


            <!-- ── TAB 2: SMTP MAIL RELAY ── -->
            <div x-show="currentTab === 'mail'" class="space-y-6" style="display:none">

                <!-- Card 2.1: SMTP Gateway Credentials -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                                ✉️
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm">SMTP Gateway &amp; Mail Dispatcher</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Relay parameters for PMO nudges, deadline alerts &amp; approval notifications.</p>
                            </div>
                        </div>

                        <!-- Toggle Enable Notifications Switch -->
                        <div class="flex items-center gap-3 self-start sm:self-auto bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200">
                            <span class="text-xs font-bold text-slate-700">Email Notifications:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="enableEmailNotifications" class="sr-only peer">
                                <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-[#c3122e]"></div>
                            </label>
                            <span class="text-xs font-bold {{ $enableEmailNotifications ? 'text-emerald-600' : 'text-slate-400' }}">
                                {{ $enableEmailNotifications ? 'Enabled' : 'Paused' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Host & Port Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    SMTP Server Host <span class="text-[#c3122e]">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="smtpHost" 
                                    placeholder="smtp.office365.com or smtp.gmail.com" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                                @error('smtpHost') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Port <span class="text-[#c3122e]">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    wire:model="smtpPort" 
                                    placeholder="587" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                                @error('smtpPort') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Username & Encryption Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    SMTP Username / Account
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="smtpUser" 
                                    placeholder="notifications@georgesteuart.com" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Encryption Protocol
                                </label>
                                <select 
                                    wire:model="smtpEncryption" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all cursor-pointer"
                                >
                                    <option value="tls">TLS (Standard Port 587)</option>
                                    <option value="ssl">SSL (Port 465)</option>
                                    <option value="none">None / Unencrypted</option>
                                </select>
                            </div>
                        </div>

                        <!-- Password with Show/Hide Toggle -->
                        <div x-data="{ showPass: false }">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                SMTP Password / App Secret
                            </label>
                            <div class="relative">
                                <input 
                                    :type="showPass ? 'text' : 'password'" 
                                    wire:model="smtpPass" 
                                    placeholder="App password or relay secret key" 
                                    class="w-full pl-3.5 pr-12 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                                <button 
                                    type="button" 
                                    @click="showPass = !showPass" 
                                    class="absolute inset-y-0 right-0 px-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer"
                                >
                                    <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Mail Sender Envelope Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Mail From Address <span class="text-[#c3122e]">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="mailFromAddress" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                                @error('mailFromAddress') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Mail From Name <span class="text-[#c3122e]">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="mailFromName" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all"
                                >
                                @error('mailFromName') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2.2: Live SMTP Relay Verification -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-slate-100 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            🧪
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Live Mail Gateway Connectivity Test</h3>
                            <p class="text-[11px] text-slate-400 font-medium">Send a live test verification email to confirm firewall, credentials and DNS relay status.</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="flex-1">
                            <input 
                                type="email" 
                                wire:model="testEmailRecipient" 
                                placeholder="Enter recipient email (e.g. admin@georgesteuart.com)" 
                                class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-600 transition-all font-mono"
                            >
                        </div>
                        <button 
                            wire:click="sendTestEmail" 
                            type="button" 
                            class="inline-flex items-center justify-center gap-2 px-4.5 py-2.5 rounded-xl text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 shadow-xs hover:shadow transition-all cursor-pointer active:scale-98 shrink-0"
                        >
                            <span wire:loading.remove wire:target="sendTestEmail" class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Send Test Email</span>
                            </span>
                            <span wire:loading wire:target="sendTestEmail" class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Dispatching...</span>
                            </span>
                        </button>
                    </div>

                    @if($testMailStatus)
                        <div class="mt-3.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $testMailStatus }}</span>
                        </div>
                    @endif

                    @if($testMailError)
                        <div class="mt-3.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <div class="min-w-0">
                                <div class="font-black">Connection Warning:</div>
                                <div class="font-normal font-mono text-[11px] mt-0.5 break-all">{{ $testMailError }}</div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>


            <!-- ── TAB 3: SECURITY, SESSIONS & AZURE SSO ── -->
            <div x-show="currentTab === 'security'" class="space-y-6" style="display:none">

                <!-- Card 3.1: Session & Password Policies -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-slate-100 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                            🛡️
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Session Inactivity &amp; Password Governance</h3>
                            <p class="text-[11px] text-slate-400 font-medium">Standard corporate compliance rules for active session tokens and user credentials.</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <!-- Session Timeout Input + Quick Presets -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-bold text-slate-700">
                                    Session Inactivity Timeout (Minutes) <span class="text-[#c3122e]">*</span>
                                </label>
                                <span class="text-xs font-mono font-bold text-slate-500">
                                    {{ round($sessionTimeout / 60, 1) }} Hours
                                </span>
                            </div>
                            <div class="flex items-center gap-3">
                                <input 
                                    type="number" 
                                    wire:model="sessionTimeout" 
                                    class="w-32 px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all font-mono"
                                >
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <button type="button" wire:click="$set('sessionTimeout', 30)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $sessionTimeout == 30 ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">30m</button>
                                    <button type="button" wire:click="$set('sessionTimeout', 60)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $sessionTimeout == 60 ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">60m (1h)</button>
                                    <button type="button" wire:click="$set('sessionTimeout', 120)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $sessionTimeout == 120 ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">120m (2h)</button>
                                    <button type="button" wire:click="$set('sessionTimeout', 240)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $sessionTimeout == 240 ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">240m (4h)</button>
                                    <button type="button" wire:click="$set('sessionTimeout', 480)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $sessionTimeout == 480 ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">480m (8h)</button>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Users will be automatically logged out after this duration of inactivity.</p>
                            @error('sessionTimeout') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password Complexity Switch -->
                        <div class="pt-4 border-t border-slate-100 flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">Enforce Strong Password Complexity</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    Requires all user accounts to use passwords with at least 8 characters, uppercase, lowercase, numerical digits, and symbol characters.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                                <input type="checkbox" wire:model="enforcePasswordComplexity" class="sr-only peer">
                                <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-[#c3122e]"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Card 3.2: Microsoft Azure AD SSO -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-2xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                🔑
                            </div>
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-sm">Microsoft Azure Active Directory SSO</h3>
                                <p class="text-[11px] text-slate-400 font-medium">Authenticate staff using their George Steuart corporate Office 365 credentials.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 self-start sm:self-auto bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200">
                            <span class="text-xs font-bold text-slate-700">Azure SSO:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="enableAzureSso" class="sr-only peer">
                                <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-xs font-bold {{ $enableAzureSso ? 'text-blue-600' : 'text-slate-400' }}">
                                {{ $enableAzureSso ? 'Active' : 'Disabled' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Azure Tenant ID
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="azureTenantId" 
                                    placeholder="common or 8-4-4-4-12 GUID" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-mono"
                                >
                                <p class="text-[11px] text-slate-400 mt-1">Set to <code>common</code> for multi-tenant or your corporate tenant ID.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    Application (Client) ID
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="azureClientId" 
                                    placeholder="e.g. 00000000-0000-0000-0000-000000000000" 
                                    class="w-full px-3.5 py-2.5 text-xs font-bold text-slate-800 bg-slate-50/70 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-mono"
                                >
                                <p class="text-[11px] text-slate-400 mt-1">Registered App ID registered in Microsoft Entra ID portal.</p>
                            </div>
                        </div>

                        <!-- Azure Redirect URI Helper Box -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wider block mb-1">
                                Authorized Azure Redirect URI:
                            </span>
                            <div class="flex items-center justify-between gap-2 font-mono text-xs text-slate-800 bg-white px-3 py-2 rounded-lg border border-slate-200">
                                <span class="truncate">{{ url('/auth/azure/callback') }}</span>
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 shrink-0">Copy for Azure App</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <!-- ══════════════════════════════════════════════
             RIGHT / TELEMETRY & DIAGNOSTICS COLUMN (4 COLS)
             ══════════════════════════════════════════════ -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card R1: System Environment & Runtime -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs">
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-3.5">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">System Environment</span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ strtoupper(app()->environment()) }}</span>
                    </span>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Framework Version</span>
                        <span class="font-bold text-slate-800 font-mono">Laravel v{{ app()->version() }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">PHP Engine</span>
                        <span class="font-bold text-slate-800 font-mono">PHP {{ phpversion() }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Database Connection</span>
                        <span class="font-bold text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>MySQL Connected</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-400 font-medium">Mail Dispatcher</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $smtpHost }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-400 font-medium">Server Clock</span>
                        <span class="font-bold text-slate-800 font-mono">{{ now()->format('H:i T') }}</span>
                    </div>
                </div>
            </div>

            <!-- Card R2: Active Configuration Snapshot -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block pb-3 border-b border-slate-100 mb-3.5">
                    Live Configuration Snapshot
                </span>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">App Identity</span>
                        <span class="font-bold text-slate-900 truncate max-w-[150px]">{{ $appName }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">WBS Calculation</span>
                        <span class="font-bold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded border border-rose-200 text-[11px]">
                            {{ ucfirst($wbsCalculationMethod) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Email Dispatcher</span>
                        <span class="font-bold text-[11px] {{ $enableEmailNotifications ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-slate-500 bg-slate-100' }} px-2 py-0.5 rounded">
                            {{ $enableEmailNotifications ? 'Active Relay' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Password Policy</span>
                        <span class="font-bold text-[11px] {{ $enforcePasswordComplexity ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-slate-500 bg-slate-100' }} px-2 py-0.5 rounded">
                            {{ $enforcePasswordComplexity ? 'Complex Rules' : 'Standard' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">Azure AD SSO</span>
                        <span class="font-bold text-[11px] {{ $enableAzureSso ? 'text-blue-700 bg-blue-50 border border-blue-200' : 'text-slate-500 bg-slate-100' }} px-2 py-0.5 rounded">
                            {{ $enableAzureSso ? 'Ready (common)' : 'Disabled' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card R3: Quick Administration Links -->
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">🛡️</span>
                    <h4 class="text-xs font-extrabold tracking-tight">Security &amp; Audit Logs</h4>
                </div>
                <p class="text-[11px] text-slate-300 leading-relaxed font-medium">
                    Every modification to system settings is immutably recorded with actor timestamps and IP logs.
                </p>
                <div class="pt-2 flex flex-col gap-2">
                    <a 
                        href="{{ route('audit-logs.index') }}" 
                        class="w-full text-center px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-white/10 hover:bg-white/20 border border-white/10 transition-all no-underline"
                    >
                        View Audit Log Trail →
                    </a>
                    <a 
                        href="{{ route('roles-permissions.index') }}" 
                        class="w-full text-center px-3.5 py-2 rounded-xl text-xs font-bold text-slate-300 hover:text-white bg-transparent hover:bg-white/5 transition-all no-underline"
                    >
                        Manage Roles &amp; Permissions
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
