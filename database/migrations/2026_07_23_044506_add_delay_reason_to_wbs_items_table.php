<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            $table->text('delay_reason')->nullable()->after('actual_hours');
            $table->foreignId('delay_reason_by')->nullable()->after('delay_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('delay_reason_at')->nullable()->after('delay_reason_by');
        });
    }

    public function down(): void
    {
        Schema::table('wbs_items', function (Blueprint $table) {
            $table->dropForeign(['delay_reason_by']);
            $table->dropColumn(['delay_reason', 'delay_reason_by', 'delay_reason_at']);
        });
    }
};
