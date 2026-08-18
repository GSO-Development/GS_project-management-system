<?php

use App\Models\WbsItem;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tasks:auto-start', function () {
    $count = WbsItem::autoStartDueTasks();
    $this->info("Auto-started {$count} task(s) whose start dates have arrived.");
})->purpose('Automatically transition tasks to in_progress on their start date');

Schedule::command('tasks:auto-start')->daily();
