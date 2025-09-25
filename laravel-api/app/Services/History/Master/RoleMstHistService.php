<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\RoleMstHistInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\RoleMstHistResource;

class RoleMstHistService
{
    /**
     * Constructor.
     *
     * @param RoleMstHistInterface $roleHistoryRepository
     */
    public function __construct(protected RoleMstHistInterface $roleHistoryRepository)
    {}

    /**
     * Handle find admin list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->roleHistoryRepository->list($payload);

        return RoleMstHistResource::collection($list);
    }

    /**
     * Handle store role history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->roleHistoryRepository->executeStore($payload);
    }

    /**
     * Handle update role history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->roleHistoryRepository->executeUpdate($payload);
    }

    /**
     * Delete role history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->roleHistoryRepository->executeDelete($payload['ids']);
    }
}
