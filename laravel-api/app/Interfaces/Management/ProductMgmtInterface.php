<?php

namespace App\Interfaces\Management;

interface ProductMgmtInterface
{
    /**
     * Get all products with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get product by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create a new product
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update an existing product
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete a product
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
