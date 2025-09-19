<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\BannerMgmtInterface;
use App\Models\Management\BannerMgmt;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BannerMgmtRepository implements BannerMgmtInterface
{
    protected BannerMgmt $model;

    /**
     * BannerMgmtRepository constructor
     */
    public function __construct()
    {
        $this->model = new BannerMgmt();
    }

    /**
     * Get all banners with pagination and filtering
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getAll(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query();

        // Apply filters from payload
        if (isset($payload['title'])) {
            $query->where('title', 'like', '%' . $payload['title'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['position'])) {
            $query->where('position', $payload['position']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'id';
        $sortOrder = $payload['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Apply pagination
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Find banner by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->model->find($id);
    }

    /**
     * Create new banner
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        $banner = new $this->model;

        if (isset($payload['title'])) {
            $banner->title = $payload['title'];
        }

        if (isset($payload['slug'])) {
            $banner->slug = $payload['slug'];
        } else if (isset($payload['title'])) {
            $banner->slug = Str::slug($payload['title']);
        }

        if (isset($payload['description'])) {
            $banner->description = $payload['description'];
        }

        if (isset($payload['link'])) {
            $banner->link = $payload['link'];
        }

        if (isset($payload['image'])) {
            $banner->image = $payload['image'];
        }

        if (isset($payload['position'])) {
            $banner->position = $payload['position'];
        }

        if (isset($payload['status'])) {
            $banner->status = $payload['status'];
        }

        $banner->created_at = now()->format('Y-m-d H:i:s');
        $banner->updated_at = now()->format('Y-m-d H:i:s');

        $banner->save();
        return $banner;
    }

    /**
     * Update banner by ID
     *
     * @param int $id
     * @param array $payload
     * @return mixed
     */
    public function update(int $id, array $payload): mixed
    {
        $banner = $this->model->find($id);

        if (!$banner) {
            return null;
        }

        if (isset($payload['title'])) {
            $banner->title = $payload['title'];
        }

        if (isset($payload['slug'])) {
            $banner->slug = $payload['slug'];
        } else if (isset($payload['title'])) {
            $banner->slug = Str::slug($payload['title']);
        }

        if (isset($payload['description'])) {
            $banner->description = $payload['description'];
        }

        if (isset($payload['link'])) {
            $banner->link = $payload['link'];
        }

        if (isset($payload['image'])) {
            $banner->image = $payload['image'];
        }

        if (isset($payload['position'])) {
            $banner->position = $payload['position'];
        }

        if (isset($payload['status'])) {
            $banner->status = $payload['status'];
        }

        $banner->updated_at = now()->format('Y-m-d H:i:s');

        $banner->save();
        return $banner;
    }

    /**
     * Delete banner by ID
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        $banner = $this->model->find($id);

        if (!$banner) {
            return false;
        }

        return $banner->delete();
    }
}
