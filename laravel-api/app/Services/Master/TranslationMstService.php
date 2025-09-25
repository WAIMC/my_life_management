<?php

namespace App\Services\Master;

use App\Interfaces\Master\TranslationMstInterface;
use App\Http\Resources\Master\TranslationMstResource;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\CommonVal;
use App\Constants\Messages;

class TranslationMstService
{
    /**
     * Constructor
     * 
     * @param TranslationMstInterface $translationMst
     * @param LanguageMstInterface $languageMst
     * @param OriginalTranslatorMstInterface $originalTranslatorMst
     */
    public function __construct(
        protected TranslationMstInterface $translationMst,
        protected LanguageMstInterface $languageMst,
        protected OriginalTranslatorMstInterface $originalTranslatorMst
    ) {}

    /**
     * Get translation list
     * 
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $translations = $this->translationMst->list($payload);

        return TranslationMstResource::collection($translations);
    }

    /**
     * Store new translation
     * 
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete translation
        if ($payload['delete']) {
            self::checkExistsTranslation($payload['delete']);
            $this->translationMst->executeDelete($payload['delete']);
        }

        // Insert translation
        if ($payload['insert']) {
            self::checkNotExistsTranslation($payload['insert']);
            $this->translationMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist translation
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsTranslation(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return '(' . (int)$item['language_id'] . ', ' . (int)$item['original_id'] . ')';
        })->all();

        $translationMstId = $this->translationMst->getTranslationMstId($values);

        // Compare $values and $translationMstId, get the differences
        $differences = array_udiff($values, $translationMstId, function ($a, $b) {
            return strcmp((string)$a, (string)$b);
        });

        // Join the differences into a string
        $diffString = implode(', ', $differences);

        // Throw exception if there are differences
        if (!empty($differences)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0017,
                    [
                        'attributes' => __('messages.translation_id') . ': ' . $diffString,
                        'tableName' => __('messages.translation_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist translation
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsTranslation(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            return '(' . (int)$item['language_id'] . ', ' . (int)$item['original_id'] . ')';
        })->all();

        $translationMstId = $this->translationMst->getTranslationMstId($values)->toArray();
        $diffString = implode(', ', $translationMstId);

        // Throw exception if there are exist
        if (!empty($translationMstId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.translation_id') . ': ' . $diffString,
                        'tableName' => __('messages.translation_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
