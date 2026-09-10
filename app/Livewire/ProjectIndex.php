<?php

namespace App\Livewire;

use App\Enums\Priority;
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
use Livewire\Component;
use Livewire\WithPagination;

class ProjectIndex extends Component
{
    use WithPagination;

    // View Mode: 'table' (Projects Directory), 'gantt' (Portfolio Gantt), 'stuck' (Stuck Tasks Radar)
    public string $viewMode = 'table';

    // Filters
    public string $search = '';
    public string $subsidiaryFilter = 'all';
    public string $managerFilter = 'all';
    public string $statusFilter = 'all';
    public string $priorityFilter = 'all';
    public int $perPage = 10;
    public bool $showMoreFilters = false;

    // Gantt Timeline Properties
    public string $ganttTimeframe = '6m'; // 'auto', '3m', '6m', '12m'
    public int $ganttMonthOffset = 0;
    public array $expandedGanttProjectIds = [];

    // Stuck Tasks Filter Properties
    public string $stuckTypeFilter = 'all'; // 'all', 'blocked', 'overdue', 'delay_reported', 'on_hold'
    public string $stuckProjectFilter = 'all';
    public string $stuckAssigneeFilter = 'all';
    public string $stuckPriorityFilter = 'all';
    public int $stuckMinDays = 0;

    public bool $showModal = false;
    public ?int $editingId = null;

    // Delete Confirmation Modal properties
    public bool $showDeleteModal = false;
    public ?int $projectToDeleteId = null;
    public ?string $projectToDeleteName = null;
    public ?string $projectToDeleteCode = null;

    // WBS Task Inspector Slide-Over Drawer properties
    public bool $showQuickDrawer = false;
    public ?int $drawerProjectId = null;
    public string $drawerWbsSearch = '';
    public string $drawerWbsStatusFilter = 'all'; // 'all', 'in_progress', 'blocked', 'overdue', 'completed', 'not_started'
    public ?int $selectedInspectorTaskId = null;

