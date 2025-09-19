<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\RoleMstInterface;
use App\Models\Master\RoleMst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleMstRepository implements RoleMstInterface
{
    protected RoleMst $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new RoleMst();
    }

    /**
     * Get all roles with optional filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters based on payload
        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['permission'])) {
            $query->where('permission', 'like', '%' . $payload['permission'] . '%');
        }

        if (isset($payload['is_active']) && is_bool($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
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
     * Get role by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new role
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update role
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
     * Delete role
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
