<?php

namespace App\Services\Master;

use App\Interfaces\Master\DepartmentMstInterface;
use App\Http\Resources\Master\DepartmentMstResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMstService
{
    /**
     * Constructor
     *
     * @param DepartmentMstInterface $departmentMst
     */
    public function __construct(protected DepartmentMstInterface $departmentMst)
    {}

    /**
     * Get all departments with optional filtering
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $records = $this->departmentMst->list($payload);

        return DepartmentMstResource::collection($records);
    }

    /**
     * Create new department
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->departmentMst->executeStore($payload);
    }

    /**
     * Update department
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->departmentMst->executeUpdate($payload);
    }

    /**
     * Delete department
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->departmentMst->executeDelete($payload['ids']);
    }
}
