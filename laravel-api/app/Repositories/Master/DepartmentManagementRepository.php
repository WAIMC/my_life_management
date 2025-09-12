<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\DepartmentManagementInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\DepartmentManagement;
use Illuminate\Support\Collection;

class DepartmentManagementRepository extends BaseRepository implements DepartmentManagementInterface
{
    public function __construct(DepartmentManagement $model)
    {
        parent::__construct($model);
    }

    /**
     * Get department management list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()->from('t_department_management AS tdm')
            ->join('t_department AS td', 'td.id', '=', 'tdm.department_id')
            ->join('t_policy_department AS tpd', 'tpd.id', '=', 'tdm.policy_department_id')
            ->select([
                'tdm.department_id        AS department_id',
                'td.code                  AS code',
                'td.name                  AS name',
                'td.status                AS department_status',
                'tdm.policy_department_id AS policy_department_id',
                'tpd.table_name           AS table_name',
                'tpd.row_id               AS row_id',
                'tdm.updated_at           AS updated_at'
            ]);

        if (isset($payload['department_id'])) {
            $query->where('tdm.department_id', $payload['department_id']);
        }

        if (isset($payload['policy_department_id'])) {
            $query->where('tdm.policy_department_id', $payload['policy_department_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tdm.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tdm.updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Store department management
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete department management
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['department_id'] . ', ' . (int)$item['policy_department_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(department_id, policy_department_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }
}
