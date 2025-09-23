<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

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
    public function translations()
    {
        return $this->hasMany('App\Models\Master\TranslationMst', 'original_id');
    }

    /**
     * Get the history records for this original translator.
     */
    public function history()
    {
        return $this->hasMany('App\Models\History\Master\OriginalTranslatorMstHist', 'original_translator_mst_id');
    }
}
