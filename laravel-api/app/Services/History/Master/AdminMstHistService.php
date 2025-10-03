<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\AdminMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\AdminMstHistResource;

class AdminMstHistService
{
    public function __construct(
        protected AdminMstHistInterface $adminMstHist
    )
    {
    }

    /**
     * Get admin mst hist list
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
     * Store admin mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->adminMstHist->executeStore($payload);
    }

    /**
     * Update admin mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->adminMstHist->executeUpdate($payload);
    }

    /**
     * Delete admin mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->adminMstHist->executeDelete($payload['ids']);
    }
}
