<?php

namespace App\Interfaces\Management;

interface UserMgmtInterface
{
    /**
     * Get list of users
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload);

    /**
     * Get user by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Get user by email
     *
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email);

    /**
     * Get user by username
     *
     * @param string $userName
     * @return mixed
     */
    public function getByUserName(string $userName);

    /**
     * Create user
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Update user
     *
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);

    /**
     * Delete user
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * Get users by department ID
     *
     * @param int $departmentId
     * @return mixed
     */
    public function getByDepartmentId(int $departmentId);

    /**
     * Get users by role ID
     *
     * @param int $roleId
     * @return mixed
     */
    public function getByRoleId(int $roleId);
}