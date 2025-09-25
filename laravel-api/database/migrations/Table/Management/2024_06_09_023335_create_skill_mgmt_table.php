<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skill_mgmt', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('Parent skill');
            $table->string('name', 50)->comment('Skill name');
            $table->string('slug', 50)->comment('Skill slug');
            $table->unsignedTinyInteger('status')->default(0)->comment('Skill status');
            $table->boolean('is_display')->default(false)->comment('Display skill');
            $table->unsignedSmallInteger('rank_order')->default(0)->comment('Skill order');
            $table->boolean('is_delete')->default(false)->comment('is deleted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_mgmt');
    }
};
