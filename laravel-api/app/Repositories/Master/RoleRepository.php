<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\RoleInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Models\Master\Role;
use App\Constants\CommonVal;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    /**
     * Get role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select('id', 'name', 'permission', 'is_active', 'updated_at');

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['permission'])) {
            $query->where('permission', 'like', '%' . $payload['permission'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        return $query->get();
    }

    /**
     * Store role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['name'] = $payload['name'] ?? null;
        $data['permission'] = $payload['permission'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;

        $this->model->create($data);
    }

    /**
     * Update role
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $role = $this->model->findById($payload['id']);
        $data['name'] = $payload['name'] ?? null;
        $data['permission'] = $payload['permission'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $role->save($data);
    }

    /**
     * Delete Role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Check is root
     *
     * @param int $id
     * @return bool
     */
    public function isRoot(int $id): bool
    {
        return DB::table('admin_permission_view AS apv')
            ->where('admin_id', $id)
            ->where('role_name', CommonVal::ROOT)
            ->exists();
    }
}
