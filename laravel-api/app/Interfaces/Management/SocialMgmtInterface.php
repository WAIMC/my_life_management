<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface SocialMgmtInterface
{
    /**
     * Get social list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new social
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update social
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete social
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
