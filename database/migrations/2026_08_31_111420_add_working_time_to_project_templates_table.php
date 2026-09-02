<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->time('work_start_time')->nullable()->after('description');
            $table->time('work_end_time')->nullable()->after('work_start_time');
            $table->json('work_days')->nullable()->after('work_end_time');
            $table->boolean('divide_by_hours')->default(false)->after('work_days');
        });
    }

    public function down(): void
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->dropColumn(['work_start_time', 'work_end_time', 'work_days', 'divide_by_hours']);
        });
    }
};

