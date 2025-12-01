# File Manager System với MinIO

Hệ thống quản lý file hoàn chỉnh sử dụng **MinIO** làm storage backend (S3-compatible), với **flat object storage structure** và virtual paths lưu trong database để hiển thị tree structure.

---

## 🎯 Mục tiêu

- ✅ ERD chuẩn cho hệ thống media, lưu migrations trong `database/migrations/Table/Management/`
- ✅ Migration + Model + Repository + Service + Controller + Validation đầy đủ (theo pattern hiện tại)
- ✅ API CRUD hoàn chỉnh với flat object storage - DB lưu metadata, MinIO lưu file thật
- ✅ Next.js component quản lý file giống Windows Explorer
- ✅ Docker MinIO config hoàn chỉnh trong `docker/.env`
- ✅ Tương thích với cấu trúc code hiện tại: `ApiResponse`, middleware (transaction/error), repository pattern
- ✅ Sử dụng `is_delete` thay vì `soft deletes`, admin_id/user_id = NULL (sẽ cấu hình auth sau)

---

## 🧱 1. Kiến trúc tổng thể

```
[Next.js File Manager UI] ←→ [Laravel API] ←→ [MinIO Object Storage]
                                    ↓
                            [PostgreSQL Database]
                         (Flat Structure + Virtual Paths)
```

| Thành phần      | Vai trò                                          |
| --------------- | ------------------------------------------------ |
| **Next.js UI**  | Windows Explorer-like UI cho quản lý file/folder |
| **Laravel API** | Backend API với Repository/Service pattern       |
| **MinIO**       | S3-compatible flat object storage                |
| **PostgreSQL**  | Lưu metadata + virtual paths                     |

### Flat Object Storage Concept

```
MinIO Storage (Flat - như S3):
├─ workspace-1/2025/01/abc123.jpg
├─ workspace-1/2025/01/def456.pdf
├─ workspace-1/documents/ghi789.docx
└─ workspace-1/images/avatar/jkl012.png

Database (Virtual Paths - để hiển thị tree):
├─ virtual_path: /Documents/           (is_file: false)
├─ virtual_path: /Documents/Reports/   (is_file: false)
├─ virtual_path: /Images/Avatar/       (is_file: false)
└─ virtual_path: /Images/Avatar/avatar.png (is_file: true, storage_path: workspace-1/images/avatar/jkl012.png)
```

---

## 🗄️ 2. Database Design (ERD)

### 2.1. Bảng `media_mgmt` (Single Table - Gộp folder + file)

```sql
CREATE TABLE media_mgmt (
    id SERIAL PRIMARY KEY,
    workspace_id INTEGER NULL,  -- Tạm null, sau sẽ cấu hình

    -- Flat storage structure
    is_file BOOLEAN NOT NULL DEFAULT TRUE,  -- true: file, false: folder

    -- Virtual path (UI tree structure)
    virtual_path VARCHAR(1000) NOT NULL,  -- Ví dụ: /Documents/Reports/file.pdf

    -- Real storage path (MinIO flat)
    storage_path VARCHAR(1000) NULL,  -- Ví dụ: workspace-1/2025/01/uuid.pdf (NULL nếu là folder)

    -- File metadata
    original_name VARCHAR(255) NOT NULL,  -- Tên hiển thị
    extension VARCHAR(50) NULL,  -- Chỉ có nếu is_file = true
    mime_type VARCHAR(100) NULL,
    size BIGINT NULL,  -- bytes

    -- MinIO info
    minio_bucket VARCHAR(100) NULL,
    minio_object_key VARCHAR(500) NULL,  -- = storage_path
    minio_etag VARCHAR(100) NULL,

    -- URLs
    url TEXT NULL,  -- Public URL

    -- Image/Video metadata
    width INTEGER NULL,
    height INTEGER NULL,
    duration INTEGER NULL,
    metadata JSONB NULL,

    -- Soft delete (is_delete thay vì deleted_at)
    is_delete BOOLEAN DEFAULT FALSE,

    -- Audit
    created_by INTEGER NULL,  -- Tạm null, sau sẽ cấu hình auth
    updated_by INTEGER NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Indexes
    CONSTRAINT unique_virtual_path UNIQUE (workspace_id, virtual_path, is_delete)
);

CREATE INDEX idx_media_workspace ON media_mgmt(workspace_id);
CREATE INDEX idx_media_virtual_path ON media_mgmt(virtual_path);
CREATE INDEX idx_media_is_file ON media_mgmt(is_file);
CREATE INDEX idx_media_is_delete ON media_mgmt(is_delete);
CREATE INDEX idx_media_mime_type ON media_mgmt(mime_type);
```

