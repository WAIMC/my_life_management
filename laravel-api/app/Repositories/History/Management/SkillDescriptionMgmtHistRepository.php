<?php

declare(strict_types=1);

namespace App\Repositories\History\Management;

use App\Enums\IsDelete;
use App\Interfaces\History\Management\SkillDescriptionMgmtHistInterface;
use App\Models\History\Management\SkillDescriptionMgmtHist;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class SkillDescriptionMgmtHistRepository extends BaseRepository implements SkillDescriptionMgmtHistInterface
{
    public function __construct(SkillDescriptionMgmtHist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): LengthAwarePaginator
    {
        $query = $this->model->query()
            ->select(['id','skill_description_mgmt_id','parent_id','title','summary','article','status','is_display','rank_order','skill_id','action','author_id'])
            ->with(['skillDescriptionMgmt:id,title', 'author:id,username']);
        $this->applyFilters($query, $payload, ['skill_description_mgmt_id','parent_id','status','is_display','rank_order','skill_id','action','author_id'], ['title','summary','article']);
        $this->applyDateRange($query, $payload);
        $this->applySorting($query, $payload);
        return $query->paginate($payload['per_page'] ?? 15, ['*'], 'page', $payload['page'] ?? 1);
    }

    /**
     * Create new record
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int
    {
        $model = $this->model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();
        return $model->id;
    }
        $data['title'] = $payload['title'] ?? null;
        $data['summary'] = $payload['summary'] ?? null;
        $data['article'] = $payload['article'] ?? null;
        $data['status'] = $payload['status'] ?? null;
        $data['is_display'] = $payload['is_display'] ?? null;
        $data['rank_order'] = $payload['rank_order'] ?? null;
        $data['skill_id'] = $payload['skill_id'] ?? null;
        $data['action'] = $payload['action'] ?? null;
        $data['author_id'] = $payload['author_id'] ?? null;
        $this->model->create($data);

        return $this->model->id;
    }


    /**
     * Update record
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int
    {
        $model = $this->model->findOrFail($payload['id']);
        $model->fill(Arr::only($payload, $this->model->getFillable()));
        $model->save();
        return $model->id;
    }
        $record['skill_description_mgmt_id'] = $payload['skill_description_mgmt_id'] ?? null;
        $record['parent_id'] = $payload['parent_id'] ?? null;
        $record['title'] = $payload['title'] ?? null;
        $record['summary'] = $payload['summary'] ?? null;
        $record['article'] = $payload['article'] ?? null;
        $record['status'] = $payload['status'] ?? null;
        $record['is_display'] = $payload['is_display'] ?? null;
        $record['rank_order'] = $payload['rank_order'] ?? null;
        $record['skill_id'] = $payload['skill_id'] ?? null;
        $record['action'] = $payload['action'] ?? null;
        $record['author_id'] = $payload['author_id'] ?? null;
        $record->save();

        return $record->id;
    }

    /**
     * Delete record
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void
    {
        $this->model->whereIn('id', $ids)->update(['is_delete' => IsDelete::TRUE->value]);
    }

}
