<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

foreach(\App\Models\ApprovalRequest::with(['project.subsidiary', 'requester', 'reviewer'])->get() as $r) {
    echo "ID: {$r->id} | Project: " . ($r->project->name ?? 'NULL') . " | Code: " . ($r->project->code ?? 'NULL') . " | Type: {$r->request_type->value} | Status: {$r->status->value}\n";
    if (!$r->project) {
        echo "   -> requested_value: " . json_encode($r->requested_value) . "\n";
    }
}
