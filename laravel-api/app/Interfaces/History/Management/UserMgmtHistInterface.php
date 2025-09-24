<?php

namespace App\Interfaces\History\Management;

interface UserMgmtHistInterface
{
    /**
     * Get all user history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAll(array $payload = []);

    /**
     * Get user history record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Get user history records by user management ID
     *
     * @param int $userMgmtId
     * @param array $payload
     * @return mixed
     */
    public function getByUserMgmtId(int $userMgmtId, array $payload = []);

    /**
     * Create a new user history record
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);
}