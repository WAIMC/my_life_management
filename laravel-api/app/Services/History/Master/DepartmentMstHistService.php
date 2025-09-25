<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\DepartmentMstHistResource;
use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentMstHistService
{
    /**
     * DepartmentMstHistService constructor.
     *
     * @param DepartmentMstHistInterface $departmentMstHist
     * @param DepartmentMstInterface $departmentMst
     */
    public function __construct(
        protected DepartmentMstHistInterface $departmentMstHist,
        protected DepartmentMstInterface $departmentMst
    ) {}

    /**
     * Handle find department list
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
     * Handle store department
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->departmentMstHist->executeStore($payload);
    }

    /**
     * Handle update department
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->departmentMstHist->executeUpdate($payload);
    }

    /**
     * Delete department
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->departmentMstHist->executeDelete($payload['ids']);
    }
}
