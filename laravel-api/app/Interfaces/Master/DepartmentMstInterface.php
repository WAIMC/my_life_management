<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface DepartmentMstInterface extends BaseInterface
{
    /**
     * Get department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store department
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Update api
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void;

    /**
     * Delete department
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
