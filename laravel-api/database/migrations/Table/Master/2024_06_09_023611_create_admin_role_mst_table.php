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
        Schema::create('admin_role_mst', function (Blueprint $table) {
            // Forein key references to admin_mst and role_mst tables
            $table->unsignedInteger('admin_mst_id')->comment('Admin ID');
            $table->unsignedInteger('role_mst_id')->comment('Role ID');

            // Composite primary key
            $table->primary(['admin_mst_id', 'role_mst_id']);

            // Foreign key constraints
            $table->foreign('admin_mst_id')->references('id')->on('admin_mst');
            $table->foreign('role_mst_id')->references('id')->on('role_mst');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_role_mst');
    }
};
