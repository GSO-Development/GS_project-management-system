<?php

namespace App\Livewire;

use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\TaskBlocker;
use App\Models\WbsItem;
use Livewire\Component;

class ProjectManagerDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        // Projects managed by this PM
        $assignedProjects = Project::where('project_manager_id', $user->id)
            ->with(['subsidiary'])
            ->get();

        $assignedProjectIds = $assignedProjects->pluck('id')->toArray();
        $activeProjectsCount = $assignedProjects->where('status.value', 'in_progress')->count();

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
        $pendingApprovals = ApprovalRequest::with('project')
            ->whereIn('project_id', $assignedProjectIds)
            ->where('status', 'pending')
            ->get();

        // Project risks
        $openRisks = ProjectRisk::with('project')
            ->whereIn('project_id', $assignedProjectIds)
            ->where('status', 'open')
            ->get();

        // Current blockers
        $currentBlockers = TaskBlocker::with(['wbsItem.project', 'reporter'])
            ->whereHas('wbsItem', fn($q) => $q->whereIn('project_id', $assignedProjectIds))
            ->where('status', 'open')
            ->get();

        // Team member & PM delayed task issues
        $teamTaskIssues = WbsItem::with(['project', 'assignedUser', 'delayReporter'])
            ->whereIn('project_id', $assignedProjectIds)
            ->whereNotNull('delay_reason')
            ->latest('delay_reason_at')
            ->get();

        return view('livewire.project-manager-dashboard', compact(
            'assignedProjects',
            'activeProjectsCount',
            'tasksDueToday',
            'overdueTasksCount',
            'pendingApprovals',
            'openRisks',
            'currentBlockers',
            'teamTaskIssues'
        ));
    }
}
