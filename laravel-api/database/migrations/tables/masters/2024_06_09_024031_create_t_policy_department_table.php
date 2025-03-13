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
    Schema::create('t_policy_department', function (Blueprint $table) {
      $table->increments('id');
      $table->string('table_name', 20)->comment('Table name');
      $table->integer('row_id')->comment('Row id');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('t_policy_department');
  }
};
