<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subsidiary;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

$role = Role::findOrCreate('super_admin', 'web');
$sub = Subsidiary::first();

// 1. Create or Update superadmin@georgesteuart.com
$superAdmin = User::updateOrCreate(
    ['email' => 'superadmin@georgesteuart.com'],
    [
        'name' => 'Super Admin',
        'password' => Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]
);
$superAdmin->assignRole($role);

// 2. Also ensure admin@nexuspm.local is super_admin
$adminLocal = User::updateOrCreate(
    ['email' => 'admin@nexuspm.local'],
    [
        'name' => 'Super Administrator',
        'password' => Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]
);
$adminLocal->assignRole($role);

echo "SUCCESS: Super Admin Account Created!\n";
echo "Email: superadmin@georgesteuart.com\n";
echo "Password: Password@123\n";
