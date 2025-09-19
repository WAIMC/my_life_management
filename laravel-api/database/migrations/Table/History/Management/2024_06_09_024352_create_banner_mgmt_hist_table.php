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
        Schema::create('banner_mgmt_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('banner_mgmt_id')->comment('Banner id');
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

            //$table->foreign('banner_mgmt_id')->references('id')->on('banner_mgmt');
            //$table->foreign('author_id')->references('id')->on('admin_mst');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_mgmt_hist');
    }
};
