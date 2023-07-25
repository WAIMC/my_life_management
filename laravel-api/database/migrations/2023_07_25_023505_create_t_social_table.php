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
        Schema::create('t_social', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->comment('Social name');
            $table->string('url', 100)->comment('Social url');
            $table->string('icon', 30)->comment('Social icon name');
            $table->string('description', 100)->comment('Social description');
            $table->unsignedTinyInteger('status')->default(0)->comment('Social status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_social');
    }
};
