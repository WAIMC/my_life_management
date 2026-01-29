<?php

namespace App\Services;

use App\Constants\MediaConst;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Exception;

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
   * @throws Exception
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
        throw new Exception(__('minio.upload_file_to_storage_failed'));
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
    } catch (Exception $e) {
      Log::error(__('minio.upload_failed'), ['error' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * Move file in MinIO (Copy + Delete)
   */
  /**
   * Move file in MinIO (Copy + Delete) using internal S3 CopyObject
   */
  public function move(string $sourceDisk, string $sourceKey, string $destDisk, string $destKey): bool
  {
    try {
      if ($this->copyKey($sourceDisk, $sourceKey, $destDisk, $destKey)) {
        return $this->delete($sourceDisk, $sourceKey);
      }
      return false;
    } catch (Exception $e) {
      Log::error(__('minio.move_failed'), ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Copy file using S3 CopyObject (internal server-side copy)
   */
  public function copyKey(string $sourceDisk, string $sourceKey, string $destDisk, string $destKey): bool
  {
    try {
      $client = $this->getS3Client($destDisk, true);
      $destBucket = config("filesystems.disks.{$destDisk}.bucket");
      $sourceBucket = config("filesystems.disks.{$sourceDisk}.bucket");

      // Use high-level copy helper to handle files > 5GB (Multipart Copy)
      // This automatically handles multipart copying for large files
      $client->copy(
        $sourceBucket,
        $sourceKey,
        $destBucket,
        $destKey
      );

      return true;
    } catch (Exception $e) {
      Log::error(__('minio.copy_failed'), ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Delete file from MinIO
   */
  public function delete(string $disk, string $objectKey): bool
  {
    try {
      $client = $this->getS3Client($disk, true);
      $bucket = config("filesystems.disks.{$disk}.bucket");

      $client->deleteObject([
        'Bucket' => $bucket,
        'Key' => $objectKey,
      ]);

      return true;
    } catch (Exception $e) {
      Log::error(__('minio.delete_failed'), ['error' => $e->getMessage()]);
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
  public function generatePresignedUploadUrl(string $extension, string $disk = MediaConst::DISK_TEMP, int $expirySeconds = MediaConst::PRESIGNED_UPLOAD_DEFAULT_EXPIRY): array
  {
    $key = $this->generateStoragePath($extension, $disk);

    $client = $this->getS3Client($disk, false);
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
    try {
      $client = $this->getS3Client($disk, true);
      $bucket = config("filesystems.disks.{$disk}.bucket");

      return $client->doesObjectExist($bucket, $objectKey);
    } catch (Exception $e) {
      Log::error(__('minio.exists_failed'), ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Create Multipart Upload
   */
  public function createMultipartUpload(string $extension, string $disk = MediaConst::DISK_TEMP): array
  {
    $key = $this->generateStoragePath($extension, $disk);
    $client = $this->getS3Client($disk, true);
    $bucket = config("filesystems.disks.{$disk}.bucket");

    $result = $client->createMultipartUpload([
      'Bucket' => $bucket,
      'Key' => $key,
      'ACL' => 'private',
      'ContentType' => 'application/octet-stream', // Important for parts
    ]);

    return [
      'upload_id' => $result['UploadId'],
      'key' => $key,
      'uuid' => basename($key, ".{$extension}"),
    ];
  }

  /**
   * Generate Presigned URL for Upload Part
   */
  public function generatePresignedUploadPartUrl(
    string $disk,
    string $key,
    string $uploadId,
    int $partNumber,
    int $expirySeconds = MediaConst::PRESIGNED_UPLOAD_DEFAULT_EXPIRY
  ): string {
    $client = $this->getS3Client($disk, false);
    $bucket = config("filesystems.disks.{$disk}.bucket");

    $cmd = $client->getCommand('UploadPart', [
      'Bucket' => $bucket,
      'Key' => $key,
      'UploadId' => $uploadId,
      'PartNumber' => $partNumber,
    ]);

    $request = $client->createPresignedRequest($cmd, "+{$expirySeconds} seconds");

    return (string) $request->getUri();
  }

  /**
   * Complete Multipart Upload
   */
  public function completeMultipartUpload(
    string $disk,
    string $key,
    string $uploadId,
    array $parts
  ): void {
    $client = $this->getS3Client($disk, true);
    $bucket = config("filesystems.disks.{$disk}.bucket");

    $client->completeMultipartUpload([
      'Bucket' => $bucket,
      'Key' => $key,
      'UploadId' => $uploadId,
      'MultipartUpload' => [
        'Parts' => $parts,
      ],
    ]);
  }

  /**
   * Abort Multipart Upload
   */
  public function abortMultipartUpload(string $disk, string $key, string $uploadId): void
  {
    $client = $this->getS3Client($disk, true);
    $bucket = config("filesystems.disks.{$disk}.bucket");

    $client->abortMultipartUpload([
      'Bucket' => $bucket,
      'Key' => $key,
      'UploadId' => $uploadId,
    ]);
  }

  /**
   * Get configured S3 Client
   */
  private function getS3Client(string $disk, bool $useInternalEndpoint = false): S3Client
  {
    $endpoint = $useInternalEndpoint
      ? config("filesystems.disks.{$disk}.endpoint")
      : config('filesystems.disks.s3.url');

    $config = [
      'region' => config("filesystems.disks.{$disk}.region"),
      'version' => 'latest',
      'endpoint' => $endpoint,
      'use_path_style_endpoint' => true,
      'credentials' => [
        'key' => config("filesystems.disks.{$disk}.key"),
        'secret' => config("filesystems.disks.{$disk}.secret"),
      ],
    ];

    return new S3Client($config);
  }
}
