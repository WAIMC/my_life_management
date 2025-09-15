<?php

namespace App\Repositories\Master;

use App\Enums\IsActive;
use App\Interfaces\Master\ApiInterface;
use App\Models\Master\Api;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ApiRepository extends BaseRepository implements ApiInterface
{
    public function __construct(Api $model)
    {
        parent::__construct($model);
    }

    /**
     * Get api list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()->from('t_api AS ta')
            ->join('t_feature AS tf', 'tf.id', '=', 'ta.feature_id')
            ->select([
                'ta.id         AS id',
                DB::raw("
          CASE
            WHEN ta.type = 0 THEN 'GET'
            WHEN ta.type = 1 THEN 'POST'
            WHEN ta.type = 2 THEN 'PUT'
            WHEN ta.type = 3 THEN 'PATCH'
            WHEN ta.type = 4 THEN 'DELETE'
            ELSE null
          END          AS type_name
        "),
                'ta.type       AS type',
                'ta.name       AS name',
                'ta.path       AS path',
                'ta.is_active  AS is_active',
                'ta.feature_id AS feature_id',
                'tf.name       AS feature_name',
                'tf.group_name AS feature_group',
                'ta.updated_at AS updated_at'
            ]);

        if (isset($payload['type'])) {
            $query->where('ta.type', $payload['type']);
        }

        if (isset($payload['name'])) {
            $query->where('ta.name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['path'])) {
            $query->where('ta.path', 'like', '%' . $payload['path'] . '%');
        }

        if (isset($payload['is_active'])) {
            $query->where('ta.is_active', $payload['is_active']);
        }

        if (isset($payload['feature_id'])) {
            $query->where('ta.feature_id', $payload['feature_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('ta.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('ta.updated_at', '<=', $toDate);
        }

        $query->where('tf.status', IsActive::TRUE);

        return $query->get();
    }

    /**
     * Store api
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['type'] = $payload['type'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['path'] = $payload['path'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['feature_id'] = $payload['feature_id'] ?? null;

        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update api
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $api = $this->model->findById($payload['id']);
        $data['type'] = $payload['type'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['path'] = $payload['path'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['feature_id'] = $payload['feature_id'] ?? null;
        $api->save($data);

        return $api->id;
    }

    /**
     * Delete api
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
