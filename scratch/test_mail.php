<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('This is a test email from GS NexusPM Engine to verify email configuration and delivery.', function ($message) {
        $message->to('nadumi@gsoptimize.lk')
                ->subject('Test Email - GS NexusPM Engine');
    });
    echo "SUCCESS: Email sent successfully to nadumi@gsoptimize.lk\n";
} catch (\Throwable $e) {
    echo "ERROR: Failed to send email.\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
