<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureMstHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('feature_mst_hist', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('feature_mst_id');
            $table->string('name', 50)->nullable();
            $table->string('group_name', 50)->nullable();
            $table->string('description', 100)->nullable();
            $table->integer('status')->nullable();
            $table->integer('action');
            $table->integer('author_id');
            $table->string('created_at');

//            $table->foreign('feature_mst_id')
//                ->references('id')
//                ->on('feature_mst')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_mst_hist');
    }
}