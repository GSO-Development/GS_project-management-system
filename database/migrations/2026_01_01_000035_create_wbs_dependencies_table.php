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
        Schema::create('wbs_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('predecessor_id')->constrained('wbs_items')->cascadeOnDelete();
            $table->foreignId('successor_id')->constrained('wbs_items')->cascadeOnDelete();
            $table->enum('dependency_type', [
                'finish_to_start',
                'start_to_start',
                'finish_to_finish',
                'start_to_finish',
            ])->default('finish_to_start');
            $table->integer('lag_days')->default(0)->comment('Positive = lag, Negative = lead');
            $table->timestamps();

            $table->unique(['predecessor_id', 'successor_id']);
            $table->index('predecessor_id');
            $table->index('successor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wbs_dependencies');
    }
};
