<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_mgmt_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('social_mgmt_id')->comment('Social mgmt id');
            $table->string('name', 50)->nullable()->comment('Social name');
            $table->string('slug', 50)->nullable()->comment('Social slug');
            $table->string('link', 255)->nullable()->comment('Social link');
            $table->string('image', 100)->nullable()->comment('Social image name');
            $table->integer('status')->nullable()->comment('Social status');
            $table->boolean('is_display')->nullable()->default(false)->comment('Social display status');
            $table->integer('rank_order')->nullable()->default(0)->comment('Social rank order');
            $table->integer('action')->comment('Social action');
            $table->integer('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('social_mgmt_hist');
    }
};
