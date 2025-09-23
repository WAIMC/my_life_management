<?php

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\ProductMgmtHistInterface;
use App\Models\History\Management\ProductMgmtHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductMgmtHistRepository implements ProductMgmtHistInterface
{
    /**
     * @var ProductMgmtHist
     */
    protected ProductMgmtHist $model;

    /**
     * ProductMgmtHistRepository constructor.
     *
     * @param ProductMgmtHist $productHist
     */
    public function __construct(ProductMgmtHist $productHist)
    {
        $this->model = $productHist;
    }

    /**
     * Get all product history records with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['product_mgmt_id'])) {
            $query->where('product_mgmt_id', $payload['product_mgmt_id']);
        }

        if (isset($payload['category_id'])) {
            $query->where('category_id', $payload['category_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['search'])) {
            $search = $payload['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($payload['date_from'])) {
            $query->where('created_at', '>=', $payload['date_from']);
        }

        if (isset($payload['date_to'])) {
            $query->where('created_at', '<=', $payload['date_to']);
        }

        // Order by
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get product history record by ID
     *
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getById(int $id): mixed
    {
        return $this->model->with(['product', 'category'])->findOrFail($id);
    }

    /**
     * Create a new product history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update an existing product history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function update(array $payload, int $id): mixed
    {
        $productHist = $this->model->findOrFail($id);

        foreach ($payload as $key => $value) {
            if (in_array($key, $this->model->getFillable())) {
                $productHist->{$key} = $value;
            }
        }

        $productHist->save();
        return $productHist;
    }

    /**
     * Delete a product history record
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $productHist = $this->model->findOrFail($id);
        return $productHist->delete();
    }
}
