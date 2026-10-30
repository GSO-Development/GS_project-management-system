<div>
    @if($showApprovalList && $approvalProject)
        @teleport('body')
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/55 p-3 backdrop-blur-sm sm:p-6" role="dialog" aria-modal="true" aria-labelledby="project-approvals-title" x-data x-trap.inert.noscroll="true" @keydown.escape.window="$wire.closeApprovals()">
            <div class="w-full max-w-3xl max-h-[88dvh] flex flex-col overflow-hidden rounded-3xl bg-white border border-slate-200 shadow-[0_30px_80px_rgba(15,23,42,.25)]">
                <div class="flex items-start justify-between gap-4 px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="min-w-0">
                        <h2 id="project-approvals-title" class="text-base font-bold text-slate-900">Project approvals</h2>
                        <p class="mt-1 text-xs text-slate-500">{{ $approvalProject->name }} · {{ $approvalProject->code }}</p>
                    </div>
                    <button type="button" wire:click="closeApprovals" class="w-9 h-9 shrink-0 inline-flex items-center justify-center rounded-xl border border-slate-200 text-slate-400 hover:bg-slate-50 hover:text-slate-700" aria-label="Close project approvals">&times;</button>
                </div>
                <div class="p-5 sm:p-6 space-y-5 overflow-y-auto bg-slate-50/50">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <p class="text-xs text-slate-500">Assignment decisions and project requests, with their review history.</p>
                        @if($approvalProject->pm_accepted)
                            <button type="button" wire:click="openCreateModal" class="px-3 py-2 rounded-lg bg-[#c3122e] text-white text-xs font-semibold">New request</button>
                        @endif
                    </div>
                    @php
                        $assignmentState = $approvalProject->pm_accepted ? 'Approved' : ($approvalProject->isPmRejected() ? 'Rejected' : 'Pending');
                        $assignmentBadge = match($assignmentState) {
                            'Approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            default => 'bg-amber-50 text-amber-800 border-amber-200',
                        };
                    @endphp
                    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-xs">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black uppercase tracking-[.14em] text-slate-400">Assigned Project Manager</p>
                                <p class="mt-1 text-sm font-black text-slate-900 truncate">{{ $approvalProject->projectManager?->name ?? 'Not assigned' }}</p>
                                <p class="mt-0.5 text-[11px] text-slate-500 truncate">{{ $approvalProject->projectManager?->email ?? 'No manager email' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[10px] font-black {{ $assignmentBadge }}"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $assignmentState }}</span>
                        </div>
                    </section>
                    @if($approvalProject->pm_rejection_reason)
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs text-rose-800">
                            <strong>Assignment rejected:</strong> {{ $approvalProject->pm_rejection_reason }}
                            @if(auth()->user()->isPmoAdmin())
                                <button type="button" wire:click="openReassignModal({{ $approvalProject->id }})" class="ml-2 underline font-bold">Reassign PM</button>
                            @endif
                        </div>
                    @endif
                    @if($approvalProject->isPendingPmAcceptance() && $approvalProject->canApproveAssignment(auth()->user()))
                        <div class="flex items-center gap-2 flex-wrap rounded-xl border border-amber-200 bg-amber-50 p-3">
                            <span class="text-xs text-amber-900 mr-auto">Project manager assignment is pending approval.</span>
                            <button type="button" wire:click="openDeclineProjectModal({{ $approvalProject->id }})" class="px-3 py-2 rounded-lg border border-rose-200 text-rose-700 bg-white text-xs font-semibold">Reject</button>
                            <button type="button" wire:click="acceptProject({{ $approvalProject->id }})" wire:loading.attr="disabled" class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold">Approve assignment</button>
                        </div>
                    @elseif($approvalProject->isPendingPmAcceptance())
                        <div class="flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <div class="w-8 h-8 rounded-xl bg-white border border-amber-200 text-amber-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div><p class="text-xs font-bold text-amber-900">Awaiting Project Manager decision</p><p class="mt-0.5 text-[11px] text-amber-800">Only the assigned Project Manager can accept or decline this assignment.</p></div>
                        </div>
                    @endif
                    <div>
                        <div class="mb-3">
                            <h3 class="text-sm font-black text-slate-900">Request history</h3>
                            <p class="mt-0.5 text-[11px] text-slate-500">Formal project decisions and their audit trail.</p>
                        </div>
                        <div class="space-y-3">
                        @forelse($requests as $approval)
                            <div class="p-4 space-y-2 rounded-2xl border border-slate-200 bg-white" wire:key="project-request-{{ $approval->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">{{ $approval->request_type->label() }}</p>
                                        <p class="text-[11px] text-slate-500 mt-1">#{{ $approval->id }} · {{ $approval->requester->name ?? 'Unknown user' }} · {{ $approval->submitted_at?->format('M d, Y H:i') }}</p>
                                    </div>
                                    @php $requestBadge = match($approval->status->value) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                                    }; @endphp
                                    <span class="px-2 py-1 text-[11px] font-semibold border rounded-full whitespace-nowrap {{ $requestBadge }}">{{ $approval->status->label() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 whitespace-pre-line break-words">{{ $approval->reason }}</p>
                                @if($approval->review_comment)
                                    <p class="rounded-lg bg-slate-50 p-3 text-xs text-slate-700 whitespace-pre-line break-words">
                                        <strong>{{ $approval->status === \App\Enums\ApprovalStatus::REJECTED ? 'Rejection reason:' : 'Review remarks:' }}</strong> {{ $approval->review_comment }}
                                    </p>
                                @endif
                                @if($approval->reviewed_at)
                                    <p class="text-[11px] text-slate-500">Reviewed by {{ $approval->reviewer->name ?? 'System' }} · {{ $approval->reviewed_at->format('M d, Y H:i') }}</p>
                                @endif
                                <div class="flex items-center gap-3">
                                    <button type="button" wire:click="review({{ $approval->id }})" class="text-xs font-semibold text-[#c3122e] hover:underline">{{ $approval->status === \App\Enums\ApprovalStatus::PENDING ? 'Review request' : 'View details' }}</button>
                                    @if($approval->status === \App\Enums\ApprovalStatus::PENDING && $approval->requested_by === auth()->id() && $approval->request_type !== \App\Enums\ApprovalType::NEW_PROJECT_PLAN)
                                        <button type="button" wire:click="cancelRequest({{ $approval->id }})" wire:confirm="Withdraw this pending request?" class="text-xs font-semibold text-slate-500 hover:underline">Withdraw</button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-center text-xs text-slate-500 rounded-2xl border border-dashed border-slate-200 bg-white">No formal requests have been recorded for this project.</p>
                        @endforelse
                        </div>
                    </div>
                </div>
                <div class="flex justify-end px-5 py-3 sm:px-6 border-t border-slate-200 bg-white">
                    <button type="button" wire:click="closeApprovals" class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50">Close</button>
                </div>
            </div>
        </div>
        @endteleport
    @endif
    @teleport('body')
    <div x-data @keydown.escape.window="$wire.closeApprovals()">
    @if($showCreateModal)
        <div role="dialog" aria-modal="true" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
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
                        <select disabled wire:model.live="projectId" class="form-select text-xs w-full mt-1 rounded-xl">
                            @foreach($userProjects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    @if($errors->any())<div class="rounded-lg bg-rose-50 border border-rose-200 p-3 text-xs text-rose-700" role="alert">@foreach($errors->all() as $message)<p>{{ $message }}</p>@endforeach</div>@endif
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
        <div role="dialog" aria-modal="true" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
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
                                @if($selectedRequest->status->value === 'pending')
                                    {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Project Manager Assignment Sign-Off' : 'Review Approval Request' }}
                                @else
                                    Approval Details &amp; Sign-Off Audit Trail
                                @endif
                            </h3>
                            <span class="text-[10px] text-slate-400 font-medium">Request Reference #{{ $selectedRequest->id }} · {{ $proj->code ?? 'GST' }}</span>
                        </div>
                    </div>
                    <button wire:click="closeReviewModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors text-lg font-bold cursor-pointer flex-shrink-0" title="Close Modal">&times;</button>
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
                                    <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-sky-50 text-sky-700 border border-sky-200">{{ $phases->count() }} Phases</span>
                                    @if($milestonesCount > 0)
                                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-black bg-purple-50 text-purple-700 border border-purple-200">🎯 {{ $milestonesCount }} Milestones</span>
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
                            @if($canReject)
                            <button type="button" wire:click="rejectRequest" wire:loading.attr="disabled" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2.5 rounded-xl font-bold cursor-pointer shadow-sm">
                                ✕ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Decline Assignment' : 'Reject Request' }}
                            </button>
                            @endif
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="closeReviewModal" class="btn btn-secondary text-xs px-3.5 py-2.5 rounded-xl cursor-pointer">Cancel</button>
                                @if($canApprove)
                                <button type="button" wire:click="approveRequest" wire:loading.attr="disabled" class="btn text-white text-xs px-5 py-2.5 rounded-xl font-black shadow-md cursor-pointer" style="background: linear-gradient(135deg, #c3122e 0%, #8b0d1f 100%);">
                                    ✓ {{ $selectedRequest->request_type->value === 'new_project_plan' ? 'Accept Assignment & Launch →' : 'Approve Request' }}
                                </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-end">
                            <button type="button" wire:click="closeReviewModal" class="btn btn-secondary text-xs px-5 py-2 rounded-xl font-bold cursor-pointer">Close</button>
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
        <div role="dialog" aria-modal="true" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-5 bg-slate-900/75 backdrop-blur-md">
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
                    <button type="button" wire:click="submitDeclineProject" wire:loading.attr="disabled" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2 font-bold shadow-sm cursor-pointer">
                        Confirm Decline &amp; Report
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MODAL 4: REASSIGN PROJECT
    ══════════════════════════════════════════════════════ --}}
    @if($showReassignModal && $reassignProject)
        @include('livewire.partials.project-reassign-modal')
    @endif

    </div>
    @endteleport
</div>
