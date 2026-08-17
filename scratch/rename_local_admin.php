<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user224 = App\Models\User::find(224);
if ($user224) {
    $user224->name = 'PMO Admin (Local)';
    $user224->save();
    echo "User 224 renamed to PMO Admin (Local)\n";
}
