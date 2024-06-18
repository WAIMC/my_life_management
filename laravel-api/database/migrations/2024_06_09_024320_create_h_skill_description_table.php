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
        Schema::create('h_skill_description', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('skill_description_id')->comment('Skill description id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent skill description');
            $table->string('title', 100)->nullable()->comment('Title skill description');
            $table->string('summary', 255)->nullable()->comment('Summary skill description');
            $table->text('article')->nullable()->comment('Article skill description');
            $table->unsignedTinyInteger('status')->nullable()->comment('Skill description status');
            $table->boolean('is_display')->nullable()->comment('Display skill description');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('Skill description order');
            $table->unsignedInteger('skill_id')->nullable()->comment('Skill id primary key');
            $table->unsignedTinyInteger('action')->comment('Skill description action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_skill_description');
    }
};
