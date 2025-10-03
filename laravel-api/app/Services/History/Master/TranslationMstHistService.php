<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\TranslationMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\TranslationMstHistResource;

class TranslationMstHistService
{
    public function __construct(
        protected TranslationMstHistInterface $translationMstHist
    )
    {
    }

    /**
     * Get translation mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->translationMstHist->list($payload);

        return TranslationMstHistResource::collection($list);
    }

    /**
     * Store translation mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->translationMstHist->executeStore($payload);
    }

    /**
     * Update translation mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->translationMstHist->executeUpdate($payload);
    }

    /**
     * Delete translation mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->translationMstHist->executeDelete($payload['ids']);
    }
}
