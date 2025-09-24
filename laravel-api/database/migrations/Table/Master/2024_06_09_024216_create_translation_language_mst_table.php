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
            $table->unsignedInteger('translation_id')->comment('Translation ID');
            $table->unsignedInteger('language_id')->comment('Language ID');
            $table->primary(['translation_id', 'language_id']);
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
