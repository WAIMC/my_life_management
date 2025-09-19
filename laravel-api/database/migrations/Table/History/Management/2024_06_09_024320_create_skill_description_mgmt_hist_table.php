<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_description_mgmt_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('skill_description_mgmt_id')->comment('SkillMgmt description mgmt hist id');
            $table->unsignedInteger('parent_id')->nullable()->comment('Parent skill description');
            $table->string('title', 100)->nullable()->comment('Title skill description');
            $table->string('summary', 255)->nullable()->comment('Summary skill description');
            $table->text('article')->nullable()->comment('Article skill description');
            $table->unsignedTinyInteger('status')->nullable()->comment('SkillMgmt description status');
            $table->boolean('is_display')->nullable()->comment('Display skill description');
            $table->unsignedSmallInteger('rank_order')->nullable()->comment('SkillMgmt description order');
            $table->unsignedInteger('skill_id')->nullable()->comment('SkillMgmt id primary key');
            $table->unsignedTinyInteger('action')->comment('SkillMgmt description action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('skill_description_mgmt_hist');
    }
};
