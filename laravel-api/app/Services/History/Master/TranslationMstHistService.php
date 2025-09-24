<?php

namespace App\Services\History\Master;

use App\Http\Resources\History\Master\TranslationMstHistResource;
use App\Interfaces\History\Master\TranslationMstHistInterface;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Interfaces\Master\TranslationMstInterface;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TranslationMstHistService
{
    /**
     * @var TranslationMstHistInterface
     */
    protected $translationMstHistRepository;

    /**
     * @var TranslationMstInterface
     */
    protected $translationMstRepository;

    /**
     * @var LanguageMstInterface
     */
    protected $languageMstRepository;

    /**
     * @var OriginalTranslatorMstInterface
     */
    protected $originalTranslatorMstRepository;

    /**
     * TranslationMstHistService constructor.
     *
     * @param TranslationMstHistInterface $translationMstHistRepository
     * @param TranslationMstInterface $translationMstRepository
     * @param LanguageMstInterface $languageMstRepository
     * @param OriginalTranslatorMstInterface $originalTranslatorMstRepository
     */
    public function __construct(
        TranslationMstHistInterface $translationMstHistRepository,
        TranslationMstInterface $translationMstRepository,
        LanguageMstInterface $languageMstRepository,
        OriginalTranslatorMstInterface $originalTranslatorMstRepository
    ) {
        $this->translationMstHistRepository = $translationMstHistRepository;
        $this->translationMstRepository = $translationMstRepository;
        $this->languageMstRepository = $languageMstRepository;
        $this->originalTranslatorMstRepository = $originalTranslatorMstRepository;
    }

    /**
     * Get list of translation history
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getList(array $payload): AnonymousResourceCollection
    {
        $data = $this->translationMstHistRepository->getList($payload);
        return TranslationMstHistResource::collection($data);
    }

    /**
     * Get translation history by ID
     *
     * @param int $id
     * @return TranslationMstHistResource
     * @throws Exception
     */
    public function getById(int $id): TranslationMstHistResource
    {
        $data = $this->translationMstHistRepository->getById($id);
        if (!$data) {
            throw new Exception("Translation history not found");
        }
        return new TranslationMstHistResource($data);
    }

    /**
     * Create translation history
     *
     * @param array $payload
     * @return TranslationMstHistResource
     * @throws Exception
     */
    public function create(array $payload): TranslationMstHistResource
    {
        // Validate translation exists
        $translation = $this->translationMstRepository->getById($payload['translation_mst_id']);
        if (!$translation) {
            throw new Exception("Translation not found");
        }

        // Validate language exists if provided
        if (isset($payload['language_id']) && $payload['language_id']) {
            $language = $this->languageMstRepository->getById($payload['language_id']);
            if (!$language) {
                throw new Exception("Language not found");
            }
        }

        // Validate original translator exists if provided
        if (isset($payload['original_id']) && $payload['original_id']) {
            $originalTranslator = $this->originalTranslatorMstRepository->getById($payload['original_id']);
            if (!$originalTranslator) {
                throw new Exception("Original translator not found");
            }
        }

        $data = $this->translationMstHistRepository->create($payload);
        return new TranslationMstHistResource($data);
    }

    /**
     * Get translation history by translation ID
     *
     * @param int $translationId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByTranslationId(int $translationId): AnonymousResourceCollection
    {
        // Validate translation exists
        $translation = $this->translationMstRepository->getById($translationId);
        if (!$translation) {
            throw new Exception("Translation not found");
        }

        $data = $this->translationMstHistRepository->getByTranslationId($translationId);
        return TranslationMstHistResource::collection($data);
    }

    /**
     * Get translation history by language ID
     *
     * @param int $languageId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByLanguageId(int $languageId): AnonymousResourceCollection
    {
        // Validate language exists
        $language = $this->languageMstRepository->getById($languageId);
        if (!$language) {
            throw new Exception("Language not found");
        }

        $data = $this->translationMstHistRepository->getByLanguageId($languageId);
        return TranslationMstHistResource::collection($data);
    }

    /**
     * Get translation history by original ID
     *
     * @param int $originalId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByOriginalId(int $originalId): AnonymousResourceCollection
    {
        // Validate original translator exists
        $originalTranslator = $this->originalTranslatorMstRepository->getById($originalId);
        if (!$originalTranslator) {
            throw new Exception("Original translator not found");
        }

        $data = $this->translationMstHistRepository->getByOriginalId($originalId);
        return TranslationMstHistResource::collection($data);
    }
}