<?php

use App\Services\TaskOverdueNotificationService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tasks:check-overdue', function () {
    $count = TaskOverdueNotificationService::checkAndNotifyOverdueTasks();
    $this->info("Checked overdue deliverables. Dispatched {$count} alert(s) to PMO Admins.");
})->purpose('Check for overdue tasks and send automated email and database notifications to PMO Admins');

Schedule::command('tasks:check-overdue')->everyThirtyMinutes();
