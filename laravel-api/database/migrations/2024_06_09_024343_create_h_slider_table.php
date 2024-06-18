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
        Schema::create('h_slider', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('slider_id')->comment('Slider id');
            $table->string('title', 50)->nullable()->comment('Slider title');
            $table->string('slug', 50)->nullable()->comment('Slider slug');
            $table->string('link', 100)->nullable()->comment('Slider path image');
            $table->string('image', 100)->nullable()->comment('Slider image name');
            $table->unsignedTinyInteger('status')->nullable()->comment('Slider image');
            $table->unsignedTinyInteger('action')->comment('Slider action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_slider');
    }
};
