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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 10)->unique();
            $table->string('name');
            $table->foreignId('sector_id')->constrained('sectors_cache')->cascadeOnDelete();
            $table->unsignedBigInteger('market_cap')->nullable();
            $table->decimal('pe_ratio', 8, 2)->nullable();
            $table->decimal('pbv_ratio', 8, 2)->nullable();
            $table->decimal('der', 8, 2)->nullable();
            $table->decimal('npm', 8, 2)->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
