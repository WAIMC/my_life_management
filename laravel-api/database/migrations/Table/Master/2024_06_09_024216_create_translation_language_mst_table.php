<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translation_language_mst', function (Blueprint $table) {
            // Foreign key references to translation_mst and language_mst tables
            $table->unsignedInteger('translation_mst_id')->comment('Translation ID');
            $table->unsignedInteger('language_mst_id')->comment('Language ID');

            // Composite primary key
            $table->primary(['translation_mst_id', 'language_mst_id']);

            // Foreign key constraints
            $table->foreign('translation_mst_id')->references('id')->on('translation_mst');
            $table->foreign('language_mst_id')->references('id')->on('language_mst');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_language_mst');
    }
};
