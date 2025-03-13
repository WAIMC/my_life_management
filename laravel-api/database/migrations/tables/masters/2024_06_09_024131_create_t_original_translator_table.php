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
        Schema::create('t_original_translator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 64)->comment('Table name');
            $table->string('column', 64)->comment('Column name');
            $table->unsignedInteger('field_id')->comment('Field id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_original_translator');
    }
};
