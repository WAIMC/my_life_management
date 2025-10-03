<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\OriginalTranslatorMstHistResource;

class OriginalTranslatorMstHistService
{
    public function __construct(
        protected OriginalTranslatorMstHistInterface $originalTranslatorMstHist
    )
    {
    }

    /**
     * Get original translator mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->originalTranslatorMstHist->list($payload);

        return OriginalTranslatorMstHistResource::collection($list);
    }

    /**
     * Store original translator mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->originalTranslatorMstHist->executeStore($payload);
    }

    /**
     * Update original translator mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->originalTranslatorMstHist->executeUpdate($payload);
    }

    /**
     * Delete original translator mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->originalTranslatorMstHist->executeDelete($payload['ids']);
    }
}
