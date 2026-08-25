<?php

use App\Enums\ApprovalStatus;
use App\Enums\ApprovalType;
use App\Enums\ItemType;
use App\Enums\ProjectStatus;
use App\Models\ApprovalRequest;
use App\Models\Project;
use App\Models\Subsidiary;
use App\Models\User;
use App\Models\WbsItem;
use App\Services\ApprovalService;
use App\Services\DependencyValidationService;
use App\Services\ProgressCalculationService;
use App\Services\WbsNumberingService;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Seed only the system-wide super_admin role
    Role::findOrCreate('super_admin', 'web');
});

test('super admin can view subsidiaries', function () {
    $admin = User::factory()->create(['is_active' => true]);
    $admin->assignRole('super_admin');

    $response = $this->actingAs($admin)->get('/subsidiaries');
    $response->assertOk();
});

test('project manager cannot access subsidiaries route', function () {
    // PMs are regular users under project-scoped roles
    $pm = User::factory()->create(['is_active' => true]);

    $response = $this->actingAs($pm)->get('/subsidiaries');
    $response->assertForbidden();
});

test('team member cannot create projects', function () {
    // Members are regular users under project-scoped roles
    $member = User::factory()->create(['is_active' => true]);

    $response = $this->actingAs($member)->get('/projects/create');
    $response->assertRedirect(route('projects.index')); // Regular team members without project creation permissions are redirected
});

test('unauthorized project access is blocked', function () {
    $pm1 = User::factory()->create(['is_active' => true]);
    $pm2 = User::factory()->create(['is_active' => true]);

    $subsidiary = Subsidiary::create(['code' => 'SUB1', 'name' => 'Sub 1']);

    $project = Project::create([
        'code' => 'PRJ-001',
        'name' => 'Secret Project PM2',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm2->id,
        'created_by' => $pm2->id,
        'status' => ProjectStatus::PLANNING,
    ]);

    // PM1 trying to access PM2 project should be forbidden
    $response = $this->actingAs($pm1)->get("/projects/{$project->id}");
    $response->assertForbidden();
});

test('wbs automatic numbering generates correct hierarchical codes', function () {
    $admin = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'SUB2', 'name' => 'Sub 2']);

    $project = Project::create([
        'code' => 'PRJ-WBS',
        'name' => 'WBS Test Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $admin->id,
        'created_by' => $admin->id,
    ]);

    $phase = WbsItem::create([
        'project_id' => $project->id,
        'item_type' => ItemType::PHASE,
        'title' => 'Phase 1',
        'sort_order' => 1,
    ]);

    $wp = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $phase->id,
        'item_type' => ItemType::WORK_PACKAGE,
        'title' => 'Work Package 1.1',
        'sort_order' => 1,
    ]);

    $task = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $wp->id,
        'item_type' => ItemType::TASK,
        'title' => 'Task 1.1.1',
        'sort_order' => 1,
    ]);

    (new WbsNumberingService())->recalculateProjectWbsCodes($project->id);

    expect($phase->fresh()->wbs_code)->toBe('1');
    expect($wp->fresh()->wbs_code)->toBe('1.1');
    expect($task->fresh()->wbs_code)->toBe('1.1.1');
});

test('parent wbs progress auto calculates from leaf nodes', function () {
    $admin = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'SUB3', 'name' => 'Sub 3']);

    $project = Project::create([
        'code' => 'PRJ-PROG',
        'name' => 'Progress Test Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $admin->id,
        'created_by' => $admin->id,
    ]);

    $parent = WbsItem::create([
        'project_id' => $project->id,
        'item_type' => ItemType::PHASE,
        'title' => 'Parent Phase',
        'progress' => 0,
    ]);

    $child1 = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $parent->id,
        'item_type' => ItemType::TASK,
        'title' => 'Child Task 1',
        'progress' => 50,
        'weight' => 1.0,
    ]);

    $child2 = WbsItem::create([
        'project_id' => $project->id,
        'parent_id' => $parent->id,
        'item_type' => ItemType::TASK,
        'title' => 'Child Task 2',
        'progress' => 100,
        'weight' => 1.0,
    ]);

    (new ProgressCalculationService())->updateItemProgress($child1, 'equal');

    expect($parent->fresh()->progress)->toBe(75);
    expect($project->fresh()->overall_progress)->toBe(75);
});

test('circular task dependency is prevented', function () {
    $admin = User::factory()->create();
    $subsidiary = Subsidiary::create(['code' => 'SUB4', 'name' => 'Sub 4']);

    $project = Project::create([
        'code' => 'PRJ-DEP',
        'name' => 'Dep Test Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $admin->id,
        'created_by' => $admin->id,
    ]);

    $t1 = WbsItem::create(['project_id' => $project->id, 'title' => 'Task 1']);
    $t2 = WbsItem::create(['project_id' => $project->id, 'title' => 'Task 2']);

    $service = new DependencyValidationService();

    // t1 -> t2 valid
    $service->validateDependency($t1->id, $t2->id);

    // Self dependency throws exception
    expect(fn() => $service->validateDependency($t1->id, $t1->id))
        ->toThrow(InvalidArgumentException::class);
});

test('super admin approval updates official project deadline', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    $pm = User::factory()->create();

    $subsidiary = Subsidiary::create(['code' => 'SUB5', 'name' => 'Sub 5']);

    $project = Project::create([
        'code' => 'PRJ-APP',
        'name' => 'Approval Test Project',
        'subsidiary_id' => $subsidiary->id,
        'project_manager_id' => $pm->id,
        'created_by' => $admin->id,
        'deadline' => '2026-10-01',
    ]);

    $request = ApprovalRequest::create([
        'project_id' => $project->id,
        'request_type' => ApprovalType::DEADLINE_EXTENSION,
        'requested_by' => $pm->id,
        'requested_value' => ['new_deadline' => '2026-12-01'],
        'reason' => 'Need additional time due to vendor delays.',
        'status' => ApprovalStatus::PENDING,
    ]);

    // Official deadline should remain unchanged before approval
    expect($project->fresh()->deadline->toDateString())->toBe('2026-10-01');

    (new ApprovalService())->approve($request, $admin, 'Approved by executive board.');

    // Official deadline MUST update after approval
    expect($request->fresh()->status->value)->toBe('approved');
    expect($project->fresh()->deadline->toDateString())->toBe('2026-12-01');
});
