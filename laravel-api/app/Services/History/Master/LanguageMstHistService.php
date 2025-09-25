<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\LanguageMstHistResource;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstHistService
{
    /**
     * LanguageMstHistService constructor.
     *
     * @param LanguageMstHistInterface $languageMstHist
     */
    public function __construct(protected LanguageMstHistInterface $languageMstHist)
    {}

    /**
     * Handle find language list
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
     * Handle store language history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->languageMstHist->executeStore($payload);
    }

    /**
     * Handle update language history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->languageMstHist->executeUpdate($payload);
    }

    /**
     * Delete language history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->languageMstHist->executeDelete($payload['ids']);
    }
}
