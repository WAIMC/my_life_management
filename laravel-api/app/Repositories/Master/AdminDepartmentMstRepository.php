<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\AdminDepartmentMstInterface;
use App\Models\Master\AdminDepartmentMst;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class AdminDepartmentMstRepository extends BaseRepository implements AdminDepartmentMstInterface
{
    public function __construct(AdminDepartmentMst $model)
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
                'admin_mst_id',
                'department_mst_id',
                'updated_at',
            ])
            ->with(['adminMst:id,username,email', 'departmentMst:id,code,name']); // Eager load

        // Apply filters
        $this->applyFilters($query, $payload, [
            'admin_mst_id',
            'department_mst_id',
        ]);

        // Apply date range
        $this->applyDateRange($query, $payload);

        // Apply sorting
        $this->applySorting($query, $payload, 'admin_mst_id');

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
            return '(' . (int)$item['admin_mst_id'] . ', ' . (int)$item['department_mst_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(admin_mst_id, department_mst_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getAdminDepartmentMstId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['admin_mst_id'] . ', ' . (int)$item['department_mst_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(admin_mst_id, department_mst_id) IN (" . implode(", ", $values) . ")")
            ->pluck('admin_mst_id', 'department_mst_id');
    }
}
