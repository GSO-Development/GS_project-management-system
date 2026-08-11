<div>
    {{-- ══════════════════════════════════════════════════════
         APPROVAL WORKFLOWS — Governance & Team Request Desk
    ══════════════════════════════════════════════════════ --}}

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
        <div>
            <div class="flex items-center gap-2.5 mb-1">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#c3122e,#8b0d1f)">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Approval Workflows</h1>
                @php $pendingTotal = $requests->total(); @endphp
                @if($pendingTotal > 0 && $statusFilter === 'pending')
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold text-white" style="background:#c3122e">{{ $pendingTotal }} pending</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 font-medium">Review, submit, and track formal project sign-offs and change requests</p>
        </div>

        {{-- Submit New Request Button for Team Members & Managers --}}
        <div>
            <button wire:click="openCreateModal"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white shadow-md transition-all active:scale-95 cursor-pointer"
                    style="background:linear-gradient(135deg,#c3122e,#9e0b22)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Submit Approval Request</span>
            </button>
        </div>
    </div>

    {{-- ── Filters Bar ── --}}
    <div class="card mb-6 p-3.5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Scope</span>
                <select wire:model.live="scopeFilter" class="form-select text-xs min-w-36 py-1.5 font-bold">
                    <option value="all">All Accessible Requests</option>
                    <option value="my_requests">My Submitted Requests</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Status</span>
                <select wire:model.live="statusFilter" class="form-select text-xs min-w-36 py-1.5 font-medium">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="revision_required">Revision Required</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="flex flex-col">
                <span class="text-[9px] uppercase font-bold text-slate-400 ml-1 mb-0.5">Request Type</span>
                <select wire:model.live="typeFilter" class="form-select text-xs min-w-44 py-1.5 font-medium">
                    <option value="all">All Request Types</option>
                    @foreach(\App\Enums\ApprovalType::cases() as $t)
                        <option value="{{ $t->value }}">{{ $t->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Summary chips --}}
        <div class="flex items-center gap-2 text-xs">
            @php
                $user = auth()->user();
                $baseQuery = \App\Models\ApprovalRequest::query();
                if ($scopeFilter === 'my_requests') {
                    $baseQuery->where('requested_by', $user->id);
                } elseif (!$user->hasRole('super_admin')) {
                    $baseQuery->where(function($q) use ($user) {
                        $q->where('requested_by', $user->id)
                          ->orWhereHas('project', fn($pq) => $pq->whereHas('members', fn($mq) => $mq->where('user_id', $user->id)));
                    });
                }
                $pendingCount  = (clone $baseQuery)->where('status','pending')->count();
                $approvedCount = (clone $baseQuery)->where('status','approved')->count();
                $rejectedCount = (clone $baseQuery)->where('status','rejected')->count();
            @endphp
            <div class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 font-bold">{{ $pendingCount }} Pending</div>
            <div class="px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold">{{ $approvedCount }} Approved</div>
            <div class="px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 font-bold">{{ $rejectedCount }} Rejected</div>
        </div>
    </div>

    {{-- ── Approval Requests Table ── --}}
    <div class="card p-0 overflow-hidden shadow-xs mb-6">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PROJECT</th>
                        <th>REQUEST TYPE &amp; DETAILS</th>
                        <th>REQUESTED BY</th>
                        <th>REASON / JUSTIFICATION</th>
                        <th>SUBMITTED</th>
                        <th>STATUS</th>
                        <th class="text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($requests as $req)
                        @php
                            $rv = $req->requested_value ?? [];
                            $typeSubLabel = match($req->request_type->value) {
                                'deadline_extension' => isset($rv['new_deadline'])
                                    ? ['📅 New deadline: ' . \Carbon\Carbon::parse($rv['new_deadline'])->format('M d, Y'), 'text-indigo-600 bg-indigo-50 border-indigo-200']
                                    : null,
                                'budget_change' => isset($rv['new_budget'])
                                    ? ['💰 New budget: Rs. ' . number_format($rv['new_budget'], 0), 'text-emerald-700 bg-emerald-50 border-emerald-200']
                                    : null,
                                'scope_change' => isset($rv['scope_description'])
                                    ? ['📝 ' . \Illuminate\Support\Str::limit($rv['scope_description'], 40), 'text-amber-700 bg-amber-50 border-amber-200']
                                    : null,
                                'wbs_baseline'       => ['📋 WBS Milestone Baseline Sign-off', 'text-slate-600 bg-slate-50 border-slate-200'],
                                'project_completion' => ['✅ Mark project as Completed (100%)', 'text-emerald-700 bg-emerald-50 border-emerald-200'],
                                'project_cancellation' => ['🚫 Cancel project permanently', 'text-rose-700 bg-rose-50 border-rose-200'],
                                'new_project_plan'   => ['🗂 Submit initial project plan', 'text-blue-700 bg-blue-50 border-blue-200'],
                                'updated_wbs_plan'   => ['🔄 Updated WBS structure for review', 'text-violet-700 bg-violet-50 border-violet-200'],
                                default              => null,
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td>
                                <div class="font-bold text-slate-900 text-xs">{{ $req->project->name ?? 'Project' }}</div>
                                <div class="text-[10px] text-[#c3122e] font-mono font-semibold">{{ $req->project->code ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="space-y-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#fdf4f4] text-[#c3122e] border border-[#f0dada]/60 whitespace-nowrap inline-block">
                                        {{ $req->request_type->label() }}
                                    </span>
                                    @if($typeSubLabel)
                                        <div class="text-[10px] font-semibold px-2 py-0.5 rounded-md border block w-fit {{ $typeSubLabel[1] }}">
                                            {{ $typeSubLabel[0] }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">
                                        {{ strtoupper(substr($req->requester->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="text-xs text-slate-800 font-bold block">{{ $req->requester->name ?? '—' }}</span>
                                        @if($req->requested_by === auth()->id())
                                            <span class="text-[9px] text-[#c3122e] font-bold">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs text-slate-600 max-w-48">
                                <p class="line-clamp-2" title="{{ $req->reason }}">{{ $req->reason }}</p>
                            </td>
                            <td class="text-xs text-slate-500 font-mono whitespace-nowrap">
                                {{ $req->created_at->format('M d, Y') }}
                                <div class="text-[10px] text-slate-400">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending'           => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'approved'          => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'rejected'          => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'revision_required' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'cancelled'         => 'bg-slate-50 text-slate-500 border-slate-200',
                                        'draft'             => 'bg-slate-50 text-slate-500 border-slate-200',
                                    ];
                                    $sc = $statusColors[$req->status->value] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $sc }} whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $req->status->value === 'approved' ? 'bg-emerald-500' : ($req->status->value === 'pending' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                    {{ $req->status->label() }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($req->status->value === 'pending' && auth()->user()->hasAnyRole(['super_admin', 'project_manager']))
                                        <button wire:click="review({{ $req->id }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-[#c3122e] hover:bg-[#a00e24] transition-all cursor-pointer shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Review</span>
                                        </button>
                                    @endif

                                    @if($req->status->value === 'pending' && ($req->requested_by === auth()->id() || auth()->user()->hasRole('super_admin')))
                                        <button wire:click="cancelRequest({{ $req->id }})"
                                                wire:confirm="Are you sure you want to withdraw this request?"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-all cursor-pointer">
                                            <span>Withdraw</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
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
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Submit Approval Request</h3>
                    </div>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                </div>

                <form wire:submit.prevent="createRequest" class="space-y-4">
                    {{-- Project Selection --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Project</label>
                        <select wire:model.live="projectId" class="form-select text-xs w-full mt-1">
                            @foreach($userProjects as $proj)
                                <option value="{{ $proj->id }}">{{ $proj->name }} ({{ $proj->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Request Type --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Request Type</label>
                        <select wire:model.live="requestType" class="form-select text-xs w-full mt-1">
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
                            <select wire:model="wbsItemId" class="form-select text-xs w-full mt-1">
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
                            <input type="date" wire:model="newDeadline" class="form-input text-xs w-full mt-1">
                        </div>
                    @elseif($requestType === 'budget_change')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Requested New Budget (LKR)</label>
                            <input type="number" step="0.01" wire:model="newBudget" placeholder="e.g. 1500000" class="form-input text-xs w-full mt-1">
                        </div>
                    @elseif($requestType === 'scope_change')
                        <div>
                            <label class="form-label text-xs font-bold text-slate-800">Scope Details</label>
                            <input type="text" wire:model="scopeDescription" placeholder="Describe the scope modification..." class="form-input text-xs w-full mt-1">
                        </div>
                    @endif

                    {{-- Reason / Justification --}}
                    <div>
                        <label class="form-label text-xs font-bold text-slate-800">Reason &amp; Justification <span class="text-rose-500">*</span></label>
                        <textarea wire:model="reason" rows="3" placeholder="Provide clear reasoning for this approval request..." class="form-input text-xs w-full mt-1"></textarea>
                        @error('reason') <p class="form-error text-[11px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="btn btn-secondary text-xs px-4 py-2">Cancel</button>
                        <button type="submit" class="btn text-xs px-4 py-2 text-white font-bold" style="background:#c3122e">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════
         MODAL 2: REVIEW / APPROVE / REJECT (ADMIN & PM)
    ══════════════════════════════════════════════════════ --}}
    @if($showReviewModal && $selectedRequest)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#fdf4f4] text-[#c3122e] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Review Approval Request</h3>
                    </div>
                    <button wire:click="$set('showReviewModal', false)" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-bold">Project:</span>
                        <span class="font-bold text-slate-900">{{ $selectedRequest->project->name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-bold">Type:</span>
                        <span class="font-bold text-[#c3122e]">{{ $selectedRequest->request_type->label() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-bold">Requested By:</span>
                        <span class="font-bold text-slate-800">{{ $selectedRequest->requester->name ?? '—' }}</span>
                    </div>
                    <div class="pt-1 border-t border-slate-200">
                        <span class="text-slate-500 font-bold block mb-1">Reason:</span>
                        <p class="text-slate-700 italic bg-white p-2 rounded-lg border border-slate-200/60">{{ $selectedRequest->reason }}</p>
                    </div>
                </div>

                <div>
                    <label class="form-label text-xs font-bold text-slate-800">Review Comment / Remarks</label>
                    <textarea wire:model="reviewComment" rows="2" placeholder="Add optional approval comment or required rejection reason..." class="form-input text-xs w-full mt-1"></textarea>
                    @error('reviewComment') <p class="form-error text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <button type="button" wire:click="rejectRequest" class="btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-4 py-2">Reject Request</button>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="$set('showReviewModal', false)" class="btn btn-secondary text-xs px-3 py-2">Cancel</button>
                        <button type="button" wire:click="approveRequest" class="btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-4 py-2 font-bold">Approve Request</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
