<?php

namespace App\Models\Master;

use App\Models\History\Master\OriginalTranslatorMstHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OriginalTranslatorMst extends Model
{
    use HasSoftDelete, HasHistory;

    protected $table = 'original_translator_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'table',
        'column',
        'field_id',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'table' => 'string',
        'column' => 'string',
        'field_id' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the translations for the original translator.
     *
     * @return HasMany
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TranslationMst::class, 'original_translator_mst_id');
    }

    /**
     * Get the history records for the original translator.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(OriginalTranslatorMstHist::class, 'original_translator_mst_id');
    }
}
