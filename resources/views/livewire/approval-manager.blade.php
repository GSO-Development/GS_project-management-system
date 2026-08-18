<div>
    {{-- ══════════════════════════════════════════════════════
         APPROVAL WORKFLOWS — Governance & Team Request Desk
    ══════════════════════════════════════════════════════ --}}

    <!-- ═══════════════════════════════════════════════════════════════
         1. TOP EXECUTIVE HERO BANNER (APPROVAL WORKFLOWS)
         ═══════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl border border-rose-900/60 shadow-2xl p-6 sm:p-8 lg:p-9 text-white mb-6" style="background: linear-gradient(135deg, #18060c 0%, #300a16 45%, #1b0710 100%);">
        <!-- Top Ambient Glowing Gold/Crimson Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#c3122e] via-amber-400 to-[#c3122e] shadow-sm shadow-rose-500/50"></div>

        <!-- Right Background Cityscape Dark Illustration with Smooth Fade -->
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

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Left Side: 3D Check/Shield Icon + Title + Meta -->
            <div class="flex items-center gap-5 min-w-0">
                <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden shadow-2xl flex-shrink-0 border-2 border-white/25 ring-4 ring-rose-500/25 flex items-center justify-center p-3" style="background: linear-gradient(135deg, #e02d4b 0%, #c3122e 60%, #7f0b1a 100%);">
                    <svg class="w-9 h-9 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight drop-shadow-md">
                            Approval Workflows
                        </h1>
                        <span class="px-3.5 py-1 rounded-full text-xs font-black text-rose-200 border border-rose-400/40 shadow-inner flex items-center gap-2 backdrop-blur-md" style="background: rgba(195, 18, 46, 0.35);">
                            <span>✓</span>
                            <span>Governance Sign-Off Desk</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300 mt-2 flex-wrap">
                        <span class="text-rose-200 font-extrabold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ now()->format('l, M d, Y') }}</span>
                        </span>
                        <span class="text-slate-500 font-normal">|</span>
                        <span class="text-slate-300 font-medium">Review, submit, and track formal project sign-offs and change requests</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Primary Action -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start lg:self-center">
                <button
                    wire:click="openCreateModal"
                    type="button"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-black text-white shadow-xl hover:scale-105 transition-all duration-200 cursor-pointer"
                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%); border: 1px solid rgba(255,255,255,0.2);"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Submit Approval Request</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Filters Bar ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs mb-6 p-4 flex flex-col lg:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Scope</span>
                <select wire:model.live="scopeFilter" class="text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 px-3.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none min-w-44 text-slate-800 transition-all">
                    <option value="all">All Accessible Requests</option>
                    <option value="my_requests">My Submitted Requests</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Status</span>
                <select wire:model.live="statusFilter" class="text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 px-3.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none min-w-36 text-slate-800 transition-all">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="revision_required">Revision Required</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-black text-slate-400 ml-1 mb-1 tracking-wider">Request Type</span>
                <select wire:model.live="typeFilter" class="text-xs font-bold rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 px-3.5 focus:border-[#c3122e] focus:ring-2 focus:ring-[#c3122e]/10 outline-none min-w-48 text-slate-800 transition-all">
                    <option value="all">All Request Types</option>
                    @foreach(\App\Enums\ApprovalType::cases() as $t)
                        <option value="{{ $t->value }}">{{ $t->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Summary KPI Chips --}}
        <div class="flex items-center gap-2.5 text-xs flex-wrap self-start lg:self-center">
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
            <div class="px-3.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-amber-500 {{ $pendingCount > 0 ? 'animate-pulse' : '' }}"></span>
                <span>{{ $pendingCount }} Pending</span>
            </div>
            <div class="px-3.5 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $approvedCount }} Approved</span>
            </div>
            <div class="px-3.5 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 font-black flex items-center gap-2 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>{{ $rejectedCount }} Rejected</span>
            </div>
        </div>
    </div>

    {{-- ── Approval Requests Table ── --}}
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">PROJECT &amp; SUBSIDIARY</th>
                        <th class="py-3.5 px-4 text-left">REQUEST TYPE &amp; DETAILS</th>
                        <th class="py-3.5 px-4 text-left">REQUESTED BY</th>
                        <th class="py-3.5 px-4 text-left">REASON / JUSTIFICATION</th>
                        <th class="py-3.5 px-4 text-left">REVIEWED / SIGNED BY</th>
                        <th class="py-3.5 px-4 text-left">SUBMITTED</th>
                        <th class="py-3.5 px-4 text-left">STATUS</th>
                        <th class="py-3.5 px-4 text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($requests as $req)
                        @php
                            $rv = $req->requested_value ?? [];
                            $isNewProjectPlan = ($req->request_type->value === 'new_project_plan');
                            $projectName = $req->project->name ?? ($rv['project_name'] ?? 'Project');
                            $projectCode = $req->project->code ?? ($rv['project_code'] ?? null);
                            $subsidiaryName = $req->project?->subsidiary?->name ?? null;

                            $typeSubLabel = match($req->request_type->value) {
                                'deadline_extension' => isset($rv['new_deadline'])
                                    ? ['📅 New deadline: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y'), 'text-indigo-700 bg-indigo-50 border-indigo-200']
                                    : null,
                                'budget_change' => isset($rv['new_budget'])
                                    ? ['💰 New budget: Rs. ' . number_format($rv['new_budget'], 0), 'text-emerald-700 bg-emerald-50 border-emerald-200']
                                    : null,
                                'scope_change' => isset($rv['scope_description'])
                                    ? ['📝 ' . \Illuminate\Support\Str::limit($rv['scope_description'], 40), 'text-amber-800 bg-amber-50 border-amber-200']
                                    : null,
                                'wbs_baseline'       => ['📋 WBS Milestone Baseline Sign-off', 'text-slate-700 bg-slate-100 border-slate-200'],
                                'project_completion' => ['✅ Mark project as Completed (100%)', 'text-emerald-700 bg-emerald-50 border-emerald-200'],
                                'project_cancellation' => ['🚫 Cancel project permanently', 'text-rose-700 bg-rose-50 border-rose-200'],
                                'new_project_plan'   => ['👑 Project Leadership Assignment', 'text-[#c3122e] bg-[#fdf4f4] border-[#faeaea]'],
                                'updated_wbs_plan'   => ['🔄 Updated WBS structure for review', 'text-violet-700 bg-violet-50 border-violet-200'],
                                default              => null,
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-black text-slate-900 text-xs">{{ $projectName }}</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($projectCode)
                                        <span class="text-[10px] text-[#c3122e] font-mono font-black bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200">{{ $projectCode }}</span>
                                    @else
                                        <span class="text-[10px] text-slate-400 font-mono">-</span>
                                    @endif
                                    @if($subsidiaryName)
                                        <span class="text-[10px] text-slate-500 font-medium">• {{ $subsidiaryName }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#fdf4f4] text-[#c3122e] border border-[#f0dada]/80 whitespace-nowrap inline-block shadow-2xs">
                                        {{ $req->request_type->label() }}
                                    </span>
                                    @if($typeSubLabel)
                                        <div class="text-[10px] font-bold px-2 py-0.5 rounded-md border block w-fit shadow-2xs {{ $typeSubLabel[1] }}">
                                            {{ $typeSubLabel[0] }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-xl flex items-center justify-center text-white text-[10px] font-black flex-shrink-0 shadow-2xs" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                        {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-800 font-bold block leading-tight">{{ $req->requester->name ?? '—' }}</span>
                                        @if($req->requested_by === auth()->id())
                                            <span class="text-[9px] text-[#c3122e] font-black uppercase">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600 max-w-52">
                                <p class="line-clamp-2 leading-relaxed" title="{{ $req->reason }}">{{ $req->reason }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($req->reviewer)
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-bold flex-shrink-0 border border-emerald-200 shadow-2xs">
                                            {{ strtoupper(substr($req->reviewer->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs text-slate-800 font-bold block truncate">{{ $req->reviewer->name }}</span>
                                            @if($req->reviewed_at)
                                                <span class="text-[10px] text-slate-500 font-bold block">{{ $req->reviewed_at->format('M d, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-slate-400 italic">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Awaiting Sign-off
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700 font-extrabold whitespace-nowrap">
                                <div>{{ $req->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $statusColors = [
                                        'pending'           => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'approved'          => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'rejected'          => 'bg-rose-50 text-rose-800 border-rose-200',
                                        'revision_required' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                                        'cancelled'         => 'bg-slate-50 text-slate-500 border-slate-200',
                                        'draft'             => 'bg-slate-50 text-slate-500 border-slate-200',
                                    ];
                                    $sc = $statusColors[$req->status->value] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-black border {{ $sc }} whitespace-nowrap shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $req->status->value === 'approved' ? 'bg-emerald-500' : ($req->status->value === 'pending' ? 'bg-amber-500 animate-pulse' : 'bg-rose-500') }}"></span>
                                    <span>{{ $req->status->label() }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    @if($req->status->value === 'pending')
                                        @if($isNewProjectPlan && $req->project?->project_manager_id === auth()->id())
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                                                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <span>👑 Review &amp; Accept</span>
                                            </button>
                                        @elseif(auth()->user()->isSuperAdmin() || $req->project?->project_manager_id === auth()->id())
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-black text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                                                    style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Review</span>
                                            </button>
                                        @else
                                            <button wire:click="review({{ $req->id }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs">
                                                <span>Details</span>
                                            </button>
                                        @endif
                                    @else
                                        <button wire:click="review({{ $req->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-extrabold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-all cursor-pointer shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Details</span>
                                        </button>
                                    @endif

                                    @if($req->status->value === 'pending' && $req->requested_by === auth()->id() && !$isNewProjectPlan)
                                        <button wire:click="cancelRequest({{ $req->id }})"
                                                wire:confirm="Are you sure you want to withdraw this request?"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-all cursor-pointer">
                                            <span>Withdraw</span>
                                        </button>
                                    @endif

                                    @if(auth()->user()->isSuperAdmin() || $req->requested_by === auth()->id())
                                        <button wire:click="openDeleteModal({{ $req->id }})"
                                                class="p-1.5 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition-all cursor-pointer shadow-2xs"
                                                title="Delete Approval Record">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 bg-white">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-800">No approval requests found</h3>
                                <p class="text-xs text-slate-400 mt-1">Submit a new request or adjust filters to view request items.</p>
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
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 space-y-4 animate-in fade-in zoom-in-95 duration-150 max-h-[92vh] flex flex-col overflow-hidden my-auto">
                <div class="flex-shrink-0 flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center font-bold text-lg shadow-2xs flex-shrink-0">
                            @if($selectedRequest->request_type->value === 'new_project_plan')
                                👑
                            @else
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-900">
                                @if($selectedRequest->request_type->value === 'new_project_plan')
                                    Project Leadership Assignment Sign-Off
                                @else
                                    {{ $selectedRequest->status->value === 'pending' ? 'Review Approval Request' : 'Governance Sign-Off Audit Trail' }}
                                @endif
                            </h3>
                            <span class="text-[10px] text-slate-400 font-medium">Request Reference #{{ $selectedRequest->id }}</span>
                        </div>
                    </div>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer flex-shrink-0">&times;</button>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold">Project:</span>
                            <span class="font-black text-slate-900">{{ $selectedRequest->project->name ?? '—' }} ({{ $selectedRequest->project->code ?? '—' }})</span>
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

                    @if(!empty($selectedRequest->requested_value))
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
                                ✕ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Decline Leadership' : 'Reject Request' }}
                            </button>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="$set('showReviewModal', false)" class="btn btn-secondary text-xs px-3.5 py-2.5 rounded-xl">Cancel</button>
                                <button type="button" wire:click="approveRequest" class="btn text-white text-xs px-5 py-2.5 rounded-xl font-black shadow-md cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    ✓ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Accept Leadership Sign-off' : 'Approve Request' }}
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
         MODAL 3: DECLINE PROJECT LEADERSHIP ASSIGNMENT
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
                            <h3 class="text-sm font-black text-slate-900">Decline Project Leadership</h3>
                            <span class="text-[10px] text-slate-400 font-medium">Notify PMO Administration of Issues</span>
                        </div>
                    </div>
                    <button wire:click="closeDeclineProjectModal" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200/80 text-xs text-amber-900 font-medium space-y-1">
                    <p class="font-bold">⚠️ Are you sure you want to decline this project leadership?</p>
                    <p class="text-[11px] text-amber-800">Please provide a clear reason or report any scope, timeline, or resource blockers for the PMO Admin.</p>
                </div>

                <div>
                    <label class="form-label text-xs font-bold text-slate-800">Reason / Issue Description <span class="text-rose-500">*</span></label>
                    <textarea 
                        wire:model="declineReason" 
                        rows="3" 
                        placeholder="State why you cannot take leadership of this project (e.g. resource conflict, timeline constraint, scope mismatch)..." 
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
