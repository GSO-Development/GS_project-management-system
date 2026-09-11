<?php

namespace App\Livewire;

use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ApprovalService;
use Livewire\Component;

class ProjectManagerDashboard extends Component
{
    public string $dashboardTab = 'overview'; // 'overview', 'approvals', 'risks'

    // Blocker resolution state
    public bool $showResolveBlockerModal = false;
    public ?int $selectedBlockerId = null;
    public string $blockerResolutionInput = '';
    public bool $showAllProjects = false;

    public function toggleShowAllProjects(): void
    {
        $this->showAllProjects = !$this->showAllProjects;
    }

    // Add Risk State
    public bool $showAddRiskModal = false;
    public ?int $riskProjectId = null;
    public string $riskTitle = '';
    public string $riskCategory = 'Technical';
    public string $riskProbability = 'medium';
    public string $riskImpact = 'medium';
    public string $riskMitigation = '';

    public bool $showReviewModal = false;
    public ?int $reviewProjectId = null;
    public bool $showRejectModal = false;
    public string $rejectionReasonInput = '';

    public function setDashboardTab(string $tab): void
    {
        $this->dashboardTab = $tab;
    }

    public function openReviewModal(int $projectId): void
    {
        $this->reviewProjectId = $projectId;
        $this->rejectionReasonInput = '';
        $this->showRejectModal = false;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->showRejectModal = false;
        $this->reviewProjectId = null;
        $this->rejectionReasonInput = '';
    }

    public function acceptProjectAssignment(int $projectId, bool $redirectToWorkspace = false)
    {
        $user = auth()->user();
        $project = Project::where('project_manager_id', $user->id)->findOrFail($projectId);

        $project->acceptByPm($user);
        $this->closeReviewModal();

        $this->dispatch('toast', message: '🎉 Project leadership accepted! Workspace is now unlocked.', type: 'success');

        if ($redirectToWorkspace) {
            return redirect()->route('projects.show', $project->id);
        }
    }

    public function openRejectForm(int $projectId): void
    {
        $this->reviewProjectId = $projectId;
        $this->rejectionReasonInput = '';
        $this->showRejectModal = true;
    }

