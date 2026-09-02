<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\Subsidiary;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use App\Notifications\GenericSystemNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectMonitor extends Component
{
    use WithPagination;

    // Filters & Search
    public string $search = '';
    public string $quickSegment = 'all'; // 'all', 'critical', 'at_risk', 'on_track', 'pending_pm', 'completed'
    public string $subsidiaryFilter = 'all';
    public string $healthFilter = 'all'; // 'all', 'delayed', 'at_risk', 'on_track', 'completed'
    public string $statusFilter = 'all'; // 'all', 'in_progress', 'planning', 'on_hold', 'completed'
    public string $pmFilter = 'all';
    public string $deadlineFilter = 'all'; // 'all', 'overdue', 'this_week', 'this_month', 'future'
    public string $priorityFilter = 'all';
    public string $sortBy = 'urgency'; // 'urgency', 'health', 'progress_asc', 'progress_desc', 'budget', 'name'
    public string $viewMode = 'table'; // 'matrix' (cards), 'table' (executive table), 'tasks' (master tasks monitor), 'gantt' (portfolio timeline), 'stuck' (stuck & blocked tasks radar)
    public string $ganttTimeframe = '6m'; // '3m', '6m', '12m'
    public array $expandedGanttProjectIds = [];
    public string $stuckTypeFilter = 'all'; // 'all', 'blocked', 'overdue', 'delay_reported', 'on_hold'
    public string $stuckProjectFilter = 'all';
    public string $stuckAssigneeFilter = 'all';
    public string $stuckReasonFilter = 'all';
    public string $stuckPriorityFilter = 'all';
    public int $stuckMinDays = 0;

    public function resetStuckFilters(): void
    {
        $this->search = '';
        $this->stuckTypeFilter = 'all';
        $this->stuckProjectFilter = 'all';
        $this->stuckAssigneeFilter = 'all';
        $this->stuckReasonFilter = 'all';
        $this->stuckPriorityFilter = 'all';
        $this->stuckMinDays = 0;
        $this->resetPage();
    }

    // Task Monitor Specific Properties
    public string $taskAssigneeFilter = 'all';
    public string $taskProjectFilter = 'all';
    public string $taskStatusFilter = 'all';
    public string $taskDueDateFilter = 'all';
    public string $taskGroupBy = 'user'; // 'user', 'project', 'date', 'flat'
    public array $expandedUserIds = [];
    public array $expandedProjectTaskIds = [];
    public array $expandedDateKeys = [];

    // Pagination
    public int $perPage = 12;

    // Modals & Actions
    public bool $showNudgeModal = false;
    public ?int $nudgeProjectId = null;
    public string $nudgeMessage = '';
    public string $nudgeUrgency = 'normal'; // 'normal', 'urgent'

    public bool $showDetailModal = false;
    public ?int $detailProjectId = null;

    public bool $showBlockersModal = false;
    public ?int $blockersProjectId = null;

    public bool $showWbsDrawer = false;
    public ?int $wbsDrawerProjectId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'quickSegment' => ['except' => 'all'],
        'subsidiaryFilter' => ['except' => 'all'],
        'healthFilter' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
        'pmFilter' => ['except' => 'all'],
        'deadlineFilter' => ['except' => 'all'],
        'priorityFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'urgency'],
        'viewMode' => ['except' => 'table'],
        'stuckTypeFilter' => ['except' => 'all'],
        'taskAssigneeFilter' => ['except' => 'all'],
        'taskProjectFilter' => ['except' => 'all'],
        'taskStatusFilter' => ['except' => 'all'],
        'taskDueDateFilter' => ['except' => 'all'],
        'taskGroupBy' => ['except' => 'user'],
    ];

    public function setQuickSegment(string $segment): void
    {
        $this->quickSegment = $segment;
        $this->resetPage();
    }

    public function setStuckTypeFilter(string $type): void
    {
        $this->stuckTypeFilter = $type;
        $this->resetPage();
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSubsidiaryFilter(): void { $this->resetPage(); }
    public function updatingHealthFilter(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingPmFilter(): void { $this->resetPage(); }
    public function updatingDeadlineFilter(): void { $this->resetPage(); }
    public function updatingPriorityFilter(): void { $this->resetPage(); }
    public function updatingSortBy(): void { $this->resetPage(); }
    public function updatingTaskAssigneeFilter(): void { $this->resetPage(); }
    public function updatingTaskProjectFilter(): void { $this->resetPage(); }
    public function updatingTaskStatusFilter(): void { $this->resetPage(); }
    public function updatingTaskDueDateFilter(): void { $this->resetPage(); }
    public function updatingTaskGroupBy(): void { $this->resetPage(); }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'gantt', 'stuck']) ? $mode : 'table';
    }

    public function setTaskGroupBy(string $gb): void
    {
        $this->taskGroupBy = in_array($gb, ['user', 'project', 'date', 'flat']) ? $gb : 'user';
    }

    public function setTaskDueDateFilter(string $dd): void
    {
        $this->taskDueDateFilter = $dd;
    }

    public function setTaskStatusFilter(string $st): void
    {
        $this->taskStatusFilter = $st;
    }

    public function toggleUserExpand(int $userId): void
    {
        if (in_array($userId, $this->expandedUserIds)) {
            $this->expandedUserIds = array_values(array_diff($this->expandedUserIds, [$userId]));
        } else {
            $this->expandedUserIds[] = $userId;
        }
    }

    public function toggleUserCollapse(int $userId): void
    {
        $this->toggleUserExpand($userId);
    }

    public function toggleProjectTaskExpand(int $projectId): void
    {
        if (in_array($projectId, $this->expandedProjectTaskIds)) {
            $this->expandedProjectTaskIds = array_values(array_diff($this->expandedProjectTaskIds, [$projectId]));
        } else {
            $this->expandedProjectTaskIds[] = $projectId;
        }
    }

    public function toggleProjectTaskCollapse(int $projectId): void
    {
        $this->toggleProjectTaskExpand($projectId);
    }

    public function toggleDateExpand(string $dateKey): void
    {
        if (in_array($dateKey, $this->expandedDateKeys)) {
            $this->expandedDateKeys = array_values(array_diff($this->expandedDateKeys, [$dateKey]));
        } else {
            $this->expandedDateKeys[] = $dateKey;
        }
    }

    public function expandAllTaskGroups(array $allIds = []): void
    {
        if ($this->taskGroupBy === 'user') {
            $this->expandedUserIds = $allIds;
        } elseif ($this->taskGroupBy === 'project') {
            $this->expandedProjectTaskIds = $allIds;
        } else {
            $this->expandedDateKeys = ['overdue', 'today', 'tomorrow', 'this_week', 'completed'];
        }
    }

    public function collapseAllTaskGroups(): void
    {
        $this->expandedUserIds = [];
        $this->expandedProjectTaskIds = [];
        $this->expandedDateKeys = [];
    }

    public function setGanttTimeframe(string $tf): void
    {
        $this->ganttTimeframe = in_array($tf, ['3m', '6m', '12m']) ? $tf : '6m';
    }

    public function toggleGanttProjectExpand(int $projectId): void
    {
        if (in_array($projectId, $this->expandedGanttProjectIds)) {
            $this->expandedGanttProjectIds = array_values(array_diff($this->expandedGanttProjectIds, [$projectId]));
        } else {
            $this->expandedGanttProjectIds[] = $projectId;
        }
    }

    public function expandAllGanttProjects(array $allIds = []): void
    {
        $this->expandedGanttProjectIds = $allIds;
    }

    public function collapseAllGanttProjects(): void
    {
        $this->expandedGanttProjectIds = [];
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->subsidiaryFilter = 'all';
        $this->healthFilter = 'all';
        $this->statusFilter = 'all';
        $this->pmFilter = 'all';
        $this->deadlineFilter = 'all';
        $this->priorityFilter = 'all';
        $this->taskAssigneeFilter = 'all';
        $this->taskProjectFilter = 'all';
        $this->taskStatusFilter = 'all';
        $this->taskDueDateFilter = 'all';
        $this->sortBy = 'urgency';
        $this->resetPage();
    }

    // ═══════════════════════════════════════════════════════════
    // 🔔 NUDGE / PM ALERT MODAL ACTIONS
    // ═══════════════════════════════════════════════════════════
    public function openNudgeModal(int $projectId): void
    {
        $project = Project::with('projectManager')->findOrFail($projectId);
        $this->nudgeProjectId = $project->id;
        $this->nudgeMessage = "PMO Governance Alert: Please review the delivery schedule, pending deliverables, and active blockers for project [{$project->code}] {$project->name}.";
        $this->nudgeUrgency = 'urgent';
        $this->showNudgeModal = true;
    }

    public function sendNudge(): void
    {
        $this->validate([
            'nudgeMessage' => 'required|string|min:5|max:1000',
            'nudgeUrgency' => 'required|in:normal,urgent',
        ]);

        $project = Project::with(['projectManager', 'subsidiary'])->findOrFail($this->nudgeProjectId);
        $sender = Auth::user();

        if ($project->projectManager) {
            $project->projectManager->notify(new GenericSystemNotification(
                title: ($this->nudgeUrgency === 'urgent' ? '🚨 URGENT: ' : '🔔 ') . "PMO Governance Alert — {$project->code}",
                message: $this->nudgeMessage,
                actionUrl: route('projects.show', $project->id),
                actionText: 'View Project Workspace',
                type: $this->nudgeUrgency === 'urgent' ? 'danger' : 'warning',
                icon: 'bell'
            ));

            ActivityLog::create([
                'user_id' => $sender->id,
                'action' => 'pmo_nudge_dispatched',
                'module' => 'projects',
                'record_type' => Project::class,
                'record_id' => $project->id,
                'new_values' => [
                    'recipient' => $project->projectManager->name,
                    'message' => $this->nudgeMessage,
                    'urgency' => $this->nudgeUrgency,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success_message', "Governance nudge successfully dispatched to PM ({$project->projectManager->name}).");
        } else {
            session()->flash('error_message', "No Project Manager is currently assigned to this project.");
        }

        $this->showNudgeModal = false;
        $this->nudgeProjectId = null;
        $this->nudgeMessage = '';
    }

    public function quickPingPm(int $projectId): void
    {
        $project = Project::with('projectManager')->findOrFail($projectId);
        $sender = Auth::user();

        if ($project->projectManager) {
            $project->projectManager->notify(new GenericSystemNotification(
                title: "🔔 Quick PMO Ping — {$project->code}",
                message: "PMO Governance Reminder: Please ensure all deliverables, milestone schedules, and task blockers for [{$project->code}] {$project->name} are up-to-date.",
                actionUrl: route('projects.show', $project->id),
                actionText: 'Update Project Workspace',
                type: 'warning',
                icon: 'bell'
            ));

            ActivityLog::create([
                'user_id' => $sender->id,
                'action' => 'pmo_quick_ping_dispatched',
                'module' => 'projects',
                'record_type' => Project::class,
                'record_id' => $project->id,
                'new_values' => [
                    'recipient' => $project->projectManager->name,
                    'action' => 'quick_ping',
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success_message', "⚡ Instant PM reminder sent to {$project->projectManager->name}!");
        } else {
            session()->flash('error_message', "No Project Manager is currently assigned to this project.");
        }
    }

    public function openWbsDrawer(int $projectId): void
    {
        $this->wbsDrawerProjectId = $projectId;
        $this->showWbsDrawer = true;
    }

    public function closeWbsDrawer(): void
    {
        $this->showWbsDrawer = false;
        $this->wbsDrawerProjectId = null;
    }

    public function openBlockersModal(int $projectId): void
    {
        $this->blockersProjectId = $projectId;
        $this->showBlockersModal = true;
    }

    public function closeBlockersModal(): void
    {
        $this->showBlockersModal = false;
        $this->blockersProjectId = null;
    }

    public function nudgeStuckUser(int $wbsItemId): void
    {
        $item = WbsItem::with(['project.projectManager', 'assignedUser'])->findOrFail($wbsItemId);
        $sender = Auth::user();

        // 1. Notify Assigned User if exists
        if ($item->assignedUser) {
            $item->assignedUser->notify(new GenericSystemNotification(
                title: "🚨 PMO Support Alert: {$item->title}",
                message: "PMO Governance Notice: You are assigned to deliverable [{$item->title}] in project {$item->project->code}. It is currently flagged as blocked or overdue. Please update your progress, report resolution, or contact your PM if assistance is needed.",
                actionUrl: route('projects.show', $item->project_id),
                actionText: 'Update Task Status',
                type: 'danger',
                icon: 'exclamation-circle'
            ));
        }

        // 2. Also notify PM
        if ($item->project?->projectManager && $item->project->project_manager_id !== $item->assigned_user_id) {
            $item->project->projectManager->notify(new GenericSystemNotification(
                title: "⚠️ PMO Escalation: Stuck Task in {$item->project->code}",
                message: "PMO Escalation: Task [{$item->title}] assigned to " . ($item->assignedUser->name ?? 'Unassigned') . " requires leadership intervention to unblock.",
                actionUrl: route('projects.show', $item->project_id),
                actionText: 'Review Blocker in Workspace',
                type: 'warning',
                icon: 'bell'
            ));
        }

        ActivityLog::create([
            'user_id' => $sender->id,
            'action' => 'pmo_stuck_task_nudge',
            'module' => 'wbs_items',
            'record_type' => WbsItem::class,
            'record_id' => $item->id,
            'new_values' => [
                'task_title' => $item->title,
                'assigned_user' => $item->assignedUser?->name,
                'project' => $item->project?->code,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success_message', "🚨 PMO alert & unblock assistance dispatched to " . ($item->assignedUser->name ?? 'the Project Manager') . "!");
    }

    public function nudgeTaskAssignee(int $wbsItemId): void
    {
        $item = WbsItem::with(['project.projectManager', 'assignedUser'])->findOrFail($wbsItemId);
        $sender = Auth::user();

        if ($item->assignedUser) {
            $item->assignedUser->notify(new GenericSystemNotification(
                title: "🔔 PMO Task Notice: {$item->title}",
                message: "PMO Governance Notice: Please update your progress and schedule status for deliverable [{$item->title}] in project {$item->project->code}.",
                actionUrl: route('projects.show', $item->project_id),
                actionText: 'View Task in Project Workspace',
                type: 'warning',
                icon: 'bell'
            ));

            ActivityLog::create([
                'user_id' => $sender->id,
                'action' => 'pmo_task_nudge',
                'module' => 'wbs_items',
                'record_type' => WbsItem::class,
                'record_id' => $item->id,
                'new_values' => [
                    'task_title' => $item->title,
                    'assigned_user' => $item->assignedUser->name,
                    'project' => $item->project?->code,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success_message', "🔔 PMO reminder dispatched to " . $item->assignedUser->name . " for task [{$item->title}]!");
        } else {
            session()->flash('error_message', "This task is currently unassigned.");
        }
    }

    public function updateTaskStatus(int $wbsItemId, string $newStatus): void
    {
        $item = WbsItem::findOrFail($wbsItemId);
        if (!\App\Enums\WbsStatus::tryFrom($newStatus)) {
            return;
        }
        $oldStatus = $item->status;
        $item->status = \App\Enums\WbsStatus::from($newStatus);
        if ($newStatus === 'completed') {
            $item->progress = 100;
        }
        $item->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'pmo_task_status_updated',
            'module' => 'wbs_items',
            'record_type' => WbsItem::class,
            'record_id' => $item->id,
            'old_values' => ['status' => $oldStatus->value],
            'new_values' => ['status' => $newStatus],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success_message', "Task status updated to " . $item->status->label() . ".");
    }

    public function render()
    {
        $today = now()->startOfDay();

        // 1. Base Portfolio Query for Overall Executive KPIs
        $allProjectsQuery = Project::with([
            'subsidiary',
            'projectManager',
            'wbsItems',
            'risks',
        ]);

        $allProjects = $allProjectsQuery->get();

        // Compute Live Portfolio Diagnostics for each project
        $enhancedProjects = $allProjects->map(function ($project) use ($today) {
            $wbs = $project->wbsItems;
            $totalTasks = $wbs->count();
            $completedTasks = $wbs->where('status.value', 'completed')->count();
            $inProgressTasks = $wbs->where('status.value', 'in_progress')->count();
            
            // Overdue Tasks count (end_date < today and not completed/cancelled)
            $overdueTasks = $wbs->filter(function($t) use ($today) {
                if ($t->status->value === 'completed' || $t->status->value === 'cancelled') return false;
                return $t->end_date && $t->end_date->lt($today);
            })->count();

            // Blocked tasks count
            $blockedTasks = $wbs->filter(function($t) {
                return $t->status->value === 'blocked';
            })->count();

            // Total active open risks
            $openRisksCount = $project->risks ? $project->risks->where('status', 'open')->count() : 0;
            $criticalRisksCount = $project->risks ? $project->risks->where('status', 'open')->where('severity', 'high')->count() : 0;

            // Schedule & Time Elapsed Calculation
            $startDate = $project->start_date ? $project->start_date->copy()->startOfDay() : null;
            $deadline = $project->deadline ? $project->deadline->copy()->endOfDay() : null;
            
            $timeElapsedPct = 0;
            $daysRemaining = null;
            $isPastDeadline = false;

            if ($startDate && $deadline) {
                $totalDays = max(1, $startDate->diffInDays($deadline));
                $daysPassed = $startDate->gt($today) ? 0 : $startDate->diffInDays($today);
                $timeElapsedPct = min(100, max(0, (int) round(($daysPassed / $totalDays) * 100)));
                
                $daysRemaining = (int) $today->diffInDays($deadline, false);
                $isPastDeadline = $deadline->lt($today) && $project->overall_progress < 100;
            } elseif ($deadline) {
                $daysRemaining = (int) $today->diffInDays($deadline, false);
                $isPastDeadline = $deadline->lt($today) && $project->overall_progress < 100;
            }

            // Progress vs Schedule Delta
            $progressDelta = $project->overall_progress - $timeElapsedPct; // Negative means behind schedule!

            // Accurate Dynamic RAG Health Computation
            $computedHealth = 'on_track'; // 'on_track' (green), 'at_risk' (yellow), 'delayed' (red)
            if ($project->status->value === 'completed') {
                $computedHealth = 'completed';
            } elseif ($isPastDeadline || $overdueTasks > 0 || $progressDelta <= -25 || $project->health->value === 'critical' || $project->isPmRejected()) {
                $computedHealth = 'delayed';
            } elseif ($progressDelta <= -10 || $blockedTasks > 0 || $criticalRisksCount > 0 || $project->health->value === 'at_risk' || $project->isPendingPmAcceptance()) {
                $computedHealth = 'at_risk';
            }

            $project->computed_health = $computedHealth;
            $project->total_tasks_count = $totalTasks;
            $project->completed_tasks_count = $completedTasks;
            $project->in_progress_tasks_count = $inProgressTasks;
            $project->overdue_tasks_count = $overdueTasks;
            $project->blocked_tasks_count = $blockedTasks;
            $project->open_risks_count = $openRisksCount;
            $project->critical_risks_count = $criticalRisksCount;
            $project->time_elapsed_pct = $timeElapsedPct;
            $project->days_remaining = $daysRemaining;
            $project->is_past_deadline = $isPastDeadline;
            $project->progress_delta = $progressDelta;

            return $project;
        });

        // ═══════════════════════════════════════════════════════════
        // 📊 OVERALL PORTFOLIO EXECUTIVE METRICS
        // ═══════════════════════════════════════════════════════════
        $totalProjectsCount = $enhancedProjects->count();
        $activeProjectsCount = $enhancedProjects->where('status.value', '!=', 'completed')->where('status.value', '!=', 'cancelled')->count();
        $completedProjectsCount = $enhancedProjects->where('status.value', 'completed')->count();

        $onTrackCount = $enhancedProjects->where('computed_health', 'on_track')->count();
        $atRiskCount = $enhancedProjects->where('computed_health', 'at_risk')->count();
        $delayedCount = $enhancedProjects->where('computed_health', 'delayed')->count();
        $onHoldCount = $enhancedProjects->where('status.value', 'on_hold')->count();
        $atRiskOrBlockedCount = $enhancedProjects->filter(function($p) {
            return $p->computed_health === 'at_risk' || $p->blocked_tasks_count > 0;
        })->count();

        $totalEstimatedBudget = $enhancedProjects->sum('estimated_budget');
        $totalActualCost = $enhancedProjects->sum('actual_cost');
        
        $avgProgress = $totalProjectsCount > 0 
            ? (int) round($enhancedProjects->avg('overall_progress')) 
            : 0;

        $totalPortfolioBlockers = $enhancedProjects->sum('blocked_tasks_count');
        $totalPortfolioOverdue = $enhancedProjects->sum('overdue_tasks_count');
        $totalPortfolioRisks = $enhancedProjects->sum('open_risks_count');

        $pendingPmCount = $enhancedProjects->filter(fn($p) => $p->isPendingPmAcceptance())->count();

        // ═══════════════════════════════════════════════════════════
        // 🔍 FILTERED & SORTED COLLECTION FOR DISPLAY
        // ═══════════════════════════════════════════════════════════
        $filteredProjects = $enhancedProjects->filter(function($p) {
            // Quick Segment Filter (Pill Switcher)
            if ($this->quickSegment === 'critical') {
                if ($p->computed_health !== 'delayed') return false;
            } elseif ($this->quickSegment === 'at_risk') {
                if ($p->computed_health !== 'at_risk') return false;
            } elseif ($this->quickSegment === 'on_track') {
                if ($p->computed_health !== 'on_track') return false;
            } elseif ($this->quickSegment === 'pending_pm') {
                if (!$p->isPendingPmAcceptance()) return false;
            } elseif ($this->quickSegment === 'completed') {
                if ($p->status->value !== 'completed') return false;
            }

            // Search Query
            if ($this->search) {
                $term = strtolower($this->search);
                $matchCode = str_contains(strtolower($p->code ?? ''), $term);
                $matchName = str_contains(strtolower($p->name ?? ''), $term);
                $matchPm = str_contains(strtolower($p->projectManager->name ?? ''), $term);
                $matchSub = str_contains(strtolower($p->subsidiary->name ?? ''), $term);
                if (!$matchCode && !$matchName && !$matchPm && !$matchSub) {
                    return false;
                }
            }

            // Subsidiary Filter
            if ($this->subsidiaryFilter !== 'all') {
                if ((string)$p->subsidiary_id !== $this->subsidiaryFilter) return false;
            }

            // Health Filter
            if ($this->healthFilter !== 'all') {
                if ($p->computed_health !== $this->healthFilter) return false;
            }

            // Status Filter
            if ($this->statusFilter !== 'all') {
                if ($p->status->value !== $this->statusFilter) return false;
            }

            // PM Filter
            if ($this->pmFilter !== 'all') {
                if ((string)$p->project_manager_id !== $this->pmFilter) return false;
            }

            // Deadline Filter
            if ($this->deadlineFilter !== 'all') {
                if ($this->deadlineFilter === 'overdue') {
                    if (!$p->is_past_deadline) return false;
                } elseif ($this->deadlineFilter === 'this_week') {
                    if ($p->days_remaining === null || $p->days_remaining < 0 || $p->days_remaining > 7) return false;
                } elseif ($this->deadlineFilter === 'this_month') {
                    if ($p->days_remaining === null || $p->days_remaining < 0 || $p->days_remaining > 30) return false;
                } elseif ($this->deadlineFilter === 'future') {
                    if ($p->days_remaining === null || $p->days_remaining <= 30) return false;
                }
            }

            // Priority Filter
            if ($this->priorityFilter !== 'all') {
                if ($p->priority->value !== $this->priorityFilter) return false;
            }

            return true;
        });

        // Apply Sorting
        $sortedProjects = $filteredProjects->sort(function($a, $b) {
            if ($this->sortBy === 'urgency') {
                // Priority order: Delayed first, then At Risk, then On Track, then Completed
                $healthWeight = ['delayed' => 1, 'at_risk' => 2, 'on_track' => 3, 'completed' => 4];
                $wA = $healthWeight[$a->computed_health] ?? 5;
                $wB = $healthWeight[$b->computed_health] ?? 5;
                if ($wA !== $wB) return $wA <=> $wB;
                return ($a->days_remaining ?? 9999) <=> ($b->days_remaining ?? 9999);
            } elseif ($this->sortBy === 'health') {
                $healthWeight = ['delayed' => 1, 'at_risk' => 2, 'on_track' => 3, 'completed' => 4];
                return ($healthWeight[$a->computed_health] ?? 5) <=> ($healthWeight[$b->computed_health] ?? 5);
            } elseif ($this->sortBy === 'progress_asc') {
                return $a->overall_progress <=> $b->overall_progress;
            } elseif ($this->sortBy === 'progress_desc') {
                return $b->overall_progress <=> $a->overall_progress;
            } elseif ($this->sortBy === 'budget') {
                return $b->estimated_budget <=> $a->estimated_budget;
            } else {
                return strcasecmp($a->name, $b->name);
            }
        });

        // ═══════════════════════════════════════════════════════════
        // 🛑 ENTERPRISE STUCK & BLOCKED TASKS LIVE RADAR
        // ═══════════════════════════════════════════════════════════
        $allStuckTasksQuery = WbsItem::with(['project.subsidiary', 'project.projectManager', 'assignedUser', 'blockers.reporter'])
            ->whereHas('project', fn($q) => $q->whereNull('deleted_at'))
            ->where(function($q) use ($today) {
                $q->where('status', 'blocked')
                  ->orWhereNotNull('delay_reason')
                  ->orWhere(function($subQ) use ($today) {
                      $subQ->where('end_date', '<', $today)
                           ->whereNotIn('status', ['completed', 'cancelled']);
                  })
                  ->orWhereHas('blockers', fn($bq) => $bq->where('status', 'open'));
            });

        $totalStuckTasksCount = (clone $allStuckTasksQuery)->count();
        $blockedTasksOnlyCount = (clone $allStuckTasksQuery)->where('status', 'blocked')->count();
        $overdueTasksOnlyCount = (clone $allStuckTasksQuery)->where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count();
        $delayReportedCount = (clone $allStuckTasksQuery)->whereNotNull('delay_reason')->count();
        $onHoldTasksCount = (clone $allStuckTasksQuery)->where('status', 'on_hold')->count();
        if ($onHoldTasksCount === 0) {
            $onHoldTasksCount = (clone $allStuckTasksQuery)->whereHas('project', fn($pq) => $pq->where('status', 'on_hold'))->count();
        }

        $allRawStuck = $allStuckTasksQuery->get();
        $stuckProjectsList = $allRawStuck->pluck('project')->filter()->unique('id')->values();
        $stuckAssigneesList = $allRawStuck->pluck('assignedUser')->filter()->unique('id')->values();

        // Apply Stuck Type Filter & Search
        $stuckTasks = $allRawStuck->filter(function($task) use ($today) {
            if ($this->stuckTypeFilter === 'blocked' && $task->status->value !== 'blocked') return false;
            if ($this->stuckTypeFilter === 'overdue' && !($task->end_date && $task->end_date->lt($today) && $task->status->value !== 'completed')) return false;
            if ($this->stuckTypeFilter === 'delay_reported' && empty($task->delay_reason)) return false;
            if ($this->stuckTypeFilter === 'on_hold' && $task->status->value !== 'on_hold' && ($task->project?->status->value ?? '') !== 'on_hold') return false;

            if ($this->stuckProjectFilter !== 'all' && (string)$task->project_id !== $this->stuckProjectFilter) return false;
            if ($this->stuckAssigneeFilter !== 'all' && (string)$task->assigned_user_id !== $this->stuckAssigneeFilter) return false;
            if ($this->stuckPriorityFilter !== 'all' && strtolower($task->priority->value ?? '') !== strtolower($this->stuckPriorityFilter)) return false;

            if ($this->stuckReasonFilter !== 'all') {
                $reasonText = strtolower($task->delay_reason ?? ($task->blockers->first()?->description ?? ''));
                if (!str_contains($reasonText, strtolower($this->stuckReasonFilter))) return false;
            }

            if ($this->search) {
                $term = strtolower($this->search);
                $mTitle = str_contains(strtolower($task->title ?? ''), $term);
                $mUser = str_contains(strtolower($task->assignedUser->name ?? ''), $term);
                $mProj = str_contains(strtolower($task->project->name ?? ''), $term) || str_contains(strtolower($task->project->code ?? ''), $term);
                $mReason = str_contains(strtolower($task->delay_reason ?? ''), $term) || str_contains(strtolower($task->blockers->first()?->description ?? ''), $term);
                if (!$mTitle && !$mUser && !$mProj && !$mReason) return false;
            }

            $sinceDate = $task->delay_reason_at ?? $task->updated_at ?? $task->end_date;
            $sinceDays = $sinceDate ? max(1, (int)$today->diffInDays($sinceDate)) : 1;
            if ($this->stuckMinDays > 0 && $sinceDays < $this->stuckMinDays) {
                return false;
            }

            if ($this->subsidiaryFilter !== 'all' && (string)$task->project?->subsidiary_id !== $this->subsidiaryFilter) return false;
            if ($this->pmFilter !== 'all' && (string)$task->project?->project_manager_id !== $this->pmFilter) return false;

            return true;
        })->map(function($task) use ($today) {
            $isOverdue = $task->end_date && $task->end_date->lt($today) && $task->status->value !== 'completed';
            $overdueDays = $isOverdue ? $today->diffInDays($task->end_date) : 0;
            $task->is_overdue = $isOverdue;
            $task->overdue_days = $overdueDays;

            $sinceDate = $task->delay_reason_at ?? $task->updated_at ?? $task->end_date ?? $today;
            $task->since_date = $sinceDate;
            $task->since_days = max(1, (int)$today->diffInDays($sinceDate));

            // Display reason
            if ($task->delay_reason) {
                $task->display_reason = $task->delay_reason;
            } elseif ($task->blockers->isNotEmpty()) {
                $task->display_reason = $task->blockers->first()->description;
            } elseif ($isOverdue) {
                $task->display_reason = 'Past deadline without completion';
            } else {
                $task->display_reason = 'Pending unblocking';
            }

            // Computed Impact (High, Medium, Low)
            $pVal = $task->priority->value ?? 'medium';
            if ($task->status->value === 'blocked' || $pVal === 'critical' || $pVal === 'high' || $overdueDays > 7) {
                $task->computed_impact = 'High';
            } elseif ($pVal === 'medium' || $overdueDays > 0) {
                $task->computed_impact = 'Medium';
            } else {
                $task->computed_impact = 'Low';
            }

            return $task;
        });

        $stuckMembersCount = $stuckTasks->pluck('assigned_user_id')->filter()->unique()->count();

        // Paginate items manually from the collection
        $currentPage = $this->getPage();
        $paginatedProjects = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedProjects->forPage($currentPage, $this->perPage)->values(),
            $sortedProjects->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Supporting reference datasets
        $subsidiaries = Subsidiary::orderBy('name')->get();
        $pms = User::whereHas('roles', fn($q) => $q->where('name', 'project_manager'))
            ->orWhereIn('id', $allProjects->pluck('project_manager_id')->filter()->unique())
            ->orderBy('name')
            ->get();

        // ═══════════════════════════════════════════════════════════
        // 🏆 PM PERFORMANCE LEADERBOARD
        // ═══════════════════════════════════════════════════════════
        $pmLeaderboard = $enhancedProjects->groupBy('project_manager_id')
            ->filter(fn($group, $key) => !empty($key))
            ->map(function($projects, $pmId) {
                $pm = $projects->first()->projectManager;
                $total = $projects->count();
                $onTrack = $projects->where('computed_health', 'on_track')->count();
                $delayed = $projects->where('computed_health', 'delayed')->count();
                $avgProg = (int) round($projects->avg('overall_progress'));
                $onTrackRate = $total > 0 ? (int) round(($onTrack / $total) * 100) : 0;
                
                return [
                    'pm' => $pm,
                    'total_projects' => $total,
                    'on_track_count' => $onTrack,
                    'delayed_count' => $delayed,
                    'avg_progress' => $avgProg,
                    'on_track_rate' => $onTrackRate,
                ];
            })
            ->sortByDesc('on_track_rate')
            ->take(5);

        // Subsidiary Distribution Summary
        $subsidiaryHealthSummary = $subsidiaries->map(function($sub) use ($enhancedProjects) {
            $subProjects = $enhancedProjects->where('subsidiary_id', $sub->id);
            $total = $subProjects->count();
            $onTrack = $subProjects->where('computed_health', 'on_track')->count();
            $delayed = $subProjects->where('computed_health', 'delayed')->count();
            $budget = $subProjects->sum('estimated_budget');
            return [
                'subsidiary' => $sub,
                'total_projects' => $total,
                'on_track_count' => $onTrack,
                'delayed_count' => $delayed,
                'total_budget' => $budget,
            ];
        })->filter(fn($s) => $s['total_projects'] > 0);

        // ═══════════════════════════════════════════════════════════
        // 👥 MASTER TASKS MONITOR DATASET (CROSS-PORTFOLIO)
        // ═══════════════════════════════════════════════════════════
        $allTasksQuery = WbsItem::with([
            'project.subsidiary',
            'project.projectManager',
            'assignedUser',
            'blockers.reporter',
            'children.assignedUser'
        ])->whereHas('project', fn($q) => $q->whereNull('deleted_at'));

        // Apply PMO Task Filters
        if ($this->search) {
            $term = strtolower($this->search);
            $allTasksQuery->where(function($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('wbs_code', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhereHas('assignedUser', fn($uq) => $uq->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('project', fn($pq) => $pq->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
            });
        }

        if ($this->subsidiaryFilter !== 'all') {
            $allTasksQuery->whereHas('project', fn($q) => $q->where('subsidiary_id', $this->subsidiaryFilter));
        }

        if ($this->pmFilter !== 'all') {
            $allTasksQuery->whereHas('project', fn($q) => $q->where('project_manager_id', $this->pmFilter));
        }

        if ($this->taskProjectFilter !== 'all') {
            $allTasksQuery->where('project_id', $this->taskProjectFilter);
        }

        if ($this->taskAssigneeFilter !== 'all') {
            if ($this->taskAssigneeFilter === 'unassigned') {
                $allTasksQuery->whereNull('assigned_user_id');
            } else {
                $allTasksQuery->where('assigned_user_id', $this->taskAssigneeFilter);
            }
        }

        if ($this->taskStatusFilter !== 'all') {
            if ($this->taskStatusFilter === 'incomplete') {
                $allTasksQuery->where('status', '!=', 'completed');
            } else {
                $allTasksQuery->where('status', $this->taskStatusFilter);
            }
        }

        if ($this->priorityFilter !== 'all') {
            $allTasksQuery->where('priority', $this->priorityFilter);
        }

        if ($this->taskDueDateFilter !== 'all') {
            $todayStr = now()->toDateString();
            if ($this->taskDueDateFilter === 'today') {
                $allTasksQuery->where(function($q) use ($todayStr) {
                    $q->whereDate('start_date', $todayStr)
                      ->orWhereDate('end_date', $todayStr)
                      ->orWhere(function($subQ) use ($todayStr) {
                          $subQ->whereDate('start_date', '<=', $todayStr)
                               ->whereDate('end_date', '>=', $todayStr);
                      });
                });
            } elseif ($this->taskDueDateFilter === 'overdue') {
                $allTasksQuery->whereDate('end_date', '<', $todayStr)
                              ->where('status', '!=', 'completed');
            } elseif ($this->taskDueDateFilter === 'this_week') {
                $weekStart = now()->startOfWeek()->toDateString();
                $weekEnd = now()->endOfWeek()->toDateString();
                $allTasksQuery->whereDate('end_date', '>=', $weekStart)
                              ->whereDate('end_date', '<=', $weekEnd);
            }
        }

        $allMasterTasks = $allTasksQuery->orderBy('sort_order')->orderBy('id')->get();

        // Compute task metrics for the master tasks view
        $totalMasterTasksCount = $allMasterTasks->count();
        $masterCompletedCount = $allMasterTasks->where('status.value', 'completed')->count();
        $masterInProgressCount = $allMasterTasks->where('status.value', 'in_progress')->count();
        $masterOverdueCount = $allMasterTasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->lt($today))->count();
        $masterBlockedCount = $allMasterTasks->filter(fn($t) => $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0))->count();
        $masterDueTodayCount = $allMasterTasks->filter(function($t) use ($today) {
            $start = $t->start_date ? $t->start_date->copy()->startOfDay() : null;
            $end = $t->end_date ? $t->end_date->copy()->endOfDay() : null;
            if ($start && $end) return $today->between($start, $end);
            if ($start) return $start->isSameDay($today);
            if ($end) return $end->isSameDay($today);
            return false;
        })->count();

        // Collect all team members who have tasks across the portfolio
        $allTeamMembers = User::whereIn('id', WbsItem::pluck('assigned_user_id')->filter()->unique())
            ->orderBy('name')
            ->get();

        // Group Master Tasks:
        // 1. Group by Assignee / User
        $tasksByUser = $allMasterTasks->groupBy(function($task) {
            return $task->assigned_user_id ?: 0;
        })->map(function($tasks, $userId) {
            $user = $userId > 0 ? $tasks->first()->assignedUser : null;
            $total = $tasks->count();
            $completed = $tasks->where('status.value', 'completed')->count();
            $inProgress = $tasks->where('status.value', 'in_progress')->count();
            $overdue = $tasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->lt(now()->startOfDay()))->count();
            $blocked = $tasks->filter(fn($t) => $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0))->count();
            $projects = $tasks->pluck('project')->unique('id');

            return [
                'user' => $user,
                'is_unassigned' => $userId == 0,
                'total_tasks' => $total,
                'completed_count' => $completed,
                'in_progress_count' => $inProgress,
                'overdue_count' => $overdue,
                'blocked_count' => $blocked,
                'progress_pct' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
                'projects' => $projects,
                'tasks' => $tasks,
            ];
        })->sortByDesc('overdue_count')->values();

        // 2. Group by Project
        $tasksByProject = $allMasterTasks->groupBy('project_id')->map(function($tasks, $projectId) {
            $project = $tasks->first()->project;
            $total = $tasks->count();
            $completed = $tasks->where('status.value', 'completed')->count();
            $inProgress = $tasks->where('status.value', 'in_progress')->count();
            $overdue = $tasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->lt(now()->startOfDay()))->count();
            $blocked = $tasks->filter(fn($t) => $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0))->count();
            $assignees = $tasks->pluck('assignedUser')->filter()->unique('id');

            return [
                'project' => $project,
                'total_tasks' => $total,
                'completed_count' => $completed,
                'in_progress_count' => $inProgress,
                'overdue_count' => $overdue,
                'blocked_count' => $blocked,
                'progress_pct' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
                'assignees' => $assignees,
                'tasks' => $tasks,
            ];
        })->sortByDesc('overdue_count')->values();

        // 3. Group by Due Horizon Date
        $tomorrowDate = now()->addDay()->toDateString();
        $nextWeekEnd = now()->addDays(7)->toDateString();
        $tasksByDate = [
            'overdue' => [
                'title' => 'Overdue Deliverables',
                'badge' => 'bg-rose-100 text-[#c3122e] border-rose-300',
                'accent' => 'border-l-4 border-l-[#c3122e]',
                'icon' => '🚨',
                'tasks' => $allMasterTasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->lt(now()->startOfDay())),
            ],
            'today' => [
                'title' => "Today's Schedule & Due",
                'badge' => 'bg-amber-100 text-amber-900 border-amber-300',
                'accent' => 'border-l-4 border-l-amber-500',
                'icon' => '⭐',
                'tasks' => $allMasterTasks->filter(function($t) use ($today) {
                    if ($t->status->value === 'completed') return false;
                    $start = $t->start_date ? $t->start_date->copy()->startOfDay() : null;
                    $end = $t->end_date ? $t->end_date->copy()->endOfDay() : null;
                    if ($start && $end) return $today->between($start, $end);
                    if ($start) return $start->isSameDay($today);
                    if ($end) return $end->isSameDay($today);
                    return false;
                }),
            ],
            'tomorrow' => [
                'title' => 'Tomorrow',
                'badge' => 'bg-blue-100 text-blue-800 border-blue-300',
                'accent' => 'border-l-4 border-l-blue-500',
                'icon' => '📅',
                'tasks' => $allMasterTasks->filter(fn($t) => $t->status->value !== 'completed' && (($t->end_date && $t->end_date->toDateString() === $tomorrowDate) || (!$t->end_date && $t->start_date && $t->start_date->toDateString() === $tomorrowDate))),
            ],
            'this_week' => [
                'title' => 'Next 7 Days',
                'badge' => 'bg-purple-100 text-purple-800 border-purple-300',
                'accent' => 'border-l-4 border-l-purple-500',
                'icon' => '📆',
                'tasks' => $allMasterTasks->filter(fn($t) => $t->status->value !== 'completed' && $t->end_date && $t->end_date->toDateString() > $tomorrowDate && $t->end_date->toDateString() <= $nextWeekEnd),
            ],
            'completed' => [
                'title' => 'Completed Deliverables',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'accent' => 'border-l-4 border-l-emerald-500',
                'icon' => '🎉',
                'tasks' => $allMasterTasks->filter(fn($t) => $t->status->value === 'completed'),
            ],
        ];

        // Active Modal Selected Project Objects
        $detailProject = $this->detailProjectId 
            ? Project::with(['subsidiary', 'projectManager', 'wbsItems.assignedUser', 'risks', 'members'])->find($this->detailProjectId) 
            : null;

        $blockersProject = $this->blockersProjectId
            ? Project::with(['wbsItems' => function($q) {
                $q->where('status', 'blocked')->orWhereNotNull('delay_reason')->with('assignedUser', 'delayReporter');
            }, 'risks' => function($q) {
                $q->where('status', 'open');
            }])->find($this->blockersProjectId)
            : null;

        $wbsDrawerProject = $this->wbsDrawerProjectId
            ? Project::with(['subsidiary', 'projectManager', 'wbsItems' => function($q) {
                $q->orderBy('sort_order')->with(['assignedUser', 'children.assignedUser']);
            }])->find($this->wbsDrawerProjectId)
            : null;

        // ═══════════════════════════════════════════════════════════
        // 📈 ENTERPRISE PORTFOLIO GANTT RADAR ENGINE
        // ═══════════════════════════════════════════════════════════
        $ganttMonthCount = match($this->ganttTimeframe) {
            '3m' => 3,
            '12m' => 12,
            default => 6,
        };

        // Anchor Gantt start to current month or earliest project start
        $earliestProjectStart = $sortedProjects->pluck('start_date')->filter()->min();
        $ganttStart = $earliestProjectStart && $earliestProjectStart->lt(now()->startOfMonth())
            ? $earliestProjectStart->copy()->startOfMonth()
            : now()->startOfMonth();

        // ganttEnd = last day of the last displayed month (e.g. Jan 31 for 6-month view, not Feb 28)
        $ganttEnd = $ganttStart->copy()->addMonths($ganttMonthCount - 1)->endOfMonth();
        $totalGanttDays = (int) max(1, $ganttStart->diffInDays($ganttEnd) + 1);

        // Today marker percentage position
        $now = now();
        if ($now->lt($ganttStart)) {
            $ganttTodayPct = 0;
            $ganttTodayVisible = false;
        } elseif ($now->gt($ganttEnd)) {
            $ganttTodayPct = 100;
            $ganttTodayVisible = false;
        } else {
            $ganttTodayPct = round(($ganttStart->diffInDays($now) / $totalGanttDays) * 100, 2);
            $ganttTodayVisible = true;
        }

        // Generate Months and Weeks structure
        $ganttMonths = [];
        $tempMonth = $ganttStart->copy();
        for ($m = 0; $m < $ganttMonthCount; $m++) {
            $mStart = $tempMonth->copy()->startOfMonth();
            $mEnd = $tempMonth->copy()->endOfMonth();
            $mDays = $mStart->diffInDays($mEnd) + 1;
            $mWidthPct = round(($mDays / $totalGanttDays) * 100, 2);

            $ganttMonths[] = [
                'label'       => $tempMonth->format('M Y'),
                'short_label' => $tempMonth->format("M 'y"),
                'short'       => $tempMonth->format('M'),
                'year'        => $tempMonth->format('Y'),
                'month_num'   => (int) $tempMonth->format('n'),
                'days'        => $mDays,
                'width_pct'   => $mWidthPct,
                'is_current'  => $tempMonth->isSameMonth($now),
            ];
            $tempMonth->addMonth();
        }

        // Generate Project Gantt Bars
        $ganttProjects = $sortedProjects->map(function($project) use ($ganttStart, $ganttEnd, $totalGanttDays, $now) {
            $pStart = $project->start_date 
                ? $project->start_date->copy()->startOfDay() 
                : ($project->created_at ? $project->created_at->copy()->startOfDay() : now()->startOfDay());
            
            $pEnd = $project->deadline 
                ? $project->deadline->copy()->endOfDay() 
                : ($project->end_date ? $project->end_date->copy()->endOfDay() : $pStart->copy()->addDays(30)->endOfDay());

            if ($pEnd->lt($pStart)) {
                $pEnd = $pStart->copy()->addDays(14)->endOfDay();
            }

            // Left offset %
            if ($pStart->lt($ganttStart)) {
                $leftDays = 0;
                $startsBefore = true;
            } else {
                $leftDays = $ganttStart->diffInDays($pStart);
                $startsBefore = false;
            }
            $leftPct = max(0, min(96, round(($leftDays / $totalGanttDays) * 100, 2)));

            // Visible end & width %
            $vStart = $pStart->lt($ganttStart) ? $ganttStart : $pStart;
            $vEnd = $pEnd->gt($ganttEnd) ? $ganttEnd : $pEnd;
            $vDays = max(1, $vStart->diffInDays($vEnd) + 1);
            $widthPct = max(3.5, min(100 - $leftPct, round(($vDays / $totalGanttDays) * 100, 2)));

            $progress = (int) ($project->overall_progress ?? 0);
            $isOverdue = $pEnd->lt($now->startOfDay()) && $progress < 100;
            $daysRemaining = (int) $now->startOfDay()->diffInDays($pEnd, false);

            // Child WBS tasks if expanded
            $wbsTasks = [];
            if (in_array($project->id, $this->expandedGanttProjectIds)) {
                $wbsTasks = $project->wbsItems->map(function($item) use ($ganttStart, $ganttEnd, $totalGanttDays, $now) {
                    $tStart = $item->start_date ? $item->start_date->copy()->startOfDay() : now()->startOfDay();
                    $tEnd = $item->end_date ? $item->end_date->copy()->endOfDay() : $tStart->copy()->addDay()->endOfDay();
                    if ($tEnd->lt($tStart)) $tEnd = $tStart->copy()->addDay()->endOfDay();

                    $tLeftDays = $tStart->lt($ganttStart) ? 0 : $ganttStart->diffInDays($tStart);
                    $tLeftPct = max(0, min(97, round(($tLeftDays / $totalGanttDays) * 100, 2)));

                    $tvStart = $tStart->lt($ganttStart) ? $ganttStart : $tStart;
                    $tvEnd = $tEnd->gt($ganttEnd) ? $ganttEnd : $tEnd;
                    $tvDays = max(1, $tvStart->diffInDays($tvEnd) + 1);
                    $tWidthPct = max(2.5, min(100 - $tLeftPct, round(($tvDays / $totalGanttDays) * 100, 2)));

                    return [
                        'item'       => $item,
                        'start_date' => $tStart,
                        'end_date'   => $tEnd,
                        'left_pct'   => $tLeftPct,
                        'width_pct'  => $tWidthPct,
                        'is_overdue' => $tEnd->lt($now->startOfDay()) && ($item->status->value ?? '') !== 'completed',
                    ];
                });
            }

            return [
                'project'         => $project,
                'start_date'      => $pStart,
                'end_date'        => $pEnd,
                'left_pct'        => $leftPct,
                'width_pct'       => $widthPct,
                'progress'        => $progress,
                'is_overdue'      => $isOverdue,
                'days_remaining'  => $daysRemaining,
                'wbs_tasks'       => $wbsTasks,
                'is_expanded'     => in_array($project->id, $this->expandedGanttProjectIds),
            ];
        });

        $ganttTimeline = [
            'start'          => $ganttStart,
            'end'            => $ganttEnd,
            'total_days'     => $totalGanttDays,
            'today_pct'      => $ganttTodayPct,
            'today_visible'  => $ganttTodayVisible,
            'months'         => $ganttMonths,
            'projects'       => $ganttProjects,
        ];

        return view('livewire.project-monitor', compact(
            'paginatedProjects',
            'sortedProjects',
            'totalProjectsCount',
            'activeProjectsCount',
            'completedProjectsCount',
            'onTrackCount',
            'atRiskCount',
            'delayedCount',
            'onHoldCount',
            'atRiskOrBlockedCount',
            'pendingPmCount',
            'totalEstimatedBudget',
            'totalActualCost',
            'avgProgress',
            'totalPortfolioBlockers',
            'totalPortfolioOverdue',
            'totalPortfolioRisks',
            'subsidiaries',
            'pms',
            'pmLeaderboard',
            'subsidiaryHealthSummary',
            'detailProject',
            'blockersProject',
            'wbsDrawerProject',
            'stuckTasks',
            'totalStuckTasksCount',
            'blockedTasksOnlyCount',
            'overdueTasksOnlyCount',
            'delayReportedCount',
            'stuckMembersCount',
            'onHoldTasksCount',
            'stuckProjectsList',
            'stuckAssigneesList',
            'allMasterTasks',
            'totalMasterTasksCount',
            'masterCompletedCount',
            'masterInProgressCount',
            'masterOverdueCount',
            'masterBlockedCount',
            'masterDueTodayCount',
            'allTeamMembers',
            'tasksByUser',
            'tasksByProject',
            'tasksByDate',
            'ganttTimeline'
        ))->with([
            'viewMode'                => $this->viewMode,
            'search'                  => $this->search,
            'quickSegment'            => $this->quickSegment,
            'subsidiaryFilter'        => $this->subsidiaryFilter,
            'healthFilter'            => $this->healthFilter,
            'statusFilter'            => $this->statusFilter,
            'pmFilter'                => $this->pmFilter,
            'priorityFilter'          => $this->priorityFilter,
            'sortBy'                  => $this->sortBy,
            'ganttTimeframe'          => $this->ganttTimeframe,
            'expandedGanttProjectIds' => $this->expandedGanttProjectIds,
            'stuckTypeFilter'         => $this->stuckTypeFilter,
            'taskAssigneeFilter'      => $this->taskAssigneeFilter,
            'taskProjectFilter'       => $this->taskProjectFilter,
            'taskStatusFilter'        => $this->taskStatusFilter,
            'taskDueDateFilter'       => $this->taskDueDateFilter,
            'taskGroupBy'             => $this->taskGroupBy,
            'expandedUserIds'         => $this->expandedUserIds,
            'expandedProjectTaskIds'  => $this->expandedProjectTaskIds,
            'expandedDateKeys'        => $this->expandedDateKeys,
            'showNudgeModal'          => $this->showNudgeModal,
            'showDetailModal'         => $this->showDetailModal,
            'showBlockersModal'       => $this->showBlockersModal,
            'showWbsDrawer'           => $this->showWbsDrawer,
        ]);
    }
}
