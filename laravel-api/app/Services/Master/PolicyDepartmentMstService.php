<?php

namespace App\Services\Master;

use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\PolicyDepartmentMstResource;

class PolicyDepartmentMstService
{
    public function __construct(
        protected PolicyDepartmentMstInterface $policyDepartmentMst, protected PolicyDepartmentMstHistInterface $policyDepartmentMstHist
    )
    {
    }

    /**
     * Get policy department mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->policyDepartmentMst->list($payload);

        return PolicyDepartmentMstResource::collection($list);
    }

    /**
     * Store policy department mst
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->policyDepartmentMst->executeStore($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['policy_department_mst_id'] = $id;
        $historyPayload['action'] = ActionType::CREATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->policyDepartmentMstHist->executeStore($historyPayload);

        return $id;
    }

    /**
     * Update policy department mst
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->policyDepartmentMst->executeUpdate($payload);

        $recordResource = $this->list(['id' => $id]);
        $historyData = $recordResource->collection->first()->toArray();
        $historyPayload = $historyData;
        unset($historyPayload['id']);
        $historyPayload['policy_department_mst_id'] = $id;
        $historyPayload['action'] = ActionType::UPDATE;
        $historyPayload['author_id'] = $payload['author_id'] ?? null;
        $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
        $this->policyDepartmentMstHist->executeStore($historyPayload);

        return $affected;
    }

    /**
     * Delete policy department mst
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->policyDepartmentMst->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $recordResource = $this->list(['id' => $id]);
            $record = $recordResource->collection->first();
            if ($record) {
                $historyData = $record->toArray();
                $historyPayload = $historyData;
                unset($historyPayload['id']);
                $historyPayload['policy_department_mst_id'] = $id;
                $historyPayload['action'] = ActionType::DELETE;
                $historyPayload['author_id'] = $payload['author_id'] ?? null;
                $historyPayload['created_at'] = now()->format('Y-m-d H:i:s');
                $this->policyDepartmentMstHist->executeStore($historyPayload);
            }
        }

        $this->policyDepartmentMst->executeDelete($payload['ids']);
    }
}
