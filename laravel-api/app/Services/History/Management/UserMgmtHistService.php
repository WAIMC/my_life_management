<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Interfaces\Management\UserMgmtInterface;
use App\Interfaces\Master\AdminMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\UserMgmtHistResource;

class UserMgmtHistService
{
    /**
     * Constructor.
     *
     * @param UserMgmtHistInterface $userMgmtHist
     * @param UserMgmtInterface $userMgmt
     * @param AdminMstInterface $adminMst
     */
    public function __construct(
        protected UserMgmtHistInterface $userMgmtHist,
        protected UserMgmtInterface $userMgmt,
        protected AdminMstInterface $adminMst
    ) {}

    /**
     * Handle find user list
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
     * Handle store user
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->userMgmtHist->executeStore($payload);
    }

    /**
     * Handle update user
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->userMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete user
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->userMgmtHist->executeDelete($payload['ids']);
    }
}
