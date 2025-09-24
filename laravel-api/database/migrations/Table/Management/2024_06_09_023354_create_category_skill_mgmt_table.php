<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_skill_mgmt', function (Blueprint $table) {
            $table->unsignedInteger('category_id')->comment('Category ID');
            $table->unsignedInteger('skill_id')->comment('Skill ID');
            $table->primary(['category_id', 'skill_id']);
            $table->timestamps();

            // $table->foreign('category_id')->references('id')->on('category_mgmt')->onDelete('cascade');
            // $table->foreign('skill_id')->references('id')->on('skill_mgmt')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_skill_mgmt');
    }
};
