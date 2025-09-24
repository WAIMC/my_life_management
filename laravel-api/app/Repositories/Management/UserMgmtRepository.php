<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\UserMgmtInterface;
use App\Models\Management\UserMgmt;
use Exception;
use Illuminate\Support\Facades\DB;

class UserMgmtRepository implements UserMgmtInterface
{
    /**
     * @var UserMgmt
     */
    protected $model;

    /**
     * UserMgmtRepository constructor.
     *
     * @param UserMgmt $model
     */
    public function __construct(UserMgmt $model)
    {
        $this->model = $model;
    }

    /**
     * Get list of users
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload)
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->where('id', $payload['id']);
        }

        if (isset($payload['role_id'])) {
            $query->where('role_id', $payload['role_id']);
        }

        if (isset($payload['department_id'])) {
            $query->where('department_id', $payload['department_id']);
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

        if (isset($payload['gender'])) {
            $query->where('gender', $payload['gender']);
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
        }

        if (isset($payload['created_at'])) {
            $query->where('created_at', 'like', '%' . $payload['created_at'] . '%');
        }

        if (isset($payload['sort_by']) && isset($payload['sort_direction'])) {
            $query->orderBy($payload['sort_by'], $payload['sort_direction']);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Include relationships if requested
        if (isset($payload['with_role']) && $payload['with_role']) {
            $query->with('role');
        }

        if (isset($payload['with_department']) && $payload['with_department']) {
            $query->with('department');
        }

        $perPage = $payload['per_page'] ?? 10;
        
        return $query->paginate($perPage);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->model->with(['role', 'department'])->find($id);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Get user by username
     *
     * @param string $userName
     * @return mixed
     */
    public function getByUserName(string $userName)
    {
        return $this->model->where('user_name', $userName)->first();
    }

    /**
     * Create user
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Update user
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function update(array $payload, int $id)
    {
        $user = $this->model->find($id);
        
        if (!$user) {
            throw new Exception('User not found');
        }

        $user->update($payload);
        return $user->refresh();
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public function delete(int $id)
    {
        $user = $this->model->find($id);
        
        if (!$user) {
            throw new Exception('User not found');
        }

        return $user->delete();
    }

    /**
     * Get users by department ID
     *
     * @param int $departmentId
     * @return mixed
     */
    public function getByDepartmentId(int $departmentId)
    {
        return $this->model->where('department_id', $departmentId)->get();
    }

    /**
     * Get users by role ID
     *
     * @param int $roleId
     * @return mixed
     */
    public function getByRoleId(int $roleId)
    {
        return $this->model->where('role_id', $roleId)->get();
    }
}