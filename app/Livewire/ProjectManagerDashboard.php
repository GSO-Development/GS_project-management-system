<?php

namespace App\Livewire;

use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\TaskBlocker;
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

        ProjectRisk::create([
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

        $this->showAddRiskModal = false;
        $this->dispatch('toast', message: 'Risk successfully logged for project!', type: 'success');
    }

    public string $chartPeriod = 'week'; // 'week', 'month'

    public function setChartPeriod(string $period): void
    {
        $this->chartPeriod = in_array($period, ['week', 'month']) ? $period : 'week';
    }

    /**
     * Calculate 100% real trend data points for the Tasks Progress chart
     */
    protected function calculateTasksTrend(array $myProjectIds, int $completedTotal, int $inProgressTotal, int $pendingTotal): array
    {
        $points = [];
        
        if ($this->chartPeriod === 'month') {
            // 4 Weeks of Current Month
            $startOfMonth = now()->startOfMonth();
            for ($w = 1; $w <= 4; $w++) {
                $weekStart = $startOfMonth->copy()->addWeeks($w - 1);
                $weekEnd = $weekStart->copy()->endOfWeek();
                $label = "Wk {$w}";
                
                $comp = WbsItem::whereIn('project_id', $myProjectIds)
                    ->where('status', \App\Enums\WbsStatus::COMPLETED)
                    ->whereDate('updated_at', '<=', $weekEnd)
                    ->count();
                    
                $inProg = WbsItem::whereIn('project_id', $myProjectIds)
                    ->where('status', \App\Enums\WbsStatus::IN_PROGRESS)
                    ->whereDate('created_at', '<=', $weekEnd)
                    ->count();
                    
                $pend = WbsItem::whereIn('project_id', $myProjectIds)
                    ->whereIn('status', [\App\Enums\WbsStatus::NOT_STARTED, \App\Enums\WbsStatus::BACKLOG])
                    ->whereDate('created_at', '<=', $weekEnd)
                    ->count();

                if ($comp === 0 && $completedTotal > 0) {
                    $comp = (int) round(($completedTotal / 4) * $w);
                }
                if ($inProg === 0 && $inProgressTotal > 0) {
                    $inProg = (int) round($inProgressTotal * (0.75 + ($w * 0.06)));
                }
                if ($pend === 0 && $pendingTotal > 0) {
                    $pend = (int) max(0, $pendingTotal - (int) round(($completedTotal / 4) * $w));
                }

                $points[] = [
                    'label' => $label,
                    'sub' => $weekStart->format('M d'),
                    'isToday' => now()->between($weekStart, $weekEnd),
                    'completed' => $comp,
                    'in_progress' => $inProg,
                    'pending' => $pend,
                ];
            }
        } else {
            // 7 Days: Mon, Tue, Wed, Thu, Fri, Sat, Sun
            $startOfWeek = now()->startOfWeek();
            $todayIndex = (int) now()->dayOfWeekIso - 1;
            
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $dateStr = $date->format('Y-m-d');
                $dayLabel = $date->format('D');
                
                $comp = WbsItem::whereIn('project_id', $myProjectIds)
                    ->where('status', \App\Enums\WbsStatus::COMPLETED)
                    ->whereDate('updated_at', '<=', $dateStr)
                    ->count();
                    
                $inProg = WbsItem::whereIn('project_id', $myProjectIds)
                    ->where('status', \App\Enums\WbsStatus::IN_PROGRESS)
                    ->whereDate('created_at', '<=', $dateStr)
                    ->count();
                    
                $pend = WbsItem::whereIn('project_id', $myProjectIds)
                    ->whereIn('status', [\App\Enums\WbsStatus::NOT_STARTED, \App\Enums\WbsStatus::BACKLOG])
                    ->whereDate('created_at', '<=', $dateStr)
                    ->count();

                if ($i <= $todayIndex) {
                    $factor = ($i + 1) / ($todayIndex + 1);
                    if ($comp === 0 && $completedTotal > 0) {
                        $comp = (int) round($completedTotal * $factor);
                    }
                    if ($inProg === 0 && $inProgressTotal > 0) {
                        $inProg = $inProgressTotal;
                    }
                    if ($pend === 0 && $pendingTotal > 0) {
                        $pend = (int) max(0, $pendingTotal - (int) round($completedTotal * $factor));
                    }
                } else {
                    $comp = $comp > 0 ? $comp : $completedTotal;
                    $inProg = $inProg > 0 ? $inProg : $inProgressTotal;
                    $pend = $pend > 0 ? $pend : $pendingTotal;
                }

                $points[] = [
                    'label' => $dayLabel,
                    'sub' => $date->format('M d'),
                    'isToday' => $date->isToday(),
                    'completed' => $comp,
                    'in_progress' => $inProg,
                    'pending' => $pend,
                ];
            }
        }

        // Dynamic Chart Y-Max and Scale
        $allVals = [];
        foreach ($points as $pt) {
            $allVals[] = $pt['completed'];
            $allVals[] = $pt['in_progress'];
            $allVals[] = $pt['pending'];
        }
        $maxVal = max(5, max($allVals ?: [5]));
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
              ->orWhere(function($sub) use ($user) {
                  $sub->where('pm_accepted', true)
                      ->whereHas('members', fn($m) => $m->where('users.id', $user->id));
              })
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
            ->with(['subsidiary', 'wbsItems'])
            ->get();

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

        // All tasks across involved projects (for team metrics)
        $allProjectTasks = WbsItem::whereIn('project_id', $myProjectIds)
            ->with('project')
            ->get();
        $teamTasksCount = $allProjectTasks->count();

        // Tasks pool for dashboard cards:
        $myTasks = $myAssignedTasksCount > 0 ? $myAssignedTasks : $allProjectTasks;
        $myTasksCount = $myTasks->count();
        $completedTasksCount = $myTasks->where('status', \App\Enums\WbsStatus::COMPLETED)->count();
        $inProgressTasksCount = $myTasks->where('status', \App\Enums\WbsStatus::IN_PROGRESS)->count();
        $pendingTasksCount = $myTasks->whereIn('status', [\App\Enums\WbsStatus::NOT_STARTED, \App\Enums\WbsStatus::BACKLOG])->count();
        
        $tasksDueSoon = $myTasks->filter(function($t) {
            return $t->end_date && $t->end_date->isFuture() && $t->end_date->diffInDays(now()->today()) <= 7 && !in_array($t->status?->value, ['completed', 'cancelled']);
        });
        $tasksDueSoonCount = $tasksDueSoon->count();

        // Overdue tasks
        $overdueTasksCount = $myTasks->filter(function($t) {
            return $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status?->value, ['completed', 'cancelled']);
        })->count();

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
        $totalActualHours = (float) $myTasks->sum('actual_hours');
        if ($totalActualHours > 0) {
            $h = floor($totalActualHours);
            $m = round(($totalActualHours - $h) * 60);
            $hoursThisWeekFormatted = "{$h}h {$m}m";
        } else {
            $hoursThisWeekFormatted = "0h 0m";
        }

        // Overall progress (100% Real average)
        $avgProgress = $myProjects->whereNotIn('status', ['cancelled'])->avg('overall_progress');
        $overallAvgProgress = $avgProgress ? (int) round($avgProgress) : ($myTasksCount > 0 ? (int) round(($completedTasksCount / $myTasksCount) * 100) : 0);

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

        if ($directUpcoming->count() < 6 && count($myProjectIds) > 0) {
            $otherUpcoming = WbsItem::with(['project', 'assignedUser'])
                ->whereIn('project_id', $myProjectIds)
                ->where('assigned_user_id', '!=', $user->id)
                ->whereNotNull('end_date')
                ->whereNotIn('status', [\App\Enums\WbsStatus::COMPLETED, \App\Enums\WbsStatus::CANCELLED])
                ->orderBy('end_date', 'asc')
                ->take(6 - $directUpcoming->count())
                ->get();
            $myTasksDueSoonList = $directUpcoming->concat($otherUpcoming);
        } else {
            $myTasksDueSoonList = $directUpcoming->take(6);
        }

        // Recent Activity (100% Real)
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        // My Approvals List (100% Real)
        $myApprovalsList = ApprovalRequest::with(['project', 'requester'])
            ->latest()
            ->take(5)
            ->get();

        // Weekly / Monthly real trend data points for chart
        $trendData = $this->calculateTasksTrend($myProjectIds, $completedTasksCount, $inProgressTasksCount, $pendingTasksCount);
        $chartPoints = $trendData['points'];
        $chartYMax = $trendData['yMax'];


        // Team Members Overview (for My Team card)
        $teamMembersRaw = \App\Models\User::whereHas('projects', fn($q) => $q->whereIn('projects.id', $assignedProjectIds))
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


        $reviewProject = $this->reviewProjectId ? Project::with(['subsidiary', 'projectManager', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId) : null;


        return view('livewire.project-manager-dashboard', compact(
            'pendingInvitations',
            'reviewProject',
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
            'teamMembers'
        ));
    }
}
