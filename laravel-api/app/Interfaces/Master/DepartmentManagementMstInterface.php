<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface DepartmentManagementMstInterface
{
    /**
     * Get department management list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store department management
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete department management
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;

    /**
     * Get department management id
     *
     * @param array $departmentMgmtIds
     * @return Collection
     */
    public function getDepartmentMgmtMstId(array $departmentMgmtIds): Collection;
}
