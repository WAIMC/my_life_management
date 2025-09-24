<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Models\Master\PolicyDepartmentMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class PolicyDepartmentMstRepository extends BaseRepository implements PolicyDepartmentMstInterface
{
    public function __construct(PolicyDepartmentMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all policy departments with optional filtering
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

        if (isset($payload['table_name'])) {
            $query->where('table_name', 'like', '%' . $payload['table_name'] . '%');
        }

        if (isset($payload['row_id'])) {
            $query->where('row_id', $payload['row_id']);
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
     * Create new policy department
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['table_name'] = $payload['table_name'] ?? null;
        $data['row_id'] = $payload['row_id'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update policy department
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['table_name'] = $payload['table_name'] ?? null;
        $data['row_id'] = $payload['row_id'] ?? null;
        $data->save();

        return $data->id;
    }

    /**
     * Delete policy department
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
