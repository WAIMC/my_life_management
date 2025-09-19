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
            $table->string('name', 50)->comment('FeatureMst name');
            $table->string('group_name', 50)->comment('FeatureMst group name');
            $table->string('description', 100)->comment('FeatureMst description');
            $table->tinyInteger('status')->default(0)->comment('FeatureMst status');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('feature_mst');
    }
};
