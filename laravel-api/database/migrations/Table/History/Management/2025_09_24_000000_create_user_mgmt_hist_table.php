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
        Schema::create('user_mgmt_hist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_mgmt_id')->comment('User management ID');
            $table->unsignedBigInteger('role_id')->nullable()->comment('Role ID');
            $table->unsignedBigInteger('department_id')->nullable()->comment('Department ID');
            $table->string('email', 30)->nullable()->comment('User email');
            $table->string('user_name', 50)->nullable()->comment('User name');
            $table->string('password', 100)->nullable()->comment('User password');
            $table->string('first_name', 20)->nullable()->comment('User first name');
            $table->string('last_name', 20)->nullable()->comment('User last name');
            $table->string('address', 100)->nullable()->comment('User address');
            $table->string('phone_number', 20)->nullable()->comment('User phone number');
            $table->string('birth')->nullable()->comment('User birth');
            $table->integer('gender')->nullable()->comment('User gender');
            $table->integer('status')->nullable()->comment('User status');
            $table->boolean('is_active')->nullable()->default(false)->comment('User active status');
            $table->string('avatar', 30)->nullable()->comment('User avatar');
            $table->string('email_verified_at')->nullable()->comment('Email verified time');
            $table->string('remember_token', 100)->nullable()->comment('Remember token');
            $table->integer('action')->comment('Action type');
            $table->unsignedBigInteger('author_id')->comment('Author ID who made this action');
            $table->timestamp('created_at')->comment('Created time');

            //$table->foreign('user_mgmt_id')->references('id')->on('user_mgmt')->onDelete('cascade');
            //$table->foreign('role_id')->references('id')->on('role_mst')->onDelete('set null');
            //$table->foreign('department_id')->references('id')->on('department_mst')->onDelete('set null');
            //$table->foreign('author_id')->references('id')->on('admin_mst')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mgmt_hist');
    }
};
