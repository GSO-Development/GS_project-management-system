<?php

use App\Livewire\ProjectTaskSheet;
use App\Livewire\ProjectWorkspace;
use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\Subsidiary;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('gives admin viewers the PMO interface with organization-wide read-only access', function () {
    Role::findOrCreate('admin_viewer', 'web');

    $viewer = User::factory()->create();
    $viewer->assignRole('admin_viewer');

    $manager = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'AVT', 'name' => 'Admin Viewer Test']);
    $project = Project::create([
        'code' => 'AVT-001',
        'name' => 'Executive Read Only Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $manager->id,
        'created_by' => $manager->id,
        'status' => 'delayed',
    ]);

    ProjectRisk::create([
        'project_id' => $project->id,
        'title' => 'Executive dashboard risk',
        'description' => 'An active risk on a delayed project.',
        'probability' => 'high',
        'impact' => 'high',
        'status' => 'open',
    ]);

    expect($viewer->isAdminViewer())->toBeTrue()
        ->and($viewer->hasAdminInterfaceAccess())->toBeTrue()
        ->and($viewer->isPmoAdmin())->toBeFalse()
        ->and($viewer->canCreateProject())->toBeFalse()
        ->and($project->canUserView($viewer))->toBeTrue()
        ->and($project->canUserManage($viewer))->toBeFalse();

    $this->actingAs($viewer)->get('/dashboard')
        ->assertOk()
        ->assertSee('Executive Portfolio &amp; Governance Overview', false)
        ->assertSeeInOrder(['At Risk', '>1<'], false)
        ->assertDontSee('Create Project');

    $this->actingAs($viewer)->get('/projects')->assertOk()->assertSee('Executive Read Only Project');
    $this->actingAs($viewer)->get("/projects/{$project->id}")
        ->assertOk()
        ->assertSee('Project Workspace')
        ->assertSee('Executive Read Only Project')
        ->assertSee('Task Sheet');

    Livewire::actingAs($viewer)
        ->test(WbsTree::class, ['project' => $project])
        ->assertDontSee('>Action<', false);

    Livewire::actingAs($viewer)
        ->test(ProjectTaskSheet::class, ['project' => $project])
        ->assertSee('Project Work Plan')
        ->assertDontSee('>Edit<', false)
        ->assertDontSee('>Save<', false);

    Livewire::actingAs($viewer)
        ->test(ProjectWorkspace::class, ['project' => $project])
        ->call('setTab', 'sheet')
        ->assertSet('activeTab', 'sheet')
        ->assertSeeLivewire(ProjectTaskSheet::class);
    $this->actingAs($viewer)->get('/projects/my-leads')->assertRedirect('/projects');
    $this->actingAs($viewer)->get('/all-tasks')->assertOk();
    $this->actingAs($viewer)->get('/daily-updates')
        ->assertOk()
        ->assertSee('Executive Read Only Project')
        ->assertDontSee('Add update');
    $this->actingAs($viewer)->get('/users')
        ->assertOk()
        ->assertDontSee('wire:click="openCreateModal"', false)
        ->assertDontSee('>ACCOUNT STATUS<', false)
        ->assertDontSee('>ACTIONS<', false);
    $this->actingAs($viewer)->get('/subsidiaries')
        ->assertOk()
        ->assertDontSee('>ACTIONS<', false)
        ->assertDontSee('Edit Subsidiary');

    $this->actingAs($viewer)->get('/projects/create')->assertForbidden();
    $this->actingAs($viewer)->get('/roles-permissions')->assertForbidden();
    $this->actingAs($viewer)->get('/settings')->assertForbidden();
});
