<?php

use App\Livewire\AllTasksViewer;
use App\Livewire\MyTasks;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use Carbon\Carbon;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->travelTo(Carbon::parse('2026-11-03 12:00:00'));
    $this->manager = User::factory()->create();
    $this->assignee = User::factory()->create();
    $this->admin = User::factory()->create();
    Role::findOrCreate('super_admin', 'web');
    $this->admin->assignRole('super_admin');
    $this->project = Project::create([
        'code' => 'DEADLINE-001',
        'name' => 'Deadline ordering project',
        'subsidiary_id' => Subsidiary::create(['code' => 'DDL', 'name' => 'Deadline Test'])->id,
        'project_manager_id' => $this->manager->id,
        'created_by' => $this->manager->id,
        'status' => 'in_progress',
    ]);

    // Intentionally mix creation order, priorities, start dates and terminal dates.
    $this->orderedTasks = collect([
        ['Today low', '2026-11-03', 'low'],
        ['Completed future', '2026-12-01', 'high', 'completed'],
        ['Upcoming later already started', '2026-11-10', 'high', 'in_progress', '2026-11-01'],
        ['Overdue recent critical', '2026-11-02', 'critical'],
        ['Undated starting today', null, 'high', 'not_started', '2026-11-03'],
        ['Today high beta', '2026-11-03', 'high'],
        ['Overdue oldest low', '2026-11-01', 'low'],
        ['Today critical', '2026-11-03', 'critical'],
        ['Upcoming nearest', '2026-11-04', 'low'],
        ['Today high alpha', '2026-11-03', 'high'],
        ['Completed today', '2026-11-03', 'critical', 'completed'],
        ['Undated low', null, 'low'],
        ['Today medium', '2026-11-03', 'medium'],
        ['Cancelled today', '2026-11-03', 'high', 'cancelled'],
        ['Completed undated', null, 'critical', 'completed'],
        ['Today high alpha', '2026-11-03', 'high'],
    ])->map(fn ($row) => WbsItem::create([
        'project_id' => $this->project->id,
        'assigned_user_id' => $this->assignee->id,
        'title' => $row[0],
        'end_date' => $row[1],
        'priority' => $row[2],
        'status' => $row[3] ?? 'not_started',
        'start_date' => $row[4] ?? null,
        'item_type' => 'task',
    ]));

    $this->expectedOrder = [6, 3, 7, 9, 15, 5, 12, 0, 8, 2, 4, 11, 1, 10, 13, 14];
});

it('orders task pages by overdue age, deadline, priority and title before pagination', function (string $page) {
    $isDirectory = $page === 'directory';
    $user = match ($page) {
        'directory' => $this->admin,
        'managed' => $this->manager,
        default => $this->assignee,
    };
    $component = Livewire::actingAs($user)->test($isDirectory ? AllTasksViewer::class : MyTasks::class);
    if ($page === 'managed') {
        $component->call('setTaskScope', 'pm_projects');
    }

    $expectedIds = collect($this->expectedOrder)
        // The directory already excludes cancelled tasks.
        ->reject(fn ($index) => $isDirectory && $index === 13)
        ->map(fn ($index) => $this->orderedTasks[$index]->id)
        ->values();
    $viewKey = $isDirectory ? 'tasks' : 'paginatedTasks';
    $component->set('perPage', 50)
        ->assertViewHas($viewKey, fn ($tasks) => $tasks->pluck('id')->all() === $expectedIds->all());

    $component->set('perPage', 5)->call('setPage', 1)
        ->assertViewHas($viewKey, fn ($tasks) => $tasks->pluck('id')->all() === $expectedIds->take(5)->all())
        ->call('setPage', 2)
        ->assertViewHas($viewKey, fn ($tasks) => $tasks->pluck('id')->all() === $expectedIds->slice(5, 5)->values()->all());

    if ($isDirectory) {
        $component->set('projectFilter', (string) $this->project->id)
            ->call('setOverdueFilter')
            ->assertViewHas('tasks', fn ($tasks) => $tasks->pluck('id')->all() === $expectedIds->take(2)->all());
    } else {
        $component->set('dueDateFilter', 'overdue')
            ->assertViewHas('paginatedTasks', fn ($tasks) => $tasks->pluck('id')->all() === $expectedIds->take(2)->all())
            ->assertSee('overdue first')
            ->assertDontSee('today top');
    }
})->with(['directory', 'assigned', 'managed']);

it('preserves explicit title sorting on both task pages', function (string $page) {
    $isDirectory = $page === 'directory';
    $component = Livewire::actingAs($isDirectory ? $this->admin : $this->assignee)
        ->test($isDirectory ? AllTasksViewer::class : MyTasks::class)
        ->set('perPage', 50);

    if ($isDirectory) {
        $component->set('sortBy', 'title');
    } else {
        $component->call('sortBy', 'title');
    }

    $component->assertViewHas($isDirectory ? 'tasks' : 'paginatedTasks', function ($tasks) {
        $titles = $tasks->pluck('title')->all();
        $sorted = $titles;
        sort($sorted);

        return $titles === $sorted;
    });
})->with(['directory', 'assigned']);
