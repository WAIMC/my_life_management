<?php

namespace App\Repositories\History\Management;

use App\Constants\CommonVal;
use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Models\History\Management\UserMgmtHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class UserMgmtHistRepository extends BaseRepository implements UserMgmtHistInterface
{
    public function __construct(UserMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all user history records with optional filtering and pagination
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->whereIn('id', $payload['id']);
        }

        if (isset($payload['user_mgmt_id'])) {
            $query->where('user_mgmt_id', $payload['user_mgmt_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
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
     * Create new user history record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['user_mgmt_id'] = $payload['user_mgmt_id'];
        $data['email'] = $payload['email'];
        $data['user_name'] = $payload['user_name'];
        $data['password'] = $payload['password'];
        $data['first_name'] = $payload['first_name'];
        $data['last_name'] = $payload['last_name'];
        $data['address'] = $payload['address'];
        $data['phone_number'] = $payload['phone_number'];
        $data['birth'] = $payload['birth'];
        $data['gender'] = $payload['gender'];
        $data['status'] = $payload['status'];
        $data['is_active'] = $payload['is_active'];
        $data['avatar'] = $payload['avatar'];
        $data['email_verified_at'] = $payload['email_verified_at'];
        $data['remember_token'] = $payload['remember_token'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update user history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['admin_mst_id'] = $payload['admin_mst_id'];
        $data['email'] = $payload['email'];
        $data['user_name'] = $payload['user_name'];
        $data['password'] = $payload['password'];
        $data['first_name'] = $payload['first_name'];
        $data['last_name'] = $payload['last_name'];
        $data['address'] = $payload['address'];
        $data['phone_number'] = $payload['phone_number'];
        $data['birth'] = $payload['birth'];
        $data['gender'] = $payload['gender'];
        $data['status'] = $payload['status'];
        $data['is_active'] = $payload['is_active'];
        $data['avatar'] = $payload['avatar'];
        $data['email_verified_at'] = $payload['email_verified_at'];
        $data['remember_token'] = $payload['remember_token'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete user history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
