<?php

namespace App\Services\Master;

use App\Interfaces\Master\TranslationMstInterface;
use App\Http\Resources\Master\TranslationMstResource;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\DB;

class TranslationMstService
{
    protected $translationMstRepository;
    protected $languageMstRepository;
    protected $originalTranslatorMstRepository;
    
    /**
     * Constructor
     * 
     * @param TranslationMstInterface $translationMstRepository
     * @param LanguageMstInterface $languageMstRepository
     * @param OriginalTranslatorMstInterface $originalTranslatorMstRepository
     */
    public function __construct(
        TranslationMstInterface $translationMstRepository,
        LanguageMstInterface $languageMstRepository,
        OriginalTranslatorMstInterface $originalTranslatorMstRepository
    ) {
        $this->translationMstRepository = $translationMstRepository;
        $this->languageMstRepository = $languageMstRepository;
        $this->originalTranslatorMstRepository = $originalTranslatorMstRepository;
    }
    
    /**
     * Get translation list
     * 
     * @param array $payload
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function list(array $payload)
    {
        $translations = $this->translationMstRepository->list($payload);
        
        return TranslationMstResource::collection($translations);
    }
    
    /**
     * Get translation by ID
     * 
     * @param int $id
     * @return TranslationMstResource
     * @throws NotFoundException
     */
    public function getById(int $id)
    {
        $translation = $this->translationMstRepository->getById($id);
        
        if (!$translation) {
            throw new NotFoundException('Translation not found');
        }
        
        return new TranslationMstResource($translation);
    }
    
    /**
     * Get translations by language ID
     * 
     * @param int $languageId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws NotFoundException
     */
    public function getByLanguageId(int $languageId)
    {
        $language = $this->languageMstRepository->getById($languageId);
        
        if (!$language) {
            throw new NotFoundException('Language not found');
        }
        
        $translations = $this->translationMstRepository->getByLanguageId($languageId);
        
        return TranslationMstResource::collection($translations);
    }
    
    /**
     * Get translations by original ID
     * 
     * @param int $originalId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws NotFoundException
     */
    public function getByOriginalId(int $originalId)
    {
        $original = $this->originalTranslatorMstRepository->getById($originalId);
        
        if (!$original) {
            throw new NotFoundException('Original translator not found');
        }
        
        $translations = $this->translationMstRepository->getByOriginalId($originalId);
        
        return TranslationMstResource::collection($translations);
    }
    
    /**
     * Store new translation
     * 
     * @param array $payload
     * @return TranslationMstResource
     * @throws NotFoundException
     */
    public function store(array $payload)
    {
        // Validate foreign keys
        $language = $this->languageMstRepository->getById($payload['language_id']);
        if (!$language) {
            throw new NotFoundException('Language not found');
        }
        
        $original = $this->originalTranslatorMstRepository->getById($payload['original_id']);
        if (!$original) {
            throw new NotFoundException('Original translator not found');
        }
        
        $translation = $this->translationMstRepository->store($payload);
        
        return new TranslationMstResource($translation);
    }
    
    /**
     * Update translation
     * 
     * @param array $payload
     * @param int $id
     * @return TranslationMstResource
     * @throws NotFoundException
     */
    public function update(array $payload, int $id)
    {
        // Check if translation exists
        $existingTranslation = $this->translationMstRepository->getById($id);
        if (!$existingTranslation) {
            throw new NotFoundException('Translation not found');
        }
        
        // Validate foreign keys if provided
        if (isset($payload['language_id'])) {
            $language = $this->languageMstRepository->getById($payload['language_id']);
            if (!$language) {
                throw new NotFoundException('Language not found');
            }
        }
        
        if (isset($payload['original_id'])) {
            $original = $this->originalTranslatorMstRepository->getById($payload['original_id']);
            if (!$original) {
                throw new NotFoundException('Original translator not found');
            }
        }
        
        $translation = $this->translationMstRepository->update($payload, $id);
        
        return new TranslationMstResource($translation);
    }
    
    /**
     * Delete translation
     * 
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function delete(int $id)
    {
        // Check if translation exists
        $existingTranslation = $this->translationMstRepository->getById($id);
        if (!$existingTranslation) {
            throw new NotFoundException('Translation not found');
        }
        
        $deleted = $this->translationMstRepository->delete($id);
        
        if (!$deleted) {
            throw new NotFoundException('Failed to delete translation');
        }
        
        return true;
    }
}