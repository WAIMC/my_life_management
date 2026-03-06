<?php

declare(strict_types=1);

namespace App\Interfaces\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryMgmtInterface extends BaseInterface
{
  /**
   * Get list
   *
   * @param array $payload
   * @return LengthAwarePaginator
   */
  public function list(array $payload): LengthAwarePaginator;

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
   * Get displayable categories for docs
   *
   * @return \Illuminate\Support\Collection
   */
  public function getDisplayableCategories(): \Illuminate\Support\Collection;

  /**
   * Search categories
   *
   * @param string $query
   * @return \Illuminate\Support\Collection
   */
  public function searchCategories(string $query): \Illuminate\Support\Collection;
}
