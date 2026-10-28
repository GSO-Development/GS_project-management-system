<?php

use App\Enums\ItemType;
use App\Enums\Priority;
use App\Enums\WbsStatus;
use App\Livewire\GanttChart;
use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsDependency;
use App\Models\WbsItem;
use Livewire\Livewire;

test('project manager can update task priority inline from the task list', function () {
    $subsidiary = Subsidiary::create(['name' => 'Priority Sub', 'code' => 'PRI']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Priority Test',
        'code' => 'PRI-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
    ]);
    $task = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'item_type' => ItemType::TASK,
        'title' => 'Prioritized task',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->assertSee('Priority')
        ->call('updateItemPriority', $task->id, 'high');

    expect($task->fresh()->priority)->toBe(Priority::HIGH);
});

test('duration calculates days for multi-day tasks and hours for same-day time slots', function () {
    $subsidiary = Subsidiary::create(['name' => 'Sub D', 'code' => 'SD']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Duration Test',
        'code' => 'DUR-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
    ]);

    // Multi-day task: Nov 02, 2026 to Nov 06, 2026 -> 5 Days
    $multiDay = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'item_type' => ItemType::PHASE,
        'title' => 'Project Initiation & Planning',
        'start_date' => '2026-11-02',
        'start_time' => '09:00:00',
        'end_date' => '2026-11-06',
        'end_time' => '17:00:00',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    expect($multiDay->duration_formatted)->toBe('5 Days');

    // Same-day hourly slot: 08:30 AM to 12:30 PM -> 4 Hours
    $morning = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $multiDay->id,
        'wbs_code' => '1.1',
        'item_type' => ItemType::SUBTASK,
        'title' => '08:30 AM – 12:30 PM',
        'start_date' => '2026-11-02',
        'start_time' => '08:30:00',
        'end_date' => '2026-11-02',
        'end_time' => '12:30:00',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    expect($morning->duration_formatted)->toBe('4 Hours');

    // Same-day afternoon slot: 12:30 PM to 05:30 PM -> 5 Hours
    $afternoon = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $multiDay->id,
        'wbs_code' => '1.2',
        'item_type' => ItemType::SUBTASK,
        'title' => '12:30 PM – 05:30 PM',
        'start_date' => '2026-11-02',
        'start_time' => '12:30:00',
        'end_date' => '2026-11-02',
        'end_time' => '17:30:00',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    expect($afternoon->duration_formatted)->toBe('5 Hours');

    // Slot with title regex fallback (times not explicitly stored)
    $titleSlot = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $multiDay->id,
        'wbs_code' => '1.3',
        'item_type' => ItemType::SUBTASK,
        'title' => 'Session 09:00 AM – 11:00 AM',
        'start_date' => '2026-11-02',
        'end_date' => '2026-11-02',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    expect($titleSlot->duration_formatted)->toBe('2 Hours');
});

test('wbs tree table renders duration and dependency columns and allows managing dependencies', function () {
    $subsidiary = Subsidiary::create(['name' => 'Sub DEP', 'code' => 'SDEP']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $pm->assignRole('super_admin');
    $project = Project::create([
        'name' => 'Dependency Test Project',
        'code' => 'DEP-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
    ]);

    $task1 = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'item_type' => ItemType::TASK,
        'title' => 'First Task',
        'start_date' => '2026-11-02',
        'end_date' => '2026-11-06',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    $task2 = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '2',
        'item_type' => ItemType::TASK,
        'title' => 'Second Task',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-13',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    // Create dependency: task2 depends on task1
    WbsDependency::create([
        'predecessor_id' => $task1->id,
        'successor_id' => $task2->id,
        'dependency_type' => 'finish_to_start',
    ]);

    $component = Livewire::actingAs($pm)
        ->test(WbsTree::class, ['project' => $project])
        ->assertSee('Duration')
        ->assertSee('Dependencies')
        ->assertSee('5 Days')
        ->assertSee('#1');

    // Test removing dependency via modal
    $dep = WbsDependency::first();
    $component->call('removeDependency', $dep->id);
    expect(WbsDependency::count())->toBe(0);

    // Test adding dependency via modal
    $component->set('depSuccessorId', $task2->id)
        ->set('depPredecessorId', $task1->id)
        ->call('addDependency');

    expect(WbsDependency::where('predecessor_id', $task1->id)->where('successor_id', $task2->id)->exists())->toBeTrue();
});

test('gantt chart renders visible task dependency connectors', function () {
    $subsidiary = Subsidiary::create(['name' => 'Gantt Dependency Sub', 'code' => 'GDEP']);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $project = Project::create([
        'name' => 'Gantt Dependency Project',
        'code' => 'GDEP-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
        'start_date' => '2026-11-01',
        'deadline' => '2026-11-30',
    ]);

    $predecessor = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '1',
        'item_type' => ItemType::TASK,
        'title' => 'Prepare requirements',
        'start_date' => '2026-11-02',
        'end_date' => '2026-11-06',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);
    $successor = WbsItem::create([
        'project_id' => $project->id,
        'wbs_code' => '2',
        'item_type' => ItemType::TASK,
        'title' => 'Build solution',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-13',
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::HIGH,
        'progress' => 0,
        'created_by' => $pm->id,
    ]);

    WbsDependency::create([
        'predecessor_id' => $predecessor->id,
        'successor_id' => $successor->id,
        'dependency_type' => 'finish_to_start',
    ]);

    Livewire::actingAs($pm)
        ->test(GanttChart::class, ['project' => $project])
        ->assertSee('1 dependencies')
        ->assertSee('gantt-dependency-layer', false)
        ->assertSee('gantt-dependency-path', false)
        ->assertSee('waiting for predecessor');
});
