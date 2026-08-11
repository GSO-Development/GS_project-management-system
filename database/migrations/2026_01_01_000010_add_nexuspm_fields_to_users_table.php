<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Extends the users table with NexusPM-specific fields.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email');
            $table->string('profile_image')->nullable()->after('phone_number');
            $table->foreignId('subsidiary_id')->nullable()->constrained('subsidiaries')->nullOnDelete()->after('profile_image');
            $table->boolean('is_active')->default(true)->after('subsidiary_id');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'profile_image',
                'subsidiary_id',
                'is_active',
                'last_login_at',
                'deleted_at',
            ]);
        });
    }
};
