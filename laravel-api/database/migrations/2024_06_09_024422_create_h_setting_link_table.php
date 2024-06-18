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
        Schema::create('h_setting_link', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('setting_link_id')->comment('Setting link id');
            $table->string('key', 30)->nullable()->comment('Setting link key');
            $table->string('value', 100)->nullable()->comment('Setting link value');
            $table->unsignedTinyInteger('action')->comment('Setting link action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_setting_link');
    }
};
