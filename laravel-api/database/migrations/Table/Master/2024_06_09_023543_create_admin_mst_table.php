<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_mst', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 30)->unique()->comment('AdminMst email');
            $table->string('user_name', 50)->unique()->comment('AdminMst user name');
            $table->string('password', 100)->comment('AdminMst password');
            $table->string('first_name', 20)->comment('AdminMst first name');
            $table->string('last_name', 20)->comment('AdminMst last name');
            $table->string('address', 100)->nullable()->comment('AdminMst address');
            $table->string('phone_number', 20)->nullable()->comment('AdminMst phone number');
            $table->timestamp('birth')->nullable()->comment('AdminMst birth');
            $table->unsignedTinyInteger('gender')->default(0)->comment('AdminMst gender');
            $table->unsignedTinyInteger('status')->default(0)->comment('AdminMst status');
            $table->boolean('is_active')->default(false)->comment('AdminMst active');
            $table->string('avatar', 30)->nullable()->comment('AdminMst avatar name');
            $table->timestamp('email_verified_at')->nullable()->comment('Verified email time');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('admin_mst');
    }
};
