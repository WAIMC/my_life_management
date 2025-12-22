<?php

declare(strict_types=1);

namespace App\Interfaces\History\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryMgmtHistInterface extends BaseInterface
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
}
