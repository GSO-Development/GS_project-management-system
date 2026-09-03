<section>
    <div class="flex items-center gap-3.5 pb-5 border-b border-slate-100">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-2xs" style="background: #fdf2f2; color: #c3122e; border: 1px solid #fecaca;">
            👤
        </div>
        <div>
            <h3 class="text-base font-black text-slate-900 tracking-tight">Personal Information</h3>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Update your corporate display name, contact phone number, and official email address.</p>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Full Name <span class="text-[#c3122e]">*</span>
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input 
                        id="name" 
                        name="name" 
                        type="text" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        placeholder="John Doe"
                    />
                </div>
                <x-input-error class="mt-1.5 text-xs text-rose-600 font-semibold" :messages="$errors->get('name')" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Official Email <span class="text-[#c3122e]">*</span>
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username" 
                        placeholder="name@georgesteuart.com"
                    />
                </div>
                <x-input-error class="mt-1.5 text-xs text-rose-600 font-semibold" :messages="$errors->get('email')" />
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Contact Phone Number <span class="text-slate-400 font-normal">(Optional)</span>
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <input 
                        id="phone_number" 
                        name="phone_number" 
                        type="tel" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all" 
                        value="{{ old('phone_number', $user->phone_number) }}" 
                        placeholder="+94 77 123 4567"
                    />
                </div>
                <x-input-error class="mt-1.5 text-xs text-rose-600 font-semibold" :messages="$errors->get('phone_number')" />
            </div>

            <!-- Assigned Subsidiary (Informational) -->
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-1.5">
                    Assigned Business Unit
                </label>
                <div class="relative rounded-xl shadow-2xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span>🏢</span>
                    </div>
                    <input 
                        type="text" 
                        class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-500 cursor-not-allowed" 
                        value="{{ $user->subsidiary->name ?? 'George Steuart Group' }}" 
                        disabled 
                        readonly
                    />
                </div>
                <p class="text-[10.5px] text-slate-400 font-medium mt-1">Managed by PMO / System Administrator.</p>
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-2">
                    <span>⚠️</span>
                    <span>Your email address is unverified.</span>
                </div>
                <button form="send-verification" class="text-xs font-bold text-[#c3122e] hover:underline cursor-pointer">
                    Resend Verification Link
                </button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-bold">
                    ✓ A new verification link has been sent to your email address.
                </div>
            @endif
        @endif

        <div class="flex items-center justify-between pt-2 border-t border-slate-100 flex-wrap gap-3">
            <button 
                type="submit" 
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-black text-white shadow-md shadow-[#c3122e]/20 hover:opacity-95 active:scale-95 transition-all cursor-pointer"
                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Save Profile Changes</span>
            </button>

            @if (session('status') === 'profile-updated')
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
                    <span>Profile updated successfully!</span>
                </div>
            @endif
        </div>
    </form>
</section>
