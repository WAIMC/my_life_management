<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface PolicyDepartmentMstInterface
{
    /**
     * Get policy department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new policy department
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update policy department
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete policy department
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