---

## 🐳 3. Docker Configuration

### 3.1. File `docker/.env` (additions)

```env
# --------------------------------------------
# MinIO Object Storage Configuration
# --------------------------------------------
# MinIO - S3-compatible object storage
# Inside host: Service name used within Docker network
# Outside host: localhost or IP used from host machine

MINIO_HOST_INSIDE_ENV=ml-minio
MINIO_HOST_OUTSIDE_ENV=localhost
MINIO_PORT_INSIDE_ENV=9000
MINIO_PORT_OUTSIDE_ENV=9100
MINIO_CONSOLE_PORT_INSIDE_ENV=9001
MINIO_CONSOLE_PORT_OUTSIDE_ENV=9101

# MinIO credentials
MINIO_ROOT_USER=ml_minio_admin
MINIO_ROOT_PASSWORD=ml_minio_password123

# MinIO buckets
MINIO_BUCKET=media
MINIO_PREVIEW_BUCKET=media-previews

# MinIO settings
MINIO_REGION=us-east-1
MINIO_USE_SSL=false
MINIO_PUBLIC_URL=http://localhost:9100
```

### 3.2. File `docker/docker-compose.yml` (additions)

```yaml
# MinIO Object Storage (S3-compatible)
minio:
  image: minio/minio:latest
  container_name: ml-minio
  restart: unless-stopped
  ports:
    - "${MINIO_PORT_OUTSIDE_ENV}:${MINIO_PORT_INSIDE_ENV}"
    - "${MINIO_CONSOLE_PORT_OUTSIDE_ENV}:${MINIO_CONSOLE_PORT_INSIDE_ENV}"
  environment:
    MINIO_ROOT_USER: ${MINIO_ROOT_USER}
    MINIO_ROOT_PASSWORD: ${MINIO_ROOT_PASSWORD}
    MINIO_BROWSER_REDIRECT_URL: http://localhost:${MINIO_CONSOLE_PORT_OUTSIDE_ENV}
  command: server /data --console-address ":${MINIO_CONSOLE_PORT_INSIDE_ENV}"
  volumes:
    - ./data/minio:/data
  networks:
    - app_network
  healthcheck:
    test:
      [
        "CMD",
        "curl",
        "-f",
        "http://localhost:${MINIO_PORT_INSIDE_ENV}/minio/health/live",
      ]
    interval: 30s
    timeout: 10s
    retries: 3

# MinIO client - auto init buckets
minio_init:
  image: minio/mc:latest
  container_name: ml-minio-init
  depends_on:
    - minio
  environment:
    MINIO_ROOT_USER: ${MINIO_ROOT_USER}
    MINIO_ROOT_PASSWORD: ${MINIO_ROOT_PASSWORD}
  entrypoint: >
    /bin/sh -c "
    sleep 5;
    /usr/bin/mc alias set myminio http://minio:${MINIO_PORT_INSIDE_ENV} ${MINIO_ROOT_USER} ${MINIO_ROOT_PASSWORD};
    /usr/bin/mc mb myminio/${MINIO_BUCKET} --ignore-existing;
    /usr/bin/mc mb myminio/${MINIO_PREVIEW_BUCKET} --ignore-existing;
    /usr/bin/mc anonymous set download myminio/${MINIO_BUCKET};
    /usr/bin/mc anonymous set download myminio/${MINIO_PREVIEW_BUCKET};
    exit 0;
    "
  networks:
    - app_network
```

---

## 🔧 4. Laravel Backend Implementation

### 4.1. Constants

**File: `app/Constants/MediaConst.php`** (NEW)

