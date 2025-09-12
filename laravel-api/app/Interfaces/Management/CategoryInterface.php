<?php

declare(strict_types=1);

namespace App\Interfaces\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface CategoryInterface extends BaseInterface
{
    /**
     * Get category list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new category
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Update category
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void;

    /**
     * Delete category
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
