<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\LanguageMstResource;

class LanguageMstService extends BaseService
{
    public function __construct(
        protected LanguageMstInterface $languageMst,
        protected LanguageMstHistInterface $languageMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->languageMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'language_mst_id';
    }

    /**
     * Get language mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->languageMst->list($payload);

        return LanguageMstResource::collection($list);
    }

    /**
     * Store language mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->languageMst->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update language mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->languageMst->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete language mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->languageMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->languageMst->executeDelete($payload['ids']);
    }
}
