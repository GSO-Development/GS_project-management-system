<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

// Test ProjectIndex Livewire component
$comp = Livewire\Livewire::test(\App\Livewire\ProjectIndex::class)
    ->assertSuccessful();

echo "✓ ProjectIndex component rendered perfectly!\n";
