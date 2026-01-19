<?php

namespace App\Services;

use App\Constants\MediaConst;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class MinioService
{
  /**
   * Upload file to MinIO
   *
   * @param UploadedFile $file
   * @param string $disk One of MediaConst::DISK_*
   * @param int|null $workspaceId
   * @param string $prefix
   * @return array
   * @throws \Exception
   */
  public function upload(UploadedFile $file, string $disk, ?int $workspaceId = null): array
  {
    $storagePath = $this->generateStoragePath($file->getClientOriginalExtension(), $disk, $workspaceId);

    try {
      $result = Storage::disk($disk)->put($storagePath, fopen($file->getRealPath(), 'r'), [
        'Metadata' => [
          'original-name' => $file->getClientOriginalName(),
        ],
      ]);

      if (!$result) {
        throw new \Exception('Failed to upload file to storage.');
      }

      $filesystem = Storage::disk($disk);
      /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */

      return [
        'disk' => $disk,
        'storage_path' => $storagePath,
        'object_key' => $storagePath,
        'url' => $filesystem->url($storagePath),
        'size' => $file->getSize(),
        'mime_type' => $file->getMimeType(),
      ];
    } catch (\Exception $e) {
      Log::error('MinIO upload failed', ['error' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * Move file in MinIO (Copy + Delete)
   */
  public function move(string $sourceDisk, string $sourceKey, string $destDisk, string $destKey): bool
  {
    try {
      // If disks are different or we want to be safe, we can use streams
      // Ideally if on same S3 driver/server, we might want to do copyObject but
      // Storage facade standard copy is for same disk.
      // Cross-disk move usually requires readStream -> writeStream -> delete

      if (Storage::disk($destDisk)->put($destKey, Storage::disk($sourceDisk)->readStream($sourceKey))) {
        return Storage::disk($sourceDisk)->delete($sourceKey);
      }

      return false;
    } catch (\Exception $e) {
      Log::error('MinIO move failed', ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Delete file from MinIO
   */
  public function delete(string $disk, string $objectKey): bool
  {
    try {
      return Storage::disk($disk)->delete($objectKey);
    } catch (\Exception $e) {
      Log::error('MinIO delete failed', ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Get public URL
   */
  public function getPublicUrl(string $disk, string $objectKey): string
  {
    /** @var \Illuminate\Filesystem\FilesystemAdapter $filesystem */
    $filesystem = Storage::disk($disk);
    return $filesystem->url($objectKey);
  }

  /**
   * Generate storage path
   * Standard: {workspace}/{year}/{month}/{uuid}.{ext}
   * Temp: {uuid}.{ext}
   */
  public function generateStoragePath(string $extension, string $disk, ?int $workspaceId = null): string
  {
    $uuid = Str::uuid();

    if ($disk === MediaConst::DISK_TEMP) {
      return str_replace(
        ['{uuid}', '{extension}'],
        [$uuid, $extension],
        MediaConst::STORAGE_PATH_TEMP
      );
    }

    $year = date('Y');
    $month = date('m');
    $workspace = $workspaceId ? "workspace-{$workspaceId}" : 'default';

    return str_replace(
      ['{workspace}', '{year}', '{month}', '{uuid}', '{extension}'],
      [$workspace, $year, $month, $uuid, $extension],
      MediaConst::STORAGE_PATH_STANDARD
    );
  }

  /**
   * Generate Presigned Upload URL (PUT)
   * 
   * @param string $extension
   * @param string $disk
   * @param int $expirySeconds TTL in seconds
   * @return array
   */
  public function generatePresignedUploadUrl(string $extension, string $disk = MediaConst::DISK_TEMP, int $expirySeconds = 300): array
  {
    $key = $this->generateStoragePath($extension, $disk);

    // We need to use a custom S3Client with the PUBLIC endpoint for signing
    // to ensure the Host header in the signature matches what the browser sends (e.g., localhost).
    // If we use the default client, it signs with the internal docker host (ml-minio), causing a mismatch.
    $publicEndpoint = config('filesystems.disks.s3.url'); // e.g., http://localhost:9100

    $config = [
      'region' => config("filesystems.disks.{$disk}.region"),
      'version' => 'latest',
      'endpoint' => $publicEndpoint,
      'use_path_style_endpoint' => true,
      'credentials' => [
        'key' => config("filesystems.disks.{$disk}.key"),
        'secret' => config("filesystems.disks.{$disk}.secret"),
      ],
    ];

    $client = new \Aws\S3\S3Client($config);
    $bucket = config("filesystems.disks.{$disk}.bucket");

    // Create the command
    $cmd = $client->getCommand('PutObject', [
      'Bucket' => $bucket,
      'Key' => $key,
      'ACL' => 'private',
    ]);

    // Create the presigned request
    $request = $client->createPresignedRequest($cmd, "+{$expirySeconds} seconds");

    return [
      'upload_url' => (string) $request->getUri(),
      'method' => 'PUT',
      'key' => $key,
      'uuid' => basename($key, ".{$extension}"), // Extract UUID from key (temp path is {uuid}.ext)
      'headers' => [
        'Content-Type' => 'application/octet-stream',
      ],
      'expires_in' => $expirySeconds,
    ];
  }

  /**
   * Check if file exists
   */
  public function exists(string $disk, string $objectKey): bool
  {
    return Storage::disk($disk)->exists($objectKey);
  }
}
