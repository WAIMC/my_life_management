<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface LanguageMstInterface
{
    /**
     * Get language list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new language
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update language
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete language
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
