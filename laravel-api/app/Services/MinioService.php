<?php

namespace App\Services;

use Aws\S3\S3Client;
use App\Constants\MediaConst;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MinioService
{
  protected S3Client $client;
  protected string $bucket;
  protected string $previewBucket;
  protected string $publicUrl;

  public function __construct()
  {
    $endpoint = config('minio.endpoint');
    $accessKey = config('minio.access_key');
    $secretKey = config('minio.secret_key');

    $this->client = new S3Client([
      'version' => 'latest',
      'region' => config('minio.region', 'us-east-1'),
      'endpoint' => $endpoint,
      'use_path_style_endpoint' => true,
      'credentials' => [
        'key' => $accessKey,
        'secret' => $secretKey,
      ],
    ]);

    $this->bucket = config('minio.bucket', 'media');
    $this->previewBucket = config('minio.preview_bucket', 'media-previews');
    $this->publicUrl = config('minio.public_url');
  }

  /**
   * Upload file to MinIO (flat structure)
   */
  public function upload(UploadedFile $file, ?int $workspaceId = null, string $prefix = ''): array
  {
    $storagePath = $this->generateStoragePath($file, $workspaceId, $prefix);

    try {
      $result = $this->client->putObject([
        'Bucket' => $this->bucket,
        'Key' => $storagePath,
        'Body' => fopen($file->getRealPath(), 'r'),
        'ContentType' => $file->getMimeType(),
        'Metadata' => [
          'original-name' => $file->getClientOriginalName(),
        ],
      ]);

      return [
        'bucket' => $this->bucket,
        'storage_path' => $storagePath,
        'object_key' => $storagePath,
        'etag' => trim($result['ETag'], '"'),
        'url' => $this->getPublicUrl($storagePath),
        'size' => $file->getSize(),
      ];
    } catch (\Exception $e) {
      Log::error('MinIO upload failed', ['error' => $e->getMessage()]);
      throw $e;
    }
  }

  /**
   * Move file in MinIO (Copy + Delete)
   */
  public function move(string $sourceKey, string $destKey): bool
  {
    try {
      // Copy
      $this->client->copyObject([
        'Bucket' => $this->bucket,
        'Key' => $destKey,
        'CopySource' => "{$this->bucket}/{$sourceKey}",
      ]);

      // Delete original
      $this->delete($sourceKey);

      return true;
    } catch (\Exception $e) {
      Log::error('MinIO move failed', ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Delete file from MinIO
   */
  public function delete(string $objectKey): bool
  {
    try {
      $this->client->deleteObject([
        'Bucket' => $this->bucket,
        'Key' => $objectKey,
      ]);
      return true;
    } catch (\Exception $e) {
      Log::error('MinIO delete failed', ['error' => $e->getMessage()]);
      return false;
    }
  }

  /**
   * Get public URL
   */
  public function getPublicUrl(string $objectKey): string
  {
    return rtrim($this->publicUrl, '/') . '/' . $this->bucket . '/' . ltrim($objectKey, '/');
  }

  /**
   * Generate storage path (flat structure like S3)
   * Pattern: {prefix}/{workspace}/{category}/{year}/{month}/{uuid}.{ext}
   */
  protected function generateStoragePath(UploadedFile $file, ?int $workspaceId, string $prefix = ''): string
  {
    $category = $this->getCategoryFromMime($file->getMimeType());
    $year = date('Y');
    $month = date('m');
    $uuid = Str::uuid();
    $extension = $file->getClientOriginalExtension();

    $workspace = $workspaceId ? "workspace-{$workspaceId}" : 'default';

    $path = "{$workspace}/{$category}/{$year}/{$month}/{$uuid}.{$extension}";

    if ($prefix) {
      $path = rtrim($prefix, '/') . '/' . $path;
    }

    return $path;
  }

  /**
   * Get category from MIME type
   */
  protected function getCategoryFromMime(string $mimeType): string
  {
    if (str_starts_with($mimeType, 'image/')) {
      return MediaConst::CATEGORY_IMAGE;
    } elseif (str_starts_with($mimeType, 'video/')) {
      return MediaConst::CATEGORY_VIDEO;
    } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
      return MediaConst::CATEGORY_DOCUMENT;
    } elseif (str_starts_with($mimeType, 'application/zip') || str_starts_with($mimeType, 'application/x-rar')) {
      return MediaConst::CATEGORY_ARCHIVE;
    }

    return MediaConst::CATEGORY_OTHER;
  }

  /**
   * Check if file exists
   */
  public function exists(string $objectKey): bool
  {
    try {
      $this->client->headObject([
        'Bucket' => $this->bucket,
        'Key' => $objectKey,
      ]);
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }
}
