<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\AdminDepartmentInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\AdminDepartment;
use Illuminate\Support\Collection;

class AdminDepartmentRepository extends BaseRepository implements AdminDepartmentInterface
{
    public function __construct(AdminDepartment $model)
    {
        parent::__construct($model);
    }

    /**
     * Get admin department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()->from('t_admin_department AS tad')
            ->join('t_admin AS ta', 'ta.id', '=', 'tad.admin_id')
            ->join('t_department AS td', 'td.id', '=', 'tad.department_id')
            ->select([
                'tad.admin_id      AS admin_id',
                'ta.email          AS email',
                'ta.status         AS admin_status',
                'ta.is_active      AS is_active',
                'tad.department_id AS department_id',
                'td.code           AS department_code',
                'td.name           AS department_name',
                'td.status         AS department_status',
                'tad.updated_at    AS updated_at'
            ]);

        if (isset($payload['admin_id'])) {
            $query->where('tad.admin_id', $payload['admin_id']);
        }

        if (isset($payload['department_id'])) {
            $query->where('tad.department_id', $payload['department_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tad.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tad.updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Store api role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        // Handle bulk insert
        $this->model->create($payload);
    }

    /**
     * Delete admin department
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['department_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(admin_id, department_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }
}
