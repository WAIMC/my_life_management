<?php

namespace App\Interfaces\Master;

interface DepartmentManagementMstInterface
{
    /**
     * Get all department management relations with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get department management relation by IDs
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function getById(int $departmentId, int $policyDepartmentId): mixed;

    /**
     * Create new department management relation
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Delete department management relation
     *
     * @param int $departmentId
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function delete(int $departmentId, int $policyDepartmentId): mixed;

    /**
     * Get department management relations by department ID
     *
     * @param int $departmentId
     * @return mixed
     */
    public function getByDepartmentId(int $departmentId): mixed;

    /**
     * Get department management relations by policy department ID
     *
     * @param int $policyDepartmentId
     * @return mixed
     */
    public function getByPolicyDepartmentId(int $policyDepartmentId): mixed;
}
