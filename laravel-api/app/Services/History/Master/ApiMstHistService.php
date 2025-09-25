<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Http\Resources\History\Master\ApiMstHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiMstHistService
{
    /**
     * Constructor
     *
     * @param ApiMstHistInterface $apiMstHist
     */
    public function __construct(protected ApiMstHistInterface $apiMstHist) {}

    /**
     * Handle find admin list
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
     * Handle store api
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->apiMstHist->executeStore($payload);
    }

    /**
     * Handle update api
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->apiMstHist->executeUpdate($payload);
    }

    /**
     * Delete api
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->apiMstHist->executeDelete($payload['ids']);
    }
}
