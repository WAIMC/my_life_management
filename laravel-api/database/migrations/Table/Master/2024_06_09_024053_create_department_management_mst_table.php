<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department_management_mst', function (Blueprint $table) {
            $table->unsignedInteger('department_id')->comment('Department ID');
            $table->unsignedInteger('policy_department_id')->comment('Policy Department ID');
            $table->primary(['department_id', 'policy_department_id']);
            $table->timestamps();

            // $table->foreign('department_id')->references('id')->on('department_mst');
            // $table->foreign('policy_department_id')->references('id')->on('policy_department_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_management_mst');
    }
};
