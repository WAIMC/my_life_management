<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_description_mgmt', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('Parent skill');
            $table->string('title', 100)->comment('Title skill');
            $table->string('summary', 255)->comment('Summary skill');
            $table->text('article')->comment('Article skill');
            $table->unsignedTinyInteger('status')->default(0)->comment('Skill status');
            $table->boolean('is_display')->default(false)->comment('Display skill');
            $table->unsignedSmallInteger('rank_order')->default(0)->comment('Rank order');
            $table->unsignedInteger('skill_id')->comment('Skill ID');
            $table->boolean('is_delete')->default(false)->comment('is deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_description_mgmt');
    }
};
