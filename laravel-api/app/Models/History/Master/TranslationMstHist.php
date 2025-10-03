<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Model;

class TranslationMstHist extends Model
{
    protected $table = 'translation_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'translation_mst_id',
        'language_id',
        'original_id',
        'value',
        'action',
        'author_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'translation_mst_id' => 'integer',
        'language_id' => 'integer',
        'original_id' => 'integer',
        'value' => 'string',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'string',
    ];
}
