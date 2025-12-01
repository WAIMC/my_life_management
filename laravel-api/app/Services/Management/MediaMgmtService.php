<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Services\MinioService;
use App\Interfaces\Management\MediaMgmtInterface;
use App\Constants\MediaConst;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\UploadedFile;
use Exception;

class MediaMgmtService extends BaseService
{
    public function __construct(
        protected MediaMgmtInterface $mediaMgmt,
        protected MinioService $minioService
    ) {}

    protected function getHistoryRepository()
    {
        return null; // No history tracking for media
    }

    protected function getHistoryForeignKey(): string
    {
        return '';
    }

    /**
     * List media (files and folders)
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->mediaMgmt->list($payload);
        return JsonResource::collection($list);
    }

    /**
     * Create folder
     */
    public function createFolder(array $payload): int
    {
        $parentPath = $payload['parent_path'] ?? '/';
        $folderName = $payload['name'];

        $virtualPath = rtrim($parentPath, '/') . '/' . $folderName . '/';

        $data = [
            'workspace_id' => $payload['workspace_id'] ?? null,
            'is_file' => MediaConst::TYPE_FOLDER,
            'virtual_path' => $virtualPath,
            'storage_path' => null,  // Folders don't have storage path
            'original_name' => $folderName,
            'is_delete' => false,
        ];

        return $this->mediaMgmt->executeStore($data);
    }

    /**
     * Upload file
     */
    public function uploadFile(UploadedFile $file, array $payload): int
    {
        $parentPath = $payload['parent_path'] ?? '/';
        $workspaceId = $payload['workspace_id'] ?? null;

        // Upload to MinIO
        $uploadResult = $this->minioService->upload($file, $workspaceId);

        // Extract metadata
        $metadata = $this->extractMetadata($file);

        // Virtual path
        $virtualPath = rtrim($parentPath, '/') . '/' . $file->getClientOriginalName();

        $data = [
            'workspace_id' => $workspaceId,
            'is_file' => MediaConst::TYPE_FILE,
            'virtual_path' => $virtualPath,
            'storage_path' => $uploadResult['storage_path'],
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'size' => $uploadResult['size'],
            'minio_bucket' => $uploadResult['bucket'],
            'minio_object_key' => $uploadResult['object_key'],
            'minio_etag' => $uploadResult['etag'],
            'url' => $uploadResult['url'],
            'width' => $metadata['width'] ?? null,
            'height' => $metadata['height'] ?? null,
            'duration' => $metadata['duration'] ?? null,
            'is_delete' => false,
        ];

        return $this->mediaMgmt->executeStore($data);
    }

    /**
     * Rename (file or folder)
     */
    public function rename(array $payload): int
    {
        $media = $this->mediaMgmt->find($payload['id']);

        if (!$media) {
            throw new Exception('Media not found');
        }

        $newName = $payload['name'];
        $parentPath = dirname($media->virtual_path);
        $newVirtualPath = rtrim($parentPath, '/') . '/' . $newName;

        if (!$media->isFile()) {
            $newVirtualPath .= '/';
        }

        $updateData = [
            'id' => $media->id,
            'original_name' => $newName,
            'virtual_path' => $newVirtualPath,
        ];

        return $this->mediaMgmt->executeUpdate($updateData);
    }

    /**
     * Move (file or folder)
     */
    public function move(array $payload): int
    {
        $media = $this->mediaMgmt->find($payload['id']);

        if (!$media) {
            throw new Exception('Media not found');
        }

        $newParentPath = $payload['new_parent_path'] ?? '/';
        $newVirtualPath = rtrim($newParentPath, '/') . '/' . $media->original_name;

        if (!$media->isFile()) {
            $newVirtualPath .= '/';
        }

        $updateData = [
            'id' => $media->id,
            'virtual_path' => $newVirtualPath,
        ];

        return $this->mediaMgmt->executeUpdate($updateData);
    }

    /**
     * Delete (file or folder)
     */
    public function delete(array $payload): void
    {
        $ids = $payload['ids'] ?? [];

        foreach ($ids as $id) {
            $media = $this->mediaMgmt->find($id);

            if ($media && $media->isFile() && $media->storage_path) {
                // Delete from MinIO
                $this->minioService->delete($media->storage_path);
            }
        }

        // Soft delete in DB
        $this->mediaMgmt->executeDelete($ids);
    }

    /**
     * Extract metadata from file
     */
    protected function extractMetadata(UploadedFile $file): array
    {
        $metadata = [];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            $imageInfo = getimagesize($file->getRealPath());
            if ($imageInfo) {
                $metadata['width'] = $imageInfo[0];
                $metadata['height'] = $imageInfo[1];
            }
        }

        return $metadata;
    }
}
