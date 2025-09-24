<?php

namespace App\Interfaces\History\Master;

use Illuminate\Support\Collection;

interface TranslationMstHistInterface
{
    /**
     * Get translation master history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new translation master history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update translation master history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete translation master history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
