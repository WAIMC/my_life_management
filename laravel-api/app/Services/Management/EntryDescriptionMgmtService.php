<?php

namespace App\Services\Management;

use App\Services\BaseService;
use App\Interfaces\Management\EntryDescriptionMgmtInterface;
use App\Interfaces\History\Management\EntryDescriptionMgmtHistInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Management\EntryDescriptionMgmtResource;

class EntryDescriptionMgmtService extends BaseService
{
    public function __construct(
        protected EntryDescriptionMgmtInterface $entryDescriptionMgmt,
        protected EntryDescriptionMgmtHistInterface $entryDescriptionMgmtHist
    ) {
    }

    protected function getHistoryRepository()
    {
        return $this->entryDescriptionMgmtHist;
    }

    protected function getHistoryForeignKey(): string
    {
        return 'entry_description_mgmt_id';
    }

    /**
     * Get entry description mgmt list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->entryDescriptionMgmt->list($payload);

        return EntryDescriptionMgmtResource::collection($list);
    }

    /**
     * Store entry description mgmt
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        $id = $this->entryDescriptionMgmt->executeStore($payload);
        $this->recordHistory($id, ActionType::CREATE, $payload);

        return $id;
    }

    /**
     * Update entry description mgmt
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        $id = $payload['id'];
        $affected = $this->entryDescriptionMgmt->executeUpdate($payload);
        $this->recordHistory($id, ActionType::UPDATE, $payload);

        return $affected;
    }

    /**
     * Delete entry description mgmt
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        if (!isset($payload['ids']) || !is_array($payload['ids'])) {
            $this->entryDescriptionMgmt->executeDelete($payload['ids'] ?? []);
            return;
        }

        foreach ($payload['ids'] as $id) {
            $this->recordHistory($id, ActionType::DELETE, $payload);
        }

        $this->entryDescriptionMgmt->executeDelete($payload['ids']);
    }
}
