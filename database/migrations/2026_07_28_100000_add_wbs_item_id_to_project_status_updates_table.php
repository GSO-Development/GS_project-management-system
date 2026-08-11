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
        if (!Schema::hasColumn('project_status_updates', 'wbs_item_id')) {
            Schema::table('project_status_updates', function (Blueprint $table) {
                $table->foreignId('wbs_item_id')->nullable()->after('project_id')->constrained('wbs_items')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('project_status_updates', 'wbs_item_id')) {
            Schema::table('project_status_updates', function (Blueprint $table) {
                $table->dropForeign(['wbs_item_id']);
                $table->dropColumn('wbs_item_id');
            });
        }
    }
};
