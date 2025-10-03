<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\DepartmentMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\DepartmentMstHistResource;

class DepartmentMstHistService
{
    public function __construct(
        protected DepartmentMstHistInterface $departmentMstHist
    )
    {
    }

    /**
     * Get department mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->departmentMstHist->list($payload);

        return DepartmentMstHistResource::collection($list);
    }

    /**
     * Store department mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->departmentMstHist->executeStore($payload);
    }

    /**
     * Update department mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->departmentMstHist->executeUpdate($payload);
    }

    /**
     * Delete department mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->departmentMstHist->executeDelete($payload['ids']);
    }
}
