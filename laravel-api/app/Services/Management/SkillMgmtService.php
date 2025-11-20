<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\SkillMgmtInterface;
use App\Interfaces\History\Management\SkillMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SkillMgmtResource;

class SkillMgmtService extends BaseService
{
    public function __construct(
        protected SkillMgmtInterface $skillMgmt,
        protected SkillMgmtHistInterface $skillMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->skillMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'skill_mgmt_id';
    }

    /**
     * Get skill mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->skillMgmt->list($payload);

        return SkillMgmtResource::collection($list);
    }

    /**
     * Store skill mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->skillMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update skill mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->skillMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete skill mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->skillMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->skillMgmt->executeDelete($payload['ids']);
    }
}
