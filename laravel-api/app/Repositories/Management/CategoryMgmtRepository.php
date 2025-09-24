<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Models\Management\CategoryMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CategoryMgmtRepository extends BaseRepository implements CategoryMgmtInterface
{
    public function __construct(CategoryMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all categories with pagination and filtering
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

        if (isset($payload['parent_id'])) {
            $query->where('parent_id', $payload['parent_id']);
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
     * Create new category
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $data = [];
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'] ? Str::slug($payload['name']) : $payload['slug'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'] ?? $this->model->max('rank_order') + 1;
        $this->model->create($data);

        return $this->model->id;
    }

    /**
     * Update category by ID
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $data = $this->model->findById($payload['id']);
        $data['parent_id'] = $payload['parent_id'];
        $data['name'] = $payload['name'];
        $data['slug'] = $payload['slug'] ? Str::slug($payload['name']) : $payload['slug'];
        $data['description'] = $payload['description'];
        $data['status'] = $payload['status'];
        $data['is_display'] = $payload['is_display'];
        $data['rank_order'] = $payload['rank_order'];
        $data->save();

        return $data->id;
    }

    /**
     * Delete category by ID
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
