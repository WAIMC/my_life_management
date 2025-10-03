<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SkillMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\SkillMgmtHistResource;

class SkillMgmtHistService
{
    public function __construct(
        protected SkillMgmtHistInterface $skillMgmtHist
    )
    {
    }

    /**
     * Get skill mgmt hist list
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
     * Store skill mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->skillMgmtHist->executeStore($payload);
    }

    /**
     * Update skill mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->skillMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete skill mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->skillMgmtHist->executeDelete($payload['ids']);
    }
}
