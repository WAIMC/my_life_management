<?php

namespace App\Interfaces\History\Master;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RoleMstHistInterface
{
    /**
     * Get role master history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new role master history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update role master history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete role master history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
