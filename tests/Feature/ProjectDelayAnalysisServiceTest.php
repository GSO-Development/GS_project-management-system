<?php

use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ProjectDelayAnalysisService;

it('identifies an overdue task reason and responsible person', function () {
    $user = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'TST', 'name' => 'Test Subsidiary']);
    $project = Project::create([
        'code' => 'TST-001',
        'name' => 'Delayed Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'status' => 'in_progress',
    ]);
    WbsItem::create([
        'project_id' => $project->id,
        'title' => 'Delayed delivery',
        'assigned_user_id' => $user->id,
        'end_date' => now()->subDays(3),
        'status' => 'in_progress',
        'progress' => 60,
        'delay_reason' => 'Supplier delivery is late',
    ]);

    $analysis = app(ProjectDelayAnalysisService::class)->analyze($project);

    expect($analysis['is_delayed'])->toBeFalse()
        ->and($analysis['has_task_delays'])->toBeTrue()
        ->and($analysis['issues'])->toHaveCount(1)
        ->and($analysis['issues'][0]['reason'])->toBe('Supplier delivery is late')
        ->and($analysis['issues'][0]['responsible'])->toBe($user->name);
});

it('opens the delay analysis popup from the projects list', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $subsidiary = Subsidiary::create(['code' => 'POP', 'name' => 'Popup Test']);
    $project = Project::create([
        'code' => 'POP-001',
        'name' => 'Popup Delayed Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'deadline' => now()->subDay(),
        'status' => 'in_progress',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\App\Livewire\ProjectIndex::class)
        ->call('openDelayAnalysis', $project->id)
        ->assertSet('showDelayAnalysis', true)
        ->assertSet('delayProjectId', $project->id)
        ->assertSee('Critical delay analysis')
        ->assertSee('Popup Delayed Project');
});

it('opens the popup from the reliable delay project URL', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $subsidiary = Subsidiary::create(['code' => 'URL', 'name' => 'URL Test']);
    $project = Project::create([
        'code' => 'URL-001',
        'name' => 'URL Delayed Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'deadline' => now()->subDay(),
        'status' => 'in_progress',
    ]);

    \Livewire\Livewire::withQueryParams(['delay_project' => $project->id])
        ->actingAs($user)
        ->test(\App\Livewire\ProjectIndex::class)
        ->assertSet('showDelayAnalysis', true)
        ->assertSet('delayProjectId', $project->id)
        ->assertSee('URL Delayed Project');
});
