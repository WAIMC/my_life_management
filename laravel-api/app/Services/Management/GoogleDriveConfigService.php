<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\GoogleDriveConfigInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\GoogleDriveConfigResource;
use Illuminate\Http\UploadedFile;
use Exception;

class GoogleDriveConfigService extends BaseService
{
    public function __construct(
        protected GoogleDriveConfigInterface $googleDriveConfig
    ) {
    }

    protected function getHistoryRepository()
    {
        return null;
    }

    protected function getHistoryForeignKey(): string
    {
        return '';
    }

    /**
     * Get list of configurations
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->googleDriveConfig->list($payload);
        return GoogleDriveConfigResource::collection($list);
    }

    /**
     * Upload and store credentials file
     */
    public function upload(array $payload): array
    {
        /** @var UploadedFile $file */
        $file = $payload['file'];
        $name = $payload['name'];
        $rootFolderId = $payload['root_folder_id'];

        try {
            // Read and validate JSON
            $credentialsJson = file_get_contents($file->getRealPath());
            $credentials = json_decode($credentialsJson, true);

            if (!$credentials) {
                throw new Exception('Invalid JSON file');
            }

            // Validate required fields
            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            foreach ($requiredFields as $field) {
                if (!isset($credentials[$field])) {
                    throw new Exception("Missing required field: {$field}");
                }
            }

            // Store in database (will be encrypted automatically)
            $id = $this->googleDriveConfig->executeStore([
                'name' => $name,
                'credentials_json' => $credentialsJson,
                'root_folder_id' => $rootFolderId,
                'is_active' => false, // Don't activate automatically
                'status' => 1,
            ]);

            return [
                'id' => $id,
                'name' => $name,
                'message' => 'Credentials uploaded successfully',
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to upload credentials: ' . $e->getMessage());
        }
    }

    /**
     * Activate a configuration
     */
    public function activate(int $id): void
    {
        $config = $this->googleDriveConfig->find($id);
        
        if (!$config) {
            throw new Exception('Configuration not found');
        }

        if (!$config->hasValidCredentials()) {
            throw new Exception('Configuration has invalid credentials');
        }

        try {
            // Deactivate all other configs
            $this->googleDriveConfig->deactivateAll();

            // Activate this config
            $this->googleDriveConfig->executeUpdate([
                'id' => $id,
                'is_active' => true,
            ]);

            // Clear any cached credentials
            \Cache::forget('google_drive_active_config');
        } catch (Exception $e) {
            throw new Exception('Failed to activate configuration: ' . $e->getMessage());
        }
    }

    /**
     * Get active configuration
     */
    public function getActive()
    {
        return $this->googleDriveConfig->getActive();
    }

    /**
     * Delete configuration
     */
    public function delete(array $payload): void
    {
        $ids = is_array($payload['ids']) ? $payload['ids'] : [$payload['ids']];

        // Check if any of the configs to delete is active
        foreach ($ids as $id) {
            $config = $this->googleDriveConfig->find($id);
            if ($config && $config->is_active) {
                throw new Exception('Cannot delete active configuration. Please activate another configuration first.');
            }
        }

        $this->googleDriveConfig->executeDelete($ids);
    }
}
