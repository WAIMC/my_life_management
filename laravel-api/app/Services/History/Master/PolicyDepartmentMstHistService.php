<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\PolicyDepartmentMstHistResource;

class PolicyDepartmentMstHistService
{
    public function __construct(
        protected PolicyDepartmentMstHistInterface $policyDepartmentMstHist
    )
    {
    }

    /**
     * Get policy department mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->policyDepartmentMstHist->list($payload);

        return PolicyDepartmentMstHistResource::collection($list);
    }

    /**
     * Store policy department mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->policyDepartmentMstHist->executeStore($payload);
    }

    /**
     * Update policy department mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->policyDepartmentMstHist->executeUpdate($payload);
    }

    /**
     * Delete policy department mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->policyDepartmentMstHist->executeDelete($payload['ids']);
    }
}
