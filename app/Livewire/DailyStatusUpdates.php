<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Project;
use App\Models\ProjectStatusUpdate;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;

class DailyStatusUpdates extends Component
{
    // Filter State
    public ?int $selectedProjectId = null;
    public string $selectedScope = 'all'; // 'all', 'task', 'project'
    public string $dateFilter = 'all'; // 'all', 'today', 'this_week'
    public string $searchQuery = '';

    // Modal State for Posting Updates
    public bool $showUpdateModal = false;
    public ?int $updateProjectId = null;
    public ?int $updateWbsItemId = null;
    public string $updateScopeType = 'task'; // 'task' or 'project'
    public string $updateTitle = '';
    public string $updateSummary = '';
    public string $updateStatus = 'in_progress';
    public string $updateWorkCompleted = '';
    public string $updateBlockers = '';
    public string $updateNextSteps = '';

    // Comment Input State indexed by updateId
    public array $newCommentContent = [];

    // Modal State for viewing history
    public bool $showHistoryModal = false;
    public ?int $historyProjectId = null;

    public function openHistoryModal(int $projectId)
    {
        $this->historyProjectId = $projectId;
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyProjectId = null;
    }

    protected $queryString = [
        'selectedProjectId' => ['except' => null, 'as' => 'project'],
        'selectedScope' => ['except' => 'all', 'as' => 'scope'],
        'searchQuery' => ['except' => '', 'as' => 'q'],
    ];

    public function mount()
    {
        $projectId = request()->query('project') ? (int) request()->query('project') : null;
        $taskId = request()->query('task') ? (int) request()->query('task') : null;
        $autoCreate = request()->query('create') || $taskId || request()->query('project');

        if ($projectId) {
            $this->selectedProjectId = $projectId;
        }

        if ($autoCreate) {
            $this->openStatusUpdateModal($projectId, $taskId);
        }
    }

    protected function isSuperAdminUser(?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) return false;

