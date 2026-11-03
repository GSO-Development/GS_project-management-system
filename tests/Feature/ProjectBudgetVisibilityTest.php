<?php

use App\Livewire\MyLeadProjects;
use App\Livewire\ProjectIndex;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->manager = User::factory()->create();
    $this->subsidiary = Subsidiary::create(['code' => 'BGT', 'name' => 'Budget Visibility']);
    $this->budgetProject = Project::create([
        'code' => 'BGT-001',
        'name' => 'Budget Visibility Project',
        'subsidiary_id' => $this->subsidiary->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'pm_accepted' => true,
        'estimated_budget' => 987654,
        'actual_cost' => 123456,
    ]);
});

test('admin project list shows actual budget immediately after budget', function () {
    Role::findOrCreate('super_admin', 'web');
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    Livewire::actingAs($admin)->test(ProjectIndex::class)
        ->assertSeeInOrder(['<th>Budget</th>', '<th>Actual Budget</th>'], false)
        ->assertSee('Rs. 987,654')
        ->assertSee('Rs. 123,456');
});

test('project manager sees both budget amounts for their project', function (bool $accepted, string $componentClass) {
    $this->budgetProject->update(['pm_accepted' => $accepted]);

    Livewire::actingAs($this->manager)->test($componentClass)
        ->assertSeeInOrder(['<th>Budget</th>', '<th>Actual Budget</th>'], false)
        ->assertSee('Budget Visibility Project')
        ->assertSee('Rs. 987,654')
        ->assertSee('Rs. 123,456');
})->with([false, true])->with([[MyLeadProjects::class], [ProjectIndex::class]]);

test('other project roles see budget amounts only on projects they manage', function (string $role, string $componentClass) {
    $viewer = User::factory()->create();
    $this->budgetProject->members()->attach($viewer->id, ['role' => $role]);
    Project::create([
        'code' => 'BGT-002',
        'name' => 'My Managed Budget Project',
        'subsidiary_id' => $this->subsidiary->id,
        'project_manager_id' => $viewer->id,
        'created_by' => $viewer->id,
        'pm_accepted' => true,
        'estimated_budget' => 333333,
        'actual_cost' => 222222,
    ]);

    Livewire::actingAs($viewer)->test($componentClass)
        ->assertSee('Budget Visibility Project')
        ->assertSee('My Managed Budget Project')
        ->assertDontSee('987,654')
        ->assertDontSee('123,456')
        ->assertSee('Rs. 333,333')
        ->assertSee('Rs. 222,222');
})->with(['member', 'sponsor', 'owner', 'steering_committee'])->with([[MyLeadProjects::class], [ProjectIndex::class]]);

test('actual budget is displayed even without an estimated budget and includes zero spending', function () {
    $this->budgetProject->update(['estimated_budget' => null]);
    $component = Livewire::actingAs($this->manager)->test(MyLeadProjects::class)
        ->assertSee('Actual Budget')
        ->assertSee('Rs. 123,456')
        ->assertDontSee('987,654');

    $this->budgetProject->update(['actual_cost' => 0]);
    $component->call('$refresh')->assertSee('Rs. 0')->assertDontSee('123,456');
});
