<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentMstHistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('department_mst_hist', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('department_mst_id');
            $table->string('code', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->integer('status')->nullable();
            $table->integer('action');
            $table->integer('author_id');
            $table->string('created_at');

//            $table->foreign('department_mst_id')
//                ->references('id')
//                ->on('department_mst')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('department_mst_hist');
    }
}
