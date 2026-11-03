<?php

use App\Enums\ProjectStatus;
use App\Livewire\MyLeadProjects;
use App\Livewire\MyTasks;
use App\Livewire\WbsTree;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

beforeEach(function () {
    Mail::fake();
    Notification::fake();
    $this->travelTo(now()->setDate(2026, 11, 3)->setTime(12, 0));
    $this->manager = User::factory()->create();
    $this->project = Project::create([
        'code' => 'LIFECYCLE-001',
        'name' => 'Project lifecycle',
        'subsidiary_id' => Subsidiary::create(['code' => 'LFC', 'name' => 'Lifecycle'])->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'pm_accepted' => false,
        'status' => 'in_progress',
        'start_date' => now()->subWeek(),
        'deadline' => now()->addMonth(),
    ]);
});

test('project status requires acceptance and a started deliverable with or without eager loading', function (bool $accepted, string $taskStatus, string $itemType, ProjectStatus $expected, bool $loaded) {
    $this->project->update(['pm_accepted' => $accepted]);
    WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Scheduled work',
        'item_type' => $itemType,
        'status' => $taskStatus,
        'start_date' => now()->subDays(2),
        'progress' => 0,
    ]);
    $project = $this->project->fresh();
    if ($loaded) {
        $project->load('wbsItems');
    }

    expect($project->syncAutoStatus())->toBe($expected)
        ->and($project->fresh()->status)->toBe($expected);
})->with([
    'pending with active task' => [false, 'in_progress', 'task', ProjectStatus::PLANNING],
    'pending with completed task' => [false, 'completed', 'task', ProjectStatus::PLANNING],
    'accepted with backlog' => [true, 'backlog', 'task', ProjectStatus::PLANNING],
    'accepted with unstarted task' => [true, 'not_started', 'task', ProjectStatus::PLANNING],
    'accepted with active task' => [true, 'in_progress', 'task', ProjectStatus::IN_PROGRESS],
    'accepted with active subtask' => [true, 'in_progress', 'subtask', ProjectStatus::IN_PROGRESS],
    'accepted with active phase only' => [true, 'in_progress', 'phase', ProjectStatus::PLANNING],
    'accepted with active work package only' => [true, 'in_progress', 'work_package', ProjectStatus::PLANNING],
])->with([false, true]);

test('accepting alone keeps the project planning and task changes update the project immediately', function () {
    $task = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'First task',
        'item_type' => 'task',
        'status' => 'not_started',
    ]);

    expect($this->project->acceptByPm($this->manager))->toBeTrue()
        ->and($this->project->fresh()->status)->toBe(ProjectStatus::PLANNING);

    $task->update(['status' => 'in_progress']);
    expect($this->project->fresh()->status)->toBe(ProjectStatus::IN_PROGRESS);

    $task->update(['status' => 'not_started']);
    expect($this->project->fresh()->status)->toBe(ProjectStatus::PLANNING);
});

test('pending and unstarted projects stay planning even after the deadline passes', function (bool $accepted) {
    $this->project->update(['pm_accepted' => $accepted, 'deadline' => now()->subDay()]);
    expect($this->project->syncAutoStatus())->toBe(ProjectStatus::PLANNING);
})->with([false, true]);

test('started projects retain delay risk and completion statuses', function () {
    $this->project->update(['pm_accepted' => true]);
    $task = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Active deliverable',
        'item_type' => 'task',
        'status' => 'in_progress',
    ]);
    $risk = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Blocked deliverable',
        'item_type' => 'task',
        'status' => 'blocked',
    ]);
    expect($this->project->fresh()->status)->toBe(ProjectStatus::AT_RISK);

    $this->project->update(['deadline' => now()->subDay()]);
    expect($this->project->syncAutoStatus())->toBe(ProjectStatus::DELAYED);

    $task->update(['status' => 'completed', 'progress' => 100]);
    $risk->update(['status' => 'completed', 'progress' => 100]);
    expect($this->project->fresh()->status)->toBe(ProjectStatus::COMPLETED);
});

test('my projects counts and filters exclude pending assignments from in progress', function () {
    WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Previously started task',
        'item_type' => 'task',
        'status' => 'in_progress',
    ]);
    $component = Livewire::actingAs($this->manager)->test(MyLeadProjects::class)
        ->assertViewHas('activeCount', 0)
        ->assertViewHas('projects', fn ($projects) => $projects->first()->status === ProjectStatus::PLANNING)
        ->set('statusFilter', 'in_progress')
        ->assertViewHas('projects', fn ($projects) => $projects->isEmpty())
        ->set('statusFilter', 'planning')
        ->assertSee('Project lifecycle');

    $component->call('acceptProject', $this->project->id)
        ->assertViewHas('activeCount', 1)
        ->assertViewHas('projects', fn ($projects) => $projects->isEmpty())
        ->set('statusFilter', 'in_progress')
        ->assertSee('Project lifecycle');
});

test('visiting task screens preserves explicit task statuses regardless of schedule', function () {
    $this->project->update(['pm_accepted' => true]);
    $due = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Due but not started',
        'item_type' => 'task',
        'assigned_user_id' => $this->manager->id,
        'status' => 'not_started',
        'start_date' => now()->subDay(),
        'end_date' => now()->addWeek(),
    ]);
    $future = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Explicitly started early',
        'item_type' => 'task',
        'assigned_user_id' => $this->manager->id,
        'status' => 'in_progress',
        'start_date' => now()->addDay(),
        'end_date' => now()->addWeek(),
    ]);

    Livewire::actingAs($this->manager)->test(MyTasks::class)->assertOk();
    Livewire::actingAs($this->manager)->test(WbsTree::class, ['project' => $this->project->fresh()])->assertOk();

    expect($due->fresh()->status->value)->toBe('not_started')
        ->and($future->fresh()->status->value)->toBe('in_progress');
});

test('progress values alone cannot start a project without a started task', function () {
    $this->project->update(['pm_accepted' => true]);
    WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Task still marked not started',
        'item_type' => 'task',
        'status' => 'not_started',
        'progress' => 20,
    ]);
    $project = $this->project->fresh();
    expect($project->overall_progress)->toBe(20)
        ->and($project->syncAutoStatus())->toBe(ProjectStatus::PLANNING);
});
