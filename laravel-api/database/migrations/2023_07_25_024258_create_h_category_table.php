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
        Schema::create('h_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->comment('Category id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent category');
            $table->string('name', 50)->nullable()->comment('Category name');
            $table->string('slug', 50)->nullable()->comment('Category slug');
            $table->string('description', 150)->nullable()->comment('Category description');
            $table->unsignedTinyInteger('status')->nullable()->comment('Category status');
            $table->boolean('is_display')->nullable()->comment('Display category');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('Category order');
            $table->unsignedTinyInteger('action')->comment('Category action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_category');
    }
};
