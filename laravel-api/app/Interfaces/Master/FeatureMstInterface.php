<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface FeatureMstInterface
{
    /**
     * Get all features with optional filtering
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new feature
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update feature
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete feature
     *
     * @param array $id
     * @return void
     */
    public function executeDelete(array $id): void;
}
