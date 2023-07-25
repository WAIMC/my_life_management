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
        Schema::create('h_skill', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('skill_id')->comment('Skill id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent skill');
            $table->string('name', 50)->nullable()->comment('Skill name');
            $table->string('slug', 50)->nullable()->comment('Skill slug');
            $table->unsignedTinyInteger('status')->nullable()->comment('Skill status');
            $table->boolean('is_display')->nullable()->comment('Display skill');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('Skill order');
            $table->unsignedTinyInteger('action')->comment('Skill action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_skill');
    }
};
