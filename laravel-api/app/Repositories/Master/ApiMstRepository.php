<?php

declare(strict_types=1);

namespace App\Repositories\Master;

use App\Enums\IsDelete;
use App\Interfaces\Master\ApiMstInterface;
use App\Models\Master\ApiMst;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class ApiMstRepository extends BaseRepository implements ApiMstInterface
{
    public function __construct(ApiMst $model)
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
                'type',
                'name',
                'path',
                'is_active',
                'feature_mst_id',
                'updated_at',
            ]);

        if (isset($payload['type'])) {
            $query->where('type', $payload['type']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['path'])) {
            $query->where('path', 'like', '%' . $payload['path'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('is_active', $payload['is_active']);
        }

        if (isset($payload['feature_mst_id'])) {
            $query->where('feature_mst_id', $payload['feature_mst_id']);
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
        $data['type'] = $payload['type'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['path'] = $payload['path'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['feature_mst_id'] = $payload['feature_mst_id'] ?? null;
        $data['is_delete'] = $payload['is_delete'] ?? null;
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
        $record['type'] = $payload['type'] ?? null;
        $record['name'] = $payload['name'] ?? null;
        $record['path'] = $payload['path'] ?? null;
        $record['is_active'] = $payload['is_active'] ?? null;
        $record['feature_mst_id'] = $payload['feature_mst_id'] ?? null;
        $record['is_delete'] = $payload['is_delete'] ?? null;
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
