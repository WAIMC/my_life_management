<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface RoleInterface extends BaseInterface
{
    /**
     * Get role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Update role
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void;

    /**
     * Delete role
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;

    /**
     * Check is root
     *
     * @param int $id
     * @return bool
     */
    public function isRoot(int $id): bool;
}
