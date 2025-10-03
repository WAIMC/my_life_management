<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SettingLinkMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\SettingLinkMgmtHistResource;

class SettingLinkMgmtHistService
{
    public function __construct(
        protected SettingLinkMgmtHistInterface $settingLinkMgmtHist
    )
    {
    }

    /**
     * Get setting link mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->settingLinkMgmtHist->list($payload);

        return SettingLinkMgmtHistResource::collection($list);
    }

    /**
     * Store setting link mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->settingLinkMgmtHist->executeStore($payload);
    }

    /**
     * Update setting link mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->settingLinkMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete setting link mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->settingLinkMgmtHist->executeDelete($payload['ids']);
    }
}
