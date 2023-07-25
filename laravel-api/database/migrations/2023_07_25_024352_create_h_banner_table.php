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
        Schema::create('h_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('banner_id')->comment('Banner id');
            $table->string('title', 50)->nullable()->comment('Banner title');
            $table->string('slug', 50)->nullable()->comment('Banner slug');
            $table->string('description', 255)->nullable()->comment('Banner description');
            $table->string('link', 100)->nullable()->comment('Banner path image');
            $table->string('image', 100)->nullable()->comment('Banner image name');
            $table->string('position', 50)->nullable()->comment('Banner display position');
            $table->unsignedTinyInteger('status')->nullable()->comment('Banner status');
            $table->unsignedTinyInteger('action')->comment('Banner action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_banner');
    }
};
