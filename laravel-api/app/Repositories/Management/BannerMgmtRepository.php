<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\BannerMgmtInterface;
use App\Models\Management\BannerMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BannerMgmtRepository extends BaseRepository implements BannerMgmtInterface
{
    public function __construct(BannerMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all banners with pagination and filtering
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

        if (isset($payload['title'])) {
            $query->where('title', 'like', '%' . $payload['title'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['position'])) {
            $query->where('position', $payload['position']);
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
     * Create new banner
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['title'] = $payload['title'];
        $data['slug'] = $payload['slug'] ?? Str::slug($payload['title']);
        $data['description'] = $payload['description'] ?? '';
        $data['link'] = $payload['link'] ?? '';
        $data['image'] = $payload['image'] ?? '';
        $data['position'] = $payload['position'] ?? '';
        $data['status'] = $payload['status'] ?? '';
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update banner by ID
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['title'] = $payload['title'];
        $data['slug'] = $payload['slug'] ?? Str::slug($payload['title']);
        $data['description'] = $payload['description'];
        $data['link'] = $payload['link'];
        $data['image'] = $payload['image'];
        $data['position'] = $payload['position'];
        $data['status'] = $payload['status'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete banner by ID
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
