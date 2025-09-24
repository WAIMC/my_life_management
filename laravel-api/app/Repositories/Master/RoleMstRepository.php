<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\RoleMstInterface;
use App\Models\Master\RoleMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class RoleMstRepository extends BaseRepository implements RoleMstInterface
{
    public function __construct(RoleMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all roles with optional filtering
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

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['permission'])) {
            $query->where('permission', 'like', '%' . $payload['permission'] . '%');
        }

        if (isset($payload['is_active']) && is_bool($payload['is_active'])) {
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

        return $query->orderBy('id')->get();
    }

    /**
     * Create new role
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['name'] = $payload['name'];
        $data['permission'] = $payload['permission'];
        $data['is_active'] = $payload['is_active'];
        $this->model->create($data);

        return $data->id;
    }

    /**
     * Update role
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['name'] = $payload['name'];
        $data['permission'] = $payload['permission'];
        $data['is_active'] = $payload['is_active'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
