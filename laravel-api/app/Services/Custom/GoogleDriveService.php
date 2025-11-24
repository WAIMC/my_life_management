<?php

namespace App\Services\Custom;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleDriveService
{
    protected GoogleClient $client;
    protected GoogleDrive $service;
    protected string $rootFolderId;

    public function __construct()
    {
        $this->initializeClient();
        $this->rootFolderId = config('services.google_drive.root_folder_id');
    }

    /**
     * Initialize Google Drive client
     */
    protected function initializeClient(): void
    {
        $this->client = new GoogleClient();
        
        // Try to load credentials from database first
        $dbConfig = $this->getActiveConfigFromDB();
        
        if ($dbConfig) {
            // Use credentials from database
            $this->client->setAuthConfig($dbConfig->credentials_array);
            $this->rootFolderId = $dbConfig->root_folder_id;
            Log::info('Using Google Drive credentials from database', ['config_id' => $dbConfig->id]);
        } else {
            // Fall back to file-based configuration
            $credentialsPath = config('services.google_drive.credentials_path');
            
            if (!file_exists($credentialsPath)) {
                throw new Exception("Google Drive credentials not found. Please upload credentials via admin panel or configure file at: {$credentialsPath}");
            }

            $this->client->setAuthConfig($credentialsPath);
            $this->rootFolderId = config('services.google_drive.root_folder_id');
            Log::info('Using Google Drive credentials from file');
        }

        $this->client->addScope(GoogleDrive::DRIVE_FILE);
        $this->service = new GoogleDrive($this->client);
    }

    /**
     * Get active configuration from database
     */
    protected function getActiveConfigFromDB()
    {
        return \Cache::remember('google_drive_active_config', 3600, function () {
            return \App\Models\Management\GoogleDriveConfig::active()->first();
        });
    }

    /**
     * Upload file to Google Drive
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folderPath
     * @return array ['file_id' => string, 'name' => string, 'size' => int]
     */
    public function uploadFile($file, string $folderPath = ''): array
    {
        try {
            // Find or create folder
            $folderId = $this->findOrCreateFolder($folderPath);

            // Create file metadata
            $fileMetadata = new DriveFile([
                'name' => $file->getClientOriginalName(),
                'parents' => [$folderId]
            ]);

            // Upload file
            $content = file_get_contents($file->getRealPath());
            $driveFile = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id, name, size, mimeType, webViewLink, webContentLink'
            ]);

