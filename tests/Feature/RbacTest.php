<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Services\RbacService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function createProject(array $attributes = []): Project
    {
        $sub = \App\Models\Subsidiary::first() ?? \App\Models\Subsidiary::create([
            'name' => 'George Steuart Solutions',
            'code' => 'GSS',
            'is_active' => true,
        ]);

        return Project::create(array_merge([
            'code' => 'PRJ-' . rand(1000, 9999),
            'name' => 'Test Project',
            'description' => 'A test project',
            'subsidiary_id' => $sub->id,
            'status' => 'planning',
            'health' => 'on_track',
            'priority' => 'medium',
            'pm_accepted' => true,
        ], $attributes));
    }

    public function test_pmo_admin_has_full_access()
    {
        $admin = User::create([
            'name' => 'Admin PMO',
            'email' => 'admin@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $admin->assignRole('super_admin');

        $project = $this->createProject();

        $this->assertTrue($admin->hasProjectPermission('task.create', $project));
        $this->assertTrue($admin->hasProjectPermission('task.delete', $project));
        $this->assertTrue($admin->hasProjectPermission('project.edit', $project));
        $this->assertTrue($admin->hasProjectPermission('budget.approve', $project));
    }

    public function test_project_manager_permissions()
    {
        $pm = User::create([
            'name' => 'Project Leader',
            'email' => 'pm@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject(['project_manager_id' => $pm->id]);

        $this->assertTrue($pm->hasProjectPermission('task.create', $project));
        $this->assertTrue($pm->hasProjectPermission('task.delete', $project));
        $this->assertTrue($pm->hasProjectPermission('team.add', $project));
        $this->assertTrue($pm->hasProjectPermission('risk.create', $project));
    }

    public function test_core_team_member_default_restrictions()
    {
        $member = User::create([
            'name' => 'Team Member',
            'email' => 'member@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject();
        $project->members()->attach($member->id, ['role' => 'member']);

        // Default member cannot create or delete tasks
        $this->assertFalse($member->hasProjectPermission('task.create', $project));
        $this->assertFalse($member->hasProjectPermission('task.delete', $project));
        $this->assertFalse($member->hasProjectPermission('team.add', $project));

        // Default member can view and update assigned
        $this->assertTrue($member->hasProjectPermission('task.view_assigned', $project));
        $this->assertTrue($member->hasProjectPermission('task.edit_assigned', $project));
        $this->assertTrue($member->hasProjectPermission('task.comment', $project));
    }

    public function test_dynamic_permission_toggle()
    {
        $member = User::create([
            'name' => 'Toggle Member',
            'email' => 'toggle@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject();
        $project->members()->attach($member->id, ['role' => 'member']);

        $role = Role::findByName('member', 'web');

        // Give task.create to member
        $role->givePermissionTo('task.create');
        RbacService::clearCache();

        $this->assertTrue($member->hasProjectPermission('task.create', $project));

        // Revoke task.create from member
        $role->revokePermissionTo('task.create');
        RbacService::clearCache();

        $this->assertFalse($member->hasProjectPermission('task.create', $project));
    }

    public function test_governance_roles_can_view_all_tasks_like_pm()
    {
        $pm = User::create(['name' => 'PM', 'email' => 'pm_view@nexuspm.local', 'password' => bcrypt('secret')]);
        $sponsor = User::create(['name' => 'Sponsor', 'email' => 'sponsor@nexuspm.local', 'password' => bcrypt('secret')]);
        $owner = User::create(['name' => 'Owner', 'email' => 'owner@nexuspm.local', 'password' => bcrypt('secret')]);
        $committee = User::create(['name' => 'Committee', 'email' => 'committee@nexuspm.local', 'password' => bcrypt('secret')]);
        $member = User::create(['name' => 'Member', 'email' => 'member_view@nexuspm.local', 'password' => bcrypt('secret')]);

        $project = $this->createProject(['project_manager_id' => $pm->id]);
        $project->members()->attach($sponsor->id, ['role' => 'sponsor']);
        $project->members()->attach($owner->id, ['role' => 'owner']);
        $project->members()->attach($committee->id, ['role' => 'steering_committee']);
        $project->members()->attach($member->id, ['role' => 'member']);

        // PM, Sponsor, Owner, Steering Committee ALL have task.view_all = true
        $this->assertTrue($project->userCan($pm, 'task.view_all'));
        $this->assertTrue($project->userCan($sponsor, 'task.view_all'));
        $this->assertTrue($project->userCan($owner, 'task.view_all'));
        $this->assertTrue($project->userCan($committee, 'task.view_all'));

        // Core Team Member ONLY has task.view_assigned = true, task.view_all = false
        $this->assertFalse($project->userCan($member, 'task.view_all'));
        $this->assertTrue($project->userCan($member, 'task.view_assigned'));
    }

    public function test_project_manager_dynamic_permission_toggle()
    {
        $pm = User::create([
            'name' => 'Dynamic PM',
            'email' => 'dynamic_pm@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject(['project_manager_id' => $pm->id]);

        $role = Role::firstOrCreate(['name' => 'lead', 'guard_name' => 'web']);
        $role->syncPermissions(['project_details.view', 'task.view', 'task.create']);
        RbacService::clearCache();

        // Project details view is granted
        $this->assertTrue($pm->hasProjectPermission('project_details.view', $project));

        // Revoke project_details.view from lead
        $role->revokePermissionTo('project_details.view');
        RbacService::clearCache();

        // Project details view should now be false (unchecked)
        $this->assertFalse($pm->hasProjectPermission('project_details.view', $project));
    }

    public function test_project_manager_task_create_permission_toggle()
    {
        $pm = User::create([
            'name' => 'PM Task Test',
            'email' => 'pm_task_test@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject(['project_manager_id' => $pm->id]);

        $role = Role::firstOrCreate(['name' => 'lead', 'guard_name' => 'web']);
        $role->syncPermissions(['project_details.view', 'task.view']); // task.create is NOT granted
        RbacService::clearCache();

        // task.create is unchecked -> must return false
        $this->assertFalse($pm->hasProjectPermission('task.create', $project));

        // Grant task.create
        $role->givePermissionTo('task.create');
        RbacService::clearCache();

        // task.create is checked -> must return true
        $this->assertTrue($pm->hasProjectPermission('task.create', $project));
    }

    public function test_project_manager_task_assign_permission_toggle()
    {
        $pm = User::create([
            'name' => 'PM Assign Test',
            'email' => 'pm_assign_test@nexuspm.local',
            'password' => bcrypt('secret'),
        ]);
        $project = $this->createProject(['project_manager_id' => $pm->id]);

        $role = Role::firstOrCreate(['name' => 'lead', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'task.assign', 'guard_name' => 'web']);
        
        // Exclude task.assign
        $role->syncPermissions(['project_details.view', 'task.view', 'task.create']);
        RbacService::clearCache();

        // task.assign is unchecked -> must return false
        $this->assertFalse($project->userCan($pm, 'task.assign'));

        // Grant task.assign
        $role->givePermissionTo('task.assign');
        RbacService::clearCache();

        // task.assign is checked -> must return true
        $this->assertTrue($project->userCan($pm, 'task.assign'));
    }
}
