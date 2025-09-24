<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_mst', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->comment('Language id');
            $table->unsignedInteger('original_id')->comment('Original translator id');
            $table->string('value', 255)->comment('Translation value');
            $table->timestamp('created_at')->nullable()->comment('Created time');
            $table->timestamp('updated_at')->nullable()->comment('Updated time');

            //$table->foreign('language_id')->references('id')->on('language_mst');
            //$table->foreign('original_id')->references('id')->on('original_translator_mst');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('translation_mst');
    }
};
