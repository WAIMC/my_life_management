<?php

namespace App\Interfaces\Master;

interface TranslationMstInterface
{
    /**
     * Get translation list with conditions
     * 
     * @param array $payload
     * @return mixed
     */
    public function list(array $payload);
    
    /**
     * Get translation by ID
     * 
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);
    
    /**
     * Get translations by language ID
     * 
     * @param int $languageId
     * @return mixed
     */
    public function getByLanguageId(int $languageId);
    
    /**
     * Get translations by original ID
     * 
     * @param int $originalId
     * @return mixed
     */
    public function getByOriginalId(int $originalId);
    
    /**
     * Store new translation
     * 
     * @param array $payload
     * @return mixed
     */
    public function store(array $payload);
    
    /**
     * Update translation
     * 
     * @param array $payload
     * @param int $id
     * @return mixed
     */
    public function update(array $payload, int $id);
    
    /**
     * Delete translation
     * 
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}