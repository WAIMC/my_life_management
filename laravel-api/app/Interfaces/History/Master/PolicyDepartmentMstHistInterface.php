<?php

namespace App\Interfaces\History\Master;

interface PolicyDepartmentMstHistInterface
{
    /**
     * Get all history records.
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get history record by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create new history record.
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update history record.
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete history record.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
