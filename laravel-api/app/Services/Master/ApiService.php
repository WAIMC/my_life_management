<?php

namespace App\Services\Master;

use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Interfaces\Master\ApiInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use InvalidArgumentException;
use App\Services\CommonService;
use App\Http\Resources\Master\ApiResource;
use App\Repositories\Master\ApiRepository;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Master\Api\ApiListRequest;
use App\Http\Requests\Master\Api\ApiStoreRequest;
use App\Http\Requests\Master\Api\ApiUpdateRequest;
use App\Models\Master\Api;

class ApiService
{
    public function __construct(
        private ApiInterface $api
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
     * @return Void
     */
    public function delete(array $payload): void
    {
        $this->api->executeDelete($payload['ids']);
    }
}
