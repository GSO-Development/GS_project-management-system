<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. All 73 Granular Permissions by Module
        $permissions = [
            // 1. Project Permissions
            'project.view',
            'project.view_all',
            'project.view_assigned',
            'project.create',
            'project.edit',
            'project.delete',
            'project.archive',
            'project.change_status',
            'project.manage_scope',
            'project.manage_schedule',
            'project.manage_milestones',

            // 2. Scope / Project Details
            'project_details.view',
            'project_details.edit',
            'scope.view',
            'scope.edit',
            'scope.approve',
            'schedule.edit',

            // 3. WBS & Task Permissions
            'task.view',
            'task.view_all',
            'task.view_assigned',
            'task.create',
            'task.edit',
            'task.edit_assigned',
            'task.delete',
            'task.assign',
            'task.reassign',
            'task.change_status',
            'task.update_progress',
            'task.change_priority',
            'task.change_due_date',
            'task.create_subtask',
            'task.comment',
            'task.upload_attachment',
            'task.complete',

            // 4. Team Permissions
            'team.view',
            'team.add',
            'team.remove',
            'team.assign_role',
            'team.change_role',
            'team.view_member',

            // 5. Budget / Finance Permissions
            'budget.view',
            'budget.view_estimated',
            'budget.edit_estimated',
            'budget.view_actual',
            'budget.edit_actual',
            'budget.submit_change',
            'budget.approve',
            'budget.report',

            // 6. Risks & Blockers
            'risk.view',
            'risk.create',
            'risk.edit',
            'risk.delete',
            'risk.assign',
            'risk.escalate',
            'risk.resolve',
            'blocker.create',
            'blocker.edit',
            'blocker.resolve',

            // 7. Approval Permissions
            'approval.view',
            'approval.submit',
            'approval.approve',
            'approval.reject',
            'approval.return',
            'approval.final_approve',

            // 8. Report Permissions
            'report.view',
            'report.project',
            'report.financial',
            'report.performance',
            'report.export',

            // 9. Project Settings
            'project_settings.view',
            'project_settings.edit',
            'project_settings.configure',
            'project_settings.manage_roles',
        ];

        foreach ($permissions as $permName) {
            Permission::findOrCreate($permName, 'web');
        }

        // 2. Ensure the 5 Project Roles exist
        $roleSponsor = Role::findOrCreate('sponsor', 'web');
        $roleOwner = Role::findOrCreate('owner', 'web');
        $roleCommittee = Role::findOrCreate('steering_committee', 'web');
        $roleLead = Role::findOrCreate('lead', 'web');
        $roleMember = Role::findOrCreate('member', 'web');

        // Also ensure aliases exist / sync
        $rolePm = Role::findOrCreate('project_manager', 'web');
        $roleTeamMember = Role::findOrCreate('team_member', 'web');

        // 3. Default Permissions for Project Manager (lead)
        $leadPerms = [
            'project.view', 'project.view_assigned', 'project.edit', 'project.manage_scope', 'project.manage_schedule', 'project.manage_milestones',
            'project_details.view', 'project_details.edit', 'scope.view', 'scope.edit', 'schedule.edit',
            'task.view', 'task.view_all', 'task.view_assigned', 'task.create', 'task.edit', 'task.edit_assigned', 'task.delete', 'task.assign', 'task.reassign', 'task.change_status', 'task.update_progress', 'task.change_priority', 'task.change_due_date', 'task.create_subtask', 'task.comment', 'task.upload_attachment', 'task.complete',
            'team.view', 'team.add', 'team.remove', 'team.assign_role', 'team.change_role', 'team.view_member',
            'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.edit_actual', 'budget.submit_change', 'budget.report',
            'risk.view', 'risk.create', 'risk.edit', 'risk.assign', 'risk.escalate', 'risk.resolve', 'blocker.create', 'blocker.edit', 'blocker.resolve',
            'approval.view', 'approval.submit',
            'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
            'project_settings.view', 'project_settings.edit'
        ];
        $roleLead->syncPermissions($leadPerms);
        $rolePm->syncPermissions($leadPerms);

        // 4. Default Permissions for Core Project Team (member)
        $memberPerms = [
            'project.view_assigned',
            'project_details.view', 'scope.view',
            'task.view', 'task.view_assigned', 'task.edit_assigned', 'task.change_status', 'task.update_progress', 'task.comment', 'task.upload_attachment', 'task.complete',
            'team.view', 'team.view_member',
            'risk.view', 'risk.create', 'blocker.create',
            'approval.view', 'approval.submit'
        ];
        $roleMember->syncPermissions($memberPerms);
        $roleTeamMember->syncPermissions($memberPerms);

        // 5. Default Permissions for Project Sponsor (sponsor)
        $sponsorPerms = [
            'project.view', 'project.view_assigned', 'project.view_all',
            'project_details.view', 'scope.view',
            'task.view', 'task.view_all',
            'team.view', 'team.view_member',
            'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.report',
            'risk.view',
            'approval.view', 'approval.final_approve',
            'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
            'project_settings.view'
        ];
        $roleSponsor->syncPermissions($sponsorPerms);

        // 6. Default Permissions for Project Owner (owner)
        $ownerPerms = [
            'project.view', 'project.view_assigned',
            'project_details.view', 'scope.view',
            'task.view', 'task.view_all',
            'team.view', 'team.view_member',
            'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.submit_change', 'budget.report',
            'risk.view',
            'approval.view', 'approval.submit', 'approval.approve', 'approval.reject', 'approval.return',
            'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
            'project_settings.view'
        ];
        $roleOwner->syncPermissions($ownerPerms);

        // 7. Default Permissions for Steering Committee (steering_committee)
        $committeePerms = [
            'project.view', 'project.view_assigned',
            'project_details.view', 'scope.view',
            'task.view', 'task.view_all',
            'team.view', 'team.view_member',
            'budget.view', 'budget.view_estimated', 'budget.view_actual', 'budget.report',
            'risk.view',
            'approval.view', 'approval.approve', 'approval.reject',
            'report.view', 'report.project', 'report.financial', 'report.performance', 'report.export',
            'project_settings.view'
        ];
        $roleCommittee->syncPermissions($committeePerms);

        // 8. Super Admin always gets all permissions
        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->givePermissionTo(Permission::all());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep permissions intact
    }
};
