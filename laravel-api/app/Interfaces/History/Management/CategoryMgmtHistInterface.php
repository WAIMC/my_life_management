<?php

namespace App\Interfaces\History\Management;

use Illuminate\Support\Collection;

interface CategoryMgmtHistInterface
{
    /**
     * Get category management history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new category management history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update category management history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete category management history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
