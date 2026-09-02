<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            $table->integer('rescheduled_shift_days')->nullable()->after('delay_reason_at')
                ->comment('Number of days this task was shifted by auto-cascade');
            $table->timestamp('rescheduled_at')->nullable()->after('rescheduled_shift_days')
                ->comment('When the auto-cascade last rescheduled this task');
            $table->string('rescheduled_reason', 500)->nullable()->after('rescheduled_at')
                ->comment('Human-readable reason e.g. upstream task X extended by N days');
        });
    }

    public function down(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            $table->dropColumn(['rescheduled_shift_days', 'rescheduled_at', 'rescheduled_reason']);
        });
    }
};
