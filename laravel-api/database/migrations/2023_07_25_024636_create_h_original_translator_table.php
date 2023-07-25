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
        Schema::create('h_original_translator', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('original_translator_id')->comment('Original translator id');
            $table->string('table', 64)->nullable()->comment('Table name');
            $table->string('column', 64)->nullable()->comment('Column name');
            $table->unsignedInteger('field_id')->nullable()->comment('Field id');
            $table->unsignedTinyInteger('action')->comment('Original translator action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_original_translator');
    }
};
