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
        Schema::create('original_translator_mst_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('original_translator_mst_id')->comment('Original translator id');
            $table->string('table', 64)->nullable()->comment('Table name');
            $table->string('column', 64)->nullable()->comment('Column name');
            $table->unsignedInteger('field_id')->nullable()->comment('Field id');
            $table->unsignedTinyInteger('action')->comment('action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');

           $table->foreign('original_translator_mst_id')->references('id')->on('original_translator_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('original_translator_mst_hist');
    }
};
