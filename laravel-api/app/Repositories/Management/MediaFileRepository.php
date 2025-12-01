<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\MediaFileInterface;
use App\Models\Management\MediaFile;
use Illuminate\Pagination\LengthAwarePaginator;

class MediaFileRepository implements MediaFileInterface
{
    /**
     * Get list of media files
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = MediaFile::query();

        // Filter by admin user
        if (isset($payload['admin_mst_id'])) {
            $query->where('admin_mst_id', $payload['admin_mst_id']);
        }

        // Filter by folder path
        if (isset($payload['folder_path'])) {
            $query->where('folder_path', 'like', $payload['folder_path'] . '%');
        }

        // Filter by mime type
        if (isset($payload['mime_type'])) {
            $query->where('mime_type', 'like', $payload['mime_type'] . '%');
        }

        // Filter by file type (images, videos, documents)
        if (isset($payload['file_type'])) {
            switch ($payload['file_type']) {
                case 'images':
                    $query->where('mime_type', 'like', 'image/%');
                    break;
                case 'videos':
                    $query->where('mime_type', 'like', 'video/%');
                    break;
                case 'documents':
                    $query->whereIn('mime_type', [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
                    break;
            }
        }

        // Search by filename
        if (isset($payload['search'])) {
            $query->where('original_name', 'like', '%' . $payload['search'] . '%');
        }

        // Filter by status
        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        // Exclude deleted files by default
        $query->where('is_delete', false);

        // Order by
        $orderBy = $payload['order_by'] ?? 'created_at';
        $orderDirection = $payload['order_direction'] ?? 'desc';
        $query->orderBy($orderBy, $orderDirection);

        // Pagination
        $perPage = $payload['per_page'] ?? 20;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Store media file metadata
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $mediaFile = MediaFile::create($payload);
        return $mediaFile->id;
    }

    /**
     * Update media file metadata
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $id = $payload['id'];
        unset($payload['id']);

        return MediaFile::where('id', $id)->update($payload);
    }

    /**
     * Delete media file records (soft delete)
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        MediaFile::whereIn('id', $ids)->update(['is_delete' => true]);
    }

    /**
     * Find media file by ID
     *
     * @param int $id
     * @return MediaFile|null
     */
    public function find(int $id): ?MediaFile
    {
        return MediaFile::where('id', $id)
            ->where('is_delete', false)
            ->first();
    }


}
