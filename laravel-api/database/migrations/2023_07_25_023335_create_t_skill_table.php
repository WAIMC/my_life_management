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
        Schema::create('t_skill', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('Parent skill');
            $table->string('name', 50)->comment('Skill name');
            $table->string('slug', 50)->comment('Skill slug');
            $table->unsignedTinyInteger('status')->default(0)->comment('Skill status');
            $table->boolean('is_display')->default(false)->comment('Display skill');
            $table->unsignedSmallInteger('rank_order')->default(0)->comment('Skill order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_skill');
    }
};
