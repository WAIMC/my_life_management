<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface RoleMstInterface
{
    /**
     * Get role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new role
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update role
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
