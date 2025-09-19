<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\FeatureMstInterface;
use App\Models\Master\FeatureMst;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FeatureMstRepository implements FeatureMstInterface
{
    protected FeatureMst $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new FeatureMst();
    }

    /**
     * Get all features with optional filtering
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

        if (isset($payload['group_name'])) {
            $query->where('group_name', 'like', '%' . $payload['group_name'] . '%');
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', '%' . $payload['description'] . '%');
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
     * Get feature by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new feature
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update feature
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
     * Delete feature
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
