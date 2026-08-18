<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$nadumi = \App\Models\User::where('email', 'nadumi@gsoptimize.lk')->first() ?? \App\Models\User::first();
auth()->login($nadumi);

echo "Logged in as: " . $nadumi->name . " ({$nadumi->email})\n";

// Check current pending invitations
$pending = \App\Models\Project::where('project_manager_id', $nadumi->id)->where('pm_accepted', false)->get();
echo "Currently has " . $pending->count() . " pending project leadership invitations:\n";
foreach($pending as $p) {
    echo " - [{$p->code}] {$p->name}\n";
}

// Test Livewire component render
$comp = Livewire\Livewire::test(\App\Livewire\ProjectManagerDashboard::class)
    ->assertSuccessful();

echo "\n✓ ProjectManagerDashboard rendered successfully with {$pending->count()} invitations!\n";
