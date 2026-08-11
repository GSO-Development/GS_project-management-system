<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProjectTemplate;

class ManageTemplates extends Component
{
    public $name = '';
    public $description = '';
    public $showModal = false;

    public $editId = null;

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'nullable|string',
    ];

    public function editTemplate($id)
    {
        $template = ProjectTemplate::findOrFail($id);
        $this->editId = $template->id;
        $this->name = $template->name;
        $this->description = $template->description;
        $this->showModal = true;
    }

    public function deleteTemplate($id)
    {
        ProjectTemplate::findOrFail($id)->delete();
    }

    public function createTemplate()
    {
        $this->validate();

        if ($this->editId) {
            $template = ProjectTemplate::findOrFail($this->editId);
            $template->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        } else {
            ProjectTemplate::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        }

        $this->reset(['name', 'description', 'showModal', 'editId']);
    }

    public function render()
    {
        return view('livewire.manage-templates', [
            'templates' => ProjectTemplate::latest()->get(),
            'totalTemplates' => ProjectTemplate::count(),
        ])->layout('layouts.app');
    }
}
