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
        ->set('activeTab', 'team')
        ->assertSee('Project Team Roster')
        ->assertSee('Lead project manager')
        ->assertSee('team-lead', false)
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


test('task sheet bulk edit validates all rows and saves or cancels together', function () {
    $sub = Subsidiary::create(['name' => 'Bulk', 'code' => 'BLK']);
    $pm = User::factory()->create(['subsidiary_id' => $sub->id]);
    $project = Project::create(['name' => 'Bulk edit', 'code' => 'BLK-1', 'subsidiary_id' => $sub->id, 'project_manager_id' => $pm->id, 'status' => 'in_progress']);
    $one = WbsItem::create(['project_id' => $project->id, 'title' => 'One', 'status' => 'not_started', 'priority' => 'medium', 'created_by' => $pm->id]);
    $two = WbsItem::create(['project_id' => $project->id, 'title' => 'Two', 'status' => 'not_started', 'priority' => 'medium', 'created_by' => $pm->id]);
    $component = Livewire::actingAs($pm)->test(ProjectTaskSheet::class, ['project' => $project])
        ->assertDontSee('>Mode<', false)->assertSee('Edit')->call('startEditing')->assertSet('editing', true)
        ->set("drafts.$one->id.title", 'Changed one')->set("drafts.$two->id.title", '')
        ->call('saveTasks')->assertHasErrors(["drafts.$two->id.title"]);
    expect($one->fresh()->title)->toBe('One');
    $component->set("drafts.$two->id.title", 'Changed two')->set("drafts.$two->id.progress", 100)
        ->call('saveTasks')->assertHasNoErrors()->assertSet('editing', false);
    expect($one->fresh()->title)->toBe('Changed one');
    expect($two->fresh()->title)->toBe('Changed two');
    expect($two->fresh()->status)->toBe(WbsStatus::COMPLETED);
    $component->call('startEditing')->set("drafts.$one->id.title", 'Discard')->call('cancelEditing');
    expect($one->fresh()->title)->toBe('Changed one');
    $component->call('startEditing')->set("drafts.$one->id.start_date", '2026-11-10')->set("drafts.$one->id.end_date", '2026-11-01')->call('saveTasks')->assertHasErrors(["drafts.$one->id.end_date"]);
});

test('task sheet rejects unauthorized bulk edits', function () {
    $sub = Subsidiary::create(['name' => 'Permissions', 'code' => 'PER']);
    $pm = User::factory()->create(['subsidiary_id' => $sub->id]);
    $other = User::factory()->create(['subsidiary_id' => $sub->id]);
    $project = Project::create(['name' => 'Permission edit', 'code' => 'PER-1', 'subsidiary_id' => $sub->id, 'project_manager_id' => $pm->id, 'status' => 'in_progress']);
    Livewire::actingAs($other)->test(ProjectTaskSheet::class, ['project' => $project])->call('startEditing')->assertForbidden();
    Livewire::actingAs($other)->test(ProjectTaskSheet::class, ['project' => $project])->set('editing', true)->call('saveTasks')->assertForbidden();
});


test('sheet date edits review and cascade without shifting overlapping edits twice', function () {
    $sub = Subsidiary::create(['name' => 'Cascade', 'code' => 'CAS']);
    $pm = User::factory()->create(['subsidiary_id' => $sub->id]);
    $project = Project::create(['name' => 'Cascade sheet', 'code' => 'CAS-1', 'subsidiary_id' => $sub->id, 'project_manager_id' => $pm->id, 'status' => 'in_progress']);
    $items = collect();
    foreach ([1, 2, 3] as $n) {
        $items->push(WbsItem::create(['project_id' => $project->id, 'wbs_code' => (string) $n, 'title' => 'Task '.$n, 'item_type' => ItemType::TASK, 'start_date' => '2026-11-0'.$n, 'end_date' => '2026-11-0'.$n, 'status' => WbsStatus::NOT_STARTED, 'priority' => Priority::MEDIUM, 'created_by' => $pm->id]));
    }
    [$a, $b, $c] = $items->all();
    $component = Livewire::actingAs($pm)->test(ProjectTaskSheet::class, ['project' => $project])->call('startEditing')
        ->set("drafts.$a->id.end_date", '2026-11-03')->set("drafts.$b->id.start_date", '2026-11-04')->set("drafts.$b->id.end_date", '2026-11-04')
        ->call('saveTasks')->assertSet('showCascadeModal', true)->assertSee('Save & Cascade');
    expect($a->fresh()->end_date->toDateString())->toBe('2026-11-01');
    $component->call('cancelCascade')->assertSet('editing', true)->assertSet('showCascadeModal', false)
        ->call('saveTasks')->call('confirmCascade')->assertHasNoErrors()->assertSet('editing', false);
    expect($a->fresh()->end_date->toDateString())->toBe('2026-11-03');
    expect($b->fresh()->end_date->toDateString())->toBe('2026-11-04');
    expect($c->fresh()->end_date->toDateString())->toBe('2026-11-05');
    expect($project->fresh()->deadline->toDateString())->toBe('2026-11-05');
    $component->call('startEditing')->set("drafts.$a->id.end_date", '2026-11-04')->call('saveTasks')->call('saveWithoutCascade')->assertHasNoErrors();
    expect($a->fresh()->end_date->toDateString())->toBe('2026-11-04');
    expect($c->fresh()->end_date->toDateString())->toBe('2026-11-05');
});