```php
<?php

namespace App\Constants;

class MediaConst
{
    // File types
    public const TYPE_FILE = true;
    public const TYPE_FOLDER = false;

    // Default paths
    public const ROOT_PATH = '/';
    public const PATH_SEPARATOR = '/';

    // File categories by MIME
    public const CATEGORY_IMAGE = 'image';
    public const CATEGORY_VIDEO = 'video';
    public const CATEGORY_DOCUMENT = 'document';
    public const CATEGORY_ARCHIVE = 'archive';
    public const CATEGORY_OTHER = 'other';

    // Max file size (100MB)
    public const MAX_FILE_SIZE = 104857600;

    // Allowed extensions
    public const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    public const ALLOWED_VIDEO_EXTENSIONS = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
    public const ALLOWED_DOCUMENT_EXTENSIONS = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
    public const ALLOWED_ARCHIVE_EXTENSIONS = ['zip', 'rar', '7z', 'tar', 'gz'];

    // Storage path patterns
    public const STORAGE_PATH_PATTERN = '{workspace}/{category}/{year}/{month}/{uuid}.{extension}';
}
```

### 4.2. Migration

**File: `database/migrations/Table/Management/2025_12_01_000001_create_media_mgmt_table.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_mgmt', function (Blueprint $table) {
            $table->id();
            $table->integer('workspace_id')->nullable()->comment('Workspace ID (null for now, will configure later)');

            // Type
            $table->boolean('is_file')->default(true)->comment('true: file, false: folder');

            // Paths
            $table->string('virtual_path', 1000)->comment('Virtual path for UI tree: /Documents/file.pdf');
            $table->string('storage_path', 1000)->nullable()->comment('Real MinIO path: workspace-1/2025/01/uuid.pdf');

            // File info
            $table->string('original_name')->comment('Display name');
            $table->string('extension', 50)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('size')->nullable()->comment('File size in bytes');

            // MinIO
            $table->string('minio_bucket', 100)->nullable();
            $table->string('minio_object_key', 500)->nullable();
            $table->string('minio_etag', 100)->nullable();

            // URL
            $table->text('url')->nullable();

            // Metadata
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('duration')->nullable()->comment('Video duration in seconds');
            $table->jsonb('metadata')->nullable();

            // Soft delete
            $table->boolean('is_delete')->default(false)->comment('is deleted');

            // Audit
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['workspace_id', 'is_delete']);
            $table->index('virtual_path');
            $table->index('is_file');
            $table->index('mime_type');
            $table->unique(['workspace_id', 'virtual_path', 'is_delete'], 'unique_virtual_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_mgmt');
    }
};
```

### 4.3. Model

**File: `app/Models/Management/MediaMgmt.php`**

```php
<?php

namespace App\Models\Management;

use App\Enums\IsDelete;
use Illuminate\Database\Eloquent\Model;

class MediaMgmt extends Model
{
    protected $table = 'media_mgmt';

    protected $fillable = [
        'workspace_id',
        'is_file',
        'virtual_path',
        'storage_path',
        'original_name',
        'extension',
        'mime_type',
        'size',
        'minio_bucket',
        'minio_object_key',
        'minio_etag',
        'url',
        'width',
        'height',
        'duration',
        'metadata',
        'is_delete',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_file' => 'boolean',
        'is_delete' => 'boolean',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Scope: not deleted
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('is_delete', IsDelete::FALSE->value);
    }

    /**
     * Check if deleted
     */
    public function isDeleted(): bool
    {
        return $this->is_delete == IsDelete::TRUE->value;
    }

    /**
     * Check if file (not folder)
     */
    public function isFile(): bool
    {
        return $this->is_file;
    }

    /**
     * Check if folder
     */
    public function isFolder(): bool
    {
        return !$this->is_file;
    }

    /**
     * Get parent folder path
     */
    public function getParentPath(): string
    {
        return dirname($this->virtual_path);
    }

    /**
     * Get children (for folders)
     */
    public function children()
    {
        if ($this->isFile()) {
            return collect([]);
        }

        $childrenPath = rtrim($this->virtual_path, '/') . '/';

        return self::where('virtual_path', 'LIKE', $childrenPath . '%')
            ->notDeleted()
            ->get();
    }
}
```

### 4.4. Interface

**File: `app/Interfaces/Management/MediaMgmtInterface.php`**

```php
<?php

namespace App\Interfaces\Management;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MediaMgmtInterface
{
    public function list(array $payload): LengthAwarePaginator;
    public function find(int $id);
    public function executeStore(array $payload): int;
    public function executeUpdate(array $payload): int;
    public function executeDelete(array $ids): void;
}
```

### 4.5. Repository

**File: `app/Repositories/Management/MediaMgmtRepository.php`**

