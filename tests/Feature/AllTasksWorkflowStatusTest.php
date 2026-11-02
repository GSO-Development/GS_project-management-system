<?php

use App\Livewire\AllTasksViewer;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Livewire\Livewire;

it('groups the all tasks directory into the four workflow statuses', function () {
    $admin = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'ATS', 'name' => 'All Tasks Status Test']);
    $project = Project::create([
        'code' => 'ATS-001',
        'name' => 'Workflow Status Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $admin->id,
        'created_by' => $admin->id,
        'status' => 'in_progress',
    ]);

    foreach ([
        'backlog' => 'Backlog task',
        'not_started' => 'Not started task',
        'in_progress' => 'Active task',
        'under_review' => 'Review task',
        'at_risk' => 'At risk task',
        'on_hold' => 'Paused task',
        'blocked' => 'Blocked task',
        'completed' => 'Completed task',
        'cancelled' => 'Cancelled task',
    ] as $status => $title) {
        WbsItem::create([
            'project_id' => $project->id,
            'title' => $title,
            'status' => $status,
        ]);
    }

    $riskTask = WbsItem::where('title', 'Not started task')->firstOrFail();
    ProjectRisk::create([
        'project_id' => $project->id,
        'wbs_item_id' => $riskTask->id,
        'title' => 'Linked task risk',
        'description' => 'Risk used to verify the All Tasks at-risk filter.',
        'category' => 'schedule',
        'probability' => 'medium',
        'impact' => 'high',
        'status' => 'open',
    ]);

    $component = Livewire::actingAs($admin)
        ->test(AllTasksViewer::class)
        ->set('showFilters', true)
        ->assertSee('All Statuses (8)')
        ->assertSee('Not Started (2)')
        ->assertSee('In Progress (3)')
        ->assertSee('Completed (1)')
        ->assertSee('On Hold (2)')
        ->assertSee('aria-label="Filter at-risk tasks: 2"', false)
        ->assertDontSee('<option value="blocked">', false)
        ->assertDontSee('Cancelled task');

    $component->set('statusFilter', 'in_progress')
        ->assertSee('Active task')
        ->assertSee('Review task')
        ->assertSee('At risk task')
        ->assertDontSee('Paused task');

    $component->set('statusFilter', 'on_hold')
        ->assertSee('Paused task')
        ->assertSee('Blocked task')
        ->assertDontSee('Active task');

    $component->call('setAtRiskFilter')
        ->assertSet('statusFilter', 'all')
        ->assertSet('riskFilter', 'at_risk')
        ->assertSee('At risk task')
        ->assertSee('Not started task')
        ->assertDontSee('Active task')
        ->assertDontSee('Paused task');

    WbsItem::where('title', 'Active task')->update(['end_date' => now()->subDay()]);

    $component->call('setOverdueFilter')
        ->assertSet('statusFilter', 'all')
        ->assertSet('dueFilter', 'overdue')
        ->assertSee('Active task')
        ->assertDontSee('Paused task');
});

