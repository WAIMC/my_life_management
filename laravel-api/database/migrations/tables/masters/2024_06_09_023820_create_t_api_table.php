<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('t_api', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedTinyInteger('type')->default(0)->comment('Api type');
      $table->string('name', 50)->comment('Api name');
      $table->string('path', 100)->comment('Api path');
      $table->boolean('is_valid')->default(false)->comment('Api valid');
      $table->unsignedInteger('feature_id')->comment('Feature ID');
      $table->timestamps();
      $table->foreign('feature_id')->references('id')->on('t_feature');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('t_api');
  }
};
