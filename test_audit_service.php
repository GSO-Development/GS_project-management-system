<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Project;
use App\Models\WbsItem;
use App\Services\AuditLogService;

echo "1. Testing AuditLogService Actor Types...\n";
$user = User::first();
auth()->login($user);

// User action
$log1 = AuditLogService::log(
    action: 'test_user_action',
    module: 'tests',
    record: $user,
    new: ['test' => 'user']
);
echo "User log actor_type: " . ($log1->actor_type ?? 'none') . "\n";

// System action
$batchUuid = AuditLogService::newBatchUuid();
$log2 = AuditLogService::asSystem(function () use ($user, $batchUuid) {
    return AuditLogService::log(
        action: 'test_system_action',
        module: 'tests',
        record: $user,
        new: ['test' => 'system'],
        batchUuid: $batchUuid
    );
}, $batchUuid);
echo "System log actor_type: " . ($log2->actor_type ?? 'none') . " | batch_uuid: " . ($log2->batch_uuid ?? 'none') . "\n";

// Maintenance action
$log3 = AuditLogService::asMaintenance(function () use ($user) {
    return AuditLogService::log(
        action: 'test_maint_action',
        module: 'tests',
        record: $user,
        new: ['test' => 'maintenance']
    );
});
echo "Maintenance log actor_type: " . ($log3->actor_type ?? 'none') . "\n";

// Test Bulk Disassociation
echo "2. Testing logBulkDisassociation...\n";
$dummyRecords = [
    (object)['id' => 99991, 'title' => 'Dummy Task 1', 'wbs_code' => 'WBS 1.1'],
    (object)['id' => 99992, 'title' => 'Dummy Task 2', 'wbs_code' => 'WBS 1.2'],
];
$count = AuditLogService::logBulkDisassociation(
    modelClass: WbsItem::class,
    records: $dummyRecords,
    fieldCleared: 'assigned_user_id',
    clearedValue: $user->name,
    action: 'unassigned_task',
    module: 'wbs_items',
    reason: "Test user {$user->name} deleted",
    batchUuid: $batchUuid
);
echo "Bulk unassigned records logged: {$count}\n";

// Verify created records in DB
$batchLogs = ActivityLog::where('batch_uuid', $batchUuid)->get();
echo "Total logs in batch {$batchUuid}: " . $batchLogs->count() . "\n";
foreach ($batchLogs as $bl) {
    echo "  - [{$bl->action}] record: {$bl->record_type}#{$bl->record_id} | actor: {$bl->actor_type}\n";
}

// Clean up test logs
ActivityLog::where('module', 'tests')->delete();
ActivityLog::where('batch_uuid', $batchUuid)->delete();

echo "All tests passed successfully!\n";

