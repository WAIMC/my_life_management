<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\SocialMgmtInterface;
use App\Models\Management\SocialMgmt;
use Illuminate\Pagination\LengthAwarePaginator;

class SocialMgmtRepository implements SocialMgmtInterface
{
    /**
     * @var SocialMgmt
     */
    protected $model;

    /**
     * SocialMgmtRepository constructor.
     *
     * @param SocialMgmt $model
     */
    public function __construct(SocialMgmt $model)
    {
        $this->model = $model;
    }

    /**
     * Get all socials with pagination
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function getList(array $payload)
    {
        $query = $this->model->query();

        // Apply filters if provided
        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        // Apply sorting
        $sortBy = $payload['sort_by'] ?? 'rank_order';
        $sortDirection = $payload['sort_direction'] ?? 'asc';
        $query->orderBy($sortBy, $sortDirection);

        // Paginate results
        $perPage = $payload['per_page'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Get social by ID
     *
     * @param int $id
     * @return object|null
     */
    public function getById(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Create a new social
     *
     * @param array $payload
     * @return object
     */
    public function create(array $payload)
    {
        return $this->model->create([
            'name' => $payload['name'],
            'slug' => $payload['slug'],
            'link' => $payload['link'],
            'image' => $payload['image'],
            'status' => $payload['status'],
            'is_display' => $payload['is_display'],
            'rank_order' => $payload['rank_order'],
        ]);
    }

    /**
     * Update an existing social
     *
     * @param array $payload
     * @param int $id
     * @return object|bool
     */
    public function update(array $payload, int $id)
    {
        $social = $this->model->find($id);
        
        if (!$social) {
            return false;
        }

        if (isset($payload['name'])) {
            $social->name = $payload['name'];
        }

        if (isset($payload['slug'])) {
            $social->slug = $payload['slug'];
        }

        if (isset($payload['link'])) {
            $social->link = $payload['link'];
        }

        if (isset($payload['image'])) {
            $social->image = $payload['image'];
        }

        if (isset($payload['status'])) {
            $social->status = $payload['status'];
        }

        if (isset($payload['is_display'])) {
            $social->is_display = $payload['is_display'];
        }

        if (isset($payload['rank_order'])) {
            $social->rank_order = $payload['rank_order'];
        }

        $social->save();
        return $social;
    }

    /**
     * Delete a social
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $social = $this->model->find($id);
        
        if (!$social) {
            return false;
        }

        return $social->delete();
    }
}
