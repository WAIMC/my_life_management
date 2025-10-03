<?php

declare(strict_types=1);

namespace App\Repositories\History\Master;

use App\Enums\IsDelete;
use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Models\History\Master\ApiMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class ApiMstHistRepository extends BaseRepository implements ApiMstHistInterface
{
    public function __construct(ApiMstHist $model)
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
                'api_mst_id',
                'type',
                'name',
                'path',
                'is_active',
                'feature_mst_id',
                'action',
                'author_id',
            ]);

        if (isset($payload['api_mst_id'])) {
            $query->where('api_mst_id', $payload['api_mst_id']);
        }

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
        $data['api_mst_id'] = $payload['api_mst_id'] ?? null;
        $data['type'] = $payload['type'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['path'] = $payload['path'] ?? null;
        $data['is_active'] = $payload['is_active'] ?? null;
        $data['feature_mst_id'] = $payload['feature_mst_id'] ?? null;
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
        $record['api_mst_id'] = $payload['api_mst_id'] ?? null;
        $record['type'] = $payload['type'] ?? null;
        $record['name'] = $payload['name'] ?? null;
        $record['path'] = $payload['path'] ?? null;
        $record['is_active'] = $payload['is_active'] ?? null;
        $record['feature_mst_id'] = $payload['feature_mst_id'] ?? null;
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
