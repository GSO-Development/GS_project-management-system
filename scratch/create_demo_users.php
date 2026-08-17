<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subsidiary;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

$sub = Subsidiary::first();

// 1. Super Admin: admin@nexuspm.local
$superAdminRole = Role::findOrCreate('super_admin', 'web');
$admin = User::updateOrCreate(
    ['email' => 'admin@nexuspm.local'],
    [
        'name' => 'Super Admin (Local)',
        'password' => Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]
);
$admin->assignRole($superAdminRole);

// 2. Project Manager: manager@nexuspm.local
$pmRole = Role::findOrCreate('project_manager', 'web');
$manager = User::updateOrCreate(
    ['email' => 'manager@nexuspm.local'],
    [
        'name' => 'Demo Project Manager',
        'password' => Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]
);
$manager->assignRole($pmRole);

// 3. Team Member: member@nexuspm.local
$memberRole = Role::findOrCreate('team_member', 'web');
$member = User::updateOrCreate(
    ['email' => 'member@nexuspm.local'],
    [
        'name' => 'Demo Team Member',
        'password' => Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]
);
$member->assignRole($memberRole);

echo "SUCCESS: Demo accounts ensured!\n";
echo "admin@nexuspm.local / Password@123 (Super Admin)\n";
echo "manager@nexuspm.local / Password@123 (Project Manager)\n";
echo "member@nexuspm.local / Password@123 (Team Member)\n";
