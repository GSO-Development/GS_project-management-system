<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Livewire\SettingsManager;
use App\Models\User;

$user = User::first();
auth()->login($user);

$sm = new SettingsManager();
$sm->mount();
$sm->testEmailRecipient = 'nadumi@gsoptimize.lk';
echo "Calling sendTestEmail()...\n";
$sm->sendTestEmail();
echo "Status: " . $sm->testMailStatus . "\n";
echo "Error: " . $sm->testMailError . "\n";
