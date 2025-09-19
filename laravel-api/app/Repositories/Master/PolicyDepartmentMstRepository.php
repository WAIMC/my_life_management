<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Models\Master\PolicyDepartmentMst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PolicyDepartmentMstRepository implements PolicyDepartmentMstInterface
{
    protected PolicyDepartmentMst $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new PolicyDepartmentMst();
    }

    /**
     * Get all policy departments with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters based on payload
        if (isset($payload['table_name'])) {
            $query->where('table_name', 'like', '%' . $payload['table_name'] . '%');
        }

        if (isset($payload['row_id'])) {
            $query->where('row_id', $payload['row_id']);
        }

        // Date range filter
        if (isset($payload['created_from'])) {
            $query->where('created_at', '>=', $payload['created_from']);
        }

        if (isset($payload['created_to'])) {
            $query->where('created_at', '<=', $payload['created_to']);
        }

        return $query->orderBy('id')->paginate(
            $payload['per_page'] ?? 15
        );
    }

    /**
     * Get policy department by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get policy departments by table name
     *
     * @param string $tableName
     * @return mixed
     */
    public function getByTableName(string $tableName): mixed
    {
        return $this->model->where('table_name', $tableName)->get();
    }

    /**
     * Get policy department by table name and row ID
     *
     * @param string $tableName
     * @param int $rowId
     * @return mixed
     */
    public function getByTableNameAndRowId(string $tableName, int $rowId): mixed
    {
        $policyDepartment = $this->model->where('table_name', $tableName)
            ->where('row_id', $rowId)
            ->first();

        if (!$policyDepartment) {
            throw new ModelNotFoundException('Policy department not found for table ' . $tableName . ' and row ' . $rowId);
        }

        return $policyDepartment;
    }

    /**
     * Create new policy department
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update policy department
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed
    {
        $record = $this->model->findOrFail($id);

        foreach ($payload as $key => $value) {
            if (in_array($key, $this->model->getFillable())) {
                $record->{$key} = $value;
            }
        }

        $record->save();
        return $record;
    }

    /**
     * Delete policy department
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $record = $this->model->findOrFail($id);
        return $record->delete();
    }
}
