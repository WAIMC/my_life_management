<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\UserMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\UserMgmtHistResource;

class UserMgmtHistService
{
    public function __construct(
        protected UserMgmtHistInterface $userMgmtHist
    )
    {
    }

    /**
     * Get user mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->userMgmtHist->list($payload);

        return UserMgmtHistResource::collection($list);
    }

    /**
     * Store user mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->userMgmtHist->executeStore($payload);
    }

    /**
     * Update user mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->userMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete user mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->userMgmtHist->executeDelete($payload['ids']);
    }
}
