<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\ApiMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\ApiMstHistResource;

class ApiMstHistService
{
    public function __construct(
        protected ApiMstHistInterface $apiMstHist
    )
    {
    }

    /**
     * Get api mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->apiMstHist->list($payload);

        return ApiMstHistResource::collection($list);
    }

    /**
     * Store api mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->apiMstHist->executeStore($payload);
    }

    /**
     * Update api mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->apiMstHist->executeUpdate($payload);
    }

    /**
     * Delete api mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->apiMstHist->executeDelete($payload['ids']);
    }
}
