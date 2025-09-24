<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface DepartmentMstInterface
{
    /**
     * Get department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new department
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update department
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete department
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
