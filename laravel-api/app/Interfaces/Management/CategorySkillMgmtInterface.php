<?php

namespace App\Interfaces\Management;

use Illuminate\Support\Collection;

interface CategorySkillMgmtInterface
{
    /**
     * Get category skill list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store category skill
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete category skill
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;

    /**
     * Get category skill id
     *
     * @param array $categorySkillIds
     * @return Collection
     */
    public function getCategorySkillId(array $categorySkillIds): Collection;
}
