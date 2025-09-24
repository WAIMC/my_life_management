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
            $table->increments('id')->comment('Feature history id');
            $table->integer('feature_mst_id')->comment('FeatureMst id');
            $table->string('name', 50)->nullable()->comment('name');
            $table->string('group_name', 50)->nullable()->comment('group name');
            $table->string('description', 100)->nullable()->comment('description');
            $table->integer('status')->nullable()->comment('status');
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