<?php

namespace App\Interfaces\History\Management;

use Illuminate\Support\Collection;

interface SocialMgmtHistInterface
{
    /**
     * Get social history list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new social history
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update social history
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete social history
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
