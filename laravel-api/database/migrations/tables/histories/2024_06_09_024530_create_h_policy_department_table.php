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
        Schema::create('h_policy_department', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('policy_department_id')->comment('Policy department id');
            $table->unsignedInteger('table_name')->nullable()->comment('Policy department');
            $table->unsignedInteger('row_id')->nullable()->comment('Row id');
            $table->unsignedTinyInteger('action')->comment('Policy department action');
            $table->unsignedInteger('author_id')->comment('Author id');
            $table->timestamp('created_at')->comment('Created time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_policy_department');
    }
};
