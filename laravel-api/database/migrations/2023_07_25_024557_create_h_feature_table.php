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
        Schema::create('h_feature', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_id')->comment('Feature id');
            $table->string('name', 50)->nullable()->comment('Feature name');
            $table->unsignedTinyInteger('group')->nullable()->comment('Feature group');
            $table->unsignedTinyInteger('action')->comment('Feature action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_feature');
    }
};