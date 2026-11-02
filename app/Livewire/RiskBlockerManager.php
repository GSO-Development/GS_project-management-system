<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;

class RiskBlockerManager extends Component
{
    // Filter State
    public ?int $selectedProjectId = null;
    public string $selectedCategory = '';
    public string $selectedStatus = 'all';
    public string $searchQuery = '';
    public ?string $matrixFilterProb = null;
    public ?string $matrixFilterImp = null;
    public bool $showFilters = false;

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function hasActiveFilters(): bool
    {
        return !empty(trim($this->searchQuery))
            || !empty($this->selectedProjectId)
            || !empty($this->selectedCategory)
            || $this->selectedStatus !== 'all'
            || !empty($this->matrixFilterProb)
            || !empty($this->matrixFilterImp);
    }
    public function resetAllFilters(): void
    {
        $this->searchQuery = '';
        $this->selectedProjectId = null;
        $this->selectedCategory = '';
        $this->selectedStatus = 'all';
        $this->matrixFilterProb = null;
        $this->matrixFilterImp = null;
    }

    // Risk Modal State
    public bool $showAddRiskModal = false;
    public bool $showEditRiskModal = false;
    public ?int $editingRiskId = null;

    // Risk Form Fields
    public ?int $riskProjectId = null;
    public ?int $riskWbsItemId = null;
    public string $riskTitle = '';
    public string $riskCategory = 'Technical';
    public string $riskProbability = 'medium';
    public string $riskImpact = 'medium';
    public ?int $riskOwnerId = null;
    public string $riskMitigation = '';
    public string $riskContingency = '';
    public string $riskDescription = '';

    protected $queryString = [
        'selectedProjectId' => ['except' => null, 'as' => 'project'],
        'searchQuery' => ['except' => '', 'as' => 'q'],
    ];

    public function mount()
    {
        $user = auth()->user();
        if (\App\Models\Project::where('project_manager_id', $user->id)->exists() && !$user->hasRole('super_admin')) {
            $firstProject = Project::where('project_manager_id', $user->id)->first();
            if ($firstProject && !$this->selectedProjectId) {
                // Default to all or user's first project
            }
        }
    }

    public function resetMatrixFilter()
    {
        $this->matrixFilterProb = null;
        $this->matrixFilterImp = null;
    }

    public function filterMatrixCell(string $prob, string $imp)
    {
        if ($this->matrixFilterProb === $prob && $this->matrixFilterImp === $imp) {
            $this->resetMatrixFilter();
        } else {
            $this->matrixFilterProb = $prob;
            $this->matrixFilterImp = $imp;
        }
    }

    public function openAddRiskModal(?int $projectId = null)
    {
        $this->resetRiskForm();
        if ($projectId) {
            $this->riskProjectId = $projectId;
        } elseif ($this->selectedProjectId) {
            $this->riskProjectId = $this->selectedProjectId;
        } else {
            $firstProj = Project::whereHas('subsidiary')->orWhereNotNull('id')->first();
            if ($firstProj) {
                $this->riskProjectId = $firstProj->id;
            }
        }
        $this->riskOwnerId = auth()->id();
        $this->showAddRiskModal = true;
    }

    public function resetRiskForm()
    {
        $this->reset([
            'editingRiskId',
            'riskProjectId',
            'riskWbsItemId',
            'riskTitle',
            'riskCategory',
            'riskProbability',
            'riskImpact',
            'riskOwnerId',
            'riskMitigation',
            'riskContingency',
            'riskDescription',
        ]);
        $this->riskCategory = 'Technical';
        $this->riskProbability = 'medium';
        $this->riskImpact = 'medium';
    }

