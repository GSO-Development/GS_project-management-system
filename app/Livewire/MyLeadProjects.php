<?php

namespace App\Livewire;

use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Services\ProjectDelayAnalysisService;
use Livewire\Component;
use Livewire\WithPagination;

class MyLeadProjects extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'all';
    public string $roleFilter = 'all';
    public int $perPage = 10;

    public bool $showReviewModal = false;
    public ?int $reviewProjectId = null;
    public bool $showRejectModal = false;
    public string $rejectionReasonInput = '';
    public bool $showDelayAnalysis = false;
    public ?int $delayProjectId = null;
    public array $delayAnalysis = [];

    public bool $showStatusConfirmModal = false;
    public ?int $statusUpdateProjectId = null;
    public ?string $statusUpdateProjectName = null;
    public ?string $statusUpdateOldValue = null;
    public ?string $statusUpdateNewValue = null;

    protected $queryString = [
        'roleFilter'   => ['except' => 'all', 'as' => 'role'],
        'statusFilter' => ['except' => 'all', 'as' => 'status'],
        'search'       => ['except' => ''],
    ];

    public function mount()
    {
        if (auth()->user()?->hasAdminInterfaceAccess()) {
            return redirect()->route('projects.index');
        }

        if (request()->has('role')) {
            $this->roleFilter = request('role');
        }
        if (request()->has('status')) {
            $this->statusFilter = request('status') === 'overdue' ? 'delayed' : request('status');
        }
        if ($delayProjectId = request()->integer('delay_project')) {
            $this->openDelayAnalysis($delayProjectId, app(ProjectDelayAnalysisService::class));
        }

        Project::syncAllProjectStatuses();
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedStatusFilter() { $this->resetPage(); }
    public function updatedRoleFilter() { $this->resetPage(); }
    public function updatedPerPage() { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->roleFilter = 'all';
        $this->resetPage();
    }

    public function openReviewModal(int $projectId): void
    {
        $this->reviewProjectId = $projectId;
        $this->rejectionReasonInput = '';
        $this->showRejectModal = false;
        $this->showReviewModal = true;
    }

    public function notifyWorkspaceLocked(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $user = auth()->user();

        abort_unless(
            $user && $project->project_manager_id === $user->id && !$project->isPmAccepted(),
            403
        );

        $this->dispatch(
            'toast',
            message: "Accept the project leadership assignment before opening the {$project->name} workspace.",
            type: 'warning'
        );
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->showRejectModal = false;
        $this->reviewProjectId = null;
        $this->rejectionReasonInput = '';
    }

    public function acceptProject(int $projectId): void
    {
        $project = Project::findOrFail($projectId);
        $user = auth()->user();

        abort_unless($project->canApproveAssignment($user), 403, 'Only the assigned Project Manager can approve this assignment.');

        abort_unless($project->acceptByPm($user), 403);
        $this->closeReviewModal();
        $this->dispatch('toast', message: '🎉 Project leadership accepted! You can now access and manage this project workspace.', type: 'success');
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
            'rejectionReasonInput.required' => 'Please explain the issue or reason for declining this assignment.',
            'rejectionReasonInput.min' => 'The reason must be at least 5 characters.',
        ]);

        $project = Project::findOrFail($this->reviewProjectId);
        $user = auth()->user();

        if ($project->project_manager_id !== $user->id) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        abort_unless($project->rejectByPm($user, $this->rejectionReasonInput), 403);
        $this->closeReviewModal();
        $this->dispatch('toast', message: 'Project assignment declined. PMO Admin has been notified of your issue report.', type: 'warning');
    }

    public function openDelayAnalysis(int $projectId, ProjectDelayAnalysisService $service): void
    {
        $project = Project::findOrFail($projectId);
        $user = auth()->user();

        abort_unless(
            $project->project_manager_id === $user->id,
            403
        );

        $this->delayProjectId = $project->id;
        $this->delayAnalysis = $service->analyze($project) + [
            'project_name' => $project->name,
            'project_code' => $project->code,
        ];
        $this->showDelayAnalysis = true;
    }

    public function closeDelayAnalysis(): void
    {
        $this->showDelayAnalysis = false;
        $this->delayProjectId = null;
        $this->delayAnalysis = [];
    }

    public function promptProjectStatusChange(int $projectId, string $newValue): void
    {
        $project = Project::findOrFail($projectId);
        $user = auth()->user();
        $newStatus = ProjectStatus::tryFrom($newValue);
        $editableStatuses = [
            ProjectStatus::PLANNING,
            ProjectStatus::IN_PROGRESS,
            ProjectStatus::COMPLETED,
            ProjectStatus::AT_RISK,
            ProjectStatus::DELAYED,
        ];

        if (!$user || $project->project_manager_id !== $user->id || !$project->isPmAccepted()) {
            $this->dispatch('toast', message: 'Only the assigned Project Manager can change this project status.', type: 'error');
            return;
        }

        if (!$newStatus || !in_array($newStatus, $editableStatuses, true) || $project->status === $newStatus) {
            return;
        }

        $this->statusUpdateProjectId = $project->id;
        $this->statusUpdateProjectName = $project->name;
        $this->statusUpdateOldValue = $project->status->value;
        $this->statusUpdateNewValue = $newStatus->value;
        $this->showStatusConfirmModal = true;
    }

    public function confirmProjectStatusChange(): void
    {
        $project = $this->statusUpdateProjectId ? Project::find($this->statusUpdateProjectId) : null;
        $user = auth()->user();
        $newStatus = $this->statusUpdateNewValue ? ProjectStatus::tryFrom($this->statusUpdateNewValue) : null;
        $editableStatuses = [
            ProjectStatus::PLANNING,
            ProjectStatus::IN_PROGRESS,
            ProjectStatus::COMPLETED,
            ProjectStatus::AT_RISK,
            ProjectStatus::DELAYED,
        ];

        if (!$this->showStatusConfirmModal || !$project || !$user
            || $project->project_manager_id !== $user->id || !$project->isPmAccepted()
            || $project->status->value !== $this->statusUpdateOldValue
            || !$newStatus || !in_array($newStatus, $editableStatuses, true)) {
            $this->cancelProjectStatusChange();
            $this->dispatch('toast', message: 'This status change is no longer available. Refresh the project list and try again.', type: 'error');
            return;
        }

        $oldStatus = $project->status;
        $project->status = $newStatus;
        if ($newStatus === ProjectStatus::COMPLETED) {
            $project->overall_progress = 100;
        }
        $project->save();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'project_status_updated',
            'module' => 'projects',
            'record_type' => Project::class,
            'record_id' => $project->id,
            'previous_values' => ['status' => $oldStatus->value],
            'new_values' => ['status' => $newStatus->value],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $projectCode = $project->code;
        $this->cancelProjectStatusChange();
        $this->dispatch('toast', message: "Status for [{$projectCode}] updated to {$newStatus->label()}!", type: 'success');
    }

    public function cancelProjectStatusChange(): void
    {
        $this->showStatusConfirmModal = false;
        $this->statusUpdateProjectId = null;
        $this->statusUpdateProjectName = null;
        $this->statusUpdateOldValue = null;
        $this->statusUpdateNewValue = null;
    }
    public function render()
    {
        $user = auth()->user();

        // Base query: all projects this user is involved in (any role)
        if ($user->isPmoAdmin()) {
            // PMO Admin sees all projects
            $baseQuery = Project::query()->with(['subsidiary', 'projectManager', 'members', 'wbsItems', 'approvalRequests']);
        } else {
            // Regular users:
            // - If user is the designated PM: sees their assigned projects (both accepted and pending acceptance)
            // - If user is any other member (sponsor, owner, committee, team member): ONLY sees projects where pm_accepted is true
            $baseQuery = Project::query()
                ->where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('pm_accepted', true)
                               ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id));
                      });
                })
                ->with(['subsidiary', 'projectManager', 'members', 'wbsItems', 'approvalRequests']);
        }

        // Summary counts (before filters)
        $totalCount      = (clone $baseQuery)->count();
        $activeCount     = (clone $baseQuery)->where('status', 'in_progress')->count();
        $overdueCount    = (clone $baseQuery)->where('status', 'delayed')->count();
        $completedCount  = (clone $baseQuery)->where('status', 'completed')->count();

        // Dynamic Role breakdown counts (before filters)
        $allRoles = \App\Services\RbacService::getAllRoles();

        // Project-level roles (exclude system-wide administration roles)
        $projectRoles = collect($allRoles)->reject(function($rMeta, $rCode) {
            return in_array($rCode, ['pmo_admin', 'super_admin', 'admin_viewer', 'project_manager', 'team_member'], true);
        });

        $roleCounts = [];
        $availableRoles = [];

        foreach ($projectRoles as $rCode => $rMeta) {
            $roleLabel = match($rCode) {
                'lead' => 'Project Manager (PM)',
                'member' => 'Team Member',
                'sponsor' => 'Project Sponsor',
                'owner' => 'Project Owner',
                'steering_committee' => 'Steering Committee',
                default => $rMeta['name'] ?? ucwords(str_replace(['_', '-'], ' ', $rCode)),
            };

            if ($rCode === 'lead') {
                $count = (clone $baseQuery)->where('project_manager_id', $user->id)->count();
            } elseif ($rCode === 'member') {
                $count = (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                    ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->whereIn('project_members.role', ['member', 'team_member', 'participant']))
                    ->count();
            } else {
                $count = (clone $baseQuery)->where('project_manager_id', '!=', $user->id)
                    ->whereHas('members', fn($mq) => $mq->where('users.id', $user->id)->where('project_members.role', $rCode))
                    ->count();
            }

            $roleCounts[$rCode] = $count;
            $availableRoles[$rCode] = [
                'code'  => $rCode,
                'label' => $roleLabel,
                'count' => $count,
            ];
        }

        // Sort: lead, sponsor, owner, steering_committee, member, then custom roles alphabetically
        $priorityOrder = [
            'lead' => 1,
            'sponsor' => 2,
            'owner' => 3,
            'steering_committee' => 4,
            'member' => 5,
        ];

        uksort($availableRoles, function ($a, $b) use ($priorityOrder, $availableRoles) {
            $posA = $priorityOrder[$a] ?? 99;
            $posB = $priorityOrder[$b] ?? 99;
            if ($posA === $posB) {
                return strcmp($availableRoles[$a]['label'] ?? $a, $availableRoles[$b]['label'] ?? $b);
            }
            return $posA <=> $posB;
        });

        // If the currently filtered role was deleted or no longer exists, safely reset filter to 'all'
        if ($this->roleFilter !== 'all' && !isset($availableRoles[$this->roleFilter])) {
            $this->roleFilter = 'all';
        }

        // Role filter
        if ($this->roleFilter !== 'all') {
            if ($this->roleFilter === 'lead') {
                $baseQuery->where('project_manager_id', $user->id);
            } elseif ($this->roleFilter === 'member') {
                $baseQuery->whereHas('members', fn($mq) =>
                    $mq->where('users.id', $user->id)->whereIn('project_members.role', ['member', 'team_member', 'participant'])
                );
            } else {
                $baseQuery->whereHas('members', fn($mq) =>
                    $mq->where('users.id', $user->id)->where('project_members.role', $this->roleFilter)
                );
            }
        }

        // Search
        if ($this->search) {
            $baseQuery->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%")
                ->orWhereHas('subsidiary', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
            );
        }

        // Status filter ('overdue' is the legacy name of the 'delayed' status)
        if ($this->statusFilter !== 'all') {
            $baseQuery->where('status', $this->statusFilter === 'overdue' ? 'delayed' : $this->statusFilter);
        }

        $projects = $baseQuery->latest()->paginate($this->perPage);
        $projects->getCollection()->each(fn (Project $p) => $p->syncAutoStatus(true));

        $delayService = app(ProjectDelayAnalysisService::class);
        // Detailed delay data belongs only to projects led by this user.
        $delaySummaries = $projects->getCollection()
            ->where('project_manager_id', $user->id)
            ->mapWithKeys(
            fn (Project $project) => [$project->id => $delayService->analyze($project)]
        );

        // Compute user role per project for display
        $userRoles = [];
        foreach ($projects as $p) {
            if ($user->isPmoAdmin()) {
                $userRoles[$p->id] = 'pmo_admin';
            } elseif ($p->project_manager_id === $user->id) {
                $userRoles[$p->id] = 'lead';
            } else {
                $userRoles[$p->id] = $p->getUserRole($user) ?? 'member';
            }
        }

        $reviewProject = $this->reviewProjectId ? Project::with(['subsidiary', 'projectManager', 'template.tasks', 'wbsItems', 'members'])->find($this->reviewProjectId) : null;

        return view('livewire.my-lead-projects', compact(
            'projects',
            'reviewProject',
            'totalCount',
            'activeCount',
            'overdueCount',
            'completedCount',
            'roleCounts',
            'availableRoles',
            'userRoles',
            'delaySummaries'
        ))->title('My Projects — GS NexusPM');
    }
}
