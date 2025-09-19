<?php

namespace App\Interfaces\History\Master;

interface AdminMstHistInterface
{
    /**
     * Get all admin history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get admin history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create new admin history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update admin history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete admin history record
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
