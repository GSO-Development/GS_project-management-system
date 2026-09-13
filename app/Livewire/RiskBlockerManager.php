<?php

namespace App\Livewire;

use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\TaskBlocker;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Component;

class RiskBlockerManager extends Component
{
    // Filter State
    public ?int $selectedProjectId = null;
    public string $selectedCategory = '';
    public string $selectedStatus = 'all';
    public string $selectedSeverity = 'all';
    public string $searchQuery = '';
    public string $activeTab = 'risks'; // 'risks' or 'blockers'
    public ?string $matrixFilterProb = null;
    public ?string $matrixFilterImp = null;

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

    // Blocker Modal State
    public bool $showAddBlockerModal = false;
    public bool $showResolveBlockerModal = false;
    public ?int $selectedBlockerId = null;
    public string $blockerResolutionInput = '';

    // Blocker Form Fields
    public ?int $blockerProjectId = null;
    public ?int $blockerWbsItemId = null;
    public string $blockerDescription = '';
    public string $blockerSeverity = 'medium';

    protected $queryString = [
        'selectedProjectId' => ['except' => null, 'as' => 'project'],
        'activeTab' => ['except' => 'risks', 'as' => 'tab'],
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
            $this->activeTab = 'risks';
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
        abort_if(!$project->userCan(auth()->user(), 'risk.create') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to create risks for this project.');

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
        abort_if(!$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to edit this risk.');

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
        abort_if(!$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to edit this risk.');

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
        abort_if(!$risk->project?->userCan(auth()->user(), 'risk.resolve') && !$risk->project?->userCan(auth()->user(), 'risk.edit') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'Unauthorized.');

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
        abort_if(!$risk->project?->userCan(auth()->user(), 'risk.delete') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to delete this risk.');

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

    // --- Blocker Management ---

    public function openAddBlockerModal(?int $projectId = null)
    {
        $this->resetBlockerForm();
        if ($projectId) {
            $this->blockerProjectId = $projectId;
        } elseif ($this->selectedProjectId) {
            $this->blockerProjectId = $this->selectedProjectId;
        } else {
            $firstProj = Project::whereHas('subsidiary')->orWhereNotNull('id')->first();
            if ($firstProj) {
                $this->blockerProjectId = $firstProj->id;
            }
        }
        $this->showAddBlockerModal = true;
    }

    public function resetBlockerForm()
    {
        $this->reset([
            'blockerProjectId',
            'blockerWbsItemId',
            'blockerDescription',
            'blockerSeverity',
        ]);
        $this->blockerSeverity = 'medium';
    }

    public function addBlocker()
    {
        $this->validate([
            'blockerProjectId' => 'required|exists:projects,id',
            'blockerWbsItemId' => 'required|exists:wbs_items,id',
            'blockerDescription' => 'required|string|min:5',
            'blockerSeverity' => 'required|in:low,medium,high,critical',
        ]);

        TaskBlocker::create([
            'wbs_item_id' => $this->blockerWbsItemId,
            'reported_by' => auth()->id(),
            'description' => $this->blockerDescription,
            'severity' => $this->blockerSeverity,
            'status' => 'open',
        ]);

        $this->showAddBlockerModal = false;
        $this->resetBlockerForm();
        $this->dispatch('toast', message: 'Task blocker reported successfully!', type: 'success');
    }

    public function openResolveBlockerModal(int $blockerId)
    {
        $blocker = TaskBlocker::findOrFail($blockerId);
        abort_if(!$blocker->wbsItem?->project?->userCan(auth()->user(), 'blocker.resolve') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to resolve blockers.');

        $this->selectedBlockerId = $blockerId;
        $this->blockerResolutionInput = '';
        $this->showResolveBlockerModal = true;
    }

    public function saveBlockerResolution()
    {
        $this->validate([
            'blockerResolutionInput' => 'required|string|min:3',
        ]);

        $blocker = TaskBlocker::findOrFail($this->selectedBlockerId);
        abort_if(!$blocker->wbsItem?->project?->userCan(auth()->user(), 'blocker.resolve') && !auth()->user()->hasRole('super_admin') && auth()->user()->id !== 1, 403, 'You do not have permission to resolve blockers.');

        $blocker->resolution = $this->blockerResolutionInput;
        $blocker->resolved_by = auth()->id();
        $blocker->resolved_at = now();
        $blocker->status = 'resolved';
        $blocker->save();

        $this->showResolveBlockerModal = false;
        $this->dispatch('toast', message: 'Task blocker resolved successfully!', type: 'success');
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

        // Base Query for Blockers (Active projects only)
        $blockersQuery = TaskBlocker::with(['wbsItem.project', 'reporter', 'resolver'])
            ->whereHas('wbsItem', fn($q) => $q->whereIn('project_id', $allowedProjectIds)->whereHas('project'));

        if ($this->selectedProjectId) {
            $blockersQuery->whereHas('wbsItem', fn($q) => $q->where('project_id', $this->selectedProjectId));
        }
        if ($this->selectedSeverity !== 'all') {
            $blockersQuery->where('severity', $this->selectedSeverity);
        }
        if ($this->searchQuery) {
            $q = $this->searchQuery;
            $blockersQuery->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhereHas('wbsItem', function ($wbs) use ($q) {
                        $wbs->where('title', 'like', "%{$q}%")
                            ->orWhere('wbs_code', 'like', "%{$q}%");
                    });
            });
        }

        $blockers = (clone $blockersQuery)->latest()->get();

        // Available WBS Items for Add/Edit Risk Modal dropdown
        $modalWbsItems = collect();
        if ($this->riskProjectId) {
            $modalWbsItems = WbsItem::where('project_id', $this->riskProjectId)->get()->sort(function ($a, $b) {
                return strnatcmp($a->wbs_code, $b->wbs_code);
            })->values();
        }

        // Available WBS Items for Add Blocker Modal dropdown
        $modalBlockerWbsItems = collect();
        if ($this->blockerProjectId) {
            $modalBlockerWbsItems = WbsItem::where('project_id', $this->blockerProjectId)->get()->sort(function ($a, $b) {
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
        $totalRisksCount = $allProjectRisks->count();
        $openRisksCount = $allProjectRisks->whereIn('status', ['open', 'monitoring'])->count();
        $criticalHighRiskCount = $allProjectRisks->whereIn('status', ['open', 'monitoring'])->whereIn('impact', ['high', 'critical'])->count();

        $allProjectBlockers = TaskBlocker::whereHas('wbsItem', fn($q) => $q->whereIn('project_id', $allowedProjectIds)->whereHas('project'))
            ->when($this->selectedProjectId, fn($q) => $q->whereHas('wbsItem', fn($w) => $w->where('project_id', $this->selectedProjectId)))
            ->get();
        $openBlockersCount = $allProjectBlockers->where('status', '!=', 'resolved')->count();
        $resolvedBlockersCount = $allProjectBlockers->where('status', 'resolved')->count();

        return view('livewire.risk-blocker-manager', compact(
            'projects',
            'risks',
            'blockers',
            'modalWbsItems',
            'modalBlockerWbsItems',
            'users',
            'totalRisksCount',
            'openRisksCount',
            'criticalHighRiskCount',
            'openBlockersCount',
            'resolvedBlockersCount'
        ))->layout('layouts.app', ['title' => 'Risks & Blockers Hub']);
    }
}
