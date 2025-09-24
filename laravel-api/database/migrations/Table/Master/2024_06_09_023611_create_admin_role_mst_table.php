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
            $table->unsignedInteger('admin_id')->comment('Admin ID');
            $table->unsignedInteger('role_id')->comment('Role ID');
            $table->primary(['admin_id', 'role_id']);
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
