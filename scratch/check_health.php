<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Project::with(['risks', 'wbsItems'])->get() as $p) {
    echo sprintf(
        "%-15s | status: %-12s | health: %-10s | deadline: %-12s | risks: %d | blocked_wbs: %d\n",
        $p->code,
        $p->status->value,
        $p->computed_health,
        $p->deadline ? $p->deadline->format('Y-m-d') : 'none',
        $p->risks->where('status', 'open')->count(),
        $p->wbsItems->where('status', 'blocked')->count()
    );
}
