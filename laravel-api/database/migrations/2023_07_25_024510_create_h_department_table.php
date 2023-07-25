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
        Schema::create('h_department', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_id')->comment('Department id');
            $table->string('code', 50)->nullable()->comment('Department code');
            $table->string('name', 50)->nullable()->comment('Department name');
            $table->unsignedTinyInteger('status')->nullable()->comment('Department status');
            $table->unsignedTinyInteger('action')->comment('Department action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_department');
    }
};
