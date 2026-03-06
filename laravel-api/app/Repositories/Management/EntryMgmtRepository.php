<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\EntryMgmtInterface;
use App\Models\Management\EntryMgmt;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;


class EntryMgmtRepository extends BaseRepository implements EntryMgmtInterface
{
  public function __construct(EntryMgmt $model)
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
        'status',
        'is_display',
        'rank_order',
        'updated_at',
      ])
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
    $model = $this->model->fill(
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

  /**
   * Get entries by category slug
   *
   * @param string $slug
   * @return \Illuminate\Support\Collection
   */
  public function getEntriesByCategorySlug(string $slug): \Illuminate\Support\Collection
  {
    return $this->model->query()
      ->select([
        'entry_mgmt.id',
        'entry_mgmt.name',
        'entry_mgmt.slug',
        'entry_mgmt.rank_order',
      ])
      ->join('category_entry_mgmt', 'entry_mgmt.id', '=', 'category_entry_mgmt.entry_mgmt_id')
      ->join('category_mgmt', 'category_entry_mgmt.category_mgmt_id', '=', 'category_mgmt.id')
      ->where('category_mgmt.slug', $slug)
      ->where('entry_mgmt.is_display', true)
      ->where('entry_mgmt.status', 1)
      ->where('entry_mgmt.is_delete', false)
      ->orderBy('entry_mgmt.rank_order', 'asc')
      ->get();
  }

  /**
   * Get entry detail by slug with descriptions
   *
   * @param string $slug
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function getEntryDetailBySlug(string $slug): ?\Illuminate\Database\Eloquent\Model
  {
    return $this->model->query()
      ->with([
        'descriptions' => function ($query) {
          $query->select([
            'id',
            'entry_mgmt_id',
            'title',
            'summary',
            'article',
            'rank_order',
          ])
            ->where('is_display', true)
            ->where('status', 1)
            ->where('is_delete', false)
            ->orderBy('rank_order', 'asc');
        },
        'categories:id,name,slug'
      ])
      ->where('slug', $slug)
      ->where('is_display', true)
      ->where('status', 1)
      ->notDeleted()
      ->first();
  }

  /**
   * Search entries
   *
   * @param string $query
   * @return \Illuminate\Support\Collection
   */
  public function searchEntries(string $query): \Illuminate\Support\Collection
  {
    return $this->model->query()
      ->select([
        'id',
        'name',
        'slug',
        'rank_order',
      ])
      ->where('is_display', true)
      ->where('status', 1)
      ->notDeleted()
      ->where('name', 'ILIKE', "%{$query}%")
      ->orderBy('rank_order', 'asc')
      ->limit(10)
      ->get();
  }
}
