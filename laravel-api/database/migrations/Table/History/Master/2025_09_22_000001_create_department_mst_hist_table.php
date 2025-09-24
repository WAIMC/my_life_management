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
            $table->increments('id')->comment('Department history id');
            $table->integer('department_mst_id')->comment('Department id');
            $table->string('code', 50)->nullable()->comment('code');
            $table->string('name', 50)->nullable()->comment('name');
            $table->integer('status')->nullable()->comment('status');
            $table->integer('action')->comment('Action');
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
