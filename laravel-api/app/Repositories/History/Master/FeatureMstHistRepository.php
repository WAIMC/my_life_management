<?php

namespace App\Repositories\History\Master;

use App\Constants\CommonVal;
use App\Interfaces\History\Master\FeatureMstHistInterface;
use App\Models\History\Master\AdminMstHist;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class FeatureMstHistRepository extends BaseRepository implements FeatureMstHistInterface
{
    public function __construct(AdminMstHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all feature history records
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

        if (isset($payload['feature_mst_id'])) {
            $query->where('feature_mst_id', $payload['feature_mst_id']);
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

        $query->orderBy('id', 'desc');

        return $query->get();
    }

    /**
     * Create new feature history record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['feature_mst_id'] = $payload['feature_mst_id'];
        $data['name'] = $payload['name'];
        $data['group_name'] = $payload['group_name'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update feature history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['feature_mst_id'] = $payload['feature_mst_id'];
        $data['name'] = $payload['name'];
        $data['group_name'] = $payload['group_name'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete feature history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
