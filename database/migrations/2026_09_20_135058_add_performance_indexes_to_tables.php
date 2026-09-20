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
        Schema::table('macro_scenarios', function (Blueprint $table) {
            $table->index('is_preset');
        });

        Schema::table('macro_analysis_results', function (Blueprint $table) {
            $table->index(['scenario_id', 'sector_id']);
            $table->index('impact_score');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index('sector_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('macro_scenarios', function (Blueprint $table) {
            $table->dropIndex(['is_preset']);
        });

        Schema::table('macro_analysis_results', function (Blueprint $table) {
            $table->dropIndex(['scenario_id', 'sector_id']);
            $table->dropIndex(['impact_score']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['sector_id']);
        });
    }
};
