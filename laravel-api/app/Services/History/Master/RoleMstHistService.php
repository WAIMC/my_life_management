<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\RoleMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\RoleMstHistResource;

class RoleMstHistService
{
    public function __construct(
        protected RoleMstHistInterface $roleMstHist
    )
    {
    }

    /**
     * Get role mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->roleMstHist->list($payload);

        return RoleMstHistResource::collection($list);
    }

    /**
     * Store role mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->roleMstHist->executeStore($payload);
    }

    /**
     * Update role mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->roleMstHist->executeUpdate($payload);
    }

    /**
     * Delete role mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->roleMstHist->executeDelete($payload['ids']);
    }
}
