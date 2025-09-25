<?php

namespace App\Repositories\History\Management;

use App\Constants\CommonVal;
use App\Models\History\Management\SocialMgmtHist;
use App\Interfaces\History\Management\SocialMgmtHistInterface;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class SocialMgmtHistRepository extends BaseRepository implements SocialMgmtHistInterface
{
    public function __construct(SocialMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get social history list with conditions
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->select('*')->orderBy('id');

        if (isset($payload['id'])) {
            $query->whereIn('id', $payload['id']);
        }

        if (isset($payload['social_mgmt_id'])) {
            $query->where('social_mgmt_id', $payload['social_mgmt_id']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
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
     * Store new social history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['social_mgmt_id'] = $payload['social_mgmt_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['link'] = $payload['link'];
        $data['image'] = $payload['image'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update social history record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['social_mgmt_id'] = $payload['social_mgmt_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['link'] = $payload['link'];
        $data['image'] = $payload['image'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data['action'] = $payload['action'];
        $data['author_id'] = $payload['author_id'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete social history record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
