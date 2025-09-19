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
        Schema::create('feature_mst_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_mst_id')->comment('FeatureMst id');
            $table->string('name', 50)->nullable()->comment('FeatureMst name');
            $table->string('group_name', 50)->nullable()->comment('FeatureMst group name');
            $table->string('description', 100)->nullable()->comment('FeatureMst description');
            $table->tinyInteger('status')->nullable()->comment('FeatureMst status');
            $table->unsignedTinyInteger('action')->comment('FeatureMst action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_mst_hist');
    }
};
