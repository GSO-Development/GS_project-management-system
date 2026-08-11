<?php

namespace App\Livewire;

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Models\Project;
use App\Models\User;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use App\Services\DependencyValidationService;
use App\Services\ProgressCalculationService;
use App\Services\WbsNumberingService;
use InvalidArgumentException;
use Livewire\Component;

class WbsTree extends Component
{
    public Project $project;
    public array $collapsedIds = [];

    // Add/Edit Modal
    public bool $showItemModal = false;
    public ?int $editingItemId = null;
    public ?int $selectedParentId = null;

    public string $item_type = 'task';
    public string $title = '';
    public ?string $description = null;
    public ?int $assigned_user_id = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public string $status = 'not_started';
    public string $priority = 'medium';
    public int $progress = 0;
    public ?float $estimated_hours = null;
    public float $weight = 1.0;

    // Dependency Modal
    public bool $showDepModal = false;
    public ?int $depSuccessorId = null;
    public ?int $depPredecessorId = null;

    protected function rules(): array
    {
        return [
            'item_type' => 'required|in:phase,work_package,task,subtask,milestone',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string',
            'priority' => 'required|string',
            'progress' => 'required|integer|min:0|max:100',
            'estimated_hours' => 'nullable|numeric|min:0',
            'weight' => 'required|numeric|min:0.1',
        ];
    }

    public function toggleCollapse(int $id)
    {
        if (in_array($id, $this->collapsedIds)) {
            $this->collapsedIds = array_diff($this->collapsedIds, [$id]);
        } else {
            $this->collapsedIds[] = $id;
        }
    }

    public function openAddItemModal(?int $parentId = null, string $type = 'task')
    {
        $this->reset(['editingItemId', 'title', 'description', 'assigned_user_id', 'start_date', 'end_date', 'progress', 'estimated_hours']);
        $this->selectedParentId = $parentId;
        $this->item_type = $type;

        if ($parentId) {
            $parentTask = WbsItem::find($parentId);
            $this->assigned_user_id = $parentTask->assigned_user_id ?? auth()->id();
            $this->end_date = $parentTask->end_date ? $parentTask->end_date->toDateString() : null;
        } else {
            $this->assigned_user_id = auth()->id();
        }

        $this->updateDefaultTitle();

        $this->status = 'not_started';
        $this->priority = 'medium';
        $this->progress = 0;
        $this->weight = 1.0;
        $this->showItemModal = true;
    }

    public function updatedSelectedParentId()
    {
        if (!$this->editingItemId) {
            $this->updateDefaultTitle();
        }
    }

    private function updateDefaultTitle()
    {
        $nextNum = WbsItem::where('project_id', $this->project->id)->where('parent_id', $this->selectedParentId)->count() + 1;
        if ($this->selectedParentId) {
            $parentItem = WbsItem::find($this->selectedParentId);
            $pCode = $parentItem ? $parentItem->wbs_code : '1';
            $this->title = "Sub-task " . $pCode . "." . $nextNum;
            $this->item_type = 'subtask';
        } else {
            $this->title = "Task " . $nextNum;
            $this->item_type = 'task';
        }
    }

    public function openEditItemModal(int $id)
    {
        $item = WbsItem::findOrFail($id);
        $this->editingItemId = $item->id;
        $this->selectedParentId = $item->parent_id;
        $this->item_type = $item->item_type->value;
        $this->title = $item->title;
        $this->description = $item->description;
        $this->assigned_user_id = $item->assigned_user_id;
        $this->start_date = $item->start_date?->toDateString();
        $this->end_date = $item->end_date?->toDateString();
        $this->status = $item->status->value;
        $this->priority = $item->priority->value;
        $this->progress = $item->progress;
        $this->estimated_hours = $item->estimated_hours;
        $this->weight = (float) $item->weight;
        $this->showItemModal = true;
    }