    public function addRisk()
    {
        $this->validate([
            'riskProjectId' => 'required|exists:projects,id',
            'riskTitle' => 'required|string|max:255',
            'riskCategory' => 'required|string',
            'riskDescription' => 'required|string',
            'riskWbsItemId' => 'nullable|exists:wbs_items,id',
            'riskOwnerId' => 'nullable|exists:users,id',
            'riskMitigation' => 'nullable|string',
            'riskContingency' => 'nullable|string',
        ]);

        $project = Project::findOrFail($this->riskProjectId);
        if (!$project->userCan(auth()->user(), 'risk.create') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1) {
            $this->dispatch('toast', message: '🔒 You do not have permission to create risks for this project.', type: 'error');
            return;
        }

        $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
        $score = ($probScores[$this->riskProbability] ?? 2) * ($impScores[$this->riskImpact] ?? 2);

        ProjectRisk::create([
            'project_id' => $this->riskProjectId,
            'wbs_item_id' => $this->riskWbsItemId ?: null,
            'title' => $this->riskTitle,
            'description' => $this->riskDescription,
            'category' => $this->riskCategory,
            'probability' => $this->riskProbability,
            'impact' => $this->riskImpact,
            'risk_score' => $score,
            'owner_id' => $this->riskOwnerId ?: auth()->id(),
            'mitigation_plan' => $this->riskMitigation,
            'contingency_plan' => $this->riskContingency,
            'status' => 'open',
        ]);

        if ($this->riskWbsItemId) {
            $wbs = WbsItem::find($this->riskWbsItemId);
            if ($wbs && $wbs->status !== WbsStatus::COMPLETED) {
                $wbs->update(['status' => WbsStatus::AT_RISK]);
            }
        }

        $this->showAddRiskModal = false;
        $this->resetRiskForm();
        $this->dispatch('wbsUpdated');
        $this->dispatch('riskUpdated');
        $this->dispatch('toast', message: 'Project risk logged successfully!', type: 'success');
    }

    public function openEditRiskModal(int $riskId)
    {
        $risk = ProjectRisk::findOrFail($riskId);
        if (!$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1) {
            $this->dispatch('toast', message: '🔒 You do not have permission to edit this risk.', type: 'error');
            return;
        }

        $this->editingRiskId = $risk->id;
        $this->riskProjectId = $risk->project_id;
        $this->riskWbsItemId = $risk->wbs_item_id;
        $this->riskTitle = $risk->title;
        $this->riskCategory = $risk->category;
        $this->riskProbability = $risk->probability;
        $this->riskImpact = $risk->impact;
        $this->riskOwnerId = $risk->owner_id;
        $this->riskMitigation = $risk->mitigation_plan ?? '';
        $this->riskContingency = $risk->contingency_plan ?? '';
        $this->riskDescription = $risk->description;

        $this->showEditRiskModal = true;
    }

    public function updateRisk()
    {
        $this->validate([
            'riskProjectId' => 'required|exists:projects,id',
            'riskTitle' => 'required|string|max:255',
            'riskCategory' => 'required|string',
            'riskDescription' => 'required|string',
            'riskWbsItemId' => 'nullable|exists:wbs_items,id',
            'riskOwnerId' => 'nullable|exists:users,id',
            'riskMitigation' => 'nullable|string',
            'riskContingency' => 'nullable|string',
        ]);

        $risk = ProjectRisk::findOrFail($this->editingRiskId);
        if (!$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1) {
            $this->dispatch('toast', message: '🔒 You do not have permission to edit this risk.', type: 'error');
            return;
        }

        $probScores = ['low' => 1, 'medium' => 2, 'high' => 3];
        $impScores = ['low' => 1, 'medium' => 2, 'high' => 3, 'critical' => 4];
        $score = ($probScores[$this->riskProbability] ?? 2) * ($impScores[$this->riskImpact] ?? 2);

        $oldWbsId = $risk->wbs_item_id;
        $risk->update([
            'project_id' => $this->riskProjectId,
            'wbs_item_id' => $this->riskWbsItemId ?: null,
            'title' => $this->riskTitle,
            'description' => $this->riskDescription,
            'category' => $this->riskCategory,
            'probability' => $this->riskProbability,
            'impact' => $this->riskImpact,
            'risk_score' => $score,
            'owner_id' => $this->riskOwnerId ?: auth()->id(),
            'mitigation_plan' => $this->riskMitigation,
            'contingency_plan' => $this->riskContingency,
        ]);

        if ($this->riskWbsItemId) {
            $wbs = WbsItem::find($this->riskWbsItemId);
            if ($wbs && $wbs->status !== WbsStatus::COMPLETED && $risk->status === 'open') {
                $wbs->update(['status' => WbsStatus::AT_RISK]);
            }
        }
        if ($oldWbsId && $oldWbsId != $this->riskWbsItemId) {
            $oldWbs = WbsItem::find($oldWbsId);
            if ($oldWbs && $oldWbs->openRisks()->count() === 0 && $oldWbs->status === WbsStatus::AT_RISK) {
                $oldWbs->update(['status' => ($oldWbs->progress > 0 ? WbsStatus::IN_PROGRESS : WbsStatus::NOT_STARTED)]);
            }
        }

        $this->showEditRiskModal = false;
        $this->resetRiskForm();
        $this->dispatch('wbsUpdated');
        $this->dispatch('riskUpdated');
        $this->dispatch('toast', message: 'Risk record updated successfully!', type: 'success');
    }

