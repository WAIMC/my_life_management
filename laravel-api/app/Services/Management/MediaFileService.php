<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\MediaFileInterface;
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
     * Upload file (placeholder - Google Drive functionality removed)
     *
     * @param array $payload
     * @return array
     */
    public function upload(array $payload): array
    {
        throw new Exception('File upload functionality has been removed. Google Drive integration is no longer available.');
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
     * Download file (placeholder - Google Drive functionality removed)
     *
     * @param int $id
     * @return array ['content' => string, 'mime_type' => string, 'filename' => string]
     */
    public function downloadFile(int $id): array
    {
        throw new Exception('File download functionality has been removed. Google Drive integration is no longer available.');
    }

    /**
     * Rename file
     *
     * @param array $payload
     * @return int
     */
    public function rename(array $payload): int
    {
        throw new Exception('File rename functionality has been removed. Google Drive integration is no longer available.');
    }

    /**
     * Move file to different folder
     *
     * @param array $payload
     * @return int
     */
    public function move(array $payload): int
    {
        throw new Exception('File move functionality has been removed. Google Drive integration is no longer available.');
    }

    /**
     * Delete file
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        throw new Exception('File delete functionality has been removed. Google Drive integration is no longer available.');
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
