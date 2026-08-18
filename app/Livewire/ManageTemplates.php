<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectTemplate;
use App\Models\TemplateTask;

class ManageTemplates extends Component
{
    public $name = '';
    public $description = '';
    public $search = '';
    public $sortBy = 'latest';
    public $showModal = false;
    public $editId = null;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
    ];

    protected function checkSuperAdmin()
    {
        if (!auth()->user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only PMO Admins can access or modify project templates.');
        }
    }

    public function mount(): void
    {
        $this->checkSuperAdmin();
    }

    public function editTemplate($id)
    {
        $this->checkSuperAdmin();
        $template = ProjectTemplate::findOrFail($id);
        $this->editId = $template->id;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->showModal = true;
    }

    public function deleteTemplate($id)
    {
        $this->checkSuperAdmin();
        ProjectTemplate::findOrFail($id)->delete();
        session()->flash('message', 'Template deleted successfully!');
    }

    public function duplicateTemplate($id)
    {
        $this->checkSuperAdmin();
        $template = ProjectTemplate::with('tasks')->findOrFail($id);
        $newTemplate = ProjectTemplate::create([
            'name' => $template->name . ' (Copy)',
            'description' => $template->description,
        ]);

        $idMap = [];
        foreach ($template->tasks as $task) {
            $newTask = TemplateTask::create([
                'project_template_id' => $newTemplate->id,
                'parent_id' => $task->parent_id ? ($idMap[$task->parent_id] ?? null) : null,
                'name' => $task->name,
                'duration' => $task->duration,
                'unit' => $task->unit,
                'order_index' => $task->order_index,
                'is_expanded' => $task->is_expanded ?? 1,
            ]);
            $idMap[$task->id] = $newTask->id;
        }

        session()->flash('message', 'Template duplicated successfully!');
    }

    public function createTemplate()
    {
        $this->checkSuperAdmin();
        $this->validate();

        if ($this->editId) {
            $template = ProjectTemplate::findOrFail($this->editId);
            $template->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Template updated successfully!');
        } else {
            $newTemplate = ProjectTemplate::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Template created successfully!');
        }

        $this->reset(['name', 'description', 'showModal', 'editId']);
    }


    public function render()
    {
        $query = ProjectTemplate::withCount([
            'tasks',
            'tasks as phases_count' => function ($q) {
                $q->whereNull('parent_id');
            }
        ])->with(['tasks' => function ($q) {
            $q->whereNull('parent_id')->orderBy('order_index')->take(3);
        }]);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        switch ($this->sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'tasks_desc':
                $query->orderBy('tasks_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $templates = $query->get();

        $totalTemplates = ProjectTemplate::count();
        $totalTasks = TemplateTask::count();
        $totalPhases = TemplateTask::whereNull('parent_id')->count();
        $avgTasks = $totalTemplates > 0 ? round($totalTasks / $totalTemplates, 1) : 0;

        return view('livewire.manage-templates', [
            'templates' => $templates,
            'totalTemplates' => $totalTemplates,
            'totalTasks' => $totalTasks,
            'totalPhases' => $totalPhases,
            'avgTasks' => $avgTasks,
        ])->layout('layouts.app');
    }
}

