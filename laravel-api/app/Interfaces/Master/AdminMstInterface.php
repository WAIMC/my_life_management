<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface AdminMstInterface extends BaseInterface
{
    /**
     * Get account list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new admin account
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update admin
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete admin
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
