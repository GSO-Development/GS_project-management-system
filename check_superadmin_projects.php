<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'superadmin@georgesteuart.com')->first();
echo "User: " . $user->name . "\n";
echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
echo "hasRole('super_admin'): " . ($user->hasRole('super_admin') ? 'YES' : 'NO') . "\n";
echo "Projects count total in DB: " . \App\Models\Project::count() . "\n";
foreach(\App\Models\Project::all() as $p) {
    echo " - [{$p->code}] {$p->name} | PM: " . ($p->projectManager->name ?? 'NULL') . " | PM Accepted: " . ($p->pm_accepted ? 'YES' : 'NO') . " | Deleted: " . ($p->trashed() ? 'YES' : 'NO') . "\n";
}
