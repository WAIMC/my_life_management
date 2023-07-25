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
        Schema::create('h_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->comment('role_id');
            $table->string('name', 30)->nullable()->comment('Role name');
            $table->string('permission', 50)->nullable()->comment('Role description');
            $table->boolean('is_active')->nullable()->comment('Role active');
            $table->unsignedTinyInteger('action')->comment('Role action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_role');
    }
};
