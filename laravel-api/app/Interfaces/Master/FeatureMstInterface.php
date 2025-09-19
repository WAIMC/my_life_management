<?php

namespace App\Interfaces\Master;

interface FeatureMstInterface
{
    /**
     * Get all features with optional filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Get feature by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed;

    /**
     * Create new feature
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update feature
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id): mixed;

    /**
     * Delete feature
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;
}
