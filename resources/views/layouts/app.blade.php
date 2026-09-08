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
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Anti-flicker Sidebar State Pre-paint Script -->
    <script>
        (function() {
            if (localStorage.getItem('gsnexuspm_sidebar_collapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    <style>
        /* Smooth SPA Instant Page Transitions */
        @keyframes gsPageFadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        main > div {
            animation: gsPageFadeIn 0.15s cubic-bezier(0.16, 1, 0.3, 1);
        }
        /* Livewire 3 Navigation Progress Bar — George Steuart Signature */
        .livewire-progress-bar {
            background: linear-gradient(90deg, #c3122e 0%, #e11d48 50%, #b8860b 100%) !important;
            height: 3px !important;
            box-shadow: 0 0 12px rgba(195, 18, 46, 0.65) !important;
            z-index: 999999 !important;
        }
    </style>

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
        class="fixed inset-y-0 left-0 z-40 flex flex-col border-r transition-all duration-300 overflow-hidden bg-white select-none"
        style="border-color: #e9e4e4; box-shadow: 2px 0 16px rgba(0,0,0,0.03);"
        :class="{
            'w-64': !sidebarCollapsed && !mobileSidebarOpen,
            'w-[72px]': sidebarCollapsed && !mobileSidebarOpen,
            'w-72 sm:w-80 shadow-2xl z-50': mobileSidebarOpen,
            '-translate-x-full lg:translate-x-0': !mobileSidebarOpen,
            'translate-x-0': mobileSidebarOpen
        }"
    >
        <!-- GS Crimson signature accent line -->
        <div class="h-1 flex-shrink-0" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, #c3122e 100%);"></div>

        <!-- Brand & Logo Header (Matches topnav height 64px) -->
        <div class="h-16 flex items-center flex-shrink-0 transition-all border-b border-slate-200/80"
             :class="{ 'justify-center px-2': sidebarCollapsed && !mobileSidebarOpen, 'justify-between px-4': !sidebarCollapsed || mobileSidebarOpen }">
            <a href="{{ route('dashboard') }}" 
               wire:navigate.hover 
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="flex items-center gap-3 min-w-0 group"
               title="{{ $appName }}">
                <!-- GS Brand Mark Shield -->
                <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 shadow-sm shadow-rose-900/20"
                     style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>

                <!-- Brand Typography -->
                <div x-show="!sidebarCollapsed || mobileSidebarOpen" class="min-w-0">
                    <div class="font-extrabold text-sm tracking-tight leading-none text-slate-900 truncate" style="font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;">
                        {{ $appName }}
                    </div>
                    <div class="text-[8.5px] font-extrabold mt-1 tracking-widest uppercase text-[#c3122e] flex items-center gap-1">
                        <span>George Steuart</span>
                        <span class="w-1 h-1 rounded-full bg-amber-500 inline-block"></span>
                    </div>
                </div>
            </a>

            <!-- Mobile Drawer Close Button -->
            <button @click="mobileSidebarOpen = false" 
                    type="button"
                    class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 lg:hidden cursor-pointer" 
                    title="Close Menu">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Links Body -->
        <nav class="flex-1 overflow-y-auto py-3 space-y-0.5 scrollbar-none transition-all"
             :class="{ 'px-2': sidebarCollapsed && !mobileSidebarOpen, 'px-3': !sidebarCollapsed || mobileSidebarOpen }">

            @php
                $user = auth()->user();
                $isSuperAdmin = $user ? ($user->isPmoAdmin() || $user->email === 'admin@nexuspm.local' || $user->id === 1) : false;
                $isPM = $user ? $user->hasRole('project_manager') : false;

                // Pending Approvals Count
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

                // Unread Notifications Count
                $unreadNotificationsCount = $user ? $user->unreadNotifications()->count() : 0;

                // Open Risks & Blockers Count
                $openRisksAndBlockersCount = 0;
                if ($user) {
                    if ($isSuperAdmin) {
                        $openRisksAndBlockersCount = \App\Models\ProjectRisk::where('status', 'open')->whereHas('project')->count() 
                            + \App\Models\TaskBlocker::where('status', '!=', 'resolved')->whereHas('wbsItem.project')->count();
                    } else {
                        $allowedProjectIds = \App\Models\Project::where('project_manager_id', $user->id)
                            ->orWhere(function($subQ) use ($user) {
                                $subQ->where('pm_accepted', true)
                                     ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                            })
                            ->pluck('id');
                        $openRisksAndBlockersCount = \App\Models\ProjectRisk::where('status', 'open')->whereIn('project_id', $allowedProjectIds)->whereHas('project')->count() 
                            + \App\Models\TaskBlocker::where('status', '!=', 'resolved')->whereHas('wbsItem', fn($wq) => $wq->whereIn('project_id', $allowedProjectIds)->whereHas('project'))->count();
                    }
                }

                // User Projects Count
                $myProjectCount = 0;
                if ($user && !$isSuperAdmin) {
                    $myProjectCount = \App\Models\Project::where(function($q) use ($user) {
                        $q->where('project_manager_id', $user->id)
                          ->orWhere(function($subQ) use ($user) {
                              $subQ->where('pm_accepted', true)
                                   ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                          });
                    })->count();
                }
            @endphp

            @php
            $navItemClass = function(bool $active): string {
                return $active
                    ? 'flex items-center rounded-xl font-bold text-xs text-white transition-all duration-150 relative group shadow-sm select-none'
                    : 'flex items-center rounded-xl font-semibold text-xs text-slate-600 hover:text-[#c3122e] hover:bg-rose-50/70 hover:translate-x-0.5 transition-all duration-150 relative group select-none';
            };
            $navItemStyle = fn(bool $active): string => $active
                ? 'background: linear-gradient(135deg, #c3122e 0%, #9e0d23 100%); box-shadow: 0 3px 10px rgba(195,18,46,0.25);'
                : '';
            @endphp

            <!-- 1. Dashboard -->
            <a href="{{ route('dashboard') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('dashboard')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('dashboard')) }}" 
               title="Dashboard">
                <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('dashboard') ? '2.5' : '2' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Dashboard</span>
            </a>

            <!-- 2. Projects Area -->
            @if($isSuperAdmin)
                <!-- PMO Admin: Projects Directory Accordion -->
                <div x-data="{ open: {{ request()->routeIs('projects.*') || request()->routeIs('templates.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open" 
                            type="button" 
                            class="{{ $navItemClass(request()->routeIs('projects.*') || request()->routeIs('templates.*')) }} w-full cursor-pointer" 
                            :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'justify-between gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                            style="{{ $navItemStyle(request()->routeIs('projects.*') || request()->routeIs('templates.*')) }}" 
                            title="Projects Directory">
                        <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                            <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('projects.*') || request()->routeIs('templates.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('projects.*') || request()->routeIs('templates.*') ? '2.5' : '2' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Projects Directory</span>
                        </div>
                        <svg x-show="!sidebarCollapsed || mobileSidebarOpen" :class="{'rotate-180': open}" class="w-3.5 h-3.5 transition-transform duration-200 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open && (!sidebarCollapsed || mobileSidebarOpen)" x-collapse class="mt-1 space-y-0.5 pl-7 pr-1 border-l-2 border-slate-100 ml-4">
                        <a href="{{ route('projects.index') }}" 
                           wire:navigate.hover 
                           @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                           class="block px-3 py-2 text-xs font-semibold rounded-xl transition-colors {{ request()->routeIs('projects.index') ? 'text-[#c3122e] bg-rose-50 font-bold' : 'text-slate-500 hover:text-[#c3122e] hover:bg-rose-50/50' }}">
                            Manage All Projects
                        </a>
                        <a href="{{ route('templates.index') }}" 
                           wire:navigate.hover 
                           @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                           class="block px-3 py-2 text-xs font-semibold rounded-xl transition-colors {{ request()->routeIs('templates.*') ? 'text-[#c3122e] bg-rose-50 font-bold' : 'text-slate-500 hover:text-[#c3122e] hover:bg-rose-50/50' }}">
                            Manage Templates
                        </a>
                    </div>
                </div>
            @else
                <!-- Regular User & Project Manager: Projects Link -->
                <a href="{{ route('projects.my-leads') }}"
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('projects.my-leads') || request()->routeIs('projects.my-collaborations') || request()->routeIs('projects.show')) }}"
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'justify-between gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('projects.my-leads') || request()->routeIs('projects.my-collaborations') || request()->routeIs('projects.show')) }}"
                   title="Projects">
                    <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                        <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('projects.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('projects.*') ? '2.5' : '2' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Projects</span>
                    </div>
                    @if($myProjectCount > 0)
                        <span x-show="!sidebarCollapsed || mobileSidebarOpen" 
                              class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('projects.*') ? 'bg-white/20 text-white' : 'bg-rose-50 text-[#c3122e] border border-rose-200/70' }}">
                            {{ $myProjectCount }}
                        </span>
                        <span x-show="sidebarCollapsed && !mobileSidebarOpen" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[#c3122e] ring-2 ring-white"></span>
                    @endif
                </a>
            @endif

            <!-- 3. My Tasks (For Non-Super Admins) -->
            @if(!($user && $user->isPmoAdmin()))
                <a href="{{ route('my-tasks.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('my-tasks.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('my-tasks.*')) }}" 
                   title="My Tasks">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('my-tasks.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('my-tasks.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">My Tasks</span>
                </a>
            @endif

            <!-- 4. Daily Updates -->
            <a href="{{ route('daily-updates.index') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('daily-updates.*')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('daily-updates.*')) }}" 
               title="Daily Updates">
                <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('daily-updates.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('daily-updates.*') ? '2.5' : '2' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Daily Updates</span>
            </a>

            <!-- 5. Calendar -->
            <a href="{{ route('calendar.index') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('calendar.*')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('calendar.*')) }}" 
               title="Corporate Calendar">
                <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('calendar.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('calendar.*') ? '2.5' : '2' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Calendar</span>
            </a>

            <!-- Approvals Hub -->
            <a href="{{ route('approvals.index') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('approvals.*')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'justify-between gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('approvals.*')) }}" 
               title="Approvals Hub">
                <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('approvals.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('approvals.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Approvals Hub</span>
                </div>
                @if($pendingApprovalsCount > 0)
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" 
                          class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('approvals.*') ? 'bg-white text-[#c3122e]' : 'bg-rose-50 text-[#c3122e] border border-rose-200/70 shadow-2xs' }}">
                        {{ $pendingApprovalsCount }}
                    </span>
                    <span x-show="sidebarCollapsed && !mobileSidebarOpen" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-[#c3122e] ring-2 ring-white animate-pulse"></span>
                @endif
            </a>

            <!-- Risks & Blockers -->
            <a href="{{ route('risks.index') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('risks.*')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'justify-between gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('risks.*')) }}" 
               title="Risks & Blockers Hub">
                <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('risks.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('risks.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Risks &amp; Blockers</span>
                </div>
                @if($openRisksAndBlockersCount > 0)
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" 
                          class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('risks.*') ? 'bg-white text-amber-700' : 'bg-amber-50 text-amber-800 border border-amber-200/70 shadow-2xs' }}">
                        {{ $openRisksAndBlockersCount }}
                    </span>
                    <span x-show="sidebarCollapsed && !mobileSidebarOpen" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-2 ring-white"></span>
                @endif
            </a>

            <!-- Subsidiaries (Super Admin Only) -->
            @if($isSuperAdmin)
                <a href="{{ route('subsidiaries.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('subsidiaries.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('subsidiaries.*')) }}" 
                   title="Subsidiaries">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('subsidiaries.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('subsidiaries.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Subsidiaries</span>
                </a>
            @endif

            <!-- Participants (Super Admin Only) -->
            @if($isSuperAdmin)
                <a href="{{ route('users.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('users.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('users.*')) }}" 
                   title="Participants & Users">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('users.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Participants</span>
                </a>
            @endif

            <!-- Reports -->
            @if($isSuperAdmin || $isPM || auth()->user()->hasProjectPermission('report.view'))
                <a href="{{ route('reports.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('reports.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('reports.*')) }}" 
                   title="Reports">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('reports.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Reports</span>
                </a>
            @endif

            <!-- Notifications -->
            <a href="{{ route('notifications.index') }}" 
               wire:navigate.hover
               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
               class="{{ $navItemClass(request()->routeIs('notifications.*')) }}" 
               :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'justify-between gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
               style="{{ $navItemStyle(request()->routeIs('notifications.*')) }}" 
               title="Notifications">
                <div class="flex items-center gap-3 min-w-0" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('notifications.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('notifications.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Notifications</span>
                </div>
                @if($unreadNotificationsCount > 0)
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" 
                          class="px-2 py-0.5 rounded-full text-[10px] font-black {{ request()->routeIs('notifications.*') ? 'bg-white text-amber-800' : 'bg-slate-100 text-slate-700 border border-slate-200/80' }}">
                        {{ $unreadNotificationsCount }}
                    </span>
                    <span x-show="sidebarCollapsed && !mobileSidebarOpen" class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-amber-500 ring-2 ring-white"></span>
                @endif
            </a>

            <!-- ================= ADMINISTRATION (Super Admin Only) ================= -->
            @if($isSuperAdmin)
                <!-- Roles & Permissions -->
                <a href="{{ route('roles-permissions.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('roles-permissions.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('roles-permissions.*')) }}" 
                   title="Roles & Permissions">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('roles-permissions.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('roles-permissions.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Roles &amp; Permissions</span>
                </a>

                <!-- Audit Logs -->
                <a href="{{ route('audit-logs.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('audit-logs.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('audit-logs.*')) }}" 
                   title="Audit Logs">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('audit-logs.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('audit-logs.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Audit Logs</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('settings.index') }}" 
                   wire:navigate.hover
                   @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                   class="{{ $navItemClass(request()->routeIs('settings.*')) }}" 
                   :class="{ 'justify-center w-10 h-10 mx-auto': sidebarCollapsed && !mobileSidebarOpen, 'gap-3 px-3 py-2.5': !sidebarCollapsed || mobileSidebarOpen }"
                   style="{{ $navItemStyle(request()->routeIs('settings.*')) }}" 
                   title="Settings">
                    <svg class="w-4.5 h-4.5 flex-shrink-0 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#c3122e] transition-colors' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('settings.*') ? '2.5' : '2' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="truncate">Settings</span>
                </a>
            @endif
        </nav>

        <!-- Bottom: User Profile Card & Collapse Toggle -->
        <div class="p-3 flex-shrink-0 space-y-2 border-t border-slate-200/80 bg-white">
            @php
                $isPmoAdminUser = $user && ($user->isPmoAdmin() || $user->id === 1 || $user->email === 'admin@nexuspm.local');
                $userRoleLabel = $isPmoAdminUser ? 'PMO Admin' : 'User';
                $userRoleBadgeClass = $isPmoAdminUser 
                    ? 'bg-rose-50 text-[#c3122e] border border-rose-200/70' 
                    : 'bg-slate-100 text-slate-600 border border-slate-200';
            @endphp

            <!-- User Profile Card -->
            <div class="transition-all rounded-2xl"
                 :class="{
                     'p-1 flex items-center justify-center': sidebarCollapsed && !mobileSidebarOpen,
                     'p-2.5 bg-slate-50 border border-slate-200/90 shadow-2xs hover:border-slate-300': !sidebarCollapsed || mobileSidebarOpen
                 }">
                <div class="flex items-center gap-2.5 min-w-0 w-full" :class="{ 'justify-center': sidebarCollapsed && !mobileSidebarOpen }">
                    <!-- Circular Avatar -->
                    <a href="{{ route('profile.edit') }}" 
                       wire:navigate.hover
                       @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                       class="flex-shrink-0" 
                       title="{{ auth()->user()->name }} ({{ auth()->user()->email }})">
                        <div class="w-8.5 h-8.5 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-2xs transition-transform hover:scale-105" 
                             style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </a>

                    <!-- User Name, Role & Email -->
                    <div class="min-w-0 flex-1 space-y-0.5" x-show="!sidebarCollapsed || mobileSidebarOpen">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('profile.edit') }}" 
                               wire:navigate.hover 
                               @click="if (window.innerWidth < 1024) mobileSidebarOpen = false"
                               class="text-xs font-bold text-slate-900 truncate leading-tight hover:text-[#c3122e] transition-colors" 
                               title="{{ auth()->user()->name }}">
                                {{ auth()->user()->name }}
                            </a>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] font-bold px-1.5 py-0.2 rounded inline-block leading-tight {{ $userRoleBadgeClass }}">
                                {{ $userRoleLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Logout Action Button -->
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 ml-auto" x-show="!sidebarCollapsed || mobileSidebarOpen">
                        @csrf
                        <button type="submit" 
                                class="w-7 h-7 rounded-lg text-slate-400 hover:text-[#c3122e] hover:bg-rose-50 flex items-center justify-center transition-colors cursor-pointer" 
                                title="Sign Out">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Desktop Collapse / Expand Toggle Button -->
            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                    type="button"
                    class="hidden lg:flex items-center justify-center w-full py-1.5 px-2 rounded-xl text-slate-400 hover:text-[#c3122e] hover:bg-rose-50/60 transition-all cursor-pointer gap-1.5" 
                    :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
                <span x-show="!sidebarCollapsed || mobileSidebarOpen" class="text-[9.5px] tracking-wider uppercase font-extrabold text-slate-400">Collapse</span>
            </button>
        </div>
    </aside>

    <!-- Mobile Drawer Overlay Backdrop -->
    <div
        x-show="mobileSidebarOpen"
        x-cloak
        @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-30 lg:hidden"
        style="background: rgba(26,10,13,0.55); backdrop-filter: blur(4px);"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- =================== TOP NAV =================== -->
    <header
        class="fixed top-0 right-0 z-30 flex items-center gap-3 h-16 transition-all duration-300"
        style="background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-bottom: 1px solid #e9e4e4; box-shadow: 0 1px 8px rgba(195,18,46,0.06);"
        :class="{ 'left-0 lg:left-64': !sidebarCollapsed, 'left-0 lg:left-[72px]': sidebarCollapsed }"
    >
        <!-- Accent bar below topnav -->
        <div class="absolute bottom-0 left-0 right-0 h-0.5" style="background: linear-gradient(90deg, #c3122e 0%, #b8860b 50%, transparent 100%); opacity: 0.4;"></div>

        <div class="px-3 sm:px-6 w-full flex items-center justify-between">
            <!-- Left: Mobile toggle + Breadcrumbs -->
            <div class="flex items-center gap-3">
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 rounded-xl lg:hidden" style="color: #534a4a;" onmouseover="this.style.background='#f9e8eb'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#534a4a';">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <nav class="hidden md:flex items-center gap-2 text-xs font-semibold">
                    @if(request()->routeIs('dashboard*'))
                        <span class="font-bold text-[#c3122e]">Workspace</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">{{ auth()->user()?->isPmoAdmin() ? 'Executive Dashboard' : 'Project Dashboard' }}</span>
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
                    @elseif(request()->routeIs('risks*'))
                        <span class="font-bold text-[#c3122e]">Governance</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Risks &amp; Blockers Hub</span>
                    @elseif(request()->routeIs('projects.create'))
                        <a href="{{ route('projects.index') }}" wire:navigate.hover class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Create Project</span>
                    @elseif(request()->routeIs('projects.my-leads'))
                        <a href="{{ route('projects.index') }}" wire:navigate.hover class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Lead Projects</span>
                    @elseif(request()->routeIs('projects.my-collaborations'))
                        <a href="{{ route('projects.index') }}" wire:navigate.hover class="font-bold text-[#c3122e] hover:underline">Projects</a>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">Collaborator Projects</span>
                    @elseif(request()->routeIs('profile*'))
                        <span class="font-bold text-[#c3122e]">Account</span>
                        <svg class="w-3 h-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="text-slate-900 font-black">User Profile &amp; Security</span>
                    @else
                        <a href="{{ route('projects.index') }}" wire:navigate.hover class="font-bold text-[#c3122e] hover:underline">Projects</a>
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
                <!-- Mobile Search Trigger Button -->
                <div x-data="{ mobileSearchOpen: false }" class="relative block sm:hidden">
                    <button
                        @click="mobileSearchOpen = !mobileSearchOpen"
                        type="button"
                        class="p-2 rounded-xl text-slate-600 hover:text-[#c3122e] hover:bg-rose-50 transition-colors"
                        title="Search"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <!-- Mobile Search Modal Overlay -->
                    <div 
                        x-show="mobileSearchOpen" 
                        @click.away="mobileSearchOpen = false" 
                        x-transition:enter="transition duration-150 ease-out"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="fixed inset-x-3 top-16 mt-2 z-50 bg-white rounded-2xl shadow-2xl p-2 border border-slate-200 max-w-lg mx-auto"
                    >
                        <div class="flex items-center justify-between pb-2 px-2 border-b border-slate-100 mb-2">
                            <span class="text-xs font-bold text-slate-800">Search GS NexusPM</span>
                            <button @click="mobileSearchOpen = false" class="text-slate-400 hover:text-slate-700 text-xs font-bold p-1">✕</button>
                        </div>
                        @livewire('global-search')
                    </div>
                </div>

                <!-- Notifications Bell -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" wire:navigate.hover class="relative p-2 rounded-xl transition-colors block" style="color: #706565;" onmouseover="this.style.background='#f9e8eb'; this.style.color='#c3122e';" onmouseout="this.style.background='transparent'; this.style.color='#706565';" title="Notifications">
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
                            <a href="{{ route('profile.edit') }}" wire:navigate.hover class="dropdown-item rounded-lg">
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

    <!-- Toast Container — top-right, ultra-high z-index above all modals/drawers -->
    <div id="toast-container"
         style="position:fixed; top:24px; right:24px; z-index:99999999; display:flex; flex-direction:column; gap:12px; align-items:flex-end; pointer-events:none; max-width:420px; width:calc(100vw - 48px);">
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
    // ═══════════════════════════════════════════════════════════════════════════
    // UNIVERSAL GS NEXUSPM TOAST NOTIFICATION ENGINE (Single Active Toast Mode)
    // ═══════════════════════════════════════════════════════════════════════════
    let lastToastMsg = '';
    let lastToastTime = 0;

    window.showToast = function (message, type = 'success', duration = 4000) {
        if (!message) return;

        // Clean string from potential JSON stringification
        if (typeof message === 'object') {
            message = message.message || message.text || message.title || JSON.stringify(message);
        }

        const now = Date.now();
        // Prevent duplicate toast if same message received within 1.5 seconds
        if (lastToastMsg === message && (now - lastToastTime) < 1500) {
            return;
        }
        lastToastMsg = message;
        lastToastTime = now;

        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position:fixed; top:24px; right:24px; z-index:99999999; display:flex; flex-direction:column; gap:12px; align-items:flex-end; pointer-events:none; max-width:420px; width:calc(100vw - 48px);';
            document.body.appendChild(container);
        }

        // Remove any existing toasts so only 1 message displays at a time
        const existingToasts = container.querySelectorAll('[data-toast]');
        existingToasts.forEach(t => {
            t.style.transform = 'translateX(120%)';
            t.style.opacity = '0';
            setTimeout(() => t.remove(), 250);
        });

        const config = {
            success: {
                bar: '#10b981',
                bg: '#ffffff',
                border: '#d1fae5',
                icon: '<svg style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
                iconBg: '#ecfdf5',
                iconColor: '#059669',
                title: 'Success',
                titleColor: '#065f46'
            },
            error: {
                bar: '#ef4444',
                bg: '#ffffff',
                border: '#fee2e2',
                icon: '<svg style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>',
                iconBg: '#fef2f2',
                iconColor: '#dc2626',
                title: 'Error',
                titleColor: '#991b1b'
            },
            warning: {
                bar: '#f59e0b',
                bg: '#ffffff',
                border: '#fef3c7',
                icon: '<svg style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
                iconBg: '#fffbeb',
                iconColor: '#d97706',
                title: 'Warning',
                titleColor: '#92400e'
            },
            info: {
                bar: '#3b82f6',
                bg: '#ffffff',
                border: '#dbeafe',
                icon: '<svg style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>',
                iconBg: '#eff6ff',
                iconColor: '#2563eb',
                title: 'Information',
                titleColor: '#1e40af'
            }
        };

        const c = config[type] || config.info;

        const toast = document.createElement('div');
        toast.setAttribute('data-toast', '');
        toast.style.cssText = `
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            background: ${c.bg};
            border: 1.5px solid ${c.border};
            border-radius: 16px;
            box-shadow: 0 20px 35px -5px rgba(0, 0, 0, 0.15), 0 8px 15px -3px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            min-width: 280px;
            max-width: 100%;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
            cursor: default;
            pointer-events: all;
            box-sizing: border-box;
        `;

        // Left accent bar
        const bar = document.createElement('div');
        bar.style.cssText = `position: absolute; left: 0; top: 0; bottom: 0; width: 4.5px; background: ${c.bar}; border-radius: 16px 0 0 16px;`;

        // Icon badge
        const iconWrap = document.createElement('div');
        iconWrap.style.cssText = `
            width: 32px; height: 32px;
            border-radius: 10px;
            background: ${c.iconBg};
            color: ${c.iconColor};
            border: 1px solid ${c.border};
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        `;
        iconWrap.innerHTML = c.icon;

        // Content
        const contentWrap = document.createElement('div');
        contentWrap.style.cssText = 'flex: 1; min-width: 0; padding-right: 4px;';
        
        const titleEl = document.createElement('div');
        titleEl.style.cssText = `font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: ${c.titleColor}; margin-bottom: 2px;`;
        titleEl.textContent = c.title;

        const textEl = document.createElement('p');
        textEl.style.cssText = 'font-size: 12.5px; font-weight: 700; color: #0f172a; line-height: 1.4; margin: 0; word-break: break-word; font-family: "Plus Jakarta Sans", "Inter", system-ui, sans-serif;';
        textEl.textContent = message;

        contentWrap.appendChild(titleEl);
        contentWrap.appendChild(textEl);

        // Close button
        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.style.cssText = 'flex-shrink: 0; width: 22px; height: 22px; border: none; background: #f1f5f9; border-radius: 6px; color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; margin-top: 1px; transition: all 0.15s;';
        closeBtn.innerHTML = '✕';
        closeBtn.onmouseover = () => { closeBtn.style.background = '#e2e8f0'; closeBtn.style.color = '#0f172a'; };
        closeBtn.onmouseout = () => { closeBtn.style.background = '#f1f5f9'; closeBtn.style.color = '#64748b'; };

        // Progress bar
        const progress = document.createElement('div');
        progress.style.cssText = `position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; background: ${c.bar}; opacity: 0.4; border-radius: 0 0 16px 16px; transform-origin: left; transition: transform ${duration}ms linear;`;

        toast.appendChild(bar);
        toast.appendChild(iconWrap);
        toast.appendChild(contentWrap);
        toast.appendChild(closeBtn);
        toast.appendChild(progress);

        container.appendChild(toast);

        const removeToast = () => {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 350);
        };

        closeBtn.addEventListener('click', removeToast);

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
                setTimeout(() => { progress.style.transform = 'scaleX(0)'; }, 40);
            });
        });

        let timer = setTimeout(removeToast, duration);
        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progress.style.transitionDuration = '0ms';
        });
        toast.addEventListener('mouseleave', () => {
            timer = setTimeout(removeToast, 1800);
        });

        return toast;
    };

    window.toast = window.showToast;

    // Single unified event listener (avoids 4x duplication)
    function handleToastEvent(payload) {
        if (!payload) return;

        if (typeof payload === 'string') {
            window.showToast(payload, 'success');
            return;
        }

        if (Array.isArray(payload)) {
            const first = payload[0];
            if (typeof first === 'string') {
                window.showToast(first, payload[1] || 'success');
            } else if (first && typeof first === 'object') {
                window.showToast(first.message || first.text || first.title || JSON.stringify(first), first.type || first.level || 'success');
            }
            return;
        }

        if (typeof payload === 'object') {
            const msg = payload.message || payload.text || payload.title || payload.msg;
            const type = payload.type || payload.level || 'success';
            if (msg) {
                window.showToast(msg, type);
            }
        }
    }

    // Bind once globally
    if (!window.__gsToastBound) {
        window.__gsToastBound = true;
        window.addEventListener('toast', (e) => handleToastEvent(e.detail));
        window.addEventListener('notify', (e) => handleToastEvent(e.detail));
    }

    // 3. Flash session messages on load & livewire navigate
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', () => window.showToast(@json(session('success')), 'success'));
        document.addEventListener('livewire:navigated', () => window.showToast(@json(session('success')), 'success'), { once: true });
    @endif
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', () => window.showToast(@json(session('error')), 'error'));
        document.addEventListener('livewire:navigated', () => window.showToast(@json(session('error')), 'error'), { once: true });
    @endif
    @if(session('warning'))
        document.addEventListener('DOMContentLoaded', () => window.showToast(@json(session('warning')), 'warning'));
        document.addEventListener('livewire:navigated', () => window.showToast(@json(session('warning')), 'warning'), { once: true });
    @endif
    @if(session('info'))
        document.addEventListener('DOMContentLoaded', () => window.showToast(@json(session('info')), 'info'));
        document.addEventListener('livewire:navigated', () => window.showToast(@json(session('info')), 'info'), { once: true });
    @endif
    @if(session('status'))
        document.addEventListener('DOMContentLoaded', () => window.showToast(@json(session('status')), 'info'));
        document.addEventListener('livewire:navigated', () => window.showToast(@json(session('status')), 'info'), { once: true });
    @endif

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
