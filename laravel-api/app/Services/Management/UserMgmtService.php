<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\UserMgmtInterface;
use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\UserMgmtResource;

class UserMgmtService extends BaseService
{
    public function __construct(
        protected UserMgmtInterface $userMgmt,
        protected UserMgmtHistInterface $userMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->userMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'user_mgmt_id';
    }

    /**
     * Get user mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->userMgmt->list($payload);

        return UserMgmtResource::collection($list);
    }

    /**
     * Store user mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->userMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update user mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->userMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete user mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->userMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->userMgmt->executeDelete($payload['ids']);
    }
}
