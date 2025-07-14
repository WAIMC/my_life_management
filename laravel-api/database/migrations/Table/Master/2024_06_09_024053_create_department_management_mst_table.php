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
        Schema::create('department_management_mst', function (Blueprint $table) {
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('policy_department_id');
            $table->primary(['department_id', 'policy_department_id']);
            $table->timestamps();
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
