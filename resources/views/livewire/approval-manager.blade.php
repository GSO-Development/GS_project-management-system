<div>
    {{-- ══════════════════════════════════════════════════════
         APPROVAL WORKFLOWS — Governance & Team Request Desk
    ══════════════════════════════════════════════════════ --}}

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-5 sm:p-7 lg:p-8 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration -->
        <div class="absolute right-0 top-0 bottom-0 w-3/5 pointer-events-none opacity-30 overflow-hidden hidden md:flex items-center justify-end">
            <img src="{{ asset('images/project-banner-dark.jpg') }}" alt="Skyline" class="h-full w-full object-cover object-right" style="-webkit-mask-image: linear-gradient(to right, transparent 0%, black 45%); mask-image: linear-gradient(to right, transparent 0%, black 45%);">
        </div>

        <!-- Gold Elegant Wave Swoosh Vector Overlay -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden opacity-35 hidden md:block">
            <svg viewBox="0 0 1200 400" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 460 0 C 560 160 620 260 780 400" stroke="#f59e0b" stroke-width="2.5" opacity="0.75" />
                <path d="M 480 0 C 580 160 640 260 800 400" stroke="#c3122e" stroke-width="1.5" opacity="0.5" />
            </svg>
        </div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <!-- Left Side: Shield Icon + Title + Meta -->
            <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                <div class="w-12 h-12 sm:w-15 sm:h-15 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-2.5 sm:p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Approval Workflows
                        </h1>
                        <span class="px-3 py-0.5 sm:py-1 rounded-full text-[11px] sm:text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-1.5 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>✓</span>
                            <span>Governance Sign-Off Desk</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-300 mt-1 sm:mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Review, submit, and track formal project sign-offs and change requests</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Primary Action -->
            <div class="flex items-center gap-3 flex-shrink-0 w-full sm:w-auto">
                <button
                    wire:click="openCreateModal"
                    type="button"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer w-full sm:w-auto"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Submit Approval Request</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         2. FILTERS & METRICS TOOLBAR
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs mb-6 p-4 sm:p-5 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full lg:w-auto flex-1 max-w-3xl">
            {{-- Scope Filter --}}
            <div class="flex flex-col">
                <label class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Scope</label>
                <div class="relative">
                    <select wire:model.live="scopeFilter" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none text-slate-800 transition-all cursor-pointer">
                        <option value="all">🌐 All Accessible Requests</option>
                        <option value="my_requests">👤 My Submitted Requests</option>
                    </select>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="flex flex-col">
                <label class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Status</label>
                <div class="relative">
                    <select wire:model.live="statusFilter" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none text-slate-800 transition-all cursor-pointer">
                        <option value="all">📋 All Statuses</option>
                        <option value="pending">⏳ Pending Review</option>
                        <option value="approved">✅ Approved</option>
                        <option value="rejected">❌ Rejected</option>
                        <option value="revision_required">🔄 Revision Required</option>
                        <option value="cancelled">🚫 Cancelled</option>
                    </select>
                </div>
            </div>

            {{-- Request Type Filter --}}
            <div class="flex flex-col">
                <label class="text-[10px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Request Type</label>
                <div class="relative">
                    <select wire:model.live="typeFilter" class="w-full text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-3.5 pr-8 focus:bg-white focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none text-slate-800 transition-all cursor-pointer">
                        <option value="all">📂 All Request Types</option>
                        @foreach(\App\Enums\ApprovalType::cases() as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Summary KPI Badges --}}
        <div class="flex items-center gap-2 text-xs flex-wrap w-full lg:w-auto justify-start lg:justify-end">
            @php
                $user = auth()->user();
                $baseQuery = \App\Models\ApprovalRequest::query();
                if ($scopeFilter === 'my_requests') {
                    $baseQuery->where('requested_by', $user->id);
                } elseif (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
                    $baseQuery->where(function($q) use ($user) {
                        $q->where('requested_by', $user->id)
                          ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id))
                          ->orWhere(function($memberQuery) use ($user) {
                              $memberQuery->whereHas('project.members', fn($mq) => $mq->where('users.id', $user->id))
                                          ->where('request_type', '!=', \App\Enums\ApprovalType::NEW_PROJECT_PLAN);
                          });
                    });
                }
                $pendingCount  = (clone $baseQuery)->where('status','pending')->count();
                $approvedCount = (clone $baseQuery)->where('status','approved')->count();
                $rejectedCount = (clone $baseQuery)->where('status','rejected')->count();
            @endphp
            <div class="px-3.5 py-2 rounded-xl bg-amber-50 border border-amber-200/90 text-amber-900 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-amber-500 {{ $pendingCount > 0 ? 'animate-pulse' : '' }}"></span>
                <span>{{ $pendingCount }} Pending</span>
            </div>
            <div class="px-3.5 py-2 rounded-xl bg-emerald-50 border border-emerald-200/90 text-emerald-900 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $approvedCount }} Approved</span>
            </div>
            <div class="px-3.5 py-2 rounded-xl bg-rose-50 border border-rose-200/90 text-rose-900 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>{{ $rejectedCount }} Rejected</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         3. APPROVAL REQUESTS TABLE
         ═══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                        <th class="py-4 px-5">Project &amp; Subsidiary</th>
                        <th class="py-4 px-4">Request Type &amp; Details</th>
                        <th class="py-4 px-4">Requested By</th>
                        <th class="py-4 px-4">Reason / Justification</th>
                        <th class="py-4 px-4">Reviewed / Signed By</th>
                        <th class="py-4 px-4">Submitted</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($requests as $req)
                        @php
                            $rv = $req->requested_value ?? [];
                            $isNewProjectPlan = ($req->request_type->value === 'new_project_plan');
                            $projectName = $req->project->name ?? ($rv['project_name'] ?? 'Project');
                            $projectCode = $req->project->code ?? ($rv['project_code'] ?? null);
                            $subsidiaryName = $req->project?->subsidiary?->name ?? null;

                            $typeBadge = match($req->request_type->value) {
                                'new_project_plan'   => ['icon' => '⭐', 'label' => 'Project Manager Assignment', 'bg' => 'bg-rose-50',    'text' => 'text-[#c3122e]', 'border' => 'border-rose-200'],
                                'deadline_extension' => ['icon' => '📅', 'label' => 'Deadline Extension',        'bg' => 'bg-blue-50',    'text' => 'text-blue-800',  'border' => 'border-blue-200'],
                                'budget_change'      => ['icon' => '💰', 'label' => 'Budget Change',             'bg' => 'bg-emerald-50', 'text' => 'text-emerald-800','border' => 'border-emerald-200'],
                                'scope_change'       => ['icon' => '📝', 'label' => 'Scope Change',              'bg' => 'bg-amber-50',   'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                                'wbs_baseline'       => ['icon' => '📋', 'label' => 'Milestone Baseline',        'bg' => 'bg-purple-50',  'text' => 'text-purple-800','border' => 'border-purple-200'],
                                'project_completion' => ['icon' => '✅', 'label' => 'Project Completion',        'bg' => 'bg-emerald-50', 'text' => 'text-emerald-800','border' => 'border-emerald-200'],
                                'project_cancellation'=>['icon' => '🚫', 'label' => 'Project Cancellation',       'bg' => 'bg-slate-100',  'text' => 'text-slate-800', 'border' => 'border-slate-300'],
                                'updated_wbs_plan'   => ['icon' => '🔄', 'label' => 'WBS Revision Plan',         'bg' => 'bg-violet-50',  'text' => 'text-violet-800','border' => 'border-violet-200'],
                                default              => ['icon' => '📌', 'label' => $req->request_type->label(), 'bg' => 'bg-slate-50',   'text' => 'text-slate-800', 'border' => 'border-slate-200'],
                            };

                            $subDetail = match($req->request_type->value) {
                                'deadline_extension' => isset($rv['new_deadline']) ? 'New date: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y') : null,
                                'budget_change' => isset($rv['new_budget']) ? 'New budget: Rs. ' . number_format($rv['new_budget'], 0) : null,
                                'scope_change' => isset($rv['scope_description']) ? \Illuminate\Support\Str::limit($rv['scope_description'], 35) : null,
                                'new_project_plan' => 'PM Review & Workspace Setup',
                                default => null,
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/75 transition-colors group">
                            {{-- 1. Project & Subsidiary --}}
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-black text-xs shadow-sm" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($projectName, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-black text-slate-900 text-xs truncate max-w-xs block">{{ $projectName }}</div>
                                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                            @if($projectCode)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-black font-mono bg-slate-100 text-slate-700 border border-slate-200 whitespace-nowrap shadow-2xs">
                                                    {{ $projectCode }}
                                                </span>
                                            @endif
                                            @if($subsidiaryName)
                                                <span class="text-[10px] text-slate-500 font-bold truncate max-w-[140px] flex items-center gap-1">
                                                    <span>🏢</span>
                                                    <span>{{ $subsidiaryName }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Request Type & Details --}}
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black {{ $typeBadge['bg'] }} {{ $typeBadge['text'] }} border {{ $typeBadge['border'] }} whitespace-nowrap shadow-2xs">
                                        <span>{{ $typeBadge['icon'] }}</span>
                                        <span>{{ $typeBadge['label'] }}</span>
                                    </span>
                                    @if($subDetail)
                                        <div class="text-[10px] font-bold text-slate-500 font-mono pl-1">
                                            ↳ {{ $subDetail }}
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- 3. Requested By --}}
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-[10px] font-black flex-shrink-0 shadow-2xs" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                        {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs text-slate-900 font-black block truncate">{{ $req->requester->name ?? '—' }}</span>
                                        @if($req->requested_by === auth()->id())
                                            <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-black uppercase bg-rose-50 text-[#c3122e] border border-rose-200">You</span>
                                        @else
                                            <span class="text-[10px] text-slate-400 font-medium block">PMO Admin</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- 4. Reason / Justification --}}
                            <td class="py-4 px-4 text-xs text-slate-600 max-w-xs">
                                <p class="line-clamp-2 leading-relaxed font-medium" title="{{ $req->reason }}">{{ $req->reason }}</p>
                            </td>

                            {{-- 5. Reviewed / Signed By --}}
                            <td class="py-4 px-4">
                                @if($req->reviewer)
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-black flex-shrink-0 border border-emerald-200 shadow-2xs">
                                            {{ strtoupper(substr($req->reviewer->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs text-slate-900 font-bold block truncate">{{ $req->reviewer->name }}</span>
                                            @if($req->reviewed_at)
                                                <span class="text-[10px] text-slate-400 font-bold block">{{ $req->reviewed_at->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Awaiting Sign-off
                                    </span>
                                @endif
                            </td>

                            {{-- 6. Submitted Date --}}
                            <td class="py-4 px-4 text-xs text-slate-800 font-extrabold whitespace-nowrap">
                                <div>{{ $req->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $req->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- 7. Status --}}
                            <td class="py-4 px-4">
                                @php
                                    $statusColors = [
                                        'pending'           => ['bg' => 'bg-amber-50',   'text' => 'text-amber-800',  'border' => 'border-amber-300',   'dot' => 'bg-amber-500 animate-pulse'],
                                        'approved'          => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800','border' => 'border-emerald-300', 'dot' => 'bg-emerald-500'],
                                        'rejected'          => ['bg' => 'bg-rose-50',    'text' => 'text-rose-800',   'border' => 'border-rose-300',    'dot' => 'bg-rose-500'],
                                        'revision_required' => ['bg' => 'bg-cyan-50',    'text' => 'text-cyan-800',   'border' => 'border-cyan-300',    'dot' => 'bg-cyan-500'],
                                        'cancelled'         => ['bg' => 'bg-slate-100',  'text' => 'text-slate-600',  'border' => 'border-slate-300',   'dot' => 'bg-slate-400'],
                                        'draft'             => ['bg' => 'bg-slate-50',   'text' => 'text-slate-500',  'border' => 'border-slate-200',   'dot' => 'bg-slate-300'],
                                    ];
                                    $sc = $statusColors[$req->status->value] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'border' => 'border-slate-200', 'dot' => 'bg-slate-400'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black {{ $sc['bg'] }} {{ $sc['text'] }} border {{ $sc['border'] }} whitespace-nowrap shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                    <span>{{ $req->status->label() }}</span>
                                </span>
                            </td>

                            {{-- 8. Actions --}}
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    @if($req->status->value === 'pending')
                                        @if($isNewProjectPlan && ($req->project?->project_manager_id === auth()->id() || auth()->user()->isSuperAdmin()))
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-white shadow-md shadow-rose-950/20 hover:scale-105 active:scale-95 transition-all cursor-pointer whitespace-nowrap"
                                                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <span>⭐ Review &amp; Accept</span>
                                            </button>
                                        @elseif(auth()->user()->isSuperAdmin() || $req->project?->project_manager_id === auth()->id())
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-black text-white shadow-md shadow-rose-950/20 hover:scale-105 active:scale-95 transition-all cursor-pointer whitespace-nowrap"
                                                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Review &amp; Sign</span>
                                            </button>
                                        @else
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-black text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs">
                                                <span>Details</span>
                                            </button>
                                        @endif
                                    @else
                                        <button wire:click="review({{ $req->id }})"
                                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-black text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Details</span>
                                        </button>
                                    @endif

                                    @if($req->status->value === 'pending' && $req->requested_by === auth()->id() && !$isNewProjectPlan)
                                        <button wire:click="cancelRequest({{ $req->id }})"
                                                wire:confirm="Are you sure you want to withdraw this request?"
                                                class="inline-flex items-center gap-1 px-2.5 py-2 rounded-xl text-xs font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-all cursor-pointer">
                                            <span>Withdraw</span>
                                        </button>
                                    @endif

                                    @if(auth()->user()->isSuperAdmin() || $req->requested_by === auth()->id())
                                        <button wire:click="openDeleteModal({{ $req->id }})"
                                                class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-all cursor-pointer"
                                                title="Delete Record">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-16 bg-white">
                                <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-[#c3122e] flex items-center justify-center mx-auto mb-3 shadow-xs">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-base font-black text-slate-900">No approval requests found</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1 max-w-sm mx-auto">There are no pending or logged governance sign-offs matching your current filter criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════
         MODAL 1: SUBMIT NEW APPROVAL REQUEST
     ══════════════════════════════════════════════════════ --}}
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center font-bold">
                            <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">Submit Approval Request</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Formal Project Governance Workflow</span>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <form wire:submit.prevent="createRequest" class="space-y-4">
                    {{-- Project Selection --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Project</label>
                        <select wire:model.live="projectId" class="form-select text-xs w-full mt-1 rounded-xl">
                            @foreach($userProjects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Request Type --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Request Type</label>
                        <select wire:model.live="requestType" class="form-select text-xs w-full mt-1 rounded-xl">
                            <option value="deadline_extension">📅 Deadline Extension</option>
                            <option value="wbs_baseline">📋 WBS Milestone Baseline Approval</option>
                            <option value="scope_change">📝 Scope Change</option>
                            <option value="budget_change">💰 Budget Change</option>
                            <option value="project_completion">✅ Project Completion Sign-off</option>
                        </select>
                    </div>

                    {{-- Optional WBS Task Selection --}}
                    @if($userWbsTasks->isNotEmpty())
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Related WBS Task (Optional)</label>
                            <select wire:model="wbsItemId" class="form-select text-xs w-full mt-1 rounded-xl">
                                <option value="">-- Select Specific Task --</option>
                                @foreach($userWbsTasks as $task)
                                    <option value="{{ $task->id }}">{{ $task->wbs_number }} {{ $task->title }} ({{ $task->progress }}%)</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Conditional Fields --}}
                    @if($requestType === 'deadline_extension')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Requested New Deadline</label>
                            <input type="date" wire:model="newDeadline" class="form-input text-xs w-full mt-1 rounded-xl">
                        </div>
                    @elseif($requestType === 'budget_change')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Requested New Budget (LKR)</label>
                            <input type="number" step="0.01" wire:model="newBudget" placeholder="e.g. 1500000" class="form-input text-xs w-full mt-1 rounded-xl">
                        </div>
                    @elseif($requestType === 'scope_change')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Scope Details</label>
                            <input type="text" wire:model="scopeDescription" placeholder="Describe the scope modification..." class="form-input text-xs w-full mt-1 rounded-xl">
                        </div>
                    @endif

                    {{-- Reason / Justification --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Reason &amp; Justification <span class="text-rose-500">*</span></label>
                        <textarea wire:model="reason" rows="3" placeholder="Provide clear reasoning for this approval request..." class="form-input text-xs w-full mt-1 rounded-xl"></textarea>
                        @error('reason') <p class="form-error text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="btn btn-secondary text-xs px-4 py-2 rounded-xl">Cancel</button>
                        <button type="submit" class="btn text-xs px-5 py-2 text-white font-bold rounded-xl shadow-md cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MODAL 2: REVIEW & APPROVE/REJECT MODAL
    ══════════════════════════════════════════════════════ --}}
    @if($showReviewModal && $selectedRequest)
        @php
            $proj = $selectedRequest->project;
            $wbsItems = $proj ? ($proj->wbsItems ?? collect()) : collect();
            $phases = $wbsItems->where('item_type.value', 'phase');
            if ($phases->isEmpty()) {
                $phases = $wbsItems->where('parent_id', null);
            }
            $tasksCount = $wbsItems->where('item_type.value', 'task')->count();
            $milestonesCount = $wbsItems->where('is_milestone', true)->count();

            $sponsors = $proj ? $proj->members->where('pivot.role', 'sponsor') : collect();
            $owners = $proj ? $proj->members->where('pivot.role', 'owner') : collect();
            $committee = $proj ? $proj->members->where('pivot.role', 'steering_committee') : collect();
            $members = $proj ? $proj->members->where('pivot.role', 'member') : collect();
            $totalTeam = $sponsors->count() + $owners->count() + $committee->count() + $members->count() + 1;

            $durationDays = ($proj && $proj->start_date && $proj->deadline) 
                ? (int) $proj->start_date->diffInDays($proj->deadline) + 1 
                : null;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150 max-h-[92vh] flex flex-col overflow-hidden my-auto">
                <div class="flex-shrink-0 flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center font-bold text-lg shadow-2xs flex-shrink-0">
                            @if($selectedRequest->request_type->value === 'new_project_plan')
                                ⭐
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-black text-slate-900 truncate">
                                @if($selectedRequest->request_type->value === 'new_project_plan')
                                    Project Manager Assignment Sign-Off
                                @else
                                    {{ $selectedRequest->status->value === 'pending' ? 'Review Approval Request' : 'Governance Sign-Off Audit Trail' }}
                                @endif
                            </h3>
                            <span class="text-[10px] text-slate-400 font-medium">Request Reference #{{ $selectedRequest->id }} · {{ $proj->code ?? 'GST' }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer flex-shrink-0">&times;</button>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                    {{-- 1. Request Overview Banner --}}
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Project:</span>
                            <span class="font-black text-slate-900">{{ $proj->name ?? '—' }} ({{ $proj->code ?? '—' }})</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Request Type:</span>
                            <span class="font-black text-[#c3122e]">{{ $selectedRequest->request_type->label() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Requested By:</span>
                            <span class="font-bold text-slate-800">{{ $selectedRequest->requester->name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Current Status:</span>
                            <span class="font-black {{ $selectedRequest->status->value === 'approved' ? 'text-emerald-700' : ($selectedRequest->status->value === 'rejected' ? 'text-rose-700' : 'text-amber-700') }}">
                                {{ $selectedRequest->status->label() }}
                            </span>
                        </div>
                    </div>

                    {{-- 2. Timeline & Project Parameters --}}
                    @if($proj)
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">📅 Start Date</span>
                                <span class="font-mono font-black text-slate-900 block mt-0.5">{{ $proj->start_date ? $proj->start_date->format('M d, Y') : 'Immediate' }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">🏁 Target Deadline</span>
                                <span class="font-mono font-black text-slate-900 block mt-0.5">{{ $proj->deadline ? $proj->deadline->format('M d, Y') : 'TBD' }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">⏳ Est. Duration</span>
                                <span class="font-bold text-slate-900 block mt-0.5">{{ $durationDays ? "{$durationDays} Days" : 'Flexible' }}</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">💰 Est. Budget</span>
                                <span class="font-mono font-bold text-slate-900 block mt-0.5">{{ $proj->estimated_budget > 0 ? 'Rs. ' . number_format($proj->estimated_budget, 0) : 'Not Set' }}</span>
                            </div>
                        </div>

                        {{-- 3. WBS Structure Breakdown --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">📋</span>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">WBS Execution Structure</h5>
                                </div>
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $wbsItems->count() }} Items</span>
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">{{ $phases->count() }} Phases</span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">🎯 {{ $milestonesCount }} Milestones</span>
                                    @endif
                                </div>
                            </div>
                            @if($wbsItems->count() > 0)
                                <div class="max-h-40 overflow-y-auto space-y-1 pr-1 scrollbar-thin rounded-xl bg-white p-2 border border-slate-200/70">
                                    @foreach($wbsItems as $item)
                                        @php $isPhase = ($item->item_type?->value === 'phase' || !$item->parent_id); @endphp
                                        <div class="flex items-center justify-between gap-2 p-1.5 rounded-lg text-xs {{ $isPhase ? 'bg-slate-50 font-black text-slate-900' : 'pl-5 text-slate-600 font-medium' }}">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span class="text-[10px] font-mono {{ $isPhase ? 'text-[#c3122e] font-black' : 'text-slate-400' }}">{{ $isPhase ? '📁' : '↳' }}</span>
                                                <span class="truncate">{{ $item->title }}</span>
                                            </div>
                                            <span class="text-slate-400 font-mono text-[10px]">{{ $item->duration ? $item->duration . 'd' : '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- 4. Governance Team Roster --}}
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm">👥</span>
                                    <h5 class="text-xs font-black text-slate-900 uppercase tracking-wide">Governance Team Roster</h5>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black font-mono bg-rose-50 text-[#c3122e] border border-rose-200">{{ $totalTeam }} Assigned</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-[#c3122e]/30 shadow-2xs">
                                    <div class="w-6 h-6 rounded-md flex items-center justify-center text-white font-black text-[10px] flex-shrink-0" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($proj->projectManager->name ?? 'PM', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-black text-slate-900 block truncate">{{ $proj->projectManager->name ?? 'Unassigned' }}</span>
                                        <span class="text-[9px] text-[#c3122e] font-bold">⭐ Project Manager</span>
                                    </div>
                                </div>
                                @foreach($sponsors as $sp)
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-amber-200 shadow-2xs">
                                        <div class="w-6 h-6 rounded-md bg-amber-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($sp->name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <span class="font-black text-slate-900 block truncate">{{ $sp->name }}</span>
                                            <span class="text-[9px] text-amber-800 font-bold">💼 Sponsor</span>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach($owners as $ow)
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-emerald-200 shadow-2xs">
                                        <div class="w-6 h-6 rounded-md bg-emerald-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($ow->name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <span class="font-black text-slate-900 block truncate">{{ $ow->name }}</span>
                                            <span class="text-[9px] text-emerald-800 font-bold">👑 Owner</span>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach($committee as $cm)
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-violet-200 shadow-2xs">
                                        <div class="w-6 h-6 rounded-md bg-violet-600 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($cm->name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <span class="font-black text-slate-900 block truncate">{{ $cm->name }}</span>
                                            <span class="text-[9px] text-violet-800 font-bold">🏛️ Committee</span>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach($members as $mb)
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-white border border-blue-200 shadow-2xs">
                                        <div class="w-6 h-6 rounded-md bg-blue-500 text-white font-black text-[10px] flex items-center justify-center flex-shrink-0">{{ strtoupper(substr($mb->name, 0, 1)) }}</div>
                                        <div class="min-w-0">
                                            <span class="font-black text-slate-900 block truncate">{{ $mb->name }}</span>
                                            <span class="text-[9px] text-blue-700 font-bold">🤝 Team Member</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($selectedRequest->requested_value) && $selectedRequest->request_type->value !== 'new_project_plan')
                        <div class="p-4 rounded-2xl bg-[#fdf4f4] border border-[#faeaea] space-y-2 text-xs">
                            <h4 class="font-black text-[#c3122e] uppercase text-[10px] tracking-wider">Requested Modifications</h4>
                            @foreach($selectedRequest->requested_value as $k => $v)
                                <div class="flex justify-between text-slate-700 font-medium">
                                    <span class="capitalize">{{ str_replace('_', ' ', $k) }}:</span>
                                    <strong class="text-slate-900">{{ is_array($v) ? json_encode($v) : $v }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs space-y-1 text-xs">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Submission Justification &amp; Purpose</span>
                        <p class="text-slate-700 font-medium leading-relaxed">{{ $selectedRequest->reason }}</p>
                    </div>

                    @if($selectedRequest->status->value !== 'pending')
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                            <h4 class="font-black text-slate-800 uppercase text-[10px] tracking-wider">Decision Audit Trail</h4>
                            <div class="flex justify-between">
                                <span class="text-slate-500 font-bold">Reviewed By:</span>
                                <span class="font-bold text-slate-900">{{ $selectedRequest->reviewer->name ?? 'System Authority' }}</span>
                            </div>
                            @if($selectedRequest->reviewed_at)
                                <div class="flex justify-between">
                                    <span class="text-slate-500 font-bold">Sign-off Timestamp:</span>
                                    <span class="font-mono text-slate-800">{{ $selectedRequest->reviewed_at->format('M d, Y H:i:s') }}</span>
                                </div>
                            @endif
                            @if($selectedRequest->review_comment)
                                <div>
                                    <span class="text-slate-500 font-bold block mb-1">Review Remarks:</span>
                                    <p class="text-slate-800 italic bg-white p-3 rounded-xl border border-slate-200/70">{{ $selectedRequest->review_comment }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($selectedRequest->status->value === 'pending')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Review Remarks / Remarks to PMO</label>
                            <textarea wire:model="reviewComment" rows="2" placeholder="Add optional sign-off remarks or required rejection reason..." class="form-input text-xs w-full mt-1.5 rounded-xl border-slate-200"></textarea>
                            @error('reviewComment') <p class="form-error text-[11px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

                <div class="flex-shrink-0 pt-2 border-t border-slate-100">
                    @if($selectedRequest->status->value === 'pending')
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <button type="button" wire:click="rejectRequest" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2.5 rounded-xl font-bold cursor-pointer shadow-sm">
                                ✕ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Decline Assignment' : 'Reject Request' }}
                            </button>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="$set('showReviewModal', false)" class="btn btn-secondary text-xs px-3.5 py-2.5 rounded-xl">Cancel</button>
                                <button type="button" wire:click="approveRequest" class="btn text-white text-xs px-5 py-2.5 rounded-xl font-black shadow-md cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    ✓ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Accept Assignment & Launch →' : 'Approve Request' }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-end">
                            <button type="button" wire:click="$set('showReviewModal', false)" class="btn btn-secondary text-xs px-5 py-2 rounded-xl font-bold">Close</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MODAL 3: DECLINE PROJECT ASSIGNMENT
    ══════════════════════════════════════════════════════ --}}
    @if($showDeclineModal)
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150 my-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-sm border border-rose-200">
                            ✕
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Decline Project Manager Assignment</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Notify PMO Administration of Issues</span>
                        </div>
                    </div>
                    <button wire:click="closeDeclineProjectModal" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/80 text-xs text-amber-900 font-medium space-y-1">
                    <p class="font-bold">⚠️ Are you sure you want to decline this project assignment?</p>
                    <p class="text-[11px] text-amber-800">Please provide a clear reason or report any scope, timeline, or resource blockers for the PMO Admin.</p>
                </div>

                <div>
                    <label class="form-label text-xs font-bold text-slate-800">Reason / Issue Description <span class="text-rose-500">*</span></label>
                    <textarea 
                        wire:model="declineReason" 
                        rows="3" 
                        placeholder="State why you cannot take management of this project (e.g. resource conflict, timeline constraint, scope mismatch)..." 
                        class="form-input text-xs w-full mt-1.5 rounded-xl border-slate-200"
                    ></textarea>
                    @error('declineReason') <p class="form-error text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closeDeclineProjectModal" class="btn btn-secondary text-xs px-3.5 py-2">Cancel</button>
                    <button type="button" wire:click="submitDeclineProject" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2 font-bold shadow-sm cursor-pointer">
                        Confirm Decline &amp; Report
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MODAL 4: DELETE APPROVAL REQUEST CONFIRMATION
    ══════════════════════════════════════════════════════ --}}
    @if($showDeleteConfirmModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-sm border border-rose-200">
                            🗑️
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Delete Approval Record</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Permanent Removal</span>
                        </div>
                    </div>
                    <button wire:click="closeDeleteModal" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200/80 text-xs text-rose-900 font-medium space-y-1">
                    <p class="font-bold">⚠️ Are you sure you want to permanently delete this approval request?</p>
                    <p class="text-[11px] text-rose-800">This action will remove the record from the governance log and cannot be undone.</p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closeDeleteModal" class="btn btn-secondary text-xs px-3.5 py-2 cursor-pointer">Cancel</button>
                    <button type="button" wire:click="confirmDelete" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2 font-bold shadow-sm cursor-pointer">
                        Confirm Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
