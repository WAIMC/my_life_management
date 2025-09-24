<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\RoleMstHistInterface;
use App\Models\History\Master\RoleMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class RoleMstHistRepository extends BaseRepository implements RoleMstHistInterface
{
    public function __construct(RoleMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all role histories with pagination
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
     * Create new role history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['role_mst_id'] = $payload['role_mst_id'];
        $data['name'] = $payload['name'];
        $data['permission'] = $payload['permission'];
        $data['is_active'] = $payload['is_active'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update role history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['role_mst_id'] = $payload['role_mst_id'];
        $data['name'] = $payload['name'];
        $data['permission'] = $payload['permission'];
        $data['is_active'] = $payload['is_active'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete role history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
