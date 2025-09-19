<?php

namespace App\Interfaces\Master;

interface RoleMstInterface
{
    /**
     * Get all roles with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get role by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create new role
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update role
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete role
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
