<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\WbsItem;
use App\Models\CalendarEvent;

$user = User::where('email', 'nadumi@gsoptimize.lk')->orWhere('name', 'like', '%Nadumi%')->first();
echo "USER ID: " . $user->id . " | NAME: " . $user->name . " | EMAIL: " . $user->email . "\n\n";

$tasks = WbsItem::where('assigned_user_id', $user->id)->get();
echo "TOTAL TASKS ASSIGNED TO USER: " . $tasks->count() . "\n";
foreach ($tasks as $t) {
    echo "ID: {$t->id} | Title: '{$t->title}' | Start: {$t->start_date} | End: {$t->end_date} | Status: " . (is_object($t->status) ? $t->status->value : $t->status) . "\n";
}

$events = CalendarEvent::where('created_by', $user->id)
    ->orWhereJsonContains('attendees', (string)$user->id)
    ->orWhereJsonContains('attendees', (int)$user->id)
    ->get();
echo "\nTOTAL CALENDAR EVENTS FOR USER: " . $events->count() . "\n";
foreach ($events as $e) {
    echo "ID: {$e->id} | Title: '{$e->title}' | Start: {$e->start_date} | End: {$e->end_date} | Attendees: " . json_encode($e->attendees) . "\n";
}
