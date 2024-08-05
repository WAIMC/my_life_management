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
        Schema::create('t_department', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->comment('Department code');
            $table->string('name', 50)->comment('Department name');
            $table->unsignedTinyInteger('status')->default(0)->comment('Department status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_department');
    }
};