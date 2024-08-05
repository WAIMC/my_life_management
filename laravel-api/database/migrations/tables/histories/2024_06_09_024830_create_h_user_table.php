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
        Schema::create('h_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('User id');
            $table->string('email', 30)->nullable()->comment('User email');
            $table->string('user_name', 50)->nullable()->comment('User name');
            $table->string('password', 100)->nullable()->comment('User password');
            $table->string('first_name', 20)->nullable()->comment('First name');
            $table->string('last_name', 20)->nullable()->comment('Last name');
            $table->string('address', 50)->nullable()->comment('User address');
            $table->string('phone_number', 20)->nullable()->comment('User phone number');
            $table->timestamp('birth')->nullable()->comment('User birth');
            $table->unsignedTinyInteger('gender')->nullable()->comment('User gender');
            $table->unsignedTinyInteger('status')->nullable()->comment('User status');
            $table->boolean('is_active')->nullable()->comment('User active');
            $table->string('avatar', 30)->nullable()->comment('User avatar name');
            $table->timestamp('email_verified_at')->nullable()->comment('Verified email time');
            $table->string('remember_token', 100)->nullable()->comment('Remember token');
            $table->unsignedTinyInteger('action')->comment('User action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_user');
    }
};
