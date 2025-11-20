<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\ApiRoleMstInterface;
use App\Models\Master\ApiRoleMst;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class ApiRoleMstRepository extends BaseRepository implements ApiRoleMstInterface
{
    public function __construct(ApiRoleMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list with pagination
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->select([
                'api_mst_id',
                'role_mst_id',
                'updated_at',
            ])
            ->with(['apiMst:id,name,endpoint', 'roleMst:id,name,code']); // Eager load

        // Apply filters
        $this->applyFilters($query, $payload, [
            'api_mst_id',
            'role_mst_id',
        ]);

        // Apply date range
        $this->applyDateRange($query, $payload);

        // Apply sorting
        $this->applySorting($query, $payload, 'api_mst_id');

        // Pagination
        $perPage = $payload['per_page'] ?? 15;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete record
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['api_mst_id'] . ', ' . (int)$item['role_mst_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(api_mst_id, role_mst_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getApiRoleMstId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['api_mst_id'] . ', ' . (int)$item['role_mst_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(api_mst_id, role_mst_id) IN (" . implode(", ", $values) . ")")
            ->pluck('api_mst_id', 'role_mst_id');
    }
}
