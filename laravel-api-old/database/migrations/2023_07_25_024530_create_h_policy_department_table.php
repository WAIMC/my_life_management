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
        Schema::create('h_policy_department', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('policy_department_id')->comment('Policy department id');
            $table->unsignedInteger('category_id')->nullable()->comment('Category id');
            $table->unsignedInteger('skill_id')->nullable()->comment('skill id');
            $table->unsignedInteger('skill_description_id')->nullable()->comment('Skill description id');
            $table->unsignedInteger('slider_id')->nullable()->comment('Slider id');
            $table->unsignedInteger('banner_id')->nullable()->comment('Banner id');
            $table->unsignedInteger('setting_link_id')->nullable()->comment('Setting link id');
            $table->unsignedInteger('social_id')->nullable()->comment('Social id');
            $table->unsignedTinyInteger('action')->comment('Policy department action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_policy_department');
    }
};