    public string $code = '';
    public string $name = '';
    public ?string $description = null;
    public ?int $subsidiary_id = null;
    public ?int $project_manager_id = null;
    public string $priority = 'medium';
    public string $status = 'planning';
    public ?string $start_date = null;
    public ?string $deadline = null;
    public ?string $estimated_budget = null;
    public array $selectedMembers = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'viewMode' => ['except' => 'table'],
        'subsidiaryFilter' => ['except' => 'all'],
        'managerFilter' => ['except' => 'all'],
        'statusFilter' => ['except' => 'all'],
        'priorityFilter' => ['except' => 'all'],
        'stuckTypeFilter' => ['except' => 'all'],
    ];

    public function mount()
    {
        if (request()->query('viewMode') === 'stuck') {
            return redirect()->route('all-tasks.index', ['view' => 'stuck']);
        }

        if (request()->query('viewMode')) {
            $this->viewMode = in_array(request()->query('viewMode'), ['table', 'gantt']) ? request()->query('viewMode') : 'table';
        }

        if (request()->routeIs('projects.create') || request()->query('create') == 1) {
            $this->openCreateModal();
        }
    }

    protected function rules(): array
    {
        return [
            'code' => 'required|string|max:30|unique:projects,code,' . $this->editingId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subsidiary_id' => 'required|exists:subsidiaries,id',
            'project_manager_id' => 'required|exists:users,id',
            'priority' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'estimated_budget' => 'nullable|numeric|min:0',
            'selectedMembers' => 'nullable|array',
        ];
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedSubsidiaryFilter() { $this->resetPage(); }
    public function updatedManagerFilter() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedPriorityFilter() { $this->resetPage(); }
    public function updatedHealthFilter() { $this->resetPage(); }
    public function updatedStuckTypeFilter() { $this->resetPage(); }
    public function updatedStuckProjectFilter() { $this->resetPage(); }
    public function updatedStuckAssigneeFilter() { $this->resetPage(); }
    public function updatedStuckPriorityFilter() { $this->resetPage(); }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['table', 'gantt', 'stuck']) ? $mode : 'table';
        $this->resetPage();
    }

    // Gantt Navigation & Controls
    public function setGanttTimeframe(string $tf): void
    {
        $this->ganttTimeframe = in_array($tf, ['auto', '3m', '6m', '12m']) ? $tf : '6m';
    }

    public function ganttPrev(): void
    {
        $this->ganttMonthOffset -= 1;
    }

    public function ganttNext(): void
    {
        $this->ganttMonthOffset += 1;
    }

    public function ganttToday(): void
    {
        $this->ganttMonthOffset = 0;
    }

    public function toggleGanttExpand(int $projectId): void
    {
        $this->toggleGanttProjectExpand($projectId);
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
        if (!empty($allIds)) {
            $this->expandedGanttProjectIds = $allIds;
        } else {
            $this->expandedGanttProjectIds = Project::pluck('id')->toArray();
        }
    }

    public function collapseAllGanttProjects(): void
    {
        $this->expandedGanttProjectIds = [];
    }

    // Stuck Tasks Controls
    public function setStuckTypeFilter(string $type): void
    {
        $this->stuckTypeFilter = $type;
        $this->resetPage();
    }

    public function resetStuckFilters(): void
    {
        $this->search = '';
        $this->stuckTypeFilter = 'all';
        $this->stuckProjectFilter = 'all';
        $this->stuckAssigneeFilter = 'all';
        $this->stuckPriorityFilter = 'all';
        $this->stuckMinDays = 0;
        $this->resetPage();
    }

    public function nudgeStuckUser(int $wbsItemId): void
    {
        $item = WbsItem::with(['project.projectManager', 'assignedUser'])->findOrFail($wbsItemId);
        $sender = Auth::user();

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

        $this->dispatch('toast', message: "🚨 PMO alert dispatched to " . ($item->assignedUser->name ?? 'the Project Manager') . "!", type: 'success');
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->subsidiaryFilter = 'all';
        $this->managerFilter = 'all';
        $this->statusFilter = 'all';
        $this->priorityFilter = 'all';
        $this->healthFilter = 'all';
        $this->resetPage();
    }

    public function isAuthorizedUser(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole('super_admin') || $user->hasRole('pmo_admin');
    }

    public function openCreateModal(): void
    {
        if (!auth()->user()?->canCreateProject()) {
            $this->dispatch('toast', message: 'You are not authorized to create projects.', type: 'error');
            return;
        }

        $this->resetValidation();
        $this->reset(['code', 'name', 'description', 'subsidiary_id', 'project_manager_id', 'priority', 'status', 'start_date', 'deadline', 'estimated_budget', 'editingId', 'selectedMembers']);
        $this->priority = 'medium';
        $this->status = 'planning';
        
        $count = Project::withTrashed()->count() + 1;
        $this->code = 'GS-PRJ-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $project = Project::with('members')->findOrFail($id);
        
        $user = auth()->user();
        $isSuperAdmin = $user && ($user->hasRole('super_admin') || $user->hasRole('pmo_admin') || $user->id === 1);
        $isPM = $user && $project->project_manager_id === $user->id;

        if (!$isSuperAdmin && !$isPM) {
            $this->dispatch('toast', message: 'You are not authorized to edit this project.', type: 'error');
            return;
        }

        $this->resetValidation();
        $this->editingId = $project->id;
        $this->code = $project->code;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->subsidiary_id = $project->subsidiary_id;
        $this->project_manager_id = $project->project_manager_id;
        $this->priority = $project->priority->value;
        $this->status = $project->status->value;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : null;
        $this->deadline = $project->deadline ? $project->deadline->format('Y-m-d') : null;
        $this->estimated_budget = $project->estimated_budget;
        $this->selectedMembers = $project->members->pluck('id')->toArray();
        
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $user = auth()->user();
        $isSuperAdmin = $user && ($user->hasRole('super_admin') || $user->hasRole('pmo_admin') || $user->id === 1);

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'subsidiary_id' => $this->subsidiary_id,
            'project_manager_id' => $this->project_manager_id,
            'priority' => Priority::from($this->priority),
            'status' => ProjectStatus::from($this->status),
            'start_date' => $this->start_date,
            'deadline' => $this->deadline,
            'estimated_budget' => $this->estimated_budget ?: null,
        ];

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $isPM = $user && $project->project_manager_id === $user->id;

            if (!$isSuperAdmin && !$isPM) {
                $this->dispatch('toast', message: 'Unauthorized.', type: 'error');
                return;
            }

            $oldValues = $project->toArray();
            $project->update($data);
            
            if (isset($this->selectedMembers)) {
                $syncData = [];
                foreach ($this->selectedMembers as $userId) {
                    $syncData[$userId] = ['role' => 'team_member', 'joined_at' => now()];
                }
                $project->members()->sync($syncData);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'project_updated',
                'module' => 'projects',
                'record_type' => Project::class,
                'record_id' => $project->id,
                'old_values' => $oldValues,
                'new_values' => $project->fresh()->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->dispatch('toast', message: 'Project updated successfully!', type: 'success');
        } else {
            if (!$user?->canCreateProject()) {
                $this->dispatch('toast', message: 'You are not authorized to create projects.', type: 'error');
                return;
            }

            $project = Project::create($data);

            if (!empty($this->selectedMembers)) {
                $syncData = [];
                foreach ($this->selectedMembers as $userId) {
                    $syncData[$userId] = ['role' => 'team_member', 'joined_at' => now()];
                }
                $project->members()->sync($syncData);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'project_created',
                'module' => 'projects',
                'record_type' => Project::class,
                'record_id' => $project->id,
                'new_values' => $project->toArray(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->dispatch('toast', message: 'Project created successfully!', type: 'success');
        }

        $this->showModal = false;
        $this->reset(['code', 'name', 'description', 'subsidiary_id', 'project_manager_id', 'priority', 'status', 'start_date', 'deadline', 'estimated_budget', 'editingId', 'selectedMembers']);
    }

    public function confirmDelete(int $id): void
    {
        $user = auth()->user();
        if (!$user || !($user->hasRole('super_admin') || $user->hasRole('pmo_admin') || $user->id === 1)) {
            $this->dispatch('toast', message: 'Only Super Admins & PMO Admins can delete projects.', type: 'error');
            return;
        }

        $project = Project::findOrFail($id);
        $this->projectToDeleteId = $project->id;
        $this->projectToDeleteName = $project->name;
        $this->projectToDeleteCode = $project->code;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->projectToDeleteId = null;
        $this->projectToDeleteName = null;
        $this->projectToDeleteCode = null;
    }

    public function deleteProject(): void
    {
        $user = auth()->user();
        if (!$user || !($user->hasRole('super_admin') || $user->hasRole('pmo_admin') || $user->id === 1)) {
            $this->dispatch('toast', message: 'Only Super Admins & PMO Admins can delete projects.', type: 'error');
            $this->cancelDelete();
            return;
        }

        if (!$this->projectToDeleteId) {
            return;
        }

        $project = Project::findOrFail($this->projectToDeleteId);
        $projectName = $project->name;
        $projectCode = $project->code;

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'project_deleted',
            'module' => 'projects',
            'record_type' => Project::class,
            'record_id' => $project->id,
            'old_values' => $project->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $project->delete();

        $this->cancelDelete();
        $this->dispatch('toast', message: "Project [{$projectCode}] {$projectName} has been deleted successfully.", type: 'success');
    }

    // ═══════════════════════════════════════════════════════════════
    // 🔍 WBS TASK INSPECTOR DRAWER CONTROLS
    // ═══════════════════════════════════════════════════════════════
    public function openQuickDrawer(int $projectId): void
    {
        $this->drawerProjectId = $projectId;
        $this->drawerWbsSearch = '';
        $this->drawerWbsStatusFilter = 'all';
        $this->selectedInspectorTaskId = null;
        $this->showQuickDrawer = true;
    }

    public function closeQuickDrawer(): void
    {
        $this->showQuickDrawer = false;
        $this->drawerProjectId = null;
        $this->drawerWbsSearch = '';
        $this->drawerWbsStatusFilter = 'all';
        $this->selectedInspectorTaskId = null;
    }

    public function selectInspectorTask(?int $taskId): void
    {
        if ($this->selectedInspectorTaskId === $taskId) {
            $this->selectedInspectorTaskId = null;
        } else {
            $this->selectedInspectorTaskId = $taskId;
        }
    }

    public function setDrawerWbsStatusFilter(string $status): void
    {
        $this->drawerWbsStatusFilter = $status;
    }

    public function toggleDrawerTask(int $taskId): void
    {
        $task = \App\Models\WbsItem::findOrFail($taskId);
        
        if ($task->status->value === 'completed') {
            $task->status = \App\Enums\WbsStatus::IN_PROGRESS;
            $task->progress = 50;
        } else {
            $task->status = \App\Enums\WbsStatus::COMPLETED;
            $task->progress = 100;
        }

        $task->save();
        (new \App\Services\ProgressCalculationService())->updateItemProgress($task);

        $this->dispatch('toast', message: "Task '{$task->title}' updated to " . $task->status->label(), type: 'success');
    }

    public function render()
    {
        $user = auth()->user();
        $today = now()->startOfDay();

        // Base query with strict role-based access scoping
        $baseQuery = Project::query();

        if ($user && !$user->isPmoAdmin()) {
            $baseQuery->where(function($q) use ($user) {
                // If user is the designated PM, they can see it
                $q->where('project_manager_id', $user->id)
                  // For all other users, project MUST be accepted by PM first
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->where(function($memberSub) use ($user) {
                              $memberSub->whereHas('members', fn($mq) => $mq->where('users.id', $user->id))
                                        ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id));
                          });
                  });
            });
        }

        // Summary metrics strictly scoped to user's authorized projects
        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('status', 'in_progress')->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();
        $overdueCount = (clone $baseQuery)->where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $onHoldCount = (clone $baseQuery)->where('status', 'on_hold')->count();

        // ═══════════════════════════════════════════════════════════════
        // 🛑 STUCK & BLOCKED TASKS COMPUTATION (ALWAYS COMPUTED FOR BADGE)
        // ═══════════════════════════════════════════════════════════════
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

        if ($user && !$user->isPmoAdmin()) {
            $allStuckTasksQuery->whereHas('project', function($pq) use ($user) {
                $pq->where('project_manager_id', $user->id)
                   ->orWhere(function($subQ) use ($user) {
                       $subQ->where('pm_accepted', true)
                            ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                   });
            });
        }

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

        // Filter Stuck Tasks for the radar view
        $stuckTasks = $allRawStuck->filter(function($task) use ($today) {
            if ($this->stuckTypeFilter === 'blocked' && $task->status->value !== 'blocked') return false;
            if ($this->stuckTypeFilter === 'overdue' && !($task->end_date && $task->end_date->lt($today) && $task->status->value !== 'completed')) return false;
            if ($this->stuckTypeFilter === 'delay_reported' && empty($task->delay_reason)) return false;
            if ($this->stuckTypeFilter === 'on_hold' && $task->status->value !== 'on_hold' && ($task->project?->status->value ?? '') !== 'on_hold') return false;

            if ($this->stuckProjectFilter !== 'all' && (string)$task->project_id !== $this->stuckProjectFilter) return false;
            if ($this->stuckAssigneeFilter !== 'all' && (string)$task->assigned_user_id !== $this->stuckAssigneeFilter) return false;
            if ($this->stuckPriorityFilter !== 'all' && strtolower($task->priority->value ?? '') !== strtolower($this->stuckPriorityFilter)) return false;

            if ($this->search) {
                $term = strtolower($this->search);
                $matchTitle = str_contains(strtolower($task->title ?? ''), $term);
                $matchCode = str_contains(strtolower($task->wbs_code ?? ''), $term);
                $matchProject = str_contains(strtolower($task->project?->name ?? ''), $term) || str_contains(strtolower($task->project?->code ?? ''), $term);
                $matchUser = str_contains(strtolower($task->assignedUser?->name ?? ''), $term);
                $matchReason = str_contains(strtolower($task->delay_reason ?? ''), $term);
                if (!$matchTitle && !$matchCode && !$matchProject && !$matchUser && !$matchReason) return false;
            }

            return true;
        })->map(function($task) use ($today) {
            $overdueDays = 0;
            if ($task->end_date && $task->end_date->lt($today) && $task->status->value !== 'completed') {
                $overdueDays = (int) $task->end_date->diffInDays($today);
            }
            $task->computed_overdue_days = $overdueDays;

            $pVal = strtolower($task->priority->value ?? 'medium');
            if ($pVal === 'critical' || $pVal === 'urgent' || $overdueDays >= 14 || $task->status->value === 'blocked') {
                $task->computed_impact = 'Critical';
            } elseif ($pVal === 'high' || $overdueDays >= 7) {
                $task->computed_impact = 'High';
            } elseif ($pVal === 'medium' || $overdueDays > 0) {
                $task->computed_impact = 'Medium';
            } else {
                $task->computed_impact = 'Low';
            }

            return $task;
        });

        $stuckMembersCount = $stuckTasks->pluck('assigned_user_id')->filter()->unique()->count();

        // ═══════════════════════════════════════════════════════════════
        // 📊 QUERY FOR PAGINATED PROJECTS TABLE
        // ═══════════════════════════════════════════════════════════════
        $query = (clone $baseQuery)->with(['subsidiary', 'projectManager', 'members', 'risks', 'wbsItems']);

        if ($this->search) {
            $query->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
                ->orWhereHas('projectManager', fn($mq) => $mq->where('name', 'like', "%{$this->search}%"))
            );
        }

        if ($this->subsidiaryFilter !== 'all') {
            $query->where('subsidiary_id', $this->subsidiaryFilter);
        }

        if ($this->managerFilter !== 'all') {
            $query->where('project_manager_id', $this->managerFilter);
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('priority', $this->priorityFilter);
        }

        if ($this->healthFilter !== 'all') {
            if ($this->healthFilter === 'at_risk') {
                $query->where(function($q) {
                    $q->where('health', 'at_risk')
                      ->orWhereHas('risks', fn($rq) => $rq->where('status', 'open'))
                      ->orWhereHas('wbsItems', fn($wq) => $wq->where('status', 'blocked')->orWhereHas('blockers', fn($bq) => $bq->where('status', 'open')));
                });
            } elseif ($this->healthFilter === 'delayed') {
                $query->where(function($q) {
                    $q->where('health', 'delayed')
                      ->orWhere('health', 'critical')
                      ->orWhere(fn($sq) => $sq->where('deadline', '<', now())->whereNotIn('status', ['completed', 'cancelled']));
                });
            } else {
                $query->where('health', $this->healthFilter);
            }
        }

        $projects = $query->latest()->paginate($this->perPage);
        $subsidiaries = Subsidiary::all();
        $pms = User::where('is_active', true)->get();
        $allUsers = User::where('is_active', true)->get();

        // ═══════════════════════════════════════════════════════════════
        // 🔍 WBS TASK INSPECTOR DATA PREPARATION
        // ═══════════════════════════════════════════════════════════════
        $selectedDrawerProject = $this->drawerProjectId 
            ? Project::with([
                'subsidiary',
                'projectManager',
                'members',
                'wbsItems' => function($q) {
                    $q->with(['assignedUser', 'blockers.reporter', 'children.assignedUser'])
                      ->orderBy('sort_order')
                      ->orderBy('wbs_code');
                },
                'risks'
            ])->find($this->drawerProjectId)
            : null;

        $drawerWbsItems = collect();
        $drawerWbsStats = [
            'total' => 0,
            'completed' => 0,
            'in_progress' => 0,
            'blocked' => 0,
            'overdue' => 0,
            'not_started' => 0,
        ];
        $selectedInspectorTask = null;

        if ($selectedDrawerProject) {
            $rawWbs = $selectedDrawerProject->wbsItems;

            $drawerWbsStats['total'] = $rawWbs->count();
            $drawerWbsStats['completed'] = $rawWbs->where('status.value', 'completed')->count();
            $drawerWbsStats['in_progress'] = $rawWbs->where('status.value', 'in_progress')->count();
            $drawerWbsStats['blocked'] = $rawWbs->filter(fn($t) => $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0))->count();
            $drawerWbsStats['overdue'] = $rawWbs->filter(fn($t) => $t->status->value !== 'completed' && $t->status->value !== 'cancelled' && $t->end_date && $t->end_date->lt($today))->count();
            $drawerWbsStats['not_started'] = $rawWbs->where('status.value', 'not_started')->count();

            $drawerWbsItems = $rawWbs->filter(function($t) use ($today) {
                if ($this->drawerWbsStatusFilter === 'in_progress' && $t->status->value !== 'in_progress') return false;
                if ($this->drawerWbsStatusFilter === 'completed' && $t->status->value !== 'completed') return false;
                if ($this->drawerWbsStatusFilter === 'not_started' && $t->status->value !== 'not_started') return false;
                if ($this->drawerWbsStatusFilter === 'blocked' && !($t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0))) return false;
                if ($this->drawerWbsStatusFilter === 'overdue' && !($t->status->value !== 'completed' && $t->status->value !== 'cancelled' && $t->end_date && $t->end_date->lt($today))) return false;

                if ($this->drawerWbsSearch) {
                    $term = strtolower($this->drawerWbsSearch);
                    $matchTitle = str_contains(strtolower($t->title ?? ''), $term);
                    $matchCode = str_contains(strtolower($t->wbs_code ?? ''), $term);
                    $matchUser = str_contains(strtolower($t->assignedUser->name ?? ''), $term);
                    $matchDesc = str_contains(strtolower($t->description ?? ''), $term);
                    if (!$matchTitle && !$matchCode && !$matchUser && !$matchDesc) return false;
                }

                return true;
            })->map(function($t) use ($today) {
                $isOverdue = $t->end_date && $t->end_date->lt($today) && $t->status->value !== 'completed' && $t->status->value !== 'cancelled';
                $t->is_overdue = $isOverdue;
                $t->overdue_days = $isOverdue ? (int) $t->end_date->diffInDays($today) : 0;
                $t->has_active_blocker = $t->status->value === 'blocked' || ($t->blockers && $t->blockers->where('status', 'open')->count() > 0);
                return $t;
            });

            if ($this->selectedInspectorTaskId) {
                $selectedInspectorTask = $rawWbs->firstWhere('id', $this->selectedInspectorTaskId);
            }
        }

        // ═══════════════════════════════════════════════════════════════
        // 📈 GANTT TIMELINE RADAR CALCULATION
        // ═══════════════════════════════════════════════════════════════
        $allGanttProjectsQuery = (clone $baseQuery)->with(['subsidiary', 'projectManager', 'wbsItems.assignedUser']);

        if ($this->search) {
            $allGanttProjectsQuery->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
                ->orWhereHas('projectManager', fn($mq) => $mq->where('name', 'like', "%{$this->search}%"))
            );
        }

        if ($this->subsidiaryFilter !== 'all') {
            $allGanttProjectsQuery->where('subsidiary_id', $this->subsidiaryFilter);
        }

        if ($this->managerFilter !== 'all') {
            $allGanttProjectsQuery->where('project_manager_id', $this->managerFilter);
        }

        if ($this->statusFilter !== 'all') {
            $allGanttProjectsQuery->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter !== 'all') {
            $allGanttProjectsQuery->where('priority', $this->priorityFilter);
        }

        if ($this->healthFilter !== 'all') {
            $allGanttProjectsQuery->where('health', $this->healthFilter);
        }

        $ganttProjectsList = $allGanttProjectsQuery->get();

        $now = now();
        if ($this->ganttTimeframe === 'auto') {
            $allStarts = $ganttProjectsList->pluck('start_date')->filter()->map(fn($d) => Carbon::parse($d));
            $allEnds   = $ganttProjectsList->map(fn($p) => $p->deadline ? Carbon::parse($p->deadline) : ($p->start_date ? Carbon::parse($p->start_date)->addMonth() : null))->filter();

            $minStart = $allStarts->min() ?? $now->copy()->startOfMonth();
            $maxEnd   = $allEnds->max() ?? $now->copy()->addMonths(4)->endOfMonth();

            if ($now->lt($minStart)) $minStart = $now->copy();
            if ($now->gt($maxEnd)) $maxEnd = $now->copy();

            $ganttStart = $minStart->copy()->startOfMonth();
            $ganttEnd   = $maxEnd->copy()->addMonth()->endOfMonth();
            $ganttMonthCount = (int) max(3, ceil($ganttStart->diffInMonths($ganttEnd)) + 1);
        } else {
            $ganttMonthCount = match($this->ganttTimeframe) {
                '3m' => 3,
                '12m' => 12,
                default => 6,
            };
            $baseStartMonthsBack = match($this->ganttTimeframe) {
                '3m' => 1,
                '12m' => 2,
                default => 1,
            };
            $anchorMonth = $now->copy()->startOfMonth()->addMonths($this->ganttMonthOffset);
            $ganttStart = $anchorMonth->copy()->subMonths($baseStartMonthsBack)->startOfMonth();
            $ganttEnd   = $ganttStart->copy()->addMonths($ganttMonthCount - 1)->endOfMonth();
        }

        $totalGanttDays = (int) max(1, $ganttStart->diffInDays($ganttEnd) + 1);

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

        $ganttProjectsData = $ganttProjectsList->map(function($project) use ($ganttStart, $ganttEnd, $totalGanttDays, $now) {
            $pStart = $project->start_date 
                ? Carbon::parse($project->start_date)->startOfDay() 
                : ($project->created_at ? Carbon::parse($project->created_at)->startOfDay() : $now->copy()->startOfDay());
            
            $pEnd = $project->deadline 
                ? Carbon::parse($project->deadline)->endOfDay() 
                : ($project->end_date ? Carbon::parse($project->end_date)->endOfDay() : $pStart->copy()->addDays(30)->endOfDay());

            if ($pEnd->lt($pStart)) {
                $pEnd = $pStart->copy()->addDays(14)->endOfDay();
            }

            $startsBefore = $pStart->lt($ganttStart);
            $endsAfter    = $pEnd->gt($ganttEnd);
            $isEntirelyBefore = $pEnd->lt($ganttStart);
            $isEntirelyAfter  = $pStart->gt($ganttEnd);

            $vStart = $startsBefore ? $ganttStart->copy() : $pStart->copy();
            $vEnd   = $endsAfter ? $ganttEnd->copy() : $pEnd->copy();

            if ($isEntirelyBefore) {
                $leftPct = 0;
                $widthPct = 1.8;
            } elseif ($isEntirelyAfter) {
                $leftPct = 98.2;
                $widthPct = 1.8;
            } else {
                $leftDays = $ganttStart->diffInDays($vStart);
                $leftPct = round(($leftDays / $totalGanttDays) * 100, 2);
                $vDays = max(1, $vStart->diffInDays($vEnd) + 1);
                $widthPct = max(2.5, round(($vDays / $totalGanttDays) * 100, 2));
                if ($leftPct + $widthPct > 100) {
                    $widthPct = max(2.5, 100 - $leftPct);
                }
            }

            $progress = (int) ($project->overall_progress ?? 0);
            $isOverdue = $pEnd->lt($now->startOfDay()) && $progress < 100;
            $daysRemaining = (int) $now->startOfDay()->diffInDays($pEnd, false);

            $wbsTasks = [];
            if (in_array($project->id, $this->expandedGanttProjectIds)) {
                $wbsTasks = $project->wbsItems()
                    ->with('assignedUser')
                    ->orderBy('sort_order')
                    ->orderBy('wbs_code')
                    ->get()
                    ->map(function($item) use ($ganttStart, $ganttEnd, $totalGanttDays, $now, $pStart) {
                        $tStart = $item->start_date ? Carbon::parse($item->start_date)->startOfDay() : $pStart->copy();
                        $tEnd   = $item->end_date ? Carbon::parse($item->end_date)->endOfDay() : $tStart->copy()->addDay()->endOfDay();
                        if ($tEnd->lt($tStart)) $tEnd = $tStart->copy()->addDay()->endOfDay();

                        $tStartsBefore = $tStart->lt($ganttStart);
                        $tEndsAfter    = $tEnd->gt($ganttEnd);
                        $tIsEntirelyBefore = $tEnd->lt($ganttStart);
                        $tIsEntirelyAfter  = $tStart->gt($ganttEnd);

                        $tvStart = $tStartsBefore ? $ganttStart->copy() : $tStart->copy();
                        $tvEnd   = $tEndsAfter ? $ganttEnd->copy() : $tEnd->copy();

                        if ($tIsEntirelyBefore) {
                            $tLeftPct = 0;
                            $tWidthPct = 1.2;
                        } elseif ($tIsEntirelyAfter) {
                            $tLeftPct = 98.8;
                            $tWidthPct = 1.2;
                        } else {
                            $tLeftDays = $ganttStart->diffInDays($tvStart);
                            $tLeftPct = round(($tLeftDays / $totalGanttDays) * 100, 2);
                            $tvDays = max(1, $tvStart->diffInDays($tvEnd) + 1);
                            $tWidthPct = max(1.8, round(($tvDays / $totalGanttDays) * 100, 2));
                            if ($tLeftPct + $tWidthPct > 100) {
                                $tWidthPct = max(1.8, 100 - $tLeftPct);
                            }
                        }

                        $itemStatus = is_object($item->status) ? $item->status->value : (string) $item->status;
                        $isOverdue  = $tEnd->lt($now->startOfDay()) && $itemStatus !== 'completed';
                        $itemType   = is_object($item->item_type) ? $item->item_type->value : (string) $item->item_type;

                        return [
                            'item'             => $item,
                            'title'            => $item->title,
                            'wbs_code'         => $item->wbs_code,
                            'status'           => $itemStatus,
                            'item_type'        => $itemType,
                            'is_milestone'     => (bool) $item->is_milestone,
                            'progress'         => (int) ($item->progress_percentage ?? 0),
                            'start_date'       => $tStart,
                            'end_date'         => $tEnd,
                            'assigned_user'    => $item->assignedUser,
                            'left_pct'         => $tLeftPct,
                            'width_pct'        => $tWidthPct,
                            'is_overdue'       => $isOverdue,
                            'starts_before'    => $tStartsBefore,
                            'ends_after'       => $tEndsAfter,
                            'is_out_of_bounds' => ($tIsEntirelyBefore || $tIsEntirelyAfter),
                        ];
                    })->toArray();
            }

            return [
                'project'          => $project,
                'start_date'       => $pStart,
                'end_date'         => $pEnd,
                'left_pct'         => $leftPct,
                'width_pct'        => $widthPct,
                'progress'         => $progress,
                'is_overdue'       => $isOverdue,
                'days_remaining'   => $daysRemaining,
                'starts_before'    => $startsBefore,
                'ends_after'       => $endsAfter,
                'is_out_of_bounds' => ($isEntirelyBefore || $isEntirelyAfter),
                'is_expanded'      => in_array($project->id, $this->expandedGanttProjectIds),
                'wbs_tasks'        => $wbsTasks,
            ];
        });

        $ganttTimeline = [
            'start'         => $ganttStart,
            'end'           => $ganttEnd,
            'total_days'    => $totalGanttDays,
            'months'        => $ganttMonths,
            'projects'      => $ganttProjectsData,
            'today_pct'     => $ganttTodayPct,
            'today_visible' => $ganttTodayVisible,
        ];

        return view('livewire.project-index', compact(
            'projects',
            'subsidiaries',
            'pms',
            'allUsers',
            'selectedDrawerProject',
            'drawerWbsItems',
            'drawerWbsStats',
            'selectedInspectorTask',
            'totalCount',
            'activeCount',
            'completedCount',
            'overdueCount',
            'onHoldCount',
            'totalStuckTasksCount',
            'blockedTasksOnlyCount',
            'overdueTasksOnlyCount',
            'onHoldTasksCount',
            'delayReportedCount',
            'stuckTasks',
            'stuckMembersCount',
            'stuckProjectsList',
            'stuckAssigneesList',
            'ganttTimeline'
        ));
    }
}
