<?php

namespace App\Services\Master;

use App\Interfaces\Master\LanguageMstInterface;
use App\Http\Resources\Master\LanguageMstResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LanguageMstService
{
    /**
     * LanguageMstService constructor.
     *
     * @param LanguageMstInterface $languageMst
     */
    public function __construct(protected LanguageMstInterface $languageMst)
    {}

    /**
     * Get all languages
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->languageMst->list($payload);

        return LanguageMstResource::collection($list);
    }

    /**
     * Create new language
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->languageMst->executeStore($payload);
    }

    /**
     * Update language
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->languageMst->executeUpdate($payload);
    }

    /**
     * Delete language
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->languageMst->executeDelete($payload['ids']);
    }
}