    public function updateRiskStatus(int $riskId, string $status)
    {
        $risk = ProjectRisk::findOrFail($riskId);
        if (!$risk->project?->userCan(auth()->user(), 'risk.resolve') && !$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1) {
            $this->dispatch('toast', message: '🔒 You do not have permission to update status of this risk.', type: 'error');
            return;
        }

        $risk->status = $status;
        $risk->save();

        if ($risk->wbs_item_id) {
            $wbs = WbsItem::find($risk->wbs_item_id);
            if ($wbs) {
                $hasOtherOpenRisks = $wbs->openRisks()->where('id', '!=', $risk->id)->exists();
                if (!$hasOtherOpenRisks && $status !== 'open') {
                    if ($wbs->status === WbsStatus::AT_RISK) {
                        $wbs->update(['status' => ($wbs->progress > 0 ? WbsStatus::IN_PROGRESS : WbsStatus::NOT_STARTED)]);
                    }
                } elseif ($status === 'open' && $wbs->status !== WbsStatus::COMPLETED) {
                    $wbs->update(['status' => WbsStatus::AT_RISK]);
                }
            }
        }

        $this->dispatch('wbsUpdated');
        $this->dispatch('riskUpdated');
        $this->dispatch('toast', message: 'Risk status updated to ' . ucfirst($status), type: 'success');
    }

    public function deleteRisk(int $riskId)
    {
        $risk = ProjectRisk::findOrFail($riskId);
        if (!$risk->project?->userCan(auth()->user(), 'risk.delete') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1) {
            $this->dispatch('toast', message: '🔒 You do not have permission to delete this risk.', type: 'error');
            return;
        }

        $wbsId = $risk->wbs_item_id;
        $risk->delete();

        if ($wbsId) {
            $wbs = WbsItem::find($wbsId);
            if ($wbs && $wbs->openRisks()->count() === 0 && $wbs->status === WbsStatus::AT_RISK) {
                $wbs->update(['status' => ($wbs->progress > 0 ? WbsStatus::IN_PROGRESS : WbsStatus::NOT_STARTED)]);
            }
        }

        $this->dispatch('wbsUpdated');
        $this->dispatch('riskUpdated');
        $this->dispatch('toast', message: 'Risk record removed.', type: 'info');
    }

