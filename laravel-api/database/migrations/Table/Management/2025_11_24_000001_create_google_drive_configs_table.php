<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('google_drive_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('Configuration name');
            $table->text('credentials_json')->comment('Encrypted Google Drive credentials JSON');
            $table->string('root_folder_id', 255)->comment('Google Drive root folder ID');
            $table->boolean('is_active')->default(false)->comment('Is this the active configuration');
            $table->unsignedTinyInteger('status')->default(1)->comment('Configuration status');
            $table->boolean('is_delete')->default(false)->comment('Soft delete flag');
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('google_drive_configs');
    }
};
