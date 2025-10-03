<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class OriginalTranslatorMst extends Model
{
    protected $table = 'original_translator_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        '"table"',
        '"column"',
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
        '"table"' => 'string',
        '"column"' => 'string',
        'field_id' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
