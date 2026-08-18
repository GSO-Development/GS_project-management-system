<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appLayout()" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Projects' }} — {{ $appName }}</title>
    <meta name="description" content="George Steuart Group — Enterprise Project Management">

    <!-- Google Fonts: Inter + Plus Jakarta Sans + Outfit + Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- JSZip + docx-preview for 100% exact Word document visual rendering -->
    <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx-preview@0.3.15/dist/docx-preview.min.js"></script>
</head>
<body style="background:#f7f4f4; font-family:'Inter',system-ui,sans-serif;">

    <!-- =================== SIDEBAR =================== -->
    <aside
        id="app-sidebar"
        class="fixed inset-y-0 left-0 z-40 flex flex-col border-r transition-all duration-300 overflow-hidden"
        style="background: #ffffff; border-color: #e8e1e1; box-shadow: 2px 0 16px rgba(195,18,46,0.06);"
        :class="{ 'w-64': !sidebarCollapsed, 'w-[72px]': sidebarCollapsed, '-translate-x-full lg:translate-x-0': !mobileSidebarOpen }"
    >
        <!-- GS Crimson accent top bar -->
        <div class="h-1 flex-shrink-0" style="background: linear-gradient(90deg, #c3122e, #b8860b, #c3122e);"></div>

        <!-- Logo & Brand -->
        <div class="flex items-center gap-3 px-4 py-4 flex-shrink-0" style="border-bottom: 1px solid #f0e8e8;">
            <!-- GS Brand Mark — Shield icon -->
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg,#c3122e,#8b0d1f); box-shadow: 0 4px 16px rgba(195,18,46,0.3);">
                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div x-show="!sidebarCollapsed" class="min-w-0">
                <div class="font-extrabold text-base tracking-tight leading-none truncate" style="font-family:'Playfair Display',serif; color:#1a0a0d;" title="{{ $appName }}">{{ $appName }}</div>
                <div class="text-[9px] font-bold mt-0.5 tracking-widest uppercase" style="color: #c3122e;">George Steuart Group</div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5 scrollbar-none">
            <div class="px-2 pb-1.5 text-[9px] font-bold uppercase tracking-widest" style="color: #c3122e; opacity:0.6;" x-show="!sidebarCollapsed">Main Menu</div>

            @php
                $user = auth()->user();
                $isSuperAdmin = $user ? ($user->hasRole('super_admin') || $user->email === 'admin@nexuspm.local' || $user->id === 1) : false;
                $isPM = $user ? $user->hasRole('project_manager') : false;

                // Accurate dynamic pending approval count matching Approvals Hub
                $pendingApprovalsCount = 0;
                if ($user) {
                    if ($isSuperAdmin) {
                        $pendingApprovalsCount = \App\Models\ApprovalRequest::where('status', 'pending')->count();
                    } else {
                        $pendingApprovalsCount = \App\Models\ApprovalRequest::where('status', 'pending')
                            ->where(function($q) use ($user) {
                                $q->where('requested_by', $user->id)
                                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id));
                            })
                            ->count();
                    }
                }
                $unreadNotificationsCount = $user ? $user->unreadNotifications()->count() : 0;
                $openRisksAndBlockersCount = \App\Models\ProjectRisk::where('status', 'open')->count() + \App\Models\TaskBlocker::where('status', '!=', 'resolved')->count();
            @endphp

            @php
            $navItem = function(bool $active): string {
                return $active
                    ? 'flex items-center gap-3 px-3 py-2.5 rounded-xl font-bold text-xs text-white transition-all'
                    : 'flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-xs transition-all';
            };
            $navStyle = fn(bool $active): string => $active
                ? 'background: linear-gradient(135deg,#c3122e,#a00e24); box-shadow: 0 2px 10px rgba(195,18,46,0.3);'
                : 'color: #706565;';
            @endphp

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="{{ $navItem(request()->routeIs('dashboard')) }}" style="{{ $navStyle(request()->routeIs('dashboard')) }}" @if(!request()->routeIs('dashboard')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
            </a>

            {{-- Projects Navigation --}}
            @php
                // Compute lead/collab counts for badge display (for non-admin users)
                $myLeadCount = 0;
                $myCollabCount = 0;
                if (!$isSuperAdmin) {
                    $myLeadCount = \App\Models\Project::where('project_manager_id', $user->id)->count();
                    $myCollabCount = \App\Models\Project::whereHas('members', fn($q) => $q
                        ->where('user_id', $user->id)
                        ->where('role', 'member')
                    )->where('project_manager_id', '!=', $user->id)->count();
                }
            @endphp

            @if($isSuperAdmin)
            {{-- PMO Admin: Manage All Projects + Templates dropdown --}}
            <div x-data="{ open: {{ request()->routeIs('projects.*') || request()->routeIs('templates.*') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open" type="button" class="{{ $navItem(request()->routeIs('projects.*') || request()->routeIs('templates.*')) }} w-full justify-between" style="{{ $navStyle(request()->routeIs('projects.*') || request()->routeIs('templates.*')) }}" @if(!(request()->routeIs('projects.*') || request()->routeIs('templates.*'))) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Projects</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="mt-1 space-y-1 pl-9 pr-2">
                    <a href="{{ route('projects.index') }}" class="block px-3 py-2 text-xs font-medium rounded-xl transition-colors {{ request()->routeIs('projects.index') ? 'text-[#c3122e] bg-[#fdf4f4]' : 'text-slate-500 hover:text-[#c3122e] hover:bg-[#fdf4f4]' }}">Manage All Projects</a>
                    <a href="{{ route('templates.index') }}" class="block px-3 py-2 text-xs font-medium rounded-xl transition-colors {{ request()->routeIs('templates.*') ? 'text-[#c3122e] bg-[#fdf4f4]' : 'text-slate-500 hover:text-[#c3122e] hover:bg-[#fdf4f4]' }}">Manage Templates</a>
                </div>
            </div>
            @else
            {{-- Regular User: Lead Projects & Collaborator Projects dropdown --}}
            <div x-data="{ open: {{ request()->routeIs('projects.*') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open" type="button" class="{{ $navItem(request()->routeIs('projects.*')) }} w-full justify-between" style="{{ $navStyle(request()->routeIs('projects.*')) }}" @if(!request()->routeIs('projects.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Projects</span>
                    </div>
                    <svg x-show="!sidebarCollapsed" :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open && !sidebarCollapsed" x-collapse class="mt-1 space-y-0.5 pl-9 pr-2">
                    {{-- Lead Projects sub-link with count badge --}}
                    <a href="{{ route('projects.my-leads') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-xl transition-colors {{ request()->routeIs('projects.my-leads') ? 'text-[#c3122e] bg-[#fdf4f4]' : 'text-slate-500 hover:text-[#c3122e] hover:bg-[#fdf4f4]' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            Lead Projects
                        </span>
                        @if($myLeadCount > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black" style="background:#c3122e; color:#fff;">{{ $myLeadCount }}</span>
                        @endif
                    </a>
                    {{-- Collaborator Projects sub-link with count badge --}}
                    <a href="{{ route('projects.my-collaborations') }}" class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-xl transition-colors {{ request()->routeIs('projects.my-collaborations') ? 'text-[#c3122e] bg-[#fdf4f4]' : 'text-slate-500 hover:text-[#c3122e] hover:bg-[#fdf4f4]' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Collaborator Projects
                        </span>
                        @if($myCollabCount > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-indigo-600 text-white">{{ $myCollabCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
            @endif

            <!-- My Tasks -->
            <a href="{{ route('my-tasks.index') }}" class="{{ $navItem(request()->routeIs('my-tasks.*')) }}" style="{{ $navStyle(request()->routeIs('my-tasks.*')) }}" @if(!request()->routeIs('my-tasks.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">My Tasks</span>
            </a>

            <!-- Daily Updates -->
            <a href="{{ route('daily-updates.index') }}" class="{{ $navItem(request()->routeIs('daily-updates.*')) }}" style="{{ $navStyle(request()->routeIs('daily-updates.*')) }}" @if(!request()->routeIs('daily-updates.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Daily Updates</span>
            </a>

            <!-- Calendar -->
            <a href="{{ route('calendar.index') }}" class="{{ $navItem(request()->routeIs('calendar.*')) }}" style="{{ $navStyle(request()->routeIs('calendar.*')) }}" @if(!request()->routeIs('calendar.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Calendar</span>
            </a>

            <!-- Approvals Hub -->
            <a href="{{ route('approvals.index') }}" class="{{ $navItem(request()->routeIs('approvals.*')) }} justify-between" style="{{ $navStyle(request()->routeIs('approvals.*')) }}" @if(!request()->routeIs('approvals.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Approvals Hub</span>
                </div>
                @if($pendingApprovalsCount > 0)
                <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold text-white" style="background:#c3122e;">{{ $pendingApprovalsCount }}</span>
                @endif
            </a>

            <!-- Risks & Blockers -->
            <a href="{{ route('risks.index') }}" class="{{ $navItem(request()->routeIs('risks.*')) }} justify-between" style="{{ $navStyle(request()->routeIs('risks.*')) }}" @if(!request()->routeIs('risks.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Risks &amp; Blockers</span>
                </div>
                @if($openRisksAndBlockersCount > 0)
                <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold text-white" style="background:#d97706;">{{ $openRisksAndBlockersCount }}</span>
                @endif
            </a>

            <!-- Subsidiaries (SA only) -->
            @if($isSuperAdmin)
            <a href="{{ route('subsidiaries.index') }}" class="{{ $navItem(request()->routeIs('subsidiaries.*')) }}" style="{{ $navStyle(request()->routeIs('subsidiaries.*')) }}" @if(!request()->routeIs('subsidiaries.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Subsidiaries</span>
            </a>
            @endif

            <!-- Participants (SA: /users, PM: /team-members) -->
            @if($isSuperAdmin)
            <a href="{{ route('users.index') }}" class="{{ $navItem(request()->routeIs('users.*')) }}" style="{{ $navStyle(request()->routeIs('users.*')) }}" @if(!request()->routeIs('users.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Participants</span>
            </a>
            @elseif($isPM)
            <a href="{{ route('team-members.index') }}" class="{{ $navItem(request()->routeIs('team-members.*')) }}" style="{{ $navStyle(request()->routeIs('team-members.*')) }}" @if(!request()->routeIs('team-members.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Participants</span>
            </a>
            @endif



            <!-- Reports -->
            @if($isSuperAdmin || $isPM)
            <a href="{{ route('reports.index') }}" class="{{ $navItem(request()->routeIs('reports.*')) }}" style="{{ $navStyle(request()->routeIs('reports.*')) }}" @if(!request()->routeIs('reports.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Reports</span>
            </a>
            @endif

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" class="{{ $navItem(request()->routeIs('notifications.*')) }} justify-between" style="{{ $navStyle(request()->routeIs('notifications.*')) }}" @if(!request()->routeIs('notifications.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <div class="flex items-center gap-3 min-w-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Notifications</span>
                </div>
                @if($unreadNotificationsCount > 0)
                <span x-show="!sidebarCollapsed" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold text-white" style="background:#b8860b;">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>

            <!-- Audit Logs + Settings -->
            @if($isSuperAdmin)
            <div class="px-2 pb-1.5 pt-3 text-[9px] font-bold uppercase tracking-widest" style="color:#c3122e; opacity:0.5;" x-show="!sidebarCollapsed">Admin</div>
            <a href="{{ route('audit-logs.index') }}" class="{{ $navItem(request()->routeIs('audit-logs.*')) }}" style="{{ $navStyle(request()->routeIs('audit-logs.*')) }}" @if(!request()->routeIs('audit-logs.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Audit Logs</span>
            </a>
            <a href="{{ route('settings.index') }}" class="{{ $navItem(request()->routeIs('settings.*')) }}" style="{{ $navStyle(request()->routeIs('settings.*')) }}" @if(!request()->routeIs('settings.*')) onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" @endif>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarCollapsed" class="truncate">Settings</span>
            </a>
            @endif
        </nav>

        <!-- Bottom: User Profile Card & Collapse Toggle -->
        <div class="p-3 flex-shrink-0 space-y-2" style="border-top: 1px solid #f0e8e8;">
            <!-- User Profile Card -->
            <div class="flex items-center gap-2.5 p-2 rounded-xl transition-all" style="background: #fdf4f4; border: 1px solid #f0dada;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-xs" style="background: linear-gradient(135deg,#c3122e,#8b0d1f);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1" x-show="!sidebarCollapsed">
                    <p class="text-xs font-bold truncate" style="color:#1a0a0d;">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] truncate" style="color: #9c9090; font-family: monospace;">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full" style="background: #22c55e;"></span>
                        <span class="text-[9px] font-bold" style="color: #16a34a;">Online</span>
                    </div>
                </div>
            </div>

            <!-- Collapse Toggle Button -->
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex items-center justify-center w-full p-1.5 rounded-xl transition-colors" style="color: #c8bfbf;" onmouseover="this.style.background='#fdf4f4'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#c8bfbf';">
                <svg class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
    </aside>

    <!-- Mobile Overlay -->
    <div
        x-show="mobileSidebarOpen"
        @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-30 lg:hidden"
        style="background: rgba(26,10,13,0.6); backdrop-filter: blur(4px);"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    ></div>

    <!-- =================== TOP NAV =================== -->
    <header
        class="fixed top-0 right-0 z-30 flex items-center gap-3 h-16 transition-all duration-300"
        style="background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-bottom: 1px solid #e9e4e4; box-shadow: 0 1px 8px rgba(195,18,46,0.06);"
        :class="{ 'left-0 lg:left-64': !sidebarCollapsed, 'left-0 lg:left-[72px]': sidebarCollapsed }"
    >
        <!-- Accent bar below topnav -->
        <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, transparent 100%); opacity: 0.4;"></div>

        <div class="px-6 w-full flex items-center justify-between">
            <!-- Left: Mobile toggle + Breadcrumbs -->
            <div class="flex items-center gap-3">
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 rounded-xl lg:hidden" style="color: #534a4a;" onmouseover="this.style.background='#f9e8eb'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#534a4a';">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <nav class="hidden md:flex items-center gap-2 text-xs font-semibold">
                    @if(request()->routeIs('dashboard*'))
                        <span class="font-bold text-[#c3122e]">Workspace</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Executive Dashboard</span>
                    @elseif(request()->routeIs('approvals*'))
                        <span class="font-bold text-[#c3122e]">Governance</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Approval Workflows</span>
                    @elseif(request()->routeIs('my-tasks*'))
                        <span class="font-bold text-[#c3122e]">Execution</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">My Tasks</span>
                    @elseif(request()->routeIs('daily-updates*'))
                        <span class="font-bold text-[#c3122e]">Execution</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Daily Updates</span>
                    @elseif(request()->routeIs('calendar*'))
                        <span class="font-bold text-[#c3122e]">Planning</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Corporate Calendar</span>
                    @elseif(request()->routeIs('risks-blockers*'))
                        <span class="font-bold text-[#c3122e]">Governance</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Risks &amp; Blockers</span>
                    @elseif(request()->routeIs('projects.create'))
                        <a href="{{ route('projects.index') }}" class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Create Project</span>
                    @elseif(request()->routeIs('projects.my-leads'))
                        <a href="{{ route('projects.index') }}" class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Lead Projects</span>
                    @elseif(request()->routeIs('projects.my-collaborations'))
                        <a href="{{ route('projects.index') }}" class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Collaborator Projects</span>
                    @else
                        <a href="{{ route('projects.index') }}" class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">All Projects</span>
                    @endif
                </nav>
            </div>

            <!-- Center Search -->
            <div x-data="{ searchOpen: false }" class="relative flex-1 max-w-md mx-4 hidden sm:block">
                <button @click="searchOpen = !searchOpen" data-search-trigger class="w-full flex items-center justify-between gap-2 rounded-xl px-3.5 py-2 text-xs text-left transition-all cursor-pointer" style="border: 1px solid #e9e4e4; background: #faf9f9; color: #9c9090; box-shadow: 0 1px 4px rgba(0,0,0,0.04);" onmouseover="this.style.borderColor='#c3122e'; this.style.background='#fff';" onmouseout="this.style.borderColor='#e9e4e4'; this.style.background='#faf9f9';">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Search projects, codes, managers...</span>
                    </div>
                    <kbd class="px-1.5 py-0.5 text-[10px] font-mono font-bold rounded" style="background: white; border: 1px solid #e9e4e4; color: #9c9090;">⌘K</kbd>
                </button>
                <div x-show="searchOpen" @click.away="searchOpen = false" class="absolute left-0 top-full mt-2 w-[480px] z-50">
                    @livewire('global-search')
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-1.5 sm:gap-2.5 flex-shrink-0">
                
                <!-- ⚡ Universal Quick Actions Dropdown -->
                <div x-data="{ quickActionOpen: false }" class="relative">
                    <button 
                        @click="quickActionOpen = !quickActionOpen" 
                        type="button" 
                        class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-[#c3122e] to-[#a00e24] hover:from-[#a00e24] hover:to-[#800a1d] shadow-sm shadow-rose-900/20 hover:shadow-md transition-all flex items-center gap-1.5 cursor-pointer active:scale-95"
                        title="Quick Actions (1-Click Shortcuts)"
                    >
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Quick Action</span>
                        <svg class="w-3 h-3 transition-transform text-rose-200" :class="{ 'rotate-180': quickActionOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div 
                        x-show="quickActionOpen" 
                        @click.away="quickActionOpen = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                        class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 p-1.5 z-50 overflow-hidden space-y-0.5"
                    >
                        <div class="px-3 py-2 border-b border-slate-100">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">1-Click Shortcuts</p>
                        </div>

                        @if($isSuperAdmin || $isPM)
                            <a href="{{ route('projects.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-rose-50 hover:text-[#c3122e] transition-colors">
                                <span class="w-6 h-6 rounded-lg bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center text-xs">🚀</span>
                                <span>Create New Project</span>
                            </a>
                        @endif

                        <a href="{{ route('daily-updates.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-rose-50 hover:text-[#c3122e] transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xs">💬</span>
                            <span>Post Daily Status</span>
                        </a>

                        <a href="{{ route('my-tasks.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-rose-50 hover:text-[#c3122e] transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-xs">✅</span>
                            <span>My Tasks Workspace</span>
                        </a>

                        <a href="{{ route('approvals.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-rose-50 hover:text-[#c3122e] transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xs">🛡️</span>
                            <span>Formal Approvals</span>
                        </a>

                        <a href="{{ route('calendar.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-800 hover:bg-rose-50 hover:text-[#c3122e] transition-colors">
                            <span class="w-6 h-6 rounded-lg bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center text-xs">📅</span>
                            <span>Corporate Calendar</span>
                        </a>
                    </div>
                </div>

                <!-- Mobile Search Button -->
                <div x-data="{ mobileSearchOpen: false }" class="sm:hidden relative">
                    <button @click="mobileSearchOpen = !mobileSearchOpen" class="p-2 rounded-xl text-slate-600 hover:text-[#c3122e] hover:bg-rose-50 transition-all" title="Search">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <div x-show="mobileSearchOpen" @click.away="mobileSearchOpen = false" class="absolute right-0 top-full mt-2 w-72 sm:w-80 z-50 shadow-2xl">
                        @livewire('global-search')
                    </div>
                </div>
                <!-- Notifications Bell -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl transition-colors block" style="color: #706565;" onmouseover="this.style.background='#f9e8eb'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" title="Notifications">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($unreadNotificationsCount > 0)
                        <span class="absolute top-1 right-1 px-1 min-w-4 h-4 rounded-full text-[9px] font-bold flex items-center justify-center text-white shadow-xs" style="background: #c3122e;">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>
                </div>

                <!-- GS Brand Divider -->
                <div class="w-px h-6 mx-1" style="background: #e9e4e4;"></div>

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 rounded-xl transition-colors" onmouseover="this.style.background='#f9e8eb';" onmouseout="this.style.background='transparent';">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background: linear-gradient(135deg,#c3122e,#8b0d1f);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <p class="text-xs font-bold leading-tight" style="color: #2b2525;">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] font-medium" style="color: #9c9090;">{{ auth()->user()->role_name }}</p>
                        </div>
                        <svg class="w-3.5 h-3.5 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #c8bfbf;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="dropdown-menu absolute right-0 top-full mt-2 w-52">
                        <!-- GS Brand header in dropdown -->
                        <div class="px-4 py-3" style="background: linear-gradient(135deg,#1a0a0d,#2e1318); border-radius: 14px 14px 0 0;">
                            <p class="text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] mt-0.5" style="color: rgba(195,18,46,0.8);">{{ auth()->user()->role_name }}</p>
                        </div>
                        <div class="p-1">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item rounded-lg">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profile Settings
                            </a>
                            <div class="my-1 mx-3 h-px" style="background: #f3f1f1;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full rounded-lg" style="color: #c3122e;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- =================== MAIN CONTENT =================== -->
    <main
        class="pt-16 min-h-screen transition-all duration-300 w-full"
        :class="{ 'pl-0 lg:pl-64': !sidebarCollapsed, 'pl-0 lg:pl-[72px]': sidebarCollapsed }"
        style="background: #f7f4f4;"
    >
        <div class="p-3 sm:p-6 max-w-[1600px] mx-auto w-full">
            {{ $slot }}
        </div>
    </main>

    <!-- Toast Container — bottom-right, above everything -->
    <div id="toast-container"
         style="position:fixed; bottom:24px; right:24px; z-index:99999; display:flex; flex-direction:column; gap:10px; align-items:flex-end; pointer-events:none;">
    </div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" x-data="confirmModal()" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center" style="display:none">
        <div class="absolute inset-0" style="background: rgba(26,10,13,0.5); backdrop-filter: blur(4px);" @click="cancel()"></div>
        <div class="relative max-w-sm w-full mx-4" style="background:#fff; border-radius:18px; border:1px solid #e9e4e4; box-shadow: 0 24px 80px rgba(195,18,46,0.12), 0 8px 32px rgba(0,0,0,0.12); padding:1.5rem;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: #f9e8eb;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #c3122e;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm" style="color: #1a1818;" x-text="title"></h3>
                    <p class="text-xs mt-0.5" style="color: #706565;" x-text="message"></p>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button @click="cancel()" class="btn-secondary btn-sm">Cancel</button>
                <button @click="confirm()" class="btn-primary btn-sm" x-text="confirmText"></button>
            </div>
        </div>
    </div>

    @livewireScripts

    <script>
    function appLayout() {
        return {
            sidebarCollapsed: localStorage.getItem('gsnexuspm_sidebar_collapsed') === 'true',
            mobileSidebarOpen: false,
            init() {
                this.$watch('sidebarCollapsed', val => localStorage.setItem('gsnexuspm_sidebar_collapsed', val));
            }
        };
    }

    function confirmModal() {
        return {
            show: false,
            title: 'Confirm Action',
            message: 'Are you sure?',
            confirmText: 'Confirm',
            resolve: null,
            open(title, message, confirmText = 'Delete') {
                this.title = title;
                this.message = message;
                this.confirmText = confirmText;
                this.show = true;
                return new Promise(resolve => this.resolve = resolve);
            },
            confirm() {
                this.show = false;
                this.resolve && this.resolve(true);
            },
            cancel() {
                this.show = false;
                this.resolve && this.resolve(false);
            }
        };
    }

    window.nexusConfirm = function(title, message, confirmText = 'Delete') {
        return Alpine.store ? Alpine.$data(document.getElementById('confirm-modal')).open(title, message, confirmText) : Promise.resolve(confirm(message));
    };

    // Global Command Palette / Search Hotkey (Cmd+K / Ctrl+K)
    window.addEventListener('keydown', function(e) {
        if ((e.metaKey || e.ctrlKey) && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            const searchBtn = document.querySelector('[data-search-trigger]') || document.querySelector('header button[class*="search"]');
            if (searchBtn) {
                searchBtn.click();
            } else {
                const globalSearchInput = document.querySelector('#global-search-input') || document.querySelector('input[placeholder*="Search"]');
                if (globalSearchInput) {
                    globalSearchInput.focus();
                }
            }
        }
    });
    </script>
</body>
</html>
