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
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'actor_type')) {
                $table->string('actor_type', 30)->default('user')->after('action')->index();
            }
            if (!Schema::hasColumn('activity_logs', 'batch_uuid')) {
                $table->string('batch_uuid', 36)->nullable()->after('record_id')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'actor_type')) {
                $table->dropIndex(['actor_type']);
                $table->dropColumn('actor_type');
            }
            if (Schema::hasColumn('activity_logs', 'batch_uuid')) {
                $table->dropIndex(['batch_uuid']);
                $table->dropColumn('batch_uuid');
            }
        });
    }
};

