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
        Schema::create('t_api_feature', function (Blueprint $table) {
            $table->unsignedInteger('api_id');
            $table->unsignedInteger('feature_id');
            $table->unsignedInteger('role_id');
            $table->primary(['api_id', 'feature_id', 'role_id']);
            $table->timestamps();
            $table->foreign('api_id')->references('id')->on('t_api');
            $table->foreign('feature_id')->references('id')->on('t_feature');
            $table->foreign('role_id')->references('id')->on('t_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_api_feature');
    }
};