```php
<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\MediaMgmtInterface;
use App\Models\Management\MediaMgmt;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class MediaMgmtRepository extends BaseRepository implements MediaMgmtInterface
{
    public function __construct(MediaMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list with pagination
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->notDeleted();

        // Filter by folder (virtual_path parent)
        if (isset($payload['parent_path'])) {
            $parentPath = rtrim($payload['parent_path'], '/') . '/';
            // Only direct children
            $query->where('virtual_path', 'LIKE', $parentPath . '%')
                ->whereRaw("virtual_path NOT LIKE ?", [$parentPath . '%/%']);
        }

        // Filter by type (file/folder)
        if (isset($payload['is_file'])) {
            $query->where('is_file', $payload['is_file']);
        }

        // Filter by MIME type
        if (isset($payload['mime_type'])) {
            $query->where('mime_type', 'LIKE', $payload['mime_type'] . '%');
        }

        // Search by name
        if (isset($payload['search'])) {
            $query->where('original_name', 'LIKE', '%' . $payload['search'] . '%');
        }

        // Apply sorting
        $this->applySorting($query, $payload);

        // Pagination
        $perPage = $payload['per_page'] ?? 50;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create new record
     */
    public function executeStore(array $payload): int
    {
        $model = $this->model->fill(
            Arr::only($payload, $this->model->getFillable())
        );

        $model->save();

        return $model->id;
    }

    /**
     * Update record
     */
    public function executeUpdate(array $payload): int
    {
        $model = $this->model->findOrFail($payload['id']);

        if ($model->isDeleted()) {
            throw new \LogicException('Cannot update deleted record');
        }

        $model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();

        return $model->id;
    }

    /**
     * Delete record (soft delete with is_delete flag)
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)
            ->notDeleted()
            ->update(['is_delete' => IsDelete::TRUE->value]);
    }
}
```

### 4.6. MinIO Helper Service

**File: `app/Services/MinioService.php`**

```php
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
    public function upload(UploadedFile $file, ?int $workspaceId = null): array
    {
        $storagePath = $this->generateStoragePath($file, $workspaceId);

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
     * Pattern: workspace-{id}/{category}/{year}/{month}/{uuid}.{ext}
     */
    protected function generateStoragePath(UploadedFile $file, ?int $workspaceId): string
    {
        $category = $this->getCategoryFromMime($file->getMimeType());
        $year = date('Y');
        $month = date('m');
        $uuid = Str::uuid();
        $extension = $file->getClientOriginalExtension();

        $workspace = $workspaceId ? "workspace-{$workspaceId}" : 'default';

        return "{$workspace}/{$category}/{$year}/{$month}/{$uuid}.{$extension}";
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
        } elseif (in_array($mimeType, ['application/pdf', 'application/msword', 'application/vnd.ms-excel'])) {
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
```

**File: `config/minio.php`** (NEW)

```php
<?php

return [
    'endpoint' => env('MINIO_ENDPOINT', 'http://localhost:9100'),
    'access_key' => env('MINIO_ROOT_USER', 'ml_minio_admin'),
    'secret_key' => env('MINIO_ROOT_PASSWORD', 'ml_minio_password123'),
    'region' => env('MINIO_REGION', 'us-east-1'),
    'bucket' => env('MINIO_BUCKET', 'media'),
    'preview_bucket' => env('MINIO_PREVIEW_BUCKET', 'media-previews'),
    'public_url' => env('MINIO_PUBLIC_URL', 'http://localhost:9100'),
    'use_ssl' => env('MINIO_USE_SSL', false),
];
```

### 4.7. Service Layer

**File: `app/Services/Management/MediaMgmtService.php`**

```php
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
        return null; // No history tracking
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
```

### 4.8. Request Validation

**File: `app/Http/Requests/Management/MediaMgmt/UploadFileRequest.php`**

```php
<?php

namespace App\Http\Requests\Management\MediaMgmt;

use App\Constants\MediaConst;
use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allExtensions = array_merge(
            MediaConst::ALLOWED_IMAGE_EXTENSIONS,
            MediaConst::ALLOWED_VIDEO_EXTENSIONS,
            MediaConst::ALLOWED_DOCUMENT_EXTENSIONS,
            MediaConst::ALLOWED_ARCHIVE_EXTENSIONS
        );

        return [
            'file' => [
                'required',
                'file',
                'max:' . (MediaConst::MAX_FILE_SIZE / 1024), // Convert to KB
                'mimes:' . implode(',', $allExtensions),
            ],
            'parent_path' => 'nullable|string',
            'workspace_id' => 'nullable|integer',
        ];
    }
}
```

