<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

foreach(\App\Models\User::all() as $user) {
    $isSuperAdmin = ($user->hasRole('super_admin') || $user->email === 'admin@nexuspm.local' || $user->id === 1);
    
    if ($isSuperAdmin) {
        $pendingApprovalsCount = \App\Models\ApprovalRequest::where('status', 'pending')->count();
    } else {
        $pendingApprovalsCount = \App\Models\ApprovalRequest::where('status', 'pending')
            ->where(function($q) use ($user) {
                $q->where('requested_by', $user->id)
                  ->orWhereHas('project', fn($pq) => $pq->where('project_manager_id', $user->id));
            })
            ->count();
    }

    echo "User [{$user->id}] {$user->name} ({$user->email}) -> Pending Approvals Count: {$pendingApprovalsCount}\n";
}
