<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface BannerMgmtInterface
{
    /**
     * Get banner list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new banner
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update banner
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete banner
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
