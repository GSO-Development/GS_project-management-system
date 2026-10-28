<?php

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Livewire\ProjectTaskSheet;
use App\Livewire\ProjectWorkspace;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use Livewire\Livewire;

test('project workspace displays Task Sheet tab right next to Task List and switches tabs', function () {
    $subsidiary = Subsidiary::create(['name' => 'Sub Workspace', 'code' => 'SWK']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $pm->assignRole('super_admin');
    $project = Project::create([
        'name' => 'Workspace Sheet Test',
        'code' => 'WST-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
        'status' => 'in_progress',
    ]);

    $component = Livewire::actingAs($pm)
        ->test(ProjectWorkspace::class, ['project' => $project])
        ->assertSee('Task List')
        ->assertSee('Task Sheet')
        ->set('activeTab', 'sheet')
        ->assertSeeLivewire(ProjectTaskSheet::class)
        ->dispatch('closeTaskSheet')
        ->assertSet('activeTab', 'wbs');

    $component
        ->call('openProjectDetailsModal')
        ->assertSet('showProjectDetailsModal', true)
        ->assertSee('Project Overview')
        ->assertSee('Workspace Sheet Test')
        ->call('closeProjectDetailsModal')
        ->assertSet('showProjectDetailsModal', false);
});

test('project task sheet renders all project tasks hierarchically with all columns', function () {
    $subsidiary = Subsidiary::create(['name' => 'Sub Sheet', 'code' => 'SSH']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id, 'name' => 'Lead PM']);
    $pm->assignRole('super_admin');
    $dev = User::factory()->create(['subsidiary_id' => $subsidiary->id, 'name' => 'Senior Dev']);
    $project = Project::create([
        'name' => 'Full Sheet Test Project',
        'code' => 'FST-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
        'status' => 'in_progress',
    ]);
    $project->members()->attach($dev->id, ['role' => 'member']);

    // Root Phase
    $phase = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'item_type' => ItemType::PHASE,
        'title' => 'Project Implementation Plan',
        'start_date' => '2026-11-02',
        'end_date' => '2026-11-13',
        'status' => WbsStatus::IN_PROGRESS,
        'priority' => Priority::HIGH,
        'progress' => 30,
        'assigned_user_id' => $pm->id,
        'created_by' => $pm->id,
    ]);

    // Subtask 1
    $sub1 = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $phase->id,
        'wbs_code' => '1.1',
        'item_type' => ItemType::SUBTASK,
        'title' => 'Configure Server Infrastructure',
        'start_date' => '2026-11-02',
        'end_date' => '2026-11-06',
        'status' => WbsStatus::COMPLETED,
        'priority' => Priority::MEDIUM,
        'progress' => 100,
        'assigned_user_id' => $dev->id,
        'created_by' => $pm->id,
    ]);

    // Subtask 2 with hourly duration
    $sub2 = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $phase->id,
        'wbs_code' => '1.2',
        'item_type' => ItemType::SUBTASK,
        'title' => '08:30 AM – 12:30 PM DB Setup',
        'start_date' => '2026-11-09',
        'start_time' => '08:30:00',
        'end_date' => '2026-11-09',
        'end_time' => '12:30:00',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'assigned_user_id' => $dev->id,
        'created_by' => $pm->id,
    ]);

    // Dependency: sub2 depends on sub1
    WbsDependency::create([
        'predecessor_id' => $sub1->id,
        'successor_id' => $sub2->id,
        'dependency_type' => 'finish_to_start',
    ]);

    Livewire::actingAs($pm)
        ->test(ProjectTaskSheet::class, ['project' => $project])
        ->assertSee('Project Implementation Plan')
        ->assertSee('Configure Server Infrastructure')
        ->assertSee('08:30 AM – 12:30 PM DB Setup')
        ->assertSee('12 Days') // Nov 2 to Nov 13
        ->assertSee('5 Days')  // Nov 2 to Nov 6
        ->assertSee('4 Hours') // 08:30 to 12:30
        ->assertSee('Senior Dev')
        ->assertSee('Lead PM')
        ->assertSee('#1.1') // Predecessor badge
        ->assertSee('100%')
        ->assertSee('Completed')
        // Test live search filter
        ->set('search', 'Server')
        ->assertSee('Configure Server Infrastructure')
        ->assertDontSee('DB Setup')
        ->set('search', '')
        ->assertSee('DB Setup')
        // Test status filter
        ->set('statusFilter', 'completed')
        ->assertSee('Configure Server Infrastructure')
        ->assertDontSee('DB Setup')
        // Test reset filters
        ->call('resetFilters')
        ->assertSee('DB Setup')
        // Test collapse and expand all
        ->call('collapseAll')
        ->call('expandAll')
        ->assertSee('DB Setup');
});
