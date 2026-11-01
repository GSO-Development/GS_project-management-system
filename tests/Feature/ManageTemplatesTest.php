<?php

namespace Tests\Feature;

use App\Livewire\ManageTemplates;
use App\Models\ProjectTemplate;
use App\Models\TemplateTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ManageTemplatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_template_form_modal_uses_edit_context_and_resets_cleanly(): void
    {
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $template = ProjectTemplate::create([
            'name' => 'Delivery Framework',
            'description' => 'A reusable delivery plan.',
        ]);

        Livewire::actingAs($admin)
            ->test(ManageTemplates::class)
            ->call('editTemplate', $template->id)
            ->assertSet('showModal', true)
            ->assertSet('editId', $template->id)
            ->assertSet('name', 'Delivery Framework')
            ->assertSee('Edit template')
            ->assertSee('Update the template name and description.')
            ->assertSee('Save changes')
            ->call('closeTemplateModal')
            ->assertSet('showModal', false)
            ->assertSet('editId', null)
            ->assertSet('name', '')
            ->assertSet('description', '');
    }

    public function test_delete_modal_workflow(): void
    {
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $template = ProjectTemplate::create([
            'name' => 'Architecture Template',
            'description' => 'Standard template description',
        ]);

        $phase = TemplateTask::create([
            'project_template_id' => $template->id,
            'name' => 'Phase 1 - Discovery',
            'duration' => 5,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => $phase->id,
            'name' => 'Requirements Gathering',
            'duration' => 2,
            'unit' => 'days',
            'order_index' => 2,
        ]);

        Livewire::actingAs($admin)
            ->test(ManageTemplates::class)
            ->assertSet('showDeleteModal', false)
            ->call('promptDeleteTemplate', $template->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('templateToDeleteId', $template->id)
            ->assertSet('templateToDeleteName', 'Architecture Template')
            ->assertSet('templateToDeletePhasesCount', 1)
            ->assertSet('templateToDeleteTasksCount', 2)
            ->assertSee('Delete this template?')
            ->assertSee('This action cannot be undone')
            ->assertSee('Architecture Template')
            ->call('cancelDeleteTemplate')
            ->assertSet('showDeleteModal', false)
            ->assertSet('templateToDeleteId', null)
            ->call('promptDeleteTemplate', $template->id)
            ->assertSet('showDeleteModal', true)
            ->call('deleteTemplate')
            ->assertSet('showDeleteModal', false)
            ->assertSee('Template deleted successfully!')
            ->assertDontSee('Architecture Template');

        $this->assertDatabaseMissing('project_templates', ['id' => $template->id]);
    }
}
