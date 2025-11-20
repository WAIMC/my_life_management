<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\AdminMstInterface;
use App\Interfaces\History\Master\AdminMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\AdminMstResource;

class AdminMstService extends BaseService
{
    public function __construct(
        protected AdminMstInterface $adminMst,
        protected AdminMstHistInterface $adminMstHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->adminMstHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'admin_mst_id';
    }

    /**
     * Get admin mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->adminMst->list($payload);

        return AdminMstResource::collection($list);
    }

    /**
     * Store admin mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->adminMst->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update admin mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->adminMst->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete admin mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->adminMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->adminMst->executeDelete($payload['ids']);
    }
}
