<?php

namespace App\Models\Master;

use App\Models\History\Master\TranslationMstHist;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TranslationMst extends Model
{
    use HasHistory;

    protected $table = 'translation_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'original_translator_mst_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'original_translator_mst_id' => 'integer',
        'value' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the original translator that owns the translation.
     *
     * @return BelongsTo
     */
    public function originalTranslator(): BelongsTo
    {
        return $this->belongsTo(OriginalTranslatorMst::class, 'original_translator_mst_id');
    }

    /**
     * Get the languages associated with the translation.
     *
     * @return BelongsToMany
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(
            LanguageMst::class,
            'translation_language_mst',
            'translation_mst_id',
            'language_mst_id'
        )->withTimestamps();
    }

    /**
     * Get the history records for the translation.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(TranslationMstHist::class, 'translation_mst_id');
    }
}
