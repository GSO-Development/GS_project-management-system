<x-app-layout :title="'My Profile & Security'">
    @php
        $words = explode(' ', trim($user->name));
        $initials = count($words) >= 2 
            ? strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1))
            : strtoupper(substr($user->name, 0, 2));
        
        $isPmoAdmin = $user->isPmoAdmin();
        $systemRole = $isPmoAdmin ? 'PMO Admin' : 'User';
        
        $managedCount = \App\Models\Project::where('project_manager_id', $user->id)->count();
        $assignedTaskCount = \App\Models\WbsItem::where('assigned_user_id', $user->id)->count();
    @endphp

    <div class="space-y-6 pb-12" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">

        <!-- ── 1. CLEAN PAGE HEADER ── -->
        <div class="pb-4 border-b border-slate-200/80">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                My Profile &amp; Corporate Identity
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Manage your personal account details, corporate profile, and authentication security.
            </p>
        </div>

        <!-- ── 2. TOP PROFILE SUMMARY BANNER ── -->
        <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            
            <!-- User Identity & Badges -->
            <div class="flex items-center gap-4 min-w-0">
                <!-- Avatar Circle -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white flex items-center justify-center font-black text-2xl shadow-md shrink-0">
                    {{ $initials }}
                </div>

                <div class="min-w-0 space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-snug truncate">
                            {{ $user->name }}
                        </h2>
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-black {{ $isPmoAdmin ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                            {{ $systemRole }}
                        </span>
                        @if(isset($user->subsidiary))
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                🏢 {{ $user->subsidiary->name }}
                            </span>
                        @endif
                        <span class="px-2 py-0.5 rounded-md text-[10.5px] font-bold {{ $user->isAzureUser() ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                            {{ $user->isAzureUser() ? 'Azure AD SSO' : 'System Created' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-slate-500 font-medium flex-wrap">
                        <span class="font-mono text-slate-600">{{ $user->email }}</span>
                        <span>•</span>
                        <span>Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'N/A' }}</span>
                        <span>•</span>
                        <span class="font-mono text-[11px] font-bold text-slate-400">UID: #{{ $user->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Telemetry Stat Cards -->
            <div class="flex items-center gap-3 shrink-0 self-stretch lg:self-auto pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[100px]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Projects Lead</span>
                    <span class="text-lg font-black text-slate-900 font-mono">{{ $managedCount }}</span>
                </div>

                <div class="px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-center min-w-[100px]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Active Tasks</span>
                    <span class="text-lg font-black text-slate-900 font-mono">{{ $assignedTaskCount }}</span>
                </div>

                <div class="px-4 py-2.5 rounded-xl bg-emerald-50/60 border border-emerald-200/80 text-center min-w-[100px]">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">Security</span>
                    <span class="text-xs font-black text-emerald-600 flex items-center justify-center gap-1 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Verified</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- ── 3. TWO-COLUMN RESPONSIVE BENTO GRID ── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- ══════════════════════════════════════════════
                 LEFT COLUMN: EDITABLE FORMS (8 COLS)
                 ══════════════════════════════════════════════ -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Section 1: Personal Information Form -->
                <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Section 2: Account Security & Password -->
                @if($user->isSystemCreated())
                    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-2xs p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                @else
                    <div class="bg-white border border-blue-100 rounded-2xl shadow-2xs p-6 sm:p-8 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-900">Microsoft Azure AD Single Sign-On</h3>
                                <p class="text-xs text-slate-500 font-medium">Authenticating via corporate Office 365 credentials.</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed bg-blue-50/60 p-3 rounded-xl border border-blue-100">
                            Your password and security settings are centrally managed by your organization's Microsoft Entra ID administrator.
                        </p>
                    </div>
                @endif



            </div>


            <!-- ══════════════════════════════════════════════
                 RIGHT COLUMN: ACCOUNT METADATA & QUICK LINKS (4 COLS)
                 ══════════════════════════════════════════════ -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Card R1: Account Metadata Overview -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Account Overview</span>
                        <span class="text-[11px] font-mono font-bold px-2 py-0.5 rounded bg-rose-50 text-[#c3122e] border border-rose-100">
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
                            <span class="font-bold text-slate-800 font-mono truncate max-w-[170px]" title="{{ $user->email }}">{{ $user->email }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Contact Phone</span>
                            <span class="font-bold text-slate-800">{{ $user->phone_number ?? 'Not set' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Business Unit</span>
                            <span class="font-bold text-slate-800">{{ $user->subsidiary->name ?? 'George Steuart Group' }}</span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">System Role</span>
                            <span class="font-bold text-[#c3122e] bg-rose-50 px-2 py-0.5 rounded border border-rose-200 text-[11px]">
                                {{ $systemRole }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Authentication</span>
                            <span class="font-bold text-slate-800">
                                {{ $user->isAzureUser() ? 'Azure AD SSO' : 'Encrypted Password' }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2 py-1">
                            <span class="text-slate-400 font-medium">Member Since</span>
                            <span class="font-bold text-slate-800">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card R2: Security & Governance Compliance -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs space-y-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block pb-3 border-b border-slate-100">
                        Security &amp; Compliance
                    </span>

                    <div class="space-y-2 text-xs">
                        <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-start gap-2">
                            <span class="text-emerald-600 font-bold">✓</span>
                            <span class="font-medium text-emerald-900">
                                Account is active and verified under corporate access policies.
                            </span>
                        </div>

                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-2">
                            <span class="text-[#c3122e] font-bold">•</span>
                            <span class="font-medium text-slate-700">
                                Access is governed by role-based permissions ({{ $systemRole }}).
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card R3: Quick Workspace Links -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-2xs space-y-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block pb-2 border-b border-slate-100">
                        Workspace Shortcuts
                    </span>

                    <div class="space-y-1">
                        <a href="{{ route('my-tasks.index') }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group no-underline">
                            <span class="flex items-center gap-2">
                                <span>📋</span>
                                <span>My Tasks Workspace</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e]">→</span>
                        </a>

                        <a href="{{ route('projects.index') }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group no-underline">
                            <span class="flex items-center gap-2">
                                <span>📁</span>
                                <span>Project Portfolio</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e]">→</span>
                        </a>

                        <a href="{{ route('calendar.index') }}" class="flex items-center justify-between p-2 rounded-xl hover:bg-rose-50 text-slate-700 hover:text-[#c3122e] text-xs font-bold transition-all group no-underline">
                            <span class="flex items-center gap-2">
                                <span>📅</span>
                                <span>Corporate Calendar</span>
                            </span>
                            <span class="text-slate-400 group-hover:text-[#c3122e]">→</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
