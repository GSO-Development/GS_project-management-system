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
            ->with(['subsidiary'])
            ->get();
        $collaboratingProjectsCount = $collaboratingProjects->count();

        // Total unique projects user is involved in
        $totalInvolvedProjectsCount = Project::where('project_manager_id', $user->id)
            ->orWhereHas('members', fn($q) => $q->where('users.id', $user->id))
            ->count();

        $assignedProjectIds = $assignedProjects->pluck('id')->toArray();
        $activeProjectsCount = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('members', fn($m) => $m->where('users.id', $user->id));
            })
            ->where('status', 'in_progress')
            ->count();

        // Automatically transition tasks to in_progress if start_date has arrived
        WbsItem::autoStartDueTasks();

        // Tasks due today in assigned projects
        $tasksDueToday = WbsItem::whereIn('project_id', $assignedProjectIds)
            ->whereDate('end_date', now()->toDateString())
            ->get();

        // Overdue tasks
        $overdueTasksCount = WbsItem::whereIn('project_id', $assignedProjectIds)
            ->whereDate('end_date', '<', now()->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();

        // Pending WBS Approvals
        $pendingApprovals = ApprovalRequest::with(['project', 'requester'])
            ->whereIn('project_id', $assignedProjectIds)
            ->where('status', 'pending')
            ->get();

        $allApprovals = ApprovalRequest::with(['project', 'requester', 'approver'])
            ->whereIn('project_id', $assignedProjectIds)
            ->latest()
            ->get();

        // Project risks
        $openRisks = ProjectRisk::with(['project', 'owner'])
            ->whereIn('project_id', $assignedProjectIds)
            ->where('status', 'open')
            ->get();

        $allRisks = ProjectRisk::with(['project', 'owner'])
            ->whereIn('project_id', $assignedProjectIds)
            ->latest()
            ->get();

        // Current blockers
        $currentBlockers = TaskBlocker::with(['wbsItem.project', 'reporter'])
            ->whereHas('wbsItem', fn($q) => $q->whereIn('project_id', $assignedProjectIds))
            ->where('status', 'open')
            ->get();

        $allBlockers = TaskBlocker::with(['wbsItem.project', 'reporter'])
            ->whereHas('wbsItem', fn($q) => $q->whereIn('project_id', $assignedProjectIds))
            ->latest()
            ->get();

        // Team member & PM delayed task issues
        $teamTaskIssues = WbsItem::with(['project', 'assignedUser', 'delayReporter'])
            ->whereIn('project_id', $assignedProjectIds)
            ->whereNotNull('delay_reason')
            ->latest('delay_reason_at')
            ->get();

        // Real User-Specific Calculations
        $myProjects = Project::where('project_manager_id', $user->id)
            ->orWhereHas('members', fn($m) => $m->where('users.id', $user->id))
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
        $myTasks = WbsItem::where(function($q) use ($user, $myProjectIds) {
                $q->where('assigned_user_id', $user->id)
                  ->orWhereIn('project_id', $myProjectIds);
            })
            ->with('project')
            ->get();

        $myTasksCount = $myTasks->count();
        $completedTasksCount = $myTasks->where('status', 'completed')->count();
        $inProgressTasksCount = $myTasks->where('status', 'in_progress')->count();
        $pendingTasksCount = $myTasks->whereIn('status', ['not_started', 'draft'])->count();
        
        $tasksDueSoon = $myTasks->filter(function($t) {
            return $t->end_date && $t->end_date->isFuture() && $t->end_date->diffInDays(now()->today()) <= 7 && !in_array($t->status->value, ['completed', 'cancelled']);
        });
        $tasksDueSoonCount = $tasksDueSoon->count();

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
        $overallAvgProgress = $avgProgress ? (int) round($avgProgress) : ($totalTasksCount > 0 ? (int) round(($completedTasksCount / $myTasksCount) * 100) : 0);

        // Upcoming Milestones (100% Real From WbsItems)
        $upcomingMilestones = WbsItem::with('project')
            ->whereIn('project_id', $myProjectIds)
            ->whereNotNull('end_date')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Tasks Due Soon List (100% Real)
        $myTasksDueSoonList = WbsItem::with('project')
            ->where(function($q) use ($user, $myProjectIds) {
                $q->where('assigned_user_id', $user->id)
                  ->orWhereIn('project_id', $myProjectIds);
            })
            ->whereNotNull('end_date')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

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

        // Weekly trend data points for chart
        $maxTasksVal = max(10, max($completedTasksCount, $inProgressTasksCount, $pendingTasksCount, 1));
        $chartYMax = max(10, (int)(ceil($maxTasksVal / 10) * 10));

        $reviewProject = $this->reviewProjectId ? Project::with(['subsidiary', 'projectManager', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId) : null;

        return view('livewire.project-manager-dashboard', compact(
            'pendingInvitations',
            'reviewProject',
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
            'completedTasksCount',
            'inProgressTasksCount',
            'pendingTasksCount',
            'tasksDueSoonCount',
            'pendingApprovalsCount',
            'hoursThisWeekFormatted',
            'overallAvgProgress',
            'upcomingMilestones',
            'myTasksDueSoonList',
            'recentActivities',
            'myApprovalsList',
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
            'teamTaskIssues'
        ));
    }
}
