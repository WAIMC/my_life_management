<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DepartmentManagementMstInterface extends BaseInterface
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
   * @return void
   */
  public function executeStore(array $payload): void;

  /**
   * Delete record
   *
   * @param array $payload
   * @return void
   */
  public function executeDelete(array $payload): void;

  /**
   * Get ids
   *
   * @param array $tuples
   * @return Collection
   */
  public function getDepartmentManagementMstId(array $tuples): Collection;
}
