<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Models\History\Master\PolicyDepartmentMstHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PolicyDepartmentMstHistRepository implements PolicyDepartmentMstHistInterface
{
    /**
     * @var PolicyDepartmentMstHist
     */
    protected PolicyDepartmentMstHist $model;

    /**
     * PolicyDepartmentMstHistRepository constructor.
     *
     * @param PolicyDepartmentMstHist $model
     */
    public function __construct(PolicyDepartmentMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all history records.
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['policy_department_mst_id'])) {
            $query->where('policy_department_mst_id', $payload['policy_department_mst_id']);
        }

        if (isset($payload['table_name'])) {
            $query->where('table_name', $payload['table_name']);
        }

        if (isset($payload['row_id'])) {
            $query->where('row_id', $payload['row_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        // Default sorting by created_at in descending order
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get history record by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed
    {
        $record = $this->getById($id);

        if (isset($payload['policy_department_mst_id'])) {
            $record->policy_department_mst_id = $payload['policy_department_mst_id'];
        }

        if (isset($payload['table_name'])) {
            $record->table_name = $payload['table_name'];
        }

        if (isset($payload['row_id'])) {
            $record->row_id = $payload['row_id'];
        }

        if (isset($payload['action'])) {
            $record->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $record->author_id = $payload['author_id'];
        }

        $record->save();
        return $record;
    }

    /**
     * Delete history record.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $record = $this->getById($id);
        return $record->delete();
    }
}
