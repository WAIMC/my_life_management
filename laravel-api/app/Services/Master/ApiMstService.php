<?php

namespace App\Services\Master;

use App\Interfaces\Master\ApiMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\ApiResource;

class ApiMstService
{
    public function __construct(
        protected ApiMstInterface $api
    )
    {
    }

    /**
     * Get api list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->api->list($payload);

        return ApiResource::collection($list);
    }

    /**
     * Store api
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->api->executeStore($payload);
    }

    /**
     * Update api
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->api->executeUpdate($payload);
    }

    /**
     * Delete api
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->api->executeDelete($payload['ids']);
    }
}
