<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\TokenMstInterface;
use App\Interfaces\History\Master\TokenMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\TokenMstResource;

class TokenMstService extends BaseService
{
    public function __construct(
        protected TokenMstInterface $tokenMst,
        protected TokenMstHistInterface $tokenMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->tokenMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'token_mst_id';
    }

    /**
     * Get token mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->tokenMst->list($payload);

        return TokenMstResource::collection($list);
    }

    /**
     * Store token mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->tokenMst->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update token mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->tokenMst->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete token mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->tokenMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->tokenMst->executeDelete($payload['ids']);
    }
}
