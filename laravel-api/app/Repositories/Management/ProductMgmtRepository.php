<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\ProductMgmtInterface;
use App\Models\Management\ProductMgmt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductMgmtRepository implements ProductMgmtInterface
{
    /**
     * @var ProductMgmt
     */
    protected ProductMgmt $model;

    /**
     * ProductMgmtRepository constructor.
     *
     * @param ProductMgmt $product
     */
    public function __construct(ProductMgmt $product)
    {
        $this->model = $product;
    }

    /**
     * Get all products with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['category_id'])) {
            $query->where('category_id', $payload['category_id']);
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['search'])) {
            $search = $payload['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Order by
        $sortBy = $payload['sort_by'] ?? 'rank_order';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get product by ID
     *
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function getById(int $id): mixed
    {
        return $this->model->with('category')->findOrFail($id);
    }

    /**
     * Create a new product
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update an existing product
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function update(array $payload, int $id): mixed
    {
        $product = $this->model->findOrFail($id);

        foreach ($payload as $key => $value) {
            if (in_array($key, $this->model->getFillable())) {
                $product->{$key} = $value;
            }
        }

        $product->save();
        return $product;
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $product = $this->model->findOrFail($id);
        return $product->delete();
    }
}
