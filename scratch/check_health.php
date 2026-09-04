<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WbsItem;
use App\Services\TaskHealthCalculationService;

$items = WbsItem::where('title', 'like', '%Day 2%')->orWhere('title', 'like', '%08:30 AM%')->get();

foreach ($items as $item) {
    $health = TaskHealthCalculationService::calculate($item);
    echo "ID: {$item->id} | Title: {$item->title} | Start: {$item->start_date} {$item->start_time} | End: {$item->end_date} {$item->end_time} | Status: {$health['status']} ({$health['label']}) | Reason: {$health['reason']}\n";
}
