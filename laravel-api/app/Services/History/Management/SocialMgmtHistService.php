<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SocialMgmtHistInterface;
use App\Http\Resources\History\Management\SocialMgmtHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialMgmtHistService
{
    /**
     * Constructor
     * 
     * @param SocialMgmtHistInterface $socialMgmtHist
     */
    public function __construct(protected SocialMgmtHistInterface $socialMgmtHist) {}

    /**
     * Handle find admin list
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
     * Handle store admin
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->socialMgmtHist->executeStore($payload);
    }

    /**
     * Handle update account
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->socialMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete account
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->socialMgmtHist->executeDelete($payload['ids']);
    }
}
