<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\SocialMgmtInterface;
use App\Interfaces\History\Management\SocialMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\SocialMgmtResource;

class SocialMgmtService extends BaseService
{
    public function __construct(
        protected SocialMgmtInterface $socialMgmt,
        protected SocialMgmtHistInterface $socialMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->socialMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'social_mgmt_id';
    }

    /**
     * Get social mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->socialMgmt->list($payload);

        return SocialMgmtResource::collection($list);
    }

    /**
     * Store social mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->socialMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update social mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->socialMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete social mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->socialMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->socialMgmt->executeDelete($payload['ids']);
    }
}
