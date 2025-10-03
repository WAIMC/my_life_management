<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\BannerMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\BannerMgmtHistResource;

class BannerMgmtHistService
{
    public function __construct(
        protected BannerMgmtHistInterface $bannerMgmtHist
    )
    {
    }

    /**
     * Get banner mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->bannerMgmtHist->list($payload);

        return BannerMgmtHistResource::collection($list);
    }

    /**
     * Store banner mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->bannerMgmtHist->executeStore($payload);
    }

    /**
     * Update banner mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->bannerMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete banner mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->bannerMgmtHist->executeDelete($payload['ids']);
    }
}
