<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$deleted = \App\Models\ApprovalRequest::whereNotNull('project_id')
    ->whereNotIn('project_id', \App\Models\Project::pluck('id'))
    ->delete();

echo "Deleted $deleted orphan approval requests.\n";
