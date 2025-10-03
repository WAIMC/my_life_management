<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

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
}
