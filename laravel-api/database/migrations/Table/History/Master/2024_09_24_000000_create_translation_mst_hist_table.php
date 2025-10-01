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
        Schema::create('translation_mst_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('translation_mst_id')->comment('Translation id');
            $table->integer('language_id')->nullable()->comment('Language id');
            $table->integer('original_id')->nullable()->comment('Original id');
            $table->string('value', 255)->nullable()->comment('value');
            $table->integer('action')->comment('Action');
            $table->integer('author_id')->comment('Author id');
            $table->string('created_at');

            $table->foreign('translation_mst_id')->references('id')->on('translation_mst');
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
