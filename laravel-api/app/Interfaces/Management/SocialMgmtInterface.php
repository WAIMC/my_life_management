<?php

namespace App\Interfaces\Management;

interface SocialMgmtInterface
{
    /**
     * Get all socials with pagination
     *
     * @param array $payload
     * @return object
     */
    public function getList(array $payload);

    /**
     * Get social by ID
     *
     * @param int $id
     * @return object|null
     */
    public function getById(int $id);

    /**
     * Create a new social
     *
     * @param array $payload
     * @return object
     */
    public function create(array $payload);

    /**
     * Update an existing social
     *
     * @param array $payload
     * @param int $id
     * @return object|bool
     */
    public function update(array $payload, int $id);

    /**
     * Delete a social
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);
}
