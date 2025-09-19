<?php

namespace App\Interfaces\Master;

interface PolicyDepartmentMstInterface
{
    /**
     * Get all policy departments with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get policy department by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Get policy departments by table name
     *
     * @param string $tableName
     * @return mixed
     */
    public function getByTableName(string $tableName): mixed;

    /**
     * Get policy department by table name and row ID
     *
     * @param string $tableName
     * @param int $rowId
     * @return mixed
     */
    public function getByTableNameAndRowId(string $tableName, int $rowId): mixed;

    /**
     * Create new policy department
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update policy department
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete policy department
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
