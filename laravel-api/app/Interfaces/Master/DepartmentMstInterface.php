<?php

namespace App\Interfaces\Master;

interface DepartmentMstInterface
{
    /**
     * Get all departments with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get department by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Get department by code
     *
     * @param string $code
     * @return mixed
     */
    public function getByCode(string $code): mixed;

    /**
     * Create new department
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update department
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete department
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
