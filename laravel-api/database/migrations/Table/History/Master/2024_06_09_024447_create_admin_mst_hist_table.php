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
        Schema::create('admin_mst_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_mst_id')->comment('AdminMst id');
            $table->string('email', 30)->nullable()->comment('AdminMst email');
            $table->string('user_name', 50)->nullable()->comment('AdminMst user name');
            $table->string('password', 100)->nullable()->comment('AdminMst password');
            $table->string('first_name', 20)->nullable()->comment('AdminMst first name');
            $table->string('last_name', 20)->nullable()->comment('AdminMst last name');
            $table->string('address', 100)->nullable()->comment('AdminMst address');
            $table->string('phone_number', 20)->nullable()->comment('AdminMst phone number');
            $table->timestamp('birth')->nullable()->comment('AdminMst birth');
            $table->unsignedTinyInteger('gender')->nullable()->comment('AdminMst gender');
            $table->unsignedTinyInteger('status')->nullable()->comment('AdminMst status');
            $table->boolean('is_active')->nullable()->comment('AdminMst active');
            $table->string('avatar', 30)->nullable()->comment('AdminMst avatar name');
            $table->timestamp('email_verified_at')->nullable()->comment('Verified email time');
            $table->string('remember_token', 100)->nullable()->comment('AdminMst remember token');
            $table->unsignedTinyInteger('action')->comment('AdminMst action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');

            // $table->foreign('admin_mst_id')->references('id')->on('admin_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_mst_hist');
    }
};
