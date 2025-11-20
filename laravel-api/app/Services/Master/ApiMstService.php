<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\ApiMstInterface;
use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\ApiMstResource;

class ApiMstService extends BaseService
{
    public function __construct(
        protected ApiMstInterface $apiMst,
        protected ApiMstHistInterface $apiMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->apiMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'api_mst_id';
    }

    /**
     * Get api mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->apiMst->list($payload);

        return ApiMstResource::collection($list);
    }

    /**
     * Store api mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->apiMst->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update api mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->apiMst->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete api mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->apiMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->apiMst->executeDelete($payload['ids']);
    }
}
