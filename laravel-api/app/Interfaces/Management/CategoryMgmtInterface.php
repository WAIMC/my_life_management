<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface CategoryMgmtInterface
{
    /**
     * Get category list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new category
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update category
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete category
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
