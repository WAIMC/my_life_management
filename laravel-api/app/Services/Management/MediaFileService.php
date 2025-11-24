<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\MediaFileInterface;
use App\Services\Custom\GoogleDriveService;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\MediaFileResource;
use Illuminate\Http\UploadedFile;
use Exception;

class MediaFileService extends BaseService
{
    public function __construct(
        protected MediaFileInterface $mediaFile,
        protected GoogleDriveService $googleDrive
    ) {
    }

    protected function getHistoryRepository()
    {
        return null; // No history tracking for media files
    }

    protected function getHistoryForeignKey(): string
    {
        return '';
    }

    /**
     * Get media files list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->mediaFile->list($payload);
        return MediaFileResource::collection($list);
    }

    /**
     * Upload file to Google Drive and store metadata
     *
     * @param array $payload
     * @return array
     */
    public function upload(array $payload): array
    {
        /** @var UploadedFile $file */
        $file = $payload['file'];
        $adminMstId = $payload['admin_mst_id'];
        
        // Determine folder path based on file type and date
        $folderPath = $this->generateFolderPath($file);

        try {
            // Upload to Google Drive
            $driveResult = $this->googleDrive->uploadFile($file, $folderPath);

            // Store metadata in database
            $mediaFileData = [
                'admin_mst_id' => $adminMstId,
                'google_file_id' => $driveResult['file_id'],
                'original_name' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'folder_path' => $folderPath,
                'is_public' => $payload['is_public'] ?? false,
                'metadata' => [
                    'web_view_link' => $driveResult['web_view_link'] ?? null,
                    'web_content_link' => $driveResult['web_content_link'] ?? null,
                ],
                'status' => 1,
            ];

            $id = $this->mediaFile->executeStore($mediaFileData);

            return [
                'id' => $id,
                'google_file_id' => $driveResult['file_id'],
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'folder_path' => $folderPath,
            ];
        } catch (Exception $e) {
            throw new Exception('File upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Get file metadata
     *
     * @param int $id
     * @return array
     */
    public function getFile(int $id): array
    {
        $mediaFile = $this->mediaFile->find($id);
        
        if (!$mediaFile) {
            throw new Exception('File not found');
        }

        return [
            'id' => $mediaFile->id,
            'google_file_id' => $mediaFile->google_file_id,
            'original_name' => $mediaFile->original_name,
            'extension' => $mediaFile->extension,
            'mime_type' => $mediaFile->mime_type,
            'size' => $mediaFile->size,
            'human_size' => $mediaFile->human_size,
            'folder_path' => $mediaFile->folder_path,
            'is_public' => $mediaFile->is_public,
            'metadata' => $mediaFile->metadata,
            'created_at' => $mediaFile->created_at,
            'updated_at' => $mediaFile->updated_at,
        ];
    }

    /**
     * Download file content from Google Drive
     *
     * @param int $id
     * @return array ['content' => string, 'mime_type' => string, 'filename' => string]
     */
    public function downloadFile(int $id): array
    {
        $mediaFile = $this->mediaFile->find($id);
        
        if (!$mediaFile) {
            throw new Exception('File not found');
        }

        try {
            $content = $this->googleDrive->downloadFile($mediaFile->google_file_id);

            return [
                'content' => $content,
                'mime_type' => $mediaFile->mime_type,
                'filename' => $mediaFile->original_name,
            ];
        } catch (Exception $e) {
            throw new Exception('File download failed: ' . $e->getMessage());
        }
    }

    /**
     * Rename file
     *
     * @param array $payload
     * @return int
     */
    public function rename(array $payload): int
    {
        $id = $payload['id'];
        $newName = $payload['new_name'];

        $mediaFile = $this->mediaFile->find($id);
        
        if (!$mediaFile) {
            throw new Exception('File not found');
        }

        try {
            // Rename in Google Drive
            $this->googleDrive->renameFile($mediaFile->google_file_id, $newName);

            // Update database
            return $this->mediaFile->executeUpdate([
                'id' => $id,
                'original_name' => $newName,
            ]);
        } catch (Exception $e) {
            throw new Exception('File rename failed: ' . $e->getMessage());
        }
    }

    /**
     * Move file to different folder
     *
     * @param array $payload
     * @return int
     */
    public function move(array $payload): int
    {
        $id = $payload['id'];
        $newFolderPath = $payload['new_folder_path'];

        $mediaFile = $this->mediaFile->find($id);
        
        if (!$mediaFile) {
            throw new Exception('File not found');
        }

        try {
            // Move in Google Drive
            $this->googleDrive->moveFile($mediaFile->google_file_id, $newFolderPath);

            // Update database
            return $this->mediaFile->executeUpdate([
                'id' => $id,
                'folder_path' => $newFolderPath,
            ]);
        } catch (Exception $e) {
            throw new Exception('File move failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete file
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $ids = is_array($payload['ids']) ? $payload['ids'] : [$payload['ids']];

        foreach ($ids as $id) {
            $mediaFile = $this->mediaFile->find($id);
            
            if ($mediaFile) {
                try {
                    // Delete from Google Drive
                    $this->googleDrive->deleteFile($mediaFile->google_file_id);
                } catch (Exception $e) {
                    // Log error but continue with database deletion
                    \Log::error('Failed to delete file from Google Drive: ' . $e->getMessage());
                }
            }
        }

        // Soft delete from database
        $this->mediaFile->executeDelete($ids);
    }

    /**
     * Generate folder path based on file type and current date
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFolderPath(UploadedFile $file): string
    {
        $mimeType = $file->getMimeType();
        $year = date('Y');
        $month = date('m');

        // Determine base folder by MIME type
        if (str_starts_with($mimeType, 'image/')) {
            $baseFolder = 'images';
        } elseif (str_starts_with($mimeType, 'video/')) {
            $baseFolder = 'videos';
        } else {
            $baseFolder = 'documents';
        }

        return "{$baseFolder}/{$year}/{$month}";
    }
}
