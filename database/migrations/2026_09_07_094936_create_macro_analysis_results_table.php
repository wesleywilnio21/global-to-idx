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
        Schema::create('macro_analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scenario_id')->constrained('macro_scenarios')->cascadeOnDelete();
            $table->foreignId('sector_id')->constrained('sectors_cache')->cascadeOnDelete();
            $table->tinyInteger('impact_score'); // -10 to +10
            $table->string('resilience_status'); // Resilient, Neutral, Vulnerable, Critical
            $table->text('reasoning');
            $table->json('vulnerable_companies')->nullable();
            $table->json('beneficiary_companies')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('macro_analysis_results');
    }
};
