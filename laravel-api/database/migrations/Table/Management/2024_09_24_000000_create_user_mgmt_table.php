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
        Schema::create('user_mgmt', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('role_id');
            $table->integer('department_id');
            $table->string('email', 30);
            $table->string('user_name', 50);
            $table->string('password', 100);
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('address', 100)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('birth')->nullable();
            $table->integer('gender');
            $table->integer('status');
            $table->boolean('is_active');
            $table->string('avatar', 30)->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();

            //$table->foreign('role_id')->references('id')->on('role_mst');
            //$table->foreign('department_id')->references('id')->on('department_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mgmt');
    }
};
