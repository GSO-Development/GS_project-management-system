<?php

use App\Models\Project;
use App\Models\User;
use App\Models\WbsItem;
use App\Models\Subsidiary;
use App\Enums\WbsStatus;
use App\Enums\Priority;
use App\Enums\ItemType;
use App\Livewire\WbsTree;
use Livewire\Livewire;

test('wbs tree does not duplicate child subtasks at top level when parent and child are assigned to same user', function () {
    $subsidiary = Subsidiary::create(['name' => 'Test Sub', 'code' => 'TS']);
    $user = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $otherUser = User::factory()->create(['subsidiary_id' => $subsidiary->id]);
    $pm = User::factory()->create(['subsidiary_id' => $subsidiary->id]);

    $project = Project::create([
        'name' => 'Duplication Test Project',
        'code' => 'DTP-01',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'pm_accepted' => true,
    ]);

    // Parent Task assigned to $user
    $parent = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => null,
        'wbs_code' => '1',
        'item_type' => ItemType::TASK,
        'title' => 'Day 2 Parent Task',
        'assigned_user_id' => $user->id,
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'weight' => 1.0,
        'created_by' => $pm->id,
    ]);

    // Child Subtask assigned to $user as well
    $child = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $parent->id,
        'wbs_code' => '1.1',
        'item_type' => ItemType::SUBTASK,
        'title' => '08:30 AM – 12:30 PM Subtask',
        'assigned_user_id' => $user->id,
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'weight' => 0.5,
        'created_by' => $pm->id,
    ]);

    // Another task assigned to someone else
    WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => null,
        'wbs_code' => '2',
        'item_type' => ItemType::TASK,
        'title' => 'Unrelated Task',
        'assigned_user_id' => $otherUser->id,
        'status' => WbsStatus::NOT_STARTED,
        'priority' => Priority::MEDIUM,
        'progress' => 0,
        'weight' => 1.0,
        'created_by' => $pm->id,
    ]);

    $this->actingAs($user);

    $component = Livewire::test(WbsTree::class, ['project' => $project])
        ->set('assigneeFilter', 'mine');

    $wbsItems = $component->viewData('wbsItems');
    $statusStats = $component->viewData('statusStats');

    // The paginated top-level collection should ONLY contain the parent item ($parent->id),
    // not the child item ($child->id) at top-level.
    expect($wbsItems->total())->toBe(1);
    expect($wbsItems->first()->id)->toBe($parent->id);

    // statusStats['total'] should reflect user's assigned tasks count (parent + child = 2), not total project items (3).
    expect($statusStats['total'])->toBe(2);
});
