<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\LanguageMstHistInterface;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\History\Master\LanguageMstHistResource;

class LanguageMstHistService
{
    public function __construct(
        protected LanguageMstHistInterface $languageMstHist
    )
    {
    }

    /**
     * Get language mst hist list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->languageMstHist->list($payload);

        return LanguageMstHistResource::collection($list);
    }

    /**
     * Store language mst hist
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->languageMstHist->executeStore($payload);
    }

    /**
     * Update language mst hist
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->languageMstHist->executeUpdate($payload);
    }

    /**
     * Delete language mst hist
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->languageMstHist->executeDelete($payload['ids']);
    }
}
