<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface AdminRoleMstInterface extends BaseInterface
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
  public function getAdminRoleMstId(array $tuples): Collection;

  /**
   * Check if payload contains current user's role
   *
   * @param array $payload
   * @return bool
   */
  public function isMyRole(array $payload): bool;
}
