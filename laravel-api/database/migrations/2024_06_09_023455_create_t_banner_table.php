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
        Schema::create('t_banner', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->comment('Banner title');
            $table->string('slug', 50)->comment('Banner slug');
            $table->string('description', 255)->comment('Banner description');
            $table->string('link', 100)->comment('Banner path image');
            $table->string('image', 100)->comment('Banner image name');
            $table->string('position', 50)->comment('Banner display position');
            $table->unsignedTinyInteger('status')->default(0)->comment('Banner status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_banner');
    }
};
