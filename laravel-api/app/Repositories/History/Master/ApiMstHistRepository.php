<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Models\History\Master\ApiMstHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ApiMstHistRepository implements ApiMstHistInterface
{
    protected ApiMstHist $model;

    /**
     * ApiMstHistRepository constructor
     */
    public function __construct()
    {
        $this->model = new ApiMstHist();
    }

    /**
     * Get all API history records
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters from payload
        if (isset($payload['api_mst_id'])) {
            $query->where('api_mst_id', $payload['api_mst_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['feature_id'])) {
            $query->where('feature_id', $payload['feature_id']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get API history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Get API history by API master ID
     *
     * @param int $apiMstId
     * @return mixed
     */
    public function findByApiMstId(int $apiMstId): mixed
    {
        return $this->model->where('api_mst_id', $apiMstId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create new API history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }
}
