<?php

use App\Livewire\DailyStatusUpdates;
use App\Models\Comment;
use App\Models\Project;
use App\Models\ProjectStatusUpdate;
use App\Models\Subsidiary;
use App\Models\User;
use App\Notifications\DailyUpdateNotification;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->admin = User::factory()->create();
    Role::findOrCreate('super_admin', 'web');
    $this->admin->assignRole('super_admin');
    $this->manager = User::factory()->create();
    $this->reporter = User::factory()->create();
    $this->outsider = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'READ', 'name' => 'Read Tracking']);
    $this->project = Project::create([
        'code' => 'READ-001',
        'name' => 'Read tracking project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->admin->id,
        'status' => 'in_progress',
        'pm_accepted' => true,
    ]);
    $this->project->members()->attach($this->reporter->id, ['role' => 'member']);
    $this->older = ProjectStatusUpdate::forceCreate([
        'id' => 1,
        'project_id' => $this->project->id,
        'created_by' => $this->reporter->id,
        'title' => 'Earlier progress log',
        'summary' => 'Earlier completed work',
        'created_at' => now()->subDay(),
    ]);
    $this->latest = ProjectStatusUpdate::forceCreate([
        'id' => 10,
        'project_id' => $this->project->id,
        'created_by' => $this->reporter->id,
        'title' => 'Latest progress log',
        'summary' => 'Latest completed work',
    ]);
    $this->teamComment = $this->older->comments()->create([
        'user_id' => $this->manager->id,
        'content' => Comment::encodeContentWithVisibility('Team feedback', 'team'),
    ]);
    $this->privateComment = $this->older->comments()->create([
        'user_id' => $this->admin->id,
        'content' => Comment::encodeContentWithVisibility('Private management feedback', 'pmo_pm'),
    ]);
    $this->notice = function (User $recipient, ProjectStatusUpdate $update, string $action = 'daily_update', ?Comment $comment = null, ?string $url = null) {
        $notification = new DailyUpdateNotification(
            updateTitle: $update->title,
            reporterName: $this->reporter->name,
            projectName: $this->project->name,
            url: $url ?? route('daily-updates.index', ['project' => $update->project_id, 'update' => $update->id]),
            actionType: $action,
            commentId: $comment?->id,
        );

        return $recipient->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => DailyUpdateNotification::class,
            'data' => $notification->toArray($recipient),
        ]);
    };
});

it('keeps notifications unread on the directory and wires the sidebar to live count updates', function () {
    $log = ($this->notice)($this->manager, $this->older);
    $comment = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);

    $this->actingAs($this->manager)->get(route('daily-updates.index'))
        ->assertOk()
        ->assertSee('x-data="{ unreadCount: 2 }"', false)
        ->assertSee('@daily-updates-read.window=', false)
        ->assertSee('unreadCount > 0', false);

    expect($log->fresh()->read_at)->toBeNull()
        ->and($comment->fresh()->read_at)->toBeNull();
});

it('reads only the opened log and preserves comments, other logs and other users notifications', function () {
    $log = ($this->notice)($this->manager, $this->older);
    $comment = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);
    $otherLog = ($this->notice)($this->manager, $this->latest, 'task_log');
    $otherUserLog = ($this->notice)($this->admin, $this->older);
    $unrelated = $this->manager->notifications()->create([
        'id' => (string) Str::uuid(), 'type' => 'OtherNotification', 'data' => ['title' => 'Unrelated alert'],
    ]);

    Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id)
        ->assertSet('selectedHistoryUpdateId', $this->older->id)
        ->assertSee('x-data="{ unreadLogs: 2 }"', false)
        ->assertSee("'has-unread-logs': unreadLogs > 0", false)
        ->assertDispatched('daily-updates-read', count: 2, projectId: $this->project->id, projectUnreadCount: 2);

    expect($log->fresh()->read_at)->not->toBeNull()
        ->and($comment->fresh()->read_at)->toBeNull()
        ->and($otherLog->fresh()->read_at)->toBeNull()
        ->and($otherUserLog->fresh()->read_at)->toBeNull()
        ->and($unrelated->fresh()->read_at)->toBeNull();
});

it('reads the latest visible log when opening a projects logs without choosing a log', function () {
    $older = ($this->notice)($this->manager, $this->older);
    $latest = ($this->notice)($this->manager, $this->latest);

    Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id)
        ->assertSet('selectedHistoryUpdateId', $this->latest->id)
        ->assertSee('x-data="{ unreadLogs: 1 }"', false)
        ->assertDispatched('daily-updates-read', count: 1, projectId: $this->project->id, projectUnreadCount: 1);

    expect($latest->fresh()->read_at)->not->toBeNull()
        ->and($older->fresh()->read_at)->toBeNull();
});

