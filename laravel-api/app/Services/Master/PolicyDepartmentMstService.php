<?php

namespace App\Services\Master;

use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Http\Resources\Master\PolicyDepartmentMstResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentMstService
{
    /**
     * Constructor
     *
     * @param PolicyDepartmentMstInterface $policyDepartmentMst
     */
    public function __construct(protected PolicyDepartmentMstInterface $policyDepartmentMst)
    {}

    /**
     * Get all policy departments with optional filtering
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->policyDepartmentMst->list($payload);

        return PolicyDepartmentMstResource::collection($list);
    }

    /**
     * Create new policy department
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->policyDepartmentMst->executeStore($payload);
    }

    /**
     * Update policy department
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->policyDepartmentMst->executeUpdate($payload);
    }

    /**
     * Delete policy department
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->policyDepartmentMst->executeDelete($payload['ids']);
    }
}
