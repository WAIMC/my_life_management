<?php

namespace App\Services\Master;

use App\Interfaces\Master\RoleMstInterface;
use App\Http\Resources\Master\RoleMstResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleMstService
{
    /**
     * Constructor
     *
     * @param RoleMstInterface $roleMst
     */
    public function __construct(protected RoleMstInterface $roleMst)
    {}

    /**
     * Get all roles with optional filtering
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->roleMst->list($payload);

        return RoleMstResource::collection($list);
    }

    /**
     * Create new role
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->roleMst->executeStore($payload);
    }

    /**
     * Update role
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->roleMst->executeUpdate($payload);
    }

    /**
     * Delete role
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->roleMst->executeDelete($payload['ids']);
    }
}
