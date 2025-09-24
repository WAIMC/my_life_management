<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface SkillMgmtInterface
{
    /**
     * Get skill list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Create new skill
     *
     * @param array $payload
     * @return int
     */
    public function executeStore(array $payload): int;

    /**
     * Update skill
     *
     * @param array $payload
     * @return int
     */
    public function executeUpdate(array $payload): int;

    /**
     * Delete skill
     *
     * @param array $ids
     * @return void
     */
    public function executeDelete(array $ids): void;
}
