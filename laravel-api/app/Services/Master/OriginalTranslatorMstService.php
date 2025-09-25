<?php

namespace App\Services\Master;

use App\Http\Resources\Master\OriginalTranslatorMstResource;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class OriginalTranslatorMstService
{
    /**
     * OriginalTranslatorMstService constructor.
     *
     * @param OriginalTranslatorMstInterface $originalTranslatorMst
     */
    public function __construct(protected OriginalTranslatorMstInterface $originalTranslatorMst)
    {}

    /**
     * Get list of original translators
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->originalTranslatorMst->list($payload);

        return OriginalTranslatorMstResource::collection($list);
    }

    /**
     * Create original translator
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->originalTranslatorMst->executeStore($payload);
    }

    /**
     * Update original translator
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->originalTranslatorMst->executeUpdate($payload);
    }

    /**
     * Delete original translator
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->originalTranslatorMst->executeDelete($payload['ids']);
    }
}
