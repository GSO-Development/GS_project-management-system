<?php

use App\Enums\WbsStatus;
use App\Livewire\ProjectWorkspace;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    $this->manager = User::factory()->create(['is_active' => true]);
    $this->manager->assignRole('project_manager');
    $this->riskProject = Project::create([
        'code' => 'RPM-001',
        'name' => 'Risk Popup Project',
        'subsidiary_id' => Subsidiary::create(['code' => 'RPM', 'name' => 'Risk Modal Entity'])->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'pm_accepted' => true,
        'status' => 'planning',
        'priority' => 'medium',
    ]);
});

test('log project risk opens a dialog and a successful save closes it and updates the register', function () {
    $task = WbsItem::create(['project_id' => $this->riskProject->id, 'title' => 'Vendor integration', 'item_type' => 'task', 'status' => 'not_started']);

    Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->riskProject->fresh()])
        ->set('activeTab', 'risks')
        ->assertSee('wire:click="openCreateRiskModal"', false)
        ->assertDontSee('wire:submit="addRisk"', false)
        ->call('openCreateRiskModal')
        ->assertSet('showCreateRiskModal', true)
        ->assertSee('aria-labelledby="project-risk-create-title"', false)
        ->assertSee('wire:submit="addRisk"', false)
        ->assertSee('Vendor integration')
        ->set('riskTitle', 'Vendor delivery delay')
        ->set('riskDescription', 'Late vendor delivery could postpone the integration milestone.')
        ->set('riskWbsItemId', $task->id)
        ->set('riskCategory', 'Schedule')
        ->set('riskProbability', 'high')
        ->set('riskImpact', 'critical')
        ->set('riskMitigation', 'Confirm vendor milestones weekly.')
        ->call('addRisk')
        ->assertHasNoErrors()
        ->assertSet('showCreateRiskModal', false)
        ->assertDontSee('wire:submit="addRisk"', false)
        ->assertSee('Vendor delivery delay')
        ->assertDispatched('riskUpdated');

    $risk = ProjectRisk::where('project_id', $this->riskProject->id)->sole();
    expect($risk->wbs_item_id)->toBe($task->id)
        ->and($risk->risk_score)->toBe(12)
        ->and($risk->mitigation_plan)->toBe('Confirm vendor milestones weekly.')
        ->and($task->fresh()->status)->toBe(WbsStatus::AT_RISK);
});

test('validation stays in the risk popup and cancel clears errors without saving', function () {
    $component = Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->riskProject])
        ->set('activeTab', 'risks')
        ->call('openCreateRiskModal')
        ->call('addRisk')
        ->assertHasErrors(['riskTitle', 'riskDescription'])
        ->assertSet('showCreateRiskModal', true)
        ->assertSee('aria-invalid="true"', false)
        ->set('riskTitle', 'Unsaved draft risk')
        ->call('closeCreateRiskModal')
        ->assertSet('showCreateRiskModal', false)
        ->assertHasNoErrors()
        ->call('openCreateRiskModal')
        ->assertSet('riskTitle', '')
        ->assertSet('riskDescription', '')
        ->assertSet('riskProbability', 'medium')
        ->assertSet('riskImpact', 'medium');

    expect(ProjectRisk::where('project_id', $this->riskProject->id)->count())->toBe(0);
});

test('new risk popup starts fresh after editing a risk', function () {
    $risk = ProjectRisk::create([
        'project_id' => $this->riskProject->id,
        'title' => 'Existing risk',
        'description' => 'Original risk description.',
        'category' => 'Technical',
        'probability' => 'high',
        'impact' => 'high',
        'status' => 'open',
    ]);

    Livewire::actingAs($this->manager)
        ->test(ProjectWorkspace::class, ['project' => $this->riskProject])
        ->set('activeTab', 'risks')
        ->call('openEditRiskModal', $risk->id)
        ->assertSet('showEditRiskModal', true)
        ->call('openCreateRiskModal')
        ->assertSet('showEditRiskModal', false)
        ->assertSet('editingRiskId', null)
        ->assertSet('showCreateRiskModal', true)
        ->assertSet('riskTitle', '')
        ->assertSet('riskProbability', 'medium');

    expect($risk->fresh()->title)->toBe('Existing risk');
});

test('roles without risk creation permission cannot open or force display of the popup', function (string $role) {
    $viewer = User::factory()->create(['is_active' => true]);
    $viewer->assignRole($role);
    $this->riskProject->members()->attach($viewer->id, ['role' => 'owner']);

    Livewire::actingAs($viewer)
        ->test(ProjectWorkspace::class, ['project' => $this->riskProject])
        ->set('activeTab', 'risks')
        ->assertDontSee('wire:click="openCreateRiskModal"', false)
        ->set('showCreateRiskModal', true)
        ->assertDontSee('wire:submit="addRisk"', false)
        ->call('openCreateRiskModal')
        ->assertForbidden();
})->with(['admin_viewer', 'owner']);
