<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_skill_mgmt', function (Blueprint $table) {
            // Composite primary key
            $table->unsignedInteger('category_mgmt_id')->comment('Category ID');
            $table->unsignedInteger('skill_mgmt_id')->comment('Skill ID');

            // Composite primary key
            $table->primary(['category_mgmt_id', 'skill_mgmt_id']);

            // Foreign key constraints
            $table->foreign('category_mgmt_id')->references('id')->on('category_mgmt');
            $table->foreign('skill_mgmt_id')->references('id')->on('skill_mgmt');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_skill_mgmt');
    }
};
