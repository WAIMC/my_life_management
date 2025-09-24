<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\SocialMgmtInterface;
use App\Models\Management\SocialMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class SocialMgmtRepository extends BaseRepository implements SocialMgmtInterface
{
    public function __construct(SocialMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all socials with pagination
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

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
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
     * Create a new social
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['link'] = $payload['link'];
        $data['image'] = $payload['image'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update an existing social
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'];
        $data['link'] = $payload['link'];
        $data['image'] = $payload['image'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete a social
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
