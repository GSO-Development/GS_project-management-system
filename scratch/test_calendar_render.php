<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::first();
auth()->login($user);

try {
    $html = \Livewire\Livewire::test(\App\Livewire\CalendarView::class)->html();
    echo "SUCCESS: CalendarView rendered successfully! HTML length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
