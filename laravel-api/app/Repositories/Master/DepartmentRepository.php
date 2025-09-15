<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\DepartmentInterface;
use App\Repositories\BaseRepository;
use DateTime;
use LogicException;
use App\Utilities\Tmp;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Models\Master\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentRepository extends BaseRepository implements DepartmentInterface
{

    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

    /**
     * Get department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select('id', 'code', 'name', 'status', 'updated_at');

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

        return $query->get();
    }

    /**
     * Store department
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['code'] = $payload['code'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['status'] = $payload['status'] ?? null;

        $this->model->create($data);
    }

    /**
     * Update department
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $department = $this->model->findById($payload['id']);
        $data['code'] = $payload['code'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['status'] = $payload['status'] ?? null;

        $department->save($data);
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
