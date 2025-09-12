<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Interfaces\Master\AdminInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminRepository extends BaseRepository implements AdminInterface
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    /**
     * Get account list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select([
                'id',
                'email',
                'user_name',
                'first_name',
                'last_name',
                'address',
                'phone_number',
                'birth',
                'gender',
                'status',
                'is_active',
                'avatar',
                'updated_at',
            ]);

        if (isset($payload['email'])) {
            $query->where('email', $payload['email']);
        }

        if (isset($payload['user_name'])) {
            $query->where('user_name', $payload['user_name']);
        }

        if (isset($payload['first_name'])) {
            $query->where('first_name', 'like', '%' . $payload['first_name'] . '%');
        }

        if (isset($payload['last_name'])) {
            $query->where('last_name', 'like', '%' . $payload['last_name'] . '%');
        }

        if (isset($payload['address'])) {
            $query->where('address', 'like', '%' . $payload['address'] . '%');
        }

        if (isset($payload['phone_number'])) {
            $query->where('phone_number', $payload['phone_number']);
        }

        if (isset($payload['birth'])) {
            $query->where('birth', $payload['birth']);
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

        if (isset($payload['avatar'])) {
            $query->where('avatar', $payload['avatar']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Create new admin account
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['email'] = $payload['email'] ?? null;
        $data['user_name'] = $payload['username'] ?? null;
        $data['first_name'] = $payload['first_name'] ?? null;
        $data['last_name'] = $payload['last_name'] ?? null;
        $data['password'] = $payload['password'] ? Hash::make($payload['password']) : null;
        $data['address'] = $payload['address'] ?? null;
        $data['phone_number'] = $payload['phone_number'] ?? null;
        $data['birth'] = $payload['birth'] ?? null;
        $data['gender'] = $payload['gender'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['avatar'] = $payload['avatar'] ?? null;

        $this->model->create($data);
    }


    /**
     * Update admin
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $admin = $this->model->findById($payload['id']);
        $admin['email'] = $payload['email'] ?? null;
        $admin['user_name'] = $payload['username'] ?? null;
        $admin['first_name'] = $payload['first_name'] ?? null;
        $admin['last_name'] = $payload['last_name'] ?? null;
        $admin['password'] = $payload['password'] ? Hash::make($payload['password']) : null;
        $admin['address'] = $payload['address'] ?? null;
        $admin['phone_number'] = $payload['phone_number'] ?? null;
        $admin['birth'] = $payload['birth'] ?? null;
        $admin['gender'] = $payload['gender'] ?? null;
        $admin['status'] = $payload['status'] ?? null;
        $admin['avatar'] = $payload['avatar'] ?? null;
        $admin->save();
    }

    /**
     * Delete Role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
