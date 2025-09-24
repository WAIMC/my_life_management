<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationMst extends Model
{
    protected $table = 'translation_mst';

    protected $fillable = [
        'language_id',
        'original_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the language that owns the translation
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageMst::class, 'language_id');
    }

    /**
     * Get the original translator that owns the translation
     */
    public function originalTranslator(): BelongsTo
    {
        return $this->belongsTo(OriginalTranslatorMst::class, 'original_id');
    }
}
