<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Subsidiary;

$subs = Subsidiary::latest()->get();
echo "Total Subsidiaries: " . $subs->count() . "\n";
foreach ($subs as $s) {
    echo "ID: {$s->id} | Created: {$s->created_at} | Name: {$s->name}\n";
}
