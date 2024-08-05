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
    Schema::create('t_feature', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name', 50)->comment('Feature name');
      $table->string('group', 50)->comment('Feature group');
      $table->string('description', 100)->comment('Feature description');
      $table->tinyInteger('status')->default(0)->comment('Feature status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('t_feature');
  }
};
