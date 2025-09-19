<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Models\Master\DepartmentManagementMst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DepartmentManagementMstRepository implements DepartmentManagementMstInterface
{
    protected DepartmentManagementMst $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new DepartmentManagementMst();
    }

    /**
     * Get all department management relations with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters based on payload
        if (isset($payload['department_id'])) {
            $query->where('department_id', $payload['department_id']);
        }

        if (isset($payload['policy_department_id'])) {
            $query->where('policy_department_id', $payload['policy_department_id']);
        }

        // Date range filter
        if (isset($payload['created_from'])) {
            $query->where('created_at', '>=', $payload['created_from']);
        }

        if (isset($payload['created_to'])) {
            $query->where('created_at', '<=', $payload['created_to']);
        }

        // Include related models if requested
        if (isset($payload['with_department']) && $payload['with_department']) {
            $query->with('department');
        }

        if (isset($payload['with_policy_department']) && $payload['with_policy_department']) {
            $query->with('policyDepartment');
        }

        return $query->orderBy('department_id')
            ->orderBy('policy_department_id')
            ->paginate($payload['per_page'] ?? 15);
    }

    /**
     * Get department management relation by IDs
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function getById(int $departmentId, int $policyDepartmentId): mixed
    {
        $record = $this->model->query()
            ->where('department_id', $departmentId)
            ->where('policy_department_id', $policyDepartmentId)
            ->first();

        if (!$record) {
            throw new ModelNotFoundException('Department management relation not found');
        }

        return $record;
    }

    /**
     * Create new department management relation
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Delete department management relation
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function delete(int $departmentId, int $policyDepartmentId): mixed
    {
        $record = $this->getById($departmentId, $policyDepartmentId);
        return $record->delete();
    }

    /**
     * Get department management relations by department ID
     *
     * @param int $departmentId
     * @return mixed
     */
    public function getByDepartmentId(int $departmentId): mixed
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    /**
     * Get department management relations by policy department ID
     *
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function getByPolicyDepartmentId(int $policyDepartmentId): mixed
    {
        return $this->model->where('policy_department_id', $policyDepartmentId)->get();
    }
}
