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
        Schema::create('t_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 30)->unique()->comment('Admin email');
            $table->string('user_name', 50)->unique()->comment('Admin user name');
            $table->string('password', 100)->comment('Admin password');
            $table->string('first_name', 20)->comment('Admin first name');
            $table->string('last_name', 20)->comment('Admin last name');
            $table->string('address', 100)->nullable()->comment('Admin address');
            $table->string('phone_number', 20)->nullable()->comment('Admin phone number');
            $table->timestamp('birth')->nullable()->comment('Admin birth');
            $table->unsignedTinyInteger('gender')->default(0)->comment('Admin gender');
            $table->unsignedTinyInteger('status')->default(0)->comment('Admin status');
            $table->boolean('is_active')->default(false)->comment('Admin active');
            $table->string('avatar', 30)->nullable()->comment('Admin avatar name');
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
        Schema::dropIfExists('t_admin');
    }
};
