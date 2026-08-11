<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "Cleaning database...\n";

// Disable foreign key checks
Schema::disableForeignKeyConstraints();

// Truncate tables
DB::table('wbs_dependencies')->truncate();
DB::table('wbs_items')->truncate();
DB::table('task_blockers')->truncate();
DB::table('comments')->truncate();
DB::table('project_documents')->truncate();
DB::table('project_risks')->truncate();
DB::table('project_status_updates')->truncate();
DB::table('approval_requests')->truncate();
DB::table('project_members')->truncate();
DB::table('activity_logs')->truncate();
DB::table('projects')->truncate();

// Clean up extra users, keep only the 3 main ones
$emailsToKeep = [
    'admin@nexuspm.local',
    'manager@nexuspm.local',
    'member@nexuspm.local',
];

$usersToDelete = User::whereNotIn('email', $emailsToKeep)->get();
foreach ($usersToDelete as $user) {
    // Detach roles
    $user->roles()->detach();
    $user->delete();
}

Schema::enableForeignKeyConstraints();

echo "Database Cleaned Successfully! Kept only default Roles, Permissions, Subsidiaries, and 3 Default Logins.\n";
