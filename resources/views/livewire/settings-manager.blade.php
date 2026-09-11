<div class="max-w-4xl space-y-6" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">

    <!-- ── CLEAN PAGE HEADER ── -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                SMTP Settings
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Manage your mail server configuration and verify live email delivery.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button 
                wire:click="saveSettings" 
                type="button" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-xs hover:shadow transition-all duration-200 cursor-pointer bg-[#c3122e] hover:bg-[#a50f27] active:scale-[0.98]"
            >
                <span wire:loading.remove wire:target="saveSettings" class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Save Settings</span>
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

    <!-- ── SUCCESS ALERT BANNER ── -->
    @if($successToast)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs">✓</div>
                <span>{{ $successToast }}</span>
            </div>
            <button wire:click="$set('successToast', null)" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">✕</button>
        </div>
    @endif

    <!-- ── MAIN CONFIGURATION CARD ── -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 sm:p-8 space-y-8">

        <!-- SECTION 1: MAIL SERVER CONFIGURATION -->
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-6">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Mail Server Credentials</h2>
                    <p class="text-[11px] text-slate-400 font-medium">SMTP server host, credentials, encryption protocol, and envelope details.</p>
                </div>

                <!-- Email Notifications Toggle Switch -->
                <div class="flex items-center gap-3 select-none">
                    <span class="text-xs font-bold text-slate-700">Notifications:</span>
                    <button 
                        type="button" 
                        wire:click="$toggle('enableEmailNotifications')" 
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $enableEmailNotifications ? 'bg-emerald-500' : 'bg-slate-300' }}" 
                        role="switch" 
                        aria-checked="{{ $enableEmailNotifications ? 'true' : 'false' }}"
                        title="Toggle Email Notifications"
                    >
                        <span 
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $enableEmailNotifications ? 'translate-x-5' : 'translate-x-0' }}"
                        ></span>
                    </button>
                    <span class="text-xs font-bold {{ $enableEmailNotifications ? 'text-emerald-600' : 'text-slate-400' }}">
                        {{ $enableEmailNotifications ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
            </div>

            <div class="space-y-5">
                <!-- Host & Port Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">SMTP Host</label>
                        <input type="text" wire:model="smtpHost" placeholder="e.g. smtp.gmail.com" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                        @error('smtpHost') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Port</label>
                        <input type="number" wire:model="smtpPort" placeholder="587" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                        @error('smtpPort') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Username & Password Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">SMTP Username</label>
                        <input type="text" wire:model="smtpUser" placeholder="username@example.com" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                    </div>

                    <div x-data="{ showPass: false }">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">SMTP Password</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" wire:model="smtpPass" placeholder="App Password / Secret" class="w-full pl-3.5 pr-10 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Encryption & Sender Details Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Encryption</label>
                        <select wire:model="smtpEncryption" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all cursor-pointer">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="none">None</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">From Address</label>
                        <input type="email" wire:model="mailFromAddress" placeholder="noreply@example.com" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                        @error('mailFromAddress') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">From Name</label>
                        <input type="text" wire:model="mailFromName" placeholder="GS NexusPM Engine" class="w-full px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#c3122e]/20 focus:border-[#c3122e] transition-all">
                        @error('mailFromName') <span class="text-[11px] text-rose-600 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: TEST EMAIL DELIVERY -->
        <div class="pt-6 border-t border-slate-100">
            <div class="pb-3 mb-4">
                <h2 class="text-sm font-extrabold text-slate-900">Send Test Email</h2>
                <p class="text-[11px] text-slate-400 font-medium">Test your mail configuration to confirm delivery.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <input 
                    type="email" 
                    wire:model.live.debounce.300ms="testEmailRecipient" 
                    placeholder="Recipient email address..." 
                    class="flex-1 px-3.5 py-2.5 text-xs font-semibold text-slate-800 bg-slate-50/60 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 transition-all"
                >
                <button 
                    wire:click="sendTestEmail" 
                    type="button" 
                    class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all cursor-pointer active:scale-98 shrink-0 flex items-center justify-center gap-1.5"
                >
                    <span wire:loading.remove wire:target="sendTestEmail">Send Test</span>
                    <span wire:loading wire:target="sendTestEmail" class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Sending...</span>
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
                    <span class="break-all">{{ $testMailError }}</span>
                </div>
            @endif
        </div>

    </div>

</div>
