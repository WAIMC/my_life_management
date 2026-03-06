<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('entry_description_mgmt_hist', function (Blueprint $table) {
      $table->increments('id')->comment('Entry description id');
      $table->unsignedInteger('entry_description_mgmt_id')->comment('Entry description management id');
      $table->unsignedInteger('parent_id')->nullable()->comment('Parent entry description');
      $table->string('title', 100)->nullable()->comment('Title');
      $table->string('summary', 255)->nullable()->comment('Summary');
      $table->text('article')->nullable()->comment('Article');
      $table->unsignedTinyInteger('status')->nullable()->comment('management status');
      $table->boolean('is_display')->nullable()->comment('Display');
      $table->unsignedSmallInteger('rank_order')->nullable()->comment('management order');
      $table->unsignedInteger('entry_mgmt_id')->nullable()->comment('Entry management id');
      $table->unsignedTinyInteger('action')->comment('management action');
      $table->unsignedInteger('author_id')->comment('Author id');
      $table->timestamp('created_at')->comment('Created time');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('entry_description_mgmt_hist');
  }
};
