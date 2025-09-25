<?php

namespace App\Services\History\Master;

use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Http\Resources\History\Master\OriginalTranslatorMstHistResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstHistService
{
    /**
     * OriginalTranslatorMstHistService constructor.
     *
     * @param OriginalTranslatorMstHistInterface $originalTranslatorMstHist
     */
    public function __construct(protected OriginalTranslatorMstHistInterface $originalTranslatorMstHist)
    {}

    /**
     * Handle find admin list
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
     * Handle store original translator history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->originalTranslatorMstHist->executeStore($payload);
    }

    /**
     * Handle update original translator history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->originalTranslatorMstHist->executeUpdate($payload);
    }

    /**
     * Delete original translator history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->originalTranslatorMstHist->executeDelete($payload['ids']);
    }
}
