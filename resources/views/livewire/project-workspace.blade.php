<div>
    {{-- PM Assignment Acceptance / Rejection Notice Banner --}}
    @if($project->isPmRejected())
        <div class="mb-5 rounded-2xl p-4 sm:p-5 bg-white border border-rose-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden animate-in fade-in duration-300">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-rose-500 to-rose-700"></div>
            <div class="flex items-start sm:items-center gap-3.5 pl-2">
                <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center text-base flex-shrink-0 shadow-2xs font-bold">
                    <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-xs sm:text-sm font-black text-slate-900">Project Leadership Assignment Declined</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wider">
                            Issue Reported to PMO
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium mt-0.5">
                        Leader <strong class="text-slate-900">{{ $project->projectManager->name ?? 'Project Leader' }}</strong> declined assignment on {{ $project->pm_rejected_at?->format('M d, Y h:i A') }}.
                    </p>
                    @if($project->pm_rejection_reason)
                        <div class="mt-2 p-2.5 rounded-xl bg-rose-50/60 border border-rose-100 text-xs text-rose-900">
                            <span class="font-bold text-rose-800 text-[10px] uppercase tracking-wider block mb-0.5">Reported Issue / Reason:</span>
                            <span class="italic">"{{ $project->pm_rejection_reason }}"</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0 pl-2 sm:pl-0">
                @if(auth()->user()->isSuperAdmin())
                    <button
                        wire:click="openReassignModal"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        <span>Reassign Leader</span>
                    </button>
                @endif

                @if($project->project_manager_id === auth()->id())
                    <button
                        wire:click="acceptAssignment"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        <span>Re-evaluate &amp; Accept</span>
                    </button>
                @endif
            </div>
        </div>
    @elseif(!$project->pm_accepted && ($project->project_manager_id !== auth()->id() || auth()->user()->isSuperAdmin()))
        <div class="mb-4 rounded-2xl p-4 sm:p-5 bg-white border border-amber-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden animate-in fade-in duration-300">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900">Project Leadership Assignment</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Pending Acceptance</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-normal mt-0.5">
                        @if($project->project_manager_id === auth()->id())
                            You have been designated as Project Leader. Review details to accept leadership and begin execution.
                        @else
                            Assigned Project Leader (<strong class="text-slate-800 font-semibold">{{ $project->projectManager->name ?? 'Unassigned' }}</strong>) has not yet accepted assignment.
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                @if($project->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin())
                    <button
                        wire:click="openRejectionModal"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-rose-50 hover:text-rose-700 border border-slate-200 hover:border-rose-200 transition-all cursor-pointer shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Decline</span>
                    </button>

                    <button
                        wire:click="acceptAssignment"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] shadow-xs active:scale-95 transition-all cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Accept Leadership →</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    @php
        $currentUser     = auth()->user();
        $isSuperAdmin    = $currentUser->isSuperAdmin() || $currentUser->hasRole('pmo_admin');
        $isPm            = ($project->project_manager_id === $currentUser->id);
        $memberRecord    = $project->members->firstWhere('id', $currentUser->id);
        $memberPivotRole = $memberRecord?->pivot?->role;

        if ($isPm) {
            $myRoleLabel      = 'Project Leader';
            $myRoleDesc       = 'You are the primary operational leader with authority over deliverables, schedule & budget.';
            $myRoleBadgeClass = 'bg-rose-50 text-[#c3122e] border-rose-200/80';
        } elseif ($memberPivotRole === 'sponsor') {
            $myRoleLabel      = 'Project Sponsor';
            $myRoleDesc       = 'You are the executive sponsor championing this strategic project.';
            $myRoleBadgeClass = 'bg-amber-50 text-amber-800 border-amber-200/80';
        } elseif ($memberPivotRole === 'owner') {
            $myRoleLabel      = 'Project Owner';
            $myRoleDesc       = 'You are the business owner accountable for project value and outcomes.';
            $myRoleBadgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200/80';
        } elseif ($memberPivotRole === 'steering_committee') {
            $myRoleLabel      = 'Steering Committee';
            $myRoleDesc       = 'You provide steering governance and strategic advisory oversight.';
            $myRoleBadgeClass = 'bg-purple-50 text-purple-800 border-purple-200/80';
        } elseif ($memberPivotRole) {
            $myRoleLabel      = 'Collaborator';
            $myRoleDesc       = 'You are an assigned project collaborator executing deliverables.';
            $myRoleBadgeClass = 'bg-blue-50 text-blue-800 border-blue-200/80';
        } elseif ($isSuperAdmin) {
            $myRoleLabel      = 'PMO Admin';
            $myRoleDesc       = 'Enterprise governance, audit authority, and full workspace access.';
            $myRoleBadgeClass = 'bg-rose-50 text-[#c3122e] border-rose-200/80';
        } else {
            $myRoleLabel      = 'Stakeholder';
            $myRoleDesc       = 'You have read-only stakeholder visibility on this project.';
            $myRoleBadgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
        }

        $statusStyle = match($project->status) {
            \App\Enums\ProjectStatus::COMPLETED => [
                'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200/80', 'dot' => 'bg-emerald-500'
            ],
            \App\Enums\ProjectStatus::IN_PROGRESS => [
                'bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200/80', 'dot' => 'bg-blue-600'
            ],
            \App\Enums\ProjectStatus::PLANNING => [
                'bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200/80', 'dot' => 'bg-indigo-500'
            ],
            \App\Enums\ProjectStatus::AT_RISK => [
                'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200/80', 'dot' => 'bg-amber-500'
            ],
            \App\Enums\ProjectStatus::ON_HOLD => [
                'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200/80', 'dot' => 'bg-amber-500'
            ],
            \App\Enums\ProjectStatus::DELAYED, \App\Enums\ProjectStatus::CANCELLED => [
                'bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200/80', 'dot' => 'bg-rose-500'
            ],
            default => [
                'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200/80', 'dot' => 'bg-slate-400'
            ],
        };

        $completedWbsCount  = $project->wbsItems->where('status', \App\Enums\WbsStatus::COMPLETED)->count();
        $totalWbsCount      = $project->wbsItems->count();
        $inProgressWbsCount = $project->wbsItems->where('status', \App\Enums\WbsStatus::IN_PROGRESS)->count();
    @endphp

    <!-- 1. Top Bar: Clean Minimalist Breadcrumbs -->
    <div class="flex items-center gap-1.5 sm:gap-2 text-xs text-slate-500 font-medium mb-2.5">
        <a href="{{ route('dashboard') }}" wire:navigate.hover class="text-slate-400 hover:text-slate-700 transition-colors inline-flex items-center p-1 rounded-lg hover:bg-slate-100" title="Dashboard">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ auth()->user()?->isPmoAdmin() ? route('projects.index') : route('projects.my-leads') }}" wire:navigate.hover class="text-slate-600 hover:text-[#c3122e] font-semibold transition-colors no-underline">
            Projects
        </a>
        <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-slate-700 font-semibold truncate max-w-[200px] sm:max-w-xs">
            {{ $project->subsidiary->name ?? 'George Steuart Teas' }}
        </span>
        <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="px-2.5 py-0.5 rounded-full text-[10.5px] font-mono font-bold bg-rose-50 text-[#c3122e] border border-rose-100 shadow-2xs">
            {{ $project->code }}
        </span>
    </div>

    <!-- 2. Modern, Nice, Simple & Clean Executive Project Header -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs mb-4 p-6">
        <!-- Top Half: Identity & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-4 min-w-0">
                <!-- Project Brand Monogram Tile (Matching Screenshot: Solid GS red box) -->
                <div class="w-14 h-14 rounded-2xl bg-[#c3122e] text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-xs tracking-wider">
                    GS
                </div>
                
                <div class="min-w-0">
                    <!-- Subsidiary & Code Micro-Strip -->
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <span class="text-xs font-semibold text-slate-500">
                            {{ $project->subsidiary->name ?? 'George Steuart Optimize' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono bg-rose-50 text-[#c3122e] border border-rose-200/80">
                            {{ $project->code }}
                        </span>
                    </div>

                    <!-- Title, Status & Role Badges -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                            {{ $project->name }}
                        </h1>

                        <!-- Status Badge -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold {{ $statusStyle['bg'] }} {{ $statusStyle['text'] }} border {{ $statusStyle['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusStyle['dot'] }}"></span>
                            {{ $project->status->label() }}
                        </span>

                        <!-- Role Badge -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold {{ $myRoleBadgeClass }} border" title="{{ $myRoleDesc }}">
                            <svg class="w-3.5 h-3.5 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $myRoleLabel }}</span>
                        </span>
                    </div>

                    @if($project->description)
                        <p class="text-xs text-slate-500 font-normal line-clamp-1 max-w-3xl mt-1.5">
                            {{ $project->description }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Primary Action -->
            @if(auth()->user()?->isPmoAdmin() || $project->userCan(auth()->user(), 'project_details.view'))
                <div class="flex items-center gap-3 shrink-0 self-start sm:self-center">
                    <button
                        wire:click="openProjectDetailsModal"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] active:scale-95 transition-all shadow-xs cursor-pointer"
                        title="View full project brief, specifications & governance"
                    >
                        <svg class="w-4 h-4 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Project Details</span>
                        <svg class="w-3.5 h-3.5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- 4-Card Grid (Exact Replica of User's Screenshot) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 mt-5 pt-5 border-t border-slate-100">
            <!-- Card 1: Project Lead -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-400 shrink-0">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-medium text-slate-400 block">Project Lead</span>
                    <span class="text-sm font-bold text-slate-900 truncate block mt-0.5">
                        {{ $project->projectManager->name ?? 'Unassigned' }}
                    </span>
                </div>
            </div>

            <!-- Card 2: Start Date -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-400 shrink-0">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="text-[11px] font-medium text-slate-400 block">Start Date</span>
                    <span class="text-sm font-bold text-slate-900 block mt-0.5">
                        {{ $project->start_date ? $project->start_date->format('M d, Y') : ($project->created_at ? $project->created_at->format('M d, Y') : 'N/A') }}
                    </span>
                </div>
            </div>

            <!-- Card 3: Due Date -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-400 shrink-0">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-[11px] font-medium text-slate-400 block">Due Date</span>
                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                        <span class="text-sm font-bold text-slate-900">
                            {{ $project->deadline ? $project->deadline->format('M d, Y') : 'Nov 01, 2026' }}
                        </span>
                        @if($project->deadline)
                            @php $daysLeft = (int) now()->startOfDay()->diffInDays($project->deadline->startOfDay(), false); @endphp
                            @if($daysLeft > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200/80">
                                    {{ $daysLeft }} days left
                                </span>
                            @elseif($daysLeft === 0)
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-amber-50 text-amber-700 border border-amber-200/80">
                                    Due today
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/80">
                                    {{ abs($daysLeft) }}d overdue
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 4: Progress -->
            <div class="bg-white border border-slate-200/80 rounded-xl p-3.5 shadow-2xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium text-slate-400">Progress</span>
                    <span class="text-sm font-bold text-slate-900 font-mono">{{ (int) $project->overall_progress }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 my-2 overflow-hidden">
                    <div class="h-full bg-[#c3122e] rounded-full transition-all duration-500" style="width: {{ min(100, max(0, (int) $project->overall_progress)) }}%;"></div>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-400">{{ $completedWbsCount }} of {{ $totalWbsCount }} tasks completed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== LEADERSHIP GATEWAY (IF PENDING ACCEPTANCE) ===== -->
    @if(!$project->isPmAccepted() && $project->project_manager_id === auth()->id() && !auth()->user()->isSuperAdmin())
        <!-- 🔒 LEADERSHIP ACCEPTANCE GATEWAY SCREEN (WORKSPACE LOCKED UNTIL ACCEPTED) -->
        <div class="mb-6 space-y-4 animate-in fade-in duration-300">
            
            <!-- 1. Executive Notice Card (Clean Modern White Card matching Top UI) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-4 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs" style="width: 40px; height: 40px;">
                        <svg class="w-5 h-5 text-amber-600" width="20" height="20" style="width: 20px; height: 20px; max-width: 20px; max-height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Project Leadership Assignment</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200/80 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Action Required</span>
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-normal mt-1">
                            PMO Administration has designated you as the Project Leader. Review the configured blueprint and project governance below to accept operational leadership.
                        </p>
                    </div>
                </div>

                <!-- Quick Action Buttons in Top Header -->
                <div class="flex items-center gap-2.5 shrink-0 self-start sm:self-center">
                    <button
                        wire:click="openProjectDetailsModal"
                        type="button"
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-all cursor-pointer flex items-center gap-1.5 active:scale-95"
                    >
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Full Brief</span>
                    </button>

                    <button
                        wire:click="acceptAssignment"
                        type="button"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] shadow-xs active:scale-95 transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Accept Leadership →</span>
                    </button>
                </div>
            </div>

            <!-- 2. Clean 2-Column Executive Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
                
                <!-- Left Column (7 cols): WBS Blueprint Architecture & Delivery Phases -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Configured WBS Blueprint Architecture Card -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">WBS Blueprint Architecture</span>
                                    <h4 class="text-sm font-bold text-slate-900">
                                        {{ $project->template->name ?? ($project->wbs_breakdown_type === 'template' ? 'Standard Blueprint Template' : 'Custom Agile WBS Canvas') }}
                                    </h4>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/80 font-mono">
                                {{ $project->wbsItems->count() }} Tasks Pre-Built
                            </span>
                        </div>

                        @if($project->description)
                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-xs text-slate-600 leading-relaxed">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Strategic Objectives &amp; Scope</span>
                                {{ $project->description }}
                            </div>
                        @elseif($project->template && $project->template->description)
                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60 text-xs text-slate-600 leading-relaxed">
                                {{ $project->template->description }}
                            </div>
                        @endif

                        @if($project->wbsItems->whereNull('parent_id')->count() > 0)
                            <div class="space-y-2.5 pt-3 border-t border-slate-100">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Configured Delivery Phases ({{ $project->wbsItems->whereNull('parent_id')->count() }})</span>
                                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                                    @foreach($project->wbsItems->whereNull('parent_id') as $phase)
                                        <div class="p-3 rounded-xl bg-slate-50/60 border border-slate-200/70 hover:border-slate-300 hover:bg-slate-50 flex items-center justify-between text-xs transition-all">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-200/70 text-[#c3122e] flex items-center justify-center font-bold text-[11px] shrink-0 font-mono">
                                                    {{ $loop->iteration }}
                                                </div>
                                                <span class="font-semibold text-slate-900 truncate">{{ $phase->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2.5 shrink-0">
                                                <span class="text-slate-500 font-medium text-[11px] font-mono">{{ $phase->duration_days ?? 0 }} Days</span>
                                                <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-600 text-[10px] font-semibold">
                                                    {{ $phase->children->count() }} Subtasks
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column (5 cols): Timeline & Budget, Team Roster & Executive Action Card -->
                <div class="lg:col-span-5 space-y-4">
                    
                    <!-- Timeline & Fiscal Key Specs -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-500 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Timeline &amp; Fiscal Estimates</h3>
                                <span class="text-[10px] text-slate-400 font-medium">Scheduled Delivery Window</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-100">
                                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400 block">Kick-off Date</span>
                                <span class="text-xs font-bold text-slate-900 block mt-0.5">
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>

                            <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-100">
                                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400 block">Target Delivery</span>
                                <span class="text-xs font-bold text-[#c3122e] block mt-0.5">
                                    {{ $project->deadline ? $project->deadline->format('M d, Y') : 'TBD' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50/70 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400 block">Total Est. Budget</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-xs font-semibold text-slate-400">Rs.</span>
                                    <span class="text-sm font-bold text-slate-900 font-mono">{{ number_format($project->estimated_budget ?? 0, 0) }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-medium uppercase tracking-wider text-slate-400 block">Estimated Duration</span>
                                <span class="text-xs font-bold text-slate-800 font-mono block mt-0.5">
                                    @if($project->start_date && $project->deadline)
                                        {{ (int) $project->start_date->diffInDays($project->deadline) }} Days
                                    @else
                                        Standard Window
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Team Members -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Assigned Team Roster</h3>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $project->members->count() }} Designated Collaborators</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                            @foreach($project->members as $member)
                                @php
                                    $isPm = ($member->id === $project->project_manager_id);
                                    $role = $isPm ? 'lead' : ($member->pivot->role ?? 'member');
                                    $roleLabel = match($role) {
                                        'sponsor' => 'Sponsor',
                                        'owner' => 'Project Owner',
                                        'steering_committee' => 'Committee',
                                        'lead' => 'Project Manager',
                                        default => 'Member'
                                    };
                                    $roleBadgeClass = match($role) {
                                        'sponsor' => 'bg-amber-50 text-amber-800 border-amber-200/80',
                                        'owner' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                                        'steering_committee' => 'bg-violet-50 text-violet-800 border-violet-200/80',
                                        'lead' => 'bg-rose-50 text-[#c3122e] border-rose-200/80',
                                        default => 'bg-blue-50 text-blue-700 border-blue-200/80'
                                    };
                                @endphp
                                <div class="p-2.5 rounded-xl bg-slate-50/60 border border-slate-200/70 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-lg bg-slate-200 text-slate-700 font-bold text-[11px] flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-900 truncate">{{ $member->name }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider border {{ $roleBadgeClass }}">
                                        {{ $roleLabel }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- EXECUTIVE ACCEPTANCE COMMAND CARD -->
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-200/70 text-[#c3122e] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Leadership Sign-Off</h3>
                                <span class="text-[10px] text-slate-400 font-medium">Accept Operational Authority</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 font-normal leading-relaxed">
                            Accepting leadership establishes your operational authority over deliverables, team assignment, timeline scheduling, and budget governance.
                        </p>

                        <div class="space-y-2 pt-2 border-t border-slate-100">
                            <!-- Primary Accept Button -->
                            <button
                                wire:click="acceptAssignment"
                                type="button"
                                class="w-full py-3 px-5 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] active:scale-95 transition-all flex items-center justify-center gap-2 shadow-xs cursor-pointer"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Accept Leadership &amp; Unlock Workspace →</span>
                            </button>

                            <!-- Decline / Full Brief Buttons -->
                            <div class="grid grid-cols-2 gap-2">
                                <button
                                    wire:click="openRejectionModal"
                                    type="button"
                                    class="py-2 px-3 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50/80 hover:bg-rose-100 border border-rose-200/70 transition-all cursor-pointer active:scale-95 text-center"
                                >
                                    ✕ Decline &amp; Report
                                </button>
                                <button
                                    wire:click="openProjectDetailsModal"
                                    type="button"
                                    class="py-2 px-3 rounded-xl text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer active:scale-95 text-center shadow-2xs"
                                >
                                    Full Brief
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- ===== 2. HERO GANTT SCHEDULE COMPONENT (ALWAYS DISPLAYED AT TOP) ===== -->
        <div class="mb-3">
            <livewire:gantt-chart :project="$project" />
        </div>

        <!-- ===== 3. EXECUTIVE SEGMENTED TABS CONTROLS ===== -->
        <div class="bg-white/95 backdrop-blur-xs p-1.5 rounded-2xl border border-slate-200/90 shadow-2xs mb-3 flex items-center justify-between gap-2 overflow-hidden">
            <div class="flex items-center gap-1 sm:gap-1.5 overflow-x-auto scrollbar-none p-0.5 w-full">
                @foreach([
                    'wbs'       => ['Task List', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    'kanban'    => ['Kanban', 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7'],
                    'team'      => ['Team Roster', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    'updates'   => ['Status Updates', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    'approvals' => ['Approvals', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'risks'     => ['Risks & Blockers', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                ] as $tabKey => [$tabLabel, $tabIcon])
                    <button
                        wire:click="$set('activeTab', '{{ $tabKey }}')"
                        type="button"
                        class="flex items-center gap-2 px-3 sm:px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-150 cursor-pointer flex-shrink-0 {{ $activeTab === $tabKey ? 'text-white shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/90 bg-transparent' }}"
                        @if($activeTab === $tabKey) style="background: linear-gradient(135deg, #c3122e 0%, #9e0f26 100%);" @endif
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ $activeTab === $tabKey ? 'text-rose-100' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcon }}"/>
                        </svg>
                        <span>{{ $tabLabel }}</span>
                        @if($tabKey === 'team')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold font-mono {{ $activeTab === $tabKey ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700 border border-slate-200/60' }} shadow-2xs">
                                {{ $project->members->count() }}
                            </span>
                        @endif
                        @if($tabKey === 'approvals')
                            @php
                                $pendingProjectApprovals = $project->approvalRequests->where('status', \App\Enums\ApprovalStatus::PENDING)->count();
                                $isPendingPmAcceptance = !$project->isPmAccepted() && $project->project_manager_id === auth()->id();
                                $approvalsTabCount = $pendingProjectApprovals + ($isPendingPmAcceptance ? 1 : 0);
                            @endphp
                            @if($approvalsTabCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === $tabKey ? 'bg-white text-[#c3122e]' : 'bg-[#c3122e] text-white' }} shadow-2xs animate-pulse">
                                    {{ $approvalsTabCount }}
                                </span>
                            @endif
                        @endif
                        @if($tabKey === 'risks' && $project->risks->count() > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $activeTab === $tabKey ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 border border-amber-200/60' }} shadow-2xs">
                                {{ $project->risks->count() }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- ===== 4. TAB CONTENTS ===== -->
        <!-- 1. DEDICATED TEAM ROSTER TAB -->
        @if($activeTab === 'team')
            @php
                $canViewTeam   = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'team.view');
                $canAddTeam    = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'team.add');
                $canRemoveTeam = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'team.remove');
                $canViewMember = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'team.view_member');
                $otherMembers  = $project->members->reject(fn($m) => $m->id === $project->project_manager_id);
                $pmUser        = $project->projectManager;
            @endphp
            @if(!$canViewTeam)
                <div class="p-8 rounded-2xl bg-white border border-slate-200 text-center space-y-3">
                    <p class="text-sm font-bold text-slate-700">Access Restricted</p>
                    <p class="text-xs text-slate-500">You do not have permission to view the team roster for this project.</p>
                </div>
            @else
            <div class="space-y-5" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                
                <!-- Main Clean Container Card -->
                <div class="bg-white rounded-3xl border border-slate-200/90 shadow-2xs overflow-hidden">
                    
                    <!-- 1. Clean Top Header & Action -->
                    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white flex items-center justify-center shadow-md shadow-rose-600/20 border border-white/20">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-black text-slate-900 tracking-tight">Project Team Roster</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10.5px] font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                                        {{ $project->members->count() }} Active Member{{ $project->members->count() !== 1 ? 's' : '' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">Assigned cross-functional delivery team and governance authorities</p>
                            </div>
                        </div>

                        @if($canAddTeam)
                            <button 
                                wire:click="openCollaboratorsModal" 
                                type="button"
                                class="px-4.5 py-2.5 rounded-xl text-xs font-black text-white shadow-sm hover:shadow-md hover:scale-102 transition-all cursor-pointer flex items-center justify-center gap-2"
                                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                <span>Add Collaborator</span>
                            </button>
                        @endif
                    </div>

                    <div class="p-6 space-y-5">
                        <!-- 3. Primary Project Lead Card -->
                        <div class="space-y-2">
                            <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block px-1">Project Leadership</span>
                            
                            @php
                                $pmTasksCount = $pmUser ? \App\Models\WbsItem::where('project_id', $project->id)->where('assigned_user_id', $pmUser->id)->count() : 0;
                            @endphp

                            <div class="p-5 rounded-2xl border-2 border-rose-200/90 bg-gradient-to-r from-rose-50/70 via-slate-50/40 to-white shadow-2xs hover:shadow-md transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="w-13 h-13 rounded-2xl bg-gradient-to-br from-[#c3122e] to-[#7a0a1a] text-white font-black text-lg flex items-center justify-center shadow-md shadow-rose-600/25 border-2 border-white">
                                            {{ strtoupper(substr($pmUser->name ?? 'P', 0, 1)) }}
                                        </div>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-base font-black text-slate-900 leading-tight">{{ $pmUser->name ?? 'Unassigned' }}</h4>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#c3122e] text-white shadow-2xs">
                                                LEAD PM
                                            </span>
                                            @if($isPm)
                                                <span class="px-2 py-0.5 rounded-md text-[9.5px] font-black uppercase tracking-wider bg-rose-100 text-[#c3122e] border border-rose-300">
                                                    ★ You
                                                </span>
                                            @endif
                                        </div>
                                        <a href="mailto:{{ $pmUser->email ?? '' }}" class="text-xs text-slate-500 hover:text-[#c3122e] hover:underline font-medium block truncate mt-0.5">
                                            {{ $pmUser->email ?? 'No email' }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 flex-shrink-0 self-start sm:self-center">
                                    @if($pmTasksCount > 0)
                                        <span class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                            📋 {{ $pmTasksCount }} Task{{ $pmTasksCount !== 1 ? 's' : '' }}
                                        </span>
                                    @endif

                                    <span class="text-xs font-bold text-[#c3122e] bg-white px-3.5 py-1.5 rounded-xl border border-rose-200 font-mono shadow-2xs">
                                        {{ $pmUser->subsidiary->code ?? ($project->subsidiary->code ?? 'GS') }} Corp
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Collaborators Squad Section -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between px-1">
                                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Team Collaborators</span>
                                <span class="text-[11px] font-semibold text-slate-400">{{ $otherMembers->count() }} Assigned</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5">
                                @forelse($otherMembers as $member)
                                    @php
                                        $isMe = ($member->id === $currentUser->id);
                                        $role = $member->pivot->role ?? 'member';
                                        $roleLabel = match($role) {
                                            'sponsor' => 'Sponsor',
                                            'owner' => 'Project Owner',
                                            'steering_committee' => 'Committee',
                                            'lead' => 'Team Lead',
                                            default => 'Team Member'
                                        };
                                        $roleBadgeClass = match($role) {
                                            'sponsor' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'owner' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'steering_committee' => 'bg-purple-50 text-purple-800 border-purple-200',
                                            'lead' => 'bg-rose-50 text-[#c3122e] border-rose-200 font-bold',
                                            default => 'bg-blue-50 text-blue-800 border-blue-200'
                                        };
                                        $memberTasksCount = \App\Models\WbsItem::where('project_id', $project->id)->where('assigned_user_id', $member->id)->count();
                                    @endphp

                                    <div class="rounded-2xl border {{ $isMe ? 'border-rose-300 bg-rose-50/30 ring-2 ring-rose-500/15' : 'border-slate-200/90 bg-white' }} p-4 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between space-y-3">
                                        <!-- Top Row: Role + Remove Action -->
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border {{ $roleBadgeClass }}">
                                                {{ $roleLabel }}
                                            </span>

                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[9.5px] font-mono font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                                    {{ $member->subsidiary->code ?? 'GS' }}
                                                </span>

                                                @if($canRemoveTeam)
                                                    <button 
                                                        wire:click="removeCollaborator({{ $member->id }})" 
                                                        wire:confirm="Remove {{ $member->name }} from this project team?"
                                                        class="w-6 h-6 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all cursor-pointer" 
                                                        title="Remove member"
                                                    >
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Center: Avatar + Info -->
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl {{ $isMe ? 'bg-[#c3122e] text-white' : 'bg-slate-800 text-white' }} font-black text-xs flex items-center justify-center flex-shrink-0 border border-white shadow-2xs">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <h5 class="text-xs font-black text-slate-900 truncate leading-tight">{{ $member->name }}</h5>
                                                    @if($isMe)
                                                        <span class="px-1.5 py-0.2 rounded text-[8.5px] font-black uppercase tracking-wider bg-rose-100 text-[#c3122e] border border-rose-200">
                                                            You
                                                        </span>
                                                    @endif
                                                </div>
                                                <a href="mailto:{{ $member->email }}" class="text-[11px] text-slate-400 hover:text-[#c3122e] hover:underline font-medium truncate block mt-0.5">
                                                    {{ $member->email }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Bottom: Task stats -->
                                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[10.5px] text-slate-500 font-medium">
                                            <span>Task Scope</span>
                                            <span class="font-bold text-slate-800 font-mono">{{ $memberTasksCount }} Assigned</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-8 rounded-2xl bg-slate-50/80 border border-dashed border-slate-300 space-y-2">
                                        <p class="text-xs font-bold text-slate-600">No additional team members attached</p>
                                        <p class="text-[11px] text-slate-400">Click "+ Add Collaborator" above to assign members to this project squad.</p>
                                    </div>
                                @endforelse

                                <!-- Add Collaborator Dashed Quick Card -->
                                @if($canAddTeam && $otherMembers->isNotEmpty())
                                    <button
                                        wire:click="openCollaboratorsModal"
                                        type="button"
                                        class="rounded-2xl border-2 border-dashed border-slate-200 hover:border-[#c3122e] hover:bg-rose-50/40 p-4 transition-all flex flex-col items-center justify-center text-center space-y-1.5 cursor-pointer group min-h-[120px]"
                                    >
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-[#c3122e] text-slate-500 group-hover:text-white flex items-center justify-center transition-all shadow-2xs">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 group-hover:text-[#c3122e] transition-colors">Add Collaborator</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            @endif
        @endif

    <!-- 2. WBS PLAN TAB -->
    @if($activeTab === 'wbs')
        <livewire:wbs-tree :project="$project" />
    @endif

    <!-- 3. KANBAN TAB -->
    @if($activeTab === 'kanban')
        <livewire:kanban-board :project="$project" />
    @endif

    <!-- 5. STATUS UPDATES TAB -->
    @if($activeTab === 'updates')
        <div class="space-y-6">
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
            <div class="card">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Publish New Status Update</h3>
                </div>
                <form wire:submit="publishStatusUpdate" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Update Title</label>
                        <input type="text" wire:model="statusTitle" placeholder="e.g. Sprint 4 Completion Report" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Summary</label>
                        <textarea wire:model="statusSummary" rows="3" placeholder="Describe current progress and status..." class="form-input"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Work Completed</label>
                            <textarea wire:model="workCompleted" rows="2" placeholder="Achievements this period..." class="form-input"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Next Steps</label>
                            <textarea wire:model="nextSteps" rows="2" placeholder="Planned activities..." class="form-input"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Publish Status Update</button>
                </form>
            </div>
            @endif

            <div class="card">
                <h3 class="font-bold text-slate-900 text-sm mb-4">Status Update Timeline</h3>
                <div class="space-y-4">
                    @forelse($project->statusUpdates as $u)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-slate-900 text-xs">{{ $u->title }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $u->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mb-2">{{ $u->summary }}</p>
                            @if($u->work_completed)
                                <p class="text-[11px] text-emerald-700 bg-emerald-50 p-2 rounded-lg border border-emerald-100"><strong>✓ Completed:</strong> {{ $u->work_completed }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs">No status updates recorded yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- 6. DOCUMENTS TAB -->
    @if($activeTab === 'documents')
        <div class="space-y-6">
            <div class="card">
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center">
                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-sm">Upload Document</h3>
                </div>
                <form wire:submit="uploadDocument" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">File</label>
                        <input type="file" wire:model="documentFile" class="form-input text-xs">
                        @error('documentFile') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description (optional)</label>
                        <input type="text" wire:model="docDescription" placeholder="Brief description of this document..." class="form-input">
                    </div>
                    <button type="submit" class="btn-primary">Upload to Secured Storage</button>
                </form>
            </div>

            <div class="card">
                <h3 class="font-bold text-slate-900 text-sm mb-4">Project Files</h3>
                <div class="space-y-3">
                    @forelse($project->documents as $doc)
                        <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                            <div class="flex items-center gap-3">
                                <div wire:click="openPreview({{ $doc->id }})" class="w-9 h-9 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 cursor-pointer hover:scale-105 transition-transform" title="Click to View Document">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <button wire:click="openPreview({{ $doc->id }})" class="text-xs font-bold text-slate-900 hover:text-[#c3122e] hover:underline cursor-pointer block text-left">
                                        {{ $doc->original_name }}
                                    </button>
                                    <p class="text-[10px] text-slate-500">Uploaded by {{ $doc->uploader->name ?? 'User' }} • {{ round($doc->file_size/1024, 1) }} KB</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="openPreview({{ $doc->id }})" class="btn-secondary btn-sm text-xs flex items-center gap-1 cursor-pointer" title="View Document without downloading">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View
                                </button>
                                <a href="{{ route('documents.download', $doc) }}" class="btn-primary btn-sm text-xs flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                                @if($doc->uploaded_by === auth()->id() || auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="deleteDocument({{ $doc->id }})"
                                            wire:confirm="Are you sure you want to delete this document?"
                                            class="p-2 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="Delete Document">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">
                            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <p class="text-xs">No documents uploaded yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- 7. DISCUSSION TAB -->
    @if($activeTab === 'comments')
        <div class="card">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 rounded-lg bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Project Discussion Thread</h3>
            </div>

            <form wire:submit="postComment" class="mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#c3122e] to-[#8b0d1f] text-white font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <textarea wire:model="commentContent" rows="2" placeholder="Write a comment, question, or update..." class="form-input mb-2 text-xs"></textarea>
                        <button type="submit" class="btn-primary btn-sm text-xs">Post Comment</button>
                    </div>
                </div>
            </form>

            <div class="space-y-4">
                @forelse($project->comments as $c)
                    <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-600 to-slate-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($c->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-900 text-xs">{{ $c->user->name ?? 'User' }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-700 leading-relaxed">{{ $c->content }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">No comments yet — start the discussion!</div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- 8. RISKS & BLOCKERS TAB -->
    @if($activeTab === 'risks')
        <div class="space-y-7">

            <!-- 2. Risk Register Form Card -->
            @if($project->userCan(auth()->user(), 'risk.create'))
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black text-sm shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Report New Project Risk</h3>
                        <p class="text-xs text-slate-500 font-medium">Identify risks and link them directly to specific WBS tasks or scope modules</p>
                    </div>
                </div>

                <form wire:submit="addRisk" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="form-group sm:col-span-2">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" placeholder="e.g. Third-party API Payment Gateway Integration Delay" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                            @error('riskTitle') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Category</label>
                            <select wire:model="riskCategory" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Component / WBS Task Identification Selector -->
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs flex items-center justify-between">
                            <span>📍 Affected Scope Component / WBS Task (Identity Location)</span>
                            <span class="text-[10px] text-[#c3122e] font-black uppercase">Links Risk to Exact Section</span>
                        </label>
                        <select wire:model="riskWbsItemId" class="form-select text-xs font-extrabold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e] bg-slate-50">
                            <option value="">🌐 [General Project Level Scope]</option>
                            @foreach($project->wbsItems as $wbs)
                                <option value="{{ $wbs->id }}">
                                    📍 Code: {{ $wbs->wbs_code }} — {{ $wbs->title }} ({{ $wbs->item_type->value }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-slate-500 mt-1 font-medium">Select the specific WBS task or module where this risk originates, or choose General Project Scope.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Probability & Impact</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block mb-1">Probability</span>
                                    <select wire:model="riskProbability" class="form-select text-xs font-bold py-2 rounded-xl">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                    </select>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 block mb-1">Impact</span>
                                    <select wire:model="riskImpact" class="form-select text-xs font-bold py-2 rounded-xl">
                                        <option value="low">Low (1)</option>
                                        <option value="medium">Medium (2)</option>
                                        <option value="high">High (3)</option>
                                        <option value="critical">Critical (4)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Mitigation Plan (Optional)</label>
                            <input type="text" wire:model="riskMitigation" placeholder="Outline steps to prevent or minimize impact..." class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Risk Description & Impact Analysis <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" placeholder="Detailed analysis of what could go wrong and potential consequences..." class="form-input text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                        @error('riskDescription') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 transition-all cursor-pointer">
                            <span>Add Risk to Project Log</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- 3. Risk Log List Card -->
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base">Project Risk Register Log</h3>
                    <a href="{{ route('risks.index', ['project' => $project->id]) }}" class="px-3.5 py-1.5 rounded-xl text-xs font-black text-[#c3122e] bg-[#fdf4f4] border border-[#faeaea] hover:bg-[#faeaea] transition-all flex items-center gap-1.5">
                        <span>Open Executive Risk Matrix &amp; Hub</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($project->risks as $r)
                        @php
                            $riskStatusColors = [
                                'open' => 'bg-rose-50 text-rose-900 border-rose-300',
                                'monitoring' => 'bg-amber-50 text-amber-900 border-amber-300',
                                'mitigated' => 'bg-emerald-50 text-emerald-900 border-emerald-300',
                                'closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                            ];
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/90 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="flex-1 min-w-0 space-y-2">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="font-black text-slate-900 text-xs">{{ $r->title }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 text-slate-700">{{ $r->category }}</span>
                                    
                                    <!-- AFFECTED COMPONENT BADGE -->
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                                        @if($r->wbsItem)
                                            📍 Component: {{ $r->wbsItem->title }} (Code: {{ $r->wbsItem->wbs_code }})
                                        @else
                                            🌐 Scope: General Project Level
                                        @endif
                                    </span>
                                </div>

                                <p class="text-xs text-slate-700 font-medium leading-relaxed">{{ $r->description }}</p>

                                @if($r->mitigation_plan || $r->contingency_plan)
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                                        @if($r->mitigation_plan)
                                            <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 text-emerald-900">
                                                <strong>Mitigation:</strong> {{ $r->mitigation_plan }}
                                            </div>
                                        @endif
                                        @if($r->contingency_plan)
                                            <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-200/80 text-amber-900">
                                                <strong>Contingency:</strong> {{ $r->contingency_plan }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 flex-shrink-0 self-end sm:self-start">
                                <div class="text-center">
                                    <span class="px-3 py-1 rounded-xl text-xs font-black bg-amber-100 text-amber-900 border border-amber-200 block">
                                        Score: {{ $r->risk_score }}/12
                                    </span>
                                </div>

                                @if($project->userCan(auth()->user(), 'risk.edit'))
                                    <select
                                        wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                        class="text-xs font-extrabold rounded-full px-3 py-1 border cursor-pointer {{ $riskStatusColors[$r->status] ?? 'bg-slate-100 text-slate-700' }}"
                                    >
                                        <option value="open" @selected($r->status === 'open')>Open</option>
                                        <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                        <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                        <option value="closed" @selected($r->status === 'closed')>Closed</option>
                                    </select>

                                    <button wire:click="openEditRiskModal({{ $r->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Edit Risk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                @endif

                                @if($project->userCan(auth()->user(), 'risk.delete'))
                                    <button wire:click="deleteRisk({{ $r->id }})" wire:confirm="Are you sure you want to delete this risk record?" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete Risk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 text-xs font-medium">No risks recorded in the project log.</div>
                    @endforelse
                </div>
            </div>
        </div>



        <!-- Edit Risk Modal -->
        <div x-data="{ open: @entangle('showEditRiskModal') }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        >
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showEditRiskModal = false"></div>

            <div class="relative bg-white rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 border border-slate-200/90 z-10">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-black">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Edit Risk Record</h3>
                            <p class="text-xs text-slate-500 font-medium">Update assessment ratings and mitigation plans</p>
                        </div>
                    </div>
                    <button type="button" @click="open = false; $wire.showEditRiskModal = false" class="text-slate-400 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="updateRisk" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="form-label font-extrabold text-slate-800 text-xs">Risk Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="riskTitle" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Category</label>
                            <select wire:model="riskCategory" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e]">
                                <option value="Technical">Technical</option>
                                <option value="Financial">Financial</option>
                                <option value="Schedule">Schedule</option>
                                <option value="Resource">Resource</option>
                                <option value="External">External Vendor</option>
                            </select>
                        </div>
                    </div>

                    <!-- Component / WBS Task Selector in Edit Modal -->
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">📍 Affected Component / Scope Task</label>
                        <select wire:model="riskWbsItemId" class="form-select text-xs font-extrabold py-2.5 rounded-xl border-slate-200">
                            <option value="">🌐 [General Project Level Scope]</option>
                            @foreach($project->wbsItems as $wbs)
                                <option value="{{ $wbs->id }}">
                                    📍 Code: {{ $wbs->wbs_code }} — {{ $wbs->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="form-label text-[10px] font-bold text-slate-400">Probability</label>
                                <select wire:model="riskProbability" class="form-select text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-[10px] font-bold text-slate-400">Impact</label>
                                <select wire:model="riskImpact" class="form-select text-xs font-bold py-2 rounded-xl">
                                    <option value="low">Low (1)</option>
                                    <option value="medium">Medium (2)</option>
                                    <option value="high">High (3)</option>
                                    <option value="critical">Critical (4)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-xs font-extrabold text-slate-800">Status</label>
                            <select wire:model="riskStatus" class="form-select text-xs font-bold py-2 rounded-xl">
                                <option value="open">Open</option>
                                <option value="monitoring">Monitoring</option>
                                <option value="mitigated">Mitigated</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Mitigation Plan</label>
                        <input type="text" wire:model="riskMitigation" class="form-input text-xs py-2 rounded-xl">
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Risk Description <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" class="form-input text-xs rounded-xl p-2.5"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showEditRiskModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md">Update Risk</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- 9. APPROVALS TAB -->
    @if($activeTab === 'approvals')
        <div class="space-y-6">
            <!-- PENDING LEADERSHIP ACCEPTANCE ALERT IN APPROVALS TAB -->
            @if(!$project->isPmAccepted() && $project->project_manager_id === auth()->id())
                <div class="p-5 sm:p-6 rounded-2xl bg-white border border-amber-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200/70 text-amber-700 flex items-center justify-center shrink-0 shadow-2xs">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-bold text-slate-900">Project Leadership Acceptance Required</h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200/80">ACTION NEEDED</span>
                            </div>
                            <p class="text-xs text-slate-500 font-normal mt-0.5">You have been designated as Project Leader for this project. Please accept leadership to unlock and manage workspaces.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button wire:click="openRejectionModal" type="button" class="px-4 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-white hover:bg-rose-50 border border-rose-200/80 shadow-2xs cursor-pointer transition-all active:scale-95">
                            ✕ Decline
                        </button>
                        <button wire:click="acceptAssignment" type="button" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a50e26] shadow-xs cursor-pointer flex items-center gap-1.5 active:scale-95 transition-all">
                            <span>✓ Accept Leadership →</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Submit Approval Request Form (Available to all 3 roles: Admin, PM, Team Member) --}}
            @if($project->userCan(auth()->user(), 'approval.submit'))
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] text-[#c3122e] flex items-center justify-center font-black text-sm shadow-2xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">Submit Formal Approval Request</h3>
                            <p class="text-xs text-slate-500 font-medium">Request sign-off for task completion, deadline extension, baseline, or scope changes</p>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="submitApprovalRequest" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label text-xs font-bold text-slate-800">Request Type <span class="text-rose-500">*</span></label>
                            <select wire:model.live="reqType" class="form-select text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                @foreach(\App\Enums\ApprovalType::cases() as $t)
                                    <option value="{{ $t->value }}">{{ $t->label() }}</option>
                                @endforeach
                            </select>
                            @error('reqType') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        {{-- Conditional: Deadline Extension --}}
                        @if($reqType === 'deadline_extension')
                            <div class="form-group">
                                <label class="form-label text-xs font-bold text-slate-800">Requested New Deadline <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="reqValue" min="{{ now()->addDay()->format('Y-m-d') }}" class="form-input text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                @if($project->deadline)
                                    <p class="text-[10px] text-slate-400 mt-1">Current deadline: <strong>{{ $project->deadline->format('M d, Y') }}</strong></p>
                                @endif
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        {{-- Conditional: Budget Change --}}
                        @if($reqType === 'budget_change')
                            <div class="form-group">
                                <label class="form-label text-xs font-bold text-slate-800">Requested New Budget (LKR) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs font-bold">Rs.</span>
                                    <input type="number" wire:model="reqValue" min="0" step="1000" placeholder="0" class="form-input pl-9 text-xs font-bold py-2.5 rounded-xl border-slate-200">
                                </div>
                                @if($project->estimated_budget)
                                    <p class="text-[10px] text-slate-400 mt-1">Current budget: <strong>Rs. {{ number_format($project->estimated_budget, 0) }}</strong></p>
                                @endif
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        {{-- Conditional: Scope Change --}}
                        @if($reqType === 'scope_change')
                            <div class="form-group sm:col-span-2">
                                <label class="form-label text-xs font-bold text-slate-800">Scope Change Description <span class="text-rose-500">*</span></label>
                                <textarea wire:model="reqValue" rows="2" placeholder="Describe the scope change in detail..." class="form-input text-xs rounded-xl border-slate-200"></textarea>
                                @error('reqValue') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label text-xs font-bold text-slate-800">Reason / Justification <span class="text-rose-500">*</span></label>
                        <textarea wire:model="reqReason" rows="3" placeholder="Provide clear reasoning for this approval request..." class="form-input text-xs rounded-xl border-slate-200"></textarea>
                        @error('reqReason') <span class="form-error text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <p class="text-[11px] text-slate-400 font-medium">Submitted requests will be logged and routed for governance sign-off</p>
                        <button type="submit" wire:loading.attr="disabled" class="btn text-xs font-bold text-white px-4 py-2.5 rounded-xl shadow-md cursor-pointer" style="background:#c3122e">
                            <span>Submit Approval Request</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- Approval Requests History Card --}}
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base">Project Approval Requests &amp; Governance History</h3>
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700">
                        {{ $project->approvalRequests->count() }} Request(s)
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($project->approvalRequests->sortByDesc('created_at') as $req)
                        @php
                            $rv = $req->requested_value ?? [];
                            $reqValueStr = match($req->request_type->value) {
                                'deadline_extension' => isset($rv['new_deadline']) ? '→ New Deadline: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y') : null,
                                'budget_change'      => isset($rv['new_budget']) ? '→ New Budget: Rs. ' . number_format($rv['new_budget'], 0) : null,
                                'scope_change'       => isset($rv['scope_description']) ? $rv['scope_description'] : null,
                                default              => null,
                            };
                            $statusCls = match($req->status->value ?? '') {
                                'approved'          => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected'          => 'bg-rose-50 text-rose-700 border-rose-200',
                                'revision_required' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                'cancelled'         => 'bg-slate-50 text-slate-500 border-slate-200',
                                default             => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-200/80 space-y-3 hover:border-slate-300 transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea]">
                                            {{ $req->request_type->label() }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $req->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-800 font-semibold leading-relaxed mt-1">{{ $req->reason }}</p>
                                    @if($reqValueStr)
                                        <div class="mt-1.5 inline-block text-[11px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-lg">
                                            {{ $reqValueStr }}
                                        </div>
                                    @endif
                                    <p class="text-[10px] text-slate-500 mt-2 font-medium">
                                        Submitted by <strong>{{ $req->requester->name ?? 'User' }}</strong> • {{ $req->created_at->format('M d, Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusCls }}">
                                        {{ $req->status->label() }}
                                    </span>

                                    {{-- Actions: Approve/Reject only if user has approval.approve permission; Withdraw if requester --}}
                                    @if($req->status->value === 'pending')
                                        @if($project->userCan(auth()->user(), 'approval.approve'))
                                            <button wire:click="approveRequestInWorkspace({{ $req->id }})"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs cursor-pointer transition-all">
                                                Approve
                                            </button>
                                            <button wire:click="rejectRequestInWorkspace({{ $req->id }})"
                                                    class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-2xs cursor-pointer transition-all">
                                                Reject
                                            </button>
                                        @elseif($req->requested_by === auth()->id())
                                            <button wire:click="cancelRequestInWorkspace({{ $req->id }})"
                                                    wire:confirm="Withdraw this approval request?"
                                                    class="px-2.5 py-1 rounded-lg text-xs font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 cursor-pointer">
                                                Withdraw
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            {{-- Show reviewer feedback if reviewed --}}
                            @if($req->reviewer && $req->review_comment)
                                <div class="pt-2 border-t border-slate-200/80 text-[11px]">
                                    <span class="font-bold text-slate-700">
                                        {{ $req->status->value === 'approved' ? 'âœ… Approved' : 'âŒ Rejected' }} by {{ $req->reviewer->name }}:
                                    </span>
                                    <span class="text-slate-600 italic ml-1">"{{ $req->review_comment }}"</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-xs font-extrabold text-slate-800">No Approval Requests for this Project</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Use the form above to submit a new formal sign-off or change request.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
    @endif

    {{-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
         IN-APP DOCUMENT PREVIEW MODAL
    â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• --}}
    <div x-data="{ open: @entangle('showPreviewModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.closePreview()"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-3xl z-10 overflow-y-auto max-h-[92vh]"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            @if($previewDoc)
                {{-- Header --}}
                <div class="flex items-center justify-between p-5 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-xs uppercase">
                            {{ $previewFileType }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 truncate max-w-md">{{ $previewDoc->original_name }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Project: <strong class="text-slate-700">{{ $previewDoc->project->name ?? 'Global' }}</strong> â€¢ Uploaded by {{ $previewDoc->uploader->name ?? 'User' }} â€¢ {{ round($previewDoc->file_size/1024, 1) }} KB
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('documents.download', $previewDoc) }}" class="btn-primary btn-sm text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                        <button type="button" @click="open = false; $wire.closePreview()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Content Preview Body --}}
                <div class="p-6">
                    @if(in_array($previewFileType, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg']))
                        <div class="flex justify-center bg-slate-50 p-4 rounded-xl border border-slate-200">
                            <img src="{{ route('documents.view', $previewDoc) }}" alt="{{ $previewDoc->original_name }}" class="max-h-[60vh] object-contain rounded-lg shadow-sm">
                        </div>
                    @elseif($previewFileType === 'pdf')
                        <iframe src="{{ route('documents.view', $previewDoc) }}" class="w-full h-[68vh] rounded-xl border border-slate-200"></iframe>
                    @elseif(in_array($previewFileType, ['docx', 'doc']))
                        <div x-init="window.renderDocxPreview('{{ route('documents.view', $previewDoc) }}', 'docx-preview-pws-{{ $previewDoc->id }}')"
                             class="bg-slate-100 p-4 rounded-xl border border-slate-200 max-h-[68vh] overflow-y-auto">
                            <div id="docx-preview-pws-{{ $previewDoc->id }}" class="w-full min-h-[400px]"></div>
                        </div>
                    @elseif($previewContent)
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 max-h-[60vh] overflow-y-auto font-sans text-slate-800 text-xs leading-relaxed space-y-3 whitespace-pre-wrap select-text">
                            <div class="flex items-center gap-2 pb-3 mb-3 border-b border-slate-200 text-slate-500 font-semibold text-[11px]">
                                <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Extracted Document Content Preview
                            </div>
                            {{ $previewContent }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-slate-50 rounded-xl border border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] mx-auto mb-3 font-bold text-sm uppercase">
                                {{ $previewFileType }}
                            </div>
                            <p class="text-slate-800 font-bold text-sm mb-1">{{ $previewDoc->original_name }}</p>
                            <p class="text-slate-500 text-xs mb-4">Direct in-app visual preview not available for this binary file format.</p>
                            <a href="{{ route('documents.download', $previewDoc) }}" class="btn-primary btn-sm inline-flex items-center gap-1.5">
                                Download File to View
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Financial Summary & Hours Modal -->
    <div x-data="{ open: @entangle('showFinancialsModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showFinancialsModal = false"></div>

        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Update Financial Summary & Hours</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Directly update project expenditure and labor hours tracking</p>
                </div>
            </div>

            <form wire:submit="saveFinancialsAndHours" class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Actual Cost (LKR / Rs.) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                        <input type="number" step="0.01" wire:model="actualCostInput" placeholder="0.00" class="form-input pl-10 text-xs font-semibold">
                    </div>
                    @error('actualCostInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="form-group">
                        <label class="form-label">Estimated Hours (h)</label>
                        <input type="number" step="0.5" wire:model="estimatedHoursInput" placeholder="0" class="form-input text-xs font-semibold">
                        @error('estimatedHoursInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Actual Hours (h)</label>
                        <input type="number" step="0.5" wire:model="actualHoursInput" placeholder="0" class="form-input text-xs font-semibold">
                        @error('actualHoursInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                    <p class="font-bold text-slate-800">Automatic Sync Note:</p>
                    <p>â€¢ Estimated and Actual Hours also automatically aggregate from tasks created in the <strong>WBS Plan</strong>.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="open = false; $wire.showFinancialsModal = false" class="btn-secondary text-xs font-bold">Cancel</button>
                    <button type="submit" class="btn-primary text-xs font-bold flex items-center gap-1.5">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Timeline & Target Deadline Modal -->
    <div x-data="{ open: @entangle('showTimelineModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showTimelineModal = false"></div>

        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Set Project Timeline &amp; Target Deadline</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Project Owner governance to set or extend project completion target</p>
                </div>
            </div>

            <form wire:submit="saveTimeline" class="space-y-4">
                <div class="form-group">
                    <label class="form-label text-xs font-bold text-slate-800">Project Start Date</label>
                    <input type="date" wire:model="startDateInput" class="form-input text-xs font-semibold rounded-xl">
                    @error('startDateInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label text-xs font-bold text-slate-800">Target Completion Deadline <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="deadlineInput" class="form-input text-xs font-semibold rounded-xl" required>
                    @error('deadlineInput') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                    <p class="font-bold text-slate-800">Project Owner Governance:</p>
                    <p>• Setting an accurate target deadline helps track project velocity and alerts your team of upcoming due dates.</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="open = false; $wire.showTimelineModal = false" class="btn-secondary text-xs font-bold">Cancel</button>
                    <button type="submit" class="btn-primary text-xs font-bold flex items-center gap-1.5">
                        Save Deadline
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Details Modal (Comprehensive Specs) -->
    @if($showEditProjectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-5 bg-slate-900/70 backdrop-blur-md animate-in fade-in duration-200">
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-2xl z-10 p-6 sm:p-7 max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-base shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base sm:text-lg" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">Edit Project Details &amp; Specifications</h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Update project identity, governance status, schedule, budget, and scope</p>
                    </div>
                </div>
                <button wire:click="$set('showEditProjectModal', false)" type="button" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit="saveProjectDetails" class="space-y-5">
                {{-- 1. Project Identity Section --}}
                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3.5">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-wider">
                        <span class="text-[#c3122e]">🏷️</span>
                        <span>Project Identity &amp; Classification</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div class="sm:col-span-2 form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Project Title <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="editName" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs" placeholder="e.g. Hospital Modernization Project" required>
                            @error('editName') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Project Code</label>
                            <input type="text" wire:model="editCode" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-mono font-bold text-[#c3122e] focus:border-[#c3122e] outline-none shadow-2xs" placeholder="e.g. GSH-PRJ-001">
                            @error('editCode') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Subsidiary / Division</label>
                            <select wire:model="editSubsidiaryId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs cursor-pointer">
                                <option value="">-- No Subsidiary Designated --</option>
                                @foreach($allSubsidiaries as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            @error('editSubsidiaryId') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Strategic Category</label>
                            <input type="text" wire:model="editCategory" list="categories-list" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs" placeholder="e.g. Corporate Strategy">
                            <datalist id="categories-list">
                                <option value="Corporate Strategy">
                                <option value="IT Modernization">
                                <option value="Healthcare & Pharma">
                                <option value="Consumer Goods & Retail">
                                <option value="Financial Operations">
                                <option value="Logistics & Supply Chain">
                                <option value="Hospitality & Leisure">
                            </datalist>
                            @error('editCategory') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- 2. Governance, Status & Priority Section --}}
                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3.5">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-wider">
                        <span class="text-[#c3122e]">⚙️</span>
                        <span>Governance, Status &amp; Leadership</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Execution Status <span class="text-rose-500">*</span></label>
                            <select wire:model="editStatus" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs cursor-pointer" required>
                                @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                    <option value="{{ $st->value }}">{{ $st->label() }}</option>
                                @endforeach
                            </select>
                            @error('editStatus') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Priority Level <span class="text-rose-500">*</span></label>
                            <select wire:model="editPriority" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs cursor-pointer" required>
                                @foreach(\App\Enums\Priority::cases() as $pr)
                                    <option value="{{ $pr->value }}">{{ $pr->label() }} Priority</option>
                                @endforeach
                            </select>
                            @error('editPriority') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Project Manager (Leader)</label>
                        <select wire:model="editProjectManagerId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs cursor-pointer">
                            <option value="">-- No Leader Assigned --</option>
                            @foreach($allPms as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        @error('editProjectManagerId') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- 3. Timeline & Financial Budget Section --}}
                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-3.5">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-wider">
                        <span class="text-[#c3122e]">📅</span>
                        <span>Schedule Horizon &amp; Financial Budget</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Kick-off Date</label>
                            <input type="date" wire:model="editStartDate" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs">
                            @error('editStartDate') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label font-extrabold text-slate-800 text-xs mb-1 block">Target Completion Deadline</label>
                            <input type="date" wire:model="editDeadline" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs">
                            @error('editDeadline') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @php
                        $canEditEstimatedBudget = auth()->user()?->isPmoAdmin() || $project->userCan(auth()->user(), 'budget.edit');
                        $canEditActualCost = auth()->user()?->isPmoAdmin() || $project->userCan(auth()->user(), 'budget.edit');
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div class="form-group">
                            <div class="flex items-center justify-between mb-1">
                                <label class="form-label font-extrabold text-slate-800 text-xs block">Total Estimated Budget (LKR)</label>
                                @if(!$canEditEstimatedBudget)
                                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">Locked (No Permission)</span>
                                @endif
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                                <input type="number" step="1" wire:model="editEstimatedBudget" {{ !$canEditEstimatedBudget ? 'disabled' : '' }} placeholder="0" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 {{ !$canEditEstimatedBudget ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-900 focus:border-[#c3122e]' }} text-xs font-bold outline-none shadow-2xs">
                            </div>
                            @error('editEstimatedBudget') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <div class="flex items-center justify-between mb-1">
                                <label class="form-label font-extrabold text-slate-800 text-xs block">Actual Cost / Spend (LKR)</label>
                                @if(!$canEditActualCost)
                                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">Locked (No Permission)</span>
                                @endif
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                                <input type="number" step="1" wire:model="editActualCost" {{ !$canEditActualCost ? 'disabled' : '' }} placeholder="0" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 {{ !$canEditActualCost ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-white text-slate-900 focus:border-[#c3122e]' }} text-xs font-bold outline-none shadow-2xs">
                            </div>
                            @error('editActualCost') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- 4. Scope Description Section --}}
                <div class="bg-slate-50/70 p-4 sm:p-5 rounded-2xl border border-slate-200/80 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-black text-slate-700 uppercase tracking-wider">
                        <span class="text-[#c3122e]">📝</span>
                        <span>Executive Scope &amp; Deliverables</span>
                    </div>
                    <textarea wire:model="editDescription" rows="4" placeholder="Enter detailed project scope, strategic deliverables, milestones, and deliverables overview..." class="w-full p-3.5 rounded-xl border border-slate-200 bg-white text-xs leading-relaxed font-normal text-slate-900 focus:border-[#c3122e] outline-none shadow-2xs"></textarea>
                    @error('editDescription') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="$set('showEditProjectModal', false)" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all shadow-2xs">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25 cursor-pointer active:scale-95 transition-all flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Save All Project Details</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Manage & Add Team Members / Collaborators Modal -->
    @if($showCollaboratorsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-in fade-in duration-200">
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-xl z-10 p-6 sm:p-7 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-sm shadow-2xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-black text-slate-900 text-base">Project Team &amp; Collaborators</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Attach team members to collaborate and assign tasks</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showCollaboratorsModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Action Bar: Search & Quick Create Toggle -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="collaboratorSearch" placeholder="Search team members by name or email..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-semibold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button wire:click="$toggle('showCreateCollaboratorSection')" type="button" class="px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer flex-shrink-0">
                    <span>{{ $showCreateCollaboratorSection ? '✕ Cancel Create' : '✨ + Add New Staff Member' }}</span>
                </button>
            </div>

            <!-- Optional: Quick Create Collaborator Form -->
            @if($showCreateCollaboratorSection)
                <div class="mb-5 p-4 rounded-2xl bg-amber-50/80 border border-amber-200/90 space-y-3">
                    <h4 class="font-extrabold text-amber-900 text-xs flex items-center gap-1.5">
                        <span>🚀 Create &amp; Attach New Staff Account</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Full Name <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="newCollabName" placeholder="e.g. Ruwan Perera" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]">
                            @error('newCollabName') <span class="text-[10px] text-rose-600 font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" wire:model="newCollabEmail" placeholder="e.g. ruwan@georgesteuart.com" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]">
                            @error('newCollabEmail') <span class="text-[10px] text-rose-600 font-bold mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Temporary Login Password</label>
                            <input type="text" wire:model="newCollabTempPassword" placeholder="Password@123" class="w-full px-3 py-2 rounded-xl border border-amber-300 bg-amber-50/50 text-xs font-mono font-bold text-amber-900 outline-none focus:border-[#c3122e]">
                            <span class="text-[10px] text-amber-800 font-medium mt-0.5 block">🔑 Default: <strong class="font-bold font-mono">Password@123</strong> (Editable)</span>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Project Role <span class="text-rose-500">*</span></label>
                            <select wire:model="newCollabRole" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]">
                                <option value="member">Core Team Member</option>
                                <option value="sponsor">Project Sponsor</option>
                                <option value="owner">Project Owner</option>
                                <option value="steering_committee">Steering Committee</option>
                                <option value="collaborator">Collaborator / Specialist</option>
                                <option value="lead">Project Manager / Lead PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button wire:click="quickCreateCollaborator" type="button" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <span>Create &amp; Attach to Project</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Collaborators Selection List -->
            <form wire:submit="saveCollaborators" class="space-y-4">
                <div class="text-xs font-extrabold text-slate-700 mb-2">Select Staff Members &amp; Assign Roles for this Project:</div>

                <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    @php
                        $filteredUsers = $availableUsers->filter(function($u) {
                            if ($this->collaboratorSearch) {
                                return str_contains(strtolower($u->name), strtolower($this->collaboratorSearch)) || str_contains(strtolower($u->email), strtolower($this->collaboratorSearch));
                            }
                            return true;
                        });
                    @endphp

                    @forelse($filteredUsers as $u)
                        @php
                            $isOwner = ($u->id === $project->project_manager_id);
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-2xl border transition-all {{ in_array($u->id, $selectedCollaboratorIds) || $isOwner ? 'bg-[#fdf4f4]/60 border-[#faeaea]' : 'bg-slate-50/70 border-slate-200/80 hover:bg-slate-100/70' }}">
                            <label class="flex items-center gap-3 flex-1 cursor-pointer">
                                <input type="checkbox" wire:model="selectedCollaboratorIds" value="{{ $u->id }}" @if($isOwner) checked disabled @endif class="w-4 h-4 rounded text-[#c3122e] focus:ring-[#c3122e] border-slate-300">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 text-white font-black text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900 leading-tight">{{ $u->name }} @if($isOwner)<span class="text-[10px] text-[#c3122e] font-black ml-1">(Project Leader)</span>@endif</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $u->email }}</p>
                                </div>
                            </label>

                            <div class="flex items-center gap-2">
                                @if($isOwner)
                                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-[#fdf4f4] text-[#c3122e] border border-[#f5c2c9]">
                                        👑 Lead PM / Owner
                                    </span>
                                @else
                                    <select wire:model="collaboratorRoles.{{ $u->id }}" class="px-2.5 py-1 rounded-xl border border-slate-200 bg-white text-[11px] font-extrabold text-slate-800 outline-none focus:border-[#c3122e]">
                                        <option value="member">Core Team Member</option>
                                        <option value="sponsor">Project Sponsor</option>
                                        <option value="owner">Project Owner</option>
                                        <option value="steering_committee">Steering Committee</option>
                                        <option value="collaborator">Collaborator / Specialist</option>
                                        <option value="lead">Project Manager / Lead PM</option>
                                    </select>
                                @endif

                                @if(!$isOwner && auth()->id() !== $u->id && auth()->user()->hasRole('super_admin') && !$u->isPmoAdmin())
                                    <button wire:click="deleteUserFromSystem({{ $u->id }})" wire:confirm="Permanently delete user '{{ $u->name }}' from system &amp; remove from all project lists?" type="button" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Delete User from System">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 py-6">No matching staff members found</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <p class="text-[11px] text-slate-500 font-medium">Selected: <strong class="text-slate-900">{{ count($selectedCollaboratorIds) }} members</strong></p>
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="$set('showCollaboratorsModal', false)" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25">
                            <span>Save Team Members</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ===== DECLINE / REJECT ASSIGNMENT MODAL ===== -->
    @if($showRejectionModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="p-5 border-b border-slate-100 bg-rose-50/60 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-black">
                        âš ï¸
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Decline Project Leadership</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Report issue or feedback to PMO Admin</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showRejectionModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="submitRejection" class="p-5 space-y-4">
                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                    Please describe the specific constraints, required scope adjustments, or reason for declining so PMO can review and reassign.
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">
                        Reason / Issue Description <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        wire:model="rejectionReasonInput"
                        rows="4"
                        placeholder="e.g. Schedule conflicts, resource constraints, scope clarifications needed..."
                        class="w-full p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-900 outline-none focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 transition-all"
                        required
                    ></textarea>
                    @error('rejectionReasonInput') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showRejectionModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-700 hover:to-red-800 shadow-md">
                        Submit Issue &amp; Decline
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ===== REASSIGN & RECONFIGURE PROJECT LEADER MODAL (PMO ADMIN) ===== -->
    @if($showReassignModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-3xl overflow-hidden animate-in fade-in zoom-in-95 duration-150 flex flex-col max-h-[92vh] my-auto">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-lg shadow-2xs flex-shrink-0">
                            🔄
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900 tracking-tight" style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;">
                                Reassign &amp; Reconfigure Project
                            </h3>
                            <p class="text-[11px] text-slate-500 font-medium">Edit project parameters &amp; reassign leadership for rejected or declined project</p>
                        </div>
                    </div>
                    <button wire:click="closeReassignModal" type="button" class="text-slate-400 hover:text-slate-600 font-bold p-1 rounded-xl hover:bg-slate-100 transition-colors text-lg cursor-pointer">&times;</button>
                </div>

                {{-- Scrollable Body --}}
                <div class="p-6 space-y-5 text-xs overflow-y-auto flex-1 scrollbar-thin">
                    {{-- Decline Reason Alert Card (if declined) --}}
                    @if($reassignDeclineReason)
                        <div class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/90 text-rose-950 space-y-1.5 shadow-2xs">
                            <div class="flex items-center justify-between font-extrabold text-xs">
                                <span class="flex items-center gap-2 text-rose-700">
                                    <span class="w-2 h-2 rounded-full bg-rose-600 animate-ping"></span>
                                    <span>⚠️ Declined Leadership Assignment Reason</span>
                                </span>
                                @if($project->projectManager)
                                    <span class="text-[10px] bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full font-bold">
                                        Prev PM: {{ $project->projectManager->name }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-rose-900 font-medium leading-relaxed bg-white/70 p-3 rounded-xl border border-rose-100">
                                "{{ $reassignDeclineReason }}"
                            </p>
                        </div>
                    @endif

                    {{-- Form Grid: Section 1 - Basic Info --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-base">📌</span>
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">1. Project Identity &amp; Classification</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Project Code --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Project Code <span class="text-rose-600">*</span></label>
                                <input type="text" wire:model="reassignCode" class="w-full text-xs font-mono font-bold rounded-xl border border-slate-200 bg-slate-50/50 p-2.5 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none uppercase" placeholder="GST-PRJ-001">
                                @error('reassignCode') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Project Name --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Project Name <span class="text-rose-600">*</span></label>
                                <input type="text" wire:model="reassignName" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none" placeholder="Project Name">
                                @error('reassignName') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {{-- Subsidiary --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Subsidiary <span class="text-rose-600">*</span></label>
                                <select wire:model.live="reassignSubsidiaryId" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer">
                                    <option value="">-- Select Subsidiary --</option>
                                    @foreach($subsidiaries as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                    @endforeach
                                </select>
                                @error('reassignSubsidiaryId') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Category</label>
                                <input type="text" wire:model="reassignCategory" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none" placeholder="e.g. Corporate Strategy">
                            </div>

                            {{-- Priority --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Priority Level <span class="text-rose-600">*</span></label>
                                <select wire:model="reassignPriority" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer">
                                    <option value="low">Low Priority</option>
                                    <option value="medium">Medium Priority</option>
                                    <option value="high">High Priority</option>
                                    <option value="critical">Critical</option>
                                </select>
                                @error('reassignPriority') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Grid: Section 2 - Timeline & Financials --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-base">📅</span>
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">2. Timeline Schedule &amp; Budget Allocation</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {{-- Start Date --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Start Date <span class="text-rose-600">*</span></label>
                                <input type="date" wire:model="reassignStartDate" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none">
                                @error('reassignStartDate') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Target Deadline --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Target Deadline <span class="text-rose-600">*</span></label>
                                <input type="date" wire:model="reassignDeadline" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none">
                                @error('reassignDeadline') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Estimated Budget --}}
                            <div>
                                <label class="block font-bold text-slate-700 text-xs mb-1">Estimated Budget (LKR)</label>
                                <input type="number" step="0.01" wire:model="reassignEstimatedBudget" class="w-full text-xs font-mono font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none" placeholder="0.00">
                                @error('reassignEstimatedBudget') <span class="text-rose-600 text-[11px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Form Grid: Section 3 - Leadership & Governance Team --}}
                    <div class="space-y-4 pt-1">
                        <div class="flex items-center justify-between border-b border-slate-200/70 pb-2.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center text-xs shadow-xs font-black">
                                    👥
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">3. Project Leadership &amp; Governance Panel</h4>
                                    <p class="text-[10px] text-slate-400 font-medium">Assign leadership roles, executive sponsors &amp; team structure</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200/60 hidden sm:inline-block">Governance &amp; Team</span>
                        </div>

                        {{-- Hero Card: Designated Project Manager / Leader --}}
                        <div class="bg-gradient-to-r from-rose-50/80 via-slate-50/90 to-white p-4.5 rounded-2xl border-l-4 border-l-[#c3122e] border border-rose-200/70 shadow-xs space-y-3 relative overflow-hidden">
                            <div class="flex items-center justify-between">
                                <label class="font-black text-slate-900 text-xs uppercase tracking-wider flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-lg bg-[#c3122e] text-white flex items-center justify-center font-black text-[10px] shadow-2xs">⭐</span>
                                    <span>Select New Project Manager / Leader <span class="text-rose-600">*</span></span>
                                </label>
                                <span class="text-[10px] font-extrabold text-[#c3122e] bg-rose-100/90 px-2.5 py-0.5 rounded-full border border-rose-200 shadow-2xs">Required Lead Role</span>
                            </div>
                            <div class="relative">
                                <select wire:model="reassignPmId" class="w-full text-xs font-extrabold rounded-xl border border-slate-200 bg-white p-3 pr-10 focus:border-[#c3122e] focus:ring-4 focus:ring-[#c3122e]/10 outline-none shadow-2xs text-slate-900 cursor-pointer hover:border-rose-300 transition-all">
                                    <option value="">-- Choose New Project Manager --</option>
                                    @foreach($availablePms as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('reassignPmId') <span class="text-rose-600 text-[11px] font-bold block mt-1 flex items-center gap-1">⚠️ {{ $message }}</span> @enderror
                        </div>

                        {{-- Governance Roles Cards (Sponsors, Owners, Steering, Members) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Project Sponsors --}}
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3 hover:border-amber-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-700 border border-amber-200/60 flex items-center justify-center font-black text-sm shadow-2xs">
                                            👑
                                        </div>
                                        <div>
                                            <h5 class="font-black text-slate-900 text-xs tracking-tight">Project Sponsors</h5>
                                            <span class="text-[10px] text-amber-700/80 font-bold bg-amber-50 px-1.5 py-0.2 rounded">Executive Tier</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black transition-colors {{ count($reassignSponsorIds) > 0 ? 'bg-amber-100 text-amber-900 border border-amber-300/80' : 'bg-slate-100 text-slate-400 border border-slate-200/60' }}">
                                        {{ count($reassignSponsorIds) }} {{ count($reassignSponsorIds) === 1 ? 'Sponsor' : 'Sponsors' }}
                                    </span>
                                </div>

                                <!-- Display Assigned Sponsors Badges -->
                                <div class="flex flex-wrap items-center gap-1.5 min-h-[46px] p-2.5 bg-slate-50/70 rounded-xl border border-dashed border-slate-200 transition-all">
                                    @forelse($availablePms->whereIn('id', array_map('intval', $reassignSponsorIds)) as $sUser)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-extrabold bg-white text-slate-900 border border-amber-200 shadow-2xs hover:border-amber-400 hover:shadow-xs transition-all">
                                            <span class="w-4.5 h-4.5 rounded-full bg-amber-500 text-white font-black text-[9px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($sUser->name, 0, 1)) }}
                                            </span>
                                            <span>{{ $sUser->name }}</span>
                                            <span class="text-[9px] font-mono font-bold text-amber-800 bg-amber-100/80 px-1.5 py-0.2 rounded-md border border-amber-200/60">({{ $sUser->subsidiary->code ?? 'GST' }})</span>
                                            <button type="button" wire:click="removeReassignSponsor('{{ $sUser->id }}')" class="w-4 h-4 rounded-full bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-400 font-bold text-xs flex items-center justify-center transition-all cursor-pointer ml-0.5" title="Remove sponsor">&times;</button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 font-medium italic pl-1 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            <span>No sponsors assigned yet.</span>
                                        </span>
                                    @endforelse
                                </div>

                                <!-- Dropdown to Add Sponsor -->
                                <div class="relative">
                                    <select wire:change="addReassignSponsor($event.target.value); $event.target.value=''" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer text-slate-700 shadow-2xs hover:border-slate-300 transition-all">
                                        <option value="">+ Add Sponsor from available list...</option>
                                        @foreach($availablePms->reject(fn($u) => in_array((string)$u->id, $reassignSponsorIds)) as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->subsidiary->code ?? 'GST' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Project Owners --}}
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3 hover:border-emerald-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-700 border border-emerald-200/60 flex items-center justify-center font-black text-sm shadow-2xs">
                                            🎯
                                        </div>
                                        <div>
                                            <h5 class="font-black text-slate-900 text-xs tracking-tight">Project Owners</h5>
                                            <span class="text-[10px] text-emerald-700/80 font-bold bg-emerald-50 px-1.5 py-0.2 rounded">Business Tier</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black transition-colors {{ count($reassignOwnerIds) > 0 ? 'bg-emerald-100 text-emerald-900 border border-emerald-300/80' : 'bg-slate-100 text-slate-400 border border-slate-200/60' }}">
                                        {{ count($reassignOwnerIds) }} {{ count($reassignOwnerIds) === 1 ? 'Owner' : 'Owners' }}
                                    </span>
                                </div>

                                <!-- Display Assigned Owners Badges -->
                                <div class="flex flex-wrap items-center gap-1.5 min-h-[46px] p-2.5 bg-slate-50/70 rounded-xl border border-dashed border-slate-200 transition-all">
                                    @forelse($availablePms->whereIn('id', array_map('intval', $reassignOwnerIds)) as $oUser)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-extrabold bg-white text-slate-900 border border-emerald-200 shadow-2xs hover:border-emerald-400 hover:shadow-xs transition-all">
                                            <span class="w-4.5 h-4.5 rounded-full bg-emerald-600 text-white font-black text-[9px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($oUser->name, 0, 1)) }}
                                            </span>
                                            <span>{{ $oUser->name }}</span>
                                            <span class="text-[9px] font-mono font-bold text-emerald-800 bg-emerald-100/80 px-1.5 py-0.2 rounded-md border border-emerald-200/60">({{ $oUser->subsidiary->code ?? 'GST' }})</span>
                                            <button type="button" wire:click="removeReassignOwner('{{ $oUser->id }}')" class="w-4 h-4 rounded-full bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-400 font-bold text-xs flex items-center justify-center transition-all cursor-pointer ml-0.5" title="Remove owner">&times;</button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 font-medium italic pl-1 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            <span>No owners assigned yet.</span>
                                        </span>
                                    @endforelse
                                </div>

                                <!-- Dropdown to Add Owner -->
                                <div class="relative">
                                    <select wire:change="addReassignOwner($event.target.value); $event.target.value=''" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer text-slate-700 shadow-2xs hover:border-slate-300 transition-all">
                                        <option value="">+ Add Owner from available list...</option>
                                        @foreach($availablePms->reject(fn($u) => in_array((string)$u->id, $reassignOwnerIds)) as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->subsidiary->code ?? 'GST' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Steering Committee --}}
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3 hover:border-violet-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-violet-500/10 text-violet-700 border border-violet-200/60 flex items-center justify-center font-black text-sm shadow-2xs">
                                            🏛️
                                        </div>
                                        <div>
                                            <h5 class="font-black text-slate-900 text-xs tracking-tight">Steering Committee</h5>
                                            <span class="text-[10px] text-violet-700/80 font-bold bg-violet-50 px-1.5 py-0.2 rounded">Oversight Panel</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black transition-colors {{ count($reassignSteeringIds) > 0 ? 'bg-violet-100 text-violet-900 border border-violet-300/80' : 'bg-slate-100 text-slate-400 border border-slate-200/60' }}">
                                        {{ count($reassignSteeringIds) }} {{ count($reassignSteeringIds) === 1 ? 'Member' : 'Members' }}
                                    </span>
                                </div>

                                <!-- Display Assigned Steering Committee Badges -->
                                <div class="flex flex-wrap items-center gap-1.5 min-h-[46px] p-2.5 bg-slate-50/70 rounded-xl border border-dashed border-slate-200 transition-all">
                                    @forelse($availablePms->whereIn('id', array_map('intval', $reassignSteeringIds)) as $scUser)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-extrabold bg-white text-slate-900 border border-violet-200 shadow-2xs hover:border-violet-400 hover:shadow-xs transition-all">
                                            <span class="w-4.5 h-4.5 rounded-full bg-violet-600 text-white font-black text-[9px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($scUser->name, 0, 1)) }}
                                            </span>
                                            <span>{{ $scUser->name }}</span>
                                            <span class="text-[9px] font-mono font-bold text-violet-800 bg-violet-100/80 px-1.5 py-0.2 rounded-md border border-violet-200/60">({{ $scUser->subsidiary->code ?? 'GST' }})</span>
                                            <button type="button" wire:click="removeReassignSteering('{{ $scUser->id }}')" class="w-4 h-4 rounded-full bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-400 font-bold text-xs flex items-center justify-center transition-all cursor-pointer ml-0.5" title="Remove committee member">&times;</button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 font-medium italic pl-1 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            <span>No steering committee assigned yet.</span>
                                        </span>
                                    @endforelse
                                </div>

                                <!-- Dropdown to Add Steering Member -->
                                <div class="relative">
                                    <select wire:change="addReassignSteering($event.target.value); $event.target.value=''" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer text-slate-700 shadow-2xs hover:border-slate-300 transition-all">
                                        <option value="">+ Add Committee Member from available list...</option>
                                        @foreach($availablePms->reject(fn($u) => in_array((string)$u->id, $reassignSteeringIds)) as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->subsidiary->code ?? 'GST' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Core Team Members --}}
                            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-xs space-y-3 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-700 border border-blue-200/60 flex items-center justify-center font-black text-sm shadow-2xs">
                                            🤝
                                        </div>
                                        <div>
                                            <h5 class="font-black text-slate-900 text-xs tracking-tight">Core Team Members</h5>
                                            <span class="text-[10px] text-blue-700/80 font-bold bg-blue-50 px-1.5 py-0.2 rounded">Execution Team</span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black transition-colors {{ count($reassignMemberIds) > 0 ? 'bg-blue-100 text-blue-900 border border-blue-300/80' : 'bg-slate-100 text-slate-400 border border-slate-200/60' }}">
                                        {{ count($reassignMemberIds) }} {{ count($reassignMemberIds) === 1 ? 'Member' : 'Members' }}
                                    </span>
                                </div>

                                <!-- Display Assigned Team Members Badges -->
                                <div class="flex flex-wrap items-center gap-1.5 min-h-[46px] p-2.5 bg-slate-50/70 rounded-xl border border-dashed border-slate-200 transition-all">
                                    @forelse($availablePms->whereIn('id', array_map('intval', $reassignMemberIds)) as $mUser)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-extrabold bg-white text-slate-900 border border-blue-200 shadow-2xs hover:border-blue-400 hover:shadow-xs transition-all">
                                            <span class="w-4.5 h-4.5 rounded-full bg-blue-600 text-white font-black text-[9px] flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($mUser->name, 0, 1)) }}
                                            </span>
                                            <span>{{ $mUser->name }}</span>
                                            <span class="text-[9px] font-mono font-bold text-blue-800 bg-blue-100/80 px-1.5 py-0.2 rounded-md border border-blue-200/60">({{ $mUser->subsidiary->code ?? 'GST' }})</span>
                                            <button type="button" wire:click="removeReassignMember('{{ $mUser->id }}')" class="w-4 h-4 rounded-full bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-400 font-bold text-xs flex items-center justify-center transition-all cursor-pointer ml-0.5" title="Remove team member">&times;</button>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-slate-400 font-medium italic pl-1 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            <span>No team members assigned yet.</span>
                                        </span>
                                    @endforelse
                                </div>

                                <!-- Dropdown to Add Core Team Member -->
                                <div class="relative">
                                    <select wire:change="addReassignMember($event.target.value); $event.target.value=''" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-white p-2.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none cursor-pointer text-slate-700 shadow-2xs hover:border-slate-300 transition-all">
                                        <option value="">+ Add Core Team Member from available list...</option>
                                        @foreach($availablePms->reject(fn($u) => in_array((string)$u->id, $reassignMemberIds)) as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->subsidiary->code ?? 'GST' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Grid: Section 4 - Description / Scope --}}
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-base">📝</span>
                            <h4 class="font-black text-slate-900 text-xs uppercase tracking-wider">4. Description &amp; Project Scope</h4>
                        </div>
                        <textarea wire:model="reassignDescription" rows="3" class="w-full text-xs font-medium rounded-xl border border-slate-200 bg-white p-3 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none" placeholder="Provide revised description or charter details..."></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5 flex-shrink-0">
                    <button wire:click="closeReassignModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition-all cursor-pointer">
                        Cancel
                    </button>
                    <button wire:click="submitReassign" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a90f27] shadow-md shadow-rose-900/20 transition-all cursor-pointer flex items-center gap-2">
                        <span>🚀</span>
                        <span>Reassign &amp; Recreate Project</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ===== COMPREHENSIVE PROJECT DETAILS EXECUTIVE MODAL (ULTRA-CLEAN MODERN SAAS) ===== -->
    <!-- ═══════════════════════════════════════════════════════════════
         PROJECT OVERVIEW / SUMMARY MODAL
         ═══════════════════════════════════════════════════════════════ -->
    @if($showProjectDetailsModal && (auth()->user()?->isPmoAdmin() || $project->userCan(auth()->user(), 'project_details.view')))
    <div 
        x-data 
        @keydown.escape.window="$wire.closeProjectDetailsModal()" 
        class="fixed inset-0 z-[999999] bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6 overflow-y-auto animate-in fade-in duration-150"
    >
        <!-- Modal Card Container -->
        <div 
            @click.outside="$wire.closeProjectDetailsModal()" 
            class="relative w-full max-w-3xl max-h-[92vh] flex flex-col bg-white rounded-3xl shadow-2xl border border-slate-200/90 overflow-hidden my-auto animate-in zoom-in-95 duration-150"
            style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif;"
        >
            <!-- 1. Header (Clean & Simple Edit-Style Header) -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4 flex-shrink-0 bg-white">
                <div class="flex items-center gap-3.5 min-w-0 flex-1">
                    <!-- Soft Light-Rose Circle Icon Box -->
                    <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-100/90 text-[#c3122e] flex items-center justify-center shadow-2xs shrink-0">
                        <svg class="w-5.5 h-5.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-snug truncate" style="font-family: 'Outfit', 'Plus Jakarta Sans', system-ui, sans-serif;">
                            Project Specifications &amp; Overview
                        </h2>
                        <p class="text-xs text-slate-500 font-medium truncate mt-0.5">
                            Comprehensive identity, governance status, schedule, budget, and scope details
                        </p>
                    </div>
                </div>

                <!-- Close Button -->
                <button 
                    wire:click="closeProjectDetailsModal" 
                    type="button" 
                    class="w-8.5 h-8.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-all cursor-pointer flex-shrink-0 active:scale-95 shadow-2xs"
                    title="Close (Esc)"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- 2. Scrollable Clean Sectioned Body -->
            @php
                $canEditProjectDetails = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'project_details.edit');
                $canChangeStatus = auth()->user()->isPmoAdmin() || $project->userCan(auth()->user(), 'project.change_status');
                $prio = $project->priority->value ?? 'medium';
                $prioBadge = match($prio) {
                    'critical' => 'bg-rose-50 text-rose-700 border-rose-200',
                    'high'     => 'bg-orange-50 text-orange-700 border-orange-200',
                    'low'      => 'bg-slate-50 text-slate-600 border-slate-200',
                    default    => 'bg-amber-50 text-amber-800 border-amber-200',
                };
            @endphp

            <div class="p-6 overflow-y-auto space-y-5 flex-1 bg-white">
                
                <!-- SECTION 1: 🏷️ PROJECT IDENTITY & CLASSIFICATION -->
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 sm:p-5 space-y-3.5 shadow-2xs">
                    <div class="border-b border-slate-200/60 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">🏷️</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                                Project Identity &amp; Classification
                            </h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <!-- Project Title -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Project Title <span class="text-rose-500">*</span></label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2.5 shadow-2xs font-extrabold text-slate-900 truncate">
                                {{ $project->name }}
                            </div>
                        </div>

                        <!-- Project Code -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Project Code</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2 shadow-2xs flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-md font-mono text-xs font-black bg-rose-50 text-[#c3122e] border border-rose-200">
                                    {{ $project->code }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-semibold">System Generated</span>
                            </div>
                        </div>

                        <!-- Subsidiary / Division -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Subsidiary / Division</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2.5 shadow-2xs font-bold text-slate-800 truncate">
                                {{ $project->subsidiary->name ?? 'George Steuart Health' }}
                            </div>
                        </div>

                        <!-- Strategic Category -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Strategic Category</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2.5 shadow-2xs font-bold text-slate-800 truncate">
                                {{ $project->category ?? 'Corporate Strategy' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: ⚙️ GOVERNANCE, STATUS & LEADERSHIP -->
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 sm:p-5 space-y-3.5 shadow-2xs">
                    <div class="border-b border-slate-200/60 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">⚙️</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                                Governance, Status &amp; Leadership
                            </h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <!-- Execution Status -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Execution Status <span class="text-rose-500">*</span></label>
                            <div class="bg-white rounded-xl border border-slate-200 p-1.5 shadow-2xs flex items-center">
                                @if($canChangeStatus)
                                    <div class="relative w-full">
                                        <select
                                            wire:change="updateProjectStatus($event.target.value)"
                                            class="w-full h-8 pl-3 pr-8 rounded-lg text-xs font-bold {{ $statusStyle['bg'] }} {{ $statusStyle['text'] }} border {{ $statusStyle['border'] }} outline-none cursor-pointer appearance-none transition-all"
                                        >
                                            @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                                <option value="{{ $st->value }}" @selected($project->status->value === $st->value) class="bg-white text-slate-900 font-semibold">
                                                    {{ $st->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <svg class="w-3.5 h-3.5 {{ $statusStyle['text'] }} absolute right-3 top-2.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                @else
                                    <div class="px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusStyle['bg'] }} {{ $statusStyle['text'] }} border {{ $statusStyle['border'] }} inline-flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full {{ $statusStyle['dot'] }}"></span>
                                        <span>{{ $project->status->label() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Priority Level -->
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Priority Level <span class="text-rose-500">*</span></label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2 shadow-2xs flex items-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-extrabold border shadow-2xs {{ $prioBadge }}">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span>{{ ucfirst($prio) }} Priority</span>
                                </span>
                            </div>
                        </div>

                        <!-- Project Manager (Leader) -->
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-extrabold text-slate-500 mb-1.5 uppercase tracking-wider">Project Manager (Leader)</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3.5 py-2.5 shadow-2xs flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-[#8b0d1f] to-[#c3122e] text-white font-black text-xs flex items-center justify-center shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($project->projectManager->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-black text-slate-900 block truncate">{{ $project->projectManager->name ?? 'Tea' }}</span>
                                        <span class="text-[11px] text-slate-400 font-medium block truncate">{{ $project->projectManager->email ?? 'tea@gmail.com' }}</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-[#c3122e] border border-rose-200 shrink-0">
                                    Lead PM
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: 📅 SCHEDULE HORIZON & FINANCIAL BUDGET -->
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 sm:p-5 space-y-3.5 shadow-2xs">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📅</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                                Schedule Horizon &amp; Financial Budget
                            </h3>
                        </div>
                        @if(($project->estimated_budget ?? 0) > 0)
                            @php
                                $bPct = min(100, round(($project->actual_cost / $project->estimated_budget) * 100));
                            @endphp
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $bPct > 90 ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200' }}">
                                {{ $bPct }}% Burn Rate
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <!-- Kick-off Date -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 mb-1 uppercase tracking-wider">Kick-Off Date</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3 py-2 shadow-2xs font-mono font-black text-slate-900">
                                {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Sep 16, 2026' }}
                            </div>
                        </div>

                        <!-- Target Deadline -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 mb-1 uppercase tracking-wider">Target Deadline</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3 py-2 shadow-2xs flex items-center justify-between gap-1">
                                <span class="font-mono font-black text-[#c3122e]">{{ $project->deadline ? $project->deadline->format('M d, Y') : 'Oct 15, 2026' }}</span>
                                @if($project->deadline)
                                    <span class="px-1.5 py-0.2 rounded text-[9.5px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200/80">{{ (int) now()->diffInDays($project->deadline, false) }}d left</span>
                                @endif
                            </div>
                        </div>

                        <!-- Actual Spend -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 mb-1 uppercase tracking-wider">Actual Spend</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3 py-2 shadow-2xs font-mono font-black text-rose-700">
                                Rs. {{ number_format($project->actual_cost ?? 0, 0) }}
                            </div>
                        </div>

                        <!-- Total Budget -->
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 mb-1 uppercase tracking-wider">Total Budget</label>
                            <div class="bg-white rounded-xl border border-slate-200 px-3 py-2 shadow-2xs font-mono font-black text-slate-900">
                                Rs. {{ number_format($project->estimated_budget ?? 0, 0) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: 📊 EXECUTION PROGRESS & BLUEPRINT -->
                @php
                    $tpl = $project->template;
                    $workStart = $tpl?->work_start_time ? \Carbon\Carbon::parse($tpl->work_start_time)->format('g:i A') : '8:30 AM';
                    $workEnd = $tpl?->work_end_time ? \Carbon\Carbon::parse($tpl->work_end_time)->format('g:i A') : '5:30 PM';
                    $days = $tpl?->work_days ? implode(', ', array_map('ucfirst', $tpl->work_days)) : 'Mon, Tue, Wed, Thu, Fri';
                    $granularity = ($tpl?->divide_by_hours ?? true) ? 'Hourly Breakdown' : 'Daily Breakdown';
                @endphp
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 sm:p-5 space-y-3.5 shadow-2xs">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📊</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                                Execution Progress &amp; Blueprint
                            </h3>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="text-xs font-black text-slate-900 font-mono">{{ $project->overall_progress }}%</span>
                            <span class="text-[11px] font-extrabold text-slate-400">Complete</span>
                        </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    <div class="w-full h-2.5 bg-slate-200/80 rounded-full overflow-hidden p-0.5 border border-slate-200/60">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-[#c3122e] to-[#8b0d1f] rounded-full transition-all duration-500 shadow-2xs" style="width: {{ max(3, $project->overall_progress) }}%;"></div>
                    </div>

                    <!-- Template Blueprint Field Box -->
                    @if($tpl)
                        <div class="bg-white rounded-xl border border-slate-200 p-3.5 shadow-2xs flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8.5 h-8.5 rounded-xl bg-rose-50 text-[#c3122e] flex items-center justify-center border border-rose-100 shrink-0 shadow-2xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">TEMPLATE:</span>
                                        <span class="font-extrabold text-slate-900 text-xs">{{ $tpl->name }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-rose-50 text-[#c3122e] border border-rose-100">{{ $tpl->tasks->count() }} Standard Tasks</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-semibold flex items-center gap-2 mt-1 flex-wrap">
                                        <span title="Schedule">🕒 {{ $workStart }} – {{ $workEnd }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span title="Working Days">📅 {{ $days }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-indigo-600 font-bold" title="Granularity">{{ $granularity }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0 shadow-2xs">
                                Active Blueprint
                            </span>
                        </div>
                    @endif
                </div>

                <!-- SECTION 5: 📝 PROJECT SCOPE & OBJECTIVES (Project Description) -->
                <div class="bg-slate-50/70 border border-slate-200/90 rounded-2xl p-4.5 sm:p-5 space-y-3 shadow-2xs">
                    <div class="border-b border-slate-200/60 pb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📝</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-slate-800">
                                Project Scope &amp; Objectives
                            </h3>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs min-h-[70px]">
                        @if(!empty(trim($project->description ?? '')))
                            <p class="text-xs text-slate-700 leading-relaxed font-medium whitespace-pre-line">
                                {{ $project->description }}
                            </p>
                        @else
                            <div class="flex items-center gap-2.5 text-slate-400 text-xs italic py-1">
                                <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>No custom project scope or description specified. Click "Edit Details" below to add specifications.</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- 3. Footer (Clean Bottom Action Bar) -->
            <div class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-between gap-4 flex-shrink-0">
                <span class="text-xs text-slate-400 font-semibold">
                    Created on {{ $project->created_at->format('M d, Y') }}
                </span>
                <div class="flex items-center gap-2.5">
                    @if($canEditProjectDetails)
                        <button 
                            wire:click="openEditProjectModal" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all cursor-pointer shadow-2xs active:scale-95 flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit Details</span>
                        </button>
                    @endif
                    <button 
                        wire:click="closeProjectDetailsModal" 
                        type="button" 
                        class="px-6 py-2 rounded-xl text-xs font-black text-white shadow-md hover:shadow-lg active:scale-95 transition-all cursor-pointer"
                        style="background: #8b0d1f;"
                    >
                        Done
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>