    public function render()
    {
        $user = auth()->user();

        // Projects list for dropdown filter
        $projectsQuery = Project::query();
        if (!$user->hasRole('super_admin') && $user->email !== 'admin@nexuspm.local' && $user->id !== 1) {
            $projectsQuery->where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhere(function($subQ) use ($user) {
                      $subQ->where('pm_accepted', true)
                           ->whereHas('members', fn($mq) => $mq->where('user_id', $user->id));
                  });
            });
        }
        $projects = $projectsQuery->orderBy('name')->get();
        $allowedProjectIds = $projects->pluck('id')->toArray();

        // Base Query for Risks (Active projects only)
        $risksQuery = ProjectRisk::with(['project', 'wbsItem', 'owner'])
            ->whereIn('project_id', $allowedProjectIds)
            ->whereHas('project');

        if ($this->selectedProjectId) {
            $risksQuery->where('project_id', $this->selectedProjectId);
        }
        if ($this->selectedCategory) {
            $risksQuery->where('category', $this->selectedCategory);
        }
        if ($this->selectedStatus !== 'all') {
            $risksQuery->where('status', $this->selectedStatus);
        }
        if ($this->matrixFilterProb && $this->matrixFilterImp) {
            $risksQuery->where('probability', $this->matrixFilterProb)
                ->where('impact', $this->matrixFilterImp);
        }
        if ($this->searchQuery) {
            $q = $this->searchQuery;
            $risksQuery->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhereHas('wbsItem', function ($wbs) use ($q) {
                        $wbs->where('title', 'like', "%{$q}%")
                            ->orWhere('wbs_code', 'like', "%{$q}%");
                    });
            });
        }

        $risks = (clone $risksQuery)->latest()->get();

        // A WBS task marked At Risk is a risk register item even if no separate
        // ProjectRisk record has been logged for it yet.
        $taskRisksQuery = WbsItem::with(['project', 'assignedUser'])
            ->whereIn('project_id', $allowedProjectIds)
            ->where('status', WbsStatus::AT_RISK->value)
            ->whereDoesntHave('openRisks');

        if ($this->selectedProjectId) {
            $taskRisksQuery->where('project_id', $this->selectedProjectId);
        }
        $allAtRiskTasksQuery = clone $taskRisksQuery;

        $includeTaskRisks = !$this->selectedCategory
            && !($this->matrixFilterProb && $this->matrixFilterImp)
            && in_array($this->selectedStatus, ['all', 'open'], true);

        if ($this->searchQuery) {
            $q = $this->searchQuery;
            $taskRisksQuery->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('wbs_code', 'like', "%{$q}%")
                    ->orWhereHas('project', fn($project) => $project->where('name', 'like', "%{$q}%"));
            });
        }

        $taskRisks = $includeTaskRisks ? (clone $taskRisksQuery)->latest('updated_at')->get() : collect();

        // Available WBS Items for Add/Edit Risk Modal dropdown
        $modalWbsItems = collect();
        if ($this->riskProjectId) {
            $modalWbsItems = WbsItem::where('project_id', $this->riskProjectId)->get()->sort(function ($a, $b) {
                return strnatcmp($a->wbs_code, $b->wbs_code);
            })->values();
        }

        // Available users for Risk Owner selection
        $users = User::orderBy('name')->get();

        // KPI Counts (Scanned only from active accessible projects)
        $allProjectRisks = ProjectRisk::whereIn('project_id', $allowedProjectIds)
            ->whereHas('project')
            ->when($this->selectedProjectId, fn($q) => $q->where('project_id', $this->selectedProjectId))
            ->get();
        $atRiskTaskCount = (clone $allAtRiskTasksQuery)->count();
        $highAtRiskTaskCount = (clone $allAtRiskTasksQuery)->whereIn('priority', ['high', 'critical'])->count();
        $totalRisksCount = $allProjectRisks->count() + $atRiskTaskCount;
        $openRisksCount = $allProjectRisks->whereIn('status', ['open', 'monitoring'])->count() + $atRiskTaskCount;
        $criticalHighRiskCount = $allProjectRisks->whereIn('status', ['open', 'monitoring'])->whereIn('impact', ['high', 'critical'])->count() + $highAtRiskTaskCount;

        $hasActiveFilters = $this->hasActiveFilters();
        $showFilters = $this->showFilters;

        return view('livewire.risk-blocker-manager', compact(
            'projects',
            'risks',
            'taskRisks',
            'modalWbsItems',
            'users',
            'totalRisksCount',
            'openRisksCount',
            'criticalHighRiskCount',
            'hasActiveFilters',
            'showFilters'
        ))->layout('layouts.app', ['title' => 'Project Risks']);
    }
}
