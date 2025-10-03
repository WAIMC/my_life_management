<?php

namespace App\Services\Master;

use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\OriginalTranslatorMstResource;

class OriginalTranslatorMstService
{
    public function __construct(
        protected OriginalTranslatorMstInterface $originalTranslatorMst, protected OriginalTranslatorMstHistInterface $originalTranslatorMstHist
    )
    {
    }

    /**
     * Get original translator mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->originalTranslatorMst->list($payload);

        return OriginalTranslatorMstResource::collection($list);
    }

    /**
     * Store original translator mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->originalTranslatorMst->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['original_translator_mst_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->originalTranslatorMstHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update original translator mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->originalTranslatorMst->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['original_translator_mst_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->originalTranslatorMstHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete original translator mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->originalTranslatorMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['original_translator_mst_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->originalTranslatorMstHist->executeStore($historyPayload);
            }
        }

        $this->originalTranslatorMst->executeDelete($payload['ids']);
    }
}
