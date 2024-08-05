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
        Schema::create('h_admin', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_id')->comment('Admin id');
            $table->string('email', 30)->nullable()->comment('Admin email');
            $table->string('user_name', 50)->nullable()->comment('Admin user name');
            $table->string('password', 100)->nullable()->comment('Admin password');
            $table->string('first_name', 20)->nullable()->comment('Admin first name');
            $table->string('last_name', 20)->nullable()->comment('Admin last name');
            $table->string('address', 100)->nullable()->comment('Admin address');
            $table->string('phone_number', 20)->nullable()->comment('Admin phone number');
            $table->timestamp('birth')->nullable()->comment('Admin birth');
            $table->unsignedTinyInteger('gender')->nullable()->comment('Admin gender');
            $table->unsignedTinyInteger('status')->nullable()->comment('Admin status');
            $table->boolean('is_active')->nullable()->comment('Admin active');
            $table->string('avatar', 30)->nullable()->comment('Admin avatar name');
            $table->timestamp('email_verified_at')->nullable()->comment('Verified email time');
            $table->string('remember_token', 100)->nullable()->comment('Admin remember token');
            $table->unsignedTinyInteger('action')->comment('Admin action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_admin');
    }
};
