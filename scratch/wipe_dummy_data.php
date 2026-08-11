<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Subsidiary;
use App\Models\Project;
use App\Models\WbsItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Starting cleanup of dummy data...\n";

// Disable Foreign Key Checks
Schema::disableForeignKeyConstraints();

// Truncate Project and Related Dummy Data Tables
DB::table('comments')->truncate();
DB::table('approval_requests')->truncate();
DB::table('project_documents')->truncate();
DB::table('project_status_updates')->truncate();
DB::table('task_blockers')->truncate();
DB::table('project_risks')->truncate();
DB::table('wbs_versions')->truncate();
DB::table('wbs_baselines')->truncate();
DB::table('wbs_dependencies')->truncate();
DB::table('wbs_items')->truncate();
DB::table('project_members')->truncate();
DB::table('projects')->truncate();
DB::table('activity_logs')->truncate();

// Clean up non-admin users if any exist
$superAdminEmail = 'superadmin@georgesteuart.com';
$adminUser = User::where('email', $superAdminEmail)->first();

if (!$adminUser) {
    $adminUser = User::where('email', 'admin@nexuspm.local')->first();
}

if ($adminUser) {
    $adminUser->email = $superAdminEmail;
    $adminUser->name = 'Super Administrator';
    $adminUser->save();

    // Delete all other dummy users except superadmin
    User::where('id', '!=', $adminUser->id)->delete();
} else {
    // Truncate users table and create fresh superadmin
    DB::table('users')->truncate();
    $sub = Subsidiary::first();
    $adminUser = User::create([
        'name' => 'Super Administrator',
        'email' => $superAdminEmail,
        'password' => \Illuminate\Support\Facades\Hash::make('Password@123'),
        'subsidiary_id' => $sub ? $sub->id : null,
        'is_active' => true,
    ]);
}

// Assign super_admin role
$role = \Spatie\Permission\Models\Role::findOrCreate('super_admin', 'web');
$adminUser->assignRole($role);

// Re-enable Foreign Key Checks
Schema::enableForeignKeyConstraints();

echo "SUCCESS: All dummy data cleared cleanly!\n";
echo "Active Users Count: " . User::count() . "\n";
echo "Active Projects Count: " . Project::count() . "\n";
echo "Active WBS Items Count: " . WbsItem::count() . "\n";
