<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\DepartmentMstInterface;
use App\Models\Master\DepartmentMst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DepartmentMstRepository implements DepartmentMstInterface
{
    protected DepartmentMst $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new DepartmentMst();
    }

    /**
     * Get all departments with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters based on payload
        if (isset($payload['code'])) {
            $query->where('code', 'like', '%' . $payload['code'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
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
     * Get department by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get department by code
     *
     * @param string $code
     * @return mixed
     */
    public function getByCode(string $code): mixed
    {
        $department = $this->model->where('code', $code)->first();

        if (!$department) {
            throw new ModelNotFoundException('Department with code ' . $code . ' not found');
        }

        return $department;
    }

    /**
     * Create new department
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update department
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
     * Delete department
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
