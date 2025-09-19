<?php

namespace App\Repositories\History\Management;

use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Models\History\Management\BannerMgmtHist;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BannerMgmtHistRepository implements BannerMgmtHistInterface
{
    protected BannerMgmtHist $model;

    /**
     * BannerMgmtHistRepository constructor
     */
    public function __construct()
    {
        $this->model = new BannerMgmtHist();
    }

    /**
     * Get all banner history records with filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters from payload
        if (isset($payload['banner_mgmt_id'])) {
            $query->where('banner_mgmt_id', $payload['banner_mgmt_id']);
        }

        if (isset($payload['action'])) {
            $query->where('action', $payload['action']);
        }

        if (isset($payload['author_id'])) {
            $query->where('author_id', $payload['author_id']);
        }

        if (isset($payload['from_date'])) {
            $query->where('created_at', '>=', $payload['from_date']);
        }

        if (isset($payload['to_date'])) {
            $query->where('created_at', '<=', $payload['to_date']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'created_at';
        $sortOrder = $payload['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Find banner history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Find banner history by banner ID
     *
     * @param int $bannerId
     * @return mixed
     */
    public function findByBannerId(int $bannerId): mixed
    {
        return $this->model->where('banner_mgmt_id', $bannerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Create new banner history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        $bannerHistory = new $this->model;

        if (isset($payload['banner_mgmt_id'])) {
            $bannerHistory->banner_mgmt_id = $payload['banner_mgmt_id'];
        }

        if (isset($payload['title'])) {
            $bannerHistory->title = $payload['title'];
        }

        if (isset($payload['slug'])) {
            $bannerHistory->slug = $payload['slug'];
        }

        if (isset($payload['description'])) {
            $bannerHistory->description = $payload['description'];
        }

        if (isset($payload['link'])) {
            $bannerHistory->link = $payload['link'];
        }

        if (isset($payload['image'])) {
            $bannerHistory->image = $payload['image'];
        }

        if (isset($payload['position'])) {
            $bannerHistory->position = $payload['position'];
        }

        if (isset($payload['status'])) {
            $bannerHistory->status = $payload['status'];
        }

        if (isset($payload['action'])) {
            $bannerHistory->action = $payload['action'];
        }

        if (isset($payload['author_id'])) {
            $bannerHistory->author_id = $payload['author_id'];
        }

        if (isset($payload['created_at'])) {
            $bannerHistory->created_at = $payload['created_at'];
        } else {
            $bannerHistory->created_at = now()->format('Y-m-d H:i:s');
        }

        $bannerHistory->save();
        return $bannerHistory;
    }
}
