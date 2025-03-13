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
        Schema::create('t_skill_description', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('Parent skill description');
            $table->string('title', 100)->comment('Title skill description');
            $table->string('summary', 255)->comment('Summary skill description');
            $table->text('article')->comment('Article skill description');
            $table->unsignedTinyInteger('status')->default(0)->comment('Skill description status');
            $table->boolean('is_display')->default(false)->comment('Display skill description');
            $table->unsignedSmallInteger('rank_order')->default(0)->comment('Skill description order');
            $table->unsignedInteger('skill_id')->comment('Skill id primary key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_skill_description');
    }
};
