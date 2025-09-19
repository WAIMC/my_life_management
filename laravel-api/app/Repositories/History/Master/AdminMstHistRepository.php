<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\AdminMstHistInterface;
use App\Models\History\Master\AdminMstHist;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminMstHistRepository implements AdminMstHistInterface
{
    protected AdminMstHist $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new AdminMstHist();
    }

    /**
     * Get all admin history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed
    {
        $query = $this->model->query();

        // Apply filters based on payload
        if (isset($payload['admin_mst_id'])) {
            $query->where('admin_mst_id', $payload['admin_mst_id']);
        }

        if (isset($payload['email'])) {
            $query->where('email', 'like', '%' . $payload['email'] . '%');
        }

        if (isset($payload['user_name'])) {
            $query->where('user_name', 'like', '%' . $payload['user_name'] . '%');
        }

        if (isset($payload['first_name'])) {
            $query->where('first_name', 'like', '%' . $payload['first_name'] . '%');
        }

        if (isset($payload['last_name'])) {
            $query->where('last_name', 'like', '%' . $payload['last_name'] . '%');
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        // Date range filter
        if (isset($payload['created_from'])) {
            $query->where('created_at', '>=', $payload['created_from']);
        }

        if (isset($payload['created_to'])) {
            $query->where('created_at', '<=', $payload['created_to']);
        }

        return $query->orderBy('id', 'desc')->paginate(
            $payload['per_page'] ?? 15
        );
    }

    /**
     * Get admin history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new admin history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update admin history record
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
     * Delete admin history record
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
