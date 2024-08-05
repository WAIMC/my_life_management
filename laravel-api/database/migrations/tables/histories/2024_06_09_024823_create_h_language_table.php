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
        Schema::create('h_language', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id')->comment('Language id');
            $table->string('abbreviation', 10)->nullable()->comment('Language abbreviation');
            $table->string('name', 30)->nullable()->comment('Language name');
            $table->boolean('is_active')->nullable()->comment('Language active');
            $table->unsignedTinyInteger('action')->comment('Language action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_language');
    }
};
