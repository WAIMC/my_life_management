<?php

namespace App\Services\History\Management;

use App\Http\Resources\History\Management\SkillMgmtHistResource;
use App\Interfaces\History\Management\SkillMgmtHistInterface;
use App\Interfaces\Management\SkillMgmtInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillMgmtHistService
{
    /**
     * SkillMgmtHistService constructor
     *
     * @param SkillMgmtHistInterface $skillMgmtHist
     * @param SkillMgmtInterface $skillMgmt
     */
    public function __construct(
        protected SkillMgmtHistInterface $skillMgmtHist,
        protected SkillMgmtInterface $skillMgmt
    ) {}

    /**
     * Handle find admin list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->skillMgmtHist->list($payload);

        return SkillMgmtHistResource::collection($list);
    }

    /**
     * Handle store admin
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->skillMgmtHist->executeStore($payload);
    }

    /**
     * Handle update skill history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->skillMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete skill history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->skillMgmtHist->executeDelete($payload['ids']);
    }
}
