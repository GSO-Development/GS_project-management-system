<x-app-layout :title="'My Profile & Security'">
    @php
        $words = explode(' ', trim($user->name));
        $initials = count($words) >= 2 
            ? strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1))
            : strtoupper(substr($user->name, 0, 2));
        
        // System-level role: strictly 'PMO Admin' or 'User'
        $isPmoAdmin = $user->isPmoAdmin();
        $systemRole = $isPmoAdmin ? 'PMO Admin' : 'User';
        
        $managedCount = \App\Models\Project::where('project_manager_id', $user->id)->count();
        $assignedTaskCount = \App\Models\WbsItem::where('assigned_user_id', $user->id)->count();
    @endphp

    <div class="space-y-6 pb-12">
        <!-- ═══════════════════════════════════════════════════════════════
             1. EXECUTIVE BRAND HERO BANNER
             ═══════════════════════════════════════════════════════════════ -->
        <!-- ═══════════════════════════════════════════════════════════════
             1. CREATIVE EXECUTIVE HERO BANNER (Obsidian & Crimson Glass)
             ═══════════════════════════════════════════════════════════════ -->
        <div class="relative overflow-hidden rounded-3xl text-white shadow-2xl border border-white/10" style="background: #0d0a10; box-shadow: 0 20px 40px -12px rgba(10, 5, 12, 0.65), 0 0 35px -5px rgba(195, 18, 46, 0.25);">
            <!-- High-Resolution AI-Generated Abstract Architecture Glass Backdrop -->
            <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
                <img 
                    src="{{ asset('images/profile-hero-bg.jpg') }}" 
                    alt="Corporate Executive Backdrop" 
                    class="w-full h-full object-cover object-center filter brightness-[0.75] contrast-125 scale-105 transition-transform duration-1000"
                />
                <!-- Deep Obsidian & Rich Crimson Vignette Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#0a070c]/95 via-[#110a12]/85 to-[#260a14]/75"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a070c] via-transparent to-transparent"></div>
                <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full bg-[#c3122e]/20 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full bg-[#c3122e]/15 blur-3xl pointer-events-none"></div>
            </div>

            <!-- Foreground Content -->
            <div class="relative z-10 p-6 sm:p-8 lg:p-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
                <!-- User Profile Identity Block -->
                <div class="flex items-center gap-5 sm:gap-7 min-w-0">
                    <!-- Avatar with Glowing Dual-Layer Rim -->
                    <div class="relative flex-shrink-0 group">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl sm:rounded-3xl p-1 shadow-2xl transition-all duration-300 group-hover:scale-105" style="background: linear-gradient(135deg, #c3122e 0%, #f43f5e 50%, #d97706 100%);">
                            <div class="w-full h-full rounded-[14px] sm:rounded-[22px] flex items-center justify-center font-black text-2xl sm:text-3xl text-white tracking-wider backdrop-blur-xl shadow-inner" style="background: linear-gradient(145deg, #2a0e19 0%, #10050a 100%);">
                                <span class="text-white font-black drop-shadow-md">{{ $initials }}</span>
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 border-3 border-[#0a070c] shadow-lg flex items-center justify-center text-[10px]" title="Active Corporate Session">
                            <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                        </span>
                    </div>

                    <!-- Details & Badges -->
                    <div class="min-w-0 space-y-2">
                        <!-- Badges Row -->
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-[10.5px] font-black uppercase tracking-wider backdrop-blur-md shadow-xs {{ $isPmoAdmin ? 'bg-[#c3122e]/30 text-rose-200 border border-[#c3122e]/50' : 'bg-white/10 text-slate-200 border border-white/20' }}">
                                {{ $isPmoAdmin ? '🛡️ PMO Admin' : '👤 User' }}
                            </span>
                            @if(isset($user->subsidiary))
                                <span class="px-3 py-1 rounded-xl text-[10.5px] font-bold bg-white/10 backdrop-blur-md text-slate-200 border border-white/15 flex items-center gap-1.5 shadow-xs">
                                    <span>🏢</span>
                                    <span>{{ $user->subsidiary->name }}</span>
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold backdrop-blur-md {{ $user->isAzureUser() ? 'bg-sky-500/20 text-sky-200 border border-sky-400/30' : 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' }}">
                                {{ $user->isAzureUser() ? '☁️ Azure AD SSO' : '⚡ System Created' }}
                            </span>
                        </div>

                        <!-- Name & Title -->
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-md truncate">
                                {{ $user->name }}
                            </h1>
                        </div>

                        <!-- Meta Row -->
                        <div class="flex items-center gap-3 text-xs text-slate-300/90 font-medium flex-wrap">
                            <span class="flex items-center gap-1.5 hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ $user->email }}</span>
                            </span>
                            <span class="text-slate-600">&bull;</span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                            </span>
                            <span class="text-slate-600">&bull;</span>
                            <span class="font-mono text-[10.5px] px-2 py-0.5 rounded-md bg-white/10 text-slate-300 border border-white/10">
                                UID: #{{ $user->id }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Modern Frosted Glass KPI Cards -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 w-full lg:w-auto flex-shrink-0">
                    <!-- KPI 1 -->
                    <div class="p-4 rounded-2xl bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-xl border border-white/15 hover:border-rose-400/40 transition-all duration-300 text-center shadow-xl group">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-200 block transition-colors">Projects Lead</span>
                        <div class="text-2xl sm:text-3xl font-black text-white font-mono mt-0.5 drop-shadow-xs">{{ $managedCount }}</div>
                        <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">Portfolio Owner</span>
                    </div>

                    <!-- KPI 2 -->
                    <div class="p-4 rounded-2xl bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-xl border border-white/15 hover:border-rose-400/40 transition-all duration-300 text-center shadow-xl group">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-200 block transition-colors">Active Tasks</span>
                        <div class="text-2xl sm:text-3xl font-black text-white font-mono mt-0.5 drop-shadow-xs">{{ $assignedTaskCount }}</div>
                        <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">Assigned WBS</span>
                    </div>

                    <!-- KPI 3 -->
                    <div class="p-4 rounded-2xl bg-white/[0.07] hover:bg-white/[0.12] backdrop-blur-xl border border-white/15 hover:border-rose-400/40 transition-all duration-300 text-center shadow-xl group">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-200 block transition-colors">Security</span>
                        <div class="text-xs sm:text-sm font-black text-emerald-400 flex items-center justify-center gap-1.5 mt-2 drop-shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Verified
                        </div>
                        <span class="text-[10px] text-slate-500 font-medium mt-0.5 block">Compliance OK</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════════════
             2. TWO-COLUMN RESPONSIVE BENTO GRID
             ═══════════════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- LEFT COLUMN: Account Metadata & Security Insights (4 cols on desktop) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Card: Account Identity Summary -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Account Overview</h4>
                        <span class="text-[10.5px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-[#c3122e] border border-rose-100 font-mono">
                            ID: #{{ $user->id }}
                        </span>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Display Name</span>
                            <span class="font-bold text-slate-800">{{ $user->name }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Corporate Email</span>
                            <span class="font-bold text-slate-800 truncate max-w-[170px]" title="{{ $user->email }}">{{ $user->email }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Phone Number</span>
                            <span class="font-bold text-slate-800">{{ $user->phone_number ?? 'Not configured' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Business Unit</span>
                            <span class="font-bold text-slate-800">{{ $user->subsidiary->name ?? 'George Steuart Group' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">System Role</span>
                            <span class="font-black {{ $isPmoAdmin ? 'text-[#c3122e]' : 'text-slate-900' }}">{{ $systemRole }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Account Type</span>
                            @if($user->isAzureUser())
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#e8f4ff] text-[#0078d4] border border-[#b3d4ff]">
                                    <svg width="10" height="10" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                                    Azure AD
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    System Create
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Member Since</span>
                            <span class="font-bold text-slate-800">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1">
                            <span class="text-slate-400 font-medium">Last Login</span>
                            <span class="font-bold text-slate-800">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Active Session' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Enterprise Security Status -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-black text-sm">
                            🛡️
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider">Security &amp; Compliance</h4>
                            <p class="text-[11px] text-slate-400 font-medium">George Steuart Information Security</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100 flex items-start gap-2.5">
                            <span class="text-emerald-600 font-black">✓</span>
                            <div class="min-w-0">
                                @if($user->isSystemCreated())
                                    <p class="font-bold text-emerald-900">Corporate Password Active</p>
                                    <p class="text-[11px] text-emerald-700">Local System Password (Encrypted Bcrypt).</p>
                                @else
                                    <p class="font-bold text-[#005a9e]">Microsoft Entra ID Protected</p>
                                    <p class="text-[11px] text-slate-600">Enterprise Single Sign-On (SSO) Authentication.</p>
                                @endif
                            </div>
                        </div>

                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2.5">
                            <span class="text-[#c3122e] font-black">&bull;</span>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800">Role-Based Access Control</p>
                                <p class="text-[11px] text-slate-500">Access limited strictly to assigned projects and deliverables.</p>
                            </div>
                        </div>

                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2.5">
                            <span class="text-[#c3122e] font-black">&bull;</span>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800">Single Session Governance</p>
                                <p class="text-[11px] text-slate-500">Active tokens monitored for secure enterprise compliance.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Quick Access Shortcuts -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-3">
                    <h4 class="text-xs font-black text-slate-900 uppercase tracking-wider mb-2">Quick Shortcuts</h4>
                    
                    <div class="space-y-1.5">
                        <a href="{{ route('my-tasks.index') }}" wire:navigate.hover class="flex items-center justify-between p-2.5 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group">
                            <span class="flex items-center gap-2">
                                <span>📋</span>
                                <span>My Tasks Workspace</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e] group-hover:translate-x-0.5 transition-all">→</span>
                        </a>

                        <a href="{{ route('projects.index') }}" wire:navigate.hover class="flex items-center justify-between p-2.5 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group">
                            <span class="flex items-center gap-2">
                                <span>📁</span>
                                <span>Project Portfolio</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e] group-hover:translate-x-0.5 transition-all">→</span>
                        </a>

                        <a href="{{ route('calendar.index') }}" wire:navigate.hover class="flex items-center justify-between p-2.5 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group">
                            <span class="flex items-center gap-2">
                                <span>📅</span>
                                <span>Corporate Calendar</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e] group-hover:translate-x-0.5 transition-all">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Interactive Settings Forms (8 cols on desktop) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Section 1: Update Profile Information -->
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Section 2: Update Password (ONLY for System Create Users) -->
                @if($user->isSystemCreated())
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                @else
                    <!-- For Azure AD SSO users, display SSO info notice -->
                    <div class="bg-white rounded-2xl border border-blue-100 shadow-2xs p-6 sm:p-8 space-y-4">
                        <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 shadow-2xs bg-[#e8f4ff] border border-[#b3d4ff]">
                                <svg width="18" height="18" viewBox="0 0 21 21" fill="none"><rect x="0" y="0" width="10" height="10" fill="#F25022"/><rect x="11" y="0" width="10" height="10" fill="#7FBA00"/><rect x="0" y="11" width="10" height="10" fill="#00A4EF"/><rect x="11" y="11" width="10" height="10" fill="#FFB900"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 tracking-tight">Enterprise SSO Authentication</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Managed centrally via Microsoft Entra ID (Azure AD)</p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#f0f7ff] border border-[#d0e5ff] text-xs text-slate-700 leading-relaxed space-y-2">
                            <p class="font-bold text-[#005a9e] flex items-center gap-2">
                                <span>🔒</span>
                                <span>Single Sign-On (SSO) Managed Account</span>
                            </p>
                            <p>
                                Your account is authenticated via your corporate Microsoft Azure AD credentials. Password resets, multi-factor authentication (MFA), and security policies are centrally governed by your organization's Microsoft 365 administrator.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Section 3: Delete Account (Danger Zone) -->
                <div class="bg-white rounded-2xl border border-rose-200/80 shadow-2xs p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
