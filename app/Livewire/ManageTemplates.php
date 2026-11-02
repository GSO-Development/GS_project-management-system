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
    public bool $selectForProject = false;
    public bool $showDeleteModal = false;
    public ?int $templateToDeleteId = null;
    public string $templateToDeleteName = '';
    public int $templateToDeletePhasesCount = 0;
    public int $templateToDeleteTasksCount = 0;

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
        $this->selectForProject = request()->query('select_for') === 'project'
            && session()->has('project_create_draft');
    }

    public function selectTemplateForProject(int $id)
    {
        $this->checkSuperAdmin();
        abort_unless($this->selectForProject && session()->has('project_create_draft'), 403);

        ProjectTemplate::findOrFail($id);

        return redirect()->route('templates.manage', [
            'template' => $id,
            'select_for' => 'project',
        ]);
    }

    public function returnToProjectCreate()
    {
        return redirect()->to(route('projects.create').'#project-blueprint');
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

    public function closeTemplateModal(): void
    {
        $this->reset(['name', 'description', 'showModal', 'editId']);
        $this->resetValidation();
    }

    public function promptDeleteTemplate(int $id)
    {
        $this->checkSuperAdmin();
        $template = ProjectTemplate::withCount([
            'tasks',
            'tasks as phases_count' => function ($q) {
                $q->whereNull('parent_id');
            }
        ])->findOrFail($id);

        $this->templateToDeleteId = $template->id;
        $this->templateToDeleteName = $template->name;
        $this->templateToDeletePhasesCount = (int) $template->phases_count;
        $this->templateToDeleteTasksCount = (int) $template->tasks_count;
        $this->showDeleteModal = true;
    }

    public function cancelDeleteTemplate()
    {
        $this->showDeleteModal = false;
        $this->templateToDeleteId = null;
        $this->templateToDeleteName = '';
        $this->templateToDeletePhasesCount = 0;
        $this->templateToDeleteTasksCount = 0;
    }

    public function deleteTemplate(?int $id = null)
    {
        $this->checkSuperAdmin();
        $targetId = $id ?? $this->templateToDeleteId;
        if ($targetId) {
            ProjectTemplate::findOrFail($targetId)->delete();
            session()->flash('message', 'Template deleted successfully!');
        }
        $this->cancelDeleteTemplate();
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

            if ($this->selectForProject) {
                $this->reset(['name', 'description', 'showModal', 'editId']);
                return redirect()->route('templates.manage', [
                    'template' => $newTemplate->id,
                    'select_for' => 'project',
                ]);
            }
        }

        $this->closeTemplateModal();
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
        return view('livewire.manage-templates', [
            'templates' => $templates,
        ])->layout('layouts.app');
    }
}

