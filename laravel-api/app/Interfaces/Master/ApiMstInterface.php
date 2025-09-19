<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface ApiMstInterface extends BaseInterface
{
    /**
     * Get admin role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store admin role
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update api
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete admin role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
