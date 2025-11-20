<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Models\Master\DepartmentManagementMst;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class DepartmentManagementMstRepository extends BaseRepository implements DepartmentManagementMstInterface
{
    public function __construct(DepartmentManagementMst $model)
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
                'department_mst_id',
                'policy_department_mst_id',
                'updated_at',
            ])
            ->with(['departmentMst:id,code,name', 'policyDepartmentMst:id,table_name,row_id']); // Eager load

        // Apply filters
        $this->applyFilters($query, $payload, [
            'department_mst_id',
            'policy_department_mst_id',
        ]);

        // Apply date range
        $this->applyDateRange($query, $payload);

        // Apply sorting
        $this->applySorting($query, $payload, 'department_mst_id');

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
            return '(' . (int)$item['department_mst_id'] . ', ' . (int)$item['policy_department_mst_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(department_mst_id, policy_department_mst_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getDepartmentManagementMstId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['department_mst_id'] . ', ' . (int)$item['policy_department_mst_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(department_mst_id, policy_department_mst_id) IN (" . implode(", ", $values) . ")")
            ->pluck('department_mst_id', 'policy_department_mst_id');
    }
}
