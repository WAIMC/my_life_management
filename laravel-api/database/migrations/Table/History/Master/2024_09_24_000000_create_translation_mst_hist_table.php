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
        Schema::create('translation_mst_hist', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('translation_mst_id');
            $table->integer('language_id')->nullable();
            $table->integer('original_id')->nullable();
            $table->string('value', 255)->nullable();
            $table->integer('action');
            $table->integer('author_id');
            $table->string('created_at');

            $table->foreign('translation_mst_id')->references('id')->on('translation_mst');
            $table->foreign('language_id')->references('id')->on('language_mst');
            $table->foreign('original_id')->references('id')->on('original_translator_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_mst_hist');
    }
};