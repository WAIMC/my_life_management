<?php

namespace App\Interfaces\History\Management;

interface SkillMgmtHistInterface
{
    /**
     * Get all skill history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload = []): mixed;

    /**
     * Find skill history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): mixed;

    /**
     * Find skill history records by skill ID
     *
     * @param int $skillId
     * @return mixed
     */
    public function findBySkillId(int $skillId): mixed;

    /**
     * Create a new skill history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload): mixed;
}
