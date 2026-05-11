<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ApiRoleMstInterface extends BaseInterface
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
  public function getApiRoleMstId(array $tuples): Collection;

  /**
   * Check if the role belongs to current user
   *
   * @param array $payload
   * @return bool
   */
  public function isMyRole(array $payload): bool;
}
