<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\UserMgmtInterface;
use App\Models\Management\UserMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserMgmtRepository extends BaseRepository implements UserMgmtInterface
{
    public function __construct(UserMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list of users
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
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

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('id', 'desc');

        return $query->get();
    }

    /**
     * Create user
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['role_id'] = $payload['role_id'] ?? null;
        $data['department_id'] = $payload['department_id'] ?? null;
        $data['email'] = $payload['email'] ?? null;
        $data['user_name'] = $payload['user_name'] ?? null;
        $data['password'] = $payload['password'] ? Hash::make($payload['password']) : null;
        $data['first_name'] = $payload['first_name'] ?? null;
        $data['last_name'] = $payload['last_name'] ?? null;
        $data['address'] = $payload['address'] ?? null;
        $data['phone_number'] = $payload['phone_number'] ?? null;
        $data['birth'] = $payload['birth'] ?? null;
        $data['gender'] = $payload['gender'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['avatar'] = $payload['avatar'] ?? null;
        $data['email_verified_at'] = $payload['email_verified_at'] ?? null;
        $data['remember_token'] = $payload['remember_token'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update user
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['role_id'] = $payload['role_id'] ?? null;
        $data['department_id'] = $payload['department_id'] ?? null;
        $data['email'] = $payload['email'] ?? null;
        $data['user_name'] = $payload['user_name'] ?? null;
        $data['password'] = $payload['password'] ? Hash::make($payload['password']) : null;
        $data['first_name'] = $payload['first_name'] ?? null;
        $data['last_name'] = $payload['last_name'] ?? null;
        $data['address'] = $payload['address'] ?? null;
        $data['phone_number'] = $payload['phone_number'] ?? null;
        $data['birth'] = $payload['birth'] ?? null;
        $data['gender'] = $payload['gender'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['avatar'] = $payload['avatar'] ?? null;
        $data['email_verified_at'] = $payload['email_verified_at'] ?? null;
        $data['remember_token'] = $payload['remember_token'] ?? null;
        $data->save();

        return $data->id;
    }

    /**
     * Delete user
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
