<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\EntryMgmtHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Management\EntryMgmtHistResource;

class EntryMgmtHistService
{
    public function __construct(
        protected EntryMgmtHistInterface $entryMgmtHist
    )
    {
    }

    /**
     * Get entry mgmt hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->entryMgmtHist->list($payload);

        return EntryMgmtHistResource::collection($list);
    }

    /**
     * Store entry mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->entryMgmtHist->executeStore($payload);
    }

    /**
     * Update entry mgmt hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->entryMgmtHist->executeUpdate($payload);
    }

    /**
     * Delete entry mgmt hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->entryMgmtHist->executeDelete($payload['ids']);
    }
}
