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
        Schema::create('sectors_cache', function (Blueprint $table) {
            $table->id();
            $table->string('sector_code', 50)->unique();
            $table->string('sector_name', 100);
            $table->decimal('avg_der', 8, 2)->nullable();
            $table->decimal('avg_npm', 8, 2)->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sectors_cache');
    }
};
