<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\User::all() as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email}\n";
    if (in_array(strtolower($u->name), ['super admin', 'super administrator', 'superadmin'])) {
        $u->name = 'PMO Admin';
        $u->save();
        echo " -> Renamed to PMO Admin\n";
    }
}
