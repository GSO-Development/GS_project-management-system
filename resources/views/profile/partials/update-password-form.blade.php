<section>
    <div class="flex items-center gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-2xs" style="background: #fdf2f2; color: #c3122e; border: 1px solid #fecaca;">
            🔒
        </div>
        <div>
            <h3 class="text-base font-black text-slate-900 tracking-tight">Account Security &amp; Password</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Ensure your account is protected with a long, random corporate password to maintain compliance.</p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                Current Password <span class="text-[#c3122e]">*</span>
            </label>
            <div class="relative rounded-xl shadow-2xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input 
                    id="update_password_current_password" 
                    name="current_password" 
                    type="password" 
                    class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                    autocomplete="current-password" 
                    placeholder="Enter current password"
                />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- New Password -->
            <div>
                <label for="update_password_password" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    New Password <span class="text-[#c3122e]">*</span>
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <input 
                        id="update_password_password" 
                        name="password" 
                        type="password" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                        autocomplete="new-password" 
                        placeholder="Enter new password"
                    />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Confirm New Password <span class="text-[#c3122e]">*</span>
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <input 
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                        autocomplete="new-password" 
                        placeholder="Re-enter new password"
                    />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-600 font-semibold" />
            </div>
        </div>

        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-500 font-medium flex items-center gap-2">
            <span>💡</span>
            <span>Tip: Use at least 8 characters, combining uppercase and lowercase letters, numbers, and special symbols.</span>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-slate-100 flex-wrap gap-3">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md shadow-[#c3122e]/20 hover:opacity-95 active:scale-95 transition-all cursor-pointer"
                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Update Password</span>
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-black text-emerald-700"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span>Password updated securely!</span>
                </div>
            @endif
        </div>
    </form>
</section>
