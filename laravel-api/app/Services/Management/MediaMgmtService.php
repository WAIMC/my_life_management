<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Services\MinioService;
use App\Interfaces\Management\MediaMgmtInterface;
use App\Constants\MediaConst;
use Illuminate\Http\Resources\Json\JsonResource;

use Exception;
use App\Http\Resources\Management\MediaFileResource;

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
  public function list(array $payload): array
  {
    $list = $this->mediaMgmt->list($payload);

    // Manually transform to array to avoid ResourceCollection pagination wrapper
    return $list->map(function ($item) {
      return (new MediaFileResource($item))->resolve();
    })->values()->all();
  }

  /**
   * Store media (file upload or folder creation)
   */
  /**
   * Store media (file from temp or folder creation)
   */
  public function store(array $payload): int
  {
    // If key is provided, move from temp
    if (isset($payload['key'])) {
      return $this->storeFromTemp($payload);
    }

    // Otherwise, create folder
    return $this->createFolder($payload);
  }

  /**
   * Update media (rename or move)
   */
  public function update(array $payload): int
  {
    $media = $this->mediaMgmt->find($payload['id']);

    if (!$media) {
      throw new Exception('Media not found');
    }

    // If new_parent_path is provided, it's a move operation
    if (isset($payload['new_parent_path'])) {
      return $this->move($payload, $media);
    }

    // Otherwise, it's a rename operation
    if (isset($payload['name'])) {
      return $this->rename($payload, $media);
    }

    throw new Exception('Either name or new_parent_path must be provided');
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
        $this->minioService->delete($media->minio_bucket, $media->storage_path);
      }
    }

    // Soft delete in DB
    $this->mediaMgmt->executeDelete($ids);
  }

  /**
   * Create folder (internal method)
   */
  protected function createFolder(array $payload): int
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
   * Prepare upload (Generate Presigned URL)
   */
  public function prepareUpload(array $payload): array
  {
    $extension = $payload['extension'];
    $ttl = MediaConst::PRESIGNED_UPLOAD_TTL; // 5 seconds

    return $this->minioService->generatePresignedUploadUrl($extension, MediaConst::DISK_TEMP, $ttl);
  }

  /**
   * Store file from Temp (Move to Official + Create DB Record)
   */
  public function storeFromTemp(array $payload): int
  {
    $tempKey = $payload['key']; // This is the path in temp bucket: {uuid}.{ext}
    $extension = $payload['extension'];
    $workspaceId = $payload['workspace_id'] ?? null;
    $parentPath = $payload['parent_path'] ?? '/';
    $originalName = $payload['original_name'];

    // 1. Verify file exists in Temp (Optional but recommended)
    if (!$this->minioService->exists(MediaConst::DISK_TEMP, $tempKey)) {
      throw new Exception("File not found in temporary storage: {$tempKey}");
    }

    // 2. Generate new storage path for Official bucket
    // Format: {workspace}/{year}/{month}/{uuid}.{ext}
    $officialDisk = MediaConst::DISK_OFFICIAL;
    $officialPath = $this->minioService->generateStoragePath($extension, $officialDisk, $workspaceId);

    // 3. Move file from Temp to Official
    if (!$this->minioService->move(MediaConst::DISK_TEMP, $tempKey, $officialDisk, $officialPath)) {
      throw new Exception("Failed to move file from temp to official storage.");
    }

    // 4. Create DB Record
    $virtualPath = rtrim($parentPath, '/') . '/' . $originalName;

    $data = [
      'workspace_id' => $workspaceId,
      'is_file' => MediaConst::TYPE_FILE,
      'virtual_path' => $virtualPath,
      'storage_path' => $officialPath,
      'original_name' => $originalName,
      'extension' => $extension,
      'mime_type' => $payload['mime_type'] ?? null, // optional
      'size' => $payload['size'] ?? 0, // optional, but better if provided
      'minio_bucket' => $officialDisk,
      'minio_object_key' => $officialPath,
      'url' => $this->minioService->getPublicUrl($officialDisk, $officialPath),
      'is_delete' => false,
      // Metadata like width/height is harder to extract without downloading, 
      // but client can send it if needed, or we use a lambda/worker later.
    ];

    return $this->mediaMgmt->executeStore($data);
  }

  /**
   * Rename (internal method)
   */
  protected function rename(array $payload, $media): int
  {
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
   * Move (internal method)
   */
  protected function move(array $payload, $media): int
  {
    $newParentPath = $payload['new_parent_path'] ?? '/';
    $currentVirtualPath = $media->virtual_path;

    // Build new virtual path
    $newVirtualPath = rtrim($newParentPath, '/') . '/' . $media->original_name;

    if (!$media->isFile()) {
      $newVirtualPath .= '/';

      // IMPORTANT: Prevent moving folder into itself or its own subfolder
      if (str_starts_with($newVirtualPath, $currentVirtualPath)) {
        throw new Exception('Cannot move folder into itself or its subfolder');
      }
    }

    // Additional check: if new path equals current path, no need to move
    if ($newVirtualPath === $currentVirtualPath) {
      return $media->id; // Return same ID, no changes needed
    }

    $updateData = [
      'id' => $media->id,
      'virtual_path' => $newVirtualPath,
    ];

    return $this->mediaMgmt->executeUpdate($updateData);
  }
}
