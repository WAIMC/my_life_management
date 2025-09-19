<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department_mst_hist', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('department_mst_id')->comment('DepartmentMst id');
            $table->string('code', 50)->nullable()->comment('DepartmentMst code');
            $table->string('name', 50)->nullable()->comment('DepartmentMst name');
            $table->unsignedTinyInteger('status')->nullable()->comment('DepartmentMst status');
            $table->unsignedTinyInteger('action')->comment('DepartmentMst action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_mst_hist');
    }
};
