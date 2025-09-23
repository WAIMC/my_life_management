<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Models\History\Master\DepartmentMstHist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DepartmentMstHistRepository implements DepartmentMstHistInterface
{
    /**
     * @var DepartmentMstHist
     */
    protected DepartmentMstHist $model;

    /**
     * DepartmentMstHistRepository constructor.
     *
     * @param DepartmentMstHist $model
     */
    public function __construct(DepartmentMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all department history records
     *
     * @param array $payload
     * @return Collection
     */
    public function getAll(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['department_mst_id'])) {
            $query->where('department_mst_id', $payload['department_mst_id']);
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['code'])) {
            $query->where('code', 'like', '%' . $payload['code'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['created_from'])) {
            $query->where('created_at', '>=', $payload['created_from']);
        }

        if (isset($payload['created_to'])) {
            $query->where('created_at', '<=', $payload['created_to']);
        }

        // Order by
        $orderBy = isset($payload['order_by']) ? $payload['order_by'] : 'created_at';
        $order = isset($payload['order']) ? $payload['order'] : 'desc';
        $query->orderBy($orderBy, $order);

        // Pagination
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Get department history record by ID
     *
     * @param int $id
     * @return DepartmentMstHist|null
     */
    public function getById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Get history records by department ID
     *
     * @param int $departmentMstId
     * @param array $payload
     * @return Collection|LengthAwarePaginator
     */
    public function getByDepartmentId(int $departmentMstId, array $payload): mixed
    {
        $query = $this->model->where('department_mst_id', $departmentMstId);

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        // Order by
        $orderBy = isset($payload['order_by']) ? $payload['order_by'] : 'created_at';
        $order = isset($payload['order']) ? $payload['order'] : 'desc';
        $query->orderBy($orderBy, $order);

        // Pagination
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Create new department history record
     *
     * @param array $payload
     * @return DepartmentMstHist
     */
    public function create(array $payload): mixed
    {
        $departmentMstHist = new $this->model;

        if (isset($payload['id'])) {
            $departmentMstHist->id = $payload['id'];
        }

        if (isset($payload['department_mst_id'])) {
            $departmentMstHist->department_mst_id = $payload['department_mst_id'];
        }

        if (isset($payload['code'])) {
            $departmentMstHist->code = $payload['code'];
        }

        if (isset($payload['name'])) {
            $departmentMstHist->name = $payload['name'];
        }

        if (isset($payload['status'])) {
            $departmentMstHist->status = $payload['status'];
        }

        if (isset($payload['action'])) {
            $departmentMstHist->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $departmentMstHist->author_id = $payload['author_id'];
        }

        $departmentMstHist->created_at = now()->format('Y-m-d H:i:s');

        $departmentMstHist->save();

        return $departmentMstHist;
    }

    /**
     * Update department history record
     *
     * @param array $payload
     * @param int $id
     * @return DepartmentMstHist|null
     */
    public function update(array $payload, int $id): mixed
    {
        $departmentMstHist = $this->model->find($id);

        if (!$departmentMstHist) {
            return null;
        }

        if (isset($payload['department_mst_id'])) {
            $departmentMstHist->department_mst_id = $payload['department_mst_id'];
        }

        if (isset($payload['code'])) {
            $departmentMstHist->code = $payload['code'];
        }

        if (isset($payload['name'])) {
            $departmentMstHist->name = $payload['name'];
        }

        if (isset($payload['status'])) {
            $departmentMstHist->status = $payload['status'];
        }

        if (isset($payload['action'])) {
            $departmentMstHist->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $departmentMstHist->author_id = $payload['author_id'];
        }

        $departmentMstHist->save();

        return $departmentMstHist;
    }

    /**
     * Delete department history record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): mixed
    {
        $departmentMstHist = $this->model->find($id);

        if (!$departmentMstHist) {
            return false;
        }

        return $departmentMstHist->delete();
    }
}
