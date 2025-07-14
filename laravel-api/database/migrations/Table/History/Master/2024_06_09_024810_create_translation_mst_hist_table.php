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
            $table->increments('id');
            $table->unsignedInteger('translation_mst_id')->comment('Translation id');
            $table->unsignedInteger('original_translator_id')->nullable()->comment('Original translator id');
            $table->text('translate')->nullable()->comment('Translate text');
            $table->unsignedTinyInteger('action')->comment('Translation action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
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
