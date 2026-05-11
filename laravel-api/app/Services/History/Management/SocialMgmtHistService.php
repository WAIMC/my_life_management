<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SocialMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\SocialMgmtHistResource;

class SocialMgmtHistService
{
    public function __construct(
        protected SocialMgmtHistInterface $socialMgmtHist
    )
    {
    }

    /**
     * Get social mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->socialMgmtHist->list($payload);

        return SocialMgmtHistResource::collection($list);
    }

    /**
     * Store social mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->socialMgmtHist->executeStore($payload);
    }

    /**
     * Update social mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->socialMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete social mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->socialMgmtHist->executeDelete($payload['ids']);
    }
}
