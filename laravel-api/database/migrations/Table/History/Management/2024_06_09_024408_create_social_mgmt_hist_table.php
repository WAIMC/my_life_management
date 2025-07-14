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
            $table->unsignedInteger('social_mgmt_id')->comment('Social mgmt hist id');
            $table->string('name', 30)->nullable()->comment('Social name');
            $table->string('url', 100)->nullable()->comment('Social url');
            $table->string('icon', 30)->nullable()->comment('Social icon name');
            $table->string('description', 100)->nullable()->comment('Social description');
            $table->unsignedTinyInteger('status')->nullable()->comment('Social status');
            $table->unsignedTinyInteger('action')->comment('Social action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('social_mgmt_hist');
    }
};
