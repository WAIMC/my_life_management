<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_skill_mgmt', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('skill_id');
            $table->primary(['category_id', 'skill_id']);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('category_skill_mgmt');
    }
};
