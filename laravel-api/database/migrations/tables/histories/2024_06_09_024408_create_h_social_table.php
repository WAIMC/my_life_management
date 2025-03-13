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
        Schema::create('h_social', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('social_id')->comment('Social id');
            $table->string('name', 30)->nullable()->comment('Social name');
            $table->string('url', 100)->nullable()->comment('Social url');
            $table->string('icon', 30)->nullable()->comment('Social icon name');
            $table->string('description', 100)->nullable()->comment('Social description');
            $table->unsignedTinyInteger('status')->nullable()->comment('Social status');
            $table->unsignedTinyInteger('action')->comment('Social action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_social');
    }
};
