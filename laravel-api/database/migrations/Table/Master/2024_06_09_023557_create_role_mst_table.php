<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_mst', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->unique()->comment('RoleMst name');
            $table->string('permission', 50)->comment('RoleMst description');
            $table->boolean('is_active')->default(false)->comment('RoleMst active');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('role_mst');
    }
};
