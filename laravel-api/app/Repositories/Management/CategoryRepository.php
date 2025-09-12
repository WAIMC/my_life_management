<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\CategoryInterface;
use App\Models\Management\Category;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    /**
     * Category list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select('id', 'parent_id', 'name', 'slug', 'description', 'status', 'is_display', 'rank_order', 'updated_at');

        if (isset($payload['parent_id'])) {
            $query->where('parent_id', $payload['parent_id']);
        }

        if (isset($payload['name'])) {
            $query->where('name', 'like', '%' . $payload['name'] . '%');
        }

        if (isset($payload['slug'])) {
            $query->where('slug', 'like', '%' . $payload['slug'] . '%');
        }

        if (isset($payload['description'])) {
            $query->where('description', 'like', '%' . $payload['description'] . '%');
        }

        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        if (isset($payload['is_display'])) {
            $query->where('is_display', $payload['is_display']);
        }

        if (isset($payload['rank_order'])) {
            $query->where('rank_order', $payload['rank_order']);
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
     * Store category
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $data = [];
        $data['parent_id'] = $payload['parent_id'] ?? null;
        $data['name'] = $payload['name'] ?? null;
        $data['slug'] = $payload['slug'] ?? null;
        $data['description'] = $payload['description'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_display'] = $payload['is_display'] ?? null;
        $data['rank_order'] = $payload['rank_order'] ?? null;

        $this->model->create($data);
    }

    /**
     * Update category
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void
    {
        $category = $this->model->findById($payload['id']);
        $category['parent_id'] = $payload['parent_id'] ?? null;
        $category['name'] = $payload['name'] ?? null;
        $category['slug'] = $payload['slug'] ?? null;
        $category['description'] = $payload['description'] ?? null;
        $category['status'] = $payload['status'] ?? null;
        $category['is_display'] = $payload['is_display'] ?? null;
        $category['rank_order'] = $payload['rank_order'] ?? null;
        $category->save();
    }

    /**
     * Delete category
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->delete();
    }
}
