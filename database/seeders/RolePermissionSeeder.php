<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Subsidiary permissions
            'view subsidiaries',
            'create subsidiaries',
            'edit subsidiaries',
            'archive subsidiaries',

            // User permissions
            'view users',
            'create users',
            'edit users',
            'manage roles',

            // Project permissions
            'view all projects',
            'view assigned projects',
            'create projects',
            'edit projects',
            'archive projects',

            // WBS permissions
            'create wbs',
            'edit wbs',
            'delete wbs',
            'approve wbs',

            // Approvals
            'submit approvals',
            'review approvals',

            // Reports & Audits
            'view reports',
            'view audit logs',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        // Create Roles & Assign Permissions
        $superAdmin = Role::findOrCreate('super_admin', 'web');
        $superAdmin->givePermissionTo(Permission::all());

        $projectManager = Role::findOrCreate('project_manager', 'web');
        $projectManager->givePermissionTo([
            'view assigned projects',
            'edit projects',
            'create wbs',
            'edit wbs',
            'delete wbs',
            'submit approvals',
            'view reports',
        ]);

        $teamMember = Role::findOrCreate('team_member', 'web');
        $teamMember->givePermissionTo([
            'view assigned projects',
        ]);

        $collaborator = Role::findOrCreate('collaborator', 'web');
        $collaborator->givePermissionTo([
            'view assigned projects',
            'submit approvals',
        ]);
    }
}