test('extending a summary deadline preserves its start and shifts all following rows', function () {
    $sub = Subsidiary::create(['name' => 'Summary', 'code' => 'SUM']);
    $pm = User::factory()->create(['subsidiary_id' => $sub->id]);
    $project = Project::create(['name' => 'Summary cascade', 'code' => 'SUM-1', 'subsidiary_id' => $sub->id, 'project_manager_id' => $pm->id, 'status' => 'in_progress']);
    $base = ['project_id' => $project->id, 'status' => WbsStatus::NOT_STARTED, 'priority' => Priority::MEDIUM, 'created_by' => $pm->id];
    $phase = WbsItem::create($base + ['wbs_code' => '1', 'title' => 'Planning', 'item_type' => ItemType::PHASE, 'start_date' => '2026-11-02', 'end_date' => '2026-11-08']);
    $child = WbsItem::create($base + ['parent_id' => $phase->id, 'wbs_code' => '1.1', 'title' => 'Kickoff', 'item_type' => ItemType::TASK, 'start_date' => '2026-11-02', 'end_date' => '2026-11-02']);
    $last = WbsItem::create($base + ['parent_id' => $phase->id, 'wbs_code' => '1.2', 'title' => 'Review', 'item_type' => ItemType::TASK, 'start_date' => '2026-11-03', 'end_date' => '2026-11-08']);
    $next = WbsItem::create($base + ['wbs_code' => '2', 'title' => 'Next task', 'item_type' => ItemType::TASK, 'start_date' => '2026-11-09', 'end_date' => '2026-11-10']);
    Livewire::actingAs($pm)->test(ProjectTaskSheet::class, ['project' => $project])->call('startEditing')
        ->set("drafts.$phase->id.end_date", '2026-11-09')->call('saveTasks')->assertSet('showCascadeModal', true)
        ->call('confirmCascade')->assertHasNoErrors();
    expect($phase->fresh()->start_date->toDateString())->toBe('2026-11-02');
    expect($phase->fresh()->end_date->toDateString())->toBe('2026-11-09');
    expect($child->fresh()->start_date->toDateString())->toBe('2026-11-03');
    expect($last->fresh()->start_date->toDateString())->toBe('2026-11-04');
    expect($last->fresh()->end_date->toDateString())->toBe('2026-11-09');
    expect($next->fresh()->start_date->toDateString())->toBe('2026-11-10');
    expect($next->fresh()->end_date->toDateString())->toBe('2026-11-11');
});


test('sheet start-only moves retain duration and the preview matches every saved date', function () {
    $sub = Subsidiary::create(['name' => 'Start Move', 'code' => 'STM']);
    $pm = User::factory()->create(['subsidiary_id' => $sub->id]);
    $project = Project::create(['name' => 'Ordered dates', 'code' => 'STM-1', 'subsidiary_id' => $sub->id, 'project_manager_id' => $pm->id, 'status' => 'in_progress']);
    $base = ['project_id' => $project->id, 'status' => WbsStatus::NOT_STARTED, 'priority' => Priority::MEDIUM, 'item_type' => ItemType::TASK, 'created_by' => $pm->id];
    // WBS labels deliberately differ from display order; sort_order controls the sheet.
    $before = WbsItem::create($base + ['wbs_code' => '9', 'sort_order' => 1, 'title' => 'Earlier', 'start_date' => '2026-11-01', 'end_date' => '2026-11-01']);
    $source = WbsItem::create($base + ['wbs_code' => '2', 'sort_order' => 2, 'title' => 'Source', 'start_date' => '2026-11-02', 'end_date' => '2026-11-04']);
    $after = WbsItem::create($base + ['wbs_code' => '1', 'sort_order' => 3, 'title' => 'Following', 'start_date' => '2026-11-05', 'end_date' => '2026-11-07', 'start_time' => '09:00', 'end_time' => '17:00']);
    $component = Livewire::actingAs($pm)->test(ProjectTaskSheet::class, ['project' => $project])->call('startEditing')
        ->set("drafts.$source->id.start_date", '2026-11-10')->call('saveTasks')->assertHasNoErrors()->assertSet('showCascadeModal', true);
    $preview = $component->get('cascadePreview')[0];
    expect($source->fresh()->start_date->toDateString())->toBe('2026-11-02');
    $component->call('confirmCascade')->assertHasNoErrors();
    foreach ($preview['tasks'] as $row) {
        $saved = WbsItem::findOrFail($row['id']);
        expect($saved->start_date->toDateString())->toBe($row['new_start']);
        expect($saved->end_date->toDateString())->toBe($row['new_end']);
    }
    expect($before->fresh()->start_date->toDateString())->toBe('2026-11-01');
    expect($source->fresh()->end_date->toDateString())->toBe('2026-11-12');
    expect($after->fresh()->start_date->toDateString())->toBe('2026-11-13');
    expect(substr($after->fresh()->start_time, 0, 5))->toBe('09:00');
    $component->call('startEditing')->set("drafts.$source->id.end_date", '2026-11-11')->call('saveTasks')->call('confirmCascade')->assertHasNoErrors();
    expect($source->fresh()->start_date->toDateString())->toBe('2026-11-10');
    expect($after->fresh()->start_date->toDateString())->toBe('2026-11-12');
    expect($after->fresh()->end_date->toDateString())->toBe('2026-11-14');
});
