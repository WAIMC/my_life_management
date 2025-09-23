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
        Schema::create('language_mst', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('abbreviation', 10)->comment('Language abbreviation code');
            $table->string('name', 30)->comment('Language name');
            $table->boolean('is_active')->default(true)->comment('Flag to indicate if language is active');
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
            
            $table->comment('Master table for languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_mst');
    }
};
