<?php

use App\Models\WbsItem;
use App\Services\TaskOverdueNotificationService;
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

Artisan::command('tasks:check-overdue', function () {
    $count = TaskOverdueNotificationService::checkAndNotifyOverdueTasks();
    $this->info("Checked overdue deliverables. Dispatched {$count} alert(s) to PMO Admins.");
})->purpose('Check for overdue tasks and send automated email and database notifications to PMO Admins');

Schedule::command('tasks:auto-start')->daily();
Schedule::command('tasks:check-overdue')->everyThirtyMinutes();
