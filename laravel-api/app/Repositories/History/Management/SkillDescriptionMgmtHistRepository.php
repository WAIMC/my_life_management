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
   * Get list with pagination
   *
   * @param array $payload
   * @return LengthAwarePaginator
   */
  public function list(array $payload): LengthAwarePaginator
  {
    $query = $this->model->query()
      ->select(['id', 'skill_description_mgmt_id', 'parent_id', 'title', 'summary', 'article', 'status', 'is_display', 'rank_order', 'skill_mgmt_id', 'action', 'author_id'])
      ->with(['skillDescriptionMgmt:id,title', 'author:id,user_name']);
    $this->applyFilters($query, $payload, ['skill_description_mgmt_id', 'parent_id', 'status', 'is_display', 'rank_order', 'skill_mgmt_id', 'action', 'author_id'], ['title', 'summary', 'article']);
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

  /**
   * Delete record (hard delete)
   *
   * @param array $ids
   * @return void
   */
  public function executeDelete(array $ids): void
  {
    $this->model->whereIn('id', $ids)->delete();
  }
}
