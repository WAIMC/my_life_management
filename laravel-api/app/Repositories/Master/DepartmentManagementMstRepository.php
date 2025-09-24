<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Models\Master\DepartmentManagementMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class DepartmentManagementMstRepository extends BaseRepository implements DepartmentManagementMstInterface
{
    /**
     * Constructor
     */
    public function __construct(DepartmentManagementMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all department management relations with optional filtering
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
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

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('department_id')
            ->orderBy('policy_department_id');

        return $query->get();
    }

    /**
     * Create new department management relation
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete department management relation
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['department_id'] . ', ' . (int)$item['policy_department_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(department_id, policy_department_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get admin departments id
     *
     * @param array $departmentMgmtIds
     * @return Collection
     */
    public function getDepartmentMgmtMstId(array $departmentMgmtIds): Collection
    {
        return $this->model
            ->whereRaw("(department_id, policy_department_id) IN (" . implode(", ", $departmentMgmtIds) . ")")
            ->pluck('department_id', 'policy_department_id');
    }
}
