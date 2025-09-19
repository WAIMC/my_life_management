<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_mgmt_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_mgmt_id')->comment('CategoryMgmt mgmt hist id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent category');
            $table->string('name', 50)->nullable()->comment('CategoryMgmt name');
            $table->string('slug', 50)->nullable()->comment('CategoryMgmt slug');
            $table->string('description', 150)->nullable()->comment('CategoryMgmt description');
            $table->unsignedTinyInteger('status')->nullable()->comment('CategoryMgmt status');
            $table->boolean('is_display')->nullable()->comment('Display category');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('CategoryMgmt order');
            $table->unsignedTinyInteger('action')->comment('CategoryMgmt action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('category_mgmt_hist');
    }
};
