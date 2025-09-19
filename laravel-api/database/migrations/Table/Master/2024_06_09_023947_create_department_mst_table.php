<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_mst', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->comment('DepartmentMst code');
            $table->string('name', 50)->comment('DepartmentMst name');
            $table->unsignedTinyInteger('status')->default(0)->comment('DepartmentMst status');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('department_mst');
    }
};
