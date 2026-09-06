<div x-data="{ currentTab: 'general' }" class="space-y-6">
    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (SYSTEM SETTINGS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl p-5 sm:p-6 lg:px-8 lg:py-4 text-white mb-6 min-h-[160px] lg:h-[160px] flex flex-col justify-center" style="background: #2b040a;">
        <!-- Full Banner Background Image (Luxury Crimson & Gold Skyline Panorama) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="System Settings & Configuration Banner" 
                class="w-full h-full object-cover object-right opacity-90"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#140205] via-[#24030a]/90 to-transparent lg:w-3/5"></div>
            <!-- Right Dark Vignette over Sunset -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/50 via-black/20 to-transparent hidden lg:block"></div>
            <!-- Depth Vignettes -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            <!-- Left Side: 3D Settings Icon + Title + Meta -->
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-2.5 sm:p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight drop-shadow-md">
                            System Settings &amp; Configuration
                        </h1>
                        <span class="px-3 py-0.5 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>⚙️</span>
                            <span>Global Administration</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-300 mt-1 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Enterprise Infrastructure</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Configure organization branding, calculation engines, SMTP notifications, and Azure AD parameters</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Setting Tab Headers -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-100 rounded-2xl max-w-max border border-slate-200/50">
        <button @click="currentTab = 'general'" :class="currentTab === 'general' ? 'bg-white text-[#c3122e] shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
            💼 General &amp; WBS
        </button>
        <button @click="currentTab = 'mail'" :class="currentTab === 'mail' ? 'bg-white text-[#c3122e] shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
            ✉️ SMTP Server
        </button>
        <button @click="currentTab = 'security'" :class="currentTab === 'security' ? 'bg-white text-[#c3122e] shadow-2xs font-extrabold' : 'text-slate-500 hover:text-slate-800'" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
            🔒 Security &amp; SSO
        </button>
    </div>

    <!-- Form container -->
    <div class="card max-w-3xl">
        <form wire:submit="saveSettings" class="space-y-6">
            
            <!-- 1. GENERAL & WBS TAB -->
            <div x-show="currentTab === 'general'" class="space-y-6">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm mb-3">General Application Identity</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-bold">Application Name</label>
                            <input type="text" wire:model="appName" class="form-input text-xs font-semibold">
                            <p class="text-[10px] text-slate-400 mt-1">Changes the title and main headers of the NexusPM platform.</p>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-bold">Organization Code</label>
                            <input type="text" value="NEXUS-ORG" readonly class="form-input bg-slate-50 text-slate-500 font-mono text-xs">
                            <p class="text-[10px] text-slate-400 mt-1">Locked organization code identifier.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h3 class="font-extrabold text-slate-900 text-sm mb-3">WBS Progress Calculation Engine</h3>
                    <div class="form-group">
                        <label class="form-label font-bold">Default Formula Strategy</label>
                        <select wire:model="wbsCalculationMethod" class="form-select text-xs font-semibold cursor-pointer">
                            <option value="weighted">Weighted Progress (Formula: Sum(Child Progress * Weight) / Sum(Weights))</option>
                            <option value="equal">Equal Weight (Formula: Sum(Child Progress) / Number of Children)</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Calculates parent progress automatically through all ancestor levels up to overall project progress.</p>
                    </div>
                </div>
            </div>

            <!-- 2. SMTP MAIL TAB -->
            <div x-show="currentTab === 'mail'" class="space-y-6" style="display:none">
                <div>
                    <div class="flex items-center justify-between mb-3.5">
                        <h3 class="font-extrabold text-slate-900 text-sm">SMTP Server Settings</h3>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                            <input type="checkbox" wire:model="enableEmailNotifications" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                            <span>Enable Notifications</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-bold">SMTP Host</label>
                            <input type="text" wire:model="smtpHost" placeholder="e.g. smtp.office365.com" class="form-input text-xs font-semibold">
                        </div>
                        <div class="form-group">
                            <label class="form-label font-bold">SMTP Port</label>
                            <input type="number" wire:model="smtpPort" placeholder="e.g. 587" class="form-input text-xs font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-bold">SMTP Username</label>
                            <input type="text" wire:model="smtpUser" placeholder="prathibhajay098@gmail.com" class="form-input text-xs font-semibold">
                        </div>
                        <div class="form-group">
                            <label class="form-label font-bold">SMTP Encryption</label>
                            <select wire:model="smtpEncryption" class="form-select text-xs font-semibold cursor-pointer">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-4" x-data="{ showPass: false }">
                        <label class="form-label font-bold">SMTP Password</label>
                        <div class="relative">
                            <input
                                :type="showPass ? 'text' : 'password'"
                                wire:model="smtpPass"
                                placeholder="App password"
                                class="form-input text-xs font-semibold pr-12"
                            >
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 hover:text-slate-700 transition-colors"
                                tabindex="-1"
                            >
                                <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100">
                        <div class="form-group">
                            <label class="form-label font-bold">Mail From Address</label>
                            <input type="email" wire:model="mailFromAddress" class="form-input text-xs font-semibold">
                        </div>
                        <div class="form-group">
                            <label class="form-label font-bold">Mail From Name</label>
                            <input type="text" wire:model="mailFromName" class="form-input text-xs font-semibold">
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. SECURITY & SSO TAB -->
            <div x-show="currentTab === 'security'" class="space-y-6" style="display:none">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm mb-3">Security &amp; Expiry Configuration</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-bold">Session Expiry Timeout (Minutes)</label>
                            <input type="number" wire:model="sessionTimeout" class="form-input text-xs font-semibold">
                        </div>

                        <div class="form-group flex items-center h-full pt-6">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer text-xs font-bold text-slate-700">
                                <input type="checkbox" wire:model="enforcePasswordComplexity" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                                <span>Enforce Password Complexity Rules</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-3.5">
                        <h3 class="font-extrabold text-slate-900 text-sm">Microsoft Azure AD SSO Settings</h3>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                            <input type="checkbox" wire:model="enableAzureSso" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 cursor-pointer">
                            <span>Enable Azure SSO</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label font-bold">Tenant ID</label>
                            <input type="text" wire:model="azureTenantId" placeholder="e.g. common" class="form-input text-xs font-semibold font-mono">
                        </div>
                        <div class="form-group">
                            <label class="form-label font-bold">Client ID</label>
                            <input type="text" wire:model="azureClientId" placeholder="e.g. az-client-id" class="form-input text-xs font-semibold font-mono">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Azure SSO parameters allow users to log in securely using their George Steuart corporate credentials.</p>
                </div>
            </div>

            <!-- Footer Save Action Button -->
            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary px-6 active:scale-98">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save System Settings
                </button>
            </div>
        </form>
    </div>
</div>