            return [
                'file_id' => $driveFile->getId(),
                'name' => $driveFile->getName(),
                'size' => $driveFile->getSize(),
                'mime_type' => $driveFile->getMimeType(),
                'web_view_link' => $driveFile->getWebViewLink(),
                'web_content_link' => $driveFile->getWebContentLink(),
            ];
        } catch (Exception $e) {
            Log::error('Google Drive upload failed: ' . $e->getMessage());
            throw new Exception('Failed to upload file to Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Get file metadata
     *
     * @param string $fileId
     * @return DriveFile
     */
    public function getFile(string $fileId): DriveFile
    {
        try {
            return $this->service->files->get($fileId, [
                'fields' => 'id, name, size, mimeType, webViewLink, webContentLink, createdTime, modifiedTime'
            ]);
        } catch (Exception $e) {
            Log::error('Google Drive get file failed: ' . $e->getMessage());
            throw new Exception('Failed to get file from Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Download file content
     *
     * @param string $fileId
     * @return string File content
     */
    public function downloadFile(string $fileId): string
    {
        try {
            $response = $this->service->files->get($fileId, [
                'alt' => 'media'
            ]);

            return $response->getBody()->getContents();
        } catch (Exception $e) {
            Log::error('Google Drive download failed: ' . $e->getMessage());
            throw new Exception('Failed to download file from Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Delete file from Google Drive
     *
     * @param string $fileId
     * @return bool
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->service->files->delete($fileId);
            return true;
        } catch (Exception $e) {
            Log::error('Google Drive delete failed: ' . $e->getMessage());
            throw new Exception('Failed to delete file from Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Rename file
     *
     * @param string $fileId
     * @param string $newName
     * @return DriveFile
     */
    public function renameFile(string $fileId, string $newName): DriveFile
    {
        try {
            $fileMetadata = new DriveFile([
                'name' => $newName
            ]);

            return $this->service->files->update($fileId, $fileMetadata, [
                'fields' => 'id, name'
            ]);
        } catch (Exception $e) {
            Log::error('Google Drive rename failed: ' . $e->getMessage());
            throw new Exception('Failed to rename file in Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Move file to different folder
     *
     * @param string $fileId
     * @param string $newFolderPath
     * @return DriveFile
     */
    public function moveFile(string $fileId, string $newFolderPath): DriveFile
    {
        try {
            // Get current parents
            $file = $this->service->files->get($fileId, ['fields' => 'parents']);
            $previousParents = join(',', $file->getParents());

            // Find or create new folder
            $newFolderId = $this->findOrCreateFolder($newFolderPath);

            // Move file
            return $this->service->files->update($fileId, new DriveFile(), [
                'addParents' => $newFolderId,
                'removeParents' => $previousParents,
                'fields' => 'id, parents'
            ]);
        } catch (Exception $e) {
            Log::error('Google Drive move failed: ' . $e->getMessage());
            throw new Exception('Failed to move file in Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Create folder in Google Drive
     *
     * @param string $name
     * @param string $parentId
     * @return string Folder ID
     */
    public function createFolder(string $name, string $parentId): string
    {
        try {
            $fileMetadata = new DriveFile([
                'name' => $name,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentId]
            ]);

            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id'
            ]);

            return $folder->getId();
        } catch (Exception $e) {
            Log::error('Google Drive create folder failed: ' . $e->getMessage());
            throw new Exception('Failed to create folder in Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Find or create folder by path
     *
     * @param string $path e.g., "images/2025/11"
     * @return string Folder ID
     */
    public function findOrCreateFolder(string $path): string
    {
        if (empty($path)) {
            return $this->rootFolderId;
        }

        $folders = explode('/', trim($path, '/'));
        $currentParentId = $this->rootFolderId;

        foreach ($folders as $folderName) {
            $folderId = $this->findFolder($folderName, $currentParentId);
            
            if (!$folderId) {
                $folderId = $this->createFolder($folderName, $currentParentId);
            }

            $currentParentId = $folderId;
        }

        return $currentParentId;
    }

    /**
     * Find folder by name and parent
     *
     * @param string $name
     * @param string $parentId
     * @return string|null Folder ID or null if not found
     */
    protected function findFolder(string $name, string $parentId): ?string
    {
        try {
            $response = $this->service->files->listFiles([
                'q' => "name='{$name}' and '{$parentId}' in parents and mimeType='application/vnd.google-apps.folder' and trashed=false",
                'fields' => 'files(id, name)',
                'pageSize' => 1
            ]);

            $files = $response->getFiles();
            return !empty($files) ? $files[0]->getId() : null;
        } catch (Exception $e) {
            Log::error('Google Drive find folder failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * List files in a folder
     *
     * @param string $folderId
     * @param int $pageSize
     * @param string|null $pageToken
     * @return array
     */
    public function listFiles(string $folderId = null, int $pageSize = 100, ?string $pageToken = null): array
    {
        try {
            $folderId = $folderId ?? $this->rootFolderId;
            
            $params = [
                'q' => "'{$folderId}' in parents and trashed=false",
                'fields' => 'nextPageToken, files(id, name, size, mimeType, createdTime, modifiedTime)',
                'pageSize' => $pageSize,
                'orderBy' => 'createdTime desc'
            ];

            if ($pageToken) {
                $params['pageToken'] = $pageToken;
            }

            $response = $this->service->files->listFiles($params);

            return [
                'files' => $response->getFiles(),
                'nextPageToken' => $response->getNextPageToken()
            ];
        } catch (Exception $e) {
            Log::error('Google Drive list files failed: ' . $e->getMessage());
            throw new Exception('Failed to list files from Google Drive: ' . $e->getMessage());
        }
    }
}
