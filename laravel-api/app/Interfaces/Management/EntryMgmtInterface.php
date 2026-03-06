<?php

declare(strict_types=1);

namespace App\Interfaces\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface EntryMgmtInterface extends BaseInterface
{
  /**
   * Get list
   *
   * @param array $payload
   * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
   */
  public function list(array $payload): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

  /**
   * Store record
   *
   * @param array $payload
   * @return int
   */
  public function executeStore(array $payload): int;

  /**
   * Update record
   *
   * @param array $payload
   * @return int
   */
  public function executeUpdate(array $payload): int;

  /**
   * Delete record
   *
   * @param array $ids
   * @return void
   */
  public function executeDelete(array $ids): void;

  /**
   * Get entries by category slug
   *
   * @param string $slug
   * @return \Illuminate\Support\Collection
   */
  public function getEntriesByCategorySlug(string $slug): \Illuminate\Support\Collection;

  /**
   * Get entry detail by slug with descriptions
   *
   * @param string $slug
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function getEntryDetailBySlug(string $slug): ?\Illuminate\Database\Eloquent\Model;

  /**
   * Search entries
   *
   * @param string $query
   * @return \Illuminate\Support\Collection
   */
  public function searchEntries(string $query): \Illuminate\Support\Collection;
}
