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
        Schema::create('t_slider', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->comment('Slider title');
            $table->string('slug', 50)->comment('Slider slug');
            $table->string('link', 100)->comment('Slider path image');
            $table->string('image', 100)->comment('Slider image name');
            $table->unsignedTinyInteger('status')->default(0)->comment('Slider image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_slider');
    }
};
