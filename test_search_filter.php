<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

// Test Lead Projects Livewire component
$comp = Livewire\Livewire::test(\App\Livewire\MyLeadProjects::class)
    ->set('search', 'ABC')
    ->assertSee('ABC')
    ->set('statusFilter', 'all')
    ->assertSuccessful();

echo "✓ MyLeadProjects Search & Filter: SUCCESS!\n";

$collabComp = Livewire\Livewire::test(\App\Livewire\MyCollaboratorProjects::class)
    ->set('search', '')
    ->set('statusFilter', 'all')
    ->assertSuccessful();

echo "✓ MyCollaboratorProjects Search & Filter: SUCCESS!\n";
