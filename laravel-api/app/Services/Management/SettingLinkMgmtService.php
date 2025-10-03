<?php

namespace App\Services\Management;

use App\Interfaces\Management\SettingLinkMgmtInterface;
use App\Interfaces\History\Management\SettingLinkMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SettingLinkMgmtResource;

class SettingLinkMgmtService
{
    public function __construct(
        protected SettingLinkMgmtInterface $settingLinkMgmt, protected SettingLinkMgmtHistInterface $settingLinkMgmtHist
    )
    {
    }

    /**
     * Get setting link mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->settingLinkMgmt->list($payload);

        return SettingLinkMgmtResource::collection($list);
    }

    /**
     * Store setting link mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->settingLinkMgmt->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['setting_link_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->settingLinkMgmtHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update setting link mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->settingLinkMgmt->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['setting_link_mgmt_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->settingLinkMgmtHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete setting link mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->settingLinkMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['setting_link_mgmt_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->settingLinkMgmtHist->executeStore($historyPayload);
            }
        }

        $this->settingLinkMgmt->executeDelete($payload['ids']);
    }
}