        return $user->hasRole('super_admin')
            || $user->email === 'admin@nexuspm.local'
            || $user->id === 1;
    }

    public function updatedUpdateProjectId()
    {
        $this->updateWbsItemId = null;
    }

    public function openStatusUpdateModal(?int $projectId = null, ?int $wbsItemId = null)
    {
        $user = auth()->user();

        $this->reset([
            'updateProjectId',
            'updateWbsItemId',
            'updateTitle',
            'updateSummary',
            'updateStatus',
            'updateWorkCompleted',
            'updateBlockers',
            'updateNextSteps',
        ]);

        if ($projectId) {
            $this->updateProjectId = $projectId;
        } else {
            $firstProject = $this->getAccessibleProjects()->first();
            if ($firstProject) {
                $this->updateProjectId = $firstProject->id;
            }
        }

        if ($wbsItemId) {
            $this->updateWbsItemId = $wbsItemId;
            $this->updateScopeType = 'task';
            $wbs = WbsItem::find($wbsItemId);
            if ($wbs) {
                $this->updateTitle = 'Daily Progress Log: ' . $wbs->title . ' (' . now()->format('M d') . ')';
            }
        } else {
            if ($user->hasAnyRole(['team_member', 'collaborator']) && !$this->isSuperAdminUser($user) && !$user->hasRole('project_manager')) {
                $this->updateScopeType = 'task';
                $this->updateTitle = 'Task Progress Log — ' . now()->format('M d, Y');
            } else {
                $this->updateScopeType = 'project';
                $this->updateTitle = 'Daily Project Update — ' . now()->format('M d, Y');
            }
        }

        $this->showUpdateModal = true;
    }

    public function saveStatusUpdate()
    {
        $user = auth()->user();

        $this->validate([
            'updateProjectId' => 'required|exists:projects,id',
            'updateTitle' => 'required|string|max:255',
            'updateSummary' => 'required|string',
            'updateWbsItemId' => 'nullable|exists:wbs_items,id',
        ]);

        $project = Project::findOrFail($this->updateProjectId);

        if (!$this->isSuperAdminUser($user) && $project->project_manager_id !== $user->id) {
            if ($this->updateWbsItemId) {
                $wbs = WbsItem::find($this->updateWbsItemId);
                if (!$wbs || $wbs->assigned_user_id !== $user->id) {
                    $this->dispatch('toast', message: 'You can only log updates for tasks assigned to you.', type: 'error');
                    return;
                }
            } else {
                $this->dispatch('toast', message: 'Only Project Managers and Admins can publish full project status updates.', type: 'error');
                return;
            }
        }

        $statusUpdate = ProjectStatusUpdate::create([
            'project_id' => $project->id,
            'wbs_item_id' => ($this->updateScopeType === 'task') ? $this->updateWbsItemId : null,
            'title' => $this->updateTitle,
            'summary' => $this->updateSummary,
            'reporting_period' => now()->format('Y-m-d'),
            'current_progress' => $project->overall_progress,
            'work_completed' => $this->updateWorkCompleted,
            'current_blockers' => $this->updateBlockers,
            'next_steps' => $this->updateNextSteps,
            'updated_status' => $this->updateStatus,
            'created_by' => $user->id,
        ]);

        if ($this->updateWbsItemId && $this->updateStatus === 'completed') {
            $wbs = WbsItem::find($this->updateWbsItemId);
            if ($wbs) {
                $wbs->status = 'completed';
                $wbs->progress_percentage = 100;
                $wbs->save();
            }
        }

        if (!$this->updateWbsItemId && ($this->isSuperAdminUser($user) || $project->project_manager_id === $user->id)) {
            $project->update(['status' => $this->updateStatus]);
        }

        $this->showUpdateModal = false;
        $this->dispatch('toast', message: 'Daily status update published successfully!', type: 'success');
    }

    public function addComment(int $updateId)
    {
        $content = trim($this->newCommentContent[$updateId] ?? '');
        if (empty($content)) {
            $this->dispatch('toast', message: 'Please write a comment before sending.', type: 'warning');
            return;
        }

        $update = ProjectStatusUpdate::findOrFail($updateId);

        Comment::create([
            'commentable_id' => $update->id,
            'commentable_type' => ProjectStatusUpdate::class,
            'user_id' => auth()->id(),
            'content' => $content,
        ]);

        $this->newCommentContent[$updateId] = '';
        $this->dispatch('toast', message: 'Feedback comment posted successfully!', type: 'success');
    }

    public function deleteUpdate(int $updateId)
    {
        $user = auth()->user();
        $update = ProjectStatusUpdate::findOrFail($updateId);

        if ($update->created_by !== $user->id && !$this->isSuperAdminUser($user) && $update->project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $update->delete();
        $this->dispatch('toast', message: 'Status update removed.', type: 'info');
    }

    protected function getAccessibleProjects()
    {
        $user = auth()->user();
        $query = Project::with(['subsidiary', 'projectManager']);

        // Super Admin sees ALL projects in the entire organization
        if (!$this->isSuperAdminUser($user)) {
            if ($user->hasRole('project_manager')) {
                $query->where('project_manager_id', $user->id)
                      ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id));
            } else {
                $query->whereHas('members', fn($q) => $q->where('user_id', $user->id))
                      ->orWhereHas('wbsItems', fn($q) => $q->where('assigned_user_id', $user->id));
            }
        }

        return $query->orderBy('name')->get();
    }

    public function render()
    {
        $user = auth()->user();
        $isSuperAdmin = $this->isSuperAdminUser($user);

        // Fetch accessible projects (All projects for Super Admin)
        $accessibleProjects = $this->getAccessibleProjects();
        $accessibleProjectIds = $accessibleProjects->pluck('id')->toArray();

        // Query for status updates feed
        $updatesQuery = ProjectStatusUpdate::with(['project.projectManager', 'project.subsidiary', 'wbsItem', 'creator', 'comments.user']);

        // Visibility Hierarchy Rules:
        if ($isSuperAdmin) {
            // Super Admin ONLY sees Project Manager updates, Overall Project Reports, and Super Admin posts.
            $updatesQuery->where(function($q) {
                $q->whereNull('wbs_item_id') // Overall Project Reports
                  ->orWhereHas('creator', function($userQuery) {
                      $userQuery->whereHas('roles', function($roleQuery) {
                          $roleQuery->whereIn('name', ['super_admin', 'project_manager']);
                      });
                  })
                  ->orWhereHas('project', function($projQuery) {
                      $projQuery->whereColumn('project_manager_id', 'project_status_updates.created_by');
                  });
            });
        } else {
            // Project Managers and Collaborators see status updates for all their accessible projects
            $updatesQuery->whereIn('project_id', $accessibleProjectIds);
        }

        if ($this->selectedProjectId) {
            $updatesQuery->where('project_id', $this->selectedProjectId);
        }

        if ($this->selectedScope === 'task') {
            $updatesQuery->whereNotNull('wbs_item_id');
        } elseif ($this->selectedScope === 'project') {
            $updatesQuery->whereNull('wbs_item_id');
        }

        if ($this->dateFilter === 'today') {
            $updatesQuery->whereDate('created_at', now()->toDateString());
        } elseif ($this->dateFilter === 'this_week') {
            $updatesQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        if ($this->searchQuery) {
            $q = $this->searchQuery;
            $updatesQuery->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhere('work_completed', 'like', "%{$q}%")
                    ->orWhereHas('creator', fn($cu) => $cu->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('wbsItem', fn($w) => $w->where('title', 'like', "%{$q}%")->orWhere('wbs_code', 'like', "%{$q}%"));
            });
        }

        $statusUpdates = $updatesQuery->latest()->get();

        $groupedProjectUpdates = collect();
        foreach ($accessibleProjects as $proj) {
            $pUpdates = $statusUpdates->where('project_id', $proj->id)->values();
            
            // Apply project filter if selected
            if ($this->selectedProjectId && $this->selectedProjectId != $proj->id) {
                continue;
            }

            $groupedProjectUpdates->push([
                'project' => $proj,
                'latestUpdate' => $pUpdates->first(),
                'allUpdates' => $pUpdates,
                'pastUpdates' => $pUpdates->slice(1),
            ]);
        }

        // Available WBS Tasks for post update modal
        $modalWbsItems = collect();
        if ($this->updateProjectId) {
            $wbsQuery = WbsItem::where('project_id', $this->updateProjectId);

            if (!$isSuperAdmin) {
                $proj = Project::find($this->updateProjectId);
                if ($proj && $proj->project_manager_id !== $user->id) {
                    $wbsQuery->where('assigned_user_id', $user->id);
                }
            }

            $modalWbsItems = $wbsQuery->orderBy('wbs_code')->get();
        }

        // Summary KPI Counts
        $totalUpdatesCount = $statusUpdates->count();
        $taskUpdatesCount = $statusUpdates->whereNotNull('wbs_item_id')->count();
        $projectUpdatesCount = $statusUpdates->whereNull('wbs_item_id')->count();
        $updatedTodayCount = $statusUpdates->filter(fn($u) => $u->created_at->isToday())->count();
        $myUpdatesCount = $statusUpdates->where('created_by', $user->id)->count();

        // Pending feedback updates (no comments yet)
        $uncommentedUpdatesCount = $statusUpdates->filter(fn($u) => $u->comments->count() === 0)->count();

        return view('livewire.daily-status-updates', compact(
            'statusUpdates',
            'groupedProjectUpdates',
            'accessibleProjects',
            'modalWbsItems',
            'totalUpdatesCount',
            'taskUpdatesCount',
            'projectUpdatesCount',
            'updatedTodayCount',
            'myUpdatesCount',
            'uncommentedUpdatesCount'
        ))->layout('layouts.app', ['title' => 'Daily Status Updates']);
    }
}
