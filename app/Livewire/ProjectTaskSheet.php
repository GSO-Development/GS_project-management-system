<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\WbsItem;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectTaskSheet extends Component
{
    public Project $project;

    // Filters & Search
    public string $search = '';
    public string $statusFilter = 'all';
    public string $assigneeFilter = 'all';
    public string $typeFilter = 'all';

    // Collapsed node IDs (empty array means everything is fully expanded)
    public array $collapsedIds = [];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    #[On('wbsUpdated')]
    #[On('refreshWbs')]
    public function refreshSheet(): void
    {
        $this->project->refresh();
    }

    public function toggleCollapse(int $id): void
    {
        if (in_array($id, $this->collapsedIds, true)) {
            $this->collapsedIds = array_values(array_diff($this->collapsedIds, [$id]));
        } else {
            $this->collapsedIds[] = $id;
        }
    }

    public function expandAll(): void
    {
        $this->collapsedIds = [];
    }

    public function collapseAll(): void
    {
        $this->collapsedIds = WbsItem::where('project_id', $this->project->id)
            ->whereHas('children')
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusFilter', 'assigneeFilter', 'typeFilter']);
    }

    public function closeSheet(): void
    {
        $this->dispatch('closeTaskSheet');
    }

    public function render()
    {
        // 1. Fetch all items for this project with relations
        $allTasks = WbsItem::with([
            'children.children',
            'children.assignedUser',
            'assignedUser',
            'predecessors.predecessor',
            'risks',
        ])
        ->where('project_id', $this->project->id)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

        // 2. Build hierarchical ordered collection (depth-first)
        $childrenByParent = $allTasks->groupBy(fn($i) => $i->parent_id ?? 0);
        $orderedTasks = collect();

        $walk = function($parentId, $level) use (&$walk, &$orderedTasks, $childrenByParent) {
            $siblings = $childrenByParent->get($parentId, collect());
            foreach ($siblings as $item) {
                $item->tree_level = $level;
                $item->has_children = $childrenByParent->has($item->id);
                $item->children_count = $item->has_children ? $childrenByParent->get($item->id)->count() : 0;
                $orderedTasks->push($item);
                $walk($item->id, $level + 1);
            }
        };

        $walk(0, 1);

        // 3. Filter items if search or filters are active
        $filteredTasks = $orderedTasks;

        if (trim($this->search) !== '') {
            $query = mb_strtolower(trim($this->search));
            // Keep matches and their ancestors
            $matchingIds = $orderedTasks->filter(function($item) use ($query) {
                return str_contains(mb_strtolower($item->title ?? ''), $query)
                    || str_contains(mb_strtolower($item->wbs_code ?? ''), $query)
                    || str_contains(mb_strtolower($item->assignedUser?->name ?? ''), $query);
            })->pluck('id')->toArray();

            // Find all ancestors of matching items so tree context remains intact
            $allAncestors = [];
            $itemsKeyed = $allTasks->keyBy('id');
            foreach ($matchingIds as $mId) {
                $curr = $itemsKeyed->get($mId);
                while ($curr && $curr->parent_id) {
                    $allAncestors[] = $curr->parent_id;
                    $curr = $itemsKeyed->get($curr->parent_id);
                }
            }
            $keepIds = array_unique(array_merge($matchingIds, $allAncestors));
            $filteredTasks = $filteredTasks->filter(fn($i) => in_array($i->id, $keepIds, true));
        }

        if ($this->statusFilter !== 'all') {
            $filteredTasks = $filteredTasks->filter(fn($i) => ($i->status?->value ?? 'not_started') === $this->statusFilter);
        }

        if ($this->typeFilter !== 'all') {
            $filteredTasks = $filteredTasks->filter(fn($i) => ($i->item_type?->value ?? 'task') === $this->typeFilter);
        }

        if ($this->assigneeFilter !== 'all') {
            if ($this->assigneeFilter === 'unassigned') {
                $filteredTasks = $filteredTasks->filter(fn($i) => empty($i->assigned_user_id));
            } else {
                $uid = (int) $this->assigneeFilter;
                $filteredTasks = $filteredTasks->filter(fn($i) => $i->assigned_user_id === $uid);
            }
        }

        // 4. Handle collapse visibility (hide descendants of collapsed nodes)
        if (!empty($this->collapsedIds) && trim($this->search) === '') {
            $itemsKeyed = $allTasks->keyBy('id');
            $collapsedSet = array_flip($this->collapsedIds);

            $filteredTasks = $filteredTasks->filter(function($item) use ($collapsedSet, $itemsKeyed) {
                $curr = $item;
                while ($curr && $curr->parent_id) {
                    if (isset($collapsedSet[$curr->parent_id])) {
                        return false;
                    }
                    $curr = $itemsKeyed->get($curr->parent_id);
                }
                return true;
            });
        }

        // 5. Summary statistics
        $stats = [
            'total'       => $allTasks->count(),
            'phases'      => $allTasks->where('parent_id', null)->count(),
            'in_progress' => $allTasks->where('status.value', 'in_progress')->count(),
            'completed'   => $allTasks->where('status.value', 'completed')->count(),
            'overdue'     => $allTasks->filter(fn($i) => $i->isOverdue())->count(),
        ];

        // Unique project assignees for filter
        $assignees = $this->project->members->push($this->project->projectManager)->filter()->unique('id')->values();

        return view('livewire.project-task-sheet', [
            'tasks' => $filteredTasks->values(),
            'allTasksCount' => $allTasks->count(),
            'stats' => $stats,
            'assignees' => $assignees,
        ]);
    }
}