**File: `app/Http/Requests/Management/MediaMgmt/CreateFolderRequest.php`**

```php
<?php

namespace App\Http\Requests\Management\MediaMgmt;

use Illuminate\Foundation\Http\FormRequest;

class CreateFolderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_\-\s]+$/',
            'parent_path' => 'nullable|string',
            'workspace_id' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Folder name can only contain letters, numbers, spaces, hyphens and underscores.',
        ];
    }
}
```

### 4.9. Controller

**File: `app/Http/Controllers/Management/MediaMgmtController.php`**

```php
<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\MediaMgmt\UploadFileRequest;
use App\Http\Requests\Management\MediaMgmt\CreateFolderRequest;
use App\Services\Management\MediaMgmtService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaMgmtController extends Controller
{
    public function __construct(
        protected MediaMgmtService $mediaMgmt
    ) {}

    /**
     * List media (files and folders)
     */
    public function list(Request $request): JsonResource
    {
        return $this->mediaMgmt->list($request->all());
    }

    /**
     * Upload file
     */
    public function uploadFile(UploadFileRequest $request): int
    {
        return $this->mediaMgmt->uploadFile($request->file('file'), $request->all());
    }

    /**
     * Create folder
     */
    public function createFolder(CreateFolderRequest $request): int
    {
        return $this->mediaMgmt->createFolder($request->all());
    }

    /**
     * Rename (file or folder)
     */
    public function rename(Request $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;
        return $this->mediaMgmt->rename($payload);
    }

    /**
     * Move (file or folder)
     */
    public function move(Request $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;
        return $this->mediaMgmt->move($payload);
    }

    /**
     * Delete (file or folder)
     */
    public function delete(Request $request): void
    {
        $this->mediaMgmt->delete($request->all());
    }
}
```

### 4.10. Routes

**File: `routes/api.php`** (additions)

```php
use App\Http\Controllers\Management\MediaMgmtController;

Route::middleware(['auth:sanctum'])->prefix('media')->name('media.')->group(function () {
    Route::get('/list', [MediaMgmtController::class, 'list'])->name('list');
    Route::post('/upload', [MediaMgmtController::class, 'uploadFile'])->name('upload');
    Route::post('/folder', [MediaMgmtController::class, 'createFolder'])->name('folder.create');
    Route::put('/{id}/rename', [MediaMgmtController::class, 'rename'])->name('rename');
    Route::put('/{id}/move', [MediaMgmtController::class, 'move'])->name('move');
    Route::delete('/delete', [MediaMgmtController::class, 'delete'])->name('delete');
});
```

---

## ⚛️ 5. Next.js Frontend (Tương tự như plan cũ - giữ nguyên)

_(Phần frontend giữ nguyên như plan trước vì chỉ cần điều chỉnh API calls phù hợp với các endpoints mới)_

---

## ✅ Tổng kết

**Thay đổi chính so với plan ban đầu:**

- ✅ **Flat storage**: MinIO lưu flat như S3, virtual paths trong DB để hiển thị tree
- ✅ **Single table**: Gộp `media_folders` + `media_files` thành 1 table `media_mgmt` với cột `is_file`
- ✅ **is_delete**: Dùng boolean `is_delete` thay vì `deleted_at` (soft deletes)
- ✅ **No auth**: `workspace_id`, `created_by`, `updated_by` = NULL (sẽ cấu hình sau)
- ✅ **No tags**: Bỏ bảng tags
- ✅ **Docker .env**: Config MinIO trong `docker/.env` với pattern hiện tại
- ✅ **Constants**: Tạo `MediaConst` cho các giá trị common
- ✅ **Pattern alignment**: Tuân thủ pattern hiện tại (Repository/Service/Controller, ApiResponse, middleware)
- ✅ **No transaction**: Middleware đã xử lý, không cần tự handle trong service

---

## 🚀 Hướng dẫn triển khai

1. **Update docker/.env**: Add MinIO config
2. **Update docker-compose.yml**: Add MinIO service
3. **Start MinIO**: `docker-compose up -d minio minio_init`
4. **Config Laravel .env**: Add MinIO credentials
5. **Install AWS SDK**: `composer require aws/aws-sdk-php`
6. **Run migration**: `php artisan migrate --path=database/migrations/Table/Management`
7. **Setup frontend**: Update API calls in Next.js
