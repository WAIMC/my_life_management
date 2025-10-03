<?php

namespace App\Services\Management;

use App\Interfaces\Management\UserMgmtInterface;
use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\UserMgmtResource;

class UserMgmtService
{
    public function __construct(
        protected UserMgmtInterface $userMgmt, protected UserMgmtHistInterface $userMgmtHist
    )
    {
    }

    /**
     * Get user mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->userMgmt->list($payload);

        return UserMgmtResource::collection($list);
    }

    /**
     * Store user mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->userMgmt->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['user_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->userMgmtHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update user mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->userMgmt->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['user_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->userMgmtHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete user mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->userMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['user_mgmt_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->userMgmtHist->executeStore($historyPayload);
            }
        }

        $this->userMgmt->executeDelete($payload['ids']);
    }
}
