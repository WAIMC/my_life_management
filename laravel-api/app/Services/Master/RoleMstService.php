<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\RoleMstInterface;
use App\Interfaces\History\Master\RoleMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\RoleMstResource;

class RoleMstService extends BaseService
{
    public function __construct(
        protected RoleMstInterface $roleMst,
        protected RoleMstHistInterface $roleMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->roleMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'role_mst_id';
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
        $this->recordHistory($id, ActionType::CREATE, $payload);

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
        $this->recordHistory($id, ActionType::UPDATE, $payload);

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
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->roleMst->executeDelete($payload['ids']);
    }
}
