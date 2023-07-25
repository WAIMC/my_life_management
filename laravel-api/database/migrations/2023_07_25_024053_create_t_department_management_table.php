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
        Schema::create('t_department_management', function (Blueprint $table) {
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('policy_department_id');
            $table->primary(['department_id', 'policy_department_id']);
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('t_department');
            $table->foreign('policy_department_id')->references('id')->on('t_policy_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_department_management');
    }
};
