<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Models\Management\CategorySkillMgmt;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class CategorySkillMgmtRepository extends BaseRepository implements CategorySkillMgmtInterface
{
    public function __construct(CategorySkillMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list with pagination
     *
     * @param array $payload
     * @return LengthAwarePaginator
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->select([
                'category_mgmt_id',
                'skill_mgmt_id',
                'updated_at',
            ])
            ->with(['categoryMgmt:id,name,slug', 'skillMgmt:id,name,icon']); // Eager load

        // Apply filters
        $this->applyFilters($query, $payload, [
            'category_mgmt_id',
            'skill_mgmt_id',
        ]);

        // Apply date range
        $this->applyDateRange($query, $payload);

        // Apply sorting
        $this->applySorting($query, $payload, 'category_mgmt_id');

        // Pagination
        $perPage = $payload['per_page'] ?? 15;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        $this->model->create($payload);
    }

    /**
     * Delete record
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['category_mgmt_id'] . ', ' . (int)$item['skill_mgmt_id'] . ')';
        })->all();

        $this->model
            ->whereRaw("(category_mgmt_id, skill_mgmt_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getCategorySkillMgmtId(array $tuples): Collection
    {
        $values = collect($tuples)->map(function ($item) {
            return '(' . (int)$item['category_mgmt_id'] . ', ' . (int)$item['skill_mgmt_id'] . ')';
        })->all();

        return $this->model
            ->whereRaw("(category_mgmt_id, skill_mgmt_id) IN (" . implode(", ", $values) . ")")
            ->pluck('category_mgmt_id', 'skill_mgmt_id');
    }
}
