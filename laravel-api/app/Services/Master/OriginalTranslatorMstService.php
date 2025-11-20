<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\OriginalTranslatorMstResource;

class OriginalTranslatorMstService extends BaseService
{
    public function __construct(
        protected OriginalTranslatorMstInterface $originalTranslatorMst,
        protected OriginalTranslatorMstHistInterface $originalTranslatorMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->originalTranslatorMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'original_translator_mst_id';
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
        $this->recordHistory($id, ActionType::CREATE, $payload);

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
        $this->recordHistory($id, ActionType::UPDATE, $payload);

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
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->originalTranslatorMst->executeDelete($payload['ids']);
    }
}
