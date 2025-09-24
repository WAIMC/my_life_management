<?php

namespace App\Models\History\Master;

use App\Models\Master\LanguageMst;
use App\Models\Master\OriginalTranslatorMst;
use App\Models\Master\TranslationMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationMstHist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translation_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'translation_mst_id',
        'language_id',
        'original_id',
        'value',
        'action',
        'author_id',
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the translation that owns the history record
     */
    public function translation(): BelongsTo
    {
        return $this->belongsTo(TranslationMst::class, 'translation_mst_id');
    }

    /**
     * Get the language that owns the history record
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageMst::class, 'language_id');
    }

    /**
     * Get the original translator that owns the history record
     */
    public function originalTranslator(): BelongsTo
    {
        return $this->belongsTo(OriginalTranslatorMst::class, 'original_id');
    }
}
