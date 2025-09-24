<?php

namespace App\Models\Master;

use App\Models\History\Master\OriginalTranslatorMstHist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OriginalTranslatorMst extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'original_translator_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'table',
        'column',
        'field_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the translations for this original translator.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TranslationMst::class, 'original_id');
    }

    /**
     * Get the history records for this original translator.
     */
    public function history(): HasMany
    {
        return $this->hasMany(OriginalTranslatorMstHist::class, 'original_translator_mst_id');
    }
}
