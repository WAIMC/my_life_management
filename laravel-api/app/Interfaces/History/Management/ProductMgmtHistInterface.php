<?php

namespace App\Interfaces\History\Management;

use Illuminate\Support\Collection;

interface ProductMgmtHistInterface
{
    /**
     * Get product master history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new product master history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update product master history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete product master history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
