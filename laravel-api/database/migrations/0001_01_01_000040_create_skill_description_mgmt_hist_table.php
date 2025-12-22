<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('skill_description_mgmt_hist', function (Blueprint $table) {
      $table->increments('id')->comment('Skill description id');
      $table->unsignedInteger('skill_description_mgmt_id')->comment('Skill description management id');
      $table->unsignedInteger('parent_id')->nullable()->comment('Parent skill description');
      $table->string('title', 100)->nullable()->comment('Title');
      $table->string('summary', 255)->nullable()->comment('Summary');
      $table->text('article')->nullable()->comment('Article');
      $table->unsignedTinyInteger('status')->nullable()->comment('management status');
      $table->boolean('is_display')->nullable()->comment('Display');
      $table->unsignedSmallInteger('rank_order')->nullable()->comment('management order');
      $table->unsignedInteger('skill_mgmt_id')->nullable()->comment('Skill management id');
      $table->unsignedTinyInteger('action')->comment('management action');
      $table->unsignedInteger('author_id')->comment('Author id');
      $table->timestamp('created_at')->comment('Created time');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('skill_description_mgmt_hist');
  }
};
