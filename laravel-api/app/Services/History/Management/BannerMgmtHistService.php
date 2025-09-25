<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Http\Resources\History\Management\BannerMgmtHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerMgmtHistService
{
    /**
     * BannerMgmtHistService constructor
     * @param BannerMgmtHistInterface $bannerMgmtHist
     */
    public function __construct(protected BannerMgmtHistInterface $bannerMgmtHist)
    {
    }

    /**
     * Get all banner history records with filtering
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
     * Handle store banner history (bulk)
     *
     * @param array $payloads
     * @return bool
     */
    public function store(array $payloads): bool
    {
        $this->bannerMgmtHist->executeStore($payloads);
        
        return true;
    }

    /**
     * Handle update banner history (bulk)
     *
     * @param array $payloads
     * @return bool
     */
    public function update(array $payloads): bool
    {
        $this->bannerMgmtHist->executeUpdate($payloads);

        return true;
    }

    /**
     * Delete banner history (bulk)
     *
     * @param array $payload
     * @return bool
     */
    public function delete(array $payload): bool
    {
        $this->bannerMgmtHist->executeDelete($payload['ids']);
    
        return true;
    }
}
