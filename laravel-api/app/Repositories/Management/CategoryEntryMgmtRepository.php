<?php

declare(strict_types=1);

namespace App\Repositories\Management;

use App\Enums\IsDelete;
use App\Interfaces\Management\CategoryEntryMgmtInterface;
use App\Models\Management\CategoryEntryMgmt;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class CategoryEntryMgmtRepository extends BaseRepository implements CategoryEntryMgmtInterface
{
  public function __construct(CategoryEntryMgmt $model)
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
        'entry_mgmt_id',
        'updated_at',
      ])
      ->with(['category:id,name,slug', 'entry:id,name,slug']); // Eager load

    // Apply filters
    $this->applyFilters($query, $payload, [
      'category_mgmt_id',
      'entry_mgmt_id',
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
    $now = now();
    $data = collect($payload)->map(function ($item) use ($now) {
      return array_merge($item, [
        'created_at' => $now,
        'updated_at' => $now,
      ]);
    })->all();

    $this->model->insert($data);
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
      return '(' . (int)$item['category_mgmt_id'] . ', ' . (int)$item['entry_mgmt_id'] . ')';
    })->all();

    $this->model
      ->whereRaw("(category_mgmt_id, entry_mgmt_id) IN (" . implode(", ", $values) . ")")
      ->delete();
  }

  /**
   * Get ids
   *
   * @param array $tuples
   * @return Collection
   */
  public function getCategoryEntryMgmtId(array $tuples): Collection
  {
    $values = collect($tuples)->map(function ($item) {
      $catId = isset($item['category_mgmt_id']) ? $item['category_mgmt_id'] : ($item[0] ?? 0);
      $entryId = isset($item['entry_mgmt_id']) ? $item['entry_mgmt_id'] : ($item[1] ?? 0);
      return '(' . (int)$catId . ', ' . (int)$entryId . ')';
    })->all();

    return $this->model
      ->whereRaw("(category_mgmt_id, entry_mgmt_id) IN (" . implode(", ", $values) . ")")
      ->get(['category_mgmt_id', 'entry_mgmt_id'])
      ->map(function ($item) {
        return [$item->category_mgmt_id, $item->entry_mgmt_id];
      });
  }
}
