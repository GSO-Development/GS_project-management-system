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
    public ?int $selectedSubsidiaryId = null;
    public string $selectedScope = 'all'; // 'all', 'task', 'project'
    public string $dateFilter = 'all'; // 'all', 'today', 'this_week'
    public string $searchQuery = '';
    public string $viewMode = 'table'; // 'table' or 'feed'
    public int $perPage = 10;
    public bool $showFilters = false;

    public function updatedSearchQuery(): void { $this->resetPage(); }
    public function updatedSelectedProjectId(): void { $this->resetPage(); }
    public function updatedSelectedSubsidiaryId(): void { $this->resetPage(); }
    public function updatedSelectedScope(): void { $this->resetPage(); }
    public function updatedDateFilter(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters(): void
    {
        $this->reset(['searchQuery', 'selectedProjectId', 'selectedSubsidiaryId', 'selectedScope', 'dateFilter']);
        $this->resetPage();
    }

    public function hasActiveFilters(): bool
    {
        return (!empty(trim($this->searchQuery)) || $this->selectedProjectId !== null || $this->selectedSubsidiaryId !== null || $this->selectedScope !== 'all' || $this->dateFilter !== 'all');
    }

    public function filterByDate(string $filter): void
    {
        $this->dateFilter = ($this->dateFilter === $filter) ? 'all' : $filter;
        $this->resetPage();
    }

    public function filterByScope(string $scope): void
    {
        $this->selectedScope = ($this->selectedScope === $scope) ? 'all' : $scope;
        $this->resetPage();
    }

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

    // Comment Input State indexed by updateId
    public array $newCommentContent = [];
    public string $commentVisibility = 'team'; // 'team' (Team Member & PM) or 'pmo_pm' (PMO Admin & PM Only)

    // Modal State for viewing history
    public bool $showHistoryModal = false;
    public ?int $historyProjectId = null;
    public ?int $selectedHistoryUpdateId = null;
    public string $historyTab = 'report';

    protected function syncDefaultCommentVisibility(?ProjectStatusUpdate $update): void
    {
        if (!$update) {
            $this->commentVisibility = 'team';
            return;
        }

        $creator = $update->creator ?? ($update->created_by ? User::find($update->created_by) : null);
        $project = $update->project ?? ($update->project_id ? Project::find($update->project_id) : null);

        if ($creator && ($this->isSuperAdminUser($creator) || $this->isProjectManagerOf($project, $creator))) {
            $this->commentVisibility = 'pmo_pm';
        } else {
            $this->commentVisibility = 'team';
        }
    }

    protected function findVisibleHistoryUpdate(int $projectId, ?int $updateId = null): ?ProjectStatusUpdate
    {
        $project = $this->getAccessibleProjects()->firstWhere('id', $projectId);
        if (!$project) {
            return null;
        }

        $query = ProjectStatusUpdate::with(['creator', 'project', 'comments.user.roles'])
            ->where('project_id', $projectId);

        return $updateId ? $query->find($updateId) : $query->latest()->first();
    }

    protected function dailyUpdateNotificationParameters($notification): array
    {
        $url = html_entity_decode($notification->data['url'] ?? '');
        $queryString = parse_url($url, PHP_URL_QUERY) ?: '';
        parse_str($queryString, $parameters);

        // Support notifications created before URLs were stored in decoded form.
        if (!isset($parameters['project']) && !isset($parameters['update'])) {
            parse_str(rawurldecode($queryString), $parameters);
        }

        return $parameters;
    }

    protected function unreadDailyUpdateCountsByProject(User $user): \Illuminate\Support\Collection
    {
        return $user->unreadNotifications()
            ->where('type', DailyUpdateNotification::class)
            ->get()
            ->map(fn ($notification) => $this->dailyUpdateNotificationParameters($notification))
            ->filter(fn (array $parameters) => isset($parameters['project']) && ctype_digit((string) $parameters['project']))
            ->countBy(fn (array $parameters) => (int) $parameters['project']);
    }

    protected function markProjectUpdatesAsRead(?int $projectId = null, ?int $updateId = null, string $tab = 'report'): void
    {
        $user = auth()->user();
        if (!$user || !$projectId || !$updateId || !in_array($tab, ['report', 'discussion'], true)) {
            return;
        }

        $update = $this->findVisibleHistoryUpdate($projectId, $updateId);
        if (!$update) {
            return;
        }
        $visibleCommentIds = $this->getVisibleCommentsForUpdate($update)->modelKeys();

        $notificationIds = $user->unreadNotifications()
            ->where('type', DailyUpdateNotification::class)
            ->get()
            ->filter(function ($notification) use ($projectId, $updateId, $tab, $visibleCommentIds) {
                $data = $notification->data;
                $parameters = $this->dailyUpdateNotificationParameters($notification);

                // Match complete IDs; update=1 must never match update=10.
                if ((string) ($parameters['update'] ?? '') !== (string) $updateId) {
                    return false;
                }
                if (isset($parameters['project']) && (string) $parameters['project'] !== (string) $projectId) {
                    return false;
                }

                $isComment = ($data['action'] ?? '') === 'comment';
                if ($tab === 'report') {
                    return !$isComment;
                }
                if (!$isComment || empty($visibleCommentIds)) {
                    return false;
                }

                // Older notifications do not contain a comment ID.
                return empty($data['comment_id']) || in_array((int) $data['comment_id'], $visibleCommentIds, true);
            })
            ->modelKeys();

        if (empty($notificationIds)) {
            return;
        }

        $user->unreadNotifications()->whereIn('id', $notificationIds)->update(['read_at' => now()]);
        $projectUnreadCount = (int) $this->unreadDailyUpdateCountsByProject($user)->get($projectId, 0);
        $this->dispatch('daily-updates-read', count: $user->unreadNotifications()
            ->where('type', DailyUpdateNotification::class)->count(),
            projectId: $projectId,
            projectUnreadCount: $projectUnreadCount);
    }

    public function openHistoryModal(int $projectId, ?int $updateId = null, string $tab = 'report')
    {
        abort_unless($this->getAccessibleProjects()->contains('id', $projectId), 403);
        $targetUpdate = $this->findVisibleHistoryUpdate($projectId, $updateId);
        abort_if($updateId && !$targetUpdate, 404);

        $this->historyProjectId = $projectId;
        $this->selectedHistoryUpdateId = $targetUpdate?->id;
        $this->historyTab = in_array($tab, ['report', 'discussion', 'history'], true) ? $tab : 'report';
        $this->showHistoryModal = true;
        $this->syncDefaultCommentVisibility($targetUpdate);
        $this->markProjectUpdatesAsRead($projectId, $targetUpdate?->id, $this->historyTab);
    }

    public function selectHistoryUpdate(int $updateId)
    {
        if (!$this->showHistoryModal || !$this->historyProjectId) {
            return;
        }
        $update = $this->findVisibleHistoryUpdate($this->historyProjectId, $updateId);
        abort_unless($update, 404);

        $this->selectedHistoryUpdateId = $update->id;
        $this->historyTab = 'report';
        $this->syncDefaultCommentVisibility($update);
        $this->markProjectUpdatesAsRead($update->project_id, $update->id);
    }

    public function updatedHistoryTab(string $tab): void
    {
        if (!in_array($tab, ['report', 'discussion', 'history'], true)) {
            $this->historyTab = 'report';
            return;
        }
        if ($this->showHistoryModal) {
            $this->markProjectUpdatesAsRead($this->historyProjectId, $this->selectedHistoryUpdateId, $tab);
        }
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyProjectId = null;
        $this->selectedHistoryUpdateId = null;
        $this->historyTab = 'report';
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
        $historyTab = (string) request()->query('tab', 'report');

        if ($projectId) {
            $this->selectedProjectId = $projectId;
        }

        if ($shouldCreate) {
            $this->openStatusUpdateModal($projectId, $taskId);
        } elseif ($projectId) {
            $this->openHistoryModal($projectId, $updateId, $historyTab);
        } elseif ($updateId) {
            $targetUpdate = ProjectStatusUpdate::find($updateId);
            if ($targetUpdate) {
                $this->openHistoryModal($targetUpdate->project_id, $updateId, $historyTab);
            }
        }

        if ($this->hasActiveFilters()) {
            $this->showFilters = true;
        }
        
        // Unread notifications stay unread on general page view until user specifically opens or inspects the update/log.
    }

    public function isSuperAdminUser(?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) return false;

        return $user->isSuperAdmin() || $user->isPmoAdmin() || $user->id === 1;
    }

    public function isProjectManagerOf(Project|int|null $project, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user || !$project) {
            return false;
        }

        $projectModel = $project instanceof Project ? $project : Project::find($project);
        if (!$projectModel) {
            return false;
        }

        if ((int) $projectModel->project_manager_id === (int) $user->id) {
            return true;
        }

        return $projectModel->members()
            ->where('users.id', $user->id)
            ->where('project_members.role', 'lead')
            ->exists();
    }

    public function canViewAllProjectUpdates(Project|int|null $project, ?User $user = null): bool
    {
        $user = $user ?? auth()->user();
        if (!$user) {
            return false;
        }

        if ($this->isSuperAdminUser($user) || $this->hasPortfolioViewAccess($user)) {
            return true;
        }

        return $this->isProjectManagerOf($project, $user);
    }

    public function getVisibleCommentsForUpdate(?ProjectStatusUpdate $update, ?Project $project = null, ?User $user = null)
    {
        if (!$update) {
            return collect();
        }

        $user = $user ?? auth()->user();
        if (!$user) {
            return collect();
        }

        $project = $project ?? $update->project;

        // PMO Admin, Super Admin, Admin Viewer, or this project's Project Manager see all comments
        if ($this->canViewAllProjectUpdates($project, $user)) {
            return $update->comments;
        }

        // Team Member: never see 'pmo_pm' comments.
        // See all 'team' (or legacy non-private) comments on updates of their accessible projects.
        return $update->comments->filter(function ($comment) {
            if ($comment->visibility === 'pmo_pm') {
                return false;
            }

            return $comment->user !== null;
        })->values();
    }

    protected function hasPortfolioViewAccess(?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        return $user?->hasAdminInterfaceAccess() ?? false;
    }

    public function updatedUpdateProjectId()
    {
        $this->updateWbsItemId = null;
        $user = auth()->user();
        $canManageProject = $this->isSuperAdminUser($user) || $this->isProjectManagerOf($this->updateProjectId, $user);

        if (!$canManageProject) {
            $this->updateScopeType = 'task';
            if ($this->updateProjectId && $user) {
                $firstTask = WbsItem::where('project_id', $this->updateProjectId)
                    ->where('assigned_user_id', $user->id)
                    ->orderBy('id')
                    ->first();
                if ($firstTask) {
                    $this->updateWbsItemId = $firstTask->id;
                }
            }
        }
    }

    public function openStatusUpdateModal(?int $projectId = null, ?int $wbsItemId = null)
    {
        $user = auth()->user();

        if ($user?->isAdminViewer()) {
            $this->dispatch('toast', message: 'Admin Viewer access is read-only.', type: 'error');
            return;
        }

        $this->reset([
            'updateProjectId',
            'updateWbsItemId',
            'updateTitle',
            'updateSummary',
            'updateStatus',
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
            $isPmOrAdminOnProject = $this->isSuperAdminUser($user) || $this->isProjectManagerOf($this->updateProjectId, $user);
            if (!$isPmOrAdminOnProject) {
                $this->updateScopeType = 'task';
                $this->updateTitle = 'Task Progress Log — ' . now()->format('M d, Y');
                if ($this->updateProjectId && $user) {
                    $firstTask = WbsItem::where('project_id', $this->updateProjectId)
                        ->where('assigned_user_id', $user->id)
                        ->orderBy('id')
                        ->first();
                    if ($firstTask) {
                        $this->updateWbsItemId = $firstTask->id;
                    }
                }
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

        if ($user?->isAdminViewer()) {
            $this->dispatch('toast', message: 'Admin Viewer access is read-only.', type: 'error');
            return;
        }

        $this->validate([
            'updateProjectId' => 'required|exists:projects,id',
            'updateTitle' => 'required|string|max:255',
            'updateSummary' => 'required|string',
            'updateWbsItemId' => 'nullable|exists:wbs_items,id',
        ]);

        $project = Project::findOrFail($this->updateProjectId);
        $isPmOrAdminOnProject = $this->isSuperAdminUser($user) || $this->isProjectManagerOf($project, $user);

        if (!$isPmOrAdminOnProject) {
            $this->updateScopeType = 'task';
            if ($this->updateWbsItemId) {
                $wbs = WbsItem::find($this->updateWbsItemId);
                if (!$wbs || (int) $wbs->project_id !== (int) $project->id || (int) $wbs->assigned_user_id !== (int) $user->id) {
                    $this->dispatch('toast', message: 'You can only log updates for tasks assigned to you.', type: 'error');
                    return;
                }
            }
        }

        $statusUpdate = ProjectStatusUpdate::create([
            'project_id' => $project->id,
            'wbs_item_id' => ($this->updateScopeType === 'task') ? $this->updateWbsItemId : null,
            'title' => $this->updateTitle,
            'summary' => $this->updateSummary,
            'reporting_period' => now()->format('Y-m-d'),
            'current_progress' => $project->overall_progress,
            'work_completed' => null,
            'current_blockers' => null,
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

        if (!$this->updateWbsItemId && $isPmOrAdminOnProject) {
            $project->update(['status' => $this->updateStatus]);
        }

        // ═══════════════════════════════════════════════════════════════
        // DISPATCH NOTIFICATIONS TO PM AND PMO ADMINS / SUPER ADMINS
        // (Team members do not receive notifications for PM <-> Admin updates)
        // ═══════════════════════════════════════════════════════════════
        try {
            $recipients = collect();

            if ($project->projectManager && (int) $project->projectManager->id !== (int) $user->id) {
                $recipients->push($project->projectManager);
            }

            $superAdmins = User::where(function($q) {
                $q->whereHas('roles', fn($rq) => $rq->whereIn('name', ['super_admin', 'pmo_admin']))
                  ->orWhere('email', 'admin@nexuspm.local')
                  ->orWhere('email', 'superadmin@georgesteuart.com')
                  ->orWhere('id', 1);
            })->where('id', '!=', $user->id)->get();

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

    public function addComment(int $updateId, ?string $visibility = null)
    {
        $user = auth()->user();
        if (!$user || $user->isAdminViewer()) {
            $this->dispatch('toast', message: 'Unauthorized to post comments.', type: 'error');
            return;
        }

        $content = trim($this->newCommentContent[$updateId] ?? '');
        if (empty($content)) {
            $this->dispatch('toast', message: 'Please write a comment before sending.', type: 'warning');
            return;
        }

        $update = ProjectStatusUpdate::with('project.projectManager', 'creator')->findOrFail($updateId);
        $isPmOrAdminOnProject = $this->isSuperAdminUser($user) || $this->isProjectManagerOf($update->project, $user);
        $canAccessProject = $isPmOrAdminOnProject || $this->getAccessibleProjects()->contains('id', (int) $update->project_id);

        if (!$canAccessProject) {
            $this->dispatch('toast', message: 'Unauthorized to comment on this project update.', type: 'error');
            return;
        }

        // Determine visibility: Team Members always post in 'team' thread; PM/Admin can choose 'team' or 'pmo_pm'
        $chosenVisibility = $visibility ?: $this->commentVisibility;
        $finalVisibility = $isPmOrAdminOnProject
            ? (in_array($chosenVisibility, ['pmo_pm', 'team'], true) ? $chosenVisibility : 'team')
            : 'team';

        $comment = Comment::create([
            'commentable_id' => $update->id,
            'commentable_type' => ProjectStatusUpdate::class,
            'user_id' => $user->id,
            'content' => Comment::encodeContentWithVisibility($content, $finalVisibility),
        ]);

        $this->markProjectUpdatesAsRead($update->project_id, $updateId, 'discussion');

        // ═══════════════════════════════════════════════════════════════
        // DISPATCH COMMENT NOTIFICATIONS
        // ═══════════════════════════════════════════════════════════════
        try {
            $commentRecipients = collect();

            // 1. Notify original update author ONLY if visibility is 'team' OR the author is PM/Admin
            if ($update->created_by && (int) $update->created_by !== (int) $user->id) {
                $creator = $update->creator ?? User::find($update->created_by);
                if ($creator) {
                    $creatorIsPmOrAdmin = $this->isSuperAdminUser($creator) || $this->isProjectManagerOf($update->project, $creator);
                    if ($finalVisibility === 'team' || $creatorIsPmOrAdmin) {
                        $commentRecipients->push($creator);
                    }
                }
            }

            // 2. Notify the Project Manager of the project if different from commenter
            if ($update->project && $update->project->projectManager && (int) $update->project->projectManager->id !== (int) $user->id) {
                $commentRecipients->push($update->project->projectManager);
            }

            // 2b. When visibility is 'team' (Both), also notify the task assignee and project team members
            if ($finalVisibility === 'team' && $this->isSuperAdminUser($user) && $update->project) {
                if ($update->wbs_item_id) {
                    $wbsAssignee = WbsItem::with('assignedUser')->find($update->wbs_item_id)?->assignedUser;
                    if ($wbsAssignee && (int) $wbsAssignee->id !== (int) $user->id) {
                        $commentRecipients->push($wbsAssignee);
                    }
                }
                foreach ($update->project->members()->where('users.id', '!=', $user->id)->get() as $member) {
                    $commentRecipients->push($member);
                }
            }

            // 3. Notify all PMO Admins / Super Admins (unless commenter is that admin)
            $superAdmins = User::where(function($q) {
                $q->whereHas('roles', fn($rq) => $rq->whereIn('name', ['super_admin', 'pmo_admin']))
                  ->orWhere('email', 'admin@nexuspm.local')
                  ->orWhere('email', 'superadmin@georgesteuart.com')
                  ->orWhere('id', 1);
            })->where('id', '!=', $user->id)->get();

            foreach ($superAdmins as $admin) {
                $commentRecipients->push($admin);
            }

            $commentRecipients = $commentRecipients->unique('id');

            foreach ($commentRecipients as $recipient) {
                $recipient->notify(new DailyUpdateNotification(
                    updateTitle: $update->title,
                    reporterName: $user->name,
                    projectName: $update->project->name ?? 'Project',
                    url: route('daily-updates.index', ['project' => $update->project_id, 'update' => $update->id, 'tab' => 'discussion']),
                    actionType: 'comment',
                    summary: Str::limit($content, 120),
                    commentId: $comment->id
                ));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to dispatch comment notification: ' . $e->getMessage());
        }

        $this->newCommentContent[$updateId] = '';
        $this->historyTab = 'discussion';
        $this->dispatch('toast', message: 'Feedback comment posted successfully!', type: 'success');
    }

    public function setCommentVisibility(string $visibility): void
    {
        if (in_array($visibility, ['team', 'pmo_pm'], true)) {
            $this->commentVisibility = $visibility;
        }
        $this->historyTab = 'discussion';
    }

    public function deleteUpdate(int $updateId)
    {
        $user = auth()->user();
        $update = ProjectStatusUpdate::with('project')->findOrFail($updateId);

        if ((int) $update->created_by !== (int) $user->id && !$this->isSuperAdminUser($user) && !$this->isProjectManagerOf($update->project, $user)) {
            $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
            return;
        }

        $update->delete();
        $this->dispatch('toast', message: 'Status update removed.', type: 'info');
    }

    public function deleteComment(int $commentId)
    {
        $user = auth()->user();
        $comment = Comment::findOrFail($commentId);

        $isAuthor = ((int) $comment->user_id === (int) $user->id);
        $isSuperAdmin = $this->isSuperAdminUser($user);

        $isPm = false;
        if ($comment->commentable instanceof ProjectStatusUpdate && $comment->commentable->project) {
            $isPm = $this->isProjectManagerOf($comment->commentable->project, $user);
        }

        if (!$isAuthor && !$isSuperAdmin && !$isPm) {
            $this->dispatch('toast', message: 'Unauthorized to delete this comment.', type: 'error');
            return;
        }

        $comment->delete();
        $this->historyTab = 'discussion';
        $this->dispatch('toast', message: 'Feedback comment removed successfully.', type: 'info');
    }

    protected function getAccessibleProjects()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }
        $query = Project::with(['subsidiary', 'projectManager']);

        // PMO Admin and Admin Viewer see every project in the organization.
        if (!$this->hasPortfolioViewAccess($user)) {
            // Nobody (including the PM) sees the project here until the PM accepts it
            $query->where('pm_accepted', true)->where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('members', fn($mq) => $mq->where('users.id', $user->id))
                  ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id));
            });
        }

        return $query->orderByDesc('id')->get();
    }

    public function render()
    {
        $user = auth()->user();
        $isSuperAdmin = $this->isSuperAdminUser($user);
        $hasPortfolioViewAccess = $this->hasPortfolioViewAccess($user);

        // Fetch accessible projects (All projects for Super Admin)
        $accessibleProjects = $this->getAccessibleProjects();
        $accessibleProjectIds = $accessibleProjects->pluck('id')->toArray();

        // Query for status updates feed
        $updatesQuery = ProjectStatusUpdate::with(['project.projectManager', 'project.subsidiary', 'wbsItem', 'creator', 'comments.user']);

        // Scope to accessible projects (all members of accessible projects can view project updates and participate in team discussions)
        $updatesQuery->whereIn('project_id', $accessibleProjectIds);

        if ($this->selectedProjectId) {
            $updatesQuery->where('project_id', $this->selectedProjectId);
        }

        if ($this->selectedSubsidiaryId) {
            $updatesQuery->whereHas('project', function($pq) {
                $pq->where('subsidiary_id', $this->selectedSubsidiaryId);
            });
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
            $q = trim($this->searchQuery);
            $updatesQuery->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhere('work_completed', 'like', "%{$q}%")
                    ->orWhere('current_blockers', 'like', "%{$q}%")
                    ->orWhere('updated_status', 'like', "%{$q}%")
                    ->orWhereHas('creator', fn($cu) => $cu->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
                    ->orWhereHas('project', fn($pr) => $pr->where('name', 'like', "%{$q}%")->orWhere('code', 'like', "%{$q}%"))
                    ->orWhereHas('wbsItem', fn($w) => $w->where('title', 'like', "%{$q}%")->orWhere('wbs_code', 'like', "%{$q}%"))
                    ->orWhereHas('comments', fn($cm) => $cm->where('content', 'like', "%{$q}%"));
            });
        }

        $statusUpdates = $updatesQuery->latest()->get();
        $unreadUpdatesByProject = $user
            ? $this->unreadDailyUpdateCountsByProject($user)
            : collect();

        $groupedProjectUpdates = collect();
        $isSearchingOrFiltering = !empty(trim($this->searchQuery)) || $this->selectedSubsidiaryId !== null || $this->selectedScope !== 'all' || $this->dateFilter !== 'all';

        foreach ($accessibleProjects as $proj) {
            $pUpdates = $statusUpdates->where('project_id', $proj->id)->values();
            
            // Apply subsidiary filter if selected
            if ($this->selectedSubsidiaryId && $proj->subsidiary_id != $this->selectedSubsidiaryId) {
                continue;
            }

            // Apply project filter if selected
            if ($this->selectedProjectId && $this->selectedProjectId != $proj->id) {
                continue;
            }

            // If active search/scope/date filter is applied and no updates match this project, skip project UNLESS the search query matches the project name/code directly
            if ($isSearchingOrFiltering && $pUpdates->isEmpty()) {
                $q = trim($this->searchQuery);
                $matchesProjectNameOrCode = !empty($q) && (
                    Str::contains(strtolower($proj->name), strtolower($q)) ||
                    Str::contains(strtolower($proj->code ?? ''), strtolower($q))
                );
                
                if (!$matchesProjectNameOrCode) {
                    continue;
                }
            }

            $groupedProjectUpdates->push([
                'project' => $proj,
                'latestUpdate' => $pUpdates->first(),
                'allUpdates' => $pUpdates,
                'pastUpdates' => $pUpdates->slice(1),
                'unreadCount' => (int) $unreadUpdatesByProject->get($proj->id, 0),
            ]);
        }

        // Available WBS Tasks for post update modal
        $modalWbsItems = collect();
        if ($this->updateProjectId) {
            $wbsQuery = WbsItem::where('project_id', $this->updateProjectId);

            if (!$isSuperAdmin && $user) {
                $proj = Project::find($this->updateProjectId);
                if ($proj && !$this->isProjectManagerOf($proj, $user)) {
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
        $myUpdatesCount = $user ? $statusUpdates->where('created_by', $user->id)->count() : 0;

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
            'subsidiaries' => \App\Models\Subsidiary::orderBy('name')->get(),
            'showFilters' => $this->showFilters,
            'hasActiveFilters' => $this->hasActiveFilters(),
            'searchQuery' => $this->searchQuery,
            'selectedProjectId' => $this->selectedProjectId,
            'selectedSubsidiaryId' => $this->selectedSubsidiaryId,
            'selectedScope' => $this->selectedScope,
            'dateFilter' => $this->dateFilter,
        ])->layout('layouts.app', ['title' => 'Latest Updates']);
    }
}
