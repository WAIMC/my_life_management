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
        Schema::create('t_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('original_translator_id')->comment('Original translator id');
            $table->text('translate')->comment('Translate text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_translation');
    }
};
