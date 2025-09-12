<?php

namespace App\Repositories\Master;

use App\Enums\IsActive;
use App\Interfaces\Master\AdminRoleInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\AdminRole;
use Illuminate\Support\Collection;

class AdminRoleRepository extends BaseRepository implements AdminRoleInterface
{
    public function __construct(AdminRole $model)
    {
        parent::__construct($model);
    }

    /**
     * Get admin role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()->from('t_admin_role AS tar')
            ->join('t_admin AS ta', 'ta.id', '=', 'tar.admin_id')
            ->join('t_role AS tr', 'tr.id', '=', 'tar.role_id')
            ->select([
                'tar.admin_id   AS admin_id',
                'ta.email       AS email',
                'ta.first_name  AS first_name',
                'ta.last_name   AS last_name',
                'ta.status      AS status',
                'tar.role_id    AS role_id',
                'tr.name        AS role_name',
                'tr.permission  AS role_permission',
                'tar.updated_at AS updated_at'
            ])
            ->where('ta.is_active', IsActive::TRUE)
            ->where('tr.is_active', IsActive::TRUE);

        if (isset($payload['admin_id'])) {
            $query->where('tar.admin_id', $payload['admin_id']);
        }

        if (isset($payload['role_id'])) {
            $query->where('tar.role_id', $payload['role_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tar.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tar.updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Store admin role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete admin role
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['admin_id'] . ', ' . (int)$item['role_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(admin_id, role_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }
}
