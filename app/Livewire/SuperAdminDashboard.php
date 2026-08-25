<?php

namespace App\Livewire;

use App\Enums\ApprovalStatus;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectRisk;
use App\Models\ProjectStatusUpdate;
use App\Models\Subsidiary;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ApprovalService;
use Livewire\Component;

class SuperAdminDashboard extends Component
{
    // Active Dashboard Tab ('overview', 'approvals', 'risks')
    public string $dashboardTab = 'overview';

    // Live Project Tracker Interactive Controls
    public string $trackerSearch = '';
    public string $trackerSubsidiaryFilter = 'all';
    public string $trackerStatusFilter = 'all';
    public string $trackerLayout = 'cards'; // 'cards' or 'table'
    public bool $showAllProjects = false;

    public function toggleShowAllProjects(): void
    {
        $this->showAllProjects = !$this->showAllProjects;
    }

    // Approvals Management State
    public string $approvalStatusFilter = 'all';
    public string $approvalTypeFilter = 'all';
    public bool $showReviewModal = false;
    public ?int $selectedRequestId = null;
    public string $reviewComment = '';

    // Blocker Resolution State
    public bool $showResolveBlockerModal = false;
    public ?int $selectedBlockerId = null;
    public string $blockerResolutionInput = '';

    // Add Risk State
    public bool $showAddRiskModal = false;
    public ?int $riskProjectId = null;
    public string $riskTitle = '';
    public string $riskCategory = 'Technical';
    public string $riskProbability = 'medium';
    public string $riskImpact = 'medium';
    public ?int $riskOwnerId = null;
    public string $riskMitigation = '';

    // Reassign Project Leader Modal State
    public bool $showReassignModal = false;
    public ?int $reassignProjectId = null;
    public ?int $newLeaderId = null;

    public function openReassignModal(int $projectId): void
    {
        $this->reassignProjectId = $projectId;
        $project = Project::find($projectId);
        $this->newLeaderId = $project?->project_manager_id;
        $this->showReassignModal = true;
    }

