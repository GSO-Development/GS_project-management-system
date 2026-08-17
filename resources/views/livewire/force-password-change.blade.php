<div class="min-h-screen flex items-center justify-center p-4 bg-slate-900 relative overflow-hidden" style="background: radial-gradient(circle at 50% 30%, #2a080c 0%, #120508 60%, #080204 100%); font-family: 'Inter', sans-serif;">

    <!-- Background Decorative Glows -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-[#c3122e]/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-[#c3122e]/10 blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#800a1d] shadow-xl shadow-[#c3122e]/30 border border-white/20 mb-4">
                <span class="text-white font-black text-2xl tracking-widest">GS</span>
            </div>
            <h2 class="text-2xl font-black text-white tracking-tight drop-shadow-md">First-Time Password Change</h2>
            <p class="text-xs text-slate-300 font-medium mt-1.5 leading-relaxed max-w-xs mx-auto">
                Your account was created by PMO Admin. Please set a new password to secure your account.
            </p>
        </div>

        <!-- Password Change Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl border border-white/20 shadow-2xl p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-bold text-base flex-shrink-0">
                    🔒
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm">Security Credentials Update</h3>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Logged in as: <strong class="text-slate-800">{{ auth()->user()->email }}</strong></p>
                </div>
            </div>

            <form wire:submit="changePassword" class="space-y-4">

                <!-- New Password -->
                <div class="form-group">
                    <label class="block text-xs font-extrabold text-slate-800 mb-1.5">New Password <span class="text-rose-500">*</span></label>
                    <input type="password" wire:model="new_password" placeholder="Minimum 8 characters" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none transition-all" required>
                    @error('new_password') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="form-group">
                    <label class="block text-xs font-extrabold text-slate-800 mb-1.5">Confirm New Password <span class="text-rose-500">*</span></label>
                    <input type="password" wire:model="new_password_confirmation" placeholder="Re-enter new password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none transition-all" required>
                    @error('new_password_confirmation') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-lg shadow-[#c3122e]/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>🔒 Save New Password &amp; Continue</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-400 hover:text-slate-600 underline">
                        Cancel &amp; Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
