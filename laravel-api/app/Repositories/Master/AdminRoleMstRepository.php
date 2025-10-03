<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\AdminRoleMstInterface;
use App\Models\Master\AdminRoleMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class AdminRoleMstRepository extends BaseRepository implements AdminRoleMstInterface
{
    public function __construct(AdminRoleMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select([
                'admin_mst_id',
                'role_mst_id',
                'updated_at',
            ]);

        if (isset($payload['admin_mst_id'])) {
            $query->where('admin_mst_id', $payload['admin_mst_id']);
        }

        if (isset($payload['role_mst_id'])) {
            $query->where('role_mst_id', $payload['role_mst_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('admin_mst_id');

        return $query->get();
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete record
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['admin_mst_id'] . ', ' . (int)$item['role_mst_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(admin_mst_id, role_mst_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getAdminRoleMstId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['admin_mst_id'] . ', ' . (int)$item['role_mst_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(admin_mst_id, role_mst_id) IN (" . implode(", ", $values) . ")")
            ->pluck('admin_mst_id', 'role_mst_id');
    }
}