    public function submitRejection(): void
    {
        $this->validate([
            'rejectionReasonInput' => 'required|string|min:5|max:1000',
        ], [
            'rejectionReasonInput.required' => 'Please explain the reason or issue for declining this leadership assignment.',
            'rejectionReasonInput.min' => 'The reason must be at least 5 characters.',
        ]);

        $project = Project::findOrFail($this->reviewProjectId);
        $user = auth()->user();

        if ($project->project_manager_id !== $user->id && !$user->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $project->rejectByPm($user, $this->rejectionReasonInput);
        $this->closeReviewModal();
        $this->dispatch('toast', message: 'Project assignment declined. PMO Admin has been notified with your issue report.', type: 'warning');
    }

    public function approveRequest(int $requestId): void
    {
        $req = ApprovalRequest::findOrFail($requestId);
        $user = auth()->user();

        (new ApprovalService())->processApproval($req, $user, true, 'Approved by Project Manager');
        $this->dispatch('toast', message: 'Request approved successfully!', type: 'success');
    }

    public function rejectRequest(int $requestId): void
    {
        $req = ApprovalRequest::findOrFail($requestId);
        $user = auth()->user();

        (new ApprovalService())->processApproval($req, $user, false, 'Rejected by Project Manager');
        $this->dispatch('toast', message: 'Request rejected.', type: 'info');
    }

    public function toggleTaskComplete(int $taskId): void
    {
        $task = WbsItem::findOrFail($taskId);
        $user = auth()->user();

        // Authorization: assigned user or PM of the project or admin
        if ($task->assigned_user_id !== $user->id && $task->project?->project_manager_id !== $user->id && !$user->isPmoAdmin()) {
            $this->dispatch('toast', message: 'You are not authorized to update this task.', type: 'error');
            return;
        }

        $isCompleted = ($task->status === \App\Enums\WbsStatus::COMPLETED || $task->status?->value === 'completed');

        if ($isCompleted) {
            $task->status = \App\Enums\WbsStatus::IN_PROGRESS;
            $task->progress = 50;
        } else {
            $task->status = \App\Enums\WbsStatus::COMPLETED;
            $task->progress = 100;
        }

        $task->save();
        (new \App\Services\ProgressCalculationService())->updateItemProgress($task);

        // Record personal & project activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $isCompleted ? 'updated_wbs_item' : 'completed_wbs_item',
            'module' => 'wbs',
            'record_type' => WbsItem::class,
            'record_id' => $task->id,
            'new_values' => [
                'project_id' => $task->project_id,
                'title' => $task->title,
                'name' => $task->title,
                'progress' => $task->progress,
                'status' => is_object($task->status) ? $task->status->value : $task->status,
                'summary' => $isCompleted ? "Moved task '{$task->title}' to In Progress" : "Completed task '{$task->title}'",
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $msg = $isCompleted ? "Task '{$task->title}' marked In Progress." : "🎉 Task '{$task->title}' marked Completed!";
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function openResolveBlockerModal(int $blockerId): void
    {
        $this->selectedBlockerId = $blockerId;
        $this->blockerResolutionInput = '';
        $this->showResolveBlockerModal = true;
    }

    public function resolveBlocker(): void
    {
        $this->validate([
            'blockerResolutionInput' => 'required|string|min:3|max:500',
        ]);

        $blocker = TaskBlocker::findOrFail($this->selectedBlockerId);
        $blocker->update([
            'status' => 'resolved',
            'resolution' => trim($this->blockerResolutionInput),
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        if ($blocker->wbsItem && $blocker->wbsItem->status->value === 'blocked') {
            $blocker->wbsItem->update(['status' => \App\Enums\WbsStatus::IN_PROGRESS]);
        }

        // Record activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'resolved_blocker',
            'module' => 'blockers',
            'record_type' => TaskBlocker::class,
            'record_id' => $blocker->id,
            'new_values' => [
                'project_id' => $blocker->wbsItem?->project_id,
                'title' => $blocker->title,
                'summary' => "Resolved blocker for task '{$blocker->wbsItem?->title}'",
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->showResolveBlockerModal = false;
        $this->selectedBlockerId = null;
        $this->blockerResolutionInput = '';
        $this->dispatch('toast', message: '✓ Task blocker resolved!', type: 'success');
    }

    public function openAddRiskModal(): void
    {
        $user = auth()->user();
        $myFirstProject = Project::where('project_manager_id', $user->id)->first();
        $this->riskProjectId = $myFirstProject?->id;
        $this->riskTitle = '';
        $this->riskCategory = 'Technical';
        $this->riskProbability = 'medium';
        $this->riskImpact = 'medium';
        $this->riskMitigation = '';
        $this->showAddRiskModal = true;
    }

    public function createRisk(): void
    {
        $this->validate([
            'riskProjectId' => 'required|exists:projects,id',
            'riskTitle' => 'required|string|min:3|max:255',
            'riskCategory' => 'required|string',
            'riskProbability' => 'required|in:low,medium,high',
            'riskImpact' => 'required|in:low,medium,high',
        ]);

        $probWeights = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impWeights  = ['low' => 1, 'medium' => 2, 'high' => 3];
        $score = ($probWeights[$this->riskProbability] ?? 2) * ($impWeights[$this->riskImpact] ?? 2);

        $risk = ProjectRisk::create([
            'project_id' => $this->riskProjectId,
            'title' => trim($this->riskTitle),
            'category' => $this->riskCategory,
            'probability' => $this->riskProbability,
            'impact' => $this->riskImpact,
            'risk_score' => $score,
            'owner_id' => auth()->id(),
            'mitigation_plan' => trim($this->riskMitigation),
            'status' => 'open',
        ]);

        // Record activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_risk',
            'module' => 'risks',
            'record_type' => ProjectRisk::class,
            'record_id' => $risk->id,
            'new_values' => [
                'project_id' => $this->riskProjectId,
                'title' => $risk->title,
                'name' => $risk->title,
                'summary' => "Logged new risk: '{$risk->title}'",
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->showAddRiskModal = false;
        $this->dispatch('toast', message: 'Risk successfully logged for project!', type: 'success');
    }

    public string $chartPeriod = 'week'; // 'week', 'month'

    public function setChartPeriod(string $period): void
    {
        $this->chartPeriod = in_array($period, ['week', 'month']) ? $period : 'week';
    }

    /**
     * Calculate 100% real system data points for the Tasks Progress chart
     */
    protected function calculateTasksTrend(array $myProjectIds): array
    {
        $user = auth()->user();

        // Also capture any projects where user has assigned tasks directly
        $assignedTaskProjectIds = WbsItem::where('assigned_user_id', $user->id)
            ->pluck('project_id')
            ->filter()
            ->unique()
            ->toArray();

        $targetProjectIds = array_values(array_unique(array_merge($myProjectIds, $assignedTaskProjectIds)));
        $points = [];
        $today = now()->today();
        
        if ($this->chartPeriod === 'month') {
            // Full calendar weeks of current month
            $monthStart = now()->startOfMonth();
            $monthEnd = now()->endOfMonth();
            $cursor = $monthStart->copy()->startOfWeek();
            $w = 1;

            while ($cursor->lte($monthEnd)) {
                $weekStart = $cursor->copy();
                $weekEnd = $cursor->copy()->endOfWeek();
                $isCurrentWeek = now()->between($weekStart, $weekEnd);
                $isFutureWeek = $weekStart->gt($today);

                // Clamp display dates to month bounds
                $displayStart = $weekStart->lt($monthStart) ? $monthStart : $weekStart;
                $displayEnd = $weekEnd->gt($monthEnd) ? $monthEnd : $weekEnd;

                $label = "Week {$w}";
                $sub = $displayStart->format('M d') . ' - ' . $displayEnd->format('d');

                if (!empty($targetProjectIds)) {
                    $comp = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->where('status', \App\Enums\WbsStatus::COMPLETED)
                        ->whereBetween('updated_at', [$weekStart->startOfDay(), $weekEnd->endOfDay()])
                        ->count();

                    $inProg = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->where('status', \App\Enums\WbsStatus::IN_PROGRESS)
                        ->where(function($q) use ($weekStart, $weekEnd) {
                            $q->whereBetween('updated_at', [$weekStart->startOfDay(), $weekEnd->endOfDay()])
                              ->orWhere(function($sq) use ($weekStart, $weekEnd) {
                                  $sq->whereDate('start_date', '<=', $weekEnd->toDateString())
                                     ->whereDate('end_date', '>=', $weekStart->toDateString());
                              });
                        })
                        ->count();

                    $pend = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->whereNotIn('status', [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED])
                        ->whereBetween('end_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                        ->count();
                } else {
                    $comp = 0;
                    $inProg = 0;
                    $pend = 0;
                }

                $points[] = [
                    'label' => $label,
                    'sub' => $sub,
                    'isToday' => $isCurrentWeek,
                    'isFuture' => $isFutureWeek,
                    'completed' => $comp,
                    'in_progress' => $inProg,
                    'pending' => $pend,
                ];

                $cursor->addWeek();
                $w++;
            }
        } else {
            // 7 Days: Mon, Tue, Wed, Thu, Fri, Sat, Sun
            $startOfWeek = now()->startOfWeek();
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $dateStr = $date->format('Y-m-d');
                $dayLabel = $date->format('D');
                $isToday = $date->isToday();
                $isFuture = $date->gt($today);

                if (!empty($targetProjectIds)) {
                    // Tasks completed on this specific day
                    $comp = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->where('status', \App\Enums\WbsStatus::COMPLETED)
                        ->whereDate('updated_at', $dateStr)
                        ->count();

                    // Tasks in progress on this day
                    $inProg = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->where('status', \App\Enums\WbsStatus::IN_PROGRESS)
                        ->where(function($q) use ($dateStr) {
                            $q->whereDate('updated_at', $dateStr)
                              ->orWhere(function($sq) use ($dateStr) {
                                  $sq->whereDate('start_date', '<=', $dateStr)
                                     ->whereDate('end_date', '>=', $dateStr);
                              });
                        })
                        ->count();

                    // Tasks due or pending deadline on this day
                    $pend = WbsItem::whereIn('project_id', $targetProjectIds)
                        ->whereNotIn('status', [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED])
                        ->whereDate('end_date', $dateStr)
                        ->count();
                } else {
                    $comp = 0;
                    $inProg = 0;
                    $pend = 0;
                }

                $points[] = [
                    'label' => $dayLabel,
                    'sub' => $date->format('M d'),
                    'isToday' => $isToday,
                    'isFuture' => $isFuture,
                    'completed' => $comp,
                    'in_progress' => $inProg,
                    'pending' => $pend,
                ];
            }
        }

        // Dynamic Chart Y-Max and Scale
        $allVals = [5];
        foreach ($points as $pt) {
            $allVals[] = $pt['completed'];
            $allVals[] = $pt['in_progress'];
            $allVals[] = $pt['pending'];
        }
        $maxVal = max($allVals);
        $chartYMax = max(5, (int)(ceil($maxVal / 5) * 5));

        return [
            'points' => $points,
            'yMax' => $chartYMax,
        ];
    }

    public function render()
    {
        $user = auth()->user();

        // Pending Invitations awaiting acceptance by this PM
        $pendingInvitations = Project::where('project_manager_id', $user->id)
            ->where('pm_accepted', false)
            ->with(['subsidiary', 'creator', 'template.tasks', 'wbsItems', 'members'])
            ->latest()
            ->get();

        // Projects managed by this PM (all)
        $assignedProjects = Project::where('project_manager_id', $user->id)
            ->with(['subsidiary'])
            ->get();

        $leadProjectsCount = $assignedProjects->count();

        // Projects where user is a team member/collaborator (excluding projects they lead)
        $collaboratingProjects = Project::whereHas('members', fn($q) => $q->where('users.id', $user->id))
            ->where('project_manager_id', '!=', $user->id)
            ->where('pm_accepted', true)
            ->with(['subsidiary'])
            ->get();
        $collaboratingProjectsCount = $collaboratingProjects->count();

        // Total unique projects user is involved in (as Lead PM, Member, Governance, or Task Assignee)
        $involvedProjectIds = Project::where(function($q) use ($user) {
            $q->where('project_manager_id', $user->id)
              ->orWhereHas('members', fn($m) => $m->where('users.id', $user->id))
              ->orWhereHas('wbsItems', fn($w) => $w->where('assigned_user_id', $user->id));
        })->pluck('id')->toArray();

        $totalInvolvedProjectsCount = count($involvedProjectIds);
        $assignedProjectIds = $assignedProjects->pluck('id')->toArray();

        $activeProjectsCount = Project::whereIn('id', $involvedProjectIds)
            ->where('status', 'in_progress')
            ->count();

        // Automatically transition tasks to in_progress if start_date has arrived
        WbsItem::autoStartDueTasks();

        // Tasks due today in involved projects
        $tasksDueToday = WbsItem::whereIn('project_id', $involvedProjectIds)
            ->whereDate('end_date', now()->toDateString())
            ->get();

        // Overdue tasks
        $overdueTasksCount = WbsItem::whereIn('project_id', $involvedProjectIds)
            ->whereDate('end_date', '<', now()->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        // Pending WBS Approvals
        $pendingApprovals = ApprovalRequest::with(['project', 'requester'])
            ->whereIn('project_id', $involvedProjectIds)
            ->where('status', 'pending')
            ->get();

        $allApprovals = ApprovalRequest::with(['project', 'requester', 'approver'])
            ->whereIn('project_id', $involvedProjectIds)
            ->latest()
            ->get();

        // Project risks across all user's involved projects & owned risks
        $openRisks = ProjectRisk::with(['project', 'owner', 'wbsItem'])
            ->where(function($q) use ($user, $involvedProjectIds) {
                $q->whereIn('project_id', $involvedProjectIds)
                  ->orWhere('owner_id', $user->id)
                  ->orWhereHas('wbsItem', fn($w) => $w->where('assigned_user_id', $user->id));
            })
            ->whereIn('status', ['open', 'monitoring', 'identified'])
            ->latest()
            ->get();

        $allRisks = ProjectRisk::with(['project', 'owner', 'wbsItem'])
            ->where(function($q) use ($user, $involvedProjectIds) {
                $q->whereIn('project_id', $involvedProjectIds)
                  ->orWhere('owner_id', $user->id)
                  ->orWhereHas('wbsItem', fn($w) => $w->where('assigned_user_id', $user->id));
            })
            ->latest()
            ->get();

        // Current blockers across all user's involved projects & reported blockers
        $currentBlockers = TaskBlocker::with(['wbsItem.project', 'reporter'])
            ->where(function($q) use ($user, $involvedProjectIds) {
                $q->whereHas('wbsItem', fn($w) => $w->whereIn('project_id', $involvedProjectIds))
                  ->orWhereHas('wbsItem', fn($w) => $w->where('assigned_user_id', $user->id))
                  ->orWhere('reported_by', $user->id);
            })
            ->where('status', 'open')
            ->latest()
            ->get();

        $allBlockers = TaskBlocker::with(['wbsItem.project', 'reporter'])
            ->where(function($q) use ($user, $involvedProjectIds) {
                $q->whereHas('wbsItem', fn($w) => $w->whereIn('project_id', $involvedProjectIds))
                  ->orWhereHas('wbsItem', fn($w) => $w->where('assigned_user_id', $user->id))
                  ->orWhere('reported_by', $user->id);
            })
            ->latest()
            ->get();

        // Team member & PM delayed task issues
        $teamTaskIssues = WbsItem::with(['project', 'assignedUser', 'delayReporter'])
            ->whereIn('project_id', $involvedProjectIds)
            ->whereNotNull('delay_reason')
            ->latest('delay_reason_at')
            ->get();

        // Real User-Specific Calculations
        $myProjects = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->whereHas('members', fn($m) => $m->where('users.id', $user->id));
                  });
            })
            ->with(['subsidiary', 'wbsItems', 'members'])
            ->get();

        // ─── User Role Breakdown across Projects (Dynamic Matrix Organization) ───
        $leadProjectsCount = Project::where('project_manager_id', $user->id)->count();

        // Projects where user is a governance/team member (and not the PM)
        $memberProjectsBase = Project::where('project_manager_id', '!=', $user->id)
            ->where(function($q) use ($user) {
                $q->where('pm_accepted', true)
                  ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
            });

        $sponsorProjectsCount = (clone $memberProjectsBase)
            ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'sponsor'))
            ->count();

        $ownerProjectsCount = (clone $memberProjectsBase)
            ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'owner'))
            ->count();

        $steeringCommitteeProjectsCount = (clone $memberProjectsBase)
            ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'steering_committee'))
            ->count();

        $coreMemberProjectsCount = (clone $memberProjectsBase)
            ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', 'member'))
            ->count();

        // Attach user's specific role to each project in $myProjects for dashboard display
        $myProjects->each(function($p) use ($user) {
            if ($p->project_manager_id === $user->id) {
                $p->user_assigned_role = 'lead';
                $p->user_role_label = 'Project Manager';
                $p->user_role_short = 'Lead PM';
                $p->user_role_icon = '⭐';
                $p->user_role_badge = 'bg-amber-50 text-amber-800 border-amber-200/80';
            } else {
                $member = $p->members->firstWhere('id', $user->id);
                $roleKey = $member?->pivot?->role ?? 'member';
                $p->user_assigned_role = $roleKey;
                $p->user_role_label = match($roleKey) {
                    'sponsor' => 'Project Sponsor',
                    'owner' => 'Project Owner',
                    'steering_committee' => 'Steering Committee',
                    default => 'Team Member',
                };
                $p->user_role_short = match($roleKey) {
                    'sponsor' => 'Sponsor',
                    'owner' => 'Owner',
                    'steering_committee' => 'Committee',
                    default => 'Member',
                };
                $p->user_role_icon = match($roleKey) {
                    'sponsor' => '💼',
                    'owner' => '🏛️',
                    'steering_committee' => '🎖️',
                    default => '🤝',
                };
                $p->user_role_badge = match($roleKey) {
                    'sponsor' => 'bg-purple-50 text-purple-800 border-purple-200/80',
                    'owner' => 'bg-blue-50 text-blue-800 border-blue-200/80',
                    'steering_committee' => 'bg-rose-50 text-rose-800 border-rose-200/80',
                    default => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                };
            }
        });

        $roleBreakdown = [
            'lead' => [
                'label' => 'Project Manager',
                'short' => 'Lead PM',
                'icon'  => '⭐',
                'count' => $leadProjectsCount,
                'bg'    => 'bg-amber-50 text-amber-800 border-amber-200/80',
                'pill'  => 'bg-amber-100 text-amber-900',
            ],
            'sponsor' => [
                'label' => 'Project Sponsor',
                'short' => 'Sponsor',
                'icon'  => '💼',
                'count' => $sponsorProjectsCount,
                'bg'    => 'bg-purple-50 text-purple-800 border-purple-200/80',
                'pill'  => 'bg-purple-100 text-purple-900',
            ],
            'owner' => [
                'label' => 'Project Owner',
                'short' => 'Owner',
                'icon'  => '🏛️',
                'count' => $ownerProjectsCount,
                'bg'    => 'bg-blue-50 text-blue-800 border-blue-200/80',
                'pill'  => 'bg-blue-100 text-blue-900',
            ],
            'steering_committee' => [
                'label' => 'Steering Committee',
                'short' => 'Committee',
                'icon'  => '🎖️',
                'count' => $steeringCommitteeProjectsCount,
                'bg'    => 'bg-rose-50 text-rose-800 border-rose-200/80',
                'pill'  => 'bg-rose-100 text-rose-900',
            ],
            'member' => [
                'label' => 'Team Member',
                'short' => 'Core Member',
                'icon'  => '🤝',
                'count' => $coreMemberProjectsCount,
                'bg'    => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                'pill'  => 'bg-emerald-100 text-emerald-900',
            ],
        ];

        $distinctActiveRolesCount = count(array_filter($roleBreakdown, fn($r) => $r['count'] > 0));
        $totalRoleAssignmentsCount = $leadProjectsCount + $sponsorProjectsCount + $ownerProjectsCount + $steeringCommitteeProjectsCount + $coreMemberProjectsCount;

        $myProjectsCount = $myProjects->count();
        $inProgressProjectsCount = $myProjects->where('status', 'in_progress')->count();
        $onHoldProjectsCount = $myProjects->where('status', 'on_hold')->count();
        $completedProjectsCount = $myProjects->where('status', 'completed')->count();
        $onTrackProjectsCount = $myProjects->where('health', 'on_track')->count();
        if ($onTrackProjectsCount === 0 && $inProgressProjectsCount > 0) {
            $onTrackProjectsCount = $inProgressProjectsCount;
        }

        // Project percentages (100% Real)
        $inProgressPct = $myProjectsCount > 0 ? (int) round(($inProgressProjectsCount / $myProjectsCount) * 100) : 0;
        $onTrackPct = $myProjectsCount > 0 ? (int) round(($onTrackProjectsCount / $myProjectsCount) * 100) : 0;
        $onHoldPct = $myProjectsCount > 0 ? (int) round(($onHoldProjectsCount / $myProjectsCount) * 100) : 0;
        $completedPct = $myProjectsCount > 0 ? (int) round(($completedProjectsCount / $myProjectsCount) * 100) : 0;

        // User tasks (100% Real)
        $myProjectIds = $myProjects->pluck('id')->toArray();
        
        // Tasks assigned specifically to this logged-in user
        $myAssignedTasks = WbsItem::where('assigned_user_id', $user->id)
            ->whereHas('project')
            ->with('project')
            ->get();
        $myAssignedTasksCount = $myAssignedTasks->count();
        $completedTodayCount = $myAssignedTasks->where('status', \App\Enums\WbsStatus::COMPLETED)
            ->filter(fn($t) => $t->updated_at && $t->updated_at->isToday())->count();

        // All tasks across involved projects (for project scope & task progress)
        $allProjectTasks = WbsItem::whereIn('project_id', $myProjectIds)
            ->with('project')
            ->get();
        $teamTasksCount = $allProjectTasks->count();
        $totalProjectTasksCount = $allProjectTasks->count();

        // Tasks pool for dashboard cards & system progress:
        $completedTasksCount = $allProjectTasks->where('status', \App\Enums\WbsStatus::COMPLETED)->count();
        $inProgressTasksCount = $allProjectTasks->where('status', \App\Enums\WbsStatus::IN_PROGRESS)->count();
        $pendingTasksCount = $allProjectTasks->whereIn('status', [\App\Enums\WbsStatus::NOT_STARTED, \App\Enums\WbsStatus::BACKLOG])->count();
        $myTasksCount = $myAssignedTasksCount;

        // Accurate completion percentages
        $completedTasksPct = $totalProjectTasksCount > 0 ? (int) round(($completedTasksCount / $totalProjectTasksCount) * 100) : 0;
        $inProgressTasksPct = $totalProjectTasksCount > 0 ? (int) round(($inProgressTasksCount / $totalProjectTasksCount) * 100) : 0;
        $pendingTasksPct = $totalProjectTasksCount > 0 ? (int) round(($pendingTasksCount / $totalProjectTasksCount) * 100) : 0;
        
        $tasksDueSoon = $allProjectTasks->filter(function($t) {
            return $t->end_date && $t->end_date->isFuture() && $t->end_date->diffInDays(now()->today()) <= 7 && !in_array($t->status?->value, ['completed', 'cancelled']);
        });
        $tasksDueSoonCount = $tasksDueSoon->count();

        // Overdue tasks: for PMs leading projects, across project tasks; for regular users, strictly tasks assigned to them
        $isLeadingProjects = Project::where('project_manager_id', $user->id)->exists();
        if ($isLeadingProjects) {
            $overdueTasksCount = $allProjectTasks->filter(function($t) {
                return $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status?->value, ['completed', 'cancelled']);
            })->count();
        } else {
            $overdueTasksCount = $myAssignedTasks->filter(function($t) {
                return $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status?->value, ['completed', 'cancelled']);
            })->count();
        }

        // New projects this week count
        $newProjectsThisWeekCount = $myProjects->filter(function($p) {
            return $p->created_at && $p->created_at->gte(now()->startOfWeek());
        })->count();

        // Approvals (100% Real)
        $pendingApprovalsCount = ApprovalRequest::whereIn('project_id', $myProjectIds)
            ->where('status', 'pending')
            ->count();
        if ($pendingApprovalsCount === 0) {
            $pendingApprovalsCount = ApprovalRequest::where('status', 'pending')->count();
        }

        // Hours this week (100% Real logged actual hours)
        $totalActualHours = (float) $allProjectTasks->sum('actual_hours');
        if ($totalActualHours > 0) {
            $h = floor($totalActualHours);
            $m = round(($totalActualHours - $h) * 60);
            $hoursThisWeekFormatted = "{$h}h {$m}m";
        } else {
            $hoursThisWeekFormatted = "0h 0m";
        }

        // Overall progress (100% Real average)
        $avgProgress = $myProjects->whereNotIn('status', ['cancelled'])->avg('overall_progress');
        $overallAvgProgress = $avgProgress ? (int) round($avgProgress) : ($totalProjectTasksCount > 0 ? (int) round(($completedTasksCount / $totalProjectTasksCount) * 100) : 0);

        // Upcoming Milestones (100% Real From WbsItems - Milestones Only)
        $upcomingMilestones = WbsItem::with('project')
            ->whereIn('project_id', $myProjectIds)
            ->where('is_milestone', true)
            ->whereNotNull('end_date')
            ->whereNotIn('status', [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED])
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Tasks Due Soon List: prioritize tasks assigned to this user first!
        $directUpcoming = WbsItem::with(['project', 'assignedUser'])
            ->where('assigned_user_id', $user->id)
            ->whereNotNull('end_date')
            ->whereNotIn('status', [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED])
            ->orderBy('end_date', 'asc')
            ->get();

        // Tasks Due Soon List: Strictly tasks assigned to this user only
        $myTasksDueSoonList = $directUpcoming->take(6);

        // Recent Activity: Strictly actions performed by this user OR related to this user's projects
        $projectTaskIds = !empty($myProjectIds) ? WbsItem::whereIn('project_id', $myProjectIds)->pluck('id')->toArray() : [];
        $projectApprovalIds = !empty($myProjectIds) 
            ? ApprovalRequest::where(function($q) use ($user, $myProjectIds) {
                $q->where('requested_by', $user->id)
                  ->orWhereIn('project_id', $myProjectIds);
            })->pluck('id')->toArray() 
            : ApprovalRequest::where('requested_by', $user->id)->pluck('id')->toArray();
        $projectRiskIds = !empty($myProjectIds) ? ProjectRisk::whereIn('project_id', $myProjectIds)->pluck('id')->toArray() : [];

        $recentActivities = ActivityLog::with('user')
            ->where(function($q) use ($user, $myProjectIds, $projectTaskIds, $projectApprovalIds, $projectRiskIds) {
                // 1. Actions performed by this user (thaman karapuwa)
                $q->where('user_id', $user->id);

                // 2. Project updates for projects the user is directly involved in (thamange project ekata adala ewa)
                if (!empty($myProjectIds)) {
                    $q->orWhere(function($sq) use ($myProjectIds) {
                        $sq->where('record_type', Project::class)
                           ->whereIn('record_id', $myProjectIds);
                    });
                }

                // 3. WBS tasks relevant to the user's projects
                if (!empty($projectTaskIds)) {
                    $q->orWhere(function($sq) use ($projectTaskIds) {
                        $sq->where('record_type', WbsItem::class)
                           ->whereIn('record_id', $projectTaskIds);
                    });
                }

                // 4. Relevant approval requests in user's projects or requested by user
                if (!empty($projectApprovalIds)) {
                    $q->orWhere(function($sq) use ($projectApprovalIds) {
                        $sq->where('record_type', ApprovalRequest::class)
                           ->whereIn('record_id', $projectApprovalIds);
                    });
                }

                // 5. Relevant project risks in user's projects
                if (!empty($projectRiskIds)) {
                    $q->orWhere(function($sq) use ($projectRiskIds) {
                        $sq->where('record_type', ProjectRisk::class)
                           ->whereIn('record_id', $projectRiskIds);
                    });
                }
            })
            ->latest()
            ->take(6)
            ->get();

        $projectNamesMap = Project::pluck('name', 'id')->toArray();
        $projectCodesMap = Project::pluck('code', 'id')->toArray();
        $actWbsIds = $recentActivities->where('record_type', WbsItem::class)->pluck('record_id')->filter()->unique()->toArray();
        $wbsTitlesMap = !empty($actWbsIds) ? WbsItem::whereIn('id', $actWbsIds)->pluck('title', 'id')->toArray() : [];

        // My Approvals List (100% Real)
        $myApprovalsList = ApprovalRequest::with(['project', 'requester'])
            ->latest()
            ->take(5)
            ->get();

        // Weekly / Monthly real trend data points for chart
        $trendData = $this->calculateTasksTrend($myProjectIds);
        $chartPoints = $trendData['points'];
        $chartYMax = $trendData['yMax'];


        // Team Members Overview (for My Team card)
        $teamMembersRaw = User::whereHas('projects', fn($q) => $q->whereIn('projects.id', $assignedProjectIds))
            ->with([
                'projects' => fn($q) => $q->whereIn('projects.id', $assignedProjectIds)->select('projects.id', 'projects.name'),
            ])
            ->get();

        $teamMembers = $teamMembersRaw->map(function ($member) use ($assignedProjectIds) {
            $activeTasks = WbsItem::where('assigned_user_id', $member->id)
                ->whereIn('project_id', $assignedProjectIds)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();
            $completedTasks = WbsItem::where('assigned_user_id', $member->id)
                ->whereIn('project_id', $assignedProjectIds)
                ->where('status', 'completed')
                ->count();
            $overdueTasks = WbsItem::where('assigned_user_id', $member->id)
                ->whereIn('project_id', $assignedProjectIds)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->whereDate('end_date', '<', now()->toDateString())
                ->count();
            return [
                'id'            => $member->id,
                'name'          => $member->name,
                'email'         => $member->email,
                'role'          => $member->projects->first()?->pivot?->role ?? 'Member',
                'activeTasks'   => $activeTasks,
                'completedTasks'=> $completedTasks,
                'overdueTasks'  => $overdueTasks,
                'projectCount'  => $member->projects->count(),
            ];
        })->sortByDesc('activeTasks')->values()->take(6);


        $reviewProject = null;
        $reviewApproval = null;
        $reviewWbsItems = collect();
        $reviewPhases = collect();
        $reviewMilestonesCount = 0;
        $reviewSponsors = collect();
        $reviewOwners = collect();
        $reviewCommittee = collect();
        $reviewMembers = collect();
        $reviewDurationDays = 0;
        $reviewTotalTeam = 0;

        if ($this->reviewProjectId) {
            $reviewProject = Project::with(['subsidiary', 'projectManager', 'creator', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId);

            if ($reviewProject) {
                $reviewApproval = ApprovalRequest::with('requester')
                    ->where('project_id', $reviewProject->id)
                    ->where('request_type', \App\Enums\ApprovalType::NEW_PROJECT_PLAN)
                    ->latest()
                    ->first();

                $reviewWbsItems = $reviewProject->wbsItems()->orderBy('id')->get();
                if ($reviewWbsItems->isEmpty() && $reviewProject->template) {
                    $reviewWbsItems = $reviewProject->template->tasks()->orderBy('id')->get();
                }

                $reviewPhases = $reviewWbsItems->filter(fn($i) => ($i->item_type?->value === 'phase' || !$i->parent_id));
                $reviewMilestonesCount = $reviewWbsItems->where('is_milestone', true)->count();

                $reviewSponsors = $reviewProject->members->where('pivot.role', 'sponsor');
                $reviewOwners = $reviewProject->members->where('pivot.role', 'owner');
                $reviewCommittee = $reviewProject->members->where('pivot.role', 'steering_committee');
                $reviewMembers = $reviewProject->members->where('pivot.role', 'member');
                $reviewTotalTeam = 1 + $reviewSponsors->count() + $reviewOwners->count() + $reviewCommittee->count() + $reviewMembers->count();

                if ($reviewProject->start_date && $reviewProject->deadline) {
                    $reviewDurationDays = max(1, $reviewProject->start_date->diffInDays($reviewProject->deadline));
                }
            }
        }


        return view('livewire.project-manager-dashboard', compact(
            'pendingInvitations',
            'reviewProject',
            'reviewApproval',
            'reviewWbsItems',
            'reviewPhases',
            'reviewMilestonesCount',
            'reviewSponsors',
            'reviewOwners',
            'reviewCommittee',
            'reviewMembers',
            'reviewDurationDays',
            'reviewTotalTeam',
            'myProjects',
            'myProjectsCount',
            'inProgressProjectsCount',
            'onHoldProjectsCount',
            'completedProjectsCount',
            'onTrackProjectsCount',
            'inProgressPct',
            'onTrackPct',
            'onHoldPct',
            'completedPct',
            'myTasksCount',
            'myAssignedTasksCount',
            'teamTasksCount',
            'completedTodayCount',
            'newProjectsThisWeekCount',
            'completedTasksCount',
            'inProgressTasksCount',
            'pendingTasksCount',
            'tasksDueSoonCount',
            'overdueTasksCount',
            'pendingApprovalsCount',
            'hoursThisWeekFormatted',
            'overallAvgProgress',
            'upcomingMilestones',
            'myTasksDueSoonList',
            'recentActivities',
            'myApprovalsList',
            'chartPoints',
            'chartYMax',
            'assignedProjects',
            'leadProjectsCount',
            'collaboratingProjectsCount',
            'totalInvolvedProjectsCount',
            'activeProjectsCount',
            'pendingApprovals',
            'allApprovals',
            'openRisks',
            'allRisks',
            'currentBlockers',
            'allBlockers',
            'teamTaskIssues',
            'teamMembers',
            'projectNamesMap',
            'projectCodesMap',
            'wbsTitlesMap',
            'totalProjectTasksCount',
            'completedTasksPct',
            'inProgressTasksPct',
            'pendingTasksPct',
            'roleBreakdown',
            'sponsorProjectsCount',
            'ownerProjectsCount',
            'steeringCommitteeProjectsCount',
            'coreMemberProjectsCount',
            'distinctActiveRolesCount',
            'totalRoleAssignmentsCount'
        ));
    }
}