it('reads comment notifications only after opening the selected logs discussion', function () {
    $log = ($this->notice)($this->manager, $this->older);
    $comment = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);
    $otherLog = ($this->notice)($this->manager, $this->latest);
    $component = Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id);
    expect($comment->fresh()->read_at)->toBeNull();

    $component->set('historyTab', 'discussion')
        ->assertSee('Team feedback')
        ->assertDispatched('daily-updates-read', count: 1, projectId: $this->project->id, projectUnreadCount: 1);
    expect($comment->fresh()->read_at)->not->toBeNull()
        ->and($otherLog->fresh()->read_at)->toBeNull();

    $component->call('selectHistoryUpdate', $this->latest->id)
        ->assertDispatched('daily-updates-read', count: 0, projectId: $this->project->id, projectUnreadCount: 0);
    expect($log->fresh()->read_at)->not->toBeNull()
        ->and($otherLog->fresh()->read_at)->not->toBeNull();
});

it('handles legacy comment notifications and encoded URLs without matching partial IDs', function () {
    $url = route('daily-updates.index').'?project%3D'.$this->project->id.'%26update%3D'.$this->older->id;
    $log = ($this->notice)($this->manager, $this->older, 'daily_update', null, $url);
    $legacyComment = ($this->notice)($this->manager, $this->older, 'comment');
    $differentId = ($this->notice)($this->manager, $this->latest);
    $component = Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id);
    expect($log->fresh()->read_at)->not->toBeNull()
        ->and($differentId->fresh()->read_at)->toBeNull()
        ->and($legacyComment->fresh()->read_at)->toBeNull();
    $component->set('historyTab', 'discussion');
    expect($legacyComment->fresh()->read_at)->not->toBeNull();
});

it('does not read private comments that the current user cannot see', function () {
    $team = ($this->notice)($this->reporter, $this->older, 'comment', $this->teamComment);
    $private = ($this->notice)($this->reporter, $this->older, 'comment', $this->privateComment);

    Livewire::actingAs($this->reporter)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id, 'discussion')
        ->assertSee('Team feedback')
        ->assertDontSee('Private management feedback')
        ->assertDispatched('daily-updates-read', count: 1, projectId: $this->project->id, projectUnreadCount: 1);

    expect($team->fresh()->read_at)->not->toBeNull()
        ->and($private->fresh()->read_at)->toBeNull();
});

it('leaves inaccessible project notifications unread', function () {
    $notification = ($this->notice)($this->outsider, $this->older);
    Livewire::actingAs($this->outsider)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id)
        ->assertForbidden();
    expect($notification->fresh()->read_at)->toBeNull();
});

it('does not clear notifications when switching tabs after closing the modal', function () {
    $comment = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);
    Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id)
        ->call('closeHistoryModal')
        ->set('historyTab', 'discussion');
    expect($comment->fresh()->read_at)->toBeNull();
});

it('keeps new feedback unread until the discussion is viewed again', function () {
    $first = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);
    $component = Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id, 'discussion');
    expect($first->fresh()->read_at)->not->toBeNull();
    $newComment = $this->older->comments()->create([
        'user_id' => $this->reporter->id, 'content' => '[VIS:team] New reply after reading',
    ]);
    $newNotice = ($this->notice)($this->manager, $this->older, 'comment', $newComment);
    $component->call('$refresh');
    expect($newNotice->fresh()->read_at)->toBeNull();
    $component->set('historyTab', 'report')->set('historyTab', 'discussion')
        ->assertDispatched('daily-updates-read', count: 0, projectId: $this->project->id, projectUnreadCount: 0);
    expect($newNotice->fresh()->read_at)->not->toBeNull();
});

it('opens notification discussion links and reads only their comment notifications', function () {
    $comment = ($this->notice)($this->manager, $this->older, 'comment', $this->teamComment);
    $log = ($this->notice)($this->manager, $this->older);
    Livewire::withQueryParams(['project' => $this->project->id, 'update' => $this->older->id, 'tab' => 'discussion'])
        ->actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->assertSet('historyTab', 'discussion')
        ->assertSet('selectedHistoryUpdateId', $this->older->id);
    expect($comment->fresh()->read_at)->not->toBeNull()
        ->and($log->fresh()->read_at)->toBeNull();
});

it('sends new feedback notifications to the discussion with the exact comment ID', function () {
    Livewire::actingAs($this->manager)->test(DailyStatusUpdates::class)
        ->call('openHistoryModal', $this->project->id, $this->older->id)
        ->set('newCommentContent.'.$this->older->id, 'New team feedback')
        ->call('addComment', $this->older->id, 'team');
    $comment = $this->older->comments()->where('content', 'like', '%New team feedback')->firstOrFail();
    $notification = $this->reporter->notifications()->where('type', DailyUpdateNotification::class)->firstOrFail();
    parse_str(parse_url($notification->data['url'], PHP_URL_QUERY), $parameters);
    expect($notification->data['comment_id'])->toBe($comment->id)
        ->and($parameters['tab'])->toBe('discussion')
        ->and($parameters['update'])->toBe((string) $this->older->id);
});
