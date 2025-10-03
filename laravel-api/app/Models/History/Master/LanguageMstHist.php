<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Model;

class LanguageMstHist extends Model
{
    protected $table = 'language_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'language_mst_id',
        'abbreviation',
        'name',
        'is_active',
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
        'language_mst_id' => 'integer',
        'abbreviation' => 'string',
        'name' => 'string',
        'is_active' => 'boolean',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
