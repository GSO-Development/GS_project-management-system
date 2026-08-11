<?php

namespace App\Livewire;

use App\Enums\ApprovalStatus;
use App\Enums\ProjectStatus;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectStatusUpdate;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ApprovalService;
use Livewire\Component;

class SuperAdminDashboard extends Component
{
    public function approveRequest(int $requestId)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $req = ApprovalRequest::findOrFail($requestId);
        (new ApprovalService())->processApproval($req, auth()->user(), true, 'Approved from Dashboard');
        $this->dispatch('toast', message: 'Request approved successfully!', type: 'success');
    }

    public function rejectRequest(int $requestId)
    {
        if (!auth()->user()->hasRole('super_admin')) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $req = ApprovalRequest::findOrFail($requestId);
        (new ApprovalService())->processApproval($req, auth()->user(), false, 'Rejected from Dashboard');
        $this->dispatch('toast', message: 'Request rejected.', type: 'info');
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
        $totalSubsidiaries = Subsidiary::count();
        $totalUsers = User::count();
        $pendingApprovals = ApprovalRequest::where('status', ApprovalStatus::PENDING)->count();

        $projectsByStatus = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $projectsBySubsidiary = Subsidiary::withCount('projects')->get();

        // 1. All Active Projects with Real-time Daily Status & Recent Updates from PMs
        $dailyProjectTracker = Project::with([
            'subsidiary',
            'projectManager',
            'statusUpdates' => fn($q) => $q->latest()->take(1),
            'wbsItems' => fn($q) => $q->whereNotIn('status', ['cancelled']),
        ])
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
        ->take(1)
        ->values();

        $recentUpdates = ProjectStatusUpdate::with(['project', 'creator'])
            ->latest()
            ->take(6)
            ->get();

        $pendingApprovalList = ApprovalRequest::with(['project', 'requester'])
            ->where('status', ApprovalStatus::PENDING)
            ->latest()
            ->take(5)
            ->get();

        $recentDocuments = ProjectDocument::with(['project', 'uploader'])
            ->latest()
            ->take(5)
            ->get();

        $delayedTaskIssues = WbsItem::with(['project', 'assignedUser', 'delayReporter'])
            ->whereNotNull('delay_reason')
            ->latest('delay_reason_at')
            ->take(6)
            ->get();

        return view('livewire.super-admin-dashboard', compact(
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'overdueProjects',
            'onHoldProjects',
            'totalSubsidiaries',
            'totalUsers',
            'pendingApprovals',
            'projectsByStatus',
            'projectsBySubsidiary',
            'dailyProjectTracker',
            'recentUpdates',
            'pendingApprovalList',
            'recentDocuments',
            'delayedTaskIssues'
        ));
    }
}
