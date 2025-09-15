<?php

namespace App\Repositories\Master;

use App\Interfaces\Master\FeatureInterface;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use App\Models\Master\Feature;
use Illuminate\Database\Eloquent\Collection;

class FeatureRepository extends BaseRepository implements FeatureInterface
{
    public function __construct(Feature $model)
    {
        parent::__construct($model);
    }

    /**
     * Get feature list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select('id', 'name', 'group_name', 'status', 'updated_at');

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['group_name'])) {
            $query->where('group_name', 'like', '%' . $payload['group_name'] . '%');
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
     * Store feature
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['name'] = $payload['name'] ?? null;
        $data['group_name'] = $payload['group_name'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['status'] = $payload['status'] ?? null;

        $this->model->create($data);
    }

    /**
     * Update feature
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $feature = $this->model->findById($payload['id']);
        $data['name'] = $payload['name'] ?? null;
        $data['group_name'] = $payload['group_name'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $feature->save($data);
    }

    /**
     * Delete feature
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
