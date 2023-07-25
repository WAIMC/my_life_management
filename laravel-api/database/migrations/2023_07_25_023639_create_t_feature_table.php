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
        Schema::create('t_feature', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('Feature name');
            $table->unsignedTinyInteger('group')->default(0)->comment('Feature group');
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
