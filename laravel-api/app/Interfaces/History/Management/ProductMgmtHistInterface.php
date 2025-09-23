<?php

namespace App\Interfaces\History\Management;

interface ProductMgmtHistInterface
{
    /**
     * Get all product history records with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get product history record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create a new product history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update an existing product history record
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete a product history record
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
