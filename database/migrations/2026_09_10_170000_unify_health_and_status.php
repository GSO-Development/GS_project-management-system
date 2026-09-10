<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // 1. Modify projects.status to allow 'at_risk' and make health nullable
            DB::statement("ALTER TABLE `projects` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'draft'");
            DB::statement("ALTER TABLE `projects` MODIFY COLUMN `health` VARCHAR(50) NULL DEFAULT 'on_track'");

            // 2. Modify wbs_items.status to allow 'at_risk'
            DB::statement("ALTER TABLE `wbs_items` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'not_started'");
        }

        // 3. Migrate any existing active projects with health 'at_risk' to status 'at_risk'
        DB::table('projects')
            ->where('health', 'at_risk')
            ->whereNotIn('status', ['completed', 'cancelled', 'archived'])
            ->update(['status' => 'at_risk']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
