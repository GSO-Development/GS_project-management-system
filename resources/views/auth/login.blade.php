<x-guest-layout>
<div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center p-4 sm:p-6 md:p-10 font-sans relative overflow-hidden" style="background: radial-gradient(circle at 50% 0%, #ffffff 0%, #f1f5f9 100%);">
    
    <!-- Background Ambient Soft Glow Orbs -->
    <div class="absolute -top-32 -left-32 w-[550px] h-[550px] bg-[#c3122e]/8 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[550px] h-[550px] bg-slate-300/40 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Outer Card Container -->
    <div class="w-full max-w-5xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl shadow-slate-300/60 overflow-hidden flex flex-col lg:flex-row min-h-[620px] relative z-10">

        <!-- ================= LEFT PANEL: Cinematic Skyscraper Hero ================= -->
        <div class="lg:w-1/2 text-white p-8 sm:p-10 xl:p-12 relative flex flex-col justify-between overflow-hidden bg-cover bg-center min-h-[380px] lg:min-h-full"
             style="background-image: url('{{ asset('images/nexuspm_login_cinematic_hero.png') }}');">
            
            <!-- Dark Executive Overlay Vignette -->
            <div class="absolute inset-0 z-0" style="background: linear-gradient(180deg, rgba(7, 10, 18, 0.45) 0%, rgba(18, 3, 7, 0.88) 100%);"></div>

            <!-- Top Header Badge -->
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#c3122e] flex items-center justify-center shadow-md shadow-[#c3122e]/50 border border-white/20">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-black text-white tracking-tight">GS NexusPM</span>
                </div>

                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-black/50 border border-white/20 text-amber-300 backdrop-blur-md">
                    EST. 1835
                </span>
            </div>

            <!-- Center Headline -->
            <div class="relative z-10 my-auto py-6 space-y-3 max-w-md">
                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-[#c3122e]/30 border border-[#e8556a]/40 text-rose-200 inline-block">
                    George Steuart Group
                </span>
                <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight tracking-tight" style="font-family: Georgia, 'Times New Roman', serif;">
                    Enterprise Project Intelligence.
                </h1>
                <p class="text-xs text-slate-300/90 leading-relaxed font-medium">
                    Centralized governance, WBS progress tracking, and audit-level compliance for all subsidiary operations.
                </p>
            </div>

            <!-- Bottom Status Card -->
            <div class="relative z-10 p-3.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md flex items-center justify-between text-xs font-extrabold text-white">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>5 Active Subsidiaries</span>
                </div>
                <div class="h-3 w-px bg-white/20"></div>
                <div class="text-slate-300 font-medium text-[11px]">
                    100% Audit Logged
                </div>
            </div>
        </div>

        <!-- ================= RIGHT PANEL: Pristine White Login Form ================= -->
        <div class="lg:w-1/2 bg-white p-8 sm:p-12 flex flex-col justify-center">
            <div class="w-full max-w-sm mx-auto space-y-6">
                
                <!-- Brand Form Header -->
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-[#fdf2f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] mb-4 shadow-2xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                        Sign In
                    </h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">
                        Access your GS NexusPM Executive Workspace
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-2" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="text-xs font-extrabold text-slate-800 mb-1.5 block">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                autocomplete="username"
                                placeholder="name@georgesteuart.com"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none"
                            >
                        </div>
                        @error('email')
                            <p class="text-[11px] text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="text-xs font-extrabold text-slate-800">Password</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-extrabold text-[#c3122e] hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 transition-all outline-none"
                            >
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1 cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="pt-0.5">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-700 font-bold select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 focus:ring-2 cursor-pointer">
                            <span>Keep me signed in</span>
                        </label>
                    </div>

                    <!-- Primary Red Submit Button -->
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full py-3.5 px-4 rounded-xl text-xs font-black text-white bg-gradient-to-r from-[#c3122e] to-[#9a091d] hover:from-[#a50d24] hover:to-[#800617] shadow-lg shadow-[#c3122e]/30 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                    >
                        <span x-show="!loading">Sign In to Executive Workspace</span>
                        <svg x-show="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Authenticating...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center py-1">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">or continue with</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Microsoft Azure SSO Button -->
                <a
                    href="{{ route('azure.redirect') }}"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-full border border-[#0078d4] bg-white hover:bg-[#0078d4]/5 hover:shadow-md transition-all duration-200 group cursor-pointer"
                    style="box-shadow: 0 1px 3px rgba(0,0,0,0.08);"
                >
                    <!-- Official Microsoft Logo SVG -->
                    <svg width="20" height="20" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                        <rect x="0" y="0" width="10" height="10" fill="#F25022"/>
                        <rect x="11" y="0" width="10" height="10" fill="#7FBA00"/>
                        <rect x="0" y="11" width="10" height="10" fill="#00A4EF"/>
                        <rect x="11" y="11" width="10" height="10" fill="#FFB900"/>
                    </svg>
                    <span class="text-xs font-extrabold text-[#0078d4] transition-colors">Sign in with Microsoft</span>
                </a>

                @if(session('errors') && session('errors')->has('azure'))
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700">
                        {{ session('errors')->first('azure') }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
</x-guest-layout>
