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
        Schema::create('t_language', function (Blueprint $table) {
            $table->increments('id');
            $table->string('abbreviation', 10)->comment('Language abbreviation');
            $table->string('name', 30)->comment('Language name');
            $table->boolean('is_active')->default(false)->comment('Language active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_language');
    }
};