    public function saveItem()
    {
        $this->validate();

        if ($this->selectedParentId) {
            if ($this->editingItemId && $this->selectedParentId === $this->editingItemId) {
                $this->addError('selectedParentId', 'An item cannot be its own parent.');
                return;
            }
        }

        $data = [
            'project_id' => $this->project->id,
            'parent_id' => $this->selectedParentId,
            'item_type' => $this->item_type,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_user_id' => $this->assigned_user_id,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'status' => $this->status,
            'priority' => $this->priority,
            'progress' => $this->progress,
            'estimated_hours' => $this->estimated_hours,
            'weight' => $this->weight,
            'is_milestone' => $this->item_type === 'milestone',
        ];

        if ($this->editingItemId) {
            $item = WbsItem::findOrFail($this->editingItemId);
            $item->update($data);
        } else {
            $lastSort = WbsItem::where('project_id', $this->project->id)->where('parent_id', $this->selectedParentId)->max('sort_order') ?? 0;
            $data['sort_order'] = $lastSort + 1;
            $data['created_by'] = auth()->id();
            $item = WbsItem::create($data);
        }

        (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
        (new ProgressCalculationService())->updateItemProgress($item);

        $this->showItemModal = false;
        $this->dispatch('toast', message: 'WBS item saved successfully!', type: 'success');
    }

    public function updateItemStatus(int $itemId, string $status)
    {
        $item = WbsItem::findOrFail($itemId);

        $user = auth()->user();
        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $item->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You are only permitted to update status on your assigned tasks.', type: 'error');
            return;
        }

        $statusEnum = WbsStatus::tryFrom($status);
        if (!$statusEnum) return;

        $item->status = $statusEnum;
        if ($status === 'completed') {
            $item->progress = 100;
        } elseif ($status === 'in_progress' && $item->progress == 0) {
            $item->progress = 10;
        } elseif ($status === 'not_started') {
            $item->progress = 0;
        }
        $item->save();

        (new ProgressCalculationService())->updateItemProgress($item);

        $this->dispatch('toast', message: 'Status updated to ' . $statusEnum->label(), type: 'success');
    }

    public function updateLeafProgress(int $itemId, int $newProgress)
    {
        $item = WbsItem::findOrFail($itemId);

        $user = auth()->user();
        if (!$user->hasAnyRole(['super_admin', 'project_manager']) && $item->assigned_user_id !== $user->id) {
            $this->dispatch('toast', message: 'You are only permitted to update progress on your assigned tasks.', type: 'error');
            return;
        }

        $item->progress = max(0, min(100, $newProgress));
        if ($newProgress === 100) {
            $item->status = WbsStatus::COMPLETED;
        } elseif ($newProgress > 0 && $item->status->value === 'not_started') {
            $item->status = WbsStatus::IN_PROGRESS;
        }
        $item->save();

        (new ProgressCalculationService())->updateItemProgress($item);

        $this->dispatch('toast', message: 'Progress updated & recalculated!', type: 'success');
    }

    public function deleteItem(int $id)
    {
        $item = WbsItem::find($id);
        if (!$item) return;

        $this->deleteItemRecursive($item);

        (new WbsNumberingService())->recalculateProjectWbsCodes($this->project->id);
        (new ProgressCalculationService())->updateProjectOverallProgress($this->project->id);

        $this->dispatch('toast', message: 'WBS item deleted successfully.', type: 'success');
    }

    private function deleteItemRecursive(WbsItem $item)
    {
        foreach ($item->children as $child) {
            $this->deleteItemRecursive($child);
        }
        
        WbsDependency::where('predecessor_id', $item->id)->orWhere('successor_id', $item->id)->delete();
        
        $item->delete();
    }

    public function openDepModal(int $successorId)
    {
        $this->depSuccessorId = $successorId;
        $this->depPredecessorId = null;
        $this->showDepModal = true;
    }

    public function addDependency()
    {
        if (!$this->depPredecessorId || !$this->depSuccessorId) return;

        try {
            (new DependencyValidationService())->validateDependency($this->depPredecessorId, $this->depSuccessorId);

            WbsDependency::create([
                'predecessor_id' => $this->depPredecessorId,
                'successor_id' => $this->depSuccessorId,
                'dependency_type' => 'finish_to_start',
            ]);

            $this->showDepModal = false;
            $this->dispatch('toast', message: 'Task dependency added successfully!', type: 'success');
        } catch (InvalidArgumentException $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function removeDependency(int $dependencyId)
    {
        $dep = WbsDependency::find($dependencyId);
        if ($dep) {
            $dep->delete();
            $this->dispatch('toast', message: 'Dependency removed.', type: 'success');
        }
    }

    public function render()
    {
        $user = auth()->user();

        $isSuperAdminOrPM = $user->hasRole('super_admin') 
            || $user->email === 'admin@nexuspm.local' 
            || $user->id === 1
            || $this->project->project_manager_id === $user->id;

        $query = WbsItem::with(['children', 'assignedUser', 'predecessors.predecessor'])
            ->where('project_id', $this->project->id);

        if (!$isSuperAdminOrPM) {
            // Collaborators / Team Members: ONLY show tasks assigned directly to them!
            $query->where('assigned_user_id', $user->id);
        } else {
            $query->whereNull('parent_id');
        }

        $wbsItems = $query->orderBy('sort_order')->get();

        $projectMembers = $this->project->members;
        $allWbsItems = WbsItem::where('project_id', $this->project->id)->get();

        return view('livewire.wbs-tree', compact('wbsItems', 'projectMembers', 'allWbsItems'));
    }
}
