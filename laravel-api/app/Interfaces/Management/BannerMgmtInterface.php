<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;
use App\Models\Management\BannerMgmt;

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
     * @return BannerMgmt
     */
    public function executeStore(array $payload): BannerMgmt;

    /**
     * Update banner
     *
     * @param array $payload
     * @return BannerMgmt
     */
    public function executeUpdate(array $payload): BannerMgmt;

    /**
     * Delete banner
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
