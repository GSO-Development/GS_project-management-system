<?php

namespace Tests\Feature;

use App\Livewire\ProjectCreate;
use App\Livewire\ManageTemplates;
use App\Models\Project;
use App\Models\ProjectTemplate;
use App\Models\Subsidiary;
use App\Models\TemplateTask;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_projects_create_page_renders_successfully(): void
    {
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $subsidiary = Subsidiary::create(['name' => 'Demo Sub', 'code' => 'DEMO']);
        $template = ProjectTemplate::create(['name' => 'Default Blueprint']);

        $rootTask = TemplateTask::create([
            'project_template_id' => $template->id,
            'name' => 'Kickoff Meeting',
            'duration' => 2,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        $response = $this->actingAs($admin)->get('/projects/create');
        $response->assertStatus(200);

        $testable = Livewire::actingAs($admin)
            ->test(ProjectCreate::class)
            ->assertOk()
            ->assertSet('creation_option', 'manual')
            ->assertSet('selected_template_id', null)
            ->assertSee('Project Name')
            ->assertSee('Project Manager')
            ->assertSee('Select Your Tasks Template')
            ->assertSee('Blank')
            ->assertSee('Select Templates')
            ->assertSee('Create Project')
            ->assertSee('wire:model="name"', false)
            ->assertSee('wire:click="selectBlankCanvas"', false)
            ->assertSee('wire:model.live="start_date"', false)
            ->assertDontSee('Next Step')
            ->assertDontSee('Previous Step');

        $this->assertNull($testable->get('calculatedDeadline'));
    }

    public function test_template_library_selection_restores_project_draft(): void
    {
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $subsidiary = Subsidiary::create(['name' => 'Selection Sub', 'code' => 'SEL']);
        $template = ProjectTemplate::create(['name' => 'Selected Delivery Template']);

        Livewire::actingAs($admin)
            ->test(ProjectCreate::class)
            ->set('name', 'Preserved Project Name')
            ->set('subsidiary_id', $subsidiary->id)
            ->call('openTemplateLibrary')
            ->assertRedirect(route('templates.index', ['select_for' => 'project']));

        $this->assertSame('Preserved Project Name', session('project_create_draft.name'));

        Livewire::actingAs($admin)
            ->withQueryParams(['select_for' => 'project'])
            ->test(ManageTemplates::class)
            ->assertSet('selectForProject', true)
            ->assertSee('Use Template')
            ->call('selectTemplateForProject', $template->id)
            ->assertRedirect(route('projects.create').'#project-blueprint');

        Livewire::actingAs($admin)
            ->test(ProjectCreate::class)
            ->assertSet('name', 'Preserved Project Name')
            ->assertSet('creation_option', 'template')
            ->assertSet('selected_template_id', $template->id)
            ->assertSee('Selected Delivery Template');
    }

    public function test_project_creation_with_template_creates_tasks_and_subtasks(): void
    {
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');

        $subsidiary = Subsidiary::create(['name' => 'Demo Sub', 'code' => 'DEMO']);
        $template = ProjectTemplate::create(['name' => 'Full Lifecycle Blueprint']);

        $rootTask = TemplateTask::create([
            'project_template_id' => $template->id,
            'name' => 'Phase 1 - Planning',
            'duration' => 5,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        TemplateTask::create([
            'project_template_id' => $template->id,
            'parent_id' => $rootTask->id,
            'name' => 'Subtask 1.1 - Kickoff',
            'duration' => 2,
            'unit' => 'days',
            'order_index' => 1,
        ]);

        $standaloneTask = TemplateTask::create([
            'project_template_id' => $template->id,
            'name' => 'Phase 2 - Execution (Standalone)',
            'duration' => 4,
            'unit' => 'days',
            'order_index' => 2,
        ]);

        Livewire::actingAs($admin)
            ->test(ProjectCreate::class)
            ->set('name', 'E2E Creation Project')
            ->set('code', 'E2E-001')
            ->set('subsidiary_id', $subsidiary->id)
            ->set('project_manager_id', $admin->id)
            ->set('creation_option', 'template')
            ->set('selected_template_id', $template->id)
            ->set('start_date', '2026-11-01')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('projects.index'));

        $project = Project::where('name', 'E2E Creation Project')->first();
        $this->assertNotNull($project);

        $wbsItems = \App\Models\WbsItem::where('project_id', $project->id)->get();
        $this->assertCount(3, $wbsItems); // Phase 1 (with subtask), Subtask 1.1, Phase 2 (standalone)

        // Phase 1 has 1 subtask
        $p1 = $wbsItems->firstWhere('title', 'Phase 1 - Planning');
        $this->assertNotNull($p1);
        $this->assertEquals(\App\Enums\ItemType::PHASE, $p1->item_type);

        $p1Subtasks = $wbsItems->where('parent_id', $p1->id);
        $this->assertCount(1, $p1Subtasks);
        $this->assertEquals('Subtask 1.1 - Kickoff', $p1Subtasks->first()->title);

        // Standalone task has NO subtasks
        $p2 = $wbsItems->firstWhere('title', 'Phase 2 - Execution (Standalone)');
        $this->assertNotNull($p2);
        $this->assertEquals(\App\Enums\ItemType::TASK, $p2->item_type);
        $this->assertCount(0, $wbsItems->where('parent_id', $p2->id));
    }

    public function test_single_page_manual_creation_preserves_input_after_validation_errors(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        \Illuminate\Support\Facades\Notification::fake();
        Role::findOrCreate('super_admin', 'web');
        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        Subsidiary::create(['name' => 'Manual Sub', 'code' => 'MAN']);

        $form = Livewire::actingAs($admin)
            ->test(ProjectCreate::class)
            ->call('selectBlankCanvas')
            ->set('name', 'Single Page Manual Project')
            ->set('project_manager_id', $admin->id)
            ->set('start_date', '2026-11-10')
            ->set('deadline', '2026-11-01')
            ->call('save')
            ->assertHasErrors(['deadline'])
            ->assertSet('name', 'Single Page Manual Project')
            ->assertSee('Please review the following')
            ->assertSee('Project Name')
            ->assertSee('Project Manager')
            ->assertSee('Select Your Tasks Template')
            ->assertSee('Create Project');

        $this->assertDatabaseMissing('projects', ['name' => 'Single Page Manual Project']);

        $form->set('deadline', '2026-12-10')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('projects.index'));

        $project = Project::where('name', 'Single Page Manual Project')->firstOrFail();
        $this->assertSame('manual', $project->wbs_breakdown_type);
        $this->assertNull($project->template_id);
        $this->assertSame('2026-11-10', $project->start_date->toDateString());
        $this->assertSame('2026-12-10', $project->deadline->toDateString());
        $this->assertCount(0, $project->wbsItems);
    }
}
