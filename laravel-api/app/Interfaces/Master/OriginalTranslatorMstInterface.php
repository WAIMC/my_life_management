<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface OriginalTranslatorMstInterface
{
    /**
     * Get original translator list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new original translator
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update original translator
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete original translator
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
