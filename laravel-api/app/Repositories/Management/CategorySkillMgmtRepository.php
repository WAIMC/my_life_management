<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Models\Management\CategorySkillMgmt;
use App\Repositories\BaseRepository;
use DateTime;
use App\Constants\CommonVal;
use Illuminate\Support\Collection;


class CategorySkillMgmtRepository extends BaseRepository implements CategorySkillMgmtInterface
{
    public function __construct(CategorySkillMgmt $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection
    {
        $query = $this->model->query()
            ->select([
                'category_mgmt_id',
                'skill_mgmt_id',
                'updated_at',
            ]);

        if (isset($payload['category_mgmt_id'])) {
            $query->where('category_mgmt_id', $payload['category_mgmt_id']);
        }

        if (isset($payload['skill_mgmt_id'])) {
            $query->where('skill_mgmt_id', $payload['skill_mgmt_id']);
        }

        if (isset($payload['from_date'])) {
            $fromDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['from_date']);
            $query->whereDate('updated_at', '>=', $fromDate);
        }

        if (isset($payload['to_date'])) {
            $toDate = DateTime::createFromFormat(CommonVal::DATE_FORMAT, $payload['to_date']);
            $query->whereDate('updated_at', '<=', $toDate);
        }

        $query->orderBy('category_mgmt_id');

        return $query->get();
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
