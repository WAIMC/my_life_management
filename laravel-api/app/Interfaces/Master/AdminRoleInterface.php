<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface AdminRoleInterface extends BaseInterface
{
    /**
     * Get admin role list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store admin role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete admin role
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;

    /**
     * Get admin role id
     *
     * @param array $adminIds
     * @return Collection
     */
    public function getAdminRoleId(array $adminIds): Collection;
}
