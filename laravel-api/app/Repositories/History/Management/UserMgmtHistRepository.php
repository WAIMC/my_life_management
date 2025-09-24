<?php

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Models\History\Management\UserMgmtHist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserMgmtHistRepository implements UserMgmtHistInterface
{
    /**
     * @var UserMgmtHist
     */
    protected $model;

    /**
     * UserMgmtHistRepository constructor.
     *
     * @param UserMgmtHist $model
     */
    public function __construct(UserMgmtHist $model)
    {
        $this->model = $model;
    }

    /**
     * Get all user history records with optional filtering and pagination
     *
     * @param array $payload
     * @return Collection|LengthAwarePaginator
     */
    public function getAll(array $payload = [])
    {
        $query = $this->model->with(['user', 'role', 'department', 'author']);

        // Filter by user_mgmt_id if provided
        if (isset($payload['user_mgmt_id'])) {
            $query->where('user_mgmt_id', $payload['user_mgmt_id']);
        }

        // Filter by action if provided
        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        // Filter by author_id if provided
        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        // Filter by date range if provided
        if (isset($payload['date_from'])) {
            $query->where('created_at', '>=', $payload['date_from']);
        }

        if (isset($payload['date_to'])) {
            $query->where('created_at', '<=', $payload['date_to']);
        }

        // Sort by
        $sortBy = isset($payload['sort_by']) ? $payload['sort_by'] : 'created_at';
        $sortDirection = isset($payload['sort_direction']) ? $payload['sort_direction'] : 'desc';
        $query->orderBy($sortBy, $sortDirection);

        // Return paginated results if per_page is set
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Get user history record by ID
     *
     * @param int $id
     * @return UserMgmtHist|null
     */
    public function getById(int $id)
    {
        return $this->model->with(['user', 'role', 'department', 'author'])->find($id);
    }

    /**
     * Get user history records by user management ID
     *
     * @param int $userMgmtId
     * @param array $payload
     * @return Collection|LengthAwarePaginator
     */
    public function getByUserMgmtId(int $userMgmtId, array $payload = [])
    {
        $query = $this->model->with(['user', 'role', 'department', 'author'])
            ->where('user_mgmt_id', $userMgmtId);

        // Filter by action if provided
        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        // Sort by
        $sortBy = isset($payload['sort_by']) ? $payload['sort_by'] : 'created_at';
        $sortDirection = isset($payload['sort_direction']) ? $payload['sort_direction'] : 'desc';
        $query->orderBy($sortBy, $sortDirection);

        // Return paginated results if per_page is set
        if (isset($payload['per_page'])) {
            return $query->paginate($payload['per_page']);
        }

        return $query->get();
    }

    /**
     * Create a new user history record
     *
     * @param array $payload
     * @return UserMgmtHist
     */
    public function create(array $payload)
    {
        $userMgmtHist = new UserMgmtHist();

        foreach ($payload as $key => $value) {
            if (in_array($key, $userMgmtHist->getFillable())) {
                $userMgmtHist->{$key} = $value;
            }
        }

        $userMgmtHist->save();

        return $userMgmtHist;
    }
}