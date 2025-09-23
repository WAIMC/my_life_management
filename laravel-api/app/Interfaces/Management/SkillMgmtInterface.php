<?php

namespace App\Interfaces\Management;

interface SkillMgmtInterface
{
    /**
     * Get all skills with pagination and filtering
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload): mixed;

    /**
     * Find skill by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Create new skill
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update skill by ID
     *
     * @param int $id
     * @param array $payload
     * @return mixed
     */
    public function update(int $id, array $payload): mixed;

    /**
     * Delete skill by ID
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
