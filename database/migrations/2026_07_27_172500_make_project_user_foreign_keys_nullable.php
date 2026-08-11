<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
            $table->dropForeign(['created_by']);

            $table->unsignedBigInteger('project_manager_id')->nullable()->change();
            $table->unsignedBigInteger('created_by')->nullable()->change();

            $table->foreign('project_manager_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('wbs_items', function (Blueprint $table) {
            $table->dropForeign(['assigned_user_id']);
            $table->unsignedBigInteger('assigned_user_id')->nullable()->change();
            $table->foreign('assigned_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        //
    }
};
