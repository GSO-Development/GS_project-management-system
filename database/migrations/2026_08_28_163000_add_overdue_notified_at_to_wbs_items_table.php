<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            if (!Schema::hasColumn('wbs_items', 'overdue_notified_at')) {
                $table->timestamp('overdue_notified_at')->nullable()->after('end_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            if (Schema::hasColumn('wbs_items', 'overdue_notified_at')) {
                $table->dropColumn('overdue_notified_at');
            }
        });
    }
};
