<?php

namespace App\Services\Master;

use App\Interfaces\Master\RoleMstInterface;
use App\Interfaces\History\Master\RoleMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\RoleMstResource;

class RoleMstService
{
    public function __construct(
        protected RoleMstInterface $roleMst, protected RoleMstHistInterface $roleMstHist
    )
    {
    }

    /**
     * Get role mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->roleMst->list($payload);

        return RoleMstResource::collection($list);
    }

    /**
     * Store role mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->roleMst->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['role_mst_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->roleMstHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update role mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->roleMst->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['role_mst_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->roleMstHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete role mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->roleMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['role_mst_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->roleMstHist->executeStore($historyPayload);
            }
        }

        $this->roleMst->executeDelete($payload['ids']);
    }
}
