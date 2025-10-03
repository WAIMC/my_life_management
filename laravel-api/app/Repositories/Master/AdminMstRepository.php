<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\AdminMstInterface;
use App\Models\Master\AdminMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminMstRepository extends BaseRepository implements AdminMstInterface
{
    public function __construct(AdminMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
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
            $query->where('user_name', 'like', '%' . $payload['user_name'] . '%');
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

        $query->orderBy('id');

        return $query->get();
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data['email'] = $payload['email'] ?? null;
        $data['user_name'] = $payload['username'] ?? null;
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
        $data['is_delete'] = $payload['is_delete'] ?? null;
        $data['remember_token'] = $payload['remember_token'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }


    /**
     * Update record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $record = $this->model->find($payload['id']);
        $record['email'] = $payload['email'] ?? null;
        $record['user_name'] = $payload['username'] ?? null;
        $record['password'] = $payload['password'] ? Hash::make($payload['password']) : null;
        $record['first_name'] = $payload['first_name'] ?? null;
        $record['last_name'] = $payload['last_name'] ?? null;
        $record['address'] = $payload['address'] ?? null;
        $record['phone_number'] = $payload['phone_number'] ?? null;
        $record['birth'] = $payload['birth'] ?? null;
        $record['gender'] = $payload['gender'] ?? null;
        $record['status'] = $payload['status'] ?? null;
        $record['is_active'] = $payload['is_active'] ?? null;
        $record['avatar'] = $payload['avatar'] ?? null;
        $record['email_verified_at'] = $payload['email_verified_at'] ?? null;
        $record['is_delete'] = $payload['is_delete'] ?? null;
        $record['remember_token'] = $payload['remember_token'] ?? null;
        $record->save();

        return $record->id;
    }

    /**
     * Delete record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->update(['is_delete' => IsDelete::TRUE->value]);
    }

}
