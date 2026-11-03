<?php

use App\Enums\WbsStatus;
use App\Livewire\MyTasks;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Notifications\TaskCompletedNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Notification::fake();
    $this->manager = User::factory()->create();
    $this->assignee = User::factory()->create();
    $this->project = Project::create([
        'code' => 'TASK-DETAIL-001',
        'name' => 'Task detail project',
        'subsidiary_id' => Subsidiary::create(['code' => 'TDM', 'name' => 'Task Detail Entity'])->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'pm_accepted' => true,
        'status' => 'planning',
    ]);
    $this->task = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Interview project managers and end users',
        'description' => 'Confirm requirements with the project team.',
        'wbs_code' => '1.2.1',
        'item_type' => 'task',
        'assigned_user_id' => $this->assignee->id,
        'status' => 'in_progress',
        'priority' => 'high',
        'progress' => 25,
        'estimated_hours' => 8,
        'start_date' => today(),
        'end_date' => today()->addDay(),
        'weight' => 1,
    ]);
});

it('shows the clean task popup without estimated effort and completes the assigned task once', function () {
    $component = Livewire::actingAs($this->assignee)->test(MyTasks::class)
        ->call('openTaskDetail', $this->task->id)
        ->assertSet('showTaskDetailModal', true)
        ->assertSee('aria-labelledby="my-task-detail-title"', false)
        ->assertSee('Confirm requirements with the project team.')
        ->assertDontSee('Estimated Effort')
        ->assertDontSee('Assignment &amp; Effort', false)
        ->assertSee('wire:click="completeDetailTask"', false)
        ->call('completeDetailTask')
        ->assertSet('showTaskDetailModal', true)
        ->assertSet('detailStatus', 'completed')
        ->assertSet('detailProgress', 100)
        ->assertSee('Task completed')
        ->assertDontSee('wire:click="completeDetailTask"', false)
        ->assertDispatched('wbsUpdated');

    expect($this->task->fresh()->status)->toBe(WbsStatus::COMPLETED)
        ->and($this->task->fresh()->progress)->toBe(100)
        ->and($this->project->fresh()->overall_progress)->toBe(100);
    Notification::assertSentToTimes($this->manager, TaskCompletedNotification::class, 1);

    $component->call('completeDetailTask');
    Notification::assertSentToTimes($this->manager, TaskCompletedNotification::class, 1);
});

it('allows the project manager to complete a task and rolls progress up to its parent', function () {
    $parent = WbsItem::create([
        'project_id' => $this->project->id,
        'title' => 'Requirements phase',
        'item_type' => 'phase',
        'status' => 'in_progress',
        'progress' => 0,
        'weight' => 1,
    ]);
    $this->task->update(['parent_id' => $parent->id]);

    Livewire::actingAs($this->manager)->test(MyTasks::class)
        ->call('openTaskDetail', $this->task->id)
        ->assertSee('wire:click="completeDetailTask"', false)
        ->call('completeDetailTask')
        ->assertSee('Task completed');

    expect($this->task->fresh()->progress)->toBe(100)
        ->and($parent->fresh()->progress)->toBe(100)
        ->and($this->project->fresh()->overall_progress)->toBe(100);
    Notification::assertNotSentTo($this->manager, TaskCompletedNotification::class);
});

it('denies completion when the popup is targeted at another users task', function () {
    $otherUser = User::factory()->create();
    Livewire::actingAs($otherUser)->test(MyTasks::class)
        ->set('detailTaskId', $this->task->id)
        ->set('showTaskDetailModal', true)
        ->assertDontSee('wire:click="completeDetailTask"', false)
        ->call('completeDetailTask')
        ->assertDispatched('toast', message: 'You do not have permission to complete this task.', type: 'error');

    expect($this->task->fresh()->status)->toBe(WbsStatus::IN_PROGRESS)
        ->and($this->task->fresh()->progress)->toBe(25);
    Notification::assertNotSentTo($this->manager, TaskCompletedNotification::class);
});

it('does not offer or repeat completion for terminal tasks', function (string $status) {
    $this->task->update(['status' => $status, 'progress' => $status === 'completed' ? 100 : 25]);
    Livewire::actingAs($this->assignee)->test(MyTasks::class)
        ->call('openTaskDetail', $this->task->id)
        ->assertDontSee('wire:click="completeDetailTask"', false)
        ->call('completeDetailTask');

    expect($this->task->fresh()->status->value)->toBe($status);
    Notification::assertNotSentTo($this->manager, TaskCompletedNotification::class);
})->with(['completed', 'cancelled']);

it('does not complete a task after its details popup has been closed', function () {
    Livewire::actingAs($this->assignee)->test(MyTasks::class)
        ->call('openTaskDetail', $this->task->id)
        ->set('showTaskDetailModal', false)
        ->call('completeDetailTask');

    expect($this->task->fresh()->status)->toBe(WbsStatus::IN_PROGRESS);
    Notification::assertNotSentTo($this->manager, TaskCompletedNotification::class);
});

it('keeps completion unavailable to a read only viewer even when assigned', function () {
    Role::findOrCreate('admin_viewer', 'web');
    $this->assignee->assignRole('admin_viewer');
    Livewire::actingAs($this->assignee)->test(MyTasks::class)
        ->call('openTaskDetail', $this->task->id)
        ->assertDontSee('wire:click="completeDetailTask"', false)
        ->call('completeDetailTask');

    expect($this->task->fresh()->status)->toBe(WbsStatus::IN_PROGRESS);
    Notification::assertNotSentTo($this->manager, TaskCompletedNotification::class);
});
