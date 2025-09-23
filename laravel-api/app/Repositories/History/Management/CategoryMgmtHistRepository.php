<?php

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\CategoryMgmtHistInterface;
use App\Models\History\Management\CategoryMgmtHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryMgmtHistRepository implements CategoryMgmtHistInterface
{
    protected CategoryMgmtHist $model;

    /**
     * CategoryMgmtHistRepository constructor
     */
    public function __construct()
    {
        $this->model = new CategoryMgmtHist();
    }

    /**
     * Get all category history records with filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters from payload
        if (isset($payload['category_mgmt_id'])) {
            $query->where('category_mgmt_id', $payload['category_mgmt_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['from_date'])) {
            $query->where('created_at', '>=', $payload['from_date']);
        }

        if (isset($payload['to_date'])) {
            $query->where('created_at', '<=', $payload['to_date']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Find category history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Find category history by category ID
     *
     * @param int $categoryId
     * @return mixed
     */
    public function findByCategoryId(int $categoryId): mixed
    {
        return $this->model->where('category_mgmt_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create new category history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        $categoryHistory = new $this->model;

        if (isset($payload['category_mgmt_id'])) {
            $categoryHistory->category_mgmt_id = $payload['category_mgmt_id'];
        }

        if (isset($payload['parent_id'])) {
            $categoryHistory->parent_id = $payload['parent_id'];
        }

        if (isset($payload['name'])) {
            $categoryHistory->name = $payload['name'];
        }

        if (isset($payload['slug'])) {
            $categoryHistory->slug = $payload['slug'];
        }

        if (isset($payload['description'])) {
            $categoryHistory->description = $payload['description'];
        }

        if (isset($payload['status'])) {
            $categoryHistory->status = $payload['status'];
        }

        if (isset($payload['is_display'])) {
            $categoryHistory->is_display = $payload['is_display'];
        }

        if (isset($payload['rank_order'])) {
            $categoryHistory->rank_order = $payload['rank_order'];
        }

        if (isset($payload['action'])) {
            $categoryHistory->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $categoryHistory->author_id = $payload['author_id'];
        }

        if (isset($payload['created_at'])) {
            $categoryHistory->created_at = $payload['created_at'];
        } else {
            $categoryHistory->created_at = now()->format('Y-m-d H:i:s');
        }

        $categoryHistory->save();
        return $categoryHistory;
    }
}
