<?php

namespace App\Repositories\Master;

use App\Constants\CommonVal;
use App\Interfaces\Master\FeatureMstInterface;
use App\Models\Master\FeatureMst;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class FeatureMstRepository extends BaseRepository implements FeatureMstInterface
{
    /**
     * Constructor
     */
    public function __construct(FeatureMst $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all features with optional filtering
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['id'])) {
            $query->whereIn('id', $payload['id']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['group_name'])) {
            $query->where('group_name', 'like', '%' . $payload['group_name'] . '%');
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', '%' . $payload['description'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tad.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tad.updated_at', '<=', $toDate);
        }

        $query->orderBy('id');

        return $query->get();
    }

    /**
     * Store feature
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['name'] = $payload['name'] ?? null;
        $data['group_name'] = $payload['group_name'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['last_name'] = $payload['status'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update feature
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['name'] = $payload['name'] ?? null;
        $data['group_name'] = $payload['group_name'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['last_name'] = $payload['status'] ?? null;
        $data->save();

        return $data->id;
    }

    /**
     * Delete feature
     *
     * @param array $id
     * @return void
     */
    public function executeDelete(array $id): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
