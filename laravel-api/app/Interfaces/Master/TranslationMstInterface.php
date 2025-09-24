<?php

namespace App\Interfaces\Master;

use Illuminate\Support\Collection;

interface TranslationMstInterface
{
    /**
     * Get translation list
     *
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload): Collection;

    /**
     * Store api role
     *
     * @param array $payload
     * @return void
     */
    public function executeStore(array $payload): void;

    /**
     * Delete translation
     *
     * @param array $payload
     * @return void
     */
    public function executeDelete(array $payload): void;

    /**
     * Get translation id
     *
     * @param array $translationIds
     * @return Collection
     */
    public function getTranslationMstId(array $translationIds): Collection;
}
