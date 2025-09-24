<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Models\History\Master\PolicyDepartmentMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class PolicyDepartmentMstHistRepository extends BaseRepository implements PolicyDepartmentMstHistInterface
{
    public function __construct(PolicyDepartmentMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all history records.
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

        if (isset($payload['policy_department_mst_id'])) {
            $query->where('policy_department_mst_id', $payload['policy_department_mst_id']);
        }

        if (isset($payload['table_name'])) {
            $query->where('table_name', $payload['table_name']);
        }

        if (isset($payload['row_id'])) {
            $query->where('row_id', $payload['row_id']);
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
     * Create new history record.
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['policy_department_mst_id'] = $payload['policy_department_mst_id'];
        $data['table_name'] = $payload['table_name'];
        $data['row_id'] = $payload['row_id'];
        $data['action'] = $payload['action'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update history record.
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['policy_department_mst_id'] = $payload['policy_department_mst_id'];
        $data['table_name'] = $payload['table_name'];
        $data['row_id'] = $payload['row_id'];
        $data['action'] = $payload['action'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete history record.
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
