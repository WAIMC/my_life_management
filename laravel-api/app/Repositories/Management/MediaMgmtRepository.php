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
            $parentPath = rtrim($payload['parent_path'], '/');
            
            if ($parentPath === '' || $parentPath === '/') {
                // Root level - only items directly under root (no additional slashes)
                $query->where(function ($q) {
                    $q->whereRaw("virtual_path ~ '^/[^/]+/?$'");
                });
            } else {
                // Get only direct children of the parent folder
                $parentPath = $parentPath . '/';
                $query->where('virtual_path', 'LIKE', $parentPath . '%')
                    ->whereRaw("virtual_path ~ ?", ['^' . preg_quote($parentPath, '/') . '[^/]+/?$']);
            }
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
        $this->applySorting($query, $payload, 'id', 'desc');

        // Pagination
        $perPage = $payload['per_page'] ?? 50;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Find by ID
     */
    public function find(int $id)
    {
        return $this->model->find($id);
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
