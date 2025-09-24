<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Models\Master\DepartmentMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class DepartmentMstRepository extends BaseRepository implements DepartmentMstInterface
{
    /**
     * Constructor
     */
    public function __construct(DepartmentMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all departments with optional filtering
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

        if (isset($payload['code'])) {
            $query->where('code', 'like', '%' . $payload['code'] . '%');
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
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
     * Create new department
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['code'] = $payload['code'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update department
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['code'] = $payload['code'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data->save();

        return $data->id;
    }

    /**
     * Delete department
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
