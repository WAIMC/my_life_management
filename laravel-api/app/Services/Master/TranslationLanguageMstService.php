<?php

namespace App\Services\Master;

use App\Services\BaseJunctionService;
use App\Interfaces\Master\TranslationLanguageMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\TranslationLanguageMstResource;

class TranslationLanguageMstService extends BaseJunctionService
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
            $this->validateExistence(
                $payload['delete'],
                fn($values) => $this->translationLanguageMst->getTranslationLanguageMstId($values),
                'translation_language_id',
                'translation_language_mst'
            );
            $this->translationLanguageMst->executeDelete($payload['delete']);
        }

        // Insert translation language mst
        if ($payload['insert']) {
            $this->validateNonExistence(
                $payload['insert'],
                fn($values) => $this->translationLanguageMst->getTranslationLanguageMstId($values),
                'translation_language_id',
                'translation_language_mst'
            );
            $this->translationLanguageMst->executeStore($payload['insert']);
        }

        return true;
    }
}
