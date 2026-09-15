<x-guest-layout>
<div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-4 sm:p-6 md:p-10 font-sans relative overflow-hidden" style="background: radial-gradient(circle at 50% 0%, #ffffff 0%, #f1f5f9 100%);">
    
    <!-- Background Ambient Soft Glow Orbs -->
    <div class="absolute -top-32 -left-32 w-[550px] h-[550px] bg-[#c3122e]/8 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[550px] h-[550px] bg-slate-300/40 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="w-full max-w-md z-10">
        <!-- Logo & Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#800a1d] shadow-xl shadow-[#c3122e]/30 border border-white/20 mb-4" style="width: 64px; height: 64px;">
                <span class="text-white font-black text-2xl tracking-widest">GS</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Reset Your Password</h1>
            <p class="text-xs text-slate-500 font-medium mt-2 leading-relaxed max-w-sm mx-auto">
                Forgot your password? Enter your corporate email address below and we will send you a secure password reset link.
            </p>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-300/60 p-6 sm:p-8 relative">
            
            <!-- Session Status Alert -->
            @if (session('status'))
                <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-xs font-bold text-emerald-800 flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="leading-relaxed">{{ session('status') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-extrabold text-slate-800 mb-1.5">Email Address <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4 text-slate-400" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="name@georgesteuart.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none"
                        >
                    </div>
                    @error('email')
                        <p class="text-[11px] text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                            <span>⚠️</span> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Primary Submit Button -->
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-3.5 px-4 rounded-xl text-xs font-black text-white bg-gradient-to-r from-[#c3122e] to-[#9a091d] hover:from-[#a50d24] hover:to-[#800617] shadow-lg shadow-[#c3122e]/30 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                >
                    <span x-show="!loading">📧 Email Password Reset Link</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" width="16" height="16" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Sending Link...
                    </span>
                </button>
            </form>

            <!-- Back to Login Footer -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-xs font-extrabold text-[#c3122e] hover:text-[#9a091d] hover:underline transition-all">
                    <svg class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Sign In</span>
                </a>
            </div>
        </div>

    </div>
</div>
</x-guest-layout>
