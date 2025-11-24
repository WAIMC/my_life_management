<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('admin_mst_id')->comment('Admin user ID who uploaded the file');
            $table->string('google_file_id', 255)->unique()->comment('Google Drive file ID');
            $table->string('original_name', 255)->comment('Original filename');
            $table->string('extension', 20)->comment('File extension');
            $table->string('mime_type', 100)->comment('MIME type');
            $table->unsignedBigInteger('size')->comment('File size in bytes');
            $table->string('folder_path', 500)->nullable()->comment('Logical folder path for UI');
            $table->boolean('is_public')->default(false)->comment('Public access flag');
            $table->json('metadata')->nullable()->comment('Additional metadata');
            $table->unsignedTinyInteger('status')->default(1)->comment('File status');
            $table->boolean('is_delete')->default(false)->comment('Soft delete flag');
            $table->timestamps();

            // Indexes
            $table->index('admin_mst_id');
            $table->index('folder_path');
            $table->index('mime_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
