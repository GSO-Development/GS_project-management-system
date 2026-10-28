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

    public bool $editing = false;
    public array $drafts = [];
    #[\Livewire\Attributes\Locked]
    public array $cascadePreview = [];
    public bool $showCascadeModal = false;


    public function getCanEditProperty(): bool
    {
        $user = auth()->user();
        return $user && ($user->isPmoAdmin() || $this->project->project_manager_id === $user->id || $this->project->userCan($user, 'task.edit'));
    }

    public function getCanAssignProperty(): bool
    {
        $user = auth()->user();
        return $user && ($user->isPmoAdmin() || $this->project->userCan($user, 'task.assign'));
    }

    public function startEditing(): void
    {
        abort_unless($this->canEdit, 403);
        $this->drafts = [];
        foreach (WbsItem::where('project_id', $this->project->id)->get() as $item) {
            $this->drafts[$item->id] = [
                'assigned_user_id' => $item->assigned_user_id,
                'title' => $item->title,
                'start_date' => $item->start_date?->format('Y-m-d'),
                'end_date' => $item->end_date?->format('Y-m-d'),
                'start_time' => $item->start_time ? substr($item->start_time, 0, 5) : null,
                'end_time' => $item->end_time ? substr($item->end_time, 0, 5) : null,
                'progress' => $item->progress ?? 0,
                'status' => $item->status?->value ?? 'not_started',
                'priority' => $item->priority?->value ?? 'medium',
            ];
        }
        $this->editing = true;
        $this->resetValidation();
    }

    public function cancelEditing(): void
    {
        $this->editing = false;
        $this->showCascadeModal = false;
        $this->cascadePreview = [];
        $this->drafts = [];
        $this->resetValidation();
    }

    public function saveTasks(): void { $this->persistTasks(null); }

    public function confirmCascade(): void
    {
        abort_unless($this->showCascadeModal, 403);
        $this->persistTasks(true);
    }

    public function saveWithoutCascade(): void
    {
        abort_unless($this->showCascadeModal, 403);
        $this->persistTasks(false);
    }

    public function cancelCascade(): void
    {
        $this->showCascadeModal = false;
        $this->cascadePreview = [];
    }

    private function persistTasks(?bool $cascade): void
    {
        abort_unless($this->canEdit && $this->editing, 403);
        // Moving only the start retains duration, even when it passes the old end.
        foreach ($this->drafts as $id => $draft) {
            $item = WbsItem::where('project_id', $this->project->id)->findOrFail($id);
            $oldStart = $item->start_date?->toDateString();
            $oldEnd = $item->end_date?->toDateString();
            $start = $draft['start_date'] ?? null;
            if ($oldStart && $oldEnd && $start && $start !== $oldStart && ($draft['end_date'] ?? null) === $oldEnd
                && preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
                try {
                    $shift = (int) \Carbon\Carbon::parse($oldStart)->diffInDays(\Carbon\Carbon::createFromFormat('!Y-m-d', $start), false);
                    $this->drafts[$id]['end_date'] = \Carbon\Carbon::parse($oldEnd)->addDays($shift)->toDateString();
                } catch (\Throwable $e) {
                    // Let the normal date validation report malformed input.
                }
            }
        }
        $rules = ['drafts' => 'required|array'];
        foreach ($this->drafts as $id => $draft) {
            $prefix = "drafts.$id.";
            $memberIds = $this->project->members()->pluck('users.id')->push($this->project->project_manager_id)->filter()->all();
            // Preserve existing assignees even if they have since left the team.
            $currentAssignee = WbsItem::where('project_id', $this->project->id)->whereKey($id)->value('assigned_user_id');
            if ($currentAssignee) $memberIds[] = $currentAssignee;
            $rules[$prefix.'assigned_user_id'] = ['nullable', \Illuminate\Validation\Rule::in($memberIds)];
            $rules[$prefix.'title'] = 'required|string|max:255';
            $rules[$prefix.'start_date'] = 'nullable|date_format:Y-m-d';
            $rules[$prefix.'end_date'] = 'nullable|date_format:Y-m-d'.(!empty($draft['start_date']) ? '|after_or_equal:'.$prefix.'start_date' : '');
            $rules[$prefix.'start_time'] = 'nullable|date_format:H:i';
            $rules[$prefix.'end_time'] = 'nullable|date_format:H:i';
            $rules[$prefix.'progress'] = 'required|integer|min:0|max:100';
            $rules[$prefix.'status'] = ['required', new \Illuminate\Validation\Rules\Enum(\App\Enums\WbsStatus::class)];
            $rules[$prefix.'priority'] = ['required', new \Illuminate\Validation\Rules\Enum(\App\Enums\Priority::class)];
            if (!empty($draft['start_date']) && $draft['start_date'] === ($draft['end_date'] ?? null) && !empty($draft['start_time']) && !empty($draft['end_time']) && $draft['end_time'] <= $draft['start_time']) {
                $this->addError($prefix.'end_time', 'End time must be after start time.');
                return;
            }
        }
        $validated = $this->validate($rules)['drafts'];
        $items = WbsItem::where('project_id', $this->project->id)->get();
        $scheduleChanged = $items->contains(function ($item) use ($validated) {
            $draft = $validated[$item->id] ?? [];
            return (array_key_exists('start_date', $draft) && (($draft['start_date'] ?: null) !== $item->start_date?->toDateString()))
                || (array_key_exists('end_date', $draft) && (($draft['end_date'] ?: null) !== $item->end_date?->toDateString()));
        });
        $plan = $scheduleChanged ? app(\App\Services\TaskSheetScheduleService::class)->plan($items, $validated) : ['updates' => [], 'tasks' => []];
        if ($cascade === null && $scheduleChanged) {
            $this->cascadePreview = [['tasks' => $plan['tasks'], 'deadline' => $plan['deadline']]];
            $this->showCascadeModal = true;
            return;
        }
        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $plan, $cascade) {
            $changed = [];
            foreach ($validated as $id => $data) {
                $item = WbsItem::where('project_id', $this->project->id)->lockForUpdate()->findOrFail($id);
                foreach (['start_date', 'end_date', 'start_time', 'end_time'] as $field) $data[$field] = ($data[$field] ?? null) ?: null;
                if ($cascade && isset($plan['updates'][$id])) $data = array_replace($data, $plan['updates'][$id]);
                if ((int) $data['progress'] === 100) $data['status'] = 'completed';
                if ($data['status'] === 'completed') $data['progress'] = 100;
                if (!$this->canAssign) unset($data['assigned_user_id']);
                elseif (empty($data['assigned_user_id'])) $data['assigned_user_id'] = null;
                $item->fill($data);
                if ($item->isDirty()) {
                    if ($item->isDirty('end_date') || $data['status'] === 'completed') $item->overdue_notified_at = null;
                    if ($cascade && isset($plan['updates'][$id])) {
                        $item->rescheduled_at = now();
                        $item->rescheduled_reason = 'Task sheet dates recalculated in task order';
                    }
                    $item->updated_by = auth()->id();
                    $item->save();
                    $changed[] = $item;
                }
            }
            // The plan also includes rows hidden by filters or collapsed branches.
            if ($cascade) {
                foreach (array_diff_key($plan['updates'], $validated) as $id => $dates) {
                    WbsItem::where('project_id', $this->project->id)->findOrFail($id)->update($dates + ['updated_by' => auth()->id(), 'overdue_notified_at' => null]);
                }
                if (!empty($plan['deadline'])) $this->project->update(['deadline' => $plan['deadline']]);
            }
            foreach ($changed as $item) (new \App\Services\ProgressCalculationService())->updateItemProgress($item);
        });
        $this->cancelEditing();
        $this->dispatch('wbsUpdated');
        $this->dispatch('toast', message: 'Tasks saved successfully.', type: 'success');
    }

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
        $this->cancelEditing();
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
