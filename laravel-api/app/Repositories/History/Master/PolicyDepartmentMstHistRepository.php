<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Models\History\Master\PolicyDepartmentMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class PolicyDepartmentMstHistRepository extends BaseRepository implements PolicyDepartmentMstHistInterface
{
    public function __construct(PolicyDepartmentMstHist $model)
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
                'id',
                'policy_department_mst_id',
                'table_name',
                'row_id',
                'action',
                'author_id',
            ]);

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

        $query->orderBy('id');

        return $query->get();
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data['policy_department_mst_id'] = $payload['policy_department_mst_id'] ?? null;
        $data['table_name'] = $payload['table_name'] ?? null;
        $data['row_id'] = $payload['row_id'] ?? null;
        $data['action'] = $payload['action'] ?? null;
        $data['author_id'] = $payload['author_id'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }


    /**
     * Update record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $record = $this->model->find($payload['id']);
        $record['policy_department_mst_id'] = $payload['policy_department_mst_id'] ?? null;
        $record['table_name'] = $payload['table_name'] ?? null;
        $record['row_id'] = $payload['row_id'] ?? null;
        $record['action'] = $payload['action'] ?? null;
        $record['author_id'] = $payload['author_id'] ?? null;
        $record->save();

        return $record->id;
    }

    /**
     * Delete record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->update(['is_delete' => IsDelete::TRUE->value]);
    }

}
