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
        Schema::create('t_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->comment('Role name');
            $table->string('permission', 50)->comment('Role description');
            $table->boolean('is_active')->default(false)->comment('Role active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_role');
    }
};
