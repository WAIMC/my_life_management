<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\PolicyDepartmentMst;
use Illuminate\Support\Collection;

class PolicyDepartmentMstRepository extends BaseRepository implements PolicyDepartmentMstInterface
{

    public function __construct(PolicyDepartmentMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get policy department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()->from('t_policy_department AS tpd')
            ->select([
                'tpd.id         AS id',
                'tpd.table_name AS table_name',
                'tpd.row_id     AS row_id',
                'tpd.updated_at AS updated_at'
            ]);

        if (isset($payload['table_name'])) {
            $query->where('tpd.table_name', $payload['table_name']);
        }

        if (isset($payload['row_id'])) {
            $query->where('tpd.row_id', $payload['row_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tpd.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tpd.updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Store policy department
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['table_name'] = $payload['table_name'] ?? null;
        $data['row_id'] = $payload['row_id'] ?? null;

        $this->model->create($data);
    }

    /**
     * Update policy department
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $policyDepartment = $this->model->findById($payload['id']);
        $data['tale_name'] = $payload['tale_name'] ?? null;
        $data['row_id'] = $payload['row_id'] ?? null;
        $policyDepartment->save($data);
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