    public function submitReassign(): void
    {
        $this->validate([
            'reassignProjectId' => 'required|exists:projects,id',
            'newLeaderId' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($this->reassignProjectId);
        $project->update([
            'project_manager_id' => $this->newLeaderId,
            'pm_accepted' => false,
            'pm_accepted_at' => null,
            'pm_rejection_reason' => null,
            'pm_rejected_at' => null,
        ]);

        $project->members()->syncWithoutDetaching([
            $this->newLeaderId => ['role' => 'lead']
        ]);

        $newLeader = User::find($this->newLeaderId);
        if ($newLeader) {
            $newLeader->notify(new \App\Notifications\ProjectAssignmentNotification($project, 'lead'));
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reassigned_project_leader',
            'module' => 'projects',
            'record_type' => Project::class,
            'record_id' => $project->id,
            'new_values' => [
                'project_name' => $project->name,
                'new_leader_id' => $this->newLeaderId,
                'new_leader_name' => $newLeader?->name,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->showReassignModal = false;
        $this->reassignProjectId = null;
        $this->dispatch('toast', message: "Project reassigned to {$newLeader->name}. Acceptance notification sent.", type: 'success');
    }

    public function setTrackerLayout(string $layout): void
    {
        $this->trackerLayout = $layout;
    }

    public function resetTrackerFilters(): void
    {
        $this->trackerSearch = '';
        $this->trackerSubsidiaryFilter = 'all';
        $this->trackerStatusFilter = 'all';
    }

    public function setDashboardTab(string $tab): void
    {
        $this->dashboardTab = $tab;
    }

    public function approveRequest(int $requestId, ?string $comment = null): void
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $req = ApprovalRequest::findOrFail($requestId);
        (new ApprovalService())->processApproval($req, auth()->user(), true, $comment ?: 'Approved from PMO Dashboard');
        $this->showReviewModal = false;
        $this->reviewComment = '';
        $this->dispatch('toast', message: '🎉 Request approved successfully!', type: 'success');
    }

    public function rejectRequest(int $requestId, ?string $comment = null): void
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $req = ApprovalRequest::findOrFail($requestId);
        (new ApprovalService())->processApproval($req, auth()->user(), false, $comment ?: 'Rejected from PMO Dashboard');
        $this->showReviewModal = false;
        $this->reviewComment = '';
        $this->dispatch('toast', message: 'Request rejected.', type: 'info');
    }

    public function openReviewModal(int $requestId): void
    {
        $this->selectedRequestId = $requestId;
        $this->reviewComment = '';
        $this->showReviewModal = true;
    }

    public function submitReview(bool $approved): void
    {
        if ($this->selectedRequestId) {
            if ($approved) {
                $this->approveRequest($this->selectedRequestId, $this->reviewComment);
            } else {
                $this->rejectRequest($this->selectedRequestId, $this->reviewComment);
            }
        }
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

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'resolved_blocker',
            'module' => 'wbs',
            'record_type' => TaskBlocker::class,
            'record_id' => $blocker->id,
            'new_values' => [
                'resolution' => trim($this->blockerResolutionInput),
                'task_title' => $blocker->wbsItem?->title,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->showResolveBlockerModal = false;
        $this->selectedBlockerId = null;
        $this->blockerResolutionInput = '';
        $this->dispatch('toast', message: '✓ Blocker marked as resolved!', type: 'success');
    }

    public function openAddRiskModal(): void
    {
        $this->riskProjectId = Project::first()?->id;
        $this->riskTitle = '';
        $this->riskCategory = 'Technical';
        $this->riskProbability = 'medium';
        $this->riskImpact = 'medium';
        $this->riskOwnerId = auth()->id();
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
            'owner_id' => $this->riskOwnerId ?: auth()->id(),
            'mitigation_plan' => trim($this->riskMitigation),
            'status' => 'open',
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'created_risk',
            'module' => 'projects',
            'record_type' => ProjectRisk::class,
            'record_id' => $risk->id,
            'new_values' => [
                'title' => $risk->title,
                'category' => $risk->category,
                'risk_score' => $risk->risk_score,
                'project_id' => $risk->project_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->showAddRiskModal = false;
        $this->dispatch('toast', message: 'Risk successfully logged to project!', type: 'success');
    }

    public function updateRiskStatus(int $riskId, string $status): void
    {
        $risk = ProjectRisk::findOrFail($riskId);
        $risk->update(['status' => $status]);
        $this->dispatch('toast', message: 'Risk status updated.', type: 'info');
    }

    public function render()
    {
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $overdueProjects = Project::where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $onHoldProjects = Project::where('status', 'on_hold')->count();
        $planningProjects = Project::where('status', 'planning')->count();
        $notStartedProjects = Project::whereIn('status', ['draft', 'not_started'])->count();
        $totalSubsidiaries = Subsidiary::count();
        $totalUsers = User::count();
        $pendingApprovals = ApprovalRequest::where('status', ApprovalStatus::PENDING)->count();

        // Total Tasks & Overdue Tasks
        $totalTasksCount = WbsItem::count();
        $completedTasksCount = WbsItem::where('status', 'completed')->count();
        $inProgressTasksCount = WbsItem::where('status', 'in_progress')->count();
        $overdueTasksCount = WbsItem::where('end_date', '<', now()->today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        
        $upcomingDeadlinesCount = WbsItem::where('end_date', '>=', now()->today())
            ->where('end_date', '<=', now()->today()->addDays(7))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        
        // Overall progress / budget utilization rate
        $avgProgress = Project::whereNotIn('status', ['cancelled'])->avg('overall_progress');
        $budgetUtilization = $avgProgress ? (int) round($avgProgress) : ($totalTasksCount > 0 ? (int) round(($completedTasksCount / $totalTasksCount) * 100) : 0);

        // Real-time Trends (New entries within the last 30 days)
        $subsTrendCount = Subsidiary::where('created_at', '>=', now()->subDays(30))->count();
        $projTrendCount = Project::where('created_at', '>=', now()->subDays(30))->count();
        $tasksTrendCount = WbsItem::where('created_at', '>=', now()->subDays(30))->count();

        // Project Health Summary (100% Real-time database metrics)
        $delayedHealthCount = Project::where('health', 'delayed')
            ->orWhere(fn($q) => $q->where('deadline', '<', now())->whereNotIn('status', ['completed', 'cancelled']))
            ->count();
        $atRiskHealthCount = Project::where('health', 'at_risk')->count();
        $onTrackHealthCount = max(0, $totalProjects - ($delayedHealthCount + $atRiskHealthCount + $notStartedProjects));
        
        $healthSummary = [
            'total' => $totalProjects,
            'on_track' => [
                'count' => $onTrackHealthCount,
                'pct' => $totalProjects > 0 ? (int) round(($onTrackHealthCount / $totalProjects) * 100) : 0,
            ],
            'at_risk' => [
                'count' => $atRiskHealthCount,
                'pct' => $totalProjects > 0 ? (int) round(($atRiskHealthCount / $totalProjects) * 100) : 0,
            ],
            'delayed' => [
                'count' => $delayedHealthCount,
                'pct' => $totalProjects > 0 ? (int) round(($delayedHealthCount / $totalProjects) * 100) : 0,
            ],
            'not_started' => [
                'count' => $notStartedProjects,
                'pct' => $totalProjects > 0 ? (int) round(($notStartedProjects / $totalProjects) * 100) : 0,
            ],
        ];

        // Status Chart Data
        $statusChartData = [
            'not_started' => $notStartedProjects,
            'planning' => $planningProjects,
            'in_progress' => $activeProjects,
            'on_hold' => $onHoldProjects,
            'completed' => $completedProjects,
        ];
        $maxRawVal = max(1, max(array_values($statusChartData)));
        $maxStatusVal = max(5, (int)(ceil($maxRawVal / 5) * 5));

        // Projects Due Soon (Real projects sorted by deadline)
        $projectsDueSoon = Project::with('subsidiary')
            ->whereNotNull('deadline')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('deadline', 'asc')
            ->take(4)
            ->get();

        // 5 Key Overview Projects for Middle-Right Box
        $overviewProjects = Project::with('subsidiary')
            ->whereNotIn('status', ['cancelled'])
            ->orderByRaw("CASE 
                WHEN status = 'in_progress' THEN 1 
                WHEN status = 'planning' THEN 2 
                WHEN status = 'on_hold' THEN 3 
                WHEN status = 'completed' THEN 4 
                ELSE 5 END")
            ->latest('updated_at')
            ->take(5)
            ->get();

        // Top Subsidiaries Overview Table
        $topSubsidiaries = Subsidiary::withCount([
            'projects',
            'projects as in_progress_count' => fn($q) => $q->where('status', 'in_progress'),
            'projects as completed_count' => fn($q) => $q->where('status', 'completed'),
        ])
        ->orderByDesc('projects_count')
        ->take(5)
        ->get();

        // Upcoming Deadlines (Tasks & Milestones) for Bottom-Left Box
        $upcomingTasks = WbsItem::with('project')
            ->whereNotNull('end_date')
            ->where('end_date', '>=', now()->today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('end_date', 'asc')
            ->take(3)
            ->get();

        if ($upcomingTasks->isEmpty()) {
            $upcomingTasks = WbsItem::with('project')
                ->whereNotNull('end_date')
                ->orderBy('end_date', 'desc')
                ->take(3)
                ->get();
        }

        // Real Activity Logs & Updates
        $recentUpdates = ProjectStatusUpdate::with(['project', 'creator'])
            ->latest()
            ->take(4)
            ->get();

        $latestUpdate = ProjectStatusUpdate::with(['project', 'creator'])
            ->latest()
            ->first();

        $recentActivityLogs = ActivityLog::with('user')
            ->latest()
            ->take(4)
            ->get();

        $pendingApprovalList = ApprovalRequest::with(['project.subsidiary', 'requester'])
            ->where('status', ApprovalStatus::PENDING)
            ->latest()
            ->take(4)
            ->get();

        // Tracker Query for search & filter
        $trackerQuery = Project::with([
            'subsidiary',
            'projectManager',
            'members',
            'statusUpdates' => fn($q) => $q->latest()->take(1),
            'wbsItems' => fn($q) => $q->whereNotIn('status', ['cancelled']),
        ]);

        if ($this->trackerSearch) {
            $s = '%' . $this->trackerSearch . '%';
            $trackerQuery->where(function($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('code', 'like', $s)
                  ->orWhereHas('projectManager', fn($pm) => $pm->where('name', 'like', $s))
                  ->orWhereHas('subsidiary', fn($sub) => $sub->where('name', 'like', $s));
            });
        }

        if ($this->trackerSubsidiaryFilter !== 'all') {
            $trackerQuery->where('subsidiary_id', $this->trackerSubsidiaryFilter);
        }

        if ($this->trackerStatusFilter !== 'all') {
            $trackerQuery->where('status', $this->trackerStatusFilter);
        }

        $dailyProjectTracker = $trackerQuery
            ->latest()
            ->get()
        ->map(function($project) {
            $latestUpdate = $project->statusUpdates->first();
            $tasksCount = $project->wbsItems->count();
            $completedTasks = $project->wbsItems->where('status', 'completed')->count();
            $blockedTasks = $project->wbsItems->where('status', 'blocked')->count();
            $overdueTasks = $project->wbsItems->filter(fn($t) => $t->end_date && $t->end_date->lt(now()->today()) && !in_array($t->status->value, ['completed', 'cancelled']))->count();

            return [
                'project' => $project,
                'latestUpdate' => $latestUpdate,
                'tasksCount' => $tasksCount,
                'completedTasks' => $completedTasks,
                'blockedTasks' => $blockedTasks,
                'overdueTasks' => $overdueTasks,
                'isUpdatedToday' => $latestUpdate && $latestUpdate->created_at->isToday(),
            ];
        })
        ->sortByDesc(function($item) {
            return $item['latestUpdate'] ? $item['latestUpdate']->created_at->timestamp : $item['project']->created_at->timestamp;
        })
        ->values();

        $recentDocuments = ProjectDocument::with(['project', 'uploader'])
            ->latest()
            ->take(5)
            ->get();

        $delayedTaskIssues = WbsItem::with(['project', 'assignedUser', 'delayReporter'])
            ->whereNotNull('delay_reason')
            ->latest('delay_reason_at')
            ->take(6)
            ->get();

        // ══════════════════════════════════════════════════════════
        // 🛡️ DEDICATED APPROVALS HUB DATA (Full Integrated View)
        // ══════════════════════════════════════════════════════════
        $approvalQuery = ApprovalRequest::with(['project.subsidiary', 'requester', 'approver']);
        if ($this->approvalStatusFilter !== 'all') {
            $approvalQuery->where('status', $this->approvalStatusFilter);
        }
        if ($this->approvalTypeFilter !== 'all') {
            $approvalQuery->where('request_type', $this->approvalTypeFilter);
        }
        $allApprovals = $approvalQuery->latest()->get();
        $approvedCount = ApprovalRequest::where('status', ApprovalStatus::APPROVED)->count();
        $rejectedCount = ApprovalRequest::where('status', ApprovalStatus::REJECTED)->count();

        // ══════════════════════════════════════════════════════════
        // ⚠️ RISKS & BLOCKERS CENTER DATA (Full Integrated View)
        // ══════════════════════════════════════════════════════════
        $allRisks = ProjectRisk::with(['project.subsidiary', 'owner'])->latest()->get();
        $highRisksCount = $allRisks->where('risk_score', '>=', 6)->where('status', 'open')->count();
        $openRisksCount = $allRisks->where('status', 'open')->count();

        $allBlockers = TaskBlocker::with(['wbsItem.project', 'reporter', 'wbsItem.assignedUser'])
            ->latest()
            ->get();
        $activeBlockersCount = $allBlockers->where('status', 'open')->count();

        $declinedProjects = Project::whereNotNull('pm_rejection_reason')
            ->with(['subsidiary', 'projectManager'])
            ->latest('pm_rejected_at')
            ->get();

        $reassignProject = $this->reassignProjectId ? Project::with(['subsidiary', 'projectManager'])->find($this->reassignProjectId) : null;

        $allProjectsList = Project::orderBy('name')->get();
        $allUsersList = User::orderBy('name')->get();
        $allSubsidiaries = Subsidiary::orderBy('name')->get();

        return view('livewire.super-admin-dashboard', compact(
            'declinedProjects',
            'reassignProject',
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'overdueProjects',
            'onHoldProjects',
            'planningProjects',
            'notStartedProjects',
            'totalSubsidiaries',
            'totalUsers',
            'pendingApprovals',
            'totalTasksCount',
            'completedTasksCount',
            'overdueTasksCount',
            'budgetUtilization',
            'subsTrendCount',
            'projTrendCount',
            'tasksTrendCount',
            'healthSummary',
            'statusChartData',
            'maxStatusVal',
            'projectsDueSoon',
            'topSubsidiaries',
            'recentUpdates',
            'recentActivityLogs',
            'pendingApprovalList',
            'recentDocuments',
            'delayedTaskIssues',
            'dailyProjectTracker',
            'allApprovals',
            'approvedCount',
            'rejectedCount',
            'allRisks',
            'highRisksCount',
            'openRisksCount',
            'allBlockers',
            'activeBlockersCount',
            'allProjectsList',
            'allUsersList',
            'allSubsidiaries',
            'inProgressTasksCount',
            'upcomingDeadlinesCount',
            'overviewProjects',
            'upcomingTasks',
            'latestUpdate'
        ));
    }
}
