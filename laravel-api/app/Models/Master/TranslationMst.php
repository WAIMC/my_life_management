<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TranslationMst extends Model
{
    protected $table = 'translation_mst';
    
    protected $fillable = [
        'language_id',
        'original_id',
        'value',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the language that owns the translation
     */
    public function language()
    {
        return $this->belongsTo(LanguageMst::class, 'language_id');
    }

    /**
     * Get the original translator that owns the translation
     */
    public function originalTranslator()
    {
        return $this->belongsTo(OriginalTranslatorMst::class, 'original_id');
    }
}