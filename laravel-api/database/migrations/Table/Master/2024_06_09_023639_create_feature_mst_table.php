<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_mst', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('Feature name');
            $table->string('group_name', 50)->comment('Feature group name');
            $table->string('description', 100)->comment('Feature description');
            $table->tinyInteger('status')->default(0)->comment('Feature status');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('feature_mst');
    }
};
