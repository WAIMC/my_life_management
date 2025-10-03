<?php

namespace App\Services\Master;

use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\DepartmentMstResource;

class DepartmentMstService
{
    public function __construct(
        protected DepartmentMstInterface $departmentMst, protected DepartmentMstHistInterface $departmentMstHist
    )
    {
    }

    /**
     * Get department mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->departmentMst->list($payload);

        return DepartmentMstResource::collection($list);
    }

    /**
     * Store department mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->departmentMst->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['department_mst_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->departmentMstHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update department mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->departmentMst->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['department_mst_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->departmentMstHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete department mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->departmentMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['department_mst_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->departmentMstHist->executeStore($historyPayload);
            }
        }

        $this->departmentMst->executeDelete($payload['ids']);
    }
}
