<?php

declare(strict_types=1);

namespace App\Interfaces\Management;

use App\Interfaces\BaseInterface;
use Illuminate\Support\Collection;

interface CategorySkillMgmtInterface extends BaseInterface
{
    /**
     * Get list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store record
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete record
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;

    /**
     * Get ids
     *
     * @param array $tuples
     * @return Collection
     */
    public function getCategorySkillMgmtId(array $tuples): Collection;
}
