<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface DepartmentManagementInterface extends BaseInterface
{
    /**
     * Get department management list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new department management account
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
}
