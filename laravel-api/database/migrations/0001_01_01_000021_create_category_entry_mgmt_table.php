<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_entry_mgmt', function (Blueprint $table) {
            // Composite primary key
            $table->unsignedInteger('category_mgmt_id')->comment('Category ID');
            $table->unsignedInteger('entry_mgmt_id')->comment('Entry ID');

            // Composite primary key
            $table->primary(['category_mgmt_id', 'entry_mgmt_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_entry_mgmt');
    }
};
