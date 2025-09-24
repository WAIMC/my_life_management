<?php

namespace App\Interfaces\History\Master;

use Illuminate\Support\Collection;

interface LanguageMstHistInterface
{
    /**
     * Get language master history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new language master history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update language master history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete language master history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
