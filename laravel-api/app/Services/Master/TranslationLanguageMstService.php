<?php

namespace App\Services\Master;

use App\Interfaces\Master\TranslationLanguageMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\TranslationLanguageMstResource;

class TranslationLanguageMstService
{
    public function __construct(
        protected TranslationLanguageMstInterface $translationLanguageMst
    )
    {
    }

    /**
     * Get translation language mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->translationLanguageMst->list($payload);

        return TranslationLanguageMstResource::collection($list);
    }

    /**
     * Update translation language mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Delete translation language mst
        if ($payload['delete']) {
            self::checkExistsTranslationLanguageMst($payload['delete']);
            $this->translationLanguageMst->executeDelete($payload['delete']);
        }

        // Insert translation language mst
        if ($payload['insert']) {
            self::checkNotExistsTranslationLanguageMst($payload['insert']);
            $this->translationLanguageMst->executeStore($payload['insert']);
        }

        return true;
    }

    /**
     * Check exist translation language mst
     *
     * @param array $payload
     * @return void
     */
    private function checkExistsTranslationLanguageMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['translation_mst_id'],(int)$item['language_mst_id']];
        })->all();

        $TranslationLanguageMstId = $this->translationLanguageMst->getTranslationLanguageMstId($values);

        // Compare $values and $TranslationLanguageMstId, get the differences
        $differences = array_udiff($values, $TranslationLanguageMstId, function ($a, $b) {
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
                        'attributes' => __('messages.translation_language_id') . ': ' . $diffString,
                        'tableName' => __('messages.translation_language_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }

    /**
     * Check not exist translation language mst
     *
     * @param array $payload
     * @return void
     */
    private function checkNotExistsTranslationLanguageMst(array $payload): void
    {
        $values = collect($payload)->map(function ($item) {
            // Make sure the data is an integer and escaped
            return [(int)$item['translation_mst_id'],(int)$item['language_mst_id']];
        })->all();

        $TranslationLanguageMstId = $this->translationLanguageMst->getTranslationLanguageMstId($values)->toArray();
        $diffString = implode(', ', $TranslationLanguageMstId);

        // Throw exception if there are exist
        if (!empty($TranslationLanguageMstId)) {
            throw new LogicException(
                Messages::getMessage(
                    Messages::E0020,
                    [
                        'attributes' => __('messages.translation_language_id') . ': ' . $diffString,
                        'tableName' => __('messages.translation_language_mst')
                    ]
                ),
                CommonVal::HTTP_UNPROCESSABLE_CONTENT
            );
        }
    }
}
