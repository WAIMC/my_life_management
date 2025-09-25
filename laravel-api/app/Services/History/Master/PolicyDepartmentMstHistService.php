<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Http\Resources\History\Master\PolicyDepartmentMstHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyDepartmentMstHistService
{
    /**
     * Constructor.
     *
     * @param PolicyDepartmentMstHistInterface $policyDepartmentMstHist
     */
    public function __construct(protected PolicyDepartmentMstHistInterface $policyDepartmentMstHist) {}

    /**
     * Handle find policy department history list
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
     * Handle store policy department history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->policyDepartmentMstHist->executeStore($payload);
    }

    /**
     * Handle update policy department history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->policyDepartmentMstHist->executeUpdate($payload);
    }

    /**
     * Delete policy department history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->policyDepartmentMstHist->executeDelete($payload['ids']);
    }
}
