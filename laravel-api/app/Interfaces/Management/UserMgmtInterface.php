<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface UserMgmtInterface
{
    /**
     * Get User list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new User
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update User
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete User
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
