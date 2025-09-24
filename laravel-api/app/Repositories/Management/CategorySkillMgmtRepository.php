<?php

namespace App\Repositories\Management;

use App\Constants\CommonVal;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Models\Management\CategorySkillMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use Illuminate\Support\Collection;

class CategorySkillMgmtRepository extends BaseRepository implements CategorySkillMgmtInterface
{
    public function __construct(CategorySkillMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all category-skill relationships
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query();

        if (isset($payload['category_id'])) {
            $query->where('category_id', $payload['category_id']);
        }

        if (isset($payload['skill_id'])) {
            $query->where('skill_id', $payload['skill_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('tad.updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('tad.updated_at', '<=', $toDate);
        }

        $query->orderBy('category_id')->orderBy('skill_id');

        return $query->get();
    }

    /**
     * Store category skill
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void
    {
        // Handle bulk insert
        $this->model->create($payload);
    }

    /**
     * Delete category skill
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['category_id'] . ', ' . (int)$item['skill_id'] . ')';
        })->all();

        // Handle bulk delete
        $this->model
            ->whereRaw("(category_id, skill_id) IN (" . implode(", ", $values) . ")")
            ->delete();
    }

    /**
     * Get admin departments id
     *
     * @param array $categorySkillIds
     * @return Collection
     */
    public function getCategorySkillId(array $categorySkillIds): Collection
    {
        return $this->model
            ->whereRaw("(category_id, skill_id) IN (" . implode(", ", $categorySkillIds) . ")")
            ->pluck('category_id', 'skill_id');
    }
}
