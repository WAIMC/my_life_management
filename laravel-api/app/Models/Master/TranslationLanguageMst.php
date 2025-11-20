<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationLanguageMst extends Model
{
    protected $table = 'translation_language_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'translation_mst_id',
        'language_mst_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'translation_mst_id' => 'integer',
        'language_mst_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the translation that owns this relationship.
     *
     * @return BelongsTo
     */
    public function translation(): BelongsTo
    {
        return $this->belongsTo(TranslationMst::class, 'translation_mst_id');
    }

    /**
     * Get the language that owns this relationship.
     *
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageMst::class, 'language_mst_id');
    }
}
