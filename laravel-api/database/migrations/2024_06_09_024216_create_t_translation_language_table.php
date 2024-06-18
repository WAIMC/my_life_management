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
        Schema::create('t_translation_language', function (Blueprint $table) {
            $table->unsignedInteger('translation_id');
            $table->unsignedInteger('language_id');
            $table->primary(['translation_id', 'language_id']);
            $table->timestamps();
            $table->foreign('translation_id')->references('id')->on('t_translation');
            $table->foreign('language_id')->references('id')->on('t_language');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_translation_language');
    }
};
