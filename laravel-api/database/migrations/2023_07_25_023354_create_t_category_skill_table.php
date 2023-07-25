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
        Schema::create('t_category_skill', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('skill_id');
            $table->primary(['category_id', 'skill_id']);
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('t_category');
            $table->foreign('skill_id')->references('id')->on('t_skill');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_category_skill');
    }
};
