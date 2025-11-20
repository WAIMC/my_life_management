<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\SkillDescriptionMgmtInterface;
use App\Interfaces\History\Management\SkillDescriptionMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SkillDescriptionMgmtResource;

class SkillDescriptionMgmtService extends BaseService
{
    public function __construct(
        protected SkillDescriptionMgmtInterface $skillDescriptionMgmt,
        protected SkillDescriptionMgmtHistInterface $skillDescriptionMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->skillDescriptionMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'skill_description_mgmt_id';
    }

    /**
     * Get skill description mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->skillDescriptionMgmt->list($payload);

        return SkillDescriptionMgmtResource::collection($list);
    }

    /**
     * Store skill description mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->skillDescriptionMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update skill description mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->skillDescriptionMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete skill description mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->skillDescriptionMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->skillDescriptionMgmt->executeDelete($payload['ids']);
    }
}
