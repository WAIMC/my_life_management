<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\CategoryMgmtInterface;
use App\Models\Management\CategoryMgmt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryMgmtRepository implements CategoryMgmtInterface
{
    protected CategoryMgmt $model;

    /**
     * CategoryMgmtRepository constructor
     */
    public function __construct()
    {
        $this->model = new CategoryMgmt();
    }

    /**
     * Get all categories with pagination and filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters from payload
        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['parent_id'])) {
            $query->where('parent_id', $payload['parent_id']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'rank_order';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Find category by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Create new category
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        $category = new $this->model;

        if (isset($payload['parent_id'])) {
            $category->parent_id = $payload['parent_id'];
        }

        if (isset($payload['name'])) {
            $category->name = $payload['name'];
        }

        if (isset($payload['slug'])) {
            $category->slug = $payload['slug'];
        } else if (isset($payload['name'])) {
            $category->slug = Str::slug($payload['name']);
        }

        if (isset($payload['description'])) {
            $category->description = $payload['description'];
        }

        if (isset($payload['status'])) {
            $category->status = $payload['status'];
        }

        if (isset($payload['is_display'])) {
            $category->is_display = $payload['is_display'];
        }

        if (isset($payload['rank_order'])) {
            $category->rank_order = $payload['rank_order'];
        } else {
            // Get the highest rank_order and add 1
            $highestRank = $this->model->max('rank_order');
            $category->rank_order = $highestRank ? $highestRank + 1 : 1;
        }

        $category->created_at = now()->format('Y-m-d H:i:s');
        $category->updated_at = now()->format('Y-m-d H:i:s');

        $category->save();
        return $category;
    }

    /**
     * Update category by ID
     *
     * @param int $id
     * @param array $payload
     * @return mixed
     */
    public function update(int $id, array $payload): mixed
    {
        $category = $this->model->find($id);

        if (!$category) {
            return null;
        }

        if (isset($payload['parent_id'])) {
            // Prevent circular references
            if ($payload['parent_id'] != $id) {
                $category->parent_id = $payload['parent_id'];
            }
        }

        if (isset($payload['name'])) {
            $category->name = $payload['name'];
        }

        if (isset($payload['slug'])) {
            $category->slug = $payload['slug'];
        } else if (isset($payload['name'])) {
            $category->slug = Str::slug($payload['name']);
        }

        if (isset($payload['description'])) {
            $category->description = $payload['description'];
        }

        if (isset($payload['status'])) {
            $category->status = $payload['status'];
        }

        if (isset($payload['is_display'])) {
            $category->is_display = $payload['is_display'];
        }

        if (isset($payload['rank_order'])) {
            $category->rank_order = $payload['rank_order'];
        }

        $category->updated_at = now()->format('Y-m-d H:i:s');

        $category->save();
        return $category;
    }

    /**
     * Delete category by ID
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        $category = $this->model->find($id);

        if (!$category) {
            return false;
        }

        return $category->delete();
    }

    /**
     * Get categories by parent ID
     *
     * @param int $parentId
     * @param array $payload
     * @return mixed
     */
    public function getByParentId(int $parentId, array $payload): mixed
    {
        $query = $this->model->where('parent_id', $parentId);

        // Apply filters from payload
        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'rank_order';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get categories that have no parent (root categories)
     *
     * @param array $payload
     * @return mixed
     */
    public function getRootCategories(array $payload): mixed
    {
        $query = $this->model->where('parent_id', 0);

        // Apply filters from payload
        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'rank_order';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }
}
