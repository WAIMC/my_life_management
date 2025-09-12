<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface AdminDepartmentInterface extends BaseInterface
{
    /**
     * Get admin department list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store api role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete admin department
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;
}
