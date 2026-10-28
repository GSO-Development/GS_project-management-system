<x-guest-layout>
<div class="login-page min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 md:p-10 font-sans relative overflow-hidden">
    
    <!-- Background Ambient Soft Glow Orbs -->
    <div class="hidden"></div>
    <div class="hidden"></div>

    <!-- Outer Card Container -->
    <div class="login-shell w-full bg-white rounded-3xl border border-slate-200 overflow-hidden flex flex-col lg:flex-row relative z-10">

        <!-- ================= LEFT PANEL: Cinematic Skyscraper Hero ================= -->
        <div class="login-brand-panel lg:w-[43%] p-7 sm:p-8 relative flex flex-col justify-between overflow-hidden min-h-[280px] lg:min-h-full">
            
            <!-- Dark Executive Overlay Vignette -->
            <div class="absolute inset-x-0 top-0 h-1 z-0" style="background: #c3122e;"></div>

            <!-- Decorative portfolio orbit -->
            <div class="login-brand-orbit" aria-hidden="true">
                <span class="login-orbit-ring login-orbit-ring--outer"></span>
                <span class="login-orbit-ring login-orbit-ring--inner"></span>
                <span class="login-orbit-node login-orbit-node--one"></span>
                <span class="login-orbit-node login-orbit-node--two"></span>
                <span class="login-orbit-node login-orbit-node--three"></span>
            </div>
            <div class="login-brand-glow" aria-hidden="true"></div>

            <!-- Top Header Badge -->
            <div class="login-reveal login-reveal--one relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-[#c3122e] flex items-center justify-center shadow-sm border border-white shrink-0" style="width: 36px; height: 36px;">
                        <svg class="w-5 h-5 text-white" width="20" height="20" style="width: 20px; height: 20px; max-width: 20px; max-height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-black text-slate-900 tracking-tight">GS NexusPM</span>
                </div>

                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-white border border-rose-200 text-[#a80f28] shadow-2xs">
                    EST. 1835
                </span>
            </div>

            <!-- Corporate brand story -->
            <div class="login-brand-copy relative z-10 my-auto max-w-md">
                <p class="login-eyebrow">George Steuart Group</p>
                <h1 class="login-animated-title" aria-label="One calm workspace for every project.">
                    <span style="--word-index: 0">One</span>
                    <span style="--word-index: 1">calm</span>
                    <span style="--word-index: 2">workspace</span>
                    <span style="--word-index: 3">for</span>
                    <span style="--word-index: 4">every</span>
                    <span style="--word-index: 5">project.</span>
                </h1>
                <p class="login-brand-intro">Plan work, follow progress and keep every delivery decision visible across the group.</p>

                <div class="login-feature-list">
                    <div><span><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span><p>Clear project and task visibility</p></div>
                    <div><span><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span><p>Simple progress and ownership tracking</p></div>
                    <div><span><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span><p>Secure, traceable team collaboration</p></div>
                </div>
            </div>

            <p class="login-brand-trust login-reveal login-reveal--three relative z-10">Trusted project governance since 1835.</p>
        </div>

        <!-- ================= RIGHT PANEL: Pristine White Login Form ================= -->
        <div class="login-form-panel lg:w-[57%] bg-white p-7 sm:p-9 lg:p-10 flex flex-col justify-center">
            <div class="login-form-content w-full max-w-sm mx-auto space-y-5">
                
                <!-- Brand Form Header -->
                <div class="login-form-heading">
                    <div class="login-secure-label"><span></span> Secure workspace</div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome back</h2>
                    <p class="text-xs text-slate-500 mt-1.5 font-normal">Sign in to continue to GS NexusPM</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-2" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="text-xs font-semibold text-slate-700 mb-1.5 block">Email address</label>
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
                            <label for="password" class="text-xs font-semibold text-slate-700">Password</label>
                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#b51a31] hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-slate-400" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <svg x-show="!showPassword" class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[11px] text-rose-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="pt-0.5">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer text-xs text-slate-600 font-medium select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#c3122e] focus:ring-[#c3122e]/20 focus:ring-2 cursor-pointer">
                            <span>Keep me signed in</span>
                        </label>
                    </div>

                    <!-- Primary Red Submit Button -->
                    <button
                        type="submit"
                        :disabled="loading"
                        class="login-submit w-full py-3 px-4 rounded-xl text-xs font-semibold text-white bg-[#bd1731] hover:bg-[#a9142b] transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]"
                    >
                        <span x-show="!loading">Sign in to workspace</span>
                        <svg x-show="!loading" class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Authenticating...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative flex items-center py-1">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="mx-3 text-[10px] font-medium text-slate-400">or continue with</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <!-- Microsoft Azure SSO Button -->
                <a
                    href="{{ route('azure.redirect') }}"
                    class="login-microsoft w-full flex items-center justify-center gap-3 py-3 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 transition-all duration-200 group cursor-pointer"
                >
                    <!-- Official Microsoft Logo SVG -->
                    <svg width="20" height="20" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                        <rect x="0" y="0" width="10" height="10" fill="#F25022"/>
                        <rect x="11" y="0" width="10" height="10" fill="#7FBA00"/>
                        <rect x="0" y="11" width="10" height="10" fill="#00A4EF"/>
                        <rect x="11" y="11" width="10" height="10" fill="#FFB900"/>
                    </svg>
                    <span class="text-xs font-semibold text-slate-700 transition-colors">Sign in with Microsoft</span>
                </a>

                @if(session('errors') && session('errors')->has('azure'))
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700">
                        {{ session('errors')->first('azure') }}
                    </div>
                @endif

                <p class="login-access-note">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Authorised George Steuart Group users only
                </p>

            </div>
        </div>

    </div>
</div>
</x-guest-layout>
