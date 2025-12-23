<?php

declare(strict_types=1);

namespace App\Interfaces\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface SocialMgmtInterface extends BaseInterface
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
}
