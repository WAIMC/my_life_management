<?php

namespace App\Interfaces\History\Master;

interface DepartmentMstHistInterface
{
    /**
     * Get all department history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get department history record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Get history records by department ID
     *
     * @param int $departmentMstId
     * @param array $payload
     * @return mixed
     */
    public function getByDepartmentId(int $departmentMstId, array $payload): mixed;

    /**
     * Create new department history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update department history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete department history record
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
