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
        Schema::create('t_setting_link', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 30)->comment('Setting link key');
            $table->string('value', 100)->comment('Setting link value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_setting_link');
    }
};
