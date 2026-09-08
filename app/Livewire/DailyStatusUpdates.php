<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Project;
use App\Models\ProjectStatusUpdate;
use App\Models\User;
use App\Models\WbsItem;
use App\Notifications\DailyUpdateNotification;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class DailyStatusUpdates extends Component
{
    use WithPagination;

    // Filter State
    public ?int $selectedProjectId = null;
    public string $selectedScope = 'all'; // 'all', 'task', 'project'
    public string $dateFilter = 'all'; // 'all', 'today', 'this_week'
    public string $searchQuery = '';
    public string $viewMode = 'table'; // 'table' or 'feed'
    public int $perPage = 10;

    public function updatedSearchQuery(): void { $this->resetPage(); }
    public function updatedSelectedProjectId(): void { $this->resetPage(); }
    public function updatedSelectedScope(): void { $this->resetPage(); }
    public function updatedDateFilter(): void { $this->resetPage(); }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = $mode;
    }

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
    public ?int $selectedHistoryUpdateId = null;

    public function openHistoryModal(int $projectId, ?int $updateId = null)
    {
        $this->historyProjectId = $projectId;
        $this->selectedHistoryUpdateId = $updateId;
        $this->showHistoryModal = true;
    }

    public function selectHistoryUpdate(int $updateId)
    {
        $this->selectedHistoryUpdateId = $updateId;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyProjectId = null;
        $this->selectedHistoryUpdateId = null;
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
        $updateId = request()->query('update') ? (int) request()->query('update') : null;
        $shouldCreate = request()->boolean('create');

        if ($projectId) {
            $this->selectedProjectId = $projectId;
        }

        if ($shouldCreate) {
            $this->openStatusUpdateModal($projectId, $taskId);
        } elseif ($projectId) {
            $this->openHistoryModal($projectId, $updateId);
        }
    }

    public function isSuperAdminUser(?User $user = null): bool
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
            $isPmOnProject = ($this->updateProjectId && Project::where('id', $this->updateProjectId)->where('project_manager_id', $user->id)->exists());
            if (!$this->isSuperAdminUser($user) && !$isPmOnProject) {
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

        // ═══════════════════════════════════════════════════════════════
        // DISPATCH NOTIFICATIONS TO PM, TEAM MEMBERS, AND SUPER ADMINS
        // ═══════════════════════════════════════════════════════════════
        try {
            $recipients = collect();

            // 1. If author is PM or Admin -> notify all assigned project team members
            if ($project->project_manager_id === $user->id || $this->isSuperAdminUser($user)) {
                $members = $project->members()->where('users.id', '!=', $user->id)->get();
                foreach ($members as $m) {
                    $recipients->push($m);
                }
            } else {
                // 2. If author is a team member -> notify the designated Project Manager
                if ($project->projectManager && $project->projectManager->id !== $user->id) {
                    $recipients->push($project->projectManager);
                }
            }

            // Also notify super admins
            $superAdmins = User::role('super_admin')->where('id', '!=', $user->id)->get();
            foreach ($superAdmins as $admin) {
                $recipients->push($admin);
            }

            $recipients = $recipients->unique('id');
            $actionType = ($this->updateScopeType === 'task') ? 'task_log' : 'daily_update';

            foreach ($recipients as $recipient) {
                $recipient->notify(new DailyUpdateNotification(
                    updateTitle: $this->updateTitle,
                    reporterName: $user->name,
                    projectName: $project->name,
                    url: route('daily-updates.index', ['project' => $project->id, 'update' => $statusUpdate->id]),
                    actionType: $actionType,
                    summary: Str::limit($this->updateSummary, 120)
                ));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to dispatch daily update notification: ' . $e->getMessage());
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
        $user = auth()->user();

        Comment::create([
            'commentable_id' => $update->id,
            'commentable_type' => ProjectStatusUpdate::class,
            'user_id' => $user->id,
            'content' => $content,
        ]);

        // ═══════════════════════════════════════════════════════════════
        // DISPATCH COMMENT NOTIFICATIONS
        // ═══════════════════════════════════════════════════════════════
        try {
            $commentRecipients = collect();

            // Notify original update author if different from commenter
            if ($update->created_by && $update->created_by !== $user->id) {
                $creator = User::find($update->created_by);
                if ($creator) $commentRecipients->push($creator);
            }

            // If commenter is not PM, notify the PM
            if ($update->project && $update->project->projectManager && $update->project->projectManager->id !== $user->id) {
                $commentRecipients->push($update->project->projectManager);
            }

            $commentRecipients = $commentRecipients->unique('id');

            foreach ($commentRecipients as $recipient) {
                $recipient->notify(new DailyUpdateNotification(
                    updateTitle: $update->title,
                    reporterName: $user->name,
                    projectName: $update->project->name ?? 'Project',
                    url: route('daily-updates.index', ['project' => $update->project_id, 'update' => $update->id]),
                    actionType: 'comment',
                    summary: Str::limit($content, 120)
                ));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to dispatch comment notification: ' . $e->getMessage());
        }

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
            $query->where(function($q) use ($user) {
                // PM sees their project
                $q->where('project_manager_id', $user->id)
                  // Collaborator sees project ONLY after PM accepts it
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->where(function($memberSub) use ($user) {
                              $memberSub->whereHas('members', fn($mq) => $mq->where('users.id', $user->id))
                                        ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id));
                          });
                  });
            });
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
                          $roleQuery->where('name', 'super_admin');
                      })->orWhereHas('managedProjects');
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

            $modalWbsItems = $wbsQuery->get()->sort(function ($a, $b) {
                return strnatcmp($a->wbs_code, $b->wbs_code);
            })->values();
        }

        // Summary KPI Counts
        $totalUpdatesCount = $statusUpdates->count();
        $taskUpdatesCount = $statusUpdates->whereNotNull('wbs_item_id')->count();
        $projectUpdatesCount = $statusUpdates->whereNull('wbs_item_id')->count();
        $updatedTodayCount = $statusUpdates->filter(fn($u) => $u->created_at->isToday())->count();
        $myUpdatesCount = $statusUpdates->where('created_by', $user->id)->count();

        // Pending feedback updates (no comments yet)
        $uncommentedUpdatesCount = $statusUpdates->filter(fn($u) => $u->comments->count() === 0)->count();

        // Paginate collections
        $currentPage = $this->getPage();
        $paginatedGroupedUpdates = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedProjectUpdates->forPage($currentPage, $this->perPage)->values(),
            $groupedProjectUpdates->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $paginatedStatusUpdates = new \Illuminate\Pagination\LengthAwarePaginator(
            $statusUpdates->forPage($currentPage, $this->perPage)->values(),
            $statusUpdates->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.daily-status-updates', [
            'statusUpdates' => $paginatedStatusUpdates,
            'groupedProjectUpdates' => $paginatedGroupedUpdates,
            'allGroupedProjectUpdates' => $groupedProjectUpdates,
            'accessibleProjects' => $accessibleProjects,
            'modalWbsItems' => $modalWbsItems,
            'totalUpdatesCount' => $totalUpdatesCount,
            'taskUpdatesCount' => $taskUpdatesCount,
            'projectUpdatesCount' => $projectUpdatesCount,
            'updatedTodayCount' => $updatedTodayCount,
            'myUpdatesCount' => $myUpdatesCount,
            'uncommentedUpdatesCount' => $uncommentedUpdatesCount,
            'isSuperAdmin' => $isSuperAdmin,
        ])->layout('layouts.app', ['title' => 'Daily Status Updates']);
    }
}
