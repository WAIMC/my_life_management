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
    protected ?GoogleDriveService $googleDrive = null;

    public function __construct(
        protected MediaFileInterface $mediaFile
    ) {
    }

    protected function getGoogleDriveService(): GoogleDriveService
    {
        if ($this->googleDrive === null) {
            $this->googleDrive = app(GoogleDriveService::class);
        }
        return $this->googleDrive;
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
            $driveResult = $this->getGoogleDriveService()->uploadFile($file, $folderPath);

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

            \Log::info('File uploaded successfully', [
                'id' => $id,
                'google_file_id' => $driveResult['file_id'],
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'folder_path' => $folderPath
            ]);

            return [
                'id' => $id,
                'google_file_id' => $driveResult['file_id'],
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'folder_path' => $folderPath,
            ];
        } catch (Exception $e) {
            \Log::error('MediaFileService::upload failed', [
                'error' => $e->getMessage(),
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'folder_path' => $folderPath,
                'admin_mst_id' => $adminMstId,
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
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
            $content = $this->getGoogleDriveService()->downloadFile($mediaFile->google_file_id);

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
            $this->getGoogleDriveService()->renameFile($mediaFile->google_file_id, $newName);

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
            $this->getGoogleDriveService()->moveFile($mediaFile->google_file_id, $newFolderPath);

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
                    $this->getGoogleDriveService()->deleteFile($mediaFile->google_file_id);
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

    /**
     * Create folder in Google Drive and store metadata
     *
     * @param array $payload
     * @return array
     */
    public function createFolder(array $payload): array
    {
        $name = $payload['name'];
        $folderPath = $payload['folder_path'] ?? '/';
        $adminMstId = $payload['admin_mst_id'];

        try {
            // Create folder in Google Drive
            $folderId = $this->getGoogleDriveService()->findOrCreateFolder(
                trim($folderPath, '/') . '/' . $name
            );

            // Store folder metadata in database
            $folderData = [
                'admin_mst_id' => $adminMstId,
                'google_file_id' => $folderId,
                'original_name' => $name,
                'extension' => '',
                'mime_type' => 'application/vnd.google-apps.folder',
                'size' => 0,
                'folder_path' => $folderPath,
                'is_public' => false,
                'metadata' => [],
                'status' => 1,
            ];

            $id = $this->mediaFile->executeStore($folderData);

            \Log::info('Folder created successfully', [
                'id' => $id,
                'google_file_id' => $folderId,
                'name' => $name,
                'folder_path' => $folderPath
            ]);

            return [
                'id' => $id,
                'google_file_id' => $folderId,
                'name' => $name,
                'folder_path' => $folderPath,
            ];
        } catch (Exception $e) {
            \Log::error('MediaFileService::createFolder failed', [
                'error' => $e->getMessage(),
                'folder_name' => $name,
                'folder_path' => $folderPath,
                'admin_mst_id' => $adminMstId,
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('Folder creation failed: ' . $e->getMessage());
        }
    }

    /**
     * List folders
     *
     * @param array $payload
     * @return JsonResource
     */
    public function listFolders(array $payload): JsonResource
    {
        $payload['mime_type'] = 'application/vnd.google-apps.folder';
        $list = $this->mediaFile->list($payload);
        return MediaFileResource::collection($list);
    }

    /**
     * Copy files to different folder
     *
     * @param array $payload
     * @return array
     */
    public function copyFiles(array $payload): array
    {
        $ids = $payload['ids'];
        $targetFolderPath = $payload['target_folder_path'];
        $adminMstId = $payload['admin_mst_id'];
        $copiedFiles = [];

        foreach ($ids as $id) {
            $mediaFile = $this->mediaFile->find($id);
            
            if (!$mediaFile) {
                continue;
            }

            try {
                // Get file content from Google Drive
                $content = $this->getGoogleDriveService()->downloadFile($mediaFile->google_file_id);
                
                // Create temporary file
                $tempFile = tmpfile();
                fwrite($tempFile, $content);
                $tempPath = stream_get_meta_data($tempFile)['uri'];
                
                // Create UploadedFile instance
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempPath,
                    $mediaFile->original_name,
                    $mediaFile->mime_type,
                    null,
                    true
                );

                // Upload to new location
                $driveResult = $this->getGoogleDriveService()->uploadFile($uploadedFile, $targetFolderPath);

                // Store new file metadata
                $newFileData = [
                    'admin_mst_id' => $adminMstId,
                    'google_file_id' => $driveResult['file_id'],
                    'original_name' => $mediaFile->original_name,
                    'extension' => $mediaFile->extension,
                    'mime_type' => $mediaFile->mime_type,
                    'size' => $mediaFile->size,
                    'folder_path' => $targetFolderPath,
                    'is_public' => $mediaFile->is_public,
                    'metadata' => [
                        'web_view_link' => $driveResult['web_view_link'] ?? null,
                        'web_content_link' => $driveResult['web_content_link'] ?? null,
                    ],
                    'status' => 1,
                ];

                $newId = $this->mediaFile->executeStore($newFileData);
                $copiedFiles[] = $newId;

                // Clean up temp file
                fclose($tempFile);
            } catch (Exception $e) {
                \Log::error('Failed to copy file: ' . $e->getMessage());
                continue;
            }
        }

        return [
            'copied_count' => count($copiedFiles),
            'copied_ids' => $copiedFiles,
        ];
    }
}
