<?php

namespace App\Interfaces\History\Management;

use Illuminate\Support\Collection;

interface BannerMgmtHistInterface
{
    /**
     * Get banner management history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new banner management history (batch)
     *
     * @param array $payloads
     * @return void
     */
    public function executeStore(array $payloads): void;

    /**
     * Update banner management history (batch)
     *
     * @param array $payloads
     * @return void
     */
    public function executeUpdate(array $payloads): void;

    /**
     * Delete banner management history (batch)
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
