<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\RbacService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

echo "Syncing permissions and default role mappings...\n";

app()[PermissionRegistrar::class]->forgetCachedPermissions();

// Create all permissions from RbacService MODULES
$modules = RbacService::MODULES;
$permCount = 0;
foreach ($modules as $mod) {
    foreach (array_keys($mod['permissions']) as $permCode) {
        Permission::findOrCreate($permCode, 'web');
        $permCount++;
    }
}
echo "✓ Ensured {$permCount} granular permissions exist in DB.\n";

$rolesToSync = [
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

foreach ($rolesToSync as $roleCode) {
    $role = Role::findOrCreate($roleCode, 'web');
    if ($roleCode === 'super_admin' || $roleCode === 'pmo_admin') {
        $defaults = Permission::pluck('name')->toArray();
        $role->syncPermissions($defaults);
    } else {
        $defaults = RbacService::getDefaultPermissionsForRole($roleCode);
        // Explicitly filter out project.create, project.delete, create projects, delete projects
        $defaults = array_diff($defaults, ['project.create', 'project.delete', 'create projects', 'delete projects', 'create_project', 'delete_project']);
        $role->syncPermissions($defaults);
        
        // Revoke if assigned directly
        $role->revokePermissionTo(['project.create', 'project.delete']);
    }
    echo "  - Synced " . count($role->permissions) . " default permissions for role '{$roleCode}' (Project create/delete restricted to PMO/Super Admin)\n";
}

RbacService::clearCache();

echo "\n✅ Role & Permission Sync Completed Successfully!\n";
