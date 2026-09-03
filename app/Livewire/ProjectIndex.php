<?php

namespace App\Livewire;

use App\Enums\Priority;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $subsidiaryFilter = 'all';
    public string $managerFilter = 'all';
    public string $statusFilter = 'all';
    public string $priorityFilter = 'all';
    public string $healthFilter = 'all';
    public int $perPage = 10;
    public bool $showMoreFilters = false;

    public bool $showModal = false;
    public ?int $editingId = null;

    // Delete Confirmation Modal properties
    public bool $showDeleteModal = false;
    public ?int $projectToDeleteId = null;
    public ?string $projectToDeleteName = null;
    public ?string $projectToDeleteCode = null;

    // Slide-Over Quick Preview Drawer properties
    public bool $showQuickDrawer = false;
    public ?int $drawerProjectId = null;
    public string $drawerQuickNote = '';
    public string $drawerNoteTitle = 'Quick Status Update';

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

    public function mount()
    {
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

        return $user->hasRole('super_admin')
            || \App\Models\Project::where('project_manager_id', $user->id)->exists()
            || $user->email === 'admin@nexuspm.local'
            || $user->id === 1;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['editingId', 'code', 'name', 'description', 'subsidiary_id', 'project_manager_id', 'priority', 'status', 'start_date', 'deadline', 'estimated_budget', 'selectedMembers']);
    }

    public function openCreateModal()
    {
        if (!$this->isAuthorizedUser()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $this->reset(['editingId', 'code', 'name', 'description', 'subsidiary_id', 'project_manager_id', 'priority', 'status', 'start_date', 'deadline', 'estimated_budget', 'selectedMembers']);

        // Default subsidiary and PM for fast creation if available
        $firstSub = Subsidiary::first();
        if ($firstSub) {
            $this->subsidiary_id = $firstSub->id;
        }

        $firstPm = User::where('is_active', true)->first() ?? auth()->user();
        if ($firstPm) {
            $this->project_manager_id = $firstPm->id;
        }

        $this->code = 'PRJ-GST-' . sprintf('%03d', Project::withTrashed()->count() + 1);
        $this->priority = 'medium';
        $this->status = 'planning';
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        if (!$this->isAuthorizedUser()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $project = Project::with('members')->findOrFail($id);

        $this->editingId = $project->id;
        $this->code = $project->code;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->subsidiary_id = $project->subsidiary_id;
        $this->project_manager_id = $project->project_manager_id;
        $this->priority = is_object($project->priority) ? $project->priority->value : (string) $project->priority;
        $this->status = is_object($project->status) ? $project->status->value : (string) $project->status;
        $this->start_date = $project->start_date ? $project->start_date->format('Y-m-d') : null;
        $this->deadline = $project->deadline ? $project->deadline->format('Y-m-d') : null;
        $this->estimated_budget = (string) $project->estimated_budget;
        $this->selectedMembers = $project->members->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function save()
    {
        if (!$this->isAuthorizedUser()) {
            $this->dispatch('toast', message: 'Unauthorized action.', type: 'error');
            return;
        }

        $this->validate();

        $data = [
            'code' => strtoupper($this->code),
            'name' => $this->name,
            'description' => $this->description,
            'subsidiary_id' => $this->subsidiary_id,
            'project_manager_id' => $this->project_manager_id,
            'created_by' => auth()->id(),
            'priority' => $this->priority,
            'status' => $this->status,
            'start_date' => $this->start_date ?: null,
            'deadline' => $this->deadline ?: null,
            'estimated_budget' => $this->estimated_budget ?: 0,
            'health' => ProjectHealth::ON_TRACK,
        ];

        if ($this->editingId) {
            $project = Project::findOrFail($this->editingId);
            $project->update($data);
            $action = 'updated_project';
        } else {
            $project = Project::create($data);
            $action = 'created_project';
        }

        // Sync Project Members
        $membersToSync = array_unique(array_merge([$this->project_manager_id], $this->selectedMembers));
        $syncData = [];
        foreach ($membersToSync as $memberId) {
            $syncData[$memberId] = ['role' => ($memberId == $this->project_manager_id) ? 'lead' : 'member'];
        }
        $project->members()->sync($syncData);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => 'projects',
            'record_type' => Project::class,
            'record_id' => $project->id,
            'new_values' => ['name' => $project->name, 'code' => $project->code],
        ]);

        $this->showModal = false;
        $this->dispatch('toast', message: 'Project ' . ($this->editingId ? 'updated' : 'created') . ' successfully!', type: 'success');
    }

    public function confirmDeleteProject(int $id): void
    {
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

    public function executeDeleteProject(): void
    {
        $user = auth()->user();
        if (!$user || (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1)) {
            $this->dispatch('toast', message: 'Unauthorized. Only PMO Admin can delete projects.', type: 'error');
            $this->cancelDelete();
            return;
        }

        if ($this->projectToDeleteId) {
            $project = Project::find($this->projectToDeleteId);
            if ($project) {
                $pName = $project->name;
                $project->delete();
                $this->dispatch('toast', message: "Project '{$pName}' moved to trash successfully.", type: 'info');
            }
        }

        $this->cancelDelete();
    }

    public function deleteProject(int $id)
    {
        $this->confirmDeleteProject($id);
    }

    public function openQuickDrawer(int $id): void
    {
        $this->drawerProjectId = $id;
        $this->drawerQuickNote = '';
        $this->drawerNoteTitle = 'Quick Status Update';
        $this->showQuickDrawer = true;
    }

    public function closeQuickDrawer(): void
    {
        $this->showQuickDrawer = false;
        $this->drawerProjectId = null;
        $this->drawerQuickNote = '';
    }

    public function saveDrawerQuickUpdate(): void
    {
        $this->validate([
            'drawerQuickNote' => 'required|string|min:3|max:1000',
        ]);

        if (!$this->drawerProjectId) return;

        $project = Project::findOrFail($this->drawerProjectId);
        $user = auth()->user();

        \App\Models\ProjectStatusUpdate::create([
            'project_id'   => $project->id,
            'user_id'      => $user->id,
            'title'        => $this->drawerNoteTitle ?: 'Quick Status Update',
            'summary'      => $this->drawerQuickNote,
            'publish_date' => now()->toDateString(),
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'logged_quick_status_update',
            'module'      => 'projects',
            'record_type' => Project::class,
            'record_id'   => $project->id,
            'new_values'  => ['note' => $this->drawerQuickNote],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        $this->drawerQuickNote = '';
        $this->dispatch('toast', message: '✅ Quick update logged successfully!', type: 'success');
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

        // Base query with strict role-based access scoping
        $baseQuery = Project::query();

        if ($user && !$user->isPmoAdmin()) {
            $baseQuery->where(function($q) use ($user) {
                // If user is the designated PM, they can see it (to review & accept)
                $q->where('project_manager_id', $user->id)
                  // For all other users (members, sponsors, owners, steering committee), the project MUST be accepted by PM first
                  ->orWhere(function($sub) use ($user) {
                      $sub->where('pm_accepted', true)
                          ->where(function($memberSub) use ($user) {
                              $memberSub->whereHas('members', fn($mq) => $mq->where('users.id', $user->id))
                                        ->orWhereHas('wbsItems', fn($wq) => $wq->where('assigned_user_id', $user->id));
                          });
                  });
            });
        }

        // 5 Summary metrics strictly scoped to user's authorized projects
        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('status', 'in_progress')->count();
        $completedCount = (clone $baseQuery)->where('status', 'completed')->count();
        $overdueCount = (clone $baseQuery)->where('deadline', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
        $onHoldCount = (clone $baseQuery)->where('status', 'on_hold')->count();

        // Query for paginated data table
        $query = (clone $baseQuery)->with(['subsidiary', 'projectManager', 'members']);

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
            $query->where('health', $this->healthFilter);
        }

        $projects = $query->latest()->paginate($this->perPage);
        $subsidiaries = Subsidiary::all();
        $pms = User::where('is_active', true)->get();
        $allUsers = User::where('is_active', true)->get();

        $selectedDrawerProject = $this->drawerProjectId 
            ? Project::with(['subsidiary', 'projectManager', 'members', 'wbsItems.assignedUser', 'statusUpdates.user', 'risks'])->find($this->drawerProjectId)
            : null;

        return view('livewire.project-index', compact(
            'projects',
            'subsidiaries',
            'pms',
            'allUsers',
            'selectedDrawerProject',
            'totalCount',
            'activeCount',
            'completedCount',
            'overdueCount',
            'onHoldCount'
        ));
    }
}
