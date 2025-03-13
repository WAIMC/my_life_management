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
        Schema::create('h_api', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('api_id')->comment('Api id');
            $table->unsignedTinyInteger('type')->nullable()->comment('Api type');
            $table->string('name', 50)->nullable()->comment('Api name');
            $table->string('path', 100)->nullable()->comment('Api path');
            $table->unsignedTinyInteger('is_active')->nullable()->comment('Api status');
            $table->unsignedInteger('feature_id')->comment('Feature id');
            $table->unsignedTinyInteger('action')->comment('Api action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_api');
    }
};
