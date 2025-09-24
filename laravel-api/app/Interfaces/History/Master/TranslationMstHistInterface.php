<?php

namespace App\Interfaces\History\Master;

interface TranslationMstHistInterface
{
    /**
     * Get list of translation history
     *
     * @param array $payload
     * @return mixed
     */
    public function getList(array $payload);

    /**
     * Get translation history by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create translation history
     *
     * @param array $payload
     * @return mixed
     */
    public function create(array $payload);

    /**
     * Get translation history by translation ID
     *
     * @param int $translationId
     * @return mixed
     */
    public function getByTranslationId(int $translationId);

    /**
     * Get translation history by language ID
     *
     * @param int $languageId
     * @return mixed
     */
    public function getByLanguageId(int $languageId);

    /**
     * Get translation history by original ID
     *
     * @param int $originalId
     * @return mixed
     */
    public function getByOriginalId(int $originalId);
}