<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class TranslationMst extends Model
{
    protected $table = 'translation_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'language_id',
        'original_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'language_id' => 'integer',
        'original_id' => 'integer',
        'value' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
