<?php

namespace App\Repositories\Master;

use App\Models\Master\TranslationMst;
use App\Interfaces\Master\TranslationMstInterface;
use Illuminate\Database\Eloquent\Collection;

class TranslationMstRepository implements TranslationMstInterface
{
    protected $model;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new TranslationMst();
    }
    
    /**
     * Get translation list with conditions
     * 
     * @param array $payload
     * @return Collection
     */
    public function list(array $payload)
    {
        $query = $this->model->select('*');
        
        if (isset($payload['language_id'])) {
            $query->where('language_id', $payload['language_id']);
        }
        
        if (isset($payload['original_id'])) {
            $query->where('original_id', $payload['original_id']);
        }
        
        if (isset($payload['value'])) {
            $query->where('value', 'like', '%' . $payload['value'] . '%');
        }
        
        if (isset($payload['with_language']) && $payload['with_language']) {
            $query->with('language');
        }
        
        if (isset($payload['with_original']) && $payload['with_original']) {
            $query->with('originalTranslator');
        }
        
        return $query->orderBy('id')->get();
    }
    
    /**
     * Get translation by ID
     * 
     * @param int $id
     * @return TranslationMst|null
     */
    public function getById(int $id)
    {
        return $this->model->with(['language', 'originalTranslator'])->find($id);
    }
    
    /**
     * Get translations by language ID
     * 
     * @param int $languageId
     * @return Collection
     */
    public function getByLanguageId(int $languageId)
    {
        return $this->model->where('language_id', $languageId)
            ->with('originalTranslator')
            ->orderBy('id')
            ->get();
    }
    
    /**
     * Get translations by original ID
     * 
     * @param int $originalId
     * @return Collection
     */
    public function getByOriginalId(int $originalId)
    {
        return $this->model->where('original_id', $originalId)
            ->with('language')
            ->orderBy('id')
            ->get();
    }
    
    /**
     * Store new translation
     * 
     * @param array $payload
     * @return TranslationMst
     */
    public function store(array $payload)
    {
        $translation = new TranslationMst();
        
        if (isset($payload['language_id'])) {
            $translation->language_id = $payload['language_id'];
        }
        
        if (isset($payload['original_id'])) {
            $translation->original_id = $payload['original_id'];
        }
        
        if (isset($payload['value'])) {
            $translation->value = $payload['value'];
        }
        
        $translation->save();
        
        return $translation;
    }
    
    /**
     * Update translation
     * 
     * @param array $payload
     * @param int $id
     * @return TranslationMst|null
     */
    public function update(array $payload, int $id)
    {
        $translation = $this->model->find($id);
        
        if (!$translation) {
            return null;
        }
        
        if (isset($payload['language_id'])) {
            $translation->language_id = $payload['language_id'];
        }
        
        if (isset($payload['original_id'])) {
            $translation->original_id = $payload['original_id'];
        }
        
        if (isset($payload['value'])) {
            $translation->value = $payload['value'];
        }
        
        $translation->save();
        
        return $translation;
    }
    
    /**
     * Delete translation
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $translation = $this->model->find($id);
        
        if (!$translation) {
            return false;
        }
        
        return $translation->delete();
    }
}