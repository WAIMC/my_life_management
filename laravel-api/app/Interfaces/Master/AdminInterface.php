<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

class AdminInterface {
    /**
     * Get account list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new admin account
     * @param array $payload
     * @return void
     */
    public static function store(array $payload): void;

     /**
      * Update admin
      *
      * @param array $payload
      * @return void
      */
  public static function update(array $payload): void;

    /**
     * Delete Role
     *
     * @param string $id
     * @return void
     */
    public static function delete(string $id): void;

    /**
     * Check is admin active
     *
     * @param array $data
     * @return bool
     */
    public static function isAdminActive(array $data): bool;
}
