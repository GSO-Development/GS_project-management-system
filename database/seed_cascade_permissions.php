<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p1 = Permission::firstOrCreate(['name' => 'schedule.view_impact',  'guard_name' => 'web']);
$p2 = Permission::firstOrCreate(['name' => 'schedule.apply_cascade', 'guard_name' => 'web']);

echo "Permissions created/found: {$p1->name}, {$p2->name}\n";

// Full access roles
foreach (['super_admin', 'pmo_admin', 'lead', 'project_manager'] as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        $role->givePermissionTo([$p1, $p2]);
        echo "✓ Both permissions → role: $roleName\n";
    }
}

// View-only roles
foreach (['member', 'team_member', 'sponsor', 'owner', 'steering_committee'] as $roleName) {
    $role = Role::where('name', $roleName)->first();
    if ($role) {
        $role->givePermissionTo($p1);
        echo "✓ view_impact only → role: $roleName\n";
    }
}

// Clear permission cache
app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

echo "\nDone! Permissions seeded successfully.\n";
