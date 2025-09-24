<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Models\History\Master\DepartmentMstHist;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class DepartmentMstHistRepository extends BaseRepository implements DepartmentMstHistInterface
{
    public function __construct(DepartmentMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all department history records
     *
     * @param array $payload
     * @return \Illuminate\Support\Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->whereIn('id', $payload['id']);
        }

        if (isset($payload['department_mst_id'])) {
            $query->where('department_mst_id', $payload['department_mst_id']);
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['code'])) {
            $query->where('code', 'like', '%' . $payload['code'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
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
     * Create new department history record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['id'] = $payload['id'];
        $data['department_mst_id'] = $payload['department_mst_id'];
        $data['code'] = $payload['code'];
        $data['name'] = $payload['name'];
        $data['status'] = $payload['status'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $this->model->id;
    }

    /**
     * Update department history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['id'] = $payload['id'];
        $data['department_mst_id'] = $payload['department_mst_id'];
        $data['code'] = $payload['code'];
        $data['name'] = $payload['name'];
        $data['status'] = $payload['status'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete department history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
