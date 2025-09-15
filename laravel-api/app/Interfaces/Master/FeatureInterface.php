<?php

declare(strict_types=1);

namespace App\Interfaces\Master;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface FeatureInterface extends BaseInterface
{
    /**
     * Get feature list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store feature
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Update feature
     *
     * @param array $payload
     * @return void
     */
    public function executeUpdate(array $payload): void;

    /**
     * Delete feature
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
