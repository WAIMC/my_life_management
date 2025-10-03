<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Model;

class OriginalTranslatorMstHist extends Model
{
    protected $table = 'original_translator_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'original_translator_mst_id',
        '"table"',
        '"column"',
        'field_id',
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
        'original_translator_mst_id' => 'integer',
        '"table"' => 'string',
        '"column"' => 'string',
        'field_id' => 'integer',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
