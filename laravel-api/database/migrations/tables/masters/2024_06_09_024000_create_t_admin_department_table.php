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
        Schema::create('t_admin_department', function (Blueprint $table) {
            $table->unsignedInteger('admin_id');
            $table->unsignedInteger('department_id');
            $table->primary(['admin_id', 'department_id']);
            $table->timestamps();
            $table->foreign('admin_id')->references('id')->on('t_admin');
            $table->foreign('department_id')->references('id')->on('t_department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_admin_department');
    }
};
