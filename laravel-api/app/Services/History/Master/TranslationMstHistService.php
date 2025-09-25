<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\TranslationMstHistResource;
use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Interfaces\Master\TranslationMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationMstHistService
{
    /**
     * Constructor.
     *
     * @param TranslationMstHistInterface $translationMstHist
     * @param TranslationMstInterface $translationMst
     * @param LanguageMstInterface $languageMst
     * @param OriginalTranslatorMstInterface $originalTranslatorMst
     */
    public function __construct(
        protected TranslationMstHistInterface $translationMstHist,
        protected TranslationMstInterface $translationMst,
        protected LanguageMstInterface $languageMst,
        protected OriginalTranslatorMstInterface $originalTranslatorMst
    ) {}

    /**
     * Handle find translation list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->translationMst->list($payload);

        return TranslationMstHistResource::collection($list);
    }

    /**
     * Handle store translation history
     *
     * @param array $payload
     * @return int
     */
    public function store(array $payload): int
    {
        return $this->translationMstHist->executeStore($payload);
    }

    /**
     * Handle update translation history
     *
     * @param array $payload
     * @return int
     */
    public function update(array $payload): int
    {
        return $this->translationMstHist->executeUpdate($payload);
    }

    /**
     * Delete translation history
     *
     * @param array $payload
     * @return void
     */
    public function delete(array $payload): void
    {
        $this->translationMstHist->executeDelete($payload['ids']);
    }
}
