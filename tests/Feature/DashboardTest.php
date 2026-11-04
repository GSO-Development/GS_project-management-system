<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Livewire\ProjectManagerDashboard;
use Livewire\Livewire;

test('user dashboard renders project manager dashboard component successfully', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(ProjectManagerDashboard::class)
        ->assertStatus(200)
        ->assertSee('My Projects')
        ->assertSee('Quick Access');
});

test('user dashboard can toggle chart period between week and month', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(ProjectManagerDashboard::class)
        ->assertSet('chartPeriod', 'week')
        ->call('setChartPeriod', 'month')
        ->assertSet('chartPeriod', 'month')
        ->assertStatus(200)
        ->call('setChartPeriod', 'week')
        ->assertSet('chartPeriod', 'week')
        ->assertStatus(200);
});

test('leadership banner hides after rejection and returns after reassignment', function () {
    $subsidiary = Subsidiary::create(['code' => 'INV', 'name' => 'Invitation Entity']);
    $manager = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'code' => 'INV-001',
        'name' => 'Leadership lifecycle project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $manager->id,
        'pm_accepted' => false,
    ]);

    $dashboard = Livewire::actingAs($manager)->test(ProjectManagerDashboard::class)
        ->assertSee('Review INV-001');

    $project->update([
        'pm_rejection_reason' => 'Current workload prevents accepting this assignment.',
        'pm_rejected_at' => now(),
    ]);

    $dashboard->call('$refresh')->assertDontSee('Review INV-001');

    // Reassignment clears the previous decision and creates a fresh pending invitation.
    $project->update([
        'pm_rejection_reason' => null,
        'pm_rejected_at' => null,
        'pm_accepted' => false,
    ]);

    $dashboard->call('$refresh')->assertSee('Review INV-001');
});
