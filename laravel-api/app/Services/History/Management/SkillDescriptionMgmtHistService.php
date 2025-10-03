<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\SkillDescriptionMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\SkillDescriptionMgmtHistResource;

class SkillDescriptionMgmtHistService
{
    public function __construct(
        protected SkillDescriptionMgmtHistInterface $skillDescriptionMgmtHist
    )
    {
    }

    /**
     * Get skill description mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->skillDescriptionMgmtHist->list($payload);

        return SkillDescriptionMgmtHistResource::collection($list);
    }

    /**
     * Store skill description mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->skillDescriptionMgmtHist->executeStore($payload);
    }

    /**
     * Update skill description mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->skillDescriptionMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete skill description mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->skillDescriptionMgmtHist->executeDelete($payload['ids']);
    }
}
