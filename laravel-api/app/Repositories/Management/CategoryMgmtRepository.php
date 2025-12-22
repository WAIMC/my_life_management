<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Models\Management\CategoryMgmt;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class CategoryMgmtRepository extends BaseRepository implements CategoryMgmtInterface
{
  public function __construct(CategoryMgmt $model)
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
        'id',
        'parent_id',
        'name',
        'slug',
        'description',
        'status',
        'is_display',
        'rank_order',
        'updated_at',
      ])
      ->with(['parent:id,name', 'skills:id,name'])
      ->notDeleted();

    // Apply filters
    $this->applyFilters($query, $payload, [
      'id',
      'parent_id',
      'status',
      'is_display',
      'rank_order',
    ], [
      'name',
      'slug',
      'description',
    ]);

    // Apply date range
    $this->applyDateRange($query, $payload);

    // Apply sorting
    $this->applySorting($query, $payload);

    // Pagination
    $perPage = $payload['per_page'] ?? 15;
    $page = $payload['page'] ?? 1;

    return $query->paginate($perPage, ['*'], 'page', $page);
  }

  /**
   * Create new record
   *
   * @param array $payload
   * @return int
   */
  public function executeStore(array $payload): int
  {
    $model = $this->model->newInstance();
    $model->fill(
      Arr::only($payload, $this->model->getFillable())
    );

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

    if ($model->isDeleted()) {
      throw new \LogicException('Cannot update deleted record');
    }

    $model->fill(Arr::only($payload, $this->model->getFillable()));
    $model->save();

    return $model->id;
  }

  /**
   * Delete record (soft delete)
   *
   * @param array $ids
   * @return void
   */
  public function executeDelete(array $ids): void
  {
    // Soft delete
    $this->model->whereIn('id', $ids)
      ->notDeleted()
      ->update(['is_delete' => IsDelete::TRUE->value]);
  }
}
