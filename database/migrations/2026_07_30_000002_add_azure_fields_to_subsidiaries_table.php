<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds azure_id to subsidiaries table to track Azure AD provisioned subsidiaries.
     */
    public function up(): void
    {
        Schema::table('subsidiaries', function (Blueprint $table) {
            $table->string('azure_id')->nullable()->unique()->after('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subsidiaries', function (Blueprint $table) {
            $table->dropColumn('azure_id');
        });
    }
};
