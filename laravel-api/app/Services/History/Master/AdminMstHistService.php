<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\AdminMstHistInterface;
use App\Interfaces\Master\AdminMstInterface;
use App\Http\Resources\History\Master\AdminMstHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMstHistService
{
    /**
     * Constructor
     *
     * @param AdminMstHistInterface $adminMstHist
     * @param AdminMstInterface $adminMst
     */
    public function __construct(
        protected AdminMstHistInterface $adminMstHist,
        protected AdminMstInterface     $adminMst
    ) {}

    /**
     * Handle find admin list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminMstHist->list($payload);

        return AdminMstHistResource::collection($list);
    }

    /**
     * Handle store admin
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->adminMstHist->executeStore($payload);
    }

    /**
     * Handle update account
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->adminMstHist->executeUpdate($payload);
    }

    /**
     * Delete account
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->adminMstHist->executeDelete($payload['ids']);
    }
}
