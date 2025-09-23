<?php

namespace App\Repositories\History\Master;

use App\Interfaces\History\Master\RoleMstHistInterface;
use App\Models\History\Master\RoleMstHist;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RoleMstHistRepository implements RoleMstHistInterface
{
    /**
     * @var RoleMstHist
     */
    protected RoleMstHist $model;

    /**
     * RoleMstHistRepository constructor.
     *
     * @param RoleMstHist $model
     */
    public function __construct(RoleMstHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all role histories with pagination
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getAll(array $params = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply role filter if provided
        if (isset($params['role_mst_id']) && !empty($params['role_mst_id'])) {
            $query->where('role_mst_id', $params['role_mst_id']);
        }

        // Apply author filter if provided
        if (isset($params['author_id']) && !empty($params['author_id'])) {
            $query->where('author_id', $params['author_id']);
        }

        // Apply action filter if provided
        if (isset($params['action']) && !empty($params['action'])) {
            $query->where('action', $params['action']);
        }

        // Apply date range filter if provided
        if (isset($params['date_from']) && !empty($params['date_from'])) {
            $query->whereDate('created_at', '>=', $params['date_from']);
        }

        if (isset($params['date_to']) && !empty($params['date_to'])) {
            $query->whereDate('created_at', '<=', $params['date_to']);
        }

        // Apply sorting
        $sortField = $params['sort_by'] ?? 'created_at';
        $sortDirection = $params['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Get paginated results
        $perPage = $params['per_page'] ?? 15;

        return $query->with(['role', 'author'])->paginate($perPage);
    }

    /**
     * Get role history by ID
     *
     * @param int $id
     * @return object|null
     */
    public function findById(int $id): ?object
    {
        return $this->model->with(['role', 'author'])->find($id);
    }

    /**
     * Create new role history
     *
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * Get history by role ID
     *
     * @param int $roleId
     * @return Collection
     */
    public function getByRoleId(int $roleId): Collection
    {
        return $this->model->where('role_mst_id', $roleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get history by author ID
     *
     * @param int $authorId
     * @return Collection
     */
    public function getByAuthorId(int $authorId): Collection
    {
        return $this->model->where('author_id', $authorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get history by action type
     *
     * @param int $action
     * @return Collection
     */
    public function getByAction(int $action): Collection
    {
        return $this->model->where('action', $action)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
