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
    @elseif(!$project->pm_accepted)
        <div class="mb-5 rounded-2xl p-4 sm:p-5 bg-white border border-amber-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden animate-in fade-in duration-300">
            <!-- Left Crimson-Gold Gradient Accent Strip -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-[#c3122e] via-amber-400 to-[#8b0d1f]"></div>

            <div class="flex items-start sm:items-center gap-3.5 pl-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 text-amber-800 flex items-center justify-center text-xl flex-shrink-0 shadow-2xs">
                    👑
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h4 class="text-xs sm:text-sm font-black text-slate-900">Project Leadership Assignment</h4>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-300 flex items-center gap-1.5 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Pending Acceptance</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 font-medium mt-1">
                        @if($project->project_manager_id === auth()->id())
                            You have been designated as Project Leader. Review details to accept leadership and begin execution.
                        @else
                            Assigned Project Leader (<strong class="text-slate-900">{{ $project->projectManager->name ?? 'Unassigned' }}</strong>) has not yet accepted assignment.
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0 flex-wrap pl-2 sm:pl-0">
                @if($project->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin())
                    <button
                        wire:click="openRejectionModal"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-rose-50 hover:text-rose-700 border border-slate-200 hover:border-rose-200 transition-all cursor-pointer shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span>Decline</span>
                    </button>

                    <button
                        wire:click="acceptAssignment"
                        type="button"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                        style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); box-shadow: 0 4px 14px rgba(195,18,46,0.35);"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Accept Leadership →</span>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <!-- ===== 1. TOP EXECUTIVE PROJECT BANNER (LUXURY CRIMSON & GOLD SUNSET) ===== -->
    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 shadow-2xl mb-6 p-5 sm:p-6 lg:p-7 text-white" style="background: #2b040a;">
        <!-- Full Banner Background Image (User's Luxury Crimson & Gold Skyline Artwork) -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
            <img 
                src="{{ asset('images/project-banner-luxury-2x.jpg') }}" 
                alt="Executive Project Banner" 
                class="w-full h-full object-cover object-center"
            >
            <!-- Left Crimson Velvet Scrim for 100% Contrast & Legibility -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#180206]/95 via-[#2a040b]/65 to-transparent md:w-3/5"></div>
            <!-- Right Subtle Dark Vignette over Sunset to eliminate muddy glare and make buttons pop -->
            <div class="absolute right-0 top-0 bottom-0 w-2/5 bg-gradient-to-l from-black/40 via-black/20 to-transparent hidden md:block"></div>
            <!-- Top & Bottom Ambient Depth Gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/45 via-transparent to-black/20"></div>
        </div>

        <!-- Top Glowing Gold & Ruby Ambient Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left Side: App Icon + Breadcrumbs + Title + Sleek Essential Meta Pills -->
            <div class="flex items-start sm:items-center gap-4 min-w-0 flex-1">
                <!-- 3D Luxury App Icon Container with Dark Bezel -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border border-amber-400/40 ring-4 ring-black/40 bg-slate-950/80 p-1 flex items-center justify-center backdrop-blur-md hover:scale-105 transition-all duration-300">
                    <img src="{{ asset('images/project-app-icon.jpg') }}" alt="{{ $project->name }}" class="w-full h-full object-cover rounded-xl shadow-inner">
                </div>

                <div class="min-w-0 space-y-2 flex-1">
                    <!-- Top Breadcrumb & Code Row -->
                    <div class="flex items-center gap-2 flex-wrap" x-data="{ fav: false }">
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-[11px] font-black text-amber-300 hover:text-white transition-colors uppercase tracking-wider no-underline group">
                            <svg class="w-3.5 h-3.5 text-amber-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            <span>Projects</span>
                        </a>
                        <span class="text-white/30 text-xs">/</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg text-[11px] font-semibold text-slate-200 bg-slate-950/70 border border-white/15 backdrop-blur-md shadow-xs">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>{{ $project->subsidiary->name ?? 'George Steuart Group' }}</span>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-lg text-[11px] font-mono font-black text-amber-300 bg-slate-950/80 border border-amber-400/50 shadow-xs backdrop-blur-md">
                            {{ $project->code }}
                        </span>
                        <button type="button" @click="fav = !fav" class="transition-all text-base cursor-pointer focus:outline-none ml-0.5 p-0.5 hover:scale-125" :class="fav ? 'text-amber-400' : 'text-white/40 hover:text-amber-300'" title="Toggle Favorite">
                            <span x-text="fav ? '★' : '☆'"></span>
                        </button>
                    </div>

                    <!-- Main Project Title with Executive Typography -->
                    <h1 class="text-xl sm:text-2xl lg:text-[25px] font-extrabold text-white tracking-tight leading-snug drop-shadow-[0_2px_6px_rgba(0,0,0,0.8)]" style="font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;">
                        {{ $project->name }}
                    </h1>

                    <!-- Sleek Meta Status Pills -->
                    <div class="flex items-center gap-2 text-xs font-semibold text-slate-200 flex-wrap pt-0.5">
                        <!-- Status Pill -->
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-950/75 text-sky-300 border border-sky-400/40 shadow-xs backdrop-blur-md">
                            {{ $project->status->label() }}
                        </span>

                        <!-- Health Pill -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-950/75 text-emerald-300 border border-emerald-400/40 shadow-xs backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>{{ $project->health->label() }}</span>
                        </span>

                        <!-- Project Leader Pill -->
                        <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-medium text-slate-200 bg-slate-950/75 border border-white/20 backdrop-blur-md shadow-xs">
                            <div class="w-4 h-4 rounded-full bg-[#c3122e] text-white flex items-center justify-center font-black text-[8px] ring-1 ring-amber-400/60 shadow-xs">
                                {{ strtoupper(substr($project->projectManager->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="truncate max-w-[150px]">{{ $project->projectManager->name ?? 'Unassigned' }}</span>
                        </div>

                        <!-- Target Deadline Pill -->
                        @if($project->deadline)
                            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-medium {{ $project->deadline->isPast() ? 'bg-rose-950/90 text-rose-300 border-rose-500/50' : 'bg-slate-950/75 text-slate-200 border-white/20' }} border backdrop-blur-md shadow-xs">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $project->deadline->format('M d, Y') }}</span>
                                @if($project->deadline->isPast())
                                    <span class="text-[9px] font-black text-rose-300 font-mono">(Overdue)</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Progress Gauge + Action Buttons (Executive Smoked Obsidian HUD) -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center flex-wrap">
                <!-- Executive Smoked Frosted Progress Card -->
                <div class="rounded-2xl px-4 py-2.5 shadow-2xl border border-white/20 ring-1 ring-black/40 bg-slate-950/80 hover:bg-slate-950/95 backdrop-blur-2xl transition-all duration-300 flex items-center gap-3.5 flex-shrink-0">
                    <div class="relative w-11 h-11 flex items-center justify-center flex-shrink-0">
                        <svg class="w-11 h-11 transform -rotate-90" viewBox="0 0 36 36">
                            <!-- Track -->
                            <path class="text-white/15" stroke-width="3" stroke="currentColor" fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Progress Arc with amber gold glow -->
                            <path class="text-amber-400 drop-shadow-[0_0_6px_rgba(251,191,36,0.6)] transition-all duration-700 ease-out" 
                                  stroke-width="3.5" 
                                  stroke-dasharray="{{ max(1, $project->overall_progress) }}, 100" 
                                  stroke-linecap="round" 
                                  stroke="currentColor" 
                                  fill="none"
                                  d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xs font-black text-white leading-none font-mono tracking-tight">{{ $project->overall_progress }}%</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-300/90 block leading-none">PROGRESS</span>
                        <span class="text-xs font-bold text-white leading-tight block mt-1">
                            <span class="font-mono font-black text-sm text-white">{{ $project->wbsItems->where('status', \App\Enums\WbsStatus::COMPLETED)->count() }}</span><span class="text-slate-400 font-normal">/</span><span class="font-mono text-slate-300">{{ $project->wbsItems->count() }}</span>
                            <span class="text-[10px] font-semibold text-slate-300 ml-0.5">Done</span>
                        </span>
                    </div>
                </div>

                <!-- Primary Action Buttons in Smoked Obsidian Glass -->
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- 👁️ View Project Details Button (Opens Comprehensive Modal) -->
                    <button
                        wire:click="openProjectDetailsModal"
                        type="button"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-950/80 hover:bg-slate-950 border border-white/20 hover:border-amber-400/50 shadow-xl backdrop-blur-2xl transition-all duration-200 flex items-center gap-2 cursor-pointer active:scale-95 hover:scale-105"
                        title="View comprehensive project details, budget, team, and delivery specs"
                    >
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Project Details</span>
                    </button>

                    <!-- Team Members Button -->
                    @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager', 'pmo_admin']) || $project->project_manager_id === auth()->id())
                        <button wire:click="openCollaboratorsModal" type="button" class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-950/80 hover:bg-slate-950 border border-white/20 hover:border-amber-400/50 shadow-xl backdrop-blur-2xl transition-all duration-200 flex items-center gap-1.5 cursor-pointer active:scale-95 hover:scale-105" title="Manage Team Members">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Team ({{ $project->members->count() }})</span>
                        </button>
                    @endif

                    <!-- Edit Project Button -->
                    @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                        <button wire:click="openEditProjectModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-black text-white hover:brightness-110 transition-all duration-200 flex items-center gap-1.5 cursor-pointer active:scale-95 hover:scale-105 shadow-xl" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(251, 191, 36, 0.6); box-shadow: 0 4px 15px rgba(195,18,46,0.5);">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>Edit</span>
                        </button>
                    @endif

                    <!-- Share Button -->
                    <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Project link copied to clipboard!');" class="p-2.5 rounded-xl text-white bg-slate-950/80 hover:bg-slate-950 border border-white/20 hover:border-amber-400/50 shadow-xl backdrop-blur-2xl transition-all duration-200 cursor-pointer active:scale-95 hover:scale-105" title="Copy Project Link">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== LEADERSHIP GATEWAY (IF PENDING ACCEPTANCE) ===== -->
    @if(!$project->isPmAccepted() && $project->project_manager_id === auth()->id() && !auth()->user()->isSuperAdmin())
        <!-- 🔒 LEADERSHIP ACCEPTANCE GATEWAY SCREEN (WORKSPACE LOCKED UNTIL ACCEPTED) -->
        <div class="mb-8 space-y-4 animate-in fade-in duration-300">
            
            <!-- 1. Top Executive Skyline Banner -->
            <div class="relative overflow-hidden rounded-3xl border border-amber-500/30 shadow-2xl p-6 sm:p-7 text-white" style="background: #38050e;">
                <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
                    <img src="{{ asset('images/project-banner-luxury-2x.jpg') }}" alt="Skyline" class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#200308]/90 via-[#36050e]/50 to-transparent sm:w-1/2"></div>
                </div>
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-[#c3122e] text-white flex items-center justify-center text-2xl shadow-xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25">
                            👑
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h2 class="text-lg sm:text-xl font-black text-white tracking-tight">Project Leadership Assignment Required</h2>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/40 animate-pulse">
                                    1 ACTION NEEDED
                                </span>
                            </div>
                            <p class="text-xs sm:text-sm text-rose-200/90 font-medium mt-1">
                                PMO Administration has designated you as Project Leader. Review project details &amp; blueprint to accept leadership.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Action Button in Top Header -->
                    <div class="flex items-center gap-2.5 flex-shrink-0 self-start sm:self-center">
                        <button
                            wire:click="openProjectDetailsModal"
                            type="button"
                            class="px-4 py-2 rounded-xl text-xs font-black text-white bg-white/10 hover:bg-white/20 border border-white/20 shadow-md backdrop-blur-md transition-all cursor-pointer flex items-center gap-1.5 active:scale-95"
                        >
                            <svg class="w-4 h-4 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Full Brief</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 2. Full-Width 2-Column Balanced Executive Bento Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- Left Column (7 cols): Project Identity, Description & WBS Architecture -->
                <div class="lg:col-span-7 space-y-4">
                    <!-- Project Core Identity Card -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-3 py-1 rounded-xl font-mono text-xs font-black bg-rose-50 text-[#c3122e] border border-rose-200 shadow-2xs">
                                    {{ $project->code }}
                                </span>
                                <span class="px-3 py-1 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 shadow-2xs">
                                    {{ $project->subsidiary->name ?? 'George Steuart Group' }}
                                </span>
                            </div>
                            <span class="px-3 py-1 rounded-xl text-xs font-black uppercase tracking-wider bg-amber-50 text-amber-900 border border-amber-200 flex items-center gap-2 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>PENDING ACCEPTANCE</span>
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
                                {{ $project->name }}
                            </h3>
                            <p class="text-slate-400 text-xs font-semibold mt-1">
                                Inception Date: {{ $project->created_at->format('M d, Y') }} • Category: {{ $project->category ?? 'Corporate Strategy' }}
                            </p>
                        </div>

                        @if($project->description)
                            <div class="p-4 rounded-2xl bg-slate-50 border-l-4 border-[#c3122e] border-y border-r border-slate-200/80">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Strategic Objectives &amp; Scope</span>
                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                    {{ $project->description }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Configured WBS Blueprint Architecture -->
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center justify-center text-lg shadow-2xs">
                                    📋
                                </div>
                                <div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">WBS Blueprint Architecture</span>
                                    <h4 class="text-sm font-black text-slate-900">
                                        {{ $project->template->name ?? ($project->wbs_breakdown_type === 'template' ? 'Standard Blueprint Template' : 'Custom Agile WBS Canvas') }}
                                    </h4>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-xl text-xs font-black bg-blue-50 text-blue-900 border border-blue-200 shadow-2xs font-mono">
                                {{ $project->wbsItems->count() }} Tasks Pre-Built
                            </span>
                        </div>

                        @if($project->template && $project->template->description)
                            <p class="text-xs text-slate-500 font-medium">
                                {{ $project->template->description }}
                            </p>
                        @endif

                        @if($project->wbsItems->whereNull('parent_id')->count() > 0)
                            <div class="space-y-2.5 pt-2 border-t border-slate-100">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Configured Delivery Phases ({{ $project->wbsItems->whereNull('parent_id')->count() }})</span>
                                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                    @foreach($project->wbsItems->whereNull('parent_id') as $phase)
                                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs hover:border-slate-300 transition-all shadow-2xs">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-7 h-7 rounded-xl bg-rose-50 border border-rose-200 text-[#c3122e] flex items-center justify-center font-black text-[11px] flex-shrink-0">
                                                    {{ $loop->iteration }}
                                                </div>
                                                <span class="font-black text-slate-900 truncate">{{ $phase->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2.5 flex-shrink-0 font-mono">
                                                <span class="text-slate-500 font-bold text-[11px]">{{ $phase->duration_days ?? 0 }} Days</span>
                                                <span class="px-2 py-0.5 rounded-lg bg-white border border-slate-200 text-slate-700 text-[10px] font-extrabold shadow-2xs">
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
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-sm border border-emerald-200 shadow-2xs">
                                📅
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Timeline &amp; Fiscal Estimates</h3>
                                <span class="text-[10px] text-slate-400 font-medium">Scheduled Delivery Window</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Kick-off Date</span>
                                <span class="text-xs font-black text-slate-900 font-mono block mt-1">
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Target Delivery</span>
                                <span class="text-xs font-black text-[#c3122e] font-mono block mt-1">
                                    {{ $project->deadline ? $project->deadline->format('M d, Y') : 'TBD' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Total Est. Budget</span>
                                <div class="flex items-baseline gap-1 mt-0.5">
                                    <span class="text-xs font-bold text-slate-400">Rs.</span>
                                    <span class="text-base font-black text-slate-900 font-mono">{{ number_format($project->estimated_budget ?? 0, 0) }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Estimated Duration</span>
                                <span class="text-xs font-black text-slate-800 font-mono block mt-1">
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
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-3.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-black text-sm border border-amber-200 shadow-2xs">
                                    👥
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Assigned Team Roster</h3>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $project->members->count() }} Designated Collaborators</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                            @foreach($project->members as $member)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs shadow-2xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-7 h-7 rounded-xl bg-slate-200 text-slate-800 font-black text-[11px] flex items-center justify-center flex-shrink-0 border border-white shadow-2xs">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span class="font-black text-slate-900 truncate">{{ $member->name }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-extrabold uppercase tracking-wider {{ $member->id === $project->project_manager_id ? 'bg-rose-50 text-[#c3122e] border border-rose-200' : 'bg-white text-slate-600 border border-slate-200' }}">
                                        {{ $member->id === $project->project_manager_id ? 'Project Leader' : ($member->pivot->role ?? 'Member') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 🚀 EXECUTIVE ACCEPTANCE COMMAND CARD -->
                    <div class="bg-gradient-to-br from-white via-rose-50/30 to-amber-50/20 p-6 rounded-3xl border-2 border-[#c3122e]/30 shadow-lg space-y-4">
                        <div class="space-y-1.5">
                            <span class="text-[10px] font-black text-[#c3122e] uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-[#c3122e] animate-ping"></span>
                                <span>LEADERSHIP SIGN-OFF DECISION</span>
                            </span>
                            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                Accepting leadership establishes your operational authority over deliverables, team assignment, timeline scheduling, and budget governance.
                            </p>
                        </div>

                        <div class="space-y-2.5 pt-2 border-t border-rose-100">
                            <!-- Primary Accept Button -->
                            <button
                                wire:click="acceptAssignment"
                                type="button"
                                class="w-full py-3.5 px-6 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
                                style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Accept Leadership &amp; Unlock Workspace →</span>
                            </button>

                            <!-- Decline / Report Button -->
                            <div class="flex items-center gap-2">
                                <button
                                    wire:click="openRejectionModal"
                                    type="button"
                                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all cursor-pointer active:scale-95 text-center shadow-2xs"
                                >
                                    ✕ Decline &amp; Report
                                </button>
                                <button
                                    wire:click="openProjectDetailsModal"
                                    type="button"
                                    class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer active:scale-95 text-center shadow-2xs"
                                >
                                    👁️ Full Brief
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- ===== 2. HERO GANTT SCHEDULE COMPONENT (ALWAYS DISPLAYED AT TOP) ===== -->
        <div class="mb-6">
            <livewire:gantt-chart :project="$project" />
        </div>

        <!-- ===== 3. EXECUTIVE SEGMENTED TABS CONTROLS ===== -->
        <div class="bg-white p-1.5 sm:p-2 rounded-2xl border border-slate-200/90 shadow-2xs mb-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 overflow-hidden">
            <div class="flex items-center gap-1 sm:gap-1.5 overflow-x-auto scrollbar-none p-0.5">
                @foreach([
                    'overview'  => ['Overview', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    'wbs'       => ['Task List', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    'kanban'    => ['Kanban', 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7'],
                    'updates'   => ['Status Updates', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    'risks'     => ['Risks & Blockers', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                    'approvals' => ['Approvals', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as $tabKey => [$tabLabel, $tabIcon])
                    <button
                        wire:click="$set('activeTab', '{{ $tabKey }}')"
                        type="button"
                        class="flex items-center gap-2 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-[13px] font-black transition-all duration-200 cursor-pointer flex-shrink-0 {{ $activeTab === $tabKey ? 'text-white shadow-md scale-[1.02]' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 bg-transparent' }}"
                        @if($activeTab === $tabKey) style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);" @endif
                    >
                        <svg class="w-4 h-4 flex-shrink-0 {{ $activeTab === $tabKey ? 'text-rose-200' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcon }}"/>
                        </svg>
                        <span>{{ $tabLabel }}</span>
                        @if($tabKey === 'approvals')
                            @php
                                $pendingProjectApprovals = $project->approvalRequests->where('status', \App\Enums\ApprovalStatus::PENDING)->count();
                                $isPendingPmAcceptance = !$project->isPmAccepted() && $project->project_manager_id === auth()->id();
                                $approvalsTabCount = $pendingProjectApprovals + ($isPendingPmAcceptance ? 1 : 0);
                            @endphp
                            @if($approvalsTabCount > 0)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $activeTab === $tabKey ? 'bg-white text-[#c3122e]' : 'bg-[#c3122e] text-white' }} shadow-2xs animate-pulse">
                                    {{ $approvalsTabCount }}
                                </span>
                            @endif
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Quick Action / Options Menu -->
            <div class="flex items-center justify-end gap-2 pr-1 pt-1 sm:pt-0">
                @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                    <button wire:click="openEditProjectModal" type="button" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-[#c3122e] hover:bg-rose-50 border border-slate-200/80 transition-all flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Edit Project Details">
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        <span class="hidden md:inline">Settings</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- ===== 4. TAB CONTENTS ===== -->
        <!-- 1. OVERVIEW TAB: RECREATED EXECUTIVE DASHBOARD -->
        @if($activeTab === 'overview')
            @php
                $totalTasks    = $project->wbsItems->count();
                $doneTasks     = $project->wbsItems->where('status', \App\Enums\WbsStatus::COMPLETED)->count();
                $inProgTasks   = $project->wbsItems->where('status', \App\Enums\WbsStatus::IN_PROGRESS)->count();
                $notStarted    = $project->wbsItems->where('status', \App\Enums\WbsStatus::NOT_STARTED)->count();
                $overdueTasks  = $project->wbsItems->filter(fn($i) => $i->isOverdue())->count();
                $daysLeft      = $project->deadline ? max(0, (int) now()->diffInDays($project->deadline, false)) : null;
                $budgetUsed    = ($project->estimated_budget ?? 0) > 0 ? min(100, round(($project->actual_cost / $project->estimated_budget) * 100)) : 0;
                $descText      = $project->description ? trim(preg_replace('/\s+/', ' ', strip_tags($project->description))) : null;
                $canManageTeam = auth()->user()->hasAnyRole(['super_admin', 'project_manager', 'pmo_admin']) || $project->project_manager_id === auth()->id();
            @endphp

            <div class="space-y-6">
                <!-- Main Overview 2-Column Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
                    <!-- Left Column (2/3 width) — Description & Updates -->
                    <div class="xl:col-span-2 space-y-5">
                        <!-- Project Description Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-rose-500 to-[#c3122e] flex items-center justify-center text-white shadow-2xs flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-900 text-sm leading-tight">Project Scope &amp; Objectives</h3>
                                        <p class="text-[10px] text-slate-400 font-medium">Core project mandate and deliverables</p>
                                    </div>
                                </div>
                                @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="openEditProjectModal" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit Scope</span>
                                    </button>
                                @endif
                            </div>

                            <div class="p-5">
                                @if($descText)
                                    <p class="text-sm text-slate-700 leading-relaxed font-medium whitespace-pre-line">{{ $descText }}</p>
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-extrabold text-slate-700">No project description added yet</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Add clear scope and deliverables for your team.</p>
                                        </div>
                                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                            <button wire:click="openEditProjectModal" class="px-4 py-2 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-sm transition-all cursor-pointer">
                                                + Add Description
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Status Updates -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-2xs flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-900 text-sm leading-tight">Recent Updates &amp; Logs</h3>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $project->statusUpdates->count() }} total status log{{ $project->statusUpdates->count() !== 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                <button wire:click="setTab('updates')" class="flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-all cursor-pointer">
                                    <span>View All Updates →</span>
                                </button>
                            </div>

                            <div class="p-5 space-y-3">
                                @forelse($project->statusUpdates->take(3) as $up)
                                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-slate-50 transition-colors">
                                        <div class="flex items-center justify-between gap-2 mb-1.5">
                                            <span class="text-xs font-black text-slate-900 truncate">{{ $up->title }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono flex-shrink-0">{{ $up->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-medium">{{ $up->summary }}</p>
                                        @if($up->creator)
                                            <div class="flex items-center gap-1.5 mt-2 pt-2 border-t border-slate-200/60 text-[10px] font-bold text-slate-500">
                                                <span>By {{ $up->creator->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-slate-400 text-xs font-medium">
                                        No status updates recorded yet. Click below to share your first progress update.
                                    </div>
                                @endforelse

                                <div class="pt-2">
                                    <button wire:click="setTab('updates')" class="w-full py-2.5 rounded-xl text-xs font-extrabold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        <span>Post a Status Update</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (1/3 width) — Team Roster & Quick Actions -->
                    <div class="space-y-5">
                        <!-- Team & Collaborators Roster Card -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
                            <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-slate-800 to-slate-950 flex items-center justify-center text-white shadow-2xs flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-900 text-sm leading-tight">Team Roster</h3>
                                        <p class="text-[10px] text-slate-400 font-medium">{{ $project->members->count() }} active member{{ $project->members->count() !== 1 ? 's' : '' }}</p>
                                    </div>
                                </div>
                                @if($canManageTeam)
                                    <button wire:click="openCollaboratorsModal" class="px-2.5 py-1 rounded-lg text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-2xs transition-all flex items-center gap-1 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        <span>Add</span>
                                    </button>
                                @endif
                            </div>

                            <div class="p-5 space-y-3">
                                <!-- Project Leader -->
                                <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/70 border border-rose-200/80">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-full bg-[#c3122e] text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            {{ strtoupper(substr($project->projectManager->name ?? 'N', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-black text-slate-900 block truncate">{{ $project->projectManager->name ?? 'Unassigned' }}</span>
                                            <span class="text-[10px] text-slate-500 block truncate font-medium">{{ $project->projectManager->email ?? '' }}</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#c3122e] text-white flex-shrink-0">
                                        👑 Lead
                                    </span>
                                </div>

                                <!-- Assigned Collaborators List -->
                                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                                    @php
                                        $otherMembers = $project->members->reject(fn($m) => $m->id === $project->project_manager_id);
                                    @endphp

                                    @forelse($otherMembers as $member)
                                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50/70 border border-slate-200/80 hover:bg-white hover:border-slate-300 transition-all">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-full bg-slate-700 text-white text-[10px] font-black flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <span class="text-xs font-extrabold text-slate-800 block truncate">{{ $member->name }}</span>
                                                    <span class="text-[10px] text-slate-400 block truncate font-medium">{{ $member->email }}</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-0.5 rounded border border-slate-200">
                                                    Member
                                                </span>
                                                @if($canManageTeam)
                                                    <button wire:click="removeCollaborator({{ $member->id }})" wire:confirm="Remove {{ $member->name }} from this project?" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Remove member">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 px-2 rounded-xl bg-slate-50 border border-dashed border-slate-200">
                                            <p class="text-xs font-extrabold text-slate-600">No team members added yet</p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Attach team members to collaborate and assign WBS tasks.</p>
                                        </div>
                                    @endforelse
                                </div>

                                @if($canManageTeam)
                                    <div class="pt-2">
                                        <button wire:click="openCollaboratorsModal" class="w-full py-2 rounded-xl text-xs font-extrabold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            <span>Manage All Participants</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Navigation Grid -->
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-4 space-y-3">
                            <span class="text-[11px] font-black text-slate-400 uppercase tracking-wider block">Quick Jump</span>
                            <div class="grid grid-cols-2 gap-2">
                                <button wire:click="setTab('wbs')" class="p-3 rounded-xl bg-slate-50 hover:bg-rose-50/70 border border-slate-200/80 hover:border-rose-200 text-left transition-all cursor-pointer group">
                                    <span class="text-base block mb-1">📋</span>
                                    <span class="text-xs font-black text-slate-800 group-hover:text-[#c3122e] block leading-tight">Task List</span>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $totalTasks }} Tasks</span>
                                </button>
                                <button wire:click="setTab('kanban')" class="p-3 rounded-xl bg-slate-50 hover:bg-rose-50/70 border border-slate-200/80 hover:border-rose-200 text-left transition-all cursor-pointer group">
                                    <span class="text-base block mb-1">📊</span>
                                    <span class="text-xs font-black text-slate-800 group-hover:text-[#c3122e] block leading-tight">Kanban</span>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Visual Board</span>
                                </button>
                                <button wire:click="setTab('risks')" class="p-3 rounded-xl bg-slate-50 hover:bg-rose-50/70 border border-slate-200/80 hover:border-rose-200 text-left transition-all cursor-pointer group">
                                    <span class="text-base block mb-1">⚠️</span>
                                    <span class="text-xs font-black text-slate-800 group-hover:text-[#c3122e] block leading-tight">Risks</span>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $project->risks->count() }} Recorded</span>
                                </button>
                                <button wire:click="setTab('updates')" class="p-3 rounded-xl bg-slate-50 hover:bg-rose-50/70 border border-slate-200/80 hover:border-rose-200 text-left transition-all cursor-pointer group">
                                    <span class="text-base block mb-1">📝</span>
                                    <span class="text-xs font-black text-slate-800 group-hover:text-[#c3122e] block leading-tight">Status Logs</span>
                                    <span class="text-[10px] text-slate-400 font-medium block mt-0.5">{{ $project->statusUpdates->count() }} Updates</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <p class="text-[11px] text-emerald-700 bg-emerald-50 p-2 rounded-lg border border-emerald-100"><strong>âœ“ Completed:</strong> {{ $u->work_completed }}</p>
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
                                    <p class="text-[10px] text-slate-500">Uploaded by {{ $doc->uploader->name ?? 'User' }} â€¢ {{ round($doc->file_size/1024, 1) }} KB</p>
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
                    <div class="text-center py-8 text-slate-400 text-xs">No comments yet â€” start the discussion!</div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- 8. RISKS & BLOCKERS TAB -->
    @if($activeTab === 'risks')
        @php
            $wbsItemIds = $project->wbsItems->pluck('id');
            $projectBlockers = \App\Models\TaskBlocker::whereIn('wbs_item_id', $wbsItemIds)
                ->with(['wbsItem', 'reporter', 'resolver'])
                ->latest()
                ->get();
            $openBlockersCount = $projectBlockers->where('status', '!=', 'resolved')->count();
        @endphp

        <div class="space-y-7">
            <!-- 1. Active Task Blockers Summary Banner & List -->
            <div class="card p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-black text-sm shadow-2xs">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-base">Active Task Blockers & Execution Issues</h3>
                            <p class="text-xs text-slate-500 font-medium">Real-time execution blockers reported by team members on specific WBS tasks</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $openBlockersCount > 0 ? 'bg-rose-100 text-rose-900 border border-rose-300' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                        {{ $openBlockersCount }} Open Blocker(s)
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($projectBlockers as $b)
                        @php
                            $sevColors = [
                                'low' => 'bg-slate-100 text-slate-700 border-slate-300',
                                'medium' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
                                'high' => 'bg-rose-50 text-rose-800 border-rose-200 font-bold',
                                'critical' => 'bg-red-100 text-red-900 border-red-300 font-black',
                            ];
                        @endphp
                        <div class="p-4 rounded-2xl border transition-all {{ $b->status === 'resolved' ? 'bg-slate-50/70 border-slate-200/80 opacity-75' : 'bg-white border-rose-200/90 shadow-2xs' }}">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2.5">
                                <!-- Affected Component Identifier -->
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-black bg-[#fdf4f4] text-[#c3122e] border border-[#faeaea] flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>ðŸ“ Component: {{ $b->wbsItem->title ?? 'General Scope' }} (Code: {{ $b->wbsItem->wbs_code ?? '-' }})</span>
                                    </span>

                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] uppercase tracking-wider font-extrabold border {{ $sevColors[$b->severity] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $b->severity }} Severity
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($b->status === 'resolved')
                                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                            âœ“ Resolved
                                        </span>
                                    @else
                                        @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                            <button wire:click="openResolveBlockerModal({{ $b->id }})" class="px-3 py-1.5 rounded-xl text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition-all cursor-pointer flex items-center gap-1">
                                                <span>Resolve Blocker</span>
                                            </button>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                Open
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <p class="text-xs font-semibold text-slate-800 leading-relaxed mb-3">{{ $b->description }}</p>

                            <div class="flex items-center justify-between text-[10px] text-slate-500 font-medium pt-2 border-t border-slate-100">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-[#c3122e] text-white font-bold flex items-center justify-center text-[9px]">
                                        {{ strtoupper(substr($b->reporter->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>Reported by <strong>{{ $b->reporter->name ?? 'Team Member' }}</strong> â€¢ {{ $b->created_at->diffForHumans() }}</span>
                                </div>

                                @if($b->status === 'resolved' && $b->resolution)
                                    <div class="text-emerald-800 font-semibold italic truncate max-w-sm">
                                        Resolution: "{{ $b->resolution }}" (by {{ $b->resolver->name ?? 'PM' }})
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-xs font-extrabold text-slate-800">No Active Task Blockers</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">All tasks are currently executing cleanly without reported blockers.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Risk Register Form Card -->
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
                            <span>ðŸ“ Affected Scope Component / WBS Task (Identity Location)</span>
                            <span class="text-[10px] text-[#c3122e] font-black uppercase">Links Risk to Exact Section</span>
                        </label>
                        <select wire:model="riskWbsItemId" class="form-select text-xs font-extrabold py-2.5 rounded-xl border-slate-200 focus:border-[#c3122e] bg-slate-50">
                            <option value="">ðŸŒ [General Project Level Scope]</option>
                            @foreach($project->wbsItems as $wbs)
                                <option value="{{ $wbs->id }}">
                                    ðŸ“ Code: {{ $wbs->wbs_code }} â€” {{ $wbs->title }} ({{ $wbs->item_type->value }})
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
                                            ðŸ“ Component: {{ $r->wbsItem->title }} (Code: {{ $r->wbsItem->wbs_code }})
                                        @else
                                            ðŸŒ Scope: General Project Level
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

                                <select
                                    wire:change="updateRiskStatus({{ $r->id }}, $event.target.value)"
                                    class="text-xs font-extrabold rounded-full px-3 py-1 border cursor-pointer {{ $riskStatusColors[$r->status] ?? 'bg-slate-100 text-slate-700' }}"
                                >
                                    <option value="open" @selected($r->status === 'open')>Open</option>
                                    <option value="monitoring" @selected($r->status === 'monitoring')>Monitoring</option>
                                    <option value="mitigated" @selected($r->status === 'mitigated')>Mitigated</option>
                                    <option value="closed" @selected($r->status === 'closed')>Closed</option>
                                </select>

                                @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                                    <button wire:click="openEditRiskModal({{ $r->id }})" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Edit Risk">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

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

        <!-- Blocker Resolution Modal -->
        <div x-data="{ open: @entangle('showResolveBlockerModal') }"
             x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display:none">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showResolveBlockerModal = false"></div>

            <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md z-10 p-6 sm:p-8">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900">Resolve Task Blocker</h3>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Provide resolution details to close this execution blocker</p>
                    </div>
                </div>

                <form wire:submit="saveBlockerResolution" class="space-y-4">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs">Resolution Summary <span class="text-rose-500">*</span></label>
                        <textarea wire:model="blockerResolutionInput" rows="4" placeholder="Detail the resolution steps taken to unblock the team..." class="form-input text-xs leading-relaxed rounded-xl"></textarea>
                        @error('blockerResolutionInput') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showResolveBlockerModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md">Mark Blocker Resolved</button>
                    </div>
                </form>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Probability</label>
                            <select wire:model="riskProbability" class="form-select text-xs font-bold py-2 rounded-xl">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Impact</label>
                            <select wire:model="riskImpact" class="form-select text-xs font-bold py-2 rounded-xl">
                                <option value="low">Low (1)</option>
                                <option value="medium">Medium (2)</option>
                                <option value="high">High (3)</option>
                                <option value="critical">Critical (4)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label font-extrabold text-slate-800 text-xs">Risk Description <span class="text-rose-500">*</span></label>
                        <textarea wire:model="riskDescription" rows="3" class="form-input text-xs rounded-xl p-3 border-slate-200 focus:border-[#c3122e]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Mitigation Strategy</label>
                            <input type="text" wire:model="riskMitigation" class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="form-label font-extrabold text-slate-800 text-xs">Contingency Plan</label>
                            <input type="text" wire:model="riskContingency" class="form-input text-xs py-2.5 rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="open = false; $wire.showEditRiskModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-slate-900 hover:bg-black shadow-md">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- 9. APPROVALS TAB -->
    @if($activeTab === 'approvals')
        <div class="space-y-6">
            <!-- 👑 PENDING LEADERSHIP ACCEPTANCE ALERT IN APPROVALS TAB -->
            @if(!$project->isPmAccepted() && $project->project_manager_id === auth()->id())
                <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-amber-500/10 via-rose-500/10 to-amber-500/10 border-2 border-amber-300 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-[#c3122e] text-white flex items-center justify-center text-xl shadow-md flex-shrink-0 border border-white/20">
                            👑
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm sm:text-base font-black text-slate-900">Project Leadership Acceptance Required</h4>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 animate-pulse">ACTION NEEDED</span>
                            </div>
                            <p class="text-xs text-slate-600 font-medium mt-0.5">You have been designated as Project Leader for this project. Please accept leadership to unlock and manage workspaces.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button wire:click="openRejectionModal" type="button" class="px-4 py-2.5 rounded-xl text-xs font-bold text-rose-700 bg-white hover:bg-rose-50 border border-rose-200 shadow-2xs cursor-pointer">
                            ✕ Decline
                        </button>
                        <button wire:click="acceptAssignment" type="button" class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md cursor-pointer flex items-center gap-1.5 active:scale-95">
                            <span>✓ Accept Leadership →</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Submit Approval Request Form (Available to all 3 roles: Admin, PM, Team Member) --}}
            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
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
                                'deadline_extension' => isset($rv['new_deadline']) ? 'â†’ New Deadline: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y') : null,
                                'budget_change'      => isset($rv['new_budget']) ? 'â†’ New Budget: Rs. ' . number_format($rv['new_budget'], 0) : null,
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
                                        Submitted by <strong>{{ $req->requester->name ?? 'User' }}</strong> â€¢ {{ $req->created_at->format('M d, Y') }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusCls }}">
                                        {{ $req->status->label() }}
                                    </span>

                                    {{-- Actions for 3 roles: Approve/Reject for Admin & PM; Withdraw for Requester --}}
                                    @if($req->status->value === 'pending')
                                        @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
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
                    <p>â€¢ Setting an accurate target deadline helps track project velocity and alerts your team of upcoming due dates.</p>
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

    <!-- Edit Project Details Modal -->
    <div x-data="{ open: @entangle('showEditProjectModal') }"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false; $wire.showEditProjectModal = false"></div>

        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-lg z-10 p-6 sm:p-7">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] border border-[#faeaea] flex items-center justify-center text-[#c3122e] flex-shrink-0 font-bold text-sm">
                    âœï¸
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base">Edit Project Details &amp; Scope</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Project Owner can update project title, scope description, priority, &amp; budget</p>
                </div>
            </div>

            <form wire:submit="saveProjectDetails" class="space-y-4">
                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Project Name <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="editName" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-bold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none" required>
                    @error('editName') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Project Scope &amp; Detailed Objectives</label>
                    <textarea wire:model="editDescription" rows="4" placeholder="Enter detailed project scope, milestones, and strategic deliverables..." class="w-full p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs leading-relaxed font-normal text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none"></textarea>
                    @error('editDescription') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Priority Level <span class="text-rose-500">*</span></label>
                        <select wire:model="editPriority" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none" required>
                            @foreach(\App\Enums\Priority::cases() as $p)
                                <option value="{{ $p->value }}">{{ $p->label() }} Priority</option>
                            @endforeach
                        </select>
                        @error('editPriority') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label font-extrabold text-slate-800 text-xs mb-1.5 block">Estimated Budget (LKR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-bold text-slate-400">Rs.</span>
                            <input type="number" step="1" wire:model="editEstimatedBudget" placeholder="0" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs font-extrabold text-slate-900 focus:bg-white focus:border-[#c3122e] outline-none">
                        </div>
                        @error('editEstimatedBudget') <span class="text-xs text-rose-600 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" @click="open = false; $wire.showEditProjectModal = false" class="px-4 py-2 rounded-xl text-xs font-extrabold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-extrabold text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md shadow-[#c3122e]/25">
                        <span>Save Project Details</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Role Type</label>
                            <select wire:model="newCollabRole" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-900 outline-none">
                                <option value="team_member">Team Member / Collaborator</option>
                                <option value="project_manager">Project Manager / Lead</option>
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
                <div class="text-xs font-extrabold text-slate-700 mb-2">Select Staff Members for this Project:</div>

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
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $isOwner ? 'bg-[#fdf4f4] text-[#c3122e] border border-[#f5c2c9]' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $isOwner ? 'Leader' : 'Participant' }}
                                </span>

                                @if(!$isOwner && auth()->id() !== $u->id && auth()->user()->hasRole('super_admin'))
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

    <!-- ===== REASSIGN PROJECT LEADER MODAL (PMO ADMIN) ===== -->
    @if($showReassignModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
        <div class="relative bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-[#c3122e] text-white flex items-center justify-center font-black text-xs">
                        ðŸ‘‘
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Reassign Project Leader</h3>
                        <p class="text-[10px] text-slate-400 font-medium">Select a new leader for this project</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showReassignModal', false)" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="submitReassign" class="p-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">
                        Select New Leader <span class="text-rose-500">*</span>
                    </label>
                    <select wire:model="newLeaderId" class="w-full p-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-900 outline-none focus:border-[#c3122e]" required>
                        <option value="">Choose a leader...</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} Â· {{ $user->email }} ({{ $user->subsidiary->code ?? 'GS' }})</option>
                        @endforeach
                    </select>
                    @error('newLeaderId') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showReassignModal', false)" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md">
                        Confirm Reassignment
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- ===== COMPREHENSIVE PROJECT DETAILS EXECUTIVE MODAL (CLEAN LUXURY BENTO) ===== -->
    @if($showProjectDetailsModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 md:p-10 animate-fade-in" x-data x-trap="true">
        <!-- Overlay Backdrop with Deep Blur -->
        <div class="fixed inset-0 backdrop-blur-md transition-opacity bg-black/70" wire:click="closeProjectDetailsModal"></div>

        <!-- Popup Modal Container -->
        <div class="relative bg-white rounded-3xl max-w-4xl w-full shadow-2xl z-10 flex flex-col max-h-[92vh] overflow-hidden border border-slate-200 ring-1 ring-black/10 animate-in fade-in zoom-in-95 duration-200">
            
            <!-- 1. Executive Skyline Modal Header (Luxury Crimson & Gold) -->
            <div class="relative px-6 sm:px-8 py-5 border-b border-rose-950/40 flex-shrink-0 text-white overflow-hidden" style="background: #38050e;">
                <!-- Background Cityscape Skyline -->
                <div class="absolute inset-0 pointer-events-none overflow-hidden select-none">
                    <img src="{{ asset('images/project-banner-luxury-2x.jpg') }}" alt="Skyline" class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#200308]/90 via-[#36050e]/50 to-transparent sm:w-1/2"></div>
                </div>
                <!-- Top Gold/Crimson Ambient Glow -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-400 via-rose-500 to-amber-300 shadow-sm shadow-amber-500/50 z-20"></div>

                <div class="relative z-10 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5 sm:gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-xl flex-shrink-0 border-2 border-white/20 ring-2 ring-rose-500/30 bg-black/50">
                            <img src="{{ asset('images/project-app-icon.jpg') }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-lg font-mono text-[10px] font-black bg-amber-400/20 text-amber-300 border border-amber-400/40 shadow-xs">
                                    {{ $project->code }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold text-slate-300 bg-white/10 border border-white/15 backdrop-blur-sm">
                                    {{ $project->subsidiary->name ?? 'George Steuart Group' }}
                                </span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight truncate mt-1">
                                {{ $project->name }}
                            </h2>
                            <p class="text-rose-300/80 text-[11px] font-semibold flex items-center gap-1.5 mt-0.5">
                                <span>📅 Inception: {{ $project->created_at->format('M d, Y') }}</span>
                                <span>•</span>
                                <span>Category: {{ $project->category ?? 'Corporate Strategy' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Close Button -->
                    <button 
                        wire:click="closeProjectDetailsModal" 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl text-white font-black text-xs transition-all cursor-pointer flex items-center gap-1.5 bg-white/10 hover:bg-white/20 border border-white/20 shadow-md backdrop-blur-md flex-shrink-0 hover:scale-105 active:scale-95"
                        title="Close Project Details"
                    >
                        <svg class="w-4 h-4 text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Close</span>
                    </button>
                </div>
            </div>

            <!-- 2. Modal Body Content (Clean Solid Background & Bento Cards) -->
            <div class="flex-1 overflow-y-auto p-5 sm:p-7 space-y-5 daily-update-scroll bg-slate-50">
                
                <!-- Section 1: Executive KPI & Live Controls (4 Solid Cards) -->
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Executive Delivery &amp; Control Specs</span>
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 font-mono">LIVE CONTROLS</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <!-- 1. Execution Status -->
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">EXECUTION STATUS</span>
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            </div>
                            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
                                <div class="mt-1">
                                    <select
                                        wire:change="updateProjectStatus($event.target.value)"
                                        class="w-full px-3 py-2 rounded-xl text-xs font-black bg-blue-50 text-blue-900 border border-blue-200 shadow-xs focus:ring-2 focus:ring-blue-500 cursor-pointer outline-none transition-all"
                                    >
                                        @foreach(\App\Enums\ProjectStatus::cases() as $st)
                                            <option value="{{ $st->value }}" @selected($project->status->value === $st->value) class="bg-white text-slate-900">
                                                {{ $st->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="mt-1 px-3 py-2 rounded-xl text-xs font-black bg-blue-50 text-blue-900 border border-blue-200 shadow-xs inline-flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    <span>{{ $project->status->label() }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- 2. Delivery Health -->
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">DELIVERY HEALTH</span>
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager']) || $project->members->contains(auth()->id()))
                                <div class="mt-1">
                                    <select
                                        wire:change="updateProjectHealth($event.target.value)"
                                        class="w-full px-3 py-2 rounded-xl text-xs font-black bg-emerald-50 text-emerald-900 border border-emerald-200 shadow-xs focus:ring-2 focus:ring-emerald-500 cursor-pointer outline-none transition-all"
                                    >
                                        @foreach(\App\Enums\ProjectHealth::cases() as $hl)
                                            <option value="{{ $hl->value }}" @selected($project->health->value === $hl->value) class="bg-white text-slate-900">
                                                {{ $hl->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="mt-1 px-3 py-2 rounded-xl text-xs font-black bg-emerald-50 text-emerald-900 border border-emerald-200 shadow-xs inline-flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                    <span>{{ $project->health->label() }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- 3. Priority Tier -->
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">PRIORITY TIER</span>
                                <span class="text-xs">⚡</span>
                            </div>
                            <div class="mt-1 px-3 py-2 rounded-xl text-xs font-black bg-amber-50 text-amber-900 border border-amber-200 shadow-xs flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>{{ ucfirst($project->priority->value) }} Priority</span>
                                </span>
                                <span class="text-[9px] font-mono text-amber-700 font-bold uppercase">Active</span>
                            </div>
                        </div>

                        <!-- 4. Target Deadline -->
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">TARGET DEADLINE</span>
                                @if($project->deadline)
                                    @if($project->deadline->isPast())
                                        <span class="text-[8px] font-black bg-rose-600 text-white px-1.5 py-0.2 rounded-md">OVERDUE</span>
                                    @else
                                        <span class="text-[8px] font-black bg-emerald-600 text-white px-1.5 py-0.2 rounded-md font-mono">{{ (int) now()->diffInDays($project->deadline, false) }}d left</span>
                                    @endif
                                @endif
                            </div>
                            <div class="mt-1 px-3 py-2 rounded-xl text-xs font-black bg-slate-50 text-slate-800 border border-slate-200 shadow-xs flex items-center gap-2 font-mono">
                                <svg class="w-4 h-4 text-[#c3122e]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $project->deadline ? $project->deadline->format('M d, Y') : 'Not Set' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Financial Utilization & WBS Architecture (2 Solid Bento Cards) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- 💰 Financial Budget & Spend Card -->
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-sm border border-emerald-200 shadow-2xs">
                                    💰
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Financial Budget &amp; Spend</h3>
                                    <span class="text-[10px] text-slate-400 font-medium">Fiscal Allocation Breakdown</span>
                                </div>
                            </div>
                            @php
                                $bPct = ($project->estimated_budget ?? 0) > 0 ? min(100, round(($project->actual_cost / $project->estimated_budget) * 100)) : 0;
                            @endphp
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black font-mono {{ $bPct > 90 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                {{ $bPct }}% Utilized
                            </span>
                        </div>

                        <!-- Main Financial Metrics Grid -->
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="p-3.5 rounded-2xl bg-slate-50/90 border border-slate-100">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Actual Cost</span>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-xs font-bold text-rose-600">Rs.</span>
                                    <span class="text-lg font-black text-slate-900 font-mono">{{ number_format($project->actual_cost ?? 0, 0) }}</span>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50/90 border border-slate-100">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Total Budget</span>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-xs font-bold text-slate-400">Rs.</span>
                                    <span class="text-lg font-black text-slate-700 font-mono">{{ number_format($project->estimated_budget ?? 0, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400">
                                <span>Expenditure Burn</span>
                                <span class="font-mono text-slate-600">Variance: Rs. {{ number_format(max(0, ($project->estimated_budget ?? 0) - ($project->actual_cost ?? 0)), 0) }}</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden p-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-[#c3122e] rounded-full transition-all duration-700 shadow-xs" style="width: {{ max(4, $bPct) }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 🏗️ Breakdown & Timeline Architecture Card -->
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 text-[#c3122e] flex items-center justify-center font-black text-sm border border-rose-200 shadow-2xs">
                                    🏗️
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Breakdown &amp; WBS Architecture</h3>
                                    <span class="text-[10px] text-slate-400 font-medium">Timeline Execution Blueprint</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black font-mono bg-rose-50 text-[#c3122e] border border-rose-200">
                                {{ $project->wbsItems->count() }} Deliverables
                            </span>
                        </div>

                        <!-- Architecture Info Box -->
                        <div class="p-3.5 rounded-2xl bg-slate-50/90 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Methodology Blueprint</span>
                                <span class="text-xs font-black text-slate-900 block mt-0.5">
                                    {{ $project->template->name ?? ($project->wbs_breakdown_type === 'template' ? 'Standard Blueprint Template' : 'Custom Agile WBS Canvas') }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Completion</span>
                                <span class="text-xs font-black text-emerald-700 font-mono block mt-0.5">
                                    {{ $project->overall_progress }}%
                                </span>
                            </div>
                        </div>

                        <!-- Milestones Strip -->
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <div class="p-2.5 rounded-xl bg-slate-50/90 border border-slate-100 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Kick-off Date</span>
                                <span class="text-xs font-black text-slate-800 font-mono block mt-0.5">
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : '—' }}
                                </span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50/90 border border-slate-100 text-center">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Target Deadline</span>
                                <span class="text-xs font-black text-[#c3122e] font-mono block mt-0.5">
                                    {{ $project->deadline ? $project->deadline->format('M d, Y') : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Leadership & Assigned Team Members Card -->
                <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-4 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-black text-sm border border-amber-200 shadow-2xs">
                                👥
                            </div>
                            <div>
                                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Project Leadership &amp; Team Roster</h3>
                                <span class="text-[10px] text-slate-400 font-medium">Designated Lead and Active Collaborators</span>
                            </div>
                        </div>
                        @if(auth()->user()->hasAnyRole(['super_admin', 'project_manager', 'pmo_admin']) || $project->project_manager_id === auth()->id())
                            <button 
                                wire:click="openCollaboratorsModal" 
                                type="button" 
                                class="px-3 py-1.5 rounded-xl text-xs font-black text-[#c3122e] hover:bg-rose-50 border border-rose-200 transition-all cursor-pointer flex items-center gap-1 active:scale-95 shadow-2xs"
                            >
                                <span>Manage Team</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        @endif
                    </div>

                    <!-- Lead Manager Card -->
                    <div class="p-4 rounded-2xl bg-gradient-to-r from-rose-50/80 via-white to-rose-50/40 border border-rose-200/90 flex items-center justify-between gap-4 shadow-2xs">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-2xl font-black text-sm text-white flex items-center justify-center shadow-md flex-shrink-0 border-2 border-white/80" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                {{ strtoupper(substr($project->projectManager->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-black text-sm text-slate-900 truncate">{{ $project->projectManager->name ?? 'Unassigned' }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-wider bg-[#c3122e] text-white shadow-2xs">PROJECT LEADER</span>
                                </div>
                                <span class="text-xs text-slate-500 font-medium block truncate mt-0.5">{{ $project->projectManager->email ?? 'No email' }}</span>
                            </div>
                        </div>
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-[9px] font-black text-rose-700 uppercase tracking-wider bg-white px-2 py-0.5 rounded-md border border-rose-200 shadow-2xs">Lead Owner</span>
                            <span class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $project->projectManager->subsidiary->code ?? 'GS' }} Corp</span>
                        </div>
                    </div>

                    <!-- Collaborators List -->
                    @if($project->members->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-1">
                            @foreach($project->members as $member)
                                <div class="p-3 rounded-2xl bg-slate-50/90 border border-slate-200/80 flex items-center gap-3 hover:border-slate-300 transition-all shadow-2xs">
                                    <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-800 font-black text-xs flex items-center justify-center flex-shrink-0 border border-white shadow-2xs">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="font-black text-xs text-slate-900 block truncate">{{ $member->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-semibold block truncate capitalize">{{ str_replace('_', ' ', $member->pivot->role ?? 'Collaborator') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-400 italic p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            No additional team members assigned yet.
                        </p>
                    @endif
                </div>

                <!-- Section 4: Project Description (If provided) -->
                @if($project->description)
                    <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/90 shadow-sm space-y-2.5">
                        <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                            <span>📝</span>
                            <span>Executive Scope &amp; Description</span>
                        </h3>
                        <p class="text-xs text-slate-700 font-medium leading-relaxed bg-slate-50/80 p-4 rounded-2xl border-l-4 border-[#c3122e] border-y border-r border-slate-200/80">
                            {{ $project->description }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- 3. Modal Footer -->
            <div class="px-6 sm:px-8 py-4 border-t border-slate-200 bg-white flex items-center justify-between gap-4 flex-shrink-0">
                <div class="text-[11px] text-slate-400 font-bold hidden sm:block">
                    George Steuart Project Management Suite • Executive View
                </div>
                <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                    @if(auth()->user()->hasRole('super_admin') || $project->project_manager_id === auth()->id())
                        <button 
                            wire:click="openEditProjectModal" 
                            type="button" 
                            class="px-4 py-2 rounded-xl text-xs font-black text-[#c3122e] bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all cursor-pointer active:scale-95 shadow-2xs"
                        >
                            Edit Project
                        </button>
                    @endif
                    <button 
                        wire:click="closeProjectDetailsModal" 
                        type="button" 
                        class="px-6 py-2 rounded-xl text-xs font-black text-white bg-[#c3122e] hover:bg-[#a00e24] shadow-md transition-all cursor-pointer active:scale-95"
                    >
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

