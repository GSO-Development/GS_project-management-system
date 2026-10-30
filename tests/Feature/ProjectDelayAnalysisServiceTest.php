<?php

use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Models\WbsDependency;
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
        ->and($analysis['delayed_task_count'])->toBe(1)
        ->and($analysis['critical_issue_count'])->toBe(0)
        ->and($analysis['issues'])->toHaveCount(1)
        ->and($analysis['issues'][0]['is_delayed'])->toBeTrue()
        ->and($analysis['issues'][0]['is_critical'])->toBeFalse()
        ->and($analysis['issues'][0]['reason'])->toBe('Supplier delivery is late')
        ->and($analysis['issues'][0]['responsible'])->toBe($user->name);
});

it('explains incomplete dependencies and every detected delay cause', function () {
    $user = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'DEP', 'name' => 'Dependency Test']);
    $project = Project::create([
        'code' => 'DEP-001',
        'name' => 'Dependency Delay Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'status' => 'in_progress',
    ]);
    $predecessor = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'title' => 'Complete foundation',
        'status' => 'in_progress',
        'progress' => 40,
    ]);
    $task = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '2',
        'title' => 'Start construction',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDays(5),
        'status' => 'not_started',
        'progress' => 0,
    ]);
    WbsDependency::create([
        'predecessor_id' => $predecessor->id,
        'successor_id' => $task->id,
        'dependency_type' => 'finish_to_start',
        'lag_days' => 0,
    ]);

    $analysis = app(ProjectDelayAnalysisService::class)->analyze($project);
    $issue = collect($analysis['issues'])->firstWhere('task_id', $task->id);

    expect($issue)->not->toBeNull()
        ->and($analysis['critical_issue_count'])->toBeGreaterThan(0)
        ->and($issue['is_delayed'])->toBeTrue()
        ->and($issue['is_critical'])->toBeTrue()
        ->and($issue['incomplete_dependencies'])->toHaveCount(1)
        ->and($issue['incomplete_dependencies'][0]['wbs_code'])->toBe('1')
        ->and(collect($issue['causes'])->pluck('type'))->toContain('dependency')
        ->and(collect($issue['causes'])->pluck('type'))->not->toContain('ownership');
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
        ->assertSee('Delayed Tasks')
        ->assertSee('Critical Tasks')
        ->assertSee("taskFilter === 'delayed'", false)
        ->assertSee("taskFilter === 'critical'", false)
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

it('opens the delay analysis popup from user MyLeadProjects list', function () {
    $user = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'USR', 'name' => 'User PM Subsidiary']);
    $project = Project::create([
        'code' => 'USR-001',
        'name' => 'My Lead Delayed Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'deadline' => now()->subDays(5),
        'status' => 'in_progress',
    ]);
    WbsItem::create([
        'project_id' => $project->id,
        'title' => 'Critical path backend task',
        'assigned_user_id' => $user->id,
        'end_date' => now()->subDays(5),
        'status' => 'in_progress',
        'progress' => 30,
        'delay_reason' => 'Blocked on third party API keys',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\App\Livewire\MyLeadProjects::class)
        ->call('openDelayAnalysis', $project->id)
        ->assertSet('showDelayAnalysis', true)
        ->assertSet('delayProjectId', $project->id)
        ->assertSee('Critical delay analysis')
        ->assertSee('My Lead Delayed Project')
        ->assertSee('Blocked on third party API keys')
        ->assertSee('Why is this project delayed?');
});

it('filters overdue projects on MyLeadProjects', function () {
    $user = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'FLT', 'name' => 'Filter Subsidiary']);
    $overdueProject = Project::create([
        'code' => 'FLT-001',
        'name' => 'Overdue Alpha',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'deadline' => now()->subDays(2),
        'status' => 'in_progress',
    ]);
    $onTrackProject = Project::create([
        'code' => 'FLT-002',
        'name' => 'Future Beta',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'deadline' => now()->addDays(20),
        'status' => 'in_progress',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\App\Livewire\MyLeadProjects::class)
        ->set('statusFilter', 'overdue')
        ->assertSee('Overdue Alpha')
        ->assertDontSee('Future Beta');
});

it('opens the schedule project event modal on corporate calendar', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');
    $subsidiary = Subsidiary::create(['code' => 'CAL', 'name' => 'Calendar Subsidiary']);
    $project = Project::create([
        'code' => 'CAL-001',
        'name' => 'Calendar Schedule Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $user->id,
        'created_by' => $user->id,
        'status' => 'in_progress',
    ]);

    \Livewire\Livewire::actingAs($user)
        ->test(\App\Livewire\CalendarView::class)
        ->call('openCreateEventModal', now()->format('Y-m-d'))
        ->assertSet('showCreateEventModal', true)
        ->assertSet('createEventStep', 1)
        ->assertSee('Schedule Project Event')
        ->assertSee('Event Scheduling Wizard')
        ->assertSee('Check Availability & Time');
});


