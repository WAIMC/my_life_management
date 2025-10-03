<?php

namespace App\Services\Management;

use App\Interfaces\Management\SkillDescriptionMgmtInterface;
use App\Interfaces\History\Management\SkillDescriptionMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SkillDescriptionMgmtResource;

class SkillDescriptionMgmtService
{
    public function __construct(
        protected SkillDescriptionMgmtInterface $skillDescriptionMgmt, protected SkillDescriptionMgmtHistInterface $skillDescriptionMgmtHist
    )
    {
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

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['skill_description_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->skillDescriptionMgmtHist->executeStore($historyPayload);

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

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['skill_description_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->skillDescriptionMgmtHist->executeStore($historyPayload);

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
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['skill_description_mgmt_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->skillDescriptionMgmtHist->executeStore($historyPayload);
            }
        }

        $this->skillDescriptionMgmt->executeDelete($payload['ids']);
    }
}
