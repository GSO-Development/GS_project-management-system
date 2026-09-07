<div x-data="{ currentTab: 'general' }" class="space-y-6">
    <!-- Clean Standard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                System Settings &amp; Configuration
            </h1>
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
