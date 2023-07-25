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
        Schema::create('t_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 30)->unique()->comment('User email');
            $table->string('user_name', 50)->unique()->comment('User name');
            $table->string('password', 100)->comment('User password');
            $table->string('first_name', 20)->comment('First name');
            $table->string('last_name', 20)->comment('Last name');
            $table->string('address', 50)->nullable()->comment('User address');
            $table->string('phone_number', 20)->nullable()->comment('User phone number');
            $table->timestamp('birth')->nullable()->comment('User birth');
            $table->unsignedTinyInteger('gender')->default(0)->comment('User gender');
            $table->unsignedTinyInteger('status')->default(0)->comment('User status');
            $table->boolean('is_active')->default(0)->comment('User active');
            $table->string('avatar', 30)->nullable()->comment('User avatar name');
            $table->timestamp('email_verified_at')->nullable()->comment('Verified email time');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_user');
    }
};
