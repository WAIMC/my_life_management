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
        Schema::create('skill_mgmt_hist', function (Blueprint $table) {
            $table->increments('id')->comment('Skill id');
            $table->unsignedInteger('skill_mgmt_id')->comment('Skill management id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent skill');
            $table->string('name', 50)->nullable()->comment('name');
            $table->string('slug', 50)->nullable()->comment('slug');
            $table->unsignedTinyInteger('status')->nullable()->comment('status');
            $table->boolean('is_display')->nullable()->comment('Display skill');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('order');
            $table->unsignedTinyInteger('action')->comment('action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_mgmt_hist');
    }
};
