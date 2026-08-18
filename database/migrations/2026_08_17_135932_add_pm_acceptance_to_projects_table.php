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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('pm_accepted')->default(false)->after('project_manager_id');
            $table->timestamp('pm_accepted_at')->nullable()->after('pm_accepted');
        });

        // Set all existing projects as accepted
        DB::table('projects')->update([
            'pm_accepted' => true,
            'pm_accepted_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['pm_accepted', 'pm_accepted_at']);
        });
    }
};
