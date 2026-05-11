<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface ProductMgmtInterface
{
    /**
     * Get product list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new product
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update product
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete product
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
