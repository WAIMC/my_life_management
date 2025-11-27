<?php

namespace App\Services\Custom;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Exceptions\GoogleDriveFileNotFoundException;
use App\Exceptions\GoogleDriveAuthException;
use App\Exceptions\GoogleDriveQuotaExceededException;
use App\Exceptions\GoogleDrivePermissionException;

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
            try {
                $this->client->setAuthConfig($dbConfig->credentials_array);
                $this->rootFolderId = $dbConfig->root_folder_id;
                
                // Validate root folder ID
                if (empty($this->rootFolderId) || $this->rootFolderId === 'your_google_drive_root_folder_id_here') {
                    Log::error('Google Drive root folder ID is not configured in database', [
                        'config_id' => $dbConfig->id,
                        'root_folder_id' => $this->rootFolderId
                    ]);
                    throw new Exception(
                        "Google Drive root folder ID is not configured. Please set a valid root folder ID in the Google Drive configuration (current value: '{$this->rootFolderId}'). " .
                        "You can find your folder ID in the Google Drive URL when viewing the folder."
                    );
                }
                
                Log::info('Using Google Drive credentials from database', ['config_id' => $dbConfig->id]);
            } catch (\Exception $e) {
                Log::error('Failed to initialize Google Drive with database credentials', [
                    'error' => $e->getMessage(),
                    'config_id' => $dbConfig->id
                ]);
                throw new Exception("Failed to initialize Google Drive with database credentials: " . $e->getMessage());
            }
        } else {
            // Fall back to file-based configuration
            $credentialsPath = config('services.google_drive.credentials_path');
            
            if (!file_exists($credentialsPath)) {
                Log::error('Google Drive credentials file not found', [
                    'expected_path' => $credentialsPath,
                    'absolute_path' => base_path($credentialsPath)
                ]);
                throw new Exception(
                    "Google Drive credentials not found. Please either:\n" .
                    "1. Upload credentials via Admin Panel > Google Drive Configuration, OR\n" .
                    "2. Place your credentials JSON file at: {$credentialsPath}\n" .
                    "You can download credentials from Google Cloud Console."
                );
            }

            try {
                $this->client->setAuthConfig($credentialsPath);
            } catch (\Exception $e) {
                Log::error('Invalid Google Drive credentials file', [
                    'path' => $credentialsPath,
                    'error' => $e->getMessage()
                ]);
                throw new Exception("Invalid Google Drive credentials file at {$credentialsPath}: " . $e->getMessage());
            }
            
            $this->rootFolderId = config('services.google_drive.root_folder_id');
            
            // Validate root folder ID
            if (empty($this->rootFolderId) || $this->rootFolderId === 'your_google_drive_root_folder_id_here') {
                Log::error('Google Drive root folder ID is not configured in .env', [
                    'current_value' => $this->rootFolderId
                ]);
                throw new Exception(
                    "Google Drive root folder ID is not configured. Please set GOOGLE_DRIVE_ROOT_FOLDER_ID in your .env file. " .
                    "Current value: '{$this->rootFolderId}'. " .
                    "You can find your folder ID in the Google Drive URL when viewing the folder (e.g., https://drive.google.com/drive/folders/YOUR_FOLDER_ID)."
                );
            }
            
            Log::info('Using Google Drive credentials from file', ['credentials_path' => $credentialsPath]);
        }

        $this->client->addScope(GoogleDrive::DRIVE_FILE);
        $this->service = new GoogleDrive($this->client);
        
        Log::info('Google Drive client initialized successfully', [
            'root_folder_id' => $this->rootFolderId
        ]);
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, '', 'upload');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $fileId, 'get');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $fileId, 'download');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $fileId, 'delete');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $fileId, 'rename');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $fileId, 'move');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $parentId, 'create folder');
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
        } catch (GoogleServiceException $e) {
            $this->handleGoogleException($e, $folderId ?? '', 'list files');
        } catch (Exception $e) {
            Log::error('Google Drive list files failed: ' . $e->getMessage());
            throw new Exception('Failed to list files from Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Convert Google Service Exception to custom exception
     *
     * @param GoogleServiceException $e
     * @param string $fileId
     * @param string $operation
     * @throws GoogleDriveFileNotFoundException
     * @throws GoogleDriveAuthException
     * @throws GoogleDriveQuotaExceededException
     * @throws GoogleDrivePermissionException
     * @throws Exception
     */
    protected function handleGoogleException(GoogleServiceException $e, string $fileId = '', string $operation = ''): void
    {
        $code = $e->getCode();
        $message = $e->getMessage();

        Log::error("Google Drive API Error [{$code}]: {$message}", [
            'file_id' => $fileId,
            'operation' => $operation
        ]);

        switch ($code) {
            case 404:
                throw new GoogleDriveFileNotFoundException($fileId);
            
            case 401:
                throw new GoogleDriveAuthException($message);
            
            case 403:
                // Check if it's quota or permission issue
                if (str_contains(strtolower($message), 'quota') || str_contains(strtolower($message), 'storage')) {
                    throw new GoogleDriveQuotaExceededException($message);
                }
                throw new GoogleDrivePermissionException($fileId, $operation);
            
            case 507:
                throw new GoogleDriveQuotaExceededException($message);
            
            default:
                throw new Exception("Google Drive error: {$message}", $code);
        }
    }
}

