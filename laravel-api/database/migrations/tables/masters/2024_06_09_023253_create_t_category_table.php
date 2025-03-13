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
        Schema::create('t_category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0)->comment('Parent category');
            $table->string('name', 50)->comment('Category name');
            $table->string('slug', 50)->comment('Category slug');
            $table->string('description', 150)->nullable()->comment('Category description');
            $table->unsignedTinyInteger('status')->default(0)->comment('Category status');
            $table->boolean('is_display')->default(false)->comment('Display category');
            $table->unsignedSmallInteger('rank_order')->default(0)->comment('Category order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_category');
    }
};
