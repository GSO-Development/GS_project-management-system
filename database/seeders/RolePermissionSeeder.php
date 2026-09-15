<?php

namespace Database\Seeders;

use App\Services\RbacService;
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

        // 1. Ensure all granular module permissions are created in database
        $modules = RbacService::MODULES;
        foreach ($modules as $mod) {
            foreach (array_keys($mod['permissions']) as $permCode) {
                Permission::findOrCreate($permCode, 'web');
            }
        }

        // 2. Standard system & governance roles
        $rolesToSeed = [
            'super_admin',
            'pmo_admin',
            'lead',
            'project_manager',
            'member',
            'team_member',
            'sponsor',
            'owner',
            'steering_committee',
            'collaborator',
        ];

        foreach ($rolesToSeed as $roleCode) {
            $role = Role::findOrCreate($roleCode, 'web');
            $defaults = RbacService::getDefaultPermissionsForRole($roleCode);
            if (!empty($defaults)) {
                $role->syncPermissions($defaults);
            }
        }

        RbacService::clearCache();
    }
}
