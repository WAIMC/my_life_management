<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\ProductMgmtHistInterface;
use App\Models\History\Management\ProductMgmtHist;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class ProductMgmtHistRepository extends BaseRepository implements ProductMgmtHistInterface
{
    public function __construct(ProductMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all product history records with optional filtering
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->with(['productMgmt:id,name', 'author:id,username']);

        if (isset($payload['id'])) {
            $query->whereIn('id', (array)$payload['id']);
        }

        $this->applyFilters($query, $payload, [
            'product_mgmt_id',
            'category_mgmt_id',
            'action',
            'author_id',
        ], [
            'name',
            'code',
            'description',
        ]);

        $this->applyDateRange($query, $payload);
        $this->applySorting($query, $payload, 'id', 'desc');

        $perPage = $payload['per_page'] ?? 15;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create new product history record
     *
     * @param array $payload
     * @return int
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
     * Update product history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $model = $this->model->findOrFail($payload['id']);
        $model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();
        return $model->id;
    }

    /**
     * Delete product history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
