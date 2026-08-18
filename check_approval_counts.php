<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

foreach(\App\Models\User::all() as $u) {
    auth()->login($u);
    $user = $u;
    $isSuperAdmin = $user->hasRole('super_admin');
    $isPM = $user->hasRole('project_manager');

    // Sidebar calculation in app.blade.php
    $sidebarCount = 0;
    if ($isSuperAdmin || $isPM) {
        $pendingReqs = \App\Models\ApprovalRequest::where('status', 'pending')
            ->when($isPM && !$isSuperAdmin, function($q) use ($user) {
                $pmProjectIds = \App\Models\Project::where('project_manager_id', $user->id)->pluck('id');
                $q->whereIn('project_id', $pmProjectIds);
            })
            ->count();

        $pendingLeadership = \App\Models\Project::where('pm_accepted', false)
            ->when($isPM && !$isSuperAdmin, function($q) use ($user) {
                $q->where('project_manager_id', $user->id);
            })
            ->count();

        $sidebarCount = $pendingReqs + $pendingLeadership;
    }

    echo "User [{$u->id}] {$u->name} ({$u->email}) - Role: " . $u->roles->pluck('name')->implode(',') . "\n";
    echo "  Sidebar badge: {$sidebarCount}\n";
    echo "  DB ApprovalRequests (pending): " . \App\Models\ApprovalRequest::where('status', 'pending')->count() . "\n";
    foreach(\App\Models\ApprovalRequest::all() as $ar) {
        echo "    - AR [{$ar->id}] type: {$ar->request_type->value} | status: {$ar->status->value} | proj: {$ar->project_id} | req_by: {$ar->requested_by}\n";
    }
    echo "  DB Projects (pm_accepted=false): " . \App\Models\Project::where('pm_accepted', false)->count() . "\n";
    foreach(\App\Models\Project::where('pm_accepted', false)->get() as $pr) {
        echo "    - PR [{$pr->id}] code: {$pr->code} | name: {$pr->name} | PM: {$pr->project_manager_id} | rejected: " . ($pr->isPmRejected() ? 'YES' : 'NO') . "\n";
    }
    echo "--------------------------------------------------\n";
}
