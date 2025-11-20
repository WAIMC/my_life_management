<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\TranslationMstInterface;
use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\TranslationMstResource;

class TranslationMstService extends BaseService
{
    public function __construct(
        protected TranslationMstInterface $translationMst,
        protected TranslationMstHistInterface $translationMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->translationMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'translation_mst_id';
    }

    /**
     * Get translation mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->translationMst->list($payload);

        return TranslationMstResource::collection($list);
    }

    /**
     * Store translation mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->translationMst->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update translation mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->translationMst->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete translation mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->translationMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->translationMst->executeDelete($payload['ids']);
    }
}
